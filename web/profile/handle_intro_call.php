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
$loggedInUser=$data["PROFILEID"];
$type_of_contact=$TYPE_OF;

if($senders_data){
	$senders_data=$senders_data;
	//If someone is indirectly accessing the script
	if($type_of_contact!='M' && $type_of_contact!='S')
	{
		echo"ERROR#This operation is not allowed";
		die;
	}
                        $rec_profileid=getProfileidFromChecksum($senders_data);
			if($rec_profileid==0)
                        {
                                echo "ERROR#Breaching of data is not allowed.";
                                die;
                        }
		if($to_do == "add_intro")
		{
			$sql="INSERT IGNORE INTO Assisted_Product.AP_CALL_HISTORY(PROFILEID,MATCH_ID,REQUEST_DATE,CALL_STATUS, FOLDER) VALUES ('$loggedInUser', '$rec_profileid', now(), 'N', 'TBC')";
			mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		}
		elseif($to_do == "remove_intro")
		{
                        $sql="DELETE FROM Assisted_Product.AP_CALL_HISTORY WHERE PROFILEID ='$loggedInUser' AND MATCH_ID= '$rec_profileid' AND CALL_STATUS='N'";
                        mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		}
	}
echo "SUCCESS";
die;
?>
