<?php
ini_set("max_execution_time","0");
include_once("connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/jpartner_include.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/shardingRelated.php");
$mysqlObj=new Mysql;
$db=connect_slave();
for($activeServerId=0;$activeServerId<3;$activeServerId++)
{
	echo "shard".$activeServerId;
        //Sharding
        $myDbName=getActiveServerName($activeServerId,'master');
        $myDb=$mysqlObj->connect("$myDbName");
        //Sharding
	$sql="SELECT PARTNER_INCOME,PROFILEID,PARTNER_CASTE,PARTNER_MTONGUE,HANDICAPPED,LHEIGHT,HHEIGHT FROM JPARTNER" ; 
        $result = mysql_query_decide($sql,$myDb) or die(mysql_error($myDb));
        while($row=mysql_fetch_array($result))
	{
		$pid=$row["PROFILEID"];
		$pcaste=$row["PARTNER_CASTE"];
		$pmtongue=$row["PARTNER_MTONGUE"];
		$phandicap=$row["HANDICAPPED"];
		$pincome=$row["PARTNER_INCOME"];

		if($pincome)
		{
			$pincome_arr=@explode(",",$pincome);
			foreach($pincome_arr as $k=>$v)
			{
				if($v=="'10'")
					$pincome_arr[$k]="'11'";
				elseif($v=="'11'")
					$pincome_arr[$k]="'12'";
				elseif($v=="'12'")
					$pincome_arr[$k]="'13'";
				elseif($v=="'13'")
					$pincome_arr[$k]="'21'";
			}
			$pincome_new=@implode(",",$pincome_arr);
			if($pincome!=$pincome_new)
				$update_arr[]= "PARTNER_INCOME=\"$pincome_new\"";
			unset($pincome_arr);
		}
		if($pcaste)
		{
			$sql1="SELECT DISTINCT(PARENT) FROM CASTE WHERE VALUE IN ($pcaste)";
	        	$result1 = mysql_query_decide($sql1,$db) or die(mysql_error($db));
		      	while($row1=mysql_fetch_array($result1))
				$preligion_arr[] = $row1["PARENT"];
			if(count($preligion_arr)!=0)
			{
				if(count($preligion_arr)>1)
					$preligion = "'".implode("','",$preligion_arr)."'";
				else
					$preligion = "'".$preligion_arr[0]."'";	

				$update_arr[]= "PARTNER_RELIGION=\"$preligion\"";	
				unset($preligion_arr);
			}
			$pcaste_arr=@explode(",",$pcaste);
			foreach($pcaste_arr as $k=>$v)
			{
				if($v=="'145'")
					$pcaste_arr[$k]="'136'";	
				elseif($v=="'14'")
					$pcaste_arr[$k]="'242'";
			}
			$pcaste_new=@implode(",",$pcaste_arr);
			if($pcaste!=$pcaste_new)
				$update_arr[]= "PARTNER_CASTE=\"$pcaste_new\"";
			unset($pcaste_arr);
		}
		if($pmtongue)
		{
			$pmtongue_arr=@explode(",",$pmtongue);
			foreach($pmtongue_arr as $k=>$v)
			{
				if($v=="'8'")
					$pmtongue_arr[$k]="'12'";
				elseif($v=="'26'")
					$pmtongue_arr[$k]="'31'";
				elseif($v=="'11'")
					$pmtongue_arr[$k]="'34'";
			}
			$pmtongue_new=@implode(",",$pmtongue_arr);
			if($pmtongue!=$pmtongue_new)
				$update_arr[]= "PARTNER_MTONGUE=\"$pmtongue_new\"";
			unset($pmtongue_arr);
		}
		if($phandicap=='Y')
		{
			$update_arr[]= "HANDICAPPED=\"'1','2','3','4'\"";	
		}
		elseif($phandicap=='N')
			$update_arr[]= "HANDICAPPED=\"'N'\"";

		if($row['LHEIGHT']!='0' && $row['HHEIGHT']!='0')
                	$update_arr[]= "LHEIGHT=LHEIGHT+5, HHEIGHT=HHEIGHT+5";
	
		$update_str=@implode(",",$update_arr);	
		unset($update_arr);
		if($update_str)
		{
			$sql2="UPDATE JPARTNER SET $update_str WHERE PROFILEID='$pid'";
			$result2 = mysql_query_decide($sql2,$myDb) or die(mysql_error());
		}
	}
}
?>
