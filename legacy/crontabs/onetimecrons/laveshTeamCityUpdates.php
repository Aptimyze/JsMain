<?
include(JsConstants::$docRoot."/profile/connect.inc");
ini_set('max_execution_time','0');
ini_set('memory_limit',-1);
$chunkSize = 1000;
$dbS = connect_slave();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbS);
	
$sql = "SET SESSION group_concat_max_len = 1000000;";
mysql_query($sql,$dbS) or mysql_error1(mysql_error($db).$sql);

$dbM = connect_db();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);

echo "\n\n";
updateMe('twowaymatch.TRENDS','CITY_VALUE_PERCENTILE','PROFILEID','PH00','PU00');
updateMe('twowaymatch.TRENDS_FOR_SPAM','CITY_VALUE_PERCENTILE','PROFILEID','PH00','PU00');
updateMe('twowaymatch.CITY_FEMALE_PERCENT','CITY','CITY','PH00','PU00');
updateMe('twowaymatch.CITY_MALE_PERCENT','CITY','CITY','PH00','PU00');
updateMe('newjs.SWAP','CITY_RES','PROFILEID','PH00','PU00');
updateMe('newjs.SEARCH_MALE','CITY_RES','PROFILEID','PH00','PU00');
updateMe('newjs.SEARCH_FEMALE','CITY_RES','PROFILEID','PH00','PU00');
updateMe('newjs.SEARCH_FEMALE_REV','PARTNER_CITYRES','PROFILEID','PH00','PU00');
updateMe('newjs.SEARCH_MALE_REV','PARTNER_CITYRES','PROFILEID','PH00','PU00');
updateMe('MIS.SEARCHQUERY','CITY_INDIA','ID','PH00','PU00');
updateMe('MIS.SEARCHQUERY','CITY_RES','ID','PH00','PU00');
updateMe('newjs.SEARCHQUERY','CITY_INDIA','ID','PH00','PU00');
updateMe('newjs.SEARCHQUERY','CITY_RES','ID','PH00','PU00');
updateMe('newjs.SEARCH_AGENT','CITY_INDIA','ID','PH00','PU00');
updateMe('newjs.SEARCH_AGENT','CITY_RES','ID','PH00','PU00');
echo "\n\n";

function updateMe($table,$col,$keyIndex,$oldVal,$newVal)
{
	global $dbS,$dbM,$chunkSize;

	$sql = "SELECT GROUP_CONCAT( DISTINCT $keyIndex ORDER BY $keyIndex DESC SEPARATOR ',')  AS KK FROM $table WHERE $col like '%$oldVal%'";
//echo "\n".$sql."\n";;die;
	$result=mysql_query($sql,$dbS) or mysql_error1(mysql_error($db).$sql);
	if($row=mysql_fetch_assoc($result))
	{
		$pid = rtrim($row["KK"],",");
		$pidArr = array_chunk(explode(",",$pid),$chunkSize);
		unset($pid);
		foreach($pidArr as $chunkKey=>$profileArr)
		{
			$pidChunk = implode("','",$profileArr); 
			$sql = "update $table SET $col = REPLACE($col,'$oldVal','$newVal') WHERE $col like '%$oldVal%' AND $keyIndex IN ('$pidChunk')";
//echo $sql."\n\n";
			mysql_query($sql,$dbM) or mysql_error1(mysql_error($dbM).$sql);
		}
	}
}
function mysql_error1($msg)
{
        //echo $msg;die;
        mail("lavesh.rawat@jeevansathi.com,lavesh.rawat@gmail.com","lavesh_city_updates.php",$msg);
        exit;
}
