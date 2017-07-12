<?php
$mysqlPath = "/usr/local/mysql-5.5.25a-linux2.6-x86_64/bin/mysql";
$mysqldumpPath = "/usr/local/mysql-5.5.25a-linux2.6-x86_64/bin/mysqldump";

$d2 = date('Y-m-d', strtotime('last day of previous month'));

$date = new DateTime($d2);
$date->add(new DateInterval('P1D'));
$d22 = $date->format('Y-m-d');

$date = new DateTime($d22);
$date->sub(new DateInterval('P1Y'));
$d1 = $date->format('Y-m-d');

$dumpMe = array(
	array("newjs","MESSAGE_LOG","MESSAGE_LOG_Shard","DATE","SENDER,RECEIVER,TYPE,DATE","SENDER"),
	array("newjs","DELETED_MESSAGE_LOG","MESSAGE_LOG_Shard","DATE","SENDER, RECEIVER, DATE, TYPE","SENDER","NODEL"),
	array("newjs","CONTACTS","CONTACTS_Shard","TIME","CONTACTID,SENDER,RECEIVER,TYPE,TIME","SENDER"),
	array("newjs","DELETED_PROFILE_CONTACTS","CONTACTS_Shard","TIME","CONTACTID,SENDER,RECEIVER,TYPE,TIME","SENDER","NODEL"),
	array("MIS","SEARCH_CONTACT_FLOW_TRACKING_NEW","SEARCH_CONTACT_FLOW_Shard","DATE","PROFILEID, SEARCH_TYPE, CONTACTID, DATE, FROM_DETAILPROFILE",""),
	array("newjs","LOG_LOGIN_HISTORY","LOGINS_Shard","TIME","PROFILEID,TIME",""),
);

$dumpDb = 'test';
$shard1UserName = MysqlDbConstants::$shard1['USER'];
$shard1Password = MysqlDbConstants::$shard1['PASS']; 
$shard1HostName = MysqlDbConstants::$shard1['HOST'];
$shard1Port =  MysqlDbConstants::$shard1['PORT'];

$shard2UserName = MysqlDbConstants::$shard2['USER'];
$shard2Password = MysqlDbConstants::$shard2['PASS']; 
$shard2HostName = MysqlDbConstants::$shard2['HOST'];
$shard2Port =  MysqlDbConstants::$shard2['PORT'];

$shard3UserName = MysqlDbConstants::$shard3['USER'];
$shard3Password = MysqlDbConstants::$shard3['PASS']; 
$shard3HostName = MysqlDbConstants::$shard3['HOST'];
$shard3Port =  MysqlDbConstants::$shard3['PORT'];



foreach($dumpMe as $k=>$v)
{
	//foreach($v as $kk=>$vv)
	{
		$tablename = $v[1];
		$databaseName = $v[0];
		$whereC = $v[3];
		$column = $v[4];
		$mod = $v[5];
		for($i=1;$i<4;$i++)
		{
/*
if($i==2 || $i==3)
	continue;
*/
			/* getting connections */
			$shardUserName =  ${'shard'.$i.'UserName'};
                        $shardPassword = ${'shard'.$i.'Password'};
                        $shardHostName = ${'shard'.$i.'HostName'};
                        $shardPort =  ${'shard'.$i.'Port'};

			//echo $shardHostName.":".$shardPort."::::::".$shardUserName.":::::::".$shardPassword."\n\n\n";
			$myDb = mysql_connect($shardHostName.":".$shardPort,$shardUserName,$shardPassword) or die(mysql_error()."Here");
			/* getting connections */

			$tableTest = $v[2];
			$tableTest = $tableTest.$i;

			if(!$v[6])
			{
				$sql = "TRUNCATE TABLE $dumpDb.$tableTest";
				runCommand($sql,$myDb);
			}
	
			$sql = "INSERT INTO $dumpDb.$tableTest SELECT $column FROM $databaseName.$tablename WHERE $whereC BETWEEN '$d1' and '$d2'";
			if($mod)
			{
				$j=$i-1;
				$sql.=" AND $mod%3=$j";
			}
			//$sql.=" LIMIT 1";
			runCommand($sql,$myDb);
echo "\n\n----------change-in-shard----------------\n\n";
		}	
	}
}

function runCommand($command,$myDb)
{
	echo $command;echo "\n\n";
	mysql_query($command,$myDb) or die(mysql_error($myDb).$command);
}
