<?php
  function getNewKeywordEstimate($text, $type, $maxCpc, $isNegative) {
    $soapClients = &APIlityClients::getClients();
    $someSoapClient = $soapClients->getTrafficEstimatorClient();
    // think in micros
    $maxCpc = $maxCpc * EXCHANGE_RATE;
    // be sure that boolean gets inserted correctly
    if ($isNegative) $isNegative = "true"; else $isNegative = "false";
    $soapParameters = "<estimateKeywordList>
                          <keywordRequests>
                            <maxCpc>".$maxCpc."</maxCpc>
                            <negative>".$isNegative."</negative>
                            <text>".$text."</text>
                            <type>".$type."</type>
                          </keywordRequests>
                        </estimateKeywordList>";
    // talk to the google server
    $trafficEstimate =
      $someSoapClient->call("estimateKeywordList", $soapParameters);
    $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
    if ($someSoapClient->fault) {
      pushFault($someSoapClient, $_SERVER['PHP_SELF'].":getNewKeywordEstimate()", $soapParameters);
      return false;
    }
    // separate returned values
    $lowerAveragePosition = $trafficEstimate['estimateKeywordListReturn']['lowerAvgPosition'];
    $upperAveragePosition = $trafficEstimate['estimateKeywordListReturn']['upperAvgPosition'];
    $lowerCostPerClick = $trafficEstimate['estimateKeywordListReturn']['lowerCpc'] / EXCHANGE_RATE;
    $upperCostPerClick = $trafficEstimate['estimateKeywordListReturn']['upperCpc'] / EXCHANGE_RATE;
    $lowerClicksPerDay = $trafficEstimate['estimateKeywordListReturn']['lowerClicksPerDay'];
    $upperClicksPerDay = $trafficEstimate['estimateKeywordListReturn']['upperClicksPerDay'];
    // create estimate array
    unset($trafficEstimate);
    $trafficEstimate = array(
      'text' => $text,
      'lowerAveragePosition' => $lowerAveragePosition,
      'upperAveragePosition' => $upperAveragePosition,
      'lowerCostPerClick' => $lowerCostPerClick,
      'upperCostPerClick' => $upperCostPerClick,
      'lowerClicksPerDay' => $lowerClicksPerDay,
      'upperClicksPerDay' => $upperClicksPerDay
    );
    return $trafficEstimate;
  }

  function getNewKeywordListEstimate($newKeywords) {
    $soapClients = &APIlityClients::getClients();
    $someSoapClient = $soapClients->getTrafficEstimatorClient();
    // prepare soap parameters
    $soapParameters = "<estimateKeywordList>";
    if (is_array($newKeywords)) foreach($newKeywords as $newKeyword) {
      // think in micros
      // be sure that boolean gets inserted correctly
      if($newKeyword['isNegative']) {
        $isNegative = "true";
      }
      else {
        $isNegative = "false";
      }
      $soapParameters .=  "<keywordRequests>
                              <maxCpc>".($newKeyword['maxCpc'] * EXCHANGE_RATE)."</maxCpc>
                              <negative>".$isNegative."</negative>
                              <text>".$newKeyword['text']."</text>
                              <type>".$newKeyword['type']."</type>
                            </keywordRequests>";
    }
    $soapParameters .= "</estimateKeywordList>";
    // talk to the google server
    $trafficEstimates =
      $someSoapClient->call("estimateKeywordList", $soapParameters);
    $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
    if ($someSoapClient->fault) {
      pushFault($someSoapClient, $_SERVER['PHP_SELF'].":getNewKeywordListEstimate()", $soapParameters);
      return false;
    }
    if (isset($trafficEstimates['estimateKeywordListReturn']['lowerCpc'])) {
      $saveArray = $trafficEstimates['estimateKeywordListReturn'];
      unset($trafficEstimates);
      $trafficEstimates['estimateKeywordListReturn'][0] = $saveArray;
    }
    $i = 0;
    foreach($trafficEstimates['estimateKeywordListReturn'] as $trafficEstimate) {
      // separate returned values
      $lowerAveragePosition = $trafficEstimate['lowerAvgPosition'];
      $upperAveragePosition = $trafficEstimate['upperAvgPosition'];
      $lowerCostPerClick = $trafficEstimate['lowerCpc'] / EXCHANGE_RATE;
      $upperCostPerClick = $trafficEstimate['upperCpc'] / EXCHANGE_RATE;
      $lowerClicksPerDay = $trafficEstimate['lowerClicksPerDay'];
      $upperClicksPerDay = $trafficEstimate['upperClicksPerDay'];
      // create estimate array
      unset($trafficEstimates['estimateKeywordListReturn'][$i]);
      $trafficEstimates['estimateKeywordListReturn'][$i] = array(
        'text' => $newKeywords[$i]['text'],
        'lowerAveragePosition' => $lowerAveragePosition,
        'upperAveragePosition' => $upperAveragePosition,
        'lowerCostPerClick' => $lowerCostPerClick,
        'upperCostPerClick' => $upperCostPerClick,
        'lowerClicksPerDay' => $lowerClicksPerDay,
        'upperClicksPerDay' => $upperClicksPerDay
      );
      $i++;
    }
    return $trafficEstimates['estimateKeywordListReturn'];
  }

  function checkKeywordListTraffic($newKeywords) {
    $soapClients = &APIlityClients::getClients();
    $someSoapClient = $soapClients->getTrafficEstimatorClient();
    // prepare soap parameters
    $soapParameters = "<checkKeywordTraffic>";
    if (is_array($newKeywords)) foreach($newKeywords as $newKeyword) {
      $soapParameters .=  "<requests>
                              <keywordText>".$newKeyword['text']."</keywordText>
                              <keywordType>".$newKeyword['type']."</keywordType>
                            </requests>";
    }
    $soapParameters .= "</checkKeywordTraffic>";
    // talk to the google server
    $trafficEstimates =
      $someSoapClient->call("checkKeywordTraffic", $soapParameters);
    $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
    if ($someSoapClient->fault) {
      pushFault($someSoapClient, $_SERVER['PHP_SELF'].":checkKeywordListTraffic()", $soapParameters);
      return false;
    }
    return $trafficEstimates['checkKeywordTrafficReturn'];
  }

  function getKeywordEstimate($keywordObject) {
    // estimates a single Keyword
     $soapClients = &APIlityClients::getClients();
    $someSoapClient = $soapClients->getTrafficEstimatorClient();
    // think in micros
    // be sure that boolean gets inserted correctly
    if($keywordObject->getIsNegative()) {
      $isNegative = "true";
    }
    else {
      $isNegative = "false";
    }
    $soapParameters = "<estimateKeywordList>
                          <keywordRequests>
                            <maxCpc>".($keywordObject->getMaxCpc() * EXCHANGE_RATE)."</maxCpc>
                            <negative>".$isNegative."</negative>
                            <text>".$keywordObject->getText()."</text>
                            <type>".$keywordObject->getType()."</type>
                          </keywordRequests>
                        </estimateKeywordList>";
    // talk to the google server
    $trafficEstimate = $someSoapClient->call("estimateKeywordList", $soapParameters);
    $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
    if ($someSoapClient->fault) {
      pushFault($someSoapClient, $_SERVER['PHP_SELF'].":getKeywordEstimate()", $soapParameters);
      return false;
    }
    // separate returned values
    $lowerAveragePosition = $trafficEstimate['estimateKeywordListReturn']['lowerAvgPosition'];
    $upperAveragePosition = $trafficEstimate['estimateKeywordListReturn']['upperAvgPosition'];
    $lowerCostPerClick = $trafficEstimate['estimateKeywordListReturn']['lowerCpc'] / EXCHANGE_RATE;
    $upperCostPerClick = $trafficEstimate['estimateKeywordListReturn']['upperCpc'] / EXCHANGE_RATE;
    $lowerClicksPerDay = $trafficEstimate['estimateKeywordListReturn']['lowerClicksPerDay'];
    $upperClicksPerDay = $trafficEstimate['estimateKeywordListReturn']['upperClicksPerDay'];

    // create estimate array
    unset($trafficEstimate);
    $trafficEstimate = array(
      'text' => $keywordObject->getText(),
      'lowerAveragePosition' => $lowerAveragePosition,
      'upperAveragePosition' => $upperAveragePosition,
      'lowerCostPerClick' => $lowerCostPerClick,
      'upperCostPerClick' => $upperCostPerClick,
      'lowerClicksPerDay' => $lowerClicksPerDay,
      'upperClicksPerDay' => $upperClicksPerDay
    );
    return $trafficEstimate;
  }

  function getAdGroupEstimate($adGroupObject) {
    // estimates all keywords in an adgroup
    $soapClients = &APIlityClients::getClients();
    $someSoapClient = $soapClients->getTrafficEstimatorClient();
    // get all keywords of the current adgroup
    $allKeywordsOfCurrentAdGroup = $adGroupObject->getAllCriteria();
    $soapParameters = "<estimateKeywordList>";
    if (is_array($allKeywordsOfCurrentAdGroup)) foreach($allKeywordsOfCurrentAdGroup as $keyword) {
      // think in micros
      // be sure that boolean gets inserted correctly
      if ($keyword->getIsNegative()) {
        $isNegative = "true";
      }
      else {
        $isNegative = "false";
      }
      if ($keyword->getMaxCpc()) {
        $maxCpc = $keyword->getMaxCpc() * EXCHANGE_RATE;
      }
      else {
        $maxCpc =  $adGroupObject->getKeywordMaxCpc() * EXCHANGE_RATE;
      }
      $soapParameters .=  "<keywordRequests>
                              <maxCpc>".$maxCpc."</maxCpc>
                              <negative>".$isNegative."</negative>
                              <text>".$keyword->getText()."</text>
                              <type>".$keyword->getType()."</type>
                            </keywordRequests>";
    }
    $soapParameters .= "</estimateKeywordList>";
    // talk to the google server
    $trafficEstimates =
      $someSoapClient->call("estimateKeywordList", $soapParameters);
    $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
    if ($someSoapClient->fault) {
      pushFault($someSoapClient, $_SERVER['PHP_SELF'].":getAdGroupEstimate()", $soapParameters);
      return false;
    }
    $trafficEstimates;
    $i = 0;
    foreach($trafficEstimates['estimateKeywordListReturn'] as $trafficEstimate) {
      // separate returned values
      $lowerAveragePosition = $trafficEstimate['lowerAvgPosition'];
      $upperAveragePosition = $trafficEstimate['upperAvgPosition'];
      $lowerCostPerClick = $trafficEstimate['lowerCpc'] / EXCHANGE_RATE;
      $upperCostPerClick = $trafficEstimate['upperCpc'] / EXCHANGE_RATE;
      $lowerClicksPerDay = $trafficEstimate['lowerClicksPerDay'];
      $upperClicksPerDay = $trafficEstimate['upperClicksPerDay'];
      // create estimate array
      unset($trafficEstimates['estimateKeywordListReturn'][$i]);
      $trafficEstimates['estimateKeywordListReturn'][$i] = array(
        'text' => $allKeywordsOfCurrentAdGroup[$i]->getText(),
        'lowerAveragePosition' => $lowerAveragePosition,
        'upperAveragePosition' => $upperAveragePosition,
        'lowerCostPerClick' => $lowerCostPerClick,
        'upperCostPerClick' => $upperCostPerClick,
        'lowerClicksPerDay' => $lowerClicksPerDay,
        'upperClicksPerDay' => $upperClicksPerDay
      );
      $i++;
    }
    $trafficEstimates['estimateKeywordListReturn']['adGroupName'] =
      $adGroupObject->getName();
    $trafficEstimates['estimateKeywordListReturn']['adGroupId'] =
      $adGroupObject->getId();
    return $trafficEstimates['estimateKeywordListReturn'];
  }

  function getCampaignEstimate($campaignObject) {
    // estimates all keywords in all adgroups in a campaign
    $soapClients = &APIlityClients::getClients();
    $someSoapClient = $soapClients->getTrafficEstimatorClient();

    // prepare soap parameters, this takes some time as we need to iterate over
    // all keywords in all adgroups of the campaign
    $soapParameters = "<estimateKeywordList>";
    // get all adgroups of the current campaign
    $allAdGroupsOfCurrentCampaign = $campaignObject->getAllAdGroups();
    $keywordOrder = array();
    $i = 0;
    if (is_array($allAdGroupsOfCurrentCampaign)) foreach($allAdGroupsOfCurrentCampaign as $adGroupObject) {
      // get all keywords of the current adgroup
      $allKeywordsOfCurrentAdGroup = $adGroupObject->getAllCriteria();
      $sizeOfAdGroup[$i] = sizeof($allKeywordsOfCurrentAdGroup);
      if (is_array($allKeywordsOfCurrentAdGroup)) foreach($allKeywordsOfCurrentAdGroup as $keyword) {
        // preserve the keyword order in an array for back-matching the estimates later
        array_push($keywordOrder, $keyword->getText());
        // be sure that boolean gets inserted correctly
        if($keyword->getIsNegative()) {
          $isNegative = "true";
        }
        else {
          $isNegative = "false";
        }
        if ($keyword->getMaxCpc()) {
          $maxCpc = $keyword->getMaxCpc() * EXCHANGE_RATE;
        }
        else {
          $maxCpc =  $adGroupObject->getKeywordMaxCpc() * EXCHANGE_RATE;
        }
        // think in micros here
        $soapParameters .=  "<keywordRequests>
                                <maxCpc>".$maxCpc."</maxCpc>
                                <negative>".$isNegative."</negative>
                                <text>".$keyword->getText()."</text>
                                <type>".$keyword->getType()."</type>
                              </keywordRequests>";
      }
      $i++;
    }
    $soapParameters .= "</estimateKeywordList>";
    // soap parameters happily prepared

    // talk to the google server
    $trafficEstimates = $someSoapClient->call("estimateKeywordList", $soapParameters);
    $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
    if ($someSoapClient->fault) {
      pushFault($someSoapClient, $_SERVER['PHP_SELF'].":getCampaignEstimate()", $soapParameters);
      return false;
    }
    $trafficEstimates;
    // process estimates an back-match to adgroups
    $k = 0;
    $adGroupEstimate = array();
    $campaignEstimate = array();
    for($i = 0; $i < sizeof($allAdGroupsOfCurrentCampaign); $i++) {
      for($j = 0; $j < $sizeOfAdGroup[$i]; $j++) {
        // separate returned values
        $lowerAveragePosition = $trafficEstimates['estimateKeywordListReturn'][$k]['lowerAvgPosition'];
        $upperAveragePosition = $trafficEstimates['estimateKeywordListReturn'][$k]['upperAvgPosition'];
        $lowerCostPerClick = $trafficEstimates['estimateKeywordListReturn'][$k]['lowerCpc'] / EXCHANGE_RATE;
        $upperCostPerClick = $trafficEstimates['estimateKeywordListReturn'][$k]['upperCpc'] / EXCHANGE_RATE;
        $lowerClicksPerDay = $trafficEstimates['estimateKeywordListReturn'][$k]['lowerClicksPerDay'];
        $upperClicksPerDay = $trafficEstimates['estimateKeywordListReturn'][$k]['upperClicksPerDay'];
        array_push($adGroupEstimate, array(
          'text' => $keywordOrder[$k],
          'lowerAveragePosition' => $lowerAveragePosition,
          'upperAveragePosition' => $upperAveragePosition,
          'lowerCostPerClick' => $lowerCostPerClick,
          'upperCostPerClick' => $upperCostPerClick,
          'lowerClicksPerDay' => $lowerClicksPerDay,
          'upperClicksPerDay' => $upperClicksPerDay)
        );
        $k++;
      }
      $adGroupEstimate['adGroupName'] = $allAdGroupsOfCurrentCampaign[$i]->getName();
      $adGroupEstimate['adGroupId'] = $allAdGroupsOfCurrentCampaign[$i]->getId();
      array_push($campaignEstimate, $adGroupEstimate);
      unset($adGroupEstimate);
      $adGroupEstimate = array();
    }
    $campaignEstimate['campaignName'] = $campaignObject->getName();
    $campaignEstimate['campaignId'] = $campaignObject->getId();
    return $campaignEstimate;
  }
?>