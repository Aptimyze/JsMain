<?php
	echo "<h2 style='color:blue;'>AdGroups</h2>";
	require_once('apility.php');

// add a temporary Campaign
	$campaignObject1 = addCampaign("AdGroupTest_".rand(0, 32768), "Active", "", "2010-01-01", 50, array("GoogleSearch"), array("fr", "de"), array('countryTargets' => array('countries' => array()), 'regionTargets' => array('regions' => array()), 'metroTargets' => array('metros' => array()), 'cityTargets' => array('cities' => array("Reutlingen, BW DE", "Karlsruhe, BW DE")), 'proximityTargets' => array('circles' => array())), true);
// prepare all data
	$belongsToCampaignId = $campaignObject1->getId();
	$name = "Test_".rand(0, 32768);
	$status = "Active";
	$dailyBudget = 50;
	$keywordMaxCpc = 0.10;
	$siteMaxCpm = 0;
	$keywordContentMaxCpc = 1.5;

	echo "<h2>Server Add Functions</h2>\n";

// addAdGroup
	$adGroupObject1 = addAdGroup($name, $belongsToCampaignId, $status, $keywordMaxCpc, $siteMaxCpm, $keywordContentMaxCpc);
	if (is_object($adGroupObject1)) echo "addAdGroup <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in addAdGroup<br />\n";

// addAdGroupList
	$adGroup2 = array('name' => "Test_".rand(0, 32768), 'belongsToCampaignId' => $belongsToCampaignId, 'status' => "Active", 'keywordMaxCpc' => 0.10, 'siteMaxCpm' => 0, 'keywordContentMaxCpc' => 1.6);
	$adGroup3 = array('name' => "Test_".rand(0, 32768), 'belongsToCampaignId' => $belongsToCampaignId, 'status' => "Enabled", 'keywordMaxCpc' => 0.10, 'siteMaxCpm' => 0, 'keywordContentMaxCpc' => 1.7);
	$adGroupList1 = addAdGroupList(array($adGroup2, $adGroup3));
	if ((is_object($adGroupList1[0])) && (is_object($adGroupList1[1]))) echo "addAdGroupList <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in addAdGroupList<br />\n";

// addAdGroupsOneByOne
	$adGroup4 = array('name' => "Test_".rand(0, 32768), 'belongsToCampaignId' => $belongsToCampaignId, 'status' => "Active", 'keywordMaxCpc' => 0.10, 'siteMaxCpm' => 0, 'keywordContentMaxCpc' => 1.8);
	$adGroup5 = array('name' => "Test_".rand(0, 32768), 'belongsToCampaignId' => $belongsToCampaignId, 'status' => "Enabled", 'keywordMaxCpc' => 0.10, 'siteMaxCpm' => 0, 'keywordContentMaxCpc' => 1.9);
	$adGroupList2 = addAdGroupsOneByOne(array($adGroup4, $adGroup5));
	if ((is_object($adGroupList2[0])) && (is_object($adGroupList2[1]))) echo "addAdGroupsOneByOne <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in addAdGroupsOneByOne<br />\n";

  echo "<h2>Server Get Functions</h2>\n";

// Server Get Functions
// getAdGroupStats
  $yesterday = gmdate("Y-m-d", mktime(date("H"), date("i"), date("s"), date("m") , date("d") - 1 , date("Y")));
  $dayBeforeYesterday = gmdate("Y-m-d", mktime(date("H"), date("i"), date("s"), date("m") , date("d") - 2 , date("Y")));
  if (is_array($adGroupObject1->getAdGroupStats($dayBeforeYesterday, $yesterday))) echo "getAdGroupStats <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getAdGroupStats<br />\n";

// createAdGroupObject (copy $adGroupObject1 locally)
	$adGroup1Copy = createAdGroupObject($adGroupObject1->getId());
	if (is_object($adGroup1Copy)) echo "createAdGroupObject <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in createAdGroupObject<br />\n";

// getAllAdGroups
  $allAdGroups = getAllAdGroups($belongsToCampaignId);
  if (is_array($allAdGroups)) echo "getAllAdGroups <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getAllAdGroups<br />\n";

  echo "<h2>Object Get Functions</h2>\n";

// Object Get Functions
  if ($adGroup1Copy->getName() == $name) echo "getName <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getName<br />\n";

	if ($adGroup1Copy->getId() > 0) echo "getId <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getId<br />\n";

	if ($adGroup1Copy->getBelongsToCampaignId() == $belongsToCampaignId) echo "getBelongsToCampaignId <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getBelongsToCampaignId<br />\n";

	if ($adGroup1Copy->getKeywordMaxCpc() == $keywordMaxCpc) echo "getKeywordMaxCpc <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getKeywordMaxCpc<br />\n";

	if ($adGroup1Copy->getSiteMaxCpm() == $siteMaxCpm) echo "getSiteMaxCpm <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getSiteMaxCpm<br />\n";

	if ($adGroup1Copy->getKeywordContentMaxCpc() == $keywordContentMaxCpc) echo "getKeywordContentMaxCpc <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getKeywordContentMaxCpc<br />\n";

	if ($adGroup1Copy->getStatus() == $status) echo "getStatus <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getStatus<br />\n";

	if (is_array($adGroup1Copy->getAdGroupData())) echo "getAdGroupData <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getAdGroupData<br />\n";

  echo "<h2>Server Set Functions</h2>\n";

// Server Set Functions
  $newName = strrev($name);
  $adGroup1Copy->setName($newName);
  if ($adGroup1Copy->getName() == $newName) echo "setName <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in setName<br />\n";

	$newKeywordMaxCpc = 2 * $keywordMaxCpc;
 	$adGroup1Copy->setKeywordMaxCpc($newKeywordMaxCpc);
 	if ($adGroup1Copy->getKeywordMaxCpc() == $newKeywordMaxCpc) echo "setKeywordMaxCpc <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in setKeywordMaxCpc<br />\n";

	$newKeywordContentMaxCpc = 2 * $keywordContentMaxCpc;
 	$adGroup1Copy->setKeywordContentMaxCpc($newKeywordContentMaxCpc);
 	if ($adGroup1Copy->getKeywordContentMaxCpc() == $newKeywordContentMaxCpc) echo "setKeywordContentMaxCpc <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in setKeywordContentMaxCpc<br />\n";

	// add a temporary CPM based Campaign and AdGroup
	$campaignObject2 = addCampaign(
	  "AdGroupTest_".rand(0, 32768),
	  "Active",
	  "",
	  "2010-01-01",
	  50, 
	  array("GoogleSearch"),
	  array("fr", "de"),
	  array(
	    'countryTargets' => array('countries' => array()),
	    'regionTargets' => array('regions' => array()),
	    'metroTargets' => array('metros' => array()),
	    'cityTargets' => array('cities' => array("Reutlingen, BW DE", "Karlsruhe, BW DE")),
	    'proximityTargets' => array('circles' => array())
	  ),
	  false
	);
	
	$siteMaxCpm = 10.0;
	$adGroupObject2 = addAdGroup("Test_".rand(0, 32768), $campaignObject2->getId(), "Active", 0, $siteMaxCpm);
	
	$newSiteMaxCpm = 0.5 * $siteMaxCpm;
 	$adGroupObject2->setSiteMaxCpm($newSiteMaxCpm);
 	if ($adGroupObject2->getSiteMaxCpm() == $newSiteMaxCpm) echo "setSiteMaxCpm <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in setSiteMaxCpm<br />\n";
 	// tidy up the created temporary CPM based Campaign object
 	@removeCampaign($campaignObject2);

 	$newStatus = "Paused";
 	$adGroup1Copy->setStatus($newStatus);
	if ($adGroup1Copy->getStatus() == $newStatus) echo "setKeywordMaxCpc <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in setKeywordMaxCpc<br />\n";

  echo "<h2>Server Remove Function</h2>";

// Server Remove Function
	@removeAdGroup($adGroupObject1);
	@removeAdGroup($adGroupList1[0]);
	@removeAdGroup($adGroupList1[1]);
	@removeAdGroup($adGroupList2[0]);
	@removeAdGroup($adGroupList2[1]);
	if (!isset($adGroupObject1)) echo "removeAdGroup <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in removeAdGroup<br />\n";

// tidy up the created temporary Campaign object
	@removeCampaign($campaignObject1);
?>