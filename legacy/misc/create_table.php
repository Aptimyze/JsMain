<?php
/********************************************************/
/* Filename: create_table.php
   This query is called for Patitioning the table VIEW_LOG_TRIGGER
    into 10 tables (Horizontal partioning)  
    Created by Nikhil dhiman 
    On 26 May 2006                                      */
/********************************************************/

include_once("connect.inc");	
$db=connect_slave()		//enable this if run on slave
//$db=connect_db();		//enable this if run on master
$temp="TEMP_";			// set $temp='' when run on master
@mysql_select_db("newjs",$db);
for($i=1;$i<11;$i++)
 {
		$sSQL="CREATE TABLE `VIEW_LOG_TRIGGER_$temp$i` (  `VIEWER` mediumint(8) unsigned NOT NULL default '0',  `VIEWED` mediumint(8) unsigned NOT NULL default '0',  `DATE` datetime NOT NULL default '0000-00-00 00:00:00',  UNIQUE KEY `VIEWED` (`VIEWED`,`VIEWER`)) ENGINE=MyISAM DEFAULT CHARSET=latin1;";

	mysql_query_decide($sSQL);
 }
?>

