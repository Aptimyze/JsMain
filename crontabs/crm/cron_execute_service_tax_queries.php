<?php
echo $msg = "Start time #".@date('H:i:s');echo "\n";

//Connection at JSDB
$db_js = mysql_connect("master.js.jsb9.net","user","CLDLRTa9") or die("Unable to connect to js server");
mysql_query('set session wait_timeout=100000,interactive_timeout=10000,net_read_timeout=10000',$db_js);

$query1 = "UPDATE billing.SERVICES SET PRICE_RS=ROUND(PRICE_RS_TAX/1.14,2)";
mysql_query($query1,$db_js) or die($query1.mysql_error($db_js));

$query2 = "UPDATE billing.REV_MASTER SET SERVICE_TAX = ROUND(DUEAMOUNT*(14/100),2), DUEAMOUNT=DUEAMOUNT+SERVICE_TAX, TOTAL_AMT=TOTAL_AMT+SERVICE_TAX WHERE DUEAMOUNT != 0 AND TAX_RATE = 12.36";
mysql_query($query2,$db_js) or die($query2.mysql_error($db_js));

echo "\n";
echo $msg.="End time :".@date('H:i:s');
$to="vibhor.garg@jeevansathi.com";
$sub="Service Tax Query Executed.";
$from="From:vibhor.garg@jeevansathi.com";
mail($to,$sub,$msg,$from);
?>
