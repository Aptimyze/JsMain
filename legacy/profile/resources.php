<?php
/*********************************************************************************************
* FILE NAME   : resources.php
* DESCRIPTION : Displays the list of categories from which links can be taken to view
                resources in that category
* MODIFY DATE        : 16 May, 2005
* MODIFIED BY        : Rahul Tara
* REASON             : Put community subfooter in resources section
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/


//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)
	ob_start("ob_gzhandler");
//end of it

include("connect.inc");
$db = connect_db();
$data=authenticated();

/*************************************Portion of Code added for display of Banners*******************************/

//$data=authenticated($checksum);
//$regionstr=8;
//include("../bmsjs/bms_display.php");
/************************************************End of Portion of Code*****************************************/

//$db=connect_db();

$sql = "Select SQL_CACHE * from RESOURCES_CAT WHERE ACTIVE ='Y' ORDER BY SORTBY";
$result = mysql_query_decide($sql,$db) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
    
while($myrow = mysql_fetch_array($result))
{  
        $values[] = array("CAT_ID"=>$myrow["CAT_ID"],
				"CAT_NAME"=>$myrow["CAT_NAME"],
				"CAT_DISPLAY"=>$myrow["CAT_DISPLAY"]);
}

$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));

/********************************************************************************************
	Changes made by Rahul Tara on 16 May,2005
	subfooter.htm file included
*******************************************************************************************/

$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));

/********************************************************************************************
	Changes made on 16 May, 2005 end here
*********************************************************************************************/

$smarty->assign("FOOT",$smarty->fetch("footer.htm"));
$smarty->assign("SUB_HEAD",$smarty->fetch("sub_head.htm"));
$smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));
//$smarty->assign("REVAMP_SEARCH_PANEL",$smarty->fetch("revamp_top_search_band.htm"));
//$smarty->assign("REVAMP_TOP_SEARCH",$smarty->fetch("revamp_top_search_band.htm"));
$smarty->assign("VALUES",$values);
$smarty->display("resources_new_1.htm");

// flush the buffer
if($zipIt)
	ob_end_flush();
		
?>
