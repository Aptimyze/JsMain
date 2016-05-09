<?php
$start_tm=microtime(true);
//to zip the file before sending it
$zipIt = 0;
$error_code = 0;
$error_message = "";
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
$zipIt = 1;
if($zipIt && !$dont_zip_now && $dont_zip_more!=1)
{
        $dont_zip_more=1;
        ob_start("ob_gzhandler");
}

include_once("connect.inc");
include_once("registration_functions.inc");
include_once("arrays.php");
include_once(JsConstants::$docRoot."/commonFiles/flag.php");
include_once("functions.inc"); //included because functions for checking messenger IDs are defined here
$db=connect_db();
$now = date("Y-m-d G:i:s");

$record_id = mysql_real_escape_string($_POST['record_id']);

/* Changes Done for the SEM Track #20 */

if($sem)
{
	 $checksum_1=$protect_obj->js_decrypt($checksum);
         $profileid=getProfileidFromChecksum($checksum_1);

	 $authe = new protect;
	 $sql="select PROFILEID,SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,USERNAME,GENDER,COUNTRY_RES,ACTIVATED,SOURCE,LAST_LOGIN_DT,CASTE,MTONGUE,INCOME,RELIGION,AGE,HEIGHT,HAVEPHOTO,INCOMPLETE,MOD_DT from newjs.JPROFILE where PROFILEID='$profileid'";
	 $result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
	 $myrow=mysql_fetch_array($result);
	 
	 $data["PROFILEID"]=$myrow["PROFILEID"];
	 $data["USERNAME"]=$myrow["USERNAME"];
	 $data["GENDER"]=$myrow["GENDER"];
	 $data["ACTIVATED"]=$myrow["ACTIVATED"];
	 $data["SUBSCRIPTION"]=$myrow["SUBSCRIPTION"];
	 $data["SOURCE"]=$myrow["SOURCE"];
	 
	 $authe->setcookies($myrow);

	 $religion=$myrow["RELIGION"];
	 $profileid=$myrow["PROFILEID"];
	 $country_residence=$myrow["COUNTRY_RES"];
	 $caste=$myrow["CASTE"];
	 $gender=$data["GENDER"];
	 $username=$data["USERNAME"];
	 $tieup_source=$data["SOURCE"];
	 	 
	 $smarty->assign('sem',$sem);
	 $smarty->assign('PROFILEID',$profileid);
	 $smarty->assign('gender',$gender);
	 $smarty->assign('checksum',$checksum);
	 $smarty->assign('RELIGION',$religion);
	 $smarty->assign('CASTE',$caste);
	 $smarty->assign('GROUPNAME',$groupname);
	 $smarty->assign('COUNTRY_RESI',$country_residence);
	 $smarty->assign('TIEUP_SOURCE',$tieup_source);
	 $smarty->assign("CURRENT_DATE",date('Y-n-j'));
}
else
{
	if(!$sem)
	{
		if(!$data_auth){
			$data_auth=authenticated($checksum,'y');
			if(!$data_auth){
			       header("Location: ".$SITE_URL."/profile/registration_page1.php");
				exit;   
			}                                                                                       
		}
	}
}
$smarty->assign("data_auth",$data_auth);
$data=authenticated();
//$IMG_URL = $SITE_URL."/profile/images/registration_revamp_new";
//$smarty->assign("IMG_URL",$IMG_URL);
if($data['PROFILEID'])
{
	$profileid=$data['PROFILEID'];	
}
if($profileid)
{
	$sql="select SCREENING,SOURCE,MTONGUE from newjs.JPROFILE where  activatedKey=1 and PROFILEID='$profileid'";
	$res=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
	$row=mysql_fetch_array($res);
	$current_screening_flag = $row['SCREENING'];
	$tieup_source=$row['SOURCE'];
	$mtongue=$row['MTONGUE'];
}

if($skip_to_next_page5)
{
	include("registration_page5.php");
	die;
}

if($Submit_pg4 || $Submit_pg4_x || $Submit_pg4_y)
{
    global $error_code;
    global $error_message;
	if($messenger_id)
	{
        list($error_code, $error_message) = check_messenger_id($messenger_id);
        if ($error_code == '0') {
            list($error_code, $error_message) = is_messenger_channel_selected($messenger_id, $messenger_channel);
        }
	}

	if($error_code != '0')
	{
		$smarty->assign('error_code', $error_code);
        $smarty->assign('error_msg', $error_message);
		$smarty->assign("IS_FTO_LIVE",FTOLiveFlags::IS_FTO_LIVE);
	}
	else
	{
        if ($error_code == '0')
        {
            if (($messenger_id == "e.g. raj1983, vicky1980 ") || ($messenger_id == "")) {
                $messenger_id = "";
                $messenger_channel = "";
            }
        }
		if($about_education)
		{
			 $jprofile_update[] = "EDUCATION='".addslashes(stripslashes(($about_education)))."'";
			$current_screening_flag = removeFlag("EDUCATION",$current_screening_flag);
		}
		if($WSTATUS)
		{
			$work_status=$WSTATUS;
			$jprofile_update[] = "WORK_STATUS='".$work_status."'";
		}
		if($about_work)
		{
			$jprofile_update[] = "JOB_INFO='".addslashes(stripslashes(($about_work)))."'";
			$current_screening_flag = removeFlag("JOB_INFO",$current_screening_flag);
		}
		if($BGROUP)
		{
			$jprofile_update[] = "BLOOD_GROUP='".$BGROUP."'";
		}
		if($hiv)
		{
			$jprofile_update[] = "HIV='".$hiv."'";
		}
		if($handicapped)
		{
			$jprofile_update[] = "HANDICAPPED='".$handicapped."'";

      //Bugid 63520
      if ($handicapped !== '1' && $handicapped !== '2') {
        $nature_of_handicap = null;
      }
			$jprofile_update[] = "NATURE_HANDICAP='".$nature_of_handicap."'";
		}
		if($language_arr && is_string($language_arr))
		{
			$language_arr = @explode(",",$language_arr);
			$language_str = @implode(",",$language_arr);
			$language_str=trim($language_str,",");
			$sql = "INSERT IGNORE INTO newjs.JHOBBY(PROFILEID,HOBBY) VALUES ($profileid,\"$language_str\")";
			$res = mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		}
		elseif(count($language_arr) > 0 && is_array($language_arr))
		{
			$language_str = @implode(",",$language_arr);

			$sql = "INSERT IGNORE INTO newjs.JHOBBY(PROFILEID,HOBBY) VALUES ($profileid,\"$language_str\")";
			$res = mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		}

		if($messenger_id && ($messenger_channel || $showmessenger))
		{

			$jprofile_update[] = "MESSENGER_ID='".addslashes(stripslashes($messenger_id))."'";
			$jprofile_update[] = "MESSENGER_CHANNEL='".$messenger_channel."'";
			$jprofile_update[] = "SHOWMESSENGER='".$showmessenger."'";
			$current_screening_flag = removeFlag("MESSENGER",$current_screening_flag);
			
			if($messenger_id)
				$msgr=$messenger_id."@".$MESSENGER_CHANNEL[$messenger_channel];

			$sql_arch_check = "SELECT cai.NEW_VAL FROM newjs.CONTACT_ARCHIVE ca, newjs.CONTACT_ARCHIVE_INFO cai WHERE ca.PROFILEID='$profileid' AND ca.CHANGEID=cai.CHANGEID AND ca.FIELD = 'MESSENGER'";
			$res_arch_check = mysql_query_decide($sql_arch_check) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			$row_arch_check = mysql_fetch_array($res_arch_check);
			if(strtolower(trim($msgr)) != strtolower(trim($row_arch_check['NEW_VAL'])))
			{
				$sql_id_ph= "INSERT INTO newjs.CONTACT_ARCHIVE (PROFILEID,FIELD) VALUES($profileid,'MESSENGER')";
				$res_id_ph= mysql_query_decide($sql_id_ph) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");

				$changeid=mysql_insert_id_js();


				$sql_info_ph= "INSERT INTO newjs.CONTACT_ARCHIVE_INFO (CHANGEID,DATE,IPADD,NEW_VAL) VALUES($changeid,'$now','$ip','$msgr')";
				$res_info_ph= mysql_query_decide($sql_info_ph) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			}
		}
		if($contact_address)
		{
			$jprofile_update[] = "CONTACT='".addslashes(stripslashes(($contact_address)))."'";
			$jprofile_update[] = "SHOWADDRESS='".$showaddress."'";
			$current_screening_flag = removeFlag("CONTACT",$current_screening_flag);

			$sql_arch_check = "SELECT cai.NEW_VAL FROM newjs.CONTACT_ARCHIVE ca, newjs.CONTACT_ARCHIVE_INFO cai WHERE ca.PROFILEID='$profileid' AND ca.CHANGEID=cai.CHANGEID AND ca.FIELD = 'CONTACT'";
			$res_arch_check = mysql_query_decide($sql_arch_check) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			$row_arch_check = mysql_fetch_array($res_arch_check);
			if(strtolower(trim($contact_address)) != strtolower(trim($row_arch_check['NEW_VAL'])))
			{
				$sql_id= "INSERT INTO newjs.CONTACT_ARCHIVE (PROFILEID,FIELD) VALUES($profileid,'CONTACT')";
				$res_id= mysql_query_decide($sql_id) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_id,"ShowErrTemplate");

				$changeid=mysql_insert_id_js();
				$sql_info= "INSERT INTO newjs.CONTACT_ARCHIVE_INFO (CHANGEID,DATE,IPADD,NEW_VAL) VALUES($changeid,'$now','$ip','$contact_address')";
				$res_info= mysql_query_decide($sql_info) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql_info,"ShowErrTemplate");
			}
		}

		if(count($jprofile_update)>0)
		{
			if(strtolower($tieup_source)=='ofl_prof')
			{
				$current_screening_flag="1099511627775";
			}

			$jprofile_update[] = "MOD_DT='".$now."'";
			$jprofile_update[]="SCREENING=$current_screening_flag";
			$jprofile_update_str = @implode(", ",$jprofile_update);
			if($profileid)
			{
				$sql="update newjs.JPROFILE set  $jprofile_update_str where PROFILEID='$profileid' and activatedKey=1";
				 mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
			}
			
		}
		
		/* Tracking Query for the Reg Count */
		$sql = "UPDATE MIS.REG_COUNT SET PAGE4='Y' WHERE PROFILEID='$profileid'";
		mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
		/* Ends Here */


		include("registration_page5.php");
		die;
	}
}

$smarty->assign("WSTATUS",create_select_drop($WSTATUS,$WORK_STATUS));
$smarty->assign("BGROUP",create_select_drop($BGROUP,$BLOOD_GROUP));
$smarty->assign("CHALLENGED",create_select_drop($handicapped,$HANDICAPPED));
$smarty->assign("NATURE_HANDICAP",create_select_drop($nature_of_handicap,$NATURE_HANDICAP));
$smarty->assign("MESSENGER_CHANNEL",create_select_drop($messenger_channel,$MESSENGER_CHANNEL));
if(!$spok_lang)
	$spok_lang=array();
$sql="SELECT LABEL, VALUE, MTONGUE_VAL FROM newjs.LANGUAGES WHERE VISIBLE='Y' ORDER BY ALPHA_SORT";
$res=mysql_query_decide($sql);

while($myrow=mysql_fetch_array($res))
{
	$mtongue_arr = @explode(",",$myrow["MTONGUE_VAL"]);
	if(@in_array($mtongue,$mtongue_arr) && !$spoken_languages && $mtongue_arr[0]!="")
	{
		$selected="1";
                $language_v[]=$myrow["VALUE"];
//		$spoken_languages_str.= "'".$row['VALUE']."',";
	}	
	else
		$selected="";

	$language[]=array("LABEL" => $myrow["LABEL"],
			"VALUE" => $myrow["VALUE"],
			"SELECTED" => $selected);
	
}
$spoken_languages_str=substr($spoken_languages_str,-1);

if(count($language_v)>1)
	$language_vstr=implode($language_v,"','");
else
	$language_vstr="'".$language_v[0]."'";

$smarty->assign("LANGUAGE_str","'$language_vstr'");
$smarty->assign("LANGUAGE",$language);
$smarty->assign("about_education",stripslashes($about_education));
$smarty->assign("about_work",stripslashes($about_work));
$smarty->assign("messenger_id",stripslashes($messenger_id));
if($record_id && !$Submit_pg4) {
	$record_id=mysql_real_escape_string($record_id);
	$smarty->assign("RECORD_ID",$record_id);
    $sql_sugar1 = "select primary_address_street, primary_address_city, primary_address_state, primary_address_postalcode from sugarcrm.leads where id='$record_id'";
    $sql_sugar2 = "select p_o_box_no_c from sugarcrm.leads_cstm where id_c='$record_id'";
    $res_sugar1 = mysql_query_decide($sql_sugar1);
    $res_sugar2 = mysql_query_decide($sql_sugar2);
    $sugar_row1 = mysql_fetch_assoc($res_sugar1);
    $sugar_row2 = mysql_fetch_assoc($res_sugar2);
    if($sugar_row1)
    {
        $street=$sugar_row1['primary_address_street'];
        if($street)
            $contact_address = $contact_address . $street . "\n";
        $city=$sugar_row1['primary_address_city'];
        if($city)
            $contact_address = $contact_address . $city . "\n";
        $state=$sugar_row1['primary_address_state'];
        if($state)
            $contact_address = $contact_address . $state . "\n";
        $postalcode=$sugar_row1['primary_address_postalcode'];
        if($postalcode)
            $contact_address = $contact_address . $postalcode . "\n";
        //$contact_address = "$street . '<br />' . $city . '<br />' . $state . '<br />' . $postalcode . '<br />'";
    }
    if($sugar_row2)
    {
        $pobox=$sugar_row2['p_o_box_no_c'];
        if($pobox)
            $contact_address = $contact_address . $pobox . "\n";
    }
    $smarty->assign("contact_address",stripslashes($contact_address));
}
$smarty->assign("PROFILEID",$profileid);
		$smarty->assign("IS_FTO_LIVE",FTOLiveFlags::IS_FTO_LIVE);

		 if(!isset($_COOKIE["ISEARCH"]))
			             $smarty->assign('ISEARCH_COOKIE_NOTSET','1');
/* Tracking Contact Center, as per Mantis 4724 Starts here */
		$end_time=microtime(true)-$start_tm;
		$smarty->assign("TRACK_FOOT",BrijjTrackingHelper::getTailTrackJs($end_time,true,2,"http://track.99acres.com/images/zero.gif","JSREGPAGE4URL"));
		/* Ends Here */
$smarty->display("registration_pg4.htm");
function create_select_drop($value,$array)
{
	$str="";
	if(is_array($array))
	{
		foreach($array as $key=>$val)
		{
			$selected="";
			if($key==$value)
				$selected="SELECTED='SELECTED'";
			
			$str.="<option value='$key' $selected >$val</option>";
		}
	}
	return $str;
}
        // flush the buffer
        if($zipIt && !$dont_zip_now)
                ob_end_flush();
?>
