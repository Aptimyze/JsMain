<?php
include("connect.inc");
if(authenticated($cid))
{
	if($submit)
	{
		$sql = "INSERT INTO incentive.LOG (PROFILEID,USERNAME,NAME,EMAIL,PHONE_RES,PHONE_MOB,SERVICE,ADDRESS,CITY,PIN,BYUSER,CONFIRM,AR_GIVEN,ENTRY_DT,ARAMEX_DT,STATUS,BILLING,ENTRYBY,COMMENTS,DISCOUNT,REF_ID) SELECT PROFILEID,USERNAME,NAME,EMAIL,PHONE_RES,PHONE_MOB,SERVICE,ADDRESS,CITY,PIN,BYUSER,
CONFIRM,AR_GIVEN,ENTRY_DT,ARAMEX_DT,STATUS,BILLING,ENTRYBY,COMMENTS,DISCOUNT,ID FROM incentive.PAYMENT_COLLECT where ID=$id";
                mysql_query_decide($sql) or die(mysql_error_js());

                $sql = "Update incentive.PAYMENT_COLLECT set COMMENTS = '$comment', ENTRYBY='$user',ENTRY_DT=now() where ID='$id'";
                mysql_query_decide($sql) or die(mysql_error_js());
		
		$msg = "Comment successfully added to <font color=blue>$username</font>";
		$smarty->assign("MSG",$msg);
		$smarty->assign("CONFIRM","Y");
		$smarty->display("status_activation_comment.htm");	
	}
	else
	{
		$smarty->assign("cid",$cid);
                $smarty->assign("profileid",$pid);
		$smarty->assign("id",$id);
                $smarty->assign("user",$user);
                $smarty->assign("username",$username);
                $smarty->display("status_activation_comment.htm");
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
