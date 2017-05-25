<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

include("connect.inc");
connect_db();

$ts=time();
$ts-=24*60*60;
$today=date("Y-m-d",$ts);
$st_date=$today." 00:00:00";
$end_date=$today." 23:59:59";

//$st_date="2007-04-01";
//$end_date="2007-04-01 23:59:59";

/****
	below query to run for first time
****/
/*
$sql="INSERT INTO MIS.SOURCE_HITS(ENTRY_DT,SOURCEID,COUNT) SELECT left(Date,10) as dd,SourceID,COUNT(*) FROM MIS.HITS WHERE 1 GROUP BY dd,SourceID";
mysql_query($sql) or die(mysql_error());
$sql="UPDATE MIS.SOURCE_HITS a, MIS.SOURCE b SET a.SOURCEGP=b.GROUPNAME WHERE a.SOURCEID=b.SourceID AND a.SOURCEGP=''";
mysql_query($sql) or die(mysql_error());
*/

/****
	queries to run daily by cron
****/

$sql="INSERT INTO MIS.SOURCE_HITS(ENTRY_DT,SOURCEID,COUNT) SELECT left(Date,10) as dd,SourceID,COUNT(*) FROM MIS.HITS WHERE Date BETWEEN '$st_date' AND '$end_date' GROUP BY dd,SourceID";
mysql_query($sql) or logError($sql);//die(mysql_error());

$sql="UPDATE MIS.SOURCE_HITS a, MIS.SOURCE b SET a.SOURCEGP=b.GROUPNAME WHERE a.SOURCEID=b.SourceID AND a.SOURCEGP=''";
mysql_query($sql) or logError($sql);//die(mysql_error());
?>
