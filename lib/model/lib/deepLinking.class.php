<?php
/**
 * CLASS deepLinking
 * This class specifies conditions required by iOS to allow deep linking and accordingly return the header if the conditions are satisfied
 */
class deepLinking
{
	//constants to be used in the query, passed as parameters in method calls
	 const FLAG_VALUE = "Y";
	 const LANDING_SCREEN = "viewProfile";
	 const OS_TYPE = "IOS";
	 const APP_VERSION = "2.2";
	 const WEBSITE_VERSION = "'I'";

	 /*
	 * function to check if all the required conditions are met to redirect the user to App and accordingly send the header or the return value
	 * @param $request : sfWebRequest
	 * @return value :header for redirecting or 0
	 */
	public function getDeepLinkingHeader($request)
	{	
	    $loginData=$request->getAttribute("loginData");
	    if(!$loginData)
			return;
		$authchecksum = $loginData["AUTHCHECKSUM"];

		$this->loggedInProfileId = $loginData["PROFILEID"];
		$this->loggedInUsername = $loginData["USERNAME"];
		$this->loggedInGender = $loginData["GENDER"];
		if(!$this->loggedInProfileId)
			return;
		$isIosDeep = JsMemcache::getInstance()->get("iosDeepLinking_".$this->loggedInProfileId);

		if(!$isIosDeep && MobileCommon::isIOSPhone())
		{

			$this->date =  date("Y-m-d H:i:s", strtotime("-1 week"));
			$conditionValue = $this->verifyDeepLinkingConditions();
			if (JsConstants::$hideUnimportantFeatureAtPeakLoad <= 1) 
			{
				$loggedInData = $this->loggedInUserCondition($this->loggedInProfileId);	
			}
			else
			{
				$loggedInData = 1;
			}
			if($conditionValue && $loggedInData)
			{
				$isIosDeep = 1;
			}
		}
		if($isIosDeep==1)
		{
			$SITE_URL = str_replace("http:","",sfConfig::get('app_site_url'));
			$trackingId = $this->fetchApiData($request);
			$profilechecksum=$request->getParameter('profilechecksum');
			$stype = $request->getParameter('stype');
			JsMemcache::getInstance()->set("iosDeepLinking_".$this->loggedInProfileId,1);				
			$headerURL = 'comjeevansathi:'.$SITE_URL.'?{"profilechecksum":"'.$profilechecksum.'","trackingId":"'.$trackingId.'","landingScreen":"'.self::LANDING_SCREEN.'","stype":"'.$stype.'","authchecksum":"'.$authchecksum.'","username":"'.$this->loggedInUsername.'","gender":"'.$this->loggedInGender.'"}';
			return($headerURL);
		}
		JsMemcache::getInstance()->set("iosDeepLinking_".$this->loggedInProfileId,2);		
	}

	/*
	 * function called by apiDeepLinkingTrackingV1 to place an entry of the user in MOBILE_API.DEEP_LINKING_TRACKING and retrieve trakcingId
	 * @param  values : $profileId,$viewerProfileId
	 * @return value : $trackId
	 */
	public function trackingUser($profileId,$viewerProfileId)
	{
		$trackingObj= new MOBILE_API_DEEP_LINKING_TRACKING();
		$trackId = $trackingObj->setTrackingData($profileId,$viewerProfileId);
		unset($trackingObj);
		return $trackId;
	}

	public function loggedInUserCondition($loggedInProfileId)
	{	
		$loggedInProfileId = "'".$loggedInProfileId."'";
		$loginTrackingObj = new MIS_LOGIN_TRACKING('newjs_slave');
		$loggedInIDArr = $loginTrackingObj->getLastLoginProfilesForDate($loggedInProfileId,$this->date,self::WEBSITE_VERSION);
		if($loggedInIDArr)
		{
		 	return 1;
		}
		else
		{
			return 0;
		}
	}

	/*
	 * function is used to verify required conditions for deep linking and accordingly return 1 or 0
	 * @param
	 * @return value :1 or 0
	 */
	public function verifyDeepLinkingConditions()
	{
		 $sourceAppObj = new MOBILE_API_REGISTRATION_ID();
		 $resultArray = $sourceAppObj->getArray(array("PROFILEID"=>$this->loggedInProfileId,"OS_TYPE"=>self::OS_TYPE),"",array("APP_VERSION"=>self::APP_VERSION));
		 if($resultArray)
		 {
		 	return 1;
		 }
		 else
		 {
		 	return 0;
		 }
	}

	/*
	 * function is used to call apiDeepLinkingTrackingV1 internally
	 * @param $request : sfWebRequest
	 * @return $data
	 */
	public function fetchApiData($request)
	{ 	//echo($this->loggedInChecksum);die;
		$request->setParameter('loggedInProfileId',$this->loggedInProfileId);
		ob_start();
		$data = sfContext::getInstance()->getController()->getPresentationFor('profile', 'apiDeepLinkingTrackingV1');
		$output = ob_get_contents();
		ob_end_clean();
		$data = json_decode($output, true);
		return $data;
	}

	/*
	 * function is used call MOBILE_API_DEEP_LINKING_TRACKING to verify the trackingId corresponding to the same viewerProfileId and viewedProfileId and accordinly update Flag
	 * @param $profileId,$trackedId,$viewerProfileId
	 * @return 
	 */
	public function verifyTrackingEntry($profileId,$trackedId,$viewerProfileId)
	{
		$deepLinkingTrackingObj= new MOBILE_API_DEEP_LINKING_TRACKING();
		$updatedRows = $deepLinkingTrackingObj->upadteTrackingData($profileId,$trackedId,$viewerProfileId,self::FLAG_VALUE);
		return $updatedRows;
	}
}
