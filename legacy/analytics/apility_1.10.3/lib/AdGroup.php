<?php
	class APIlityAdGroup {
		// class attributes
		var $maxCpc;
		var $maxCpm;
		var $maxContentCpc;
		var $proxyMaxCpc;
	  var $name;
		var $id;
		var $belongsToCampaignId;
	  var $status;

		// constructor
		function APIlityAdGroup (
		  $name,
		  $id,
		  $belongsToCampaignId,
		  $maxCpc,
		  $maxCpm,
		  $maxContentCpc,
		  $proxyMaxCpc,
		  $status
		) {
			$this->name = $name;
			$this->id = $id;
			$this->belongsToCampaignId = $belongsToCampaignId;
			$this->maxCpc = $maxCpc;
			$this->maxCpm = $maxCpm;
			$this->maxContentCpc = $maxContentCpc;
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
	<maxCpc>".$this->getMaxCpc()."</maxCpc>
	<maxCpm>".$this->getMaxCpm()."</maxCpm>
	<maxContentCpc>".$this->getMaxContentCpc()."</maxContentCpc>
	<proxyMaxCpc>".$this->getProxyMaxCpc()."</proxyMaxCpc>
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

		function getMaxCpc() {
			return $this->maxCpc;
		}

		function getProxyMaxCpc() {
			return $this->proxyMaxCpc;
		}

		function getMaxContentCpc() {
			return $this->maxContentCpc;
		}

		function getMaxCpm() {
			return $this->maxCpm;
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
												'maxCpc'=>$this->getMaxCpc(),
												'maxCpm'=>$this->getMaxCpm(),
												'maxContentCpc'=>$this->getMaxContentCpc(),
                        'proxyMaxCpc'=>$this->getProxyMaxCpc(),
												'status'=>$this->getStatus()
												);
			return $adGroupData;
		}

		function getAdGroupStats($startDate, $endDate) {
			global $soapClients;
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
  		global $soapClients;
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
  		global $soapClients;
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
  	 	global $soapClients;
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
			global $soapClients;
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

		function setMaxCpc ($newMaxCpc) {
			// update the google servers
			global $soapClients;
			$someSoapClient = $soapClients->getAdGroupClient();
			// think in micros
			$soapParameters = "<updateAdGroup>
														<changedData>
															<campaignId>".
															  $this->getBelongsToCampaignId()."
															</campaignId>
															<id>".$this->getId()."</id>
															<maxCpc>".($newMaxCpc * EXCHANGE_RATE)."</maxCpc>
														</changedData>
												 </updateAdGroup>";
			// set the new maxcpc on the google servers
			$someSoapClient->call("updateAdGroup", $soapParameters);
			$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));

			if ($someSoapClient->fault) {
		  	pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setMaxCpc()", $soapParameters);
		    return false;
			}
			// update local object
			$this->maxCpc = $newMaxCpc;
			return true;
		}

		function setMaxContentCpc ($newMaxContentCpc) {
			// update the google servers
			global $soapClients;
			$someSoapClient = $soapClients->getAdGroupClient();
			// think in micros
			$soapParameters = "<updateAdGroup>
														<changedData>
															<campaignId>".
															  $this->getBelongsToCampaignId()."
															</campaignId>
															<id>".$this->getId()."</id>
															<maxContentCpc>".
															  ($newMaxContentCpc * EXCHANGE_RATE)."
															</maxContentCpc>
														</changedData>
												 </updateAdGroup>";
			// set the new maxcpc on the google servers
			$someSoapClient->call("updateAdGroup", $soapParameters);
			$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));

			if ($someSoapClient->fault) {
		  	pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setMaxContentCpc()", $soapParameters);
		    return false;
			}
			// update local object
			$this->maxContentCpc = $newMaxContentCpc;
			return true;
		}

		function setMaxCpm ($newMaxCpm) {
			// update the google servers
			global $soapClients;
			$someSoapClient = $soapClients->getAdGroupClient();
			// think in micros
			$soapParameters = "<updateAdGroup>
														<changedData>
															<campaignId>".
															  $this->getBelongsToCampaignId()."
															</campaignId>
															<id>".$this->getId()."</id>
															<maxCpm>".($newMaxCpm * EXCHANGE_RATE)."</maxCpm>
														</changedData>
												 </updateAdGroup>";
			// set the new maxcpc on the google servers
			$someSoapClient->call("updateAdGroup", $soapParameters);
			$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));

			if ($someSoapClient->fault) {
		  	pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setMaxCpm()", $soapParameters);
		    return false;
			}
			// update local object
			$this->maxCpm = $newMaxCpm;
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
			global $soapClients;
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

	function addAdGroup(
	  $name,
	  $campaignId,
	  $status,
	  $maxCpc,
	  $maxCpm,
	  $maxContentCpc = 0
	) {
		// update the google servers
		global $soapClients;
		$someSoapClient = $soapClients->getAdGroupClient();
		// google server calls active adgroups enabled adgroups so rename if
		// necessary
		if (strtolower($status) == "active") $status = "Enabled";
		// think in micros
		// danger: we need to have maxCpc XOR maxCpm, so we need to set either
		// maxCpc or maxCpm to a value different from zero
		if ($maxCpc > 0) {
			$maxCpc = $maxCpc * EXCHANGE_RATE;
			$maxCpWhateverXml = "<maxCpc>".$maxCpc."</maxCpc>";
		}
		else {
			$maxCpm = $maxCpm * EXCHANGE_RATE;
			$maxCpWhateverXml = "<maxCpm>".$maxCpm."</maxCpm>";
		}
		$maxContentCpc = $maxContentCpc * EXCHANGE_RATE;
		if ($maxContentCpc > 0) $maxContentCpcXml = "<maxContentCpc>".$maxContentCpc."</maxContentCpc>"; else $maxContentCpcXml = "";

		$soapParameters = "<addAdGroup>
													<campaignId>".$campaignId."</campaignId>
													<newData>
														<status>".$status."</status>
														<name>".$name."</name>"
														.$maxCpWhateverXml.""
													  .$maxContentCpcXml."
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
		global $soapClients;
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
			// danger: we need to have maxCpc XOR maxCpm, so we need to set either
			// maxCpc or maxCpm to a value different from zero
			if ($adgroup['maxCpc'] > 0) {
				$adgroup['maxCpc'] = $adgroup['maxCpc'] * EXCHANGE_RATE;
				$maxCpWhateverXml = "<maxCpc>".$adgroup['maxCpc']."</maxCpc>";
			}
			else {
				$adgroup['maxCpm'] = $adgroup['maxCpm'] * EXCHANGE_RATE;
				$maxCpWhateverXml = "<maxCpm>".$adgroup['maxCpm']."</maxCpm>";
			}
			$adgroup['maxContentCpc'] = $adgroup['maxContentCpc'] * EXCHANGE_RATE;
			if ($adgroup['maxContentCpc'] > 0) {
			  $maxContentCpcXml =
			    "<maxContentCpc>".$adgroup['maxContentCpc']."</maxContentCpc>";
			}
			else {
			  $maxContentCpcXml = "";
			}
			$soapParameters .= "<newData>
															<status>".$adgroup['status']."</status>
														<name>".$adgroup['name']."</name>"
														.$maxCpWhateverXml.""
													  .$maxContentCpcXml."
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
			  $adGroup['maxCpc'],
			  $adGroup['maxCpm'],
			  $adGroup['maxContentCpc']
			);
			array_push($adGroupObjects, $adGroupObject);
		}
		return $adGroupObjects;
	}

	function removeAdGroup(&$adGroupObject) {
		// update the google servers
		global $soapClients;
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
		global $soapClients;
		$someSoapClient = $soapClients->getAdGroupClient();
		$soapParameters = "<getAllAdGroups><id>".$campaignId."</id></getAllAdGroups>";
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
		global $soapClients;
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
		global $soapClients;
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
  		if (@isset($someAdGroup['maxCpc'])) {
  		  $maxCpc = $someAdGroup['maxCpc'] / EXCHANGE_RATE;
  		}
  		else {
  		  $maxCpc = NULL;
  		}
  		if (@isset($someAdGroup['maxCpm'])) {
  		  $maxCpm = $someAdGroup['maxCpm'] / EXCHANGE_RATE;
  		}
  		else {
  		  $maxCpm = NULL;
  		}
  		if (@isset($someAdGroup['maxContentCpc'])) {
  		  $maxContentCpc = $someAdGroup['maxContentCpc'] / EXCHANGE_RATE;
  		}
  		else {
  		  $maxContentCpc = NULL;
  		}
      if (@isset($someAdGroup['proxyMaxCpc'])) {
        $proxyMaxCpc = $someAdGroup['proxyMaxCpc'] / EXCHANGE_RATE;
      }
      else {
        $proxyMaxCpc = NULL;
      }
  		$adGroupObject = new APIlityAdGroup(
  		  $someAdGroup['name'],
  		  $someAdGroup['id'],
  		  $someAdGroup['campaignId'],
  		  $maxCpc,
  		  $maxCpm,
  		  $maxContentCpc,
  		  $proxyMaxCpc,
  		  $someAdGroup['status']
  		);
  		return $adGroupObject;
  	}
  }
?>
