<?php
$fp = fopen("/tmp/shardJpartner.txt", "w+");
if (flock($fp, LOCK_EX)) 
{ 
	flock($fp, LOCK_UN); // unlock
	
} 
else 
{
	mail("lavesh.rawat@jeevansathi.com,lavesh.rawat@gmail.com","Locking Issue : shard_jpartner_dump.php","Locking Issue : shard_jpartner_dump.php");
}

//****shard 1 string*****
if(MysqlDbConstants::$alerts[SOCKET] !='')
	$shard1Str=MysqlDbConstants::$mySqlDumpPath. ' -t -u'. MysqlDbConstants::$shard1SlaveDump[USER].' -p'.MysqlDbConstants::$shard1SlaveDump[PASS] .' -h'. MysqlDbConstants::$shard1SlaveDump[HOST]. ' -P '.MysqlDbConstants::$shard1SlaveDump[PORT].' newjs JPARTNER | '.MysqlDbConstants::$mySqlPath. ' -t -u' . MysqlDbConstants::$alerts[USER].' -p'.MysqlDbConstants::$alerts[PASS] .' -S '.MysqlDbConstants::$alerts[SOCKET].' -h'.MysqlDbConstants::$alerts[HOST].' matchalerts;';
else
	$shard1Str=MysqlDbConstants::$mySqlDumpPath. ' -t -u'. MysqlDbConstants::$shard1SlaveDump[USER].' -p'.MysqlDbConstants::$shard1SlaveDump[PASS] .' -h'. MysqlDbConstants::$shard1SlaveDump[HOST]. ' -P '.MysqlDbConstants::$shard1SlaveDump[PORT].' newjs JPARTNER | '.MysqlDbConstants::$mySqlPath. ' -t -u' . MysqlDbConstants::$alerts[USER].' -p'.MysqlDbConstants::$alerts[PASS] .' -P'.MysqlDbConstants::$alerts[PORT].' -h'.MysqlDbConstants::$alerts[HOST].' matchalerts;';

//****shard 2 string*****	
if(MysqlDbConstants::$alerts[SOCKET] !='')
	$shard2Str=MysqlDbConstants::$mySqlDumpPath. ' -t -u'. MysqlDbConstants::$shard2SlaveDump[USER].' -p'.MysqlDbConstants::$shard2SlaveDump[PASS] .' -h '.MysqlDbConstants::$shard2SlaveDump[HOST].' -P'. MysqlDbConstants::$shard2SlaveDump[PORT].' newjs JPARTNER | '.MysqlDbConstants::$mySqlPath. ' -t -u' . MysqlDbConstants::$alerts[USER].' -p'.MysqlDbConstants::$alerts[PASS] .' -S '.MysqlDbConstants::$alerts[SOCKET].' -h'.MysqlDbConstants::$alerts[HOST].' matchalerts;';
else
	$shard2Str=MysqlDbConstants::$mySqlDumpPath. ' -t -u'. MysqlDbConstants::$shard2SlaveDump[USER].' -p'.MysqlDbConstants::$shard2SlaveDump[PASS] .' -h '.MysqlDbConstants::$shard2SlaveDump[HOST].' -P'. MysqlDbConstants::$shard2SlaveDump[PORT].' newjs JPARTNER | '.MysqlDbConstants::$mySqlPath. ' -t -u' . MysqlDbConstants::$alerts[USER].' -p'.MysqlDbConstants::$alerts[PASS] .' -P'.MysqlDbConstants::$alerts[PORT].' -h'.MysqlDbConstants::$alerts[HOST].' matchalerts;';
	
//****shard 3 string*****	
if(MysqlDbConstants::$alerts[SOCKET] !='')
	$shard3Str=MysqlDbConstants::$mySqlDumpPath. ' -t -u'. MysqlDbConstants::$shard3SlaveDump[USER].' -p'.MysqlDbConstants::$shard3SlaveDump[PASS] .' -h'. MysqlDbConstants::$shard3SlaveDump[HOST].' -P'. MysqlDbConstants::$shard3SlaveDump[PORT].' newjs JPARTNER | '.MysqlDbConstants::$mySqlPath. ' -t -u' . MysqlDbConstants::$alerts[USER].' -p'.MysqlDbConstants::$alerts[PASS] .' -S '.MysqlDbConstants::$alerts[SOCKET].' -h'.MysqlDbConstants::$alerts[HOST].' matchalerts;';
else
	$shard3Str=MysqlDbConstants::$mySqlDumpPath. ' -t -u'. MysqlDbConstants::$shard3SlaveDump[USER].' -p'.MysqlDbConstants::$shard3SlaveDump[PASS] .' -h'. MysqlDbConstants::$shard3SlaveDump[HOST].' -P'. MysqlDbConstants::$shard3SlaveDump[PORT].' newjs JPARTNER | '.MysqlDbConstants::$mySqlPath. ' -t -u' . MysqlDbConstants::$alerts[USER].' -p'.MysqlDbConstants::$alerts[PASS] .' -P'.MysqlDbConstants::$alerts[PORT].' -h'.MysqlDbConstants::$alerts[HOST].' matchalerts;';

passthru($shard1Str);
passthru($shard2Str);
passthru($shard3Str);

fclose($fp);
?>
