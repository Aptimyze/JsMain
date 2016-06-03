<?php
	echo "<h2 style='color:blue;'>KeywordCriteria</h2>";
	require_once('apility.php');

// add a temporary Campaign
	$campaignObject1 = addCampaign("CriterionTest_".rand(0, 32768), "Active", "", "2010-01-01", 50, array("GoogleSearch"), array("fr", "de"), array('countryTargets' => array('countries' => array()), 'regionTargets' => array('regions' => array()), 'metroTargets' => array('metros' => array()), 'cityTargets' => array('cities' => array("Reutlingen, BW DE", "Karlsruhe, BW DE")), 'proximityTargets' => array('circles' => array())), true);
// add a temporary AdGroup
	$belongsToCampaignId = $campaignObject1->getId();
	$adGroupObject1 = addAdGroup("Test_".rand(0, 32768), $belongsToCampaignId, "Active", 1, 0);

// prepare all data
	$text = "Test".rand(0, 32768);
	$belongsToAdGroupId = $adGroupObject1->getId();
	$type = "Broad";
	$isNegative = false;
	$maxCpc = 1;
	$language = "de";
	$destinationUrl = "http://groups-beta.google.com/group/adwords-api-php";

	echo "<h2>Server Add Functions</h2>\n";
// addKeywordCriterion
	$criterionObject1 = addKeywordCriterion($text, $belongsToAdGroupId, $type, $isNegative, $maxCpc, $language, $destinationUrl);
	if (is_object($criterionObject1)) echo "addKeywordCriterion <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in addKeywordCriterion<br />\n";

// addKeywordCriterionList
	$criterion2 = array('text' => "Test".rand(0, 32768), 'belongsToAdGroupId' => $belongsToAdGroupId, 'type' => "Broad", 'isNegative' => false, 'maxCpc' => 1.0, 'language' => "de", 'destinationUrl' => "http://groups-beta.google.com/group/adwords-api-php");
	$criterion3 = array('text' => "Test".rand(0, 32768), 'belongsToAdGroupId' => $belongsToAdGroupId, 'type' => "Broad", 'isNegative' => false, 'maxCpc' => 1.0, 'language' => "de", 'destinationUrl' => "http://groups-beta.google.com/group/adwords-api-php");
	$criterionList1 = addKeywordCriterionList(array($criterion2, $criterion3));
	if ((is_object($criterionList1[0])) && (is_object($criterionList1[1]))) echo "addKeywordCriterionList <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in addKeywordCriterionList<br />\n";

// addKeywordCriteriaOneByOne
	$criterion4 = array('text' => "Test".rand(0, 32768), 'belongsToAdGroupId' => $belongsToAdGroupId, 'type' => "Broad", 'isNegative' => false, 'maxCpc' => 1.0, 'language' => "de", 'destinationUrl' => "http://groups-beta.google.com/group/adwords-api-php");
	$criterion5 = array('text' => "Test".rand(0, 32768), 'belongsToAdGroupId' => $belongsToAdGroupId, 'type' => "Broad", 'isNegative' => false, 'maxCpc' => 1.0, 'language' => "de", 'destinationUrl' => "http://groups-beta.google.com/group/adwords-api-php");
	$criterionList2 = addKeywordCriteriaOneByOne(array($criterion4, $criterion5));
	if ((is_object($criterionList2[0])) && (is_object($criterionList2[1]))) echo "addKeywordCriteriaOneByOne <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in addKeywordCriteriaOneByOne<br />\n";

	echo "<h2>Server Get Functions</h2>\n";

// createCriterionObject (copy $criterionObject1 locally)
	$criterion1Copy = createCriterionObject($belongsToAdGroupId, $criterionObject1->getId());
	if (is_object($criterion1Copy)) echo "createCriterionObject <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in createCriterionObject<br />\n";

// getAllCriteria
	$allCriteria = getAllCriteria($belongsToAdGroupId);
	if (is_array($allCriteria)) echo "getAllCriteria <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getAllCriteria<br />\n";

// getCriterionStats
  $yesterday = gmdate("Y-m-d", mktime(date("H"), date("i"), date("s"), date("m") , date("d") - 1 , date("Y")));
  $dayBeforeYesterday = gmdate("Y-m-d", mktime(date("H"), date("i"), date("s"), date("m") , date("d") - 2 , date("Y")));
  if (is_array($criterionObject1->getCriterionStats($dayBeforeYesterday, $yesterday))) echo "getCriterionStats <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getCriterionStats<br />\n";

// getEstimate
	if (is_array($criterionObject1->getEstimate())) echo "getEstimate <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getEstimate<br />\n";

	echo "<h2>Server Get Functions from Campaign.php</h2>\n";

// getEstimate (Campaign.php)
	if (is_array($campaignObject1->getEstimate())) echo "getEstimate (Campaign.php) <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getEstimate (Campaign.php)<br />\n";
// getAllAdGroups (Campaign.php)
  if (is_array($campaignObject1->getAllAdGroups())) echo "getAllAdGroups (Campaign.php) <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getAllAdGroups (Campaign.php)<br />\n";

	echo "<h2>Server Get Functions from AdGroup.php</h2>\n";

// getEstimate (AdGroup.php)
	if (is_array($adGroupObject1->getEstimate())) echo "getEstimate (AdGroup.php) <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getEstimate (AdGroup.php)<br />\n";

// getAllCriteria (AdGroup.php)
	if (is_array($adGroupObject1->getAllCriteria())) echo "getAllCriteria (AdGroup.php) <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getAllCriteria (AdGroup.php)<br />\n";

	echo "<h2>Object Get Functions</h2>\n";

// Object Get Functions
	if ($criterion1Copy->getText() == $text) echo "getText <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getText<br />\n";

	if ($criterion1Copy->getId() > 0) echo "getId <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getId<br />\n";

	if ($criterion1Copy->getBelongsToAdGroupId() == $belongsToAdGroupId) echo "getBelongsToAdGroupId <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getBelongsToAdGroupId<br />\n";

	if ($criterion1Copy->getType() == $type) echo "getType <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getType<br />\n";

	if ($criterion1Copy->getIsNegative() == $isNegative) echo "getIsNegative <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getIsNegative<br />\n";

	if ($criterion1Copy->getMaxCpc() == $maxCpc) echo "getMaxCpc <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getMaxCpc<br />\n";

	if ($criterion1Copy->getMinCpc() > 0) echo "getMinCpc <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getMinCpc<br />\n";

  $criterionStatuses = Array('Active', 'InActive', 'Disapproved', 'Deleted');
	if (in_array($criterion1Copy->getStatus(), $criterionStatuses)) echo "getStatus <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getStatus<br />\n";

	if ( !$criterion1Copy->getIsPaused() ) echo "getIsPaused <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getIsPaused<br />\n";

	// if ($criterion1Copy->getLanguage() == $language) echo "getLanguage <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getLanguage<br />\n";

	if ($criterion1Copy->getDestinationUrl() == $destinationUrl) echo "getDestinationUrl <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getDestinationUrl<br />\n";

	if (is_array($criterion1Copy->getCriterionData())) echo "getCriterionData <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getCriterionData<br />\n";

	echo "<h2>Server Set Functions</h2>\n";

// Server Set Functions
	$newText = strrev($text);
	$criterion1Copy->setText($newText);
	if ($criterion1Copy->getText() == $newText) echo "setText <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in setText<br />\n";

	$newMaxCpc = 2 * $maxCpc;
	$criterion1Copy->setMaxCpc($newMaxCpc);
	if ($criterion1Copy->getMaxCpc() == $newMaxCpc) echo "setMaxCpc <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in setMaxCpc<br />\n";

	$criterion1Copy->matchMaxCpcToMinCpc();
	if ($criterion1Copy->getMaxCpc() >= $criterion1Copy->getMinCpc()) echo "matchMaxCpcToMinCpc <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in matchMaxCpcToMinCpc<br />\n";

	$newType = "Exact";
	$criterion1Copy->setType($newType);
	if ($criterion1Copy->getType() == $newType) echo "setType <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in setType<br />\n";

	$newFlag = (bool) !$isNegative;
	$criterion1Copy->setIsNegative($newFlag);
	if ($criterion1Copy->getIsNegative() == $newFlag) echo "setIsNegative <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in setIsNegative<br />\n";

	$newFlag = true;
	$criterion1Copy->setIsPaused($newFlag);
	if ($criterion1Copy->getIsPaused() == $newFlag) echo "setIsPaused <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in setIsPaused<br />\n";

	$newDestinationUrl = "http://groups-beta.google.com/group/adwords-api";
	$criterion1Copy->setDestinationUrl($newDestinationUrl);
	if ($criterion1Copy->getDestinationUrl() == $newDestinationUrl) echo "setDestinationUrl <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in setDestinationUrl<br />\n";

	/*
	$newLanguage = "fr";
	$criterion1Copy->setLanguage($newLanguage);
	if ($criterion1Copy->getLanguage() == $newLanguage) echo "setLanguage <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in setLanguage<br />\n";
	*/

	  echo "<h2>Server Remove Function</h2>";
// Server Remove Function
	@removeCriterion($criterion1Copy);
	@removeCriterion($criterionList1[0]);
	@removeCriterion($criterionList1[1]);
	@removeCriterion($criterionList2[0]);
	@removeCriterion($criterionList2[1]);
	if (!isset($criterion1Copy)) echo "removeCriterion <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in removeCriterion<br />\n";

// tidy up the created temporary Campaign object
	@removeCampaign($campaignObject1);

// add a temporary Campaign
	$campaignObject2 = addCampaign("CriterionTest_".rand(0, 32768), "Active", "", "2010-01-01", 500, array("GoogleSearch"), array("fr", "de"), array('countryTargets' => array('countries' => array()), 'regionTargets' => array('regions' => array()), 'metroTargets' => array('metros' => array()), 'cityTargets' => array('cities' => array("Reutlingen, BW DE", "Karlsruhe, BW DE")), 'proximityTargets' => array('circles' => array())), false);

// add a temporary AdGroup
	$belongsToCampaignId = $campaignObject2->getId();
	$adGroupObject2 = addAdGroup("Test_".rand(0, 32768), $belongsToCampaignId, "Active", 0, 50);

	echo "<h2>WebsiteCriteria</h2>";
// prepare all data
	$url = "aol.com";
	$belongsToAdGroupId = $adGroupObject2->getId();
	$isNegative = false;
	$maxCpm = 50;
	$destinationUrl = "http://www.google.com/";

	echo "<h2>Server Add Functions</h2>\n";
// addWebsiteCriterion
	$criterionObject1 = addWebsiteCriterion($url, $belongsToAdGroupId, $isNegative, $maxCpm, $destinationUrl);
	if (is_object($criterionObject1)) echo "addWebsiteCriterion <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in addWebsiteCriterion<br />\n";

// addWebsiteCriterionList
	$criterion2 = array('url' => "spiegel.de", 'belongsToAdGroupId' => $belongsToAdGroupId, 'isNegative' => false, 'maxCpm' => 50, 'destinationUrl' => "http://www.google.com/");
	$criterion3 = array('url' => "dict.leo.org", 'belongsToAdGroupId' => $belongsToAdGroupId, 'isNegative' => false, 'maxCpm' => 50, 'destinationUrl' => "http://www.google.com/");
	$criterionList1 = addWebsiteCriterionList(array($criterion2, $criterion3));
	if ((is_object($criterionList1[0])) && (is_object($criterionList1[1]))) echo "addWebsiteCriterionList <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in addWebsiteCriterionList<br />\n";

// addWebsiteCriteriaOneByOne
	$criterion4 = array('url' => "bild.de", 'belongsToAdGroupId' => $belongsToAdGroupId, 'isNegative' => false, 'maxCpm' => 50, 'destinationUrl' => "http://www.google.com/");
	$criterion5 = array('url' => "frankfurter-rundschau.de", 'belongsToAdGroupId' => $belongsToAdGroupId, 'isNegative' => false, 'maxCpm' => 50, 'destinationUrl' => "http://www.google.com/");
	$criterionList2 = addWebsiteCriteriaOneByOne(array($criterion4, $criterion5));
	if ((is_object($criterionList2[0])) && (is_object($criterionList2[1]))) echo "addWebsiteCriteriaOneByOne <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in addWebsiteCriteriaOneByOne<br />\n";

	echo "<h2>Object Get Functions</h2>\n";

// Object Get Functions
	if ($criterionObject1->getMaxCpm() == $maxCpm) echo "getMaxCpm <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getMaxCpm<br />\n";

	if ($criterionObject1->getUrl() == $url) echo "getUrl <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getUrl<br />\n";

	if (is_array($criterionObject1->getCriterionData())) echo "getCriterionData <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getCriterionData<br />\n";

	echo "<h2>Server Set Functions</h2>\n";

// Server Set Functions
	$newMaxCpm = 2 * $maxCpm;
	$criterionObject1->setMaxCpm($newMaxCpm);
	if ($criterionObject1->getMaxCpm() == $newMaxCpm) echo "setMaxCpm <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in setMaxCpm<br />\n";

	$newUrl = "germany2006.com";
	$criterionObject1->setUrl($newUrl);
	if ($criterionObject1->getUrl() == $newUrl) echo "setUrl <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in setUrl<br />\n";

// tidy up the created temporary Campaign object
	@removeCampaign($campaignObject2);
?>