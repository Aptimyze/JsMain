<?php
//Connection at JSDB
/*include_once("../../web/profile/connect_db.php");
$db_js = connect_db();*/
$db_js = mysql_connect("ser2.jeevansathi.com","user_dialer","DIALlerr") or die("Unable to connect to js server");
mysql_query('set session wait_timeout=50000',$db_js);

//Connection at DialerDB
$db_dialer = mssql_connect("dailer.jeevansathi.com","easy","G0dblessyou") or die("Unable to connect to dialer server");

$squery1 = "SELECT Profile_ID,Last_call_date,Last_call_time,Last_agent_name FROM easy.dbo.ct_FTA_Revamp2 WHERE DATEDIFF(day,Last_call_date,getdate())<1";
$sresult1 = mssql_query($squery1,$db_dialer) or logerror($squery1,$db_dialer);
while($srow1 = mssql_fetch_array($sresult1))
{
	$proid = $srow1["Profile_ID"];	
 	$last_call_time = date("Y-m-d",strtotime($srow1["Last_call_date"]));
	$last_call_time .= " ".date("H:i:s",strtotime($srow1["Last_call_time"]));
	$executive = $srow1["Last_agent_name"];
	@mysql_ping($db_js);
	$sql_vd="insert ignore incentive.FTA_DATA (PROFILEID,CALLED_DATE,EXECUTIVE) values ('$proid','$last_call_time','$executive')";
	$res_vd = mysql_query($sql_vd,$db_js) or die("$sql_vd".mysql_error($db_js));
}
?>
