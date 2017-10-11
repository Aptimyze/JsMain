<?php
exit;
/************************************************************************************************************************
* FILENAME           : discount_sms.php
* DESCRIPTION        : SMS will be send to the user which are Available in mailer.DISCOUNT_MAILER_SMS Table
* CREATED BY         : Anurag Gautam
* Date               : 09th September 2008
***********************************************************************************************************************/

$_SERVER['DOCUMENT_ROOT']=JsConstants::$docRoot;
include($_SERVER['DOCUMENT_ROOT']."/profile/connect.inc");
//include($_SERVER['DOCUMENT_ROOT']."/profile/sms_inc.inc");
include_once(JsConstants::$docRoot."/commonFiles/sms_inc.php");

$db = connect_slave();
$db2 = connect_db();

ini_set('max_execution_time','0');
ini_set("memory_limit","-1");

mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db2);

$from="9911328109";
$gsm=1;
$table='DISCOUNT_MAILER_SMS';

$sql="SELECT DISTINCT (PHONE_MOB), PROFILE_ID FROM newjs.JPROFILE AS A, mailer.DISCOUNT_MAILER_SMS AS B WHERE B.SENT_SMS = 'N' AND A.PROFILEID = B.PROFILE_ID";
$res=mysql_query($sql,$db) or die(mysql_error1($db));
$i=0;
while($row=mysql_fetch_array($res))
{	
	$id = $row['PROFILE_ID'];
        $mob = $row['PHONE_MOB'];

	$sql1="SELECT CODE FROM mailer.DISCOUNT_MAILER WHERE PROFILE_ID='$id'";
	$res1=mysql_query($sql1,$db) or die(mysql_error1($db));
	while($row3=mysql_fetch_array($res1))
	{	
		$code = $row3['CODE'];
	}
        
	if($mob)
	{
		$message="Jeevansathi.com offers you a grand 40% discount on premium memberships. Use the code $code to avail it.'Jab rishta mil jaye kuch mitha ho jaye'";
		send_sms($message,$from,$mob,$id,$gsm,'','Y');
         	$sql="UPDATE mailer.DISCOUNT_MAILER_SMS SET SENT_SMS='Y'WHERE PROFILE_ID='$id'" ;
	        mysql_query($sql,$db2) or die(mysql_error1($db2));
		$i++;
        }
}

  mail("Anurag.Gautam@jeevansathi.com","Script discount_sms.php ran successfully", date("Y-m-d"));

  function mysql_error1($db)
  {
	mail("Anurag.Gautam@jeevansathi.com","Error in discount_sms.php ",mysql_error($db));
  }

?>
