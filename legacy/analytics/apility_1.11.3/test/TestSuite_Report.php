<?php
	echo "<h2 style='color:blue;'>Reports</h2>";
	require_once('apility.php');

// add a temporary Campaign
	$campaignObject1 = addCampaign("ReportTest_".rand(0, 32768), "Active", "", "2010-01-01", 100, array("GoogleSearch"), array("fr", "de"), array('countryTargets' => array('countries' => array()), 'regionTargets' => array('regions' => array()), 'metroTargets' => array('metros' => array()), 'cityTargets' => array('cities' => array("Reutlingen, BW DE", "Karlsruhe, BW DE")), 'proximityTargets' => array('circles' => array())), true);
// add a temporary AdGroup
	$belongsToCampaignId = $campaignObject1->getId();
	$adGroupObject1 = addAdGroup("Test_".rand(0, 32768), $belongsToCampaignId, "Active", 1, 0);
// add a temporary TextAd
	$belongsToAdGroupId = $adGroupObject1->getId();
	$adObject1 = addTextAd($belongsToAdGroupId, "Headline ".rand(0, 32768), "Description1 ".rand(0, 32768), "Description2 ".rand(0, 32768), 'Enabled', "http://groups-beta.com", "http://groups-beta.com/group");
// add a temporary Keyword
	$keywordObject1 = addKeywordCriterion("Test_".rand(0, 32768), $belongsToAdGroupId, "Broad", false, 1, "", "");

// getUrlXmlReport (XML)
	if (getUrlXmlReport(
    'report name',
    '2006-09-01',
    '2007-09-01',
    array('DestinationURL'),
    array('Weekly'),
    $campaigns = array(),
    $campaignStatuses = array(),
    $adGroups = array(),
    $adGroupStatuses = array(),
    $keywords = array(),
    $keywordStatuses = array(),
    $adWordsType = 'SearchOnly',
    $keywordType = 'Broad',
    $isCrossClient = false,
    $clientEmails = array(),
    $includeZeroImpression = false,
    20,
    false)
  ) {
    echo "getUrlXmlReport (XML) <font color='darkgreen'>OK</font><br />\n";
  }
  else {
    echo "<font color='red'>error</font> in getUrlXmlReport (XML)<br />\n";
  }

// getUrlTsvReport (TSV)
	if (getUrlTsvReport(
    'report name',
    '2006-09-01',
    '2007-09-01',
    array('DestinationURL'),
    array('Weekly'),
    $campaigns = array(),
    $campaignStatuses = array(),
    $adGroups = array(),
    $adGroupStatuses = array(),
    $keywords = array(),
    $keywordStatuses = array(),
    $adWordsType = 'SearchOnly',
    $keywordType = 'Broad',
    $isCrossClient = false,
    $clientEmails = array(),
    $includeZeroImpression = false,
    20,
    false)
  ) {
    echo "getUrlTsvReport (XML) <font color='darkgreen'>OK</font><br />\n";
  }
  else {
    echo "<font color='red'>error</font> in getUrlTsvReport (XML)<br />\n";
  }


// getAllJobs
	$allJobs = getAllJobs();
	if (is_array($allJobs)) echo "getAllJobs <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getAllJobs<br />\n";

// downloadXmlReport
  $i = 0;
  while($allJobs[$i]['status'] != "Completed") $i++;
	if (downloadXmlReport($allJobs[$i]['id'])) echo "downloadXmlReport <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in downloadXmlReport<br />\n";

// downloadTsvReport
	if (downloadTsvReport($allJobs[$i]['id'])) echo "downloadTsvReport <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in downloadTsvReport<br />\n";

// tidy up the created temporary Campaign object
	@removeCampaign($campaignObject1);
?>