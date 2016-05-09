<?php
include ("../connect.inc");
include ("functions.php");

connect_db();

$ip = FetchClientIP();

if (strstr($ip, ",")) {
    $ip_new = explode(",", $ip);
    $ip = $ip_new[1];
}
if ($data = authenticated($checksum)) {
    $profileid = $data["PROFILEID"];
    $smarty->assign("SERVICE_SELECTED", $service);
    $smarty->assign("SERVICE_SELECTED", $service);
    $smarty->assign("VOICEMAIL", $voicemail);
    $smarty->assign("HOROSCOPE", $horoscope);
    $smarty->assign("BOLDLISTING", $boldlisting);
    $smarty->assign("MATRI_PROFILE", $matri_profile);
    $smarty->assign("KUNDLI", $kundli);
    
    $smarty->assign("SERVICE_STR", $service_str);
    $smarty->assign("SERVICE_MAIN", $service_main);
    $smarty->assign("SER_MAIN", $ser_main);
    $smarty->assign("SER_DURATION", $ser_duration);
    $smarty->assign("TYPE", $type);
    $smarty->assign("DISCOUNT_VALUE", $discount);
    $smarty->assign("TOTAL", $total);
    $smarty->assign("PAYMODE", $paymode);
    $smarty->assign("SETACTIVATE", $setactivate);
    $smarty->assign("CHECKSUM", $checksum);
    $smarty->assign("ACTION_PATH", $ACTION_PATH);
    $smarty->assign("ADDON", $addon);
    $smarty->assign("SER_MAIN", $stp);
    $smarty->assign("DEC_AG", $dec_ag);
    
    $smarty->assign("CHECKSUM", $checksum);
    $smarty->assign("head_tab", "memberships");

    $smarty->assign("HEAD", $smarty->fetch("headnew.htm"));
    $smarty->assign("SUBHEADER", $smarty->fetch("subheader.htm"));
    $smarty->assign("TOPLEFT", $smarty->fetch("topleft.htm"));
    $smarty->assign("LEFTPANEL", $smarty->fetch("leftpanelnew.htm"));
    $smarty->assign("FOOT", $smarty->fetch("foot.htm"));
    $smarty->assign("SUBFOOTER", $smarty->fetch("subfooternew.htm"));
    
    $request_cnt = 0;
    $paymate_status = socket_function($mer_username, $mer_refno, $cus_mobno, $txn_amount, $mer_orderid, $request_cnt);
}

function socket_function($mer_username, $mer_refno, $cus_mobno, $txn_amount, $mer_orderid, $request_cnt) {
    global $smarty;
    
    $req = "mer_username=" . $mer_username . "&mer_refno=" . $mer_refno . "&cus_mobno=" . $cus_mobno . "&txn_amount=" . $txn_amount . "&mer_orderid=" . $mer_orderid;
    $host = 'www.paymate.co.in';
    $service_uri = '/connect/connect.aspx';
    $header = "Host: $host\r\n";
    $header.= "User-Agent: PHP Script\r\n";
    $header.= "Content-Type: application/x-www-form-urlencoded\r\n";
    $header.= "Content-Length: " . strlen($req) . "\r\n";
    $header.= "Connection: close\r\n\r\n";
    
    $fp = fsockopen("ssl://" . $host, 443, $errno, $errstr);
    
    if (!$fp) {
        mail("gaurav.arora@jeevansathi.com", "PAYMATE HTTP Error", "error occured" . $message_str);
    } 
    else {
        fputs($fp, "POST $service_uri  HTTP/1.1\r\n");
        fputs($fp, $header . $req);
        while (!feof($fp)) {
            $res = fgets($fp, 4096);
            
            $res = explode("&", $res);
            
            $res_temp = explode("=", $res[1]);
            
            if (strcmp($res_temp[0], "paymate_trxid") == 0) {
                $paymate_trxid = $res_temp[1];
            }
            
            unset($res_temp);
            
            $res_temp = explode("=", $res[3]);
            
            if (strcmp($res_temp[0], "paymate_status") == 0) {
                $paymate_status = $res_temp[1];
            }
            
            unset($res_temp);
            
            $res_temp = explode("=", $res[2]);
            
            if (strcmp($res_temp[0], "txn_amount") == 0) {
                $txn_amount = $res_temp[1];
            }
            
            unset($res_temp);
            
            $res_temp = explode("=", $res[0]);
            
            if (strcmp($res_temp[0], "mer_orderid") == 0) {
                $mer_orderid = $res_temp[1];
            }
        }
        fclose($fp);
    }
    
    if ($paymate_status == 400)
    {
        $smarty->assign("paymate_trxid", $paymate_trxid);
        
        $smarty->assign("cus_mobno", $cus_mobno);
        $smarty->assign("mer_orderid", $mer_orderid);
        $smarty->assign("mer_username", $mer_username);
        $smarty->assign("mer_refno", $mer_refno);
        $smarty->assign("txn_amount", $txn_amount);
        $smarty->display("pg/paymate_payment1.htm");
    }
    else
    {        
        mail("gaurav.arora@jeevansathi.com", "PAYMATE error", "error occured as paymate_status is not 400 while asking for paymatetrxid" . $message_str);
        $smarty->display("pg/ordererror.htm");
    }
}
?>
