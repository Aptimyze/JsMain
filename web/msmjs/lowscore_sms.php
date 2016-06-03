<?php
chdir(dirname(__FILE__));

ini_set('max_execution_time','0');

$today=date("Y-m-d");
list($year,$month,$day)=explode("-",$today);
$ts=time();
$ts_3=$ts-2*24*60*60;
$ts_15=$ts-14*24*60*60;
$ts_30=$ts-29*24*60*60;

$dt_3=date("Y-m-d",$ts_3);
$dt_15=date("Y-m-d",$ts_15);
$dt_30=date("Y-m-d",$ts_30);
$date_arr=Array($dt_3,$dt_15,$dt_30);

include_once("connect.inc");

mysql_query("set session wait_timeout=1000",$db);

$db_slave=connect_slave();
mysql_query("set session wait_timeout=1000",$db_slave);

include "lib/SendMessage.class.php";
$sendMessageObj = new SendMessage;
//$message="Get the best out of your matrimony search on Jeevansathi.com. Call us on 18004196299 (TollFree) and let our expert counselors help you in finding better matches";
$message="Upgrade your Jeevansathi membership to paid membership and contact lacs of profiles. Call 0120-4303200";
$from='';

$i=0;
$j=500;
foreach($date_arr as $k=>$v)
{
	$sql="SELECT J.PROFILEID,J.GET_SMS,J.PHONE_MOB FROM incentive.MAIN_ADMIN_POOL AS A, newjs.JPROFILE AS J WHERE J.PROFILEID = A.PROFILEID AND J.ENTRY_DT >= '$v 00:00:00' AND J.ENTRY_DT <= '$v 23:59:59' AND J.ACTIVATED <> 'D' AND A.SCORE<350";
	$res=mysql_query($sql,$db_slave) or die(mysql_error());
	while($row=mysql_fetch_array($res))
	{
		if($i==$j)
		{
			$j+=500;
			$sendMessageObj->sendSMS($xmldata,'0');
			$xmldata='';
			$sql2="INSERT INTO mmmjs.LOWSCORE_SMS(PROFILEID,DATE) VALUES $values";
			mysql_query($sql2,$db) or die(mysql_error($db));
			$values='';
		}
		$pid=$row['PROFILEID'];
		if($row['PHONE_MOB']!='' && $row1['GET_SMS']!='N')
		{
			$i++;
			if($values!='')
				$values.=", (".$pid.",'$today')";
			else
				$values="(".$pid.",'$today')";

			$to='91'.$row['PHONE_MOB'];
			$from=$sendMessageObj->getFromMobile($to);
			$xmldata.=$sendMessageObj->generateReceiverXmlData($pid, $message, $from, $to);
		}
	}
	if($xmldata!='')
        {
                $sendMessageObj->sendSMS($xmldata,'0');
                $xmldata='';
		$sql2="INSERT INTO mmmjs.LOWSCORE_SMS(PROFILEID,DATE) VALUES $values";
                mysql_query($sql2,$db) or die(mysql_error($db));
                $values='';
        }
}

?>
