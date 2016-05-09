<?php
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
chdir(dirname(__FILE__));

include("../connect.inc");
$db_slave =connect_slave();
$master =connect_db();

$sqlc = "CREATE TABLE `MAIN_ADMIN_POOL_buffer` (
 `ID` int(11) NOT NULL AUTO_INCREMENT,
 `PROFILEID` int(11) NOT NULL DEFAULT '0',
 `SCORE` mediumint(6) NOT NULL DEFAULT '0',
 `ALLOTMENT_AVAIL` char(1) NOT NULL DEFAULT '',
 `TIMES_TRIED` mediumint(6) NOT NULL DEFAULT '0',
 `SOURCE` varchar(10) NOT NULL DEFAULT '',
 `ENTRY_DT` date NOT NULL DEFAULT '0000-00-00',
 `CITY_RES` varchar(5) NOT NULL DEFAULT '',
 `TOTAL_POINTS` mediumint(9) NOT NULL DEFAULT '0',
 `MTONGUE` tinyint(3) NOT NULL,
 `ATTRIBUTE_SCORE` smallint(6) NOT NULL DEFAULT '0',
 `CONV_RATE_ATTRIBUTE` float NOT NULL,
 `ANALYTIC_SCORE` smallint(6) NOT NULL,
 `CUTOFF_DT` date NOT NULL,
 PRIMARY KEY (`ID`),
 UNIQUE KEY `PROFILEID` (`PROFILEID`),
 KEY `SOURCE` (`SOURCE`),
 KEY `ENTRY_DT` (`ENTRY_DT`),
 KEY `CITY_RES` (`CITY_RES`),
 KEY `CUTOFF_DT` (`CUTOFF_DT`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1
";
mysql_query_decide($sqlc,$master) or die("$sqlc".mysql_error_js($master));

$sql="select * from incentive.MAIN_ADMIN_POOL WHERE CUTOFF_DT>='2012-01-01'";
$res=mysql_query_decide($sql,$db_slave) or die("$sql".mysql_error_js($slave));
while($row = mysql_fetch_array($res))
{
        $sql1 ="INSERT INTO MAIN_ADMIN_POOL_buffer (ID,PROFILEID,SCORE,ALLOTMENT_AVAIL,TIMES_TRIED,SOURCE,ENTRY_DT,CITY_RES,TOTAL_POINTS,MTONGUE,ATTRIBUTE_SCORE,CONV_RATE_ATTRIBUTE,ANALYTIC_SCORE,CUTOFF_DT) VALUES ('$row[ID]','$row[PROFILEID]','$row[SCORE]','$row[ALLOTMENT_AVAIL]','$row[TIMES_TRIED]','$row[SOURCE]','$row[ENTRY_DT]','$row[CITY_RES]','$row[TOTAL_POINTS]','$row[MTONGUE]','$row[ATTRIBUTE_SCORE]','$row[CONV_RATE_ATTRIBUTE]','$row[ANALYTIC_SCORE]','$row[CUTOFF_DT]')";
        mysql_query_decide($sql1,$master) or die("$sql1".mysql_error_js($master));
}

$sqlr1 = "Rename table MAIN_ADMIN_POOL to MAIN_ADMIN_POOL_13Aug2015";
mysql_query_decide($sqlr1,$master) or die("$sqlr1".mysql_error_js($master));

$sqlr2 = "Rename table MAIN_ADMIN_POOL_buffer to MAIN_ADMIN_POOL";
mysql_query_decide($sqlr2,$master) or die("$sqlr2".mysql_error_js($master));

$sqld = "Delete * from MAIN_ADMIN_POOL_13Aug2015 where CUTOFF_DT<'2012-01-01'";
mysql_query_decide($sqld,$master) or die("$sqld".mysql_error_js($master));
?>
