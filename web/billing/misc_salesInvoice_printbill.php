<?php
include("../jsadmin/connect.inc");
include("../profile/comfunc.inc");
include_once("comfunc_sums.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Membership.class.php");
if(authenticated($cid) || ($receiptid && $billid))
{
	if($invoiceType=='M'){
		$bill=misc_rev_sales_printbill($receiptid,$saleid,$saleType);
	}
	elseif($invoiceType=='JS'){
               $membershipObj = new Membership;
               $bill=$membershipObj->printbill($receiptid,$billid);
	}
	echo $bill;
}
else
{
        $msg="Error: Incorrect userid or password";
        $smarty->assign("name",$adminname);
        $smarty->assign("cid",$cid);
        $smarty->assign("MSG",$msg);
        $smarty->display("../billing_msg.tpl");
                                                                                                 
}
?>
