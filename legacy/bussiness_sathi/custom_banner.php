<?php
/*********************************************************************************************
* FILE NAME     : custom_banners.php
* DESCRIPTION   : Mails the administrator the request for a customized banner asked by an Affiliate
* INCLUDES      : connect.inc
* FUNCTIONS     : connect_db()          : To connect to the database server
*               : authenticated()       : To check if the user is authenticated or not
*               : TimedOut()            : To take action if the user is not authenticated
* CREATION DATE : 1 July, 2005
* CREATED BY  	: Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/

include("connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
$db=connect_db();

$data=authenticated($checksum);
$smarty->assign("CHECKSUM",$checksum);
$smarty->assign("HEAD",$smarty->fetch("business_sathi/heads.htm"));
$smarty->assign("LEFT",$smarty->fetch("business_sathi/left.htm"));

$EMAIL="madhurima.sil@naukri.com";

if(isset($data))
{
	$ID=$data["AFFILIATEID"];
	
	$sql_det="SELECT USERNAME,EMAIL FROM affiliate.AFFILIATE_DET WHERE AFFILIATEID='$ID'";
	$res_det=mysql_query($sql_det) or logError("Due to a temporary problem your request could not be processed",$sql_det);
	$row=mysql_fetch_array($res_det);
	$uname=$row["USERNAME"];
	$from=$row["EMAIL"];
	$subject=$uname."(AFFILIATEID-".$ID.") has requested a customized banner";

	if(!$submit)
	{
		$smarty->assign("uname",$uname);
		$smarty->assign("email",$from);
		$smarty->display("business_sathi/custom_banner.htm");
	}
	else
	{
		$msg=nl2br($req);
		send_email($EMAIL,$msg,$subject,$from);
		$smarty->assign("username",$data["USERNAME"]);
		$smarty->display("business_sathi/businesssathi_mybusi_sathi.htm");
	}
}
else
{
	TimedOut();
}
?>
