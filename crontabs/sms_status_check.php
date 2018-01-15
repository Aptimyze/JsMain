<?php
/**	
	Fills the current delivery status of messages in MOB_VERIFY 
*/

$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
chdir($_SERVER["DOCUMENT_ROOT"]."/profile");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect.inc");
include(JsConstants::$docRoot."/commonFiles/sms_inc.php");
$noOfRecordsToBeFetchedAtATime = 50000;

$mysqlObj=new Mysql;
$dbM=connect_db();
$mysqlObj->executeQuery("set session wait_timeout=10000",$dbM);

$date = date("Y-m-d");
$date2 = strtotime ( '-3 day' , strtotime ( $date ) ) ;
$date2 = date ( 'Y-m-j' , $date2 );

$count = "SELECT COUNT(*) AS COUNT FROM MIS.MOB_VERIFY WHERE DATE_OF_ENTRY='$date2'";
$result = mysql_query($count,$dbM) or die(mysql_error($dbM));
if($rowResult=mysql_fetch_array($result))
{
	$noOfResults = $rowResult['COUNT'];
}

$start = 0;
while($start < $noOfResults)
{
	unset($guid);
	$sql="SELECT GUID,MESSAGE_TYPE,PROFILEID FROM MIS.MOB_VERIFY WHERE DATE_OF_ENTRY='$date2' LIMIT $start,$noOfRecordsToBeFetchedAtATime";
	$res = mysql_query($sql,$dbM) or die(mysql_error($dbM));

	$start += $noOfRecordsToBeFetchedAtATime;

	while($row=mysql_fetch_array($res))
		//$guid[$row['MESSAGE_TYPE']][$row['PROFILEID']]=$row['GUID'];
		$guid[$row['MESSAGE_TYPE']][]=$row['GUID'];

	//getResponse($guid,$dbM);
	if(is_array($guid))
	{
		foreach($guid as $k=>$v)
		{
			getResponse($v,$dbM,$k);
		}
	}
	/*
	unset($guid);
	unset($date);
	unset($date2);
	unset($v);
	unset($k);
	include("mobileno_verif.php");
	*/
}
	passthru(JsConstants::$php5path." -q mobileno_verif.php");
?>
