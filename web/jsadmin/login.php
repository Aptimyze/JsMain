<?php
header("Location:$SITE_URL/jsadmin");die;
//it starts zipping
/*$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)   
	ob_start("ob_gzhandler");*/

//end of it
/**
*       Included        :       connect.inc
*       Description     :       contains functions related to database connection and login authentication
**/
//include ("connect.inc");
/**
*       Included        :       time.php
*       Description     :       contains functions related to date and time 
**/
/*include ("time.php");
$ip = FetchClientIP();
$connection = login($username, $password);

// csv Generation url start
if(!$httpReferer)
	$httpReferer =$_SERVER['HTTP_REFERER'];
if(strstr("$httpReferer",'processName'))
	$csvGenerationUrl=true;
// csv generation url ends

if($connection && $connection!="-1")//successful login
{
/*	$privilage = getprivilage($connection);
   	$priv = explode("+",$privilage);
*/
   	/*setLoginCookies($connection,$username);
   	
	if($csvGenerationUrl)
                header("Location: $httpReferer&name=$user&cid=$connection");
        else
		header("Location: $SITE_URL/jsadmin/mainpage.php");		
}
else//login failed
{

	if($connection=="-1")
		$smarty->assign("EXPIRE","Y");
	if($csvGenerationUrl)
		$smarty->assign("httpReferer",$httpReferer);
	$smarty->assign("username","$username");
	$smarty->display("jsconnectError.tpl");
}
if($zipIt)
	ob_end_flush();*/
?>
