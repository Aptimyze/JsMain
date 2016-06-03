<?php
include("connect.inc");
connect_db();

$sql="SELECT VALUE,LABEL from RELIGION ORDER BY SORTBY";
$result=mysql_query_decide($sql);
$str="";
$j=0;
$i=0;
while($myrow=mysql_fetch_row($result))
{
	$strtemp = '';
	$religion_value[]="$myrow[0]";
	$strtemp .= $religion_value[$j]."|X|";

	$sql="SELECT VALUE,LABEL from CASTE where PARENT='$myrow[0]' ";
	$result1= mysql_query_decide($sql);
	
	while($myrow1=mysql_fetch_row($result1))
	{
		$caste_value[]="$myrow1[0]";	
		$caste_label[]="$myrow1[1]";
		$strtemp .= $caste_value[$i]."$".$caste_label[$i]."#";
		$i++;
	}
	$strtemp = substr($strtemp,0,(strlen($strtemp)-1));
	$strtemp .= "<br><br>";
	$j++;
	$str[] = $strtemp;
}
print_r($str);
?>
