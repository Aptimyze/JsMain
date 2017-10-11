<?php
/*********************************************************************************************
Script name     :      	oneTimeRemoveDM.php 
Script Type     :       One time
Created On      :       23 Sep 09
Created By      :       Tanu Gupta
Description     :       Updates entries in JPARTNER table where value of columns set as "DM"
**********************************************************************************************/

include("connect.inc");

$mysqlObj=new Mysql;
global $activeServers,$noOfActiveServers,$slave_activeServers;
for($i=0;$i<$noOfActiveServers;$i++)
{
	$myDbName=$activeServers[$i];
	$myDb=$mysqlObj->connect("$myDbName");
	mysql_query("set session wait_timeout=10000",$myDb);
	
	$myDbNameSlave = $slave_activeServers[$i];
	$myDbSlave=$mysqlObj->connect("$myDbNameSlave");
	mysql_query("set session wait_timeout=10000",$myDbSlave);

	$sql = "SELECT PROFILEID FROM JPARTNER WHERE PARTNER_MTONGUE LIKE \"%DM%\"";
	echo $sql."\n\n\n";
	$res=$mysqlObj->executeQuery($sql,$myDbSlave);
	while($row=mysql_fetch_array($res))
	{
		$profile_mtongue[] = "'".$row["PROFILEID"]."'";
	}
	if($profile_mtongue)
	{
		$profile_mtongue_str = implode(",",$profile_mtongue);
		$sql = "UPDATE JPARTNER SET PARTNER_MTONGUE = '' WHERE PROFILEID IN ($profile_mtongue_str)";
		$mysqlObj->executeQuery($sql,$myDb) or die(mysql_error());	
		echo $sql."\n";
	}

	$sql = "SELECT PROFILEID FROM JPARTNER WHERE PARTNER_MSTATUS LIKE \"%DM%\"";
	echo $sql."\n\n\n";
	$res=$mysqlObj->executeQuery($sql,$myDbSlave);
	while($row=mysql_fetch_array($res))
	{
		$profile_mstatus[] = "'".$row["PROFILEID"]."'";
	}
	if($profile_mstatus)
	{
		$profile_mstatus_str = implode(",",$profile_mstatus);
		$sql = "UPDATE JPARTNER SET PARTNER_MSTATUS = '' WHERE PROFILEID IN ($profile_mstatus_str)";
		$mysqlObj->executeQuery($sql,$myDb);
		echo $sql."\n";
	}

	$sql = "SELECT PROFILEID FROM JPARTNER WHERE PARTNER_RELIGION LIKE \"%DM%\"";
	echo $sql."\n\n\n";
	$res=$mysqlObj->executeQuery($sql,$myDbSlave);
	while($row=mysql_fetch_array($res))
	{
		$profile_religion[] = "'".$row["PROFILEID"]."'";
	}
	if($profile_religion)
	{
		$profile_religion_str = implode(",",$profile_religion);
		$sql = "UPDATE JPARTNER SET PARTNER_RELIGION = '' WHERE PROFILEID IN ($profile_religion_str)";
		$mysqlObj->executeQuery($sql,$myDb);
		echo $sql."\n";
	}

	$sql = "SELECT PROFILEID FROM JPARTNER WHERE PARTNER_CASTE LIKE \"%DM%\"";
	echo $sql."\n\n\n";
	$res=$mysqlObj->executeQuery($sql,$myDbSlave);
	while($row=mysql_fetch_array($res))
	{
		$profile_caste[] = "'".$row["PROFILEID"]."'";
	}
	if($profile_caste)
	{
		$profile_caste_str = implode(",",$profile_caste);
		$sql = "UPDATE JPARTNER SET PARTNER_CASTE = '' WHERE PROFILEID IN ($profile_caste_str)";
		$mysqlObj->executeQuery($sql,$myDb);
		echo $sql."\n";
	}
}
?>
