<?php
/*************************************************************************************************************
Filename    : show_matchalert.php
Description : To show the match alerts sent to a user in descending order of date. [2505]
Created On  : 23 November 2007
Created By  : Sadaf Alam
**************************************************************************************************************/

include("connect.inc");
include("search_crm.inc");

$db=connect_81();

if(1)
{
	$PAGELEN=12;
	if(!$j)
		$j=0;
        if(!is_numeric($pid) || !is_numeric($j))
                die();

	$crm_pid=$pid;
	$sql="SELECT COUNT(*) AS CNT FROM matchalerts.LOG WHERE RECEIVER ='$pid'";
	$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
	$row=mysql_fetch_assoc($res);
	$totalcount=$row["CNT"];
	mysql_free_result($res);
	/*
	$sql="SELECT COUNT(*) AS CNT FROM dailyalerts.LOG WHERE RECEIVER='$pid'";
	$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
	$row=mysql_fetch_assoc($res);
	$totalcount+=$row["CNT"];
	*/
	//mysql_free_result($res);
	$sql="SELECT USERNAME FROM newjs.JPROFILE WHERE PROFILEID='$pid'";
	$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
	$row=mysql_fetch_assoc($res);
	$smarty->assign("USERNAME",$row["USERNAME"]);
	mysql_free_result($res);
	//$sql="SELECT DISTINCT(USER),DATE FROM matchalerts.LOG WHERE RECEIVER ='$pid' UNION SELECT DISTINCT(USER),DATE FROM dailyalerts.LOG WHERE RECEIVER='$pid' ORDER BY 2 DESC LIMIT $j,$PAGELEN";
	$sql="SELECT DISTINCT(USER),DATE FROM matchalerts.LOG WHERE RECEIVER ='$pid' ORDER BY 2 DESC LIMIT $j,$PAGELEN";
	$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
	if(mysql_num_rows($res))
	displayresults($res,$j,"/crm/show_matchalert.php",$totalcount,'',"1",'',"cid=$cid&pid=$pid",'','','','','',"admin");
	$smarty->assign("cid",$cid);
	$smarty->assign("pid",$pid);
	$smarty->assign("crmback","admin");
	$smarty->display("show_matchalert.htm");
	
	
}
else
{
	$msg="Your session has been timed out<br>";
	$msg.="<a href=\"$SITE_URL/jsadmin/index.htm\">";
	$msg.="Login Again</a>";
	$smarty->assign("MSG",$msg);
	$smarty->display("../jsadmin/jsadmin_msg.tpl");
}
?>
