<?php
//INCLUDE FILES HERE
  $curFilePath = dirname(__FILE__)."/";
 include_once("/usr/local/scripts/DocRoot.php");

include_once($_SERVER['DOCUMENT_ROOT']."/profile/config.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/shardingRelated.php");
include_once("state_city_dropdown.php");
//INCLUDE FILE ENDS

//MAKE CONNECTION TO MASTER AND SLAVE
$mysqlObjM = new Mysql;
$dbM = $mysqlObjM->connect("master") or logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);

$mysqlObjS = new Mysql;
$dbS = $mysqlObjS->connect("slave") or logError("Unable to connect to master","ShowErrTemplate");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbS);

foreach($CITY as $k=>$v)
{
	$old_value[] = $k;
	$new_value[] = $v;
}

foreach($new_value as $k=>$v)
{
	$temp = explode(",",$v);
	foreach($temp as $kk=>$vv)
	{
		$temp[$kk] = trim($vv,"'");
	}
	$new_value1[$k] = implode(",",$temp);
}

for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{
        $myDbName=getActiveServerName($activeServerId,'master',$mysqlObjM);
        $shardDbM=$mysqlObjM->connect("$myDbName");
        mysql_query('set session wait_timeout=86400,interactive_timeout=86400,net_read_timeout=86400',$shardDbM);

        $myDbName=getActiveServerName($activeServerId,'slave',$mysqlObjS);
        $shardDbS=$mysqlObjS->connect("$myDbName");
        mysql_query('set session wait_timeout=86400,interactive_timeout=86400,net_read_timeout=86400',$shardDbS);

        comma_separated_type1_update($old_value,$new_value,"JPARTNER","PARTNER_CITYRES","newjs","PROFILEID",$mysqlObjM,$mysqlObjS,$shardDbM,$shardDbS);
        echo "SHARD".$activeServerId." newjs|JPARTNER|PARTNER_CITYRES|PROFILEID \n";

        mysql_close($shardDbS);
        mysql_close($shardDbM);
}

comma_separated_type1_update($old_value,$new_value,"AP_TEMP_DPP","PARTNER_CITYRES","Assisted_Product","CREATED_BY,PROFILEID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "Assisted_Product|AP_TEMP_DPP|PARTNER_CITYRES|CREATED_BY,PROFILEID \n";
comma_separated_type1_update($old_value,$new_value,"AP_DPP_FILTER_ARCHIVE","PARTNER_CITYRES","Assisted_Product","DPP_ID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "Assisted_Product|AP_DPP_FILTER_ARCHIVE|PARTNER_CITYRES|DPP_ID \n";

comma_separated_type3_update($old_value,$new_value1,"SEARCH_AGENT","CITY_RES","newjs","ID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "newjs|SEARCH_AGENT|CITY_RES|ID \n";
comma_separated_type3_update($old_value,$new_value1,"SEARCH_AGENT","RES_STATUS","newjs","ID",$mysqlObjM,$mysqlObjS,$dbM,$dbS);
echo "newjs|SEARCH_AGENT|RES_STATUS|ID \n";


//'ABC','BCD','FGH'
function comma_separated_type1_update($old_value,$new_value,$table_name,$column_name,$db_name,$primary_key,$mysqlObjM,$mysqlObjS,$dbM,$dbS)
{
        foreach($old_value as $k=>$v)
        {
                $select_statement = "SELECT ".$primary_key.",".$column_name." FROM ".$db_name.".".$table_name." WHERE ".$column_name." REGEXP (\"'".$v."'\") = 1";
//              echo $select_statement."<br />";
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
                                        $data[$kk] = $new_value[$k];
                                }
                        }
                        $newUpdateVal = implode(",",array_unique($data));
			$newUpdateValArr = explode(",",$newUpdateVal);
			$newUpdateVal = implode(",",array_unique($newUpdateValArr)); 

                        if($table_name == "AP_TEMP_DPP" && $column_name == "PARTNER_CITYRES")
                                $update_statement = "UPDATE ".$db_name.".".$table_name." SET ".$column_name." = \"".$newUpdateVal."\" WHERE ".$column_name." = \"".$row[$column_name]."\" AND CREATED_BY = \"".$row["CREATED_BY"]."\" AND PROFILEID = ".$row["PROFILEID"];
                        else
                                $update_statement = "UPDATE ".$db_name.".".$table_name." SET ".$column_name." = \"".$newUpdateVal."\" WHERE ".$column_name." = \"".$row[$column_name]."\" AND ".$primary_key." = ".$row[$primary_key];
//                        echo $update_statement."<br />";
       			$mysqlObjM->executeQuery($update_statement,$dbM) or $mysqlObjM->logError($update_statement);
                }
        }
}

//ABC,BCD,LMN
function comma_separated_type3_update($old_value,$new_value,$table_name,$column_name,$db_name,$primary_key,$mysqlObjM,$mysqlObjS,$dbM,$dbS)
{
        foreach($old_value as $k=>$v)
        {
                $select_statement = "SELECT ".$primary_key.",".$column_name." FROM ".$db_name.".".$table_name." WHERE ".$column_name." REGEXP (\"^".$v.",|,".$v.",|,".$v."$|^".$v."$\") = 1";
//              echo $select_statement."<br />";
                $result = $mysqlObjS->executeQuery($select_statement,$dbS) or $mysqlObjS->logError($select_statement);
                while($row = $mysqlObjS->fetchArray($result))
                {
                        $data = explode(",",$row[$column_name]);
                        foreach ($data as $kk=>$vv)
                        {
                                if (trim($vv) == $v)
                                {
                                        $data[$kk] = $new_value[$k];
                                }
                        }
                        $newUpdateVal = implode(",",array_unique($data));

                        $newUpdateValArr = explode(",",$newUpdateVal);
                        $newUpdateVal = implode(",",array_unique($newUpdateValArr));

                        $update_statement = "UPDATE ".$db_name.".".$table_name." SET ".$column_name." = \"".$newUpdateVal."\" WHERE ".$column_name." = \"".$row[$column_name]."\" AND ".$primary_key." = ".$row[$primary_key];
//                      echo $update_statement."<br />";
                        $mysqlObjM->executeQuery($update_statement,$dbM) or $mysqlObjM->logError($update_statement);
                }
        }
}

?>
