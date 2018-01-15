<?php

include("connect.inc");

if(authenticated($cid))
{

	$smarty->assign("CID",$cid);
	$smarty->assign("HEAD",$smarty->fetch("head.htm"));
	
	$str = " ";
	if ($total_hits)
		$str = $str." TH";
	if ($profile_com)
		$str = $str." CP";
	if ($profile_incom)
		$str = $str." IP";
	if ($profile_del)
		$str = $str." DP";
	if ($profile_paid)
		$str = $str." PAID";
		
	$str = trim($str);
	$priv = explode(" ",$str);
	$privilege = implode("+",$priv);
	
	
	if ($username == "" || $passwd == "" )
	{
		$text = "Username or password field is empty. Please try again.";
		$smarty->assign("DISPLAY",$text);
		$smarty->display("tieups_create_login.htm");	
	}	
	else
	{
		$sql = "Select * from tieups.PSWRDS where USERNAME = binary '$username' ";
		$result = mysql_query_decide($sql) or die (mysql_error_js());
		
		if (mysql_fetch_array($result) > 0)	
		{
			$text = "Username already exists. Please try again.";
			$smarty->assign("DISPLAY",$text);
			$smarty->display("tieups_create_login.htm");	
		}
		else
		{	

			$sql = "Insert into tieups.PSWRDS(USERNAME,PASSWORD,GROUPNAME, PRIVILAGE) values ( binary '$username', binary '$passwd', '$SourceId', '$privilege')" ;
			$result = mysql_query_decide($sql) or die(mysql_error_js());

			$text = "Username successfully created";
			$smarty->assign("DISPLAY",$text);
			$smarty->display("tieups_create_login.htm");	
		}

	}
		

}
else
{
	$smarty->assign("user",$username);
	$smarty->display("jsconnectError.tpl");
}
?>
