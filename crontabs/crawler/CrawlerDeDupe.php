<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

include("CrawlerCommon.php");

$siteId=$_SERVER['argv'][1];
startProcess(3,$siteId);

$deDupeProfiles=CrawlerCompetitionProfile::getProfilesForDeDupe($siteId);

if(is_array($deDupeProfiles))
{
	foreach($deDupeProfiles as $competitionProfileObj)
	{
		unset($values);
		$competitionProfileObj->deDupe();
		$values["de_duped"]='Y';
		$competitionProfileObj->uploadCompetitionProfileDetails($values,1);
	}
}
endProcess(3,$siteId);
?>
