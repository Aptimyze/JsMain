<?php
                                                                                                                             
include("connect.inc");
														     
connect_db();

$sql="SELECT PROFILEID,LAGE,HAGE FROM newjs.JPARTNER WHERE GENDER='M'";
$res=mysql_query_decide($sql) or die(mysql_error_js());
                                                                                                                             
while($row=mysql_fetch_array($res))
{
	$pid=$row['PROFILEID'];
	$lage=$row['LAGE'];
	
	$sql1="SELECT AGE FROM newjs.JPROFILE WHERE PROFILEID='$pid'";
	$res1=mysql_query_decide($sql1)or die(mysql_error_js());
	$row1=mysql_fetch_array($res1);

	$age=$row1['AGE'];

	if($age>$lage && $age>$row['HAGE'])
	{
		$sql1="UPDATE newjs.JPARTNER SET LAGE='$age',HAGE='$age' WHERE PROFILEID='$pid'"; 
		mysql_query_decide($sql1)or die(mysql_error_js());
	}
	elseif($age>$lage)
	{
		$sql1="UPDATE newjs.JPARTNER SET LAGE='$age' WHERE PROFILEID='$pid'";
                mysql_query_decide($sql1)or die(mysql_error_js());
        }
}

?>
