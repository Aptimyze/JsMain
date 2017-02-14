<?php

if($CMDGo)
{
	//condition added to show amount from ORDERS for CCAVENUE and TRANSECUTE in case of DOLLAR payment as we receive amount in INR.
	//Applied from April 2008 otherwise old logic.
	if(($yy > 2009) || ($mm > 3 && $yy==2009)) 
	{
		header('Location: deferred_monthwise_newlogic2009.php?cid='.$cid.'&CMDGo=1&mm='.$mm.'&yy='.$yy);
                die();
	}
	elseif(($yy > 2008) || ($mm > 3 && $yy==2008))
	{
		header('Location: deferred_monthwise_newlogic.php?cid='.$cid.'&CMDGo=1&mm='.$mm.'&yy='.$yy);
		die();
	}
}

include("connect.inc");
ini_set("memory_limit","32M");

$duration =array(	"S1" => 3,
			"S2" => 6,
			"S3" =>	12,
			"S4" => 3,
			"S5" => 6,
			"S6" => 12,
			"P2" => 2,
			"P3" => 3,
			"P4" => 4,
			"P5" => 5,
			"P6" => 6,
			"P12" => 12
		  );
if(authenticated($cid))
{
	$db=connect_misdb();
	if($CMDGo)
	{
		$filename="deferral.xls";
		if (!$handle = fopen($filename, 'w'))
		{
			 echo "Cannot open file ($filename)";
		 	exit;
	   	}
		$header = "Sno"."\t"."username"."\t"."type"."\t"."netamount"."\t"."startdate"."\t"."expirydate"."\t"."serviceduration"."\t"."deferredduration"."\t"."deferredamount"."\t"."Total (RS)"."\t"."Total (DOL)"."\t \n";
		 if (fwrite($handle, $header) === FALSE)
		 {
		       echo "Cannot write to file ($filename)";
		       exit;
		 }
			
		$lastdayofmonth= getlastdayofmonth($mm,$yy);
		$month_end_date= $yy."-".$mm."-".$lastdayofmonth;
		$month_start_date= $yy."-".$mm."-01";
		$sql= " SELECT DISTINCT SERVICE_STATUS.BILLID, SERVICE_STATUS.SERVICEID, USERNAME, 
			ACTIVATED_ON, EXPIRY_DT
			FROM billing.SERVICE_STATUS, billing.PURCHASES
			WHERE PURCHASES.BILLID = SERVICE_STATUS.BILLID
			AND (
			(MONTH( SERVICE_STATUS.ACTIVATED_ON ) = '$mm' AND YEAR(SERVICE_STATUS.ACTIVATED_ON) = '$yy')
			OR (MONTH( SERVICE_STATUS.EXPIRY_DT ) = '$mm' AND YEAR(SERVICE_STATUS.EXPIRY_DT) = '$yy')
			OR (
			SERVICE_STATUS.ACTIVATED_ON < '$month_start_date'
			AND SERVICE_STATUS.EXPIRY_DT  >'$month_end_date'
			   )
			)
			AND SERVICE_STATUS.ACTIVATED_ON !=0 AND STATUS='DONE'
			ORDER BY BILLID	";
		$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		while($myrow=mysql_fetch_array($result))
		{
			$billid[]=$myrow['BILLID'];
			$arr[$myrow['BILLID']]['ACTIVATED_ON']=$myrow['ACTIVATED_ON'];
			$arr[$myrow['BILLID']]['EXPIRY_DT']=$myrow['EXPIRY_DT'];
			$arr[$myrow['BILLID']]['USERNAME']=$myrow['USERNAME'];
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
			$data = trim($line)."\t \n";
			//$data .= trim($line)."\t \n";
			fwrite($handle, $data);
			$i++;
		}
	
	$line="NA"."\t"."NA"." \t"."NA"." \t"."NA"." \t"."NA"." \t"."NA"." \t"."NA"." \t"."NA"." \t"."NA"." \t".$total_rs." \t".$total_dol;
	$data = trim($line)."\t \n";
	//$data .= trim($line)."\t \n";
	fwrite($handle, $data);
	fclose($handle);
// Writing xls file to deferral.xls	
/*        $fd = fopen("deferral.xls", "w");
                                                                                                 
        for($i=0;$i<strlen($data);$i+=4096)
        {
                $buffer = substr($data,$i,4096);
                fputs($fd,$buffer) or die("Cannot put on file .");
        }
        fclose($fd) or die("Cannot close the file .");
// ends here
  */                                                                                               
        $msg = "<a href=\"$filename\">";
        //$msg .= "<a href=\"get_deferred_monthwise.php?cid=$cid\">";
                                                                                                 
	$msg .= "Download Deferral sheet for ".$mm."/".$yy."</a>";
	$smarty->assign("cid",$cid);
	$smarty->assign("MSG",$msg);
	$smarty->display("mis_msg1.htm");

	
/*	header("Content-Type: application/vnd.ms-excel");
	header("Content-Disposition:attachment; filename=deferral.xls");
	header("Pragma: no-cache");
	header("Expires: 0");
	echo $output = $header."\n".$data;
*/
	}
	else
	{
	         for($i=0;$i<12;$i++)
                 {
                        $mmarr[$i]=$i+1;
			if(strlen($mmarr[$i])==1)
				$mmarr[$i]= "0".$mmarr[$i];
                 }
                                                                                                 
                                                                                                 
                                                                                                 
                 for($i=2004;$i<=date("Y");$i++)
{
        $yyarr[$i-2004]=$i;
}
                 $smarty->assign("mmarr",$mmarr);
                 $smarty->assign("yyarr",$yyarr);
                 $smarty->assign("cid",$cid);

		$smarty->display("deferred_monthwise.htm");
	}
}
else
{
        $smarty->display("jsconnectError.tpl");
}


function getTimeDiff($date1,$date2)
{
        if($date2 > $date1)
        {
		list($yy1,$mm1,$dd1)= explode("-",$date1);
		list($yy2,$mm2,$dd2)= explode("-",$date2);
		$begin_date = gregoriantojd($mm1,$dd1,$yy1);
		$end_date = gregoriantojd($mm2,$dd2,$yy2);

		return $end_date - $begin_date;
	}
        elseif($date2 == $date1)
                return 0;
        else
                return 0;
/*
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
*/
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
//echo $date1."<br>".$date2."<br>".$month."<br>".$year."<br>";
        if($date1>$date2)
                return 0;
        $lastdayofmonth=getlastdayofmonth($month,$year);
        $month_end_date=$year."-".$month."-".$lastdayofmonth;
        $month_start_date=$year."-".$month."-01";//date changed from 0 to 1 for greogory...
	$month_end_date_timestamp= mktime(0,0,0,$month,$lastdayofmonth,$year);
        $next_month_start_timestamp= $month_end_date_timestamp+(24*60*60);
	$next_month_start= date('Y-m-d',$next_month_start_timestamp);

        list($year1,$month1,$day1)=explode("-",$date1);
        list($year2,$month2,$day2)=explode("-",$date2);
        if((($year1.$month1) < ($year.$month)) and (($year2.$month2) > ($year.$month)))
                $returnvalue= $lastdayofmonth;
        else
        {
                if (($year1.$month1) == ($year.$month))
                        $returnvalue= getTimeDiff($date1,$next_month_start);
                elseif(($year2.$month2) == ($year.$month)) 
                        $returnvalue=  getTimeDiff($month_start_date,$date2) + 1;//1 day added to consider both first and last date 
                else
                        $returnvalue= 0;
        }
return $returnvalue;
                                                                                                 
}

?>
