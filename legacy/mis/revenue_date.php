<?php
include_once("connect.inc");
$db=connect_misdb();
$db2=connect_master();

//$data=authenticated($cid);
$data=authenticated($checksum);

if(isset($data))
{
	$searchMonth='';
        $searchYear='';
        $monthDays=0;
	if(!$today)
        $today=date("Y-m-d");
        list($todYear,$todMonth,$todDay)=explode("-",$today);
	if($type)
	{
		$searchFlag=1;
		$searchMonth=$month;
		$searchYear=$year;
		  if(($searchMonth=='01')||($searchMonth=='03')||($searchMonth=='05')||($searchMonth=='07')||($searchMonth=='08')||($searchMonth=='10')||($searchMonth=='12'))
			$monthDays=31;
			elseif(($searchMonth=='04')||($searchMonth=='06')||($searchMonth=='09')||($searchMonth=='11'))
				$monthDays=30;
				elseif(($searchYear%4==0)&&($searchYear%100!=0)||($searchYear%400==0))
					$monthDays=29;
					else
                                $monthDays=28;
		$monthArray=array('01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'May','06'=>'Jun','07'=>'Jul','08'=>'Aug','09'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec');
			foreach($monthArray as $i=>$value)
			{
				if($i== $searchMonth)
					$month_name= $value;
			}

		$k=1;
		while($k<=$monthDays)
		{
		$monthDaysArray[]=$k;
		$k++;
		}
	}
	if($submit1 && $type== 't_date')
	{
		$data=array();	
		
		$all_sql="SELECT day( B.ENTRY_DT ) AS DAY_NO, B.BILLID, C.AMOUNT AS AMT,(C.AMOUNT/(1+TAX_RATE/100)) AS AMT_NOTAX FROM billing.PURCHASES AS B, billing.PAYMENT_DETAIL AS C WHERE B.ENTRY_DT BETWEEN '$searchYear-$searchMonth-01 00:00:00' AND '$searchYear-$searchMonth-$monthDays 23:59:59' AND B.BILLID= C.BILLID AND C.STATUS= 'DONE' AND B.SALES_TYPE='offline'";
		$all_res= mysql_query_decide($all_sql) or die(mysql_error_js());
		while($all_row= mysql_fetch_array($all_res))
		{
			$billid= $all_row['BILLID'];
			$amount= $all_row['AMT'];		
			$amount_notax= $all_row['AMT_NOTAX'];		
			$day= $all_row['DAY_NO'];
			$data[$day]=$data[$day]+$amount;	
			$data_notax[$day]+=round($amount_notax,2);	
			$total= $total+$amount;
			$total_notax= $total_notax+$amount_notax;
			
		}
		$smarty->assign('monthDaysArray',$monthDaysArray);
	        $smarty->assign('searchFlag',$searchFlag);
                $smarty->assign('month_name',$month_name);
               	$smarty->assign('searchYear',$searchYear);
		$smarty->assign("data",$data);
		$smarty->assign("total",$total);
		/////////
		$billid_str2=offline_online_sale($searchYear,$searchYear,$searchMonth,$searchMonth,1);
		if($billid_str2!='')
                {
                        $all_sql="SELECT day( C.ENTRY_DT ) AS DAY_NO, C.BILLID, AMOUNT AS AMT,(AMOUNT/(1+B.TAX_RATE/100)) AS AMT_NOTAX FROM billing.PURCHASES AS B LEFT JOIN billing.PAYMENT_DETAIL AS C ON B.BILLID=C.BILLID WHERE C.BILLID IN ($billid_str2)";
                        $all_res= mysql_query_decide($all_sql) or die(mysql_error_js());
                        while($all_row= mysql_fetch_array($all_res))
                        {
                                $billid= $all_row['BILLID'];
                                $amount= $all_row['AMT'];
                                $amount_notax= $all_row['AMT_NOTAX'];
                                $day= $all_row['DAY_NO'];
                                $data2[$day]=$data2[$day]+$amount;
                                $data_notax[$day]+=round($amount_notax,2);
                                $total2= $total2+$amount;
                                $total_notax= $total_notax+$amount_notax;
                        }	
			$smarty->assign("data2",$data2);
	                $smarty->assign("total2",$total2);
		}
                $smarty->assign("data_notax",$data_notax);
                $total_notax=round($total_notax,2);
                $smarty->assign("total_notax",$total_notax);
		/////////
	}
/**************************************************************************************************/
	elseif($submit1 && $type)
	{
		$revenue=array();	
		$flag_type= 'Y';
		
		if($type== 'location')
		{
			if($location== 'all')
			{
				$all_sql= "SELECT C.BILLID,B.CENTER AS LOC, C.AMOUNT AS AMT, DAY(C.ENTRY_DT) AS DAY_NO FROM billing.PURCHASES AS A, billing.PAYMENT_DETAIL AS C,jsadmin.PSWRDS AS B WHERE A.PROFILEID= C.PROFILEID AND B.USERNAME=C.ENTRYBY AND  C.ENTRY_DT BETWEEN '$searchYear-$searchMonth-01 00:00:00' AND '$searchYear-$searchMonth-$monthDays 23:59:59' AND C.STATUS= 'DONE' AND A.SALES_TYPE='offline'";
			}
			else
			{
				$all_sql= "SELECT C.BILLID,C.AMOUNT AS AMT, DAY(C.ENTRY_DT) AS DAY_NO FROM billing.PURCHASES AS A, jsadmin.PSWRDS AS B, billing.PAYMENT_DETAIL AS C WHERE A.PROFILEID= C.PROFILEID AND B.USERNAME= C.ENTRYBY AND B.CENTER= '$location' AND C.ENTRY_DT BETWEEN '$searchYear-$searchMonth-01 00:00:00' AND '$searchYear-$searchMonth-$monthDays 23:59:59' AND C.STATUS= 'DONE' AND A.SALES_TYPE='offline'";
				$loc=$location;
			}
			$all_res= mysql_query_decide($all_sql) or die(mysql_error_js());
			if(mysql_num_rows($all_res)>0)
			{
				while($all_row= mysql_fetch_array($all_res))
				{
					if($location=="all")
					$loc= $all_row['LOC'];
					$tax=get_tax_rate($all_row['BILLID']);
					$amt= $all_row['AMT'];
					$amt_notax= $amt/(1+$tax/100);
					$day= $all_row['DAY_NO'];
					$revenue[$loc][$day]+= $amt;
					$total[$loc]+= $amt;
					$tot_d[$day]+= $amt;
					$tot_d_notax[$day]+= round($amt_notax,2);
					$tot+= $amt;
					$tot_notax+= round($amt_notax,2);
				}
			}
			$billid_str2=offline_online_sale($searchYear,$searchYear,$searchMonth,$searchMonth,1);
                        if($billid_str2 != '')
                        {
                                if($location== 'all')
                                {
                                        $all_sql= "SELECT C.BILLID,B.CENTER AS LOC, C.AMOUNT AS AMT, DAY(C.ENTRY_DT) AS DAY_NO FROM billing.PAYMENT_DETAIL AS C,jsadmin.PSWRDS AS B WHERE B.USERNAME=C.ENTRYBY AND C.BILLID IN ($billid_str2)";
                                }
                                else
                                {
                                        $all_sql= "SELECT C.BILLID,C.AMOUNT AS AMT, DAY(C.ENTRY_DT) AS DAY_NO FROM jsadmin.PSWRDS AS B, billing.PAYMENT_DETAIL AS C WHERE B.USERNAME= C.ENTRYBY AND B.CENTER= '$location' AND C.BILLID IN ($billid_str2)";
                                        $loc=$location;
                                }
                                $all_res= mysql_query_decide($all_sql) or die(mysql_error_js());
                                if(mysql_num_rows($all_res)>0)
                                {
                                        while($all_row= mysql_fetch_array($all_res))
                                        {
                                                if($location=="all")
                                                $loc= $all_row['LOC'];
                                                $tax=get_tax_rate($all_row['BILLID']);
                                                $amt= $all_row['AMT'];
                                                $amt_notax= $amt/(1+$tax/100);
                                                $day= $all_row['DAY_NO'];
                                                $revenue2[$day]+= $amt;
                                                $total2+= $amt;
                                                $tot_d[$day]+= $amt;
                                                $tot_d_notax[$day]+= round($amt_notax,2);
                                                $tot+= $amt;
                                                $tot_notax+= round($amt_notax,2);
                                        }
                                }
                        }
			else
			{
				$msg= "No billing is done in selected location";

				$smarty->assign("nodata","Y");
				$smarty->assign("msg",$msg);
			}
		}
/*************************************************************************************************/
		if($type== 'operator')
		{
			if($operator== 'all')
				$all_sql= "SELECT B.ENTRYBY AS OP, A.AMOUNT AS AMT,(A.AMOUNT/(1+TAX_RATE/100)) AS AMT_NOTAX, DAY(B.ENTRY_DT) AS DAY_NO FROM billing.PURCHASES AS  B, billing.PAYMENT_DETAIL AS A WHERE B.BILLID= A.BILLID AND B.ENTRY_DT BETWEEN '$searchYear-$searchMonth-01 00:00:00' AND '$searchYear-$searchMonth-$monthDays 23:59:59' AND A.STATUS= 'DONE' AND B.SALES_TYPE='offline'";
			else	
			{
				$all_sql= "SELECT B.ENTRYBY AS OP, A.AMOUNT AS AMT,(A.AMOUNT/(1+TAX_RATE/100)) AS AMT_NOTAX, DAY(B.ENTRY_DT) AS DAY_NO FROM billing.PURCHASES AS  B, incentive.CRM_DAILY_ALLOT AS C, billing.PAYMENT_DETAIL AS A WHERE C.PROFILEID= B.PROFILEID AND B.BILLID= A.BILLID AND B.ENTRY_DT BETWEEN '$searchYear-$searchMonth-01 00:00:00' AND '$searchYear-$searchMonth-$monthDays 23:59:59' AND C.ALLOTED_TO='$operator' AND A.STATUS= 'DONE' AND B.SALES_TYPE='offline'";
				$op=$operator;
			}
			$all_res= mysql_query_decide($all_sql) or die(mysql_error_js());
			if(mysql_num_rows($all_res)>0)
			{
				while($all_row= mysql_fetch_array($all_res))
				{
					if($operator=="all")
					$op= $all_row['OP'];
					$amt= $all_row['AMT'];
					$amt_notax= $all_row['AMT_NOTAX'];
					$day= $all_row['DAY_NO'];
					$revenue[$op][$day]+= $amt;
					$total[$op]+= $amt;
					$tot_d[$day]+= $amt;
					$tot_d_notax[$day]+= round($amt_notax,2);
					$tot+= $amt;
					$tot_notax+= round($amt_notax,2);
		 	
				}
		
			}
			$billid_str2=offline_online_sale($searchYear,$searchYear,$searchMonth,$searchMonth,1);
                        if($billid_str2 != '')
                        {
                                if($operator== 'all')
                                        $all_sql= "SELECT B.ENTRYBY AS OP, A.AMOUNT AS AMT,(A.AMOUNT/(1+TAX_RATE/100)) AS AMT_NOTAX, DAY(B.ENTRY_DT) AS DAY_NO FROM billing.PURCHASES AS  B LEFT JOIN billing.PAYMENT_DETAIL AS A ON B.BILLID=A.BILLID WHERE A.BILLID IN ($billid_str2)";
                                else
                                {
                                        $all_sql= "SELECT B.ENTRYBY AS OP, A.AMOUNT AS AMT,(A.AMOUNT/(1+TAX_RATE/100)) AS AMT_NOTAX, DAY(B.ENTRY_DT) AS DAY_NO FROM billing.PURCHASES AS  B LEFT JOIN billing.PAYMENT_DETAIL AS A ON B.BILLID=A.BILLID WHERE B.ENTRYBY= '$operator' AND A.BILLID IN ($billid_str2)";
                                        $op=$operator;
                                }
                                $all_res= mysql_query_decide($all_sql) or die(mysql_error_js());
                                if(mysql_num_rows($all_res)>0)
                                {
                                        while($all_row= mysql_fetch_array($all_res))
                                        {
                                                if($operator=="all")
                                                $op= $all_row['OP'];
                                                $amt= $all_row['AMT'];
                                                $amt_notax= $all_row['AMT_NOTAX'];
                                                $day= $all_row['DAY_NO'];
                                                $revenue2[$day]+= $amt;
                                                $total2+= $amt;
                                                $tot_d[$day]+= $amt;
                                                $tot_d_notax[$day]+= round($amt_notax,2);
                                                $tot+= $amt;
                                                $tot_notax+= round($amt_notax,2);
                                        }
                               }
                        }
			else
			{
				$msg= "No billing is done by selected operator";

				$smarty->assign("nodata","Y");
				$smarty->assign("msg",$msg);
			}
			
		}
		$smarty->assign('tot',$tot);
		$smarty->assign('tot_notax',$tot_notax);
		$smarty->assign('month_name',$month_name);
		$smarty->assign('monthDaysArray',$monthDaysArray);
		$smarty->assign('searchFlag',$searchFlag);
		$smarty->assign('flag_type',$flag_type);
		$smarty->assign("reven",$revenue);
		$smarty->assign('searchYear',$searchYear);
		$smarty->assign("total",$total);
		$smarty->assign("tot_d",$tot_d);
		$smarty->assign("tot_d_notax",$tot_d_notax);
		$smarty->assign("reven2",$revenue2);
                $smarty->assign("total2",$total2);
	}
	else
	{        
		$sql= "SELECT DISTINCT CENTER FROM jsadmin.PSWRDS WHERE PRIVILAGE LIKE '%OB%'";
		$res= mysql_query_decide($sql) or die(mysql_error_js());
		while($row= mysql_fetch_array($res))
			$op_center[]= $row['CENTER'];
		$sql= "SELECT DISTINCT USERNAME FROM jsadmin.PSWRDS WHERE PRIVILAGE LIKE '%OB%'";
		$res= mysql_query_decide($sql) or die(mysql_error_js());
		while($row= mysql_fetch_array($res))
			$op_uname[]= $row['USERNAME'];
		$smarty->assign("op_center",$op_center);
		$smarty->assign("op_uname",$op_uname);
	}	

	$k=0;
	while($k<=5)
	{
		$yearArray[]=$todYear-$k;
		$k++;
	}
	$monthArray=array('01'=>'Jan','02'=>'Feb','03'=>'Mar','04'=>'Apr','05'=>'May','06'=>'Jun','07'=>'Jul','08'=>'Aug','09'=>'Sep','10'=>'Oct','11'=>'Nov','12'=>'Dec');
	$smarty->assign("checksum",$checksum);                
		
	$smarty->assign("cid",$cid);                
	$smarty->assign('yearArray',$yearArray);
	$smarty->assign('monthArray',$monthArray);
	$smarty->assign('todYear',$todYear);
	$smarty->assign('todMonth',$todMonth);
	$smarty->display("revenue_date.tpl");
		
}
else
{
        $smarty->assign("user",$username);
        $smarty->display("jsconnectError.tpl");
}

function get_tax_rate($billid)
{
	$sql="SELECT TAX_RATE FROM billing.PURCHASES WHERE BILLID='$billid'";
	$res=mysql_query_decide($sql) or die(mysql_error_js());
	$row=mysql_fetch_assoc($res);
	return $row['TAX_RATE'];
}
?>
