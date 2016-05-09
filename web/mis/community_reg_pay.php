<?php
/****
        File 		:	community_reg_pay.php
        Description 	:   	This gives details of profiles becoming paid members from December 1, 2004 onwards and
                        	which community they belong to
        Created by 	: 	Shiv
        Modified On 	: 	May 26, 2004 by shiv
        Modifications 	: 	Query which picks up paid members is optimized, earlier there was no left join
****/

//include("connect.inc");
//$db=connect_misdb();
//$db2=connect_master();

if(authenticated($cid))
{
	//$sql="SELECT COUNT(DISTINCT p.PROFILEID) as cnt,j.MTONGUE,j.COUNTRY_RES FROM newjs.JPROFILE j,billing.PURCHASES p WHERE j.ENTRY_DT>='2004-12-01' AND j.ACTIVATED IN ('Y','H','D') AND p.STATUS='DONE' AND j.PROFILEID=p.PROFILEID GROUP BY MTONGUE,COUNTRY_RES ORDER BY cnt DESC";

	$sql="SELECT COUNT(DISTINCT p.PROFILEID) as cnt,j.MTONGUE,j.COUNTRY_RES FROM billing.PURCHASES p LEFT JOIN newjs.JPROFILE j ON j.PROFILEID=p.PROFILEID WHERE j.ENTRY_DT>='2004-12-01' AND j.ACTIVATED IN ('Y','H','D') AND p.STATUS='DONE' GROUP BY MTONGUE,COUNTRY_RES ORDER BY cnt DESC";
	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
	if($row=mysql_fetch_array($res))
	{
		do
		{
			$mtongue=$row['MTONGUE'];
			if($mtongue==27)
			{
				if($row['COUNTRY_RES']!=51)
					$mtongue=1;
			}
			if(is_array($commarr))
			{
				if(!in_array($mtongue,$commarr))
				{
					$commarr[]=$mtongue;
				}
			}
			else
			{
				$commarr[]=$mtongue;
			}
			$i=array_search($mtongue,$commarr);
			$paid_cnt[$i]+=$row['cnt'];
			$paid_tot+=$row['cnt'];
		}while($row=mysql_fetch_array($res));
	}

	$sql="SELECT COUNT(*) as cnt,MTONGUE,COUNTRY_RES FROM newjs.JPROFILE WHERE ENTRY_DT>='2004-12-01' AND ACTIVATED IN ('Y','H','D') GROUP BY MTONGUE,COUNTRY_RES";
	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
	if($row=mysql_fetch_array($res))
	{
		do
		{
			$mtongue=$row['MTONGUE'];
			if($mtongue==27)
			{
				if($row['COUNTRY_RES']!=51)
					$mtongue=1;
			}
			$i=array_search($mtongue,$commarr);
			$reg_cnt[$i]+=$row['cnt'];
			$reg_tot+=$row['cnt'];
		}while($row=mysql_fetch_array($res));
	}

	for($i=0;$i<count($commarr);$i++)
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

		$sql="SELECT SMALL_LABEL FROM newjs.MTONGUE WHERE VALUE='$commarr[$i]'";
		$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
		$row=mysql_fetch_array($res);
		$commarr[$i]=$row['SMALL_LABEL'];
	}

	$smarty->assign("commarr",$commarr);
	$smarty->assign("paid_cnt",$paid_cnt);
	$smarty->assign("paid_tot",$paid_tot);
	$smarty->assign("reg_cnt",$reg_cnt);
	$smarty->assign("reg_tot",$reg_tot);
	$smarty->assign("percent",$percent);
	$smarty->assign("pertot",$pertot);
	//$smarty->assign("jtotpercent",$jtotpercent);
	$smarty->assign("select","community");
	$smarty->assign("cid",$cid);
	$smarty->display("community_reg_pay.htm");
}
else
{
	$smarty->assign("user",$username);
	$smarty->display("jsconnectError.tpl");
}
?>
