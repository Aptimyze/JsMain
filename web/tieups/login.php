<?php
//it starts zipping
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)   
	ob_start("ob_gzhandler");

//end of it
/**
*       Included        :       connect.inc
*       Description     :       contains functions related to database connection and login authentication
**/
include("connect.inc");

$ip = FetchClientIP();
$db2=connect_db();

//$smarty->assign("HEAD",$smarty->fetch("head.htm"));

$connection = login($username, $password);

if($connection)//successful login
{
	setLoginCookies($connection,$username);
	header("Location: $SITE_URL/tieups/mainpage.php?cid=$connection");		
}
else//login failed
{
	$smarty->assign("username","$username");
	$smarty->display("jsconnectError.tpl");
}
if($zipIt)
	ob_end_flush();
?>
