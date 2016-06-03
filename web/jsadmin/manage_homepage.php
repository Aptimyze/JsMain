<?php
/***************************************************************************************************************
* FILE NAME     : manage_homepage.php
* DESCRIPTION   : Acts as a gateway for the user to add/view/remove users on the Home Page
* CREATION DATE : 11 August, 2005
* CREATED BY    : Shakti Srivastava
* Copyright  2005, InfoEdge India Pvt. Ltd.
*****************************************************************************************************************/

include("connect.inc");

if($cid)
	$data=$cid;
else if($checksum)
	$data=$checksum;

$smarty->assign("cid",$data);

if(authenticated($data))
{
	$smarty->display("manage_homepage.htm");
}
else
{
	$msg="Your session has been timed out<br>";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");

}
?>
