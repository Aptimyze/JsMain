<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

require_once("./incomplete_to_lead.php");
$incomplete=new IncompleteProfiles("2011-01-18 00:00:00","2011-01-04 00:00:00");
$pids=$incomplete->getIdsThatAreNotInSugar("2011-01-18 00:00:00","2011-01-04 00:00:00");
$incomplete->createLeadsFromProfileIds($pids);
?>
