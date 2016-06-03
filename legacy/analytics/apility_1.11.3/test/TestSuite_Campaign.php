<?php
	echo "<h2 style='color:blue;'>Campaigns</h2>";

	require_once('apility.php');

// prepare all data
	$name = "Test2_".rand(0, 32768);
	$status = "Active";
	$startDate = "";
	$endDate = "2010-01-01";
	$dailyBudget = 50;
	$networkTargeting = array("GoogleSearch", "ContentNetwork");
	$languages = array("fr");
	$geoTargets = array('countryTargets' => array('countries' => array()), 'regionTargets' => array('regions' => array()), 'metroTargets' => array('metros' => array()), 'cityTargets' => array('cities' => array("Grenoble, V FR")), 'proximityTargets' => array('circles' => array()));
	$isEnabledSeparateContentBids = true;
	$campaignNegativeKeywordCriteria = array(array('text' => "none", 'type' => "Broad"), array('text' => "of", 'type' => "Phrase"), array('text' => "these", 'type' => "Exact"));
	$campaignNegativeWebsiteCriteria = array(array('url' => "spiegel.de"));

	echo "<h2>Server Add Functions</h2>\n";

// addCampaign
	$campaignObject1 = addCampaign("Test1_".rand(0, 32768), "Active", "", "2010-01-01", 50, array("ContentNetwork", "GoogleSearch"), array("fr", "de"), array('countryTargets' => array('countries' => array()), 'regionTargets' => array('regions' => array()), 'metroTargets' => array('metros' => array()), 'cityTargets' => array('cities' => array("Reutlingen, BW DE", "Karlsruhe, BW DE")), 'proximityTargets' => array('circles' => array())), true);
	$campaignObject2 = addCampaign($name, $status, $startDate, $endDate, $dailyBudget, $networkTargeting, $languages, $geoTargets, $isEnabledSeparateContentBids);
	// we need to set the negative criteria separately (as with v3 addCampaign doesn't support this feature anymore)
	$campaignObject2->setCampaignNegativeKeywordCriteria($campaignNegativeKeywordCriteria);
	$campaignObject2->setCampaignNegativeWebsiteCriteria($campaignNegativeWebsiteCriteria);

	if ((is_object($campaignObject1)) && (is_object($campaignObject2))) echo "addCampaign <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in addCampaign<br />\n";

// addCampaignList
	$campaign3 = array('name' => "Test3_".rand(0, 32768), 'status' => "Active", 'startDate' => "", 'endDate' => "2010-01-01", 'dailyBudget' => 50, 'networkTargeting' => array("ContentNetwork"), 'languages' => array("de", "en"), 'geoTargets' => array('countryTargets' => array('countries' => array("DE", "FR")), 'regionTargets' => array('regions' => array()), 'metroTargets' => array('metros' => array()), 'cityTargets' => array('cities' => array()), 'proximityTargets' => array('circles' => array())), 'isEnabledSeparateContentBids' => false);
	$campaign4 = array('name' => "Test4_".rand(0, 32768), 'status' => "Active", 'startDate' => "", 'endDate' => "2010-01-01", 'dailyBudget' => 50, 'networkTargeting' => array("SearchNetwork"), 'languages' => array("de"), 'geoTargets' => array('countryTargets' => array('countries' => array("DE", "FR")), 'regionTargets' => array('regions' => array()), 'metroTargets' => array('metros' => array()), 'cityTargets' => array('cities' => array()), 'proximityTargets' => array('circles' => array())), 'isEnabledSeparateContentBids' => false);
	$campaignList1 = addCampaignList(array($campaign3, $campaign4));
	if ((is_object($campaignList1[0])) && (is_object($campaignList1[1]))) echo "addCampaignList <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in addCampaignList<br />\n";

// addCampaignsOneByOne
	$campaign5 = array('name' => "Test5_".rand(0, 32768), 'status' => "Active", 'startDate' => "", 'endDate' => "2010-01-01", 'dailyBudget' => 50, 'networkTargeting' => array("ContentNetwork"), 'languages' => array("de", "en"), 'geoTargets' => array('countryTargets' => array('countries' => array("DE", "FR")), 'regionTargets' => array('regions' => array()), 'metroTargets' => array('metros' => array()), 'cityTargets' => array('cities' => array()), 'proximityTargets' => array('circles' => array())), 'isEnabledSeparateContentBids' => false);
	$campaign6 = array('name' => "Test6_".rand(0, 32768), 'status' => "Active", 'startDate' => "", 'endDate' => "2010-01-01", 'dailyBudget' => 50, 'networkTargeting' => array("SearchNetwork"), 'languages' => array("all"), 'geoTargets' => array('countryTargets' => array('countries' => array("DE", "FR")), 'regionTargets' => array('regions' => array()), 'metroTargets' => array('metros' => array()), 'cityTargets' => array('cities' => array()), 'proximityTargets' => array('circles' => array())), 'isEnabledSeparateContentBids' => false);
	$campaignList2 = addCampaignsOneByOne(array($campaign5, $campaign6));
	if ((is_object($campaignList2[0])) && (is_object($campaignList2 [1]))) echo "addCampaignsOneByOne <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in addCampaignsOneByOne<br />\n";

  echo "<h2>Server Get Functions</h2>\n";

// Server Get Functions

// createCampaignObject (copy $campaignObject2 locally)
	$campaign2Copy = createCampaignObject($campaignObject2->getId());
	if (is_object($campaign2Copy)) echo "createCampaignObject <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in createCampaignObject<br />\n";

// getCampaignStats
  $yesterday = gmdate("Y-m-d", mktime(date("H"), date("i"), date("s"), date("m") , date("d") - 1 , date("Y")));
  $dayBeforeYesterday = gmdate("Y-m-d", mktime(date("H"), date("i"), date("s"), date("m") , date("d") - 2 , date("Y")));
  if (is_array($campaignObject1->getCampaignStats($dayBeforeYesterday, $yesterday))) echo "getCampaignStats <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getCampaignStats<br />\n";

// getAllCampaigns
  $allCampaigns = getAllCampaigns();
  if (is_array($allCampaigns)) echo "getAllCampaigns <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getAllCampaigns<br />\n";

  echo "<h2>Object Get Functions</h2>\n";

// Object Get Functions
  if ($campaign2Copy->getName() == $name) echo "getName <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getName<br />\n";

  if ($campaign2Copy->getId() > 0) echo "getId <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getId<br />\n";

  if (($campaign2Copy->getStatus() == $status) || ($campaign2Copy->getStatus() == "Pending")) echo "getStatus <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getStatus<br />\n";

  //if (substr($campaign2Copy->getStartDate(), 0, 10) == $startDate) echo "getStartDate <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getStartDate<br />\n";

  if (substr($campaign2Copy->getEndDate(), 0, 10) == $endDate) echo "getEndDate <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getEndDate<br />\n";

  if ($campaign2Copy->getDailyBudget() == $dailyBudget) echo "getDailyBudget <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getDailyBudget<br />\n";

  $difference = array_diff($campaign2Copy->getNetworkTargeting(), $networkTargeting);
  if (empty($difference)) echo "getNetworkTargeting <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getNetworkTargeting<br />\n";

  $difference = array_diff($campaign2Copy->getLanguages(), $languages);
  if (empty($difference)) echo "getLanguages <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getLanguages<br />\n";

  if (is_array($campaign2Copy->getGeoTargets())) echo "getGeoTargets <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getGeoTargets<br />\n";

  $difference = array_diff($campaign2Copy->getCampaignNegativeKeywordCriteria(), $campaignNegativeKeywordCriteria);
  if (empty($difference)) echo "getCampaignNegativeKeywordCriteria <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getCampaignNegativeKeywordCriteria<br />\n";

  $difference = array_diff($campaign2Copy->getCampaignNegativeWebsiteCriteria(), $campaignNegativeWebsiteCriteria);
  if (empty($difference)) echo "getCampaignNegativeWebsiteCriteria <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getCampaignNegativeWebsiteCriteria<br />\n";

  if ($campaign2Copy->getIsEnabledSeparateContentBids() == $isEnabledSeparateContentBids) echo "getIsEnabledSeparateContentBids <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getIsEnabledSeparateContentBids<br />\n";

  if (is_array($campaign2Copy->getCampaignData())) echo "getCampaignData <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getCampaignData<br />\n";

  echo "<h2>Server Set Functions</h2>\n";

// Server Set Functions
  $newName = strrev($name);
  $campaign2Copy->setName($newName);
  if ($campaign2Copy->getName() == $newName) echo "setName <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in setName<br />\n";

  $newEndDate = gmdate("Y-m-d", mktime(date("H"), date("i"), date("s"), date("m") , date("d"), date("Y") + 2));
  $campaign2Copy->setEndDate($newEndDate);
  if ($campaign2Copy->getEndDate() == $newEndDate) echo "setEndDate <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in setEndDate<br />\n";

  $newDailyBudget = 2 * $dailyBudget;
  $campaign2Copy->setDailyBudget($newDailyBudget);
  if ($campaign2Copy->getDailyBudget() == $newDailyBudget) echo "setDailyBudget <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in setDailyBudget<br />\n";

	$newNetworkTargeting = array("SearchNetwork");
	$campaign2Copy->setNetworkTargeting($newNetworkTargeting);
  if ($campaign2Copy->getNetworkTargeting() == $newNetworkTargeting) echo "setNetworkTargeting <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in setNetworkTargeting<br />\n";

  $newLanguages = array("all");
  $campaign2Copy->setLanguages($newLanguages);
  $changedLanguages = $campaign2Copy->getLanguages();
  if (empty($changedLanguages)) echo "setLanguages with array('all') <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in setLanguages with array('all')<br />\n";
  $newLanguages = array("el");
  $campaign2Copy->setLanguages($newLanguages);
  $difference = array_diff($campaign2Copy->getLanguages(), $newLanguages);
  if (empty($difference)) echo "setLanguages with array('arg1') <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in setLanguages with array('arg1')<br />\n";
  $newLanguages = array("hu", "is", "fi");
  $campaign2Copy->setLanguages($newLanguages);
  $difference = array_diff($campaign2Copy->getLanguages(), $newLanguages);
  if (empty($difference)) echo "setLanguages with array('arg1', ... , 'argN') <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in setLanguages with array('arg1', ... , 'argN')<br />\n";

  $newGeoTargets = array('countryTargets' => array('countries' => array()), 'regionTargets' => array('regions' => array("DE-BW", "DE-HH")), 'metroTargets' => array('metros' => array()), 'cityTargets' => array('cities' => array("Berlin, BE DE")), 'proximityTargets' => array('circles' => array()));
  $campaign2Copy->setGeoTargets($newGeoTargets);
  $changedGeoTargets = $campaign2Copy->getGeoTargets();
  if (($changedGeoTargets['countryTargets']['countries'] == array()) && ($changedGeoTargets['regionTargets']['regions'] == array("DE-BW", "DE-HH")) && ($changedGeoTargets['metroTargets']['metros'] == array()) && ($changedGeoTargets['cityTargets']['cities'] == array("Berlin, BE DE"))) echo "setGeoTargets <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in setGeoTargets<br />\n";

  $newCampaignNegativeKeywordCriteria = array();
  $campaign2Copy->setCampaignNegativeKeywordCriteria($newCampaignNegativeKeywordCriteria);
  $changedCampaignNegativeKeywordCriteria = $campaign2Copy->getCampaignNegativeKeywordCriteria();
  if (empty($changedCampaignNegativeKeywordCriteria)) echo "setCampaignNegativeKeywordCriteria with array() <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in setCampaignNegativeKeywordCriteria with array()<br />\n";
  $newCampaignNegativeKeywordCriteria = array(array('text' => "nope", 'type' => "Phrase"));
  $campaign2Copy->setCampaignNegativeKeywordCriteria($newCampaignNegativeKeywordCriteria);
  $difference = array_diff($campaign2Copy->getCampaignNegativeKeywordCriteria(), $newCampaignNegativeKeywordCriteria);
  if (empty($difference)) echo "setCampaignNegativeKeywordCriteria with array('arg1') <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in setCampaignNegativeKeywordCriteria with array('arg1')<br />\n";
  $newCampaignNegativeKeywordCriteria = array(array('text' => "nope", 'type' => "Phrase"), array('text' => "never", 'type' => "Exact"));
  $campaign2Copy->setCampaignNegativeKeywordCriteria($newCampaignNegativeKeywordCriteria);
  $difference = array_diff($campaign2Copy->getCampaignNegativeKeywordCriteria(), $newCampaignNegativeKeywordCriteria);
  if (empty($difference)) echo "setCampaignNegativeKeywordCriteria with array('arg1', ... , 'argN') <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in setCampaignNegativeKeywordCriteria with array('arg1', ... , 'argN')<br />\n";

  $newCampaignNegativeWebsiteCriteria = array();
  $campaign2Copy->setCampaignNegativeWebsiteCriteria($newCampaignNegativeWebsiteCriteria);
  $changedCampaignNegativeWebsiteCriteria = $campaign2Copy->getCampaignNegativeWebsiteCriteria();
  if (empty($changedCampaignNegativeWebsiteCriteria)) echo "setCampaignNegativeWebsiteCriteria with array() <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in setCampaignNegativeWebsiteCriteria with array()<br />\n";
  $newCampaignNegativeWebsiteCriteria = array(array('url' => "spiegel.de"));
  $campaign2Copy->setCampaignNegativeWebsiteCriteria($newCampaignNegativeWebsiteCriteria);
  $difference = array_diff($campaign2Copy->getCampaignNegativeWebsiteCriteria(), $newCampaignNegativeWebsiteCriteria);
  if (empty($difference)) echo "setCampaignNegativeWebsiteCriteria with array('arg1') <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in setCampaignNegativeWebsiteCriteria with array('arg1')<br />\n";
  $newCampaignNegativeWebsiteCriteria = array(array('url' => "spiegel.de"), array('url' => "aol.de"));
  $campaign2Copy->setCampaignNegativeWebsiteCriteria($newCampaignNegativeWebsiteCriteria);
  $difference = array_diff($campaign2Copy->getCampaignNegativeWebsiteCriteria(), $newCampaignNegativeWebsiteCriteria);
  if (empty($difference)) echo "setCampaignNegativeWebsiteCriteria with array('arg1', ... , 'argN') <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in setCampaignNegativeWebsiteCriteria with array('arg1', ... , 'argN')<br />\n";

	$newFlag = false;
	$campaign2Copy->setIsEnabledOptimizedAdServing($newFlag);
	if (!$campaign2Copy->getIsEnabledOptimizedAdServing()) echo "setIsEnabledOptimizedAdServing <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in setIsEnabledOptimizedAdServing<br />\n";

	$newFlag = false;
	$campaign2Copy->setIsEnabledSeparateContentBids($newFlag);
	if (!$campaign2Copy->getIsEnabledSeparateContentBids()) echo "setIsEnabledSeparateContentBids <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in setIsEnabledSeparateContentBids<br />\n";

  $newStatus = "Paused";
  $campaign2Copy->setStatus($newStatus);
  if ($campaign2Copy->getStatus() == $newStatus) echo "setStatus <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in setStatus<br />\n";

  echo "<h2>Server Remove Function</h2>";
// Server Remove Function
  removeCampaign($campaignObject1);
  removeCampaign($campaignObject2);
  removeCampaign($campaignList1[0]);
  removeCampaign($campaignList1[1]);
  removeCampaign($campaignList2[0]);
  removeCampaign($campaignList2[1]);
  if (!isset($campaignObject1)) echo "removeCampaign <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in removeCampaign<br />\n";
?>