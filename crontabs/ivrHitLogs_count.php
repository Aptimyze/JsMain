<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");


include_once(JsConstants::$docRoot."/commonFiles/sms_inc.php");
include_once($_SERVER['DOCUMENT_ROOT']."/jsadmin/connect.inc");
$db=connect_db();

$date =date("Y-m-d");
$dateS =date("Y-m-d")." 00:00:00";
$dateE =date("Y-m-d")." 23:59:59";

$sql="SELECT `TEXT`,count(*) AS COUNT FROM newjs.IVR_HIT where `ENTRY_DT` BETWEEN '$dateS' AND '$dateE' GROUP BY TEXT";
$res=mysql_query($sql,$db) or die(mysql_error());
while($row=mysql_fetch_array($res))
{
	$text = $row['TEXT'];
	$textarr = explode("=",$text);
	$count[$textarr[1]] =$row['COUNT'];
}

$verified_count 	=$count['confirm'];
$denied_count 		=$count['denied'];
$busy_count 		=$count['busy'];
$total_count 		=$verified_count+$denied_count+$busy_count;
if($busy_count)
	$percentBusy =intval(($busy_count/$total_count)*100);

if($percentBusy>'50' || $denied_count>'300' || $total_count<'10')
{
	$mobileArr		= array("9999216910","9811637297","9971101352");
	$cellcast_mobileArr 	= array("9833274690","9819874170","9987028151");

	if($percentBusy>'60' || $total_count<'100')
		$mobileArr =array_merge($mobileArr,$cellcast_mobileArr);

	if($total_count<10)
		$message= "There has been no hit from Cellcast to Jeevansathi for phone verification on $date";
	else
		$message= "Verification alert(for $date)-> Denials:$denied_count. Busy %: $percentBusy% of calls returned as busy. Hits:$total_count";
	$profileid 	= "111";
	foreach($mobileArr as $key=>$val){ 
		$state = send_sms($message,'',$val,$profileid,'','Y');
	}
}

?>
