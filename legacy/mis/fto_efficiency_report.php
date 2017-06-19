<?php
include("connect.inc");
include_once("user_hierarchy.php");

$db2		=connect_master();
$data		=authenticated($cid);
$db		=connect_misdb();
$name 		=trim(getname($cid));

if($data)
{
	//************************************    Condition after submit state  ***************************************

	if($submit || $outside=='Y')
	{
		$privilege      =getprivilage($cid);
		$priv           =explode("+",$privilege);
		if(in_array("TRNG",$priv) || in_array("P",$priv) || in_array("MG",$priv)){
			$branch="ALL";
			$allAgent ='Y';
		}
		else{
			$branch="";	
			$allAgent ='';
		}

		if($outside=="Y"){
			list($year_r1,$month_r1,$day_r1) =explode("-",date("Y-m-1"));
			list($year_r2,$month_r2,$day_r2)=explode("-",date("Y-m-d"));
			$select_type='R';							
		}

		$start_dt       ='';
		$end_dt         ='';
		$uname_str 	='';
		$uname_arr	=array();
		$defaultDate	='0000-00-00 00:00:00';
		$exec_name_arr  =array();

		$uname_str 	=user_hierarchy($name,$branch,'Y','Y');
		$uname_str 	=str_replace("'","",$uname_str);
		$uname_arr 	=@explode(",",$uname_str);
		$uname_arr	=@array_unique($uname_arr);
		$uname_cnt 	=count($uname_arr);	
		if($uname_cnt==0)
			die("No records found");

                if($select_type=="M"){
                	$start_dt 	=$year_month."-".$month."-01";
                        $end_dt   	=$year_month."-".$month."-31";
			$headLabelTxt 	='For the month';
                }
                elseif($select_type=="R"){
                        $start_dt 	=$year_r1."-".$month_r1."-".$day_r1;
                        $end_dt   	=$year_r2."-".$month_r2."-".$day_r2;
			$headLabelTxt 	='For the period';
		}
                $datetime1 	=JSstrToTime($start_dt);
                $datetime2 	=JSstrToTime($end_dt);
	        $time 		=$datetime2-$datetime1;
                $days 		=round($time/86400);
		if($days>'184' || ($start_dt=='' || $end_dt==''))
			$err ='1';

                $fields =array("0"=>'EXECUTIVE',"1"=>"SUPERVISOR","2"=>'TOTAL_ALLOCATION',"3"=>'FTO_ELIGIBILITY',"4"=>'PHOTO',"5"=>'PHONE_VERIFY','6'=>'FTO_OFFER',"7"=>'FTO_OFFER_PERCENTAGE',"8"=>'FIRST_EOI',"9"=>'FTO_ACTIVATION',"10"=>"FTO_ACTIVATION_PERCENTAGE","11"=>'AVG_ALLOCATION_DAYS',"12"=>"FTO_INCENTIVE","13"=>'AVG_DAYS');
		$label_arr =array(
			array("NAME"=>"Executive","VALUE"=>"0"),
			array("NAME"=>"Supervisor","VALUE"=>"1"),
			array("NAME"=>"Total number of allocations","VALUE"=>"2"),
			array("NAME"=>"Number of FTO Eligible allocations","VALUE"=>"3"),
			array("NAME"=>"Number of profiles on which photo was uploaded","VALUE"=>"4"),
			array("NAME"=>"Number of profiles on which phone was verified","VALUE"=>"5"),
			array("NAME"=>"Number of Free Trial Offers ","VALUE"=>"6"),
			array("NAME"=>"%age Free Trail Offers","VALUE"=>"7"),
			array("NAME"=>"Number of profiles on which first EoI was sent","VALUE"=>"8"),
			array("NAME"=>"Number of profiles with FTO Activation","VALUE"=>"9"),
			array("NAME"=>"%age of FTO Activation","VALUE"=>"10"),
			array("NAME"=>"Average Allocation Days","VALUE"=>"11"),
			array("NAME"=>"Number of profiles eligible for incentive","VALUE"=>"12")
		);


		if(!$err){
			$sql ="SELECT * from MIS.FTO_EXEC_EFFICIENCY_MIS where ALLOT_TIME>='$start_dt 00:00:00' AND ALLOT_TIME<='$end_dt 23:59:59'";
			$res =mysql_query_decide($sql,$db2) or die("$sql".mysql_error_js());
			while($row=mysql_fetch_array($res))
			{
				$allotTime	=$row['ALLOT_TIME'];
				$exec 		=trim($row['EXECUTIVE']);
				$profileid      =$row['PROFILEID'];
				$deAllocationDt	=$row['DEALLOCATION_DT'];

				// only under hierarchy executive are considered
				if(!in_array("$exec",$uname_arr))	
					continue;
				$exec_name_arr[]=$exec;

				$deAllocationDtArr 	=@explode(" ",$deAllocationDt);
				$allotTimeArr		=@explode(" ",$allotTime);	
				$timeDiff 		=abs(JSstrToTime($deAllocationDtArr[0])-JSstrToTime($allotTimeArr[0]));
				$allocDays 		=round($timeDiff/86400,0)+1;

				$execFtoCnt[$exec][$fields[13]] +=$allocDays;
				$execFtoCntTot[$fields[13]] 	+=$allocDays;	

				$execFtoCnt[$exec][$fields[2]] 	+=1;
				$execFtoCntTot[$fields[2]] 	+=1;

				$fto_eligibility=$row['FTO_ELIGIBILITY_DT'];
				if($fto_eligibility!=$defaultDate){
					$execFtoCnt[$exec][$fields[3]] +=1;
					$execFtoCntTot[$fields[3]] +=1;
				}
                                $photo          =$row['PHOTO_DT'];
				if($photo!=$defaultDate){
					$execFtoCnt[$exec][$fields[4]] +=1;
					$execFtoCntTot[$fields[4]] +=1;
				}
                                $phone_verify   =$row['PHONE_VERIFY_DT'];
				if($phone_verify!=$defaultDate){
					$execFtoCnt[$exec][$fields[5]] +=1;
					$execFtoCntTot[$fields[5]] +=1;
				}
                                $photo_offer    =$row['FTO_OFFER_DT'];
				if($photo_offer!=$defaultDate){
					$execFtoCnt[$exec][$fields[6]] +=1;
					$execFtoCntTot[$fields[6]] +=1;
				}	
                                $first_eoi	=$row['FIRST_EOI_DT'];
				if($first_eoi!=$defaultDate){
					$execFtoCnt[$exec][$fields[8]] +=1;
					$execFtoCntTot[$fields[8]] +=1;
				}	
                                $fto_act        =$row['FTO_ACTIVATION_DT'];
				if($fto_act!=$defaultDate){
					$execFtoCnt[$exec][$fields[9]] +=1;
					$execFtoCntTot[$fields[9]] +=1;
				}
			}

			$sql ="select EXECUTIVE,count(*) as CNT from MIS.FTO_EXEC_EFFICIENCY_MIS WHERE FTO_INCENTIVE_DT>='$start_dt 00:00:00' AND FTO_INCENTIVE_DT<='$end_dt 23:59:59' GROUP BY EXECUTIVE";
                        $res =mysql_query_decide($sql,$db2) or die("$sql".mysql_error_js());
                        while($row=mysql_fetch_array($res)){
                                $cnt           	=$row['CNT'];
                                $exec      	=trim($row['EXECUTIVE']);
                                if(!in_array("$exec",$uname_arr))
                                        continue;
				$exec_name_arr[] =$exec;
                                $execFtoCnt[$exec][$fields[12]] =$cnt; 
                                $execFtoCntTot[$fields[12]] 	+=$cnt;
			}
			$exec_name_arr =@array_unique($exec_name_arr);

			// Total count manipulation
			$fto_offerCntTot                        =$execFtoCntTot[$fields[6]];
			$fto_eligibleCntTot                     =$execFtoCntTot[$fields[3]];
			if($fto_eligibleCntTot)
			$fto_offer_percentageTot                =($fto_offerCntTot/$fto_eligibleCntTot)*100;
			$execFtoCntTot[$fields[7]]           	=@round($fto_offer_percentageTot,1);

			$fto_actCntTot                          =$execFtoCntTot[$fields[9]];
			$fto_totalActTot                        =$execFtoCntTot[$fields[2]];
			if($fto_totalActTot)
			$fto_act_percentageTot                  =($fto_actCntTot/$fto_totalActTot)*100;
			$execFtoCntTot[$fields[10]]          	=@round($fto_act_percentageTot,1);

			$fto_allocDaysTot                       =$execFtoCntTot[$fields[13]];
			if($fto_totalActTot)
			$fto_avgAllocDaysTot                    =$fto_allocDaysTot/$fto_totalActTot;
			$execFtoCntTot[$fields[11]]       	=@round($fto_avgAllocDaysTot,1);

			// get supervisor
			$total_exec =count($exec_name_arr);
			if($total_exec>0){
				$head_name_arr =getExecSupervisor($exec_name_arr);
				foreach($exec_name_arr as $key=>$val){
					$supervisor				=$head_name_arr[$val];		  	
					$supervisorArr[]			=$supervisor;	
					$execFtoCnt[$val][$fields[1]] 		=$supervisor;
					$execFtoCnt =getFtoPercentageCac($val,$fields,$execFtoCnt);
				} 

				$supervisorArr =array_unique($supervisorArr);			
				$head_name_arr2 =getExecSupervisor($supervisorArr);
				if(count($supervisorArr)>0){
	                	        foreach($supervisorArr as $key2=>$val2){

                                                $uname_str1      =user_hierarchy($val2,$branch,'Y');
                                                $uname_str1      =str_replace("'","",$uname_str1);
                                                $uname_arr1      =@explode(",",$uname_str1);
                                                $uname_arr1      =@array_unique($uname_arr1);

                                                for($k=2; $k<count($fields); $k++){
                                                        foreach($uname_arr1 as $key3=>$val3)
                                                                $supCountArr[$val2][$fields[$k]]   +=$execFtoCnt[$val3][$fields[$k]];
                                                }

        	        	        	$supervisor2             	=$head_name_arr2[$val2];
                		        	$supCountArr[$val2]['SUPERVISOR2'] =$supervisor2;
						$supCountArr =getFtoPercentageCac($val2,$fields,$supCountArr);
                        		}
				}
			}	
				

			// Selected data range
			if($select_type=='M')
				 $headLabelDate =date("M-Y",JSstrToTime($start_dt));
			else{
                                $from_dt =date("d-M-Y",JSstrToTime($start_dt));
                                $upto_dt =date("d-M-Y",JSstrToTime($end_dt));
				$headLabelDate =$from_dt." to ".$upto_dt;
			}
			$headLabelTxt .=" ".$headLabelDate;

			// ----------------------  Excel format Start  ------------------------
			if($format_type=="XLS")
			{
				unset($fields['13']);
				$header_label = "$headLabelTxt"."\n\n\n";	
				for($x=0; $x<count($label_arr); $x++){
					$label_value =$label_arr[$x]['NAME'];
					$header_label .=$label_value."\t";	
				}
				$dataSet .="\n";

				if(count($execFtoCnt)>0){
				foreach($execFtoCnt as $execKey=>$execVal){			
					$dataSet .=$execKey."\t";
					for($z=1; $z<count($fields); $z++)
					{
						$data_value =$execVal[$fields[$z]];
						if(!$data_value)
							$data_value =0;
						$dataSet .=$data_value;
						if($z==7||$z==10)
							$dataSet .="%";
						$dataSet .="\t";
					}
					$dataSet .="\n";
				}

				$dataSet .="Total\t";
				for($z=1; $z<count($fields); $z++){
					$data_value1 =$execFtoCntTot[$fields[$z]];
					$dataSet .=$data_value1;
					if($z==7||$z==10)
						$dataSet .="%";
					$dataSet .="\t";
				}
				$dataSet .="\n";

				$fields[1] =$fields[1]."2";                   
		             	foreach($supCountArr as $execKey1=>$execVal1){
                                        $dataSet .=$execKey1."\t";
                                        for($z=1; $z<count($fields); $z++)
                                        {
                                                $data_value1 =$execVal1[$fields[$z]];
                                                $dataSet .=$data_value1;
	                                        if($z==7||$z==10)
        	                                        $dataSet .="%";
        	                                $dataSet .="\t";
                                        }
                                        $dataSet .="\n";
                                }
				}

                	        header("Content-Type: application/vnd.ms-excel");
                	        header("Content-Disposition: attachment; filename=fto_efficiency_report.php");
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
			$smarty->assign("data_arr",$execFtoCnt);
			$smarty->assign("execFtoCntTot",$execFtoCntTot);
			$smarty->assign("supCountArr",$supCountArr);
			$smarty->assign("head_label","$headLabelTxt");
			$smarty->assign("type",$select_type);
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
                for($i=1;$i<=31;$i++)
                        $ddarr[] = $i;
		for($i=0;$i<12;$i++)
			$mmarr[] = $month_arr[$i];
		for($i=2010;$i<=$curyear+2;$i++)
			$yyarr[] = $i;
		$smarty->assign("curyear",$curyear);
		$smarty->assign("curmonth",$curmonth);
		$smarty->assign("curday",$curday);
		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("yyarr",$yyarr);
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("name",$name);
	}
	//************************************    Condition before submit state Ends  ***************************************

	$smarty->assign("err","$err");
	$smarty->assign("cid",$cid);
	$smarty->assign("name",$name);	
	$smarty->assign("outside",$outside);
	$smarty->display("fto_efficiency_report.htm");
}
else
{
        $smarty->assign("name",$name);
        $smarty->display("jsconnectError.tpl");
}

function getFtoPercentageCac($val,$fields,$execFtoCnt)
{

	$fto_offerCnt                           =$execFtoCnt[$val][$fields[6]];
	$fto_eligibleCnt                        =$execFtoCnt[$val][$fields[3]];
	if($fto_eligibleCnt)
	$fto_offer_percentage                   =($fto_offerCnt/$fto_eligibleCnt)*100;
	$execFtoCnt[$val][$fields[7]]           =@round($fto_offer_percentage,1);

	$fto_actCnt                             =$execFtoCnt[$val][$fields[9]];
	$fto_totalAct                           =$execFtoCnt[$val][$fields[2]];
	if($fto_totalAct)
	$fto_act_percentage                     =($fto_actCnt/$fto_totalAct)*100;
	$execFtoCnt[$val][$fields[10]]          =@round($fto_act_percentage,1);

	$fto_allocDays                          =$execFtoCnt[$val][$fields[13]];
	if($fto_totalAct)
	$fto_avgAllocDays                       =$fto_allocDays/$fto_totalAct;
	$execFtoCnt[$val][$fields[11]]          =@round($fto_avgAllocDays,1);
	
	return $execFtoCnt;
}

function getExecSupervisor($exec_name_arr)
{
	$head_id_arr 	=array();
	$head_name_arr 	=array();

	$exec_name_str ="'".@implode("','",$exec_name_arr)."'";
        $sql ="SELECT HEAD_ID,USERNAME from jsadmin.PSWRDS where USERNAME IN($exec_name_str)";
        $res =mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
        while($row=mysql_fetch_array($res)){
                $username =trim($row['USERNAME']);
                $head_id_arr[$username] =$row['HEAD_ID']; 
        }
	foreach($exec_name_arr as $key=>$val){
		$emp_id =$head_id_arr[$val];
		$sql ="SELECT USERNAME from jsadmin.PSWRDS where EMP_ID='$emp_id'";
		$res =mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		if($row=mysql_fetch_array($res))
			$head_name_arr[$val] =trim($row['USERNAME']);
	}
	return $head_name_arr;
}

?>
