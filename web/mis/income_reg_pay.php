<?php
//include("connect.inc");
//$db=connect_misdb();
//$db2=connect_master();

if(authenticated($cid))
{
	//$sql="SELECT COUNT(DISTINCT p.PROFILEID) as cnt,j.INCOME FROM newjs.JPROFILE j,billing.PURCHASES p WHERE j.ENTRY_DT>='2004-12-01' AND j.ACTIVATED IN ('Y','H','D') AND p.STATUS='DONE' AND j.PROFILEID=p.PROFILEID GROUP BY INCOME ORDER BY cnt DESC";
	$sql="SELECT COUNT(DISTINCT p.PROFILEID) as cnt,j.INCOME FROM billing.PURCHASES p LEFT JOIN newjs.JPROFILE j ON j.PROFILEID=p.PROFILEID WHERE j.ENTRY_DT>='2004-12-01' AND j.ACTIVATED IN ('Y','H','D') AND p.STATUS='DONE' GROUP BY INCOME ORDER BY cnt DESC";
	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
	if($row=mysql_fetch_array($res))
	{
		$incomearr[]="Other";
		do
		{
			if($row['INCOME']==0)
			{
				$i=0;
			}
			else
			{
				$income=get_income($row['INCOME']);
				if(!in_array($income,$incomearr))
					$incomearr[]=$income;
				$i=array_search($income,$incomearr);
			}
			$paid_cnt[$i]+=$row['cnt'];
			$paid_tot+=$row['cnt'];
		}while($row=mysql_fetch_array($res));
	}

	$sql="SELECT COUNT(*) as cnt,INCOME FROM newjs.JPROFILE WHERE ENTRY_DT>='2004-12-01' AND ACTIVATED IN ('Y','H','D') GROUP BY INCOME";
	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
	if($row=mysql_fetch_array($res))
	{
		do
		{
			if($row['INCOME']==0)
			{
				$i=0;
			}
			else
			{
				$income=get_income($row['INCOME']);
				$i=array_search($income,$incomearr);
			}
			$reg_cnt[$i]+=$row['cnt'];
			$reg_tot+=$row['cnt'];
		}while($row=mysql_fetch_array($res));
	}

	for($i=0;$i<count($incomearr);$i++)
	{
		if($reg_cnt[$i])
		{
			$percent[$i]=$paid_cnt[$i]/$reg_cnt[$i] * 100;
			$percent[$i]=round($percent[$i],1);
		}
		if($reg_tot)
		{
			$pertot=$paid_tot/$reg_tot * 100;
			$pertot=round($pertot,1);
		}
	}

	$smarty->assign("incomearr",$incomearr);
	$smarty->assign("paid_cnt",$paid_cnt);
	$smarty->assign("paid_tot",$paid_tot);
	$smarty->assign("reg_cnt",$reg_cnt);
	$smarty->assign("reg_tot",$reg_tot);
	$smarty->assign("percent",$percent);
	$smarty->assign("pertot",$pertot);
	$smarty->assign("select","income");
	$smarty->assign("cid",$cid);
	$smarty->display("income_reg_pay.htm");
}
else
{
	$smarty->assign("user",$username);
	$smarty->display("jsconnectError.tpl");
}

function get_income($val)
{
	global $db;

	$sql="SELECT LABEL FROM newjs.INCOME WHERE VALUE='$val'";
	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
	$row=mysql_fetch_array($res);
	$income=$row['LABEL'];

	return $income;
}
?>
