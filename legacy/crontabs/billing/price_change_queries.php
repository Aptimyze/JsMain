<?php
echo $msg = "Start time #".@date('H:i:s');echo "\n";

//Connection at JSDB
$db_js = mysql_connect("master.js.jsb9.net","user","CLDLRTa9") or die("Unable to connect to js server");
mysql_query('set session wait_timeout=100000,interactive_timeout=10000,net_read_timeout=10000',$db_js);

$query1 = "UPDATE billing.SERVICES SET PRICE_RS_TAX = '5500' WHERE SERVICEID = 'P6'";
mysql_query($query1,$db_js) or die($query1.mysql_error($db_js));
$query2 = "UPDATE billing.SERVICES SET PRICE_RS_TAX = '6900' WHERE SERVICEID = 'C6'";
mysql_query($query2,$db_js) or die($query2.mysql_error($db_js));
$query3 = "UPDATE billing.SERVICES SET PRICE_RS_TAX = '12900' WHERE SERVICEID = 'CL'";
mysql_query($query3,$db_js) or die($query3.mysql_error($db_js));
$query4 = "UPDATE billing.SERVICES SET PRICE_RS_TAX = '7900' WHERE SERVICEID = 'NCP6'";
mysql_query($query4,$db_js) or die($query4.mysql_error($db_js));
$query5 = "UPDATE billing.SERVICES SET PRICE_RS_TAX = '35900' WHERE SERVICEID = 'X6'";
mysql_query($query5,$db_js) or die($query5.mysql_error($db_js));

include_once("/var/www/html/lib/model/enums/Membership.enum.class.php");
$membershipKeyArray =VariableParams::$membershipKeyArray;
foreach($membershipKeyArray as $key=>$keyVal)
	memcache_call($keyVal,"");

echo "\n";
echo $msg.="End time :".@date('H:i:s');
$to="vibhor.garg@jeevansathi.com";
$sub="New Pricing Updated <EOM>";
$from="From:vibhor.garg@jeevansathi.com";
mail($to,$sub,$msg,$from);
?>
