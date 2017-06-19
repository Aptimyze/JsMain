<?
include("connect.inc");
connect_slave();
$P2=$C2=25;
$P3=$C3=50;
$P4=$C4=75;
$P6=$C6=100;
$P9=$C9=200;
$PL=$CL=225;
$date=date('Y-m-d');
//$sql="select PROFILEID,SERVICEID,EXPIRY_DT,DATEDIFF(now(),EXPIRY_DT) from billing.SERVICE_STATUS where DATEDIFF(now(),EXPIRY_DT)<0 and SERVEFOR like 'F%' and ACTIVE='Y' and SERVICEID IN('P2','P3','P4','P6','P9','PL','C2','C3','C4','C6','C9','CL') order by EXPIRY_DT DESC";
$sql="select PROFILEID,SERVICEID from billing.SERVICE_STATUS where EXPIRY_DT>='$date' and SERVEFOR like '%F%' and ACTIVE='Y' ";
$res=mysql_query($sql) or die(mysql_error());
$profile_arr=array();
while($row=mysql_fetch_array($res))
{
	$profileid=$row[0];
	$serviceid=$$row[1];
	if(array_key_exists($profileid,$profile_arr))
	{
		$profile_arr[$profileid]+=$serviceid;
	}
	else
		$profile_arr[$profileid]=$serviceid;
}
unset($profileid);
unset($serviceid);
//print_r($profile_arr);
$date=date("Y-m-d G:i:s");
$dbm=connect_db();
foreach($profile_arr as $k=>$v)
{
	$profileid=$k;
	$serviceid=$v;
	$SQL="insert ignore into jsadmin.CONTACTS_ALLOTED(PROFILEID,ALLOTED,VIEWED,LAST_VIEWED,CREATED) values('$profileid','$serviceid',0,'$date','$date')";
	mysql_query($SQL,$dbm) or die(mysql_error($dbm));
	
}

