<?php
$path=$_SERVER['DOCUMENT_ROOT'];
include("$path/profile/connect.inc");
$db=connect_db();
$smarty->assign("MODE",$MODE);
$data=authenticated($bookmarker);
$smarty->assign('multiple',$multiple);
if(!$data)
{
	$smarty->assign("PREV_URL",$_SERVER['REQUEST_URI']);
	include_once($_SERVER['DOCUMENT_ROOT']."/profile/include_file_for_login_layer.php");
	$smarty->display("login_layer.htm");
	die;
}
if($type=='show')
{
	$smarty->assign("OTHER_USERNAME",$username);
	$smarty->display("remove_bookmark.htm");
	//echo $div;
	die;
}
$bkmarker=$data["PROFILEID"];
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
	$profileMemcacheServiceObj = new ProfileMemcacheService($bkmarker);
	for($start=0;$start<count($rec_check);$start++)
	{
			
			$receiver_id=$rec_check[$start];
                        $rec_profileid=getProfileidFromChecksum($receiver_id);
			if($rec_profileid==0)
                        {
                                echo "ERROR#Breaching of data is not allowed.";
                                die;
                        }
		$sql="delete from newjs.BOOKMARKS where BOOKMARKER='$bkmarker' and BOOKMARKEE='$rec_profileid'";
		mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		$profileMemcacheServiceObj->update("BOOKMARK",-1);
	}
	$profileMemcacheServiceObj->updateMemcache();
}
echo "SUCCESS";
die;
?>
