<?php
include("connect.inc");
$dbSlave=connect_slave();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbSlave);

$dbM=connect_db(); //can be slave as well.
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);

$sql="SELECT PROFILEID FROM test.DELETE_PROFILE_BACKLOGS WHERE STATUS='N'";
$result=mysql_query($sql,$dbSlave) or mysql_error1(mysql_error($dbSlave).$sql);
while($myrow=mysql_fetch_array($result))
{
	$pid=$myrow["PROFILEID"];
//echo "\n\n".$pid;
	$sql="SELECT ACTIVATED FROM newjs.JPROFILE WHERE PROFILEID='$pid'";
	$res=mysql_query($sql,$dbM) or mysql_error1(mysql_error($dbM).$sql);
	$row=mysql_fetch_array($res);
	$activated=$row["ACTIVATED"];
	if($activated=='D')
	{
		$status='Y';
		$path = $_SERVER['DOCUMENT_ROOT']."/profile/deleteprofile_bg.php $pid >  /dev/null";
		$cmd = "/usr/bin/php -q ".$path;
		passthru($cmd);
	}
	else
		$status="X";	

	$sql="UPDATE test.DELETE_PROFILE_BACKLOGS SET STATUS='$status' WHERE PROFILEID='$pid'";
	mysql_query($sql,$dbSlave) or mysql_error1(mysql_error($dbSlave).$sql);
}

function mysql_error1($msg)
{
        //echo $msg;
        //die;
        mail("lavesh.rawat@jeevansathi.com,lavesh.rawat@gmail.com","deleteprofile_bg_autocommit_final.php",$msg);
	exit;
}
?>	

