<?php


$curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");
 
$flag_using_php5=1;
include("connect.inc");

//for preventing timeout to maximum possible
ini_set('max_execution_time',0);
ini_set('memory_limit', '512M');
ini_set('mysql.connect_timeout',-1);
ini_set('default_socket_timeout',259200); // 3 days
ini_set('log_errors_max_len',0);
//for preventing timeout to maximum possible

$db=connect_db();
mysql_select_db("viewSimilar",$db);

mysql_query("set session wait_timeout=30000,interactive_timeout=30000,net_read_timeout=30000",$db);


include("$_SERVER[DOCUMENT_ROOT]/commonFiles/vspCronCommonFunctions.php");

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
	if($gender == 'MALE'){
		$oppositeGender = 'FEMALE';
                $columnForGender = 'PARTNER_INCOME';
        }
	elseif($gender == 'FEMALE'){
		$oppositeGender = 'MALE';
                $columnForGender = 'PARTNER_ELEVEL_NEW';
        }

	$sql = "CREATE TEMPORARY TABLE $databaseName.SUGGESTED_PROFILEIDS_AGE_AND_REV_$oppositeGender (PROFILEID INT( 11 ) UNSIGNED DEFAULT NULL ,AGE TINYINT( 4 ) DEFAULT  '0' ,LAST_LOGIN_DT DATE DEFAULT NULL, HAVEPHOTO CHAR(1) DEFAULT NULL ,PHOTO_DISPLAY CHAR(1) DEFAULT NULL,PARTNER_LAGE tinyint(4) DEFAULT NULL,PARTNER_HAGE tinyint(4) DEFAULT NULL,PARTNER_LHEIGHT tinyint(3) DEFAULT NULL,PARTNER_HHEIGHT tinyint(3) DEFAULT NULL,PARTNER_MSTATUS text DEFAULT NULL,PARTNER_CITYRES text DEFAULT NULL, PARTNER_COUNTRYRES text DEFAULT NULL,PARTNER_HANDICAPPED text DEFAULT NULL,PARTNER_RELIGION text DEFAULT NULL,PARTNER_CASTE text DEFAULT NULL,$columnForGender text DEFAULT NULL, KEY `PROFILEID` (`PROFILEID`) )"; 
	mysqlquerydebug($sql,$db) or mysql_error_mail(mysql_error($db));
        
        echo "\n".date("Y-m-d --- H:i:s")."\n";
	$sql = "INSERT IGNORE INTO $databaseName.SUGGESTED_PROFILEIDS_AGE_AND_REV_$oppositeGender SELECT S.PROFILEID,AGE,LAST_LOGIN_DT,HAVEPHOTO,PHOTO_DISPLAY,PARTNER_LAGE,PARTNER_HAGE,PARTNER_LHEIGHT,PARTNER_HHEIGHT,PARTNER_MSTATUS,PARTNER_CITYRES,PARTNER_COUNTRYRES,PARTNER_HANDICAPPED,PARTNER_RELIGION,PARTNER_CASTE,$columnForGender FROM newjs.SEARCH_$oppositeGender S LEFT JOIN newjs.SEARCH_".$oppositeGender."_REV AS R ON S.PROFILEID=R.PROFILEID WHERE LAST_LOGIN_DT >= DATE_SUB(CURDATE(), INTERVAL $days_since_last_login DAY)";
	mysqlquerydebug($sql,$db) or mysql_error_mail(mysql_error($db));
        echo "\n".date("Y-m-d --- H:i:s")."\n";

	//$sql = "UPDATE $databaseName.TEMP_CONTACTS_CACHE_LEVEL2_".$gender."_RENAME AS T INNER JOIN $databaseName.SUGGESTED_PROFILEIDS_AGE_$oppositeGender AS M ON M.PROFILEID = T.RECEIVER SET T.AGE = M.AGE, T.PRIORITY = IF(HAVEPHOTO='Y',IF(PHOTO_DISPLAY='C',3,1),IF(HAVEPHOTO='U',5,7))";
	$sql = "UPDATE $databaseName.TEMP_CONTACTS_CACHE_LEVEL2_".$gender."_RENAME AS T INNER JOIN $databaseName.SUGGESTED_PROFILEIDS_AGE_AND_REV_$oppositeGender AS M ON M.PROFILEID = T.RECEIVER SET T.AGE = M.AGE, T.PRIORITY = T.PRIORITY+1,T.PARTNER_MSTATUS=M.PARTNER_MSTATUS,T.PARTNER_CITYRES=M.PARTNER_CITYRES,T.PARTNER_COUNTRYRES=M.PARTNER_COUNTRYRES,T.PARTNER_HANDICAPPED=M.PARTNER_HANDICAPPED, T.PARTNER_RELIGION=M.PARTNER_RELIGION, T.PARTNER_CASTE=M.PARTNER_CASTE,T.PARTNER_LAGE=M.PARTNER_LAGE, T.PARTNER_HAGE=M.PARTNER_HAGE, T.PARTNER_LHEIGHT=M.PARTNER_LHEIGHT, T.PARTNER_HHEIGHT=M.PARTNER_HHEIGHT,T.".$columnForGender."=M.".$columnForGender;
	mysqlquerydebug($sql,$db) or mysql_error_mail(mysql_error($db));
        echo "\n".date("Y-m-d --- H:i:s")."\n";
        
//        $sql = "UPDATE $databaseName.TEMP_CONTACTS_CACHE_LEVEL2_".$gender."_RENAME AS T INNER JOIN $databaseName.SUGGESTED_PROFILEIDS_AGE_$oppositeGender AS M ON M.PROFILEID = T.RECEIVER SET T.AGE = M.AGE, T.PRIORITY = T.PRIORITY+1";
//	mysqlquerydebug($sql,$db) or mysql_error_mail(mysql_error($db));

	$sql = "UPDATE $databaseName.TEMP_CONTACTS_CACHE_LEVEL2_".$gender."_RENAME AS T INNER JOIN $databaseName.SUGGESTED_PROFILEIDS_AGE_AND_REV_$oppositeGender AS M ON M.PROFILEID = T.RECEIVER SET PRIORITY = PRIORITY+1 WHERE M.LAST_LOGIN_DT <= DATE_SUB(CURDATE(), INTERVAL $days_since_last_login_priority DAY)";
	mysqlquerydebug($sql,$db) or mysql_error_mail(mysql_error($db));
        echo "\n".date("Y-m-d --- H:i:s")."\n";
	
	$sql = "DROP TABLE $databaseName.SUGGESTED_PROFILEIDS_AGE_AND_REV_$oppositeGender";
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
	
	


