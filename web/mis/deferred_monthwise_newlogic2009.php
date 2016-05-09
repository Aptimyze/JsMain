<?php

 //condition added to show amount from ORDERS for CCAVENUE and TRANSECUTE in case of DOLLAR payment as we receive amount in INR.
//Applied from April 2009 otherwise old logic. 
if(($yy < 2009) || ($mm < 3 && $yy==2009))
{
	die("Please follow proper links");
}


include("connect.inc");
ini_set("memory_limit","-1");
ini_set("max_execution_time","0");
$db2=connect_master();

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

$sql="SELECT NAME,SERVICEID FROM billing.SERVICES WHERE ACTIVE='Y'";
$res=mysql_query_decide($sql) or die("$sql".mysql_error_js());
while($row=mysql_fetch_array($res))
{
        $sid=$row['SERVICEID'];
        $name=$row['NAME'];
        $Service_arr[$sid]=$name;
}

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
		$header = "Sno"."\t"."Billid"."\t"."Service"."\t"."username"."\t"."type"."\t"."netamount"."\t"."startdate"."\t"."expirydate"."\t"."serviceduration"."\t"."deferredduration"."\t"."deferredamount"."\t"."Total (RS)"."\t"."Total (DOL)"."\t \n";
		 if (fwrite($handle, $header) === FALSE)
		 {
		       echo "Cannot write to file ($filename)";
		       exit;
		 }
			
		$lastdayofmonth= getlastdayofmonth($mm,$yy);
		$month_end_date= $yy."-".$mm."-".$lastdayofmonth;
		$month_start_date= $yy."-".$mm."-01";
		$sql= " SELECT PD.BILLID, PD.SERVICEID,  
			START_DATE, END_DATE, SHARE,DEFERRABLE
			FROM billing.PURCHASE_DETAIL AS PD 
			WHERE
			(
			(MONTH( PD.START_DATE ) = '$mm' AND YEAR(PD.START_DATE) = '$yy')
			OR (MONTH( PD.END_DATE ) = '$mm' AND YEAR(PD.END_DATE) = '$yy')
			OR (
			PD.START_DATE < '$month_start_date'
			AND PD.END_DATE  >'$month_end_date'
			   )
			)
			AND PD.START_DATE !=0 AND STATUS='DONE' 
			ORDER BY BILLID	";
		$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
		$cnt=0;
		while($myrow=mysql_fetch_array($result))
		{
			$arr[$cnt]['BILLID']=$myrow['BILLID'];
			$arr[$cnt]['START_DATE']=$myrow['START_DATE'];
			$arr[$cnt]['END_DATE']=$myrow['END_DATE'];
			$arr[$cnt]['SERVICEID']=$myrow['SERVICEID'];
			$arr[$cnt]['SHARE']=$myrow['SHARE'];
			$arr[$cnt]['DEFERRABLE']=$myrow['DEFERRABLE'];
			$cnt++;
		}
		if(!$cnt)
		{
			$msg = "No Payments in this month";
			$smarty->assign("cid",$cid);
			$smarty->assign("MSG",$msg);
			$smarty->display("mis_msg1.htm");
			die();
		}

		$total_rs=0;
		$total_dol=0;
		for($j=0;$j<$cnt;$j++)
		{
			
			$billid=$arr[$j]['BILLID'];
			//$sql="SELECT pd.BILLID AS BILLID,pd.PROFILEID AS PROFILEID,pd.MODE AS MODE,pd.TYPE AS TYPE,sum(pd.AMOUNT) as AMOUNT, p.ORDERID  AS ORDERID, pd.ENTRY_DT AS ENTRY_DT,p.USERNAME from billing.PAYMENT_DETAIL pd, billing.PURCHASES p where pd.BILLID='$billid' and p.BILLID=pd.BILLID and pd.STATUS='DONE' group by pd.BILLID"; 
			$sql="SELECT BILLID ,PROFILEID ,MODE ,TYPE ,sum(AMOUNT) as AMOUNT, ENTRY_DT from billing.PAYMENT_DETAIL where BILLID='$billid' and STATUS='DONE' group by BILLID"; 
			$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
			//$i=1;
			while($myrow=mysql_fetch_array($result))
			{
				$from_orders = 0;
				$non_deferrable=0;
				$sql_pur="SELECT ORDERID,USERNAME FROM billing.PURCHASES WHERE BILLID='$billid'";
				$res_pur=mysql_query_decide($sql_pur) or die(mysql_error());
				$row_pur=mysql_fetch_assoc($res_pur);
				$orderid=$row_pur['ORDERID'];
				$username=$row_pur['USERNAME'];
				$startdate = $arr[$j]['START_DATE'];
				$expirydate = $arr[$j]['END_DATE'];
			
				if($arr[$j]['DEFERRABLE']=='N')
				{
					list($sdy,$sdm,$sdd)=@explode("-",$startdate);
					if($mm==$sdm && $yy==$sdy)
						$non_deferrable=1;
					else
						continue;
				
				}
				$servicename=$Service_arr[$arr[$j]['SERVICEID']];
				if(!$non_deferrable)
				{
					$serviceduration = getTimeDiff($arr[$j]['START_DATE'],$arr[$j]['END_DATE']);
					$deferredduration = days_month($arr[$j]['START_DATE'],$arr[$j]['END_DATE'],$mm,$yy);
				}
				//new logic condition applied for payments received after 31st March 2008.
				$payment_date = $myrow['ENTRY_DT'];
				$payment_arr = @explode(" ",$payment_date);
				list($py,$pm,$pd) = @explode("-",$payment_arr[0]);

				$payment_timestamp = mktime(0,0,0,$pm,$pd,$py);
				$newlogic_timestamp = mktime(0,0,0,"03","31","2008");

				if($myrow['MODE'] == "ONLINE" && $myrow['TYPE'] == "DOL" && ($payment_timestamp > $newlogic_timestamp))
				{
		/*			$sql_pur="SELECT ORDERID FROM billing.PURCHASES WHERE BILLID='$billid'";
					$res_pur=mysql_query_decide($sql_pur) or die(mysql_error());
					$row_pur=mysql_fetch_assoc($res_pur);
					$orderid=$row_pur['ORDERID'];*/
					//$sql_order = "SELECT AMOUNT FROM billing.ORDERS WHERE ID='$myrow[ORDERID]' AND GATEWAY IN('CCAVENUE','TRANSECUTE')";
					$sql_order = "SELECT AMOUNT FROM billing.ORDERS WHERE ID='$orderid' AND GATEWAY IN('CCAVENUE','TRANSECUTE')";
					$res_order = mysql_query_decide($sql_order,$db) or die("$sql_order".mysql_error_js());
					if($row_order = mysql_fetch_array($res_order))
					{
						$netamount=($row_order["AMOUNT"]*$arr[$j]['SHARE'])/100;
						if($non_deferrable)
							$deferredamount=$netamount;
						else
							$deferredamount=  round((($deferredduration * $netamount)/ $serviceduration),2);
						$type = "RS";
						$total_rs += $deferredamount;
						$from_orders = 1;
					}
				}
				if(!$from_orders)
				{
					$netamount=($myrow["AMOUNT"]*$arr[$j]['SHARE'])/100;
					if($non_deferrable)
						$deferredamount=$netamount;
					else
						$deferredamount=  round((($deferredduration * $netamount)/ $serviceduration),2);
					if($myrow['TYPE']=="RS")
					{
						$type = "RS";
						$total_rs += $deferredamount;
					}
					elseif($myrow['TYPE']== "DOL")
					{
						$type = "DOL";
						$total_dol += $deferredamount;
					}
				}
				$total_rs=round($total_rs,2);	
				$total_dol=round($total_dol,2);	
				$line=($j+1)." \t".$billid." \t".$servicename." \t".$username." \t".$type." \t".$netamount." \t".$startdate." \t".$expirydate." \t".$serviceduration." \t".$deferredduration." \t".$deferredamount." \t".""." \t";
				$line = str_replace("\t".'$', '', $line);
				$data = trim($line)."\t \n";
				//$data .= trim($line)."\t \n";
				fwrite($handle, $data);
			}
		}
		
		$line="NA"."\t"."NA"." \t"."NA"." \t"."NA"." \t"."NA"." \t"."NA"." \t"."NA"." \t"."NA"." \t"."NA"." \t"."NA"." \t"."NA"." \t".$total_rs." \t".$total_dol;
		$data = trim($line)."\t \n";
		//$data .= trim($line)."\t \n";
		fwrite($handle, $data);
		fclose($handle);
		$msg = "<a href=\"$filename\">";
													 
		$msg .= "Download Deferral sheet for ".$mm."/".$yy."</a>";
		$smarty->assign("cid",$cid);
		$smarty->assign("MSG",$msg);
		$smarty->display("mis_msg1.htm");
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

		return ($end_date - $begin_date) + 1;
	}
        elseif($date2 == $date1)
		return 1;
		//return 0 changed to return 1 because, when date1==date2 the service is consumed for 24 hrs, i.e 1 day.
                //return 0;
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
	if(strlen($month)<2)
		$month="0".$month;
        if($date1>$date2)
                return 0;
        $lastdayofmonth=getlastdayofmonth($month,$year);
        $month_end_date=$year."-".$month."-".$lastdayofmonth;
        $month_start_date=$year."-".$month."-01";
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
                        $returnvalue= getTimeDiff($date1,$month_end_date);
                elseif(($year2.$month2) == ($year.$month)) 
                        $returnvalue=  getTimeDiff($month_start_date,$date2);
                else
                        $returnvalue= 0;
        }
return $returnvalue;
                                                                                                 
}

?>
