<?php
	/*
	 SUPER CLASS FOR ADS
	*/
	class APIlityAd {
		// class attributes
	 	var $id;
		var $belongsToAdGroupId;
		var $status;
		var $isDisapproved;
		var $displayUrl;
		var $destinationUrl;
    var $adType;

		// constructor
		function APIlityAd (
		  $id,
		  $belongsToAdGroupId,
		  $displayUrl,
		  $destinationUrl,
		  $status,
		  $isDisapproved,
		  $adType
		) {
			$this->id = $id;
			$this->belongsToAdGroupId = $belongsToAdGroupId;
			$this->displayUrl = $displayUrl;
			$this->destinationUrl = $destinationUrl;
			$this->status = $status;
			$this->isDisapproved = convertBool($isDisapproved);
			$this->adType = $adType;
		}

		// get functions
		function getId() {
			return $this->id;
		}

		function getBelongsToAdGroupId() {
			return $this->belongsToAdGroupId;
		}

		function getDestinationUrl() {
			return $this->destinationUrl ;
		}

		function getDisplayUrl() {
			return $this->displayUrl;
		}

		function getStatus() {
			return $this->status;
		}

		function getAdType() {
			return $this->adType;
		}

		function getIsDisapproved() {
			 // return boolean type-casted to integer for making it readable
			 return (integer) $this->isDisapproved;
		}

		function getAdStats($startDate, $endDate) {
			global $soapClients;
			$someSoapClient = $soapClients->getAdClient();
			$soapParameters = "<getAdStats>
														<adGroupId>".$this->getBelongsToAdGroupId()."</adGroupId>
														<adIds>".$this->getId()."</adIds>
														<startDay>".$startDate."</startDay>
														<endDay>".$endDate."</endDay>
												 </getAdStats>";
			// query the google servers
			$adStats = $someSoapClient->call("getAdStats", $soapParameters);
			$soapClients->updateSoapRelatedData(
			  extractSoapHeaderInfo($someSoapClient->getHeaders()));
			if ($someSoapClient->fault) {
		  	pushFault($someSoapClient, $_SERVER['PHP_SELF'].":getAdStats()", $soapParameters);
		    return false;
			}
			$adStats['getAdStatsReturn']['cost'] = $adStats['getAdStatsReturn']['cost'] / EXCHANGE_RATE;
			return $adStats['getAdStatsReturn'];
		}
	}

  /*
    VIDEO ADS
  */
  class APIlityVideoAd extends APIlityAd {
    // class attributes
    var $image;
    var $name;
    var $video;

		// constructor
		function APIlityVideoAd (
		  $id,
		  $belongsToAdGroupId,
		  $image,
		  $name,
		  $video,
      $displayUrl,
      $destinationUrl,
      $status,
      $isDisapproved
    ) {
			// we need to construct the superclass first, this is php-specific
			// object-oriented behaviour
			APIlityAd::APIlityAd(
			  $id,
			  $belongsToAdGroupId,
			  $displayUrl,
			  $destinationUrl,
			  $status,
			  $isDisapproved,
			  'VideoAd');
			// now construct the video ad which inherits all other ad attributes
			$this->image = $image;
			$this->video = $video;
			$this->name = $name;
		}

    function toXml() {
			if ($this->getIsDisapproved()) {
			  $isDisapproved = "true";
			}
			else {
			  $isDisapproved = "false";
			}
			$image = $this->getImage();
			$video = $this->getVideo();

			$xml = "<VideoAd>
	<id>".$this->getId()."</id>
	<belongsToAdGroupId>".$this->getBelongsToAdGroupId()."</belongsToAdGroupId>
  <image>
    <type>".$image['type']."</type>
  	<name>".$image['name']."</name>
  	<width>".$image['width']."</width>
  	<height>".$image['height']."</height>
  	<imageUrl>".$image['imageUrl']."</imageUrl>
  	<thumbnailUrl>".$image['thumbnailUrl']."</thumbnailUrl>
  	<mimeType>".$image['mimeType']."</mimeType>
  </image>
  <video>
    <duration>".$video['duration']."</duration>
    <filename>".$video['filename']."</filename>
    <preview>".$video['preview']."</preview>
    <title>".$video['title']."</title>
    <videoId>".$video['videoId']."</videoId>
  </video>
  <name>".$this->getName()."</name>
	<displayUrl>".$this->getDisplayUrl()."</displayUrl>
	<destinationUrl>".$this->getDestinationUrl()."</destinationUrl>
	<status>".$this->getStatus()."</status>
	<isDisapproved>".$isDisapproved."</isDisapproved>
</VideoAd>";
			return $xml;
    }
		// get functions
		function getName() {
		  return $this->name;
		}

		function getImage() {
		  return $this->image;
		}

		function getVideo() {
		  return $this->video;
		}

	  // report function
		function getAdData() {
			$adData = array(
												'id' => $this->getId(),
												'belongsToAdGroupId' => $this->getBelongsToAdGroupId(),
                  			'video' => $this->getVideo(),
                  			'image' => $this->getImage(),
                  			'name' => $this->getName(),
												'displayUrl' => $this->getDisplayUrl(),
												'destinationUrl' => $this->getDestinationUrl(),
												'status' => $this->getStatus(),
												'isDisapproved' => $this->getIsDisapproved()
											);
			return $adData;
		}

		// set functions
		function setStatus ($newStatus) {
			// update the google servers
			global $soapClients;
			$someSoapClient = $soapClients->getAdClient();
			$soapParameters = "<updateAds>
													 <ads>
														 <adGroupId>".$this->getBelongsToAdGroupId()."</adGroupId>
														 <id>".$this->getId()."</id>
														 <status>".$newStatus."</status>
														 <adType>VideoAd</adType>
												 	 </ads>
												 </updateAds>";
			$someSoapClient->call("updateAds", $soapParameters);
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

  /*
    LOCAL BUSINESS ADS
  */
  class APIlityLocalBusinessAd extends APIlityAd {
		// class attributes
		var $address;
    var $businessImage;
    var $businessKey;
    var $businessName;
    var $city;
    var $countryCode;
    var $customIcon;
    var $customIconId;
    var $description1;
    var $description2;
    var $phoneNumber;
    var $postalCode;
    var $region;
    var $stockIcon;
    var $targetRadiusInKm;

		// constructor
		function APIlityLocalBusinessAd (
		  $id,
		  $belongsToAdGroupId,
		  $address,
      $businessImage,
      $businessKey,
      $businessName,
      $city,
      $countryCode,
      $customIcon,
      $customIconId,
      $description1,
      $description2,
      $phoneNumber,
      $postalCode,
      $region,
      $stockIcon,
      $targetRadiusInKm,
      $displayUrl,
      $destinationUrl,
      $status,
      $isDisapproved
    ) {
			// we need to construct the superclass first, this is php-specific
			// object-oriented behaviour
			APIlityAd::APIlityAd(
			  $id,
			  $belongsToAdGroupId,
			  $displayUrl,
			  $destinationUrl,
			  $status,
			  $isDisapproved,
			  'LocalBusinessAd');
			// now construct the text ad which inherits all other ad attributes
			$this->address = $address;
      $this->businessImage = $businessImage;
      $this->businessKey = $businessKey;
      $this->businessName = $businessName;
      $this->city = $city;
      $this->countryCode = $countryCode;
      $this->customIcon = $customCode;
      $this->customIconId = $customIconId;
      $this->description1 = $description1;
      $this->description2 = $description2;
      $this->phoneNumber = $phoneNumber;
      $this->postalCode = $postalCode;
      $this->region = $region;
      $this->stockIcon = $stockIcon;
      $this->targetRadiusInKm = $targetRadiusInKm;
		}

		// XML output
		function toXml() {
			if ($this->getIsDisapproved())
			  $isDisapproved = "true";
			else
			  $isDisapproved = "false";
			$businessImage = $this->getBusinessImage();
			$customIcon = $this->getCustomIcon();

			$xml = "<LocalBusinessAd>
	<id>".$this->getId()."</id>
	<belongsToAdGroupId>".$this->getBelongsToAdGroupId()."</belongsToAdGroupId>
	<address>".$this->getAddress()."
  <businessImage>
    <image>
      <type>".$businessImage['type']."</type>
    	<name>".$businessImage['name']."</name>
    	<width>".$businessImage['width']."</width>
    	<height>".$businessImage['height']."</height>
    	<imageUrl>".$businessImage['imageUrl']."</imageUrl>
    	<thumbnailUrl>".$businessImage['thumbnailUrl']."</thumbnailUrl>
    	<mimeType>".$businessImage['mimeType']."</mimeType>
    </image>
  </businessImage>
  <businessKey>".$this->getBusinessKey()."</businessKey>
  <businessName>".$this->getBusinessName()."</businessName>
  <city>".$this->getCity()."</city>
  <countryCode>".$this->getCountryCode()."</countryCode>
  <customIcon>
    <image>
      <type>".$customIcon['type']."</type>
    	<name>".$customIcon['name']."</name>
    	<width>".$customIcon['width']."</width>
    	<height>".$customIcon['height']."</height>
    	<imageUrl>".$customIcon['imageUrl']."</imageUrl>
    	<thumbnailUrl>".$customIcon['thumbnailUrl']."</thumbnailUrl>
    	<mimeType>".$customIcon['mimeType']."</mimeType>
    </image>
  </customIcon>
  <customIconId>".$this->getCustomIconId()."</customIconId>
  <description1>".$this->getDescription1()."</description1>
  <description2>".$this->getDescription2()."</description2>
  <phoneNumber>".$this->getPhoneNumber()."</phoneNumber>
  <postalCode>".$this->getPostalCode()."</postalCode>
  <region>".$this->getRegion()."</region>
  <stockIcon>".$this->getStockIcon()."</stockIcon>
  <targetRadiusInKm>".$this->getTargetRadiusInKm()."</targetRadiusInKm>
	<displayUrl>".$this->getDisplayUrl()."</displayUrl>
	<destinationUrl>".$this->getDestinationUrl()."</destinationUrl>
	<status>".$this->getStatus()."</status>
	<isDisapproved>".$isDisapproved."</isDisapproved>
</LocalBusinessAd>";
			return $xml;
		}

		// get functions
		function getAddress() {
			return $this->address;
		}

		function getBusinessImage() {
			return $this->businessImage;
		}

		function getBusinessKey() {
			return $this->businessKey;
		}

		function getBusinessName() {
			return $this->businessName;
		}

		function getCity() {
			return $this->city;
		}

		function getCountryCode() {
			return $this->countryCode;
		}

		function getCustomIcon() {
			return $this->customIcon;
		}

		function getCustomIconId() {
			return $this->customIconId;
		}

		function getDescription1() {
			return $this->description1;
		}

		function getDescription2() {
			return $this->description2;
		}

		function getPhoneNumber() {
			return $this->phoneNumber;
		}

		function getPostalCode() {
			return $this->postalCode;
		}

		function getRegion() {
			return $this->region;
		}

		function getStockIcon() {
			return $this->stockIcon;
		}

		function getTargetRadiusInKm() {
		  return $this->targetRadiusInKm;
		}

		// report function
		function getAdData() {
			$adData = array(
												'id'=>$this->getId(),
												'belongsToAdGroupId'=>$this->getBelongsToAdGroupId(),
                  			'address' => $this->getAddress(),
                        'businessImage' => $this->getBusinessImage(),
                        'businessKey' => $this->getBusinessKey(),
                        'businessName' => $this->getBusinessName(),
                        'city' => $this->getCity(),
                        'countryCode' => $this->getCountryCode(),
                        'customIcon' => $this->getCustomIcon(),
                        'customIconId' => $this->getCustomIconId(),
                        'description1' => $this->getDescription1(),
                        'description2' => $this->getDescription2(),
                        'phoneNumber' => $this->getPhoneNumber(),
                        'postalCode' => $this->getPostalCode(),
                        'region' => $this->getRegion(),
                        'stockIcon' => $this->getStockIcon(),
                        'targetRadiusInKm' => $this->getTargetRadiusInKm(),
												'displayUrl'=>$this->getDisplayUrl(),
												'destinationUrl'=>$this->getDestinationUrl(),
												'status'=>$this->getStatus(),
												'isDisapproved'=>$this->getIsDisapproved()
											);
			return $adData;
		}

		// set functions
		function setStatus ($newStatus) {
			// update the google servers
			global $soapClients;
			$someSoapClient = $soapClients->getAdClient();
			$soapParameters = "<updateAds>
													 <ads>
														 <adGroupId>".$this->getBelongsToAdGroupId()."</adGroupId>
														 <id>".$this->getId()."</id>
														 <status>".$newStatus."</status>
														 <adType>LocalBusinessAd</adType>
												 	 </ads>
												 </updateAds>";
			$someSoapClient->call("updateAds", $soapParameters);
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

  /*
    COMMERCE ADS
  */
  class APIlityCommerceAd extends APIlityAd {
		// class attributes
    var $description1;
    var $description2;
    var $headline;
    var $postPriceAnnotation;
    var $prePriceAnnotation;
    var $priceString;
    var $productImage = array();

		// constructor
		function APIlityCommerceAd (
		  $id,
		  $belongsToAdGroupId,
      $description1,
      $description2,
      $headline,
      $postPriceAnnotation,
      $prePriceAnnotation,
      $priceString,
      $productImage,
      $displayUrl,
      $destinationUrl,
      $status,
      $isDisapproved
    ) {
			// we need to construct the superclass first, this is php-specific
			// object-oriented behaviour
			APIlityAd::APIlityAd(
			  $id,
			  $belongsToAdGroupId,
			  $displayUrl,
			  $destinationUrl,
			  $status,
			  $isDisapproved,
			  'CommerceAd'
			);
			// now construct the commerce ad which inherits all other ad attributes
        $this->description1 = $description1;
        $this->description2 = $description2;
        $this->headline = $headline;
        $this->postPriceAnnotation = $postPriceAnnotation;
        $this->prePriceAnnotation = $prePriceAnnotation;
        $this->priceString = $priceString;
        $this->productImage = $productImage;
		}

		// XML output
		function toXml() {
			if ($this->getIsDisapproved())
			  $isDisapproved = "true";
			else
			  $isDisapproved = "false";
			$productImage = $this->getProductImage();

			$xml = "<CommerceAd>
	<id>".$this->getId()."</id>
	<belongsToAdGroupId>".$this->getBelongsToAdGroupId()."</belongsToAdGroupId>
  <description1>".$this->getDescription1()."</description1>
  <description2>".$this->getDescription2()."</description2>
  <headline>".$this->getHeadline()."</headline>
  <postPriceAnnotation>".$this->getPostPriceAnnotation()."</postPriceAnnotation>
  <prePriceAnnotation>".$this->getPrePriceAnnotation()."</prePriceAnnotation>
  <priceString>".$this->getPriceString()."</priceString>
  <productImage>
    <image>
      <type>".$productImage['type']."</type>
    	<name>".$productImage['name']."</name>
    	<width>".$productImage['width']."</width>
    	<height>".$productImage['height']."</height>
    	<imageUrl>".$productImage['imageUrl']."</imageUrl>
    	<thumbnailUrl>".$productImage['thumbnailUrl']."</thumbnailUrl>
    	<mimeType>".$productImage['mimeType']."</mimeType>
    </image>
  </productImage>
	<displayUrl>".$this->getDisplayUrl()."</displayUrl>
	<destinationUrl>".$this->getDestinationUrl()."</destinationUrl>
	<status>".$this->getStatus()."</status>
	<isDisapproved>".$isDisapproved."</isDisapproved>
</CommerceAd>";
			return $xml;
		}

		// get functions
		function getDescription1() {
			return $this->description1;
		}

		function getDescription2() {
			return $this->description2;
		}

		function getHeadline() {
			return $this->headline;
		}

		function getPostPriceAnnotation() {
			return $this->postPriceAnnotation;
		}

		function getPrePriceAnnotation() {
			return $this->prePriceAnnotation;
		}

		function getPriceString() {
			return $this->priceString;
		}

		function getProductImage() {
			return $this->productImage;
		}

		// report function
		function getAdData() {
			$adData = array(
												'id'=>$this->getId(),
												'belongsToAdGroupId'=> $this->getBelongsToAdGroupId(),
                        'description1' => $this->getDescription1(),
                        'description2' => $this->getDescription2(),
                        'headline' => $this->getHeadline(),
                        'postPriceAnnotation' => $this->getPostPriceAnnotation(),
                        'prePriceAnnotation' => $this->getPrePriceAnnotation(),
                        'priceString' => $this->getPriceString(),
                        'productImage' => $this->getProductImage(),
												'displayUrl'=> $this->getDisplayUrl(),
												'destinationUrl'=> $this->getDestinationUrl(),
												'status'=> $this->getStatus(),
												'isDisapproved'=> $this->getIsDisapproved()
											);
			return $adData;
		}

		// set functions
		function setStatus ($newStatus) {
			// update the google servers
			global $soapClients;
			$someSoapClient = $soapClients->getAdClient();
			$soapParameters = "<updateAds>
													 <ads>
														 <adGroupId>".$this->getBelongsToAdGroupId()."</adGroupId>
														 <id>".$this->getId()."</id>
														 <status>".$newStatus."</status>
														 <adType>CommerceAd</adType>
												 	 </ads>
												 </updateAds>";
			$someSoapClient->call("updateAds", $soapParameters);
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

  /*
    MOBILE ADS
  */
  class APIlityMobileAd extends APIlityAd {
		// class attributes
		var $businessName;
    var $countryCode;
    var $description;
    var $headline;
    var $markupLanguages = array();
    var $mobileCarriers = array();
    var $phoneNumber;

		// constructor
		function APIlityMobileAd (
		  $id,
		  $belongsToAdGroupId,
		  $businessName,
      $countryCode,
      $description,
      $headline,
      $markupLanguages,
      $mobileCarriers,
      $phoneNumber,
      $displayUrl,
      $destinationUrl,
      $status,
      $isDisapproved
    ) {
			// we need to construct the superclass first, this is php-specific
			// object-oriented behaviour
			APIlityAd::APIlityAd(
			  $id,
			  $belongsToAdGroupId,
			  $displayUrl,
			  $destinationUrl,
			  $status,
			  $isDisapproved,
			  'MobileAd'
			);
			// now construct the mobile ad which inherits all other ad attributes
			$this->businessName = $businessName;
      $this->countryCode = $countryCode;
      $this->description = $description;
      $this->headline = $headline;
      $this->markupLanguages = convertToArray($markupLanguages);
      $this->mobileCarriers = convertToArray($mobileCarriers);
      $this->phoneNumber = $phoneNumber;
		}

		// XML output
		function toXml() {
			if ($this->getIsDisapproved())
			  $isDisapproved = "true"; else $isDisapproved = "false";
			$markupLanguages = $this->getMarkupLanguages();
      $markupLanguagesXml = "";
			foreach($markupLanguages as $markupLanguage) {
			  $markupLanguagesXml .= "<markupLanguage>".$markupLanguage."</markupLanguage>";
			}

			$mobileCarriers = $this->getMobileCarriers();
      $mobileCarriersXml = "";
			foreach($mobileCarriers as $mobileCarrier) {
			  $mobileCarriersXml .= "<mobileCarrier>".$mobileCarrier."</mobileCarrier>";
			}

			$xml = "<MobileAd>
	<id>".$this->getId()."</id>
	<belongsToAdGroupId>".$this->getBelongsToAdGroupId()."</belongsToAdGroupId>
	<businessName>".$this->getBusinessName()."</businessName>
  <countryCode>".$this->getCountryCode()."</countryCode>
  <description>".$this->getDescription()."</description>
  <headline>".$this->getHeadline()."</headline>
  <markupLanguages>".$markupLanguagesXml."</markupLanguages>
  <mobileCarriers>".$mobileCarriersXml."</mobileCarriers>
  <phoneNumber>".$this->getPhoneNumber()."</phoneNumber>
	<displayUrl>".$this->getDisplayUrl()."</displayUrl>
	<destinationUrl>".$this->getDestinationUrl()."</destinationUrl>
	<status>".$this->getStatus()."</status>
	<isDisapproved>".$isDisapproved."</isDisapproved>
</MobileAd>";
			return $xml;
		}

		// get functions
		function getBusinessName() {
			return $this->businessName;
		}

		function getCountryCode() {
			return $this->countryCode;
		}

		function getDescription() {
			return $this->description;
		}

		function getHeadline() {
			return $this->headline;
		}

		function getMarkupLanguages() {
			return $this->markupLanguages;
		}

		function getMobileCarriers() {
			return $this->mobileCarriers;
		}

		function getPhoneNumber() {
			return $this->phoneNumber;
		}
		// report function
		function getAdData() {
			$adData = array(
												'id' => $this->getId(),
												'belongsToAdGroupId' => $this->getBelongsToAdGroupId(),
                        'businessName' => $this->getBusinessName(),
                        'countryCode' => $this->getCountryCode(),
                  			'description' => $this->getDescription(),
                  			'headline' => $this->getHeadline(),
                  			'markupLanguages' => $this->getMarkupLanguages(),
                  			'mobileCarriers' => $this->getMobileCarriers(),
                  			'phoneNumber' => $this->getPhoneNumber(),
												'displayUrl'=>$this->getDisplayUrl(),
												'destinationUrl'=>$this->getDestinationUrl(),
												'status'=>$this->getStatus(),
												'isDisapproved'=>$this->getIsDisapproved()
											);
			return $adData;
		}

		// set functions
		function setStatus ($newStatus) {
			// update the google servers
			global $soapClients;
			$someSoapClient = $soapClients->getAdClient();
			$soapParameters = "<updateAds>
													 <ads>
														 <adGroupId>".$this->getBelongsToAdGroupId()."</adGroupId>
														 <id>".$this->getId()."</id>
														 <status>".$newStatus."</status>
														 <adType>MobileAd</adType>
												 	 </ads>
												 </updateAds>";
			$someSoapClient->call("updateAds", $soapParameters);
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

	/*
	 TEXT ADS
	*/

	class APIlityTextAd extends APIlityAd {
		// class attributes
		var $headline;
		var $description1;
		var $description2;

		// constructor
		function APIlityTextAd (
		  $id,
		  $belongsToAdGroupId,
		  $headline,
		  $description1,
		  $description2,
		  $displayUrl,
		  $destinationUrl,
		  $status,
		  $isDisapproved
		) {
			// we need to construct the superclass first, this is php-specific
			// object-oriented behaviour
			APIlityAd::APIlityAd(
			  $id,
			  $belongsToAdGroupId,
			  $displayUrl,
			  $destinationUrl,
			  $status,
			  $isDisapproved,
			  'TextAd'
			);
			// now construct the text ad which inherits all other ad attributes
			$this->headline = $headline;
			$this->description1 = $description1;
			$this->description2 = $description2;
		}

		// XML output
		function toXml() {
			if ($this->getIsDisapproved())
			  $isDisapproved = "true";
			else
			  $isDisapproved = "false";
			$xml = "<TextAd>
	<id>".$this->getId()."</id>
	<belongsToAdGroupId>".$this->getBelongsToAdGroupId()."</belongsToAdGroupId>
	<headline>".$this->getHeadline()."</headline>
	<description1>".$this->getDescription1()."</description1>
	<description2>".$this->getDescription2()."</description2>
	<displayUrl>".$this->getDisplayUrl()."</displayUrl>
	<destinationUrl>".$this->getDestinationUrl()."</destinationUrl>
	<status>".$this->getStatus()."</status>
	<isDisapproved>".$isDisapproved."</isDisapproved>
</TextAd>";
			return $xml;
		}

		// get functions
		function getHeadline() {
			return $this->headline;
		}

		function getDescription1() {
			return $this->description1;
		}

		function getDescription2() {
			return $this->description2;
		}

		// report function
		function getAdData() {
			$adData = array(
												'id'=>$this->getId(),
												'belongsToAdGroupId'=>$this->getBelongsToAdGroupId(),
												'headline'=>$this->getHeadline(),
												'description1'=>$this->getDescription1(),
												'description2'=>$this->getDescription2(),
												'displayUrl'=>$this->getDisplayUrl(),
												'destinationUrl'=>$this->getDestinationUrl(),
												'status'=>$this->getStatus(),
												'isDisapproved'=>$this->getIsDisapproved()
											);
			return $adData;
		}

		// set functions
		function setStatus ($newStatus) {
			// update the google servers
			global $soapClients;
			$someSoapClient = $soapClients->getAdClient();
			$soapParameters = "<updateAds>
													 <ads>
														 <adGroupId>".$this->getBelongsToAdGroupId()."</adGroupId>
														 <id>".$this->getId()."</id>
														 <status>".$newStatus."</status>
														 <adType>TextAd</adType>
												 	 </ads>
												 </updateAds>";
			$someSoapClient->call("updateAds", $soapParameters);
			$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
			if ($someSoapClient->fault) {
		  	pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setStatus()", $soapParameters);
		    return false;
			}
			// update local object
			$this->status = $newStatus;
			return true;
		}

		function setHeadline ($newHeadline) {
			// setting the headline is not provided by the api so emulating this by
			// deleting and then re-creating the ad
			// update the google servers
			global $soapClients;
			$someSoapClient = $soapClients->getAdClient();
			// then recreate it with the new headline set
			$soapParameters = "<addAds>
														<ads>
															<adGroupId>".$this->getBelongsToAdGroupId()."</adGroupId>
															<headline>".$newHeadline."</headline>
															<description1>".utf8_decode($this->getDescription1())."</description1>
															<description2>".utf8_decode($this->getDescription2())."</description2>
															<displayUrl>".$this->getDisplayUrl()."</displayUrl>
															<destinationUrl>".$this->getDestinationUrl()."</destinationUrl>
															<adType>TextAd</adType>
														</ads>
													</addAds>";
			// add the ad to the google servers
			$someAd = $someSoapClient->call("addAds", $soapParameters);
			$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
			if ($someSoapClient->fault) {
		  	pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setHeadline()", $soapParameters);
		    return false;
			}
			// first delete the current ad
			$soapParameters = "<updateAds>
													 <ads>
														 <adGroupId>".$this->getBelongsToAdGroupId()."</adGroupId>
														 <id>".$this->getId()."</id>
														 <status>Disabled</status>
														 <adType>TextAd</adType>
												 	 </ads>
												 </updateAds>";
			// delete the ad on the google servers
			$someSoapClient->call("updateAds", $soapParameters);
			$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
			if ($someSoapClient->fault) {
		  	pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setHeadline()", $soapParameters);
		    return false;
			}
			// update local object
			$this->headline = $someAd['addAdsReturn']['headline'];
			// changing the headline of a ad will change its id so update local object
			$this->id = $someAd['addAdsReturn']['id'];
			return true;
		}

		function setDescription1 ($newDescription1) {
			// update the google servers
			// setting the description1 is not provided by the api so emulating this
			// by deleting and then re-creating the ad
			global $soapClients;
			$someSoapClient = $soapClients->getAdClient();
			// then recreate it with the new description1 set
			$soapParameters = "<addAds>
														<ads>
															<adGroupId>".$this->getBelongsToAdGroupId()."</adGroupId>
															<headline>".utf8_decode($this->getHeadline())."</headline>
															<description1>".$newDescription1."</description1>
															<description2>".utf8_decode($this->getDescription2())."</description2>
															<displayUrl>".$this->getDisplayUrl()."</displayUrl>
															<destinationUrl>".$this->getDestinationUrl()."</destinationUrl>
                              <adType>TextAd</adType>
														</ads>
													</addAds>";
			// add the ad to the google servers
			$someAd = $someSoapClient->call("addAds", $soapParameters);
			$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
			if ($someSoapClient->fault) {
		  	pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setDescription1()", $soapParameters);
		    return false;
			}
			// first delete the current ad
			$soapParameters = "<updateAds>
													 <ads>
														 <adGroupId>".$this->getBelongsToAdGroupId()."</adGroupId>
														 <id>".$this->getId()."</id>
														 <status>Disabled</status>
														 <adType>TextAd</adType>
												 	 </ads>
												 </updateAds>";
			// delete the ad on the google servers
			$someSoapClient->call("updateAds", $soapParameters);
			$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
			if ($someSoapClient->fault) {
		  	pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setDescription1()", $soapParameters);
		    return false;
			}
			// update local object
			$this->description1 = $someAd['addAdsReturn']['description1'];
			// changing the description1 of a ad will change its id so update local object
			$this->id = $someAd['addAdsReturn']['id'];
			return true;
		}

		function setDescription2 ($newDescription2) {
			// setting the description2 is not provided by the api so emulating this
			// by deleting and then re-creating the ad
			// update the google servers
			global $soapClients;
			$someSoapClient = $soapClients->getAdClient();
			// then recreate it with the new description2 set
			$soapParameters = "<addAds>
														<ads>
															<adGroupId>".$this->getBelongsToAdGroupId()."</adGroupId>
															<headline>".utf8_decode($this->getHeadline())."</headline>
															<description1>".utf8_decode($this->getDescription1())."</description1>
															<description2>".$newDescription2."</description2>
															<displayUrl>".$this->getDisplayUrl()."</displayUrl>
															<destinationUrl>".$this->getDestinationUrl()."</destinationUrl>
															<adType>TextAd</adType>
														</ads>
													</addAds>";
			// add the ad to the google servers
			$someAd = $someSoapClient->call("addAds", $soapParameters);
			$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
			if ($someSoapClient->fault) {
		  	pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setDescription2()", $soapParameters);
		    return false;
			}
			// first delete the current ad
			$soapParameters = "<updateAds>
													 <ads>
														 <adGroupId>".$this->getBelongsToAdGroupId()."</adGroupId>
														 <id>".$this->getId()."</id>
														 <status>Disabled</status>
														 <adType>TextAd</adType>
												 	 </ads>
												 </updateAds>";
			// delete the ad on the google servers
			$someSoapClient->call("updateAds", $soapParameters);
			$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
			if ($someSoapClient->fault) {
		  	pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setDescription2()", $soapParameters);
		    return false;
			}
			// update local object
			$this->description2 = $someAd['addAdsReturn']['description2'];
			// changing the description2 of a ad will change its id so update local object
			$this->id = $someAd['addAdsReturn']['id'];
			return true;
		}

		function setDisplayUrl ($newDisplayUrl) {
			// setting the display url is not provided by the api so emulating this
			// by deleting and then re-creating the ad
			// update the google servers
			global $soapClients;
			$someSoapClient = $soapClients->getAdClient();
			// then recreate it with the new display url set
			$soapParameters = "<addAds>
														<ads>
															<adGroupId>".$this->getBelongsToAdGroupId()."</adGroupId>
															<headline>".utf8_decode($this->getHeadline())."</headline>
															<description1>".utf8_decode($this->getDescription1())."</description1>
															<description2>".utf8_decode($this->getDescription2())."</description2>
															<displayUrl>".$newDisplayUrl."</displayUrl>
															<destinationUrl>".$this->getDestinationUrl()."</destinationUrl>
															<adType>TextAd</adType>
														</ads>
													</addAds>";
			// add the ad to the google servers
			$someAd = $someSoapClient->call("addAds", $soapParameters);
			$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
			if ($someSoapClient->fault) {
		  	pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setDisplayUrl()", $soapParameters);
		    return false;
			}
			// first delete the current ad
			$soapParameters = "<updateAds>
													 <ads>
														 <adGroupId>".$this->getBelongsToAdGroupId()."</adGroupId>
														 <id>".$this->getId()."</id>
														 <status>Disabled</status>
														 <adType>TextAd</adType>
												 	 </ads>
												 </updateAds>";
			// delete the ad on the google servers
			$someSoapClient->call("updateAds", $soapParameters);
			$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
			if ($someSoapClient->fault) {
		  	pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setDisplayUrl()", $soapParameters);
		    return false;
			}
			// update local object
			$this->displayUrl = $someAd['addAdsReturn']['displayUrl'];
			// changing the display url of a ad will change its id so update local object
			$this->id = $someAd['addAdsReturn']['id'];
			return true;
		}

		function setDestinationUrl ($newDestinationUrl) {
			// setting the destination url is not provided by the api so emulating
			// this by deleting and then re-creating the ad
			// update the google servers
			global $soapClients;
			$someSoapClient = $soapClients->getAdClient();
			// then recreate it with the new destination url set
			$soapParameters = "<addAds>
														<ads>
															<adGroupId>".$this->getBelongsToAdGroupId()."</adGroupId>
															<headline>".utf8_decode($this->getHeadline())."</headline>
															<description1>".utf8_decode($this->getDescription1())."</description1>
															<description2>".utf8_decode($this->getDescription2())."</description2>
															<displayUrl>".$this->getDisplayUrl()."</displayUrl>
															<destinationUrl>".$newDestinationUrl."</destinationUrl>
															<adType>TextAd</adType>
														</ads>
													</addAds>";
			// add the ad to the google servers
			$someAd = $someSoapClient->call("addAds", $soapParameters);
			$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
			if ($someSoapClient->fault) {
		  	pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setDestinationUrl()", $soapParameters);
		    return false;
			}
			// first delete the current ad
			$soapParameters = "<updateAds>
													 <ads>
														 <adGroupId>".$this->getBelongsToAdGroupId()."</adGroupId>
														 <id>".$this->getId()."</id>
														 <status>Disabled</status>
														 <adType>TextAd</adType>
												 	 </ads>
												 </updateAds>";
			// delete the ad on the google servers
			$someSoapClient->call("updateAds", $soapParameters);
			$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
			if ($someSoapClient->fault) {
		  	pushFault($someSoapClient, $_SERVER['PHP_SELF'].":setDestinationUrl()", $soapParameters);
		    return false;
			}
			// update local object
			$this->destinationUrl = $someAd['addAdsReturn']['destinationUrl'];
			// changing the destination url of a ad will change its id so update local object
			$this->id = $someAd['addAdsReturn']['id'];
			return true;
		}
	}

	/*
	 IMAGE ADS
	*/

	class APIlityImageAd extends APIlityAd {
		// class attributes
	 	var $image = array();

		// constructor
		function APIlityImageAd (
		  $id,
		  $belongsToAdGroupId,
		  $image,
		  $displayUrl,
		  $destinationUrl,
		  $status,
		  $isDisapproved
		) {
			// we need to construct the superclass first, this is php-specific
			// object-oriented behaviour
			APIlityAd::APIlityAd(
			  $id,
			  $belongsToAdGroupId,
			  $displayUrl,
			  $destinationUrl,
			  $status,
			  $isDisapproved,
			  'ImageAd'
			);

			// now construct the image ad which inherits all other ad attributes
			$this->image = $image;
		}

		// XML output
		function toXml() {
			if ($this->getIsDisapproved())
			  $isDisapproved = "true";
			else
			  $isDisapproved = "false";
			$image = $this->getImage();
			$xml = "<ImageAd>
	<id>".$this->getId()."</id>
	<belongsToAdGroupId>".$this->getBelongsToAdGroupId()."</belongsToAdGroupId>
	<image>
  	<type>".$image['type']."</type>
  	<name>".$image['name']."</name>
  	<width>".$image['width']."</width>
  	<height>".$image['height']."</height>
  	<imageUrl>".$image['imageUrl']."</imageUrl>
  	<thumbnailUrl>".$image['thumbnailUrl']."</thumbnailUrl>
  	<mimeType>".$image['mimeType']."</mimeType>
	</image>
	<displayUrl>".$this->getDisplayUrl()."</displayUrl>
	<destinationUrl>".$this->getDestinationUrl()."</destinationUrl>
	<status>".$this->getStatus()."</status>
	<isDisapproved>".$isDisapproved."</isDisapproved>
</ImageAd>";
			return $xml;
		}

		// get functions
	  function getImage() {
			return $this->image;
		}

		// report function
		function getAdData() {
			$adData = array(
												'id' => $this->getId(),
												'belongsToAdGroupId' => $this->getBelongsToAdGroupId(),
												'image' => $this->getImage(),
												'displayUrl'=>$this->getDisplayUrl(),
												'destinationUrl'=>$this->getDestinationUrl(),
												'status'=>$this->getStatus(),
												'isDisapproved'=>$this->getIsDisapproved()
											);
			return $adData;
		}

		// set functions
		// none, as these functions would require the base64 data for uploading the
		// image ad again after	deleting it (emulating changes by first deleting
		// things and then recreating them)
	}

	/*
	  GENERIC CLASS FUNCTIONS FOR BOTH IMAGE AND TEXT ADS
	*/

	function createAdObject($givenAdGroupId, $givenAdId) {
		// this creates a local ad object
		global $soapClients;
		$someSoapClient = $soapClients->getAdClient();
		// prepare soap parameters
		$soapParameters = "<getAd>
													<adGroupId>".$givenAdGroupId."</adGroupId>
													<adId>".$givenAdId."</adId>
												</getAd>";
		// execute soap call
		$someAd = $someSoapClient->call("getAd", $soapParameters);
		$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
		if ($someSoapClient->fault) {
	  	pushFault($someSoapClient, $_SERVER['PHP_SELF'].":createAdObject()", $soapParameters);
	    return false;
		}
    // invalid ids are silently ignored. this is not what we want so put out a
    // warning and return without doing anything.
		if (empty($someAd)) {
			if (!SILENCE_STEALTH_MODE)
			  echo "<br /><b>APIlity PHP library => Warning: </b>Invalid Ad ID. No Ads found.";
			return null;
		}

		return receiveAd($someAd['getAdReturn']);
	}

	// add a ad to the server and create local object
	function addTextAd(
	  $belongsToAdGroupId,
	  $headline,
	  $description1,
	  $description2,
	  $status,
	  $displayUrl,
	  $destinationUrl,
	  $exemptionRequest = false,
	  $checkOnly = false
	) {
		// update the google servers
		global $soapClients;
		$someSoapClient = $soapClients->getAdClient();
		$adGroupIdXml = "";
		if($belongsToAdGroupId) {
		  $adGroupIdXml = "<adGroupId>".$belongsToAdGroupId."</adGroupId>";
		}
	  $soapParameters =       $adGroupIdXml."
														<headline>".$headline."</headline>
														<description1>".$description1."</description1>
														<description2>".$description2."</description2>
														<status>".$status."</status>
														<displayUrl>".$displayUrl."</displayUrl>
														<destinationUrl>".$destinationUrl."</destinationUrl>
														<adType>TextAd</adType>";
	  if ($exemptionRequest) {
	    $soapParameters .= "<exemptionRequest>".$exemptionRequest."</exemptionRequest>";
	  }
	  if ($checkOnly) return $soapParameters;
    $soapParameters = "<addAds>
													<ads>".
													  $soapParameters."
													</ads>
											</addAds>";
		// add the ad to the google servers
		$someAd = $someSoapClient->call("addAds", $soapParameters);
		$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
		if ($someSoapClient->fault) {
	    pushFault($someSoapClient, $_SERVER['PHP_SELF'].":addTextAd()", $soapParameters);
	    return false;
		}
		return receiveAd($someAd['addAdsReturn']);
	}

	function addTextAdList($ads) {
		// update the google servers
		global $soapClients;
		$someSoapClient = $soapClients->getAdClient();
		$soapParameters = "<addAds>";
		foreach ($ads as $ad) {
		  $adGroupIdXml = "";
			if (isset($ad['exemptionRequest'])) {
				$soapParameters .= "<ads>
										          <adGroupId>".$ad['belongsToAdGroupId']."<adGroupId>
															<headline>".$ad['headline']."</headline>
															<description1>".$ad['description1']."</description1>
															<description2>".$ad['description2']."</description2>
															<status>".$ad['status']."</status>
															<displayUrl>".$ad['displayUrl']."</displayUrl>
															<destinationUrl>".$ad['destinationUrl']."</destinationUrl>
															<adType>TextAd</adType>
															<exemptionRequest>".$ad['exemptionRequest']."</exemptionRequest>
														</ads>";
			}
			else {
				$soapParameters .= "<ads>
															<adGroupId>".$ad['belongsToAdGroupId']."</adGroupId>
															<headline>".$ad['headline']."</headline>
															<description1>".$ad['description1']."</description1>
															<description2>".$ad['description2']."</description2>
															<displayUrl>".$ad['displayUrl']."</displayUrl>
															<destinationUrl>".$ad['destinationUrl']."</destinationUrl>
															<adType>TextAd</adType>
														</ads>";
			}
		}
		$soapParameters .= "</addAds>";
		// add the ads to the google servers
		$someAds = $someSoapClient->call("addAds", $soapParameters);
		$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
		if ($someSoapClient->fault) {
	    pushFault($someSoapClient, $_SERVER['PHP_SELF'].":addTextAdList()", $soapParameters);
	    return false;
		}

		// when we have only one ad return a (one ad element) array  anyway
		$someAds = makeNumericArray($someAds);

		// create local objects
		$adObjects = array();
		foreach($someAds['addAdsReturn'] as $someAd) {
			$adObject = receiveAd($someAd);
			if (isset($adObject)) {
			  array_push($adObjects, $adObject);
			}
		}
		return $adObjects;
	}

	// this won't fail completely if only one ad fails but will cause a lot
	// of soap overhead
	function addTextAdsOneByOne($ads) {
		// this is just a wrapper to the addTextAdd function
		$adObjects = array();
		foreach ($ads as $ad) {
			if (isset($ad['exemptionRequest'])) {
				// with exemption request
				$adObject = addTextAd(
				  $ad['belongsToAdGroupId'],
				  $ad['headline'],
				  $ad['description1'],
				  $ad['description2'],
          $ad['status'],
				  $ad['displayUrl'],
				  $ad['destinationUrl'],
				  $ad['exemptionRequest']
				);
			}
			else {
				// without exemption request
				$adObject = addTextAd(
				  $ad['belongsToAdGroupId'],
				  $ad['headline'],
				  $ad['description1'],
				  $ad['description2'],
				  $ad['status'],
				  $ad['displayUrl'],
				  $ad['destinationUrl']
				);
			}
			array_push($adObjects, $adObject);
		}
		return $adObjects;
	}

	// add a ad to the server and create local object
	function addMobileAd(
	  $belongsToAdGroupId,
	  $businessName,
    $countryCode,
    $description,
    $headline,
    $markupLanguages,
    $mobileCarriers,
    $phoneNumber,
    $status,
    $displayUrl,
    $destinationUrl,
    $exemptionRequest = false,
    $checkOnly = false
  ) {
		// update the google servers
		global $soapClients;
		$someSoapClient = $soapClients->getAdClient();

    $mobileCarriersXml = "";
    foreach($mobileCarriers as $mobileCarrier) {
      $mobileCarriersXml .= "<mobileCarriers>".$mobileCarrier."</mobileCarriers>";
    }
    $markupLanguagesXml = "";
    foreach($markupLanguages as $markupLanguage) {
      $markupLanguagesXml .= "<markupLanguages>".$markupLanguage."</markupLanguages>";
    }
    if ($belongsToAdGroupId) {
		  $adGroupIdXml = "<adGroupId>".$belongsToAdGroupId."</adGroupId>";
		}
	  $soapParameters = 			$adGroupIdXml."
                      			<businessName>".$businessName."</businessName>
                            <countryCode>".$countryCode."</countryCode>
                            <description>".$description."</description>
                            <headline>".$headline."</headline>".
                            $markupLanguagesXml.
                            $mobileCarriersXml."
                            <phoneNumber>".$phoneNumber."</phoneNumber>
                            <status>".$status."</status>
														<displayUrl>".$displayUrl."</displayUrl>
														<destinationUrl>".$destinationUrl."</destinationUrl>
														<adType>MobileAd</adType>";
	  if ($exemptionRequest)
	    $soapParameters .= "<exemptionRequest>".$exemptionRequest."</exemptionRequest>";
	  if ($checkOnly) return $soapParameters;
    $soapParameters = "<addAds>
													<ads>".
													  $soapParameters."
													</ads>
											</addAds>";
		// add the ad to the google servers
		$someAd = $someSoapClient->call("addAds", $soapParameters);
		$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
		if ($someSoapClient->fault) {
	    pushFault($someSoapClient, $_SERVER['PHP_SELF'].":addMobileAd()", $soapParameters);
	    return false;
		}
		return receiveAd($adObject['addAdsReturn']);
	}

	// this won't fail completely if only one ad fails but will cause a lot
	// of soap overhead
	function addMobileAdsOneByOne($ads) {
		// this is just a wrapper to the addMobileAd function
		$adObjects = array();
		foreach ($ads as $ad) {
			$adObject = addMobileAd(
			  $ad['belongsToAdGroupId'],
    	  $ad['businessName'],
        $ad['countryCode'],
        $ad['description'],
        $ad['headline'],
        $ad['markupLanguages'],
        $ad['mobileCarriers'],
        $ad['phoneNumber'],
			  $ad['status'],
			  $ad['displayUrl'],
			  $ad['destinationUrl']
			);
			array_push($adObjects, $adObject);
		}
		return $adObjects;
	}

	function addMobileAdList($ads) {
		// update the google servers
		global $soapClients;
		$someSoapClient = $soapClients->getAdClient();
		$soapParameters = "<addAds>";
		foreach ($ads as $ad) {
		  $mobileCarriersXml = "";
      foreach($ad['mobileCarriers'] as $mobileCarrier) {
        $mobileCarriersXml .= "<mobileCarriers>".$mobileCarrier."</mobileCarriers>";
      }
      $markupLanguagesXml = "";
      foreach($ad['markupLanguages'] as $markupLanguage) {
        $markupLanguagesXml .= "<markupLanguages>".$markupLanguage."</markupLanguages>";
      }
  		if (isset($ad['exemptionRequest'])) {
    		$soapParameters .= "<ads>
    													<adGroupId>".$ad['belongsToAdGroupId']."</adGroupId>
                          	  <businessName>".$ad['businessName']."</businessName>
                              <countryCode>".$ad['countryCode']."</countryCode>
                              <description>".$ad['description']."</description>
                              <headline>".$ad['headline']."</headline>".
                              $markupLanguagesXml.
                              $mobileCarriersXml."
                              <phoneNumber>".$ad['phoneNumber']."</phoneNumber>
    													<status>".$ad['status']."</status>
    													<displayUrl>".$ad['displayUrl']."</displayUrl>
    													<destinationUrl>".$ad['destinationUrl']."</destinationUrl>
    													<adType>MobileAd</adType>
    													<exemptionRequest>".$ad['exemptionRequest']."</exemptionRequest>
    												</ads>";
			}
			else {
				$soapParameters .= "<ads>
    													<adGroupId>".$ad['belongsToAdGroupId']."</adGroupId>
                          	  <businessName>".$ad['businessName']."</businessName>
                              <countryCode>".$ad['countryCode']."</countryCode>
                              <description>".$ad['description']."</description>
                              <headline>".$ad['headline']."</headline>".
                              $markupLanguagesXml.
                              $mobileCarriersXml."
                              <phoneNumber>".$ad['phoneNumber']."</phoneNumber>
    													<status>".$ad['status']."</status>
    													<displayUrl>".$ad['displayUrl']."</displayUrl>
    													<destinationUrl>".$ad['destinationUrl']."</destinationUrl>
    													<adType>MobileAd</adType>
														</ads>";
			}
		}
		$soapParameters .= "</addAds>";
		// add the ads to the google servers
		$someAds = $someSoapClient->call("addAds", $soapParameters);
		$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
		if ($someSoapClient->fault) {
	    pushFault($someSoapClient, $_SERVER['PHP_SELF'].":addMobileAdList()", $soapParameters);
	    return false;
		}

		// when we have only one ad return a (one ad element) array  anyway
		$someAds = makeNumericArray($someAds);
		// create local objects
		$adObjects = array();
		foreach($someAds['addAdsReturn'] as $someAd) {
			$adObject = receiveAd($someAd);
			if (isset($adObject)) {
			  array_push($adObjects, $adObject);
			}
		}
		return $adObjects;
	}

	// add a ad to the server and create local object
	function addLocalBusinessAd(
	  $belongsToAdGroupId,
    $address,
    $businessImageLocation,
    $businessKey,
    $businessName,
    $city,
    $countryCode,
    $customIconLocation,
    $customIconId,
    $description1,
    $description2,
    $phoneNumber,
    $postalCode,
    $region,
    $stockIcon,
    $targetRadiusInKm,
    $status,
    $displayUrl,
    $destinationUrl,
    $exemptionRequest = false,
    $checkOnly = false
  ) {
		// update the google servers
		global $soapClients;
		$someSoapClient = $soapClients->getAdClient();
    // One of the three fields stockIcon, customIconId, and customIcon can be
    // used to indicate the image used to identify this ad on maps. These three
    // fields are mutually exclusive
    $iconXml = "";
    if ($customIconId) {
      $iconXml .= "<customIconId>".$customIconId."</customIconId>";
    }
    else if ($stockIcon) {
      $iconXml .= "<stockIcon>".$stockIcon."</stockIcon>";
    }
    else if ($customIconLocation) {
      $iconXml .= "<customIcon>
									   <data xsi:type=\"xsd:base64Binary\">".img2base64($customIconLocation)."</data>
										 <name>Custom Icon</name>
									 </customIcon>";
		}
		$adGroupIdXml = "";
		if ($belongsToAdGroupId) {
		  $adGroupIdXml = "<adGroupId>".$belongsToAdGroupId."</adGroupId>";
		}
	  $soapParameters =       $adGroupIdXml."
                            <address>".$address."</address>
                            <businessImage>
                              <data xsi:type=\"xsd:base64Binary\">".img2base64($businessImageLocation)."</data>
										          <name>Business Image</name>
										        </businessImage>
                            <businessKey>".$businessKey."</businessKey>
                            <businessName>".$businessName."</businessName>
                            <city>".$city."</city>
                            <countryCode>".$countryCode."</countryCode>".
                            $iconXml."
                            <description1>".$description1."</description1>
                            <description2>".$description2."</description2>
                            <phoneNumber>".$phoneNumber."</phoneNumber>
                            <postalCode>".$postalCode."</postalCode>
                            <region>".$region."</region>
                            <targetRadiusInKm>".$targetRadiusInKm."</targetRadiusInKm>
                            <status>".$status."</status>
														<displayUrl>".$displayUrl."</displayUrl>
														<destinationUrl>".$destinationUrl."</destinationUrl>
														<adType>LocalBusinessAd</adType>";
	  if ($exemptionRequest)
	    $soapParameters .= "<exemptionRequest>".$exemptionRequest."</exemptionRequest>";
    if ($checkOnly) return $soapParameters;
    $soapParameters = "<addAds>
													<ads>".
													  $soapParameters."
													</ads>
											</addAds>";

		// add the ad to the google servers
		$someAd = $someSoapClient->call("addAds", $soapParameters);
		$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
		if ($someSoapClient->fault) {
	    pushFault($someSoapClient, $_SERVER['PHP_SELF'].":addLocalBusinessAd()", $soapParameters);
	    return false;
		}
		return receiveAd($someAd['addAds']);
	}

	// this won't fail completely if only one ad fails but will cause a lot
	// of soap overhead
	function addLocalBusinessAdsOneByOne($ads) {
		// this is just a wrapper to the addMobileAd function
		$adObjects = array();
		foreach ($ads as $ad) {
			$adObject = addLocalBusinessAd(
			  $ad['belongsToAdGroupId'],
        $ad['address'],
        $ad['businessImageLocation'],
        $ad['businessKey'],
        $ad['businessName'],
        $ad['city'],
        $ad['countryCode'],
        $ad['customIconLocation'],
        $ad['customIconId'],
        $ad['description1'],
        $ad['description2'],
        $ad['phoneNumber'],
        $ad['postalCode'],
        $ad['region'],
        $ad['stockIcon'],
        $ad['targetRadiusInKm'],
			  $ad['status'],
			  $ad['displayUrl'],
			  $ad['destinationUrl']
			);
			array_push($adObjects, $adObject);
		}
		return $adObjects;
	}

	function addLocalBusinessAdList($ads) {
		// update the google servers
		global $soapClients;
		$someSoapClient = $soapClients->getAdClient();
		$soapParameters = "<addAds>";
		foreach ($ads as $ad) {
      // One of the three fields stockIcon, customIconId, and customIcon can be
      // used to indicate the image used to identify this ad on maps. These three
      // fields are mutually exclusive
      $iconXml = "";
      if ($ad['customIconId']) {
        $iconXml .= "<customIconId>".$ad['customIconId']."</customIconId>";
      }
      else if ($ad['stockIcon']) {
        $iconXml .= "<stockIcon>".$ad['stockIcon']."</stockIcon>";
      }
      else if ($ad['customIconLocation']) {
        $iconXml .= "<customIcon>
  									   <data xsi:type=\"xsd:base64Binary\">".img2base64($ad['customIconLocation'])."</data>
  										 <name>Custom Icon</name>
  									 </customIcon>";
  		}

  		if (isset($ad['exemptionRequest'])) {
    		$soapParameters .= "<ads>
    													<adGroupId>".$ad['belongsToAdGroupId']."</adGroupId>
                              <address>".$ad['address']."<address>
                              <businessImage>
                                <data xsi:type=\"xsd:base64Binary\">".img2base64($ad['businessImageLocation'])."</data>
										            <name>Business Image</name>
										          </businessImage>".
                              $iconXml."
                              <businessKey>".$ad['businessKey']."<businessKey>
                              <businessName>".$ad['businessName']."<businessName>
                              <city>".$ad['city']."<city>
                              <countryCode>".$ad['countryCode']."<countryCode>
                              <description1>".$ad['description1']."<description1>
                              <description2>".$ad['description2']."<description2>
                              <phoneNumber>".$ad['phoneNumber']."<phoneNumber>
                              <postalCode>".$ad['postalCode']."<postalCode>
                              <region>".$ad['region']."<region>
                              <targetRadiusInKm>".$ad['targetRadiusInKm']."<targetRadiusInKm>
    													<status>".$ad['status']."</status>
    													<displayUrl>".$ad['displayUrl']."</displayUrl>
    													<destinationUrl>".$ad['destinationUrl']."</destinationUrl>
    													<adType>LocalBusinessAd</adType>
    													<exemptionRequest>".$ad['exemptionRequest']."</exemptionRequest>
    												</ads>";
			}
			else {
				$soapParameters .= "<ads>
    													<adGroupId>".$ad['belongsToAdGroupId']."</adGroupId>
                              <address>".$ad['address']."<address>
                              <businessImage>
                                <data xsi:type=\"xsd:base64Binary\">".img2base64($ad['businessImageLocation'])."</data>
										            <name>Business Image</name>
										          </businessImage>".
                              $iconXml."
                              <businessKey>".$ad['businessKey']."<businessKey>
                              <businessName>".$ad['businessName']."<businessName>
                              <city>".$ad['city']."<city>
                              <countryCode>".$ad['countryCode']."<countryCode>
                              <description1>".$ad['description1']."<description1>
                              <description2>".$ad['description2']."<description2>
                              <phoneNumber>".$ad['phoneNumber']."<phoneNumber>
                              <postalCode>".$ad['postalCode']."<postalCode>
                              <region>".$ad['region']."<region>
                              <targetRadiusInKm>".$ad['targetRadiusInKm']."<targetRadiusInKm>
    													<status>".$ad['status']."</status>
    													<displayUrl>".$ad['displayUrl']."</displayUrl>
    													<destinationUrl>".$ad['destinationUrl']."</destinationUrl>
    													<adType>LocalBusinessAd</adType>
														</ads>";
			}
		}
		$soapParameters .= "</addAds>";
		// add the ads to the google servers
		$someAds = $someSoapClient->call("addAds", $soapParameters);
		$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
		if ($someSoapClient->fault) {
	    pushFault($someSoapClient, $_SERVER['PHP_SELF'].":addLocalBusinessAdList()", $soapParameters);
	    return false;
		}

		// when we have only one ad return a (one ad element) array  anyway
		$someAds = makeNumericArray($someAds);

		// create local objects
		$adObjects = array();
		foreach($someAds['addAdsReturn'] as $someAd) {
			$adObject = receiveAd($someAd);
			if (isset($adObject)) {
			  array_push($adObjects, $adObject);
			}
		}
		return $adObjects;
	}

	function removeAd(&$adObject) {
		// update the google servers
		global $soapClients;
		$someSoapClient = $soapClients->getAdClient();
		$soapParameters = "<updateAds>
												 <ads>
													 <adGroupId>".$adObject->getBelongsToAdGroupId()."</adGroupId>
													 <id>".$adObject->getId()."</id>
													 <status>Disabled</status>
													 <adType>".$adObject->getAdType()."</adType>
											 	 </ads>
											 </updateAds>";
		// delete the ad on the google servers
		$someSoapClient->call("updateAds", $soapParameters);
		$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
		if ($someSoapClient->fault) {
	    pushFault($someSoapClient, $_SERVER['PHP_SELF'].":removeAd()", $soapParameters);
	    return false;
		}
		// set status of the local object to "Disabled"
		$adObject->status =  "Disabled";
		// delete remote calling object
		$adObject = @$GLOBALS['adObject'];
		unset($adObject);
		return true;
	}

	function getAllAds($adGroupIds) {
		global $soapClients;
		$someSoapClient = $soapClients->getAdClient();
		$soapParameters = "<getAllAds>";
		foreach($adGroupIds as $adGroupId) {
			$soapParameters .= "<adGroupIds>".$adGroupId."</adGroupIds>";
		}
		$soapParameters .= "</getAllAds>";
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

	function getActiveAds($adGroupIds) {
		global $soapClients;
		$someSoapClient = $soapClients->getAdClient();
		$soapParameters = "<getActiveAds>";
		foreach($adGroupIds as $adGroupId) {
			$soapParameters .= "<adGroupIds>".$adGroupId."</adGroupIds>";
		}
		$soapParameters .= "</getActiveAds>";
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

	// add an image ad to the server and create local object
	function addImageAd(
	  $belongsToAdGroupId,
	  $imageLocation,
	  $name,
	  $status,
	  $displayUrl,
	  $destinationUrl,
	  $checkOnly = false
	) {
		// update the google servers
		global $soapClients;
		$someSoapClient = $soapClients->getAdClient();
		$adGroupIdXml = "";
		if ($belongsToAdGroupId) {
		  $adGroupIdXml = "<adGroupId>".$belongsToAdGroupId."</adGroupId>";
		}
		$soapParameters =      $adGroupIdXml."
											     <image>
											       <data xsi:type=\"xsd:base64Binary\">".img2base64($imageLocation)."</data>
											       <name>".$name."</name>
											     </image>
											     <status>".$status."</status>
											     <destinationUrl>".$destinationUrl."</destinationUrl>
											     <displayUrl>".$displayUrl."</displayUrl>
											     <adType>ImageAd</adType>";
		if ($checkOnly) return $soapParameters;
		// add the ad to the google servers
		$soapParameters = "<addAds>
											   <ads>".
											      $soapParameters."
											   </ads>
											 </addAds>";
		$someAd = $someSoapClient->call("addAds", $soapParameters);
		$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
		if ($someSoapClient->fault) {
	    pushFault($someSoapClient, $_SERVER['PHP_SELF'].":addImageAd()", $soapParameters);
	    return false;
		}
		// populate object attributes
		return receiveAd($someAd['addAdsReturn']);
	}

	// this won't fail completely if only one ad fails but will cause a lot
	// of soap overhead
	function addImageAdsOneByOne($ads) {
		// this is just a wrapper to the addImageAd function
		$adObjects = array();
		foreach ($ads as $ad) {
			$adObject = addImageAd(
			  $ad['belongsToAdGroupId'],
			  $ad['imageLocation'],
			  $ad['name'],
			  $ad['status'],
			  $ad['displayUrl'],
			  $ad['destinationUrl']
			);
			array_push($adObjects, $adObject);
		}
		return $adObjects;
	}

	function addImageAdList($ads) {
		// update the google servers
		global $soapClients;
		$someSoapClient = $soapClients->getAdClient();
		$soapParameters = "<addAds>";
		foreach ($ads as $ad) {
			$soapParameters .= "<ads>
														<adGroupId>".$ad['belongsToAdGroupId']."</adGroupId>
											      <image>
											        <data xsi:type=\"xsd:base64Binary\">".img2base64($ad['imageLocation'])."</data>
											        <name>".$ad['name']."</name>
											      </image>
                            <status>".$ad['status']."</status>
											      <destinationUrl>".$ad['destinationUrl']."</destinationUrl>
											      <displayUrl>".$ad['displayUrl']."</displayUrl>
											      <adType>ImageAd</adType>
													</ads>";
		}
		$soapParameters .= "</addAds>";
		// add the ads to the google servers
		$someAds = $someSoapClient->call("addAds", $soapParameters);
		$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
		if ($someSoapClient->fault) {
	    pushFault($someSoapClient, $_SERVER['PHP_SELF'].":addImageAdList()", $soapParameters);
	    return false;
		}
		$someAds = makeNumericArray($someAds);
		// create local objects
		$adObjects = array();
		foreach($someAds['addAdsReturn'] as $someAd) {
			$adObject = receiveAd($someAd);
			if (isset($adObject)) {
			  array_push($adObjects, $adObject);
			}
		}
		return $adObjects;
	}

	// add a commerce ad to the server and create local object
	function addCommerceAd(
	  $belongsToAdGroupId,
	  $description1,
    $description2,
    $headline,
    $prePriceAnnotation,
    $postPriceAnnotation,
    $priceString,
    $productImageLocation,
	  $status,
	  $displayUrl,
	  $destinationUrl,
	  $checkOnly = false
	) {
		// update the google servers
		global $soapClients;
		$someSoapClient = $soapClients->getAdClient();
		$adGroupIdXml = "";
		if ($belongsToAdGroupId) {
		  $adGroupIdXml = "<adGroupId>".$belongsToAdGroupId."</adGroupId>";
		}
		$soapParameters =      $adGroupIdXml."
                        	 <description1>".$description1."</description1>
                        	 <description2>".$description2."</description2>
                        	 <headline>".$headline."</headline>
                           <postPriceAnnotation>".$postPriceAnnotation."</postPriceAnnotation>
                           <prePriceAnnotation>".$prePriceAnnotation."</prePriceAnnotation>
                           <priceString>".$priceString."</priceString>
											     <productImage>
											       <data xsi:type=\"xsd:base64Binary\">".img2base64($productImageLocation)."</data>
											       <name>Product Image</name>
											     </productImage>
											     <status>".$status."</status>
											     <destinationUrl>".$destinationUrl."</destinationUrl>
											     <displayUrl>".$displayUrl."</displayUrl>
											     <adType>CommerceAd</adType>";
    if ($checkOnly) return $soapParameters;
    $soapParameters = "<addAds>
											   <ads>".
											     $soapParameters."
											   </ads>
											 </addAds>";
		// add the ad to the google servers
		$someAd = $someSoapClient->call("addAds", $soapParameters);
		$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
		if ($someSoapClient->fault) {
	    pushFault($someSoapClient, $_SERVER['PHP_SELF'].":addCommerceAd()", $soapParameters);
	    return false;
		}
		return receiveAd($someAd['addAdsReturn']);
	}

	// this won't fail completely if only one ad fails but will cause a lot
	// of soap overhead
	function addCommerceAdsOneByOne($ads) {
		// this is just a wrapper to the addImageAd function
		$adObjects = array();
		foreach ($ads as $ad) {
			$adObject = addCommerceAd(
			  $ad['belongsToAdGroupId'],
    	  $ad['description1'],
        $ad['description2'],
        $ad['headline'],
        $ad['postPriceAnnotation'],
        $ad['prePriceAnnotation'],
        $ad['priceString'],
        $ad['productImageLocation'],
			  $ad['status'],
			  $ad['displayUrl'],
			  $ad['destinationUrl']
			);
			array_push($adObjects, $adObject);
		}
		return $adObjects;
	}

	function addCommerceAdList($ads) {
		// update the google servers
		global $soapClients;
		$someSoapClient = $soapClients->getAdClient();
		$soapParameters = "<addAds>";
		foreach ($ads as $ad) {
			$soapParameters .= "<ads>
														<adGroupId>".$ad['belongsToAdGroupId']."</adGroupId>
                        	  <description1>".$ad['description1']."</description1>
                        	  <description2>".$ad['description2']."</description2>
                        	  <headline>".$ad['headline']."</headline>
                            <postPriceAnnotation>".$ad['postPriceAnnotation']."</postPriceAnnotation>
                            <prePriceAnnotation>".$ad['prePriceAnnotation']."</prePriceAnnotation>
                            <priceString>".$ad['priceString']."</priceString>
											      <productImage>
											        <data xsi:type=\"xsd:base64Binary\">".img2base64($ad['productImageLocation'])."</data>
											        <name>Product Image</name>
											      </productImage>
                            <status>".$ad['status']."</status>
											      <destinationUrl>".$ad['destinationUrl']."</destinationUrl>
											      <displayUrl>".$ad['displayUrl']."</displayUrl>
											      <adType>CommerceAd</adType>
													</ads>";
		}
		$soapParameters .= "</addAds>";
		// add the ads to the google servers
		$someAds = $someSoapClient->call("addAds", $soapParameters);
		$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
		if ($someSoapClient->fault) {
	    pushFault($someSoapClient, $_SERVER['PHP_SELF'].":addCommerceAdList()", $soapParameters);
	    return false;
		}
		$someAds = makeNumericArray($someAds);
		// create local objects
		$adObjects = array();
		foreach($someAds['addAdsReturn'] as $someAd) {
		  $adObject = receiveAd($someAd);
		  if (isset($adObject)) {
			  array_push($adObjects, $adObject);
			}
		}
		return $adObjects;
	}

  function addVideoAd(
	  $belongsToAdGroupId,
	  $imageLocation,
	  $name,
	  $video,
    $displayUrl,
    $destinationUrl,
    $status,
    $exemptionRequest = false,
    $checkOnly = false
  ) {
		// update the google servers
		global $soapClients;
		$someSoapClient = $soapClients->getAdClient();
		$adGroupIdXml = "";
		if ($belongsToAdGroupId) {
		  $adGroupIdXml = "<adGroupId>".$belongsToAdGroupId."</adGroupId>";
		}
		$soapParameters =      $adGroupIdXml."
		                       <image>
		                       	 <data xsi:type=\"xsd:base64Binary\">".img2base64($imageLocation)."</data>
											       <name>Still Image</name>
		                       </image>
                        	 <name>".$name."</name>
                        	 <video>
                             <videoId>".$video['videoId']."</videoId>
                           </video>
											     <status>".$status."</status>
											     <destinationUrl>".$destinationUrl."</destinationUrl>
											     <displayUrl>".$displayUrl."</displayUrl>
											     <adType>VideoAd</adType>";
    if ($checkOnly) return $soapParameters;
    $soapParameters = "<addAds>
											   <ads>".
											     $soapParameters."
											   </ads>
											 </addAds>";
		// add the ad to the google servers
		$someAd = $someSoapClient->call("addAds", $soapParameters);
		$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
		if ($someSoapClient->fault) {
	    pushFault($someSoapClient, $_SERVER['PHP_SELF'].":addVideoAd()", $soapParameters);
	    return false;
		}
		return receiveAd($someAd['addAdsReturn']);
  }

	function getMyBusinesses() {
		global $soapClients;
		$someSoapClient = $soapClients->getAdClient();
		$soapParameters = "<getMyBusinesses></getMyBusinesses>";
		$myBusinesses = $someSoapClient->call("getMyBusinesses", $soapParameters);
		$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
		if ($someSoapClient->fault) {
	    pushFault($someSoapClient, $_SERVER['PHP_SELF'].":getMyBusinesses()", $soapParameters);
	    return false;
		}
	  return $myBusinesses;
	}

	function findBusinesses($name, $address, $countryCode) {
		global $soapClients;
		$someSoapClient = $soapClients->getAdClient();
		$soapParameters = "<findBusinesses>
		                     <name>".$name."</name>
		                     <address>".$address."</address>
		                     <countryCode>".$countryCode."</countryCode>
		                   </findBusinesses>";
		$businesses = $someSoapClient->call("findBusinesses", $soapParameters);
		$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
		if ($someSoapClient->fault) {
	    pushFault($someSoapClient, $_SERVER['PHP_SELF'].":findBusinesses()", $soapParameters);
	    return false;
		}
	  return $businesses;
	}

  function receiveAd($someAd) {
		// populate class attributes
		$id = $someAd['id'];
		$belongsToAdGroupId = $someAd['adGroupId'];
		$destinationUrl = $someAd['destinationUrl'];
		$status = $someAd['status'];
		$isDisapproved = $someAd['disapproved'];
		$displayUrl = $someAd['displayUrl'];
		if (isset($someAd['video'])) {
		  $image = $someAd['image'];
		  $name = $someAd['name'];
		  $video = $someAd['video'];
  		$adObject = new APIlityVideoAd (
  		  $id,
  		  $belongsToAdGroupId,
  		  $image,
  		  $name,
  		  $video,
        $displayUrl,
        $destinationUrl,
        $status,
        $isDisapproved
      );
    }
		// these attributes apply just to image ads, so just assign these
		// attributes if we have an image ad
		else if (isset($someAd['image'])){
		  $image = array();
			$image['name'] = $someAd['image']['name'];
			$image['width'] = $someAd['image']['width'];
			$image['height'] = $someAd['image']['height'];
			$image['imageUrl'] = $someAd['image']['imageUrl'];
			$image['thumbnailUrl'] = $someAd['image']['thumbnailUrl'];
			$image['mimeType'] = $someAd['image']['mimeType'];
			$image['type'] = $someAd['image']['type'];
			$adObject = new APIlityImageAd(
			  $id,
			  $belongsToAdGroupId,
			  $image,
			  $displayUrl,
			  $destinationUrl,
			  $status,
			  $isDisapproved
			);
		}
		else if (isset($someAd['mobileCarriers'])) {
  		$businessName = @$someAd['businessName'];
      $countryCode = $someAd['countryCode'];
      $description = $someAd['description'];
      $headline = $someAd['headline'];
      $markupLanguages = $someAd['markupLanguages'];
      $mobileCarriers = $someAd['mobileCarriers'];
      $phoneNumber = $someAd['phoneNumber'];
      $adObject = new APIlityMobileAd (
  		  $id,
  		  $belongsToAdGroupId,
  		  $businessName,
        $countryCode,
        $description,
        $headline,
        $markupLanguages,
        $mobileCarriers,
        $phoneNumber,
        $displayUrl,
        $destinationUrl,
        $status,
        $isDisapproved
      );
		}
		else if (isset($someAd['targetRadiusInKm'])) {
		  $address = $someAd['address'];
      $businessImage = $someAd['businessImage'];
      $businessKey = $someAd['businessKey'];
      $businessName = $someAd['businessName'];
      $city = $someAd['city'];
      $countryCode = $someAd['countryCode'];
      $customIcon = $someAd['customIcon'];
      $customIconId = $someAd['customIconId'];
      $description1 = $someAd['description1'];
      $description2 = $someAd['description2'];
      $phoneNumber = $someAd['phoneNumber'];
      $postalCode = $someAd['postalCode'];
      $region = $someAd['region'];
      $stockIcon = $someAd['stockIcon'];
      $targetRadiusInKm = $someAd['targetRadiusInKm'];
      $adObject = new APIlityLocalBusinessAd (
        $id,
        $belongsToAdGroupId,
        $address,
        $businessImage,
        $businessKey,
        $businessName,
        $city,
        $countryCode,
        $customIcon,
        $customIconId,
        $description1,
        $description2,
        $phoneNumber,
        $postalCode,
        $region,
        $stockIcon,
        $targetRadiusInKm,
        $displayUrl,
        $destinationUrl,
        $status,
        $isDisapproved
      );
    }
    else if (isset($someAd['postPriceAnnotation'])) {
      $description1 = $someAd['description1'];
      $description2 = $someAd['description2'];
      $headline = $someAd['headline'];
      $postPriceAnnotation = $someAd['postPriceAnnotation'];
      $prePriceAnnotation = $someAd['prePriceAnnotation'];
      $priceString = $someAd['priceString'];
      $productImage = $someAd['productImage'];
		  $adObject = new APIlityCommerceAd (
  		  $id,
  		  $belongsToAdGroupId,
        $description1,
        $description2,
        $headline,
        $postPriceAnnotation,
        $prePriceAnnotation,
        $priceString,
        $productImage,
        $displayUrl,
        $destinationUrl,
        $status,
        $isDisapproved
      );
    }
		else if (isset($someAd['headline'])) {
		  $headline = $someAd['headline'];
		  $description1 = $someAd['description1'];
		  $description2 = $someAd['description2'];
		  $adObject = new APIlityTextAd (
		    $id,
		    $belongsToAdGroupId,
		    $headline,
		    $description1,
		    $description2,
		    $displayUrl,
		    $destinationUrl,
		    $status,
		    $isDisapproved
		  );
		}
    return $adObject;
  }

  function checkAdList($ads, $languages, $newGeoTargets) {
		global $soapClients;
		$someSoapClient = $soapClients->getAdClient();

		$soapParameters = "<checkAds>";
		foreach ($ads as $ad) {
      $soapParameters .= "<ads>";

      if (array_key_exists('imageLocation', $ad)) {
        $soapParameters .= addImageAd(
          "",
  			  $ad['imageLocation'],
  			  $ad['name'],
  			  $ad['status'],
  			  $ad['displayUrl'],
  			  $ad['destinationUrl'],
  			  true
  			);
      }
      else if (array_key_exists('mobileCarriers', $ad)) {
        $soapParameters .= addMobileAd(
      	  "",
      	  $ad['businessName'],
          $ad['countryCode'],
          $ad['description'],
          $ad['headline'],
          $ad['markupLanguages'],
          $ad['mobileCarriers'],
          $ad['phoneNumber'],
  			  $ad['status'],
  			  $ad['displayUrl'],
  			  $ad['destinationUrl'],
  			  true
  			);
      }
      else if (array_key_exists('targetRadiusInKm', $ad)) {
        $soapParameters .= addLocalBusinessAd(
          "",
          $ad['address'],
          $ad['businessImageLocation'],
          $ad['businessKey'],
          $ad['businessName'],
          $ad['city'],
          $ad['countryCode'],
          $ad['customIconLocation'],
          $ad['customIconId'],
          $ad['description1'],
          $ad['description2'],
          $ad['phoneNumber'],
          $ad['postalCode'],
          $ad['region'],
          $ad['stockIcon'],
          $ad['targetRadiusInKm'],
  			  $ad['status'],
  			  $ad['displayUrl'],
  			  $ad['destinationUrl'],
  			  true
  			);
      }
      else if (array_key_exists('postPriceAnnotation', $ad)) {
			  $soapParameters .= addCommerceAd(
			    "",
      	  $ad['description1'],
          $ad['description2'],
          $ad['headline'],
          $ad['postPriceAnnotation'],
          $ad['prePriceAnnotation'],
          $ad['priceString'],
          $ad['productImageLocation'],
  			  $ad['status'],
  			  $ad['displayUrl'],
  			  $ad['destinationUrl'],
  			  true
  			);
      }
      else if (array_key_exists('headline', $ad)) {
        $soapParameters .= addTextAd(
          "",
				  $ad['headline'],
				  $ad['description1'],
				  $ad['description2'],
          $ad['status'],
				  $ad['displayUrl'],
				  $ad['destinationUrl'],
				  @$ad['exemptionRequest'],
				  true
				);
      }
      else if (array_key_exists('video', $ad)) {
        $soapParameters .= addVideoAd(
    		  "",
    		  $ad['image'],
    		  $ad['name'],
    		  $ad['video'],
          $ad['displayUrl'],
          $ad['destinationUrl'],
          $ad['status'],
          @$ad['exemptionRequest'],
          true
        );
      }
			$soapParameters .= "</ads>";
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

    $soapParameters .= "<geoTarget>".$newGeoTargetsXml."</geoTarget></checkAds>";

	 	// query the google servers
	 	$adsCheck = $someSoapClient->call('checkAds', $soapParameters);
	 	$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
		if ($someSoapClient->fault)	{
	  	pushFault($someSoapClient, $_SERVER['PHP_SELF'].':checkAdList()', $soapParameters);
	    return false;
		}
    return makeNumericArray($adsCheck);
  }

  function getMyVideos() {
		global $soapClients;
		$someSoapClient = $soapClients->getAdClient();
		$soapParameters = "<getMyVideos>
		                   </getMyVideos>";
		$videos = $someSoapClient->call("getMyVideos", $soapParameters);
		$soapClients->updateSoapRelatedData(extractSoapHeaderInfo($someSoapClient->getHeaders()));
		if ($someSoapClient->fault) {
	    pushFault($someSoapClient, $_SERVER['PHP_SELF'].":getMyVideos()", $soapParameters);
	    return false;
		}
	  return makeNumericArray($videos);
  }
?>