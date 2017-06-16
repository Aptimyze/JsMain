<?php
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
$zipIt = 1;
if($zipIt && !$dont_zip_now && $dont_zip_more!=1)
{
        $dont_zip_more=1;
        ob_start("ob_gzhandler");
}

$path=$_SERVER['DOCUMENT_ROOT'];
include_once($path."/profile/connect.inc");
$db=connect_db();
$smarty->assign("checksum", $checksum);
$smarty->assign("PROFILEID",$profileid);
if(!$data_auth){
	$data_auth=authenticated($checksum,'y');
	if(!$data_auth){
		header("Location: ".$SITE_URL."/profile/sugarcrm_registration/registration_page1.php?record_id=$record_id");
		exit;
	}                       
}
$smarty->assign("record_id",$record_id);
if($submit_type1!=""){
	if($submit_type1=='skip'){
	$sql="SELECT T_BROTHER,T_SISTER,PARENT_CITY_SAME,ENTRY_DT from newjs.JPROFILE where PROFILEID='$profileid'";
	$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_t,"ShowErrTemplate");
	if($row=mysql_fetch_assoc($res)){
		$brothers=$row['T_BROTHER'];
		$sisters=$row['T_SISTER'];
		$parent_city_same=$row['PARENT_CITY_SAME'];
		$entry_dt=substr($row['ENTRY_DT'],0,10);
	}
	//if any of number of brothers, sisters or living with parent is filled by the user, he should be directly logged in
		if(($brothers || $sisters || $parent_city_same) && $entry_dt!=$curDate){
			header("Location: $SITE_URL/profile/mainmenu.php");
			exit;
		}
		else
		include_once("$path/profile/registration_page3.php");
die;
	}
	else{
		include_once("$path/profile/mem_comparison.php");
	 	die;
	}
}
$smarty->display("sugarcrm_registration/sugarcrm_registration_pg7.htm");
if($zipIt && !$dont_zip_now)
  ob_end_flush();
?>

