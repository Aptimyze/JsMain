<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");


/***************************************************************************************************************
* FILE NAME     : ats_variableDiscountDailyCleanup.php 
* DESCRIPTION   : Cron script, daily scheduled
		: 1. Updates the billing.VARIABLE_DISCOUNT tables to contain only valid discount records and removes the expired records.
		: 2. Update the newjs.ANALYTICS_VARIABLE_DISCOUNT to contain the banner slabs for the profileids for which valid discount exists
*****************************************************************************************************************/

$flag_using_php5=1;
include_once("connect.inc");

$db = connect_ddl();
$db_slave = connect_slave();

$todayDate =date("Y-m-d");

// Array defined for the discount slab;
$discountSlabArr =array("0"=>"1","10"=>"2","15"=>"3","20"=>"4","25"=>"5","30"=>"6","35"=>"7","40"=>"8","45"=>"9","50"=>"10","55"=>"11","60"=>"12","11"=>"13","16"=>"14","21"=>"15","26"=>"16","31"=>"17");

// ************  Banner discount slabs cleanup process Starts  ***************

$sql ="TRUNCATE TABLE newjs.ANALYTICS_VARIABLE_DISCOUNT";
mysql_query($sql,$db) or logError("Due to a temporary problem your request could not be processed.");

$sql ="SELECT PROFILEID,DISCOUNT FROM billing.VARIABLE_DISCOUNT WHERE SDATE<='$todayDate' AND EDATE>='$todayDate'";
$res =mysql_query($sql,$db_slave) or logError("Due to a temporary problem your request could not be processed.");
while($row=mysql_fetch_array($res))
{
	$profileid 	=$row["PROFILEID"];
	$discount 	=$row["DISCOUNT"];
	$discountSlab	=$discountSlabArr[$discount];

	$sql ="INSERT IGNORE INTO newjs.ANALYTICS_VARIABLE_DISCOUNT(`PROFILEID`,`SLAB`) VALUES('$profileid','$discountSlab')";
	mysql_query($sql,$db) or logError("Due to a temporary problem your request could not be processed.");
}

//**************  Banner discount slabs cleanup process Ends  **************

?>
