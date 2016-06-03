<?php
	include("connect.inc");
	
	$db=connect_db();
	
	$sql="insert into MIS.POLL(RESPONSE,IPADD,DATE,POLLNO) values ('$Q1','" . FetchClientIP() . "',now(),'$pollno')";
	mysql_query_decide($sql);
	
	$smarty->display("poll.htm");
?>
