<?php

include("connect.inc");
connect_db();

$sql="SELECT DTOFBIRTH,PROFILEID FROM newjs.JPROFILE ORDER BY PROFILEID ASC LIMIT 200000,30000";
$res=mysql_query_decide($sql) or die(mysql_error_js());
if($row=mysql_fetch_array($res))
{
	do
	{
		$dob=$row['DTOFBIRTH'];
		$profileid=$row['PROFILEID'];
		$getage=getAge($dob);

		$sql="UPDATE newjs.JPROFILE SET AGE='$getage' WHERE PROFILEID='$profileid'";
		mysql_query_decide($sql) or die(mysql_error_js());
	}while($row=mysql_fetch_array($res));
}
?>
