<?php

//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
        $zipIt = 1;
if($zipIt)
        ob_start("ob_gzhandler");
//end of it

include("connect.inc");
$db = connect_db();
$sql = "Select COUNT(*) AS CNT from newjs.RESOURCES_CAT ORDER BY SORTBY";
$result = mysql_query_decide($sql,$db) or die("$sql<br>".mysql_error_js());
$myrow = mysql_fetch_array($result);
for($i=0;$i<4;$i++)
{
	for($var=0;$myrow['CNT'] >= $var;$var++)
	{
		$var1=$var+1;
		$j=$i*20;
//echo		$sql = "update newjs.RESOURCES_DETAILS set PAGE ='$i' WHERE CAT_ID = '$var1' and VISIBLE = 'y' Order by ID Desc Limit $j,20";echo '<br>';
//echo		$sql = "update newjs.RESOURCES_DETAILS set PAGE ='$i' WHERE CAT_ID = (Select CAT_ID from newjs.RESOURCES_DETAILS WHERE CAT_ID = '$var1' and VISIBLE = 'y' Order by ID Desc Limit $j,20)";echo '<br>';
		$sql = "Select ID from newjs.RESOURCES_DETAILS  WHERE CAT_ID = '$var1' and VISIBLE = 'y' Order by ID Desc Limit $j,20";
		$result = mysql_query_decide($sql) or logError($errorMsg,$sql,"ShowErrTemplate");
		while($row = mysql_fetch_array($result))
		{
			$sql = "update newjs.RESOURCES_DETAILS set PAGE ='$i' WHERE  ID='$row[ID]'";	
			mysql_query_decide($sql) or logError($errorMsg,$sql,"ShowErrTemplate");
		}
		
	}
}
?>
