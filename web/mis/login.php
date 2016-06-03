<?php
header("Location:$SITE_URL/mis");die;
/**
	// PRIVILAGES 
	* MA - BOSS
	* MB - LOWER LEVEL
**/

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
include ("connect.inc");
/**
*       Included        :       time.php
*       Description     :       contains functions related to date and time 
**/
include ("time.php");
$db=connect_misdb();
$db2=connect_master();

$ip = FetchClientIP();
if(strstr($ip, ","))
{
        $ip_new = explode(",",$ip);
        $ip = $ip_new[1];
}

//$smarty->assign("HEAD",$smarty->fetch("head.htm"));
$connection = login($username, $password);
if($connection && $connection!="-1")//successful login
{
	setLoginCookies($connection,$username);
	header("Location: $SITE_URL/mis/mainpage.php?name=$user&cid=$connection");
}
else//login failed
{
	if($connection=="-1")
                $smarty->assign("EXPIRE","Y");
	$smarty->assign("username","$username");
	$smarty->display("jsconnectError.tpl");
}
if($zipIt)
	ob_end_flush();
?>
