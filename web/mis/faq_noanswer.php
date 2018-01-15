<?php
include("connect.inc");

$db=connect_misdb();

if(authenticated($cid))
{
	$sql="SELECT COUNT(*) as cnt,left(ENTRY_DT,10) as dd FROM feedback.TICKET_MESSAGES WHERE REPLY_DT=0 GROUP BY dd";
	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
	while($row=mysql_fetch_array($res))
	{
		$ddarr[]=$row['dd'];
		$i=array_search($row['dd'],$ddarr);
		$cnt[$i]=$row['cnt'];
	}

	$smarty->assign("cid",$cid);
	$smarty->assign("ddarr",$ddarr);
	$smarty->assign("cnt",$cnt);
	$smarty->display("faq_noanswer.htm");
}
else
{
	$smarty->display("jsconnectError.tpl");
}
?>
