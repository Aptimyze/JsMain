<?php
include("../jsadmin/connect.inc");
include("../profile/comfunc.inc");
include_once("comfunc_sums.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Membership.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."../lib/model/store/billing_PAYMENT_DETAILS.class.php");
if(authenticated($cid) || ($receiptid && $billid))
{
	if($invoiceType=='M'){
		$bill=misc_rev_sales_printbill($receiptid,$saleid,$saleType);
	}
	elseif($invoiceType=='JS'){
		$membershipObj = new Membership;
                $billingPaymentDetObj = new BILLING_PAYMENT_DETAIL();
                $myrow1 = $billingPaymentDetObj->fetchPrintBillDataForReceiptId($receiptid);
                $billDate = $myrow1['ENTRY_DT'];
                if($billDate>=billingVariables::TAX_LIVE_DATE){
                    $bill=$membershipObj->printGSTbill($receiptid,$billid);
                }else{
                    $bill=$membershipObj->printbill($receiptid,$billid);
                }
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
