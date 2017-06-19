<?php
/**
This class handles all reponse tracking for contact actions
**/
class JSResponseTracking {
		
	/** 
	 * calculate response tracking string on profile page 	
	 * name: getProfilePageTracking
	 * parameter: request object
	 * return responsetracking string
	 * */
	public static function getProfilePageTracking($request)
	{	
		$responseTracking = $request->getParameter("responseTracking");
		
		if(MobileCommon::isApp())
		{
			if(MobileCommon::isApp()=="I")
				$profileTracking = JSTrackingPageType::PROFILE_PAGE_IOS;
			else
				$profileTracking = JSTrackingPageType::PROFILE_PAGE_APP;
		}
		else
		{
			$profileTracking = JSTrackingPageType::PROFILE_PAGE;
		}
		if($responseTracking)
		{$track = explode("-",$responseTracking);
			if($track[sizeof($track)-1] != $profileTracking)
				$responseTracking = $responseTracking."-".$profileTracking;
			
		}	
		else if($request->getParameter("fromPage") == "contacts" && $request->getParameter("page") == "eoi")
		{
			$responseTracking = JSTrackingPageType::CONTACT_AWAITING."-".$profileTracking;
		}
		else if($request->getParameter("fromPage") == "contacts" && $request->getParameter("page") != "eoi")
		{
			$responseTracking = JSTrackingPageType::CONTACT_OTHER."-".$profileTracking;
		}
		else
		{
			$responseTracking = JSTrackingPageType::OTHER."-".$profileTracking;
		}
		return $responseTracking;
	}
	
	/** 
	 * insert into response tracking table
	 * name: updateResponseTracking
	 * parameter: contactHandler object, responsetracking string
	 * */
	 public static function updateResponseTracking($contactHandlerObj, $responseTracking)
	 {
		$currentFlag	=	$contactHandlerObj->getContactType();//depends on function provided by nikhil
		if(!$responseTracking)
			$responseTracking = 0;
		if($currentFlag == ContactHandler::INITIATED)
		{
			$dbname = JsDbSharding::getShardNo($contactHandlerObj->getViewer()->getPROFILEID());
			$responseTrackingObj = new MIS_RESPONSETRACKING($dbname);
			$responseTrackingObj->insert($contactHandlerObj->getContactObj()->getContactId(),$contactHandlerObj->getViewer()->getPROFILEID(),$contactHandlerObj->getToBeType(),$responseTracking);
		}
	}
}
