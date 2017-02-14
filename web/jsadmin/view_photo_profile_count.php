<?php
/*****
*	FILENAME	:	view_photo_profile_count.php
*	DESCRIPTION	:	Displays new/edit/skip/mail photo profile stats to the administrator
*	CREATED BY	:	Sadaf Alam
*	CREATED ON	:	28th May,2007
*****/

include("connect.inc");
include("time1.php");
include(JsConstants::$docRoot."/commonFiles/flag.php");

global $screen_time;
$tdate=date("Y-m-d");
$lastweek_date=strftime("%Y-%m-%d",JSstrToTime("$tdate-5days "));
$sum=SetAllFlags();

$db = connect_rep();

if(authenticated($cid))
{
	$user=getname($cid);

	//Symfony Photo Modification
	$sql_s="SELECT COUNT(*) as cnt FROM newjs.JPROFILE LEFT JOIN jsadmin.MAIN_ADMIN ON jsadmin.MAIN_ADMIN.PROFILEID = newjs.JPROFILE.PROFILEID WHERE (jsadmin.MAIN_ADMIN.PROFILEID IS NULL) AND (HAVEPHOTO='U' AND PHOTOSCREEN=0)";

        $res_s=mysql_query_decide($sql_s,$db) or die("$sql_s".mysql_error_js());
        $row_s=mysql_fetch_array($res_s);
        $unewcnt=$row_s['cnt'];
        if(mysql_num_rows($res_s)<1)
        {
                $unewcnt=0;
        }

	$sql_s="SELECT COUNT(*) AS cnt FROM newjs.PICTURE_FOR_SCREEN_APP";
	$res_s=mysql_query_decide($sql_s,$db) or die("$sql_s".mysql_error_js());
        $row_s=mysql_fetch_array($res_s);
	$uappcnt=$row_s['cnt'];

	//Symfony Photo Modification
	$sql_s="SELECT count(*) as cnt FROM newjs.JPROFILE LEFT JOIN jsadmin.MAIN_ADMIN ON jsadmin.MAIN_ADMIN.PROFILEID = newjs.JPROFILE.PROFILEID WHERE (jsadmin.MAIN_ADMIN.PROFILEID IS NULL) AND (HAVEPHOTO='Y' AND PHOTOSCREEN=0) ";
	
	$res_s=mysql_query_decide($sql_s,$db) or die("$sql_s".mysql_error_js());
	$row_s=mysql_fetch_array($res_s);
	$ueditcnt=$row_s['cnt'];
	if(mysql_num_rows($res_s)<1)
	{
		$ueditcnt=0;
	}
	$sql_u="SELECT SQL_CACHE USERNAME,PRIVILAGE FROM jsadmin.PSWRDS WHERE PRIVILAGE like '%PU%'";
	$res_u=mysql_query_decide($sql_u,$db) or die(mysql_error_js());
	if($row_u=mysql_fetch_array($res_u))
	{
		$now=date('Y-m-d');
		$i=0;
		do
		{
			$s_user[$i]=$row_u['USERNAME'];
			//$sql="SELECT COUNT(*) AS sno FROM MAIN_ADMIN_LOG,newjs.JPROFILE WHERE ALLOTED_TO='$s_user[$i]' AND SUBMITED_TIME LIKE '$now%' AND SCREENING_TYPE='O' AND newjs.JPROFILE.ACTIVATED<>'D' AND MAIN_ADMIN_LOG.PROFILEID=newjs.JPROFILE.PROFILEID";         
			
			$sql="SELECT NEW+NEW_DEL  as sno FROM MIS.PHOTO_SCREEN_STATS WHERE SCREENED_BY='$s_user[$i]' AND DATE=CURDATE()";
			$result=mysql_query_decide($sql,$db) or die(mysql_error_js());
	                $row=mysql_fetch_array($result);
        	        if($row['sno'])
                	$snonew[$i]=$row['sno'];
                        else
	                $snonew[$i]=0;
        	        mysql_free_result($result);
			$sql="SELECT EDIT+EDIT_DEL as sno FROM MIS.PHOTO_SCREEN_STATS WHERE SCREENED_BY='$s_user[$i]' AND DATE=CURDATE()";
			$result=mysql_query_decide($sql,$db) or die(mysql_error_js());
			$row=mysql_fetch_array($result);
			if($row['sno'])
			$snoedit[$i]=$row['sno'];
			else
			$snoedit[$i]=0;
			mysql_free_result($result);
			$sql="SELECT COUNT(*) AS sno FROM SCREEN_PHOTOS_FROM_MAIL WHERE ASSIGNED_TO='$s_user[$i]' AND SUBMITED_DATE >= '$now'";
			$result=mysql_query_decide($sql,$db) or die(mysql_error_js());
                        $row=mysql_fetch_array($result);
                        $smailno[$i]=$row['sno'];
                        mysql_free_result($result);
			//$tqueue+=$sno[$i];
			$i++;
		}while($row_u=mysql_fetch_array($res_u));
	}
	$sql_sk="SELECT COUNT(*) as cnt from jsadmin.MAIN_ADMIN where SCREENING_TYPE='P' AND SKIP_FLAG='Y'";
	$result_sk=mysql_query_decide($sql_sk,$db) or die(mysql_error_js()); 
	$row_sk=mysql_fetch_assoc($result_sk);
	$tqueue=$row_sk['cnt'];
	$sql_mail="SELECT COUNT(*) AS CNT FROM jsadmin.PHOTOS_FROM_MAIL LEFT JOIN jsadmin.SCREEN_PHOTOS_FROM_MAIL ON PHOTOS_FROM_MAIL.ID=SCREEN_PHOTOS_FROM_MAIL.MAILID WHERE SCREEN_PHOTOS_FROM_MAIL.MAILID IS NULL AND ATTACHMENT='Y'";
	$result_mail=mysql_query_decide($sql_mail,$db) or die("$sql_mail".mysql_error_js());
	$row_mail=mysql_fetch_assoc($result_mail);
	$mail_count=$row_mail["CNT"];
	mysql_free_result($result_mail);
	$sql_mail="SELECT COUNT(*) AS CNT FROM jsadmin.SCREEN_PHOTOS_FROM_MAIL WHERE SKIP='Y'";
	$result_mail=mysql_query_decide($sql_mail,$db) or die("$sql_mail".mysql_error_js());
	$row_mail=mysql_fetch_assoc($result_mail);
	$skip_mail_count=$row_mail["CNT"];
	mysql_free_result($result_mail);
	$smarty->assign("skip_mail_count",$skip_mail_count);
	$smarty->assign("mail_count",$mail_count);
	$smarty->assign("s_user",$s_user);
	$smarty->assign("cid",$cid);
	$smarty->assign("val",$val);
	$smarty->assign("unewcnt",$unewcnt);
	$smarty->assign("ueditcnt",$ueditcnt);
	$smarty->assign("uappcnt",$uappcnt);
	$smarty->assign("snonew",$snonew);
	$smarty->assign("snoedit",$snoedit);
	$smarty->assign("user",$user);
	$smarty->assign("sno",$sno);
	$smarty->assign("smailno",$smailno);
	$smarty->assign("totalqueue",$tqueue);
	$smarty->assign("flag",$flag);
	$smarty->display("view_photo_profile_count.htm");
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
