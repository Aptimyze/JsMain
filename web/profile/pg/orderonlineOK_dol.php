<?php
include ("../connect.inc");
include ("functions.php");
connect_db();
include_once ($_SERVER['DOCUMENT_ROOT'] . "/classes/Services.class.php");
include_once ($_SERVER['DOCUMENT_ROOT'] . "/classes/Membership.class.php");
$serObj = new Services;
$membershipObj = new Membership;

if ($data = authenticated($checksum)) {
    $profileid = $data["PROFILEID"];
}

if (JsConstants::$whichMachine == 'test') {
    $WorkingKey = gatewayConstants::$CCAvenueTestDolSalt;
} 
else {
    $WorkingKey = gatewayConstants::$CCAvenueLiveDolSalt;
}

$AuthDesc = $Auth_Status;

if ($AuthDesc == "Y") $ret_status = "S";
elseif ($AuthDesc == "N") $ret_status = "F";
elseif ($AuthDesc == "B") $ret_status = "P";
else $ret_status = "U";
$membershipObj->log_payment_status($Order_Id, $ret_status, 'CCAVENUE', $AuthDesc);

$Checksum = CCAvenueDolManager::verifyCheckSumAll($Merchant_Id, $Order_Id, $Amount, $WorkingKey, $Currency, $Auth_Status, $checkSumAll);

$dup = false;

if ($Checksum == "true" && $AuthDesc == "Y") {
    $dup = false;
    $ret = $membershipObj->updtOrder($Order_Id, $dup, $AuthDesc);

    if (!$dup && $ret) $membershipObj->startServiceOrder($Order_Id);
    
    list($part1, $part2) = explode("-", $Order_Id);
    $sql = "SELECT * from billing.ORDERS where ID = '$part2' and ORDERID = '$part1'";
    $res = mysql_query_decide($sql);
    
    if (mysql_num_rows($res)) {
        $myrow = mysql_fetch_array($res);
        $Amount = $myrow["AMOUNT"];
        list($year, $month, $day) = explode("-", $myrow["ENTRY_DT"]);
        $orderdate = my_format_date($day, $month, $year);
        
        list($year, $month, $day) = explode("-", $myrow["EXPIRY_DT"]);
        $expirydate = my_format_date($day, $month, $year);
        
        if ($myrow["CURTYPE"] == 'DOL') {
            $paytype = "US $";
        } 
        else {
            $paytype = "RS.";
        }
        $ser_name = $serObj->getServiceName($myrow["SERVICEMAIN"]);
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
        $smarty->assign("EXPIRYDATE", $expirydate);
        $smarty->assign("BILL_NAME", $myrow["USERNAME"]);
        $smarty->assign("PAYTYPE", $paytype);
        $smarty->assign("PROFILEID", $profileid);
        $smarty->assign("CHECKSUM", $Merchant_Param);
        $data = authenticated();
        $smarty->assign("USERNAME", $data[USERNAME]);
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
else if ($Checksum == "true" && $AuthDesc == "B") {
    $ret = $membershipObj->updtOrder($Order_Id, $dup, $AuthDesc);
    
    list($part1, $part2) = explode("-", $Order_Id);
    $sql = "SELECT * from billing.ORDERS where ID = '$part2' and ORDERID = '$part1'";
    $res = mysql_query_decide($sql);
    
    if (mysql_num_rows($res)) {
        $myrow = mysql_fetch_array($res);
        
        list($year, $month, $day) = explode("-", $myrow["ENTRY_DT"]);
        $orderdate = my_format_date($day, $month, $year);
        
        list($year, $month, $day) = explode("-", $myrow["EXPIRY_DT"]);
        $expirydate = my_format_date($day, $month, $year);
        
        if ($myrow["CURTYPE"] == "DOL") {
            $paytype = "US $";
        } 
        else {
            $paytype = "RS.";
        }
        $ser_name = $serObj->getServiceName($myrow["SERVICEMAIN"]);
        $QueryString = '';
        foreach ($ser_name as $Key => $Value) {
            $QueryString.= ',' . $Value[NAME];
        }
        $smarty->assign("MEMTYPE", substr($QueryString, 1));

        $service_main_details = getServiceDetails($myrow["SERVICEMAIN"]);
        $smarty->assign("PERIOD", $service_main_details["DURATION"]);
        $smarty->assign("AMOUNT", $Amount);
        $smarty->assign("ORDERID", $Order_Id);
        $smarty->assign("PROFILEID", $profileid);
        $smarty->assign("ORDERDATE", $orderdate);
        $smarty->assign("EXPIRYDATE", $expirydate);
        $smarty->assign("BILL_NAME", $myrow["USERNAME"]);
        $smarty->assign("PAYTYPE", $paytype);
        $smarty->assign("CHECKSUM", $Merchant_Param);
        $data = authenticated();
        $smarty->assign("USERNAME", $data[USERNAME]);
        $smarty->assign("HEAD", $smarty->fetch("revamp_head.htm"));
        $smarty->assign("SUBHEADER", $smarty->fetch("subheader.htm"));
        $smarty->assign("TOPLEFT", $smarty->fetch("topleft.htm"));
        $smarty->assign("LEFTPANEL", $smarty->fetch("leftpanelnew.htm"));
        $smarty->assign("FOOT", $smarty->fetch("footer.htm"));
        $smarty->assign("SUBFOOTER", $smarty->fetch("subfooternew.htm"));
        
        if ($ret) $smarty->display("pg/orderreceipt_delayed.htm");
        else {
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
    } 
    else {
        $smarty->assign("CHECKSUM", $Merchant_Param);
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
} 
else if ($Checksum == "true" && $AuthDesc == "N") {
    $ret = $membershipObj->updtOrder($Order_Id, $dup, $AuthDesc);
    
    $smarty->assign("CHECKSUM", $Merchant_Param);
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
else {
    echo "<br>Security Error. Illegal access detected";
}
?>
