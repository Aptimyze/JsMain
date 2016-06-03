<?php
  function addAdGroup(
    $name,
    $campaignId,
    $status,
    $keywordMaxCpc,
    $siteMaxCpm,
    $keywordContentMaxCpc = 0
  ) {
    // update the google servers
    $soapClients = &APIlityClients::getClients();
    $someSoapClient = $soapClients->getAdGroupClient();
    // google server calls active adgroups enabled adgroups so rename if
    // necessary
    if (strtolower($status) == "active") $status = "Enabled";
    // think in micros
    // danger: we need to have keywordMaxCpc XOR siteMaxCpm, so we need to set either
    // keywordMaxCpc or siteMaxCpm to a value different from zero
    if ($keywordMaxCpc > 0) {
      $keywordMaxCpc = $keywordMaxCpc * EXCHANGE_RATE;
      $maxCpWhateverXml = "<keywordMaxCpc>".$keywordMaxCpc."</keywordMaxCpc>";
    }
    else {
      $siteMaxCpm = $siteMaxCpm * EXCHANGE_RATE;
      $maxCpWhateverXml = "<siteMaxCpm>".$siteMaxCpm."</siteMaxCpm>";
    }
    $keywordContentMaxCpc = $keywordContentMaxCpc * EXCHANGE_RATE;
    if ($keywordContentMaxCpc > 0) {
      $keywordContentMaxCpcXml = "<keywordContentMaxCpc>".$keywordContentMaxCpc."</keywordContentMaxCpc>";
    }
    else {
      $keywordContentMaxCpcXml = "";
    }

    $soapParameters = "<addAdGroup>
                          <campaignId>".$campaignId."</campaignId>
                          <newData>
                            <status>".$status."</status>
                            <name>".$name."</name>"
                            .$maxCpWhateverXml.""
                            .$keywordContentMaxCpcXml."
                          </newData>
                        </addAdGroup>";
    // add the adgroup to the google servers
    $someAdGroup = $someSoapClient->call("addAdGroup", $soapParameters);
    $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
    if ($someSoapClient->fault) {
      pushFault($someSoapClient, $_SERVER['PHP_SELF'].":addAdGroup()", $soapParameters);
      return false;
    }
    return receiveAdGroup($someAdGroup['addAdGroupReturn']);
  }

  // this will fail completely if only one adgroup fails
  // but won't cause soap overhead
  function addAdGroupList($adgroups) {
    // update the google servers
    $soapClients = &APIlityClients::getClients();
    $someSoapClient = $soapClients->getAdGroupClient();

    $soapParameters = "<addAdGroupList>
                         <campaignId>".
                           $adgroups[0]['belongsToCampaignId']."
                         </campaignId>";
    foreach ($adgroups as $adgroup) {
      if (strtolower($adgroup['status']) == "active") {
        $adgroup['status'] = "Enabled";
      }
      // think in micros
      // danger: we need to have keywordMaxCpc XOR siteMaxCpm, so we need to set either
      // keywordMaxCpc or siteMaxCpm to a value different from zero
      if ($adgroup['keywordMaxCpc'] > 0) {
        $adgroup['keywordMaxCpc'] = $adgroup['keywordMaxCpc'] * EXCHANGE_RATE;
        $maxCpWhateverXml = "<keywordMaxCpc>".$adgroup['keywordMaxCpc']."</keywordMaxCpc>";
      }
      else {
        $adgroup['siteMaxCpm'] = $adgroup['siteMaxCpm'] * EXCHANGE_RATE;
        $maxCpWhateverXml = "<siteMaxCpm>".$adgroup['siteMaxCpm']."</siteMaxCpm>";
      }
      $adgroup['keywordContentMaxCpc'] = $adgroup['keywordContentMaxCpc'] * EXCHANGE_RATE;
      if ($adgroup['keywordContentMaxCpc'] > 0) {
        $keywordContentMaxCpcXml =
          "<keywordContentMaxCpc>".$adgroup['keywordContentMaxCpc']."</keywordContentMaxCpc>";
      }
      else {
        $keywordContentMaxCpcXml = "";
      }
      $soapParameters .= "<newData>
                              <status>".$adgroup['status']."</status>
                            <name>".$adgroup['name']."</name>"
                            .$maxCpWhateverXml.""
                            .$keywordContentMaxCpcXml."
                          </newData>";
    }
    $soapParameters .= "</addAdGroupList>";
    // add adgroups to the google servers
    $someAdGroups = $someSoapClient->call("addAdGroupList", $soapParameters);
    $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
    if ($someSoapClient->fault) {
      pushFault($someSoapClient, $_SERVER['PHP_SELF'].":addAdGroupList()", $soapParameters);
      return false;
    }

    $someAdGroups = makeNumericArray($someAdGroups);

    // create local objects
    $adGroupObjects = array();
    foreach($someAdGroups['addAdGroupListReturn'] as $someAdGroup) {
      $adGroupObject = receiveAdGroup($someAdGroup);
      if (isset($adGroupObject)) {
        array_push($adGroupObjects, $adGroupObject);
      }
    }
    return $adGroupObjects;
  }

  // this will not fail completely if only one adgroup fails
  // but will cause soap overhead
  function addAdGroupsOneByOne($adGroups) {
    // this is just a wrapper to the addAdGroup function
    $adGroupObjects = array();
    foreach ($adGroups as $adGroup) {
      $adGroupObject = addAdGroup(
        $adGroup['name'],
        $adGroup['belongsToCampaignId'],
        $adGroup['status'],
        $adGroup['keywordMaxCpc'],
        $adGroup['siteMaxCpm'],
        $adGroup['keywordContentMaxCpc']
      );
      array_push($adGroupObjects, $adGroupObject);
    }
    return $adGroupObjects;
  }

  function removeAdGroup(&$adGroupObject) {
    // update the google servers
    $soapClients = &APIlityClients::getClients();
    $someSoapClient = $soapClients->getAdGroupClient();
    $soapParameters = "<updateAdGroup>
                          <changedData>
                            <campaignId>".
                              $adGroupObject->getBelongsToCampaignId()."
                            </campaignId>
                            <id>".$adGroupObject->getId()."</id>
                            <status>Deleted</status>
                          </changedData>
                      </updateAdGroup>";
    // remove the adgroup from the google servers
    $someSoapClient->call("updateAdGroup", $soapParameters);
    $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
    if ($someSoapClient->fault) {
      pushFault($someSoapClient, $_SERVER['PHP_SELF'].":removeAdGroup()", $soapParameters);
      return false;
    }
    // delete remote calling object
    $adGroupObject = @$GLOBALS['adGroupObject'];
    unset($adGroupObject);
    return true;
  }

  function getAllAdGroups($campaignId) {
    $soapClients = &APIlityClients::getClients();
    $someSoapClient = $soapClients->getAdGroupClient();
    $soapParameters = "<getAllAdGroups>
                          <id>".$campaignId."</id>
                       </getAllAdGroups>";
    // query the google servers for all adgroups
    $allAdGroups = array();
    $allAdGroups = $someSoapClient->call("getAllAdGroups", $soapParameters);
    $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
    if ($someSoapClient->fault) {
      pushFault($someSoapClient, $_SERVER['PHP_SELF'].":getAllAdGroups()", $soapParameters);
      return false;
    }

    // when we have only one adgroup in the campaign return a (one adgroup
    // element) array  anyway
    $allAdGroups = makeNumericArray($allAdGroups);

    // return only active (google servers call this 'enabled') or paused
    // adgroups
    $allAdGroupObjects = array();
    if (!isset($allAdGroups['getAllAdGroupsReturn'])) {
      return $allAdGroupObjects;
    }
    foreach($allAdGroups['getAllAdGroupsReturn'] as $adGroup) {
      $adGroupObject = receiveAdGroup($adGroup);
      if (isset($adGroupObject)) {
        array_push($allAdGroupObjects, $adGroupObject);
      }
    }
    return $allAdGroupObjects;
  }

  function createAdGroupObject($givenAdGroupId) {
    // creates a local adgroup object
    $soapClients = &APIlityClients::getClients();
    $someSoapClient = $soapClients->getAdGroupClient();
    // prepare soap parameters
    $soapParameters = "<getAdGroup>
                          <id>".$givenAdGroupId."</id>
                       </getAdGroup>";
    // execute soap call
    $someAdGroup = $someSoapClient->call("getAdGroup", $soapParameters);
    $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
    if ($someSoapClient->fault) {
      pushFault($someSoapClient, $_SERVER['PHP_SELF'].":createAdGroupObject()", $soapParameters);
      return false;
    }

    // invalid ids are silently ignored. this is not what we want so put out a
    // warning and return without doing anything.
    if (empty($someAdGroup)) {
      if (!SILENCE_STEALTH_MODE) echo "<br /><b>APIlity PHP library => Warning: </b>Invalid AdGroup ID. No AdGroup with the ID ".$givenAdGroupId." found.";
      return null;
    }
    return receiveAdGroup($someAdGroup['getAdGroupReturn']);
  }

  function getAdGroupList($adGroupIds) {
    $soapClients = &APIlityClients::getClients();
    $someSoapClient = $soapClients->getAdGroupClient();

    $soapParameters = '<getAdGroupList>';
    foreach($adGroupIds as $adGroupId) {
      $soapParameters .= '<ids>'.$adGroupId.'</ids>';
    }
    $soapParameters .= '</getAdGroupList>';

    // query the google servers for all adgroups
    $allAdGroups = array();
    $allAdGroups = $someSoapClient->call('getAdGroupList', $soapParameters);
    $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
    if($someSoapClient->fault) {
      pushFault($someSoapClient, $_SERVER['PHP_SELF'].':getAdGroupList()', $soapParameters);
       return false;
    }

    // when we have only one adgroup in the campaign return a (one adgroup
    // element) array  anyway
    $allAdGroups = makeNumericArray($allAdGroups);

    // return only active (google servers call this 'enabled') or paused adgroups
    $allAdGroupObjects = array();
    if (!isset($allAdGroups['getAdGroupListReturn'])) {
      return $allAdGroupObjects;
    }

    foreach($allAdGroups['getAdGroupListReturn'] as $adGroup) {
      $adGroupObject = receiveAdGroup($adGroup);
      if (isset($adGroupObject)) {
        array_push($allAdGroupObjects, $adGroupObject);
      }
    }
    return $allAdGroupObjects;
  }

  function receiveAdGroup($someAdGroup) {
    if ( ($someAdGroup['status'] == "Enabled") ||
         ($someAdGroup['status'] == "Paused")
    ) {
      // create local object
      // danger! think in currency units here
      if (isset($someAdGroup['keywordMaxCpc'])) {
        $keywordMaxCpc = $someAdGroup['keywordMaxCpc'] / EXCHANGE_RATE;
      }
      else {
        $keywordMaxCpc = NULL;
      }
      if (isset($someAdGroup['siteMaxCpm'])) {
        $siteMaxCpm = $someAdGroup['siteMaxCpm'] / EXCHANGE_RATE;
      }
      else {
        $siteMaxCpm = NULL;
      }
      if (isset($someAdGroup['keywordContentMaxCpc'])) {
        $keywordContentMaxCpc = $someAdGroup['keywordContentMaxCpc'] / EXCHANGE_RATE;
      }
      else {
        $keywordContentMaxCpc = NULL;
      }
      if (isset($someAdGroup['proxyKeywordMaxCpc'])) {
        $proxyKeywordMaxCpc = $someAdGroup['proxyKeywordMaxCpc'] / EXCHANGE_RATE;
      }
      else {
        $proxyKeywordMaxCpc = NULL;
      }
      $adGroupObject = new APIlityAdGroup(
        $someAdGroup['name'],
        $someAdGroup['id'],
        $someAdGroup['campaignId'],
        $keywordMaxCpc,
        $siteMaxCpm,
        $keywordContentMaxCpc,
        $proxyKeywordMaxCpc,
        $someAdGroup['status']
      );
      return $adGroupObject;
    }
  }
?>