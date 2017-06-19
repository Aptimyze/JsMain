<?php
//include("connect.inc");			commented by Shakti for JSIndicatorMIS, 28 November, 2005

include_once("../profile/pg/functions.php");    // included for dollar conversion rate
include_once("connect.inc");

$db=connect_misdb();
$db2=connect_master();

$data=authenticated($checksum);
if(isset($data) || $JSIndicator)
{
	if($outside)
	{
		$CMDGo='Y';
		$vtype='D';
		$branch='ALL';
		if(!$today)
			$today=date("Y-m-d");
		list($dyear,$dmonth,$d)=explode("-",$today);
	}

	if($CMDGo)
	{
		$flag=1;
                $mmarr=array('Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Jan','Feb','Mar');
		$qtrarr=array('Apr-Jun','Jul-Sep','Oct-Dec','Jan-Mar');
		if($branch!="ALL")
		{
			$bflag='N';
			if($vtype=='Q')
			{
				unset($amt);
				unset($amt1);
				unset($tota);
				unset($totb);
				$qflag=1;
				$qyearp1=$qyear+1;
				$sql="SELECT sum(if(billing.PAYMENT_DETAIL.TYPE='DOL',billing.PAYMENT_DETAIL.AMOUNT*billing.PAYMENT_DETAIL.DOL_CONV_RATE,billing.PAYMENT_DETAIL.AMOUNT)) as amt,QUARTER(billing.PAYMENT_DETAIL.ENTRY_DT) as qtr,billing.PURCHASES.WALKIN as eb FROM billing.PAYMENT_DETAIL,billing.PURCHASES WHERE billing.PAYMENT_DETAIL.STATUS='DONE' AND billing.PAYMENT_DETAIL.ENTRY_DT BETWEEN '$qyear-04-01 00:00:00' AND '$qyearp1-03-31 23:59:59' AND billing.PURCHASES.CENTER='$branch' AND billing.PURCHASES.BILLID=billing.PAYMENT_DETAIL.BILLID GROUP BY QUARTER(billing.PAYMENT_DETAIL.ENTRY_DT),eb"; 

				$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
				while($row=mysql_fetch_array($res))
				{
					$qtr=$row['qtr']-1;
					if($qtr<1)
					{
						$qtr+=3;
					}
					else
					{
						$qtr-=1;
					}
					if($branch=="HO")
					{
						if($row['eb']=="OFFLINE")
						{
							$amt[$qtr]["rj"]=$row['amt'];
							$tot["rj"]+=$row['amt'];//$amt[$qtr]["rj"];
							$amttot[$qtr]+=$row['amt'];//$amt[$qtr]["rj"];
							$hotot+=$row['amt'];//$tot["rj"];
						}
						elseif($row['eb']=="ONLINE")
						{
							$amt[$qtr]["ol"]=$row['amt'];
							$tot["ol"]+=$row['amt'];//$amt[$qtr]["ol"];
							$amttot[$qtr]+=$row['amt'];//$amt[$qtr]["ol"];
							$hotot+=$row['amt'];//$tot["ol"];
						}
						elseif($row['eb']=="ARAMEX")
						{
							$amt[$qtr]["ar"]=$row['amt'];//$row['amt'];
							$tot["ar"]+=$row['amt'];//$amt[$qtr]["ar"];
							$amttot[$qtr]+=$row['amt'];//$amt[$qtr]["ar"];
							$hotot+=$row['amt'];//$tot["ar"];
						}
						elseif($row['eb']=="BANK_TSFR")
						{
							$amt[$qtr]["bt"]=$row['amt'];//$row['amt'];
							$tot["bt"]+=$row['amt'];//$amt[$qtr]["ar"];
							$amttot[$qtr]+=$row['amt'];//$amt[$qtr]["ar"];
							$hotot+=$row['amt'];//$tot["ar"];
						}
					}
					else
					{
						$amt[$qtr]+=$row['amt'];
						$amt2a[$qtr]=$row['amt'];
						$tot+=$amt2a[$qtr];
					}
				}
				$smarty->assign("amttot",$amttot);
				$smarty->assign("hotot",$hotot);
			}
			elseif($vtype=='M')
			{
				unset($amt);
				unset($amt1);
				unset($tota);
				unset($totb);
				$mflag=1;
				$myearp1=$myear+1;
				if($pay_exec=="Selected")
				{
					$sql="SELECT sum(if(billing.PAYMENT_DETAIL.TYPE='DOL',billing.PAYMENT_DETAIL.AMOUNT*billing.PAYMENT_DETAIL.DOL_CONV_RATE,billing.PAYMENT_DETAIL.AMOUNT)) as amt,month(billing.PAYMENT_DETAIL.ENTRY_DT) as mm,billing.PURCHASES.WALKIN as eb FROM billing.PAYMENT_DETAIL,billing.PURCHASES WHERE billing.PAYMENT_DETAIL.STATUS='DONE' AND billing.PAYMENT_DETAIL.ENTRY_DT BETWEEN '$myear-04-01 00:00:00' AND '$myearp1-03-31 23:59:59' AND billing.PURCHASES.CENTER='$branch' AND billing.PURCHASES.BILLID=billing.PAYMENT_DETAIL.BILLID GROUP BY month(billing.PAYMENT_DETAIL.ENTRY_DT),eb";

					$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
					while($row=mysql_fetch_array($res))
					{
						$mm=$row['mm'];
						if($mm<=3)
						{
							$mm+=8;
						}
						else
						{
							$mm-=4;
						}
						if($branch=="HO")
						{
							if($row['eb']=="OFFLINE")
							{
								$amt[$mm]["rj"]=$row['amt'];
								$tot["rj"]+=$row['amt'];//$amt[$mm]["rj"];
								$amttot[$mm]+=$row['amt'];//$amt[$mm]["rj"];
								$hotot+=$row['amt'];//$tot["rj"];
							}
							elseif($row['eb']=="ONLINE")
							{
								$amt[$mm]["ol"]=$row['amt'];
								$tot["ol"]+=$row['amt'];//$amt[$mm]["ol"];
								$amttot[$mm]+=$row['amt'];//$amt[$mm]["ol"];
								$hotot+=$row['amt'];//$tot["ol"];
							}
							elseif($row['eb']=="ARAMEX")
							{
								$amt[$mm]["ar"]=$row['amt'];
								$tot["ar"]+=$row['amt'];//$amt[$mm]["ar"];
								$amttot[$mm]+=$row['amt'];//$amt[$mm]["ar"];
								$hotot+=$row['amt'];//$tot["ar"];
							}
							elseif($row['eb']=="BANK_TSFR")
							{
								$amt[$mm]["bt"]=$row['amt'];
								$tot["bt"]+=$row['amt'];//$amt[$mm]["ar"];
								$amttot[$mm]+=$row['amt'];//$amt[$mm]["ar"];
								$hotot+=$row['amt'];//$tot["ar"];
							}
						}
						else
						{
							$amt[$mm]+=$row['amt'];
							$amt2a[$mm]=$row['amt'];
							$tot+=$amt2a[$mm];
						}
					}
				}
				else
				{
					if($pay_exec=='P')
					{
						unset($amt);
						unset($amt1);
						unset($tota);
						unset($totb);
						$pflag=1;
						$modearr=array('CASH','CHEQUE','DD','TT','ONLINE','OTHER','CREDIT','CCOFFLINE','BANK_TRSFR_ONLINE');
						$year=$myear;
						$yearp1=$year+1;

		//				$sql="SELECT sum(if(billing.PAYMENT_DETAIL.TYPE='DOL',billing.PAYMENT_DETAIL.AMOUNT*$DOL_CONV_RATE,billing.PAYMENT_DETAIL.AMOUNT)) as amt,billing.PAYMENT_DETAIL.MODE as mode FROM billing.PAYMENT_DETAIL,billing.PURCHASES WHERE billing.PAYMENT_DETAIL.STATUS='DONE' AND billing.PAYMENT_DETAIL.ENTRY_DT BETWEEN '$year-04-01' AND '$yearp1-03-31' AND billing.PURCHASES.CENTER='$branch' AND billing.PURCHASES.BILLID=billing.PAYMENT_DETAIL.BILLID GROUP BY billing.PAYMENT_DETAIL.MODE";

						$sql="SELECT sum(if(billing.PAYMENT_DETAIL.TYPE='DOL',billing.PAYMENT_DETAIL.AMOUNT*billing.PAYMENT_DETAIL.DOL_CONV_RATE,billing.PAYMENT_DETAIL.AMOUNT)) as amt,month(billing.PAYMENT_DETAIL.ENTRY_DT) as mm,billing.PAYMENT_DETAIL.MODE as mode FROM billing.PAYMENT_DETAIL,billing.PURCHASES WHERE billing.PAYMENT_DETAIL.STATUS='DONE' AND billing.PAYMENT_DETAIL.ENTRY_DT BETWEEN '$year-04-01 00:00:00' AND '$yearp1-03-31 23:59:59' AND billing.PURCHASES.CENTER='$branch' AND billing.PURCHASES.BILLID=billing.PAYMENT_DETAIL.BILLID GROUP BY mm,mode ORDER BY mode";

						$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
						while($row=mysql_fetch_array($res))
						{
							$mode=$row['mode'];
							$i=array_search($mode,$modearr);
							$mm=$row['mm'];
							if($mm<=3)
							{
								$mm+=8;
							}
							else
							{
								$mm-=4;
							}
							$amt[$i][$mm]=$row['amt'];
							$amt1[$mm][$i]=$row['amt'];
							$tota[$i]+=$amt[$i][$mm];
							$totb[$mm]+=$amt1[$mm][$i];
						}
					}
					elseif($pay_exec=='E')
					{
						unset($amt);
						unset($amt1);
						unset($tota);
						unset($totb);
						$eflag=1;
						$year=$myear;
						$yearp1=$year+1;
						if($branch=="HO")
						{
							$userarr[]="ONLINE";
							$userarr[]="OFFLINE";
							$userarr[]="ARAMEX";
							$userarr[]="BANK_TSFR";
						}
						else
						{
							$sql_e="SELECT USERNAME FROM jsadmin.PSWRDS WHERE PRIVILAGE LIKE '%BU%' AND CENTER='$branch'";
							$res_e=mysql_query_decide($sql_e,$db) or die("$sql_e".mysql_error_js($db));
							while($row_e=mysql_fetch_array($res_e))
							{
								$userarr[]=$row_e['USERNAME'];
							}
						}

		//				$sql="SELECT sum(if(billing.PAYMENT_DETAIL.TYPE='DOL',billing.PAYMENT_DETAIL.AMOUNT*$DOL_CONV_RATE,billing.PAYMENT_DETAIL.AMOUNT)) as amt,billing.PURCHASES.WALKIN as walkin FROM billing.PAYMENT_DETAIL,billing.PURCHASES WHERE billing.PAYMENT_DETAIL.STATUS='DONE' AND billing.PAYMENT_DETAIL.ENTRY_DT BETWEEN '$year-04-01' AND '$yearp1-03-31' AND billing.PURCHASES.CENTER='$branch' AND billing.PURCHASES.BILLID=billing.PAYMENT_DETAIL.BILLID GROUP BY billing.PURCHASES.WALKIN";

						$sql="SELECT sum(if(billing.PAYMENT_DETAIL.TYPE='DOL',billing.PAYMENT_DETAIL.AMOUNT*billing.PAYMENT_DETAIL.DOL_CONV_RATE,billing.PAYMENT_DETAIL.AMOUNT)) as amt,month(billing.PAYMENT_DETAIL.ENTRY_DT) as mm,billing.PURCHASES.WALKIN as walkin FROM billing.PAYMENT_DETAIL,billing.PURCHASES WHERE billing.PAYMENT_DETAIL.STATUS='DONE' AND billing.PAYMENT_DETAIL.ENTRY_DT BETWEEN '$year-04-01 00:00:00' AND '$yearp1-03-31 23:59:59' AND billing.PURCHASES.CENTER='$branch' AND billing.PURCHASES.BILLID=billing.PAYMENT_DETAIL.BILLID GROUP BY mm,walkin";

						$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
						while($row=mysql_fetch_array($res))
						{
							$walkin=$row['walkin'];
							$i=array_search($walkin,$userarr);
							$mm=$row['mm'];
							if($mm<=3)
							{
								$mm+=8;
							}
							else
							{
								$mm-=4;
							}
							$amt[$i][$mm]=$row['amt'];
							$amt1[$mm][$i]=$row['amt'];
							$tota[$i]+=$amt[$i][$mm];
							$totb[$mm]+=$amt1[$mm][$i];
						}
					}
				}
				$smarty->assign("amttot",$amttot);
				$smarty->assign("hotot",$hotot);
			}
			elseif($vtype=='D')
			{
				unset($amt);
				unset($amt1);
				unset($tota);
				unset($totb);
				$dflag=1;

				for($i=0;$i<31;$i++)
				{
					$ddarr[$i]=$i+1;
				}

				$sql="SELECT sum(if(billing.PAYMENT_DETAIL.TYPE='DOL',billing.PAYMENT_DETAIL.AMOUNT*billing.PAYMENT_DETAIL.DOL_CONV_RATE,billing.PAYMENT_DETAIL.AMOUNT)) as amt,DAYOFMONTH(billing.PAYMENT_DETAIL.ENTRY_DT) as dd,billing.PURCHASES.WALKIN as eb FROM billing.PAYMENT_DETAIL,billing.PURCHASES WHERE billing.PAYMENT_DETAIL.STATUS='DONE' AND billing.PAYMENT_DETAIL.ENTRY_DT BETWEEN '$dyear-$dmonth-01 00:00:00' AND '$dyear-$dmonth-31 23:59:59' AND billing.PURCHASES.CENTER='$branch' AND billing.PURCHASES.BILLID=billing.PAYMENT_DETAIL.BILLID GROUP BY DAYOFMONTH(billing.PAYMENT_DETAIL.ENTRY_DT),eb";

				$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
				while($row=mysql_fetch_array($res))
				{
					$dd=$row['dd']-1;
					if($branch=="HO")
					{
						if($row['eb']=="OFFLINE")
						{
							$amt[$dd]["rj"]=$row['amt'];
							$tot["rj"]+=$row['amt'];//$amt[$dd]["rj"];
							$amttot[$dd]+=$row['amt'];//$amt[$dd]["rj"];
							$hotot+=$row['amt'];//$tot["rj"];
						}
						elseif($row['eb']=="ONLINE")
						{
							$amt[$dd]["ol"]=$row['amt'];
							$tot["ol"]+=$row['amt'];//$amt[$dd]["ol"];
							$amttot[$dd]+=$row['amt'];//$amt[$dd]["ol"];
							$hotot+=$row['amt'];//$tot["ol"];
						}
						elseif($row['eb']=="ARAMEX")
						{
							$amt[$dd]["ar"]=$row['amt'];
							$tot["ar"]+=$row['amt'];//$amt[$dd]["ar"];
							$amttot[$dd]+=$row['amt'];//$amt[$dd]["ar"];
							$hotot+=$row['amt'];//$tot["ar"];
						}
						elseif($row['eb']=="BANK_TSFR")
						{
							$amt[$dd]["bt"]=$row['amt'];
							$tot["bt"]+=$row['amt'];//$amt[$dd]["ar"];
							$amttot[$dd]+=$row['amt'];//$amt[$dd]["ar"];
							$hotot+=$row['amt'];//$tot["ar"];
						}
					}
					else
					{
						$amt[$dd]+=$row['amt'];
						$amt2a[$dd]=$row['amt'];
						$tot+=$amt2a[$dd];
					}
				}
				$smarty->assign("amttot",$amttot);
				$smarty->assign("hotot",$hotot);
			}
		}
		else
		{
			$bflag='A';
			$sql_b="SELECT NAME FROM billing.BRANCHES";
			$res_b=mysql_query_decide($sql_b,$db) or die("$sql_b".mysql_error_js($db));
			while($row_b=mysql_fetch_array($res_b))
			{
				$brancharr[]=strtoupper($row_b['NAME']);
			}
			$brancharr[]="MISC-REVENUE-WITHOUT-TAX";
			$brancharr[]="REVENUE-WITHOUT-TAX";
			$brancharr[]="MISC-REVENUE-RES";
			if($vtype=='Q')
			{
				unset($amt);
				unset($amt1);
				unset($tota);
				unset($totb);
				$qflag=1;
				$qyearp1=$qyear+1;
				$sql="SELECT sum(if(billing.PAYMENT_DETAIL.TYPE='DOL',billing.PAYMENT_DETAIL.AMOUNT*billing.PAYMENT_DETAIL.DOL_CONV_RATE,billing.PAYMENT_DETAIL.AMOUNT)) as amt,QUARTER(billing.PAYMENT_DETAIL.ENTRY_DT) as qtr,billing.PURCHASES.CENTER as center,billing.PURCHASES.WALKIN as eb FROM billing.PAYMENT_DETAIL,billing.PURCHASES WHERE billing.PAYMENT_DETAIL.STATUS='DONE' AND billing.PAYMENT_DETAIL.ENTRY_DT BETWEEN '$qyear-04-01 00:00:00' AND '$qyearp1-03-31 23:59:59' AND billing.PURCHASES.BILLID=billing.PAYMENT_DETAIL.BILLID GROUP BY QUARTER(billing.PAYMENT_DETAIL.ENTRY_DT),center,eb ORDER BY center,eb"; 

				$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
				if($row=mysql_fetch_array($res))
				{
					do
					{
						$center=strtoupper($row['center']);
						$k=array_search($center,$brancharr);
						$qtr=$row['qtr']-1;
						if($qtr<1)
						{
							$qtr+=3;
						}
						else
						{
							$qtr-=1;
						}
						if($center=='HO')
						{
							if($row['eb']=='OFFLINE')
							{
								$amt[$k][$qtr]["rj"]=$row['amt'];
								$tota[$k]["rj"]+=$row['amt'];//$amt[$k][$qtr]["rj"];
							}
							elseif($row['eb']=='ONLINE')
							{
								$amt[$k][$qtr]["ol"]=$row['amt'];
								$tota[$k]["ol"]+=$row['amt'];//$amt[$k][$qtr]["ol"];
							}
							elseif($row['eb']=='ARAMEX')
							{
								$amt[$k][$qtr]["ar"]=$row['amt'];
								$tota[$k]["ar"]+=$row['amt'];//$amt[$k][$qtr]["ar"];
							}
							elseif($row['eb']=='BANK_TSFR')
							{
								$amt[$k][$qtr]["bt"]=$row['amt'];
								$tota[$k]["bt"]+=$row['amt'];//$amt[$k][$qtr]["ar"];
							}
							$amt1[$qtr][$k]=$row['amt'];
							$totb[$qtr]+=$row['amt'];//$amt1[$qtr][$k];
						}
						else
						{
							$amt[$k][$qtr]+=$row['amt'];
							$amt2a[$k][$qtr]=$row['amt'];
							$amt1[$qtr][$k]=$row['amt'];
							$tota[$k]+=$row['amt'];//$amt2a[$k][$qtr];
							$totb[$qtr]+=$row['amt'];//$amt1[$qtr][$k];
						}
					}while($row=mysql_fetch_array($res));
				}
/*************************************Code Portion for Misc Rev Resources*************************************************/
				$center="MISC-REVENUE-RES";
				$k=array_search($center,$brancharr);
			
				$sql="SELECT sum(if(TYPE='DOL',AMOUNT*DOL_CONV_RATE,AMOUNT)) as amt,QUARTER(ENTRY_DT) as qtr from billing.REV_PAYMENT WHERE STATUS='DONE' AND ENTRY_DT BETWEEN '$qyear-04-01 00:00:00' AND '$qyearp1-03-31 23:59:59' GROUP BY qtr";

				$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
				while($row=mysql_fetch_array($res))
				{
					$qtr=$row['qtr']-1;
					if($qtr<1)
					{
						$qtr+=3;
					}
					else
					{
						$qtr-=1;
					}
					$amt[$k][$qtr]+=$row['amt'];
					$tota[$k]+=$row['amt'];//$amt2a[$k][$qtr];
					$totb[$qtr]+=$row['amt'];//$amt1[$qtr][$k];
				}
/*************************************End of code portion**************************************************************/
				$center="REVENUE-WITHOUT-TAX";
				$k=array_search($center,$brancharr);
				$sql="SELECT sum(IF (TYPE = 'DOL', AMOUNT *DOL_CONV_RATE / ( 1+ TAX_RATE /100 ), AMOUNT / ( 1+ TAX_RATE /100 ) )) AS amt, QUARTER(a.ENTRY_DT) as qtr FROM billing.PAYMENT_DETAIL a, billing.PURCHASES b WHERE a.BILLID = b.BILLID AND a.ENTRY_DT BETWEEN '$qyear-04-01 00:00:00' AND '$qyearp1-03-31 23:59:59' AND a.STATUS = 'DONE' group by qtr";
				$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
				while($row=mysql_fetch_array($res))
				{
					$qtr=$row['qtr']-1;
					if($qtr<1)
					{
						$qtr+=3;
					}
					else
					{
						$qtr-=1;
					}
					$amt[$k][$qtr]+=round($row['amt'],2);
					$tota[$k]+=round($row['amt'],2);//$amt2a[$k][$qtr];
					$totc[$qtr]+=round($row['amt'],2);//$amt1[$qtr][$k];
				}
			
				$center="MISC-REVENUE-WITHOUT-TAX";
                                $k=array_search($center,$brancharr);

                                $sql="SELECT sum(if(a.TYPE='DOL',a.AMOUNT*a.DOL_CONV_RATE/(1+ b.TAX_RATE/100),a.AMOUNT/(1+ b.TAX_RATE/100) )) as amt,QUARTER(a.ENTRY_DT) as qtr from billing.REV_PAYMENT as a, billing.REV_MASTER as b WHERE a.STATUS='DONE' AND a.ENTRY_DT BETWEEN '$qyear-04-01 00:00:00' AND '$qyearp1-03-31 23:59:59' and b.SALEID=a.SALEID GROUP BY qtr";
                                $res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
                                while($row=mysql_fetch_array($res))
                                {
                                        $qtr=$row['qtr']-1;
                                        if($qtr<1)
                                        {
                                                $qtr+=3;
                                        }
                                        else
                                        {
                                                $qtr-=1;
                                        }
                                        $amt[$k][$qtr]+=round($row['amt'],2);
                                        $tota[$k]+=round($row['amt'],2);//$amt2a[$k][$qtr];
                                        $totc[$qtr]+=round($row['amt'],2);//$amt1[$qtr][$k];
                                }
			}
			elseif($vtype=='M')
			{
				unset($amt);
				unset($amt1);
				unset($tota);
				unset($totb);
				$mflag=1;
				$myearp1=$myear+1;
				if($pay_exec=="Selected")
				{
					$sql="SELECT sum(if(billing.PAYMENT_DETAIL.TYPE='DOL',billing.PAYMENT_DETAIL.AMOUNT*billing.PAYMENT_DETAIL.DOL_CONV_RATE,billing.PAYMENT_DETAIL.AMOUNT)) as amt,month(billing.PAYMENT_DETAIL.ENTRY_DT) as mm,billing.PURCHASES.CENTER as center,billing.PURCHASES.WALKIN as eb FROM billing.PAYMENT_DETAIL,billing.PURCHASES WHERE billing.PAYMENT_DETAIL.STATUS='DONE' AND billing.PAYMENT_DETAIL.ENTRY_DT BETWEEN '$myear-04-01 00:00:00' AND '$myearp1-03-31 23:59:59' AND billing.PURCHASES.BILLID=billing.PAYMENT_DETAIL.BILLID GROUP BY month(billing.PAYMENT_DETAIL.ENTRY_DT),center,eb ORDER BY center,eb";

					$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
					while($row=mysql_fetch_array($res))
					{
						$center=strtoupper($row['center']);
						$k=array_search($center,$brancharr);
						$mm=$row['mm'];
						if($mm<=3)
						{
							$mm+=8;
						}
						else
						{
							$mm-=4;
						}
						if($center=='HO')
						{
							if($row['eb']=='OFFLINE')
							{
								$amt[$k][$mm]["rj"]=$row['amt'];
								$tota[$k]["rj"]+=$row['amt'];//$amt[$k][$mm]["rj"];
							}
							elseif($row['eb']=='ONLINE')
							{
								$amt[$k][$mm]["ol"]=$row['amt'];
								$tota[$k]["ol"]+=$row['amt'];//$amt[$k][$mm]["ol"];
							}
							elseif($row['eb']=='ARAMEX')
							{
								$amt[$k][$mm]["ar"]=$row['amt'];
								$tota[$k]["ar"]+=$row['amt'];//$amt[$k][$mm]["ar"];
							}
							elseif($row['eb']=='BANK_TSFR')
							{
								$amt[$k][$mm]["bt"]=$row['amt'];
								$tota[$k]["bt"]+=$row['amt'];//$amt[$k][$mm]["ar"];
							}
							$amt1[$mm][$k]=$row['amt'];
							$totb[$mm]+=$row['amt'];//$amt1[$mm][$k];
						}
						else
						{
							$amt[$k][$mm]+=$row['amt'];
							$tota[$k]+=$row['amt'];//$amt2a[$k][$mm];
							$totb[$mm]+=$row['amt'];//$amt1[$mm][$k];
						}
					}
/*************************************Code Portion for Misc Rev Resources*************************************************/
					$center="MISC-REVENUE-RES";
					$k=array_search($center,$brancharr);
				
					$sql="SELECT sum(if(TYPE='DOL',AMOUNT*DOL_CONV_RATE,AMOUNT)) as amt,MONTH(ENTRY_DT) as qtr from billing.REV_PAYMENT WHERE STATUS='DONE' AND ENTRY_DT BETWEEN '$myear-04-01 00:00:00' AND '$myearp1-03-31 23:59:59' GROUP BY qtr";

					$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
					while($row=mysql_fetch_array($res))
					{
						$qtr=$row['qtr'];
						if($qtr<=3)
						{
							$qtr+=8;
						}
						else
						{
							$qtr-=4;
						}
						$amt[$k][$qtr]+=$row['amt'];
						$tota[$k]+=$row['amt'];//$amt2a[$k][$qtr];
						$totb[$qtr]+=$row['amt'];//$amt1[$qtr][$k];
					}
/*************************************End of code portion**************************************************************/
					$center="REVENUE-WITHOUT-TAX";
					$k=array_search($center,$brancharr);
					$sql="SELECT sum(IF (TYPE = 'DOL', AMOUNT *DOL_CONV_RATE / ( 1+ TAX_RATE /100 ), AMOUNT / ( 1+ TAX_RATE /100 ) )) AS amt, MONTH(a.ENTRY_DT) as qtr FROM billing.PAYMENT_DETAIL a, billing.PURCHASES b WHERE a.BILLID = b.BILLID AND a.ENTRY_DT BETWEEN '$myear-04-01 00:00:00' AND '$myearp1-03-31 23:59:59' AND a.STATUS = 'DONE' group by qtr";
					$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
					while($row=mysql_fetch_array($res))
					{
						$qtr=$row['qtr'];
						if($qtr<=3)
						{
							$qtr+=8;
						}
						else
						{
							$qtr-=4;
						}
						$amt[$k][$qtr]+=round($row['amt'],2);
						$tota[$k]+=round($row['amt'],2);//$amt2a[$k][$qtr];
						$totc[$qtr]+=round($row['amt'],2);//$amt1[$qtr][$k];
					}
				
					$center="MISC-REVENUE-WITHOUT-TAX";
					$k=array_search($center,$brancharr);

					$sql="SELECT sum(if(a.TYPE='DOL',a.AMOUNT*a.DOL_CONV_RATE/ ( 1+ b.TAX_RATE /100 ),a.AMOUNT/(1+ b.TAX_RATE/100) )) as amt,MONTH(a.ENTRY_DT) as qtr from billing.REV_PAYMENT as a,billing.REV_MASTER as b WHERE a.STATUS='DONE' AND a.ENTRY_DT BETWEEN '$myear-04-01 00:00:00' AND '$myearp1-03-31 23:59:59' AND b.SALEID=a.SALEID GROUP BY qtr";
					$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
					while($row=mysql_fetch_array($res))
					{
						$qtr=$row['qtr'];
						if($qtr<=3)
						{
							$qtr+=8;
						}
						else
						{
							$qtr-=4;
						}
						$amt[$k][$qtr]+=round($row['amt'],2);
						$tota[$k]+=round($row['amt'],2);//$amt2a[$k][$qtr];
						$totc[$qtr]+=round($row['amt'],2);//$amt1[$qtr][$k];
					}
				}
				else
				{
					if($pay_exec=='P')
					{
						unset($amt);
						unset($amt1);
						unset($tota);
						unset($totb);
						$pflag=1;
						$modearr=array('CASH','CHEQUE','DD','TT','ONLINE','OTHER','CREDIT','CCOFFLINE','BANK_TRSFR_ONLINE');
						$year=$myear;
						$yearp1=$year+1;

		//				$sql="SELECT sum(if(billing.PAYMENT_DETAIL.TYPE='DOL',billing.PAYMENT_DETAIL.AMOUNT*$DOL_CONV_RATE,billing.PAYMENT_DETAIL.AMOUNT)) as amt,billing.PAYMENT_DETAIL.MODE as mode,billing.PURCHASES.CENTER as center FROM billing.PAYMENT_DETAIL,billing.PURCHASES WHERE billing.PAYMENT_DETAIL.STATUS='DONE' AND billing.PAYMENT_DETAIL.ENTRY_DT BETWEEN '$year-04-01' AND '$yearp1-03-31' AND billing.PURCHASES.BILLID=billing.PAYMENT_DETAIL.BILLID GROUP BY billing.PAYMENT_DETAIL.MODE";

						$sql="SELECT sum(if(billing.PAYMENT_DETAIL.TYPE='DOL',billing.PAYMENT_DETAIL.AMOUNT*billing.PAYMENT_DETAIL.DOL_CONV_RATE,billing.PAYMENT_DETAIL.AMOUNT)) as amt,month(billing.PAYMENT_DETAIL.ENTRY_DT) as mm,billing.PAYMENT_DETAIL.MODE as mode FROM billing.PAYMENT_DETAIL,billing.PURCHASES WHERE billing.PAYMENT_DETAIL.STATUS='DONE' AND billing.PAYMENT_DETAIL.ENTRY_DT BETWEEN '$year-04-01 00:00:00' AND '$yearp1-03-31 23:59:59' AND billing.PURCHASES.BILLID=billing.PAYMENT_DETAIL.BILLID GROUP BY mm,mode ORDER BY mode";
					

						$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
						while($row=mysql_fetch_array($res))
						{
		//					$center=strtoupper($row['center']);
		//                                        $k=array_search($center,$brancharr);
							$mode=$row['mode'];
							$i=array_search($mode,$modearr);
							$mm=$row['mm'];
							if($mm<=3)
							{
								$mm+=8;
							}
							else
							{
								$mm-=4;
							}
							$amt[$i][$mm]=$row['amt'];
							$amt1[$mm][$i]=$row['amt'];
							$tota[$i]+=$amt[$i][$mm];
							$totb[$mm]+=$amt1[$mm][$i];
						}
					}
					elseif($pay_exec=='E')
					{
						unset($amt);
						unset($amt1);
						unset($tota);
						unset($totb);
						$eflag=1;
						$year=$myear;
						$yearp1=$year+1;
						$sql_e="SELECT USERNAME FROM jsadmin.PSWRDS WHERE PRIVILAGE LIKE '%BU%'";
						$res_e=mysql_query_decide($sql_e,$db) or die("$sql_e".mysql_error_js($db));
						while($row_e=mysql_fetch_array($res_e))
						{
							$userarr[]=$row_e['USERNAME'];
						}
						$userarr[]="ONLINE";
						$userarr[]="OFFLINE";
						$userarr[]="ARAMEX";
						$userarr[]="BANK_TSFR";

		//				$sql="SELECT sum(if(billing.PAYMENT_DETAIL.TYPE='DOL',billing.PAYMENT_DETAIL.AMOUNT*$DOL_CONV_RATE,billing.PAYMENT_DETAIL.AMOUNT)) as amt,billing.PURCHASES.WALKIN as walkin,billing.PURCHASES.CENTER as center FROM billing.PAYMENT_DETAIL,billing.PURCHASES WHERE billing.PAYMENT_DETAIL.STATUS='DONE' AND billing.PAYMENT_DETAIL.ENTRY_DT BETWEEN '$year-04-01' AND '$yearp1-03-31' AND billing.PURCHASES.BILLID=billing.PAYMENT_DETAIL.BILLID GROUP BY billing.PURCHASES.WALKIN";

						$sql="SELECT sum(if(billing.PAYMENT_DETAIL.TYPE='DOL',billing.PAYMENT_DETAIL.AMOUNT*billing.PAYMENT_DETAIL.DOL_CONV_RATE,billing.PAYMENT_DETAIL.AMOUNT)) as amt,month(billing.PAYMENT_DETAIL.ENTRY_DT) as mm,billing.PURCHASES.WALKIN as walkin FROM billing.PAYMENT_DETAIL,billing.PURCHASES WHERE billing.PAYMENT_DETAIL.STATUS='DONE' AND billing.PAYMENT_DETAIL.ENTRY_DT BETWEEN '$year-04-01 00:00:00' AND '$yearp1-03-31 23:59:59' AND billing.PURCHASES.BILLID=billing.PAYMENT_DETAIL.BILLID GROUP BY mm,walkin";

						$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
						while($row=mysql_fetch_array($res))
						{
							$walkin=$row['walkin'];
							$i=array_search($walkin,$userarr);
							$mm=$row['mm'];
							if($mm<=3)
							{
								$mm+=8;
							}
							else
							{
								$mm-=4;
							}
							$amt[$i][$mm]=$row['amt'];
							$amt1[$mm][$i]=$row['amt'];
							$tota[$i]+=$amt[$i][$mm];
							$totb[$mm]+=$amt1[$mm][$i];
						}
					}
				}
			}
			elseif($vtype=='D')
			{
				unset($amt);
				unset($amt1);
				unset($tota);
				unset($totb);
				$dflag=1;

				for($i=0;$i<31;$i++)
				{
					$ddarr[$i]=$i+1;
				}

				$sql="SELECT sum(if(billing.PAYMENT_DETAIL.TYPE='DOL',billing.PAYMENT_DETAIL.AMOUNT*billing.PAYMENT_DETAIL.DOL_CONV_RATE,billing.PAYMENT_DETAIL.AMOUNT)) as amt,DAYOFMONTH(billing.PAYMENT_DETAIL.ENTRY_DT) as dd,billing.PURCHASES.CENTER as center,billing.PURCHASES.WALKIN as eb FROM billing.PAYMENT_DETAIL,billing.PURCHASES WHERE billing.PAYMENT_DETAIL.STATUS='DONE' AND billing.PAYMENT_DETAIL.ENTRY_DT BETWEEN '$dyear-$dmonth-01 00:00:00' AND '$dyear-$dmonth-31 23:59:59' AND billing.PURCHASES.BILLID=billing.PAYMENT_DETAIL.BILLID GROUP BY DAYOFMONTH(billing.PAYMENT_DETAIL.ENTRY_DT),center,eb ORDER BY center,eb";

				$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
				while($row=mysql_fetch_array($res))
				{
					$center=strtoupper($row['center']);
                                        $k=array_search($center,$brancharr);
					$dd=$row['dd']-1;
					if($center=='HO')
					{
						if($row['eb']=='OFFLINE')
						{
							$amt[$k][$dd]["rj"]=$row['amt'];
							$tota[$k]["rj"]+=$row['amt'];//$amt[$k][$dd]["rj"];
						}
						elseif($row['eb']=='ONLINE')
						{
							$amt[$k][$dd]["ol"]=$row['amt'];
							$tota[$k]["ol"]+=$row['amt'];//$amt[$k][$dd]["ol"];
						}
						elseif($row['eb']=='ARAMEX')
						{
							$amt[$k][$dd]["ar"]=$row['amt'];
							$tota[$k]["ar"]+=$row['amt'];//$amt[$k][$dd]["ar"];
						}
						elseif($row['eb']=='BANK_TSFR')
						{
							$amt[$k][$dd]["bt"]=$row['amt'];
							$tota[$k]["bt"]+=$row['amt'];//$amt[$k][$dd]["ar"];
						}
						$amt1[$dd][$k]=$row['amt'];
						$totb[$dd]+=$row['amt'];//$amt1[$dd][$k];
					}
					else
					{
						$amt[$k][$dd]+=$row['amt'];
						$amta[$k][$dd]=$row['amt'];
						$amt1[$dd][$k]=$row['amt'];
						$tota[$k]+=$row['amt'];//$amta[$k][$dd];
						$totb[$dd]+=$row['amt'];//$amt1[$dd][$k];
					}
				}
/*************************************Code Portion for Misc Rev Resources*************************************************/
				$center="MISC-REVENUE-RES";
				$k=array_search($center,$brancharr);
			
				$sql="SELECT sum(if(TYPE='DOL',AMOUNT*DOL_CONV_RATE,AMOUNT)) as amt,DAYOFMONTH(ENTRY_DT) as dd from billing.REV_PAYMENT WHERE STATUS='DONE' AND ENTRY_DT BETWEEN '$dyear-$dmonth-01 00:00:00' AND '$dyear-$dmonth-31 23:59:59' GROUP BY dd";

				$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
				while($row=mysql_fetch_array($res))
				{
					$dd=$row['dd']-1;
					$amt[$k][$dd]+=$row['amt'];
					$amta[$k][$dd]=$row['amt'];
					//$amt1[$dd][$k]=$row['amt'];
					$tota[$k]+=$row['amt'];//$amta[$k][$dd];
					$totb[$dd]+=$row['amt'];//$amt1[$dd][$k];
				}
/***********************************End of code portion******************************************************************/
				//Code Added by sriram on 6th June 2007 to show Revenue and Misc-revenue without tax daywise.
				$center = "REVENUE-WITHOUT-TAX";
				$k = array_search($center,$brancharr);
				$sql="SELECT SUM(IF (TYPE = 'DOL', AMOUNT *DOL_CONV_RATE  / ( 1+ TAX_RATE /100 ), AMOUNT / ( 1+ TAX_RATE /100 ) )) AS amt, DAYOFMONTH(a.ENTRY_DT) as dd FROM billing.PAYMENT_DETAIL a, billing.PURCHASES b WHERE a.BILLID = b.BILLID AND a.ENTRY_DT BETWEEN '$dyear-$dmonth-01 00:00:00' AND '$dyear-$dmonth-31 23:59:59' AND a.STATUS = 'DONE' group by dd";
				$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
				while($row=mysql_fetch_array($res))
				{
					$dd=$row['dd']-1;
					$amt[$k][$dd]+=round($row['amt'],2);
					$tota[$k]+=round($row['amt'],2);
					$totc[$dd]+=round($row['amt'],2);
				}
			
				$center="MISC-REVENUE-WITHOUT-TAX";
				$k=array_search($center,$brancharr);
				$sql="SELECT sum(if(a.TYPE='DOL',a.AMOUNT*a.DOL_CONV_RATE/(1+ b.TAX_RATE/100),a.AMOUNT/(1+ b.TAX_RATE/100) )) as amt,DAYOFMONTH(a.ENTRY_DT) as dd from billing.REV_PAYMENT as a,billing.REV_MASTER as b WHERE a.STATUS='DONE' AND a.ENTRY_DT BETWEEN '$dyear-$dmonth-01 00:00:00' AND '$dyear-$dmonth-31 23:59:59' AND b.SALEID=a.SALEID GROUP BY dd";
				$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
				while($row=mysql_fetch_array($res))
				{
					$dd=$row['dd']-1;
					$amt[$k][$dd]+=round($row['amt'],2);
					$tota[$k]+=round($row['amt'],2);
					$totc[$dd]+=round($row['amt'],2);
				}
				//Code Added by sriram on 6th June 2007 to show Revenue and Misc-revenue without tax daywise.
			}
		}

/*************************************************************************************************************************
                        Added By        :       Shakti Srivastava
                        Date            :       24 November, 2005
                        Reason          :       This was needed for stopping further execution of this script whenever
                                        :       indicator_mis.php was used to obtain data
*************************************************************************************************************************/
                if($JSIndicator==1)
                {
                        return;
                }
/**************************************End of Addition*******************************************************************/
		$smarty->assign("brancharr",$brancharr);
		$smarty->assign("branch",$branch);
		$smarty->assign("amt",$amt);
		$smarty->assign("tot",$tot);
		$smarty->assign("tota",$tota);
		$smarty->assign("totb",$totb);
		$smarty->assign("totc",$totc);
		$smarty->assign("tot1",$tot1);
		$smarty->assign("tot2",$tot2);
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("qtrarr",$qtrarr);
		$smarty->assign("flag",$flag);
		$smarty->assign("pflag",$pflag);
		$smarty->assign("qflag",$qflag);
                $smarty->assign("mflag",$mflag);
                $smarty->assign("dflag",$dflag);
                $smarty->assign("eflag",$eflag);
		$smarty->assign("bflag",$bflag);
		$smarty->assign("qyear",$qyear);
		$smarty->assign("qyearp1",$qyearp1);
		$smarty->assign("year",$year);
		$smarty->assign("yearp1",$yearp1);
		$smarty->assign("myear",$myear);
		$smarty->assign("myearp1",$myearp1);
		$smarty->assign("dyear",$dyear);
		$smarty->assign("dmonth",$dmonth);
		$smarty->assign("dmonthp1",$dmonthp1);
		$smarty->assign("mode",$mode);
		$smarty->assign("walkin",$walkin);
		$smarty->assign("userarr",$userarr);
		$smarty->assign("modearr",$modearr);

                $smarty->display("collectionmis.htm");
	}
	else
	{
		$user=getname($checksum);
		for($i=0;$i<12;$i++)
                {
                        $mmarr[$i]=$i+1;
                }
                for($i=2004;$i<=date("Y");$i++)
{
        $yyarr[$i-2004]=$i;
}
		$privilage=getprivilage($checksum);
		$priv=explode("+",$privilage);
		if(in_array('MA',$priv) || in_array('MB',$priv))
		{
			$smarty->assign("VIEWALL","Y");
			//run query : select all branches
			$sql="SELECT * FROM billing.BRANCHES";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			if($row=mysql_fetch_array($res))
			{
				$i=0;
				do
				{
					$brancharr[$i]["id"]=$row['ID'];
					$brancharr[$i]["name"]=$row['NAME'];

					$i++;
				}while($row=mysql_fetch_array($res));
			}

			$smarty->assign("brancharr",$brancharr);
		}
		else
		{
			// run query : select branch of user
			$sql="SELECT CENTER FROM jsadmin.PSWRDS WHERE USERNAME='$user'";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
			if($row=mysql_fetch_array($res))
			{
				$branch=strtoupper($row['CENTER']);
			}

			$smarty->assign("ONLYBRANCH","Y");
			$smarty->assign("branch",$branch);
		}

		$smarty->assign("priv",$priv);
		$smarty->assign("mmarr",$mmarr);
                $smarty->assign("yyarr",$yyarr);
		$smarty->assign("CHECKSUM",$checksum);
		$smarty->display("collectionmis.htm");
	}
}
else
{
	$smarty->assign("user",$username);
	$smarty->display("jsconnectError.tpl");
}
?>
