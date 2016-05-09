<?php
include("connect.inc");

$sql="SELECT USERNAME,ALLOT_DATE,AGENTID FROM incentive.ALLOT_DATA";
$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
if(mysql_num_rows($result))
{
	echo "<table border=1><tr><td>USERNAME</td><td>ALLOT DATE</td><td>AGENT ID</td></tr>";
	while($row=mysql_fetch_assoc($result))
	{
		echo"<tr><td>$row[USERNAME]</td><td>$row[ALLOT_DATE]</td><td>$row[AGENTID]</td></tr>";
	}
	echo "</table>";
}
else
{
	echo "No valid entries left";
}
?>
