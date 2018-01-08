<?php
/*********************************************************************************************
* FILE NAME   : resources_new.php
* DESCRIPTION : Displays the list of new resources to the administrator
* MODIFY DATE        : 5 May, 2005
* MODIFIED BY        : Rahul Tara
* REASON             : Addition of new categories
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/

include("../jsadmin/connect.inc");

if(authenticated($cid))
{
	$sql = "Select * from newjs.RESOURCES_DETAILS where VISIBLE ='t'" ;
        $result = mysql_query_decide($sql,$db) or die("$sql<br>".mysql_error_js());

	while ($myrow = mysql_fetch_array($result))
        {
                $values[] = array(      "ID"=>$myrow["ID"],
                                "CAT_ID" => $myrow["CAT_ID"],
                                "NAME" => $myrow["NAME"],
                                "LINK" => $myrow["LINK"],
                                "DESCR" => $myrow["DESCR"],
                                "VISIBLE" =>"new") ;
        }
	$smarty->assign("ROWS",$values);
	$smarty->assign("CID",$cid);
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
	$smarty->assign("HEAD",$smarty->fetch("head.htm"));
	$smarty->display("resources_admin_new.htm");
}
else
{
        $msg="Your session has been timed out<br>  ";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
?>
