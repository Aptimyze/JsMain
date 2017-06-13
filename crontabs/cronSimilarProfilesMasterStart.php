<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");


/**
  * This cron is used to insert data into tables that would be used to fetch profiles for similar profiles section on the detail view page.
  * Author: Prinka Wadhwa
**/

$flag_using_php5=1;
include("connect.inc");

//for preventing timeout to maximum possible
ini_set('max_execution_time',0);
ini_set('memory_limit', '512M');
ini_set('mysql.connect_timeout',-1);
ini_set('default_socket_timeout',259200); // 3 days
ini_set('log_errors_max_len',0);
//for preventing timeout to maximum possible


$db=connect_ddl();
$debug = 1;
mysql_select_db("viewSimilar",$db);

mysql_query("set session wait_timeout=30000,interactive_timeout=30000,net_read_timeout=30000",$db);

include("$_SERVER[DOCUMENT_ROOT]/commonFiles/vspCronCommonFunctions.php");

foreach($genderArr as $gender)
{

	$sql = "TRUNCATE TABLE $databaseName.SUGGESTED_PROFILEIDS_1_$gender";
	mysqlquerydebug($sql,$db) or mysql_error_mail(mysql_error($db));

	disable_keys($db,$databaseName,"SUGGESTED_PROFILEIDS_1_$gender");

	$sql = "INSERT INTO $databaseName.SUGGESTED_PROFILEIDS_1_$gender SELECT PROFILEID FROM newjs.SEARCH_$gender";
	mysqlquerydebug($sql,$db) or mysql_error_mail(mysql_error($db));

	enable_keys($db,$databaseName,"SUGGESTED_PROFILEIDS_1_$gender");

	$sql = "TRUNCATE TABLE $databaseName.SUGGESTED_PROFILEIDS_2_$gender ";
	mysqlquerydebug($sql,$db) or mysql_error_mail(mysql_error($db));

	disable_keys($db,$databaseName,"SUGGESTED_PROFILEIDS_2_$gender");

$sql = "INSERT INTO $databaseName.SUGGESTED_PROFILEIDS_2_$gender SELECT PROFILEID,LAST_LOGIN_DT FROM newjs.SEARCH_$gender WHERE LAST_LOGIN_DT >= DATE_SUB(CURDATE(), INTERVAL $days_since_last_login DAY)";
	mysqlquerydebug($sql,$db) or mysql_error_mail(mysql_error($db));

	enable_keys($db,$databaseName,"SUGGESTED_PROFILEIDS_2_$gender");

	//dump the above tables on shards and execute the following on all shards
	for($i=1;$i<=2;$i++)
	{
		if($debug) 
		{ 
			echo "\n".date("Y-m-d --- H:i:s")."\n"; 
			echo "\n\n dumping tables to shards \n\n"; 
		}
		
		$tablename = 'SUGGESTED_PROFILEIDS_'.$i.'_'.$gender;

		if(is_numeric($shard1Port) && is_numeric($masterPort))
		{
			passthru(" echo \"truncate table $tablename \" | $mysqlPath -u $shard1UserName -p$shard1Password -h $shard1HostName -P $shard1Port $databaseName;");
			passthru("$mysqldumpPath -t -u $masterUserName -p$masterPassword -h $masterHostName -P $masterPort $databaseName $tablename --skip-add-locks | $mysqlPath -t -u $shard1UserName -p$shard1Password -h $shard1HostName -P $shard1Port $databaseName");
			//echo $x;die;
		}
		else
			passthru(" echo \"truncate table $tablename \" | $mysqlPath -u $shard1UserName -p$shard1Password -h $shard1HostName -S $shard1Port $databaseName;$mysqldumpPath -t -u $masterUserName -p$masterPassword -h $masterHostName -S $masterPort $databaseName $tablename --skip-add-locks | $mysqlPath -t -u $shard1UserName -p$shard1Password -h $shard1HostName -S $shard1Port $databaseName");

			//die("**");
		if(is_numeric($shard2Port) && is_numeric($masterPort))
		{
			passthru(" echo \"truncate table $tablename \" | $mysqlPath -u $shard2UserName -p$shard2Password -h $shard2HostName -P $shard2Port $databaseName;");
			passthru(" $mysqldumpPath -t -u $masterUserName -p$masterPassword -h $masterHostName -P $masterPort $databaseName $tablename --skip-add-locks | $mysqlPath -t -u $shard2UserName -p$shard2Password -h $shard2HostName -P $shard2Port $databaseName");
		}
		else
			passthru(" echo \"truncate table $tablename \" | $mysqlPath -u $shard2UserName -p$shard2Password -h $shard2HostName -S $shard2Port $databaseName;$mysqldumpPath -t -u $masterUserName -p$masterPassword -h $masterHostName -S $masterPort $databaseName $tablename --skip-add-locks | $mysqlPath -t -u $shard2UserName -p$shard2Password -h $shard2HostName -S $shard2Port $databaseName");

		if(is_numeric($shard3Port) && is_numeric($masterPort))
		{
			passthru(" echo \"truncate table $tablename \" | $mysqlPath -u $shard3UserName -p$shard3Password -h $shard3HostName -P $shard3Port $databaseName;");
			passthru(" $mysqldumpPath -t -u $masterUserName -p$masterPassword -h $masterHostName -P $masterPort $databaseName $tablename --skip-add-locks | $mysqlPath -t -u $shard3UserName -p$shard3Password -h $shard3HostName -P $shard3Port $databaseName");
		}
		else
			passthru(" echo \"truncate table $tablename \" | $mysqlPath -u $shard3UserName -p$shard3Password -h $shard3HostName -S $shard3Port $databaseName;$mysqldumpPath -t -u $masterUserName -p$masterPassword -h $masterHostName -S $masterPort $databaseName $tablename --skip-add-locks | $mysqlPath -t -u $shard3UserName -p$shard3Password -h $shard3HostName -S $shard3Port $databaseName");
		if($debug) 
		{ 
			echo "dump done \n\n"; 
			echo "\n".date("Y-m-d --- H:i:s")."\n"; 
		}
	}
        
        echo "\n".date("Y-m-d --- H:i:s")."\n"; 

	$sql = "TRUNCATE TABLE $databaseName.TEMP_CONTACTS_CACHE_LEVEL1_$gender ";
	mysqlquerydebug($sql,$db) or mysql_error_mail(mysql_error($db));

	$sql = "TRUNCATE TABLE $databaseName.TEMP_CONTACTS_CACHE_LEVEL2_$gender ";
	mysqlquerydebug($sql,$db) or mysql_error_mail(mysql_error($db));
}

?>
