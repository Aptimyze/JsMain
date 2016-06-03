<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

/************************************************************************************************************
File		: maintain_servicelist.php	
Description 	: Corn for maintaining the process of providing service to offline profile.
Developed By	: Vibhor Garg
Date		: 12-07-2008
*************************************************************************************************************/
include_once("$_SERVER[DOCUMENT_ROOT]/jsadmin/connect.inc");
$profileArray=array();
$sql="SELECT PROFILEID,ENTRY_DATE,SERVICE_DATE,SERVICED FROM OFFLINE_BILLING WHERE DATEDIFF(CURDATE()+1,ENTRY_DATE)%15=0 AND ACTIVE='Y'";
$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
while($row=mysql_fetch_assoc($result))
{
	$profileid = $row["PROFILEID"];
	if(!in_array($profileid,$profileArray))
        {
		$profileArray[] = $profileid;
		$entry_date=$row["ENTRY_DATE"];
		$edate[]=$row["ENTRY_DATE"];
		if($row["SERVICE_DATE"]=="0000-00-00")
			$service_date[] = cal_service_date($entry_date);
		else
		$service_date[] = $row["SERVICE_DATE"];		
		$service[] = $row["SERVICED"];         
	}
}
$count1= mysql_num_rows($result);
$day=date("D");
if($day == 'Sat')
{
	$sql2="SELECT PROFILEID,ENTRY_DATE,SERVICE_DATE,SERVICED FROM OFFLINE_BILLING WHERE DATEDIFF(CURDATE()+2,ENTRY_DATE)%15=0 AND ACTIVE='Y'";
	$result2=mysql_query_decide($sql2,$db) or die("$sql2".mysql_error_js($db));
	while($row2=mysql_fetch_assoc($result2))
	{
		$profileid = $row2["PROFILEID"];
		if(!in_array($profileid,$profileArray))
		{
			$profileArray[] = $profileid;
			$entry_date=$row2["ENTRY_DATE"];
			$edate[]=$row2["ENTRY_DATE"];
			if($row2["SERVICE_DATE"]=="0000-00-00")
				$service_date[] = cal_service_date($entry_date);
			else
                		$service_date[] = $row2["SERVICE_DATE"];
                	$service[] = $row2["SERVICED"];
        	}
	}
	$count2= mysql_num_rows($result2);
}
if(!$count2)
	$count2=0;
$count=$count1+$count2;
for($i=0;$i<$count;$i++)
{
	if($service[$i]=='Y')
	{
		$sql="UPDATE OFFLINE_BILLING SET SERVICED='' where PROFILEID='$profileArray[$i]'";
                $result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
	}
	elseif($service[$i]=='')
	{
		$sql="UPDATE OFFLINE_BILLING SET SERVICED='N',SERVICE_DATE='$service_date[$i]' where PROFILEID='$profileArray[$i]' AND ENTRY_DATE='$edate[$i]'";
                $result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
	}
}
function cal_service_date($entry_date)
{
        $entry_time = strtotime($entry_date);
        $present_time = time();
        $service_time = $entry_time;
        while($service_time<$present_time)
                $service_time+=15*24*60*60;
        $service_dt=date("Y-m-d",$service_time);
        return $service_dt;
}
?>
