<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

/************
File name: delete_sms_mod.php
Description: deletes entries older than 30 days 1000 records at a time from tables: newjs.SMS_DETAIL, MIS.MOB_VERIFY
Author: @ESHA
************/
include("connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
$db=connect_db();
$slave=connect_slave();
$to='palash.chordia@jeevansathi.com, nitesh.s@jeevansathi.com';
$subject="SMS_DETAIL and MOB_VERIFY cleanup report";

$SMS_DAYS=15;
$MOB_DAYS=30;
$LIMIT=1000;

$days_sms1=mktime(0,0,0,date("m"),date("d")-$SMS_DAYS,date("Y"));
$back_sms_days=date("Y-m-d",$days_sms1);

$days_mob1=mktime(0,0,0,date("m"),date("d")-$MOB_DAYS,date("Y"));
$back_mob_days=date("Y-m-d",$days_mob1);






//storing yesterday's data in SMS_TRIGGER_///////////////////////////////////////////////// 
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$slave);

$dateYesterday=date("Y-m-d", time() - 60 * 60 * 24);
$SQL2="SELECT SENT, COUNT( * ) AS CNT, SMS_KEY FROM  `SMS_DETAIL` WHERE DATE(  `ADD_DATE` ) =  '$dateYesterday' GROUP BY  `SENT` ,  `SMS_KEY`";
$RES=mysql_query($SQL2,$slave) or errorEmail($SQL2 ,$to);

while($ROWA=mysql_fetch_assoc($RES)){

$RESA[]=$ROWA;

}
foreach ($RESA as $key => $value) {

	$resArray[$value['SMS_KEY']]['FIRED']+=$value['CNT'];
if($value['SENT']=='Y')$resArray[$value['SMS_KEY']]['SENT']=$value['CNT'];

}
$writeStr="";

foreach ($resArray as $key => $value) {
$writeStr.=("('".$key."','".$dateYesterday."',".$value['FIRED'].",".$value['SENT']."),");
}
$len=strlen($writeStr);
$writeStr=substr($writeStr,0,$len-1);

$SQL3="INSERT INTO  MIS.SMS_TRIGGER_LOG (  `SMS_KEY` ,  `DATE` ,  `TOTAL_FIRED` ,  `TOTAL_SENT` ) 
VALUES ".$writeStr.";";
mysql_query($SQL3,$db) or errorEmail($SQL3 ,$to);

///////////////////////////////



$sqla="SELECT count(*) FROM newjs.SMS_DETAIL WHERE ADD_DATE < '".$back_sms_days."'";
$resa=mysql_query($sqla,$slave) or logError($sqla,$slave);
$rowa=mysql_fetch_array($resa);

$total1= $rowa["count(*)"];
$loop_count= ceil($total1/$LIMIT);

for($i=0;$i<$loop_count;$i++)
{
	$sql1="DELETE from newjs.SMS_DETAIL WHERE ADD_DATE <  '".$back_sms_days."' LIMIT ".$LIMIT;
	$result1=mysql_query($sql1,$db) or errorEmail($sql1,$to);
}
$todayDate=mktime(0,0,0,date("m"),date("d"),date("Y"));
$today=date("Y-m-d",$todayDate);

$msg ='No of entry deleted from SMS_DETAIL is: '.$total1.'<br>';
$msg.='on '.$today.'. <br><br>';
$msg.='Regards,<br>JS';
send_email($to,$msg,$subject);

function errorEmail($sql,$to)
{
$subject="SMS_DETAIL cleanup error";
$msg='Error while executing query :'.$sql.'.<br> '.mysql_errno() . ": " . mysql_error().'<br><br>Regards,<br> JS'; 
send_email($to,$msg,$subject);
die;

}

?>
