<?php
die("Temporarily disabled");
include("connect.inc");
$db=connect_misdb();
$db2=connect_master();

if(authenticated($cid))
{
	if($CMDGo)
	{
		$smarty->assign("flag","1");
		for($i=0;$i<31;$i++)
		{
			$ddarr[$i]=$i+1;
		}

		$sql="SELECT NAME FROM incentive.BRANCHES";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$brancharr[]=strtoupper($row['NAME']);
		}

		$sql="SELECT USERNAME,CENTER,PRIVILAGE FROM jsadmin.PSWRDS";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$username=$row['USERNAME'];
			$center=strtoupper($row['CENTER']);
			$privilage=$row['PRIVILAGE'];
			$priv=explode("+",$privilage);
			$i=array_search($center,$brancharr);

			$operatorarr[$i][0]="Other";

			if(!in_array($username,$operatorarr[$i]))
			{
				if(in_array('IUO',$priv) || in_array('IUI',$priv) || in_array('IUW',$priv))
				{
					$operatorarr[$i][]=$username;
				}
			}
		}

		if($mmonth<10)
			$mmonth="0".$mmonth;
		$st_date=$myear."-".$mmonth."-01 00:00:00";
		$end_date=$myear."-".$mmonth."-31 23:59:59";

//		$sql="SELECT PROFILEID,sum(if(billing.PAYMENT_DETAIL.TYPE='DOL',billing.PAYMENT_DETAIL.AMOUNT*45,billing.PAYMENT_DETAIL.AMOUNT)) AS AMOUNT,ENTRY_DT,CENTER FROM billing.PAYMENT_DETAIL WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND STATUS='DONE' GROUP BY PROFILEID";
		$sql="SELECT pd.STATUS,pd.PROFILEID,if(pd.TYPE='DOL',pd.AMOUNT*pd.DOL_CONV_RATE,pd.AMOUNT) AS AMOUNT,pd.ENTRY_DT,p.CENTER,p.WALKIN as eb FROM billing.PAYMENT_DETAIL pd,billing.PURCHASES p WHERE pd.ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND pd.STATUS='DONE' AND pd.STATUS IN ('DONE','REFUND') AND pd.BILLID=p.BILLID";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		if($row=mysql_fetch_array($res))
		{
			do
			{
				unset($pmode);
                                unset($flag);
				$mcnt=0;
				$ind=0;
				$allotarr=array();
                                $temparr=array();
				$profileid=$row['PROFILEID'];
				$entry_dt=$row['ENTRY_DT'];
                                $status=$row['STATUS'];
				$dd=substr($entry_dt,8,2)-1;
				list($edt,$etime)=explode(" ",$entry_dt);
				list($yy,$mm,$dd1)=explode("-",$edt);
				list($hr,$min,$sec)=explode(":",$etime);
				$ts=mktime($hr,$min,$sec,$mm,$dd1,$yy);
				$ts+=24*60*60;
				$entry_dt=date("Y-m-d H:i:s",$ts);
				$eb=$row['eb'];
				$center=strtoupper($row['CENTER']);

				$sql="(SELECT CLAIM_TIME as claim_time,ALLOTED_TO as alloted_to,MODE FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid' AND STATUS='F' AND WILL_PAY='Y' AND CLAIM_TIME BETWEEN DATE_SUB('$entry_dt',INTERVAL 31 DAY) AND '$entry_dt') UNION (SELECT CONVINCE_TIME as claim_time,ENTRYBY as alloted_to,MODE FROM incentive.CLAIM WHERE PROFILEID='$profileid' AND WILL_PAY='Y' AND CONVINCE_TIME BETWEEN DATE_SUB('$entry_dt',INTERVAL 31 DAY) AND '$entry_dt') ORDER BY claim_time ASC";
                                $res1=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
                                while($row1=mysql_fetch_array($res1))
                                {
                                        $alloted_to=$row1['alloted_to'];
                                        $mode=$row1['MODE'];

                                        if(is_array($temparr))
                                        {
                                                if(!in_array($alloted_to,$temparr))
                                                {
                                                        $flag[$ind]=0;
                                                        $temparr[$ind]=$alloted_to;
                                                        $pmode[$ind]=$mode;
                                                        $ind++;
                                                }
                                        }
                                        else
                                        {
                                                $flag[$ind]=0;
                                                $temparr[$ind]=$alloted_to;
                                                $pmode[$ind]=$mode;
                                                $ind++;
                                        }
                                        if($pmode[0]=='O')
                                        {
                                                $mcnt=1;
                                                if(!$flag[0])
                                                {
                                                        $k=multi_array_search($temparr[0],$operatorarr);
							if($status=='DONE')
							{
								$totb[$k[0]][$dd]+=$row['AMOUNT']/$mcnt;
								$amttot[$k[0]]+=$row['AMOUNT']/$mcnt;
								$daytot[$dd]+=$row['AMOUNT']/$mcnt;
								$grandtot+=$row['AMOUNT']/$mcnt;
							}
							else
							{
								$totb[$k[0]][$dd]-=$row['AMOUNT']/$mcnt;
								$amttot[$k[0]]-=$row['AMOUNT']/$mcnt;
								$daytot[$dd]-=$row['AMOUNT']/$mcnt;
								$grandtot-=$row['AMOUNT']/$mcnt;
							}
                                                }
                                                $flag[0]=1;
                                                $not_outbound=0;
                                        }
                                        else
                                        {
						$mcnt=1;
                                                $not_outbound=1;
                                        }
                                }
				if($mcnt)
				{
					if($not_outbound)
                                        {
						$mcnt=count($temparr);

						for($ctr=0;$ctr<$mcnt;$ctr++)
						{
							$k=multi_array_search($temparr[$ctr],$operatorarr);

							if($status=='DONE')
							{
								$totb[$k[0]][$dd]+=$row['AMOUNT']/$mcnt;
								$amttot[$k[0]]+=$row['AMOUNT']/$mcnt;
								$daytot[$dd]+=$row['AMOUNT']/$mcnt;
								$grandtot+=$row['AMOUNT']/$mcnt;
							}
							else
							{
								$totb[$k[0]][$dd]-=$row['AMOUNT']/$mcnt;
								$amttot[$k[0]]-=$row['AMOUNT']/$mcnt;
								$daytot[$dd]-=$row['AMOUNT']/$mcnt;
								$grandtot-=$row['AMOUNT']/$mcnt;
							}
						}
					}
				}
				else
				{
					$i=array_search($center,$brancharr);

					if($center=='HO')
					{
						if($eb=='OFFLINE')
						{
							if ($status=='DONE')
							{
								$totb[$i][$dd]["of"]+=$row['AMOUNT'];
								$amttot[$i]["of"]+=$row['AMOUNT'];
							}
							else
							{
								$totb[$i][$dd]["of"]-=$row['AMOUNT'];
								$amttot[$i]["of"]-=$row['AMOUNT'];
							}
						}
						elseif($eb=='ONLINE')
						{
							if($status=='DONE')
							{
								$totb[$i][$dd]["ol"]+=$row['AMOUNT'];
								$amttot[$i]["ol"]+=$row['AMOUNT'];
							}
							else
							{
								$totb[$i][$dd]["ol"]-=$row['AMOUNT'];
								$amttot[$i]["ol"]-=$row['AMOUNT'];
							}
						}
						elseif($eb=='ARAMEX')
						{
							if($status=='DONE')
							{
								$totb[$i][$dd]["ar"]+=$row['AMOUNT'];
								$amttot[$i]["ar"]+=$row['AMOUNT'];
							}
							else
							{
								$totb[$i][$dd]["ar"]-=$row['AMOUNT'];
								$amttot[$i]["ar"]-=$row['AMOUNT'];
							}
						}
					}
					else
					{
						if($status=='DONE')
						{
							$totb[$i][$dd]+=$row['AMOUNT'];
							$amttot[$i]+=$row['AMOUNT'];
						}
						else
						{
							$totb[$i][$dd]-=$row['AMOUNT'];
							$amttot[$i]-=$row['AMOUNT'];
						}
					}
					if($status=='DONE')
					{
						$daytot[$dd]+=$row['AMOUNT'];
						$grandtot+=$row['AMOUNT'];
					}
					else
					{
						$daytot[$dd]-=$row['AMOUNT'];
						$grandtot-=$row['AMOUNT'];
					}
				}
			}while($row=mysql_fetch_array($res));
		}
		if($brancharr)
			$smarty->assign("BRANCH","Y");

		for($i=0;$i<count($brancharr);$i++)
		{
			if($brancharr[$i]=='HO')
			{
				if($grandtot)
				{
					$pertot[$i]["of"]=$amttot[$i]["of"]/$grandtot * 100;
					$pertot[$i]["of"]=round($pertot[$i]["of"],1);
					$pertot[$i]["ol"]=$amttot[$i]["ol"]/$grandtot * 100;
					$pertot[$i]["ol"]=round($pertot[$i]["ol"],1);
					$pertot[$i]["ar"]=$amttot[$i]["ar"]/$grandtot * 100;
					$pertot[$i]["ar"]=round($pertot[$i]["ar"],1);
				}
			}
			else
			{
				if($grandtot)
				{
					$pertot[$i]=$amttot[$i]/$grandtot * 100;
					$pertot[$i]=round($pertot[$i],1);
				}
			}
		}

		$smarty->assign("amt",$amt);
		$smarty->assign("tota",$tota);
		$smarty->assign("totb",$totb);
		$smarty->assign("amttot",$amttot);
		$smarty->assign("daytot",$daytot);
		$smarty->assign("grandtot",$grandtot);
		$smarty->assign("pertot",$pertot);
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("brancharr",$brancharr);
		$smarty->assign("operatorarr",$operatorarr);

		$smarty->display("total_overall_collection_crm_mis.htm");
	}
	else
	{
		$user=getname($cid);
		$smarty->assign("flag","0");
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

                        $smarty->assign("brancharr",$brancharr);
                }
                else
                {
                        // run query : select branch of user
                        $sql="SELECT CENTER FROM jsadmin.PSWRDS WHERE USERNAME='$user'";
                        $res=mysql_query_decide($sql,$db) or die(mysql_error_js());
                        if($row=mysql_fetch_array($res))
                        {
                                $branch=$row['CENTER'];
                        }

                        $smarty->assign("branch",$branch);
                }

                $smarty->assign("priv",$priv);
                $smarty->assign("ddarr",$ddarr);
                $smarty->assign("mmarr",$mmarr);
                $smarty->assign("yyarr",$yyarr);
                $smarty->assign("cid",$cid);
                $smarty->display("total_overall_collection_crm_mis.htm");
	}
}
else
{
	$smarty->assign("user",$username);
        $smarty->display("jsconnectError.tpl");
}

function multi_array_search($search_value, $the_array)
{
   if (is_array($the_array))
   {
       foreach ($the_array as $key => $value)
       {
           $result = multi_array_search($search_value, $value);
           if (is_array($result))
           {
               $return = $result;
               array_unshift($return, $key);
               return $return;
           }
           elseif ($result == true)
           {
               $return[] = $key;
               return $return;
           }
       }
       return false;
   }
   else
   {
       if ($search_value == $the_array)
       {
           return true;
       }
       else return false;
   }
}
?>
