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
//$db_slave = connect_slave();

$todayDate =date("Y-m-d");

//************** Variable discount cleanup process Starts *************

$sql ="TRUNCATE TABLE billing.VARIABLE_DISCOUNT_BACKUP_1DAY";
mysql_query($sql,$db) or logError("Due to a temporary problem your request could not be processed.");

$sql ="INSERT INTO billing.VARIABLE_DISCOUNT_BACKUP_1DAY SELECT * FROM billing.VARIABLE_DISCOUNT";
mysql_query($sql,$db) or logError("Due to a temporary problem your request could not be processed.");

$sql ="TRUNCATE TABLE billing.VARIABLE_DISCOUNT";
mysql_query($sql,$db) or logError("Due to a temporary problem your request could not be processed.");

// Only edate check is added to include current available discounts and future discounts
/* Future discounts : those discounts whose start date is greater than todays date */ 
$sql ="INSERT IGNORE INTO billing.VARIABLE_DISCOUNT SELECT * FROM billing.VARIABLE_DISCOUNT_BACKUP_1DAY WHERE EDATE>='$todayDate'";
mysql_query($sql,$db) or logError("Due to a temporary problem your request could not be processed.");

// Maintains log for the expired discounts
$sql ="INSERT INTO billing.VARIABLE_DISCOUNT_LOG SELECT * FROM billing.VARIABLE_DISCOUNT_BACKUP_1DAY WHERE EDATE<'$todayDate'";
mysql_query($sql,$db) or logError("Due to a temporary problem your request could not be processed.");

// Maintain records from VARIABLE_DISCOUNT_OFFER_DURATION for the expired discounts in VARIABLE_DISCOUNT_OFFER_DURATION_LOG
$sql ="INSERT INTO billing.VARIABLE_DISCOUNT_OFFER_DURATION_LOG SELECT a.*, b.EDATE FROM billing.VARIABLE_DISCOUNT_OFFER_DURATION a, billing.VARIABLE_DISCOUNT_BACKUP_1DAY b where a.PROFILEID=b.PROFILEID AND b.EDATE<'$todayDate'";
mysql_query($sql,$db) or logError("Due to a temporary problem your request could not be processed.");

// Delete records from VARIABLE_DISCOUNT_OFFER_DURATION for the expired discounts
$sql ="delete billing.VARIABLE_DISCOUNT_OFFER_DURATION.* from billing.VARIABLE_DISCOUNT_OFFER_DURATION, billing.VARIABLE_DISCOUNT_BACKUP_1DAY b where billing.VARIABLE_DISCOUNT_OFFER_DURATION.PROFILEID=b.PROFILEID AND b.EDATE<'$todayDate'";
mysql_query($sql,$db) or logError("Due to a temporary problem your request could not be processed.");

//*************  Variable discount cleanup process Ends   ****************

?>
