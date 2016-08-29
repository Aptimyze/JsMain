<?php
include_once("connect.inc");
include_once("../profile/pg/functions.php");
include("../profile/arrays.php");

$db=connect_rep();

if(authenticated($cid))
{
	if($CMDGo)
	{
		$smarty->assign("flag","1");
		$ssarr =array();
		$serviceArrMap =array("0"=>"e-Rishta","1"=>"e-Value","2"=>"e-Sathi","3"=>"JS Exclusive","4"=>"JS Assisted","5"=>"Value Added Services","6"=>"e-Advantage");

		if($type=='M'){
			$mmarr  	=array('Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Jan','Feb','Mar');
			$yy     	=$MYear;
			$yyp1   	=$yy+1;
			$st_date	=$yy."-04-01";
			$end_date	=$yyp1."-03-31";
		}
		elseif($type=='D'){
			for($z=1;$z<=31;$z++)
				$mmarr[$z]=$z;
			if(isset($DMonth) && isset($DYear) && !empty($DMonth) && !empty($DYear)){
				$mm     =$DMonth;
				$yy     =$DYear;
				$st_date	=$yy."-".$mm."-01";
				$end_date	=$yy."-".$mm."-31";
			} else {
				$mm = date('m');
				$yy = date('Y');
				$st_date = date("Y-m-01");
				$end_date = date("Y-m-31");
			}
			$rmonth		=date("M",strtotime($st_date));
		}
		if($displayUnit == 'REVENUE')
		{
			if($type == 'M')
				$monthOrderArr = array("Apr"=>4, "May"=>5, "Jun"=>6, "Jul"=>7, "Aug"=>8, "Sep"=>9, "Oct"=>10, "Nov"=>11, "Dec"=>12, "Jan"=>1, "Feb"=>2, "Mar"=>3);
			else
				$monthOrderArr = range(1,date('t',strtotime($st_date)));
			
			if(empty($profileType) || count($profileType)==1 && in_array('F', $profileType))
			{
				list($serviceNameArr, $serviceWiseArr, $durationWiseArr) = array(NULL, NULL, NULL);				
			}
			else 
			{
				list($serviceNameArr, $serviceWiseArr, $durationWiseArr) = getRevenueDetail($reportType, $type, $st_date." 00:00:00", $end_date." 23:59:59", $branch);
			}

			$smarty->assign("cid",$cid);
			$smarty->assign("displayUnit","REVENUE");
			$smarty->assign("monthOrderArr",$monthOrderArr);
			$smarty->assign("serviceNameArr",$serviceNameArr);
			$smarty->assign("serviceWiseArr",$serviceWiseArr);
			$smarty->assign("durationWiseArr",$durationWiseArr);
			$smarty->assign("branch",$branch);
			$smarty->assign("yy",$yy);
			$smarty->assign("yyp1",$yyp1);
			$smarty->assign("type",$type);
			$smarty->assign("rmonth",$rmonth);
			$smarty->display("servicesmis.htm");
		}
		else 
		{
			if($type=='M')
				if(count($profileType) == 2 || empty($profileType))
					$sql="SELECT SUM(COUNT) as cnt,SERVICE,MONTH(ENTRY_DT) as mm,BRANCH FROM MIS.SERVICE_DETAILS WHERE ENTRY_DT>='$st_date' AND ENTRY_DT<='$end_date'";
				elseif(in_array('F', $profileType))
					$sql="SELECT SUM(FREE_COUNT) as cnt,SERVICE,MONTH(ENTRY_DT) as mm,BRANCH FROM MIS.SERVICE_DETAILS WHERE ENTRY_DT>='$st_date' AND ENTRY_DT<='$end_date'";
				elseif(in_array('P', $profileType))
					$sql="SELECT SUM(PAID_COUNT) as cnt,SERVICE,MONTH(ENTRY_DT) as mm,BRANCH FROM MIS.SERVICE_DETAILS WHERE ENTRY_DT>='$st_date' AND ENTRY_DT<='$end_date'";
			if($type=='D')
				if(count($profileType) == 2 || empty($profileType))
					$sql="SELECT SUM(COUNT) as cnt,SERVICE,DAY(ENTRY_DT) as mm,BRANCH FROM MIS.SERVICE_DETAILS WHERE ENTRY_DT>='$st_date' AND ENTRY_DT<='$end_date'";
				elseif(in_array('F', $profileType))
					$sql="SELECT SUM(FREE_COUNT) as cnt,SERVICE,DAY(ENTRY_DT) as mm,BRANCH FROM MIS.SERVICE_DETAILS WHERE ENTRY_DT>='$st_date' AND ENTRY_DT<='$end_date'";
				elseif(in_array('P', $profileType))
					$sql="SELECT SUM(PAID_COUNT) as cnt,SERVICE,DAY(ENTRY_DT) as mm,BRANCH FROM MIS.SERVICE_DETAILS WHERE ENTRY_DT>='$st_date' AND ENTRY_DT<='$end_date'";
			if($branch)
				$sql.=" AND UPPER(BRANCH)='".strtoupper($branch)."' ";
			$sql.=" GROUP BY mm, BRANCH, SERVICE ORDER BY SERVICE";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			while($row=mysql_fetch_array($res))
			{
				$center=strtoupper($row['BRANCH']);
				$mm=$row['mm'];
				if($type=='M'){
					if($mm<=3)
						$mm+=8;
					else
						$mm-=4;
				}
				$sid	=$row['SERVICE'];

				// Code exist for old services-- Start
				if($sid=='S1' || $sid=='S4')
					$sid='P3';
				elseif($sid=='S2' || $sid=='S5')
					$sid='P6';
				elseif($sid=='S3' || $sid=='S6')
					$sid='P12';
				// Code exist for old services-- End

				if(is_array($ssarr)){
					if(!in_array($sid,$ssarr)){
						if($reportType=='D'){

							// Matri profile handling
							if(strstr($sid,'M'))
								$sid='M';
							if($matriSet && $sid=='M'){}
								else
									$ssarr[]=$sid;
								if(strstr($sid,'M'))
									$matriSet =true;
							// Matrin profilehandling ends
							}
							elseif($reportType=='S')
							{
								if(strstr($sid,'ESJA'))
									$ssarr['4']=$sid;
								elseif(strstr($sid,'ES'))
									$ssarr['2']=$sid;
								elseif(strstr($sid,'P') && !strstr($sid,'NCP'))
									$ssarr['0']=$sid;
								elseif(strstr($sid,'C') && !strstr($sid,'NCP'))
									$ssarr['1']=$sid;
								elseif(strstr($sid,'X'))
									$ssarr['3']=$sid;
								elseif(strstr($sid,'NCP'))
									$ssarr['6']=$sid;
								else
									$ssarr['5']=$sid;
							}
						}
					}
					$i=array_search($sid,$ssarr);
					if($row['cnt'] != 0){
						$tot[$i][$mm]+=$row['cnt'];
						$tota[$i]+=$row['cnt'];
						$totb[$mm]+=$row['cnt'];
						$totall+=$row['cnt'];
					}
				}

			// Report Type check
				if($reportType=='D'){
					for($i=0;$i<count($ssarr);$i++)
						$ssarr[$i]=getsname($ssarr[$i]);
					$ssarrTemp =$ssarr;

				//sorting code
					foreach($ssarr as $kk=>$yyVal){
						if(strstr($yyVal,'11'))
							$newVal =str_replace("11 months","y months",$yyVal);
						elseif(strstr($yyVal,'12'))
							$newVal =str_replace("12 months","z months",$yyVal);
						else
							$newVal=$yyVal;
						$ssarr1[] =$newVal;
					}
					if($ssarr1)
						sort($ssarr1);
					unset($ssarr);
					if(count($ssarr1)>0){
						foreach($ssarr1 as $kk1=>$yy1Val){
							if(strstr($yy1Val,'y months'))
								$newVal =str_replace("y months","11 months",$yy1Val);
							elseif(strstr($yy1Val,'z months'))
								$newVal =str_replace("z months","12 months",$yy1Val);
							else
								$newVal=$yy1Val;
							$ssarr[] =$newVal;
						}
					}
				// sorting code ends

					$j=0;
					if(count($ssarr)>0){
						foreach($ssarr as $key=>$value){
							$i=array_search($value,$ssarrTemp);
							$newArr =$tot[$i];
							foreach($newArr as $key1=>$val1)
								$totTemp[$j][$key1] =$tot[$i][$key1];
							$totaTemp[$j]               =$tota[$i];
							$j++;
						}
					}
					unset($tot);
					unset($tota);
					$tot =$totTemp;
					$tota =$totaTemp;
				}
				elseif($reportType=='S'){
					for($i=0;$i<count($ssarr);$i++)
						$ssarr[$i]=$serviceArrMap[$i];
				}

				// print_r($mmarr);
				// print_r($totb);
				// print_r($ssarr);
				// print_r($tot);
				// print_r($tota);
				// print_r($totall);die;
				$smarty->assign("cid",$cid);
				$smarty->assign("mmarr",$mmarr);
				$smarty->assign("yy",$yy);
				$smarty->assign("yyp1",$yyp1);
				$smarty->assign("branch",$branch);
				$smarty->assign("ssarr",$ssarr);
				$smarty->assign("tot",$tot);
				$smarty->assign("tota",$tota);
				$smarty->assign("totb",$totb);
				$smarty->assign("totall",$totall);
				$smarty->assign("type",$type);
				$smarty->assign("rmonth",$rmonth);
				$smarty->display("servicesmis.htm");
			}
		}
		else
		{
			$user		=getname($cid);
			$privilage	=getprivilage($cid);
			$priv		=explode("+",$privilage);
			if(in_array('MA',$priv) || in_array('MB',$priv))
			{
			//run query : select all branches
				$smarty->assign("VIEWALL","Y");
				$sql="SELECT NAME FROM billing.BRANCHES";
				$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
				if($row=mysql_fetch_array($res)){
					$i=0;
					do{
						$branches[$i]=$row['NAME'];
						$i++;
					}while($row=mysql_fetch_array($res));
				}
				$smarty->assign("branches",$branches);
			}
			elseif(in_array('MC',$priv) || in_array('MD',$priv))
			{
						// run query : select branch of user
				$sql="SELECT CENTER FROM jsadmin.PSWRDS WHERE USERNAME='$user'";
				$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
				if($row=mysql_fetch_array($res))
					$branch=strtoupper($row['CENTER']);
				$smarty->assign("branch",$branch);
			}

			$smarty->assign("priv",$priv);
			$smarty->assign("cid",$cid);
			$smarty->display("servicesmis.htm");
		}
	}
	else
		$smarty->display("jsconnectError.tpl");

	function getsname($services_id)
	{
		$services_id=str_replace(",","','",$services_id);
		$sql="SELECT NAME from billing.SERVICES where SERVICEID in ('$services_id') ORDER BY NAME";
		$result=mysql_query_decide($sql) or die(mysql_error_js());
		while($myrow=mysql_fetch_array($result))
		{
			$sname[]=$myrow['NAME'];
		}
		if(count($sname)>0)
			$sname=implode("<br>",$sname);
		$sname =str_replace("Months","months",$sname);
		return $sname;
	}

	function getRevenueDetail($reportType, $reportPeriod, $st_date, $end_date, $branch='')
	{
		// $reportType = Detailed/Summary
		// $reportPeriod = Month-wise/Date-wise

		// slave connection
		$myDb=connect_misdb();
		mysql_query('set session wait_timeout=50000',$myDb);

		if($reportPeriod=='D')
			$reportPeriod = "DAYOFMONTH";
		else 
			$reportPeriod = "MONTH";

		if($branch!='')	
			$sql = "SELECT pd.`SERVICEID`, SUM(IF(pd.CUR_TYPE =  'DOL',  `NET_AMOUNT` * DOL_CONV_RATE,  `NET_AMOUNT`)) AS AMT, $reportPeriod( pm.ENTRY_DT ) AS DD
FROM billing.PURCHASES AS pur, billing.PURCHASE_DETAIL AS pd, billing.PAYMENT_DETAIL AS pm WHERE pur.BILLID = pd.BILLID AND pd.BILLID = pm.BILLID AND pm.ENTRY_DT >=  '$st_date' AND pm.ENTRY_DT <=  '$end_date' AND pm.STATUS =  'DONE' AND pur.SERVICEID NOT LIKE 'ES%' AND pur.SERVICEID NOT LIKE 'NCP%' AND pur.CENTER='$branch' GROUP BY pd.`SERVICEID` , DD";
		else
			$sql = "SELECT pd.`SERVICEID`, SUM(IF(pd.CUR_TYPE =  'DOL',  `NET_AMOUNT` * DOL_CONV_RATE,  `NET_AMOUNT`)) AS AMT, $reportPeriod( pm.ENTRY_DT ) AS DD
FROM billing.PURCHASES AS pur, billing.PURCHASE_DETAIL AS pd, billing.PAYMENT_DETAIL AS pm WHERE pur.BILLID = pd.BILLID AND pd.BILLID = pm.BILLID AND pm.ENTRY_DT >=  '$st_date' AND pm.ENTRY_DT <=  '$end_date' AND pm.STATUS =  'DONE' AND pur.SERVICEID NOT LIKE 'ES%' AND pur.SERVICEID NOT LIKE 'NCP%' GROUP BY pd.`SERVICEID` , DD";
		$res = mysql_query_decide($sql,$myDb) or die(mysql_error_js($myDb));

		if($reportType == 'D')
		{
			while($row = mysql_fetch_array($res))
			{
				$amt = round(net_off_tax_calculation($row['AMT'],$end_date));

				if(strstr($row['SERVICEID'],'M'))
				{
					$serviceWiseArr['M'][$row['DD']] += $amt;
					$serviceWiseArr['M']['TOTAL'] += $amt;
				}
				else
				{
					$serviceWiseArr[$row['SERVICEID']][$row['DD']] = $amt;
					$serviceWiseArr[$row['SERVICEID']]['TOTAL'] += $amt;
				}
			}

			$sql = "SELECT pur.`SERVICEID`, SUM( IF (pm.TYPE = 'DOL', AMOUNT * DOL_CONV_RATE, AMOUNT)) AS AMT, $reportPeriod( pm.ENTRY_DT ) AS DD FROM billing.PURCHASES AS pur, billing.PAYMENT_DETAIL AS pm WHERE pur.BILLID = pm.BILLID AND pm.ENTRY_DT >= '$st_date' AND pm.ENTRY_DT <= '$end_date' AND pm.STATUS = 'DONE' AND pur.SERVICEID LIKE 'ES%' GROUP BY pur.`SERVICEID`, DD";
			$res = mysql_query_decide($sql) or die(mysql_error_js());
			while($row = mysql_fetch_array($res))
			{
				$sid = explode(',', $row['SERVICEID']);
				$sid = $sid[0];
				$amt = round(net_off_tax_calculation($row['AMT'],$end_date));

				if(strstr($sid,'ESJ'))
				{
					$serviceWiseArr[$sid][$row['DD']] += $amt;
					$serviceWiseArr[$sid]['TOTAL'] += $amt;
				}
				else
				{
					$serviceWiseArr[$sid][$row['DD']] += $amt;
					$serviceWiseArr[$sid]['TOTAL'] += $amt;
				}
			}

			$sql = "SELECT pur.`SERVICEID`, SUM( IF (pm.TYPE = 'DOL', AMOUNT * DOL_CONV_RATE, AMOUNT)) AS AMT, $reportPeriod( pm.ENTRY_DT ) AS DD FROM billing.PURCHASES AS pur, billing.PAYMENT_DETAIL AS pm WHERE pur.BILLID = pm.BILLID AND pm.ENTRY_DT >= '$st_date' AND pm.ENTRY_DT <= '$end_date' AND pm.STATUS = 'DONE' AND pur.SERVICEID LIKE 'NCP%' GROUP BY pur.`SERVICEID`, DD";
			$res = mysql_query_decide($sql) or die(mysql_error_js());
			while($row = mysql_fetch_array($res))
			{
				$sid = explode(',', $row['SERVICEID']);
				$sid = $sid[0];
				$amt = round(net_off_tax_calculation($row['AMT'],$end_date));

				$serviceWiseArr[$sid][$row['DD']] += $amt;
				$serviceWiseArr[$sid]['TOTAL'] += $amt;
			}

			$sql = "SELECT SERVICEID, NAME FROM billing.SERVICES ORDER BY NAME";
			$res = mysql_query_decide($sql) or die(mysql_error_js());
			while($row = mysql_fetch_array($res))
			{
				$serviceNameArr[$row['SERVICEID']] = $row['NAME'];
				if(!$serviceWiseArr[$row['SERVICEID']])
					unset($serviceNameArr[$row['SERVICEID']]);
			}
		}
		else
		{
			while($row = mysql_fetch_array($res))
			{
				$amt = round(net_off_tax_calculation($row['AMT'],$end_date));

				if(strstr($row['SERVICEID'],'P') && !strstr($row['SERVICEID'],'NCP'))
				{
					$serviceWiseArr[0][$row['DD']] += $amt;
					$serviceWiseArr[0]['TOTAL'] += $amt;
				}
				elseif(strstr($row['SERVICEID'],'C') && !strstr($row['SERVICEID'],'NCP'))
				{
					$serviceWiseArr[1][$row['DD']] += $amt;
					$serviceWiseArr[1]['TOTAL'] += $amt;
				}
				elseif(strstr($row['SERVICEID'],'X'))
				{
					$serviceWiseArr[3][$row['DD']] += $amt;
					$serviceWiseArr[3]['TOTAL'] += $amt;
				}
				else
				{
					$serviceWiseArr[5][$row['DD']] += $amt;
					$serviceWiseArr[5]['TOTAL'] += $amt;
				}
			}
			$sql = "SELECT pur.`SERVICEID`, SUM( IF (pm.TYPE = 'DOL', AMOUNT * DOL_CONV_RATE, AMOUNT)) AS AMT, $reportPeriod( pm.ENTRY_DT ) AS DD FROM billing.PURCHASES AS pur, billing.PAYMENT_DETAIL AS pm WHERE pur.BILLID = pm.BILLID AND pm.ENTRY_DT >= '$st_date' AND pm.ENTRY_DT <= '$end_date' AND pm.STATUS = 'DONE' AND pur.SERVICEID LIKE 'ES%' GROUP BY pur.`SERVICEID`, DD";
			$res = mysql_query_decide($sql) or die(mysql_error_js());
			while($row = mysql_fetch_array($res))
			{
				$sid = $row['SERVICEID'];
				$amt = round(net_off_tax_calculation($row['AMT'],$end_date));

				if(strstr($sid,'ESJ'))
				{
					$serviceWiseArr[4][$row['DD']] += $amt;
					$serviceWiseArr[4]['TOTAL'] += $amt;
				}
				else
				{
					$serviceWiseArr[2][$row['DD']] += $amt;
					$serviceWiseArr[2]['TOTAL'] += $amt;
				}
			}
			
			$sql = "SELECT pur.`SERVICEID`, SUM( IF (pm.TYPE = 'DOL', AMOUNT * DOL_CONV_RATE, AMOUNT)) AS AMT, $reportPeriod( pm.ENTRY_DT ) AS DD FROM billing.PURCHASES AS pur, billing.PAYMENT_DETAIL AS pm WHERE pur.BILLID = pm.BILLID AND pm.ENTRY_DT >= '$st_date' AND pm.ENTRY_DT <= '$end_date' AND pm.STATUS = 'DONE' AND pur.SERVICEID LIKE 'NCP%' GROUP BY pur.`SERVICEID`, DD";
			$res = mysql_query_decide($sql) or die(mysql_error_js());
			while($row = mysql_fetch_array($res))
			{
				$sid = $row['SERVICEID'];
				$amt = round(net_off_tax_calculation($row['AMT'],$end_date));

				$serviceWiseArr[6][$row['DD']] += $amt;
				$serviceWiseArr[6]['TOTAL'] += $amt;
			}
			$serviceNameArr = array("e-Rishta", "e-Value", "e-Sathi", "JS Exclusive", "JS Assisted", "Value Added Services","e-Advantage");
		}

		if($serviceWiseArr && is_array($serviceWiseArr))
		{
			foreach ($serviceWiseArr as $serviceid => $durationWiseData) 
			{
				foreach ($durationWiseData as $dd => $amt) 
				{
					$durationWiseArr[$dd] += $amt;
				}
			}
		}
		return array($serviceNameArr, $serviceWiseArr, $durationWiseArr);
	}

?>
