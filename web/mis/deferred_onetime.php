<?php

include("connect.inc");
ini_set("memory_limit","16M");
$db=connect_misdb();
$db2=connect_master();

		$mm=12;
		$yy=2005;

		$header = "Sno"."\t"."username"."\t"."type"."\t"."netamount"."\t"."startdate"."\t"."expirydate"."\t"."serviceduration"."\t"."deferredduration"."\t"."deferredamount"."\t"."Total (RS)"."\t"."Total (DOL)";
		
		$lastdayofmonth= getlastdayofmonth($mm,$yy);
		$month_end_date= $yy."-".$mm."-".$lastdayofmonth;
		$month_start_date= $yy."-".$mm."-01";

		$sql="SELECT MAX(ID) AS ID, PROFILEID FROM billing.SERVICE_STATUS GROUP BY PROFILEID";
		$res=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		while($row=mysql_fetch_array($res))
		{
			$arr_id[]=$row['ID'];
		}
		if($arr_id)
			$arr_str=implode("','",$arr_id);
		unset($arr_id);

		$sql="SELECT BILLID,SERVICEID,PROFILEID,EXPIRY_DT,ACTIVATED_ON FROM billing.SERVICE_STATUS WHERE ID IN ('$arr_str') AND EXPIRY_DT>='2005-12-31' AND ACTIVATED_ON <> 0";

		unset($arr_str);

		$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		while($myrow=mysql_fetch_array($result))
		{
			$billid[]=$myrow['BILLID'];
			$arr[$myrow['BILLID']]['ACTIVATED_ON']=$myrow['ACTIVATED_ON'];
			$arr[$myrow['BILLID']]['EXPIRY_DT']=$myrow['EXPIRY_DT'];
			$arr[$myrow['BILLID']]['USERNAME']=$myrow['PROFILEID'];
			$arr[$myrow['BILLID']]['SERVICEID']=$myrow['SERVICEID'];
		}
		if(count($billid)>0)
			$billid_str= implode("','",$billid);
		else
		{
			$msg = "No Payments in this month";
			$smarty->assign("cid",$cid);
			$smarty->assign("MSG",$msg);
			$smarty->display("mis_msg1.htm");
			die();
		}
		$sql="SELECT BILLID,PROFILEID,TYPE,sum(AMOUNT) as AMOUNT from billing.PAYMENT_DETAIL where BILLID in ('$billid_str') and STATUS='DONE' group by BILLID"; 
		$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		$i=1;
		$total_rs=0;
		$total_dol=0;
		while($myrow=mysql_fetch_array($result))
		{
			$startdate = $arr[$myrow['BILLID']]['ACTIVATED_ON'];
			$expirydate = $arr[$myrow['BILLID']]['EXPIRY_DT'];
			$serviceduration = getTimeDiff($arr[$myrow['BILLID']]['ACTIVATED_ON'],$arr[$myrow['BILLID']]['EXPIRY_DT']);
			$deferredduration = days_month($arr[$myrow['BILLID']]['ACTIVATED_ON'],$arr[$myrow['BILLID']]['EXPIRY_DT'],$mm,$yy);
			$netamount=$myrow["AMOUNT"];
			$deferredamount=  round((($deferredduration * $netamount)/ $serviceduration),2);
			if($myrow['TYPE']=="RS")
				$total_rs += $deferredamount;
			elseif($myrow['TYPE']== "DOL")
				$total_dol += $deferredamount;
			$total_rs=round($total_rs,2);	
			$total_dol=round($total_dol,2);	
			$line=$i." \t".$arr[$myrow['BILLID']]['USERNAME']." \t".$myrow["TYPE"]." \t".$netamount." \t".$startdate." \t".$expirydate." \t".$serviceduration." \t".$deferredduration." \t".$deferredamount." \t".""." \t";
			$line = str_replace("\t".'$', '', $line);
			$data .= trim($line)."\t \n";
			$i++;
		}
	$line="NA"."\t"."NA"." \t"."NA"." \t"."NA"." \t"."NA"." \t"."NA"." \t"."NA"." \t"."NA"." \t"."NA"." \t".$total_rs." \t".$total_dol;
	$data .= trim($line)."\t \n";

// Writing xls file to deferral.xls	
        $fd = fopen("deferral.xls", "w");
                                                                                                 
        for($i=0;$i<strlen($data);$i+=4096)
        {
                $buffer = substr($data,$i,4096);
                fputs($fd,$buffer) or die("Cannot put on file .");
        }
        fclose($fd) or die("Cannot close the file .");
// ends here
                                                                                                 
function getTimeDiff($date1,$date2)
{
        if($date2 > $date1)
        {
                list($yy1,$mm1,$dd1)= explode("-",$date1);
                list($yy2,$mm2,$dd2)= explode("-",$date2);
                $date1_timestamp= mktime(0,0,0,$mm1,$dd1,$yy1);
                $date2_timestamp= mktime(0,0,0,$mm2,$dd2,$yy2);
                $timestamp_diff= $date2_timestamp - $date1_timestamp;
                $days_diff= $timestamp_diff / (24*60*60);
                return floor($days_diff);
        }
        elseif($date2 == $date1)
                return 0;
        else
                return 0;
}

function getlastdayofmonth($mm,$yy)
{
	if($mm<10)
		$mm="0".$mm;

	switch($mm)
	{
		case '01' : $ret='31';
			break;
		case '02' : 
			$check=date("L",mktime(0,0,0,$mm,31,$yy));
			if($check)
				$ret='29';
			else
				$ret='28';
			break;
		case '03' : $ret='31';
			break;
		case '04' : $ret='30';
			break;
		case '05' : $ret='31';
			break;
		case '06' : $ret='30';
			break;
		case '07' : $ret='31';
			break;
		case '08' : $ret='31';
			break;
		case '09' : $ret='30';
			break;
		case '10' : $ret='31';
			break;
		case '11' : $ret='30';
			break;
		case '12' : $ret='31';
			break;
	}
	return $ret;
}
function days_month($date1,$date2,$month,$year)
{
	$returnvalue= getTimeDiff("2006-01-01",$date2);
	return $returnvalue;
}

?>
