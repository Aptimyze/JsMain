<?php
include("connect.inc");
$db=connect_misdb();
$db2=connect_master();

if(authenticated($cid))
{
	$sql="SELECT COUNT(DISTINCT p.PROFILEID) as cnt,j.SOURCE,j.GENDER FROM newjs.JPROFILE j,billing.PURCHASES p WHERE j.COUNTRY_RES='88' AND j.ENTRY_DT>='2004-12-01' AND j.ACTIVATED IN ('Y','H','D') AND p.STATUS='DONE' AND j.PROFILEID=p.PROFILEID GROUP BY SOURCE,GENDER ORDER BY cnt DESC";
	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
	if($row=mysql_fetch_array($res))
	{
		$srcarr[]="EMPTY";
		do
		{
			$srcid=$row['SOURCE'];
			if($srcid)
			{
				$srcgp=get_source($srcid);
				if(!in_array($srcgp,$srcarr))
					$srcarr[]=$srcgp;
				$i=array_search($srcgp,$srcarr);
			}
			else
				$i=0;
			if($row['GENDER']=='M')
				$j=0;
			else
				$j=1;
			$paid_cnt[$i][$j]+=$row['cnt'];
			$paid_tota[$i]+=$row['cnt'];
			$paid_totb[$j]+=$row['cnt'];
			$paid_totall+=$row['cnt'];
		}while($row=mysql_fetch_array($res));
	}

	$sql="SELECT COUNT(*) as cnt,SOURCE,GENDER FROM newjs.JPROFILE WHERE COUNTRY_RES='88' AND ENTRY_DT>='2004-12-01' AND ACTIVATED IN ('Y','H','D') GROUP BY SOURCE,GENDER";
	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
	if($row=mysql_fetch_array($res))
	{
		do
		{
			$srcid=$row['SOURCE'];
			if($srcid)
			{
				$srcgp=get_source($srcid);
				$i=array_search($srcgp,$srcarr);
			}
			else
				$i=0;
			if($row['GENDER']=='M')
				$j=0;
			else
				$j=1;
			$reg_cnt[$i][$j]+=$row['cnt'];
			$reg_tota[$i]+=$row['cnt'];
			$reg_totb[$j]+=$row['cnt'];
			$reg_totall+=$row['cnt'];
		}while($row=mysql_fetch_array($res));
	}
/*
	for($i=0;$i<count($srcarr);$i++)
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
*/
	$smarty->assign("srcarr",$srcarr);
	$smarty->assign("paid_cnt",$paid_cnt);
	$smarty->assign("paid_totall",$paid_totall);
	$smarty->assign("paid_tota",$paid_tota);
	$smarty->assign("paid_totb",$paid_totb);
	$smarty->assign("reg_cnt",$reg_cnt);
	$smarty->assign("reg_tota",$reg_tota);
	$smarty->assign("reg_totb",$reg_totb);
	$smarty->assign("reg_totall",$reg_totall);
	$smarty->assign("percent",$percent);
	$smarty->assign("pertot",$pertot);
	$smarty->display("pak_reg_pay.htm");
}
else
{
	$smarty->assign("user",$username);
	$smarty->display("jsconnectError.tpl");
}

function get_source($sid)
{
	global $db;

	$sql="SELECT GROUPNAME FROM MIS.SOURCE WHERE SourceID='".addslashes($sid)."'";
	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
	if($row=mysql_fetch_array($res))
	{
		$srcgp=$row['GROUPNAME'];
	}
	else
		$srcgp="Other";

	return $srcgp;
}
?>
