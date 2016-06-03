<?php
include("connect.inc");
include_once(JsConstants::$docRoot."/classes/authentication.class.php");
$SITE_URL =JsConstants::$siteUrl;

$id_arr=explode("i",$id);
$id_sel=$id_arr[1];
if(md5($id_arr[1])!=$id_arr[0]){
	die("invalid URL");
}

// slave connection	
$db=connect_db();

if($id)
{
	$todaysDt =date('Y-m-d H:i:s');
	$sql="SELECT ENTRY_DT,PROFILEID,EMAIL from incentive.PAYMENT_COLLECT where ID='$id_sel'";
	$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
	if($myrow=mysql_fetch_array($result)){
		$entryDt 	=$myrow['ENTRY_DT'];
		$profileid 	=$myrow['PROFILEID'];
		$email	 	=$myrow['EMAIL'];	
	}

	$timeDiff =strtotime($todaysDt)-strtotime($entryDt);
	if($extendedTime){ // this param appears for membership mailers of 1 month
		if(($timeDiff/(86400*$extendedTime))>1){
			$linkExpired =true;
		} else {
			$linkExpired =false;
		}
	} else {
		if(($timeDiff/86400)>1){
			$linkExpired =true;
		} else {
			$linkExpired =false;
		}
	}

	if(!$linkExpired){
		// auto link url generation	
		$protect_obj		=new protect;
		$profilechecksum	=md5($profileid)."i".$profileid;
		$echecksum		=$protect_obj->js_encrypt($profilechecksum,$email);
		$source 		="discount_link";
		$id_sel			=md5($id_sel)."i".$id_sel;
		$url 			="$SITE_URL//membership/jspc?CMGFRMMMMJS=1&checksum=$profilechecksum&profilechecksum=$profilechecksum&echecksum=$echecksum&enable_auto_loggedin=1&reqid=$id_sel&from_source=$source";
		header("Location: $url");
	}

	die('Your link has expired');
}

?>
