<?php
//die("Under Maintenance");
include("connect.inc");
include_once("user_hierarchy.php");

$data		=authenticated($cid);
$db		=connect_rep(); //connect_misdb();
$name 		=trim(getname($cid));
$privilage	=getprivilage($cid);
$priv		=explode("+",$privilage);

if($data)
{
	//************************************    Condition after submit state  ***************************************
	if($submit)
	{
        	if(in_array("MG",$priv) || in_array("TRNG",$priv) || in_array("P",$priv))
                	$outside ="'Y'";

		$allAgent='';
		if(stripslashes($outside)=="'Y'"){

        	        $branch="ALL";
			$allAgent='Y';
		}
	        else
                	$branch="";
		$start_dt       ='';
		$end_dt         ='';
		$uname_str 	='';
		$uname_arr	=array();
		$name 		=trim($name);

		$uname_str 	=user_hierarchy($name,$branch,'Y',$allAgent);
		$uname_str 	=str_replace("'","",$uname_str);
		$uname_arr 	=explode(",",$uname_str);
		$uname_arr	=array_unique($uname_arr);
		$uname_cnt 	=count($uname_arr);	
		if($uname_cnt==0)
			die("No records found");

                if($select_type=="M")
                {       
                	$start_dt =$year_month."-".$month."-01";
                        $end_dt   =$year_month."-".$month."-31";
                }
                elseif($select_type=="Q")
                {
			if($quarter=='1'){     
				$start_dt =$year_quarter."-04-01";
				$end_dt   =$year_quarter."-06-30";
			}
			else if($quarter=='2'){
				$start_dt =$year_quarter."-07-01";
				$end_dt   =$year_quarter."-09-30";
			}
			else if($quarter=='3'){
				$start_dt =$year_quarter."-10-01";
				$end_dt   =$year_quarter."-12-31";
			}
			else if($quarter=='4'){
				$start_dt =$year_quarter."-01-01";
				$end_dt   =$year_quarter."-03-31";
			}
                }
                elseif($select_type=="R")
                {
                        $start_dt 	=$year_r1."-".$month_r1."-".$day_r1;
                        $end_dt   	=$year_r2."-".$month_r2."-".$day_r2;
		}

                $datetime1 	=JSstrToTime($start_dt);
                $datetime2 	=JSstrToTime($end_dt);
	        $time 		=$datetime2-$datetime1;
                $days 		=round($time/86400);
		if($days>'184')
			$err ='1';

		if($start_dt=='' || $end_dt=='')
			$err ='1';

                $fields_arr =array("0"=>'NAME',"1"=>'EMPID',"2"=>"SUPERVISOR","3"=>'REGISTERED',"4"=>'ACTIVATED',"5"=>'PHOTO',"6"=>'EOI',"7"=>'PAID','8'=>'PAYMENT',"9"=>'PHONE',"10"=>'EMAIL',"11"=>'ADDRESS',"12"=>'PHONE_EMAIL_ADDRESS');

		if(!$err)
		{
			// Checking Master Connection
			/*if(in_array("MG",$priv) || in_array("TRNG",$priv) || in_array("LTFSUP",$priv)){

                        	if($days>'31'){
                                	mysql_close($db2);
                                	$db2 =$db;
                        	}
                	}
                	else{
                        	mysql_close($db2);
                        	$db2 =$db;
                	}*/

                        // function called to get payment details
                        $paid_details_arr =getProfilesCount($start_dt, $end_dt, $uname_arr ,$db);

			$sql ="SELECT * from MIS.LTF where DATE>='$start_dt 00:00:00' AND DATE<='$end_dt 23:59:59'";
			$res =mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
			$i=0;
			$dataArr 	=array();
			$vSealArr	=array();
			$exec_name_arr 	=array();
			$exec_name_arr_1=array();
			while($row=mysql_fetch_array($res))
			{
				$exec 		=trim($row['EXECUTIVE']);
				$profileid 	=$row['PROFILEID'];
				$type		=trim($row['TYPE']);
				if(in_array("$exec",$uname_arr))
				{
					$exec_name_arr[$i] 	=$exec;
					$dataArr[$exec][$i] 	=array("PROFILEID"=>"$profileid","TYPE"=>"$type");			
					$vSealArr[$exec][$i]	=$profileid."|".$type;	
					$i++;
				}
			}
			// Added new code
			$executiveNewArr 	=array();
			$paidResultNewArr	=array();
			$executiveNewArr 	=$paid_details_arr['executiveArr'];
			$paidResultNewArr	=$paid_details_arr['resultArr'];
			$executiveMergeArr 	=array_merge($exec_name_arr, $executiveNewArr);	

			$exec_name_arr_1 =array_unique($executiveMergeArr);		
			asort($exec_name_arr_1);
			unset($exec_name_arr);
			foreach($exec_name_arr_1 as $ex_key=>$ex_val)
			{
				$exec_name_arr[] =$ex_val;
			}
			$total_exec =count($exec_name_arr);
			if($total_exec>0)
			{
				$exec_supervisor_arr =getExecSupervisor($exec_name_arr);	
				$head_name_arr = $exec_supervisor_arr[0];	
				$emp_id_arr = $exec_supervisor_arr[1];	
			}
		
			for($i=0; $i<$total_exec; $i++)
			{			
					// Records condition added
					$profile_count1 	=0;
					$profile_count2 	=0;
					$profile_count3 	=0;
					$profile_count4 	=0;
					$profile_count5 	=0;
					$profile_count6 	=0;
					$profile_count7 	=0;
					$profile_count8 	=0;
					$profile_count9 	=0;		
					$profile_count11	=0;
					$profileidArrSet 	=array();
					$profileidArrSetSeal 	=array();
					$dataSetArr		=array();		
					$vSealSetArr		=array();

	                                $exec_name  =$exec_name_arr[$i];
	                                $exec_emp_id  =$emp_id_arr[$exec_name];
					$head_name  =$head_name_arr[$exec_name];	
	                                $dataSetArr =$dataArr[$exec_name];
					$vSealSetArr=$vSealArr[$exec_name];
					if(count($dataSetArr)>0)
					{
						foreach($dataSetArr as $key=>$val)
	                                	{
							$dataValArr 	=$val;
							$profileidVal 	=$dataValArr['PROFILEID'];
							$typeVal	=$dataValArr['TYPE'];
	
							if($typeVal=='REG')
								$profile_count1++;
                                                	elseif($typeVal=='ACT')
                                                	        $profile_count2++;
                                                	elseif($typeVal=='PHONE')
                                                	        $profile_count6++;
							elseif($typeVal=='EMAIL')
								$profile_count7++;	
							elseif($typeVal=='ADDR')
								$profile_count8++;
							elseif($typeVal=='PHOTO')
								$profile_count3++;
							elseif($typeVal=='EOI')
								$profile_count11++;

							// Vishwas seal check added
							$val1 =$profileidVal."|PHONE"; 
							$val2 =$profileidVal."|EMAIL";
							$val3 =$profileidVal."|ADDR";
							if(!in_array("$profileidVal",$profileidArrSetSeal))
							{					
								$profileidArrSetSeal[] =$profileidVal;
								if(in_array($val1,$vSealSetArr) && in_array($val2,$vSealSetArr) && in_array($val3,$vSealSetArr))
									$profile_count9++;
							}
						}
					}

					$paidSet =$paidResultNewArr["$exec_name"];	
					if(count($paidSet)>0)
					{
						foreach($paidSet as $keyP=>$valP)
						{
							$profile_count4++;
							$profile_count5 +=$valP;
						}						
					}

					$data_arr[$i][$fields_arr[0]]   =$exec_name;
					$data_arr[$i][$fields_arr[1]]   =$exec_emp_id;
					$data_arr[$i][$fields_arr[2]]   =$head_name;
					$data_arr[$i][$fields_arr[3]] 	=$profile_count1;		
					$data_arr[$i][$fields_arr[4]] 	=$profile_count2;
					$data_arr[$i][$fields_arr[5]] 	=$profile_count3;
					$data_arr[$i][$fields_arr[6]]   =$profile_count11;
					$data_arr[$i][$fields_arr[7]] 	=$profile_count4;	
					$data_arr[$i][$fields_arr[8]]   =$profile_count5;
					$data_arr[$i][$fields_arr[9]]   =$profile_count6;
					$data_arr[$i][$fields_arr[10]]   =$profile_count7;
					$data_arr[$i][$fields_arr[11]]   =$profile_count8;
					$data_arr[$i][$fields_arr[12]]   =$profile_count9;

					$data_arr_tot[$fields_arr[3]] 	+=$data_arr[$i][$fields_arr[3]];		
					$data_arr_tot[$fields_arr[4]] 	+=$data_arr[$i][$fields_arr[4]];
					$data_arr_tot[$fields_arr[5]] 	+=$data_arr[$i][$fields_arr[5]];
					$data_arr_tot[$fields_arr[6]]   +=$data_arr[$i][$fields_arr[6]];
					$data_arr_tot[$fields_arr[7]] 	+=$data_arr[$i][$fields_arr[7]];
	                        	$data_arr_tot[$fields_arr[8]] 	+=$data_arr[$i][$fields_arr[8]];
	                        	$data_arr_tot[$fields_arr[9]] 	+=$data_arr[$i][$fields_arr[9]];
	                        	$data_arr_tot[$fields_arr[10]] 	+=$data_arr[$i][$fields_arr[10]];
	                        	$data_arr_tot[$fields_arr[11]] 	+=$data_arr[$i][$fields_arr[11]];
					$data_arr_tot[$fields_arr[12]] 	+=$data_arr[$i][$fields_arr[12]];

			} //for each executive loop condition ends

			$var_array =array("Total","Supervisor-Team-Average","Overall-Average");
			for($m=0; $m<count($var_array); $m++)
			{
				$sup_team_avg[$m][0]=$var_array[$m];	
				for($j=1; $j<count($fields_arr); $j++)
				{
					$field =$fields_arr[$j];
					if($field=='EMPID' || $field=='SUPERVISOR')
						$sup_team_avg[$m][$j] ='';
					else{
						if($m==0)
							$sup_team_avg[$m][$j] = $data_arr_tot[$field];
						elseif($total_exec)
							$sup_team_avg[$m][$j] = $data_arr_tot[$field]/$total_exec;
					}
				}		
			}

                	$from_dt1 =explode("-",$start_dt);
                	$upto_dt1 =explode("-",$end_dt);
			$from_dt =$from_dt1[2]."-".$from_dt1[1]."-".$from_dt1[0];
			$upto_dt =$upto_dt1[2]."-".$upto_dt1[1]."-".$upto_dt1[0];	

        	       	$label_arr =array(
                        	array("NAME"=>"Executive","VALUE"=>"0"),
                        	array("NAME"=>"Employee ID","VALUE"=>"1"),
				array("NAME"=>"Supervisor","VALUE"=>"2"),
                                array("NAME"=>"Total-Profiles-Created","VALUE"=>"3"),
                                array("NAME"=>"Total-Profiles-Activated/Registered-Post-Screening","VALUE"=>"4"),
				array("NAME"=>"Photo","VALUE"=>"5"),
				array("NAME"=>"Number of profiles on which first EoI was sent","VALUE"=>"6"),
                                array("NAME"=>"Total-Paid-Profiles","VALUE"=>"7"),
                                array("NAME"=>"Total-Payments-Received","VALUE"=>"8"),
                                array("NAME"=>"Total-profiles-with-Phone-verified","VALUE"=>"9"),
                                array("NAME"=>"Total-profiles-with-Email-Verified","VALUE"=>"10"),
                                array("NAME"=>"Total-profiles-with-Address-Documents-Received","VALUE"=>"11"),
                                array("NAME"=>"Vishwas-Seal","VALUE"=>"12")
                        );

			// ----------------------  Excel format Start  ------------------------
			if($format_type=="XLS")
			{
				$header_label = "Selected-Time-Period $from_dt To $upto_dt"."\n\n\n";	
				$header_label .="\t";
				for($x=0; $x<count($label_arr); $x++)
				{
					//$header_label .="\t";
					$label_value =$label_arr[$x]['NAME'];
					$header_label .=$label_value."\t";	
				}
				$dataSet ="\n"."$name"."\n";
	
				for($y=0; $y<$total_exec; $y++)
				{
					$dataSet .="\t";
					for($z=0; $z<count($fields_arr); $z++)
					{
						$data_value =$data_arr[$y][$fields_arr[$z]];
						$dataSet .=$data_value."\t";
					}
					$dataSet .="\n";
				}	

				for($a=0; $a<count($var_array); $a++)
				{
					$result .="\t";
					for($b=0; $b<count($fields_arr); $b++)
					{
						$res_val =$sup_team_avg[$a][$b];
						$result .=$res_val."\t";	
					}
					$result .="\n";
				}	
                	        header("Content-Type: application/vnd.ms-excel");
                	        header("Content-Disposition: attachment; filename=sugarcrm_LTF_report.xls");
                	        header("Pragma: no-cache");
                	        header("Expires: 0");
                	        $final_data = $header_label.$dataSet.$result;
				echo $final_data;
				die;
			}	
			//------------------------  Excel format Ends ---------------------------:

			$smarty->assign("start_dt",$start_dt);
			$smarty->assign("end_dt",$end_dt);	
			$smarty->assign("label_arr",$label_arr);
			$smarty->assign("data_arr",$data_arr);
			$smarty->assign("sup_team_avg",$sup_team_avg);
			$smarty->assign("head_label","Selected Time Period $from_dt To $upto_dt");
			$smarty->assign("RESULT",1);
	
		} // error check brace condition ends
	}
	//************************************    Condition after submit state Ends ***************************************
	//************************************    Condition before submit state Start ***************************************
	else
	{
		$date = date("Y-m-d");
		list($curyear,$curmonth,$curday) = explode("-",$date);
                $month_arr = array(
                                array("NAME" => "January", "VALUE" => "01"),
                                array("NAME" => "February", "VALUE" => "02"),
                                array("NAME" => "March", "VALUE" => "03"),
                                array("NAME" => "April", "VALUE" => "04"),
                                array("NAME" => "May", "VALUE" => "05"),
                                array("NAME" => "June", "VALUE" => "06"),
                                array("NAME" => "July", "VALUE" => "07"),
                                array("NAME" => "August", "VALUE" => "08"),
                                array("NAME" => "September", "VALUE" => "09"),
                                array("NAME" => "October", "VALUE" => "10"),
                                array("NAME" => "November", "VALUE" => "11"),
                                array("NAME" => "December", "VALUE" => "12"),
               			);
		$quarter_arr =array(
				array("NAME"=>"Apr-June","VALUE"=>"1"),
				array("NAME"=>"Jul-Sept","VALUE"=>"2"),
				array("NAME"=>"Oct-Dec","VALUE"=>"3"),
				array("NAME"=>"Jan-Mar","VALUE"=>"4")
				);

                for($i=1;$i<=31;$i++)
                        $ddarr[] = $i;

		for($i=0;$i<12;$i++)
			$mmarr[] = $month_arr[$i];

		for($i=2010;$i<=$curyear+2;$i++)
			$yyarr[] = $i;

                for($i=0;$i<4;$i++)
                        $qqarr[] = $quarter_arr[$i];

		$smarty->assign("top_label","Month");
		$smarty->assign("curyear",$curyear);
		$smarty->assign("curmonth",$curmonth);
		$smarty->assign("curday",$curday);
		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("yyarr",$yyarr);
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("qqarr",$qqarr);
		$smarty->assign("name",$name);
	}
	//************************************    Condition before submit state Ends  ***************************************

	$smarty->assign("err","$err");
	$smarty->assign("cid",$cid);
	$smarty->assign("name",$name);	
	$smarty->assign("outside",stripslashes($outside));
	$smarty->display("sugarcrm_LTF_report.htm");
}
else
{
        $smarty->assign("name",$name);
        $smarty->display("jsconnectError.tpl");
}

function getProfilesCount($start_dt, $end_dt, $uname_arr, $db)
{
	$sql_amount ="SELECT if(p.TYPE='DOL', p.DOL_CONV_RATE*p.AMOUNT,p.AMOUNT) as amt,PROFILEID,ENTRY_DT from billing.PAYMENT_DETAIL p where p.STATUS='DONE' AND p.ENTRY_DT >='$start_dt 00:00:00' AND p.ENTRY_DT <='$end_dt 23:59:59'";	
	$amount_res =mysql_query_decide($sql_amount,$db) or die("$sql_amount".mysql_error_js());	
	$amnt =array();
	while($amount_row=mysql_fetch_array($amount_res))	
	{
		$profileid =$amount_row['PROFILEID'];
		if(isPaymentDoneWithin12Months($profileid, $amount_row['ENTRY_DT'])) {
			if(array_key_exists("$profileid",$amnt))
				$amnt[$profileid] +=$amount_row['amt'];
			else 	
				$amnt[$profileid] =$amount_row['amt'];
		}		
	}

	$resultArr 	=array();
	$executiveArr 	=array();
	$executiveArr1 	=array();	
	$uname_str = "'".@implode("','",$uname_arr)."'";
	if(count($amnt)>0)
	{
        	foreach($amnt as $key=>$val)
        	{
   			$sql1 ="SELECT EXECUTIVE from MIS.LTF WHERE PROFILEID='$key' AND EXECUTIVE IN($uname_str)";                   
        		$res1 =mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js());
			while($row1=mysql_fetch_array($res1))
			{
				$executive 	=trim($row1['EXECUTIVE']);
				$resultArr["$executive"]["$key"] =$val;	
				$executiveArr[] =$executive;
			}
        	}
	}
	$executiveArr1 =array_unique($executiveArr);	
	return array("resultArr"=>$resultArr,"executiveArr"=>$executiveArr1);	
}	

function getExecSupervisor($exec_name_arr)
{
	$head_id_arr =array();
	$head_name_arr =array();

	$exec_name_str ="'".@implode("','",$exec_name_arr)."'";
        $sql ="SELECT HEAD_ID,EMP_ID,USERNAME from jsadmin.PSWRDS where USERNAME IN($exec_name_str)";
        $res =mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
        while($row=mysql_fetch_array($res)){
                $username =trim($row['USERNAME']);
                $emp_id_arr[$username] =$row['EMP_ID'];
                $head_id_arr[$username] =$row['HEAD_ID']; 
        }

	foreach($exec_name_arr as $key=>$val)
	{	
		$emp_id =$head_id_arr[$val];
		$sql ="SELECT USERNAME from jsadmin.PSWRDS where EMP_ID='$emp_id'";
		$res =mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		if($row=mysql_fetch_array($res))
			$head_name_arr[$val] =trim($row['USERNAME']);
	}
	return array($head_name_arr, $emp_id_arr);
}

function isPaymentDoneWithin12Months($profileid, $payment_dt) {
	$payment_dt_12months_back = date('Y-m-d', strtotime('-364 days', strtotime($payment_dt)));
	$sql ="SELECT PROFILEID from MIS.LTF WHERE PROFILEID='$profileid' AND TYPE='REG' AND DATE>='".$payment_dt_12months_back." 00:00:00'";   
	$res =mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
	return(mysql_fetch_array($res) ? 1 : 0);
}

?>
