<?php
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip'))
	$zipIt = 1;
if($zipIt)
	ob_start("ob_gzhandler");
if($lavesh)
	$_SERVER['ajax_error']=2;
include_once("connect.inc");
$db=connect_db();

$ret = "";
$please="Please Select";
$sql = "select  STD_CODE , VALUE, LABEL from CITY_NEW WHERE COUNTRY_VALUE='$Country_code' AND TYPE!='STATE' order by LABEL";
$res = mysql_query_optimizer($sql) or logError("error",$sql);
$ret .= "<span><select style=\"width:204px;\" name=\"city_residence\" id=\"city_residence\" onblur=\"validate(this);populate_std_code(this);\">";
$ret .= "<option value=\"\">$please</option>\n";
while($myrow = mysql_fetch_array($res))
{
	$a=$myrow["VALUE"]."##".$myrow["STD_CODE"];
	if($cityRes==$myrow["VALUE"])
		$ret .= "<option value=\"$a\" selected=\"selected\">$myrow[LABEL]</option>\n";
	else
		$ret .= "<option value=\"$a\">$myrow[LABEL]</option>\n";
}
$ret .= "</select></span>";

$sql_isd = "select ISD_CODE from COUNTRY_NEW WHERE VALUE='$Country_code'";
$res_isd = mysql_query_optimizer($sql_isd) or logError("error",$sql_isd);
$myrow_isd = mysql_fetch_array($res_isd);
$ret .= "isd";
$ret .= $myrow_isd["ISD_CODE"];

echo $ret;
die;
?>
