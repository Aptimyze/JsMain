<?
include('connect.inc');
include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");

ini_set(max_execution_time,0);
ini_set(memory_limit,-1);
ini_set(mysql.connect_timeout,-1);
ini_set(default_socket_timeout,259200); // 3 days
ini_set(log_errors_max_len,0);

$db=connect_db();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
mysql_select_db("newjs",$db) or die(mysql_error());

$mysqlObj=new Mysql;

for($ll=0;$ll<$noOfShardedServers;$ll++)
{
		echo $activeServers[$ll].'--';
	$affectedDb[$ll]=$mysqlObj->connect($activeServers[$ll]);
}

print_r($affectedDb);

$sql="SELECT  PROFILEID FROM newjs.JPROFILE";
$res=mysql_query_decide($sql,$db) or die(mysql_error().$sql);
while($row=mysql_fetch_array($res))
{
	$profileid=$row['PROFILEID'];
	$serverid=$profileid%3;

        $serverid=$profileid%$noOfShardedServers;

        $sql = "INSERT IGNORE INTO newjs.PROFILEID_SERVER_MAPPING VALUES('$profileid','$serverid')";
	for($ll=0;$ll<count($affectedDb);$ll++)
	{
		$tempDb=$affectedDb[$ll];
		$mysqlObj->ping();
		$mysqlObj->executeQuery($sql,$tempDb);
		unset($tempDb);
	}
        if($kk==100)
                die;
        $kk=$kk+1;
}
?>



