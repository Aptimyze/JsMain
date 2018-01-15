<?php
include("connect.inc");

if(authenticated($cid))
{

	if($Submit)
	{
		$sql="UPDATE newjs.LIVECHAT set STATUS = '$status'";
		mysql_query_decide($sql) or die("Can not reset previous list because of : ".mysql_error_js());

		$sql="INSERT INTO jsadmin.LIVECHAT_LOG (DATETIME,USERNAME,STATUS) values (now(),'".getuser($cid)."','$status')";
		mysql_query_decide($sql) or die("Can not reset previous list because of : ".mysql_error_js());

                $msg = "You have successfully changed the live chat status on homepage<br><br>";
		$msg .= "<a href=\"mainpage.php?name=$user&cid=$cid\">";
		$msg .= "Continue &gt;&gt;</a>";

		$smarty->assign("cid",$cid);
		$smarty->assign("MSG",$msg);
		$smarty->display("jsadmin_msg.tpl");
	}
	else
	{
		$sql="SELECT SQL_CACHE STATUS from newjs.LIVECHAT";
		$result=mysql_query_decide($sql) or die(mysql_error_js());
		$myrow1=mysql_fetch_array($result);
		
		$smarty->assign("STATUS",$myrow1["STATUS"]);
		$smarty->assign("cid",$cid);
		$smarty->display("livechat_status_manage.tpl");
	}
}
else
{
        $msg="Your session has been timed out<br>";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}

?>
