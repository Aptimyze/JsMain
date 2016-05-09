<?php
/************************************************************************************************************
File		: customers_list_tobeserviced.php	
Description 	: Number and details of each profiles to be seviced by the logged in Operator on the 
		  particular day.
Developed By	: Vibhor Garg
Date		: 10-07-2008
*************************************************************************************************************/
include_once("connect.inc");
$operator_name = $name;

if(authenticated($cid))
{
	if($dispatch)
	{
		$sql="SELECT SERVICE_DATE,ENTRY_DATE,SERVICED FROM OFFLINE_BILLING WHERE PROFILEID='$profileid' ORDER BY ENTRY_DATE DESC LIMIT 1";
		$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
	        $row=mysql_fetch_assoc($result);
                $sdate = $row["SERVICE_DATE"];
		$edate = $row["ENTRY_DATE"];
		$ser_status = $row["SERVICED"];
		if($sdate == "0000-00-00")
			$sdate = cal_service_date($edate);
		$ts =JSstrToTime($sdate);
		$ts+=15*24*60*60;
		$new_service_dt=date("Y-m-d",$ts);
		if($ser_status != 'Y')
		{
			$sql="UPDATE OFFLINE_BILLING SET SERVICED='Y',SERVICE_DATE='$new_service_dt' where PROFILEID='$profileid' AND ACTIVE='Y'";
        		$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));			
		}
		$dispatch=0;
	}		
	$sql="Select PROFILEID from OFFLINE_ASSIGNED where OPERATOR = '".$operator_name."'";
        $result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
        while($row=mysql_fetch_assoc($result))
        {
                $profileid_arr[] = $row["PROFILEID"];            
        }
	$profileid_str="'".@implode("','",$profileid_arr)."'";
	$profileArray=array();
	$sql="SELECT PROFILEID,ENTRY_DATE,SERVICE_DATE FROM OFFLINE_BILLING WHERE SERVICED='N' AND PROFILEID IN ($profileid_str) AND ACTIVE='Y'";
        $result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
        while($row=mysql_fetch_assoc($result))
        {
                $profileid = $row["PROFILEID"];
                $profileArray[] = $row["PROFILEID"];
                $entry_date=$row["ENTRY_DATE"];
                if($row["SERVICE_DATE"]=="0000-00-00")
                {
                        $sdate = cal_service_date($entry_date);
                }
                else
                        $sdate = $row["SERVICE_DATE"];
		$service_date[$profileid] = $sdate;
		$service[$profileid] = $row["SERVICED"];
		
        }
	$sql="SELECT PROFILEID,ENTRY_DATE,SERVICE_DATE,SERVICED FROM OFFLINE_BILLING WHERE DATEDIFF(CURDATE()+1,ENTRY_DATE)%15=0 AND PROFILEID IN ($profileid_str) AND SERVICED!='Y' AND ACTIVE='Y'";
	$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
	while($row=mysql_fetch_assoc($result))
        {
                $profileid = $row["PROFILEID"];  
		if(!in_array($profileid,$profileArray))
		{
			$profileArray[] = $row["PROFILEID"];
			$entry_date=$row["ENTRY_DATE"];
			if($row["SERVICE_DATE"]=="0000-00-00")
			{
				$service_date[$profileid] = cal_service_date($entry_date);
			}
			else
				$service_date[$profileid] = $row["SERVICE_DATE"];		
			$service[$profileid] = $row["SERVICED"];         
		}
        }
	$day=date("D");
	if($day == 'Sat')
	{
		$sql="SELECT PROFILEID,ENTRY_DATE,SERVICE_DATE,SERVICED FROM OFFLINE_BILLING WHERE DATEDIFF(CURDATE()+2,ENTRY_DATE)%15=0 AND PROFILEID IN ($profileid_str) AND SERVICED!='Y' AND ACTIVE='Y'";
	        $result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		while($row=mysql_fetch_assoc($result))
		{
			$profileid = $row["PROFILEID"];
			if(!in_array($profileid,$profileArray))
			{
				$profileArray[] = $row["PROFILEID"];
				$entry_date=$row["ENTRY_DATE"];
				if($row["SERVICE_DATE"]=="0000-00-00")
				{
					$service_date[$profileid] = cal_service_date($entry_date);
                	        }
                        	else
                                	$service_date[$profileid] = $row["SERVICE_DATE"];
                        	$service[$profileid] = $row["SERVICED"];
                	}
        	}
	}
	$count = count($profileArray);
	$active_count = 0;		
	for($i=0;$i<$count;$i++)
	{
		$active_count++;
		unset($countArray);
		unset($dayremain);
                unset($count_pool);
                unset($count_sl);
                unset($dateArray);
                unset($accAllowedArray);
                unset($accRemainArray);
                unset($uname);
                unset($pass);
                unset($phone_res);
                unset($phone_mob);
                unset($std);
                unset($is_deleted);
                unset($contact_no);
		 $sql="SELECT BILLID,ACTIVE FROM jsadmin.OFFLINE_BILLING WHERE PROFILEID='$profileArray[$i]' ORDER BY BILLID DESC LIMIT 1";
                $res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		$type="unbilled";
		$dayremain=0;
                if(mysql_num_rows($res))
                {	
                        $row=mysql_fetch_assoc($res);
                        $billid=$row["BILLID"];
			if($row["ACTIVE"]=="Y")
			{
				$sql="SELECT DATEDIFF(EXPIRY_DT,NOW()) AS DAYREMAIN FROM billing.SERVICE_STATUS WHERE BILLID='$billid' AND ACTIVATED='Y'";
				$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
				$row=mysql_fetch_assoc($res);
				$dayremain=$row["DAYREMAIN"];
				$type="billed";
			}
			else
			{
				$type="unbilled";
				$dayremain=0;
			}
			$sql="Select COUNT(*) AS CNTP from OFFLINE_MATCHES where PROFILEID = '".$profileArray[$i]."' AND CATEGORY !=''  AND (STATUS='NACC'||STATUS='N'||STATUS='NNOW')";
			$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			$row=mysql_fetch_assoc($result);
			$count_pool = $row["CNTP"];
		
			$sql="Select COUNT(*) AS CNTSL from OFFLINE_MATCHES where PROFILEID = '".$profileArray[$i]."' AND STATUS = 'SL' ";
			$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			$row=mysql_fetch_assoc($result);
			$count_sl = $row["CNTSL"];		
		
			$sql="Select ENTRY_DATE,ACC_UPGRADED,ACC_ALLOWED,ACC_MADE from OFFLINE_BILLING where PROFILEID = '".$profileArray[$i]."' ORDER BY BILLID DESC";
                        $result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                        $row=mysql_fetch_assoc($result);
                        $dateArray = $row["ENTRY_DATE"];
                        $accAllowedArray = $row["ACC_ALLOWED"] + $row["ACC_UPGRADED"];
									
		}	
		$sql="Select USERNAME,PASSWORD from newjs.JPROFILE where PROFILEID = '".$profileArray[$i]."'";
		$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		$row=mysql_fetch_assoc($result);
		$uname = $row["USERNAME"];
		$pass= $row["PASSWORD"];
		$j=$i+1;
		$countArray=$j;
		$tableArray[$profileArray[$i]] = array($countArray,$uname,$pass,$service_date[$profileArray[$i]],$count_pool,$count_sl,$accAllowedArray,$dateArray,$dayremain);
	}	
	$smarty->assign('tableArray',$tableArray);
	$smarty->assign("searchFlag","1");
	$smarty->assign("operator_name",$operator_name);	
	$smarty->assign("assigned_profile",$active_count);		
	$smarty->assign("profileArray",$profileArray);
	$smarty->assign("CHECKSUM",$cid);
	$smarty->display("customers_list_tobeserviced.htm");
}
else
{
	$msg="Your session has been timed out  ";
        $smarty->assign("MSG",$msg);
        $smarty->display("jsadmin_msg.tpl");

}
function cal_service_date($entry_date)
{
	$entry_time = JSstrToTime($entry_date);
        $present_time = time();
	$service_time = $entry_time;
	while($service_time<$present_time)
		$service_time+=15*24*60*60;
	$service_dt=date("Y-m-d",$service_time);
	return $service_dt;
}
?>
