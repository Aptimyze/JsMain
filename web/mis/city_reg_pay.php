<?php
/****
        File            :       city_reg_pay.php
        Description     :       This gives details of profiles becoming paid members from December 1, 2004 onwards and
                                which city they live in i.e. their current city of residence
        Created by      :       Shiv
        Modified On     :       May 26, 2004 by shiv
        Modifications   :       Query which picks up paid members is optimized, earlier there was no left join
****/

//include("connect.inc");
//$db=connect_misdb();
//$db2=connect_master();

if(authenticated($cid))
{
	//$sql="SELECT COUNT(DISTINCT p.PROFILEID) as cnt,j.CITY_RES as city FROM newjs.JPROFILE j,billing.PURCHASES p WHERE COUNTRY_RES='51' AND j.ENTRY_DT>='2004-12-01' AND j.ACTIVATED IN ('Y','H','D') AND p.STATUS='DONE' AND j.PROFILEID=p.PROFILEID GROUP BY city ORDER BY cnt DESC LIMIT 20";

	$sql="SELECT COUNT(DISTINCT p.PROFILEID) as cnt,j.CITY_RES as city FROM billing.PURCHASES p LEFT JOIN newjs.JPROFILE j ON j.PROFILEID=p.PROFILEID WHERE COUNTRY_RES='51' AND j.ENTRY_DT>='2004-12-01' AND j.ACTIVATED IN ('Y','H','D') AND p.STATUS='DONE' GROUP BY city ORDER BY cnt DESC LIMIT 20";
	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
	if($row=mysql_fetch_array($res))
	{
		do
		{
			$cityarr[]=$row['city'];
			$i=array_search($row['city'],$cityarr);
			$paid_cnt[$i]=$row['cnt'];
			$paid_tot+=$row['cnt'];
		}while($row=mysql_fetch_array($res));
	}

	$citystr=implode("','",$cityarr);

	$sql="SELECT COUNT(*) as cnt,CITY_RES as city FROM newjs.JPROFILE WHERE ENTRY_DT>='2004-12-01' AND ACTIVATED IN ('Y','H','D') AND CITY_RES IN ('$citystr') GROUP BY city";
	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
	if($row=mysql_fetch_array($res))
	{
		do
		{
			$i=array_search($row['city'],$cityarr);
			$reg_cnt[$i]=$row['cnt'];
			$reg_tot+=$row['cnt'];
		}while($row=mysql_fetch_array($res));
	}

	for($i=0;$i<count($cityarr);$i++)
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

		$sql="SELECT LABEL FROM newjs.CITY_NEW WHERE VALUE='$cityarr[$i]'";
		$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
		$row=mysql_fetch_array($res);
		$cityarr[$i]=$row['LABEL'];
	}

	//$sql="SELECT COUNT(DISTINCT p.PROFILEID) as cnt,j.COUNTRY_RES as ctry FROM newjs.JPROFILE j,billing.PURCHASES p WHERE COUNTRY_RES<>'51' AND j.ENTRY_DT>='2004-12-01' AND j.ACTIVATED IN ('Y','H','D') AND p.STATUS='DONE' AND j.PROFILEID=p.PROFILEID GROUP BY ctry ORDER BY cnt DESC LIMIT 10";
	$sql="SELECT COUNT(DISTINCT p.PROFILEID) as cnt,j.COUNTRY_RES as ctry FROM billing.PURCHASES p LEFT JOIN newjs.JPROFILE j ON j.PROFILEID=p.PROFILEID WHERE COUNTRY_RES<>'51' AND j.ENTRY_DT>='2004-12-01' AND j.ACTIVATED IN ('Y','H','D') AND p.STATUS='DONE' GROUP BY ctry ORDER BY cnt DESC LIMIT 10";
	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
	if($row=mysql_fetch_array($res))
	{
		do
		{
			$ctryarr[]=$row['ctry'];
			$i=array_search($row['ctry'],$ctryarr);
			$opaid_cnt[$i]=$row['cnt'];
			$opaid_tot+=$row['cnt'];
		}while($row=mysql_fetch_array($res));
	}

	$ctrystr=implode("','",$ctryarr);

	$sql="SELECT COUNT(*) as cnt,COUNTRY_RES as ctry FROM newjs.JPROFILE WHERE COUNTRY_RES<>'51' AND ENTRY_DT>='2004-12-01' AND ACTIVATED IN ('Y','H','D') AND COUNTRY_RES IN ('$ctrystr') GROUP BY ctry";
	$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
	if($row=mysql_fetch_array($res))
	{
		do
		{
			$i=array_search($row['ctry'],$ctryarr);
			$oreg_cnt[$i]=$row['cnt'];
			$oreg_tot+=$row['cnt'];
		}while($row=mysql_fetch_array($res));
	}

	for($i=0;$i<count($ctryarr);$i++)
	{
		if($oreg_cnt[$i])
		{
			$opercent[$i]=$opaid_cnt[$i]/$oreg_cnt[$i] * 100;
			$opercent[$i]=round($opercent[$i],1);
		}
		if($oreg_tot)
		{
			$opertot=$opaid_tot/$oreg_tot * 100;
			$opertot=round($opertot,1);
		}

		$sql="SELECT LABEL FROM newjs.COUNTRY WHERE VALUE='$ctryarr[$i]'";
		$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
		$row=mysql_fetch_array($res);
		$ctryarr[$i]=$row['LABEL'];
	}

	$smarty->assign("cityarr",$cityarr);
	$smarty->assign("ctryarr",$ctryarr);
	$smarty->assign("paid_cnt",$paid_cnt);
	$smarty->assign("paid_tot",$paid_tot);
	$smarty->assign("opaid_cnt",$opaid_cnt);
	$smarty->assign("opaid_tot",$opaid_tot);
	$smarty->assign("reg_cnt",$reg_cnt);
	$smarty->assign("reg_tot",$reg_tot);
	$smarty->assign("oreg_cnt",$oreg_cnt);
	$smarty->assign("oreg_tot",$oreg_tot);
	$smarty->assign("percent",$percent);
	$smarty->assign("opercent",$opercent);
	$smarty->assign("pertot",$pertot);
	$smarty->assign("opertot",$opertot);
	//$smarty->assign("jtotpercent",$jtotpercent);
	$smarty->assign("select","city");
	$smarty->assign("cid",$cid);
	$smarty->display("city_reg_pay.htm");
}
else
{
	$smarty->assign("user",$username);
	$smarty->display("jsconnectError.tpl");
}
?>
