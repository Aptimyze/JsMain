<?php
chdir(dirname(__FILE__));
ini_set('max_execution_time','0');

include_once("connect.inc");
$date=date("Y-m-d");
mysql_query("set session wait_timeout=1000",$db);

include "lib/SendMessage.class.php";
$sendMessageObj = new SendMessage;

$message="Get the best out of your matrimony search on Jeevansathi.com by becoming a paid member. Call us on 0120-4303200 to know how";
$from='';

$pid_str=$_POST['Profiles'];

if($pid_str!='')
{
	$db_slave=connect_slave();
	mysql_query("set session wait_timeout=1000",$db_slave);
	$sql="SELECT PROFILEID FROM mmmjs.LOWSCORE_SMS WHERE DATE='$date'";
	$res=mysql_query($sql,$db_slave) or die(mysql_error());
	if(mysql_num_rows($res))
	{	
		$pid_arr=explode(',',$pid_str);
		$exclude=array();
		while($row=mysql_fetch_array($res))
		{
			$exclude[]=$row['PROFILEID'];
		}
		$new_pid_arr=array_diff($pid_arr,$exclude);
		$pid_str=implode(',',$new_pid_arr);
		if($pid_str=='')
			exit;
	}
	$sql1="SELECT PROFILEID, PHONE_MOB,ACTIVATED,GET_SMS FROM newjs.JPROFILE WHERE PROFILEID IN ($pid_str)";
	$res1=mysql_query($sql1,$db_slave) or die(mysql_error());
	while($row1=mysql_fetch_array($res1))
	{
		$pid=$row1['PROFILEID'];
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
		$sql2="INSERT INTO mmmjs.PropensityToPay_SMS(PROFILEID,DATE) VALUES $values";
		$res2=mysql_query($sql2,$db) or die(mysql_error($db));
		$values='';
	}
}
?>
