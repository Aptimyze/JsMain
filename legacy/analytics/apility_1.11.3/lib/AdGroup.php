<?php

  // import this file only when we are not in OO mode
  // however, if we are in OO mode, the import happens in APIlityUser.php  
  if (!IS_ENABLED_OO_MODE) {
    require_once('AdGroup.inc');  
  }

  class APIlityAdGroup {
    // class attributes
    var $keywordMaxCpc;
    var $siteMaxCpm;
    var $keywordContentMaxCpc;
    var $proxyKeywordMaxCpc;
    var $name;
    var $id;
    var $belongsToCampaignId;
    var $status;

    // constructor
    function APIlityAdGroup (
      $name,
      $id,
      $belongsToCampaignId,
      $keywordMaxCpc,
      $siteMaxCpm,
      $keywordContentMaxCpc,
      $proxyKeywordMaxCpc,
      $status
    ) {
      $this->name = $name;
      $this->id = $id;
      $this->belongsToCampaignId = $belongsToCampaignId;
      $this->keywordMaxCpc = $keywordMaxCpc;
      $this->siteMaxCpm = $siteMaxCpm;
      $this->keywordContentMaxCpc = $keywordContentMaxCpc;
      // google server uses "enabled" as adgroup status. we overcome this
      // inconsistency and call all "active" states just active. anyway we catch
      // user input when someone passes "enabled"
      if (strtolower($status) == "enabled") $status = "Active";
      $this->status = $status;
    }

    // XML output
    function toXml() {
      $xml = "<AdGroup>
  <name>".$this->getName()."</name>
  <id>".$this->getId()."</id>
  <status>".$this->getStatus()."</status>
  <belongsToCampaignId>".$this->getBelongsToCampaignId()."</belongsToCampaignId>
  <keywordMaxCpc>".$this->getKeywordMaxCpc()."</keywordMaxCpc>
  <siteMaxCpm>".$this->getSiteMaxCpm()."</siteMaxCpm>
  <keywordContentMaxCpc>".$this->getKeywordContentMaxCpc()."</keywordContentMaxCpc>
  <proxyKeywordMaxCpc>".$this->getProxyKeywordMaxCpc()."</proxyKeywordMaxCpc>
</AdGroup>";
      return $xml;
    }

    // get functions
    function getName() {
      return $this->name;
    }

    function getId() {
      return $this->id;
    }

    function getBelongsToCampaignId() {
      return $this->belongsToCampaignId;
    }

    function getKeywordMaxCpc() {
      return $this->keywordMaxCpc;
    }

    function getProxyKeywordMaxCpc() {
      return $this->proxyKeywordMaxCpc;
    }

    function getKeywordContentMaxCpc() {
      return $this->keywordContentMaxCpc;
    }

    function getSiteMaxCpm() {
      return $this->siteMaxCpm;
    }

    function getStatus() {
      return $this->status;
    }

    function getEstimate() {
      // this function is located in TrafficEstimate.php
      return getAdGroupEstimate($this);
    }

    // report function
    function getAdGroupData() {
      $adGroupData = array(
                        'name'=>$this->getName(),
                        'id'=>$this->getId(),
                        'belongsToCampaignId'=>$this->getBelongsToCampaignId(),
                        'keywordMaxCpc'=>$this->getKeywordMaxCpc(),
                        'siteMaxCpm'=>$this->getSiteMaxCpm(),
                        'keywordContentMaxCpc'=>$this->getKeywordContentMaxCpc(),
                        'proxyKeywordMaxCpc'=>$this->getProxyKeywordMaxCpc(),
                        'status'=>$this->getStatus()
                        );
      return $adGroupData;
    }

    function getAdGroupStats($startDate, $endDate) {
      $soapClients = &APIlityClients::getClients();
      $someSoapClient = $soapClients->getAdGroupClient();
      $soapParameters = "<getAdGroupStats>
                            <campaignId>".
                              $this->getBelongsToCampaignId()."
                            </campaignId>
                            <adGroupIds>".$this->getId()."</adGroupIds>
                            <startDay>".$startDate."</startDay>
                            <endDay>".$endDate."</endDay>
                         </getAdGroupStats>";
      // query the google servers for the adgroup stats
      $adGroupStats = $someSoapClient->call("getAdGroupStats", $soapParameters);
      $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
      if ($someSoapClient->fault) {
        pushFault($someSoapClient, $_SERVER['PHP_SELF'].":getAdGroupStats()", $soapParameters);
        return false;
      }
      // add the adgroup name to the stats for the sake of clarity
      $adGroupStats['getAdGroupStatsReturn']['name'] = $this->getName();
      // think in currency units here
      $adGroupStats['getAdGroupStatsReturn']['cost'] =
        $adGroupStats['getAdGroupStatsReturn']['cost'] / EXCHANGE_RATE;
      return $adGroupStats['getAdGroupStatsReturn'];
    }

    function getAllAds() {
      $soapClients = &APIlityClients::getClients();
      $someSoapClient = $soapClients->getAdClient();
      $soapParameters = "<getAllAds>
                           <adGroupIds>".$this->getId()."</adGroupIds>
                         </getAllAds>";
      // query the google servers for all ads
      $allAds = array();
      $allAds = $someSoapClient->call("getAllAds", $soapParameters);
      $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
      if ($someSoapClient->fault) {
        pushFault($someSoapClient, $_SERVER['PHP_SELF'].":getAllAds()", $soapParameters);
        return false;
      }

      // if only one ad then copy and create (one element) array of ads
      $allAds = makeNumericArray($allAds);

      $allAdObjects = array();
      if (!isset($allAds['getAllAdsReturn'])) {
        return $allAdObjects;
      }
      foreach($allAds['getAllAdsReturn'] as $ad) {
        $adObject = receiveAd($ad);
        if (isset($adObject)) {
          array_push($allAdObjects, $adObject);
        }
      }
      return $allAdObjects;
    }

    function getActiveAds() {
      $soapClients = &APIlityClients::getClients();
      $someSoapClient = $soapClients->getAdClient();      
      $soapParameters = "<getActiveAds>
                           <adGroupIds>".$this->getId()."</adGroupIds>
                         </getActiveAds>";
      // query the google servers for all ads
      $allAds = array();
      $allAds = $someSoapClient->call("getActiveAds", $soapParameters);
      $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
      if ($someSoapClient->fault) {
        pushFault($someSoapClient, $_SERVER['PHP_SELF'].":getActiveAds()", $soapParameters);
        return false;
      }

      // if only one ad then copy and create (one element) array of ads
      $allAds = makeNumericArray($allAds);
      $allAdObjects = array();
      if (isset($allAds['getActiveAdsReturn'])) foreach($allAds['getActiveAdsReturn'] as $ad) {
        $adObject = receiveAd($ad);
        if (isset($adObject)) {
          array_push($allAdObjects, $adObject);
        }
      }
      return $allAdObjects;
    }

    function getAllCriteria() {
       $soapClients = &APIlityClients::getClients();
      $someSoapClient = $soapClients->getCriterionClient();
       $soapParameters = "<getAllCriteria>
                             <adGroupId>".$this->getId()."</adGroupId>
                          </getAllCriteria>";
       // query the google servers for all criteria
       $allCriteria = array();
       $allCriteria = $someSoapClient->call("getAllCriteria", $soapParameters);
       $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
      if ($someSoapClient->fault) {
        pushFault($someSoapClient, $_SERVER['PHP_SELF'].":getAllCriteria()", $soapParameters);
        return false;
      }

      // when we have only one criterion in the adgroup return a (one criterion
      // element) array  anyway
      $allCriteria = makeNumericArray($allCriteria);

      $allCriterionObjects = array();
      if (!isset($allCriteria['getAllCriteriaReturn'])) {
        return $allCriterionObjects;
      }
      foreach ($allCriteria['getAllCriteriaReturn'] as $criterion) {
        $criterionObject = receiveCriterion($criterion);
        if (isset($criterionObject)) {
          array_push($allCriterionObjects, $criterionObject);
        }
      }

      return $allCriterionObjects;
    }

    // set functions
    function setName ($newName) {
      // update the google servers
      $soapClients = &APIlityClients::getClients();
      $someSoapClient = $soapClients->getAdGroupClient();
      $soapParameters = "<updateAdGroup>
                            <changedData>
                              <campaignId>".
                                $this->getBelongsToCampaignId()."
                              </campaignId>
                              <id>".$this->getId()."</id>
                              <name>".$newName."</name>
                            </changedData>
                          </updateAdGroup>";
      // set the new name on the google servers
      $someSoapClient->call("updateAdGroup", $soapParameters);
      $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
      if ($someSoapClient->fault) {
        pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setName()", $soapParameters);
        return false;
      }
      // update local object
      $this->name = $newName;
      return true;
    }

    function setKeywordMaxCpc ($newKeywordMaxCpc) {
      // update the google servers
      $soapClients = &APIlityClients::getClients();
      $someSoapClient = $soapClients->getAdGroupClient();
      // think in micros
      $soapParameters = "<updateAdGroup>
                            <changedData>
                              <campaignId>".
                                $this->getBelongsToCampaignId()."
                              </campaignId>
                              <id>".$this->getId()."</id>
                              <keywordMaxCpc>".($newKeywordMaxCpc * EXCHANGE_RATE)."</keywordMaxCpc>
                            </changedData>
                         </updateAdGroup>";
      // set the new keywordMaxCpc on the google servers
      $someSoapClient->call("updateAdGroup", $soapParameters);
      $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));

      if ($someSoapClient->fault) {
        pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setKeywordMaxCpc()", $soapParameters);
        return false;
      }
      // update local object
      $this->keywordMaxCpc = $newKeywordMaxCpc;
      return true;
    }

    function setKeywordContentMaxCpc ($newKeywordContentMaxCpc) {
      // update the google servers
      $soapClients = &APIlityClients::getClients();
      $someSoapClient = $soapClients->getAdGroupClient();
      // think in micros
      $soapParameters = "<updateAdGroup>
                            <changedData>
                              <campaignId>".
                                $this->getBelongsToCampaignId()."
                              </campaignId>
                              <id>".$this->getId()."</id>
                              <keywordContentMaxCpc>".
                                ($newKeywordContentMaxCpc * EXCHANGE_RATE)."
                              </keywordContentMaxCpc>
                            </changedData>
                         </updateAdGroup>";
      // set the new keywordcontentcpc on the google servers
      $someSoapClient->call("updateAdGroup", $soapParameters);
      $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));

      if ($someSoapClient->fault) {
        pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setKeywordContentMaxCpc()", $soapParameters);
        return false;
      }
      // update local object
      $this->keywordContentMaxCpc = $newKeywordContentMaxCpc;
      return true;
    }

    function setSiteMaxCpm ($newSiteMaxCpm) {
      // update the google servers
      $soapClients = &APIlityClients::getClients();
      $someSoapClient = $soapClients->getAdGroupClient();
      // think in micros
      $soapParameters = "<updateAdGroup>
                            <changedData>
                              <campaignId>".
                                $this->getBelongsToCampaignId()."
                              </campaignId>
                              <id>".$this->getId()."</id>
                              <siteMaxCpm>".($newSiteMaxCpm * EXCHANGE_RATE)."</siteMaxCpm>
                            </changedData>
                         </updateAdGroup>";
      // set the new sitemaxcpm on the google servers
      $someSoapClient->call("updateAdGroup", $soapParameters);
      $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));

      if ($someSoapClient->fault) {
        pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setSiteMaxCpm()", $soapParameters);
        return false;
      }
      // update local object
      $this->siteMaxCpm = $newSiteMaxCpm;
      return true;
    }

    function matchMaxCpcsToMinCpcs() {
      $allCriteria = $this->getAllCriteria();
      if (!empty($allCriteria)) foreach($allCriteria as $criterion) {
        $criterion->matchMaxCpcToMinCpc();
      }
      return true;
    }

    function setStatus($newStatus) {
      // local object has status "active" and "paused"
      // google server object has "enabled" and "paused"
      // renaming all "enabled"-s to "active"-s for consistency reasons
      if (strtolower($newStatus) == "active") $newStatus = "Enabled";
      // update the google servers
      $soapClients = &APIlityClients::getClients();
      $someSoapClient = $soapClients->getAdGroupClient();
      $soapParameters = "<updateAdGroup>
                            <changedData>
                              <campaignId>".
                                $this->getBelongsToCampaignId()."
                              </campaignId>
                              <id>".$this->getId()."</id>
                              <status>".$newStatus."</status>
                            </changedData>
                         </updateAdGroup>";
      // set the new status on the google servers
      $someSoapClient->call("updateAdGroup", $soapParameters);
      $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
      if ($someSoapClient->fault) {
        pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setStatus()", $soapParameters);
        return false;
      }
      // update local object
      if (strtolower($newStatus) == "enabled") $newStatus = "Active";
      $this->status = $newStatus;
      return true;
    }
  }
?>