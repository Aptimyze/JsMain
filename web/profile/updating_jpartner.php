<?php
ini_set("max_execution_time","0");
include_once("connect.inc");
$db=connect_db();

$sql="SELECT PROFILEID,HHEIGHT,LHEIGHT FROM JPARTNER WHERE DPP<>'O' AND GENDER='M'";
$res=mysql_query_decide($sql) or die(mysql_error_js());
while($row=mysql_fetch_array($res))
{
	$pid=$row["PROFILEID"];
	$hheight=$row["HHEIGHT"];
	$lheight=$row["LHEIGHT"];

	$sql_i="SELECT HEIGHT FROM JPROFILE WHERE  activatedKey=1 and PROFILEID='$pid'"; 
	$res_i=mysql_query_decide($sql_i) or die(mysql_error_js());
	$row_i=mysql_fetch_array($res_i);
	$height=$row_i["HEIGHT"];

	if($hheight <= $height)
	{
		$newlh=$height;
		$newhh=$height+10;
		if($newhh>32)
			$newhh = 32;

		$sql_i="UPDATE JPARTNER SET HHEIGHT='$newhh' , LHEIGHT='$newlh' WHERE PROFILEID='$pid'";
		mysql_query_decide($sql_i) or die(mysql_error_js());
	}
}

?>
