<?php 
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");
include($_SERVER['DOCUMENT_ROOT']."/jsadmin/connect.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/pg/functions.php");

$db=connect_db();

// dol conv rate changed to 43.5 - overwitten current 45.8 - so that mis does not change
//$DOL_CONV_RATE=43.5;

//$today="2006-12-31";
$ts=time();
$ts-=5*24*60*60;
$today=date("Y-m-d",$ts);

list($myear,$mmonth,$d)=explode("-",$today);
/*if ($mmonth == 1)
{
	$mmonth = 12;
	$myear = $myear - 1;
}
else
	$mmonth = $mmonth - 1;
*/
 $st_date=$myear."-".$mmonth."-01 00:00:00";
 $end_date=$myear."-".$mmonth."-31 23:59:59";

$sql="SELECT STATUS,PROFILEID,if(TYPE='DOL',AMOUNT*DOL_CONV_RATE,AMOUNT) AS AMOUNT,ENTRY_DT,DAYOFMONTH(ENTRY_DT) as dd FROM billing.PAYMENT_DETAIL WHERE ENTRY_DT BETWEEN '$st_date' AND '$end_date' AND STATUS IN ('DONE','REFUND')";
$res=mysql_query($sql,$db) or die("$sql".mysql_error($db));
if($row=mysql_fetch_array($res))
{
	do
	{
		$valid_id = 0;
		$profileid=$row['PROFILEID'];
		$valid_id = check_validity($profileid,$st_date,$end_date);
		if($valid_id)
		{
			$amount=$row['AMOUNT'];
			$status=$row['STATUS'];
			$paid_dt=$row['ENTRY_DT'];
		
			$sql="SELECT ID,ALLOTED_TO FROM incentive.CRM_DAILY_ALLOT WHERE PROFILEID='$profileid' AND ALLOT_TIME<='$paid_dt' AND DATE_ADD(ALLOT_TIME, INTERVAL (30+RELAX_DAYS) DAY)>='$paid_dt' ORDER BY ID DESC";

			$res1=mysql_query($sql,$db) or die("$sql".mysql_error($db));
			if($row1=mysql_fetch_array($res1))
			{
				$alloted_to=$row1['ALLOTED_TO'];

				if(is_array($operatorarr))
				{
					if(!in_array($alloted_to,$operatorarr))
					{
						$operatorarr[]=$alloted_to;
					}
				}
				else
				{
					$operatorarr[]=$alloted_to;
				}
				$j=array_search($alloted_to,$operatorarr);
				if($status=='DONE')
					$amttot[$j]+=$amount;
				else
					$amttot[$j]-=$amount;
			}
		}
	}while($row=mysql_fetch_array($res));
}
$db2=connect_db();
for ($i = 0;$i < count($amttot);$i++)
{
	$dd = getlastdayofmonth($mmonth,$myear);
	$date = $myear."-".$mmonth."-".$dd;
	$sql = "INSERT INTO MIS.CRM_MONTHLY_REVENUE (USER,AMOUNT,ENTRY_DT) VALUES ('$operatorarr[$i]','$amttot[$i]','$date')";
	$res=mysql_query($sql,$db2) or die("$sql".mysql_error($db2));
}
unset($operatorarr);
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
function check_validity($pid,$st_date,$end_date)
{
	return 1;
}
?>
