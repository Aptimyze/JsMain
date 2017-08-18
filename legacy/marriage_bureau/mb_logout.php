<?php
/************************************************************************************************************************
*   FILENAME      :  mb_logout.php
*   DESCRIPTION   :  To end the session of the user.
*   CREATED BY    :  Lavesh
***********************************************************************************************************************/
include_once("connectmb.inc");
$db = connect_dbmb();
$lout=logoutmb($cid);
$smarty->assign("LOGOUT","Y");
$smarty->display("login.htm");
?>
