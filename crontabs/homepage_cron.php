<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

/***************************************************************************************************************
* FILE NAME     : homepage_cron.php
* DESCRIPTION   : Cron script for not displaying homepage profiles that have more than 150 open contacts.
* CREATION DATE : 15 December, 2005
* CREATED BY    : Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*****************************************************************************************************************/
$flag_using_php5=1;

include "connect.inc";
$db=connect_db();

$sql="UPDATE newjs.HOMEPAGE_PROFILES SET DISPLAY='Y'";
$res=mysql_query($sql) or logError("Error while updating newjs.HOMEPAGE_PROFILES",$sql);

$sql="SELECT PROFILEID FROM newjs.HOMEPAGE_PROFILES";
$res=mysql_query($sql) or logError("Error while selecting profileids from newjs.HOMEPAGE_PROFILES",$sql);
while($row=mysql_fetch_array($res))
{
	$profiles[]=$row['PROFILEID'];
}

for($temp=0;$temp<count($profiles);$temp++)
{
	//Sharding On Contacts done by Lavesh Rawat
        $contactResult=getResultSet("count(*) as CNT","","",$profiles[$temp],"","'I'");
	$row['CNT']=$contactResult[0]['CNT'];
	if($row['CNT']>=50)
	{
		$removeable[]=$profiles[$temp];
	}
}

if(count($removeable)>=1)
	$remove=implode("','",$removeable);

$sql="UPDATE newjs.HOMEPAGE_PROFILES SET DISPLAY='N' WHERE PROFILEID IN ('".$remove."')";
$res=mysql_query($sql) or logError("Error while updating HOMEPAGE_PROFILES".$sql);

?>
