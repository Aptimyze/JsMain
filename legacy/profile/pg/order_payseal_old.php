<?php
include ("../connect.inc");
include ("functions.php");
connect_db();

$ip = FetchClientIP();

if ($data = authenticated($checksum)) {
    $profileid = $data["PROFILEID"];
    
    if ($checkout) {
        $notype = 0;
        if ($type == 'RS') {
            $Merchant_Id = "00001673";
            $currency = "INR";
            $return_url = "http://www.jeevansathi.com/jspellhtml2k4/SFAResponse.jsp";
        } 
        elseif ($type == 'DOL') {
            $Merchant_Id = "00001712";
            $currency = "USD";
            $return_url = "http://www.jeevansathi.com/jspellhtml2k4/SFAResponse_dol.jsp";
        } 
        else {
            $notype = 1;
        }
        $ORDER = newOrder($profileid, $paymode, $type, $total, $service_str, $service_main, $discount, $setactivate, 'PAYSEAL');
        
        if (!$ORDER || $notype == 1) {
            $smarty->assign("CHECKSUM", $checksum);
            $smarty->assign("HEAD", $smarty->fetch("headnew.htm"));
            $smarty->assign("SUBHEADER", $smarty->fetch("subheader.htm"));
            $smarty->assign("TOPLEFT", $smarty->fetch("topleft.htm"));
            $smarty->assign("LEFTPANEL", $smarty->fetch("leftpanelnew.htm"));
            $smarty->assign("FOOT", $smarty->fetch("foot.htm"));
            $smarty->assign("SUBFOOTER", $smarty->fetch("subfooternew.htm"));
            
            $smarty->display("pg/ordererror.htm");
            die;
        }
        
        $service = $ORDER["SERVICE_MAIN"] . "," . $ORDER["ADDON_SERVICE"];
        $service = getServiceName($service);
        
        $smarty->assign("MERCHANTID", $Merchant_Id);
        $smarty->assign("ACTIVE", $ORDER["ACTIVE"]);
        if ($type == "RS") $smarty->assign("AMOUNT", ($ORDER["AMOUNT"]));
        else $smarty->assign("AMOUNT", $ORDER["AMOUNT"]);
        $smarty->assign("ORDERID", $ORDER["ORDERID"]);
        $smarty->assign("SERVICE", $service);
        $smarty->assign("CURRENCY", $currency);
        
        $smarty->assign("RETURN_METHOD", "POST");
        $smarty->assign("IMAGE_URL", "http://www.jeevansathi.com/profile/imagesnew/Matrimonial.gif");
        $smarty->assign("RETURN", $return_url);
        $smarty->display("pg/payseal_redirect.htm");
    } 
    else {
        $smarty->assign("CHECKSUM", $checksum);
        $smarty->assign("HEAD", $smarty->fetch("headnew.htm"));
        $smarty->assign("SUBHEADER", $smarty->fetch("subheader.htm"));
        $smarty->assign("TOPLEFT", $smarty->fetch("topleft.htm"));
        $smarty->assign("LEFTPANEL", $smarty->fetch("leftpanelnew.htm"));
        $smarty->assign("FOOT", $smarty->fetch("foot.htm"));
        $smarty->assign("SUBFOOTER", $smarty->fetch("subfooternew.htm"));
        
        $smarty->display("pg/ordererror.htm");
    }
} 
else {
    TimedOut();
}
?>
