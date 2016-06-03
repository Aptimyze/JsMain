<?php

function get_source_registrations($source,$st_date,$end_date,$like="")
{
	$sql="SELECT count(*) as cnt FROM billing.PURCHASES a LEFT JOIN newjs.JPROFILE b ON a.PROFILEID=b.PROFILEID WHERE a.ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND a.STATUS='DONE'";
	if($like=="Y")
	{
		$sql.=" AND b.SOURCE LIKE '$source' ";
	}
	else
	{
		$sql.=" AND b.SOURCE='$source' ";
	}
	$res=mysql_query($sql) or logError(mysql_error(),$sql);
	$row=mysql_fetch_array($res);

	if($row['cnt'])
		$data["cnt"]=$row['cnt'];
	else
		$data["cnt"]=0;

	$total_members=$data["cnt"];

	$sql="SELECT SUM(if(a.TYPE='DOL',AMOUNT*43.5,AMOUNT)) as amt FROM billing.PAYMENT_DETAIL a LEFT JOIN newjs.JPROFILE b ON a.PROFILEID=b.PROFILEID WHERE a.ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND a.STATUS='DONE'";
	if($like=="Y")
	{
		$sql.=" AND b.SOURCE LIKE '$source' ORDER BY a.ENTRY_DT ASC ";
	}
	else
	{
		$sql.=" AND b.SOURCE='$source' ORDER BY a.ENTRY_DT ASC ";
	}

	//echo $sql;
	$res=mysql_query($sql) or logError(mysql_error(),$sql);
	$row=mysql_fetch_array($res);

	if(!$row['amt'])
		$data["amt"]=0;
	else
		$data["amt"]=$row['amt'];

	return $data;
}

?>
