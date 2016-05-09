<?php

include("../jsadmin/connect.inc");
include("../jsadmin/time.php");
include(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include("bounced_mail.php");

$sql = "SELECT RECEIPTID  , PROFILEID , BILLID , STATUS , BOUNCE_DT , ENTRYBY , ENTRY_DT FROM billing.PAYMENT_DETAIL WHERE STATUS IN  ('BOUNCE','CHARGE_BACK')";
$res = mysql_query_decide($sql) or die("$sql".mysql_error_js());

while($row=mysql_fetch_array($res))
{
	$profileid = $row['PROFILEID'];

	//$sql1 = "SELECT STATUS,ENTRY_DT FROM billing.PAYMENT_DETAIL WHERE PROFILEID='$profileid' ORDER BY ENTRY_DT DESC LIMIT 1";
        //$res1=mysql_query_decide($sql1) or die("$sql1".mysql_error_js());
        //if($row1=mysql_fetch_array($res1))
        //{
          //      if($row1['STATUS']=='BOUNCE' || $row1['STATUS']=='CHARGE_BACK')
            //    {
			$date=date("Y-m-d");
			list($yy,$mm,$dd)=explode("-",$date);
			$today = mktime(0,0,0,$mm,$dd,$yy);

			list($b_yy,$b_mm,$b_dd) = explode("-",$row['BOUNCE_DT']);
			$bounce_dt = mktime(0,0,0,$b_mm,$b_dd,$b_yy);
			$days = ($today-$bounce_dt);

			$diff = (int) ($days/(24*60*60));

			if ($diff >=5)
				$display = 'N';
			else
				$display = 'Y';

			if($row['BOUNCE_DT'] != 0)
			{
				$sql_ins="INSERT INTO billing.BOUNCED_CHEQUE_HISTORY ( ID , RECEIPTID , PROFILEID , BILLID ,ACTION , STATUS , BOUNCE_DT , REMINDER_DT , ENTRYBY , ENTRY_DT , DISPLAY ) VALUES ('', '$row[RECEIPTID]', '$row[PROFILEID]', '$row[BILLID]', '' , '$row[STATUS]', '$row[BOUNCE_DT]', DATE_ADD('$row[BOUNCE_DT]' , INTERVAL  2  DAY), '$row[ENTRYBY]', NOW(), '$display')";
				mysql_query_decide($sql_ins) or die("$sql_ins".mysql_error_js());
			}
//                }
  //      }
	
}
?>
