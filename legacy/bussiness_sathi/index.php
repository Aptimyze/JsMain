<?php
/*********************************************************************************************
* FILE NAME     : index.php
* DESCRIPTION   : Displays Business Sathi Index page after putting Head and Left panels in place
* INCLUDES      : connect.inc
* CREATION DATE : 16 June, 2005
* CREATED BY  : Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/
include("connect.inc");

$smarty->assign("CHECKSUM",$checksum);
$smarty->assign("HEAD",$smarty->fetch("business_sathi/heads.htm"));
$smarty->assign("LEFT",$smarty->fetch("business_sathi/left.htm"));
$smarty->display("business_sathi/businesssathi_index.htm");


?>
