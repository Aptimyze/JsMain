<?
include("connect.inc");

$db=connect_misdb();
$db2=connect_master();

if(authenticated($cid))
{
	if($CMDGo)
	{
		$start_dt=$year."-".$month."-".$day." 00:00:00";
		$end_dt=$year2."-".$month2."-".$day2." 23:59:59";

		$date1=$day."-".$month."-".$year;
		$date2=$day2."-".$month2."-".$year2;
		$smarty->assign("DATE1",$date1);
		$smarty->assign("DATE2",$date2);

		$i=0;
		$sql="SELECT a.RECEIPTID,b.USERNAME,b.SERVICEID,b.CENTER,a.PROFILEID,a.BILLID,a.MODE,a.SOURCE,a.TYPE,a.AMOUNT ,a.CD_NUM,a.CD_DT,a.CD_CITY,a.BANK,a.OBANK,a.STATUS,a.BOUNCE_DT,a.ENTRY_DT,a.ENTRYBY,b.WALKIN,UPPER(a.DEPOSIT_BRANCH) as DEPOSIT_BRANCH, a.TRANS_NUM FROM billing.PAYMENT_DETAIL a, billing.PURCHASES b WHERE a.BILLID=b.BILLID AND a.ENTRY_DT BETWEEN '$start_dt' AND '$end_dt' AND a.STATUS='DONE' ";

                $sql.=" AND a.MODE IN('CHEQUE','DD') ";

		if($branch!="")
		{
			$branch_str="'".implode("','",$branch)."'";
			if($branch_str!="'All'")
				$sql.=" AND a.DEPOSIT_BRANCH IN($branch_str) ";
		}
		$sql.=" ORDER BY a.DEPOSIT_BRANCH,a.CD_CITY ";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
				if($last_sort!=$row['DEPOSIT_BRANCH'])
				{
					$arr[$i-1]['chq_cnt']=$chq_cnt;
					$chq_cnt=0;
					$arr[$i]['new_tab']='Y';
					$arr[$i]['last_sort']=$row['DEPOSIT_BRANCH'];
					$arr[$i-1]['tot_sort_amt_rs']=$tot_sort_amt_rs;
					$tot_sort_amt_rs=0;
					$arr[$i-1]['tot_sort_amt_dol']=$tot_sort_amt_dol;
					$tot_sort_amt_dol=0;
					
				}
			$last_sort=$row['DEPOSIT_BRANCH'];
			$arr[$i]['client']=$row['USERNAME'];
			$arr[$i]['receiptid']=$row['RECEIPTID'];
			$arr[$i]['mode']=$row['MODE'];
			$arr[$i]['source']=$row['SOURCE'];
			$arr[$i]['type']=$row['TYPE'];
			$arr[$i]['amt']=$row['AMOUNT'];
			if($row['TYPE']=='RS')
			{
				$tot_sort_amt_rs+=$row['AMOUNT'];
				$tot_rs+=$row['AMOUNT'];
			}
			else
			{
				$tot_sort_amt_dol+=$row['AMOUNT'];
				$tot_dol+=$row['AMOUNT'];
			}
			$cd_dt=$row['CD_DT'];
			list($yy,$mm,$dd)=explode("-",$cd_dt);
			$arr[$i]["cd_dt"]=$dd."/".$mm."/".$yy;
			$arr[$i]['cd_num']=$row['CD_NUM'];
			$arr[$i]['cd_city']=$row['CD_CITY'];
			$arr[$i]['bank']=$row['BANK'];
			
			list($edt,$time)=explode(" ",$row['ENTRY_DT']);
			list($yy,$mm,$dd)=explode("-",$edt);
			$arr[$i]["entry_dt"]=$dd."/".$mm."/".$yy;
			$arr[$i]['sale_by']=$row['SALE_BY'];
			$arr[$i]['deposit_dt']=$row['DEPOSIT_DT'];
			$arr[$i]['deposit_branch']=$row['DEPOSIT_BRANCH'];
			$arr[$i]['transaction_number']=$row['TRANS_NUM'];
			$chq_cnt++;
			$i++;
		}
		$arr[$i-1]['tot_sort_amt_rs']=$tot_sort_amt_rs;	
		$arr[$i-1]['tot_sort_amt_dol']=$tot_sort_amt_dol;
		$arr[$i-1]['chq_cnt']=$chq_cnt;	
		$smarty->assign("arr",$arr);
		$smarty->assign("tot_rs",$tot_rs);
		$smarty->assign("tot_dol",$tot_dol);
		$smarty->assign("cid",$cid);
		$smarty->assign("flag","1");
		$smarty->display("cheque_dd_list.htm");
	}
	else
	{
		$dt_arr=explode("-",Date('Y-m-d'));
		for($i=0;$i<12;$i++)
		{
			$mmarr[$i]=$i+1;
		}

		for($i=0;$i<31;$i++)
		{
			$ddarr[$i]=$i+1;
		}

		for($i=2005;$i<=date('Y')+1;$i++)
                        $yyarr[] = $i;

		$sql="SELECT NAME FROM billing.BRANCHES";
		$res=mysql_query_decide($sql) or die(mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$branch[]=strtoupper($row['NAME']);
		}
														     
		$smarty->assign("dt",$dt_arr[2]);
		$smarty->assign("mm",$dt_arr[1]);
		$smarty->assign("yy",$dt_arr[0]);
		$smarty->assign("cid",$cid);
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("yyarr",$yyarr);
		$smarty->assign("branch",$branch);
		$smarty->assign("employee",$employee);

		$smarty->display("cheque_dd_list.htm");
	}
}
else
{
	$smarty->display("jsconnectError.tpl");
}


?>
