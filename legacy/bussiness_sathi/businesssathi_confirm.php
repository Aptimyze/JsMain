<?php
/*********************************************************************************************
* FILE NAME     : businesssathi_confirm.php
* DESCRIPTION   : Displays Business Sathi comfirmation page after putting Head and Left panels
*		  in place
* INCLUDES	: connect.inc
* FUNCTION	: connect_db()		: To connect to the database server
*		: authenticated()	: To check if the user is authenticated or not.
* CREATION DATE : 16 June, 2005
* CREATED BY  : Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/
include("connect.inc");
$db=connect_db();

$data=authenticated($checksum);

$smarty->assign("CHECKSUM",$checksum);
$smarty->assign("HEAD",$smarty->fetch("business_sathi/heads.htm"));
$smarty->assign("LEFT",$smarty->fetch("business_sathi/left.htm"));
                                                                                                                            
if(isset($data))
{
	$smarty->display("business_sathi/businesssathi_confirm.htm");
}
else
{
	$smarty->display("business_sathi/businesssathi_confirm.htm");
}

?>
