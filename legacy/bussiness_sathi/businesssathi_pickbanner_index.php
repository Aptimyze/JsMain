<?php
/*********************************************************************************************
* FILE NAME     : businesssathi_pickbanner_index.php
* DESCRIPTION   : Displays Business Sathi pick banner page after putting Head and Left panel in place
* INCLUDES      : connect.inc
* FUNCTIONS     : connect_db()          : To connect to the database server
*               : authenticated()       : To check if the user is authenticated or not
*               : TimedOut()            : To take action if the user is not authenticated
* CREATION DATE : 16 June, 2005
* CREATED BY  	: Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/

include("connect.inc");
$db=connect_db();

$data=authenticated($checksum);
$smarty->assign("CHECKSUM",$checksum);
$smarty->assign("HEAD",$smarty->fetch("business_sathi/heads.htm"));
$smarty->assign("LEFT",$smarty->fetch("business_sathi/left.htm"));

$sql="SELECT SIZE FROM affiliate.BANNERS WHERE NEW_CATEGORY='Y'";
$res=mysql_query($sql) or logError("Due to a temporary problem your request could not be processed",$sql);
$rows=mysql_num_rows($res);
$smarty->assign("rows",$rows);

while($myrow=mysql_fetch_array($res))
{
	$det[]=array("size"=>$myrow["SIZE"]);
}
$smarty->assign("det",$det);

if(isset($data))
{
	$smarty->display("business_sathi/businesssathi_pickbanner_index.htm");
}
else
{
	TimedOut();
}
?>
