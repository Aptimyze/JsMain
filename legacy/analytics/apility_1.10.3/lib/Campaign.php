<?php
  class APIlityCampaign {
    // class attributes
    var $name;
    var $id;
    var $status;
    var $startDate;
    var $endDate;
    var $dailyBudget;
    var $languages = array();
    var $geoTargets = array();
    var $isEnabledOptimizedAdServing;
    var $networkTargeting = array();
    var $isEnabledSeparateContentBids;
    var $adScheduling = array();
    var $budgetOptimizerSettings = array();
    var $campaignNegativeKeywordCriteria = array();
    var $campaignNegativeWebsiteCriteria = array();

    // constructor
    function APIlityCampaign (
      $name,
      $id,
      $status,
      $startDate,
      $endDate,
      $dailyBudget,
      $networkTargeting,
      $languages,
      $geoTargets,
      $isEnabledOptimizedAdServing,
      $isEnabledSeparateContentBids,
      $campaignNegativeKeywordCriteria = array(),
      $campaignNegativeWebsiteCriteria = array(),
      $adScheduling,
      $budgetOptimizerSettings = array()
    ) {
      $this->name = $name;
      $this->id = $id;
      $this->status = $status;
      $this->startDate = $startDate;
      $this->endDate = $endDate;
      $this->dailyBudget = $dailyBudget;
      $this->isEnabledSeparateContentBids =
        convertBool($isEnabledSeparateContentBids);
      $this->networkTargeting = convertToArray($networkTargeting);
      $this->languages = convertToArray($languages);
      $this->geoTargets = $geoTargets;
      $this->campaignNegativeKeywordCriteria =
        convertToArray($campaignNegativeKeywordCriteria);
      $this->campaignNegativeWebsiteCriteria =
        convertToArray($campaignNegativeWebsiteCriteria);
      $this->isEnabledOptimizedAdServing =
        convertBool($isEnabledOptimizedAdServing);
      $this->adScheduling = $adScheduling;
      $this->budgetOptimizerSettings = $budgetOptimizerSettings;
    }

    // XML output
    function toXml() {
      if ($this->getIsEnabledSeparateContentBids()) {
        $isEnabledSeparateContentBids = "true";
      }
      else {
        $isEnabledSeparateContentBids = "false";
      }
      $adSchedulingXml = "";
      $adScheduling = $this->getAdScheduling();
      $adSchedulingXml .= "\t<status>".$adScheduling['status']."</status>\n";
      if ( strcasecmp($adScheduling['status'], "Disabled") != 0 ) {
        foreach ($adScheduling['intervals'] as $interval) {
          $adSchedulingXml .= "\t<intervals>\n
                               \t\t<multiplier>".$interval['multiplier']."</multiplier>\n
                               \t\t<day>".$interval['day']."</day>\n
                               \t\t<startHour>".$interval['startHour']."</startHour>\n
                               \t\t<startMinute>".$interval['startMinute']."</startMinute>\n
                               \t\t<endHour>".$interval['endHour']."</endHour>\n
                               \t\t<endMinute>".$interval['endMinute']."</endMinute>\n
                               \t</intervals>\n";
        }
      }

      $networkTargetingXml = "";
      foreach ($this->getNetworkTargeting() as $networkTarget) {
        $networkTargetingXml .= "\t\t<networkTarget>".$networkTarget."</networkTarget>\n";
      }

      $languagesXml = "";
      foreach ($this->getLanguages() as $language) {
        $languagesXml .= "\t\t<language>".$language."</language>\n";
      }

      $geoTargetsXml = "";
      $geoTargets = $this->getGeoTargets();
      $geoTargetsXml .= "\t<countryTargets>\n";
      foreach (@$geoTargets['countryTargets']['countries'] as $country) {
        $geoTargetsXml .= "\t\t<countries>".$country."</countries>\n";
      }
      $geoTargetsXml .= "\t</countryTargets>\n\t<regionTargets>";
      foreach (@$geoTargets['regionTargets']['regions'] as $region) {
        $geoTargetsXml .= "\t\t<regions>".$region."</regions>\n";
      }
      $geoTargetsXml .= "\t</regionTargets>\n\t<metroTargets>";
      foreach (@$geoTargets['metroTargets']['metros'] as $metro) {
        $geoTargetsXml .= "\t\t<metros>".$metro."</metros>\n";
      }
      $geoTargetsXml .= "\t</metroTargets>\n\t<cityTargets>";
      foreach (@$geoTargets['cityTargets']['cities'] as $city) {
        $geoTargetsXml .= "\t\t<cities>".$city."</cities>\n";
      }
      $geoTargetsXml .= "\t</cityTargets>\n\t<proximityTargets>";
      foreach (@$geoTargets['proximityTargets']['circles'] as $circle) {
        $geoTargetsXml .= "\t\t<circles>\n";
        $geoTargetsXml .= "\t\t\t<latitudeMicroDegrees>".$circle['latitudeMicroDegrees']."</latitudeMicroDegrees>\n";
        $geoTargetsXml .= "\t\t\t<longitudeMicroDegrees>".$circle['longitudeMicroDegrees']."</longitudeMicroDegrees>\n";
        $geoTargetsXml .= "\t\t\t<radiusMeters>".$circle['radiusMeters']."</radiusMeters>\n";
        $geoTargetsXml .= "\t\t</circles>\n";
      }
      $geoTargetsXml .= "\t</proximityTargets>\n";
      if (@$geoTargets['targetAll']) {
        $geoTargets['targetAll'] = "true";
      }
      else {
        $geoTargets['targetAll'] = "false";
      }
      $geoTargetsXml .= "\t</targetAll>".$geoTargets['targetAll']."</targetAll>\n";

      $negativeWebsiteCriteriaXml = "";
      foreach ($this->getCampaignNegativeWebsiteCriteria() as $criterion) {
        $negativeWebsiteCriteriaXml .=
          "\t\t<negativeKeywordCriterion>\n\t\t\t<url>".
          $criterion['url']."</url>\n\t\t</negativeKeywordCriterion>\n";
      }

      $negativeKeywordCriteriaXml = "";
      foreach ($this->getCampaignNegativeKeywordCriteria() as $criterion) {
        $negativeKeywordCriteriaXml .=
          "\t\t<negativeKeywordCriterion>\n\t\t\t<text>".
          $criterion['text']."</text>\n\t\t\t<type>".
          $criterion['type']."</type>\n\t\t</negativeKeywordCriterion>\n";
      }

      $budgetOptimizerSettingsXml = "";
      $budgetOptimizerSettings = $this->getBudgetOptimizerSettings();
      if ($budgetOptimizerSettings['enabled']) {
        $budgetOptimizerSettings['enabled'] = "true";
      }
      else {
        $budgetOptimizerSettings['enabled'] = "false";
      }
      $budgetOptimizerSettingsXml .=
        "\t\t<bidCeiling>".$budgetOptimizerSettings['bidCeiling'].
        "</bidCeiling>\n\t\t<enabled>".
        $budgetOptimizerSettings['enabled']."</enabled>\n";

      $xml = "<Campaign>
  <name>".$this->getName()."</name>
  <id>".$this->getId()."</id>
  <status>".$this->getStatus()."</status>
  <startDate>".$this->getStartDate()."</startDate>
  <endDate>".$this->getEndDate()."</endDate>
  <dailyBudget>".$this->getDailyBudget()."</dailyBudget>
  <isEnabledSeparateContentBids>".$isEnabledSeparateContentBids."</isEnabledSeparateContentBids>
  <networkTargeting>\n".$networkTargetingXml."\t</networkTargeting>
  <languages>\n".$languagesXml."\t</languages>
  <geoTargets>\n".$geoTargetsXml."\t</geoTargets>
  <negativeKeywordCriteria>\n".$negativeKeywordCriteriaXml."\t</negativeKeywordCriteria>
  <negativeWebsiteCriteria>\n".$negativeWebsiteCriteriaXml."\t</negativeWebsiteCriteria>
  <adScheduling>\n".$adSchedulingXml."\t</adScheduling>
  <budgetOptimizerSettings>\n".$budgetOptimizerSettingsXml."\t</budgetOptimizerSettings>
</Campaign>";
      return $xml;
    }

    // get functions
    function getName() {
      return $this->name;
    }

    function getId() {
      return $this->id;
    }

    function getStatus() {
      return $this->status;
    }

    function getStartDate() {
      return $this->startDate;
    }

    function getEndDate() {
      return $this->endDate;
    }

    function getAdScheduling() {
      return $this->adScheduling;
    }

    function getBudgetOptimizerSettings() {
      return $this->budgetOptimizerSettings;
    }

    function getDailyBudget() {
      // thinking in currency units here
      return $this->dailyBudget;
    }

    function getNetworkTargeting() {
      return $this->networkTargeting;
    }

    function getIsEnabledSeparateContentBids() {
      // make sure bool is transformed correctly to integer
      return (integer) $this->isEnabledSeparateContentBids;
    }

    function getLanguages() {
      return $this->languages;
    }

    function getGeoTargets() {
      return $this->geoTargets;
    }

    function getIsEnabledOptimizedAdServing() {
      // make sure bool is transformed correctly to integer
      return (integer) $this->isEnabledOptimizedAdServing;
    }

    function getEstimate() {
      // this function is located in TrafficEstimate.php
      return getCampaignEstimate($this);
    }

    // report function
    function getCampaignData() {
      $campaignData = array(
        'name' => $this->getName(),
        'id' => $this->getId(),
        'status' => $this->getStatus(),
        'startDate' => $this->getStartDate(),
        'endDate' => $this->getEndDate(),
        'dailyBudget' => $this->getDailyBudget(),
        'networkTargeting' => $this->getNetworkTargeting(),
        'languages' => $this->getLanguages(),
        'geoTargets' => $this->getGeoTargets(),
        'isEnabledSeparateContentBids' => $this->getIsEnabledSeparateContentBids(),
        'isEnabledOptimizedAdServing' => $this->getIsEnabledOptimizedAdServing(),
        'campaignNegativeWebsiteCriteria' => $this->getCampaignNegativeWebsiteCriteria(),
        'campaignNegativeKeywordCriteria' => $this->getCampaignNegativeKeywordCriteria(),
        'adScheduling' => $this->getAdScheduling()
      );
      return $campaignData;
    }

    function getCampaignStats($startDate, $endDate) {
      global $soapClients;
      $someSoapClient = $soapClients->getCampaignClient();
      $soapParameters = "<getCampaignStats>
                            <campaignIds>".$this->getId()."</campaignIds>
                            <startDay>".$startDate."</startDay>
                            <endDay>".$endDate."</endDay>
                         </getCampaignStats>";
      // query the google servers for the campaign stats
      $campaignStats = $someSoapClient->call("getCampaignStats", $soapParameters);
      $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
      if ($someSoapClient->fault) {
        pushFault($someSoapClient, $_SERVER['PHP_SELF'].":getCampaignStats()", $soapParameters);
        return false;
      }
      // add campaign name to the stats for the sake of clarity
      $campaignStats['getCampaignStatsReturn']['name'] = $this->getName();
      // think in currency units
      $campaignStats['getCampaignStatsReturn']['cost'] =
        $campaignStats['getCampaignStatsReturn']['cost'] / EXCHANGE_RATE;
      return $campaignStats['getCampaignStatsReturn'];
    }

    function getAllAdGroups() {
      global $soapClients;
      $someSoapClient = $soapClients->getAdGroupClient();
      $soapParameters = "<getAllAdGroups>
                            <campaignID>".$this->getId()."</campaignID>
                         </getAllAdGroups>";
      // query the google servers for all adgroups of the campaign
      $allAdGroups = array();
      $allAdGroups = $someSoapClient->call("getAllAdGroups", $soapParameters);
      $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
      if ($someSoapClient->fault) {
        pushFault($someSoapClient, $_SERVER['PHP_SELF'].":getAllAdGroups()", $soapParameters);
        return false;
      }

      $allAdGroups = makeNumericArray($allAdGroups);

      // return only paused and active adgroups
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

    function getCampaignNegativeWebsiteCriteria() {
      global $soapClients;
      $someSoapClient = $soapClients->getCriterionClient();
      $soapParameters = "<getCampaignNegativeCriteria>
                            <campaignId>".$this->getId()."</campaignId>
                         </getCampaignNegativeCriteria>";
      $allCampaignNegativeCriteria =
        $someSoapClient->call("getCampaignNegativeCriteria", $soapParameters);
      $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
      if ($someSoapClient->fault) {
        pushFault($someSoapClient, $_SERVER['PHP_SELF'].":getCampaignNegativeWebsiteCriteria()", $soapParameters);
        return false;
      }
      // if we have only one campaign negative criterion return a one-element array anyway
      if (isset($allCampaignNegativeCriteria['getCampaignNegativeCriteriaReturn'])) {
        $saveNegativeCriteria = $allCampaignNegativeCriteria['getCampaignNegativeCriteriaReturn'];
      }
      else {
        $saveNegativeCriteria = array();
      }
      if (isset($allCampaignNegativeCriteria['getCampaignNegativeCriteriaReturn']['id'])) {
        unset($allCampaignNegativeCriteria);
        $allCampaignNegativeCriteria = array();
        if (isset($saveNegativeCriteria['url'])) {
          $allCampaignNegativeCriteria[0] =
            array('url' => $saveNegativeCriteria['url']);
        }
      }
      else {
        unset($allCampaignNegativeCriteria);
        $allCampaignNegativeCriteria = array();
        foreach ($saveNegativeCriteria as $negativeCriterion) {
          if (isset($negativeCriterion['url'])) {
            array_push(
              $allCampaignNegativeCriteria,
              array('url' => $negativeCriterion['url'])
            );
          }
        }
      }
      return $allCampaignNegativeCriteria;
    }

    function getCampaignNegativeKeywordCriteria() {
      global $soapClients;
      $someSoapClient = $soapClients->getCriterionClient();
      $soapParameters = "<getCampaignNegativeCriteria>
                            <campaignId>".$this->getId()."</campaignId>
                         </getCampaignNegativeCriteria>";
      $allCampaignNegativeCriteria =
        $someSoapClient->call("getCampaignNegativeCriteria", $soapParameters);
      $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
      if ($someSoapClient->fault) {
        pushFault($someSoapClient, $_SERVER['PHP_SELF'].":getCampaignNegativeKeywordCriteria()", $soapParameters);
        return false;
      }
      // if we have only one campaign negative criterion return a one-element array anyway
      if (isset($allCampaignNegativeCriteria['getCampaignNegativeCriteriaReturn'])) {
        $saveNegativeCriteria =
          $allCampaignNegativeCriteria['getCampaignNegativeCriteriaReturn'];
      }
      else {
        $saveNegativeCriteria = array();
      }
      if (isset($allCampaignNegativeCriteria['getCampaignNegativeCriteriaReturn']['id'])) {
        unset($allCampaignNegativeCriteria);
        $allCampaignNegativeCriteria = array();
        if (isset($saveNegativeCriteria['text'])) {
          $allCampaignNegativeCriteria[0] = array(
            'text' => $saveNegativeCriteria['text'],
            'type' => $saveNegativeCriteria['type']
          );
        }
      }
      else {
        unset($allCampaignNegativeCriteria);
        $allCampaignNegativeCriteria = array();
        foreach ($saveNegativeCriteria as $negativeCriterion) {
          if (isset($negativeCriterion['text'])) {
            array_push(
              $allCampaignNegativeCriteria,
              array(
                'text' => $negativeCriterion['text'],
                'type' => $negativeCriterion['type']
              )
            );
          }
        }
      }
      return $allCampaignNegativeCriteria;
    }

    // set functions
    function setCampaignNegativeWebsiteCriteria($newCampaignNegativeCriteria) {
      global $soapClients;
      $someSoapClient = $soapClients->getCriterionClient();

      // we need to save potentially existing negative KEYWORD criteria
      // as they will be deleted when we set new negative WEBSITE criteria
      $saveNegativeKeywordCriteria = array();
      $saveNegativeKeywordCriteria = $this->getCampaignNegativeKeywordCriteria();
      $saveNegativeKeywordCriteriaXml = "";
      if (!empty($saveNegativeKeywordCriteria)) {
        foreach ($saveNegativeKeywordCriteria as $saveNegativeKeywordCriterion) {
          $saveNegativeKeywordCriteriaXml .= "<criteria>
                                                <criterionType>Keyword</criterionType>
                                                <id>0</id>
                                                <adGroupId>0</adGroupId>
                                                <language></language>
                                                <maxCpc>0</maxCpc>
                                                <negative>true</negative>
                                                <type>".trim($saveNegativeKeywordCriterion['type'])."</type>
                                                <text>".
                                                  trim($saveNegativeKeywordCriterion['text'])."
                                                </text>
                                              </criteria>";
        }
      }
      // end of saving negative KEYWORD criteria

      // expecting array('url' => "none.de", 'url' => "of.com", 'url' => "these.net")
      $newCampaignNegativeCriteriaXml = "";
      $soapParameters = "<setCampaignNegativeCriteria>
                           <campaignId>".$this->getId()."</campaignId>";
      if (!empty($newCampaignNegativeCriteria)) {
        foreach ($newCampaignNegativeCriteria as $newCampaignNegativeCriterion) {
          // update google servers
          $newCampaignNegativeCriteriaXml .= "<criteria>
                                                <criterionType>Website</criterionType>
                                                <id>0</id>
                                                <adGroupId>0</adGroupId>
                                                <language></language>
                                                <maxCpm>0</maxCpm>
                                                <negative>true</negative>
                                                <url>".
                                                  trim($newCampaignNegativeCriterion['url'])."
                                                </url>
                                              </criteria>";
        }
      }
      // attach saved negative KEYWORD criteria
      $soapParameters .= $saveNegativeKeywordCriteriaXml;
      // close soap parameters
      $soapParameters .=
        $newCampaignNegativeCriteriaXml."</setCampaignNegativeCriteria>";
      $someSoapClient->call("setCampaignNegativeCriteria", $soapParameters);
      $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
      if ($someSoapClient->fault) {
        pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setCampaignNegativeWebsiteCriteria()", $soapParameters);
        return false;
      }
      return true;
    }

    function setCampaignNegativeKeywordCriteria($newCampaignNegativeCriteria) {
      global $soapClients;
      $someSoapClient = $soapClients->getCriterionClient();

      // we need to save potentially existing negative WEBSITE criteria
      // as they will be deleted when we set new negative KEYWORD criteria
      $saveNegativeWebsiteCriteria = array();
      $saveNegativeWebsiteCriteria = $this->getCampaignNegativeWebsiteCriteria();
      $saveNegativeWebsiteCriteriaXml = "";
      if (!empty($saveNegativeWebsiteCriteria)) {
        foreach ($saveNegativeWebsiteCriteria as $saveNegativeWebsiteCriterion) {
          $saveNegativeWebsiteCriteriaXml .= "<criteria>
                                                <criterionType>Website</criterionType>
                                                <id>0</id>
                                                <adGroupId>0</adGroupId>
                                                <language></language>
                                                <maxCpm>0</maxCpm>
                                                <negative>true</negative>
                                                <url>".
                                                  trim($saveNegativeWebsiteCriterion['url'])."
                                                </url>
                                              </criteria>";
        }
      }
      // end of saving negative WEBSITE criteria

      // expecting
      // array(
      //   array('text' => "none", 'type' => "Phrase"),
      //   array('text' => "of", 'type' => "Exact"),
      //   array('text' => "these", 'type' => "Broad")
      // )
      $newCampaignNegativeCriteriaXml = "";
      $soapParameters = "<setCampaignNegativeCriteria>
                           <campaignId>".$this->getId()."</campaignId>";
      if (!empty($newCampaignNegativeCriteria)) {
        foreach ($newCampaignNegativeCriteria as $newCampaignNegativeCriterion) {
          // update google servers
          $newCampaignNegativeCriteriaXml .= "<criteria>
                                                <criterionType>Keyword</criterionType>
                                                <id>0</id>
                                                <adGroupId>0</adGroupId>
                                                <language></language>
                                                <type>".trim($newCampaignNegativeCriterion['type'])."</type>
                                                <maxCpc>0</maxCpc>
                                                <negative>true</negative>
                                                <text>".trim($newCampaignNegativeCriterion['text'])."</text>
                                              </criteria>";
        }
      }
      // attach saved negative WEBSITE criteria
      $soapParameters .= $saveNegativeWebsiteCriteriaXml;
      // close soap parameters
      $soapParameters .= $newCampaignNegativeCriteriaXml."</setCampaignNegativeCriteria>";
      $someSoapClient->call("setCampaignNegativeCriteria", $soapParameters);
      $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
      if ($someSoapClient->fault) {
        pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setCampaignNegativeKeywordCriteria()", $soapParameters);
        return false;
      }
      return true;
    }

    function setName ($newName) {
      // update google servers
      global $soapClients;
      $someSoapClient = $soapClients->getCampaignClient();
      // danger! think in micros
      $soapParameters = "<updateCampaign>
                            <campaign>
                              <id>".$this->getId()."</id>
                              <name>".$newName."</name>
                            </campaign>
                          </updateCampaign>";
      // set the new name on the google servers
      $someSoapClient->call("updateCampaign", $soapParameters);
      $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
      if ($someSoapClient->fault) {
        pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setName()", $soapParameters);
        return false;
      }
      // update local object
      $this->name = $newName;
      return true;
    }

    function setEndDate ($newEndDate) {
      // update google servers
      global $soapClients;
      $someSoapClient = $soapClients->getCampaignClient();
      // danger! think in micros
      $soapParameters = "<updateCampaign>
                            <campaign>
                              <id>".$this->getId()."</id>
                              <endDay>".$newEndDate."</endDay>
                            </campaign>
                          </updateCampaign>";
      // set the new end date on the google servers
      $someSoapClient->call("updateCampaign", $soapParameters);
      $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
      if ($someSoapClient->fault) {
        pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setEndDate()", $soapParameters);
        return false;
      }
      // update local object
      $this->endDate = $newEndDate;
      return true;
    }

    function setAdScheduling ($newAdScheduling) {
      // update google servers
      global $soapClients;
      $someSoapClient = $soapClients->getCampaignClient();
      $intervalsXml = "";
      foreach ($newAdScheduling['intervals'] as $interval) {
        $intervalsXml .= "<intervals>
                            <multiplier>".$interval['multiplier']."</multiplier>
                            <day>".$interval['day']."</day>
                            <startHour>".$interval['startHour']."</startHour>
                            <startMinute>".$interval['startMinute']."</startMinute>
                            <endHour>".$interval['endHour']."</endHour>
                            <endMinute>".$interval['endMinute']."</endMinute>
                           </intervals>";
      }
      $soapParameters = "<updateCampaign>
                            <campaign>
                              <id>".$this->getId()."</id>
                              <schedule>
                                <status>".$newAdScheduling['status']."</status>
                                ".$intervalsXml."
                              </schedule>
                            </campaign>
                          </updateCampaign>";
      // set the new end date on the google servers
      $someSoapClient->call("updateCampaign", $soapParameters);
      $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
      if ($someSoapClient->fault) {
        pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setAdScheduling()", $soapParameters);
        return false;
      }
      // update local object
      $this->adScheduling = $newAdScheduling;
      return true;
    }

    function setBudgetOptimizerSettings ($newBudgetOptimizerSettings) {
      // update google servers
      global $soapClients;
      $someSoapClient = $soapClients->getCampaignClient();
      if ($newBudgetOptimizerSettings['enabled']) {
        $newBudgetOptimizerSettings['enabled'] = "true";
      }
      else {
        $newBudgetOptimizerSettings['enabled'] = "false";
      }
      if ($newBudgetOptimizerSettings['takeOnOptimizedBids']) {
        $newBudgetOptimizerSettings['takeOnOptimizedBids'] = "true";
      }
      else {
        $newBudgetOptimizerSettings['takeOnOptimizedBids'] = "false";
      }
      $soapParameters = "<updateCampaign>
                            <campaign>
                              <id>".$this->getId()."</id>
                              <budgetOptimizerSettings>
                                <bidCeiling>".
                                  $newBudgetOptimizerSettings['bidCeiling'] * EXCHANGE_RATE."
                                </bidCeiling>
                                <enabled>".
                                  $newBudgetOptimizerSettings['enabled']."
                                </enabled>
                                <takeOnOptimizedBids>".
                                  $newBudgetOptimizerSettings['takeOnOptimizedBids']."
                                </takeOnOptimizedBids>
                              </budgetOptimizerSettings>
                            </campaign>
                          </updateCampaign>";
      // set the new end date on the google servers
      $someSoapClient->call("updateCampaign", $soapParameters);
      $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
      if ($someSoapClient->fault) {
        pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setBudgetOptimizerSettings()", $soapParameters);
        return false;
      }
      // update local object
      $this->budgetOptimizerSettings = $newBudgetOptimizerSettings;
      return true;
    }

    function setDailyBudget ($newDailyBudget) {
      // update google servers
      global $soapClients;
      $someSoapClient = $soapClients->getCampaignClient();
      // think in micros
      $soapParameters = "<updateCampaign>
                            <campaign>
                              <id>".$this->getId()."</id>
                              <dailyBudget>".
                                ($newDailyBudget * EXCHANGE_RATE)."
                              </dailyBudget>
                            </campaign>
                          </updateCampaign>";
      // set the new name on the google servers
      $someSoapClient->call("updateCampaign", $soapParameters);
      $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
      if ($someSoapClient->fault) {
        pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setDailyBudget()", $soapParameters);
        return false;
      }
      // update local object
      $this->dailyBudget = $newDailyBudget;
      return true;
    }

    function setIsEnabledSeparateContentBids($newFlag) {
      // update google servers
      global $soapClients;
      $someSoapClient = $soapClients->getCampaignClient();
      // make sure bool is transformed to string correctly
      if ($newFlag) $newFlag="true"; else $newFlag="false";
      // danger! think in micros
      $soapParameters = "<updateCampaign>
                            <campaign>
                              <id>".$this->getId()."</id>
                              <enableSeparateContentBids>".
                                $newFlag."
                              </enableSeparateContentBids>
                            </campaign>
                         </updateCampaign>";
      // set the active in content flag on the google servers
      $someSoapClient->call("updateCampaign", $soapParameters);
      $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
      if ($someSoapClient->fault) {
        pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setIsEnabledSeparateContentBids()", $soapParameters);
        return false;
      }
      // update local object
      $this->isEnabledSeparateContentBids = convertBool($newFlag);
      return true;
    }

    function setNetworkTargeting($networkTargeting) {
      // update google servers
      global $soapClients;
      $someSoapClient = $soapClients->getCampaignClient();
      // danger! think in micros
      $soapParameters = "<updateCampaign>
                            <campaign>
                            <networkTargeting>";
      foreach($networkTargeting as $networkTarget) {
        $soapParameters .=   "<networkTypes>".trim($networkTarget)."</networkTypes>";
      }
      $soapParameters .=   "</networkTargeting>
                            <id>".$this->getId()."</id>
                          </campaign>
                        </updateCampaign>";
      // set the network targets on the google servers
      $someSoapClient->call("updateCampaign", $soapParameters);
      $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
      if ($someSoapClient->fault) {
        pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setNetworkTargeting()", $soapParameters);
        return false;
      }
      // update local object
      $this->networkTargeting = $networkTargeting;
      return true;
    }

    function setLanguages ($newLanguages) {
      // expecting languages as array("en", "de", "fr")
      // update google servers
      $newLanguagesXml = "";
      if (strcasecmp(trim($newLanguages[0]), "all") != 0) {
        foreach ($newLanguages as $newLanguage) {
          // build the new languages xml
          $newLanguagesXml .= "<languages>".trim($newLanguage)."</languages>";
        }
      }
      global $soapClients;
      $someSoapClient = $soapClients->getCampaignClient();
      // danger! think in micros
      $soapParameters = "<updateCampaign>
                            <campaign>
                              <id>".$this->getId()."</id>
                              <languageTargeting>".
                                $newLanguagesXml."
                              </languageTargeting>
                            </campaign>
                          </updateCampaign>";
      // set the new languages on the google servers
      $someSoapClient->call("updateCampaign", $soapParameters);
      $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
      if ($someSoapClient->fault) {
        pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setLanguages()", $soapParameters);
        return false;
      }
      // update local object
      if (strcasecmp(trim($newLanguages[0]), "all") != 0) {
        $this->languages = $newLanguages;
      }
      else {
        $this->languages = array();
      }
      return true;
    }

    function setGeoTargets ($newGeoTargets) {
      // expecting geoTargets as
      // array(
      //   ['countryTargets']['countries'] => array(),
      //   ['regionTargtes']['regions'] => array(),
      //   ['metroTargets']['metros'] => array(),
      //   ['cityTargets']['cities'] => array()
      //   ['targetAll'] => boolean
      // )
      $newGeoTargetsXml = "";
      $newGeoTargetsXml .= "<countryTargets>";
      foreach(@$newGeoTargets['countryTargets']['countries'] as $country) {
        $newGeoTargetsXml .= "<countries>".trim($country)."</countries>";
      }
      $newGeoTargetsXml .= "</countryTargets><regionTargets>";
      foreach(@$newGeoTargets['regionTargets']['regions'] as $region) {
        $newGeoTargetsXml .= "<regions>".trim($region)."</regions>";
      }
      $newGeoTargetsXml .= "</regionTargets><metroTargets>";
      foreach(@$newGeoTargets['metroTargets']['metros'] as $metro) {
        $newGeoTargetsXml .= "<metros>".trim($metro)."</metros>";
      }
      $newGeoTargetsXml .= "</metroTargets><cityTargets>";
      foreach(@$newGeoTargets['cityTargets']['cities'] as $city) {
        $newGeoTargetsXml .= "<cities>".trim($city)."</cities>";
      }
      $newGeoTargetsXml .= "</cityTargets><proximityTargets>";
      foreach(@$newGeoTargets['proximityTargets']['circles'] as $circle) {
        $newGeoTargetsXml .= "<circles>";
        $newGeoTargetsXml .= "<latitudeMicroDegrees>".$circle['latitudeMicroDegrees']."</latitudeMicroDegrees>";
        $geoTargetsXml .= "<longitudeMicroDegrees>".$circle['longitudeMicroDegrees']."</longitudeMicroDegrees>";
        $geoTargetsXml .= "<radiusMeters>".$circle['radiusMeters']."</radiusMeters>";
        $newGeoTargetsXml .= "</circles>";
      }
      $newGeoTargetsXml .= "</proximityTargets>";
      if (@$newGeoTargets['targetAll']) {
        $newGeoTargetsXml .= "<targetAll>true</targetAll>";
      }

      global $soapClients;
      $someSoapClient = $soapClients->getCampaignClient();
      // danger! think in micros
      $soapParameters = "<updateCampaign>
                            <campaign>
                              <id>".$this->getId()."</id>
                              <geoTargeting>".$newGeoTargetsXml."</geoTargeting>
                            </campaign>
                          </updateCampaign>";
      // set the new geo targets on the google servers
      $someSoapClient->call("updateCampaign", $soapParameters);
      $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
      if ($someSoapClient->fault) {
        pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setGeoTargets()", $soapParameters);
        return false;
      }
      // update local object
      $this->geoTargets = $newGeoTargets;
      return true;
    }

    function setIsEnabledOptimizedAdServing($newFlag) {
      // update google servers
      // make sure bool gets transformed to string correctly
      if ($newFlag) $newFlag = "true"; else $newFlag = "false";
      $soapParameters = "<setOptimizeAdServing>
                            <campaignId>".$this->getId()."</campaignId>
                            <enable>".$newFlag."</enable>
                         </setOptimizeAdServing>";
      global $soapClients;
      $someSoapClient = $soapClients->getCampaignClient();
      // set the new optimize adserving flag on the server
      $someSoapClient->call("setOptimizeAdServing", $soapParameters);
      $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
      if ($someSoapClient->fault) {
        pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setIsEnabledOptimizedAdServing()", $soapParameters);
        return false;
      }
      // update local object
      $this->isEnabledOptimizedAdServing = convertBool($newFlag);
      return true;
    }

    function setStatus($newStatus) {
      // update google servers
      global $soapClients;
      $someSoapClient = $soapClients->getCampaignClient();
      // danger! thinking in micros
      $soapParameters = "<updateCampaign>
                            <campaign>
                              <id>".$this->getId()."</id>
                              <status>".$newStatus."</status>
                            </campaign>
                         </updateCampaign>";
      // set the new status on the google servers
      $someSoapClient->call("updateCampaign", $soapParameters);
      $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
      if ($someSoapClient->fault) {
        pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setStatus()", $soapParameters);
        return false;
      }
      // update local object
      $this->status = $newStatus;
      return true;
    }
  }

  // creates a local campaign object we can play with
  function createCampaignObject($givenCampaignId) {
    global $soapClients;
    $someSoapClient = $soapClients->getCampaignClient();
    // prepare soap parameters
    $soapParameters = "<getCampaign>
                          <id>".$givenCampaignId."</id>
                       </getCampaign>";
    // execute soap call
    $someCampaign = $someSoapClient->call("getCampaign", $soapParameters);
    $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
    if ($someSoapClient->fault) {
      pushFault($someSoapClient, $_SERVER['PHP_SELF'].":createCampaignObject()", $soapParameters);
      return false;
    }

    // invalid ids are silently ignored. this is not what we want so put out a
    // warning and return without doing anything.
    if (empty($someCampaign)) {
      if (!SILENCE_STEALTH_MODE) echo "<br /><b>APIlity PHP library => Warning: </b>Invalid Campaign ID. No Campaign with the ID ".$givenCampaignId." found.";
      return null;
    }
    return receiveCampaign($someCampaign['getCampaignReturn'], 'createCampaignObject');
  }

  function getAllCampaigns() {
    global $soapClients;
    $someSoapClient = $soapClients->getCampaignClient();
    // just need a dummy argument here. don't tell this to the real world and
    // just keep it inside
    $soapParameters = "<getAllAdWordsCampaigns>
                          <dummy>0</dummy>
                       </getAllAdWordsCampaigns>";
    // query the google server for all campaigns
    $allCampaigns = array();
    $allCampaigns = $someSoapClient->call("getAllAdWordsCampaigns", $soapParameters);
    $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
    if ($someSoapClient->fault) {
      pushFault($someSoapClient, $_SERVER['PHP_SELF'].":getAllCampaigns()", $soapParameters);
      return false;
    }

    $allCampaigns = makeNumericArray($allCampaigns);

    $allCampaignObjects = array();
    // return only active or paused campaigns
    if (!isset($allCampaigns['getAllAdWordsCampaignsReturn'])) {
      return $allCampaignObjects;
    }
    foreach ($allCampaigns['getAllAdWordsCampaignsReturn'] as $campaign) {
      $campaignObject = receiveCampaign($campaign, 'getAllAdWordsCampaigns');
      if (isset($campaignObject)) {
        array_push($allCampaignObjects, $campaignObject);
      }
    }
    return $allCampaignObjects;
  }

  function getCampaignList($campaignIds) {
    global $soapClients;
    $someSoapClient = $soapClients->getCampaignClient();
    // just need a dummy argument here. don't tell this to the real world and
    // just keep it inside
    $soapParameters = "<getCampaignList>";
    foreach($campaignIds as $campaignId) {
      $soapParameters .= "<ids>".$campaignId."</ids>";
    }
    $soapParameters .= "</getCampaignList>";
    // query the google server for all campaigns
    $campaigns = array();
    $campaigns = $someSoapClient->call("getCampaignList", $soapParameters);
    $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
    if ($someSoapClient->fault) {
      pushFault($someSoapClient, $_SERVER['PHP_SELF'].":getCampaignList()", $soapParameters);
      return false;
    }

    $campaigns = makeNumericArray($campaigns);

    $campaignObjects = array();
    // return only active or paused campaigns
    if (!isset($campaigns['getCampaignListReturn'])) {
      return $campaignObjects;
    }
    foreach ($campaigns['getCampaignListReturn'] as $campaign) {
      $campaignObject = receiveCampaign($campaign, 'getCampaignList');
      if (isset($campaignObject)) {
        array_push($campaignObjects, $campaignObject);
      }
    }
    return $campaignObjects;
  }

  function removeCampaign(&$campaignObject) {
    // update google servers
    global $soapClients;
    $someSoapClient = $soapClients->getCampaignClient();
    // danger! think in micros
    $soapParameters = "<updateCampaign>
                          <campaign>
                            <id>".$campaignObject->getId()."</id>
                            <status>Deleted</status>
                          </campaign>
                       </updateCampaign>";
    // delete the campaign on the google servers
    $someSoapClient->call("updateCampaign", $soapParameters);
    $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
    if ($someSoapClient->fault) {
      pushFault($someSoapClient, $_SERVER['PHP_SELF'].":removeCampaign()", $soapParameters);
      return false;
    }
    // delete remote calling object
    $campaignObject = @$GLOBALS['campaignObject'];
    unset($campaignObject);
    return true;
  }

  function addCampaign(
    $name,
    $status,
    $startDate,
    $endDate,
    $dailyBudget,
    $networkTargeting,
    $languages,
    $newGeoTargets,
    $isEnabledSeparateContentBids = false,
    $adScheduling = false,
    $budgetOptimizerSettings = false
  ) {
    // update the google server
    global $soapClients;
    $someSoapClient = $soapClients->getCampaignClient();
    $languagesXml = "";
    $networkTargetingXml = "";
    // expecting array("target1", "target2")
    foreach($networkTargeting as $networkTarget)  {
      $networkTargetingXml .=
        "<networkTypes>".trim($networkTarget)."</networkTypes>";
    }

    $newGeoTargetsXml ="";
    $newGeoTargetsXml .= "<countryTargets>";
    foreach(@$newGeoTargets['countryTargets']['countries'] as $country) {
      $newGeoTargetsXml .= "<countries>".trim($country)."</countries>";
    }
    $newGeoTargetsXml .= "</countryTargets><regionTargets>";
    foreach(@$newGeoTargets['regionTargets']['regions'] as $region) {
      $newGeoTargetsXml .= "<regions>".trim($region)."</regions>";
    }
    $newGeoTargetsXml .= "</regionTargets><metroTargets>";
    foreach(@$newGeoTargets['metroTargets']['metros'] as $metro) {
      $newGeoTargetsXml .= "<metros>".trim($metro)."</metros>";
    }
    $newGeoTargetsXml .= "</metroTargets><cityTargets>";
    foreach(@$newGeoTargets['cityTargets']['cities'] as $city) {
      $newGeoTargetsXml .= "<cities>".trim($city)."</cities>";
    }
    $newGeoTargetsXml .= "</cityTargets><proximityTargets>";
    foreach(@$newGeoTargets['proximityTargets']['circles'] as $circle) {
      $newGeoTargetsXml .= "<circles>";
      $newGeoTargetsXml .= "<latitudeMicroDegrees>".$circle['latitudeMicroDegrees']."</latitudeMicroDegrees>";
      $geoTargetsXml .= "<longitudeMicroDegrees>".$circle['longitudeMicroDegrees']."</longitudeMicroDegrees>";
      $geoTargetsXml .= "<radiusMeters>".$circle['radiusMeters']."</radiusMeters>";
      $newGeoTargetsXml .= "</circles>";
    }
    $newGeoTargetsXml .= "</proximityTargets>";
    if (@$newGeoTargets['targetAll']) {
      $newGeoTargetsXml .= "<targetAll>true</targetAll>";
    }

    // expecting array("en", "fr", "gr")
    if (strcasecmp ($languages[0], "all") == 0) {
      $languagesXml = "";
    }
    else {
      foreach ($languages as $language) {
        $languagesXml .= "<languages>".trim($language)."</languages>";
      }
    }
    // make sure bool is transformed to string correctly
    if ($isEnabledSeparateContentBids) {
      $isEnabledSeparateContentBids = "true";
    }
    else {
      $isEnabledSeparateContentBids = "false";
    }
    // only send a start day if it is necessary
    if ($startDate) {
      $startDateXml = "<startDay>".$startDate."</startDay>";
    }
    else {
      $startDateXml = "";
    }

    $adSchedulingXml = "";
    if ($adScheduling) {
      $adSchedulingXml .=
        "<schedule><status>".$adScheduling['status']."</status>";
      foreach ($adScheduling['intervals'] as $interval) {
        $adSchedulingXml .= "<intervals>
                                <multiplier>".$interval['multiplier']."</multiplier>
                                <day>".$interval['day']."</day>
                                <startHour>".$interval['startHour']."</startHour>
                                <startMinute>".$interval['startMinute']."</startMinute>
                                <endHour>".$interval['endHour']."</endHour>
                                <endMinute>".$interval['endMinute']."</endMinute>
                             </intervals>";
      }
      $adSchedulingXml .= "</schedule>";
    }

    // think in micros
    $dailyBudget = $dailyBudget * EXCHANGE_RATE;

    $budgetOptimizerSettingsXml = "";
    if ($budgetOptimizerSettings) {
      if ($budgetOptimizerSettings['enabled']) {
        $budgetOptimizerSettings['enabled'] = "true";
      }
      else {
        $budgetOptimizerSettings['enabled'] = "false";
      }
      if ($budgetOptimizerSettings['takeOnOptimizedBids']) {
        $budgetOptimizerSettings['takeOnOptimizedBids'] = "true";
      }
      else {
        $budgetOptimizerSettings['takeOnOptimizedBids'] = "false";
      }
      $budgetOptimizerSettingsXml .= "<budgetOptimizerSettings>
                                        <bidCeiling>".
                                          $budgetOptimizerSettings['bidCeiling'] * EXCHANGE_RATE."
                                        </bidCeiling>
                                        <enabled>".
                                          $budgetOptimizerSettings['enabled']."
                                        </enabled>
                                        <takeOnOptimizedBids>".
                                          $budgetOptimizerSettings['takeOnOptimizedBids']."
                                        </takeOnOptimizedBids>
                                      </budgetOptimizerSettings>";
    }
    $soapParameters = "<addCampaign>
                          <campaign>
                            <dailyBudget>".$dailyBudget."</dailyBudget>
                            <name>".$name."</name>
                            <status>".$status."</status>".
                            $startDateXml."
                            <endDay>".$endDate."</endDay>
                            <networkTargeting>".
                              $networkTargetingXml."
                            </networkTargeting>
                            <languageTargeting>".
                              $languagesXml."
                            </languageTargeting>
                            <geoTargeting>".$newGeoTargetsXml."</geoTargeting>
                            <enableSeparateContentBids>".
                              $isEnabledSeparateContentBids."
                            </enableSeparateContentBids>".
                            $adSchedulingXml.
                            $budgetOptimizerSettingsXml."
                         </campaign>
                       </addCampaign>";
    // add the campaign to the google servers
    $someCampaign = $someSoapClient->call("addCampaign", $soapParameters);
    $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
    if ($someSoapClient->fault) {
      pushFault($someSoapClient, $_SERVER['PHP_SELF'].":addCampaign()", $soapParameters);
      return false;
    }

    return receiveCampaign($someCampaign['addCampaignReturn'], 'addCampaign');
  }

  function addCampaignList($campaigns) {
    // update the google server
    global $soapClients;
    $someSoapClient = $soapClients->getCampaignClient();
    $soapParameters = "<addCampaignList>";
    foreach ($campaigns as $campaign) {
      $newGeoTargetsXml = "";
      $languagesXml = "";
      $networkTargetingXml = "";
      $newGeoTargetsXml .= "<countryTargets>";
      foreach(@$campaign['geoTargets']['countryTargets']['countries'] as $country) {
        $newGeoTargetsXml .= "<countries>".trim($country)."</countries>";
      }
      $newGeoTargetsXml .= "</countryTargets><regionTargets>";
      foreach(@$campaign['geoTargets']['regionTargets']['regions'] as $region) {
        $newGeoTargetsXml .= "<regions>".trim($region)."</regions>";
      }
      $newGeoTargetsXml .= "</regionTargets><metroTargets>";
      foreach(@$campaign['geoTargets']['metroTargets']['metros'] as $metro) {
        $newGeoTargetsXml .= "<metros>".trim($metro)."</metros>";
      }
      $newGeoTargetsXml .= "</metroTargets><cityTargets>";
      foreach(@$campaign['geoTargets']['cityTargets']['cities'] as $city) {
        $newGeoTargetsXml .= "<cities>".trim($city)."</cities>";
      }
      $newGeoTargetsXml .= "</cityTargets><proximityTargets>";
      foreach(@$campaign['geoTargets']['proximityTargets']['circles'] as $circle) {
        $newGeoTargetsXml .= "<circles>";
        $newGeoTargetsXml .= "<latitudeMicroDegrees>".$circle['latitudeMicroDegrees']."</latitudeMicroDegrees>";
        $geoTargetsXml .= "<longitudeMicroDegrees>".$circle['longitudeMicroDegrees']."</longitudeMicroDegrees>";
        $geoTargetsXml .= "<radiusMeters>".$circle['radiusMeters']."</radiusMeters>";
        $newGeoTargetsXml .= "</circles>";
      }
      $newGeoTargetsXml .= "</proximityTargets>";
      if (@$campaign['targetAll']) {
        $newGeoTargetsXml .= "<targetAll>true</targetAll>";
      }

      // expecting array("en", "fr", "gr")
      if (strcasecmp($campaign['languages'][0], "all") != 0) {
        foreach ($campaign['languages'] as $language) {
          $languagesXml .= "<languages>".trim($language)."</languages>";
        }
      }
      foreach($campaign['networkTargeting'] as $networkTargeting) {
        $networkTargetingXml .=
          "<networkTypes>".trim($networkTargeting)."</networkTypes>";
      }

      $adSchedulingXml = "";
      if (@$campaign['adScheduling']) {
        $adSchedulingXml .=
          "<schedule><status>".$campaign['adScheduling']['status']."</status>";
        foreach ($campaign['adScheduling']['intervals'] as $interval) {
          $adSchedulingXml .= "<intervals>
                                  <multiplier>".$interval['multiplier']."</multiplier>
                                  <day>".$interval['day']."</day>
                                  <startHour>".$interval['startHour']."</startHour>
                                  <startMinute>".$interval['startMinute']."</startMinute>
                                  <endHour>".$interval['endHour']."</endHour>
                                  <endMinute>".$interval['endMinute']."</endMinute>
                               </intervals>";
        }
        $adSchedulingXml .= "</schedule>";
      }

      $budgetOptimizerSettingsXml = "";
      if (@$campaign['budgetOptimizerSettings']) {
        if ($campaign['budgetOptimizerSettings']['enabled']) {
          $campaign['budgetOptimizerSettings']['enabled'] = "true";
         }
         else {
           $campaign['budgetOptimizerSettings']['enabled'] = "false";
         }
        if ($campaign['budgetOptimizerSettings']['takeOnOptimizedBids']) {
          $campaign['budgetOptimizerSettings']['takeOnOptimizedBids'] = "true";
        }
        else {
          $campaign['budgetOptimizerSettings']['takeOnOptimizedBids'] = "false";
        }
        $budgetOptimizerSettingsXml .= "<budgetOptimizerSettings>
                                          <bidCeiling>".
                                            $campaign['budgetOptimizerSettings']['bidCeiling'] * EXCHANGE_RATE."
                                          </bidCeiling>
                                          <enabled>".
                                            $campaign['budgetOptimizerSettings']['enabled']."
                                          </enabled>
                                          <takeOnOptimizedBids>".
                                            $campaign['budgetOptimizerSettings']['takeOnOptimizedBids']."
                                          </takeOnOptimizedBids>
                                        </budgetOptimizerSettings>";
      }
      // make sure bool is transformed to string correctly
      if (@$campaign['isEnabledSeparateContentBids']) {
        $campaign['isEnabledSeparateContentBids'] = "true";
      }
      else {
        $campaign['isEnabledSeparateContentBids'] = "false";
      }
      // only send a start day if it is necessary
      if (@$campaign['startDate']) {
        $startDateXml = "<startDay>".$campaign['startDate']."</startDay>";
      }
      else {
        $startDateXml = "";
      }
      // think in micros
      $campaign['dailyBudget'] = $campaign['dailyBudget'] * EXCHANGE_RATE;
      $soapParameters .= "<campaigns>
                              <dailyBudget>".$campaign['dailyBudget']."</dailyBudget>
                              <name>".$campaign['name']."</name>
                              <status>".$campaign['status']."</status>".
                              $startDateXml."
                              <endDay>".$campaign['endDate']."</endDay>
                              <networkTargeting>".
                                $networkTargetingXml."
                              </networkTargeting>
                              <enableSeparateContentBids>".
                                $campaign['isEnabledSeparateContentBids']."
                              </enableSeparateContentBids>
                              <languageTargeting>".$languagesXml."</languageTargeting>
                              <geoTargeting>".$newGeoTargetsXml."</geoTargeting>".
                              $adSchedulingXml."
                          </campaigns>";
    }
    $soapParameters .= "</addCampaignList>";
    // add the campaigns to the google servers
    $someCampaigns = $someSoapClient->call("addCampaignList", $soapParameters);
    $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
    if ($someSoapClient->fault) {
      pushFault($someSoapClient, $_SERVER['PHP_SELF'].":addCampaignList()", $soapParameters);
      return false;
    }

    $someCampaigns = makeNumericArray($someCampaigns);

    // create local objects
    $campaignObjects = array();
    foreach($someCampaigns['addCampaignListReturn'] as $someCampaign) {
      $campaignObject = receiveCampaign($someCampaign, 'addCampaignList');
      if (isset($campaignObject)) {
        array_push($campaignObjects, $campaignObject);
      }
    }
    return $campaignObjects;
  }

  function addCampaignsOneByOne($campaigns) {
    // this is just a wrapper to the addCampaign function
    $campaignObjects = array();

    foreach ($campaigns as $campaign) {
      $campaignObject = addCampaign(
        $campaign['name'],
        $campaign['status'],
        $campaign['startDate'],
        $campaign['endDate'],
        $campaign['dailyBudget'],
        $campaign['networkTargeting'],
        $campaign['languages'],
        $campaign['geoTargets'],
        @$campaign['isEnabledSeparateContentBids'],
        @$campaign['adScheduling'],
        @$campaign['budgetOptimizerSettings']
      );
      array_push($campaignObjects, $campaignObject);
    }
    return $campaignObjects;
  }

  function getExplicitCampaignNegativeWebsiteCriteria($id) {
    global $soapClients;
    $someSoapClient = $soapClients->getCriterionClient();
    $soapParameters = "<getCampaignNegativeCriteria>
                          <campaignId>".$id."</campaignId>
                       </getCampaignNegativeCriteria>";
    $allCampaignNegativeCriteria =
      $someSoapClient->call("getCampaignNegativeCriteria", $soapParameters);
    $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
    if ($someSoapClient->fault) {
      pushFault($someSoapClient, $_SERVER['PHP_SELF'].":getExplicitCampaignNegativeWebsiteCriteria()", $soapParameters);
      return false;
    }
    // if we have only one campaign negative criterion return a one-element array anyway
    if (isset($allCampaignNegativeCriteria['getCampaignNegativeCriteriaReturn'])) {
      $saveNegativeCriteria = $allCampaignNegativeCriteria['getCampaignNegativeCriteriaReturn'];
    }
    else {
      $saveNegativeCriteria = array();
    }
    if (isset($allCampaignNegativeCriteria['getCampaignNegativeCriteriaReturn']['id'])) {
      unset($allCampaignNegativeCriteria);
      $allCampaignNegativeCriteria = array();
      if (isset($saveNegativeCriteria['url'])) {
        $allCampaignNegativeCriteria[0] = array('url' => $saveNegativeCriteria['url']);
      }
    }
    else {
      unset($allCampaignNegativeCriteria);
      $allCampaignNegativeCriteria = array();
      foreach ($saveNegativeCriteria as $negativeCriterion) {
        if (isset($negativeCriterion['url'])) {
          array_push($allCampaignNegativeCriteria, array('url' => $negativeCriterion['url']));
        }
      }
    }
    return $allCampaignNegativeCriteria;
  }

  function getExplicitCampaignNegativeKeywordCriteria($id) {
    global $soapClients;
    $someSoapClient = $soapClients->getCriterionClient();
    $soapParameters = "<getCampaignNegativeCriteria>
                          <campaignId>".$id."</campaignId>
                       </getCampaignNegativeCriteria>";
    $allCampaignNegativeCriteria = $someSoapClient->call("getCampaignNegativeCriteria", $soapParameters);
    $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
    if ($someSoapClient->fault) {
      pushFault($someSoapClient, $_SERVER['PHP_SELF'].":getExplicitCampaignNegativeKeywordCriteria()", $soapParameters);
      return false;
    }
    // if we have only one campaign negative criterion return a one-element array anyway
    if (isset($allCampaignNegativeCriteria['getCampaignNegativeCriteriaReturn'])) {
      $saveNegativeCriteria = $allCampaignNegativeCriteria['getCampaignNegativeCriteriaReturn'];
    }
    else {
      $saveNegativeCriteria = array();
    }
    if (isset($allCampaignNegativeCriteria['getCampaignNegativeCriteriaReturn']['id'])) {
      unset($allCampaignNegativeCriteria);
      $allCampaignNegativeCriteria = array();
      if (isset($saveNegativeCriteria['text'])) {
        $allCampaignNegativeCriteria[0] = array(
          'text' => $saveNegativeCriteria['text'],
          'type' => $saveNegativeCriteria['type']
        );
      }
    }
    else {
      unset($allCampaignNegativeCriteria);
      $allCampaignNegativeCriteria = array();
      foreach ($saveNegativeCriteria as $negativeCriterion) {
        if (isset($negativeCriterion['text'])) {
          array_push(
            $allCampaignNegativeCriteria,
            array(
              'text' => $negativeCriterion['text'],
              'type' => $negativeCriterion['type']
            )
          );
        }
      }
    }
    return $allCampaignNegativeCriteria;
  }

  function receiveCampaign($someCampaign, $apiOperation) {
    if (($someCampaign['status'] == "Active") ||
        ($someCampaign['status'] == "Paused") ||
        ($someCampaign['status'] == "Pending") ||
        ($someCampaign['status'] == "Suspended") ||
        ($someCampaign['status'] == "Ended")
    ) {
      // populate class attributes
      $name = $someCampaign['name'];
      $id = $someCampaign['id'];
      $status = $someCampaign['status'];
      $startDate = $someCampaign['startDay'];
      $endDate = $someCampaign['endDay'];
      // think in currency units
      $dailyBudget =
        @$someCampaign['dailyBudget'] / EXCHANGE_RATE;
      $budgetOptimizerSettings = array();
      $budgetOptimizerSettings['bidCeiling'] =
        @$someCampaign['budgetOptimizerSettings']['bidCeiling'] / EXCHANGE_RATE;
      $budgetOptimizerSettings['enabled'] =
        @$someCampaign['budgetOptimizerSettings']['enabled'];
      $networkTargeting =
        @$someCampaign['networkTargeting']['networkTypes'];
      $languages =
        @$someCampaign['languageTargeting']['languages'];
      // determine the geoTargets
      $geoTargets = array(
        'countryTargets' => array('countries' => array()),
        'regionTargets' => array('regions' => array()),
        'metroTargets' => array('metros' => array()),
        'cityTargets' => array('cities' => array()),
        'proximityTargets' => array('circles' => array()),
        'targetAll' => false
      );
      if (@is_array($someCampaign['geoTargeting']['countryTargets']['countries'])) {
        foreach ($someCampaign['geoTargeting']['countryTargets']['countries'] as $country) {
          array_push($geoTargets['countryTargets']['countries'], $country);
        }
      }
      else if (@isset($someCampaign['geoTargeting']['countryTargets']['countries'])) {
        array_push($geoTargets['countryTargets']['countries'], $someCampaign['geoTargeting']['countryTargets']['countries']);
      }

      if (@is_array($someCampaign['geoTargeting']['regionTargets']['regions'])) {
        foreach ($someCampaign['geoTargeting']['regionTargets']['regions'] as $region) {
          array_push($geoTargets['regionTargets']['regions'], $region);
        }
      }
      else if (@isset($someCampaign['geoTargeting']['regionTargets']['regions'])) {
        array_push($geoTargets['regionTargets']['regions'], $someCampaign['geoTargeting']['regionTargets']['regions']);
      }

      if (@is_array($someCampaign['geoTargeting']['metroTargets']['metros'])) {
        foreach ($someCampaign['geoTargeting']['metroTargets']['metros'] as $metro) {
          array_push($geoTargets['metroTargets']['metros'], $metro);
        }
      }
      else if (@isset($someCampaign['geoTargeting']['metroTargets']['metros'])) {
        array_push($geoTargets['metroTargets']['metros'], $someCampaign['geoTargeting']['metroTargets']['metros']);
      }

      if (@is_array($someCampaign['geoTargeting']['cityTargets']['cities'])) {
        foreach ($someCampaign['geoTargeting']['cityTargets']['cities'] as $city) {
          array_push($geoTargets['cityTargets']['cities'], $city);
        }
      }
      else if (@isset($someCampaign['geoTargeting']['cityTargets']['cities'])) {
        array_push($geoTargets['cityTargets']['cities'], $someCampaign['geoTargeting']['cityTargets']['cities']);
      }

      if (@is_array($someCampaign['geoTargeting']['proximityTargets']['circles'])) {
        foreach ($someCampaign['geoTargeting']['proximityTargets']['circles'] as $circle) {
          array_push($geoTargets['proximityTargets']['circles'], $circle);
        }
      }
      else if (@isset($someCampaign['geoTargeting']['proximityTargets']['circles'])) {
        array_push($geoTargets['proximityTargets']['circles'], $someCampaign['geoTargeting']['proximityTargets']['circles']);
      }

      if (@isset($someCampaign['geoTargeting']['targetAll'])) {
        $geoTargets['targetAll'] = $someCampaign['geoTargeting']['targetAll'];
      }

      $adScheduling = array();
      if (@isset($someCampaign['schedule']['status'])) {
        $adScheduling['status'] = $someCampaign['schedule']['status'];
      }
      if ( strcasecmp($someCampaign['schedule']['status'], "Disabled") != 0 ) {
        if (!@isset($someCampaign['schedule']['intervals']['day'])) {
          $adScheduling['intervals'] = array();
          foreach ($someCampaign['schedule']['intervals'] as $interval) {
            array_push($adScheduling['intervals'], $interval);
          }
        }
        else if (@isset($someCampaign['schedule']['intervals']['day'])) {
          $adScheduling['intervals'] = array();
          array_push($adScheduling['intervals'], $someCampaign['schedule']['intervals']);
        }
      }

      if (IS_ENABLED_OPTIMIZED_AD_SERVING_ATTRIBUTE) {
        // isEnabledOptimizedAdServing?
        // this is not an object attribute but we make it be one. as we can change
        // it we want to see its value
        $soapParameters = "<getOptimizeAdServing>
                              <campaignId>".$id."</campaignId>
                           </getOptimizeAdServing>";
        // query the google servers whether the campaign is optimize adserving
        $isEnabledOptimizedAdServing = $someSoapClient->call("getOptimizeAdServing", $soapParameters);
        $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
        if ($someSoapClient->fault) {
          pushFault($someSoapClient, $_SERVER['PHP_SELF'].":".$apiOperation."()", $soapParameters);
          return false;
        }
        $isEnabledOptimizedAdServing = @$isEnabledOptimizedAdServing['getOptimizeAdServingReturn'];
      }
      else {
        $isEnabledOptimizedAdServing = NULL;
      }
      $isEnabledSeparateContentBids =
        @$someCampaign['enableSeparateContentBids'];

      $campaignNegativeKeywordCriteria = null;
      $campaignNegativeWebsiteCriteria = null;
      if (INCLUDE_CAMPAIGN_NEGATIVE_CRITERIA) {
        $campaignNegativeKeywordCriteria =
          getExplicitCampaignNegativeKeywordCriteria($id);
        $campaignNegativeWebsiteCriteria =
          getExplicitCampaignNegativeWebsiteCriteria($id);
      }
      // end of populate class attributes

      // now we can create the object
      $campaignObject = new APIlityCampaign (
        $name,
        $id,
        $status,
        $startDate,
        $endDate,
        $dailyBudget,
        $networkTargeting,
        $languages,
        $geoTargets,
        $isEnabledOptimizedAdServing,
        $isEnabledSeparateContentBids,
        $campaignNegativeKeywordCriteria,
        $campaignNegativeWebsiteCriteria,
        $adScheduling,
        $budgetOptimizerSettings
      );
      return $campaignObject;
    }
  }
?>