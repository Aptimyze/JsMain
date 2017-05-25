<?php
include("connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");
$mysqlObj=new Mysql;

if(authenticated($cid))
{
		
	if(0)//$Submit)
	{
		//Code not written
	}
	else
	{
		for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
		{
			$myDbName=getActiveServerName($activeServerId);
			$myDb=$mysqlObj->connect("$myDbName");
			
			$sql=" DELETE from newjs.CONTACTS where RECEIVER=$pid";
			$mysqlObj->executeQuery($sql,$myDb) or die(mysql_error_js($myDb));
			

		}

		
		echo "<center>Contact received has been deleted.</center>";
	}
}
else
{
	$msg="Your session has been timed out<br>";
	$msg .="<a href=\"index.htm\">";
	$msg .="Login again </a>";
	$smarty->assign("MSG",$msg);
	$smarty->assign("user",$user);
	$smarty->display("jsadmin_msg.tpl");
}
?>
