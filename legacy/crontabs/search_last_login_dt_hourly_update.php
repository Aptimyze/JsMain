<?php 
$curFilePath = dirname(__FILE__)."/"; 
ini_set('max_execution_time','0');
ini_set('memory_limit',-1);
chdir(dirname(__FILE__));
include(jsConstants::$cronDocRoot."/crontabs/connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect_functions.inc");

$dbM=connect_db();
mysql_query("set session wait_timeout=10000",$dbM);

$dbS = connect_slave();
mysql_query("set session wait_timeout=10000",$dbS);
$sql = "SET SESSION group_concat_max_len = 1000000;";
mysql_query($sql,$dbS) or mysql_error1(mysql_error($dbS).$sql);

$dt = date("Y-m-d");

$tableArr = array("SEARCH_FEMALE","SEARCH_MALE");
$chunkSize = 1000;

foreach($tableArr as $tablek=>$table)
{
	$sql = "SELECT GROUP_CONCAT( DISTINCT A.PROFILEID ORDER BY A.PROFILEID DESC SEPARATOR ',') AS PID FROM test.JPROFILE_FOR_DUPLICATION A , newjs.$table B WHERE A.LAST_LOGIN_DT='$dt' AND B.LAST_LOGIN_DT<'$dt' AND A.PROFILEID=B.PROFILEID";
	//echo "\n".$sql."\n\n";
	$result=mysql_query($sql,$dbS) or mysql_error1(mysql_error($dbS).$sql);
	if($row=mysql_fetch_assoc($result))
	{
                $pid = rtrim($row["PID"],",");
                $pidArr = array_chunk(explode(",",$pid),$chunkSize);
                unset($pid);
                foreach($pidArr as $chunkKey=>$profileArr)
                {
                        $pidChunk = implode("','",$profileArr);
                        $sql = "update newjs.$table SET LAST_LOGIN_DT='$dt' WHERE PROFILEID IN ('$pidChunk')";
			//echo "\n".$sql."\n\n";
                        mysql_query($sql,$dbM) or mysql_error1(mysql_error($dbM).$sql);
                }
	}
}

$hhhh = date("H");
if(!in_array($hhhh,array(9,11,13,15,17,19,21,22,23)) || JsConstants::$whichMachine=='test')
{
        callDeleteCronBasedOnId('EXPORT','N');
}


function mysql_error1($msg)
{
        //echo $msg;die;
        mail("lavesh.rawat@jeevansathi.com,lavesh.rawat@gmail.com","search_last_login_dt_hourly_update.php",$msg);
        exit;
}
