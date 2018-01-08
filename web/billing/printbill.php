<?php
include_once("../jsadmin/connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include_once("comfunc_sums.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Membership.class.php");

if(authenticated($cid))
{
	$membershipObj = new Membership;
	$back="<input type=button value=\"Back\" onClick=\"history.go(-1)\">";
	if($_POST['cbbill'] == '')
	{
		echo "Please select a payment to generate bill.\n";
		echo $back;
		exit;
	}
	list($billid,$receiptid)= explode("i",$_POST['cbbill']);
	$billoption= $_POST['billoption'];
	if($billoption=="")
	{
		echo "Please select an option to print or send a bill in a mail\n";
		echo $back;
		exit;
	}
		
	if($billoption == "1")
	{
		if(JsConstants::$whichMachine == 'prod' && JsConstants::$siteUrl == 'https://www.jeevansathi.com'){
			$SITE_URL = 'https://crm.jeevansathi.com';
		} else {
			$SITE_URL = JsConstants::$siteUrl;
		}
		header("Location: $SITE_URL/operations.php/invoice/pdf?receiptid=$receiptid&billid=$billid&invoiceType=JS&cid=$cid");
                exit;
	}
	elseif($billoption == "2" || $billoption=="3")
	{
		include_once("invoiceGenerate.php");
		//$bill=$membershipObj->printbill($receiptid,$billid);
		if($billoption=="3" and $mailto == "")
		{
			echo "Mail To field is left blank";
			echo $back;
			exit;
		}
		$sql="SELECT SERVICEID,ADDON_SERVICEID,USERNAME,EMAIL,AMOUNT,TYPE,PAYMENT_DETAIL.ENTRY_DT from billing.PURCHASES,billing.PAYMENT_DETAIL where PAYMENT_DETAIL.RECEIPTID='$receiptid' and PURCHASES.BILLID=PAYMENT_DETAIL.BILLID";
		$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		$myrow=mysql_fetch_array($result);
		$username=$myrow['USERNAME'];
		$amount=$myrow['AMOUNT'];
		if($billoption=="3")
			$email= $mailto;
		else
			$email=$myrow['EMAIL'];
		$service_selected=$myrow['SERVICEID'];
		$sql="SELECT c.COMPID as COMPID, c.DURATION as DURATION from billing.SERVICES a,
billing.PACK_COMPONENTS b, billing.COMPONENTS c  where a.PACKID = b.PACKID AND b.COMPID = c.COMPID AND a.SERVICEID = '$service_selected'";
		$result_pkg=mysql_query_decide($sql) or die(mysql_error_js());
                $myrow_pkg = mysql_fetch_array($result_pkg);
		$duration=$myrow_pkg["DURATION"];

		$addon_service_selected=$myrow['ADDON_SERVICEID'];
		$type=$myrow['TYPE'];
		list($year,$month,$day)=explode("-",$myrow['ENTRY_DT']);
		$date=my_format_date($day,$month,$year);

            //    $subject = "Congrats!You are now a Full Member!";
	/*	if(strstr($service_selected,'P'))
                        $subject = "Congrats!You are now an e-Rishta Member!";
                if(strstr($service_selected,'D'))
                        $subject = "Congrats! You are now an e-Classified Member!";
                if(strstr($service_selected,'C'))
                        $subject = "Congrats! You are now an e-Value Pack Member!";
	*/
		$subject = "Bill for your subscription";

		$msg = "Dear $username,\n\nThank you for subscribing to Jeevansathi.com.\n\nWe have received your payment of $type $amount . \n\nCopy of your bill (BILL.pdf) has been attached with this mail. Kindly revert back for any discrepancies in the bill.";
 
		$sql="SELECT EMAIL from jsadmin.PSWRDS where USERNAME='$user'";
		$result=mysql_query_decide($sql);
		$myrow=mysql_fetch_array($result) or die("$sql".mysql_error_js());
		$entrybyemail= $myrow['EMAIL'];

                //send_email("$email",$msg,$subject,'webmaster@jeevansathi.com',$entrybyemail,"aman.sharma@jeevansathi.com",$bill);
		//different mail function called to send html mail along with rtf attachment.

                	send_email("$email",$msg,$subject,'membership@jeevansathi.com',$entrybyemail,"aman.sharma@jeevansathi.com",$bill,'','','',1,'','Jeevansathi Membership');
		
		echo "Mail has been sent to $email with bill as an attachment against Receipt Id $receiptid";	
	}
	//echo $back;	
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
