<?php
$flag_using_php5=1;
include("connect.inc");
include("ap_common.php");

$db=connect_db();

$endFor=array();

$sql="SELECT COUNT(*) AS COUNT,AP_PROFILE_INFO.PROFILEID,ACTIVE FROM Assisted_Product.AP_PROFILE_INFO LEFT JOIN billing.SERVICE_STATUS ON AP_PROFILE_INFO.PROFILEID = SERVICE_STATUS.PROFILEID WHERE SERVEFOR = 'T' GROUP BY PROFILEID,ACTIVE ORDER BY BILLID ASC";
$res=mysql_query($sql) or die("query failed   ".mysql_error());
if(mysql_num_rows($res))
{
	while($row=mysql_fetch_assoc($res))
	{
		$pid=$row["PROFILEID"];
		if($row["ACTIVE"]=='E' || $row["ACTIVE"]=='N')
		{ 
			if(!in_array($pid,$endFor))
				$endFor[$pid]=$pid;
		}
		if($row["ACTIVE"]=='Y')
		{
			if(in_array($pid,$endFor))
				unset($endFor[$pid]);
				
		}
		
	}
	foreach($endFor as $key=>$value)
		endAutoApply($value);
}
?>
