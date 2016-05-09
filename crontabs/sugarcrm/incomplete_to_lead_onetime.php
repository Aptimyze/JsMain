<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

require_once("incomplete_to_lead.php");
$startDate="2010-03-10 00:00:00";
$currentDate=date('Y-m-d')." 00:00:00";
$incomplete=new IncompleteProfiles();
//This will add username of the JPROFILE to the duplicate lead and fetch pids of PROFILES that will be created lead for.
$pidsToAdd=$incomplete->getIdsThatAreNotInSugar($currentDate,$startDate);
$incomplete->createLeadsFromProfileIds($pidsToAdd);
?>

