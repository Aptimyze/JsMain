<?php
include("connect.inc");
include("extract_csv.php");
include("put_csv.php");
include_once('uploadfile_inc.php');
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");
global $file;
$path = JsConstants::$docRoot."/crm/csv_files/";
if(authenticated($cid))
{	
        $mail_login= get_login_email($cid);
        $loginname= getuser($cid);
	if($flag_upload)
	{
		if($save_file)
		{
			$maillist=put_csv($path,"new.csv");
		        if(count($maillist)>0)
			        insert_claim($maillist,$mail_login);
		        else
			        echo "\nWarning! File is not in the correct format to claim the entries. Please make sure that the csv file is being uploaded in the zip format\n";	
			$msg="You have successfully claimed the entries for chat<br>";
			$msg .="<a href=\"uploadfile.php?username=$username&cid=$cid\">";		
			$msg .="Continue&gt;&gt;</a>";
			$smarty->assign("MSG",$msg);
			$smarty->assign("name",$username);
			$smarty->assign("cid",$cid);		
			$smarty->display("jsadmin_msg.tpl");		
		}
	}
}
else//user timed out
{
	$msg="Your session has been timed out<br><br>";
	$msg .="<a href=\"uploadfile.php?username=$username&cid=$cid\">";
	$msg .="Login again </a>";	
	$smarty->assign("MSG",$msg);	
	$smarty->display("jsadmin_msg.tpl");
}
?>
