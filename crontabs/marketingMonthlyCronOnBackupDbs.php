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
$shard1UserName =  'user_js';
$shard1Password = 'userjsKm7Iv80l'; 
$shard1HostName = '172.16.3.157';
$shard1Port =  '3309';

$shard2UserName =  'user_js';
$shard2Password = 'userjsKm7Iv80l'; 
$shard2HostName = '172.16.3.187';
$shard2Port =  '3308';

$shard3UserName =  'user_js';
$shard3Password = 'userjsKm7Iv80l'; 
$shard3HostName = '192.168.2.240';
$shard3Port =  '3314';



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
