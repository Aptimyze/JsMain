
<?php
$path=realpath(dirname(__FILE__)."/../..");
include("$path/web/profile/connect.inc");

$db=connect_slave();
$db1=connect_db();
$sql="Select PROFILEID from newjs.JPROFILE where SERIOUSNESS_COUNT>1 and ACTIVATED='Y' AND SORT_DT<'2012-12-15'";
$result=mysql_query($sql,$db);
$arr=array();
while($row=mysql_fetch_array($result))
{
	$arr[]=$row['PROFILEID'];
}

$count=sizeof($arr);
$dataSet=$count/1000;

for($i=0;$i<$dataSet;$i++)
{
	$profileArr=array();
	for($j=1000*$i;($j<$j+1000 && $j<$count);$j++)
	{
			if($arr[$j])
			$profileArr[]=$arr[$j];
	}
	$values=implode("'),('", $profileArr);
	$sql="Insert INTO newjs.TEMP_SORT_PROFILEID (PROFILEID)";
	$sql.=" VALUES ('".$values."') ";
	$result=mysql_query($sql,$db1);
}	
	

$sql="select * from newjs.TEMP_SORT_PROFILEID where 1";
$result=mysql_query($sql,$db1);

while($row=mysql_fetch_array($result))
{
		$pid=$row['PROFILEID'];
		if($pid)
		{
		$sql="UPDATE newjs.JPROFILE set SORT_DT='2012-12-15 00:00:00' where PROFILEID =$pid";
		$result1=mysql_query($sql,$db1);
		}
}
