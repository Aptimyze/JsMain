<?
include_once("../../connect.inc");
require("functions_transecute.php");
include_once("../functions.php");
connect_db();
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Services.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Membership.class.php");
        $serObj = new Services;
$membershipObj = new Membership;

/*

	This is the sample RedirectURL PHP script. It can be directly used for integration with CCAvenue if your application is developed in PHP. You need to simply change the variables to match your variables as well as insert routines for handling a successful or unsuccessful transaction.

	return values i.e the parameters namely Merchant_Id,Order_Id,Amount,AuthDesc,Checksum,billing_cust_name,billing_cust_address,billing_cust_country,billing_cust_tel,billing_cust_email,delivery_cust_name,delivery_cust_address,delivery_cust_tel,billing_cust_notes,Merchant_Param POSTED to this page by CCAvenue. 

*/

	//$key = "ZVI8mBzmllwBpMoe48xBFM0pB9y5mgvE";
	$key = "hdEgfuE99JeTrBbsqIRPmV5iirQicbwe";

	//mail("aman.sharma@jeevansathi.com,alok@jeevansathi.com","Online Payment details through TRANSECUTE","Details are : $desc, $amount, $status, $newchecksum");	

	$Checksum= verifychecksum($desc,$amount,$status,$newchecksum,$key);

	$Order_Id=$desc;
	$dup = false;
//$Checksum="true";$status="Y";$Order_Id="JF04950D996-437564";
	if($Checksum=="true" && $status=="Y")
	{
		$membershipObj->log_payment_status($Order_Id,'S','TRANSECUTE',"Success");
		$dup = false;
		$ret = $membershipObj->updtOrder($Order_Id, &$dup, $status);

		if(!$dup && $ret)
			$membershipObj->startServiceOrder($Order_Id);

		list($part1,$part2) = explode("-",$Order_Id);
		$sql = "SELECT * from billing.ORDERS where ID = '$part2' and ORDERID = '$part1'";
		$res = mysql_query_decide($sql);
			
		if(mysql_num_rows($res))
		{
			$myrow = mysql_fetch_array($res);
			$Amount=$myrow["AMOUNT"];
			list($year,$month,$day) = explode("-",$myrow["ENTRY_DT"]);
			$orderdate = my_format_date($day,$month,$year);

			list($year,$month,$day) = explode("-",$myrow["EXPIRY_DT"]);
			$expirydate = my_format_date($day,$month,$year);

			if($myrow["CURTYPE"] == 'DOL')
			{
				$paytype = "US $";
				$smarty->assign("SHOWCONVERSION","Y");
				$smarty->assign("CONVERSIONVALUE",floor($myrow["AMOUNT"]));
				$smarty->assign("DOL_CONV_RATE",$DOL_CONV_RATE);
				$Amount = $Amount / $DOL_CONV_RATE; //Amount always comes in INR
			}
			else
			{
				$paytype = "RS.";
			}
                        $ser_name =$serObj->getServiceName($myrow["SERVICEMAIN"]);
                        $QueryString = '';
                        foreach ($ser_name as $Key => $Value)
                        {
                                $QueryString .= ','. $Value[NAME];
                        }
                        $smarty->assign("MEMTYPE",substr($QueryString,1));
/*
			if(strstr($myrow["SERVICEMAIN"],"P"))
				$smarty->assign("MEMTYPE","e-Rishta");
			elseif(strstr($myrow["SERVICEMAIN"],"D"))
				$smarty->assign("MEMTYPE","e-Classifieds");
			elseif(strstr($myrow["SERVICEMAIN"],"C"))
				$smarty->assign("MEMTYPE","e-Value Pack");
				
			
			if(strstr($myrow["SERVEFOR"],"V"))
				$smarty->assign("VOICEMAIL","Y");
			if(strstr($myrow["SERVEFOR"],"H"))
				$smarty->assign("HOROSCOPE","Y");
			if(strstr($myrow["SERVEFOR"],"K"))
				$smarty->assign("KUNDLI","Y");
			if(strstr($myrow["SERVEFOR"],"B"))
				$smarty->assign("BOLDLISTING","Y");
			if($myrow["SERVEFOR"]=='')
				$smarty->assign("NOMEMBERSHIP","Y");
		
			if($myrow["SERVICEMAIN"]=='P2' || $myrow["SERVICEMAIN"]=='P3' || $myrow["SERVICEMAIN"]=='P4')
                                $smarty->assign("sid","10");
                        else
                                $smarty->assign("sid","12");			

*/			if(isset($_COOKIE['JSLOGIN']))
                        {
                                $checksum=$_COOKIE['JSLOGIN'];
                                list($val,$id)=explode("i",$checksum);
                                $sql="UPDATE newjs.CONNECT SET SUBSCRIPTION='".$myrow['SERVEFOR']."' WHERE ID='$id'";
                                mysql_query_decide($sql);
                        }
		
			$service_main_details=getServiceDetails($myrow["SERVICEMAIN"]);	
			$smarty->assign("PERIOD",$service_main_details["DURATION"]);
			$smarty->assign("AMOUNT",$Amount);
			$smarty->assign("ORDERID",$Order_Id);
			$smarty->assign("ORDERDATE",$orderdate);
			$smarty->assign("EXPIRYDATE",$expirydate);
			$smarty->assign("BILL_NAME",$myrow["USERNAME"]);
			$smarty->assign("PAYTYPE",$paytype);

			$smarty->assign("CHECKSUM",$Merchant_Param);
                        $data=authenticated();
                        $smarty->assign("USERNAME",$data[USERNAME]);
                        $smarty->assign("FOOT",$smarty->fetch("footer.htm"));
                        $smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));

                        set_subscription_cookie($myrow['PROFILEID']);
 //                       payment_thanks_things_to_do($myrow['PROFILEID'],$myrow['SET_ACTIVATE']);

			$smarty->display("pg/orderreceipt.htm");

		}

		//echo "<br>Thank you for shopping with us. Your credit card has been charged and your transaction is successful. We will be shipping your order to you soon.";		
		//Here you need to put in the routines for a successful 
		//transaction such as sending an email to customer,
		//setting database status, informing logistics etc etc
	}
	else
	{
//		mail('aman.sharma@jeevansathi.com,alok@jeevansathi.com','ILLEGAL ACCESS TRANSECUTE','');
			$membershipObj->log_payment_status($Order_Id,'U','TRANSECUTE',"illegal or failed");
                        $smarty->assign("FOOT",$smarty->fetch("footer.htm"));
                        $smarty->assign("HEAD",$smarty->fetch("revamp_head.htm"));
                        $smarty->assign("SUBFOOTER",$smarty->fetch("subfooter.htm"));
                        $smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));
                        $smarty->assign("TOPLEFT",$smarty->fetch("topleft.htm"));
                        $smarty->assign("LEFTPANEL",$smarty->fetch("leftpanel.htm"));
                                                                                                 
                        $smarty->assign("msg_error","There is some problem in retreiving the requested URL.<br><br> <a href=\"/profile/mainmenu.php?checksum=$checksum\">Click here</a> to go back to your jeevansathi.com account.");
                        //$smarty->display("error_template.htm");
                        $smarty->display("pg/ordererror.htm");
                        die;

		//Here you need to simply ignore this and dont need
		//to perform any operation in this condition
	}
?>
