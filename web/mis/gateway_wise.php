<?php
include("connect.inc");
include_once("../profile/pg/functions.php");    // included for dollar conversion rate
$db=connect_misdb();

if(authenticated($checksum))
{
	if($CMDGo)
	{
		$smarty->assign("flag","1");
		$st_date=$year."-".$month."-".$day." 00:00:00";
		$end_date=$year2."-".$month2."-".$day2." 23:59:59";
        if(strtotime($st_date) >= strtotime("2017-04-01 00:00:00")){
            $tableName = "PAYMENT_DETAIL_NEW";
            $condition = "IN ('DONE','BOUNCE','CANCEL', 'REFUND', 'CHARGE_BACK')";
        }
        else{
            $tableName = "PAYMENT_DETAIL";
            $condition = "IN ('DONE','CHARGE_BACK')";
        }
		// Cases for date range check
		$dateFlag;
		if(strtotime($st_date) > strtotime($end_date)){
			$dateFlag = 0; // Default flag, covers an invalid date range
			$smarty->assign("flag","0");
		}elseif(strtotime($st_date) <= strtotime("2014-06-30 23:59:59") && strtotime($end_date) <= strtotime("2014-06-30 23:59:59")){
			// Both dates are less than 2nd July 2014
			$dateFlag = 1;
		}elseif(strtotime($st_date) >= strtotime("2014-07-02 00:00:00") && strtotime($end_date) >= strtotime("2014-07-02 00:00:00")){
			// Both dates are greater than 2nd July 2014
			$dateFlag = 2;
		}elseif(strtotime($st_date) <= strtotime("2014-07-01 23:59:59") && strtotime($end_date) >= strtotime("2014-07-02 00:00:00")){
			// Start date is less than 2nd July and End date is greater than 2nd July
			$dateFlag = 3;
		}

		if($dateFlag == 0){
			for($i=0;$i<12;$i++)
			{
				$mmarr[$i]=$i+1;
			}
			for($i=2005;$i<=date('Y')+1;$i++)
	                        $yyarr[] = $i;

			for($i=0;$i<31;$i++)
			{
				$ddarr[$i]=$i+1;
			}
			$smarty->assign("ddarr",$ddarr);
			$smarty->assign("mmarr",$mmarr);
			$smarty->assign("yyarr",$yyarr);
			$smarty->assign("checksum",$checksum);
			$smarty->display("gateway_wise.htm");
			die();
		}

		$temp_gateway = $gateway;

		if($gateway!='ALL')
			$sql="select pd.DOL_CONV_RATE as rate,pur.USERNAME,pd.TYPE,if(APPLE_COMMISSION>0,pd.AMOUNT+pd.APPLE_COMMISSION,pd.AMOUNT) as amt,ord.ORDERID as ordno,ord.ID as ord_ID,pd.ENTRY_DT, ord.GATEWAY as GATEWAY,pd.INVOICE_NO as INVOICE_NO from billing.PURCHASES as pur,billing.$tableName as pd,billing.ORDERS as ord where pd.ENTRY_DT between '$st_date' and '$end_date' and pd.MODE='ONLINE' and pd.STATUS $condition and pd.BILLID=pur.BILLID and pur.ORDERID=ord.ID and ord.STATUS!='R' and ord.GATEWAY='$gateway'";
		else
			$sql="select pd.DOL_CONV_RATE as rate,pur.USERNAME,pd.TYPE,if(APPLE_COMMISSION>0,pd.AMOUNT+pd.APPLE_COMMISSION,pd.AMOUNT) as amt,ord.ORDERID as ordno,ord.ID as ord_ID,pd.ENTRY_DT, ord.GATEWAY as GATEWAY,pd.INVOICE_NO as INVOICE_NO from billing.$tableName as pd,billing.PURCHASES as pur,billing.ORDERS as ord where pd.ENTRY_DT between '$st_date' and '$end_date' and pd.MODE='ONLINE' and pd.STATUS $condition and pd.BILLID=pur.BILLID and pur.ORDERID=ord.ID and ord.STATUS!='R'";
                
		//print $sql.PHP_EOL;
		// print $dateFlag;
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		$gateways=array();
		$final_count;

		if($row=mysql_fetch_array($res))
		{
			$i=0;
			do
			{
				if($gateway)
				{	

					// Show only 70% amount for Apple transations
					if($row['GATEWAY'] == 'APPLEPAY'){
						$row['amt'] = round(($row['amt']*0.70),2);
					}

					if($gateway == "ALL"){
						$gateway = $row['GATEWAY'];
						$entry_dt=$row['ENTRY_DT'];
						$j=array_search($row['GATEWAY'],$gateways);
						if($j===FALSE)
						{	
							$gateways[]=$row['GATEWAY'];
							$j=array_search($row['GATEWAY'],$gateways);
							if($report_format=='XLS'){
								$arr_gate[$j]['GATE'] =$row['GATEWAY'];
							}
						}
						if($row['TYPE']=='DOL')
						{
							$arr_gate[$j]['DOL']+=$row['amt'];
							$total_dollars_gateway[$j]+=$row['amt'];
						}
						else
						{
							$arr_gate[$j]['RS']+=$row['amt'];
							$total_rs_amount_gateway[$j]+=$row['amt'];
						}

						if($final_count < $j){
							$final_count = $j;
						}
					}

					$entry_dt=$row['ENTRY_DT'];
					$arr[$i]["username"]=$row['USERNAME'];
					list($edt,$time)=explode(" ",$entry_dt);
					list($yy,$mm,$dd)=explode("-",$edt);
					$arr[$i]["entry_dt"]=$dd."/".$mm."/".$yy;
					if($row['TYPE']=='DOL' && ($gateway=='CCAVENUE' || $gateway=='PAYU' || $gateway=='APPLEPAY' || $gateway=='PAYTM')){
						if($dateFlag == 1){
							$arr[$i]["type"]='Con-INR';
						}elseif($dateFlag == 2){
							$arr[$i]["type"]='DOL';
						}elseif($dateFlag == 3){
							if(strtotime($entry_dt) <= strtotime("2014-07-01 23:59:59")){
								$arr[$i]["type"]='Con-INR';
							}else{
								$arr[$i]["type"]='DOL';
							}
						}
					}elseif($row['TYPE']=='DOL' && $gateway == 'TRANSECUTE'){
						$arr[$i]["type"]='Con-INR';
					}else{
						$arr[$i]["type"]=$row['TYPE'];
					}
					$arr[$i]["amt_topay"]=$row["amt"];
					$arr[$i]["ordno"]=$row["ordno"]."-".$row["ord_ID"];
					$arr[$i]["rate"]=$row["rate"];
					$arr[$i]["gateway"]=$gateway;
					$arr[$i]["invoice_no"]=$row['INVOICE_NO'];
					if($row['TYPE']=='DOL')
					{
						if ($gateway=='CCAVENUE' || $gateway == 'PAYU' || $gateway=='APPLEPAY' || $gateway=='PAYTM'){
							if($dateFlag == 1){
								$total_dol_amount+=$row["amt"];
							}elseif($dateFlag == 2){
								$total_dollars+=$row["amt"];
							}elseif($dateFlag == 3){
								if(strtotime($entry_dt) <= strtotime("2014-07-01 23:59:59")){
									$total_dol_amount+=$row["amt"];
								}else{
									$total_dollars+=($row["amt"]);
								}
							}
						}elseif($gateway=='TRANSECUTE'){
							$total_dol_amount+=$row["amt"];
						}else{
							$total_dollars+=$row["amt"];
						}
					}
					else
					{
						$total_rs_amount+=$row["amt"];
					}
				}	
				$i++;
				$gateway = $temp_gateway;
			}while($row=mysql_fetch_array($res));
		}

		// if($gateway=='ALL'){
		// 	for($k=0;$k<=$final_count;$k++)
		// 	{
		// 		$total_paid+=$total_paid_gateway[$k];
		// 		$total_dollars+=$total_dollars_gateway[$k];
		// 		$total_dol_amount+=$total_dol_amount_gateway[$k];
		// 		$total_rs_amount+=$total_rs_amount_gateway[$k];
		// 	}
		// }

		//-----------  New code set for XLS formation Start ------------
		if($report_format=='XLS')
		{

			$dataSetHeading1 ="Results for Gateway $gateway";
			$date1 =my_format_date($day,$month,$year);
			$date2 =my_format_date($day2,$month2,$year2);
			$date11 =str_replace(","," ",$date1);
			$date22 =str_replace(","," ",$date2);
			$dataSetHeading2 ="Duration: $date11 to $date22";

			if($gateway=='ALL')
			{

			// Adding total in array
				$arr_gate[] =array("GATE"=>"ALL","DOL"=>"$total_dollars","CON"=>"$total_dol_amount","RS"=>"$total_rs_amount");
				$dataHeader =array("GATE"=>"Gateway","DOL"=>"Total Collection For Dollars($)","CON"=>"Converted Rs Value","RS"=>"Total Collection in Rs");
				$dataSet = getExcelData($arr_gate,$dataHeader);
				$dataSetComp =$dataSetHeading1."\n\n".$dataSetHeading2."\n\n".$dataSet."\n\n";			
			}
			
			$dataSet1 ="Gateway \t Total Collection For Dollars($) \t Converted Rs Value \t Total Collection in Rs";
			$dataSet2 ="$gateway \t $total_dollars \t $total_dol_amount \t $total_rs_amount";	

			$dataHeader1 =array("entry_dt"=>"Date","username"=>"Username","ordno"=>"OrderId","type"=>"Type","amt_topay"=>"Amount","gateway"=>"Gateway","invoice_no"=>"Invoice No");

			$dataSet .= getExcelData($arr,$dataHeader1);
			$dataSetComp =$dataSetHeading1."\n\n".$dataSetHeading2."\n\n".$dataSet1 ."\n".$dataSet2."\n\n\n".$dataSet;
			
			header("Content-Type: application/vnd.ms-excel");
			header("Content-Disposition: attachment; filename=Gateweway_wise_report.xls");
			header("Pragma: no-cache");
			header("Expires: 0");
			echo $dataSetComp;
			die;
		}
		//-----------  New code set for XLS formation Ends  ------------
		$smarty->assign("arr",$arr);
		$smarty->assign("day",$day);
		$smarty->assign("month",$month);
		$smarty->assign("year",$year);
		$smarty->assign("day2",$day2);
		$smarty->assign("month2",$month2);
		$smarty->assign("year2",$year2);
		$smarty->assign("date1",my_format_date($day,$month,$year));
		$smarty->assign("date2",my_format_date($day2,$month2,$year2));
		$smarty->assign("gateway",$gateway);
		$smarty->assign("gateways",$gateways);
		if($gateway=='ALL')
		{	
			$smarty->assign("arr_gate", $arr_gate);
			$smarty->assign("total_paid_gateway",$total_paid_gateway);
			$smarty->assign("total_dollars_gateway",$total_dollars_gateway);
			$smarty->assign("total_dol_amount_gateway",$total_dol_amount_gateway);
			$smarty->assign("total_rs_amount_gateway",$total_rs_amount_gateway);
		}
		$smarty->assign("total_paid",$total_paid);
		$smarty->assign("total_dollars",$total_dollars);
		$smarty->assign("total_dol_amount",$total_dol_amount);
		$smarty->assign("total_rs_amount",$total_rs_amount);
		$smarty->assign("checksum",$checksum);
		$smarty->assign("rate",$DOL_CONV_RATE);
		$smarty->display("gateway_wise.htm");
	}
	else
	{
		for($i=0;$i<12;$i++)
		{
			$mmarr[$i]=$i+1;
		}
		for($i=2005;$i<=date('Y')+1;$i++)
                        $yyarr[] = $i;
		for($i=0;$i<31;$i++)
		{
			$ddarr[$i]=$i+1;
		}
		$smarty->assign("ddarr",$ddarr);
		$smarty->assign("mmarr",$mmarr);
		$smarty->assign("yyarr",$yyarr);
		$smarty->assign("checksum",$checksum);
		$smarty->display("gateway_wise.htm");
	}
}
else
{
	$smarty->display("jsconnectError.tpl");
}
?>
