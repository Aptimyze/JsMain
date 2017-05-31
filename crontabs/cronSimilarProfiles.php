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

$minimum_no_of_results_required_for_rename = 1500000;

$days_since_last_login = 45;
$no_of_level_1_contacts = 50;
$no_of_level_2_contacts = 40;
$exp_constant = 1;
$acceptedConstant = 20;
$declinedConstant = -7;
$days_since_last_login_priority = 15;

$mysqlPath=MysqlDbConstants::$mySqlPath;
$mysqldumpPath=MysqlDbConstants::$mySqlDumpPath;


$masterHostName = MysqlDbConstants::$masterDDL[HOST];
$masterPort = MysqlDbConstants::$masterDDL[PORT];
$masterUserName = MysqlDbConstants::$masterDDL[USER];
$masterPassword = MysqlDbConstants::$masterDDL[PASS];

$shard1HostName = MysqlDbConstants::$shard1SlaveDDL[HOST];
$shard1Port = MysqlDbConstants::$shard1SlaveDDL[PORT];

$shard1UserName = MysqlDbConstants::$shard1SlaveDDL[USER];
$shard1Password = MysqlDbConstants::$shard1SlaveDDL[PASS];


$shard2HostName = MysqlDbConstants::$shard2SlaveDDL[HOST];
$shard2Port = MysqlDbConstants::$shard2SlaveDDL[PORT];

$shard2UserName = MysqlDbConstants::$shard2SlaveDDL[USER];
$shard2Password = MysqlDbConstants::$shard2SlaveDDL[PASS];

$shard3HostName = MysqlDbConstants::$shard3SlaveDDL[HOST];
$shard3Port = MysqlDbConstants::$shard3SlaveDDL[PORT];

$shard3UserName = MysqlDbConstants::$shard3SlaveDDL[USER];
$shard3Password = MysqlDbConstants::$shard3SlaveDDL[PASS];


$genderArr[0]='MALE';
$genderArr[1]='FEMALE';

$databaseName = 'viewSimilar';
function disable_keys($db,$databaseName,$tableName)
{
$sql = "ALTER TABLE $databaseName.$tableName DISABLE KEYS";
	mysqlquerydebug($sql,$db) or mysql_error_mail(mysql_error($db));
}

function enable_keys($db,$databaseName,$tableName)
{
$sql = "ALTER TABLE $databaseName.$tableName ENABLE KEYS";
	mysqlquerydebug($sql,$db) or mysql_error_mail(mysql_error($db));
}

function mysql_error_mail($msg='')
{
        echo $msg;
        mail("lavesh.rawat@jeevansathi.com","cronSimilarProfiles.php",$msg);
        die;
}

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
			passthru("$mysqldumpPath -t -u $masterUserName -p$masterPassword -h $masterHostName -P $masterPort $databaseName $tablename --skip-add-locks | $mysqlPath -t -u $shard1UserName -p$shard1Password -h $shard3HostName -P $shard1Port $databaseName");
			//echo $x;die;
		}
		else
			passthru(" echo \"truncate table $tablename \" | $mysqlPath -u $shard1UserName -p$shard1Password -h $shard1HostName -S $shard1Port $databaseName;$mysqldumpPath -t -u $masterUserName -p$masterPassword -h $masterHostName -S $masterPort $databaseName $tablename --skip-add-locks | $mysqlPath -t -u $shard1UserName -p$shard1Password -h $shard3HostName -S $shard1Port $databaseName");

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

$mysqlObj=new Mysql;
for($serverId=0;$serverId<$noOfActiveServers;$serverId++)
{
	$myDbName=$ddlShardSlaveUser[$serverId];
	$myDb=$mysqlObj->connect("$myDbName");

	foreach($genderArr as $gender)
	{

		if($gender == 'MALE')
			$oppositeGender = 'FEMALE';
		elseif($gender == 'FEMALE')
			$oppositeGender = 'MALE';


		disable_keys($myDb,$databaseName,"SUGGESTED_PROFILEIDS_1_$gender");
                if($debug) 
		{ 
                    $sql = "Select count(*) AS COUNT from $databaseName.SUGGESTED_PROFILEIDS_1_$gender";
                    $res = mysqlquerydebug($sql,$myDb) or mysql_error_mail(mysql_error($myDb));
                    $rowDe=mysql_fetch_assoc($res);
                    $countDe = $rowDe['COUNT']; 
                    echo "\ncount in SUGGESTED_PROFILEIDS_1_$gender =".$countDe."\n";
                }
                

	$sql = "DELETE FROM $databaseName.SUGGESTED_PROFILEIDS_1_$gender WHERE PROFILEID % 3 <> $serverId ";
		mysqlquerydebug($sql,$myDb) or mysql_error_mail(mysql_error($myDb));

		enable_keys($myDb,$databaseName,"SUGGESTED_PROFILEIDS_1_$gender");
                
                if($debug) 
		{ 
                    $sql = "Select count(*) AS COUNT from $databaseName.SUGGESTED_PROFILEIDS_1_$gender";
                    $res = mysqlquerydebug($sql,$myDb) or mysql_error_mail(mysql_error($myDb));
                    $rowDe=mysql_fetch_assoc($res);
                    $countDe = $rowDe['COUNT']; 
                    echo "\ncount in SUGGESTED_PROFILEIDS_1_$gender after del=".$countDe."\n";
                
                    $sql = "Select count(*) AS COUNT from $databaseName.SUGGESTED_PROFILEIDS_2_$gender";
                    $res = mysqlquerydebug($sql,$myDb) or mysql_error_mail(mysql_error($myDb));
                    $rowDe=mysql_fetch_assoc($res);
                    $countDe = $rowDe['COUNT']; 
                    echo "\ncount in SUGGESTED_PROFILEIDS_2_$gender =".$countDe."\n";
                }

		disable_keys($myDb,$databaseName,"SUGGESTED_PROFILEIDS_2_$gender");

	$sql = "DELETE FROM $databaseName.SUGGESTED_PROFILEIDS_2_$gender WHERE PROFILEID % 3 <> $serverId ";
		mysqlquerydebug($sql,$myDb) or mysql_error_mail(mysql_error($myDb));

		enable_keys($myDb,$databaseName,"SUGGESTED_PROFILEIDS_2_$gender");
                if($debug) 
		{ 
                    $sql = "Select count(*) AS COUNT from $databaseName.SUGGESTED_PROFILEIDS_2_$gender";
                    $res = mysqlquerydebug($sql,$myDb) or mysql_error_mail(mysql_error($myDb));
                    $rowDe=mysql_fetch_assoc($res);
                    $countDe = $rowDe['COUNT']; 
                    echo "\ncount in SUGGESTED_PROFILEIDS_2_$gender after del=".$countDe."\n";
                }

	$sql = "TRUNCATE TABLE $databaseName.TEMP_CONTACTS_CACHE_LEVEL1_$gender ";
		mysqlquerydebug($sql,$myDb) or mysql_error_mail(mysql_error($myDb));

	$sql = "TRUNCATE TABLE $databaseName.TEMP_CONTACTS_CACHE_LEVEL2_$oppositeGender ";
		mysqlquerydebug($sql,$myDb) or mysql_error_mail(mysql_error($myDb));

		disable_keys($myDb,$databaseName,"TEMP_CONTACTS_CACHE_LEVEL1_$gender");
                echo "\n".date("Y-m-d --- H:i:s")."\n"; 

	$sql = "INSERT IGNORE INTO $databaseName.TEMP_CONTACTS_CACHE_LEVEL1_$gender (SENDER, RECEIVER, TIME)(SELECT SENDER, RECEIVER, TIME FROM $databaseName.SUGGESTED_PROFILEIDS_1_$gender S JOIN newjs.CONTACTS C ON C.SENDER = S.PROFILEID WHERE C.TYPE='A') UNION (SELECT RECEIVER AS SENDER, SENDER AS RECEIVER, TIME FROM $databaseName.SUGGESTED_PROFILEIDS_1_$gender S JOIN newjs.CONTACTS C ON C.RECEIVER = S.PROFILEID) ORDER BY TIME DESC";
		mysqlquerydebug($sql,$myDb) or mysql_error_mail(mysql_error($myDb));


		enable_keys($myDb,$databaseName,"TEMP_CONTACTS_CACHE_LEVEL1_$gender");
                
                if($debug) 
		{ 
                    $sql = "Select count(*) AS COUNT from $databaseName.TEMP_CONTACTS_CACHE_LEVEL1_$gender";
                    $res = mysqlquerydebug($sql,$myDb) or mysql_error_mail(mysql_error($myDb));
                    $rowDe=mysql_fetch_assoc($res);
                    $countDe = $rowDe['COUNT']; 
                    echo "\ncount in TEMP_CONTACTS_CACHE_LEVEL1_$gender =".$countDe."\n";
                }
                echo "\n".date("Y-m-d --- H:i:s")."\n"; 

		//reduce the no of results for each profileid to 50 based on time
	$sql = "DELETE FROM $databaseName.TEMP_CONTACTS_CACHE_LEVEL1_$gender WHERE SNO>$no_of_level_1_contacts ";
		mysqlquerydebug($sql,$myDb) or mysql_error_mail(mysql_error($myDb));

		disable_keys($myDb,$databaseName,"TEMP_CONTACTS_CACHE_LEVEL2_$oppositeGender");
                echo "\n".date("Y-m-d --- H:i:s")."\n"; 

	$sql = "INSERT IGNORE INTO $databaseName.TEMP_CONTACTS_CACHE_LEVEL2_$oppositeGender (SENDER, RECEIVER, TIME,TYPE) (SELECT SENDER, RECEIVER, TIME, TYPE FROM $databaseName.SUGGESTED_PROFILEIDS_2_$gender S JOIN newjs.CONTACTS C ON C.RECEIVER = S.PROFILEID WHERE C.FILTERED <> 'Y') UNION (SELECT RECEIVER AS SENDER, SENDER AS RECEIVER, TIME, TYPE FROM $databaseName.SUGGESTED_PROFILEIDS_2_$gender S JOIN newjs.CONTACTS C ON C.SENDER = S.PROFILEID WHERE C.TYPE = 'A') ORDER BY TIME DESC";
		mysqlquerydebug($sql,$myDb) or mysql_error_mail(mysql_error($myDb));
                

		enable_keys($myDb,$databaseName,"TEMP_CONTACTS_CACHE_LEVEL2_$oppositeGender");
                
                if($debug) 
		{ 
                    $sql = "Select count(*) AS COUNT from $databaseName.TEMP_CONTACTS_CACHE_LEVEL2_$oppositeGender";
                    $res = mysqlquerydebug($sql,$myDb) or mysql_error_mail(mysql_error($myDb));
                    $rowDe=mysql_fetch_assoc($res);
                    $countDe = $rowDe['COUNT']; 
                    echo "\ncount in TEMP_CONTACTS_CACHE_LEVEL2_$oppositeGender =".$countDe."\n";
                }
                
                echo "\n".date("Y-m-d --- H:i:s")."\n"; 

		//reduce no of results for each profileid to 40 based on time
	$sql = "DELETE FROM $databaseName.TEMP_CONTACTS_CACHE_LEVEL2_$oppositeGender WHERE SNO>$no_of_level_2_contacts ";
		mysqlquerydebug($sql,$myDb) or mysql_error_mail(mysql_error($myDb));

	$sql = "UPDATE $databaseName.TEMP_CONTACTS_CACHE_LEVEL2_$oppositeGender SET CONSTANT_VALUE = EXP(-$exp_constant)*( DATEDIFF( NOW(), TIME ))/30 ";
		mysqlquerydebug($sql,$myDb) or mysql_error_mail(mysql_error($myDb));

	$sql = "UPDATE $databaseName.TEMP_CONTACTS_CACHE_LEVEL2_$oppositeGender SET CONSTANT_VALUE = CONSTANT_VALUE*$acceptedConstant WHERE TYPE='A' ";
		mysqlquerydebug($sql,$myDb) or mysql_error_mail(mysql_error($myDb));

	$sql = "UPDATE $databaseName.TEMP_CONTACTS_CACHE_LEVEL2_$oppositeGender SET CONSTANT_VALUE = CONSTANT_VALUE*$declinedConstant WHERE TYPE='D' ";
		mysqlquerydebug($sql,$myDb) or mysql_error_mail(mysql_error($myDb));

	}
}
echo "\n".date("Y-m-d --- H:i:s")."\n"; 
for($i=1;$i<=2;$i++)
{
	foreach($genderArr as $gender)
	{
		if($debug) 
		{ 
			echo "\n\n dumping tables to master \n\n"; 
			echo "\n".date("Y-m-d --- H:i:s")."\n"; 
		}
		$tablename = 'TEMP_CONTACTS_CACHE_LEVEL'.$i.'_'.$gender;

		disable_keys($db,$databaseName,$tablename);

		if(is_numeric($shard1Port) && is_numeric($masterPort))
			passthru("$mysqldumpPath -t  -u $shard1UserName -p$shard1Password -h $shard1HostName -P $shard1Port $databaseName $tablename --skip-add-locks --skip-lock-tables | $mysqlPath -t -u $masterUserName -p$masterPassword -h $masterHostName -P $masterPort $databaseName");
		else
			passthru("$mysqldumpPath -t  -u $shard1UserName -p$shard1Password -h $shard1HostName -S $shard1Port $databaseName $tablename --skip-add-locks --skip-lock-tables | $mysqlPath -t -u $masterUserName -p$masterPassword -h $masterHostName -S $masterPort $databaseName");

		if(is_numeric($shard1Port) && is_numeric($masterPort))
			passthru("$mysqldumpPath -t  -u $shard2UserName -p$shard2Password -h $shard2HostName -P $shard2Port $databaseName $tablename --skip-add-locks --skip-lock-tables | $mysqlPath -t -u $masterUserName -p$masterPassword -h $masterHostName -P $masterPort $databaseName");
		else
			passthru("$mysqldumpPath -t  -u $shard2UserName -p$shard2Password -h $shard2HostName -S $shard2Port $databaseName $tablename --skip-add-locks --skip-lock-tables | $mysqlPath -t -u $masterUserName -p$masterPassword -h $masterHostName -S $masterPort $databaseName");

		if(is_numeric($shard1Port) && is_numeric($masterPort))
			passthru("$mysqldumpPath -t  -u $shard3UserName -p$shard3Password -h $shard3HostName -P $shard3Port $databaseName $tablename --skip-add-locks --skip-lock-tables| $mysqlPath -t -u $masterUserName -p$masterPassword -h $masterHostName -P $masterPort $databaseName");
		else
			passthru("$mysqldumpPath -t  -u $shard3UserName -p$shard3Password -h $shard3HostName -S $shard3Port $databaseName $tablename --skip-add-locks --skip-lock-tables| $mysqlPath -t -u $masterUserName -p$masterPassword -h $masterHostName -S $masterPort $databaseName");

		enable_keys($db,$databaseName,$tablename);
		if($debug) 
		{ 
			echo "\n\n dump done \n\n"; 
			echo "\n".date("Y-m-d --- H:i:s")."\n"; 
		}
	}
}


$tableArr=array("TEMP_CONTACTS_CACHE_LEVEL2_MALE","TEMP_CONTACTS_CACHE_LEVEL2_FEMALE");

foreach($tableArr as $tableName)
{

	$temporaryTable = $tableName."_RENAME";

$truncate = "TRUNCATE TABLE $databaseName.$temporaryTable";
	mysqlquerydebug($truncate,$db) or mysql_error_mail(mysql_error($db));

$sql="ALTER TABLE $databaseName.$temporaryTable ADD SNO INT NOT NULL AUTO_INCREMENT PRIMARY KEY FIRST";
	mysqlquerydebug($sql,$db) or mysql_error_mail(mysql_error($db));

$alter1 = "ALTER TABLE $databaseName.$temporaryTable ADD UNIQUE S_R (SENDER , RECEIVER);";
	mysqlquerydebug($alter1,$db) or mysql_error_mail(mysql_error($db));

$alter2 = "ALTER TABLE $databaseName.$temporaryTable ADD UNIQUE S_S (SENDER , SNO);";
	mysqlquerydebug($alter2,$db) or mysql_error_mail(mysql_error($db));

$alter3="ALTER TABLE $databaseName.$temporaryTable DROP PRIMARY KEY";
	mysqlquerydebug($alter3,$db) or mysql_error_mail(mysql_error($db));

$sql = "INSERT IGNORE INTO $databaseName.$temporaryTable(SENDER,RECEIVER,CONSTANT_VALUE) SELECT SENDER,RECEIVER,CONSTANT_VALUE FROM $tableName ORDER BY TIME DESC";
	mysqlquerydebug($sql,$db) or mysql_error_mail(mysql_error($db));
        
echo "\n".date("Y-m-d --- H:i:s")."\n";
$sql="DELETE FROM $databaseName.$temporaryTable WHERE SNO>$no_of_level_2_contacts";
	mysqlquerydebug($sql,$db) or mysql_error_mail(mysql_error($db));
echo "\n".date("Y-m-d --- H:i:s")."\n";

$sql="ALTER TABLE $databaseName.$temporaryTable  CHANGE SNO SNO INT(11) DEFAULT NULL";
	mysqlquerydebug($sql,$db) or mysql_error_mail(mysql_error($db));

$alter7 = "ALTER TABLE $databaseName.$temporaryTable DROP INDEX S_S";
        mysqlquerydebug($alter7,$db) or mysql_error_mail(mysql_error($db));

$alter5 = "ALTER TABLE $databaseName.$temporaryTable DROP COLUMN SNO";
        mysqlquerydebug($alter5,$db) or mysql_error_mail(mysql_error($db));

$alter6 = "ALTER TABLE $databaseName.$temporaryTable DROP INDEX S_R";
        mysqlquerydebug($alter6,$db) or mysql_error_mail(mysql_error($db));
}

$tableArr=array("CONTACTS_CACHE_LEVEL1_MALE","CONTACTS_CACHE_LEVEL2_MALE","CONTACTS_CACHE_LEVEL1_FEMALE","CONTACTS_CACHE_LEVEL2_FEMALE");

foreach($tableArr as $tableName)
{
	$count = 0;
	$table1 = 'TEMP_'.$tableName;
        if($tableName=='CONTACTS_CACHE_LEVEL2_FEMALE'  || $tableName=='CONTACTS_CACHE_LEVEL2_MALE')
                $table1.="_RENAME";
	$check = "SELECT COUNT(*) AS COUNT FROM $table1 ";
	$res = mysql_query($check,$db) or mysql_error_mail(mysql_error($db));
	if($row=mysql_fetch_assoc($res))
	{
		$count = $row['COUNT'];
                if($debug)
                    echo "\n count in $table1 =".$count."\n";
	}
	if($count < $minimum_no_of_results_required_for_rename)
	{
		mysql_error_mail("no of entries in $table1 is $count. execution of cron stopped");
	}

}

foreach($genderArr as $gender)
{
	if($gender == 'MALE')
		$oppositeGender = 'FEMALE';
	elseif($gender == 'FEMALE')
		$oppositeGender = 'MALE';

	$sql = "CREATE TEMPORARY TABLE $databaseName.SUGGESTED_PROFILEIDS_AGE_$oppositeGender (PROFILEID INT( 11 ) UNSIGNED DEFAULT NULL ,AGE TINYINT( 4 ) DEFAULT  '0' ,LAST_LOGIN_DT DATE DEFAULT NULL, HAVEPHOTO CHAR(1) DEFAULT NULL ,PHOTO_DISPLAY CHAR(1) DEFAULT NULL, KEY `PROFILEID` (`PROFILEID`) )"; 
	mysqlquerydebug($sql,$db) or mysql_error_mail(mysql_error($db));

	$sql = "INSERT IGNORE INTO $databaseName.SUGGESTED_PROFILEIDS_AGE_$oppositeGender SELECT PROFILEID,AGE,LAST_LOGIN_DT,HAVEPHOTO,PHOTO_DISPLAY FROM newjs.SEARCH_$oppositeGender WHERE LAST_LOGIN_DT >= DATE_SUB(CURDATE(), INTERVAL $days_since_last_login DAY)";
	mysqlquerydebug($sql,$db) or mysql_error_mail(mysql_error($db));

	//$sql = "UPDATE $databaseName.TEMP_CONTACTS_CACHE_LEVEL2_".$gender."_RENAME AS T INNER JOIN $databaseName.SUGGESTED_PROFILEIDS_AGE_$oppositeGender AS M ON M.PROFILEID = T.RECEIVER SET T.AGE = M.AGE, T.PRIORITY = IF(HAVEPHOTO='Y',IF(PHOTO_DISPLAY='C',3,1),IF(HAVEPHOTO='U',5,7))";
	$sql = "UPDATE $databaseName.TEMP_CONTACTS_CACHE_LEVEL2_".$gender."_RENAME AS T INNER JOIN $databaseName.SUGGESTED_PROFILEIDS_AGE_$oppositeGender AS M ON M.PROFILEID = T.RECEIVER SET T.AGE = M.AGE, T.PRIORITY = T.PRIORITY+1";
	mysqlquerydebug($sql,$db) or mysql_error_mail(mysql_error($db));	

	$sql = "UPDATE $databaseName.TEMP_CONTACTS_CACHE_LEVEL2_".$gender."_RENAME AS T INNER JOIN $databaseName.SUGGESTED_PROFILEIDS_AGE_$oppositeGender AS M ON M.PROFILEID = T.RECEIVER SET PRIORITY = PRIORITY+1 WHERE M.LAST_LOGIN_DT <= DATE_SUB(CURDATE(), INTERVAL $days_since_last_login_priority DAY)";
	mysqlquerydebug($sql,$db) or mysql_error_mail(mysql_error($db));
	
	$sql = "DROP TABLE $databaseName.SUGGESTED_PROFILEIDS_AGE_$oppositeGender";
	mysqlquerydebug($sql,$db) or mysql_error_mail(mysql_error($db));	
}

foreach($tableArr as $tableName)
{
	$table1 = 'TEMP_'.$tableName;
	$table2 = 'TEMP2_'.$tableName;
        if($tableName=='CONTACTS_CACHE_LEVEL2_FEMALE'  || $tableName=='CONTACTS_CACHE_LEVEL2_MALE')
                $table1.="_RENAME";

	$sql = "RENAME TABLE $tableName TO $table2, $table1 TO $tableName, $table2 TO $table1 ";
	mysqlquerydebug($sql,$db) or mysql_error_mail(mysql_error($db));
	sleep(60);
}

function mysqlquerydebug($sql,$db)
{
	global $debug;
	if($debug==1)
		echo $sql."\n\n";
	return mysql_query($sql,$db);
}
	
	

?>
