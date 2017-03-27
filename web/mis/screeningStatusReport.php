<?php
include ("connect.inc");
$db             =connect_misdb();
$data           =authenticated($cid);

if($data)
{
	$name=getname($cid);
	$smarty->assign('cid',$cid);

	$sql ="SELECT count(*) cnt,SCREEN_ACTION FROM duplicates.PROBABLE_DUPLICATES WHERE SCREEN_ACTION IN('NONE','OUT','IN') GROUP BY SCREEN_ACTION";
	$result =mysql_query_decide($sql) or die("$sql".mysql_error_js());
	while($row=mysql_fetch_array($result))
	{
		$screenCnt 	=$row['cnt'];
		$action 	=$row['SCREEN_ACTION'];

		if($action=='NONE' || $action=='IN')
			$screenCntExec +=$screenCnt;
		elseif($action=='OUT')
			$screenCntSup =$screenCnt;
	}								

	$smarty->assign("screenCntExec",$screenCntExec);
	$smarty->assign("screenCntSup",$screenCntSup);

	$smarty->display("screeningStatusReport.htm");	
}
else
{
        $msg="Your session has been timed out<br>  ";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
?>
