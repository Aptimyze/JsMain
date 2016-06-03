<?php
include_once ("../connect.inc");

//require("functions_transecute.php");
include_once ("functions.php");
connect_db();
include_once ($_SERVER['DOCUMENT_ROOT'] . "/classes/Services.class.php");
include_once ($_SERVER['DOCUMENT_ROOT'] . "/classes/Membership.class.php");
$serObj = new Services;
$membershipObj = new Membership;

$req = 'cmd=_notify-validate';

$message_str = '';

foreach ($_POST as $key => $value) {
    $message_str.= $key . " => " . $value . ", ";
    $value = urlencode(stripslashes($value));
    $req.= "&$key=$value";
}

$header.= "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header.= "Content-Type: application/x-www-form-urlencoded\r\n";
$header.= "Content-Length: " . strlen($req) . "\r\n\r\n";
$fp = fsockopen('www.paypal.com', 80, $errno, $errstr, 30);

$item_name = $_POST['item_name'];
$item_number = $_POST['item_number'];
$payment_status = $_POST['payment_status'];
$payment_amount = $_POST['mc_gross'];
$payment_currency = $_POST['mc_currency'];
$txn_id = $_POST['txn_id'];
$receiver_email = $_POST['receiver_email'];
$payer_email = $_POST['payer_email'];

if (!$fp) {
    mail("vibhor.garg@jeevansathi.com", "PAYPAL HTTP Error - IPN", "error occured" . $message_str);
} 
else {
    fputs($fp, $header . $req);
    while (!feof($fp)) {
        $res = fgets($fp, 1024);
        if (strcmp($res, "VERIFIED") == 0) {
            if ($payment_status == "Completed") {
                if ($receiver_email == "paypal@jeevansathi.com" || $receiver_email == "shyam.kumar@jeevansathi.com") {
                    $membershipObj->log_payment_status($item_number, 'S', 'PAYPAL', $message_str);
                    $Order_Id = $item_number;
                    $dup = false;
                    $status = "Y";
                    $ret = $membershipObj->updtOrder($Order_Id, $dup, $status);
                    
                    if (!$dup && $ret) $membershipObj->startServiceOrder($Order_Id);
                } 
                else {
                    mail("vibhor.garg@jeevansathi.com,alok@jeevansathi.com", "PAYPAL Payment gone wrong", "Payment Gone To : $receiver_email\n" . $message_str);
                }
            } 
            else {
                if ($payment_status == "Pending") {
                    $membershipObj->log_payment_status($item_number, 'P', 'PAYPAL', $message_str);
                } 
                elseif ($payment_status == "Failed") {
                    $membershipObj->log_payment_status($item_number, 'F', 'PAYPAL', $message_str);
                }
            }
        } 
        else if (strcmp($res, "INVALID") == 0) {
            $membershipObj->log_payment_status($item_number, 'U', 'PAYPAL', $message_str);
        }
    }
    fclose($fp);
}
?>
