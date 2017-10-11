<?php
	echo "<h2 style='color:blue;'>Ads</h2>";
	require_once('../apility.php');

// add a temporary Campaign
	$campaignObject1 = addCampaign("AdTest_".rand(0, 32768), "Active", "", "2010-01-01", 50, array("GoogleSearch"), array("fr", "de"), array('countryTargets' => array('countries' => array()), 'regionTargets' => array('regions' => array()), 'metroTargets' => array('metros' => array()), 'cityTargets' => array('cities' => array("Reutlingen, BW DE", "Karlsruhe, BW DE")), 'proximityTargets' => array('circles' => array())), true);
// add a temporary AdGroup
	$belongsToCampaignId = $campaignObject1->getId();
	$adGroupObject1 = addAdGroup("Test_".rand(0, 32768), $belongsToCampaignId, "Active", 1, 0);

// prepare all data
	$belongsToAdGroupId = $adGroupObject1->getId();
	$headline = "Headline ".rand(0, 32768);
	$description1 = "Description1 öäü".rand(0, 32768);
	$description2 = "Description2 ".rand(0, 32768);
	$status = 'Enabled';
	$displayUrl = "http://groups-beta.com";
	$destinationUrl = "http://groups-beta.com";

	echo "<h2>Server Add Functions</h2>\n";

// addTextAd
	$creativeObject1 = addTextAd($belongsToAdGroupId, $headline, $description1, $description2, $status, $displayUrl, $destinationUrl);
	if (is_object($creativeObject1)) echo "addTextAd <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in addTextAd<br />\n";

// addTextAdList
	$creative2 = array('belongsToAdGroupId' => $belongsToAdGroupId, 'headline' => "Headline ".rand(0, 32768), 'description1' => "Description1 ".rand(0, 32768), 'description2' => "Description2 ".rand(0, 32768), 'status' => 'Enabled', 'displayUrl' => "http://groups-beta.com", 'destinationUrl' => "http://groups-beta.com");
	$creative3 = array('belongsToAdGroupId' => $belongsToAdGroupId, 'headline' => "Headline ".rand(0, 32768), 'description1' => "Description1 ".rand(0, 32768), 'description2' => "Description2 ".rand(0, 32768), 'status' => 'Enabled', 'displayUrl' => "http://groups-beta.com", 'destinationUrl' => "http://groups-beta.com");
	$creativeList1 = addTextAdList(array($creative2, $creative3));
	if ((is_object($creativeList1[0])) && (is_object($creativeList1[1]))) echo "addTextAdList <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in addTextAdList<br />\n";

// addTextAdsOneByOne
	$creative2 = array('belongsToAdGroupId' => $belongsToAdGroupId, 'headline' => "Headline ".rand(0, 32768), 'description1' => "Description1 ".rand(0, 32768), 'description2' => "Description2 ".rand(0, 32768), 'status' => 'Enabled', 'displayUrl' => "http://groups-beta.com", 'destinationUrl' => "http://groups-beta.com");
	$creative3 = array('belongsToAdGroupId' => $belongsToAdGroupId, 'headline' => "Headline ".rand(0, 32768), 'description1' => "Description1 ".rand(0, 32768), 'description2' => "Description2 ".rand(0, 32768), 'status' => 'Enabled', 'displayUrl' => "http://groups-beta.com", 'destinationUrl' => "http://groups-beta.com");
	$creativeList2 = addTextAdsOneByOne(array($creative2, $creative3));
	if ((is_object($creativeList2[0])) && (is_object($creativeList2[1]))) echo "addTextAdsOneByOne <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in addTextAdsOneByOne<br />\n";

// prepare all data
	$imageLocation = "TestSuite_TextAd.jpg";
	$name = "Test ".rand(0, 32768);

// addImageAd
	$imageCreativeObject1 = addImageAd($belongsToAdGroupId, $imageLocation, $name, $status, $displayUrl, $destinationUrl);
	if (is_object($imageCreativeObject1)) echo "addImageAd <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in addImageAd<br />\n";

// addImageAdList
	$imageCreative2 =  array('belongsToAdGroupId' => $belongsToAdGroupId, 'imageLocation' => $imageLocation, 'name' => "Test ".rand(0, 32768), 'status' => 'Enabled', 'displayUrl' => "http://groups-beta.com/group", 'destinationUrl' => "http://groups-beta.com");
	$imageCreative3 =  array('belongsToAdGroupId' => $belongsToAdGroupId, 'imageLocation' => $imageLocation, 'name' => "Test ".rand(0, 32768), 'status' => 'Enabled', 'displayUrl' => "http://groups-beta.com/group", 'destinationUrl' => "http://groups-beta.com");
	$imageCreativeList1 = addImageAdList(array($imageCreative2, $imageCreative3));
	if ((is_object($imageCreativeList1[0])) && (is_object($imageCreativeList1[1]))) echo "addImageAdList <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in addImageAdList<br />\n";

// addImageAdsOneByOne
	$imageCreative4 =  array('belongsToAdGroupId' => $belongsToAdGroupId, 'imageLocation' => $imageLocation, 'name' => "Test ".rand(0, 32768), 'status' => 'Paused', 'displayUrl' => "http://groups-beta.com", 'destinationUrl' => "http://groups-beta.com");
	$imageCreative5 =  array('belongsToAdGroupId' => $belongsToAdGroupId, 'imageLocation' => $imageLocation, 'name' => "Test ".rand(0, 32768), 'status' => 'Paused', 'displayUrl' => "http://groups-beta.com", 'destinationUrl' => "http://groups-beta.com");
	$imageCreativeList2 = addImageAdsOneByOne(array($imageCreative2, $imageCreative3));
	if ((is_object($imageCreativeList2[0])) && (is_object($imageCreativeList2[1]))) echo "addImageAdsOneByOne <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in addImageAdsOneByOne<br />\n";

	echo "<h2>Server Get Functions</h2>\n";

// createAdObject (copy $creativeObject1 locally)
	$creative1Copy = createAdObject($belongsToAdGroupId, $creativeObject1->getId());
	if (is_object($creative1Copy)) echo "createAdObject <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in createAdObject<br />\n";

// getAllAds
	$allCreatives = getAllAds(array($belongsToAdGroupId));
	if (is_array($allCreatives)) echo "getAllAds <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getAllAds<br />\n";

// getActiveAds
	$allCreatives = getActiveAds(array($belongsToAdGroupId));
	if (is_array($allCreatives)) echo "getActiveAds <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getActiveAds<br />\n";

	echo "<h2>Object Get Functions</h2>\n";

	if ($creative1Copy->getId() > 0) echo "getId <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getId<br />\n";

 	if ($creative1Copy->getBelongsToAdGroupId() == $belongsToAdGroupId) echo "getBelongsToAdGroupId <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getBelongsToAdGroupId<br />\n";

 	if ($creative1Copy->getHeadline() == $headline) echo "getHeadline <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getHeadline<br />\n";

 	if ($creative1Copy->getDescription1() == utf8_encode($description1)) echo "getDescription1 <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getDescription1<br />\n";

 	if ($creative1Copy->getDescription2() == $description2) echo "getDescription2 <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getDescription2<br />\n";

 	if (strpos($displayUrl, $creative1Copy->getDisplayUrl())) echo "getDisplayUrl <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getDisplayUrl<br />\n";

	if ($creative1Copy->getDestinationUrl() == $destinationUrl) echo "getDestinationUrl <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getDestinationUrl<br />\n";

	if ($creative1Copy->getStatus()) echo "getStatus <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getStatus<br />\n";

	if (!$creative1Copy->getIsDisapproved()) echo "getIsDisapproved <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getIsDisapproved<br />\n";

  if ($creative1Copy->getAdType() == 'TextAd') echo "getAdType <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getAdType<br />\n";

	if (is_array($creative1Copy->getAdData())) echo "getAdData <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getAdData<br />\n";

	$yesterday = gmdate("Y-m-d", mktime(date("H"), date("i"), date("s"), date("m") , date("d") - 1 , date("Y")));
	$dayBeforeYesterday = gmdate("Y-m-d", mktime(date("H"), date("i"), date("s"), date("m") , date("d") - 2 , date("Y")));
	if (is_array($creative1Copy->getAdStats($dayBeforeYesterday, $yesterday))) echo "getAdStats <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getAdStats<br />\n";

	echo "<h2>Server Set Functions</h2>\n";

	$newHeadline = strrev($headline);
	$creative1Copy->setHeadline($newHeadline);
	if ($creative1Copy->getHeadline() == $newHeadline) echo "setHeadline <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in setHeadline<br />\n";

	$newDescription1 = strrev($description1);
	$creative1Copy->setDescription1($newDescription1);
	if ($creative1Copy->getDescription1() == utf8_encode($newDescription1)) echo "setDescription1 <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in setDescription1<br />\n";

	$newDescription2 = strrev($description2);
	$creative1Copy->setDescription2($newDescription2);
	if ($creative1Copy->getDescription2() == $newDescription2) echo "setDescription2 <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in setDescription2<br />\n";

	$newDisplayUrl = "http://www.blog.chanezon.com";
	$creative1Copy->setDisplayUrl($newDisplayUrl);
	if (strpos($newDisplayUrl, $creative1Copy->getDisplayUrl())) echo "setDisplayUrl <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in setDisplayUrl<br />\n";

	$newDestinationUrl = "http://www.blog.chanezon.com";
	$creative1Copy->setDestinationUrl($newDestinationUrl);
	if ($creative1Copy->getDestinationUrl() == $newDestinationUrl) echo "setDestinationUrl <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in setDestinationUrl<br />\n";

	$newStatus = "Paused";
	$creative1Copy->setStatus($newStatus);
	if ($creative1Copy->getStatus() == $newStatus) echo "setStatus <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in setStatus<br />\n";

 	echo "<h2>Server Remove Function</h2>";

// Server Remove Function
	@removeAd($creative1Copy);
	@removeAd($imageCreativeObject1);
	@removeAd($creativeList1[0]);
	@removeAd($creativeList1[1]);
	@removeAd($creativeList2[0]);
	@removeAd($creativeList2[1]);
	@removeAd($imageCreativeList1[0]);
	@removeAd($imageCreativeList1[1]);
	@removeAd($imageCreativeList2[0]);
	@removeAd($imageCreativeList2[1]);
	if ((!isset($creative1Copy)) && (!isset($imageCreativeObject1))) echo "removeAd <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in removeAd<br />\n";

// tidy up the created temporary Campaign object
	@removeCampaign($campaignObject1);

// Server Business Functions
	echo "<h2>Server Business Functions</h2>";

	$myBusinesses = getMyBusinesses();
	if (is_array($myBusinesses)) echo "getMyBusinesses <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getMyBusinesses<br />\n";

	$businesses = findBusinesses('Farmacia', 'Barcelona', 'ES');
	if (is_array($businesses)) echo "findBusinesses <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in findBusinesses<br />\n";
?>
