<?php
include("connect.inc");
include_once("user_hierarchy.php");
include_once("../crm/functions_inbound.php");

$db=connect_misdb();
$db_dnc =connect_dnc();
$db2 =connect_master();

if(authenticated($cid))
{
	$name =getname($cid);
	$smarty->assign("cid",$cid);
	$time_diff_check =120;
	$call_source_arr =populate_call_source();

			if($exec=='' || $day=='' || $month=='' || $year=='')
				$err =1;
			if( ($day && ($day<1 || $day>31)) || ($month && ($month<1 || $month>12)) )
				$err =1;	
			if ($month < 9)
				$month = "0".$month;

			if($err)
				die('Some error occurred, please try again');

			$i =1;
			$resultArr =array();
			$startTime ="$year-$month-$day 00:00:00";
			$endTime   ="$year-$month-$day 23:59:59";	
			$startTime =getUSTime($startTime);
			$endTime   =getUSTime($endTime);

			$j=0;
                        $sql="SELECT PROFILEID,ALLOT_TIME FROM incentive.CRM_DAILY_ALLOT WHERE ALLOT_TIME >='$startTime' AND ALLOT_TIME<='$endTime' AND ALLOTED_TO='$exec' ORDER BY ALLOT_TIME ASC";
                        $res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
                        while($row=mysql_fetch_array($res))
                        {
                                $elements[$j][PROFILEID]=$row[PROFILEID];
                                $elements[$j][ALLOT_TIME]=$row[ALLOT_TIME];
                                $j++;
                        }
                        $sql ="SELECT PROFILEID,ALLOT_TIME FROM incentive.CRM_DAILY_ALLOT_TRACK WHERE ALLOT_TIME >='$startTime' AND ALLOT_TIME<='$endTime' AND ALLOTED_TO='$exec' ORDER BY ALLOT_TIME ASC";
                        $res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
                        while($row=mysql_fetch_array($res))
                        {
                                $elements[$j][PROFILEID]=$row[PROFILEID];
                                $elements[$j][ALLOT_TIME]=$row[ALLOT_TIME];
                                $j++;
                        }
			for($k=0;$k<count($elements);$k++)
                        {
				$phoneNumberArray 	=array();
				$DNCArrNew 		=array();
				$DNCArr 		=array();
				$username_red 		='N';
				$mobile1_red		='N';
				$landline_red		='N';
				$mobile2_red		='N';
				$crm_red		='N';
				$call_source		='';		
			
				$allot_time     =getIST($elements[$k]['ALLOT_TIME']);
                                $profileid      =$elements[$k]['PROFILEID'];
	
				if($i>1){
					$j =$i-1;
					$pre_allot_time =$resultArr[$j]['ALLOT_TIME'];
					$check_time1 =JSstrToTime($pre_allot_time);
					$check_time2 =JSstrToTime($allot_time);
					$time_diff =$check_time2-$check_time1;
					if($time_diff<$time_diff_check)
						$username_red ='Y';
				}

				$sql_j="SELECT USERNAME,PHONE_MOB,PHONE_WITH_STD FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
                                $res_j=mysql_query_decide($sql_j,$db) or die("$sql_j".mysql_error_js());
                                $row_j=mysql_fetch_array($res_j);
				$username 	=$row_j['USERNAME'];
				$mobile1 	=phoneNumberCheck($row_j['PHONE_MOB']);
				$landline	=phoneNumberCheck($row_j['PHONE_WITH_STD']);
				
                                $sql_alt="SELECT ALT_MOBILE FROM newjs.JPROFILE_CONTACT WHERE PROFILEID='$profileid'";
                                $res_alt=mysql_query_decide($sql_alt,$db) or die("$sql_alt".mysql_error_js());
                                $row_alt	=mysql_fetch_array($res_alt);
                                $mobile2 	=phoneNumberCheck($row_alt['ALT_MOBILE']);     
			 	
				$sql_f ="select ALTERNATE_NUMBER from incentive.PROFILE_ALTERNATE_NUMBER where PROFILEID='$profileid'";
				$res_f=mysql_query_decide($sql_f,$db) or die("$sql_f".mysql_error_js());	
				$row_f		=mysql_fetch_array($res_f);
				$crm_number 	=phoneNumberCheck($row_f['ALTERNATE_NUMBER']);	

                                $sql_ia ="select CALL_SOURCE from incentive.INBOUND_ALLOT where PROFILEID='$profileid' AND ALLOTED_TO='$exec'";
                                $res_ia=mysql_query_decide($sql_ia,$db) or die("$sql_ia".mysql_error_js());
                                $row_ia          =mysql_fetch_array($res_ia);
                                $call_source     =$row_ia['CALL_SOURCE'];
				if($call_source==''){
                                	$sql_ma ="select CALL_SOURCE from incentive.MANUAL_ALLOT where PROFILEID='$profileid' AND ALLOTED_TO='$exec'";
                                	$res_ma=mysql_query_decide($sql_ma,$db) or die("$sql_ma".mysql_error_js());
                                	$row_ma          =mysql_fetch_array($res_ma);
                                	$call_source     =$row_ma['CALL_SOURCE'];
				}
				foreach($call_source_arr as $key=>$val){
				        $value =$val['value'];
        				if($value=="$call_source"){
                				$call_source =$val['name'];
                				break;
					}
				}
				
				// DNC check Start
				if($mobile1 || $landline || $mobile2 || $crm_number){
				if($mobile1)
					array_push($phoneNumberArray,"$mobile1");
				if($landline)
					array_push($phoneNumberArray,"$landline");
				if($mobile2)
					array_push($phoneNumberArray,"$mobile2");
				if($crm_number)
					array_push($phoneNumberArray,"$crm_number");
				$DNCArr =checkDNC($phoneNumberArray);
				if(count($DNCArr)>0){
					foreach($DNCArr as $key=>$val){
						if($val=='Y'){
							$key1 =str_replace('S','',$key);
							$DNCArrNew[]=$key1;
						}
					}
					if(count($DNCArrNew)>0){
						foreach($DNCArrNew as $key =>$val){
							$dncNumber =$DNCArr[$val];
							if($dncNumber==$mobile1)
								$mobile1_red ='Y';
							if($dncNumber==$landline)
								$landline_red ='Y';
							if($dncNumber==$mobile2)
								$mobile2_red ='Y';
							if($dncNumber==$crm_number)
								$crm_red ='Y';
						}
					}
				}}
				// DNC check Ends

				$resultArr[$i]['PROFILEID']     =$profileid;	
				$resultArr[$i]['ALLOT_TIME'] 	=$allot_time;
				$resultArr[$i]['USERNAME'] 	=$username;
				$resultArr[$i]['MOBILE1'] 	=$mobile1;
				$resultArr[$i]['LANDLINE'] 	=$landline;
				$resultArr[$i]['MOBILE2'] 	=$mobile2;
				$resultArr[$i]['CRM_NUMBER'] 	=$crm_number;
				$resultArr[$i]['CALL_SOURCE'] 	=$call_source;
				$resultArr[$i]['USERNAME_RED'] 	=$username_red;
				$resultArr[$i]['MOBILE1_RED'] 	=$mobile1_red;
				$resultArr[$i]['LANDLINE_RED'] 	=$landline_red;
				$resultArr[$i]['MOBILE2_RED'] 	=$mobile2_red;
				$resultArr[$i]['CRM_RED']	=$crm_red;
				$i++;
			}
		$smarty->assign("resultArr",$resultArr);
		$smarty->assign("exec",$exec);
		$smarty->assign("date_complete","$day-$month-$year");	
		$smarty->display("crm_sales_allocation_exec.htm");
}
else
{
        $smarty->display("jsconnectError.tpl");
}

function getUSTime($time_ist)
{
        $USTime=strftime("%Y-%m-%d %H:%M",JSstrToTime("$time_ist - 10 hours 30 minutes"));
        return $USTime;
}

function checkDNC($phoneNumberArray)
        {
                global $db_dnc;
                mysql_ping($db_dnc);
                $DNCArr         =array();
                $DNC_NumberArr  =array();
                $selectedArr    =array();
                $status         =true;

                if(!is_array($phoneNumberArray) || count($phoneNumberArray)=='0')
                        return false;
                else{
                        foreach($phoneNumberArray as $key1=>$val1)
                        {
                                if($val1)
                                        $selectedArr[] =$val1;
                        }
                }

                $phoneNumberStr =implode("','",$selectedArr);
                $sql="SELECT PHONE FROM DNC.DNC_LIST WHERE PHONE IN('$phoneNumberStr')";
                $res=mysql_query($sql,$db_dnc) or die($sql.mysql_error());
                while($row=mysql_fetch_array($res))
                {
                        $DNC_NumberArr[] =$row['PHONE'];
                }

                foreach($phoneNumberArray as $key=>$val)
                {
                        if(in_array($val, $DNC_NumberArr)){
                                $DNCArr[$key] =$val;
                                $key1 =$key."S";
                                $DNCArr[$key1] ='Y';
                        }
                        else{
                                $DNCArr[$key] =$val;
                                $key1 =$key."S";
                                $DNCArr[$key1] ='N';
				if(in_array($val, $selectedArr))
                                        $status =false;
                        }
                }
                $DNCArr['STATUS'] =$status;
                return $DNCArr;
        }
        function phoneNumberCheck($phoneNumber)
        {
                $phoneNumber    =substr(preg_replace("/[a-zA-Z!(\' ')@#$+^&*-]/", "",$phoneNumber),-10);
                $phoneNumber    =ltrim($phoneNumber,0);
                if(!is_numeric($phoneNumber))
                        return false;
                if(strlen($phoneNumber)!=10)
                        return false;
                return $phoneNumber;
        }

?>
