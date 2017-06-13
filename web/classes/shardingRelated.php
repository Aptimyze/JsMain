<?php
//global $matchalertServer;
if(!$matchalertServer)
{
	include_once(JsConstants::$docRoot."/classes/Memcache.class.php");
	include_once(JsConstants::$docRoot."/classes/globalVariables.Class.php");
}
global $activeServers;
global $slave_activeServers;
global $shardSlave112;
global $noOfShardedServers;
global $noOfActiveServers;

$activeServers[0]='11Master';
$activeServers[1]='211';  //3307
$activeServers[2]='303Master';  //3309
$noOfActiveServers=3;

$slave_activeServers[0]='11Slave';
$slave_activeServers[1]='211Slave';//3307
$slave_activeServers[2]='303Slave';//3309

$noOfShardedServers=3;
$shardedServers[0]='11Master';
$shardedServers[1]='211';  //3307
$shardedServers[2]='303Master';  //3309

$shardSlave112[0]='112Slave_shard1';// 3309
$shardSlave112[1]='112Slave_shard2'; // 3306
$shardSlave112[2]='112Slave_shard3'; // 3307

$ddlShardUser[0]= 'shard1DDL';
$ddlShardUser[1]= 'shard2DDL';
$ddlShardUser[2]= 'shard3DDL';

$ddlShardSlaveUser[0]= 'shard1SlaveDDL';
$ddlShardSlaveUser[1]= 'shard2SlaveDDL';
$ddlShardSlaveUser[2]= 'shard3SlaveDDL';

$shardSlaveUser[0]= 'shard1Slave';
$shardSlaveUser[1]= 'shard2Slave';
$shardSlaveUser[2]= 'shard3Slave';

/**
* This function is used to map serverId to ServerName. Server name is required for connection.
* @param string $master_or_slave master or slave(mis/cron) database to select
* @param int $activeServerId is unique id of a database server .
* @return string active Server Name
*/

function getActiveServerName($activeServerId,$master_or_slave='master')
{
        global $activeServers;
        global $ddlShardUser;
	global $ddlShardSlaveUser;
        global $slave_activeServers;
        global $shardSlave112;
        global $shardedServers;
        if($master_or_slave=='master')
                return $activeServers[$activeServerId];
        elseif($master_or_slave=='slave112')
                return $shardSlave112[$activeServerId];
        elseif($master_or_slave=='masterDDL')
                return 'masterDDL';
	elseif($master_or_slave=='alertsDDL')
                return 'alertsDDL';
	elseif($master_or_slave=='viewLogDDL')
                return 'viewLogDDL';
        elseif($master_or_slave=='shardDDL')
                return $ddlShardUser[$activeServerId];
    elseif($master_or_slave=='shardServer')
                return $shardedServers[$activeServerId];
	elseif($master_or_slave=='shardSlaveDDL')
                return $ddlShardSlaveUser[$activeServerId];
        else
                return $slave_activeServers[$activeServerId];
}

/**
* This function is used to map profileid to its ServerName.ServerName is database where all its records are stored.
* @param string $master_or_slave master or slave(mis/cron) database to select
* @param int $profileid is unique id of a user.
* @return string active Server Name
*/
function getProfileDatabaseConnectionName($profileid,$master_or_slave='master',$mysqlObj='',$optionalDb="")
{
        global $activeServers;
        global $slave_activeServers;
	global $matchalertServer;
        if(!$matchalertServer)
        	$myDbId=getProfileDatabaseId($profileid,$db="",$mysqlObj="");
	else
		$myDbId=getProfileDatabaseId($profileid,"",$mysqlObj,$optionalDb);
        if($master_or_slave=='master' || !$master_or_slave)
                return $activeServers[$myDbId];
        else
                return $slave_activeServers[$myDbId];
}
/**
* This function will test whether given number is interger or not
*/
function IsValInteger($val)
{
	
	 $new=intval($val);
        if("$new"=="$val")
		return true;
        else
                return false;
}
/**
* This function is used to map profileid to its unique ServerId.
* @param int $profileid is unique id of a user.
* @return string serverId corresponding to where all its records are stored.
*/
function getProfileDatabaseId($profileid,$db="",$mysqlObj="",$optionalDb="")
{
	$profileid=trim($profileid,"'");
	$profileid=trim($profileid,"\"");
	if(!IsValInteger($profileid))
	{
		$profileid=htmlspecialchars($profileid,ENT_QUOTES);
		trigger_error("Profileid is not numeric",E_USER_ERROR);
		die;
	}
	global $activeServers;
	global $matchalertServer;
	if(!$matchalertServer)
	{	
		$memcacheObj=new UserMemcache;
       		$myDbId=$memcacheObj->getServerProfileMapping($profileid);
	}
	if($myDbId=='')
        {
		if(!$mysqlObj)
	                $mysqlObj=new Mysql;
		if(!$matchalertServer)
			$db=$mysqlObj->connect("master");
        	else
		{
			if($optionalDb)
				$db=$optionalDb;
			else
				$db=$mysqlObj->connect("slave");
		}
		//This saves a query
		$myDbId=JsDbSharding::getShardNumber($profileid);
		
		// $sql="SELECT SERVERID FROM newjs.PROFILEID_SERVER_MAPPING WHERE PROFILEID =$profileid";
		// $result = $mysqlObj->executeQuery($sql,$db);
  //               $myrow=$mysqlObj->fetchArray($result);                
  //               $myDbId=$myrow["SERVERID"];
		if(!$matchalertServer)
		{
			$memcacheObj->logServerProfileMapping($profileid,$myDbId);
		}
        }
        if($myDbId=='')
        {
                assignServerToProfile($profileid);

		if(!$mysqlObj)
		$mysqlObj=new Mysql;
		if($matchalertServer)
			$db=$mysqlObj->connect("slave");
		else
			$db=$mysqlObj->connect("master");
	
		//This saves a query
		$myDbId=JsDbSharding::getShardNumber($profileid);
		// $sql="SELECT SERVERID FROM newjs.PROFILEID_SERVER_MAPPING WHERE PROFILEID =$profileid";
		// $result = $mysqlObj->executeQuery($sql,$db);
		// $myrow=$mysqlObj->fetchArray($result);
		// $myDbId=$myrow["SERVERID"];

		if(!$matchalertServer)
		{
			$memcacheObj->logServerProfileMapping($profileid,$myDbId);
		}        
	}
	return $myDbId;
}


/**
* This function is used to map profileid to serverid and stores the record in database.
* @param int $profileid is unique id of a user.
*/
function assignServerToProfile($profileid)
{
	global $matchalertServer;
	global $noOfShardedServers,$noOfActiveServers,$activeServers;
	$mysqlObj=new Mysql;
	$db_main=$mysqlObj->connect("master");
	$serverid=$profileid%$noOfShardedServers;
	//On Re-sharding
	/*
	//eg. shard4 replaces shard0 & shard5 replaces shard1
	$reshardingArray[0]=4;
	$reshardingArray[1]=5;
	if(in_array($serverid,$reshardingArray))
		$serverid=$reshardingArray[$serverid];
	*/
	//On Re-sharding
	$shard=$serverid;
	$assign_date=date("Y-m-d");
	if(!$matchalertServer)
	{
		$sql = "INSERT IGNORE INTO newjs.PROFILEID_SERVER_MAPPING(PROFILEID,SERVERID,ASSIGN_DATE) VALUES('$profileid','$serverid','$assign_date')";
		$mysqlObj->executeQuery($sql,$db_main);

		$myDb=$mysqlObj->connect($activeServers[$shard]);
		$mysqlObj->executeQuery($sql,$myDb);
	}
}
?>
