<?php
/******************************************************************************************************************
file        : timein.php
Description : script to mark log-in time and log-out time of a user
Created By  : Neha Verma
Created On  : 29 Dec 2008
*******************************************************************************************************************/

include_once("connect.inc");
$db=connect_db();
if(authenticated($cid))
{
	date_default_timezone_set('Asia/Calcutta');
	$date=date('Y-m-d H:i:s');
	list($dt,$tt)=explode(' ',$date);
	if($link=="in")
	{
		//$sql="INSERT INTO LOGIN_DETAILS (OPERATOR,DATE,LOGIN) VALUES('$name',CURDATE(),CURTIME())";
		$sql="INSERT INTO jsadmin.LOGIN_DETAILS (OPERATOR,DATE,LOGIN) VALUES('$name','$dt','$tt')";
		$res=mysql_query_decide($sql) or die(mysql_error());
	}
	elseif($link=="out")
	{
		if($id)
		{
			//$sql="UPDATE LOGIN_DETAILS SET LOGOUT=CURTIME() WHERE ID=$id";
			$sql="UPDATE jsadmin.LOGIN_DETAILS SET LOGOUT='$tt' WHERE ID=$id";
			$res=mysql_query_decide($sql) or die(mysql_error());
		}
	}
	
	unset($get_post);
	if(is_array($_GET))
	{
		foreach($_GET as $key => $value)
			$get_post[] = "$key=$value";
	}
	if(is_array($_POST))
	{
		foreach($_POST as $key => $value)
			$get_post[] = "$key=$value";
	}
	if(is_array($get_post))
        $get_post_string = @implode("&",$get_post);

	header('Location:'.$SITE_URL.'/jsadmin/mainpage.php?'.$get_post_string);
	unset($get_post_string);
	die;
}
else
{
        $msg="Your session has been timed out<br><br>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
 
?>
