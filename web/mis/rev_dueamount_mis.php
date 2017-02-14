<?php
include("connect.inc");

$db=connect_misdb();

if(authenticated($cid))
{
	$user=getuser($cid,"0.0.0.0");
        $privilege=explode("+",getprivilage($cid));
        $center=getcenter_for_operator($user);

	if($CMDGo)
	{
		//added by sriram to provide option for entrydate-wise or depositdate-wise MIS.
		if($date_wise == "entry_dt")
		{
			$date_wise = "b.ENTRY_DT";
			$start_dt=$year."-".$month."-".$day." 00:00:00";
			$end_dt=$year2."-".$month2."-".$day2." 23:59:59";
		}
		else
		{
			$date_wise = "b.DEPOSIT_DT";
			$start_dt=$year."-".$month."-".$day;
			$end_dt=$year2."-".$month2."-".$day2;
		}
		//end of - added by sriram to provide option for entrydate-wise or depositdate-wise MIS.

		$date1=$day."-".$month."-".$year;
		$date2=$day2."-".$month2."-".$year2;
		$smarty->assign("DATE1",$date1);
		$smarty->assign("DATE2",$date2);

		$i=0;
		
		$sql="SELECT a.COMP_NAME,a.SALE_DES,a.SALEID,a.SALE_BY,a.DUEAMOUNT,a.DUE_DT,b.RECEIPTID,b.MODE,b.AMOUNT,b.TDS,b.TYPE,b.CD_DT,b.CD_NUM,b.TRANS_NUM,b.CD_CITY,b.BANK,b.ENTRY_DT,b.DEPOSIT_DT,b.DEPOSIT_BRANCH from billing.REV_MASTER as a,billing.REV_PAYMENT as b WHERE $date_wise BETWEEN '$start_dt' AND '$end_dt' AND a.SALEID=b.SALEID and b.STATUS='DONE' AND a.DUEAMOUNT!=0";
	
		if($currency=='inr')
			$sql.=" AND b.TYPE='RS' ";
		elseif($currency=='usd')
			$sql.=" AND b.TYPE='DOL' ";

		$mode_str="'".@implode("','",$mode)."'";
		if($mode_str!="''")
                        $sql.=" AND b.MODE IN($mode_str) ";
		if($show=='branch')
                {
                        $branch_str="'".@implode("','",$branch)."'";
                        if($branch_str=="'All'")
                        {
                                if(in_array('ACCA',$privilege))
                                {
                                        if($showall=='Y')
                                                $sql_t="SELECT NAME FROM billing.BRANCHES WHERE 1";
                                        else
                                                $sql_t="SELECT NAME FROM billing.BRANCHES where REGION_ACC=''";
                                                                                                                             
                                        $res=mysql_query_decide($sql_t,$db) or die(mysql_error_js());
                                        while($row=mysql_fetch_array($res))
                                        {
                                                $brancharr[]=strtoupper($row['NAME']);
                                        }
                                }
                                else
                                {
                                        $branch_sel=strtoupper($center);
                                        $sql_t="SELECT NAME FROM billing.BRANCHES where REGION_ACC='$branch_sel'";
                                        $res=mysql_query_decide($sql_t,$db) or die(mysql_error_js());
                                        while($row=mysql_fetch_array($res))
                                        {
                                                $brancharr[]=strtoupper($row['NAME']);
                                        }
                                }
                                $branch_str="'".@implode("','",$brancharr)."'";
                        }
                        //else
			//$branch_str="'".'NOIDA'."'";
                        $sql.=" AND b.DEPOSIT_BRANCH IN($branch_str) ";
                }
		
		if($show=='emp')
                {
                        $emp_str="'".@implode("','",$employee)."'";
                        if($emp_str=="'All'")
                        {
                                if(in_array('ACCA',$privilege))
                                {
                                        if($showall=='Y')
                                                $sql_t="SELECT NAME FROM billing.BRANCHES WHERE 1";
                                        else
                                                $sql_t="SELECT NAME FROM billing.BRANCHES where REGION_ACC=''";
                                                                                                                             
                                        $res=mysql_query_decide($sql_t,$db) or die(mysql_error_js());
                                        while($row=mysql_fetch_array($res))
                                        {
                                                $brancharr[]=strtoupper($row['NAME']);
                                        }
                                }
                                else
                                {
                                        $branch_sel=strtoupper($center);
                                        $sql_t="SELECT NAME FROM billing.BRANCHES where REGION_ACC='$branch_sel'";
                                        $res=mysql_query_decide($sql_t,$db) or die(mysql_error_js());
                                        while($row=mysql_fetch_array($res))
                                        {
                                                $brancharr[]=strtoupper($row['NAME']);
                                        }
                                }
                                $branch_str="'".@implode("','",$brancharr)."'";
                                $sql_t="SELECT USERNAME FROM jsadmin.PSWRDS WHERE ACTIVE!='N' and PRIVILAGE REGEXP 'BA|BU' and UPPER(CENTER) IN($branch_str)";
                                $res=mysql_query_decide($sql_t,$db) or die(mysql_error_js());
                                while($row=mysql_fetch_array($res))
                                {
                                        $employeearr[]=$row['USERNAME'];
                                }
                                $emp_str="'".@implode("','",$employeearr)."'";
			}
			//else
                        $sql.=" AND a.SALE_BY IN($emp_str) ";
                }

		if($sort=='Branch')
			$sql.=" ORDER BY b.DEPOSIT_BRANCH ";
		if($sort=='Employee')
			$sql.=" ORDER BY a.SALE_BY ";
		if($sort=='Mode')
			$sql.=" ORDER BY b.MODE ";

		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			if($sort=='Branch')
			{
				if($last_sort!=$row['DEPOSIT_BRANCH'])
				{
					$arr[$i]['new_tab']='Y';
					$arr[$i]['last_sort']=$row['DEPOSIT_BRANCH'];
					$arr[$i-1]['tot_sort_amt_rs']=$tot_sort_amt_rs;
					$tot_sort_amt_rs=0;
					$arr[$i-1]['tot_sort_amt_dol']=$tot_sort_amt_dol;
					$tot_sort_amt_dol=0;
					
				}
				$last_sort=$row['DEPOSIT_BRANCH'];
			}
			if($sort=='Employee')
                        {
                                if($last_sort!=$row['SALE_BY'])
				{
                                        $arr[$i]['new_tab']='Y';
					$arr[$i]['last_sort']=$row['SALE_BY'];
					$arr[$i-1]['tot_sort_amt_rs']=$tot_sort_amt_rs;
					$tot_sort_amt_rs=0;
				$arr[$i-1]['tot_sort_amt_dol']=$tot_sort_amt_dol;
					$tot_sort_amt_dol=0;
				}
                                $last_sort=$row['SALE_BY'];
                        }
			if($sort=='Mode')
                        {
                                if($last_sort!=$row['MODE'])
				{
                                        $arr[$i]['new_tab']='Y';
					$arr[$i]['last_sort']=$row['MODE'];
					$arr[$i-1]['tot_sort_amt_rs']=$tot_sort_amt_rs;
					$tot_sort_amt_rs=0;
					$arr[$i-1]['tot_sort_amt_dol']=$tot_sort_amt_dol;
					$tot_sort_amt_dol=0;
				}
                                $last_sort=$row['MODE'];
                        }
			$arr[$i]['client']=$row['COMP_NAME'];
			$arr[$i]['sale_des']=$row['SALE_DES'];
			$arr[$i]['saleid']="JR-".$row['SALEID'];
			$arr[$i]['dueamount']=$row['DUEAMOUNT'];
			$arr[$i]['duedate']= substr($row['DUE_DT'],0,10);  
			$arr[$i]['receiptid']=$row['RECEIPTID'];
			$arr[$i]['mode']=$row['MODE'];
			$arr[$i]['type']=$row['TYPE'];
			$arr[$i]['amt']=$row['AMOUNT'];
			if($row['TYPE']=='RS')
			{
				$tot_sort_amt_rs+=$row['AMOUNT'];
				$tot_rs+=$row['AMOUNT'];
			}
			else
			{
				$tot_sort_amt_dol+=$row['AMOUNT'];
				$tot_dol+=$row['AMOUNT'];
			}
			$arr[$i]['tds']=$row['TDS'];
			$arr[$i]['cd_dt']=$row['CD_DT'];
			$arr[$i]['cd_num']=$row['CD_NUM'];
			$arr[$i]['t_num']=$row['TRANS_NUM'];
			$arr[$i]['cd_city']=$row['CD_CITY'];
			$arr[$i]['bank']=$row['BANK'];
			$arr[$i]['entry_dt']=substr($row['ENTRY_DT'],0,10);
			$arr[$i]['sale_by']=$row['SALE_BY'];
			$arr[$i]['deposit_dt']=$row['DEPOSIT_DT'];
			$arr[$i]['deposit_branch']=$row['DEPOSIT_BRANCH'];
			$i++;
		}
		$arr[$i-1]['tot_sort_amt_rs']=$tot_sort_amt_rs;	
		$arr[$i-1]['tot_sort_amt_dol']=$tot_sort_amt_dol;	

                //-----------  New code set for XLS formation Start ------------        
		if($report_format=='XLS')
		{
                        $dataSet1 =$date1." To ".$date2;
                        $dataSet2 ="Total(Rs) $tot_rs And Total(DOL) ";
                        if($tot_dol)
                                $dataSet2 .="$tot_dol";
                        else
                                $dataSet2 .=0;

                	$dataHeader =array("entry_dt"=>"Entry Date","client"=>"Client name","saleid"=>"Sale ID","sale_des"=>"Sale Description","type"=>"Type","amt"=>"Sale Amount(Incl. of tax)","dueamount"=>"Due amount","duedate"=>"Due Date","sale_by"=>"Sale by");

                	$totrec =count($arr);
                	for($i=0; $i<$totrec; $i++)
                	{
                	        if($arr["$i"]['new_tab']=='Y')
                	                $dataSet3 =$arr["$i"]['last_sort'];

                	        unset($arr["$i"]['new_tab']);
                	        unset($arr["$i"]['last_sort']);
                        	unset($arr["$i"]['cd_dt']);
                        	unset($arr["$i"]['cd_city']);
                        	unset($arr["$i"]['bank']);
                        	unset($arr["$i"]['deposit_dt']);
				unset($arr["$i"]['receiptid']);
				unset($arr["$i"]['mode']);
				unset($arr["$i"]['tds']);
				unset($arr["$i"]['cd_num']);
				unset($arr["$i"]['t_num']);
				unset($arr["$i"]['bank']);
				unset($arr["$i"]['deposit_dt']);
				unset($arr["$i"]['deposit_branch']);

                        	if( ($arr["$i"]['tot_sort_amt_rs']) || ($arr["$i"]['tot_sort_amt_dol']))
                        	{
                        	        $dataSet4 ="Total(Rs) ".$arr["$i"]['tot_sort_amt_rs'];
                        	        $dataSet5 ="Total(Dol) ".$arr["$i"]['tot_sort_amt_dol'];
                        	}
                        	unset($arr["$i"]['tot_sort_amt_rs']);
                        	unset($arr["$i"]['tot_sort_amt_dol']);

                	}
        	        $dataSet =getExcelData($arr,$dataHeader);
        	        header("Content-Type: application/vnd.ms-excel");
        	        header("Content-Disposition: attachment; filename=Mis_outstanding_report.xls");
        	        header("Pragma: no-cache");
        	        header("Expires: 0");
        	        echo $dataSet1."\n\n".$dataSet2."\n\n".$dataSet3."\n\n".$dataSet."\n\t".$dataSet4."\t\t".$dataSet5;         
        	        die;
		}
                //--------  New code set for XLS formation Start Ends ---------------



		$smarty->assign("arr",$arr);
		$smarty->assign("tot_rs",$tot_rs);
		$smarty->assign("tot_dol",$tot_dol);
		$smarty->assign("cid",$cid);
		$smarty->assign("flag","1");
		$smarty->display("rev_dueamount_mis.htm");
	}
	else
	{
		$dt_arr=explode("-",Date('Y-m-d'));
		for($i=0;$i<12;$i++)
		{
			$mmarr[$i]=$i+1;
		}

		for($i=0;$i<31;$i++)
		{
			$ddarr[$i]=$i+1;
		}

		for($i=2005;$i<=date('Y')+1;$i++)
                        $yyarr[] = $i;

		if(in_array('ACCU',$privilege))
                {
                        $branch_sel=strtoupper($center);
			$branch = array();
                        $sql="SELECT NAME FROM billing.BRANCHES where REGION_ACC='$branch_sel'";
                        $res=mysql_query_decide($sql,$db) or die(mysql_error_js($db));
                        while($row=mysql_fetch_array($res))
                        {
                                $branch[]=strtoupper($row['NAME']);
                        }
                }
                                                                                                                             
                elseif(in_array('ACCA',$privilege))
                {
                        //$branch_sel=strtoupper($row['CENTER']);
                                                                                                                             
                        if($showall=='Y')
                                $sql="SELECT NAME FROM billing.BRANCHES WHERE 1";
                        else
                                $sql="SELECT NAME FROM billing.BRANCHES where REGION_ACC=''";
                                                                                                                             
                        $res=mysql_query_decide($sql,$db) or die(mysql_error_js($db));
                        while($row=mysql_fetch_array($res))
                        {
                                $branch[]=strtoupper($row['NAME']);
                        }
                        $smarty->assign("admin","Y");
                }
                else
                {
                        echo "sorry you can't see this mis";
                        die();
                }

		if(count($branch)>0)
		{
			$branch_str="'".@implode("','",$branch)."'";
                	$sql="SELECT USERNAME FROM jsadmin.PSWRDS WHERE ACTIVE!='N' and PRIVILAGE REGEXP 'BA|BU' and UPPER(CENTER) IN($branch_str)";
			$res=mysql_query_decide($sql) or die(mysql_error_js());
			while($row=mysql_fetch_array($res))
			{
				$employee[]=$row['USERNAME'];
			}
		}
		$smarty->assign("dt",$dt_arr[2]);
		$smarty->assign("mm",$dt_arr[1]);
		$smarty->assign("yy",$dt_arr[0]);
		$smarty->assign("cid",$cid);
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("yyarr",$yyarr);
		$smarty->assign("branch",$branch);
		$smarty->assign("employee",$employee);

		$smarty->display("mis_search_dueamount.htm");
	}
}
else
{
	$smarty->display("jsconnectError.tpl");
}
?>
