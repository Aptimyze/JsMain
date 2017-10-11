<?php
/*
This script retrive the membership status corresponding to Billid
*/

include("../jsadmin/connect.inc");

$data=authenticated($cid);
if(isset($data))
{
	$user=getname($cid);
	$sql="SELECT PROFILEID, USERNAME,SERVEFOR from billing.PURCHASES where BILLID='$billid'";
	$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
	$myrow=mysql_fetch_array($result);

	$sql="UPDATE newjs.JPROFILE SET ACTIVATED=PREACTIVATED,PREACTIVATED='',SUBSCRIPTION='$myrow[SERVEFOR]' , ACTIVATE_ON=now(), activatedKey=if(ACTIVATED<>'D',1,0) WHERE PROFILEID='$myrow[PROFILEID]' ";
        mysql_query_decide($sql) or die(mysql_error_js());

	
	echo "Subscription for <font color=\"blue\">$myrow[USERNAME]</font> is restored\n";
	echo "<input type=button value=\"Close\" onClick=\"window.close()\">";
        exit;
	
}
else
{
	$smarty->assign("HEAD",$smarty->fetch("../head.htm"));
	$smarty->assign("FOOT",$smarty->fetch("../foot.htm"));
	$smarty->display("jsconnectError.tpl");
}
?>
