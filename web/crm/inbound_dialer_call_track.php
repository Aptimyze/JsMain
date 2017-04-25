<?php
include_once("connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
//$db = connect_db();
$db = connect_crmSlave();

$general_message_filename_val="general_info_1";
$profile_specific_filename_val="transfer_agent";
$call_forward_to_center_val=0;

$abusive_val='yes';
$phone=preg_replace("/[a-zA-Z!(\' ')@#$+^&*-]/", "",$phone);
$phone=substr($phone,-10);
$phoneNumeric =is_numeric($phone);
if(!$phoneNumeric)
	$phone ='';

if($phone){
	$abusive_val=getAbusiveStatus($db,$phone);
}

$call_divert_to_online_sales_team_val='';
$paidCampaign ='no';
$call_divert_to_online_sales_team_val=getCallDeiverStatus($db);
$multiple=0;
$no_of_profiles=0;
$present_in_db_val='';
$online_profile_val='';
$fto_state ='';

$default_str="call_forward_to_center=".$call_forward_to_center_val.",abusive=".$abusive_val.",call_divert_to_online_sales_team=".$call_divert_to_online_sales_team_val.",present_in_db=no,online_profile=yes,general_message_filename=".$general_message_filename_val.",profile_specific_filename=".$profile_specific_filename_val.",fto_state=".$fto_state.",paid_campaign=".$paidCampaign;

if($phone){
	$res_db =getProfileDetails($db,$phone,'',$default_str,'JPROFILE');
	$no_of_profiles=mysql_num_rows($res_db);
	if(!$no_of_profiles){
		$res_db1 =getProfileDetails($db,$phone,'',$default_str,'JPROFILE_CONTACT');
                while($row_on1 = mysql_fetch_array($res_db1)){
                        $profileidArr[] =$row_on1['PROFILEID'];
		}
		if(count($profileidArr)>0){
			$profileidStr =implode(",",$profileidArr);
			$res_db =getProfileDetails($db,'',$profileidStr,$default_str);
			$no_of_profiles=mysql_num_rows($res_db);		
		}
	}
}
else{
	$no_of_profiles=0;
}

if(!$no_of_profiles)
{
	$present_in_db_val='no';
	$online_profile_val='yes';
        $str = "call_forward_to_center=".$call_forward_to_center_val.",abusive=".$abusive_val.",call_divert_to_online_sales_team=".$call_divert_to_online_sales_team_val.",present_in_db=".$present_in_db_val.",online_profile=".$online_profile_val.",general_message_filename=".$general_message_filename_val.",profile_specific_filename=".$profile_specific_filename_val.",fto_state=".$fto_state.",paid_campaign=".$paidCampaign;
        echo $str;
        exit;
}
else
{
	$present_in_db_val='yes';
	$src='';
	if($no_of_profiles>1)
	{
		$online_profile_val='yes';
		while($row_on = mysql_fetch_array($res_db))
		{
			$subscription =$row_on["SUBSCRIPTION"];
			$profileid =$row_on['PROFILEID'];
			if((strstr($subscription,"F")!="")||(strstr($subscription,"D")!="")){
				$paidCampaign ='yes';
				break;
			}
			/*if($row_on["ACTIVATED"]=='Y')
			{
				$str = "call_forward_to_center=".$call_forward_to_center_val.",abusive=".$abusive_val.",call_divert_to_online_sales_team=".$call_divert_to_online_sales_team_val.",present_in_db=".$present_in_db_val.",online_profile=".$online_profile_val.",general_message_filename=".$general_message_filename_val.",profile_specific_filename=".$profile_specific_filename_val.",fto_state=".$fto_state.",paid_campaign=".$paidCampaign;
				echo $str;	
				exit;
			}*/
		}
	}
	else{
		$row_get=mysql_fetch_assoc($res_db);
		$subscription =$row_get['SUBSCRIPTION'];
		if((strstr($subscription,"F")!="")||(strstr($subscription,"D")!=""))
			$paidCampaign ='yes';
	        $online_profile_val='yes';
		$profileid =$row_get['PROFILEID'];
	}
	cachProfileInfo($profileid,$phone);
	$str = "call_forward_to_center=".$call_forward_to_center_val.",abusive=".$abusive_val.",call_divert_to_online_sales_team=".$call_divert_to_online_sales_team_val.",present_in_db=".$present_in_db_val.",online_profile=".$online_profile_val.",general_message_filename=".$general_message_filename_val.",profile_specific_filename=".$profile_specific_filename_val.",fto_state=".$fto_state.",paid_campaign=".$paidCampaign;
	echo $str;
	exit;
}

function getProfileDetails($db,$phone='',$profileidStr='',$default_str,$table='')
{
	if($phone && $table=='JPROFILE'){
	        $sql_db = "SELECT PROFILEID,ACTIVATED,SUBSCRIPTION FROM newjs.JPROFILE WHERE `PHONE_WITH_STD`='$phone' OR `PHONE_MOB` IN('0$phone','+91$phone','91$phone','$phone') AND ACTIVATED!='D'";
	}
        elseif($phone && $table=='JPROFILE_CONTACT'){
                $sql_db = "SELECT PROFILEID FROM newjs.JPROFILE_CONTACT WHERE `ALT_MOBILE` IN('0$phone','+91$phone','91$phone','$phone')";
        }
	elseif($profileidStr){
		$sql_db = "SELECT PROFILEID,ACTIVATED,SUBSCRIPTION FROM newjs.JPROFILE WHERE PROFILEID IN($profileidStr) AND ACTIVATED!='D'";
	}	
        $res_db = mysql_query($sql_db,$db) or die($default_str);
	return $res_db;
}
function getAbusiveStatus($db,$phone)
{
        $sql_ab ="SELECT PHONE_WITH_STD FROM newjs.ABUSIVE_PHONE WHERE PHONE_WITH_STD='$phone'";
        $res_ab = mysql_query($sql_ab,$db);
        if($row_ab = mysql_fetch_array($res_ab))
                $abusive_val = 'yes';
        else
                $abusive_val='no';
	return $abusive_val;
}
function getCallDeiverStatus($db)
{
	$sql_cd ="SELECT STATUS FROM newjs.CALL_DIVERT";
	$res_cd = mysql_query($sql_cd,$db);
	if($row_cd = mysql_fetch_array($res_cd))
	        $call_divert_to_online_sales_team_val=$row_cd["STATUS"];
	else
	        $call_divert_to_online_sales_team_val='no';
	return $call_divert_to_online_sales_team_val;
}
function cachProfileInfo($profileid,$phone){
        $JsMemcacheObj =JsMemcache::getInstance();
        $key ='sales_campaign_'.$phone;
        $profileid = $JsMemcacheObj->set($key,$profileid,86400);
	//$profileid1 = $JsMemcacheObj->get($key);
}

?>
