<?php
/*****************************************************************************************************************************           FILE NAME      : verification_failed.php
*           DESCRIPTION    : Report verification failed for a user by updating in OFFLINE_ASSIGNED and sending mail
*           FILES INCLUDED : connect.inc ; functions used : authenticated()(for authentication of the user) & getemail(to get					email of the operator)
			     ../profile/comfunc.inc; function used: send_email()
*           CREATION DATE  : 31 July, 2008
*           CREATED BY     : Neha Verma
*           Copyright  2005, InfoEdge India Pvt. Ltd.
****************************************************************************************************************************/


include("connect.inc");
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");

if(authenticated($cid))
{
	if($username)
	{
		$sql="UPDATE jsadmin.OFFLINE_ASSIGNED SET VERIFIED='N' WHERE PROFILEID='$profileid'";
		mysql_query_decide($sql) or logError($sql);
		$op_email=getemail($cid);
		$msg="Match point user <strong>$username</strong> has failed our verification process. Kindly take necessary action";
		//$to="neha.verma@jeevansathi.com";
		$to="krishnan@jeevansathi.com";
		
		send_email($to,$msg,"Failed verification report",$op_email);
		$smarty->assign("done","1");
	}
	$sql_uname="SELECT USERNAME FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
        $res_uname=mysql_query_decide($sql_uname) or logError($sql_uname);
        $row_uname=mysql_fetch_assoc($res_uname);
        $username=$row_uname["USERNAME"];
	$smarty->assign("username",$username);
	$smarty->assign("cid",$cid);
	$smarty->assign("profileid",$profileid);
	$smarty->display("verification_failed.htm");
}
else
{
        $msg="Your session has been timed out  ";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");

}


?>
