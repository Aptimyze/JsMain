<?php

include("../profile/connect.inc");

$db = connect_db();

$sql = "Select SQL_CACHE * from RESOURCES_CAT where 1";
$result = mysql_query_decide($sql,$db) or logError("Due to some temporary problem your request could not be processed. Please try after some time.",$sql,"ShowErrTemplate");
    
$i = 0;
$j = 0;
$col = 3;
while($myrow = mysql_fetch_array($result))
{  
	if (  $j % $col == 0)
	{
		$i++;
		$j = 0 ;
	}

	$values[$i-1][$j] = array("CAT_ID"=>$myrow["CAT_ID"],
				"CAT_NAME"=>$myrow["CAT_NAME"], 
				"CAT_DISPLAY"=>$myrow["CAT_DISPLAY"]);
	$j++;
}

$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
$smarty->assign("HEAD",$smarty->fetch("head.htm"));
 
$smarty->assign("VALUES",$values);
$smarty->assign("ROWS",$i);	
$smarty->assign("COLS",$col);	
//$smarty->display("resources_cat.htm");
$smarty->display("resources_form.htm");
  
?>
