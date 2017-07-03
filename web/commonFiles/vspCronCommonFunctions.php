<?php


$debug = 1;
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

function mysqlquerydebug($sql,$db)
{
	global $debug;
	if($debug==1){
		echo $sql."\n\n";
                file_put_contents(sfConfig::get("sf_upload_dir")."/SearchLogs/VspCronLogs.txt",date("Y-m-d --- H:i:s")."\n".$sql."\n\n",FILE_APPEND);
        }
	return mysql_query($sql,$db);
}