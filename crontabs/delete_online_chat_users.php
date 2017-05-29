<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

	include("connect.inc");
	
	$db=connect_db();
	
	/***********************************************
	Queries for chat 
	************************************************/
	$query="delete from userplane.users where lastTimeOnline < date_sub( now(), INTERVAL 4 hour )";
	mysql_query($query) or die(mysql_error());
	
	$query="delete from bot_jeevansathi.user_online where lastTimeOnline < date_sub( now(), INTERVAL 1 hour )";
	mysql_query($query) or die(mysql_error());
	/***********************************************/
	
	$query="delete from userplane.recentusers WHERE lastTimeOnline < ( NOW( ) - INTERVAL 15 minute )";
	mysql_query($query) or die(mysql_error());
?>
