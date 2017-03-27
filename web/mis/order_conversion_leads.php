<?php

/*********************************************************************************************
* FILE NAME     : order_conversion_leads.php
* DESCRIPTION   : It provides MIS details . 
* CREATION DATE : 12 Jan, 2012
* CREATED BY    : Manoj
*********************************************************************************************/
                                                                                                                             
                                                                                                                          
include("connect.inc");
                                                                                                                             
$db=connect_misdb();
@mysql_select_db("billing",$db);

if(authenticated($checksum))
{
	$CMDGo='Y';
	$vtype='D';
	$today=date("Y-m-d");
	$todayDate=$today." 23:59:59";
	$yesterday =date("Y-m-d",JSstrToTime("$today -1 days"));
	$yesterdayDate =$yesterday." 00:00:00";  
	list($yr1,$mm,$d)=explode("-",$today);
	list($yr1_y,$mm_y,$d_y)=explode("-",$yesterday);

        if($CMDGo)
	{
		for($i=0;$i<31;$i++)
		{
			$mmarr[$i]=$i+1;
		}

		//$st_date=$yr1."-".$mm."-01 00:00:00";
		//$end_date=$yr1."-".$mm."-31 23:59:59";
		$st_date =$yesterdayDate;
		$end_date=$todayDate;

		$sel_type='Day';
		$sql1="min(DAYOFMONTH(t1.ENTRY_DT))";
		$num=31;
		$smarty->assign("mm",$mm);
		$smarty->assign("yr1",$yr1);	
		$smarty->assign("dt",$mm."-".$yr1);

		$sql ="select t2.PROFILEID as cnt,$sql1 as dd,t1.CURTYPE from billing.ORDERS as t1,billing.PURCHASES as t2 where t1.PROFILEID=t2.PROFILEID and t2.STATUS='DONE' and t2.ENTRY_DT >= t1.ENTRY_DT and t1.ENTRY_DT BETWEEN '$st_date' and  '$end_date' and (PAYMODE like '%card%' or PAYMODE like 'paytm') group by t2.PROFILEID,t1.CURTYPE";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$cnt1=1;
			$curtype=$row['CURTYPE'];
			if($curtype=='DOL')
				$j=0;
			else
				$j=1;
		
			$dd=$row['dd']-1;

			$con_arr[$dd]+=$cnt1;
			$con_arr_cur[$dd][$j]+=$cnt1;
			$total_con_cur[$j]+=$cnt1;
			$total_con+=$cnt1;
		}

		$sql="select PROFILEID AS cnt,$sql1 as dd,CURTYPE from billing.ORDERS t1 where ENTRY_DT BETWEEN '$st_date'  and '$end_date' and (PAYMODE like '%card%' or PAYMODE like 'paytm')  group by PROFILEID,CURTYPE";
		$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$cnt1=1;
			$curtype=$row['CURTYPE'];
			if($curtype=='DOL')
				$j=0;
			else
				$j=1;
		
			$dd=$row['dd']-1;

			$tot_arr[$dd]+=$cnt1;
			$tot_arr_cur[$dd][$j]+=$cnt1;
			$total_cur[$j]+=$cnt1;
			$total+=$cnt1;
		}

		for($i=0;$i<$num;$i++)
		{
			for($j=0;$j<2;$j++)
			{
				$cnt_cur[$i][$j]["C"]=$con_arr_cur[$i][$j];
				$cnt_cur[$i][$j]["N"]=$tot_arr_cur[$i][$j]-$con_arr_cur[$i][$j];
				$cnt_cur[$i][$j]["T"]=$tot_arr_cur[$i][$j];
				if($tot_arr_cur[$i][$j] >0)
					$cnt_cur[$i][$j]["P"]=round(($con_arr_cur[$i][$j]/$tot_arr_cur[$i][$j]*100),2)."%";
			}
			$cnt[$i]["C"]=$con_arr[$i];
			$cnt[$i]["N"]=$tot_arr[$i]-$con_arr[$i];
			$cnt[$i]["T"]=$tot_arr[$i];
			if($tot_arr[$i] >0)
				$cnt[$i]["P"]=round(($con_arr[$i]/$tot_arr[$i]*100),2)."%";
		}

		for($i=0;$i<2;$i++)
		{
			if($total_cur[$i])
			{
				$tot_not_con_cur[$i]=$total_cur[$i]-$total_con_cur[$i];
				$totp_cur[$i]=round(($total_con_cur[$i]/$total_cur[$i]*100),2)."%";
			}
		}
		
		if($total)
		{
			$tot_not_con=$total-$total_con;
			$totp=round(($total_con/$total*100),2)."%";
		}

		$smarty->assign("totn",$tot_not_con);
		$smarty->assign("totn_cur",$tot_not_con_cur);
		$smarty->assign("sel_type",$sel_type);	
		$smarty->assign("cnt_cur",$cnt_cur);
		$smarty->assign("tota_cur",$total_con_cur);
                $smarty->assign("totb_cur",$total_cur);
                $smarty->assign("totp_cur",$totp_cur);
		$smarty->assign("cnt",$cnt);
		$smarty->assign("tota",$total_con);
                $smarty->assign("totb",$total);
                $smarty->assign("totp",$totp);
		$smarty->assign("mmarr",$mmarr);
                $smarty->assign("flag","1");
		$smarty->assign("cid","$checksum");
		$smarty->display("order_conversion_leads.htm");
	}
}
else
{
        $smarty->display("jsconnectError.tpl");
}
?>
