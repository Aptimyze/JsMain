<?php

include ("../connect.inc");
include ("functions.php");
connect_db();

$data = authenticated($checksum);
$Order_Id = $orderid;

$sql = "insert into billing.ITZ (ORDERID,ACTIONTYPE,RESPONSECODE,PRODUCTCOST,TRANSACTIONID,DESCRIPTION) values ('$Order_Id','$actiontype','$responsecode','$productcost','$transactionid','$description')";
mysql_query_decide($sql);

if ($actiontype == 'redirect') {
    if ($responsecode == 0) {
        $dup = false;
        $status = "Y";
        $ret = updtOrder($Order_Id, $dup, $status);
        
        if (!$dup && $ret) start_service($Order_Id);
        
        list($part1, $part2) = explode("-", $Order_Id);
        $sql = "SELECT * from billing.ORDERS where ID = '$part2' and ORDERID = '$part1'";
        $res = mysql_query_decide($sql);
        
        if (mysql_num_rows($res) > 0) {
            $myrow = mysql_fetch_array($res);
            $Amount = $myrow["AMOUNT"];
            list($year, $month, $day) = explode("-", $myrow["ENTRY_DT"]);
            $orderdate = my_format_date($day, $month, $year);
            
            list($year, $month, $day) = explode("-", $myrow["EXPIRY_DT"]);
            $expirydate = my_format_date($day, $month, $year);
            $paytype = "RS.";
            
            if (strstr($myrow["SERVICEMAIN"], "P")) $smarty->assign("MEMTYPE", "e-Rishta");
            elseif (strstr($myrow["SERVICEMAIN"], "D")) $smarty->assign("MEMTYPE", "e-Classifieds");
            elseif (strstr($myrow["SERVICEMAIN"], "C")) $smarty->assign("MEMTYPE", "e-Value Pack");
            
            if (strstr($myrow["SERVEFOR"], "V")) $smarty->assign("VOICEMAIL", "Y");
            if (strstr($myrow["SERVEFOR"], "H")) $smarty->assign("HOROSCOPE", "Y");
            if (strstr($myrow["SERVEFOR"], "K")) $smarty->assign("KUNDLI", "Y");
            if (strstr($myrow["SERVEFOR"], "B")) $smarty->assign("BOLDLISTING", "Y");
            if ($myrow["SERVEFOR"] == '') $smarty->assign("NOMEMBERSHIP", "Y");
            
            if ($myrow["SERVICEMAIN"] == 'P2' || $myrow["SERVICEMAIN"] == 'P3' || $myrow["SERVICEMAIN"] == 'P4') $smarty->assign("sid", "10");
            else $smarty->assign("sid", "12");
            
            $service_main_details = getServiceDetails($myrow["SERVICEMAIN"]);
            $smarty->assign("PERIOD", $service_main_details["DURATION"]);
            $smarty->assign("AMOUNT", $Amount);
            $smarty->assign("ORDERID", $Order_Id);
            $smarty->assign("ORDERDATE", $orderdate);
            $smarty->assign("EXPIRYDATE", $expirydate);
            $smarty->assign("BILL_NAME", $myrow["USERNAME"]);
            $smarty->assign("PAYTYPE", $paytype);
            
            $smarty->assign("CHECKSUM", $Merchant_Param);
            $smarty->assign("HEAD", $smarty->fetch("headnew.htm"));
            $smarty->assign("SUBHEADER", $smarty->fetch("subheader.htm"));
            $smarty->assign("TOPLEFT", $smarty->fetch("topleft.htm"));
            $smarty->assign("LEFTPANEL", $smarty->fetch("leftpanelnew.htm"));
            $smarty->assign("FOOT", $smarty->fetch("foot.htm"));
            $smarty->assign("SUBFOOTER", $smarty->fetch("subfooternew.htm"));
            
            set_subscription_cookie($myrow['PROFILEID']);
            payment_thanks_things_to_do($myrow['PROFILEID'], $myrow['SET_ACTIVATE']);
            
            $smarty->display("pg/orderreceipt.htm");
        } 
        else {
            $smarty->assign("HEAD", $smarty->fetch("headnew.htm"));
            $smarty->assign("SUBHEADER", $smarty->fetch("subheader.htm"));
            $smarty->assign("TOPLEFT", $smarty->fetch("topleft.htm"));
            $smarty->assign("LEFTPANEL", $smarty->fetch("leftpanelnew.htm"));
            $smarty->assign("FOOT", $smarty->fetch("foot.htm"));
            $smarty->assign("SUBFOOTER", $smarty->fetch("subfooternew.htm"));
            $smarty->display("pg/ordererror.htm");
        }
    } 
    else
    {
        $smarty->assign("HEAD", $smarty->fetch("headnew.htm"));
        $smarty->assign("SUBHEADER", $smarty->fetch("subheader.htm"));
        $smarty->assign("TOPLEFT", $smarty->fetch("topleft.htm"));
        $smarty->assign("LEFTPANEL", $smarty->fetch("leftpanelnew.htm"));
        $smarty->assign("FOOT", $smarty->fetch("foot.htm"));
        $smarty->assign("SUBFOOTER", $smarty->fetch("subfooternew.htm"));
        $smarty->display("pg/ordererror.htm");
    }
} 
else
{
    $smarty->assign("HEAD", $smarty->fetch("headnew.htm"));
    $smarty->assign("SUBHEADER", $smarty->fetch("subheader.htm"));
    $smarty->assign("TOPLEFT", $smarty->fetch("topleft.htm"));
    $smarty->assign("LEFTPANEL", $smarty->fetch("leftpanelnew.htm"));
    $smarty->assign("FOOT", $smarty->fetch("foot.htm"));
    $smarty->assign("SUBFOOTER", $smarty->fetch("subfooternew.htm"));
    $smarty->display("pg/ordererror.htm");
}
?>
