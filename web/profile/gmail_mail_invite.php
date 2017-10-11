<?
	ini_set('max_execution_time','0');
	ini_set(memory_limit,-1);
	ini_set(mysql.connect_timeout,-1);
	ini_set(default_socket_timeout,25920000);
	ini_set(log_errors_max_len,0);
	chdir(dirname(__FILE__));
	include "connect.inc";
	$smarty->assign("IMG_URL2","http://ser4.jeevansathi.com");
	$db=connect_slave81();
	mysql_query_decide("set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000");

	$sql="select EMAIL from bot_jeevansathi.SEND_INVITE where SENT<>'Y'";
	$res=mysql_query_decide($sql) or die(mysql_error_js());
	$db2=connect_db();

	mysql_query_decide("set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000",$db2);
	while($row=mysql_fetch_array($res))
	{
		
		$data=$smarty->fetch("gtalk_mailer.htm");
		$to=$row['EMAIL'];
		$response_msg=$data;
		$subject="Find your jeevansathi on google talk.";
		$from="info@jeevansathi.com";
		$to='dhiman_nikhil@yahoo.com';
		send_email($to,$response_msg,$subject,$from);
		$sql="update bot_jeevansathi.SEND_INVITE set SENT='Y' where EMAIL='$row[EMAIL]'";
		mysql_query_decide($sql,$db2) or die(mysql_error_js($db2)); 

	}

	
?>	
