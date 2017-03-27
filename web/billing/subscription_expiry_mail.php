<?php

chdir(dirname(__FILE__));
//ini_set("memory_limit","24M");
if(!$_SERVER['DOCUMENT_ROOT'])
        $_SERVER['DOCUMENT_ROOT'] = JsConstants::$docRoot;

ini_set("max_execution_time",0);
include(JsConstants::$docRoot."/jsadmin/connect.inc");
require_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");

$ts=time();
$dir=date("mY",$ts);
$d=date("d",$ts);
$curdate = date("Y-m-d",$ts);
$ts+=10*24*60*60;
$curdate10=date("Y-m-d",$ts);

$db_slave = connect_slave();
$db = connect_db();

$sql="SELECT distinct(PROFILEID) as PROFILEID FROM billing.SERVICE_STATUS WHERE EXPIRY_DT='$curdate' AND ACTIVE='Y' AND SERVEFOR like '%F%'";
$result_id=mysql_query_decide($sql,$db_slave) or logError("Error1:",$sql);
while($row_id=mysql_fetch_array($result_id)){
	$pid=$row_id['PROFILEID'];
	$profileids_arr[]=$pid;
}

$sql="SELECT distinct(PROFILEID) as PROFILEID FROM billing.SERVICE_STATUS WHERE EXPIRY_DT='$curdate10' AND ACTIVE='Y' AND SERVEFOR like '%F%'";
$result_id=mysql_query_decide($sql,$db_slave)  or logError("Error2:",$sql);
while($row_id=mysql_fetch_array($result_id)){
	$pid=$row_id['PROFILEID'];
	$profileids10_arr[]=$pid;
}

if($profileids_arr)
{
	//$memExpiryMailer = new EmailSender(MailerGroup::MEMB_EXPIRY, 1792);
	/** code for daily count monitoring**/
        $cronDocRoot = JsConstants::$cronDocRoot;
        $php5 = JsConstants::$php5path;
        passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring SUBSCRIPTION_EXPIRY_MAILER#INSERT");
        /**code ends*/
	$countObj = new jeevansathi_mailer_DAILY_MAILER_COUNT_LOG();
        $instanceID = $countObj->getID('SUBSCRIPTION_EXPIRY_MAILER');

	$memExpiryMailer = new EmailSender(MailerGroup::MEMBERSHIP_MAILER, 1792);
	for($i=0;$i<count($profileids_arr);$i++){
		$profile=$profileids_arr[$i];
		$sql1="SELECT PROFILEID FROM billing.SERVICE_STATUS WHERE EXPIRY_DT >'$curdate' AND ACTIVE='Y' AND SERVEFOR like '%F%' AND PROFILEID='$profile'";
		$res1=mysql_query_decide($sql1,$db_slave) or  die("$sql1".mysql_error_js());
		if(!mysql_num_rows($res1)){

			list($year,$month,$day) = explode("-",$curdate);
			$expiryDate =my_format_date($day,$month,$year);
	                $emailTpl =$memExpiryMailer->setProfileId($profile);
	                $smartyObj = $emailTpl->getSmarty();
	                $smartyObj->assign("expiryDate",$expiryDate);
			$smartyObj->assign("instanceID",$instanceID);
	                $memExpiryMailer->send();
			$deliveryStatus =$memExpiryMailer->getEmailDeliveryStatus();
			addEmailLog($profile, $deliveryStatus, $curdate);
		}
	}
	/** code for daily count monitoring**/
        passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring SUBSCRIPTION_EXPIRY_MAILER");
        /**code ends*/
	unset($profileids_arr);
	unset($memExpiryMailer);
}

if($profileids10_arr)
{
	//$memExpiryMailer = new EmailSender(MailerGroup::MEMB_EXPIRY, 1793);
	/** code for daily count monitoring**/
	$cronDocRoot = JsConstants::$cronDocRoot;
	$php5 = JsConstants::$php5path;
	passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring SUBSCRIPTION_EXPIRY_MAILER_10DAYS#INSERT");
	/**code ends*/
	$countObj = new jeevansathi_mailer_DAILY_MAILER_COUNT_LOG();
	$instanceID = $countObj->getID('SUBSCRIPTION_EXPIRY_MAILER_10DAYS');

	$memExpiryMailer = new EmailSender(MailerGroup::MEMBERSHIP_MAILER, 1793);
	for($i=0;$i<count($profileids10_arr);$i++){
                $profile=$profileids10_arr[$i];
		$sql1="SELECT PROFILEID FROM billing.SERVICE_STATUS WHERE EXPIRY_DT >'$curdate10' AND ACTIVE='Y' AND SERVEFOR  like '%F%' AND PROFILEID='$profile'";
                $res1=mysql_query_decide($sql1,$db_slave) or  die("$sql1".mysql_error_js());
                if(!mysql_num_rows($res1)){

			list($year,$month,$day) = explode("-",$curdate10);
			$expiryDate =my_format_date($day,$month,$year);
			$emailTpl =$memExpiryMailer->setProfileId($profile);
			$smartyObj = $emailTpl->getSmarty();
			$smartyObj->assign("expiryDate",$expiryDate);
			$smartyObj->assign("instanceID",$instanceID);
			$memExpiryMailer->send();
			$deliveryStatus =$memExpiryMailer->getEmailDeliveryStatus();
			addEmailLog10days($profile, $deliveryStatus, $curdate);
		}
	}
	/** code for daily count monitoring**/
        passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring SUBSCRIPTION_EXPIRY_MAILER_10DAYS");
        /**code ends*/
	unset($profileids10_arr);
}

function addEmailLog($profile, $deliveryStatus='', $curdate)
{
	$sqlMailAdd ="insert ignore into billing.SUBSCRIPTION_EXPIRY_MAILER_LOG(`PROFILEID`,`SENT`,`ENTRY_DT`) VALUES('$profile','$deliveryStatus','$curdate')";
	mysql_query_decide($sqlMailAdd,$db) or  die("$sqlMailAdd".mysql_error_js());
}

function addEmailLog10days($profile, $deliveryStatus='', $curdate)
{
        $sqlMailAdd ="insert ignore into billing.SUBSCRIPTION_EXPIRY_MAILER_LOG_10DAY(`PROFILEID`,`SENT`,`ENTRY_DT`) VALUES('$profile','$deliveryStatus','$curdate')";
        mysql_query_decide($sqlMailAdd,$db) or  die("$sqlMailAdd".mysql_error_js());
}

mail("manoj.rana@naukri.com,vibhor.garg@jeevansathi.com","Subscription(expiry) ended up mails sent","Success");

?>
