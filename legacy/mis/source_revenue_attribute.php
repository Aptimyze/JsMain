<?php
include("connect.inc");
/**************************************************************************************************************************
*       FILE NAME        : source revenue attribute mis.php
*       CREATED BY       : 
*       MODIFIED ON      : 
*       FILE DESCRIPTION : This file shows source wise revenue for a month.
*       FILES INCLUDED   : connect.inc
**************************************************************************************************************************/
$db=connect_misdb();
$db2=connect_master();

if(authenticated($cid))
{
	if($CMDGo && $month!='ALL' && $year!='ALL')
	{
		if ($month <= 9)
			$month = "0".$month;
		
		//$sql="SELECT A.PROFILEID,A.SCORE,A.CURRENT_SCORE,A.ENTRY_DT,A.SOURCE,A.GROUPNAME,A.PAID_ALLTIME,A.CONV_RATE_ATTRIBUTE,A.COUNTRY_RES,DAY(A.ENTRY_DT) AS day FROM incentive.MAIN_ADMIN_POOL_INTO_SYSTEM_20_DAYS AS A , newjs.JPROFILE AS B WHERE A.PROFILEID=B.PROFILEID AND B.ACTIVATED='Y' AND A.ENTRY_DT BETWEEN '$year-$month-01' AND '$year-$month-31'";
		$sql="SELECT A.PROFILEID , A.ENTRY_DT , A.SOURCE, A.CONV_RATE_ATTRIBUTE , B.COUNTRY_RES , DAY(A.ENTRY_DT) AS day FROM incentive.MAIN_ADMIN_POOL AS A , newjs.JPROFILE AS B WHERE A.PROFILEID=B.PROFILEID AND B.ACTIVATED='Y' AND A.ENTRY_DT BETWEEN '$year-$month-01' AND '$year-$month-31'";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js($db));
		
		$i=0;
		while($row=mysql_fetch_array($res))
		{
			$row['PAID_ALLTIME']='Y';
			
			if($row['PAID_ALLTIME']=='Y')
			{	
				$sql2="SELECT sum(if(billing.PAYMENT_DETAIL.TYPE='DOL',billing.PAYMENT_DETAIL.AMOUNT*billing.PAYMENT_DETAIL.DOL_CONV_RATE,billing.PAYMENT_DETAIL.AMOUNT)) as amt from billing.PAYMENT_DETAIL WHERE billing.PAYMENT_DETAIL.STATUS='DONE' AND billing.PAYMENT_DETAIL.PROFILEID='$row[PROFILEID]'";
				$res2=mysql_query_decide($sql2,$db) or die("$sql2".mysql_error_js($db));
				$row2=mysql_fetch_array($res2);
			}
			else
				unset($row2);			

			//price shoud change when services prices are increased. 1874,1350
			if($row['COUNTRY_RES']!=51)
				$price=2200;
			else
				$price=1700;
			
			$sql_group=" select GROUPNAME from MIS.SOURCE WHERE SourceID='$row[SOURCE]' ";
			$res_group=mysql_query($sql_group,$db) or logError($sql_group,$db);
			$row_group = mysql_fetch_array($res_group);
			$row['GROUPNAME']=$row_group['GROUPNAME'];

			if( !is_array($groups_name) ||  (is_array($groups_name) && !in_array( $row['GROUPNAME'] ,$groups_name)) )
			{	
				$groups_name[]=$row['GROUPNAME'];
				$groups[$row['GROUPNAME']]=$i;
				$i++;
			}
			
			$sources[$groups[$row['GROUPNAME']]][$row['day']-1]++;
			$sources_total[$groups[$row['GROUPNAME']]]++;
			$sources_day_total[$row['day']-1]++;
			
			$revenue[$groups[$row['GROUPNAME']]][$row['day']-1]+=round(($row['CONV_RATE_ATTRIBUTE']/100) * $price,2);
			$revenue_total[$groups[$row['GROUPNAME']]]+=round(($row['CONV_RATE_ATTRIBUTE']/100) * $price,2);
			$revenue_day_total[$row['day']-1]+=round(($row['CONV_RATE_ATTRIBUTE']/100) * $price,2);		
			
			$amount[$groups[$row['GROUPNAME']]][$row['day']-1]+=$row2['amt'];
			$amount_total[$groups[$row['GROUPNAME']]]+=$row2['amt'];
			$amount_day_total[$row['day']-1]+=$row2['amt'];
		}
	
		//print_r($amount_day_total);

	
		if(is_array($sources))
		foreach ($sources as $key => $value)
		{
			foreach ($value as $key2=>$value2)
			{	
				$revenue_per_profile[$key][$key2]=round($revenue[$key][$key2]/$value2,2);
				$amount_per_profile[$key][$key2]=round($amount[$key][$key2]/$value2,2);
			}
		}
		//print_r($revenue_per_profile);
		

		if(is_array($sources_total))
		foreach ($sources_total as $key => $value)
		{
			$revenue_per_profile_total[$key]=round($revenue_total[$key]/$value,2);
			$amount_per_profile_total[$key]=round($amount_total[$key]/$value,2);
		}
		//print_r($revenue_per_profile_total);
		
		if(is_array($sources_day_total))
		foreach ($sources_day_total as $key => $value)
		{
			$revenue_per_profile_day_total[$key]=round($revenue_day_total[$key]/$value,2);
			$amount_per_profile_day_total[$key]=round($amount_day_total[$key]/$value,2);
		}
		//print_r($revenue_per_profile_day_total);



		if(is_array($sources_total))
		foreach ($sources_total as $key => $value)
		{
			$sources_final_total+=$value;
		}	
		
		if(is_array($revenue_total))
		foreach ($revenue_total as $key => $value)
		{
			$revenue_final_total+=$value;
		}	
		
		if(is_array($amount_day_total))
		foreach ($amount_day_total as $key => $value)
		{
			$amount_final_total+=$value;
		}	
	
		if($sources_final_total)	
		{	
			$revenue_per_profile_final_total=round($revenue_final_total/$sources_final_total,2);
			$amount_per_profile_final_total=round($amount_final_total/$sources_final_total,2);
		}
		//print_r($sources);
		//print_r($sources_total);
		//print_r($revenue);
		//print_r($revenue_total);
		//print_r($groups);
		//print_r($groups_name);
	}
	
	for($i=0;$i<12;$i++)
	{
		$mmarr[$i]=$i+1;
	}
	for($i=2004;$i<=date("Y");$i++)
{
        $yyarr[$i-2004]=$i;
}
	for($i=1;$i<=31;$i++)
	{
		$ddarr[$i-1]=$i;
	}
	$smarty->assign("month",$month);
	$smarty->assign("myear",$year);
	$smarty->assign("mmarr",$mmarr);
	$smarty->assign("yyarr",$yyarr);
	$smarty->assign("ddarr",$ddarr);
	
	$smarty->assign("sources",$sources);
	$smarty->assign("sources_total",$sources_total);
	$smarty->assign("sources_day_total",$sources_day_total);
	$smarty->assign("sources_final_total",$sources_final_total);
	
	$smarty->assign("revenue",$revenue);
	$smarty->assign("revenue_total",$revenue_total);
	$smarty->assign("revenue_day_total",$revenue_day_total);
	$smarty->assign("revenue_final_total",$revenue_final_total);
	$smarty->assign("revenue_per_profile",$revenue_per_profile);
	$smarty->assign("revenue_per_profile_total",$revenue_per_profile_total);
	$smarty->assign("revenue_per_profile_day_total",$revenue_per_profile_day_total);
	$smarty->assign("revenue_per_profile_final_total",$revenue_per_profile_final_total);
	
	$smarty->assign("amount",$amount);
	$smarty->assign("amount_total",$amount_total);
	$smarty->assign("amount_day_total",$amount_day_total);
	$smarty->assign("amount_final_total",$amount_final_total);
	$smarty->assign("amount_per_profile",$amount_per_profile);
	$smarty->assign("amount_per_profile_total",$amount_per_profile_total);
	$smarty->assign("amount_per_profile_day_total",$amount_per_profile_day_total);
	$smarty->assign("amount_per_profile_final_total",$amount_per_profile_final_total);
	
	$smarty->assign("groups",$groups);
	$smarty->assign("groups_name",$groups_name);

	$smarty->assign("cid",$cid);
	$smarty->display("source_revenue_attribute_mis.htm");
}
else
{
	$smarty->display("jsconnectError.tpl");
}

?>
