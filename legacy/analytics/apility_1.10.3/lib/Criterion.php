<?php
	/*
	 SUPER CLASS FOR CRITERION
	*/

	class APIlityCriterion {
		// class attributes
		var $belongsToAdGroupId;
		var $criterionType;
		var $destinationUrl;
		var $id;
		var $language;
		var $isNegative;
		var $isPaused;
		var $status;

		// constructor
		function APIlityCriterion(
		  $id,
		  $belongsToAdGroupId,
		  $criterionType,
		  $isNegative,
		  $isPaused,
		  $status,
		  $language,
		  $destinationUrl
		) {
			$this->id = $id;
			$this->belongsToAdGroupId = $belongsToAdGroupId;
			$this->criterionType = $criterionType;
			$this->status = $status;
			$this->language = $language;
			$this->destinationUrl = $destinationUrl;
			$this->isPaused = convertBool($isPaused);
			$this->isNegative = convertBool($isNegative);
		}

		// get functions
		function getBelongsToAdGroupId() {
			return $this->belongsToAdGroupId;
		}

		function getCriterionType() {
			return $this->criterionType;
		}

		function getDestinationUrl() {
			return $this->destinationUrl;
		}

		function getId() {
			return $this->id;
		}

		function getLanguage() {
			return $this->language;
		}

		function getIsNegative() {
			return $this->isNegative;
		}

		function getIsPaused() {
			return $this->isPaused;
		}

		function getStatus() {
			return $this->status;
		}

		function getCriterionStats($startDate, $endDate) {
			global $soapClients;
			$someSoapClient = $soapClients->getCriterionClient();
			$soapParameters = "<getCriterionStats>
														<adGroupId>".
														  $this->getBelongsToAdGroupId()."
														</adGroupId>
														<criterionIds>".$this->getId()."</criterionIds>
														<startDay>".$startDate."</startDay>
														<endDay>".$endDate."</endDay>
												 </getCriterionStats>";
			// get criterion stats from the google servers
			$criterionStats = $someSoapClient->call("getCriterionStats", $soapParameters);
			$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
			if ($someSoapClient->fault) {
		    pushFault($someSoapClient, $_SERVER['PHP_SELF'].":getCriterionStats()", $soapParameters);
		    return false;
			}
			// if we have a keyword criterion add keyword text to the returned stats
			// for the sake of clarity
			if (strcasecmp($this->criterionType, "Keyword") == 0)
			  $criterionStats['getCriterionStatsReturn']['text'] = $this->getText();
			// transform micros to currency units
			$criterionStats['getCriterionStatsReturn']['cost'] =
			  $criterionStats['getCriterionStatsReturn']['cost'] / EXCHANGE_RATE;
			return $criterionStats['getCriterionStatsReturn'];
		}

		// set functions
		function setLanguage($newLanguage) {
			// update the google servers
			global $soapClients;
			$someSoapClient = $soapClients->getCriterionClient();
			if (isset($this->text)) {
			  $criterionType = "Keyword";
			}
			else {
			  $criterionType = "Website";
			}
			if ($this->getIsNegative()) {
			  $isNegative = "true";
			}
			else {
			  $isNegative = "false";
			}
			// danger! think in micros
			$soapParameters = "<updateCriteria>
														<criteria>
															<id>".$this->getId()."</id>
															<adGroupId>".
															  $this->getBelongsToAdGroupId()."
															</adGroupId>
															<criterionType>".$criterionType."</criterionType>
															<negative>".$isNegative."</negative>
															<destinationUrl>".$this->getDestinationUrl()."</destinationUrl>
															<language>".$newLanguage."</language>
														</criteria>
													</updateCriteria>";
			// update the keyword on the google servers
			$someSoapClient->call("updateCriteria", $soapParameters);
			$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
			if ($someSoapClient->fault) {
		  	pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setLanguage()", $soapParameters);
		  	return false;
			}
			// update local object
			$this->language = $newLanguage;
			return true;
		}

		function setDestinationUrl($newDestinationUrl) {
			// update the google servers
			global $soapClients;
			$someSoapClient = $soapClients->getCriterionClient();
			if (isset($this->text)) {
			  $criterionType = "Keyword";
			}
			else {
			  $criterionType = "Website";
			}
			if ($this->getIsNegative()) {
			  $isNegative = "true";
			}
			else {
			  $isNegative = "false";
			}
			// danger! think in micros
			$soapParameters = "<updateCriteria>
														<criteria>
															<id>".$this->getId()."</id>
															<adGroupId>".
															  $this->getBelongsToAdGroupId()."
															</adGroupId>
															<criterionType>".$criterionType."</criterionType>
															<negative>".$isNegative."</negative>
															<destinationUrl>".$newDestinationUrl."</destinationUrl>
														</criteria>
													</updateCriteria>";
			// update the keyword on the google servers
			$someSoapClient->call("updateCriteria", $soapParameters);
			$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
			if ($someSoapClient->fault) {
		  	pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setDestinationUrl()", $soapParameters);
		  	return false;
			}
			// update local object
			$this->destinationUrl = $newDestinationUrl;
			return true;
		}

		function setIsNegative($newFlag) {
			// update the google servers
			global $soapClients;
			$someSoapClient = $soapClients->getCriterionClient();
			if (isset($this->text)) {
			  $criterionType = "Keyword";
			}
			else {
			  $criterionType = "Website";
			}
			// make sure bool gets transformed into string correctly
			if ($newFlag) $newFlag = "true"; else $newFlag = "false";
			// danger! think in micros
			$soapParameters = "<updateCriteria>
														<criteria>
															<id>".$this->getId()."</id>
															<adGroupId>".
															  $this->getBelongsToAdGroupId()."
															</adGroupId>
															<negative>".$newFlag."</negative>
															<criterionType>".$criterionType."</criterionType>
															<destinationUrl>".$this->getDestinationUrl()."</destinationUrl>
														</criteria>
													</updateCriteria>";
			// update the keyword on the google servers
			$someSoapClient->call("updateCriteria", $soapParameters);
			$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
			if ($someSoapClient->fault) {
		  	pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setIsNegative()", $soapParameters);
		  	return false;
			}
			// update local object
			$this->isNegative = convertBool($newFlag);
			return true;
		}

		function setIsPaused($newFlag) {
			// update the google servers
			global $soapClients;
			$someSoapClient = $soapClients->getCriterionClient();
			if (isset($this->text)) {
			  $criterionType = "Keyword";
			}
			else {
			  $criterionType = "Website";
			}
			// make sure bool gets transformed into string correctly
			if ($newFlag) $newFlag = "true"; else $newFlag = "false";
			$soapParameters = "<updateCriteria>
														<criteria>
															<id>".$this->getId()."</id>
															<adGroupId>".
															  $this->getBelongsToAdGroupId()."
															</adGroupId>
															<paused>".$newFlag."</paused>
															<criterionType>".$criterionType."</criterionType>
															<destinationUrl>".$this->getDestinationUrl()."</destinationUrl>
														</criteria>
													</updateCriteria>";
			// update the keyword on the google servers
			$someSoapClient->call("updateCriteria", $soapParameters);
			$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
			if ($someSoapClient->fault) {
		  	pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setIsPaused()", $soapParameters);
		  	return false;
			}
			// update local object
			$this->isPaused = convertBool($newFlag);
			return true;
		}

	}

	/*
		KEYWORD CRITERION
	*/

	class APIlityKeywordCriterion extends APIlityCriterion {
		// keyword class attributes
		var $text;
		var $maxCpc;
    var $proxyMaxCpc;
		var $type;

		// constructor
		function APIlityKeywordCriterion(
		  $text,
		  $id,
		  $belongsToAdGroupId,
		  $type,
		  $criterionType,
		  $isNegative,
		  $isPaused,
		  $maxCpc,
		  $minCpc,
		  $proxyMaxCpc,
		  $status,
		  $language,
		  $destinationUrl
		) {
			// we need to construct the superclass first, this is php-specific
			// object-oriented behaviour
			APIlityCriterion::APIlityCriterion(
			  $id,
			  $belongsToAdGroupId,
			  $criterionType,
			  $isNegative,
			  $isPaused,
			  $status,
			  $language,
			  $destinationUrl
			);
			// now construct the keyword criterion which inherits all other criterion
			// attributes
			$this->text = $text;
			$this->maxCpc =  $maxCpc;
			$this->minCpc =  $minCpc;
			$this->proxyMaxCpc = $proxyMaxCpc;
			$this->type = $type;
		}

		// XML output
		function toXml() {
			if ($this->getIsNegative()) {
			  $isNegative = "true";
			}
			else {
			  $isNegative = "false";
			}
			if ($this->getIsPaused()) {
			  $isPaused = "true";
			}
			else {
			  $isPaused = "false";
			}
			$xml = "<KeywordCriterion>
	<text>".$this->getText()."</text>
	<id>".$this->getId()."</id>
	<belongsToAdGroupId>".$this->getBelongsToAdGroupId()."</belongsToAdGroupId>
	<type>".$this->getType()."</type>
	<criterionType>".$this->getCriterionType()."</criterionType>
	<isNegative>".$isNegative."</isNegative>
  <isPaused>".$isPaused."</isPaused>
	<status>".$this->getStatus()."</status>
	<maxCpc>".$this->getMaxCpc()."</maxCpc>
	<minCpc>".$this->getMinCpc()."</minCpc>
	<proxyMaxCpc>".$this->getProxyMaxCpc()."</proxyMaxCpc>
	<language>".$this->getLanguage()."</language>
	<destinationUrl>".$this->getDestinationUrl()."</destinationUrl>
</KeywordCriterion>";
			return $xml;
		}

		// get functions
		function getText() {
			return $this->text;
		}

		function getMaxCpc() {
			return $this->maxCpc;
		}

		function getProxyMaxCpc() {
			return $this->proxyMaxCpc;
		}

		function getMinCpc() {
			return $this->minCpc;
		}

		function getType() {
			return $this->type;
		}

		function getCriterionData() {
			$criterionData = array(
													'text' => $this->getText(),
													'id' => $this->getId(),
													'belongsToAdGroupId' => $this->getBelongsToAdGroupId(),
													'type' => $this->getType(),
													'criterionType' => $this->getCriterionType(),
													'isNegative' => $this->getIsNegative(),
													'isPaused' => $this->getIsPaused(),
													'maxCpc' => $this->getMaxCpc(),
													'minCpc' => $this->getMinCpc(),
                          'proxyMaxCpc' => $this->getProxyMaxCpc(),
													'status' => $this->getStatus(),
													'language' => $this->getLanguage(),
													'destinationUrl' => $this->getDestinationUrl()
												);
			return $criterionData;
		}

		function getEstimate() {
			// this function is located in TrafficEstimate.php
			return getKeywordEstimate($this);
		}

		// set functions
		function setText($newText) {
			// update the google servers
			global $soapClients;
			$someSoapClient = $soapClients->getCriterionClient();
			// changing the text is not provided by the api so we need to emulate this
			// by removing and re-creating then re-create the keyword with the new
			// text set
			// make sure bool gets correctly transformed to string
			if ($this->getIsNegative()) {
			  $isNegative = "true";
			}
			else {
			  $isNegative = "false";
			}
			// danger! we need to think in micros so we need to transform the object
			// maxcpc to micros
			$soapParameters = "<addCriteria>
														<criteria>
															<adGroupId>".
															  $this->getBelongsToAdGroupId()."
															</adGroupId>
															<criterionType>Keyword</criterionType>
															<type>".$this->getType()."</type>
															<text>".$newText."</text>
															<negative>".$isNegative."</negative>
															<maxCpc>".
															  $this->getMaxCpc()* EXCHANGE_RATE."
															</maxCpc>
															<language>".$this->getLanguage()."</language>
															<destinationUrl>".$this->getDestinationUrl()."</destinationUrl>
														</criteria>
													</addCriteria>";
			// add criterion to the google servers
			$someCriterion = $someSoapClient->call("addCriteria", $soapParameters);
			$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
			if ($someSoapClient->fault) {
		  	pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setText()", $soapParameters);
		    return false;
			}
			// first delete current keyword
			$soapParameters = "<removeCriteria>
														<adGroupId>".
														  $this->getBelongsToAdGroupId()."
														</adGroupId>
														<criterionIds>".$this->getId()."</criterionIds>
												 </removeCriteria>";
			// talk to the google servers
			$someSoapClient->call("removeCriteria", $soapParameters);
			$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
			if ($someSoapClient->fault) {
		  	pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setText()", $soapParameters);
		    return false;
			}
			// update local object
			$this->text = $newText;
			// changing the text of a keyword will change its id, so update object id
			// data
			$this->id = $someCriterion['addCriteriaReturn']['id'];
			return true;
		}

		function matchMaxCpcToMinCpc() {
			if ($this->getMaxCpc() < $this->getMinCpc()) {
			  $this->setMaxCpc($this->getMinCpc());
			}
		}

		function setType($newType) {
			// update the google servers
			global $soapClients;
			$someSoapClient = $soapClients->getCriterionClient();
			// changing the type is not provided by the api so emulate this by
			// deleting and re-creating the keyword
			// then re-create the keyword with the new text set
			// make sure bool gets correctly transformed to string
			if ($this->getIsNegative()) {
			  $isNegative = "true";
			}
			else {
			  $isNegative = "false";
			}
			// danger! we need to think in micros so we need to transform the object
			// maxcpc to micros
			$soapParameters = "<addCriteria>
														<criteria>
															<adGroupId>".
															  $this->getBelongsToAdGroupId()."
															</adGroupId>
															<criterionType>Keyword</criterionType>
															<type>".$newType."</type>
															<text>".$this->getText()."</text>
															<negative>".$isNegative."</negative>
															<maxCpc>".
															  $this->getMaxCpc()* EXCHANGE_RATE."
															</maxCpc>
															<language>".$this->getLanguage()."</language>
															<destinationUrl>".$this->getDestinationUrl()."</destinationUrl>
														</criteria>
													</addCriteria>";
			// add criterion to the google servers
			$someCriterion = $someSoapClient->call("addCriteria", $soapParameters);
			$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
			if ($someSoapClient->fault) {
		  	pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setType()", $soapParameters);
		    return false;
			}
			// first delete current keyword
			$soapParameters = "<removeCriteria>
														<adGroupId>".
														  $this->getBelongsToAdGroupId()."
														</adGroupId>
														<criterionIds>".$this->getId()."</criterionIds>
												 </removeCriteria>";
			// talk to the google servers
			$someSoapClient->call("removeCriteria", $soapParameters);
			$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
			if ($someSoapClient->fault) {
		  	pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setType()", $soapParameters);
		    return false;
			}
			// update local object
			$this->type = $newType;
			// changing the type of a keyword will change its id, so update object
			// id data
			$this->id = $someCriterion['addCriteriaReturn']['id'];
			return true;
		}

		function setMaxCpc($newMaxCpc) {
			// update the google servers
			global $soapClients;
			$someSoapClient = $soapClients->getCriterionClient();
			if ($this->getIsNegative()) {
			  $isNegative = "true";
			}
			else {
			  $isNegative = "false";
			}
			// danger! think in micros
			$soapParameters = "<updateCriteria>
														<criteria>
															<id>".$this->getId()."</id>
															<adGroupId>".
															  $this->getBelongsToAdGroupId()."
															</adGroupId>
															<criterionType>Keyword</criterionType>
															<maxCpc>".$newMaxCpc * EXCHANGE_RATE."</maxCpc>
															<negative>".$isNegative."</negative>
															<destinationUrl>".$this->getDestinationUrl()."</destinationUrl>
														</criteria>
													</updateCriteria>";
			// update the keyword on the google servers
			$someSoapClient->call("updateCriteria", $soapParameters);
			$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
			if ($someSoapClient->fault) {
		  	pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setMaxCpc()", $soapParameters);
		  	return false;
			}
			// update local object
			$this->maxCpc = $newMaxCpc;
			return true;
		}
	}

	/*
		WEBSITE CRITERION
	*/

	class APIlityWebsiteCriterion extends APIlityCriterion {
		// website class attributes
		var $maxCpm;
		var $url;

		// constructor
		function APIlityWebsiteCriterion(
		  $url,
		  $id,
		  $belongsToAdGroupId,
		  $criterionType,
		  $isNegative,
		  $isPaused,
		  $maxCpm,
		  $status,
		  $language,
		  $destinationUrl
		) {
			// we need to construct the superclass first, this is php-specific
			// object-oriented behaviour
			APIlityCriterion::APIlityCriterion(
			  $id,
			  $belongsToAdGroupId,
			  $criterionType,
			  $isNegative,
			  $isPaused,
			  $status,
			  $language,
			  $destinationUrl
			);
			// now construct the website criterion which inherits all other criterion
			// attributes
			$this->maxCpm = $maxCpm;
			$this->url = $url;
		}

		// XML output
		function toXml() {
			if ($this->getIsNegative()) {
			  $isNegative = "true";
			}
			else {
			  $isNegative = "false";
			}
			if ($this->getIsPaused()) {
			  $isPaused = "true";
			}
			else {
			  $isPaused = "false";
			}
			$xml = "<WebsiteCriterion>
	<url>".$this->getUrl()."</url>
	<id>".$this->getId()."</id>
	<belongsToAdGroupId>".$this->getBelongsToAdGroupId()."</belongsToAdGroupId>
	<criterionType>".$this->getCriterionType()."</criterionType>
	<isNegative>".$isNegative."</isNegative>
	<isPaused>".$isPaused."</isPaused>
	<status>".$this->getStatus()."</status>
	<maxCpm>".$this->getMaxCpm()."</maxCpm>
	<language>".$this->getLanguage()."</language>
	<destinationUrl>".$this->getDestinationUrl()."</destinationUrl>
</WebsiteCriterion>";
			return utf8_encode($xml);
		}

		// get functions
		function getMaxCpm() {
			return $this->maxCpm;
		}

		function getUrl() {
			return $this->url;
		}

		function getCriterionData() {
			$criterionData = array(
													'id' => $this->getId(),
													'url' => $this->getUrl(),
													'belongsToAdGroupId' => $this->getBelongsToAdGroupId(),
													'criterionType' => $this->getCriterionType(),
													'isNegative' => $this->getIsNegative(),
													'isPaused' => $this->getIsPaused(),
													'maxCpm' => $this->getMaxCpm(),
													'status' => $this->getStatus(),
													'language' => $this->getLanguage(),
													'destinationUrl' => $this->getDestinationUrl()
												);
			return $criterionData;
		}

		// set functions
		function setUrl($newUrl) {
			// update the google servers
			global $soapClients;
			$someSoapClient = $soapClients->getCriterionClient();
			// changing the url is not provided by the api so we need to emulate this
			// by removing and re-creating
			// then re-create the website with the new url set
			// make sure bool gets correctly transformed to string
			if ($this->getIsNegative()) {
			  $isNegative = "true";
			}
			else {
			  $isNegative = "false";
			}
			// danger! we need to think in micros so we need to transform the object
			// maxcpc to micros
			$soapParameters = "<addCriteria>
														<criteria>
															<adGroupId>".
															  $this->getBelongsToAdGroupId()."
															</adGroupId>
															<criterionType>Website</criterionType>
															<negative>".$isNegative."</negative>
															<maxCpm>".
															  $this->getMaxCpm()* EXCHANGE_RATE."
															</maxCpm>
															<language>".$this->getLanguage()."</language>
															<destinationUrl>".$this->getDestinationUrl()."</destinationUrl>
															<url>".$newUrl."</url>
														</criteria>
													</addCriteria>";
			// add criterion to the google servers
			$someCriterion = $someSoapClient->call("addCriteria", $soapParameters);
			$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
			if ($someSoapClient->fault) {
		  	pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setUrl()", $soapParameters);
		    return false;
			}
			// first delete current website
			$soapParameters = "<removeCriteria>
														<adGroupId>".
														  $this->getBelongsToAdGroupId()."
														</adGroupId>
														<criterionIds>".$this->getId()."</criterionIds>
												 </removeCriteria>";
			// talk to the google servers
			$someSoapClient->call("removeCriteria", $soapParameters);
			$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
			if ($someSoapClient->fault) {
		  	pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setUrl()", $soapParameters);
		    return false;
			}
			// update local object
			$this->url = $newUrl;
			// changing the text of a keyword will change its id, so update object
			// id data
			$this->id = $someCriterion['addCriteriaReturn']['id'];
			return true;
		}

		function setMaxCpm($newMaxCpm) {
			// update the google servers
			global $soapClients;
			$someSoapClient = $soapClients->getCriterionClient();
			// danger! think in micros
			$soapParameters = "<updateCriteria>
														<criteria>
															<id>".$this->getId()."</id>
															<adGroupId>".
															  $this->getBelongsToAdGroupId()."
															</adGroupId>
															<criterionType>Website</criterionType>
															<maxCpm>".$newMaxCpm * EXCHANGE_RATE."</maxCpm>
															<negative>".$this->getIsNegative()."</negative>
															<destinationUrl>".$this->getDestinationUrl()."</destinationUrl>
														</criteria>
													</updateCriteria>";
			// update the keyword on the google servers
			$someSoapClient->call("updateCriteria", $soapParameters);
			$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
			if ($someSoapClient->fault) {
		  	pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setMaxCpm()", $soapParameters);
		  	return false;
			}
			// update local object
			$this->maxCpm = $newMaxCpm;
			return true;
		}
	}

	/*
  	GENERIC CLASS FUNCTIONS FOR BOTH KEYWORD AND WEBSITE CRITERIONS
	*/

	// add keyword criterion on google servers and create local object
	function addKeywordCriterion(
	  $text,
		$belongsToAdGroupId,
		$type,
		$isNegative,
		$maxCpc,
		$language,
		$destinationUrl,
		$exemptionRequest = false
  ) {
		// update the google servers
		global $soapClients;
		$someSoapClient = $soapClients->getCriterionClient();
		// populate variables with function arguments

		// make sure bool gets transformed to string correctly
		if ($isNegative) $isNegative = "true"; else $isNegative = "false";
    $exemptionRequestXml = '';
    if ($exemptionRequest) {
      $exemptionRequestXml = '<exemptionRequest>'.$exemptionRequest.'</exemptionRequest>';
    }

    // when budget optimizer is on the maxcpc needs to be omitted
    $maxCpcXml = "";
    if ($maxCpc) {
      $maxCpcXml = "<maxCpc>".$maxCpc * EXCHANGE_RATE."</maxCpc>";
    }

		$soapParameters = "<addCriteria>
													<criteria>
														<adGroupId>".$belongsToAdGroupId."</adGroupId>
														<criterionType>Keyword</criterionType>
														<type>".$type."</type>
														<text>".$text."</text>
														<negative>".$isNegative."</negative>".
														$macCpcXml."
														<language>".$language."</language>
														<destinationUrl>".$destinationUrl."</destinationUrl>".
														$exemptionRequestXml."
													</criteria>
												</addCriteria>";
		// add criterion to the google servers
		$someCriterion = $someSoapClient->call("addCriteria", $soapParameters);
		$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
		if ($someSoapClient->fault) {
	  	pushFault($someSoapClient, $_SERVER['PHP_SELF'].":addKeywordCriterion()", $soapParameters);
	    return false;
		}
    return receiveCriterion($someCriterion['addCriteriaReturn']);
	}

	function addKeywordCriterionList($criteria) {
		// update the google servers
		global $soapClients;
		$someSoapClient = $soapClients->getCriterionClient();

		$soapParameters = "<addCriteria>";

		foreach ($criteria as $criterion) {
			// make sure integer is transformed to string correctly
			if ($criterion['isNegative']) {
			  $criterion['isNegative'] = "true";
			}
			else {
			  $criterion['isNegative'] = "false";
			}
			// think in micros
			// when budget optimizer is on the maxcpc needs to be omitted
      $maxCpcXml = "";
      if ($criterion['maxCpc']) {
        $maxCpcXml = "<maxCpc>".$criterion['maxCpc'] * EXCHANGE_RATE."</maxCpc>";
      }
			if (isset($criterion['exemptionRequest'])) {
				// with exemption request
				$soapParameters .= "<criteria>
															<exemptionRequest>".
															  $criterion['exemptionRequest']."
															</exemptionRequest>
															<adGroupId>".
															  $criterion['belongsToAdGroupId']."
															</adGroupId>
															<type>".$criterion['type']."</type>
															<criterionType>Keyword</criterionType>
															<text>".$criterion['text']."</text>
															<negative>".$criterion['isNegative']."</negative>".
															$maxCpcXml."
															<language>".$criterion['language']."</language>
															<destinationUrl>".$criterion['destinationUrl']."</destinationUrl>
														</criteria>";
			}
			else {
				// without exemption request
				$soapParameters .= "<criteria>
															<type>".$criterion['type']."</type>
															<adGroupId>".
															  $criterion['belongsToAdGroupId']."
															</adGroupId>
															<criterionType>Keyword</criterionType>
															<text>".$criterion['text']."</text>
															<negative>".$criterion['isNegative']."</negative>".
															$maxCpcXml."
															<language>".$criterion['language']."</language>
															<destinationUrl>".$criterion['destinationUrl']."</destinationUrl>
														</criteria>";
			}
		}
		$soapParameters .= "</addCriteria>";
		// add criteria to the google servers
		$someCriteria = $someSoapClient->call("addCriteria", $soapParameters);
		$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
		if ($someSoapClient->fault) {
	  	pushFault($someSoapClient, $_SERVER['PHP_SELF'].":addKeywordCriterionList()", $soapParameters);
	    return false;
		}

		// when we have only one keyword return a (one keyword element) array anyway
		$someCriteria = makeNumericArray($someCriteria);

		// create local objects
		$criterionObjects = array();
		foreach($someCriteria['addCriteriaReturn'] as $someCriterion) {
		  $criterionObject = receiveCriterion($someCriterion);
		  if (isset($criterionObject)) {
			  array_push($criterionObjects, $criterionObject);
			}
		}
		return $criterionObjects;
	}

	// this won't fail completely if only one criterion fails
	// but causes a lot soap overhead
	function addKeywordCriteriaOneByOne($criteria) {
		// this is basically just a wrapper to the addKeywordCriterion function
		$criterionObjects = array();
		foreach ($criteria as $criterion) {
			if (isset($criterion['exemptionRequest'])) {
				// with exemption request
				$criterionObject = addKeywordCriterion(
				  $criterion['text'],
				  $criterion['belongsToAdGroupId'],
				  $criterion['type'],
				  $criterion['isNegative'],
				  $criterion['maxCpc'],
				  $criterion['language'],
				  $criterion['destinationUrl'],
				  $criterion['exemptionRequest']
				);
			}
			else {
				// without exemption request
				$criterionObject = addKeywordCriterion(
				  $criterion['text'],
				  $criterion['belongsToAdGroupId'],
				  $criterion['type'],
				  $criterion['isNegative'],
				  $criterion['maxCpc'],
				  $criterion['language'],
				  $criterion['destinationUrl']
				);
			}
			array_push($criterionObjects, $criterionObject);
		}
		return $criterionObjects;
	}

	function getAllCriteria($adGroupId) {
	 	global $soapClients;
		$someSoapClient = $soapClients->getCriterionClient();
	 	$soapParameters = "<getAllCriteria>
	 												<adGroupId>".$adGroupId."</adGroupId>
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

	// remove criterion on google servers and delete local object
	function removeCriterion(&$criterionObject) {
		// update the google servers
		global $soapClients;
		$someSoapClient = $soapClients->getCriterionClient();
		$soapParameters = "<removeCriteria>
													<adGroupId>".
													  $criterionObject->getBelongsToAdGroupId()."
													</adGroupId>
													<criterionIds>".
													  $criterionObject->getId()."
													</criterionIds>
											 </removeCriteria>";
		// talk to the google servers
		$someSoapClient->call("removeCriteria", $soapParameters);
		$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
		if ($someSoapClient->fault) {
	  	pushFault($someSoapClient, $_SERVER['PHP_SELF'].":removeCriterion()", $soapParameters);
	    return false;
		}
		// delete remote calling object
		$criterionObject = @$GLOBALS['criterionObject'];
		unset($criterionObject);
		return true;
	}

	function createCriterionObject($givenAdGroupId, $givenCriterionId) {
		// this will create a local criterion object that we can play with
		global $soapClients;
		$someSoapClient = $soapClients->getCriterionClient();
		// prepare soap parameters
		$soapParameters = "<getCriteria>
													<adGroupId>".$givenAdGroupId."</adGroupId>
													<criterionIds>".$givenCriterionId."</criterionIds>
											 </getCriteria>";
		// execute soap call
		$someCriterion = $someSoapClient->call("getCriteria", $soapParameters);
		$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
		if ($someSoapClient->fault) {
	  	pushFault($someSoapClient, $_SERVER['PHP_SELF'].":createCriterionObject()", $soapParameters);
	    return false;
		}
		// invalid ids are silently ignored. this is not what we want so put out a
		// warning and return without doing anything.
		if (empty($someCriterion)) {
			if (!SILENCE_STEALTH_MODE) echo "<br /><b>APIlity PHP library => Warning: </b>Invalid Criterion ID or AdGroup ID. No Criterion found.";
			return null;
		}
    return receiveCriterion($someCriterion['getCriteriaReturn']);
	}

	// add keyword criterion on google servers and create local object
	function addWebsiteCriterion(
	  $url,
	  $belongsToAdGroupId,
	  $isNegative,
	  $maxCpm,
	  $destinationUrl
	) {
		// update the google servers
		global $soapClients;
		$someSoapClient = $soapClients->getCriterionClient();

		// thinking in micros here
		$maxCpm = $maxCpm * EXCHANGE_RATE;
		// make sure bool gets transformed to string correctly
		if ($isNegative) $isNegative = "true"; else $isNegative = "false";

		$soapParameters = "<addCriteria>
													<criteria>
														<adGroupId>".$belongsToAdGroupId."</adGroupId>
														<url>".$url."</url>
														<criterionType>Website</criterionType>
														<negative>".$isNegative."</negative>
														<maxCpm>".$maxCpm."</maxCpm>
														<destinationUrl>".$destinationUrl."</destinationUrl>
													</criteria>
												</addCriteria>";
		// add criterion to the google servers
		$someCriterion = $someSoapClient->call("addCriteria", $soapParameters);
		$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
		if ($someSoapClient->fault) {
	  	pushFault($someSoapClient, $_SERVER['PHP_SELF'].":addWebsiteCriterion()", $soapParameters);
	    return false;
		}
    return receiveCriterion($someCriterion['addCriteriaReturn']);
	}

	function addWebsiteCriteriaOneByOne($criteria) {
		// this is basically just a wrapper to the addWebsiteCriterion function
		$criterionObjects = array();
		foreach ($criteria as $criterion) {
			$criterionObject = addWebsiteCriterion(
			  $criterion['url'],
			  $criterion['belongsToAdGroupId'],
			  $criterion['isNegative'],
			  $criterion['maxCpm'],
			  $criterion['destinationUrl']
			);
			array_push($criterionObjects, $criterionObject);
		}
		return $criterionObjects;
	}

	function addWebsiteCriterionList($criteria) {
		global $soapClients;
		$someSoapClient = $soapClients->getCriterionClient();
		$criterionObjects = array();
		// prepare soap parameters
		$soapParameters = "<addCriteria>";
		foreach ($criteria as $criterion) {
			// update the google servers

			// thinking in micros here
			$criterion['maxCpm'] = $criterion['maxCpm'] * EXCHANGE_RATE;
			// make sure bool gets transformed to string correctly
			if ($criterion['isNegative']) {
			  $criterion['isNegative'] = "true";
			}
			else {
			  $criterion['isNegative'] = "false";
			}
			$soapParameters .= "<criteria>
														<adGroupId>".
														  $criterion['belongsToAdGroupId']."
														</adGroupId>
														<url>".$criterion['url']."</url>
														<criterionType>Website</criterionType>
														<negative>".$criterion['isNegative']."</negative>
														<maxCpm>".$criterion['maxCpm']."</maxCpm>
														<destinationUrl>".$criterion['destinationUrl']."</destinationUrl>
													</criteria>";

		}
		$soapParameters .= "</addCriteria>";
		// add criteria to the google servers
		$someCriteria = $someSoapClient->call("addCriteria", $soapParameters);
		$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
		if ($someSoapClient->fault) {
	  	pushFault($someSoapClient, $_SERVER['PHP_SELF'].":addWebsiteCriterionList()", $soapParameters);
	    return false;
		}
		// when we have only one criterion return a (one criterion element) array
		// anyway
	  $someCriteria = makeNumericArray($someCriteria);

		// create local objects
		$criterionObjects = array();
		foreach($someCriteria['addCriteriaReturn'] as $someCriterion) {
      $criterionObject = receiveCriterion($someCriterion);
      if (isset($criterionObject)) {
		  	array_push($criterionObjects, $criterionObject);
		  }
		}
		return $criterionObjects;
	}

	function updateCriterionList($criteria) {
	  // update the google servers
	  global $soapClients;
	  $someSoapClient = $soapClients->getCriterionClient();
	  $soapParameters = "<updateCriteria>";
	  foreach ($criteria as $criterion) {
	  	$isNegativeXml = "";
			$isPausedXml = "";
	  	$maxCpcXml = "";
	  	$maxCpmXml = "";
	  	$destinationUrlXml = "";
	  	$languageXml = "";
	  	// make sure integer is transformed to string correctly
	    if (isset($criterion['isNegative'])) {
	    	if ($criterion['isNegative']) {
	    	  $criterion['isNegative'] = "true";
	    	}
	    	else {
	    	  $criterion['isNegative'] = "false";
	    	}
	    	$isNegativeXml = "<negative>".$criterion['isNegative']."</negative>";
	    }
	  	// make sure integer is transformed to string correctly
	    if (isset($criterion['isPaused'])) {
	    	if ($criterion['isPaused']) {
	    	  $criterion['isPaused'] = "true";
	    	}
	    	else {
	    	  $criterion['isPaused'] = "false";
	    	}
	    	$isPausedXml = "<paused>".$criterion['isPaused']."</paused>";
	    }
	    // think in micros
	    if (isset($criterion['maxCpc'])) {
	    	$maxCpcXml = "<maxCpc>".$criterion['maxCpc'] * EXCHANGE_RATE."</maxCpc>
	    								<criterionType>Keyword</criterionType>";
	    }
	    if (isset($criterion['maxCpm'])) {
	    	$maxCpcXml = "<maxCpm>".$criterion['maxCpm'] * EXCHANGE_RATE."</maxCpm>
	    								<criterionType>Website</criterionType>";
	    }
	    if (isset($criterion['destinationUrl'])) {
	    	 $destinationUrlXml =
	    	   "<destinationUrl>".$criterion['destinationUrl']."</destinationUrl>";
	    }
	    if (isset($criterion['language'])) {
	    	 $languageXml = "<language>".$criterion['language']."</language>";
	    }
	    $soapParameters .= "<criteria>
	    										  <id>".$criterion['id']."</id>
	    											<adGroupId>".
	    											  $criterion['belongsToAdGroupId']."
	    											</adGroupId>".
														$isNegativeXml.
														$isPausedXml.
														$maxCpcXml.
														$destinationUrlXml.
														$languageXml.
	        								"</criteria>";
	  }
	  $soapParameters .= "</updateCriteria>";
	  // update the criteria on the google servers
	  $someSoapClient->call("updateCriteria", $soapParameters);
	  $soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
	  if ($someSoapClient->fault) {
	    pushFault($someSoapClient, $_SERVER['PHP_SELF'].":updateCriterionList()", $soapParameters);
	    return false;
	  }
	  else {
	    return true;
	  }
	}

	function getCriteriaList($adGroupId, $criteriaIds) {
		global $soapClients;
		$someSoapClient = $soapClients->getCriterionClient();

	 	$_criteriaIds = '';
	 	foreach($criteriaIds as $criteriaId) {
	 		$_criteriaIds .= '<criterionIds>'.$criteriaId.'</criterionIds>';
	 	}

		$soapParameters = '<getCriteria>
												 <adGroupId>'.$adGroupId.'</adGroupId>'.
												 $_criteriaIds.'
											 </getCriteria>';

	 	// query the google servers for all criteria
	 	$listCriteria = array();
	 	$listCriteria = $someSoapClient->call('getCriteria', $soapParameters);
	 	$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
		if ($someSoapClient->fault)	{
	  	pushFault($someSoapClient, $_SERVER['PHP_SELF'].':getCriteriaList()', $soapParameters);
	    return false;
		}

		// when we have only one criterion in the adgroup return a (one criterion
		// element) array  anyway
		$listCriteria = makeNumericArray($listCriteria);

		$listCriterionObjects = array();
		if (isset($listCriteria['getCriteriaReturn'])) {
			foreach ($listCriteria['getCriteriaReturn'] as $criterion) {
			  $criterionObject = receiveCriterion($criterion);
			  if (isset($criterionObject)) {
				  array_push($listCriterionObjects, $criterionObject);
				}
			}
		}
		return $listCriterionObjects;
	}

	function checkCriterionList($criteria, $languages, $newGeoTargets) {
		global $soapClients;
		$someSoapClient = $soapClients->getCriterionClient();

		$soapParameters = "<checkCriteria>";
		foreach ($criteria as $criterion) {
			// make sure integer is transformed to string correctly
			if ($criterion['isNegative']) {
			  $criterion['isNegative'] = "true";
			}
			else {
			  $criterion['isNegative'] = "false";
			}
			// think in micros
			$soapParameters .= "<criteria>
			                      <destinationUrl>".
			                        $criterion['destinationUrl']."
			                      </destinationUrl>
														<negative>".$criterion['isNegative']."</negative>";
      if (isset($criterion['text'])) {
				$soapParameters .= "<type>".$criterion['type']."</type>
														<criterionType>Keyword</criterionType>
														<text>".$criterion['text']."</text>
														<maxCpc>".
														  $criterion['maxCpc'] * EXCHANGE_RATE."
														</maxCpc>
														<language>".$criterion['language']."</language>";
      }
      else if (isset($criterion['url'])) {
        $soapParameters .= "<url>".$criterion['url']."</url>
                            <criterionType>Website</criterionType>
                            <maxCpm>".
                              $criterion['maxCpm']* EXCHANGE_RATE."
                            </maxCpm>";
      }
      $soapParameters .= "</criteria>";
		}

		$languagesXml = "<languageTarget>";
		foreach($languages as $language) {
		  $languagesXml .= "<languages>".$language."</languages>";
		}
		$languagesXml .= "</languageTarget>";
	  $soapParameters .= $languagesXml;

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
    $soapParameters .= "<geoTarget>".$newGeoTargetsXml."</geoTarget></checkCriteria>";


	 	// query the google servers
	 	$criteriaCheck = $someSoapClient->call('checkCriteria', $soapParameters);
	 	$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
		if ($someSoapClient->fault)	{
	  	pushFault($someSoapClient, $_SERVER['PHP_SELF'].':checkCriterionList()', $soapParameters);
	    return false;
		}
    return $criteriaCheck;
	}

	function receiveCriterion($someCriterion) {
		// create generic object attributes
		$belongsToAdGroupId = $someCriterion['adGroupId'];
		$criterionType = $someCriterion['criterionType'];
		$destinationUrl = @$someCriterion['destinationUrl'];
		$id = $someCriterion['id'];
		$language = @$someCriterion['language'];
		$isNegative = $someCriterion['negative'];
		$isPaused = $someCriterion['paused'];
		$status = $someCriterion['status'];

		// if we have a keyword criterion create its object attributes
		if (strcasecmp($someCriterion['criterionType'], "Keyword") == 0) {
		  if (@isset($someCriterion['maxCpc'])) {
			  $maxCpc = $someCriterion['maxCpc'];
	    }
	    else {
	      $maxCpc = null;
	    }
		  if (@isset($someCriterion['minCpc'])) {
			  $minCpc = $someCriterion['minCpc'];
	    }
	    else {
	      $minCpc = null;
	    }
		  if (@isset($someCriterion['proxyMaxCpc'])) {
	      $proxyMaxCpc = $someCriterion['proxyMaxCpc'];
	    }
	    else {
	      $proxyMaxCpc = null;
	    }
      $type = $someCriterion['type'];
      $text = $someCriterion['text'];
      $criterionObject = new APIlityKeywordCriterion(
        $text,
        $id,
        $belongsToAdGroupId,
        $type,
        $criterionType,
        $isNegative,
        $isPaused,
        $maxCpc / EXCHANGE_RATE,
        $minCpc / EXCHANGE_RATE,
        $proxyMaxCpc / EXCHANGE_RATE,
        $status,
        $language,
        $destinationUrl
      );
    }
    // else create the website criterion's object attributes
    else {
    	if (@isset($someCriterion['maxCpm'])) {
			  $maxCpm = $someCriterion['maxCpm'];
	    }
	    else {
	      $maxCpm = null;
	    }

			$url = $someCriterion['url'];
			$criterionObject = new APIlityWebsiteCriterion(
			  $url,
			  $id,
			  $belongsToAdGroupId,
			  $criterionType,
			  $isNegative,
			  $isPaused,
			  $maxCpm / EXCHANGE_RATE,
			  $status,
			  $language,
			  $destinationUrl
			);
    }
    return $criterionObject;
	}
?>