<?

/**
*	Filename	:	collection_status.php
*	Included	:	connect.inc
*	Description	:	To mark a payment as 'RECEIVED' by the Accounts.
*	Created by	:	Kaushlendra Rai
*	Created on	:	21/05/2006
*
*/

// FLAGS used for PAYMENT_DETAIL table's field 'COLLECTED' -> 'P' - Pending
//							      'Y' - Collected

include_once(JsConstants::$cronDocRoot.'/lib/model/lib/FieldMapLib.class.php');
include("connect.inc");
                                                                                                 
$db2=connect_master();
$db = connect_rep();

$data=authenticated($cid);
if(isset($data))
//if(1)
{
	$user=getuser($cid,"0.0.0.0");
        if(($user=='shyam.kumar') || ($user=='shilpi.sharma'))
                $showall='Y';
	$privilege=explode("+",getprivilage($cid));
	$center=getcenter_for_operator($user); 

	if($preview_received || $preview_not_received)// show user the Preview of selected items.
	{
		// Fetch information for each selected receipt.
		for($i=0;$i<count($mark_coll);$i++)
		{
			$sql="SELECT a.USERNAME,a.BILLID,a.WALKIN,b.COLLECTED,b.RECEIPTID, b.INVOICE_NO,b.MODE,b.AMOUNT,b.TYPE,b.CD_DT,b.CD_NUM,b.CD_CITY,b.BANK,b.ENTRY_DT,b.DEPOSIT_DT,b.DEPOSIT_BRANCH,b.TRANS_NUM from billing.PAYMENT_DETAIL as b,billing.PURCHASES as a WHERE b.RECEIPTID='".$mark_coll[$i]."' AND a.BILLID=b.BILLID";
			$result=mysql_query_decide($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");

			while($row=mysql_fetch_array($result))
			{
				$arr[$i]['client']=$row['USERNAME'];
				$sql_slave="SELECT COUNTRY_RES FROM newjs.JPROFILE WHERE USERNAME='{$row['USERNAME']}'";
				$res_slave=mysql_query_decide($sql_slave,$db) or die(mysql_error_js());
				if($row_slave=mysql_fetch_array($res_slave))
				{
					$arr[$i]['country'] = FieldMap::getFieldLabel('country',$row_slave['COUNTRY_RES']);
				}
				$billidArr[] = $row['BILLID'];
				$arr[$i]['saleid']="JR-".$row['BILLID'];
				$arr[$i]['receiptid']=$row['RECEIPTID'];
				$arr[$i]['mode']=$row['MODE'];
				//$emp_str="'".implode("','",$employee)."'";
				$arr[$i]['type']=$row['TYPE'];
				$arr[$i]['amt']=$row['AMOUNT'];
				$arr[$i]['cd_dt']=$row['CD_DT'];
				$arr[$i]['cd_num']=$row['CD_NUM'];
				$arr[$i]['cd_city']=$row['CD_CITY'];
				$arr[$i]['bank']=$row['BANK'];
				$arr[$i]['entry_dt']=substr($row['ENTRY_DT'],0,10);
				$arr[$i]['sale_by']=$row['WALKIN'];
				$arr[$i]['deposit_dt']=$row['DEPOSIT_DT'];
				$arr[$i]['deposit_branch']=$row['DEPOSIT_BRANCH'];

				if($row['COLLECTED']=="P")
					$arr[$i]['collection_status']="Pending";
				else
					$arr[$i]['collection_status']="Collected";
				$arr[$i]['transaction_number']=$row['TRANS_NUM'];
				$arr[$i]['invoice_no']=$row['INVOICE_NO'];
			}
		
		}

		$billOrdDevObj = new billing_ORDERS_DEVICE('newjs_slave');
		$billOrdObj = new BILLING_ORDERS('newjs_slave');
		$transObj = new billing_TRACK_TRANSACTION_DISCOUNT_APPROVAL('newjs_slave');
		if(is_array($billidArr)) {
			$orderId = $billOrdDevObj->getPaymentSourceFromBillidStr(implode(",",$billidArr));
			$approvedByArr = $transObj->fetchApprovedBy($billidArr);
		}

		foreach($orderId as $temp=>$temp2){
			$orderidArr[] = $temp2['ID'];
		}
		if(is_array($orderidArr))
			$ordersArr = $billOrdObj->getOrderDetailsForIdStr(implode(",",$orderidArr));

		foreach($arr as $key=>&$val){
			foreach($orderId as $k=>$v){
				if(str_replace("JR-", "", $val['saleid']) == $k){
					$arr[$key]['orderid'] = $v['ORDERID']."-".$v['ID'];
					$arr[$key]['gateway'] = $ordersArr[$v['ID']]['GATEWAY'];
				}
			}
			$arr[$key]['approved_by'] = $approvedByArr[str_replace("JR-", "", $val['saleid'])];
		}

		if($preview_not_received)
			$smarty->assign("received","N");
		else
			$smarty->assign("received","Y");

		$smarty->assign("cid",$cid);
		$smarty->assign("arr",$arr);
		$smarty->display("preview_collection_status.htm");
	}
	else if($mark_received || $mark_not_received)// Perform the final UPDATE
	{
		if($mark_received)
		{
			$msg="The Receipts have been marked <b> 'Collection Done'</b>";
			$VALUE="Y";
		}
		else
		{
			$msg="The Receipts have been marked <b> 'Collection Not Received'</b>";
			$VALUE="P";
		}

		 for($i=0;$i<count($mark_coll);$i++)// Update the information.
		{
			$query="UPDATE billing.PAYMENT_DETAIL SET COLLECTED='".$VALUE."',COLLECTED_BY='".$user."',COLLECTION_DATE=now() WHERE RECEIPTID='".$mark_coll[$i]."'";
			mysql_query_decide($query,$db2) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$query,"ShowErrTemplate");
		}

		$smarty->assign("cid",$cid);
		$smarty->assign("MSG",$msg);
		$smarty->display("mis_msg1.htm");
	}
	else if($CMDGo)// Show information regarding to the 'SEARCH CRITERION' of first page.
	{
		$start_dt=$year."-".$month."-".$day." 00:00:00";
		$end_dt=$year2."-".$month2."-".$day2." 23:59:59";

                // Date range check added
                $datetime1      =JSstrToTime($start_dt);
                $datetime2      =JSstrToTime($end_dt);
                $timeCheck      =$datetime2-$datetime1;
                $days           =round($timeCheck/86400);
                if($days>'62'){
                        echo "<b> * Sorry, please select the date range again, it should not be greater than 2 months.</b>";
                        die();
                }

		$date1=$day."-".$month."-".$year;
		$date2=$day2."-".$month2."-".$year2;
		$smarty->assign("DATE1",$date1);
		$smarty->assign("DATE2",$date2);

		$i=0;

		$sql="SELECT a.USERNAME,a.BILLID,a.WALKIN,b.COLLECTED,b.RECEIPTID,b.INVOICE_NO,b.SOURCE,b.AMOUNT,b.TYPE,b.CD_DT,b.CD_NUM,b.CD_CITY,b.BANK,b.ENTRY_DT,b.DEPOSIT_DT,b.DEPOSIT_BRANCH,b.ENTRYBY, b.TRANS_NUM, a.DISCOUNT_TYPE from billing.PAYMENT_DETAIL as b,billing.PURCHASES as a WHERE b.ENTRY_DT BETWEEN '$start_dt' AND '$end_dt' AND a.BILLID=b.BILLID and b.STATUS='DONE' AND b.AMOUNT>0 ";

		if($currency=='inr')
			$sql.=" AND b.TYPE='RS' ";
		elseif($currency=='usd')
			$sql.=" AND b.TYPE='DOL' ";

		$mode_str=implode("','",$mode);
		if($mode_str!="")
		{
			if($mode_str=='ALL_CASH')
				$sql.= " AND b.SOURCE IN('CASH','BANK_TRSFR_CASH','EB_CASH')";
			elseif($mode_str=='ALL_CHEQUE')
				$sql.= " AND b.SOURCE IN('CHEQUE','DD','BANK_TRSFR_CHQ','EB_CHEQUE')";
			elseif($mode_str=='ALL_ONLINE')
				$sql.= " AND b.SOURCE IN('ONLINE','BANK_TRSFR_ONLINE','IVR')";
			elseif($mode_str=='CHEQUE')
				$sql.= " AND b.SOURCE IN('CHEQUE','DD')";
			else
				$sql.=" AND b.SOURCE IN('$mode_str') ";
		}
	
		if($show=='branch')
		{
			$branch_str="'".implode("','",$branch)."'";
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
				$branch_str="'".implode("','",$brancharr)."'";
			}
			//else
			$sql.=" AND b.DEPOSIT_BRANCH IN($branch_str) ";
		}
		if($show=='emp')
		{
			$emp_str="'".implode("','",$employee)."'";
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
				$branch_str="'".implode("','",$brancharr)."'";
				$sql_t="SELECT USERNAME FROM jsadmin.PSWRDS WHERE ACTIVE!='N' and PRIVILAGE REGEXP 'BA|BU' and UPPER(CENTER) IN($branch_str)";
				$res=mysql_query_decide($sql_t,$db) or die(mysql_error_js());
				while($row=mysql_fetch_array($res))
				{
					$employeearr[]=$row['USERNAME'];
				}
				$emp_str="'".implode("','",$employeearr)."'";
			}
			//else
			$sql.=" AND a.WALKIN IN($emp_str) ";
		}
		if($collection_status=="P")
		{
			$smarty->assign("collection_status","Pending");
                        $sql.=" AND b.COLLECTED='P'";
		}
                else if ($collection_status=="Y")
                {
		        $sql.=" AND b.COLLECTED='Y'";
			$smarty->assign("collection_status","Confirmed");
		}
		else
			$smarty->assign("collection_status","All");

		if($sort=='Branch')
			$sql.=" ORDER BY b.DEPOSIT_BRANCH ";
		if($sort=='Employee')
			$sql.=" ORDER BY a.WALKIN ";
		if($sort=='Mode')
			$sql.=" ORDER BY b.SOURCE ";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		
		$purchaseObj = new BILLING_PURCHASES('newjs_slave');
		$taxData = $purchaseObj->getDataFromTaxBreakUp($start_dt, $end_dt);
		// Fetch data in coresponding to search
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
				if($last_sort!=$row['WALKIN'])
				{
					$arr[$i]['new_tab']='Y';
					$arr[$i]['last_sort']=$row['WALKIN'];
					$arr[$i-1]['tot_sort_amt_rs']=$tot_sort_amt_rs;
					$tot_sort_amt_rs=0;
					$arr[$i-1]['tot_sort_amt_dol']=$tot_sort_amt_dol;
					$tot_sort_amt_dol=0;
				}
				$last_sort=$row['WALKIN'];
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
			$arr[$i]['client']=$row['USERNAME'];
			$sql_slave="SELECT COUNTRY_RES FROM newjs.JPROFILE WHERE USERNAME='{$row['USERNAME']}'";
			$res_slave=mysql_query_decide($sql_slave,$db) or die(mysql_error_js());
			if($row_slave=mysql_fetch_array($res_slave))
			{
				$arr[$i]['country'] = FieldMap::getFieldLabel('country',$row_slave['COUNTRY_RES']);
			}
			$billidArr[] = $row['BILLID'];
			$arr[$i]['saleid']=$row['BILLID'];
			$arr[$i]['receiptid']=$row['RECEIPTID'];
			$arr[$i]['mode']=$row['SOURCE'];
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

			$arr[$i]['cd_dt']=$row['CD_DT'];

			$arr[$i]['cd_num']=$row['CD_NUM'];
			$arr[$i]['cd_city']=$row['CD_CITY'];
			$arr[$i]['bank']=$row['BANK'];
			$arr[$i]['entry_dt']=substr($row['ENTRY_DT'],0,10);
			$arr[$i]['sale_by']=$row['WALKIN'];
			$arr[$i]['entry_by']=$row['ENTRYBY'];
			$arr[$i]['deposit_dt']=$row['DEPOSIT_DT'];
			$arr[$i]['deposit_branch']=$row['DEPOSIT_BRANCH'];
			$arr[$i]['transaction_number']=$row['TRANS_NUM'];
	
			if($row['COLLECTED']=="P")
				$arr[$i]['collection_status']="Pending";
			else
				$arr[$i]['collection_status']="Collected";

			$arr[$i]['invoice_no']=$row['INVOICE_NO'];
			$arr[$i]['discount_type'] = memDiscountTypes::$discountArr[$row['DISCOUNT_TYPE']];
			
			//Adding tax and city entries
			$billid = $row['BILLID'];
			
			$arr[$i]['SGST'] = $taxData[$billid]["SGST"];
			$arr[$i]['IGST'] = $taxData[$billid]["IGST"];
			$arr[$i]['CGST'] = $taxData[$billid]["CGST"];
			$arr[$i]['CITY_RES'] = $taxData[$billid]["CITY_RES"];
			$StateCity = $arr[$i]['CITY_RES'];
			$arr[$i]["CITY_RES"] = FieldMap::getFieldLabel("city",$StateCity);
			$StateCity = substr($StateCity, 0, 2);
			$arr[$i]["STATE_RES"] = FieldMap::getFieldLabel("state_india",$StateCity);
			$i++;
		}

		$billOrdDevObj = new billing_ORDERS_DEVICE('newjs_slave');
		$billOrdObj = new BILLING_ORDERS('newjs_slave');
		$transObj = new billing_TRACK_TRANSACTION_DISCOUNT_APPROVAL('newjs_slave');
		if(is_array($billidArr)) {
			$orderId = $billOrdDevObj->getPaymentSourceFromBillidStr(implode(",",$billidArr));
			$approvedByArr = $transObj->fetchApprovedBy($billidArr);
		}

		foreach($orderId as $temp=>$temp2){
			$orderidArr[] = $temp2['ID'];
		}
		if(is_array($orderidArr))
			$ordersArr = $billOrdObj->getOrderDetailsForIdStr(implode(",",$orderidArr));

		foreach($arr as $key=>&$val){
			foreach($orderId as $k=>$v){
				if(str_replace("JR-", "", $val['saleid']) == $k){
					$arr[$key]['orderid'] = $v['ORDERID']."-".$v['ID'];
					$arr[$key]['gateway'] = $ordersArr[$v['ID']]['GATEWAY'];
				}
			}
			$arr[$key]['approved_by'] = $approvedByArr[str_replace("JR-", "", $val['saleid'])];
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

                        $dataSet3 ="Transaction Number is the number depending on mode of payment (eg: if MODE is EB_CASH then TRANSACTION NUMBER is Easy Bill Reference ID)";
                        $dataSet4 ="Collection-Status";
		
			$dataHeader =array("entry_dt"=>"Entry-Dt","client"=>"Username","country"=>"User Country","saleid"=>"Bill-Id","receiptid"=>"Receipt-Id","mode"=>"Mode","type"=>"Type","amt"=>"Amount","cd_num"=>"Cheque/DD-No","sale_by"=>"Sale-By","entry_by"=>"Entry-By","deposit_branch"=>"Deposit-Branch","collection_status"=>"Collection-Status","transaction_number"=>"Transaction-Number","invoice_no"=>"Invoice-No","orderid"=>"Order-ID","gateway"=>"Gateway","approved_by"=>"Approved By","discount_type"=>"Discount Type");

			$totrec =count($arr);
			for($i=0; $i<$totrec; $i++)
			{
				if($arr["$i"]['new_tab']=='Y')
					$dataSet5 =$arr["$i"]['last_sort'];

				unset($arr["$i"]['new_tab']);
				unset($arr["$i"]['last_sort']);
	
				unset($arr["$i"]['cd_dt']);
				unset($arr["$i"]['cd_city']);
				unset($arr["$i"]['bank']);
				unset($arr["$i"]['deposit_dt']);
				
				if( ($arr["$i"]['tot_sort_amt_rs']) || ($arr["$i"]['tot_sort_amt_dol']))
				{
					$dataSet6 ="Total(Rs) ".$arr["$i"]['tot_sort_amt_rs'];
					$dataSet7 ="Total(Dol) ".$arr["$i"]['tot_sort_amt_dol'];	
				}
				unset($arr["$i"]['tot_sort_amt_rs']);
				unset($arr["$i"]['tot_sort_amt_dol']);	
			}

			$dataSet =getExcelData($arr,$dataHeader);
        	header("Content-Type: application/vnd.ms-excel");
        	header("Content-Disposition: attachment; filename=Payment_collection_status_report.xls");
        	header("Pragma: no-cache");
       		header("Expires: 0");
			echo $dataSet1."\n\n".$dataSet2."\n\n".$dataSet3."\n\n".$dataSet4."\n\n".$dataSet5."\n\n".$dataSet."\n\t".$dataSet6."\t\t".$dataSet7;
                	die;
		}	
		//--------  New code set for XLS formation Start Ends ---------------
	
		$smarty->assign("arr",$arr);
		$smarty->assign("tot_rs",$tot_rs);
		$smarty->assign("tot_dol",$tot_dol);
		$smarty->assign("cid",$cid);
		$smarty->assign("flag","1");
		$smarty->display("collection_status.htm");
	}
	else// Default first search page.
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

        $branch = array();
		if(in_array('ACCU',$privilege))
		{
			$branch_sel=strtoupper($center);
			$sql="SELECT NAME FROM billing.BRANCHES where REGION_ACC='$branch_sel'";
			$res=mysql_query_decide($sql,$db) or die(mysql_error_js($db));
			while($row=mysql_fetch_array($res))
			{
				$branch[] = strtoupper($row['NAME']);
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
				$branch[] = strtoupper($row['NAME']);
			}
			$smarty->assign("admin","Y");
		}
		else
		{
			echo "sorry you can't see this mis";
			die();
		}
		$branch_str="'".@implode("','",$branch)."'";
		$sql="SELECT USERNAME FROM jsadmin.PSWRDS WHERE ACTIVE!='N' and PRIVILAGE REGEXP 'BA|BU' and UPPER(CENTER) IN($branch_str)";
		$res=mysql_query_decide($sql,$db) or die(mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$employee[]=$row['USERNAME'];
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
		$smarty->display("collection_search.htm");
	}
}
else
{
	$smarty->display("jsconnectError.tpl");
}
?>
