<?php
 	$curFilePath = dirname(__FILE__)."/";
 	include_once("/usr/local/scripts/DocRoot.php");

	/* cron added by shubham. it set the ACTIVE  status 'N'  in PSWRDS table of those agents those have not login
	from more than last 50 days on the website */	
	chdir("$docRoot/crontabs/crm");
	include("$docRoot/crontabs/connect.inc");
	$db = connect_db();
	$days50_before=date("Y-m-d H:i:s",time()-50*86400);
	$sql="UPDATE jsadmin.PSWRDS SET ACTIVE='N' WHERE LAST_LOGIN_DT < '$days50_before'";
	$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());


?>
