<?php
include_once ("../connect.inc");

include_once ("functions.php");
connect_db();

$req = 'cmd=_notify-synch';

$tx_token = $_GET['tx'];

$auth_token = "fSvi3twDPnzmAzY2DCr3C5IAl7xzOElPvVxwwLx56R8sPX6ru_XwIhjb1p4";
$req.= "&tx=$tx_token&at=$auth_token";

$header.= "POST /cgi-bin/webscr HTTP/1.0\r\n";
$header.= "Content-Type: application/x-www-form-urlencoded\r\n";
$header.= "Content-Length: " . strlen($req) . "\r\n\r\n";
$fp = fsockopen('www.paypal.com', 80, $errno, $errstr, 30);
fputs($fp, $header . $req);

$res = '';
$headerdone = false;
while (!feof($fp)) {
    $line = fgets($fp, 1024);
    if (strcmp($line, "\r\n") == 0) {
        $headerdone = true;
    } 
    else if ($headerdone) {
        $res.= $line;
    }
}
$lines = explode("\n", $res);
$keyarray = array();

for ($i = 1; $i < count($lines); $i++) {
    list($key, $val) = explode("=", $lines[$i]);
    $keyarray[urldecode($key) ] = urldecode($val);
}
$Order_Id = $keyarray['item_number'];
$smarty->assign("HEAD", $smarty->fetch("revamp_head.htm"));
$smarty->assign("SUBHEADER", $smarty->fetch("subheader.htm"));
$smarty->assign("TOPLEFT", $smarty->fetch("topleft.htm"));
$smarty->assign("LEFTPANEL", $smarty->fetch("leftpanelnew.htm"));
$smarty->assign("FOOT", $smarty->fetch("footer.htm"));
$smarty->assign("ORDERID", $Order_Id);
$smarty->assign("SUBFOOTER", $smarty->fetch("subfooternew.htm"));

$smarty->display("pg/ordererror.htm");
?>
