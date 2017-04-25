<?php 
$flag_using_php5 = 1;
include_once("/usr/local/scripts/DocRoot.php");
include("config.php");
include("connect.inc");
include_once(JsConstants::$docRoot."/classes/Mysql.class.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
include_once(JsConstants::$docRoot."/classes/globalVariables.Class.php");

global $mysqlObjM;

$mysqlObjM = new Mysql;
$db = $mysqlObjM->connect("slave") or logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);

$dbM = $mysqlObjM->connect("master") or logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);

$sql = "SELECT DISTINCT(BOOKMARKER) AS BOOKMARKER FROM newjs.BOOKMARKS LIMIT 0,100";
$res = mysql_query($sql,$db) or die($sql.mysql_error($db));

while($row = mysql_fetch_assoc($res)){

	$pid = $row["BOOKMARKER"];
	phpService($pid,$dbM,"SHORTLIST");
	javaService($pid,$dbM);
}


function phpService($pid,$dbM,$type)
{
	$start_tm=microtime(true);
	$url = JsConstants::$siteUrl."/api/v1/chat/getRoasterData?type=".$type."&profileid=".$pid."&limit=50";
	
	$response = CommonUtility::sendCurlGetRequest($url);
	$diff=microtime(true)-$start_tm;

	$data = (Array)json_decode($response);
	$insert["pid"]=$pid;
	$insert["count"] = $data["count"];
	$insert["ids"]="";
	//print_r(expression)
	foreach($data["profiles"] as $k=>$v)
	{
		$insert["ids"] .= $k.",";
		//$insert["count"]++;
	}
	insertData($insert,1,$diff,$dbM);
}

function javaService($pid,$dbM)
{
	//$WebAuthentication = new WebAuthentication;
	//$x = $WebAuthentication->setPaymentGatewayAuthchecksum($pid);
	//$auth = $x["AUTHCHECKSUM"];
	$url = "http://localhost:8190/listings/v1/activities?type=chat&listing=shortlist";
	$header = array("JB-Profile-Identifier:".$pid);
	$start_tm=microtime(true);
	$response = CommonUtility::sendCurlPostRequest($url,"","",$header);
	$diff=microtime(true)-$start_tm;
	$data = (Array)json_decode($response);
	$insert["pid"]=$pid;
	$insert["count"]=$data["data"]->totalCount;//$data["header"]->totalCount;
	$insert["ids"]="";
	
	foreach($data["data"]->items as $k=>$v)
	{
		$insert["ids"].=$v->profileid.",";
	}
	insertData($insert,2,$diff,$dbM);
}

function insertData($insert,$type,$diff,$dbM)
{
	if($type==1){
		$sql_up = "REPLACE INTO search.TestDppService(PROFILEID,TOTAL_PHP,IDS_PHP,PHPTIME) VALUES ('$insert[pid]','$insert[count]','$insert[ids]',$diff)";
	}
	if($type==2){
		$sql_up = "UPDATE search.TestDppService SET TOTAL_JAVA='$insert[count]' , IDS_JAVA='$insert[ids]' , JAVATIME='$diff' WHERE PROFILEID='$insert[pid]'";
	}
	mysql_query($sql_up,$dbM) or die($sql_up.mysql_error($dbM));	
}
?>