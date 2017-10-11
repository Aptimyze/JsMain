<?php
include("connect.inc");
include("../profile/pg/functions.php");
ini_set("memory_limit","16M");
ini_set("max_execution_time","0");
$db=connect_misdb();
$db2=connect_master();

if(authenticated($cid))
{
	$smarty->assign("cid",$cid);
	if($CMDGo)
	{
		//{
			//$branch = 'ALL';
			for($i=0;$i<31;$i++)
                	{
                        	$ddarr[$i]=$i+1;
                	}
                	$smarty->assign("month",$month);
			$smarty->assign("year",$year);

			$dflag=1;
			if ($month < 9)
				$month = "0".$month;

			$sql="SELECT COUNT(*) as cnt,ALLOTED_TO,DAYOFMONTH(ALLOT_TIME) as dd FROM incentive.CRM_DAILY_ALLOT WHERE ALLOT_TIME BETWEEN '$year-$month-01 00:00:00' AND '$year-$month-31 23:59:59' GROUP BY ALLOTED_TO,dd";
			//$sql="SELECT COUNT(*) as cnt,ALLOTED_TO,DAYOFMONTH(ALLOT_TIME) as dd FROM incentive.MAIN_ADMIN WHERE ALLOT_TIME BETWEEN '$year-$month-01 00:00:00' AND '$year-$month-31 23:59:59' GROUP BY ALLOTED_TO,dd";

			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
			while($row=mysql_fetch_array($res))
			{
				$dd=$row['dd']-1;
				$alloted_to = $row['ALLOTED_TO'];
				$sql="SELECT CENTER FROM jsadmin.PSWRDS WHERE USERNAME='$alloted_to'";
                                $res_c=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
                                $row_c=mysql_fetch_array($res_c);

				/*if(is_array($operatorarr))
				{
					if(!in_array($alloted_to,$operatorarr))
					{
						$operatorarr[]=$alloted_to;
					}
				}
				else
				{
					$operatorarr[]=$alloted_to;
				}*/
				$center=strtoupper($row_c['CENTER']);
				if(is_array($brancharr))
				{
					if(!in_array($center,$brancharr))
					{
						if($branch=='ALL' || strtoupper($branch)==$center)
							$brancharr[]=$center;
					}
				}
				else
				{
					if($branch=='ALL' || strtoupper($branch)==$center)
					{
						$brancharr[]=$center;
					}
				}

				if($branch=='ALL' || strtoupper($branch)==$center)
				{
					$i=array_search($center,$brancharr);
					if(is_array($operatorarr[$i]))
					{
						if(!in_array($alloted_to,$operatorarr[$i]))
						{
							$operatorarr[$i][]=$alloted_to;
						}
					}
					else
					{
						$operatorarr[$i][]=$alloted_to;
					}
					$j=array_search($alloted_to,$operatorarr[$i]);
					$crmwork[$i][$j][$dd]['callcnt']= $row['cnt'];
                                	$total[$i][$j]['callcnt']+= $row['cnt'];
                                	$grandtotal[$i][$dd]['callcnt']+= $row['cnt'];
                                	$annualtot[$i]['callcnt']+= $row['cnt'];

					//added by sriram.
					$grandtotal_allbranch[$dd]['callcnt'] += $row['cnt'];
                                	$annualtot_allbranch['callcnt']+= $row['cnt'];
				}

				/*$i=array_search($alloted_to,$operatorarr);
				$crmwork[$i][$dd]['callcnt']= $row['cnt'];
				$total[$i]['callcnt']+= $row['cnt'];
				$grandtotal[$dd]['callcnt']+= $row['cnt'];
				$annualtot['callcnt']+= $row['cnt'];*/
			}

			$sql = "SELECT COUNT(DISTINCT p.PROFILEID) AS cnt , SUM( if(p.TYPE='DOL', $DOL_CONV_RATE*p.AMOUNT,p.AMOUNT) ) AS amt , c.ALLOTED_TO , DAYOFMONTH(c.ALLOT_TIME) as dd FROM incentive.CRM_DAILY_ALLOT AS c LEFT JOIN billing.PAYMENT_DETAIL AS p ON p.PROFILEID = c.PROFILEID WHERE c.ALLOT_TIME BETWEEN '$year-$month-01 00:00:00' AND '$year-$month-31 23:59:59' AND p.ENTRY_DT >= c.ALLOT_TIME AND p.ENTRY_DT <= DATE_ADD( c.ALLOT_TIME, INTERVAL( 30 + c.RELAX_DAYS ) DAY ) AND STATUS IN ('DONE', 'ADJUST') GROUP BY dd ,c.ALLOTED_TO";
			//$sql = "SELECT COUNT( DISTINCT p.PROFILEID ) AS cnt, SUM( p.AMOUNT ) AS amt, c.ALLOTED_TO, DAYOFMONTH( c.ALLOT_TIME ) AS dd FROM incentive.MAIN_ADMIN AS c LEFT JOIN billing.PAYMENT_DETAIL AS p ON p.PROFILEID = c.PROFILEID WHERE c.ALLOT_TIME BETWEEN '$year-$month-01 00:00:00' AND '$year-$month-31 23:59:59' AND p.ENTRY_DT >= c.ALLOT_TIME AND p.ENTRY_DT <= DATE_ADD( c.ALLOT_TIME, INTERVAL 30 DAY ) AND p.STATUS IN ('DONE', 'ADJUST') GROUP BY dd, c.ALLOTED_TO";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
			while($row=mysql_fetch_array($res))
			{
				$dd=$row['dd']-1;

				$alloted_to = $row['ALLOTED_TO'];
				$sql="SELECT CENTER FROM jsadmin.PSWRDS WHERE USERNAME='$alloted_to'";
                                $res_c=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
                                $row_c=mysql_fetch_array($res_c);
				$center=strtoupper($row_c['CENTER']);

				//$i=array_search($center,$brancharr);
				//$j=array_search($alloted_to,$operatorarr);

				if(is_array($brancharr))
				{
					if(in_array($center,$brancharr))
					{
						$i=array_search($center,$brancharr);
						//if(in_array($alloted_to,$operatorarr))
						//{
							$j=array_search($alloted_to,$operatorarr[$i]);
							$crmwork[$i][$j][$dd]['conv']= $row['cnt'];
							/*if ($crmwork[$i][$j][$dd]['callcnt'])
							{
								$crmwork[$i][$j][$dd]['callcnt'];
								$per = $row['cnt']/$crmwork[$i][$j][$dd]['callcnt'] * 100;
								$crmwork[$i][$j][$dd]['conv_per']=round($per,2);
							}*/
							$crmwork[$i][$j][$dd]['totamt']+= $row['amt'];
							$total[$i][$j]['conv_per']+= $row['cnt'];
							$total[$i][$j]['totamt']+= $row['amt'];
							$grandtotal[$i][$dd]['conv_per']+= $row['cnt'];
							$grandtotal[$i][$dd]['totamt']+= $row['amt'];
							$annualtot[$i]['conv_per']+= $row['cnt'];
							$annualtot[$i]['totamt']+= $row['amt'];

							//added by sriram
							$grandtotal_allbranch[$dd]['conv_per'] += $row['cnt'];
							$annualtot_allbranch['conv_per'] += $row['cnt'];
						//}
					}
				}
			}
			
			for ($i =0;$i < count($brancharr);$i++)
			{
				for($j=0; $j < count($operatorarr[$i]);$j++ )
				{
					if ($total[$i][$j]['callcnt'])
					{
						$per = $total[$i][$j]['conv_per']/$total[$i][$j]['callcnt'] * 100;
						$total[$i][$j]['conv_per']= round($per,2);
					}
				}
			}
			for ($i =0;$i < count($brancharr);$i++)
                        {
				if ($annualtot[$i]['callcnt']!=0)
				{
					$per = $annualtot[$i]['conv_per']/$annualtot[$i]['callcnt'] * 100;
					$annualtot[$i]['conv_per'] = round($per,2);

				}
			}

			//added by sriram
			for($i=0; $i<31; $i++)
			{
				$grandtotal_allbranch_callcnt += $grandtotal_allbranch[$i]['callcnt'];
				$grandtotal_allbranch_conv += $grandtotal_allbranch[$i]['conv_per'];
			}
			$annualtot_allbranch['conv_per'] = round(($grandtotal_allbranch_conv/$grandtotal_allbranch_callcnt)*100,2);
			//added by sriram

			if (!count($crmwork))
				$norecords = 1;

		//}
		for($i=0;$i<12;$i++)
                {
                        $montharr[$i]=$i+1;
                }

/**********************************************Code Added by Aman for Excel*********************************************/
		if($mis_type=="XLS")
                {
                        $header = "Executive / Days"."\t";
                        for($i=0;$i<count($ddarr);$i++)
                        {
                                $header=$header.$ddarr[$i]."\t";
                        }
                        $header=$header."Total"."\t"."Amount";
			
			for($i=0;$i<count($brancharr);$i++)
			{
				$data.=$brancharr[$i]."\n";
				for($j=0;$j<count($operatorarr);$j++)
				{
					$data.=$operatorarr[$i][$j]."-Total Calls"."\t";
					for($k=0;$k<count($ddarr);$k++)
					{
						$data.=$crmwork[$i][$j][$k]["callcnt"]."\t";
					}
					$data.=$total[$i][$j]["callcnt"]."\n";
			
					$data.=$operatorarr[$i][$j]."-Conv Percent"."\t";
                                        for($k=0;$k<count($ddarr);$k++)
                                        {
                                                $data.=$crmwork[$i][$j][$k]["conv"]."\t";
                                        }
                                        $data.=$total[$i][$j]["conv_per"]."\n";
					$data.=$operatorarr[$i][$j]."-Total Amount Collected"."\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t\t".$total[$i][$j]["totamt"]."\n";
				}	
				
				$data.="Total Calls"."\t";
				for($dd_t=0;$dd_t<count($ddarr);$dd_t++)
                                {
					$data.=$grandtotal[$i][$dd_t]["callcnt"]."\t";
				}
				$data.=$annualtot[$i]["callcnt"]."\n";

				$data.="Total Conv percent"."\t";
                                for($dd_t=0;$dd_t<count($ddarr);$dd_t++)
                                {
                                        $data.=$grandtotal[$i][$dd_t]["conv_per"]."\t";
                                }
                                $data.=$annualtot[$i]["conv_per"]."\n";
				
				$data.="Total Collection"."\t";
				for($dd_t=0;$dd_t<count($ddarr);$dd_t++)
                                {
                                        $data.=$grandtotal[$i][$dd_t]["totamt"]."\t";
                                }
				$data.=$annualtot[$i]["totamt"]."\n";

			}
			$data.="All branches -Total Calls"."\t";
			for($l=0;$l<count($ddarr);$l++)
                        {
				$data.=$grandtotal_allbranch[$l]["callcnt"]."\t";
			}
			$data.=$annualtot_allbranch["callcnt"]."\n";

			$data.="All branches -Total Conv percent"."\t";
                        for($l=0;$l<count($ddarr);$l++)
                        {
                                $data.=$grandtotal_allbranch[$l]["conv_per"]."\t";
                        }
                        $data.=$annualtot_allbranch["conv_per"]."\n";

			$data = trim($data)."\t \n";

			header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=crm_monthly_revenue.xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo $final_data = $header."\n".$data;
			die();
		}
/************************************************Code Ended for Excel*****************************************************/

		$smarty->assign("crmwork",$crmwork);
		$smarty->assign("brancharr",$brancharr);
		$smarty->assign("yy",$yy);
		$smarty->assign("norecords",$norecords);
		$smarty->assign("grandtotal",$grandtotal);
		$smarty->assign("grandtotal_allbranch",$grandtotal_allbranch);
		$smarty->assign("annualtot",$annualtot);
		$smarty->assign("annualtot_allbranch",$annualtot_allbranch);
		$smarty->assign("total",$total);
		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("montharr",$montharr);
		$smarty->assign("operatorarr",$operatorarr);
		$smarty->assign("amt",$amt);
		$smarty->assign("amta",$amta);
		$smarty->assign("amtb",$amtb);
		$smarty->assign("amttot",$amttot);
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("flag",1);
		$smarty->assign("mflag",$mflag);
		$smarty->assign("dflag",$dflag);
		$smarty->display("crm_monthly_revenue_mis_tmp.htm");

		unset($grandtotal);
		unset($grandtotal_allbranch);
		unset($norecords);
		unset($annualtot);
		unset($annualtot_allbranch);
		unset($crmwork);
		unset($per);
	}
	else
	{
		$user=getname($cid);
                $smarty->assign("flag","0");
                for($i=0;$i<12;$i++)
                {
                        $mmarr[$i]=$i+1;
                }
                for($i=2004;$i<=date("Y");$i++)
{
        $yyarr[$i-2004]=$i;
}
		$privilage=getprivilage($cid);
                $priv=explode("+",$privilage);
                if(in_array('MA',$priv) || in_array('MB',$priv))
                {
                        $smarty->assign("VIEWALL","Y");
                        //run query : select all branches
                        $sql="SELECT NAME FROM incentive.BRANCHES";
                        $res=mysql_query_decide($sql,$db) or die(mysql_error_js());
                        if($row=mysql_fetch_array($res))
                        {
                                do
                                {
                                        $brancharr[]=$row['NAME'];
                                }while($row=mysql_fetch_array($res));
                        }
                }
		else
		{
			$sql="SELECT CENTER FROM jsadmin.PSWRDS WHERE USERNAME='$user'";
			$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
			if($row=mysql_fetch_array($res))
			{
				$brancharr[]=$row['CENTER'];
			}
			//$smarty->assign("ONLYBRANCH","Y");
			//$smarty->assign("branch",$branch);
		}
		$smarty->assign("brancharr",$brancharr);
		$smarty->assign("cid",$cid);
                $smarty->assign("mmarr",$mmarr);
                $smarty->assign("yyarr",$yyarr);
		$smarty->display("crm_monthly_revenue_mis_tmp.htm");
	}
}
else
{
        $smarty->display("jsconnectError.tpl");
}
?>
