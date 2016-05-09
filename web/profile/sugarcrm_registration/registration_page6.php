<?php
mail("kumar.anand@jeevansathi.com,nitesh.sethi@jeevansathi.com","web/profile/sugarcrm_registration/registration_page6.php called","web/profile/sugarcrm_registration/registration_page6.php called");
die;
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
include_once($path."/profile/functions.inc");
$db=connect_db();
if(!$data_auth){
	$data_auth=authenticated($checksum,'y');
	if(!$data_auth){
		header("Location: ".$SITE_URL."/profile/sugarcrm_registration/registration_page1.php?record_id=$record_id");
		exit;
	}                       
}
$now = date("Y-m-d G:i:s");
$curDate=date("Y-m-d");
$smarty->assign("USERNAME",$username);
$smarty->assign("TIEUP_SOURCE",$tieup_source);
$smarty->assign("photo_uploaded",$photo_uploaded);
$smarty->assign("record_id",$record_id);
if(!$profileid){
$checksum1=$protect_obj->js_decrypt($checksum);
$profileid=getProfileidFromChecksum($checksum1);
}
$smarty->assign("PROFILEID",$profileid);
if($submit_type){
	$smarty->assign("CHECKSUM",$checksum);
	$sql="SELECT T_BROTHER,T_SISTER,PARENT_CITY_SAME,ENTRY_DT from newjs.JPROFILE where PROFILEID='$profileid'";
	$res=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_t,"ShowErrTemplate");
	if($row=mysql_fetch_assoc($res)){
		$brothers=$row['T_BROTHER'];
		$sisters=$row['T_SISTER'];
		$parent_city_same=$row['PARENT_CITY_SAME'];
		$entry_dt=substr($row['ENTRY_DT'],0,10);
	}
	switch ($submit_type){
	case 'C':
		include("$path/profile/sugarcrm_registration/registration_page7.php");
		die;
		break;
	case 'S':
		if(($brothers || $sisters || $parent_city_same) && $entry_dt!=$curDate){
			header("Location: $SITE_URL/profile/mainmenu.php");
			exit;
		}
		else
			include("$path/profile/registration_page3.php");
		die;
		break;
	}
		die;
}
if($delete_yes == 1){
/*
 $sql_t = "UPDATE PICTURE_TITLES SET TITLE='',T_IN_SCREEN='' where PROFILEID=$profileid";
 mysql_query_decide($sql_t) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_t,"ShowErrTemplate");
 $sql_del = "delete from PICTURE_FOR_SCREEN where PROFILEID=$profileid";
 mysql_query_decide($sql_del) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_del,"ShowErrTemplate");
	*/
 delete_picture($profileid,3);
}
else
{
/*
$sql = "select * from PICTURE_FOR_SCREEN where PROFILEID=$profileid";
$result = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
if(mysql_num_rows($result) > 0)
{
	$ro = mysql_fetch_array($result);
	$filename1 = $ro["MAINPHOTO"];
	$filename4 = $ro["THUMBNAIL"];
} 
*/  
if($filename1)
	$smarty->assign("profilephoto",1);
if($filename4)
	$smarty->assign("thumbphoto",1);
}
$smarty->assign("PHOTO_DISPLAY",'A');
$smarty->display("sugarcrm_registration/sugarcrm_registration_pg6.htm");
if($zipIt && !$dont_zip_now)
  ob_end_flush();
?>
