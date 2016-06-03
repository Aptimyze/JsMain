<?php
header("Location:$SITE_URL/billing");die;
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
include ("../jsadmin/connect.inc");
/**
*       Included        :       time.php
*       Description     :       contains functions related to date and time 
**/
include ("time.php");
$ip = FetchClientIP();
if(strstr($ip, ","))
{
        $ip_new = explode(",",$ip);
        $ip = $ip_new[1];
}

//$smarty->assign("HEAD",$smarty->fetch("head.htm"));
$connection = login($username, $password);

if($connection)//successful login
{
	$privilage = getprivilage($connection);
   	$priv = explode("+",$privilage);
   	setLoginCookies($connection,$username);
	
//	if(in_array('B',$priv))
//	{
		 header("Location: $SITE_URL/jsadmin/mainpage.php?user=$username&cid=$connection");
		 //header("Location: http://".$_SERVER['HTTP_HOST']."/jsadmin/mainpage.php?user=$username&cid=$connection");
//	}
	
}
else//login failed
{
	$smarty->assign("username","$username");
	$smarty->display("jsconnectError.tpl");
}
if($zipIt)
	ob_end_flush();

?>
