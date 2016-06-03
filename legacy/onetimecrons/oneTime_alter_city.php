<?php
include("connect.inc");
include("mapping_for_sphinx1.php");
$db=connect_db();

$sql="SELECT CITY_RES,PROFILEID FROM newjs.SEARCH_MALE";
$res= mysql_query($sql) or die(mysql_error());
while($row=mysql_fetch_array($res))
{
	$city=$row['CITY_RES'];
	if($SPHINX_CITY_RMAP[$city]!='')
	{
		$sql_update="UPDATE newjs.SEARCH_MALE SET CITY_RES='$SPHINX_CITY_RMAP[$city]' WHERE PROFILEID='".$row['PROFILEID']."'";
		$res_update= mysql_query($sql_update) or die(mysql_error());
	} 
}

$sql="SELECT CITY_RES,PROFILEID FROM newjs.SEARCH_FEMALE";
$res= mysql_query($sql) or die(mysql_error());
while($row=mysql_fetch_array($res))
{
	$city=$row['CITY_RES'];
	if($SPHINX_CITY_RMAP[$city]!='')
	{
		$sql_update="UPDATE newjs.SEARCH_FEMALE SET CITY_RES='$SPHINX_CITY_RMAP[$city]' WHERE PROFILEID='".$row['PROFILEID']."'";
		$res_update= mysql_query($sql_update) or die(mysql_error());
	} 
}

?>
