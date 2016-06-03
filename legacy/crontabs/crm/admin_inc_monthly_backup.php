<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

/**********************************************************************************\
                FILE NAME       :       admin_inc.php
                FILES INCLUDED  :       connect.inc
		FUNCTION DEFINED:	time_day(date,int)
                DETAILS         :       Allot the profiles to the CRM department for tele sales
					on monthly basis	 
\**********************************************************************************/

//ini_set("memory_limit","128M");
include("../connect.inc");

$db=connect_db();

$sql="UPDATE billing.PURCHASES,incentive.MAIN_ADMIN SET incentive.MAIN_ADMIN.STATUS='P' WHERE billing.PURCHASES.ENTRY_DT>='2006-03-31' AND billing.PURCHASES.STATUS='DONE' AND billing.PURCHASES.PROFILEID=incentive.MAIN_ADMIN.PROFILEID";
mysql_query($sql,$db) or die("$sql".mysql_error());//logError($sql);

$sql="CREATE TABLE incentive.MAIN_ADMIN_APR (
 `ID` int(11) NOT NULL auto_increment,
 `PROFILEID` int(11) NOT NULL default '0',
 `CONTACTS_ACC` int(11) NOT NULL default '0',
 `CONTACTS_RCV` int(11) NOT NULL default '0',
 `ALLOT_TIME` datetime NOT NULL default '0000-00-00 00:00:00',
 `CLAIM_TIME` datetime NOT NULL default '0000-00-00 00:00:00',
 `ALLOTED_TO` char(40) NOT NULL default '',
 `STATUS` char(2) NOT NULL default '',
 `ALTERNATE_NO` char(15) NOT NULL default '',
 `FOLLOWUP_TIME` datetime NOT NULL default '0000-00-00 00:00:00',
 `MODE` char(2) NOT NULL default '',
 `CONVINCE_TIME` datetime NOT NULL default '0000-00-00 00:00:00',
 `COMMENTS` char(100) NOT NULL default '',
 `RES_NO` char(40) NOT NULL default '0',
 `MOB_NO` char(40) NOT NULL default '0',
 `EMAIL` char(100) NOT NULL default '',
 `WILL_PAY` char(1) NOT NULL default '',
 `TIMES_TRIED` int(11) NOT NULL default '0',
 `ORDERS` char(1) NOT NULL default '',
 PRIMARY KEY  (`ID`),
 UNIQUE KEY `UNIQUE3` (`PROFILEID`),
 KEY `ALLOTED_TO` (`ALLOTED_TO`,`STATUS`),
 KEY `ALLOT_TIME` (`ALLOT_TIME`)
) TYPE=MyISAM
";
mysql_query($sql,$db) or die("1".mysql_error());

$sql="ALTER TABLE incentive.MAIN_ADMIN_APR DISABLE KEYS";
mysql_query($sql,$db) or die("$sql".mysql_error());//logError($sql);

$sql="INSERT INTO incentive.MAIN_ADMIN_APR SELECT * FROM incentive.MAIN_ADMIN";
mysql_query($sql,$db) or die("1".mysql_error());

$sql="ALTER TABLE incentive.MAIN_ADMIN_APR ENABLE KEYS";
mysql_query($sql,$db) or die("$sql".mysql_error());//logError($sql);

$sql="DELETE FROM incentive.MAIN_ADMIN WHERE CONVINCE_TIME=0 AND STATUS NOT IN ('C','F','P')";
//mysql_query($sql,$db) or die("$sql".mysql_error());//logError($sql);

?>
