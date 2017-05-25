<?php
	echo "<h2 style='color:blue;'>SiteSuggestion</h2>";
	require_once('apility.php');

  // getSitesByCategoryName
  $targeting = array(
    'countries' => array('ES'),
    'languages' => array('es'),
    'metros' => array(),
    'regions' => array()
  );
  $sites = getSitesByCategoryName("MP3 Players", $targeting);
  if ((is_array($sites)) && (!empty($sites))) echo "getSitesByCategoryName <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getSitesByCategoryName<br />\n";
  
  // getSitesByDemographics
  $targeting = array(
    'countries' => array('US'),
    'languages' => array('en'),
    'metros' => array(),
    'regions' => array()
  );
  $demographics = array(
    'childrenTarget' => 'HouseholdsWithChildrenOnly',
    'ethnicityTarget' => 'NoPreference',
    'genderTarget' => 'NoPreference',
    'minAgeRange' => 'Range25To34',
    'maxAgeRange' => 'Range25To34',
    'minHouseholdIncomeRange' => 'Range75000To99999',
    'maxHouseholdIncomeRange' => 'Range100000PLUS'
  );
  $sites = getSitesByDemographics($demographics, $targeting);  
  if ((is_array($sites)) && (!empty($sites))) echo "getSitesByDemographics <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getSitesByDemographics<br />\n";
  
  // getSitesByTopics
  $targeting = array(
    'countries' => array('ES'),
    'languages' => array('es'),
    'metros' => array(),
    'regions' => array()
  );
  $topics = array('search engines', 'web services');
  $sites = getSitesByTopics($topics, $targeting);  
  if ((is_array($sites)) && (!empty($sites))) echo "getSitesByTopics <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getSitesByTopics<br />\n";
  
  // getSitesByUrls
  $targeting = array(
    'countries' => array('ES'),
    'languages' => array('es'),
    'metros' => array(),
    'regions' => array()
  );
  $urls = array('google.com', 'yahoo.com');
  $sites = getSitesByUrls($urls, $targeting);    
  if ((is_array($sites)) && (!empty($sites))) echo "getSitesByUrls <font color='darkgreen'>OK</font><br />\n"; else echo "<font color='red'>error</font> in getSitesByUrls<br />\n";
?>