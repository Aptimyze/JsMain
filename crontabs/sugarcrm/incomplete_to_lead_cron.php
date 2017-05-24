<?php 
$fromCron=1;
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");
ini_set('max_execution_time','0');
ini_set('memory_limit',-1);

require_once("incomplete_to_lead.php");
$prevDate=date('Y-m-d',JSstrToTime("-1 day"));
$endDate=$prevDate." 23:59:59";
$startDate=$prevDate." 00:00:00";
$incomplete=new IncompleteProfiles();
//This will add username of the JPROFILE to the duplicate lead and fetch pids of PROFILES that will be created lead for.
$pidsToAdd=$incomplete->getIdsThatAreNotInSugar($endDate,$startDate);
$incomplete->createLeadsFromProfileIds($pidsToAdd);
send_email("nikhil.dhiman@jeevansathi.com,nitesh.s@jeevansathi.com","sugarcrm incoplete to lead cron","Successfull incomplete to lead cron");
?>

