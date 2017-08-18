<?php
/*********************************************************************************************
* FILE NAME   : resources.php
* DESCRIPTION : Displays the list of categories from which links can be taken to view
                resources in that category
* MODIFY DATE        : 13 May, 2005
* MODIFIED BY        : Rahul Tara
* REASON             : Addition of new pages for each category
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/


//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)
	ob_start("ob_gzhandler");
//end of it
	
include("../profile/connect.inc");

$db = connect_db();

$sql = "Select SQL_CACHE * from RESOURCES_CAT ORDER BY SORTBY";
$result = mysql_query_decide($sql,$db) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
    
$i = 0;
$j = 1;
$col = 4;
while($myrow = mysql_fetch_array($result))
{  
	$j=0;	
	while($j < 4)
	{
		if($j == 0)
		{
			$values[$i][$j] = array("CAT_ID"=>$myrow["CAT_ID"],
                                                "CAT_NAME"=>$myrow["CAT_NAME"],
                                                "CAT_DISPLAY"=>$myrow["CAT_DISPLAY"]);

		}
		else
		{	
			$values[$i][$j] = array("CAT_ID"=>$myrow["CAT_ID"],
						"CAT_NAME"=>$myrow["CAT_NAME"]."$j",
						"CAT_DISPLAY"=>"Page".$j);
		}
		$j++;
	}
	$i++;
}

$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
$smarty->assign("HEAD",$smarty->fetch("head.htm"));
 
$smarty->assign("VALUES",$values);
$smarty->assign("ROWS",$i);	
$smarty->assign("COLS",$col);	
$smarty->display("resources.htm");

// flush the buffer
if($zipIt)
	ob_end_flush();
		
?>
