<?php
	$curFilePath = dirname(__FILE__)."/";
	include_once("/usr/local/scripts/DocRoot.php");

	include("$docRoot/crontabs/connect.inc");
	$db = connect_slave();
	$yesterday = date("Y-m-d",time()-86400);
	$p=0;
	$q=0;

	$sql="SELECT ALLOTED_TO,count(*) as cnt FROM incentive.MAIN_ADMIN WHERE ALLOT_TIME >= '$yesterday 00:00:00' AND ALLOT_TIME <= '$yesterday 23:59:59' GROUP BY ALLOTED_TO HAVING cnt > 10";
	$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
        while($myrow=mysql_fetch_array($result))
		$allotedto_arr[]=$myrow['ALLOTED_TO'];

	for($i=0;$i<count($allotedto_arr);$i++)
	{
		$name=$allotedto_arr[$i];
		$sql1="SELECT ALLOT_TIME,PROFILEID FROM incentive.MAIN_ADMIN WHERE ALLOTED_TO='$name' AND ALLOT_TIME >= '$yesterday 00:00:00' AND ALLOT_TIME <= '$yesterday 23:59:59' ORDER BY ALLOT_TIME ASC";
		$result1= mysql_query_decide($sql1) or die(mysql_error_js());
               	while($myrow1=mysql_fetch_array($result1))
		{
			$allot_time = date("Y-m-d H",JSstrToTime($myrow1['ALLOT_TIME']));
			$sql2="SELECT ALLOT_TIME FROM incentive.MANUAL_ALLOT WHERE ALLOTED_TO='$name' AND PROFILEID = '$myrow1[PROFILEID]' AND ALLOT_TIME >= '$yesterday 00:00:00' AND ALLOT_TIME <= '$yesterday 23:59:59'";
        	        $result2= mysql_query_decide($sql2) or die(mysql_error_js());
			if($myrow2=mysql_fetch_array($result2))
			{
				$manually_allot_time = date("Y-m-d H",JSstrToTime($myrow2['ALLOT_TIME']));
				if($allot_time != $manually_allot_time)
					$allottime_arr[]=$myrow1['ALLOT_TIME'];
			}
			else
				$allottime_arr[]=$myrow1['ALLOT_TIME'];
		}

		$total=count($allottime_arr);
		if($total >=30)
		{
			$fake_executive_arr_day[$p]=$name;
			$p++;
		}
		for($j=0;$j<($total-9);$j++)
		{
			$interval1=JSstrToTime($allottime_arr[$j]);
			$interval2=JSstrToTime($allottime_arr[$j+9]);
			if(($interval2-$interval1) < 1800)	
			{
				$fake_executive_arr_min[$q]=$name;
				$q++;
				break;
			}
		}
		unset($allottime_arr);
	}

	$yesterday = date("d-M-Y",time()-86400);
        if(count($fake_executive_arr_day))
        {
                for($i=0;$i<count($fake_executive_arr_day);$i++)
                        $message_day.="Please look at allocations in detail for ".$fake_executive_arr_day[$i]." for ".$yesterday." because 30 or more allocations were taken in a day."."\n";
        }
        else
                 $message_day="No fake allocations found on ".$yesterday.".";

        if(count($fake_executive_arr_min))
        {
                for($i=0;$i<count($fake_executive_arr_min);$i++)
                        $message_min.="Please look at allocations in detail for ".$fake_executive_arr_min[$i]. " for ".$yesterday." because 10 or more allocations were taken within 30 minutes."."\n";
        }
        else
                 $message_min="No fake allocations found on ".$yesterday.".";

	$to = "anamika.singh@jeevansathi.com, bharat.vaswani@jeevansathi.com,rajeev.joshi@jeevansathi.com,rohan.mathur@jeevansathi.com,jstraining@Infoedge.com,sapna.sharma@jeevansathi.com,heena.arora@jeevansathi.com,mohammad.aalam@jeevansathi.com,amit.parashar@jeevansathi.com,ravi.kapur@jeevansathi.com";
        $from="From:vibhor.garg@jeevansathi.com";
	$subject_day = "30 or more allocations taken by branch agent within a day";
	$subject_min = "10 or more allocations taken by branch agent within 30 minutes";
        mail($to,$subject_day,$message_day,$from);
	mail($to,$subject_min,$message_min,$from);
?>

