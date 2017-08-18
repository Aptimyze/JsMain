<?php
//INCLUDE FILES HERE
  $curFilePath = dirname(__FILE__)."/";
 include_once("/usr/local/scripts/DocRoot.php");

include_once($_SERVER['DOCUMENT_ROOT']."/profile/config.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/shardingRelated.php");
//INCLUDE FILE ENDS

//MAKE CONNECTION TO MASTER AND SLAVE
$mysqlObjM = new Mysql;
$dbM = $mysqlObjM->connect("master") or logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);

$mysqlObjS = new Mysql;
$dbS = $mysqlObjS->connect("slave") or logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbS);

$old_value = array(27);
$new_value = array(19);

$activeServerId = $_SERVER['argv'][1];

        $myDbName=getActiveServerName($activeServerId,'master',$mysqlObjM);
        $shardDbM=$mysqlObjM->connect("$myDbName");
        mysql_query('set session wait_timeout=86400,interactive_timeout=86400,net_read_timeout=86400',$shardDbM);

        $myDbName=getActiveServerName($activeServerId,'slave',$mysqlObjS);
        $shardDbS=$mysqlObjS->connect("$myDbName");
        mysql_query('set session wait_timeout=86400,interactive_timeout=86400,net_read_timeout=86400',$shardDbS);

	////'ABC','BCD','FGH'
	comma_separated_type1_update($old_value,$new_value,"JPARTNER","PARTNER_ELEVEL_NEW","newjs","PROFILEID",$mysqlObjM,$mysqlObjS,$shardDbM,$shardDbS);
	echo "SHARD".$activeServerId." newjs|JPARTNER|PARTNER_ELEVEL_NEW|PROFILEID \n";
	
	mysql_close($shardDbS);
	mysql_close($shardDbM);

//CLOSE DATABASE CONNECTION
mysql_close($dbM);
mysql_close($dbS);
//CLOSING ENDS

//'ABC','BCD','FGH'
function comma_separated_type1_update($old_value,$new_value,$table_name,$column_name,$db_name,$primary_key,$mysqlObjM,$mysqlObjS,$dbM,$dbS)
{
        foreach($old_value as $k=>$v)
        {
                $select_statement = "SELECT ".$primary_key.",".$column_name." FROM ".$db_name.".".$table_name." WHERE ".$column_name." REGEXP (\"'".$v."'\") = 1";
//              echo $select_statement."\n";
                $result = $mysqlObjS->executeQuery($select_statement,$dbS) or $mysqlObjS->logError($select_statement);
                while($row = $mysqlObjS->fetchArray($result))
                {
                        $data = explode(",",$row[$column_name]);
                        foreach ($data as $kk=>$vv)
                        {
                                $vv = rtrim($vv,"'");
                                $vv = ltrim($vv,"'");
                                if (trim($vv) == $v)
                                {
					unset($data[$kk]);
                                        //$data[$kk] = "'".$new_value[$k]."'";
                                }
                        }
                        $newUpdateVal = implode(",",array_unique($data));

                  	$update_statement = "UPDATE ".$db_name.".".$table_name." SET ".$column_name." = \"".$newUpdateVal."\" WHERE ".$column_name." = \"".$row[$column_name]."\" AND ".$primary_key." = ".$row[$primary_key];
//                      echo $update_statement."\n";
                        $mysqlObjM->executeQuery($update_statement,$dbM) or $mysqlObjM->logError($update_statement);
                }
        }
}

?>
