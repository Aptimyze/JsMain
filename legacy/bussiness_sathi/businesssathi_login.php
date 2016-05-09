<?php
/*********************************************************************************************
* FILE NAME     : businesssathi_login.php
* DESCRIPTION   : Displays Business Sathi Login page after putting Head and Left panels in place
* INCLUDES      : connect.inc
* FUNCTIONS     : connect_db()          : To connect to the database server
*               : login()            	: To log-in the user
* CREATION DATE : 16 June, 2005
* CREATED BY  : Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/
include("connect.inc");
$db=connect_db();                                                                                                                            
$smarty->assign("CHECKSUM",$data["CHECKSUM"]);
$smarty->assign("HEAD",$smarty->fetch("business_sathi/heads.htm"));
$smarty->assign("LEFT",$smarty->fetch("business_sathi/left.htm"));

$error=0;

if(!$submit)
{
	$smarty->display("business_sathi/businesssathi_login.htm");
}
else
{
	if($uname=="")
	{
		$error++;
		$smarty->assign("erruname","1");
	}
	else if($pwd=="")
	{
		$error++;
		$smarty->assign("errpwd","1");
	}
	else
	{
		$sql_pwd="SELECT USERNAME,PASSWORD FROM affiliate.AFFILIATE_DET WHERE USERNAME='$uname'";
		$res_pwd=mysql_query($sql_pwd) or logError("Due to a temporary problem your request could not be processed",$sql_pwd);
		if(mysql_num_rows($res_pwd)<=0)
		{
			$error++;
			$smarty->assign("NOUSER","1");
		}
		else
		{
			$row_pwd=mysql_fetch_array($res_pwd);
			if($pwd!=$row_pwd["PASSWORD"])
			{
				$error++;
				$smarty->assign("WRONGPWD","1");
			}
		}
	}
	
	if($error!=0)
	{
		$smarty->assign("uname",$uname);
		$smarty->display("business_sathi/businesssathi_login.htm");
	}
	else
	{
		$data=login($uname,$pwd);
		if(isset($data))
		{
			$smarty->assign("CHECKSUM",$data["CHECKSUM"]);
			$smarty->assign("username",$data["USERNAME"]);
			$smarty->assign("NOIMAGE","1");
			$smarty->assign("HEAD",$smarty->fetch("business_sathi/heads.htm"));
			$smarty->assign("LEFT",$smarty->fetch("business_sathi/left.htm"));
			$smarty->display("business_sathi/businesssathi_mybusi_sathi.htm");
		}
		else
		{
			$smarty->assign("WRONGUSER","1");
                	$smarty->assign("FOOT",$smarty->fetch("business_sathi/left.htm"));
	                $smarty->assign("HEAD",$smarty->fetch("business_sathi/heads.htm"));
			$smarty->display("business_sathi/businesssathi_login.htm");
		}
	}
}

?>
