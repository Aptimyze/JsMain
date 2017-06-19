<?php
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
$zipIt = 1;
if($zipIt && !$dont_zip_now && $dont_zip_more!=1)
{
        $dont_zip_more=1;
        ob_start("ob_gzhandler");
}
$root_path1=$_SERVER['DOCUMENT_ROOT'];
include_once($root_path1."/profile/connect.inc");
include_once($root_path1."/profile/mobile_detect.php");
include_once($root_path1."/profile/registration_functions.inc");
$db=connect_db();
$data_auth=authenticated($checksum,'y');

if(!$data_auth&&$sugar_incomplete!='Y'){
	header("Location: ".$SITE_URL."/profile/sugarcrm_registration/registration_page1.php?record_id=$record_id&secondary_source=$secondary_source");
	exit;
} 
$smarty->assign("record_id",$record_id);
$smarty->assign("from_sugar_exec",$from_sugar_exec);
$smarty->assign("YEAR_OF_BIRTH",$year);
$smarty->assign("MONTH_OF_BIRTH",$month);
$smarty->assign("DAY_OF_BIRTH",$day);
$smarty->assign("COUNTRY_RESIDENCE",$country_residence);

$sql="select jsprofileid_c,about_the_profile_c from sugarcrm.leads_cstm where id_c='$record_id'";
$res=mysql_query_decide($sql);
$row=mysql_fetch_array($res);
$username=$row['jsprofileid_c'];
if(!$page2asubmit)
	$smarty->assign("about_yourself",$row['about_the_profile_c']);
if($sugar_incomplete=='Y'){
	//get profile id of the profile
	if($username){
		$sql="select PROFILEID,GENDER,SOURCE from newjs.JPROFILE where USERNAME='$username'";
		$res=mysql_query_decide($sql);
		$row=mysql_fetch_array($res);
		$profileid=$row['PROFILEID'];
		$help_to_build_aboutme='Y';
		$cookies['PROFILEID']=$profileid;
                $cookies['USERNAME']=$username;
                $cookies['GENDER']=$row['GENDER'];
            $cookies['SUBSCRIPTION']='';
            $cookies['ACTIVATED']='N';
            $cookies['SOURCE']=$row['SOURCE'];
            $protect_obj=new protect;
            $protect_obj->setcookies($cookies);
            $checksum=md5($id)."i".$id;
            $checksum=$protect_obj->js_encrypt($checksum);
	}
}
$smarty->assign("checksum",$checksum);

$sql="SELECT MSTATUS,AGE,EMAIL,INCOME,CASTE,USERNAME FROM newjs.JPROFILE WHERE PROFILEID = '$profileid'";
$result=mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
$row=mysql_fetch_array($result);
$age=$row["AGE"];	
$income=$row["INCOME"];
$caste=$row["CASTE"];
$email=$row["EMAIL"];
$username=$row["USERNAME"];
$smarty->assign("username",$username);
if($page2asubmit)
{
	$smarty->assign("gender",$gender);
	$smarty->assign("EMAIL",$email);
	$smarty->assign("yourHeading",$yourHeading);
	$smarty->assign("TIEUP_SOURCE",$tieup_source);
	//$smarty->assign("TIEUP_SOURCE",$source);
	$smarty->assign("HITSOURCE",$hit_source);
	$smarty->assign("NEWIP",$newip);
	$smarty->assign("ADNETWORK",$adnetwork);
	$smarty->assign("ACCOUNT",$account);
	$smarty->assign("CAMPAIGN",$campaign);
	$smarty->assign("ADGROUP",$adgroup);
	$smarty->assign("KEYWORD",$keyword_tieup);
	$smarty->assign("MATCH",$match);
	$smarty->assign("LMD",$lmd);
	$smarty->assign("SHOWLOGIN",$showlogin);
	$smarty->assign("GROUPNAME",$groupname);
	$smarty->assign("groupname",$groupname);
	$smarty->assign("CURRENT_DATE",date('Y-n-j'));
	$smarty->assign("PROFILEID",$profileid);
	$smarty->assign("RELIGION",$religion);
	$smarty->assign("CASTE",$caste);

	$is_error=0;
	//yourinfo
	$length=strlen($about_yourself);
	if($length>=100)
	{
		$smarty->assign("profileComplete",1);
	}
	//yourinfo

	//name
/*	if(($fname_user && !ereg("^[a-zA-Z\.\, ]+$",$fname_user)) || ($lname_user && !ereg("^[a-zA-Z\.\, ]+$",$lname_user)))
	{
		$smarty->assign("usernameError",1);
		$page2_error=1;
	}
*/
	if($length<=99 || !$about_yourself)
	{
		$is_error++;
		$smarty->assign("yourinfoError",'1');
	}
	if($is_error==0)
	{
		$checksum1=$protect_obj->js_decrypt($checksum);
		$profileid=getProfileidFromChecksum($checksum1);
		if($profileid)
		{
			if($from_sugar_exec)
                                $process="register_lead_button";
                        else
                                $process="auto_registration";
			$about_yourself=mysql_real_escape_string(stripslashes($about_yourself));
			update_about_yourself($about_yourself,$profileid,$db,$record_id,'sugarcrm.leads','sugarcrm.leads_cstm',$process);

			// Under Screening Mailer attached to the first page
			$msg =$smarty->fetch('Under_Screening.html');
	//		send_email($email,$msg,"Welcome to Jeevansathi.com","register@jeevansathi.com","","","","","","Y");
			
			if($gender=='F')
			include_once("registration_page2.inc");	
/*			{
				include_once($_SERVER['DOCUMENT_ROOT']."/classes/Jpartner.class.php");
include_once(JsConstants::$docRoot."/commonFiles/jpartner_include.inc");
include_once(JsConstants::$docRoot."/commonFiles/dropdowns.php");
		//		$Min_Age=($row['AGE']>29)?$row['AGE']-2:(($row['AGE']>26)?$row['AGE']-1:(($row['AGE']>22)?$row['AGE']:21));
		//		$Max_Age=($row['AGE']>33)?$row['AGE']+15:(($row['AGE']==33)?47:(($row['AGE']==32)?44:(($row['AGE']==31)?42:$row['AGE']+10)));
				if($income)
				{
					$new_partner_income=get_income_sortby_new($income,'','F');
					$new_partner_income=explode(",",$new_partner_income);
					$new_partner_income=implode("','",$new_partner_income);
					$DPP['Income']="'$new_partner_income'";
				}

				$sql = "SELECT DISTINCT REL_CASTE FROM newjs.CASTE_COMMUNITY WHERE PARENT_CASTE = '$caste'";
				$res = mysql_query_decide($sql) or logError("error",$sql);

				if(mysql_num_rows($res)<1)
				{
					$def="'$religion'";
				}
				else
				{       $abc="";
					while($rowed = mysql_fetch_array($res))
					{
						$abc.=$rowed[REL_CASTE].",";
					}
					$abc=rtrim($abc,",");
					$sql1="SELECT DISTINCT PARENT FROM newjs.CASTE WHERE VALUE IN ($abc,$caste)";
					$res1 = mysql_query_decide($sql1) or logError("error",$sql1);
					$def="'";
					while($row1 = mysql_fetch_assoc($res1))
					{
						$def.=$row1[PARENT]."','";
					}
					$def=rtrim(rtrim($def,"'"),",");
				}
				$partner_religion_str=$def;					
				$jpartnerObj=new Jpartner;
				$mysqlObj=new Mysql;
				if(!$myDb)
				{
					$myDbName=getProfileDatabaseConnectionName($profileid,'',$mysqlObj);
					$myDb=$mysqlObj->connect("$myDbName");
				}
				$jpartnerObj->setPartnerDetails($profileid,$myDb,$mysqlObj);
				$jpartnerObj->setPROFILEID($profileid);
				if($row['MSTATUS']=='N')
				{
					$jpartnerObj->setPARTNER_MSTATUS("'N'");
					$age/=4;
				}
				$jpartnerObj->setPARTNER_INCOME($DPP['Income']);
				$jpartnerObj->setPARTNER_RELIGION($partner_religion_str);
				$jpartnerObj->updatePartnerDetails($myDb,$mysqlObj);
				$age_filter=$mstatus_filter=$religion_filter=$country_filter=$mtongue_filter=$caste_filter=$city_filter=$income_filter='N';	
				if($age<=8.00)
					$age_filter=$mstatus_filter=$religion_filter=$income_filter='Y';
				else if($age>10.00)
					$income_filter='Y';
				else
					$mstatus_filter=$income_filter='Y';
				$sql="INSERT ignore INTO newjs.FILTERS(PROFILEID,AGE,MSTATUS,RELIGION,COUNTRY_RES,MTONGUE,CASTE,CITY_RES,INCOME) VALUES ('$profileid', '$age_filter', '$mstatus_filter', '$religion_filter', '$country_filter','$mtongue_filter','$caste_filter','$city_filter','$income_filter')";
		                mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
}*/
			$phone=explode('-',$phone);
			$phone=$phone[2];

			/* Scenarios checked for IVR call: 1. junk number exist (no ivr call)
							  2. Duplicate Exist (no ivr call)
							  3. ivr call (if neither junk nor duplicate)
			*/
			include_once($_SERVER['DOCUMENT_ROOT']."/ivr/jsPhoneVerify.php");
			if($mobile){
				$ivr_phone 	=$mobile;
				$phoneType	='M';
				$ivr_std 	='';
			}
			else if($phone){
				$ivr_phone 	=$phone;
				$phoneType	='L';
				$ivr_std 	=trim($state_code);
				if($ivr_std)
					$ivr_phone	=$ivr_std."-".$phone;
			}
			if($ivr_phone){
		    		$chk_junk =chkJunkNumberList($ivr_phone,$phoneType);
				if($chk_junk)
					phoneUpdateProcess($profileid,'',$phoneType,'J');
			}
			/* SMS Code for sending sms to users */
			
			//include_once "$root_path1/profile/InstantSMS.php";
			// $sms = new InstantSMS("REGISTER_CONFIRM", $profileid);
			 //$sms->send();
    
			/* Ends Here of SMS code */

			//include 3rd page.
	header("Location: $SITE_URL/register/page3?record_id=$record_id");
			die;
		}
		else
		{
			//else mail
			//$http_msg = "User Agent : $_SERVER['HTTP_USER_AGENT']\n #Referer : $_SERVER['HTTP_REFERER'] \n #Self : ".$_SERVER['PHP_SELF']."\n #Uri : ".$_SERVER['REQUEST_URI']."\n";
			//$http_msg .= implode(",",$_POST);
			$http_msg=print_r($_SERVER,true);
			//mail('lavesh.rawat@jeevansathi.com,lavesh.rawat@gmail.com','profileid blank 2',$http_msg);
		}
			
	}
	else
	{
		//print_r($smarty->_tpl_vars);
               // $smarty->assign("fname_user",$fname_user);
               // $smarty->assign("lname_user",$lname_user);
		$about_yourself=htmlspecialchars(stripslashes($about_yourself),ENT_QUOTES);
		$smarty->assign("about_yourself",$about_yourself);
		$smarty->assign("DRINK",$drink);
		$smarty->assign("SMOKE",$smoke);
		$smarty->assign("checksum",$checksum);
		$smarty->assign("record_id",$record_id);
		$smarty->assign("from_sugar_exec",$from_sugar_exec);
	}
}
if(!$is_error or $sugar_incomplete=='Y')
{
	if(!$profileid)
	{
		//$http_msg = "User Agent : $_SERVER['HTTP_USER_AGENT']\n #Referer : $_SERVER['HTTP_REFERER'] \n #Self : ".$_SERVER['PHP_SELF']."\n #Uri : ".$_SERVER['REQUEST_URI']."\n";
		//$http_msg .= implode(",",$_POST);
		$http_msg=print_r($_SERVER,true);
		//mail('lavesh.rawat@jeevansathi.com,lavesh.rawat@gmail.com','profileid blank-----source1',$http_msg);
	}
	$checksum=md5($profileid)."i".$profileid;
	$checksum=$protect_obj->js_encrypt($checksum);
	$smarty->assign("checksum",$checksum);
}
if($help_to_build_aboutme){
	if($gender=='M')
		include_once("registration_page2b.php");
	else
		include_once("registration_page2c.php");
}

$smarty->assign("gender",$gender);
$smarty->assign('p_percent',profile_percent_new($profileid));
if(!$record_id){
	$smarty->display("registration_pg2.htm");
}else if(!$help_to_build_aboutme)
	$smarty->display("sugarcrm_registration/sugarcrm_registration_pg2a.htm");
// flush the buffer
if($zipIt && !$dont_zip_now)
ob_end_flush();
?>
