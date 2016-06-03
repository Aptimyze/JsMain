<?php
require_once('connect.inc');

$db	= db_connect();
$db2	= dbsql2_connect();

	if($myear)
	{
		$reg_sql = "SELECT  newjs.JPROFILE.SUBSCRIPTION as SUBSCRIPTION,newjs.JPROFILE.USERNAME AS MEMBERS, MONTH(jsadmin.AFFILIATE_MAIN.ENTRYTIME) as mm FROM jsadmin.AFFILIATE_MAIN LEFT JOIN newjs.JPROFILE ON jsadmin.AFFILIATE_MAIN.EMAIL=newjs.JPROFILE.EMAIL WHERE JPROFILE.EMAIL IS NOT NULL ";

		if ($srcgrp == 1)
			$reg_sql.=" AND jsadmin.AFFILIATE_MAIN.MODE IN ('N','A')";
		elseif ($srcgrp == 2)
			$reg_sql.=" AND jsadmin.AFFILIATE_MAIN.MODE ='N' ";
		elseif ($srcgrp == 3)
			$reg_sql.=" AND jsadmin.AFFILIATE_MAIN.MODE ='A'";

		if($month<=9)
			$month = "0".$month;

		$reg_sql.= " AND jsadmin.AFFILIATE_MAIN.ENTRYTIME BETWEEN '$myear-$month-01' AND '$myear-$month-31' GROUP BY newjs.JPROFILE.SUBSCRIPTION , mm , jsadmin.AFFILIATE_MAIN.ENTRYBY ";

	}

	elseif($dyear)
	{
		$reg_sql = "SELECT newjs.JPROFILE.USERNAME as MEMBERS, newjs.JPROFILE.SUBSCRIPTION as SUBSCRIPTION, DAYOFMONTH(jsadmin.AFFILIATE_MAIN.ENTRYTIME) as dd FROM jsadmin.AFFILIATE_MAIN LEFT JOIN newjs.JPROFILE ON jsadmin.AFFILIATE_MAIN.EMAIL=newjs.JPROFILE.EMAIL WHERE JPROFILE.EMAIL IS NOT NULL AND jsadmin.AFFILIATE_MAIN.ENTRYTIME";   
		
		if ($day <= 9)
			$day = "0".$day;
		if ($dmonth<=9)
			$dmonth = "0".$dmonth;

		$reg_sql.=" '$dyear-$dmonth-$day' ";

		if($srcgrp == 1)
			$reg_sql.=" AND MODE IN ('N','A')";
                                                                                                                           
       		elseif($srcgrp == 2)
                	$reg_sql.=" AND MODE IN ('N')";                                                                                                                             
        	elseif($srcgrp == 3)
			$reg_sql.=" AND MODE IN ('A')";                                                                     
		$reg_sql.=" GROUP BY newjs.JPROFILE.SUBSCRIPTION , dd , jsadmin.AFFILIATE_MAIN.ENTRYBY ";
	}

	$reg_res = mysql_query($reg_sql,$db) or logerror("Due to a temporary problem your information cannot be processed.Please try after a few minutes.",$reg_sql);

	while ($reg_row = mysql_fetch_array($reg_res))
	{
		$sub	= $reg_row["SUBSCRIPTION"];

		if ($sub!='')
			$paidmembers[] = $reg_row["MEMBERS"];

		$regmembers[] = $reg_row["MEMBERS"];		
	}

	
	$smarty->assign("paid",$paid);
	$smarty->assign("cid",$cid);
	$smarty->assign("name",$name);
	$smarty->assign("regmembers",$regmembers);
	$smarty->assign("paidmembers",$paidmembers);
	$smarty->assign("head",$smarty->fetch("head.htm"));
	$smarty->assign("foot",$smarty->fetch("foot.htm"));

	$smarty->display('membernames.htm');

?>
