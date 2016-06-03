<?php
include("connect.inc");

if(authenticated($cid))
{

	if($submit)
	{
		$sql = "INSERT INTO incentive.LOG (PROFILEID,USERNAME,NAME,EMAIL,PHONE_RES,PHONE_MOB,SERVICE,ADDRESS,CITY,PIN,BYUSER,CONFIRM,AR_GIVEN,ENTRY_DT,ARAMEX_DT,STATUS,BILLING,ENTRYBY,COMMENTS,DISCOUNT,REF_ID) SELECT PROFILEID,USERNAME,NAME,EMAIL,PHONE_RES,PHONE_MOB,SERVICE,ADDRESS,CITY,PIN,BYUSER,CONFIRM,AR_GIVEN,ENTRY_DT,ARAMEX_DT,STATUS,BILLING,ENTRYBY,COMMENTS,DISCOUNT,'$id_user' FROM incentive.PAYMENT_COLLECT where ID='$id_user'";

		mysql_query_decide($sql) or die(mysql_error_js());
		$id = mysql_insert_id_js();

		$sql = "Update incentive.PAYMENT_COLLECT set AR_GIVEN = 'N', ENTRYBY = '$user',ENTRY_DT=now() where ID = '$id_user'";
		mysql_query_decide($sql) or die(mysql_error_js());

		$sql = "INSERT INTO incentive.LOG (PROFILEID,USERNAME,NAME,EMAIL,PHONE_RES,PHONE_MOB,SERVICE,ADDRESS,CITY,PIN,BYUSER,CONFIRM,AR_GIVEN,ENTRY_DT,ARAMEX_DT,STATUS,BILLING,ENTRYBY,COMMENTS,ADDON_SERVICEID,DISCOUNT,REF_ID) SELECT PROFILEID,USERNAME,NAME,EMAIL,PHONE_RES,PHONE_MOB,SERVICE,ADDRESS,CITY,PIN,BYUSER,CONFIRM,AR_GIVEN,ENTRY_DT,ARAMEX_DT,STATUS,BILLING,ENTRYBY,COMMENTS,ADDON_SERVICEID,DISCOUNT,'$id_user' FROM incentive.PAYMENT_COLLECT where ID='$id_user'";
		mysql_query_decide($sql) or die(mysql_error_js());
	
		$msg = "<font color=blue>$username</font> has been successfully discarded from the Dispatch invoice List.";
		$smarty->assign("MSG",$msg);
		$smarty->assign("DISCARD","Y");
		$smarty->display("clientinvoice_discard.htm");
	}
	else
	{
		$smarty->assign("cid",$cid);
		$smarty->assign("profileid",$pid);
		$smarty->assign("user",$user);
		$smarty->assign("username",$username);
		$smarty->assign("id_user",$id_user);
		$smarty->display("clientinvoice_discard.htm");
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
