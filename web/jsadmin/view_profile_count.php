<?php
/*****
*	FILENAME	:	view_profile_count.php
*	DESCRIPTION	:	Displays new/skip profile stats to the administrator
*	CREATED BY	:	Tripti Singh
*	CREATED ON	:	5th July,2006
*****/

include("connect.inc");
include("time1.php");
include(JsConstants::$docRoot."/commonFiles/flag.php");

global $screen_time;
$tdate=date("Y-m-d");
$lastweek_date=strftime("%Y-%m-%d",JSstrToTime("$tdate-5days "));
$sum=SetAllFlags();

if(authenticated($cid))
{
	$user=getname($cid);
	if($val=="new")
	{
		$sql_s="SELECT COUNT(*) as cnt FROM newjs.JPROFILE J LEFT JOIN newjs.JPROFILE_CONTACT C ON J.PROFILEID=C.PROFILEID WHERE ACTIVATED='N' AND INCOMPLETE='N' AND (J.MOB_STATUS='Y' OR J.LANDL_STATUS='Y' OR C.ALT_MOB_STATUS='Y')";
	}
	elseif($val=="edit")
	{
		$sql_s="SELECT count(*) as cnt FROM newjs.JPROFILE USE INDEX(SCREENING) LEFT JOIN jsadmin.MAIN_ADMIN ON jsadmin.MAIN_ADMIN.PROFILEID = newjs.JPROFILE.PROFILEID WHERE (jsadmin.MAIN_ADMIN.PROFILEID IS NULL) AND ( ACTIVATED = 'Y' AND SCREENING < '1099511627775' ) ";
	}
	$res_s=mysql_query_decide($sql_s) or die("$sql_s".mysql_error_js());
	$row_s=mysql_fetch_array($res_s);
	$cnt=$row_s['cnt'];
	if(mysql_num_rows($res_s)<1)
	{
		$cnt=0;
	}
	$sql_u="SELECT SQL_CACHE USERNAME,PRIVILAGE FROM jsadmin.PSWRDS WHERE PRIVILAGE like '%NU%'";
	$res_u=mysql_query_decide($sql_u) or die(mysql_error_js());
	if($row_u=mysql_fetch_array($res_u))
	{
		$now=date('Y-m-d');
		$i=0;
		do
		{
			$s_user[$i]=$row_u['USERNAME'];
			//$sql="SELECT COUNT(*) AS sno FROM MAIN_ADMIN_LOG,newjs.JPROFILE WHERE ALLOTED_TO='$s_user[$i]' AND SUBMITED_TIME LIKE '$now%' AND SCREENING_TYPE='O' AND newjs.JPROFILE.ACTIVATED<>'D' AND MAIN_ADMIN_LOG.PROFILEID=newjs.JPROFILE.PROFILEID";         
			$sql="SELECT COUNT(*) AS sno FROM MAIN_ADMIN_LOG WHERE ALLOTED_TO='$s_user[$i]' AND SUBMITED_TIME >= '$now' AND SCREENING_TYPE='O'";
			$result=mysql_query_decide($sql) or die(mysql_error_js());
			$row=mysql_fetch_array($result);
			$sno[$i]=$row['sno'];
			//$tqueue+=$sno[$i];
			$i++;
		}while($row_u=mysql_fetch_array($res_u));
	}
	$sql_sk="SELECT COUNT(*) as cnt from jsadmin.MAIN_ADMIN where SCREENING_TYPE='O' AND SKIP_FLAG='Y'";
	$result_sk=mysql_query_decide($sql_sk) or die(mysql_error_js()); 
	$row_sk=mysql_fetch_assoc($result_sk);
	$tqueue=$row_sk['cnt'];
	$smarty->assign("s_user",$s_user);
	$smarty->assign("cid",$cid);
	$smarty->assign("val",$val);
	$smarty->assign("user",$user);
	$smarty->assign("sno",$sno);
	$smarty->assign("totalnew",$cnt);
	$smarty->assign("totalqueue",$tqueue);
	$smarty->assign("flag",$flag);
	$smarty->display("view_profile_count.htm");
}
else
{
	$msg="Your session has been timed out<br>";
        $msg .="<a href=\"index.htm\">";
        $msg .="Login again </a>";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");
}
?>
