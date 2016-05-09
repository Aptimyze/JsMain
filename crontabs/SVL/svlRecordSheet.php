<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

/***************************************************************************************************************************
Filename    : svlRecordSheet.php
Description : Display the data of discripancy(if any) after verification of sharding.
Created By  : Vibhor Garg
Created On  : 10 Jun 2008
****************************************************************************************************************************/

$path =$_SERVER[DOCUMENT_ROOT];
chdir($path);
include_once($path."/classes/Mysql.class.php");
include_once($path."/profile/connect_db.php");
include_once($path."/classes/shardingRelated.php");

$mysqlObj=new Mysql;

//Take the connection on shard on which data is stored for verification and final output available. 
$myDbName_dump=getActiveServerName(2);
$myDbarr[$myDbName_dump]=$mysqlObj->connect("$myDbName_dump");
$myDb_dump=$myDbarr[$myDbName_dump];

$track_time=date("Y-m-d H:i:s");

$count_str="Track Time : $track_time\n\n";

$sql="SELECT COUNT(*) FROM SVL.NO_SHARD";
$res=mysql_query($sql,$myDb_dump) or die(mysql_error($myDb_dump));
$row=mysql_fetch_array($res);
$count1=$row[0];
if($count1)
	$count_str.="Entries is NO_SHARD : "."$count1"."\n";
else
	$count_str.="Entries is NO_SHARD : 0"."\n";

$sql="SELECT COUNT(*) FROM SVL.WRONG_SHARD_SINGLE";
$res=mysql_query($sql,$myDb_dump) or die(mysql_error($myDb_dump));
$row=mysql_fetch_array($res);
$count2=$row[0];
if($count2)
	$count_str.="Entries is WRONG_SHARD_SINGLE : "."$count2"."\n";
else
        $count_str.="Entries is WRONG_SHARD_SINGLE : 0"."\n";

$sql="SELECT COUNT(*) FROM SVL.WRONG_SHARD_DOUBLE";
$res=mysql_query($sql,$myDb_dump) or die(mysql_error($myDb_dump));
$row=mysql_fetch_array($res);
$count3=$row[0];
if($count3)
	$count_str.="Entries is WRONG_SHARD_DOUBLE : "."$count3"."\n";
else
        $count_str.="Entries is WRONG_SHARD_DOUBLE : 0"."\n";

$sql="SELECT COUNT(*) FROM SVL.INCOMPLETE_SHARDING";
$res=mysql_query($sql,$myDb_dump) or die(mysql_error($myDb_dump));
$row=mysql_fetch_array($res);
$count4=$row[0];
if($count4)
	$count_str.="Entries is INCOMPLETE_SHARDING : "."$count4"."\n";
else
        $count_str.="Entries is INCOMPLETE_SHARDING : 0"."\n";

$sql="SELECT COUNT(*) FROM SVL.UNMATCH_SHARDING";
$res=mysql_query($sql,$myDb_dump) or die(mysql_error($myDb_dump));
$row=mysql_fetch_array($res);
$count5=$row[0];
if($count5)
	$count_str.="Entries is UNMATCH_SHARDING : "."$count5"."\n";
else
        $count_str.="Entries is UNMATCH_SHARDING : 0"."\n";

mail("vibhor.garg@jeevansathi.com,vikas.jayna@jeevansathi.com","Latest SVL Record Sheet","$count_str");

?>
