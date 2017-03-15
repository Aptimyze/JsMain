<?php

$flag_using_php5 = 1;
include_once("/usr/local/scripts/DocRoot.php");
include("config.php");
include("connect.inc");
include_once(JsConstants::$docRoot."/classes/Mysql.class.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
include_once(JsConstants::$docRoot."/classes/globalVariables.Class.php");


global $mysqlObjS;

$mysqlObjM = new Mysql;
$connMaster = $mysqlObjM->connect("master") or logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$connMaster);

$mysqlObjS = new Mysql;
$connSlave = $mysqlObjS->connect("slave") or logError("Unable to connect to slave","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$connSlave);


$searchTables = array(0=>array("type"=>"MALE","number"=>"560000"),1=>array("type"=>"FEMALE","number"=>"270000"));

$file = fopen(sfConfig::get("sf_upload_dir")."/SearchLogs/astroCron".$date.".txt","a");

foreach($searchTables as $key=>$val){
    
    $noOfProfiles=0;
    for($counter = 0;$counter<$val["number"];$counter=$counter+2000 ){
        $sqlSearch = "SELECT PROFILEID FROM SEARCH_".$val['type']." LIMIT 2000 OFFSET ".$counter ;
        $profileidArr = $mysqlObjS->executeQuery($sqlSearch,$connSlave) or $mysqlObjS->logError($sqlSearch);
        $profileStr='';
        while($row = $mysqlObjS->fetchAssoc($profileidArr)){
            $profile = $row['PROFILEID'];
            $profileStr .= ",".$profile;
        }
        
        $profileStr = trim($profileStr,",");
        if($profileStr!=''){
            $sqlJprofile = "SELECT J.PROFILEID FROM JPROFILE J LEFT JOIN ASTRO_DETAILS A ON J.PROFILEID=A.PROFILEID WHERE (J.BTIME!='' OR (J.COUNTRY_BIRTH!='' AND J.COUNTRY_BIRTH!=0) OR J.CITY_BIRTH!='') AND A.PROFILEID IS NULL AND J.PROFILEID IN ($profileStr)";
            $result = $mysqlObjS->executeQuery($sqlJprofile,$connSlave) or $mysqlObjS->logError($sqlJprofile);
            while($newRow = $mysqlObjS->fetchAssoc($result)){
                fwrite($file,$newRow['PROFILEID']."\n");
                $noOfProfiles++;
                
                $sqlJprofile = "UPDATE JPROFILE SET BTIME='',COUNTRY_BIRTH='',CITY_BIRTH='' WHERE PROFILEID =".$newRow['PROFILEID'];
		$mysqlObjS->executeQuery($sqlJprofile,$connMaster) or $mysqlObjS->logError($sqlJprofile);
            }
        }
    
    }
    
    fwrite($file,"\n\n\n\ntotal count for ".$val['type'].":   ".$noOfProfiles."\n\n\n\n");
    echo "total count for ".$val['type'].":   ".$noOfProfiles."\n";
    
    
}

fclose($file);
