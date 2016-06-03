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
//include ("../../jsadmin/time.php");
$ip = getenv ('REMOTE_ADDR');

dbsql2_connect();
$connection = login($username, $password);

if($connection)//successful login
{
/*	$privilage = getprivilage($connection);
   	$priv = explode("+",$privilage);

	if($username=="shiv")
	{*/
		//header("Location: $SITE_URL/jsadmin/mainpage.php?name=$user&cid=$connection");		

		header("Location: mainpage.php?cid=$connection");
		//header("Location: $SITE_URL/mainpage.php?cid=$connection");
		//header("Location: http://".$_SERVER['HTTP_HOST']."/live/promotions/mainpage.php?cid=$connection");
/*	}
	elseif(in_array('PA',$priv))//photo administrator login
	{
		$user="n";//for showing newly added photo profiles 
		header("Location: http://".$_SERVER['HTTP_HOST']."/jsadmin/showprofilestoassign_new.php?name=$username&user=$user&cid=$connection");		
	}	
	elseif(in_array('PU',$priv))//photo operator login 
	{		
		header("Location: http://".$_SERVER['HTTP_HOST']."/jsadmin/showprofilestoscreen.php?username=$username&cid=$connection");				
	}	
	elseif(in_array('A',$priv))
	{
		 header("Location: http://".$_SERVER['HTTP_HOST']."/jsadmin/alternate.php?name=$username&cid=$connection&val=new");
	}
	elseif(in_array('U',$priv))
	{
		 header("Location: http://".$_SERVER['HTTP_HOST']."/jsadmin/userview.php?user=$username&cid=$connection");
	}
	elseif(in_array('S',$priv))
	{
		 header("Location: http://".$_SERVER['HTTP_HOST']."/jsadmin/searchpage.php?user=$username&cid=$connection");
	}
	elseif(in_array('MA',$priv))
	{
		 header("Location: http://".$_SERVER['HTTP_HOST']."/jsadmin/searchpage.php?user=$username&cid=$connection");
	}
	elseif(in_array('MC',$priv))
	{
		 header("Location: http://".$_SERVER['HTTP_HOST']."/jsadmin/searchpage.php?user=$username&cid=$connection");
	}
	elseif(in_array('R',$priv))
	{
		 header("Location: http://".$_SERVER['HTTP_HOST']."/jsadmin/retrievepage.php?user=$username&cid=$connection");
	}
	elseif(in_array('TA',$priv))//thumbnail administrator
	{
		header("Location: http://".$_SERVER['HTTP_HOST']."/jsadmin/show_thumbnails_to_assign.php?name=$username&cid=$connection");		
	}	
	elseif(in_array('TU',$priv))//thumbnail operator
	{
		header("Location: http://".$_SERVER['HTTP_HOST']."/jsadmin/show_thumbnails_to_screen.php?username=$username&cid=$connection");		
	}
	elseif(in_array('B',$priv))
        {
                 header("Location: http://".$_SERVER['HTTP_HOST']."/billing/billingview.php?user=$username&cid=$connection");
        }	*/
}
else//login failed
{
	$smarty->assign("username","$username");
	$smarty->display("jsconnectError.tpl");
}
if($zipIt)
	ob_end_flush();
?>
