<?php

include_once("./includes/bms_connect.php");


$sql="SELECT BannerId, sum( Impressions ) AS TOT FROM BANNERMIS
WHERE BannerId IN ( 1078, 1546, 1558, 2144, 2147, 3505, 3506, 3507, 3508, 3535, 3727, 3851, 3877, 4058, 4062, 4138, 4210, 4241, 4359, 4452, 4457, 4478, 4533, 4555, 4556, 4557, 4558, 4559, 4580, 4597, 4598, 4604, 4605, 4658 ) GROUP BY BannerID";
$res = mysql_query($sql) or die ("$sql".mysql_error());
while ($row = mysql_fetch_array($res))
{
	$temp=$row["TOT"];
	$pid=$row["BannerId"];

	echo $sql1="UPDATE BANNERHEAP set BannerCount=$temp WHERE BannerId='$pid'";
	echo "<br>";
	//mysql_query($sql1)or die ("$sql1".mysql_error());
}

?>
	

