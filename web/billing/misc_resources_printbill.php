<?php
include("../jsadmin/connect.inc");
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");

$smarty->template_dir="/usr/local/apache/sites/jeevansathi.com/htdocs/jsadmin/templates";
$smarty->compile_dir="/usr/local/apache/sites/jeevansathi.com/htdocs/jsadmin/templates_c";

if(authenticated($cid))
{
	$back="<input type=button value=\"Back\" onClick=\"history.go(-1)\">";

	
	if($_POST['cbbill'] == '')
	{
		echo "Please select a payment to generate bill.\n";
		echo $back;
		exit;
	}
	list($billid,$receiptid)= explode("i",$_POST['cbbill']);
	$sql="select a.CATEGORY,a.BUREAU_PID from billing.REV_MASTER as a,billing.REV_PAYMENT as b where b.RECEIPTID='$receiptid' and a.SALEID=b.SALEID ";
	$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
	$myrow=mysql_fetch_array($result);
	$category=$myrow['CATEGORY'];
	$bureau_pid=$myrow['BUREAU_PID'];
	if($category!='marriage_bureau') 
	{
		echo "This feature is available only for Marriage bureau's billing .\n";
                echo $back;
                exit;
        }
	
	$billoption= $_POST['billoption'];
	if($billoption=="")
	{
		echo "Please select an option to print or send a bill in a mail\n";
		echo $back;
		exit;
	}
		
	$bill=misc_rev_printbill($receiptid,$billid);
	if($billoption == "1")
	{
		header('Content-type: text/rtf');	
		header('Content-Disposition: filename="BILL.rtf"');	
		echo $bill;
	}
	elseif($billoption == "2")
	{
		if($mailto == "")
		{
			echo "Mail To field is left blank";
			echo $back;
			exit;
		}
		
		$sql="select a.USERNAME,a.NAME,c.RECEIPTID,c.TYPE,c.AMOUNT,c.ENTRY_DT from marriage_bureau.BUREAU_PROFILE as a,billing.REV_MASTER as b,billing.REV_PAYMENT as c where c.RECEIPTID='$receiptid' and b.SALEID=c.SALEID and a.PROFILEID=b.BUREAU_PID";
		$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
		$myrow=mysql_fetch_array($result);
		$name=$myrow['NAME'];
		$username=$myrow['USERNAME'];
		$amount=$myrow['AMOUNT'];
		$type=$myrow['TYPE'];
		$billdate=$myrow['ENTRY_DT'];
		$inv_dt_arr=explode(" ",$billdate);
	        list($inv_year,$inv_month,$inv_day)=explode("-",$inv_dt_arr[0]);
        	$inv_date=$inv_day."-".$inv_month."-".$inv_year;

		$email= $mailto;
		$subject="Congrats!You are now a marriage-bureau Member!";
                $msg = "Dear $name,\n\n We have received your payment of $type $amount on $inv_date \n\nCopy of your bill (Bill.rtf) has been attached with this mail. Kindly revert back for any discrepancies in the bill."; 
		$sql="SELECT EMAIL from jsadmin.PSWRDS where USERNAME='$user'";
		$result=mysql_query_decide($sql);
		$myrow=mysql_fetch_array($result) or die("$sql".mysql_error_js());
		$entrybyemail= $myrow['EMAIL'];

                send_email("$email",$msg,$subject,'webmaster@jeevansathi.com',$entrybyemail,"aman.sharma@jeevansathi.com",$bill);
		echo "Mail has been sent to $email with bill as an attachment";	
	}
	echo $back;	
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
