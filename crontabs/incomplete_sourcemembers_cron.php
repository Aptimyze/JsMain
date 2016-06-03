<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

include("connect.inc");
connect_db();

$ts=time();
$ts-=2*24*60*60;
$today=date("Y-m-d",$ts);
$st_date=$today." 00:00:00";
$end_date=$today." 23:59:59";

/* query to insert incomplete profiles marked from backened */
$sql="INSERT INTO MIS.SOURCE_MEMBERS_INCOMPLETE(ENTRY_DT,SOURCEID,COUNT) SELECT left(j.ENTRY_DT,10) as dd,j.SOURCE,COUNT(distinct j.PROFILEID) FROM newjs.JPROFILE j,jsadmin.INCOMPLETE i WHERE j.ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND j.INCOMPLETE='Y' AND j.PROFILEID=i.PROFILEID GROUP BY dd,j.SOURCE ";
mysql_query($sql) or die(mysql_error());

$sql="UPDATE MIS.SOURCE_MEMBERS_INCOMPLETE a, MIS.SOURCE b SET a.SOURCEGP=b.GROUPNAME WHERE a.SOURCEID=b.SourceID AND a.SOURCEGP=''";
mysql_query($sql) or die(mysql_error());

?>
