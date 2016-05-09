<?php
include("connect.inc");
$db=connect_misdb();
$db2=connect_master();

if(authenticated($cid))
{
	$smarty->assign("cid",$cid);
	if($CMDGo)
	{
		$smarty->assign("flag",1);
		$st_date=$year."-".$month."-01 00:00:00";
		$end_date=$year."-".$month."-31 23:59:59";

		$smarty->assign("paymode",$paymode);
		if($paymode=='CDNUM')
		{
			$sql="SELECT COUNT(*) as cnt,PROFILEID,CD_NUM FROM billing.PAYMENT_DETAIL WHERE MODE IN ('DD','CHEQUE') AND ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND STATUS='DONE' GROUP BY CD_NUM HAVING cnt > 1";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
			while($row=mysql_fetch_array($res))
			{
				$cdarr[]=$row['CD_NUM'];
			}

			if($cdarr)
			{
				$k=0;
				$cdstr="'".implode("','",$cdarr)."'";
				$sql="SELECT PROFILEID,BILLID,RECEIPTID,ENTRY_DT,CD_NUM,CD_DT,CD_CITY,AMOUNT,MODE,STATUS,ENTRYBY,TYPE FROM billing.PAYMENT_DETAIL WHERE CD_NUM IN ($cdstr) ORDER BY PROFILEID ,ENTRY_DT ASC";
				$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
				while($row=mysql_fetch_array($res))
				{
					$profileid=$row['PROFILEID'];
					if($profileid!=$oldprofileid)
						$i=0;
					if($i==0 || ($arr[$k-1]["cd_city"]==$row['CD_CITY'] && $arr[$k-1]["cdnum"]==$row['CD_NUM']))
					{
						$getin=1;
						$i++;
					}
					if($getin)
					{
						$sql="SELECT USERNAME FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
						$res1=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
						if($row1=mysql_fetch_array($res1))
							$arr[$k]["username"]=$row1['USERNAME'];
						else
							$arr[$k]["username"]="Not found";
						$arr[$k]["profileid"]=$profileid;
						$arr[$k]["billid"]=$row['BILLID'];
						$arr[$k]["receiptid"]=$row['RECEIPTID'];
						$arr[$k]["entry_dt"]=$row['ENTRY_DT'];
						$arr[$k]["cdnum"]=$row['CD_NUM'];
						$arr[$k]["cd_dt"]=$row['CD_DT'];
						$arr[$k]["cd_city"]=$row['CD_CITY'];
						$arr[$k]["amt"]=$row['AMOUNT'];
						$arr[$k]["mode"]=$row['MODE'];
						$arr[$k]["status"]=$row['STATUS'];
						$arr[$k]["entryby"]=$row['ENTRYBY'];
						$arr[$k]["type"]=$row['TYPE'];
						$k++;
					}
					$oldprofileid=$profileid;
				}
			}
		}
		elseif($paymode=='CASH')
		{
			$sql="SELECT COUNT(*) as cnt, PROFILEID, ENTRY_DT,AMOUNT , MODE FROM billing.PAYMENT_DETAIL WHERE MODE='CASH' AND STATUS='DONE' AND AMOUNT>0 AND ENTRY_DT BETWEEN '$st_date' AND '$end_date' GROUP BY PROFILEID,MODE,AMOUNT HAVING cnt>1";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
			while($row=mysql_fetch_array($res))
			{
				$profilearr[]=$row['PROFILEID'];
			}
		}
		elseif($paymode=='ONLINE')
		{
			$sql="SELECT COUNT(*) as cnt, PROFILEID, ENTRY_DT , MODE FROM billing.PAYMENT_DETAIL WHERE MODE='ONLINE' AND STATUS='DONE' AND ENTRY_DT BETWEEN '$st_date' AND '$end_date' GROUP BY PROFILEID HAVING cnt>1";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
			while($row=mysql_fetch_array($res))
			{
				$profilearr[]=$row['PROFILEID'];
			}
		}
		if($profilearr)
		{
			$k=0;
			$ts=30*24*60*60;
			$profilestr=implode(",",$profilearr);
			$sql="SELECT PROFILEID,BILLID,RECEIPTID,ENTRY_DT,AMOUNT,STATUS,ENTRYBY,MODE,TYPE FROM billing.PAYMENT_DETAIL WHERE PROFILEID IN ($profilestr) AND STATUS='DONE' AND AMOUNT>0 ORDER BY PROFILEID,ENTRY_DT ASC";
			$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
			while($row=mysql_fetch_array($res))
			{
				$profileid=$row['PROFILEID'];

				$sql="SELECT USERNAME FROM newjs.JPROFILE WHERE PROFILEID=$profileid";
				$res1=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
				$row1=mysql_fetch_array($res1);

				if($profileid!=$oldprofileid)
				{
					$k++;
					$i=0;

					if($row1)
						$username[$i]=$row1['USERNAME'];
					else
						$username[$i]="No Record";
					$entry_dt[$i]=$row['ENTRY_DT'];
					$billid[$i]=$row['BILLID'];
					$receiptid[$i]=$row['RECEIPTID'];
					$status[$i]=$row['STATUS'];
					$entryby[$i]=$row['ENTRYBY'];
					$mode[$i]=$row['MODE'];
					$amt[$i]=$row['AMOUNT'];
					$type[$i]=$row['TYPE'];
				}
				else
				{
					if($row1)
						$username[$i]=$row1['USERNAME'];
					else
						$username[$i]="No Record";
					$entry_dt[$i]=$row['ENTRY_DT'];
					$billid[$i]=$row['BILLID'];
					$receiptid[$i]=$row['RECEIPTID'];
					$status[$i]=$row['STATUS'];
					$entryby[$i]=$row['ENTRYBY'];
					$mode[$i]=$row['MODE'];
					$amt[$i]=$row['AMOUNT'];
					$type[$i]=$row['TYPE'];

					if($i>0)
					{
						list($odt,$otime)=explode(" ",$entry_dt[$i-1]);
						list($ndt,$ntime)=explode(" ",$entry_dt[$i]);
						list($yy1,$mm1,$dd1)=explode("-",$odt);
						list($hr1,$min1,$sec1)=explode(":",$otime);
						list($yy2,$mm2,$dd2)=explode("-",$ndt);
						list($hr2,$min2,$sec2)=explode(":",$ntime);
						$ts1=mktime($hr1,$min1,$sec1,$mm1,$dd1,$yy1);
						$ts2=mktime($hr2,$min2,$sec2,$mm2,$dd2,$yy2);
						if($ts2-$ts1<=$ts)
						{
//							if(($mode[$i]=='CASH' && $mode[$i-1]=='CASH' && $amt[$i]==$amt[$i-1]) || $mode[$i]=='ONLINE')
							if($paymode=='ONLINE' || ($paymode=='CASH' && $amt[$i]==$amt[$i-1]))
							{
								$getin=1;
							}
							if($getin)
							{
								$arr[$k-1]["username"]=$username[$i-1];
								$arr[$k-1]["profileid"]=$oldprofileid;
								$arr[$k-1]["entry_dt"]=$entry_dt[$i-1];
								$arr[$k-1]["billid"]=$billid[$i-1];
								$arr[$k-1]["receiptid"]=$receiptid[$i-1];
								$arr[$k-1]["status"]=$status[$i-1];
								$arr[$k-1]["entryby"]=$entryby[$i-1];
								$arr[$k-1]["mode"]=$mode[$i-1];
								$arr[$k-1]["amt"]=$amt[$i-1];
								$arr[$k-1]["type"]=$type[$i-1];

								$arr[$k]["username"]=$username[$i];
								$arr[$k]["profileid"]=$profileid;
								$arr[$k]["entry_dt"]=$entry_dt[$i];
								$arr[$k]["billid"]=$billid[$i];
								$arr[$k]["receiptid"]=$receiptid[$i];
								$arr[$k]["status"]=$status[$i];
								$arr[$k]["entryby"]=$entryby[$i];
								$arr[$k]["mode"]=$mode[$i];
								$arr[$k]["amt"]=$amt[$i];
								$arr[$k]["type"]=$type[$i];
								$k++;
							}
						}
					}
				}
				$oldprofileid=$profileid;
				$i++;
			}
		}
		$smarty->assign("arr",$arr);
		unset($arr);

		$smarty->assign("month",$month);
		$smarty->assign("year",$year);
		$smarty->display("duplicate_entry_mis.htm");
	}
	else
	{
		for($i=0;$i<12;$i++)
		{
			$mmarr[$i]=$i+1;
		}

		for($i=2004;$i<=date("Y");$i++)
{
        $yyarr[$i-2004]=$i;
}

		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("yyarr",$yyarr);
		$smarty->display("duplicate_entry_mis.htm");
	}
}
else
{
        $smarty->display("jsconnectError.tpl");
}
?>
