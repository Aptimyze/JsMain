<?php

/*********************************************************************************************
* FILE NAME     : offline_conversion.php
* DESCRIPTION   : It provides MIS details for online member conversions. 
* CREATION DATE : 4 july, 2005
* CREATED BY    : Aman Sharma
* Copyright  2005, InfoEdge India Pvt. Ltd.
*********************************************************************************************/
                                                                                                                             
include("connect.inc");
                                                                                                                             
$db=connect_misdb();
$db2=connect_master();
@mysql_select_db("billing",$db);
                                                                                                                             
$flag=0;

if(authenticated($checksum))
{
	if($outside)
	{
		$CMDGo='Y';
		$vtype='D';
		$today=date("Y-m-d");
		list($yr1,$mm,$d)=explode("-",$today);
	}

        if($CMDGo)
	{
		 if($vtype=='D')
                 {
			for($i=0;$i<31;$i++)
			{
				$mmarr[$i]=$i+1;
			}


			$t1=mktime(0,0,0,7,22,2006);
			$t2=mktime(0,0,0,$mm,1,$yr1);
			if($t1>$t2)
				$st_date="2006-07-22 00:00:00";
			else
				$st_date=$yr1."-".$mm."-01 00:00:00";

			$end_date=$yr1."-".$mm."-31 23:59:59";
			$sel_type='Day';
			$sql1="min(DAYOFMONTH(t1.REQ_DT))";
			$num=31;
			$smarty->assign("mm",$mm);
			$smarty->assign("yr1",$yr1);	
			$smarty->assign("dt",$mmarr[$mm-1]."-".$yr1);
		}
		elseif($vtype=='M')
		{
			$mmarr=array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');

			$st_date=$yr."-01-01 00:00:00";
			$end_date=$yr."-12-31 23:59:59";
			$sel_type='MONTH';
			$sql1="MONTH(t1.REQ_DT)";
			$num=12;
			$smarty->assign("dt",$yr);
		}

		$sql ="select ";
		if($vtype=='M')
		{
			$sql.="distinct ";
		}
		$sql.="t2.PROFILEID as cnt,$sql1 as dd,t1.PICKUP_TYPE from incentive.PAYMENT_COLLECT as t1,billing.PURCHASES as t2 where t1.PROFILEID=t2.PROFILEID and t2.STATUS='DONE' and t2.ENTRY_DT >= t1.REQ_DT and t1.REQ_DT BETWEEN '$st_date' and  '$end_date'";
		if($vtype=='D')
		{
			$sql.="group by t2.PROFILEID,t1.PICKUP_TYPE";
		}
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$cnt1=1;
			$pickup_type=$row['PICKUP_TYPE'];
			if($pickup_type=='CHEQ_DROP')
				$j=0;
			elseif($pickup_type=='CHEQ_REQ_USER')
				$j=1;
			elseif($pickup_type=='CHEQ_REQ_EXEC')
				$j=2;
			elseif($pickup_type=='ONLINE')
				$j=3;
			elseif($pickup_type=='ICICI_CHEQUE')
				$j=4;
			elseif($pickup_type=='FUND_TRANSFER')
                                $j=5;
		
			$dd=$row['dd']-1;

			$con_arr[$dd]+=$cnt1;
			$con_arr_cur[$dd][$j]+=$cnt1;
			$total_con_cur[$j]+=$cnt1;
			$total_con+=$cnt1;
		}

		$sql="select ";
		if($vtype=='M')
		{
			$sql.="distinct ";
		}
		$sql.=" PROFILEID AS cnt,$sql1 as dd,PICKUP_TYPE from incentive.PAYMENT_COLLECT t1 where REQ_DT BETWEEN '$st_date'  and '$end_date' ";
		if($vtype=='D')
		{
			$sql.=" group by PROFILEID,PICKUP_TYPE";
		}
		$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$cnt1=1;
			$pickup_type=$row['PICKUP_TYPE'];
			if($pickup_type=='CHEQ_DROP')
				$j=0;
			elseif($pickup_type=='CHEQ_REQ_USER')
				$j=1;
			elseif($pickup_type=='CHEQ_REQ_EXEC')
				$j=2;
			elseif($pickup_type=='ONLINE')
				$j=3;
			elseif($pickup_type=='ICICI_CHEQUE')
				$j=4;
			elseif($pickup_type=='FUND_TRANSFER')
                                $j=5;
		
			$dd=$row['dd']-1;

			$tot_arr[$dd]+=$cnt1;
			$tot_arr_cur[$dd][$j]+=$cnt1;
			$total_cur[$j]+=$cnt1;
			$total+=$cnt1;
		}
/*******************************Code added for easy bill**************************************************************/
		if($vtype=='D')
			$sel=" DAYOFMONTH(ENTRY_DT) ";
		elseif($vtype=='M')	
			$sel=" MONTH(ENTRY_DT) ";
		$sql_eb_paid=" SELECT PROFILEID as cnt ,$sel as dd from billing.EASY_BILL where ENTRY_DT between '$st_date' and '$end_date' and BILLING='Y' " ;
                $res_eb_paid=mysql_query_decide($sql_eb_paid,$db) or die(mysql_error_js($db));
                while($row=mysql_fetch_array($res_eb_paid))
                {
                        $cnt1=1;
                        $dd=$row['dd']-1;
                        $j=6;
			$con_arr[$dd]+=$cnt1;
                        $con_arr_cur[$dd][$j]+=$cnt1;
                        $total_con_cur[$j]+=$cnt1;
                        $total_con+=$cnt1;
                }

		if($vtype=='D')
			$sql_eb=" SELECT PROFILEID as cnt ,min(DAYOFMONTH(ENTRY_DT)) as dd from billing.EASY_BILL where ENTRY_DT between '$st_date' and '$end_date' group by PROFILEID " ;
		elseif($vtype=='M')
			 $sql_eb=" SELECT DISTINCT PROFILEID as cnt,MONTH(ENTRY_DT) as dd from billing.EASY_BILL where ENTRY_DT between '$st_date' and '$end_date' ";
		$res_eb=mysql_query_decide($sql_eb,$db) or die(mysql_error_js($db));
                while($row=mysql_fetch_array($res_eb))
                {
                        $cnt1=1;
			$dd=$row['dd']-1;
			$j=6;
			$tot_arr[$dd]+=$cnt1;
                        $tot_arr_cur[$dd][$j]+=$cnt1;
                        $total_cur[$j]+=$cnt1;
                        $total+=$cnt1;
                }

/********************************easy bill portion ended here*******************************************************/


		for($i=0;$i<$num;$i++)
		{
			for($j=0;$j<7;$j++)
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
		for($i=0;$i<7;$i++)
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
		$smarty->assign("cid",$checksum);
		$smarty->display("offline_conversion.htm");
	}
	else
	{
                 for($i=0;$i<12;$i++)
                 {
                        $mmarr[$i]=$i+1;
                 }

		 for($i=0;$i<10;$i++)
         	 {
                 	$yyarr[$i]=$i+2006;
         	 }
                 $smarty->assign("mmarr",$mmarr); 
         	 $smarty->assign("flag","0");
         	 $smarty->assign("yyarr",$yyarr);
                 $smarty->assign("cid",$checksum);
                 $smarty->display("offline_conversion.htm");
	}
}
else
{
        $smarty->display("jsconnectError.tpl");
}
?>
