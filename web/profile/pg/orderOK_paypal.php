<?php
include ("../connect.inc");
include ("functions.php");
connect_db();
include_once ($_SERVER['DOCUMENT_ROOT'] . "/classes/Services.class.php");
include_once ($_SERVER['DOCUMENT_ROOT'] . "/classes/Membership.class.php");
$serObj = new Services;
$membershipObj = new Membership;

if (MobileCommon::isMobile()) {
    include_once ($_SERVER['DOCUMENT_ROOT'] . "/profile/common_functions.inc");
    assignHamburgerSmartyVariables($profileid);
}

$tx_token = $_GET['tx'];

if(JsConstants::$whichMachine == 'test'){
	$auth_token = gatewayConstants::$PaypalTestToken;
	$paypalURL = gatewayConstants::$PaypalTestURL;
} else {
	$auth_token = gatewayConstants::$PaypalLiveToken;
	$paypalURL = gatewayConstants::$PaypalLiveURL;
}

if ($data = authenticated($cm)) {
    $profileid = $data["PROFILEID"];
}

$url = "https://" . $paypalURL . "/cgi-bin/webscr";
$post_vars = "cmd=_notify-synch&tx=" . $tx_token . "&at=" . $auth_token;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_vars);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 15);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_USERAGENT, 'cURL/PHP');

$fp = curl_exec($ch);

$dup =  false;

if($profileid && $fp){
    $lines = explode("\n", $fp);
    $keyarray = array();
    if (strcmp($lines[0], "SUCCESS") == 0) {
        for ($i = 1; $i < count($lines); $i++) {
            list($key, $val) = explode("=", $lines[$i]);
            $keyarray[urldecode($key) ] = urldecode($val);
        }

        $Order_Id = $keyarray['item_number'];
        $amount_gateway = $keyarray['payment_gross'];
        $itemName = $keyarray["item_name"];
        $myEmail = $keyarray["receiver_email"];
        $userEmailPaypalId = $keyarray["payer_email"];
        $paymentStatus = $keyarray["payment_status"];
        $paypalTxId = $keyarray["txn_id"];
        $currency = $keyarray["mc_currency"];
        
        $ret = $membershipObj->updtOrder($Order_Id, $dup, "Y");

    	if (!$dup && $ret) $membershipObj->startServiceOrder($Order_Id);

        list($part1, $part2) = explode("-", $Order_Id);
	    $sql = "SELECT * from billing.ORDERS where ID = '$part2' and ORDERID = '$part1'";
	    $res = mysql_query_decide($sql);
	    $ordrDeviceObj = new billing_ORDERS_DEVICE();
	    $device = $ordrDeviceObj->getOrderDevice($part2, $part1);
	    
	    if (mysql_num_rows($res)) {
	        $myrow = mysql_fetch_array($res);
	        $Amount = $myrow["AMOUNT"];
	        list($year, $month, $day) = explode("-", $myrow["ENTRY_DT"]);
	        $order_date_mobile = date("d M Y", strtotime($myrow["ENTRY_DT"]));
	        $orderdate = my_format_date($day, $month, $year);
	        
	        list($year, $month, $day) = explode("-", $myrow["EXPIRY_DT"]);
	        $expirydate = my_format_date($day, $month, $year);
	        
	        if ($myrow["CURTYPE"] == 'DOL') {
	            $paytype = "US $";
	        } 
	        else {
	            $paytype = "Rs.";
	        }
	        $ser_name = $serObj->getServiceName($myrow["SERVICEMAIN"]);
	        $memHandlerObj = new MembershipHandler();
	        list($vas, $main) = $memHandlerObj->getMobileDisplayServiceArray($ser_name, $part2, $part1, $myrow['PROFILEID'], $myrow['ENTRY_DT'], $myrow['EXPIRY_DT']);
	        $smarty->assign("vasServices", $vas);
	        $smarty->assign("mainServices", $main);
	        
	        $QueryString = '';
	        foreach ($ser_name as $Key => $Value) {
	            $QueryString.= ',' . $Value[NAME];
	        }
	        $smarty->assign("MEMTYPE", substr($QueryString, 1));

	        if (isset($_COOKIE['JSLOGIN'])) {
	            $checksum = $_COOKIE['JSLOGIN'];
	            list($val, $id) = explode("i", $checksum);
	            $sql = "UPDATE newjs.CONNECT SET SUBSCRIPTION='" . $myrow['SERVEFOR'] . "' WHERE ID='$id'";
	            mysql_query_decide($sql);
	        }
	        
	        $service_main_details = getServiceDetails($myrow["SERVICEMAIN"]);
	        $smarty->assign("PERIOD", $service_main_details["DURATION"]);
	        $smarty->assign("AMOUNT", $Amount);
	        $smarty->assign("ORDERID", $Order_Id);
	        $smarty->assign("PROFILEID", $profileid);
	        $smarty->assign("ORDERDATE", $orderdate);
	        $smarty->assign("ORDERDATEMOB", $order_date_mobile);
	        $smarty->assign("EXPIRYDATE", $expirydate);
	        $smarty->assign("BILL_NAME", $myrow["USERNAME"]);
	        $smarty->assign("PAYTYPE", $paytype);
	        $smarty->assign("CHECKSUM", $cm);
	        $data = authenticated();
	        $smarty->assign("USERNAME", $data["USERNAME"]);
	        $smarty->assign("FOOT", $smarty->fetch("footer.htm"));
	        $smarty->assign("REVAMP_HEAD", $smarty->fetch("revamp_head.htm"));
	        if (MobileCommon::isMobile()) {
	            if (strpos($device, 'mobile_website') === FALSE) {
	                $smarty->display("pg/mob_redirect_orderreceipt.htm");
	            } 
	            else {
	                if (BrowserCheck::IsHtml5Browser()) {
	                    $smarty->display("pg/rev_redirect_orderreceipt.htm");
	                } 
	                else {
	                    $smarty->display("pg/mob_orderreceipt.htm");
	                }
	            }
	        } 
	        else {
	            $smarty->display("pg/orderreceipt.htm");
	        }
	    }
    } 
    else if (strcmp($lines[0], "FAIL") == 0) {
        $ret = $membershipObj->updtOrder($Order_Id, $dup, 'N');
	    list($part1, $part2) = explode("-", $Order_Id);
	    $ordrDeviceObj = new billing_ORDERS_DEVICE();
	    $device = $ordrDeviceObj->getOrderDevice($part2, $part1);
	    
	    $smarty->assign("CHECKSUM", $cm);
	    $smarty->assign("HEAD", $smarty->fetch("revamp_head.htm"));
	    $smarty->assign("SUBHEADER", $smarty->fetch("subheader.htm"));
	    $smarty->assign("TOPLEFT", $smarty->fetch("topleft.htm"));
	    $smarty->assign("LEFTPANEL", $smarty->fetch("leftpanelnew.htm"));
	    $smarty->assign("FOOT", $smarty->fetch("footer.htm"));
	    $smarty->assign("PROFILEID", $profileid);
	    $smarty->assign("ORDERID", $Order_Id);
	    $smarty->assign("SUBFOOTER", $smarty->fetch("subfooternew.htm"));
	    if (MobileCommon::isMobile()) {
	        if (strpos($device, 'mobile_website') === FALSE) {
	            $smarty->display("pg/mob_redirect_ordererror.htm");
	        } 
	        else {
	            if (BrowserCheck::IsHtml5Browser()) {
	                $smarty->display("pg/rev_redirect_ordererror.htm");
	            } 
	            else {
	                $smarty->display("pg/mob_ordererror.htm");
	            }
	        }
	    } 
	    else {
	        $smarty->display("pg/ordererror.htm");
	    }
    }
} else {
	echo "<br>Security Error. Illegal access detected";
}

fclose($fp);
?>
