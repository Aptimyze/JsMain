<?php
include("../mis/connect.inc");
$db=connect_master();
$sql="SELECT PROFILEID,USERNAME,STATUS,UPLOAD_DATE,VERIFIED_DATE,UPLOADED_BY,VERIFIED_BY,VERIFIED FROM billing.UPLOAD_MATRI_STATUS";
$res=mysql_query_decide($sql);
while($row=mysql_fetch_array($res))
{
	$profileid=$row['PROFILEID'];
	$username=$row['USERNAME'];
	$status=$row['STATUS'];
	$upload_date=$row['UPLOAD_DATE'];
	$verified_date=$row['VERIFIED_DATE'];
	$uploaded_by=$row['UPLOADED_BY'];
	$verified_by=$row['VERIFIED_BY'];
	$verified=$row['VERIFIED'];
	$sql2="SELECT ENTRY_DT FROM billing.PURCHASES WHERE (SERVICEID='M' OR ADDON_SERVICEID REGEXP 'M') AND STATUS='DONE' AND PROFILEID='$profileid'";
	$res2=mysql_query_decide($sql2);
	while($row2=mysql_fetch_array($res2))
	{
		$entry_dt=$row2['ENTRY_DT'];
	}
	//$scheduled_time=date("Y-m-d G:i:s", JSstrToTime($entry_dt)+7*24*60*60);
	if($status=='N')
	{
		$sql1="INSERT INTO billing.MATRI_PROFILE(PROFILEID,USERNAME,ALLOTTED_TO,ALLOT_TIME,ENTRY_DT,STATUS) VALUES('$profileid','$username','vertika','$entry_dt','$entry_dt','N')";
		mysql_query_decide($sql1);
	}
	elseif($status=='Y' and $verified=='N')
	{
		$sql1="INSERT INTO billing.MATRI_PROFILE(PROFILEID,USERNAME,ALLOTTED_TO,ALLOT_TIME,ENTRY_DT,STATUS) VALUES('$profileid','$username','vertika','$entry_dt','$entry_dt','N')";
		mysql_query_decide($sql1);
	}
	elseif($status=='Y' and $verified=='Y')
	{
	//	$sql1="INSERT INTO billing.MATRI_PROFILE(PROFILEID,USERNAME,ALLOTTED_TO,ALLOT_TIME,SCHEDULED_TIME,COMPLETION_TIME,ENTRY_DT,STATUS) VALUES('$profileid','$username','vertika','$entry_dt','$scheduled_time','$verified_date','$entry_dt','V')";echo $sql1.'<br>';
	//	mysql_query_decide($sql1);
		$sql3="INSERT INTO billing.MATRI_COMPLETED(PROFILEID,USERNAME,ALLOTTED_TO,ALLOT_TIME,VERIFIED_BY,VERIFY_DATE,ENTRY_DT,CUTS,COMPLETION_TIME,ONHOLD_TIME,REASON_IFHOLD) VALUES('$profileid','$username','vartika','$entry_dt','vartika','$verified_date','$entry_dt',1,'$verified_date','0000-00-00 00:00:00','')";
		mysql_query_decide($sql3);
	}
}
?>
		
		
