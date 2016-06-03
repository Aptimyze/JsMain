<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

chdir(dirname(__FILE__));
include_once("connect.inc");
ini_set('max_execution_time','0');
ini_set('memory_limit',-1);

$db_slave=connect_slave();
mysql_query("set session wait_timeout=10000",$db_slave);

$db=connect_db();
mysql_query("set session wait_timeout=10000",$db);

$tables['M']='SEARCH_FEMALE';
$tables['F']='SEARCH_MALE';
foreach($tables as $k=>$v)
{
	$sql="SELECT PROFILEID FROM $v WHERE MOD_DT>'2009-10-21'";
	$sql="SELECT PROFILEID FROM $v WHERE 1";
	$res= mysql_query($sql,$db) or die(mysql_error1($db));
	while($row=mysql_fetch_array($res))
	{
		$pid=$row['PROFILEID'];
		$sql_check="SELECT PROFILEID FROM JPROFILE WHERE PROFILEID=$pid AND GENDER='$k'";
		$res_check=mysql_query($sql_check,$db_slave) or die(mysql_error1($db_slave));
		if(mysql_num_rows($res_check))
			$profile_arr[]=$pid;
	}
	if(is_array($profile_arr))
	{
		$profile_str=implode(",",$profile_arr);
echo		$sql_del="DELETE FROM $v WHERE PROFILEID IN ($profile_str)";
		$res_del=mysql_query($sql_del,$db)or die(mysql_error1($db));
		if($k=='M')
			$tab="SEARCH_FEMALE_REV";
		else
			$tab="SEARCH_MALE_REV";
			
		echo $sql_del="DELETE FROM $tab WHERE PROFILEID IN ($profile_str)";
		$res_del=mysql_query($sql_del,$db)or die(mysql_error1($db));
		unset($profile_arr);
		$profile_str='';
	}
	echo "--------------------------".":do"."-----";
}

function mysql_error1($db)
{
        echo $msg=mysql_error($db);
        mail("neha.verma@jeevansathi.com","Error in cron for rectify search tables",$msg);
}

