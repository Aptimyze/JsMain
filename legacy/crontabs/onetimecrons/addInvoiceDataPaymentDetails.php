<?php 
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");

include('../connect.inc');
$db = connect_db();
$db_slave = connect_slave();

function get_service_type($serviceid)
{
	$serviceid = str_replace("'","",$serviceid);

	$string_length = strlen($serviceid);
	$string = substr($serviceid,0,$string_length);
	if($string)
	{
		while(!ctype_alpha($string))
		{
			$string_length--;
			$string = substr($string,0,$string_length);
		}
	}
	return $string;
}

$sql = "SELECT * FROM billing.PURCHASES ORDER BY ENTRY_DT DESC";
$res = mysql_query($sql,$db_slave) or die(mysql_error($db_slave));
while($row = mysql_fetch_array($res)){
	
	$billid = $row['BILLID'];
	$entry_dt = $row['ENTRY_DT'];
	$serviceid = $row['SERVICEID'];
	$entry_dt = strtotime($entry_dt);

	$sql2 = "SELECT * FROM billing.PAYMENT_DETAIL WHERE BILLID=$billid";
	$res2 = mysql_query($sql2,$db_slave) or die(mysql_error($db_slave));

	while($row2 = mysql_fetch_array($res2)){

		$receiptid = $row2['RECEIPTID'];

		// Invoice generation Logic
		$billyear = (date('m', strtotime($row2['ENTRY_DT']))<'04') ? date('y',strtotime('-1 year', strtotime($row2['ENTRY_DT']))) : date('y', strtotime($row2['ENTRY_DT']));
	    $billid_toassign = $billyear;
	    $d = $billid_toassign + 1;
	    if ($d < 10) {
	    	$d = "0" . $d;
	    }
	    $billid_toassign.= $d;
	    $serviceid_arr = @explode(",", $serviceid);
	    for ($i = 0; $i < count($serviceid_arr); $i++) {
	    	$service_type[] = get_service_type($serviceid_arr[$i]);
	    }
		$sid = end($serviceid_arr);
	    if (@in_array("P", $service_type)) $billid_toassign.= "-F";
	    if (@in_array("D", $service_type)) $billid_toassign.= "-D";
	    if (@in_array("C", $service_type)) $billid_toassign.= "-C";
	    if (strlen($serviceid) == 2) {
	        if (strstr($sid, '2')) $billid_toassign.= "02";
	        if (strstr($sid, '3')) $billid_toassign.= "03";
	        if (strstr($sid, '4')) $billid_toassign.= "04";
	        if (strstr($sid, '5')) $billid_toassign.= "05";
	        if (strstr($sid, '6')) $billid_toassign.= "06";
	    } 
	    else $billid_toassign.= "12";
	    $no_zero = 6 - strlen($billid);
	    for ($i = 0; $i < $no_zero; $i++) $billid_toassign.= "0";
	    $billid_toassign.= $billid;
	    $invNo = $billid_toassign;

	    $sql3 = "UPDATE billing.PAYMENT_DETAIL SET INVOICE_NO='$invNo' WHERE BILLID='$billid'";
		$res3 = mysql_query($sql3,$db) or die(mysql_error($db));
		unset($invNo,$billid,$receiptid,$serviceid);
	}
}

?>
