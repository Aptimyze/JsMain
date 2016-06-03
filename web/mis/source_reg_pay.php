<?php
/****
	File 		: 	source_reg_pay.php
	Description 	:   	This gives details of profiles becoming paid members from December 1, 2004 onwards and 
				from which source they registered
	Created by 	: 	Shiv 
	Modified On 	: 	May 26, 2004 by shiv
	Modifications 	: 	Query which picks up paid members is optimized, earlier there was no left join
****/

//include("connect.inc");
//$db=connect_misdb();
//$db2=connect_master();

if(authenticated($cid))
{
	//$sql="SELECT COUNT(DISTINCT p.PROFILEID) as cnt,j.SOURCE FROM newjs.JPROFILE j,billing.PURCHASES p WHERE j.ENTRY_DT>='2004-12-01' AND j.ACTIVATED IN ('Y','H','D') AND p.STATUS='DONE' AND j.PROFILEID=p.PROFILEID GROUP BY SOURCE ORDER BY cnt DESC";

	$sql="SELECT COUNT(DISTINCT p.PROFILEID) as cnt,j.SOURCE FROM billing.PURCHASES p LEFT JOIN newjs.JPROFILE j ON j.PROFILEID=p.PROFILEID WHERE j.ENTRY_DT>='2004-12-01' AND j.ACTIVATED IN ('Y','H','D') AND p.STATUS='DONE' GROUP BY SOURCE ORDER BY cnt DESC";
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
			$paid_cnt[$i]+=$row['cnt'];
			$paid_tot+=$row['cnt'];
		}while($row=mysql_fetch_array($res));
	}

	$sql="SELECT COUNT(*) as cnt,SOURCE FROM newjs.JPROFILE WHERE ENTRY_DT>='2004-12-01' AND ACTIVATED IN ('Y','H','D') GROUP BY SOURCE";
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
			$reg_cnt[$i]+=$row['cnt'];
			$reg_tot+=$row['cnt'];
		}while($row=mysql_fetch_array($res));
	}

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

	$smarty->assign("srcarr",$srcarr);
	$smarty->assign("paid_cnt",$paid_cnt);
	$smarty->assign("paid_tot",$paid_tot);
	$smarty->assign("reg_cnt",$reg_cnt);
	$smarty->assign("reg_tot",$reg_tot);
	$smarty->assign("percent",$percent);
	$smarty->assign("pertot",$pertot);
	$smarty->assign("select","source");
	$smarty->assign("cid",$cid);
	$smarty->display("source_reg_pay.htm");
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
