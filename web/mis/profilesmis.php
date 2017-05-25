<?php
include("connect.inc");
$db=connect_misdb();
$db2=connect_master();

//mysql_select_db("newjs");

if($Submit)
{
	$date1=$year1."-".$month1."-".$day1." 00:00:00";
	$date2=$year2."-".$month2."-".$day2." 23:59:59";
	if($Source=='ALL')
	{
		$sql="SELECT count(*) from newjs.JPROFILE where ENTRY_DT between '$date1' and '$date2' AND INCOMPLETE <> 'Y' AND ACTIVATED != 'D'";
		$result=mysql_query_decide($sql,$db) or die(mysql_error_js());
		$myrow=mysql_fetch_row($result);
		$new=$myrow[0];

		$sql="SELECT count(*) from newjs.JPROFILE where MOD_DT != ENTRY_DT AND MOD_DT between '$date1' and '$date2' AND INCOMPLETE <> 'Y' AND ACTIVATED != 'D'";
		$result=mysql_query_decide($sql,$db) or die(mysql_error_js());
		$myrow=mysql_fetch_row($result);
		$edit=$myrow[0];
		$total=$new+$edit;
	}
	else
	{
		if ($Source=="IP")
			$sourcestring= "SOURCE in ('IP','')";
		else
			$sourcestring= "SOURCE = '$Source'";
		$sql="SELECT count(*) from newjs.JPROFILE where ENTRY_DT between '$date1' and '$date2' AND ".$sourcestring." AND INCOMPLETE <> 'Y' AND ACTIVATED != 'D'";
		$result=mysql_query_decide($sql,$db) or die(mysql_error_js());
		$myrow=mysql_fetch_row($result);
		$new=$myrow[0];
		$sql="SELECT count(*) from newjs.JPROFILE where MOD_DT != ENTRY_DT AND MOD_DT between '$date1' and '$date2' AND ".$sourcestring." AND INCOMPLETE <> 'Y' AND ACTIVATED != 'D'";
		$result=mysql_query_decide($sql,$db) or die(mysql_error_js());
		$myrow=mysql_fetch_row($result);
		$edit=$myrow[0];
		$total=$new+$edit;
	}
	$smarty->assign("TOTAL",$total);
	$smarty->assign("NEW",$new);
	$smarty->assign("EDIT",$edit);
	$smarty->assign("PAGE","1");
	$smarty->assign("YEAR1",$year1);
	$smarty->assign("MONTH1",$month1);
	$smarty->assign("DAY1",$day1);
	$smarty->assign("YEAR2",$year2);
	$smarty->assign("MONTH2",$month2);
	$smarty->assign("DAY2",$day2);

	$smarty->assign("source",create_dd($Source,"Source"));	
	$smarty->display("profilesmis.tpl");
}
else
{
	$smarty->assign("source",create_dd($Source,"Source"));	
	$smarty->display("profilesmis.tpl");
}
?>
