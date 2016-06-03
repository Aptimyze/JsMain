<?php
include("connect.inc");
$db=connect_misdb();
$db2=connect_master();

$data=authenticated($checksum);
$flag=0;
$lflag=0;
if(isset($data))
{
	if($CMDGo)
	{
		$flag=1;
                $yearp1=$year+1;
                $mmarr=array('Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec','Jan','Feb','Mar');
                if($branch!="ALL")
                {
                        $bflag='N';

			if($location=="L")
			{
				$lflag=1;

				$sql="SELECT COUNT(*) as total_cheque,STATUS,billing.PAYMENT_DETAIL.CD_CITY as ccity FROM billing.PAYMENT_DETAIL WHERE billing.PAYMENT_DETAIL.ENTRY_DT BETWEEN 'CURDATE()' AND 'DATE_SUB(CURDATE(),INTERVAL 90 DAY)' GROUP BY ccity";

				$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
				if($row=mysql_fetch_array($res))
				{
					$j=0;
					do
					{
						$ccity=$row['ccity'];
						if(is_array($ccityarr))
						{
							if(!in_array($ccity,$ccityarr))
							{
								$ccityarr[]=$ccity;
							}
						}
						else
						{
							$ccity[]=$row['ccity'];
						}

						$i=array_search($ccity,$ccityarr);
						if($row['STATUS']=='BOUNCE')
                                                {
                                                        $bounce_count[$i]+=$row['cnt'];
                                                        $tot_bounce+=$bounce_count[$i];
                                                }
						$total_cheque_count[$i]+=$row['cnt'];
						$tot+=$total_cheque_count[$i];
						$j++;
					}while($row=mysql_fetch_array($res));
				}
			}
			else
			{
				$sql="SELECT COUNT(*) as cnt,month(billing.PAYMENT_DETAIL.ENTRY_DT) as mm,billing.PAYMENT_DETAIL.STATUS FROM billing.PAYMENT_DETAIL,billing.PURCHASES WHERE billing.PAYMENT_DETAIL.ENTRY_DT BETWEEN '$year-04-01 00:00:00' AND '$yearp1-03-31 23:59:59' AND billing.PURCHASES.CENTER='$branch' AND billing.PURCHASES.BILLID=billing.PAYMENT_DETAIL.BILLID AND billing.PAYMENT_DETAIL.MODE='CHEQUE' GROUP BY mm,STATUS";

				$res=mysql_query_decide($sql,$db) or die(mysql_error_js());

	                        if($row=mysql_fetch_array($res))
        	                {
                	                $tot=0;
					$tot_bounce=0;
					do
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
						if($row['STATUS']=='BOUNCE')
						{
							$bounce_count[$mm]+=$row['cnt'];
							$tot_bounce+=$bounce_count[$mm];
						}
//						else
//							$bounce_count[$mm]=0;

						$total_cheque_count[$mm]+=$row['cnt'];
						$tot+=$row['cnt'];
					}while($row=mysql_fetch_array($res));
				}
                        }
                        $smarty->assign("branch",$branch);
                        $smarty->assign("ccity",$ccity);
                        $smarty->assign("year",$year);
                        $smarty->assign("yearp1",$yearp1);
                        $smarty->assign("flag",$flag);
                        $smarty->assign("lflag",$lflag);
                        $smarty->assign("bflag",$bflag);
                        $smarty->assign("mmarr",$mmarr);
                        $smarty->assign("bounce_count",$bounce_count);
                        $smarty->assign("tot_bounce",$tot_bounce);
                        $smarty->assign("total_cheque_count",$total_cheque_count);
                        $smarty->assign("tot",$tot);
			$smarty->display("bounce_cheque_mis.htm");
                }
                else
                {
                        $bflag='A';
                        $sql_b="SELECT NAME FROM billing.BRANCHES";
                        $res_b=mysql_query_decide($sql_b,$db) or die(mysql_error_js());
                        while($row_b=mysql_fetch_array($res_b))
                        {
	                        $brancharr[]=strtoupper($row_b['NAME']);
			}

			if($location=="L")
			{
				$lflag=1;
				$sql="SELECT COUNT(*) as total_count,month(billing.PAYMENT_DETAIL.ENTRY_DT) as mm,billing.PAYMENT_DETAIL.CD_CITY as ccity,billing.PAYMENT_DETAIL.STATUS FROM billing.PAYMENT_DETAIL,billing.PURCHASES WHERE billing.PAYMENT_DETAIL.ENTRY_DT BETWEEN '$year-04-01 00:00:00' AND '$yearp1-03-31 23:59:59' AND billing.PAYMENT_DETAIL.MODE='CHEQUE' AND billing.PURCHASES.BILLID=billing.PAYMENT_DETAIL.BILLID GROUP BY mm,ccity,STATUS";

				$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
				if($row=mysql_fetch_array($res))
				{
					do
					{
						$status=$row['STATUS'];
						$ccity=strtoupper($row['ccity']);
						$mm=$row['mm'];
						if($mm<=3)
						{
							$mm+=8;
						}
						else
						{
							$mm-=4;
						}

						if(is_array($locarr))
						{
							if(!in_array($ccity,$locarr))
							{
								$locarr[]=$ccity;
							}
						}
						else
						{
							$locarr[]=$ccity;
						}

						$i=array_search($ccity,$locarr);

						if($status=='BOUNCE')
						{
							$bounce_count[$i][$mm]+=$row['total_count'];
							$bounce_count1[$mm][$i]+=$row['total_count'];
							$total_bounce[$i]+=$bounce_count[$i][$mm];
							$total_bounce1[$mm]+=$bounce_count1[$mm][$i];
						}
//						else
//							$bounce_count[$i][$mm]=0;

						$total[$i][$mm]+=$row['total_count'];
						$total1[$i][$mm]=$row['total_count'];
						$tot1[$mm][$i]=$row['total_count'];
						$tota[$i]+=$total1[$i][$mm];
						$totb[$mm]+=$tot1[$mm][$i];
					}while($row=mysql_fetch_array($res));
				}
			}
			else
			{
				$sql="SELECT COUNT(*) as total_count,month(billing.PAYMENT_DETAIL.ENTRY_DT) as mm,billing.PAYMENT_DETAIL.STATUS,billing.PURCHASES.CENTER as center FROM billing.PAYMENT_DETAIL,billing.PURCHASES WHERE billing.PAYMENT_DETAIL.ENTRY_DT BETWEEN '$year-04-01 00:00:00' AND '$yearp1-03-31 23:59:59' AND billing.PURCHASES.BILLID=billing.PAYMENT_DETAIL.BILLID AND billing.PAYMENT_DETAIL.MODE='CHEQUE' GROUP BY mm,center,STATUS";

				$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
				if($row=mysql_fetch_array($res))
				{
					do
					{
						$center=strtoupper($row['center']);
						$i=array_search($center,$brancharr);
						$mm=$row['mm'];
						if($mm<=3)
						{
							$mm+=8;
						}
						else
						{
							$mm-=4;
						}
						if($row['STATUS']=='BOUNCE')
						{
							$bounce_count[$i][$mm]=$row['total_count'];
							$bounce_count1[$mm][$i]=$row['total_count'];
							$total_bounce[$i]+=$bounce_count[$i][$mm];
							$total_bounce1[$mm]+=$bounce_count1[$mm][$i];
						}
//						else
//							$bounce_count[$i][$mm]=0;

						$total[$i][$mm]+=$row['total_count'];
						$total1[$i][$mm]=$row['total_count'];
						$tot1[$mm][$i]=$row['total_count'];
						$tota[$i]+=$total1[$i][$mm];
						$totb[$mm]+=$tot1[$mm][$i];
					}while($row=mysql_fetch_array($res));
				}
			}
			$smarty->assign("brancharr",$brancharr);
			$smarty->assign("locarr",$locarr);
                        $smarty->assign("year",$year);
                        $smarty->assign("yearp1",$yearp1);
                        $smarty->assign("ccity",$ccity);
                        $smarty->assign("flag",$flag);
                        $smarty->assign("lflag",$lflag);
                        $smarty->assign("bflag",$bflag);
                        $smarty->assign("mmarr",$mmarr);
                        $smarty->assign("total",$total);
                        $smarty->assign("total_bounce",$total_bounce);
                        $smarty->assign("total_bounce1",$total_bounce1);
                        $smarty->assign("bounce_count",$bounce_count);
                        $smarty->assign("tota",$tota);
                        $smarty->assign("totb",$totb);

                        $smarty->display("bounce_cheque_mis.htm");
                }
	}
	else
	{
		$user=getname($checksum);
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
			$sql="SELECT NAME FROM billing.BRANCHES";
			$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
			if($row=mysql_fetch_array($res))
			{
				$i=0;
				do
				{
					$branch[$i]=$row['NAME'];
					$i++;
				}while($row=mysql_fetch_array($res));
			}

			$smarty->assign("branch",$branch);
		}
		elseif(in_array('MC',$priv) || in_array('MD',$priv))
		{
			// run query : select branch of user
			$sql="SELECT CENTER FROM jsadmin.PSWRDS WHERE USERNAME='$user'";
			$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
			if($row=mysql_fetch_array($res))
			{
				$branch=strtoupper($row['CENTER']);
			}

			$smarty->assign("ONLYBRANCH","Y");
			$smarty->assign("branch",$branch);
		}

		$smarty->assign("priv",$priv);
//		$smarty->assign("mmarr",$mmarr);
                $smarty->assign("yyarr",$yyarr);
		$smarty->assign("CHECKSUM",$checksum);
		$smarty->display("bounce_cheque_mis.htm");
	}
}
else
{
        $smarty->assign("user",$username);
        $smarty->display("jsconnectError.tpl");
}
?>
