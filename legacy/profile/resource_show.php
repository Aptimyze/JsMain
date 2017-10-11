<?php

//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)
	ob_start("ob_gzhandler");
//end of it
include_once("../resources/resources.inc");
include_once("connect.inc");
$db = connect_db();
$data=authenticated();
//echo $CatId.'<br>';
if(substr($CatId,-1)>0&&substr($CatId,-1)<4)
{
	$START_PAGE = get_page_start(substr($CatId,-1));
	$CatId =substr_replace($CatId ,"",-1);
}
//echo $CatId.'<br>';
$PAGE_NO=$START_PAGE/30;
$sql = "Select CAT_ID from newjs.RESOURCES_CAT WHERE CAT_NAME = '$CatId'";
$result = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
$myrow = mysql_fetch_array($result);
$CatId = $myrow['CAT_ID'];
$sql = "Select * from newjs.RESOURCES_DETAILS where CAT_ID =$CatId and VISIBLE = 'y' and PAGE ='$PAGE_NO' Order by SORTBY Limit 0,$PAGE_LEN" ;
//$result = mysql_query_decide($sql,$db) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
$result = mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

while ($myrow = mysql_fetch_array($result))
{
	$values[] = array(	"ID"=>$myrow["ID"],
				"CAT_ID" => $myrow["CAT_ID"],
				"NAME" => $myrow["NAME"],
				"LINK" => $myrow["LINK"],
				"DESCR" => $myrow["DESCR"]) ;
}  

$sql = "Select * from newjs.RESOURCES_CAT where CAT_ID=$CatId";
$result = mysql_query_decide($sql,$db) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");;
$myrow = mysql_fetch_array($result);
$cat_display = $myrow["CAT_DISPLAY"];


/********************************************************************************************
        Changes made by Rahul Tara on 16 May,2005
        subfooter.htm file included
*******************************************************************************************/


/********************************************************************************************
        Changes made on 16 May, 2005 end here
*********************************************************************************************/

$smarty->assign("FOOT",$smarty->fetch("footer.htm"));
$smarty->assign("SUB_HEAD",$smarty->fetch("sub_head.htm"));
$smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));
//$smarty->assign("REVAMP_SEARCH_PANEL",$smarty->fetch("revamp_top_search_band.htm"));
//$smarty->assign("REVAMP_TOP_SEARCH",$smarty->fetch("revamp_top_search_band.htm"));

$smarty->assign("CAT_DISPLAY",$cat_display);
$smarty->assign("CATID",$CatId); 
$smarty->assign("ROWS",$values);
$smarty->display("resources_detail_new_1.htm");

// flush the buffer
if($zipIt)
	ob_end_flush();
?>
