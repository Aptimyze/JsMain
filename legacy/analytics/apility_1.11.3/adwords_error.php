<?php
include('connect_adwords_db.php');
/****************************date format=2007-10-29****************************************/

if(!$date && !$table)
{
	$dates=array('2008-06-08','2008-06-12','2008-06-18');
	//$table='99acres';
	$table='naukri';
	//$table='jeevansathi';
}
else
{
	$dates[0]=$date;
	//table and date values are passed from adwords_report_error_shell.sh
}

if(is_array($dates) && (count($dates)>0)  && $table)
for($i=0;$i<count($dates);$i++)
{
	$sql="delete from adwords_$table.KeywordCriterion_Report where Date='$dates[$i]'";
	mysql_query($sql) or die(mysql_error().$sql);

	echo '<br>';

	$sql="delete from adwords_$table.AdGroup_Report where Date='$dates[$i]'";
	mysql_query($sql) or die(mysql_error().$sql);
	
	echo '<br>';

	$sql="delete from adwords_$table.Campaign_Report where Date='$dates[$i]'";
	mysql_query($sql) or die(mysql_error().$sql);
	
	echo '<br>';

	$sql="delete from adwords_$table.Creative_Report where Date='$dates[$i]'";
	mysql_query($sql) or die(mysql_error().$sql);
	
	echo '<br>';

	//echo   '/usr/bin/wget --timeout=0 --tries=1 --append-output=/home/developer/htdocs/analytics/apility_1.11.3/adwords_log --output-document=/home/developer/htdocs/analytics/apility_1.11.3/logs/jeevansathi_report_'.$dates[$i].'.htm http://localhost/analytics/apility_1.11.3/adwords_'.$table.'_report.php?date='.$dates[$i].' &';
	
	passthru('/usr/bin/wget --timeout=0 --tries=1 --append-output=/home/developer/htdocs/analytics/apility_1.11.3/adwords_log --output-document=/home/developer/htdocs/analytics/apility_1.11.3/logs/'.$table.'_report_'.$dates[$i].'.htm http://localhost/analytics/apility_1.11.3/adwords_'.$table.'_report.php?date='.$dates[$i].' &');

	echo '<br>';
	echo '<br>';
	echo '<br>';

}
?>
