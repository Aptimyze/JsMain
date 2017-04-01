<?php
include("connect.inc");
include_once("../profile/pg/functions.php");

$db=connect_misdb();

$data=authenticated($checksum);
$flag=0;

if(isset($data))
{
	if($CMDGo)
	{
		$start_date = $st_yr."-".$st_mt."-".$st_dt;
		$end_date = $end_yr."-".$end_mt."-".$end_dt;
		$flag=1;

		$sql = "SELECT p.USERNAME, pd.BILLID, pd.MODE, pd.TYPE, pd.AMOUNT, pd.CD_NUM, pd.CD_DT, pd.CD_CITY, pd.ENTRY_DT, pd.ENTRYBY, pd.DOL_CONV_RATE FROM billing.PURCHASES p, billing.PAYMENT_DETAIL pd WHERE p.BILLID=pd.BILLID AND (pd.STATUS='REFUND' OR pd.STATUS='CHARGE_BACK') AND pd.ENTRY_DT BETWEEN '$start_date' AND '$end_date'";
		$res = mysql_query_decide($sql) or die($sql.mysql_error_js());
		$i=0;
		if(mysql_num_rows($res) > 0)
		{
			while($row = mysql_fetch_array($res))
			{
				$details[$i]['USERNAME'] = $row['USERNAME'];
				$details[$i]['BILLID'] = $row['BILLID'];
				$details[$i]['MODE'] = $row['MODE'];
				$details[$i]['AMOUNT'] = $row['TYPE']." ".$row['AMOUNT'];
				if("CHEQUE" == $row['MODE'])
				{
					$details[$i]['CD_NUM'] = $row['CD_NUM'];
					$details[$i]['CD_DT'] = $row['CD_DT'];
					$details[$i]['CD_CITY'] = $row['CD_CITY'];
				}
				$details[$i]['ENTRYBY'] = $row['ENTRYBY'];
				$details[$i]['ENTRY_DT'] = $row['ENTRY_DT'];

				if("DOL" == $row['TYPE'])
					$total_refund += $row['DOL_CONV_RATE'] * $row['AMOUNT'];
				else
					$total_refund += $row['AMOUNT'];
				$i++;
			}
		}
		else
			$smarty->assign("NO_RESULT",1);

		$smarty->assign("start_date",$start_date);
		$smarty->assign("end_date",$end_date);
		$smarty->assign("flag",$flag);
		$smarty->assign("details",$details);
		$smarty->assign("total_refund",$total_refund);
	}
	else
	{
		list($curyear,$curmonth,$curday) = explode("-",date("Y-m-d"));
		$last_day = date("t");

		for($i=1;$i<=31;$i++)
			$ddarr[] = $i;

		for($i=1;$i<=12;$i++)
			$mmarr[] = $i;

		for($i=2004;$i<$curyear+2;$i++)
			$yyarr[]=$i;

		$smarty->assign("flag","0");
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("yyarr",$yyarr);
		$smarty->assign("curday",$curday);
		$smarty->assign("curmonth",$curmonth);
		$smarty->assign("curyear",$curyear);
		$smarty->assign("last_day",$last_day);
	}
	$smarty->assign("CHECKSUM",$checksum);
	$smarty->display("refund_user_details.htm");
}
else
{
        $smarty->assign("user",$username);
        $smarty->display("jsconnectError.tpl");
}
?>
