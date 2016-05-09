<?php
/*********************************************************************************************
* FILE NAME     : businesssathi_logout.php
* DESCRIPTION   : Logs out a person
* INCLUDES      : connect.inc
* FUNCTIONS     : connect_db()          : To connect to the database server
*               : logout()       	: To log out the person
* CREATION DATE : 16 June, 2005
* CREATED BY  : Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/
include("connect.inc");
                                                                                                                            
$db=connect_db();
                                                                                                                            
logout($checksum);
                                                                                                                            
$smarty->assign("LOGOUT","1");
                                                                                                                            
$smarty->assign("HEAD",$smarty->fetch("business_sathi/heads.htm"));
$smarty->assign("LEFT",$smarty->fetch("business_sathi/left.htm"));
$smarty->display("business_sathi/businesssathi_login.htm");
?>

