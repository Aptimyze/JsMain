<?php
include("connect.inc");
include("../profile/pg/functions.php");
include_once("user_hierarchy.php");
ini_set("memory_limit","16M");
ini_set("max_execution_time","0");
$db=connect_misdb();

if(authenticated($cid))
{
	$name =getname($cid);
	$privilege=explode("+",getprivilage($cid));
	if(in_array("TRNG",$privilege) || in_array("P",$privilege) || in_array("MG",$privilege))
		$mgr =1;
	else
		$mgr =0;

        if($mgr || in_array("SLSUP",$privilege) || in_array("SLHD",$privilege))
                $disableLink =0;
        else
                $disableLink =1;

	$smarty->assign("cid",$cid);
	if($CMDGo)
	{
			$allotetArr 		=array();
			$grandtotal 		=array();
			$grandtotal_allbranch 	=array();
			$agentArray 		=$usernamesArray2;
			$tot_exec_cnt		=0;
			if($agentArray[0]==''){
				if($mgr){
					$all_agent='ALL';
					$username_str =user_hierarchy($name,$all_agent,'',$all_agent);	
				}
				else
					$username_str =user_hierarchy($name,'','');
			}
			else
				$username_str ="'".implode("','",$agentArray)."'";

			for($i=0;$i<31;$i++)
                	{
                        	$ddarr[$i]=$i+1;
                	}
                	$smarty->assign("month",$month);
			$smarty->assign("year",$year);
			if ($month < 9)
				$month = "0".$month;

			// Current Allocation 
			$sql ="select PROFILEID,ALLOTED_TO from incentive.MAIN_ADMIN WHERE STATUS!='P' AND ALLOTED_TO IN($username_str)";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
			while($row=mysql_fetch_array($res)){
				$profileid    	=$row['PROFILEID'];	
				$alloted_exec 	=$row['ALLOTED_TO'];

			       	$sql1 ="SELECT ID from billing.SERVICE_STATUS where PROFILEID='$profileid' AND ACTIVATED='Y' AND ACTIVE='Y' AND SERVEFOR LIKE '%F%' LIMIT 1 ";
			       	$res1 =mysql_query_decide($sql1,$db) or die("$sql1".mysql_error_js());
			       	if(!mysql_num_rows($res1))
					$allotetArr[$alloted_exec] +=1;	
			}

			// Upsell Check
			$sqlj="SELECT USER FROM jsadmin.UPSELL_AGENT";
			$resj=mysql_query_decide($sqlj,$db) or die("$sqlj".mysql_error_js());
			while($rowj = mysql_fetch_array($resj))
			        $allot_to_array[] = $rowj['USER'];
			$upsell_agent=implode("','",$allot_to_array);

                       	$sql ="select count(*) as cnt,ALLOTED_TO from incentive.MAIN_ADMIN WHERE STATUS='P' AND ALLOTED_TO IN('$upsell_agent')";
                       	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
                       	while($row=mysql_fetch_array($res)){
                       	        $alloted_exec = $row['ALLOTED_TO'];
                       	        $alloted_cnt = $row['cnt'];
                       	        $allotetArr[$alloted_exec] +=$alloted_cnt;
                       	}	

			$sql="SELECT COUNT(*) as cnt,ALLOTED_TO,DAYOFMONTH(ALLOT_TIME) as dd FROM incentive.CRM_DAILY_ALLOT WHERE ALLOT_TIME BETWEEN '$year-$month-01 00:00:00' AND '$year-$month-31 23:59:59' AND ALLOTED_TO IN ($username_str) GROUP BY ALLOTED_TO,dd";
			$sql .=" UNION SELECT COUNT(*) as cnt,ALLOTED_TO,DAYOFMONTH(ALLOT_TIME) as dd FROM incentive.CRM_DAILY_ALLOT_TRACK WHERE ALLOT_TIME BETWEEN '$year-$month-01 00:00:00' AND '$year-$month-31 23:59:59' AND ALLOTED_TO IN ($username_str) GROUP BY ALLOTED_TO,dd";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
			while($row=mysql_fetch_array($res))
			{
				$dd=$row['dd']-1;
				$alloted_to = $row['ALLOTED_TO'];

                                $sql="SELECT CENTER FROM jsadmin.PSWRDS WHERE USERNAME='$alloted_to'";
                                $res_c=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
                                $row_c=mysql_fetch_array($res_c);
                                $center=strtoupper($row_c['CENTER']);

				if(is_array($brancharr))
				{
					if(!in_array($center,$brancharr))
						$brancharr[]=$center;
				}
				else
				{
					$brancharr[]=$center;
				}

				if(1){
					$i=array_search($center,$brancharr);
					if(is_array($operatorarr[$i])){
						if(!in_array($alloted_to,$operatorarr[$i]))
						{
							$operatorarr[$i][]	=$alloted_to;
							$currentAlloc_arr[$i][]	=$allotetArr[$alloted_to];
							$currentAlloctot[$i]['callcnt'] +=$allotetArr[$alloted_to];
							$currentAlloctot_allbranch['callcnt'] +=$allotetArr[$alloted_to];
							$tot_exec_cnt++;
						}
					}
					else{
						$operatorarr[$i][]=$alloted_to;
						$currentAlloc_arr[$i][] =$allotetArr[$alloted_to];
                                                $currentAlloctot[$i]['callcnt'] +=$allotetArr[$alloted_to];
						$currentAlloctot_allbranch['callcnt'] +=$allotetArr[$alloted_to];
						$tot_exec_cnt++;
					}
					$j=array_search($alloted_to,$operatorarr[$i]);
					$crmwork[$i][$j][$dd]['callcnt'] += $row['cnt'];
                                	//$total[$i][$j]['callcnt']+= $row['cnt'];

                                	$grandtotal[$i][$dd]['callcnt']+= $row['cnt'];
                                	//$annualtot[$i]['callcnt']+= $row['cnt'];
					//$currentAlloctot[$i]['callcnt'] +=$allotetArr[$alloted_to];

					$grandtotal_allbranch[$dd]['callcnt'] += $row['cnt'];
                                	//$annualtot_allbranch['callcnt']+= $row['cnt'];
					//$currentAlloctot_allbranch['callcnt'] +=$allotetArr[$alloted_to];
				}
			}

			// Agerage Calculation
			if(count($grandtotal>0) && $tot_exec_cnt){
				foreach($grandtotal as $key=>$val){
					$branch_exec_cnt =count($operatorarr[$key]);
					//$annualtot_avg[$key]['callcnt'] =intval($annualtot[$key]['callcnt']/$branch_exec_cnt);
					$currentAlloctot_avg[$key]['callcnt'] =intval($currentAlloctot[$key]['callcnt']/$branch_exec_cnt);	
					foreach($val as $key1=>$val1){
						$grandtotal_avg[$key][$key1]['callcnt']= intval($grandtotal[$key][$key1]['callcnt']/$branch_exec_cnt);
					}
				}
			}
			if(count($grandtotal_allbranch>0) && $tot_exec_cnt){	
 	                       foreach($grandtotal_allbranch as $key3=>$val3){
        	                        $grandtotal_allbranch_avg[$key3]['callcnt'] =intval($grandtotal_allbranch[$key3]['callcnt']/$tot_exec_cnt);
        	                }
			}
			//$annualtot_allbranch_avg['callcnt'] =intval($annualtot_allbranch['callcnt']/$tot_exec_cnt);
			if($tot_exec_cnt)
				$currentAlloctot_allbranch_avg['callcnt'] =intval($currentAlloctot_allbranch['callcnt']/$tot_exec_cnt);	
			// Agerage Calculation

                        if (!count($crmwork))
                                $norecords = 1;

/**********************************************Code Added by for Excel*********************************************/
		if($mis_type=="XLS")
                {
			$header .="\t"."\t"."\t"."\t"."\t"."Executive Sales Allocation MIS"."\n";
			$header .="\t"."\t"."\t"."\t"."\t"."MIS for : $month - $year"."\n\n";
                        $header .= "Executive / Days"."\t"."Current Allocation"."\t";
                        for($i=0;$i<count($ddarr);$i++)
                        {
                                $header=$header.$ddarr[$i]."\t";
                        }
			
			for($i=0;$i<count($brancharr);$i++)
			{
				$data.=$brancharr[$i]."\n";
				for($j=0;$j<count($operatorarr[$i]);$j++)
				{
					$data.=$operatorarr[$i][$j]."-Total Calls"."\t";
					$data.=$currentAlloc_arr[$i][$j]."\t";		//new 
					for($k=0;$k<count($ddarr);$k++)
					{
						$data.=$crmwork[$i][$j][$k]["callcnt"]."\t";
					}
					$data.="\n";
					//$data.=$total[$i][$j]["callcnt"]."\n";
				}
			
				// Total calculation start (branchwise)
				$data.="Total Calls"."\t";
				$data.=$currentAlloctot[$i]['callcnt']."\t";
				for($dd_t=0;$dd_t<count($ddarr);$dd_t++)
                                {
					$data.=$grandtotal[$i][$dd_t]["callcnt"]."\t";
				}
				$data.="\n";
				//$data.=$annualtot[$i]["callcnt"]."\n";
				// Total calculation end (branchwise)

				// Agerage Calculation start (branchwise) 
                                $data.="Average"."\t";
                                $data.=$currentAlloctot_avg[$i]['callcnt']."\t";
                                for($dd_t=0;$dd_t<count($ddarr);$dd_t++)
                                {
                                        $data.=$grandtotal_avg[$i][$dd_t]["callcnt"]."\t";
                                }
				$data.="\n";
                                //$data.=$annualtot_avg[$i]["callcnt"]."\n";
				// Average calculationends (branchwise)

			}

			// Total calculation start (all branch)
			$data.="All branches -Total Calls"."\t";
			$data.=$currentAlloctot_allbranch['callcnt']."\t";		//current allocation	
			for($l=0;$l<count($ddarr);$l++)
                        {
				$data.=$grandtotal_allbranch[$l]["callcnt"]."\t";
			}
			$data.="\n";
			//$data.=$annualtot_allbranch["callcnt"]."\n";
			// Total calculation ends (all branch)

			// Agerage Calculation start (all branch)
                        $data.="Average (All branches) "."\t";
                        $data.=$currentAlloctot_allbranch_avg['callcnt']."\t";      	//current allocation
                        for($l=0;$l<count($ddarr);$l++)
                        {
                                $data.=$grandtotal_allbranch_avg[$l]["callcnt"]."\t";
                        }
			$data.="\n";
                        //$data.=$annualtot_allbranch_avg["callcnt"]."\n";

			// Average calculationends (all branch)

			$data = trim($data)."\t \n";

			header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=crm_sales_allocation.xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo $final_data = $header."\n".$data;
			die();
		}
/************************************************Code Ended for Excel*****************************************************/

		$smarty->assign("crmwork",$crmwork);
                $smarty->assign("grandtotal",$grandtotal);
                $smarty->assign("grandtotal_allbranch",$grandtotal_allbranch);
		$smarty->assign("grandtotal_avg",$grandtotal_avg);
		$smarty->assign("grandtotal_allbranch_avg",$grandtotal_allbranch_avg);

		$smarty->assign("norecords",$norecords);
		$smarty->assign("operatorarr",$operatorarr);

                $smarty->assign("currentAlloc_arr",$currentAlloc_arr);
                $smarty->assign("currentAlloctot",$currentAlloctot);
                $smarty->assign("currentAlloctot_allbranch",$currentAlloctot_allbranch);
                $smarty->assign("currentAlloctot_avg",$currentAlloctot_avg);
                $smarty->assign("currentAlloctot_allbranch_avg",$currentAlloctot_allbranch_avg);

		$smarty->assign("brancharr",$brancharr);
		$smarty->assign("yy",$yy);
		$smarty->assign("mmarr",$mmarr);
		//$smarty->assign("montharr",$montharr);
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("flag",1);
		$smarty->assign("disableLink","$disableLink");

		$smarty->display("crm_sales_allocation.htm");

		unset($grandtotal);
		unset($grandtotal_allbranch);
		unset($norecords);
		unset($crmwork);
		unset($currentAlloc_tot);
		unset($currentAlloc_branch_tot);
	}
	else
	{
                if($mgr)
                        $all_agent="ALL";
                else
                        $all_agent="";
                $usernames_str =user_hierarchy($name,$all_agent,'',$all_agent);
                $usernames_str_rep =str_replace("'","",$usernames_str);
                $usernames_array =explode(",",$usernames_str_rep);
                if(count($usernames_array)>1)
                        sort($usernames_array);

		$curDate =explode("-",date("Y-m"));
		$curMonth =$curDate[1];
		$curYear  =$curDate[0];	

                for($i=0;$i<12;$i++)
                {
                        $mmarr[$i]=$i+1;
                }
		$yy =date("Y");
		$j=0;
                for($i=2004;$i<=$yy;$i++)
                {
                        $yyarr[$j]=$i;
			$j++;
                }
		$smarty->assign("flag","0");
                $smarty->assign("mmarr",$mmarr);
                $smarty->assign("yyarr",$yyarr);
		$smarty->assign("name",$name);
		$smarty->assign("curMonth",$curMonth);
		$smarty->assign("curYear",$curYear);
		$smarty->assign("usernames_array",$usernames_array);
		$smarty->display("crm_sales_allocation.htm");
	}
}
else
{
        $smarty->display("jsconnectError.tpl");
}
?>
