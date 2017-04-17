<?php

$flag_using_php5 = 1;
include_once("/usr/local/scripts/DocRoot.php");
include("config.php");
include("connect.inc");
include_once(JsConstants::$docRoot."/classes/Mysql.class.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
include_once(JsConstants::$docRoot."/classes/globalVariables.Class.php");


global $mysqlObjS;

$mysqlObjS = new Mysql;
$connSlave = $mysqlObjS->connect("slave") or logError("Unable to connect to slave","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$connSlave);

$storageTypeMapping = array("TINYINT"=>array('S'=>127,'U'=>255),"SMALLINT"=>array('S'=>32767,'U'=>65535),"MEDIUMINT"=>array('S'=>8388607,'U'=>16777215),"INT"=>array('S'=>2147483647,'U'=>4294967295),"BIGINT"=>array('S'=>9223372036854775807,'U'=>18446744073709551615));


$sqlDatabases = "SHOW DATABASES" ;
$databases = $mysqlObjS->executeQuery($sqlDatabases,$connSlave) or $mysqlObjS->logError($sqlDatabases);

while($row = $mysqlObjS->fetchRow($databases)){
    $databaseNames[] = $row['Database'];
}

foreach ($databaseNames as $k=>$database){
    $sqlTables = "SHOW TABLES IN ".$database ;
    $tables = $mysqlObjS->executeQuery($sqlTables,$connSlave) or $mysqlObjS->logError($sqlTables);

    while($row = $mysqlObjS->fetchRow($tables)){
        $tableKey = "Tables_in_".$database;
        $tableNames[] = $row[$tableKey];
        
    }
    
    foreach ($tableNames as $k1=>$table){
        $sqlTableInfo = "SELECT COLUMN_NAME,DATA_TYPE,COLUMN_TYPE,character_maximum_length FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$table' AND TABLE_SCHEMA='$database' AND EXTRA like '%auto_increment%'";
        $tablesInfo = $mysqlObjS->executeQuery($sqlTableInfo,$connSlave) or $mysqlObjS->logError($sqlTableInfo);
        
        while($row = $mysqlObjS->fetchRow($tablesInfo)){
            $dataType = $row['DATA_TYPE'];
            $lengthOfColumn = $row['character_maximum_length'];
            $columnName = $row['COLUMN_NAME'];
            $columnType = $row['COLUMN_TYPE'];
            if(strpos($columnType,"unsigned"))
                $signedUnsigned = 'U';
            else 
                $signedUnsigned = 'S';
            
            if($columnName && !strpos($table,".")){
                $sqlMaxValue = "SELECT MAX($columnName) FROM ".$database.".".$table;
                $maxValOfColumn = $mysqlObjS->executeQuery($sqlMaxValue,$connSlave) or $mysqlObjS->logError($sqlMaxValue);

                $maxColumnKeyName = 'MAX('.$columnName.')';
                $maxValOfColumn = $mysqlObjS->fetchAssoc($maxValOfColumn)[$maxColumnKeyName];
                if($maxValOfColumn > (99/100*$storageTypeMapping[strtoupper($dataType)][$signedUnsigned]))
                    echo "\n=========================================\nDatabase : ".$database."\nTable : ".$table."\nColumn : ".$columnName."\nCurrent Max value of column : ".$maxValOfColumn."\nType of column : ".$columnType;
            }
        }
        
    }
}
