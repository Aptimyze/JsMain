<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

//Function defined to track the changes made to astro details if the user changes his DOB or POB or TOB
//Funtion defined to update the changes in JPROFILE
//Function defined to send corresponding mail to the user
//@ANAND

include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/authentication.class.php");
include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/arrays.php");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/functions_edit_profile.php");
$symfonyFilePath=realpath($_SERVER['DOCUMENT_ROOT']."/../");
include_once($symfonyFilePath."/lib/model/lib/Flag.class.php");
include_once($symfonyFilePath."/lib/model/lib/FieldMapLib.class.php");
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");

function track_astro_details($profileid,$db,$mysqlObj="")
{
	if (!$mysqlObj)
		$mysqlObj = new Mysql;
	$statement1 = "INSERT INTO MIS.TRACK_ASTRO_DETAILS(ID,PROFILEID,OLD_RASHI,OLD_MANGLIK,OLD_NAKSHATRA,OLD_SUNSIGN,CHANGE_DATE) SELECT '',PROFILEID,RASHI,MANGLIK,NAKSHATRA,SUNSIGN,NOW() FROM newjs.JPROFILE where PROFILEID='$profileid'";
  	$mysqlObj->executeQuery($statement1,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$statement1,"ShowErrTemplate");
}

function update_astro_details($profileid,$astrodata="",$horoscope="",$db,$mysqlObj="")
{
	if($astrodata)
	{
		if (!$mysqlObj)
			$mysqlObj = new Mysql;
		$new_manglik = $astrodata['MANGLIK'];
		$new_moon_sign = $astrodata['MOON_SIGN'];
		$new_nakshatra = $astrodata['NAKSHATRA'];
		$new_sunsign = $astrodata['SUN_SIGN'];
		$city = addslashes(stripslashes($astrodata['CITY_BIRTH']));
		$b_time = $astrodata['BTIME'];
		$country = $astrodata['COUNTRY_BIRTH_EXIST'];
		$dup_flag_value=get_from_duplication_check_fields($profileid);
		if($dup_flag_value){
			if($dup_flag_value[TYPE]=='NEW')
				$to_not_update_dup=true;
			else
				$dup_flag_value=$dup_flag_value[FIELDS_TO_BE_CHECKED];
		}	
		if($new_manglik)
			$updateArr[]="MANGLIK = '$new_manglik'";
		if($new_moon_sign)
			$updateArr[]="RASHI = '$new_moon_sign'";
		if($new_nakshatra)	
			$updateArr[]="NAKSHATRA = '$new_nakshatra'";
		if($new_sunsign)	
			$updateArr[]="SUNSIGN = '$new_sunsign'";
		if($city){
			$updateArr[]="CITY_BIRTH = '$city'";
			$dup_flag_value=Flag::setFlag('city_birth',$dup_flag_value,'duplicationFieldsVal');
			$dup_change=true;
		}
		if($b_time){
			$updateArr[]="BTIME = '$b_time'";
			$dup_flag_value=Flag::setFlag('btime',$dup_flag_value,'duplicationFieldsVal');
			$dup_change=true;
		}
		if($country)
			$updateArr[]="COUNTRY_BIRTH = '$country'";
		if($horoscope)
			$updateArr[]="SHOW_HOROSCOPE = 'Y'";

		if($updateArr)
		{
			//$updateStr = implode(",",$updateArr);
                        foreach ($updateArr as $val){
                          $arr = explode('=',$val);
                          $sampleString =  trim($arr[1],' ');
                          $arrFields[trim($arr[0])] = trim($sampleString,"'");
                        }
                        $objUpdate = JProfileUpdateLib::getInstance();
			//$statement = "update newjs.JPROFILE SET ".$updateStr." where PROFILEID = '$profileid'";
			//$mysqlObj->executeQuery($statement,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$statement,"ShowErrTemplate");
                        $objUpdate->editJPROFILE($arrFields,$profileid,"PROFILEID");
			if(!$to_not_update_dup && $dup_flag_value && $dup_change){
				$sql="INSERT IGNORE INTO duplicates.DUPLICATE_CHECKS_FIELDS SET PROFILEID='$profileid',TYPE='edit',FIELDS_TO_BE_CHECKED='$dup_flag_value'";
				$mysqlObj->executeQuery($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$statement,"ShowErrTemplate");	
			}
		}
	}
}

function mail_to_user($profileid,$email,$username,$astrodata="",$type_mail,$db,$mysqlObj="")
{
	global $NAKSHATRA_DROP,$MANGLIK_LABEL,$RASHI_DROP,$SUNSIGN_DROP,$SITE_URL;
	
	if($astrodata)
	{
		$new_manglik = $astrodata['MANGLIK'];
		$new_moon_sign = $astrodata['MOON_SIGN'];
		$new_nakshatra = $astrodata['NAKSHATRA'];
		$new_sunsign = $astrodata['SUN_SIGN'];

		if (!$mysqlObj)
			$mysqlObj = new Mysql;
		$statement = "select OLD_RASHI, OLD_MANGLIK, OLD_NAKSHATRA, OLD_SUNSIGN from MIS.TRACK_ASTRO_DETAILS where PROFILEID = '".$profileid."' ORDER BY CHANGE_DATE DESC LIMIT 1";
		$result = $mysqlObj->executeQuery($statement,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$statement,"ShowErrTemplate");
		$row = $mysqlObj->fetchArray($result);

		if ($row['OLD_RASHI']>=1 && $row['OLD_RASHI']<=count($RASHI_DROP))
			$row['OLD_RASHI']=$RASHI_DROP[$row['OLD_RASHI']];
		elseif(trim($row['OLD_RASHI'])=='')
			$row['OLD_RASHI']=' - ';
			
		if(trim($row['OLD_NAKSHATRA'])=='')
			$row['OLD_NAKSHATRA']=' - ';

		if ($row['OLD_MANGLIK']=='D' || $row['OLD_MANGLIK']=='A' || $row['OLD_MANGLIK']=='N' || $row['OLD_MANGLIK']=='M')
			$row['OLD_MANGLIK']=$MANGLIK_LABEL[$row['OLD_MANGLIK']];
		elseif(trim($row['OLD_MANGLIK'])=='')
			$row['OLD_MANGLIK']=' - ';

		if ($row['OLD_SUNSIGN']>=1 && $row['OLD_SUNSIGN']<=count($SUNSIGN_DROP))
                        $row['OLD_SUNSIGN']=$SUNSIGN_DROP[$row['OLD_SUNSIGN']];
                elseif(trim($row['OLD_SUNSIGN'])=='' || trim($row['OLD_SUNSIGN'])==0)
                        $row['OLD_SUNSIGN']=' - ';

		$flag=0;
		$to = $email;
		$subject = "Your astro details updated";
		$from="info@jeevansathi.com";
		
		$protect_obj=new protect;
		$profilechecksum=md5($profileid)."i".$profileid;
		$echecksum=$protect_obj->js_encrypt($profilechecksum,$to);

		$message = "
		<html>
		<body>
		<p>Dear ".$username.",</p>";

		if ($type_mail==0)
			$message = $message."<p>Since you have changed your birth details, we have updated your astro details.</p>";
		else
			$message = $message."<p>We have updated your astrological details.</p>";
		
		$message = $message."<table border='1'>
		<tr align='center'>
		<td><b>Astro Details</b></td>
		<td><b>Old Value</b></td>
		<td><b>New Value</b></td>
		</tr>";
		if ($new_sunsign)
		{
			$flag=1;
			$message = $message."<tr align='center'>
						<td><b>Sun Sign</b></td>
						<td>".$row['OLD_SUNSIGN']."</td>
						<td>".$SUNSIGN_DROP[$new_sunsign]."</td>
						</tr>";
		}
		if ($new_moon_sign)
		{
			$flag=1;
			$message = $message."<tr align='center'>
						<td><b>Moon Sign</b></td>
						<td>".$row['OLD_RASHI']."</td>
						<td>".$RASHI_DROP[$new_moon_sign]."</td>
						</tr>";
		}
		if ($new_nakshatra)
		{
			$flag=1;
			$message = $message."<tr align='center'>
						<td><b>Nakshatra</b></td>
						<td>".$row['OLD_NAKSHATRA']."</td>
						<td>".$new_nakshatra."</td>
						</tr>";
		}
		if ($new_manglik)
		{
			$flag=1;
			$message = $message."<tr align='center'>
						<td><b>Manglik Status</b></td>
						<td>".$row['OLD_MANGLIK']."</td>
						<td>".$MANGLIK_LABEL[$new_manglik]."</td>
						</tr>";
		}
		$message = $message."</table>
		<p>You can view these details on <a href=".$SITE_URL."/profile/viewprofile.php?checksum=$profilechecksum&echecksum=$echecksum&profilechecksum=$profilechecksum&enable_auto_loggedin=1' target=\"_blank\">My Profile</a> page after you log into your account. You can also update these details by clicking on Edit link on Religion and Ethnicity and Astro Details sections of your profile.</p>
		<p>We wish you success with your search.</p>
		<p>Warm Regards,</p>
		<p>Jeevansathi.com Team</p>
		</body>
		</html>";
		if ($flag==1)
			send_email($to,$message,$subject,$from,"","","","","","",1,"");
			//echo $message;
	}
}
?>
