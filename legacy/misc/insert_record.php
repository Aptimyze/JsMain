<?php
/********************************************************/
/*Filename: insert_record.php
 
 This Script inserts the records into partition table from VIEW_LOG_TRIGGER 
 Table based on Profileid of the User's
    Created by Nikhil dhiman
    On 26 May 2006                                      */
/********************************************************/

include_once("connect.inc");
$db=connect_slave();
$temp="TEMP_";
@mysql_select_db("newjs",$db);
 
for($i=1;$i<11;$i++)
{
	$sSQL="alter table VIEW_LOG_TRIGGER_$temp$i disable keys";
	mysql_query_decide($sSQL);
	$min=($i-1)*300000;
	$max=$i*300000;
	$sSQL="insert into VIEW_LOG_TRIGGER_$temp$i select * from VIEW_LOG_TRIGGER where VIEWED>$min and VIEWED<=$max";
	mysql_query_decide($sSQL);
	$sSQL="alter table VIEW_LOG_TRIGGER_$temp$i enable keys";
	mysql_query_decide($sSQL);
}
?>
	
