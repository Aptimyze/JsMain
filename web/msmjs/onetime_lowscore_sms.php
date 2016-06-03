<?php
ini_set('max_execution_time','0');

include_once("connect.inc");

mysql_query("set session wait_timeout=1000",$db);

include "lib/SendMessage.class.php";
$sendMessageObj = new SendMessage;

$message="Get the best out of your matrimony search on Jeevansathi.com. Call us on 18004196299 (TollFree) and let our expert counselors help you in finding better matches";
$from='';
$table=Array('SEARCH_MALE','SEARCH_FEMALE');
$date=date('Y-m-d');

$db_slave=connect_slave();
mysql_query("set session wait_timeout=1000",$db_slave);

foreach($table as $k=>$v)
{
	$i=0;
	$j=500;
	$sql="SELECT PROFILEID FROM newjs.$v WHERE PROFILE_SCORE<350";
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
		$sql1="SELECT PROFILEID, PHONE_MOB,ACTIVATED,GET_SMS FROM newjs.JPROFILE WHERE PROFILEID=$pid";
		$res1=mysql_query($sql1,$db_slave) or die(mysql_error());
		$row1=mysql_fetch_assoc($res1);
		if($row1['PHONE_MOB']!='' && $row1['ACTIVATED']!='D' && $row1['GET_SMS']!='N')
		{
			$i++;
			if($values!='')
				$values.=", (".$pid.",'$date')";
			else
				$values="(".$pid.",'$date')";

			$to='91'.$row1['PHONE_MOB'];
			$from=$sendMessageObj->getFromMobile($to);
			$xmldata.=$sendMessageObj->generateReceiverXmlData($pid, $message, $from, $to);	
		}
	}
	if($xmldata!='')
	{
		$sendMessageObj->sendSMS($xmldata,'0');
		$xmldata='';
		$sql2="INSERT INTO mmmjs.LOWSCORE_SMS(PROFILEID,DATE) VALUES $values";
		$res2=mysql_query($sql2,$db) or die(mysql_error($db));
		$values='';
	}
}
?>
