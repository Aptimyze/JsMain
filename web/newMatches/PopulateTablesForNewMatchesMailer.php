<?php
//This cron is only used for the purpose of populating tables for new matches mailers. If a error occurs in any of the query mentioned in this cron then the cron dies and a mail is sent. Then it has to be re-run. 

$matchalertServer = 1;
$new_matches_email_table_population_cron = 1;

include_once(JsConstants::$alertDocRoot."/classes/Mysql.class.php");
include_once(JsConstants::$alertDocRoot."/newMatches/TrackingFunctions.class.php");
include_once(JsConstants::$alertDocRoot."/commonFiles/SymfonyPictureFunctions.class.php");

$mysqlObj = new Mysql;
$localdb=$mysqlObj->connect("alerts");
if(!$localdb)
	errorMail("Connection Error");
if(!$php5)
	$php5=JsConstants::$php5path; //live php5
/** code for daily count monitoring**/
		$cronDocRoot = JsConstants::$cronDocRoot;
		 passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring NMA_MAILER");
	 
/**code ends*/
/** code for inserting daily count**/
		 passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring NMA_MAILER#INSERT");
	 
/**code ends*/                 


$sql = "TRUNCATE TABLE new_matches_emails.RECEIVER";
$output = $mysqlObj->executeQuery($sql,$localdb);
if(!$output)
	errorMail($sql);

$dt = date("Y-m-d H:i:s");

$sql = "INSERT INTO  new_matches_emails.RECEIVER(PROFILEID,SENT) SELECT J.PROFILEID AS PROFILEID,'N' FROM newjs.JPROFILE J LEFT JOIN newjs.JPROFILE_ALERTS JA ON J.PROFILEID = JA.PROFILEID WHERE (J.ACTIVATED='Y' OR ( J.ACTIVATED = 'N' AND J.INCOMPLETE = 'Y' ) ) AND J.SORT_DT >= DATE_SUB('".$dt."' , INTERVAL 6 MONTH ) AND (JA.NEW_MATCHES_MAILS IS NULL OR JA.NEW_MATCHES_MAILS='' OR JA.NEW_MATCHES_MAILS='S')";
$output = $mysqlObj->executeQuery($sql,$localdb);
if(!$output)
	errorMail($sql);
$count= $mysqlObj->affectedRows();

$deleteDate = MailerConfigVariables::getLogicalDate();
$deleteDate = $deleteDate-31;

$sql = "INSERT INTO new_matches_emails.TOP_VIEW_COUNT_BKUP SELECT * FROM new_matches_emails.TOP_VIEW_COUNT";
$output = $mysqlObj->executeQuery($sql,$localdb);
if(!$output)
        errorMail($sql);

$sql = "TRUNCATE TABLE new_matches_emails.TOP_VIEW_COUNT";
$output = $mysqlObj->executeQuery($sql,$localdb);
if(!$output)
        errorMail($sql);

$sql = "DELETE FROM new_matches_emails.TOP_VIEW_COUNT_BKUP WHERE DATE < ".$deleteDate;
$output = $mysqlObj->executeQuery($sql,$localdb);
if(!$output)
        errorMail($sql);

$deleteDate = MailerConfigVariables::getNoOfDays();
$deleteDate = $deleteDate-31;

$sql = "INSERT INTO new_matches_emails.LOG SELECT * FROM new_matches_emails.LOG_TEMP";
$output = $mysqlObj->executeQuery($sql,$localdb);
if(!$output)
        errorMail($sql);

$sql = "TRUNCATE TABLE new_matches_emails.LOG_TEMP";
$output = $mysqlObj->executeQuery($sql,$localdb);
if(!$output)
        errorMail($sql);

$sql = "DELETE FROM new_matches_emails.LOG WHERE DATE < ".$deleteDate;
$output = $mysqlObj->executeQuery($sql,$localdb);
if(!$output)
        errorMail($sql);

$sql = "TRUNCATE TABLE new_matches_emails.MAILER";
$output = $mysqlObj->executeQuery($sql,$localdb);
if(!$output)
        errorMail($sql);

$trackObj = new TrackingFunctions("",$mysqlObj);
$output = $trackObj->trackingMis(array("PROFILES_CONSIDERED"=>$count));
if(!$output)
        errorMail($sql);
unset($trackObj);

function errorMail($err)
{
	mail("lavesh.rawat@jeevansathi.com","Error in table population for new matches emails",$err);
	die;
}
?>
