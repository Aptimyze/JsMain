<?php
//die("Under Maintenance");
include("connect.inc");
include_once("user_hierarchy.php");

$db2		=connect_master();
$data		=authenticated($cid);
$db		=connect_rep(); //connect_misdb();
$name 		=trim(getname($cid));
$privilage      =getprivilage($cid);
$priv           =explode("+",$privilage);

if($data)
{
	//************************************    Condition after submit state  ***************************************

		$datetime1 	=JSstrToTime($start_dt);
                $datetime2 	=JSstrToTime($end_dt);
                $time 		=$datetime2-$datetime1;
                $days 		=round($time/86400);
		if($days>'184')
			$err ='1';		
		if($start_dt=='' || $end_dt=='' || $agentName=='')
			$err ='1';

		if(!$err)
		{
			$agentName =trim($agentName);
                        // function called to get payment details
			$profileidArr1	=array();
			$dateArr1	=array();
			$amountArr1	=array();
                        $profileidArr   =array();
                        $dateArr        =array();
                        $amountArr      =array();

			$m		=0;
			$n		=0;	
			if($type=='PAID')
			{
				$paidArr =getProfilesCount($start_dt, $end_dt, $agentName,$db);
				if(count($paidArr)>0)
				{	
					foreach($paidArr as $key1=>$val1){
                                                $dateSel        		=JSstrToTime($val1['ENTRY_DT']);
                                                $dateSel        		=date("d-M-Y",$dateSel);
                                                $dateArr1[$m]     		=$dateSel;
						$profileidArr1[$dateSel][$m] 	=$val1['PROFILEID'];
						$amountArr1[$m] 		=$val1['AMOUNT'];	
						$m++;
					}

					// Sorting Usernames logic
		                        $dateArr1 =array_unique($dateArr1);
                		        foreach($dateArr1 as $key1=>$val1){
                                		$pidArr =$profileidArr1[$val1];
                                		asort($pidArr);
                                		foreach($pidArr as $key2=>$val2){
                                        		$profileidArr[] =$val2;
                                        		$dateArr[]      =$val1;
							$amountArr[]	=$amountArr1[$key2];
                                		}
                        		}
				}		
			}			
			else
			{
	                        // Checking Master Connection
	                        if(in_array("MG",$priv) || in_array("TRNG",$priv) || in_array("LTFSUP",$priv)){
        	                        if($days>'31'){
                	                        mysql_close($db2);
                        	                $db2 =$db;
                                	}
                        	}
                        	else{
                        	        mysql_close($db2);
                        	        $db2 =$db;
                        	}

				if($type=='VSEAL')
					$sql ="SELECT PROFILEID,DATE,COUNT(*) AS cnt from MIS.LTF where DATE>='$start_dt 00:00:00' AND DATE<='$end_dt 23:59:59' AND EXECUTIVE='$agentName' AND TYPE IN('PHONE','EMAIL','ADDR') GROUP BY PROFILEID HAVING cnt='3' ORDER BY DATE ASC";
				else
					$sql ="SELECT PROFILEID,DATE from MIS.LTF where DATE>='$start_dt 00:00:00' AND DATE<='$end_dt 23:59:59' AND EXECUTIVE='$agentName' AND TYPE='$type' ORDER BY DATE ASC";
				$res =mysql_query_decide($sql,$db2) or die("$sql".mysql_error_js());
				while($row=mysql_fetch_array($res))
				{
					$profileid 	=$row['PROFILEID'];
					$dateSel	=$row['DATE'];
					$dateSel      	=JSstrToTime($dateSel);
					$dateSel	=date("d-M-Y",$dateSel);

					$sqlJ ="SELECT USERNAME from newjs.JPROFILE WHERE PROFILEID='$profileid'";
					$resJ =mysql_query_decide($sqlJ,$db2) or die("$sqlJ".mysql_error_js());
					$rowJ=mysql_fetch_array($resJ);
					$username =$rowJ['USERNAME'];
	
					$profileidArr1[$dateSel][$n] 	=$username;
					$dateArr1[$n]			=$dateSel;	
					$n++;
				}

				// Sorting Usernames logic 
				$dateArr1 =array_unique($dateArr1);	
				foreach($dateArr1 as $key1=>$val1){
					$pidArr =$profileidArr1[$val1];
					asort($pidArr);		
					foreach($pidArr as $key2=>$val2){
						$profileidArr[] =$val2;				
						$dateArr[]	=$val1;	
					}	
				}
			}
	
                        $from_dt1 =explode("-",$start_dt);
                        $upto_dt1 =explode("-",$end_dt);
                        $from_dt =$from_dt1[2]."-".$from_dt1[1]."-".$from_dt1[0];
                        $upto_dt =$upto_dt1[2]."-".$upto_dt1[1]."-".$upto_dt1[0];
			
			// ----------------------  Excel format Start  ------------------------
			if($format_type=="XLS")
			{
				$header_label = "Selected-Time-Period $from_dt To $upto_dt"."\n\n\n";	
				$head =$agentName."\n\n";
				if($type=='PAID')
					$head .="Date"."\t"."Username"."\t"."Payment"."\n\n";
				else
					$head .="Date"."\t"."Username"."\n\n";	

				for($y=0; $y<count($profileidArr); $y++)
				{
					$dateVal =$dateArr[$y];
					$profileidVal =$profileidArr[$y];
					$dataSet .=$dateVal."\t".$profileidVal;
					if($type=='PAID'){
						$amountVal =$amountArr[$y];
						$dataSet .="\t".$amountVal;
					}
					$dataSet .="\n";
				}	

                	        header("Content-Type: application/vnd.ms-excel");
                	        header("Content-Disposition: attachment; filename=sugarcrm_LTF_report.xls");
                	        header("Pragma: no-cache");
                	        header("Expires: 0");
                	        $final_data = $head.$dataSet;
				echo $final_data;
				die;
			}	
			//------------------------  Excel format Ends ---------------------------:

			$smarty->assign("type","$type");
			$smarty->assign("agentName","$agentName");	
			$smarty->assign("profileidArr",$profileidArr);
			$smarty->assign("dateArr",$dateArr);
			$smarty->assign("amountArr",$amountArr);
			$smarty->assign("head_label","Selected Time Period $from_dt To $upto_dt");
			$smarty->assign("start_dt",$start_dt);
			$smarty->assign("end_dt",$end_dt);	
	
		} // error check brace condition ends

	$smarty->assign("err","$err");
	$smarty->assign("cid",$cid);
	$smarty->assign("name",$name);
	$smarty->assign("outside",stripslashes($outside));	
	$smarty->display("sugarcrm_LTF_report_2.htm");
}
else
{
        $smarty->assign("name",$name);
        $smarty->display("jsconnectError.tpl");
}

function getProfilesCount($start_dt, $end_dt, $agentName,$db)
{
	$i 		=0;
	$j 		=0;
	$amnt 		=array();
	$resultArr	=array();
	$profileStore	=array();

	$sql_amount ="SELECT if(p.TYPE='DOL', p.DOL_CONV_RATE*p.AMOUNT,p.AMOUNT) as amt,PROFILEID,ENTRY_DT from billing.PAYMENT_DETAIL p where p.STATUS='DONE' AND p.ENTRY_DT >='$start_dt 00:00:00' AND p.ENTRY_DT <='$end_dt 23:59:59' ORDER BY ENTRY_DT ASC";	
	$amount_res =mysql_query_decide($sql_amount,$db) or die("$sql_amount".mysql_error_js());	
	while($amount_row=mysql_fetch_array($amount_res))	
	{	
		$amnt[$i]['PROFILEID'] 	=$amount_row['PROFILEID'];
		$amnt[$i]['ENTRY_DT'] 	=$amount_row['ENTRY_DT'];
		$amnt[$i]['AMOUNT'] 	=$amount_row['amt'];
		$i++;
	}
	if(count($amnt)>0)
	{
        	foreach($amnt as $key=>$val)
        	{
			$pid 	=$val['PROFILEID'];
			$addVal =false;
			if(!isPaymentDoneWithin12Months($pid, $val['ENTRY_DT'])) continue;
			if(!array_key_exists($pid,$profileStore))
			{
   				$sql1 	="SELECT distinct(PROFILEID) from MIS.LTF WHERE PROFILEID='$pid' AND EXECUTIVE='$agentName'";                   
        			$res1 	=mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js());
				if($row1=mysql_fetch_array($res1))
				{
					$pid =$row1['PROFILEID'];
                        	        $sqlJ ="SELECT USERNAME from newjs.JPROFILE WHERE PROFILEID='$pid'";
                        	        $resJ =mysql_query_decide($sqlJ,$db) or die("$sqlJ".mysql_error_js());
                        	        $rowJ=mysql_fetch_array($resJ);
                        	        $username =$rowJ['USERNAME'];
					$profileStore[$pid] =$username;
					$addVal =true;
				}
			}
			else if(array_key_exists($pid,$profileStore)){
				$username =$profileStore[$pid];
				$addVal =true;
			}
			if($addVal){
				$resultArr[$j]['PROFILEID']	=$username;		
				$resultArr[$j]['ENTRY_DT'] 	=$val['ENTRY_DT'];
				$resultArr[$j]['AMOUNT'] 	=$val['AMOUNT'];
				$j++;
			}
        	}	
	}
	return $resultArr;	
}	

function isPaymentDoneWithin12Months($profileid, $payment_dt) {
        $payment_dt_12months_back = date('Y-m-d', strtotime('-364 days', strtotime($payment_dt)));
        $sql ="SELECT PROFILEID from MIS.LTF WHERE PROFILEID='$profileid' AND TYPE='REG' AND DATE>='".$payment_dt_12months_back." 00:00:00'";   
        $res =mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
        return(mysql_fetch_array($res) ? 1 : 0);
}
?>
