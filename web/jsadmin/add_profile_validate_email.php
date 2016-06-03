<?php
include("connect.inc");

if(authenticated($cid))
{
	if($profileid)
	{
		/*$sql="SELECT USERNAME,EMAIL FROM newjs.JPROFILE WHERE PROFILEID = '$profileid'";
		$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		$row=mysql_fetch_array($res);
		$org_email=$row['EMAIL'];
		$username=$row['USERNAME'];*/

		$sql="UPDATE newjs.JPROFILE SET VERIFY_EMAIL='Y' WHERE PROFILEID='$profileid'";
		mysql_query_decide($sql) or die("$sql".mysql_error_js());
		
		$msg="Record updated successfully<br><br>";
		$msg .="<a href=\"searchpage.php?cid=$cid\">";
		$msg .="Continue </a>";
		$smarty->assign("MSG",$msg);
		$smarty->display("jsadmin_msg.tpl");
	}
	else
	{
		$msg="Some error occured. Profile not screened<br><br>";
		$msg .="<a href=\"userview.php?cid=$cid\">";
		$msg .="Try again </a>";
		$smarty->assign("MSG",$msg);
		$smarty->display("jsadmin_msg.tpl");
	}
}
else
{
        $msg="Your session has been timed out<br><br>";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
?>
