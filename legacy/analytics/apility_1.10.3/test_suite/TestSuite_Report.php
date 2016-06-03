<?php
	echo "<h2 style='color:blue;'>Reports</h2>";
	require_once('../apility.php');

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

// getAccountReportJob (XML)
	if (getAccountReportJob('XML', 20, 'XML Report', '2006-07-01', '2006-08-01', 'Summary')) echo "getAccountReportJob (XML) <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getAccountReportJob (XML)<br />\n";

// getAccountReportJob (CSV)
	if (getAccountReportJob('CSV', 20, 'CSV Report', '2006-07-01', '2006-08-01', 'Summary')) echo "getAccountReportJob (CSV) <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getAccountReportJob (CSV)<br />\n";

// getCustomReportJob (XML)
	if (getCustomReportJob('XML', 10, 'Custom Report Job', '2006-01-01', '2006-09-01', 'Daily', false, array(), array('CustomerTimeZone', 'CampaignEndDate'))) echo "getCustomReportJob (XML) <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getCustomReportJob (XML)<br />\n";

// getCustomReportJob (CSV)
	if (getCustomReportJob('CSV', 10, 'Custom Report Job', '2006-01-01', '2006-09-01', 'Daily', false, array(), array('CustomerTimeZone', 'CampaignEndDate'))) echo "getCustomReportJob (CSV) <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getCustomReportJob (CSV)<br />\n";

// getAllJobs
	$allJobs = getAllJobs();
	if (is_array($allJobs)) echo "getAllJobs <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getAllJobs<br />\n";

// downloadXmlReport
  $i = 0;
  while($allJobs[$i]['status'] != "Completed") $i++;
	if (downloadXmlReport($allJobs[$i]['id'])) echo "downloadXmlReport <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in downloadXmlReport<br />\n";

// downloadCsvReport
	if (downloadCsvReport($allJobs[$i]['id'])) echo "downloadCsvReport <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in downloadCsvReport<br />\n";

// tidy up the created temporary Campaign object
	@removeCampaign($campaignObject1);
?>
