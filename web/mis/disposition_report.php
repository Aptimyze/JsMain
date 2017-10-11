<?php


include("connect.inc");
include_once("user_hierarchy.php");
$data=authenticated($cid);
$db=connect_misdb();
$db1 = connect_crmSlave();

$data = authenticated($cid);
$name =trim(getname($cid));

if($data)
{
	//************************************    Condition after submit state  ***************************************
        $smarty->assign("cid",$cid);
        $smarty->assign("name",$name);

	$privilage      =getprivilage($cid);
	$priv           =explode("+",$privilage);
	if(in_array("MG",$priv) || in_array("TRNG",$priv) || in_array("P",$priv))
	        $outside ="'Y'";

        $smarty->assign("outside",stripslashes($outside));
	if($submit)
	{
		$start_dt ='';
		$end_dt ='';
                if($select_type=="M")
                {       
                	$start_dt = $year_month."-".$month."-01";
                        $end_dt = $year_month."-".$month."-31";
			$agentArray =$usernamesArray1;
                }
                elseif($select_type=="R")
                {
                        $start_dt = $year_r1."-".$month_r1."-".$day_r1;
                        $end_dt = $start_dt; 
			$agentArray =$usernamesArray2;
                }
		if($start_dt=='' || $end_dt=='')
			$err ='1';

                $agentCnt =count($agentArray);
                if($agentCnt==0){
                        $supervisor=0;
                        $agentArray=array($name);
                        $agentCnt =1;
                }
                else
                        $supervisor=1;


		if($err)
		{
			$smarty->assign("err",$err);
			$smarty->display("disposition_report.htm");
			die;
		}

		//*************  Common code for bothe levels  Start *********
		$dispositionValArr 	=array();
		$dispositionNewArr 	=array();
		$dispositionLabelArr 	=array();		
	
                $dispositionNewArr =array("NAME"=>"Executive","TOTAL"=>"Total Disposition");
                $sql ="SELECT DISPOSOTION_VALUE,DISPOSOTION_LABEL from incentive.CRM_DISPOSITION where ACTIVE='Y'";
                $res_disp =mysql_query_decide($sql,$db1) or die("$sql".mysql_error_js());
                while($row_disp =mysql_fetch_array($res_disp))
                {
			$dispositionVal		=$row_disp["DISPOSOTION_VALUE"];
			$dispositionLabel	=$row_disp["DISPOSOTION_LABEL"];

                	$dispositionValArr[] 			=$dispositionVal;
                        $dispositionNewArr[$dispositionVal] 	=$dispositionLabel;
                        $dispositionLabelArr[$dispositionVal] 	=$dispositionLabel;
                }
		$dispositionValStr = "'".@implode("','",$dispositionValArr)."'";	

		$sql ="select VALIDATION_VALUE, VALIDATION_LABEL from incentive.CRM_DISPOSITION_VALIDATION";
		$res_valid =mysql_query_decide($sql,$db1) or die("$sql".mysql_error_js());
		while($row_valid =mysql_fetch_array($res_valid))
		{
			$validationLabelArr[$row_valid["VALIDATION_VALUE"]] =$row_valid["VALIDATION_LABEL"];		
		}
		//****************  Common code for bothe levels  *****************

		// *************** Section for the aggregate level Start ******************
		if($field_level =='AGG')
		{
			$total_cnt =0;
			$disposition_countArr =array();

			for($i=0; $i<$agentCnt;$i++)
			{
				$agent_name =$agentArray[$i];
				$sql ="select count(PROFILEID) AS CNT,DISPOSITION, MODE from incentive.HISTORY where ENTRY_DT >='$start_dt 00:00:00' AND ENTRY_DT <='$end_dt 23:59:59' AND ENTRYBY='$agent_name' AND DISPOSITION IN($dispositionValStr) group by DISPOSITION";
				$res_disp =mysql_query_decide($sql,$db1) or die("$sql".mysql_error_js());	
				while($row_disp =mysql_fetch_array($res_disp))
				{
					$disp_count 		=$row_disp["CNT"];
					$disposition_val 	=$row_disp["DISPOSITION"];
					$disposition_countArr[$i][$disposition_val] 	=$disp_count;
					$disposition_countArr[$i]["TOTAL"] 		+=$disp_count;

					$disposition_sumArr[$disposition_val] 		+=$disp_count;
					$disposition_sumArr["TOTAL"] 			+=$disp_count;	
				}
				$disposition_countArr[$i]["NAME"] =$agent_name;
			}
			
			$effective_cnt=0;
			for($jj=0; $jj<count($disposition_countArr); $jj++)
			{
				if($disposition_countArr[$jj]["TOTAL"]>0)
					$effective_cnt++;		
			}

			if($supervisor)
			{
				$var_array =array("Total","Supervisor-Team-Average","Overall-Average");
				for($m=0; $m<count($var_array); $m++)
				{
					$sup_team_avg[$m]['NAME']=$var_array[$m];	
					for($j=0; $j<count($dispositionValArr); $j++)
					{
						$field =$dispositionValArr[$j];
						if($m==0){
							$sup_team_avg[$m][$field] = $disposition_sumArr[$field];
							$sup_team_avg[$m]["TOTAL"] = $disposition_sumArr["TOTAL"];
						}
						elseif($effective_cnt){
							$sup_team_avg[$m][$field] = $disposition_sumArr[$field]/$effective_cnt;
							$sup_team_avg[$m]["TOTAL"] = $disposition_sumArr["TOTAL"]/$effective_cnt;
						}	
					}		
				}
			}
			$smarty->assign("dispositionNewArr",$dispositionNewArr);
			$smarty->assign("dispositionLabelArr",$dispositionLabelArr);
			$smarty->assign("disposition_countArr",$disposition_countArr);
			$smarty->assign("sup_team_avg",$sup_team_avg);
			$result =1;

		}
		// *************** Section for the aggregate level Ends  *****************

		// *************** Section for the Detailed level Start ******************
		else if($field_level =='DET')
		{
			for($k=0; $k<$agentCnt;$k++)
			{
				$agent_name =$agentArray[$k];
                                $sql ="select PROFILEID, DISPOSITION, VALIDATION, ENTRY_DT, COMMENT, MODE from incentive.HISTORY where ENTRY_DT >='$start_dt 00:00:00' AND ENTRY_DT <='$end_dt 23:59:59' AND ENTRYBY='$agent_name' ORDER BY PROFILEID ASC,ENTRY_DT DESC";
                                $res_disp =mysql_query_decide($sql,$db1) or die("$sql".mysql_error_js());
				$i= 0;
                                while($row_disp =mysql_fetch_array($res_disp))
                                {
                                        $profileid	=$row_disp["PROFILEID"];
					$entry_dt       =$row_disp["ENTRY_DT"];
					$disposition 	=$row_disp["DISPOSITION"];
					$validation 	=$row_disp["VALIDATION"];
					$comment     	=$row_disp["COMMENT"];
					$mode     		=preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $row_disp["MODE"]);
					if(is_numeric($row_disp["MODE"])){
						$processVal = $row_disp["MODE"];
					} else {
						$processVal = $mode[1];
					}
					$process 		=crmParams::$processNames[crmParams::$processFlagReverse[$processVal]];

					$data_arr[$agent_name]['PROFILEID'][$i] =$profileid;
					$data_arr[$agent_name]['ENTRY_DT'][$i] =$entry_dt;
					$data_arr[$agent_name]['COMMENT'][$i] =$comment;
					$data_arr[$agent_name]['DISPOSITION'][$i] =$dispositionLabelArr[$disposition];
					$data_arr[$agent_name]['VALIDATION'][$i] =$validationLabelArr[$validation];
					$data_arr[$agent_name]['PROCESS'][$i] =$process;

                                        $sql ="select USERNAME from newjs.JPROFILE where PROFILEID='$profileid'";
                                        $res_name =mysql_query_decide($sql,$db1) or die("$sql".mysql_error_js());
                                        $row_name =mysql_fetch_array($res_name);
                                        $profile_name =$row_name["USERNAME"];
                                        $data_arr[$agent_name]['USERNAME'][$i] =$profile_name;
				
					$sql ="select ALLOTED_TO from incentive.MAIN_ADMIN where PROFILEID='$profileid'";
					$res_allotment =mysql_query_decide($sql,$db1) or die("$sql".mysql_error_js());	
					$row_allotment =mysql_fetch_array($res_allotment);		
					$allotedTo =$row_allotment["ALLOTED_TO"];
					$data_arr[$agent_name]['ALLOTED_TO'][$i] =$allotedTo;

					$i++;
                                }
			}
			$smarty->assign("data_arr",$data_arr);
			$result =2;
		}
		// *************** Section for the Detailed level Ends ******************			

		// ************** Excel format Start  ***************************
		if($format_type=="XLS")
		{
			$header_label 	='';
			$data 		='';
			// ****  Aggregate level xls sheet formation ****** 
			if($field_level =='AGG')
			{
				$result	='';
				$header_label = "Selected Time Period $start_dt To $end_dt"."\n\n\n";
			
	                        foreach($dispositionNewArr as $key=>$val)
        	                {
                	                $header_label .="\t";
                	                $header_label .="$val";
                	        }
                	        $data ="\n"."$name";

				foreach($disposition_countArr as $key=>$val)
				{
					$data .="\n\t";
					$data .=$val["NAME"]."\t".$val["TOTAL"];
					foreach($dispositionLabelArr as $key1=>$val1)
					{
						$data .="\t".$disposition_countArr[$key][$key1];
					}
				}

                        	foreach($sup_team_avg as $key=>$val)	
	                        {
        	                        $result .="\n\t";
					$result .=$val["NAME"]."\t".$val["TOTAL"];
					foreach($dispositionLabelArr as $key1=>$val1)
                	                {
						$result .="\t".$sup_team_avg[$key][$key1];
                	                }
                	        }

				$finalData =$header_label.$data.$result;
			}

			// ****  Detailed level xls sheet formation ******
			else if($field_level =='DET')
			{
	                        $header_label = "Selected Time Period $start_dt To $end_dt"."\n\n\n";
                                $header_label .="\tExecutive\tUsername\tDateTime\tDisposition\tValidation\tComments\tCurrently-Alloted-To\tAgent Process";
	
				$data ="\n"."$name";
				foreach($data_arr as $key=>$val)
				{
					$data .="\n\t";
					$data .=$key;
					$entry_dt_arr =$val["ENTRY_DT"];				
					foreach($entry_dt_arr as $key1=>$val1)
					{
						$data .="\n\t\t";	
						//$data .=$data_arr[$key]["PROFILEID"][$key1]."\t".$data_arr[$key]["USERNAME"][$key1]."\t".$val1."\t".$data_arr[$key]["DISPOSITION"][$key1]."\t".$data_arr[$key]["VALIDATION"][$key1]."\t".$data_arr[$key]["ALLOTED_TO"][$key1];			
						$data .=$data_arr[$key]["USERNAME"][$key1]."\t".$val1."\t".$data_arr[$key]["DISPOSITION"][$key1]."\t".$data_arr[$key]["VALIDATION"][$key1]."\t".$data_arr[$key]["COMMENT"][$key1]."\t".$data_arr[$key]["ALLOTED_TO"][$key1]."\t".$data_arr[$key]["PROCESS"][$key1];	

					}
				}	
				$finalData =$header_label.$data;
			}			

                        header("Content-Type: application/vnd.ms-excel");
                        header("Content-Disposition: attachment; filename=Disposition_Report.xls");
                        header("Pragma: no-cache");
                        header("Expires: 0");
                        echo $finalData;
                        die;
		}	
		//****************  Excel format Ends **************************

		$smarty->assign("RESULT",$result);
		$smarty->assign("head_label","Selected Time Period $start_dt To $end_dt");

	}
	//***********************    Submitted  Section Ends    *****************************

	//***********************    Normal form section Start  *****************************
	else
	{
	        if(stripslashes($outside)=="'Y'")
        	        $branch="ALL";
        	else    
                	$branch="";
        	$usernames_str =user_hierarchy($name,$branch);
        	$usernames_str_rep =str_replace("'","",$usernames_str);
        	$usernames_array =explode(",",$usernames_str_rep);
		if(count($usernames_array)>1)
			sort($usernames_array);

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

		$smarty->assign("usernames_array",$usernames_array);
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
	//***********************    Normal form section Ends  *****************************
	$smarty->display("disposition_report.htm");
}
else
{
        $smarty->assign("name",$name);
        $smarty->display("jsconnectError.tpl");
}



?>
