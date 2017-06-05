<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

/*********************************************************************************************
* FILE NAME             : 1min_cron.php
* DESCRIPTION           : script for updating BACKEND field in JPROFILE_AFFILIATE after 24 hours.
* CREATION DATE         : 28 Oct, 2005
* CREATED BY            : Gaurav Arora
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/
                                                                                                 
include_once("connect.inc");
connect_db();

$sql="select BACKEND,ID,ENTRY_DT from JPROFILE_AFFILIATE where BACKEND='T' AND MOVED='N'";
$res=mysql_query($sql) or logError($sql);//die(mysql_error().$sql);
while($row=mysql_fetch_array($res))
{
	$t1=$row['ENTRY_DT'];
	$t2=date('Y-m-d H:i:s');
	$t=strtotime($t2)-strtotime($t1);	//to get the difference of times
	$t=hhmmss($t);
	$time_diff=explode(":",$t);
	//update BACKEND field to Y
	if($time_diff[0]>=24)
	{
		$sql_update="update JPROFILE_AFFILIATE set BACKEND='Y' where ID=$row[ID]";
		mysql_query($sql_update) or logError($sql_update);//or die(mysql_error().$sql_update);
	}
}

// function to get the hours of a time.
function hhmmss($length) 
{
        $hrs = floor($length / 3600);
        //$min = $length - $hrs * 3600;
        //$min = floor($min / 60);
        //$sec = $length - $hrs * 3600 - $min * 60;
        return  str_pad($hrs,2,'0',STR_PAD_LEFT);
        //return  str_pad($hrs,2,'0',STR_PAD_LEFT) . ':' .
                //str_pad($min,2,'0',STR_PAD_LEFT) . ':' .
                //str_pad($sec,2,'0',STR_PAD_LEFT);
}
?>
