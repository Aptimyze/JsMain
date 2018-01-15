<?php

/************************************************************************************************************************
*    FILENAME           : online_call_divert.php
*    INCLUDED           : connect.inc
*    DESCRIPTION        : activate/deactivate the online call divert
*    CREATED BY         : vibhor
***********************************************************************************************************************/


include ("connect.inc");

if (authenticated($cid))
{     
	if($act=='Y')
        {
                $sql="update newjs.CALL_DIVERT set STATUS='yes'";
                mysql_query_decide($sql) or die(mysql_error_js());

                $msg= " Record Updated<br>  ";
                $msg .="<a href=\"online_call_divert.php?cid=$cid\">";
                $msg .="Continue </a>";
                $smarty->assign("MSG",$msg);
                $smarty->display("jsadmin_msg.tpl");
        }
	elseif($act=='N')
        {
                $sql="update newjs.CALL_DIVERT set STATUS='no'";
                mysql_query_decide($sql) or die(mysql_error_js());

                $msg= " Record Updated<br>  ";
                $msg .="<a href=\"online_call_divert.php?cid=$cid\">";
                $msg .="Continue </a>";
                $smarty->assign("MSG",$msg);
                $smarty->display("jsadmin_msg.tpl");
        }
	else
	{
		$sql  = "select STATUS from newjs.CALL_DIVERT" ;
		$result = mysql_query_decide($sql) or die("$sql".mysql_error_js());
		if($row=mysql_fetch_array($result))
			$status=$row['STATUS'];
		$smarty->assign("cid",$cid);
		$smarty->assign("status",$status);
		$smarty->display("online_call_divert.htm");
	}
}
else
{
	$msg="Your session has been timed out<br>  ";
	$msg .="<a href=\"index.htm\">";
	$msg .="Login again </a>";
	$smarty->assign("MSG",$msg);
	$smarty->display("jsadmin_msg.tpl");
}
?>
