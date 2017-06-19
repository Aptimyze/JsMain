<?php
$path=$_SERVER['DOCUMENT_ROOT'];
include("$path/profile/connect.inc");
$db=connect_db();
$data=authenticated($checksum);
if(!$data)
{
	$smarty->assign("PREV_URL",$_SERVER['REQUEST_URI']);
	include_once($_SERVER['DOCUMENT_ROOT']."/profile/include_file_for_login_layer.php");
	$smarty->display("login_layer.htm");
	die;
}
if($casteStr)
{
        $sql_cs = "SELECT DISTINCT(VALUE), LABEL FROM newjs.CASTE WHERE VALUE IN ($casteStr)";
	if($religion)
		$sql_cs .= " AND PARENT = '".$religion."'";
	if($religion == 2 || $religion == 3)
		$option_str = "<select class=\"big lf\" name=\"caste\" style=\"margin-left:10px;\"><option value=\"\" selected>Select a sect</option>";
	else
		$option_str = "<select class=\"big lf\" name=\"caste\" style=\"margin-left:10px;\"><option value=\"\" selected>Select a caste</option>";
	$sql_cs .=" ORDER BY SORTBY";
        $res_cs = mysql_query_decide($sql_cs) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql_cs,"ShowErrTemplate");
        while($row_cs = mysql_fetch_array($res_cs))
        {
		$option_str .= "<option value=\"$row_cs[VALUE]\" >$row_cs[LABEL]</option>";

        }
	$option_str .= "</select>";
}
echo $option_str;
die;
?>
