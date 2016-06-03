<?php

include("connect.inc");
include("../profile/display_result.inc");

$db2=connect_737();
$db=connect_db();

if(authenticated($cid))
{
	if ($mail_list != "")
	{
		$list_blank_separated = str_replace(","," ",$mail_list) ;

		$sql="SELECT j.PROFILEID, j.USERNAME FROM newjs.JPROFILE as j, newjs.SEARCH_MALE_FULL as s WHERE j.PROFILEID = s.PROFILEID and MATCH (s.KEYWORDS, s.SUBCASTE, s.YOURINFO, s.FAMILYINFO, s.SPOUSE, s.EDUCATION , s.FATHER_INFO, s.JOB_INFO, s.SIBLING_INFO) AGAINST ('".$list_blank_separated."') and j.SCREENING = '4094303'";
		$result = mysql_query_decide($sql,$db2) or die("$sql : ".mysql_error_js($db2));
		$num_rows = mysql_num_rows($result);
		while($myrow=mysql_fetch_array($result))
		{
			$sql = "INSERT IGNORE INTO CHECK_MAILID(PROFILEID,USERNAME) VALUES ('".$myrow['PROFILEID']."','".addslashes(stripslashes($myrow['USERNAME']))."')"; 
			mysql_query_decide($sql,$db) or die("$sql : ".mysql_error_js($db));
		}

		$sql = "SELECT j.PROFILEID, j.USERNAME FROM newjs.JPROFILE as j, newjs.SEARCH_FEMALE_FULL as s WHERE j.PROFILEID = s.PROFILEID and MATCH (s.KEYWORDS, s.SUBCASTE, s.YOURINFO, s.FAMILYINFO, s.SPOUSE, s.EDUCATION, s.FATHER_INFO, s.JOB_INFO, s.SIBLING_INFO) AGAINST ('".$list_blank_separated."') and j.SCREENING = '4094303'";
		$result = mysql_query_decide($sql,$db2) or die("$sql : ".mysql_error_js($db2));
		$num_rows += mysql_num_rows($result);
		while($myrow=mysql_fetch_array($result))
		{
			$sql = "INSERT IGNORE INTO CHECK_MAILID(PROFILEID,USERNAME) VALUES ('".$myrow['PROFILEID']."','".addslashes(stripslashes($myrow['USERNAME']))."')"; 
			mysql_query_decide($sql,$db) or die("$sql : ".mysql_error_js($db));
		}
	}

	$smarty->assign("NUM_ROWS",$num_rows);
	$smarty->assign("CID",$cid);
	$smarty->assign("FOOT",$smarty->fetch("foot.htm"));
	$smarty->assign("HEAD",$smarty->fetch("head.htm"));
	$smarty->display("mailid_search.htm");
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
