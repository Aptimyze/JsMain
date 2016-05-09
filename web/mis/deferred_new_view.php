<?php

include("connect.inc");

$db=connect_misdb();
$db2=connect_master();

$duration =array(	"S1" => 3,
			"S2" => 6,
			"S3" =>	12,
			"S4" => 3,
			"S5" => 6,
			"S6" => 12
		  );
if(authenticated($cid))
{
	$header = "Sno"."\t"."username"."\t"."type"."\t"."netamount"."\t"."startdate"."\t"."expirydate"."\t"."serviceduration"."\t"."deferredduration"."\t"."deferredamount"."\t"."Total (RS)"."\t"."Total (DOL)";
	
	$sql="SELECT DISTINCT SERVICE_STATUS.BILLID,SERVICE_STATUS.SERVICEID,USERNAME,ACTIVATED_ON,EXPIRY_DT from billing.SERVICE_STATUS,billing.PURCHASES where PURCHASES.BILLID=SERVICE_STATUS.BILLID and SERVICE_STATUS.ACTIVATED_ON <= '2005-03-31' and SERVICE_STATUS.ACTIVATED_ON != '0' order by BILLID";
	$result=mysql_query_decide($sql,$db) or die("$sql".mysql_error_js());
	while($myrow=mysql_fetch_array($result))
	{
		$billid[]=$myrow['BILLID'];
		$arr[$myrow['BILLID']]['ACTIVATED_ON']=$myrow['ACTIVATED_ON'];
		$arr[$myrow['BILLID']]['EXPIRY_DT']=$myrow['EXPIRY_DT'];
		$arr[$myrow['BILLID']]['USERNAME']=$myrow['USERNAME'];
		$arr[$myrow['BILLID']]['SERVICEID']=$myrow['SERVICEID'];
	}
	$billid_str= implode("','",$billid);
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
		$deferredduration = getTimeDiff('2005-03-31',$arr[$myrow['BILLID']]['EXPIRY_DT']);
		$netamount=$myrow["AMOUNT"];
		$deferredamount=  round((($deferredduration * $netamount)/ $serviceduration),2);
		if($myrow['TYPE']=="RS")
			$total_rs += $deferredamount;
		elseif($myrow['TYPE']== "DOL")
			$total_dol += $deferredamount;
		
		$line=$i." \t".$arr[$myrow['BILLID']]['USERNAME']." \t".$myrow["TYPE"]." \t".$netamount." \t".$startdate." \t".$expirydate." \t".$serviceduration." \t".$deferredduration." \t".$deferredamount." \t".""." \t";
		$line = str_replace("\t".'$', '', $line);
		$data .= trim($line)."\t \n";
		$i++;
	}
$line="NA"."\t"."NA"." \t"."NA"." \t"."NA"." \t"."NA"." \t"."NA"." \t"."NA"." \t"."NA"." \t"."NA"." \t".$total_rs." \t".$total_dol;
$data .= trim($line)."\t \n";

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition:attachment; filename=deferral.xls");
header("Pragma: no-cache");
header("Expires: 0");
echo $output = $header."\n".$data;

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

?>
