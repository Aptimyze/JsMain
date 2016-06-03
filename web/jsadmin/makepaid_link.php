<?php
include("connect.inc");
if(authenticated($cid))
{
        if($SUBMIT)
        {
               if($val=="refund")
	                $ref_amount=$paidamount-$pricenew+$discount_new;
		$smarty->assign("REF_AMOUNT",$ref_amount);
		$smarty->assign("cid",$cid);
		$smarty->assign("pid",$pid);
		$smarty->assign("val",$val);
		$smarty->assign("user",$user);
		$smarty->assign("username",$username);
		$smarty->assign("mtype",$mtype);
		$smarty->assign("duration",$duration);
		$smarty->assign("serviceid",$serviceid);
		$smarty->assign("billid",$billid);
		$smarty->assign("link_msg",$link_msg);
		$smarty->assign("PAIDAMOUNT",$paidamount);
		$smarty->assign("DUEAMOUNT_NEW",$dueamount_new);
		$smarty->assign("PRICENEW",$pricenew);
		$smarty->assign("ENTRYDT",$entrydt);
		$smarty->assign("bank",create_dd($Bank,"Bank"));
		$smarty->display("makepaid_refund_paypart.htm");
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
