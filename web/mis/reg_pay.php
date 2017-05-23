<?php
include("connect.inc");
$db=connect_misdb();
$db2=connect_master();

$data=authenticated($checksum);

if(isset($data))
{
	$jmmarr=array('Dec','Jan','Feb','Mar','Apr','May','Jun');

	$sql="SELECT count(*) as cnt, MONTH(ENTRY_DT) as mm, YEAR(ENTRY_DT) as yy FROM newjs.JPROFILE WHERE INCOMPLETE<>'Y' AND ACTIVATED IN ('Y','H','D') AND ENTRY_DT>='2004-12-01 00:00:00' GROUP BY yy,mm ORDER BY yy DESC , mm DESC";
	$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
	if($row=mysql_fetch_array($res))
	{
		do
		{
			$cnt=$row['cnt'];
			$mm=$row['mm'];
			$yy=$row['yy'];

			if($yy==2004)
			{
				if($mm==12)
					$mm=0;
			}
			$tot[$mm]["reg"]+=$cnt;
		}while($row=mysql_fetch_array($res));
	}

//	$sql="SELECT count(*) as cnt, MONTH(j.ENTRY_DT) as jmm, YEAR(j.ENTRY_DT) as jyy,MONTH(p.ENTRY_DT) as pmm, YEAR(p.ENTRY_DT) as pyy FROM newjs.JPROFILE j,billing.PAYMENT_DETAIL p WHERE INCOMPLETE<>'Y' AND ACTIVATED<>'D' AND j.PROFILEID=p.PROFILEID AND j.ENTRY_DT>='2004-12-01 00:00:00' AND jyy=pyy AND jmm=pmm GROUP BY jyy,jmm,pyy,pmm";

	//$sql="SELECT count(DISTINCT p.PROFILEID) as cnt, MONTH(j.ENTRY_DT) as jmm, YEAR(j.ENTRY_DT) as jyy,MONTH(p.ENTRY_DT) as pmm, YEAR(p.ENTRY_DT) as pyy FROM newjs.JPROFILE j,billing.PURCHASES p WHERE INCOMPLETE<>'Y' AND j.PROFILEID=p.PROFILEID AND j.ENTRY_DT>='2004-12-01 00:00:00' AND p.STATUS='DONE' GROUP BY jyy,jmm,pyy,pmm";

	$sql="SELECT count(DISTINCT p.PROFILEID) as cnt, MONTH(j.ENTRY_DT) as jmm, YEAR(j.ENTRY_DT) as jyy,MONTH(p.ENTRY_DT) as pmm, YEAR(p.ENTRY_DT) as pyy FROM billing.PURCHASES p LEFT JOIN newjs.JPROFILE j ON j.PROFILEID=p.PROFILEID WHERE INCOMPLETE<>'Y' AND j.ENTRY_DT>='2004-12-01 00:00:00' AND p.STATUS='DONE' GROUP BY jyy,jmm,pyy,pmm";
	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
	if($row=mysql_fetch_array($res))
	{
		do
		{
			$cnt=$row['cnt'];
			$jmm=$row['jmm'];
			$jyy=$row['jyy'];
			$pmm=$row['pmm'];
			$pyy=$row['pyy'];

			if($jyy==2004)
			{
				if($jmm==12)
					$jmm=0;
			}
			$tot[$jmm]["paid"]+=$cnt;
		}while($row=mysql_fetch_array($res));
	}

	for($i=0;$i<count($jmmarr);$i++)
	{
		if($tot[$i]["reg"])
			$tot[$i]["percent"]=($tot[$i]["paid"]/$tot[$i]["reg"]) * 100;
		$tot[$i]["percent"]=round($tot[$i]["percent"],1);
	}

	$smarty->assign("jmmarr",$jmmarr);
	$smarty->assign("tot",$tot);
	$smarty->display("reg_pay.htm");
}
else
{
	$smarty->assign("user",$username);
	$smarty->display("jsconnectError.tpl");
}
?>
