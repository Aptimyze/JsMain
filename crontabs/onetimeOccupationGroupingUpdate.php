<?php 
/**
 * This file updates the occupation grouping for new and changed occupation values
 */
$flag_using_php5 = 1;
include_once("/usr/local/scripts/DocRoot.php");
include("config.php");
include("connect.inc");


include_once(JsConstants::$docRoot."/classes/Mysql.class.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
include_once(JsConstants::$docRoot."/classes/globalVariables.Class.php");
include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");


$updateArray = array(
    0 => array('TABLE_NAME'=>'newjs.JPARTNER','IS_MASTER'=>FALSE,'OCCUPATION_VALUE_FIELD'=>' PARTNER_OCC','OCCUPATIN_GROUP_FIELD'=>'OCCUPATION_GROUPING','IS_SINGLE_QUOTE' => TRUE),
    1 => array('TABLE_NAME'=>'newjs.SEARCH_MALE','IS_MASTER'=>TRUE,'OCCUPATION_VALUE_FIELD'=>'OCCUPATION','OCCUPATIN_GROUP_FIELD'=>'OCCUPATION_GROUPING','IS_SINGLE_QUOTE' => FALSE),
    2 => array('TABLE_NAME'=>'newjs.SEARCH_FEMALE','IS_MASTER'=>TRUE,'OCCUPATION_VALUE_FIELD'=>'OCCUPATION','OCCUPATIN_GROUP_FIELD'=>'OCCUPATION_GROUPING','IS_SINGLE_QUOTE' => FALSE),
    3 => array('TABLE_NAME'=>'newjs.SEARCH_AGENT','IS_MASTER'=>TRUE,'OCCUPATION_VALUE_FIELD'=>'OCCUPATION','OCCUPATIN_GROUP_FIELD'=>'OCCUPATION_GROUPING','IS_SINGLE_QUOTE' => FALSE),
    4 => array('TABLE_NAME'=>'search.LATEST_SEARCHQUERY','IS_MASTER'=>TRUE,'OCCUPATION_VALUE_FIELD'=>'OCCUPATION','OCCUPATIN_GROUP_FIELD'=>'OCCUPATION_GROUPING','IS_SINGLE_QUOTE' => FALSE),
    );  
// Master and slave connection object
global $mysqlObjS , $mysqlObjM;

$mysqlObjM = new Mysql;
$connMaster = $mysqlObjM->connect("master") or logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$connMaster);

$sql="SET @DONT_UPDATE_TRIGGER=1";
mysql_query($sql,$connMaster) or die(mysql_error().$sql);

$mysqlObjS = new Mysql;
$connSlave = $mysqlObjS->connect("slave") or logError("Unable to connect to slave","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$connSlave);



foreach ($updateArray as $key => $tableArr) 
{
    if($tableArr['TABLE_NAME'] == 'newjs.JPARTNER')
    {
        for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
        {
            $connArray = getShardConnection($activeServerId);  
            if($connArray['slave'] && $connArray['master'])
            {
                updateOccupationGrouping($tableArr["TABLE_NAME"],$tableArr['OCCUPATION_VALUE_FIELD'],$tableArr['OCCUPATION_VALUE_FIELD'],$connArray['slave'],$connArray['master'],$tableArr['IS_SINGLE_QUOTE']);
            }
        }
    }
    else
    {
        echo "I am in else.";
     updateOccupationGrouping($tableArr["TABLE_NAME"],$tableArr['OCCUPATION_VALUE_FIELD'],$tableArr['OCCUPATION_VALUE_FIELD'],$connSlave,$connMaster);              
    }
}



function updateOccupationGrouping($tableName,$occupationValueField,$occupationGroupField,$slaveConn,$masterConn,$isSingleQuote)
{
    echo "I am in update.";
    global $mysqlObjS , $mysqlObjM;

    $selectSql = "SELECT PROFILEID,$occupationValueField from $tableName where ".$occupationValueField." != ''";


    // print_r($selectSql);
    // die();
    $result = $mysqlObjS->executeQuery($selectSql,$slaveConn) or $mysqlObjS->logError($selectSql);
    while($row = $mysqlObjS->fetchAssoc($result))
    { 
        if ( $row['PARTNER_OCC'] )
        {   
            $occupationGroups = CommonFunction::getOccupationGroups($row['PARTNER_OCC'],$isSingleQuote);
            $occupationValues = CommonFunction::getOccupationValues($occupationGroups,$isSingleQuote);

            $updateSql = 'UPDATE '.$tableName.' SET '.$occupationValueField.' = "'.$occupationValues .'" AND '.$occupationGroupField.'= "'.$occupationGroups.'" WHERE PROFILEID = '.$row['PROFILEID'];
            // echo "Return groupings are: ";

            // print_r($updateSql);
            // print_r($occupationGroups);
            
            // echo "Return values are: ";
            // print_r($occupationValues);

            // die(print_r($occupationValues));
            $mysqlObjM->executeQuery($updateSql,$masterConn) or $mysqlObjM->logError($updateSql);
            // die("inserted.");

        }
    }
}
/**
 * 
 * @global Mysql $mysqlObjM
 * @global Mysql $mysqlObjS
 * @param type $activeServerId active server id
 * @return type array of master and slave connection
 */
function getShardConnection($activeServerId){
    global $mysqlObjM, $mysqlObjS;
    
    $myDbName=getActiveServerName($activeServerId,'master',$mysqlObjM);
    $shardConnMaster=$mysqlObjM->connect("$myDbName");
    mysql_query('set session wait_timeout=86400,interactive_timeout=86400,net_read_timeout=86400',$shardConnMaster);
    $sql="SET @DONT_UPDATE_TRIGGER=1";
    mysql_query($sql,$shardConnMaster) or die(mysql_error().$sql);

    $myDbName=getActiveServerName($activeServerId,'slave',$mysqlObjS);
    $shardConnSlave=$mysqlObjS->connect("$myDbName");
    mysql_query('set session wait_timeout=86400,interactive_timeout=86400,net_read_timeout=86400',$shardConnSlave);
    return array('master'=>$shardConnMaster,'slave'=>$shardConnSlave);
}


?>