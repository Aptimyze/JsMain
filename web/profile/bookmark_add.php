<?php
$path=$_SERVER['DOCUMENT_ROOT'];
include("$path/profile/connect.inc");
include_once("mobile_detect.php");
$db=connect_db();
$smarty->assign("MODE",$MODE);
$data=authenticated($bookmarker);
if($isMobile){
	    navigation($nav_type,"","");
		assignHamburgerSmartyVariables($data["PROFILEID"]);
		$smarty->assign("NAV_TYPE",$nav_type);
}
if(!$data)
{
	$smarty->assign("PREV_URL",$_SERVER['REQUEST_URI']);
	if($isMobile)
		$smarty->display("mobilejs/jsmb_login.html");
	else
	{
	include_once($_SERVER['DOCUMENT_ROOT']."/profile/include_file_for_login_layer.php");
	$smarty->display("login_layer.htm");
	}
	die;
}
else {
    $smarty->assign("LOGGEDIN", 1);
}
if($type=='show')
{
	if($isMobile){
		$smarty->assign("NAV_TYPE",$nav_type);
		$smarty->assign("senders_data",$senders_data);
		$smarty->display("mobilejs/jsmb_add_fav.html");
		die;
	}
	$smarty->display("add_favourites.htm");
	//echo $div;
	die;
}
$bkmarker=$data["PROFILEID"];
$bkdate=date("Y-m-d H:i:s");
$text=$MESSAGE;
$type_of_contact=$TYPE_OF;

if($senders_data){
	$senders_data=$senders_data;
	//If someone is indirectly accessing the script
	if($type_of_contact!='M' && $type_of_contact!='S')
	{
		echo"ERROR#This operation is not allowed";
		die;
	}
	if($senders_data=="")
	{
		echo "ERROR#Please first select Users";
		die;
	}
	$rec_check=explode(",",$senders_data);
	$total_contact=count($rec_check);
	for($start=0;$start<count($rec_check);$start++)
	{
			
			$receiver_id=$rec_check[$start];
                        $rec_profileid=getProfileidFromChecksum($receiver_id);
			if($rec_profileid==0)
                        {
                                echo "ERROR#Breaching of data is not allowed.";
                                die;
                        }
			
		$sql="REPLACE INTO newjs.BOOKMARKS(BOOKMARKER,BOOKMARKEE,BKDATE,BKNOTE) VALUES('$bkmarker','$rec_profileid','$bkdate','$text')";
		mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$profileMemcacheObj = new ProfileMemcacheService($bkmarker);
			$profileMemcacheObj->update("BOOKMARK",1);
			$profileMemcacheObj->updateMemcache();
	}
}
if($isMobile)
	$smarty->display("mobilejs/jsmb_add_fav_confirm.html");
else
echo "SUCCESS";
die;
?>
