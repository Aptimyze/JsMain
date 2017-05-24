<?php
include("connect.inc");

$sql_1="SELECT DISTINCT(CITY) FROM billing.BLUEDART_PINCODE GROUP BY CITY";
$res_1=mysql_query_decide($sql_1) or die(mysql_error_js());
while($row_1=mysql_fetch_array($res_1))
{
	$city_pin[]=$row_1['CITY'];	
	$smarty->assign('CITY_PIN',$city_pin);
}	

if($submit)
{
	$sql="SELECT CITY,BDEL_LOC,STATE,PINCODE FROM billing.BLUEDART_PINCODE WHERE CITY='$pin'";
	$res=mysql_query_decide($sql) or die(mysql_error_js());
	$count=mysql_num_rows($res);
	if($count)
	{
		while($row=mysql_fetch_array($res))
		{	
			$state[]=$row['STATE'];
			$city[]=$row['CITY'];
			$subarea[]=$row['BDEL_LOC'];
			$pincode[]=$row['PINCODE'];

			$smarty->assign('count',$count);
			$smarty->assign('state',$state);
			$smarty->assign('area',$city);
			$smarty->assign('subarea',$subarea);
			$smarty->assign('pincode',$pincode);
			$smarty->assign('showmessage','Y');
		}
	}
	else
	{
		$smarty->assign('showmessage','Y');
		$smarty->assign('NO_RECORD','Y');
	}
}
$smarty->assign('pin',$pin);
$smarty->display('bluedart_pincode.htm');

?>
