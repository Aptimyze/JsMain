<?php
include ("connect.inc");
include_once("../commonFiles/SymfonyPictureFunctions.class.php");

if(authenticated($cid))
{
	$name=getname($cid);
	$timestamp=time()+ (24*60*60);
	$tomorrow=date('Y-m-d',$timestamp);
	$timestamp=time()+(20*24*60*60);
	$after20=date('Y-m-d',$timestamp)." 23:59:59";

	$sql="SELECT COUNT(*) as cnt, LEFT(FOLLOWUP_TIME,10) as dd FROM incentive.MAIN_ADMIN WHERE ALLOTED_TO = '$name' AND STATUS='F' AND FOLLOWUP_TIME BETWEEN '$tomorrow' AND '$after20'  GROUP BY dd";

	$res=mysql_query_decide($sql)or die(mysql_error_js());
	$i=0;
	while($row=mysql_fetch_array($res))
	{
		$follow[$i]['FOLLOWUP']=$row['cnt'];
		$follow[$i]['DATE']=$row['dd'];
		$i++;
	}

	$smarty->assign("cid",$cid);
	$smarty->assign("follow",$follow);
	$smarty->assign("name",$name);
	$smarty->display("followupdetail.htm");
}
else
{
	$smarty->display("jsconnectError.tpl");
}
?>
