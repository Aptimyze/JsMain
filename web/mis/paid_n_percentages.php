<?php
//include("connect.inc");			commented by Shakti for JSIndicatorMIS

include_once("connect.inc");
include_once("../profile/pg/functions.php");
include("../profile/arrays.php");
ini_set("max_execution_time","0");
$db=connect_misdb();

if(authenticated($cid) || $JSIndicator)
{
	
	 if($outside)
        {
                $CMDGo='Y';
		if(!$today)
			$today=date("Y-m-d");
                list($year,$month,$d)=explode("-",$today);
        }


	if($CMDGo)
	{
		$smarty->assign("flag",1);

		for($j=0;$j<count($RELATIONSHIP);$j++)
		{
			$relarr[$j]=$RELATIONSHIP[$j+1];
		}

		if($fromday && $todate)
		{
			$st_date=$fromyear."-".$frommonth."-".$fromday." 00:00:00";
			$end_date=$toyear."-".$tomonth."-".$todate." 23:59:59";
		}
		else
		{
			$st_date=$fromyear."-".$frommonth."-01 00:00:00";
			$end_date=$toyear."-".$tomonth."-31 23:59:59";
                }
		// total amount of all paid members
		$sql="SELECT sum(if(TYPE='DOL',AMOUNT*DOL_CONV_RATE,AMOUNT)) as amt FROM billing.PAYMENT_DETAIL WHERE STATUS='DONE' AND ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND AMOUNT!=0";
		$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$totamt+=$row['amt'];
		}


		// count of all paid members
		// total amount of all paid members payment-wise (NRI or INDIAN)
		// total count of all paid members payment-wise (NRI or INDIAN)
		// amount of users gender-relation wise
		// total count of users gender-relation wise
		$totcnt=0;
		$totamti=0;
		$totamtn=0;
		$totcnti=0;
		$totcntn=0;
		$totamtm=0;
		$totamtf=0;
		$totcntm=0;
		$totcntf=0;
		$sql="SELECT PROFILEID,BILLID FROM billing.PURCHASES WHERE STATUS='DONE' AND ENTRY_DT BETWEEN '$st_date' AND '$end_date'";
		$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$pid = $row["PROFILEID"];
			$bid = $row["BILLID"];

			$sqlamt="SELECT sum(if(TYPE='DOL',AMOUNT*DOL_CONV_RATE,AMOUNT)) as amt,TYPE from billing.PAYMENT_DETAIL WHERE STATUS='DONE' AND billing.PAYMENT_DETAIL.ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND BILLID='$bid' AND AMOUNT!=0";
			$resamt=mysql_query_decide($sqlamt,$db) or die(mysql_error_js());
	               	if($rowamt=mysql_fetch_array($resamt))
				$amt = $rowamt['amt'];
			else
				$amt = 0;
		
			if($amt)
			{
			$totcnt++;
			$sqljap="SELECT COUNTRY_RES,GENDER,RELATION,MTONGUE from newjs.JPROFILE WHERE PROFILEID='$pid' and activatedKey=1";
			$resjap=mysql_query_decide($sqljap,$db) or die(mysql_error_js());
                        if($rowjap=mysql_fetch_array($resjap))
			{
				if($amt && $rowjap['MTONGUE']) 
				{
					$communityArr[$rowjap['MTONGUE']]['cnt']++; 
					$communityArr[$rowjap['MTONGUE']]['amt'] += $amt; 
				}

				if($rowjap['COUNTRY_RES']=='51' && !in_array($pid,$ind_proarr))
                                        $ind_proarr[]=$pid;
                                if($rowjap['COUNTRY_RES']!='51' && !in_array($pid,$nri_proarr))
                                        $nri_proarr[]=$pid;

                	        if($rowamt['TYPE']=='RS')
				{
                        		$totamti+=$amt;
					$totcnti++;
				}
	                        else
				{
        	                	$totamtn+=$amt;
					$totcntn++;
				}
				$j=$rowjap['RELATION']-1;
	                        if($rowjap['GENDER']=='M')
        	                {
                	                $totamtm+=$amt;
                        	        $relamtm[$j]+=round($amt,0);
	                                $relamtm_net_off_tax_amount[$j] += round(net_off_tax_calculation($amt,$end_date),0);
					$totcntm++;
					$relcntm[$j]++;
        	                }
                	        if($rowjap['GENDER']=='F')
                        	{
                                	$totamtf+=$amt;
	                                $relamtf[$j]+=round($amt,0);
                	                $relamtf_net_off_tax_amount[$j] += round(net_off_tax_calculation($amt,$end_date),0);
					$totcntf++;
					$relcntf[$j]++;
                        	}
                	}
			}
		}

                if($JSIndicator==1)
                {
                        return;
                }

		if($totcnt)
		{
			$percnti=$totcnti/$totcnt * 100;
			$percntn=$totcntn/$totcnt * 100;
			$percnti=round($percnti,1);
			$percntn=round($percntn,1);
		}
		if($totamt)
		{
			$peramti=$totamti/$totamt * 100;
			$peramtn=$totamtn/$totamt * 100;
			$peramti=round($peramti,1);
			$peramtn=round($peramtn,1);
		}

		if($totcnt)
		{
			$percntm=$totcntm/$totcnt * 100;
			$percntf=$totcntf/$totcnt * 100;
			$percntm=round($percntm,1);
			$percntf=round($percntf,1);
		}
		if($totamt)
		{
			$peramtm=$totamtm/$totamt * 100;
			$peramtf=$totamtf/$totamt * 100;
			$peramtm=round($peramtm,1);
			$peramtf=round($peramtf,1);
		}

		for($j=0;$j<count($relarr);$j++)
		{
			if($totcntm)
			{
				$relpercntm[$j]=$relcntm[$j]/$totcntm * 100;
				$relpercntm[$j]=round($relpercntm[$j],1);
			}
			if($totcntf)
			{
				$relpercntf[$j]=$relcntf[$j]/$totcntf * 100;
				$relpercntf[$j]=round($relpercntf[$j],1);
			}
			if($totamtm)
			{
				$relperamtm[$j]=$relamtm[$j]/$totamtm * 100;
				$relperamtm[$j]=round($relperamtm[$j],1);
			}
			if($totamtf)
			{
				$relperamtf[$j]=$relamtf[$j]/$totamtf * 100;
				$relperamtf[$j]=round($relperamtf[$j],1);
			}
		}



		// amount of indian cities : top 40
		$ind_prostr = implode(",",$ind_proarr);
		if($ind_prostr)
		{
			$sql="SELECT sum(if(TYPE='DOL',AMOUNT*DOL_CONV_RATE,AMOUNT)) as amt,CITY_RES FROM billing.PAYMENT_DETAIL,newjs.JPROFILE WHERE STATUS='DONE' AND billing.PAYMENT_DETAIL.ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND billing.PAYMENT_DETAIL.PROFILEID=newjs.JPROFILE.PROFILEID AND COUNTRY_RES=51 AND newjs.JPROFILE.PROFILEID IN ($ind_prostr) AND AMOUNT!=0 GROUP BY CITY_RES ORDER BY amt DESC LIMIT 40";
			$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
			while($row=mysql_fetch_array($res))
			{
				if(is_array($icityarr))
				{
					if(!in_array($row['CITY_RES'],$icityarr))
					{
						$icityarr[]=$row['CITY_RES'];
					}
				}
				else
				{
					$icityarr[]=$row['CITY_RES'];
				}	

				$i=array_search($row['CITY_RES'],$icityarr);
				$ind[$i]["totamt"]=round($row['amt'],0);
				//added by sriram.
				$ind[$i]["totamt_net_off_tax_amount"] = round(net_off_tax_calculation($row['amt'],$end_date),0);
				$indtotamt+=$row['amt'];
			}
		}
	
		if($icityarr)
			$icitystr="'".implode("','",$icityarr)."'";

		// count of indian cities : top 40
		if($icitystr && $ind_prostr)
		{
			$sql="SELECT count(DISTINCT billing.PAYMENT_DETAIL.BILLID) as cnt,CITY_RES FROM billing.PAYMENT_DETAIL,newjs.JPROFILE WHERE STATUS='DONE' AND billing.PAYMENT_DETAIL.ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND billing.PAYMENT_DETAIL.PROFILEID=newjs.JPROFILE.PROFILEID AND COUNTRY_RES=51 AND CITY_RES IN ($icitystr) AND newjs.JPROFILE.PROFILEID IN ($ind_prostr) AND AMOUNT!=0 GROUP BY CITY_RES ORDER BY cnt DESC";
			$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
			while($row=mysql_fetch_array($res))
			{
				if(is_array($icityarr))
	                        {
        	                        if(!in_array($row['CITY_RES'],$icityarr))
                	                {
                        	                $icityarr[]=$row['CITY_RES'];
                                	}
	                        }
        	                else
                	        {
                        	        $icityarr[]=$row['CITY_RES'];
                        	}

	                        $i=array_search($row['CITY_RES'],$icityarr);
        	                $ind[$i]["totcnt"]=$row['cnt'];
				$indtotcnt+=$row['cnt'];
			}
		}

		// amount of all countries apart from india : top 10
		$nri_prostr = implode(",",$nri_proarr);
		if($nri_prostr)
		{
			$sql="SELECT sum(if(TYPE='DOL',AMOUNT*DOL_CONV_RATE,AMOUNT)) as amt,COUNTRY_RES FROM billing.PAYMENT_DETAIL,newjs.JPROFILE WHERE STATUS='DONE' AND billing.PAYMENT_DETAIL.ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND billing.PAYMENT_DETAIL.PROFILEID=newjs.JPROFILE.PROFILEID AND COUNTRY_RES<>51 AND newjs.JPROFILE.PROFILEID IN ($nri_prostr) AND AMOUNT!=0 GROUP BY COUNTRY_RES ORDER BY amt DESC LIMIT 10";
			$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
			while($row=mysql_fetch_array($res))
			{
				if(is_array($ctryarr))
                	        {
                        	        if(!in_array($row['COUNTRY_RES'],$ctryarr))
                                	{
                                        	$ctryarr[]=$row['COUNTRY_RES'];
	                                }
        	                }
                	        else
                        	{
                                	$ctryarr[]=$row['COUNTRY_RES'];
	                        }

        	                $i=array_search($row['COUNTRY_RES'],$ctryarr);
				$ctry[$i]["totamt"]+=round($row['amt'],0);
				//added by sriram
		                $ctry[$i]["totamt_net_off_tax_amount"] += round(net_off_tax_calculation($row['amt'],$end_date),0);
				$ctrtotamt+=$row['amt'];
			}
		}

		if($ctryarr)
			$ctrystr="'".implode("','",$ctryarr)."'";

		// count of all countries apart from india : top 10
		if($ctrystr && $nri_prostr)
		{
			$sql="SELECT count(DISTINCT billing.PAYMENT_DETAIL.BILLID) as cnt,COUNTRY_RES FROM billing.PAYMENT_DETAIL,newjs.JPROFILE WHERE STATUS='DONE' AND billing.PAYMENT_DETAIL.ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND billing.PAYMENT_DETAIL.PROFILEID=newjs.JPROFILE.PROFILEID AND COUNTRY_RES<>51 AND COUNTRY_RES IN ($ctrystr) AND newjs.JPROFILE.PROFILEID IN ($nri_prostr) AND AMOUNT!=0 GROUP BY COUNTRY_RES ORDER BY cnt DESC";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
			while($row=mysql_fetch_array($res))
			{
				if(is_array($ctryarr))
                	        {
                        	        if(!in_array($row['COUNTRY_RES'],$ctryarr))
                                	{
                                        	$ctryarr[]=$row['COUNTRY_RES'];
	                                }
        	                }
                	        else
                        	{
                                	$ctryarr[]=$row['COUNTRY_RES'];
	                        }

        	                $i=array_search($row['COUNTRY_RES'],$ctryarr);
                	        $ctry[$i]["totcnt"]+=$row['cnt'];
				$ctrtotcnt+=$row['cnt'];
			}
		}	

		for($j=0;$j<count($icityarr);$j++)
		{
			if($totcnt)
			{
				$ind[$j]["percnt"]=$ind[$j]["totcnt"]/$totcnt * 100;
				$ind[$j]["percnt"]=round($ind[$j]["percnt"],1);
			}
			if($totamt)
			{
				$ind[$j]["peramt"]=$ind[$j]["totamt"]/$totamt * 100;
				$ind[$j]["peramt"]=round($ind[$j]["peramt"],1);
			}
		}

		if($totcnt)
		{
			$indpercnt=$indtotcnt/$totcnt * 100;
			$indpercnt=round($indpercnt,1);
		}
		if($totamt)
		{
			$indperamt=$indtotamt/$totamt * 100;
			$indperamt=round($indperamt,1);
		}

		for($j=0;$j<count($ctryarr);$j++)
		{
			if($totcnt)
			{
				$ctry[$j]["percnt"]=$ctry[$j]["totcnt"]/$totcnt * 100;
				$ctry[$j]["percnt"]=round($ctry[$j]["percnt"],1);
			}
			if($totamt)
			{
				$ctry[$j]["peramt"]=$ctry[$j]["totamt"]/$totamt * 100;
				$ctry[$j]["peramt"]=round($ctry[$j]["peramt"],1);
			}
		}

		if($totcnt)
		{
			$ctrpercnt=$ctrtotcnt/$totcnt * 100;
			$ctrpercnt=round($ctrpercnt,1);
		}
		if($totamt)
		{
			$ctrperamt=$ctrtotamt/$totamt * 100;
			$ctrperamt=round($ctrperamt,1);
		}

		for($j=0;$j<count($icityarr);$j++)
		{
			$sql="SELECT LABEL FROM newjs.CITY_NEW WHERE VALUE='$icityarr[$j]'";
			$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
			$row=mysql_fetch_array($res);
			$icityarr[$j]=$row['LABEL'];
		}

		for($j=0;$j<count($ctryarr);$j++)
		{
			$sql="SELECT LABEL FROM newjs.COUNTRY WHERE VALUE='$ctryarr[$j]'";
			$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
			$row=mysql_fetch_array($res);
			$ctryarr[$j]=$row['LABEL'];
		}

		// Top 10 communities (MTONGUE) - start
		$sql = "SELECT VALUE, SMALL_LABEL FROM newjs.MTONGUE";
		$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
		while ($row = mysql_fetch_array($res)) 
		{
			if(strstr($row['SMALL_LABEL'], "Hindi"))
				$mtongueArr[$row['VALUE']] = "Hindi-All";
			else
				$mtongueArr[$row['VALUE']] = $row['SMALL_LABEL'];
		}
		unset($res);

		foreach($communityArr as $mtongue => $val)
		{
			$amtArr[$mtongueArr[$mtongue]] += $val['amt']; 
			$temp[$mtongueArr[$mtongue]]['amt'] += $val['amt']; 
			$temp[$mtongueArr[$mtongue]]['cnt'] += $val['cnt']; 
		}
		unset($communityArr);
		arsort($amtArr);

		$i=0;
		foreach($amtArr as $k => $v)
		{
			if($i>=10) 
				break;
			$communityArr[$i]['MTONGUE'] = $k;
			$communityArr[$i]['amt'] = $temp[$k]['amt'];
			$communityArr[$i]['cnt'] = $temp[$k]['cnt'];
			$i++;
		}
		unset($amtArr);
		unset($temp);

		$totamt_net_off_tax_amount = net_off_tax_calculation($totamt,$end_date);
		foreach ($communityArr as $k => $val) 
		{
	            $communityArr[$k]["net_off_tax_amt"] = round(net_off_tax_calculation($val['amt'],$end_date),0);
	            
	            if($totcnt)
	            	$communityArr[$k]['percnt'] = round($val['cnt'] / $totcnt * 100, 1);
	            if($totamt)
	            	$communityArr[$k]['peramt'] = round($val['amt'] / $totamt * 100, 1);

			$totcommArr['totcnt'] += $val['cnt']; 
			$totcommArr['totamt'] += $val['amt']; 
	            $totcommArr["net_off_tax_totamt"] += $communityArr[$k]["net_off_tax_amt"];
		}
            if($totcnt)
            	$totcommArr['totpercnt'] = round($totcommArr['totcnt'] / $totcnt * 100, 1);
            if($totamt)
            	$totcommArr['totperamt'] = round($totcommArr['totamt'] / $totamt * 100, 1);
		// Top 10 communities (MTONGUE) - end

		/*added by sriram to show net off tax amount*/
		$smarty->assign("totamt_net_off_tax_amount",round($totamt_net_off_tax_amount,0));
		$totamtm_net_off_tax_amount = net_off_tax_calculation($totamtm,$end_date);
		$smarty->assign("totamtm_net_off_tax_amount",round($totamtm_net_off_tax_amount,0));
		$totamtf_net_off_tax_amount = net_off_tax_calculation($totamtf,$end_date);
		$smarty->assign("totamtf_net_off_tax_amount",round($totamtf_net_off_tax_amount,0));
		$totamti_net_off_tax_amount = net_off_tax_calculation($totamti,$end_date);
		$smarty->assign("totamti_net_off_tax_amount",round($totamti_net_off_tax_amount,0));
		$totamtn_net_off_tax_amount = net_off_tax_calculation($totamtn,$end_date);
		$smarty->assign("totamtn_net_off_tax_amount",round($totamtn_net_off_tax_amount,0));
		$indtotamt_net_off_tax_amount = net_off_tax_calculation($indtotamt,$end_date);
		$smarty->assign("indtotamt_net_off_tax_amount",round($indtotamt_net_off_tax_amount,0));
		$ctrtotamt_net_off_tax_amount = net_off_tax_calculation($ctrtotamt,$end_date);
		$smarty->assign("ctrtotamt_net_off_tax_amount",round($ctrtotamt_net_off_tax_amount,0));
		/*added by sriram to show net off tax amount*/

		$smarty->assign("communityArr",$communityArr);
		$smarty->assign("totcommArr",$totcommArr);
		$smarty->assign("ctrtotcnt",$ctrtotcnt);
		$smarty->assign("ctrtotamt",round($ctrtotamt,0));
		$smarty->assign("ctrpercnt",$ctrpercnt);
		$smarty->assign("ctrperamt",$ctrperamt);
		$smarty->assign("us",$us);
		$smarty->assign("ind",$ind);
		$smarty->assign("indtotcnt",$indtotcnt);
		$smarty->assign("indtotamt",round($indtotamt,0));
		$smarty->assign("indpercnt",$indpercnt);
		$smarty->assign("indperamt",$indperamt);
		$smarty->assign("totamt",round($totamt,0));
		$smarty->assign("totcnt",$totcnt);
		$smarty->assign("totamti",round($totamti,0));
		$smarty->assign("totcnti",$totcnti);
		$smarty->assign("totamtn",round($totamtn,0));
		$smarty->assign("totcntn",$totcntn);
		$smarty->assign("peramti",$peramti);
		$smarty->assign("percnti",$percnti);
		$smarty->assign("peramtn",$peramtn);
		$smarty->assign("percntn",$percntn);
		$smarty->assign("cnt",$cnt);
		$smarty->assign("cntg",$cntg);
		$smarty->assign("icityarr",$icityarr);
		$smarty->assign("ucityarr",$ucityarr);
		$smarty->assign("ctryarr",$ctryarr);
		$smarty->assign("ctry",$ctry);
		$smarty->assign("totamtf",round($totamtf,0));
		$smarty->assign("totamtm",round($totamtm,0));
		$smarty->assign("totcntf",$totcntf);
		$smarty->assign("totcntm",$totcntm);
		$smarty->assign("peramtf",$peramtf);
		$smarty->assign("peramtm",$peramtm);
		$smarty->assign("percntf",$percntf);
		$smarty->assign("percntm",$percntm);
		$smarty->assign("RELATIONARR",$relarr);
		$smarty->assign("relamtm",$relamtm);
		$smarty->assign("relamtm_net_off_tax_amount",$relamtm_net_off_tax_amount);
		$smarty->assign("relamtf",$relamtf);
		$smarty->assign("relamtf_net_off_tax_amount",$relamtf_net_off_tax_amount);
		$smarty->assign("relcntm",$relcntm);
		$smarty->assign("relcntf",$relcntf);
		$smarty->assign("relperamtm",$relperamtm);
		$smarty->assign("relperamtf",$relperamtf);
		$smarty->assign("relpercntm",$relpercntm);
		$smarty->assign("relpercntf",$relpercntf);

		$smarty->assign("day",$day);
		$smarty->assign("year",$year);
		$smarty->assign("month",$month);
		$smarty->assign("cid",$cid);
                $smarty->display("paid_n_percentages.htm");
	}
	else
	{
		for($i=0;$i<31;$i++)
		{
			$ddarr[$i]=$i+1;
		}

		for($i=0;$i<12;$i++)
                {
                        $mmarr[$i]=$i+1;
                }

                for($i=2004;$i<=date("Y");$i++)
{
        $yyarr[$i-2004]=$i;
}

		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("mmarr",$mmarr);
                $smarty->assign("yyarr",$yyarr);
		$smarty->assign("cid",$cid);
		$smarty->display("paid_n_percentages.htm");
	}
}
else
{
	$smarty->assign("user",$username);
	$smarty->display("jsconnectError.tpl");
}
?>
