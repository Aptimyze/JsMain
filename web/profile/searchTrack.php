<?php
include("connect.inc");
$_SERVER['ajax_error']=1;
$db=connect_db();
$headTime=$headTime/1000;
$difference=$difference/1000;
$bodyTime=$bodyTime/1000;
$dns=round(($differnceDNS/1000-$pagExec),2);
$total=round(($difference+$pagExec+$dns),2);
$total2=$difference+$pagExec;
$errorstringtemp.="\" >> /var/www/html/profile/logerror_temp.txt";
$tmp="echo $differnceDNS---::$nikhil---::$ankit >>/var/www/html/profile/lavesh.txt";
//passthru($tmp);
//die;
if($dns<1000 && $dns>0 && $ankit)
{
$sql="INSERT INTO MIS.SEARCH_PROFILING_NEW VALUES ('','$searchid','$difference','$headTime','$bodyTime','$pagExec','$dns','$total','$total2',now(),'$sphinxS')";
mysql_query($sql) or die(mysql_error());

if($dns>100)
{
$sql="INSERT INTO MIS.ERROR_PROFILING_NEW VALUES ($searchid,'$dns','$ankit','$nikhil','$differnceDNS','$nikhil-$ankit')";
mysql_query($sql);
}
} 
else
{
$sql="INSERT INTO MIS.ERROR_SEARCH_PROFILING_NEW VALUES ('','$searchid','$difference','$headTime','$bodyTime','$pagExec','$dns','$total','$total2',now(),'$sphinxS')";
mysql_query($sql) or die(mysql_error());
}
?>

