<?php

include("connect.inc");
$db = connect_db();
$data=authenticated();

$sql = "Select SQL_CACHE * from newjs.RESOURCES_DETAILS where PAGE2 = 'y' and VISIBLE = 'y' Order by ID
Desc" ;

$result = mysql_query_decide($sql,$db) or logError("Due to some temporary problem your request could not
 be processed. Please try after some time.",$sql,"ShowErrTemplate");;

while ($myrow = mysql_fetch_array($result))
{
        $values[] = array(      "ID"=>$myrow["ID"],
                                "CAT_ID" => $myrow["CAT_ID"],
                                "NAME" => $myrow["NAME"],
                                "LINK" => $myrow["LINK"],
                                "DESCR" => $myrow["DESCR"],
				"PAGE2"=>$myrow["PAGE2"]) ;
}

$smarty->assign("FOOT",$smarty->fetch("footer.htm"));
$smarty->assign("SUB_HEAD",$smarty->fetch("sub_head.htm"));
$smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));
$smarty->assign("REVAMP_SEARCH_PANEL",$smarty->fetch("revamp_top_search_band.htm"));
//$smarty->assign("REVAMP_TOP_SEARCH",$smarty->fetch("revamp_top_search_band.htm"));
$smarty->assign("ROWS",$values);
$smarty->assign("resources1",1);
$smarty->display("resources_detail_new_1.htm");
?>

