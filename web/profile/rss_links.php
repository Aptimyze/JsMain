<?php
/**
*       Filename        :       rss_links.php
*       Description     :       creates RSS LINKS and shows the rss template to the user to get their rss feeds.
*       Created by      :       Puneet Makkar
*       Created on      :       30 Dec 2005
**/
include("connect.inc");
$db=connect_db();
$data=authenticated($checksum);
$sql="SELECT FEED_NAME,NAME,TITLE FROM RSS where DISPLAY='Y' ORDER BY SORTBY asc";
$result=mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
while($row=mysql_fetch_array($result))
{
	$feed_arr[]=$row['FEED_NAME'];
	$name_arr[]=$row['NAME'];
	$title_arr[]=$row['TITLE'];
}
$smarty->assign("TITLE_ARR",$title_arr);
$smarty->assign("FEED_ARR",$feed_arr);
$smarty->assign("NAME_ARR",$name_arr);
$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
$smarty->assign("HEAD",$smarty->fetch("headnew.htm"));
$smarty->assign("SUBFOOTER",$smarty->fetch("subfooternew.htm"));
$smarty->display("rss_links.htm");
?>
