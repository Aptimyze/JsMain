<?php
/****
        File            :       age_reg_pay.php
        Description     :       This gives details of profiles becoming paid members from December 1, 2004 onwards and
                                which age range they belong to
        Created by      :       Shiv
        Modified On     :       May 26, 2004 by shiv
        Modifications   :       Query which picks up paid members is optimized, earlier there was no left join
****/

//include("connect.inc");
//$db=connect_misdb();
//$db2=connect_master();

if(authenticated($cid))
{
	$genderarr=array('Male','Female');
	$agearr=array('18-21','22-25','26-29','30-33','34+');

	//$sql="SELECT COUNT(DISTINCT p.PROFILEID) as cnt,j.AGE,j.GENDER FROM newjs.JPROFILE j,billing.PURCHASES p WHERE j.ENTRY_DT>='2004-12-01' AND j.ACTIVATED IN ('Y','H','D') AND p.STATUS='DONE' AND j.PROFILEID=p.PROFILEID GROUP BY GENDER,AGE ORDER BY cnt DESC";

	$sql="SELECT COUNT(DISTINCT p.PROFILEID) as cnt,j.AGE,j.GENDER FROM billing.PURCHASES p LEFT JOIN newjs.JPROFILE j ON j.PROFILEID=p.PROFILEID WHERE j.ENTRY_DT>='2004-12-01' AND j.ACTIVATED IN ('Y','H','D') AND p.STATUS='DONE' GROUP BY GENDER,AGE ORDER BY cnt DESC";
	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
	if($row=mysql_fetch_array($res))
	{
		do
		{
			if($row['AGE']<=21)
				$k=0;
			elseif($row['AGE']>21 && $row['AGE']<=25)
				$k=1;
			elseif($row['AGE']>25 && $row['AGE']<=29)
				$k=2;
			elseif($row['AGE']>29 && $row['AGE']<=33)
				$k=3;
			else
				$k=4;

			if($row['GENDER']=='M')
				$i=0;
			elseif($row['GENDER']=='F')
				$i=1;

			$paid_cnt[$k][$i]+=$row['cnt'];
			$paid_tot[$k]+=$row['cnt'];
			$paid_tot1[$i]+=$row['cnt'];
			$paid_totall+=$row['cnt'];
		}while($row=mysql_fetch_array($res));
	}

	$sql="SELECT COUNT(*) as cnt,GENDER,AGE FROM newjs.JPROFILE WHERE ENTRY_DT>='2004-12-01' AND ACTIVATED IN ('Y','H','D') GROUP BY GENDER,AGE";
	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
	if($row=mysql_fetch_array($res))
	{
		do
		{
			if($row['AGE']<=21)
				$k=0;
			elseif($row['AGE']>21 && $row['AGE']<=25)
				$k=1;
			elseif($row['AGE']>25 && $row['AGE']<=29)
				$k=2;
			elseif($row['AGE']>29 && $row['AGE']<=33)
				$k=3;
			else
				$k=4;

			if($row['GENDER']=='M')
				$i=0;
			elseif($row['GENDER']=='F')
				$i=1;

			$reg_cnt[$k][$i]+=$row['cnt'];
			$reg_tot[$k]+=$row['cnt'];
			$reg_tot1[$i]+=$row['cnt'];
			$reg_totall+=$row['cnt'];
		}while($row=mysql_fetch_array($res));
	}

	for($k=0;$k<count($agearr);$k++)
	{
		for($i=0;$i<count($genderarr);$i++)
		{
			if($reg_cnt[$k][$i])
			{
				$percent[$k][$i]=$paid_cnt[$k][$i]/$reg_cnt[$k][$i] * 100;
				$percent[$k][$i]=round($percent[$k][$i],1);
			}
		}
		if($reg_tot[$k])
		{
			$pertot[$k]=$paid_tot[$k]/$reg_tot[$k] * 100;
			$pertot[$k]=round($pertot[$k],1);
		}
	}

	for($i=0;$i<count($genderarr);$i++)
	{
		if($reg_tot1[$i])
		{
			$pertot1[$i]=$paid_tot1[$i]/$reg_tot1[$i] * 100;
			$pertot1[$i]=round($pertot1[$i],1);
		}
	}

	if($reg_totall)
	{
		$pertotall=$paid_totall/$reg_totall * 100;
		$pertotall=round($pertotall,1);
	}

	$smarty->assign("agearr",$agearr);
	$smarty->assign("genderarr",$genderarr);
	$smarty->assign("paid_cnt",$paid_cnt);
	$smarty->assign("paid_tot",$paid_tot);
	$smarty->assign("paid_tot1",$paid_tot1);
	$smarty->assign("paid_totall",$paid_totall);
	$smarty->assign("reg_cnt",$reg_cnt);
	$smarty->assign("reg_tot",$reg_tot);
	$smarty->assign("reg_tot1",$reg_tot1);
	$smarty->assign("reg_totall",$reg_totall);
	$smarty->assign("percent",$percent);
	$smarty->assign("pertot",$pertot);
	$smarty->assign("pertot1",$pertot1);
	$smarty->assign("pertotall",$pertotall);
	$smarty->assign("select","age");
	$smarty->assign("cid",$cid);
	$smarty->display("age_reg_pay.htm");
}
else
{
	$smarty->assign("user",$username);
	$smarty->display("jsconnectError.tpl");
}
?>
