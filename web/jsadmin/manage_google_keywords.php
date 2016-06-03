<?php
include('connect.inc');

if(authenticated($cid))
{
	$smarty->assign("cid",$cid);
	if ($Submit)
	{
		$iserror = 0;
		if (!$ggl_keywords)
		{
			$iserror++;
                        $error_msg = "Please enter some relevant keywords";
		}
		if (strstr($ggl_keywords,"\'") || strstr($ggl_keywords,"\"") || strstr($ggl_keywords,"-") || strstr($ggl_keywords,":") || strstr($ggl_keywords,";") || strstr($ggl_keywords,"="))
		{
			$iserror++;
			$error_msg = "Please remove any special character <b>( ' , \", - , : etc.) </b>from keywords";
		}
		if ($iserror > 0)
		{
			$ggl_keywords = stripslashes($ggl_keywords);
			$smarty->assign("keywords",$ggl_keywords);
			$smarty->assign("error_msg",$error_msg);
			$smarty->display('manage_google_keywords.htm');
		}
		else
		{
			$sql = "SELECT COUNT(*) AS CNT FROM jsadmin.GOOGLE_KEYWORDS WHERE ID='1'";
                	$res = mysql_query_decide($sql) or die("$sql".mysql_error_js());
			$row = mysql_fetch_array($res);
			if ($row['CNT'] > 0)
			{
				$sql = "UPDATE jsadmin.GOOGLE_KEYWORDS SET KEYWORDS='$ggl_keywords' WHERE ID='1'";
			}
			else
				$sql = "INSERT INTO jsadmin.GOOGLE_KEYWORDS (KEYWORDS) VALUES ('$ggl_keywords')";
			mysql_query_decide($sql) or die("$sql".mysql_error_js());

			$msg="Record Updated.<br>  ";
			$msg .="<a href=\"mainpage.php?cid=$cid\">";
			$msg .="Click here to continue </a><br><br>";
														    
			$smarty->assign("MSG",$msg);
			$smarty->display("jsadmin_msg.tpl");
		}
	}
	else
	{
		$sql = "SELECT KEYWORDS FROM jsadmin.GOOGLE_KEYWORDS";
		$res = mysql_query_decide($sql) or die("$sql".mysql_error_js());
		$row = mysql_fetch_array($res);

		$keywords = $row['KEYWORDS'];
		$smarty->assign("keywords",$keywords);
		$smarty->display('manage_google_keywords.htm');
	}
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
