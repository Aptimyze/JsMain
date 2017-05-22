<?php
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");
include($_SERVER['DOCUMENT_ROOT']."/sugarcrm/custom/crons/housekeepingConfig.php");
include($_SERVER['DOCUMENT_ROOT']."/profile/connect_db.php");
$db=connect_db();

$dt=date("Y-m-d");

/*$sql="UPDATE sugarcrm.leads,sugarcrm.leads_cstm SET status='32',disposition_c='26' WHERE id=id_c AND deleted='1' AND status!='47' AND disposition_c!='27'";
mysql_query($sql,$db);*/

global $partitionsArray,$backupTablesArr;
foreach($partitionsArray as $partition=>$partitionArray)
{
	foreach($partitionArray as $condition=>$conditionArray)
	{
		if($condition=='status')
		{
			if(is_array($conditionArray))
			{
				unset($statusConditionString);
				unset($disConditionString);
				foreach($conditionArray as $statusVal=>$disValArray)
				{
					$statusConditionString.="'$statusVal',";
					foreach($disValArray as $disVal)
						$disConditionString.="'$disVal',";
				}
				$statusConditionString=trim($statusConditionString,",");
				$disConditionString=trim($disConditionString,",");
			}
		}
		else
			$conditionString="'".implode("','",$conditionArray)."'";
		foreach($backupTablesArr as $table=>$tableInfo)
		{
			unset($baseTable);
			unset($partitionTable);
			unset($joinField);
			unset($whereArr);
			unset($backupSql);
			unset($deleteSql);
			unset($result);
			unset($join);
			$flag=1;
			$leadFlag=1;
			$whereCondition="";
			$joinCondition="";
			$baseTable="sugarcrm.$table";
			$partitionTable="sugarcrm_housekeeping.$partition"."_".$table;
			if($tableInfo["joinField"])
			{
				$join=1;
				$joinField=$tableInfo["joinField"];
				if($join)
				{
					if($table=='email_addresses')
					{
						if($condition=='deleted')
							$joinCondition=" JOIN sugarcrm.email_addr_bean_rel ON $baseTable.$joinField=email_addr_bean_rel.email_address_id JOIN sugarcrm.leads ON email_addr_bean_rel.bean_id=leads.id";
						elseif($condition=='mtongue' || $condition=='status')
							$joinCondition=" JOIN sugarcrm.email_addr_bean_rel ON $baseTable.$joinField=email_addr_bean_rel.email_address_id JOIN sugarcrm.leads ON email_addr_bean_rel.bean_id=leads.id JOIN sugarcrm.leads_cstm on leads.id=leads_cstm.id_c";
					}
					else
					{
						if($condition=='deleted')
							$joinCondition=" JOIN sugarcrm.leads ON $baseTable.$joinField=leads.id";
						elseif($condition=='status')
						{
							if($table!='leads_cstm')
								$joinCondition=" JOIN sugarcrm.leads ON $baseTable.$joinField=leads.id JOIN sugarcrm.leads_cstm on leads.id=leads_cstm.id_c";
							else
								$joinCondition=" JOIN sugarcrm.leads ON leads.id=leads_cstm.id_c";
						}
						elseif($condition=='mtongue')
						{
							$joinCondition=" JOIN sugarcrm.leads ON $baseTable.$joinField=leads.id ";
							if($table!='leads_cstm')
								$joinCondition.=" JOIN sugarcrm.leads_cstm on leads.id=leads_cstm.id_c";
						}
					}
				}
			}
			if($condition=='status')
			{
				$whereArr[]="leads.status in ($statusConditionString)";
				$whereArr[]="leads_cstm.disposition_c in ($disConditionString)";
			}
			elseif($condition=='mtongue')
				$whereArr[]="leads_cstm.mother_tongue_c in ($conditionString)";
			if($tableInfo["whereCondition"])
				$whereArr[]=$tableInfo["whereCondition"];
			if($partition=='inactive')
			{
				if($condition=='deleted')
					$whereArr[]="leads.deleted='1'";
				else
					$whereArr[]="leads.date_entered<DATE_SUB('$dt',INTERVAL 45 DAY)";
			}
			if(is_array($whereArr))
				$whereCondition=" WHERE ".implode(" AND ",$whereArr);
			if($tableInfo["backup"])
			{
				$backupSql="REPLACE INTO $partitionTable SELECT $baseTable.* FROM $baseTable$joinCondition$whereCondition";
				//echo "\n\n\n".$backupSql;
				mysql_query($backupSql,$db) or $flag=0;
				if($flag==0)
                                {
                                        $error="Error while inserting data in $partitionTable for $condition";
                                        $error.="\n".$backupSql."\n".mysql_error($db);
					echo $error;
                                        mail("sadaf.alam@jeevansathi.com","Error while inserting in sugar housekeeping",$error);
                                        die;
                                }
                                else
                                {
                                        if($table=='email_addresses' || $table=='email_addr_bean_rel')
                                        {
                                                $noOfRows=mysql_affected_rows($db);
                                                $msg="Rows inserted for condition $condition in $partitionTable : $noOfRows";
						echo "\n".$msg;
                                                mail("sadaf.alam@jeevansathi.com","Sugar housekeeping info",$msg);
                                        }
                                }
				if($table=='leads_cstm')
				{
					$backupSql=str_replace($partition."_leads_cstm",$partition."_leads",$backupSql);
					$backupSql=str_replace("sugarcrm.leads_cstm.*","sugarcrm.leads.*",$backupSql);
					echo "\n\n\n$backupSql";
					mysql_query($backupSql,$db) or $leadFlag=0;
					if($leadFlag==0)
					{
						$error="Error while inserting data in ".$partition."_leads for $condition";
						$error.="\n".$backupSql."\n".mysql_error($db);
						echo $error;
						mail("sadaf.alam@jeevansathi.com","Error while inserting in sugar housekeeping",$error);
						die;
					}
				}
			}
			if($flag)
			{
				$deleteFlag=1;
                                $deleteSql="DELETE $baseTable.* FROM $baseTable$joinCondition$whereCondition";
                                echo "\n\n\n$deleteSql";
                                mysql_query($deleteSql,$db) or $deleteFlag=0;
                                if($deleteFlag==0)
                                {
                                        $error="Error while deleting data from $baseTable for $condition";
                                        $error.="\n".$deleteSql."\n".mysql_error($db);
					echo $error;
                                        mail("sadaf.alam@jeevansathi.com","Error while deleting in sugar housekeeping",$error);
                                        die;
                                }
				if($table=='leads_cstm' && $leadFlag)
				{
					$leadDeleteFlag=1;
					if($condition=='deleted')
						$deleteSql="DELETE FROM sugarcrm.leads WHERE leads.deleted='1'";
					elseif($condition=='status' || $condition=='mtongue')
					{
						$deleteSql=str_replace("leads_cstm",$partition."_leads_cstm",$deleteSql);
						$deleteSqlArr=explode("WHERE",$deleteSql);
						$deleteSql="DELETE sugarcrm.leads.* FROM sugarcrm.leads JOIN sugarcrm_housekeeping.".$partition."_leads_cstm ON leads.id=".$partition."_leads_cstm.id_c ";
						$deleteSql.="WHERE ".trim($deleteSqlArr[1]);
					}
					echo "\n\n\n$deleteSql";
					mysql_query($deleteSql,$db) or $leadDeleteFlag=0;
					if($leadDeleteFlag==0)
					{
						$error="Error while deleting data from leads for $condition";
						$error.="\n".$deleteSql."\n".mysql_error($db);
						echo $error;
						mail("sadaf.alam@jeevansathi.com","Error while deleting in sugar housekeeping",$error);
						die;
					}
				}
			}
		}
	}
}
?>
