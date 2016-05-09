<?php
/*********************************************************************************************
* FILE NAME   : resources_form.php
* DESCRIPTION : Displays the form to allow the user to enter new resource
* MODIFY DATE        : 5 May, 2005
* MODIFIED BY        : Rahul Tara
* REASON             : Addition of new categories
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/

include("connect.inc");

$db = connect_db();
$data=authenticated();
$smarty->assign("CATEGORY",$category);
$smarty->assign("FOOT",$smarty->fetch("footer.htm"));

$smarty->assign("SUB_HEAD",$smarty->fetch("sub_head.htm"));
$smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));
//$smarty->assign("REVAMP_SEARCH_PANEL",$smarty->fetch("revamp_top_search_band.htm"));
//$smarty->assign("REVAMP_TOP_SEARCH",$smarty->fetch("revamp_top_search_band.htm"));

$smarty->display("resources_form_new1.htm");


?>
