<?php


$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");

$flag_using_php5=1;
include("connect.inc");


//for preventing timeout to maximum possible
ini_set('max_execution_time',0);
ini_set('mysql.connect_timeout',-1);
ini_set('default_socket_timeout',259200); // 3 days
ini_set('log_errors_max_len',0);
//for preventing timeout to maximum possible

include("$_SERVER[DOCUMENT_ROOT]/commonFiles/vspCronCommonFunctions.php");

$serverId = $argv[1];

$mysqlObj=new Mysql;

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