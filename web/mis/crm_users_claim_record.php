<?php

include("connect.inc");
$db=connect_misdb();
$db2=connect_master();

if(authenticated($cid))
{
	if($CMDGo)
	{
		$smarty->assign("flag","1");
		$modearr=array('I','O','W','C');

		if($vtype=='M')
		{
			if($mmonth<10)
				$mmonth="0".$mmonth;
			$st_date=$myear."-".$mmonth."-01 00:00:00";
			$end_date=$myear."-".$mmonth."-31 23:59:59";
		}
		elseif($vtype=='D')
		{
			if($dmonth<10)
				$dmonth="0".$dmonth;
			if($day<10)
				$day="0".$day;
			$st_date=$dyear."-".$dmonth."-".$day." 00:00:00";
			$end_date=$dyear."-".$dmonth."-".$day." 23:59:59";
		}

		$sql="SELECT STATUS,PROFILEID,BILLID,if(billing.PAYMENT_DETAIL.TYPE='DOL',billing.PAYMENT_DETAIL.AMOUNT*45,billing.PAYMENT_DETAIL.AMOUNT) AS AMOUNT,ENTRY_DT FROM billing.PAYMENT_DETAIL WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND STATUS IN ('DONE','REFUND')";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		if($row=mysql_fetch_array($res))
		{
			do
			{
				$ind=0;
				unset($opsarr);
				unset($profilearr);
				$cntops=0;
				$profileid=$row['PROFILEID'];
				$billid=$row['BILLID'];
				$valid_id = 0;
		                $valid_id = check_validity($billid);
                		if($valid_id)
                		{
					$entry_dt=$row['ENTRY_DT'];
					$status=$row['STATUS'];
					list($edt,$etime)=explode(" ",$entry_dt);
					list($yy,$mm,$dd)=explode("-",$edt);
					list($hr,$min,$sec)=explode(":",$etime);
					$ts=mktime($hr,$min,$sec,$mm,$dd,$yy);
					$ts+=24*60*60;
					$entry_dt=date("Y-m-d H:i:s",$ts);

					$sql="SELECT DISTINCT ALLOTED_TO FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid' AND STATUS='F' AND WILL_PAY='Y' AND CLAIM_TIME BETWEEN DATE_SUB('$entry_dt',INTERVAL 31 DAY) AND '$entry_dt'";
					$res1=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
					while($row1=mysql_fetch_array($res1))
					{
						if(is_array($opsarr))
						{
							if(!in_array($row1['ALLOTED_TO'],$opsarr))
							{
								$opsarr[]=$row1['ALLOTED_TO'];
							}
						}
						else
						{
							$opsarr[]=$row1['ALLOTED_TO'];
						}
					}
					$sql="SELECT COUNT(DISTINCT ENTRYBY) as cnt FROM incentive.CLAIM WHERE PROFILEID='$profileid' AND WILL_PAY='Y' AND CONVINCE_TIME BETWEEN DATE_SUB('$entry_dt',INTERVAL 31 DAY) AND '$entry_dt'";
					if($opsarr)
					{
						$cntops=count($opsarr);
						$opsstr="'".implode("','",$opsarr)."'";
						$sql.=" AND ENTRYBY NOT IN ($opsstr)";
					}
					$res1=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
					if($row1=mysql_fetch_array($res1))
					{
						$cntops+=$row1['cnt'];
					}

					if($cntops>0)
					{
						$sql="(SELECT ALLOTED_TO as alloted_to,CLAIM_TIME as claim_time,MODE FROM incentive.MAIN_ADMIN WHERE PROFILEID='$profileid' AND CLAIM_TIME BETWEEN DATE_SUB('$entry_dt',INTERVAL 31 DAY) AND '$entry_dt' AND STATUS='F' AND WILL_PAY='Y') UNION (SELECT ENTRYBY as alloted_to,CONVINCE_TIME as claim_time,MODE FROM incentive.CLAIM WHERE PROFILEID='$profileid' AND WILL_PAY='Y' AND CONVINCE_TIME BETWEEN DATE_SUB('$entry_dt',INTERVAL 31 DAY) AND '$entry_dt') ORDER BY claim_time ASC";
						$res_ma=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
						while($row_ma=mysql_fetch_array($res_ma))
						{
							$alloted_to=$row_ma['alloted_to'];
							$sql="SELECT CENTER FROM jsadmin.PSWRDS WHERE USERNAME='$alloted_to'";
							$res_c=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
							$row_c=mysql_fetch_array($res_c);
							$center=strtoupper($row_c['CENTER']);
							if(is_array($brancharr))
							{
								if(!in_array($center,$brancharr))
								{
									$brancharr[]=$center;
								}
							}
							else
							{
								$brancharr[]=$center;
							}

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
							$mode=$row_ma['MODE'];
							$str=$profileid.",".$alloted_to;
							list($i,$j)=multi_array_search($alloted_to,$operatorarr);
							$k=array_search($mode,$modearr);
							if(is_array($temparr))
							{
								if(!in_array($profileid,$temparr))
								{
									$flag[$ind]=0;
									$temp_branch[$ind]=$center;
									$temparr[$ind]=$profileid;
									$pmode[$ind]=$mode;
									$temp_allotarr[$ind]=$alloted_to;
									$ind++;
								}
							}
							else
							{
								$flag[$ind]=0;
								$temp_branch[$ind]=$center;
								$temparr[$ind]=$profileid;
								$pmode[$ind]=$mode;
								$temp_allotarr[$ind]=$alloted_to;
								$ind++;
							}

							if($pmode[0]=='O')
							{
								$cntops=1;
								if(!$flag[0])
								{
									list($i,$j)=multi_array_search($temp_allotarr[0],$operatorarr);
									$k=array_search('O',$modearr);
									if($status=='DONE')
									{
										$amt[$i][$j][$k]+=round($row['AMOUNT']/$cntops,2);
										$amta[$i][$j]+=round($row['AMOUNT']/$cntops,2);
										$amtb[$i][$k]+=round($row['AMOUNT']/$cntops,2);
										$amttot[$i]+=round($row['AMOUNT']/$cntops,2);
									}
									else
									{
										$amt[$i][$j][$k]-=round($row['AMOUNT']/$cntops,2);
										$amta[$i][$j]-=round($row['AMOUNT']/$cntops,2);
										$amtb[$i][$k]-=round($row['AMOUNT']/$cntops,2);
										$amttot[$i]-=round($row['AMOUNT']/$cntops,2);
									}
								}
								$not_outbound=0;
								$flag[0]=1;
							}
							else
							{
								$not_outbound=1;
							}

							if($cntops)
							{
								if($not_outbound)
								{
									if(is_array($profilearr))
									{
										if(!in_array($str,$profilearr))
										{
											$profilearr[]=$str;
											if($status=='DONE')
											{
												$amt[$i][$j][$k]+=round($row['AMOUNT']/$cntops,2);
												$amta[$i][$j]+=round($row['AMOUNT']/$cntops,2);
												$amtb[$i][$k]+=round($row['AMOUNT']/$cntops,2);
												$amttot[$i]+=round($row['AMOUNT']/$cntops,2);
											}
											else
											{
												$amt[$i][$j][$k]-=round($row['AMOUNT']/$cntops,2);
												$amta[$i][$j]-=round($row['AMOUNT']/$cntops,2);
												$amtb[$i][$k]-=round($row['AMOUNT']/$cntops,2);
												$amttot[$i]-=round($row['AMOUNT']/$cntops,2);
											}
										}
									}
									else
									{
										$profilearr[]=$str;
										if($status=='DONE')
										{
											$amt[$i][$j][$k]+=round($row['AMOUNT']/$cntops,2);
											$amta[$i][$j]+=round($row['AMOUNT']/$cntops,2);
											$amtb[$i][$k]+=round($row['AMOUNT']/$cntops,2);
											$amttot[$i]+=round($row['AMOUNT']/$cntops,2);
										}
										else
										{
											$amt[$i][$j][$k]-=round($row['AMOUNT']/$cntops,2);
											$amta[$i][$j]-=round($row['AMOUNT']/$cntops,2);
											$amtb[$i][$k]-=round($row['AMOUNT']/$cntops,2);
											$amttot[$i]-=round($row['AMOUNT']/$cntops,2);
										}
									}
								}
							}
						}
					}
				}
			}while($row=mysql_fetch_array($res));
		}
		if($brancharr)
			$smarty->assign("BRANCH","Y");

		$smarty->assign("amt",$amt);
		$smarty->assign("amta",$amta);
		$smarty->assign("amtb",$amtb);
		$smarty->assign("amttot",$amttot);
		$smarty->assign("modearr",$modearr);
		$smarty->assign("brancharr",$brancharr);
		$smarty->assign("operatorarr",$operatorarr);

		$smarty->display("crm_users_claim_record.htm");
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
                $smarty->display("crm_users_claim_record.htm");
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
