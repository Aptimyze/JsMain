<?php
ini_set("max_execution_time","0");
include_once("connect.inc");
$db= connect_db();

$sql="SELECT ID,CASTE,MTONGUE,HANDICAPPED,LHEIGHT,HHEIGHT,INCOME FROM SEARCH_AGENT";
$result = mysql_query_decide($sql,$db) or die(mysql_error($db));
while($row=mysql_fetch_array($result))
{
	$id=$row["ID"];
	$pcaste=$row["CASTE"];
	$pmtongue=$row["MTONGUE"];
	$phandicap=$row["HANDICAPPED"];

	$pincome=$row["INCOME"];

	if($pincome)
	{
		$pincome_arr=@explode(",",$pincome);
		foreach($pincome_arr as $k=>$v)
		{
			if($v=="10")
				$pincome_arr[$k]="11";
			elseif($v=="11")
				$pincome_arr[$k]="12";
			elseif($v=="12")
				$pincome_arr[$k]="13";
			elseif($v=="13")
				$pincome_arr[$k]="21";
		}
		$pincome_new=@implode(",",$pincome_arr);
		if($pincome!=$pincome_new)
			$update_arr[]= "INCOME=\"$pincome_new\"";
		unset($pincome_arr);
	}
	if($pcaste)
	{
		$sql1="SELECT DISTINCT(PARENT) FROM CASTE WHERE VALUE IN ($pcaste)";
		$result1 = mysql_query_decide($sql1,$db) or die($sql1.mysql_error($db));
		while($row1=mysql_fetch_array($result1))
			$preligion_arr[] = $row1["PARENT"];
		if(count($preligion_arr)!=0)
		{
			if(count($preligion_arr)>1)
				$preligion = implode(",",$preligion_arr);
			else
				$preligion = $preligion_arr[0];

			$update_arr[]= "RELIGION=\"$preligion\"";
			unset($preligion_arr);
		}
		$pcaste_arr=@explode(",",$pcaste);
		foreach($pcaste_arr as $k=>$v)
		{
			if($v=="145")
				$pcaste_arr[$k]="136";
			elseif($v=="14")
				$pcaste_arr[$k]="242";
		}
		$pcaste_new=@implode(",",$pcaste_arr);
		if($pcaste!=$pcaste_new)
			$update_arr[]= "CASTE=\"$pcaste_new\"";
		unset($pcaste_arr);
	}
	if($pmtongue)
	{
		$pmtongue_arr=@explode(",",$pmtongue);
		foreach($pmtongue_arr as $k=>$v)
		{
			if($v=="8")
				$pmtongue_arr[$k]="12";
			elseif($v=="26")
				$pmtongue_arr[$k]="31";
			elseif($v=="11")
				$pmtongue_arr[$k]="34";
		}
		$pmtongue_new=@implode(",",$pmtongue_arr);
		if($pmtongue!=$pmtongue_new)
			$update_arr[]= "MTONGUE=\"$pmtongue_new\"";
		unset($pmtongue_arr);
	}
	if($phandicap=='Y')
	{
		$update_arr[]= "HANDICAPPED=\"1,2,3,4\"";
	}
	if($row['LHEIGHT']!='0' && $row['HHEIGHT']!='0')
		$update_arr[]= "LHEIGHT=LHEIGHT+5, HHEIGHT=HHEIGHT+5";

	$update_str=@implode(",",$update_arr);
	unset($update_arr);
	if($update_str)
	{
		$sql2="UPDATE SEARCH_AGENT SET $update_str WHERE ID='$id'";
		$result2 = mysql_query_decide($sql2,$db) or die(mysql_error());
	}


}

?>
