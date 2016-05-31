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
include ("connect.inc");
/**
*       Included        :       time.php
*       Description     :       contains functions related to date and time 
**/
//include ("../jsadmin/time.php");
$ip = getenv ('REMOTE_ADDR');
$connection = login($username, $password);
if($connection)//successful login
{
//	header("Location: $SITE_URL/mainpage.php?name=$username&cid=$connection");	
	//setLoginCookies($connection,$username);
		
	$smarty->assign("username","$username");
	$smarty->assign("cid","$connection");
	$smarty->display("mainpage.htm");
}
else//login failed
{
	if($flag=='out')
	{
		$smarty->assign("flag","Y");
	}	

	$smarty->assign("username","$username");
	$smarty->display("jsconnectError.htm");
}
if($zipIt)
	ob_end_flush();
?>
