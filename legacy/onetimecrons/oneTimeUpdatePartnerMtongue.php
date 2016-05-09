<?php
/*********************************************************************************************
Script name     :      	oneTimeUpdatePartnerMtongue.php 
Script Type     :       One time
Created On      :       20 Mar 13
Created By      :       Nitesh Sethi
Description     :       Updates PARTNER_MTONGUE in JPARTNER table having column entry as '10,19,33,7,28,13,41'
**********************************************************************************************/
$flag_using_php5=1;
include_once("../connect.inc");
$mysqlObj=new Mysql;
global $activeServers,$noOfActiveServers,$slave_activeServers;
//print_r($activeServers);
//echo $noOfActiveServers;
//print_r($slave_activeServers);
for($i=0;$i<$noOfActiveServers;$i++)
{
	$myDbName=$activeServers[$i];
	$myDb=$mysqlObj->connect("$myDbName");
	mysql_query("set session wait_timeout=10000",$myDb);
	
	$myDbNameSlave = $slave_activeServers[$i];
	$myDbSlave=$mysqlObj->connect("$myDbNameSlave");
	mysql_query("set session wait_timeout=10000",$myDbSlave);
	

	$sql_select = "SELECT PROFILEID ,PARTNER_MTONGUE FROM JPARTNER WHERE PARTNER_MTONGUE LIKE '%\'10\,19\,33\,7\,28\,13\,41\'%'  OR PARTNER_MTONGUE LIKE '%#%'";
	$res_select=$mysqlObj->executeQuery($sql_select,$myDbSlave);
	
	while($row=mysql_fetch_array($res_select))
	{
		$profile_mtongue[$row["PROFILEID"]] = $row["PARTNER_MTONGUE"];
	}
	
	foreach($profile_mtongue as $key=>$val)
	{		
		$mtongue_string=trim($val,"'");
		
		//keywords contain array of all mtongue values specified in the string
		$keywords = preg_split("/\|#\|+|','+|,/", "$mtongue_string");
	
		$keywords=array_unique($keywords);
		//print_r($keywords);
				
		$val = implode("','",$keywords);
		//echo "\n\n\n";
		$sql = "UPDATE JPARTNER set PARTNER_MTONGUE =\"'".$val."'\" where PROFILEID=".$key;
		//echo $sql."\n\n\n";
		$mysqlObj->executeQuery($sql,$myDb) or die(mysql_error());
		
		
		$sql_insert_updateProfileId = "INSERT IGNORE INTO MTONGUE_UPDATE_MIS set PROFILEID =".$key;
	   // echo $sql_insert_updateProfileId."\n\n\n";
		$mysqlObj->executeQuery($sql_insert_updateProfileId,$myDb) or die(mysql_error());
		
	}

	unset($profile_mtongue);
	
}
?>
