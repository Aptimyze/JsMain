<?php 
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");

chdir(dirname(__FILE__));
include("../connect.inc");
$db = connect_db();
$db_slave = connect_slave();

$sql="SELECT s.BILLID as BILL_ID, p.USERNAME as USER_NAME, s.PROFILEID as PROFILE_ID, s.SERVICEID as SERVICE_ID, s.SERVEFOR AS SERVE_FOR FROM billing.PURCHASES p, billing.SERVICE_STATUS s WHERE p.BILLID=s.BILLID AND p.PROFILEID=s.PROFILEID AND p.STATUS='DONE' AND p.ENTRY_DT>='2015-07-10 00:00:00' AND p.ENTRY_DT<='2016-06-25 00:00:00' AND p.SERVICEID LIKE '%NCP%' AND s.SERVICEID LIKE '%C%' AND s.ACTIVE='Y' AND s.ACTIVATED='Y'";
$resj = mysql_query($sql,$db_slave) or die(mysql_error($db_slave));
while($rowj = mysql_fetch_array($resj)){
	//print_r($rowj);
	if ($rowj['SERVE_FOR'] == 'F,D') {
    	$sql_update="UPDATE newjs.JPROFILE SET SUBSCRIPTION=REPLACE(SUBSCRIPTION, 'F,D', 'F,D,N') WHERE PROFILEID=".$rowj['PROFILE_ID']." AND USERNAME='".$rowj['USER_NAME']."'";
    	mysql_query($sql_update,$db);
    	$sql_update2="UPDATE billing.SERVICE_STATUS SET SERVEFOR=REPLACE(SERVEFOR, 'F,D', 'F,D,N') WHERE BILLID=".$rowj['BILL_ID']." AND SERVICEID='".$rowj['SERVICE_ID']."' AND PROFILEID=".$rowj['PROFILE_ID'];
    	mysql_query($sql_update2,$db);
	} else if ($rowj['SERVE_FOR'] == 'D,F') {
		$sql_update="UPDATE newjs.JPROFILE SET SUBSCRIPTION=REPLACE(SUBSCRIPTION, 'D,F', 'D,F,N') WHERE PROFILEID=".$rowj['PROFILE_ID']." AND USERNAME='".$rowj['USER_NAME']."'";
		mysql_query($sql_update,$db);
		$sql_update2="UPDATE billing.SERVICE_STATUS SET SERVEFOR=REPLACE(SERVEFOR, 'D,F', 'D,F,N') WHERE BILLID=".$rowj['BILL_ID']." AND SERVICEID='".$rowj['SERVICE_ID']."' AND PROFILEID=".$rowj['PROFILE_ID'];
		mysql_query($sql_update2,$db);
	}
    //print_r(array($sql_update))."\n".PHP_EOL;
    //print_r(array($sql_update2))."\n".PHP_EOL;
}

?>
