<?php
  $curFilePath = dirname(__FILE__)."/";
 include_once("/usr/local/scripts/DocRoot.php");
	include($_SERVER['DOCUMENT_ROOT']."/crm/connect.inc");
	$yesterday = date("Y-m-d",time()-86400);
	$sql="SELECT ALLOTED_TO,ALLOT_TIME FROM incentive.MAIN_ADMIN WHERE ALLOT_TIME >= '$yesterday 00:00:00' AND ALLOT_TIME <= '$yesterday 23:59:59'";
   	$result=mysql_query_decide($sql) or die("$sql".mysql_error_js());
        while($myrow=mysql_fetch_array($result))
        {
		$alloted_to=$myrow['ALLOTED_TO'];
		$alloted_time=$myrow['ALLOT_TIME'];

		$sub=strtotime($alloted_time);
		$interval1=date("Y-m-d H:i:s",($sub-1800));

		if(!in_array($alloted_to,$fake_executive_arr_day))
		{
			$sql1="SELECT COUNT(*) AS daycount FROM incentive.MAIN_ADMIN WHERE ALLOTED_TO='$alloted_to' AND ALLOT_TIME >= '$yesterday 00:00:00' AND ALLOT_TIME <= '$yesterday 23:59:59'";
			$result1= mysql_query_decide($sql1) or die(mysql_error_js());
			$myrow1=mysql_fetch_array($result1);
			if ($myrow1['daycount'] > 30)
				$fake_executive_arr_day[]=$alloted_to;
		}

		if(!in_array($alloted_to,$fake_executive_arr_min))
		{
			$sql2="SELECT COUNT(*) AS 30mincount FROM incentive.MAIN_ADMIN WHERE ALLOT_TIME >'$interval1' AND ALLOT_TIME <'$alloted_time' AND ALLOTED_TO='$alloted_to'";
			$result2= mysql_query_decide($sql2) or die(mysql_error_js());
			$myrow2=mysql_fetch_array($result2);
			if($myrow2['30mincouunt'] > 10)
				$fake_executive_arr_min[]=$alloted_to;
		}
	}
        for($i=0;$i<count($fake_executive_arr_day);$i++)
		$message_day.="Please look at allocations in detail for  ".$fake_executive_arr_day[$i]." for ".$yesterday." because more than 30 allocations were taken in a day.<br>";
	for($i=0;$i<count($fake_executive_arr_min);$i++)
                $message_min.="Please look at allocations in detail for".$fake_executive_arr_min[$i]. " for ".$yesterday." because more than 10 allocations were taken within 30 minutes.<br>";
	$to = "anamika.singh@jeevansathi.com, bharat.vaswani@jeevansathi.com, manish.raj@jeevansathi.com,rajeev.joshi@jeevansathi.com,rohan.mathur@jeevansathi.com,rohit.manghnani@jeevansathi.com";
	$cc = "vibhor.garg@jeevansathi.com";
        $subject = "Abnormal allocation activity by branch agent";
        mail($to,$cc,$subject,$message_day);
	mail($to,$cc,$subject,$message_min);
        unset($fake_executive_arr_min);
	unset($fake_executive_arr_day);
?>

