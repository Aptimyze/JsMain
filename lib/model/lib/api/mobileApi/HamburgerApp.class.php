<?php
class HamburgerApp
{
	public static function getHamburgerDetails($profileid,$version='',$forwardingArray)
        {
		$moduleName = $forwardingArray['moduleName'];
		$actionName = $forwardingArray['actionName'];
		$appVersion=sfContext::getInstance()->getRequest()->getParameter("API_APP_VERSION")?sfContext::getInstance()->getRequest()->getParameter("API_APP_VERSION"):0; 
		if($profileid && RequestHandlerConfig::$moduleActionHamburgerArray[$moduleName][$actionName])
		{
                        $isNewMobileSite = MobileCommon::isNewMobileSite();
		$profileObj=LoggedInProfile::getInstance('newjs_master');
		$profileObj->getDetail("","","*");
		$profilePic = $profileObj->getHAVEPHOTO();
		if (empty($profilePic))
			$profilePic="N";
		if($profilePic  && $profilePic!="N")
		{
			$pictureServiceObj=new PictureService($profileObj);
			$profilePicObj = $pictureServiceObj->getProfilePic();
			if($profilePicObj)
                        {
			if($profilePic=='U')	
				$picUrl = $profilePicObj->getProfilePic235Url();
			else
				$picUrl = $profilePicObj->getProfilePic120Url();
			$photoArray = PictureFunctions::mapUrlToMessageInfoArr($picUrl,'ThumbailUrl','',$profileObj->getGENDER());
                        $thumbNail =$photoArray;
			}
			
		}
                else
                {
                        $thumbNail = PictureService::getRequestOrNoPhotoUrl('noPhoto','ThumbailUrl',$profileObj->getGENDER());
                        $thumbNail = PictureFunctions::mapUrlToMessageInfoArr($thumbNail,'ThumbailUrl');
                }

			$hamburgerDetails['THUMBNAIL']=$thumbNail;
			$request = sfContext::getInstance()->getRequest();
			$memHandlerObj = new MembershipHandler();
			$data2 = $memHandlerObj->fetchHamburgerMessage($request);
			$membershipMessage = $data2['hamburger_message'];
			$hamburgerDetails["MEMBERSHIPT_TOP"] = $membershipMessage["top"]?$membershipMessage["top"]:null;
			$hamburgerDetails["MEMBERSHIPT_BOTTOM"] = $membershipMessage["bottom"]?$membershipMessage["bottom"]:null;
			$profileMemcacheObj = new ProfileMemcacheService($profileObj);
			$hamburgerDetails['AWAITING_RESPONSE_NEW']=$profileMemcacheObj->get("AWAITING_RESPONSE_NEW");
			$hamburgerDetails['AWAITING_RESPONSE']=$profileMemcacheObj->get("AWAITING_RESPONSE");
			$hamburgerDetails['FILTERED']=$profileMemcacheObj->get("FILTERED");
			$hamburgerDetails["FILTERED_NEW"] = $profileMemcacheObj->get("FILTERED_NEW");
				if(!$hamburgerDetails["FILTERED_NEW"]){
					$hamburgerDetails["FILTERED_NEW"] = 0;
				}
			$hamburgerDetails['ACC_ME_NEW']=$profileMemcacheObj->get("ACC_ME_NEW");
			if(JsConstants::$hideUnimportantFeatureAtPeakLoad >= 1 || ($isApp=='A' && $appVersion>89))
				$hamburgerDetails['MESSAGE_NEW']=0;
			else
	          	$hamburgerDetails['MESSAGE_NEW']= $isNewMobileSite ? $profileMemcacheObj->get("MESSAGE_NEW") : 0;
	                
	        if(JsConstants::$hideUnimportantFeatureAtPeakLoad >= 2)
				$hamburgerDetails['MATCHALERT']=0;
			else
				$hamburgerDetails['MATCHALERT']=$profileMemcacheObj->get("MATCHALERT_TOTAL");
			if(MobileCommon::isIOSApp() || MobileCommon::isAndroidApp())
			{
				$hamburgerDetails['VISITOR_ALERT']=0;
				//$hamburgerDetails['VISITOR_ALERT']=$profileMemcacheObj->get("VISITORS_ALL");
			}
			else
			{
				$hamburgerDetails['VISITOR_ALERT']=0;
				//$hamburgerDetails['VISITOR_ALERT']=$profileMemcacheObj->get("VISITOR_ALERT");
			}
			
			$hamburgerDetails['VISITORS_ALL']=0;
			//$hamburgerDetails['VISITOR_ALERT']=$profileMemcacheObj->get("VISITORS_ALL");
			if(JsConstants::$hideUnimportantFeatureAtPeakLoad >= 2){
				$hamburgerDetails['BOOKMARK']=0;
				$hamburgerDetails['JUST_JOINED_COUNT'] = 0;
				$hamburgerDetails['JUST_JOINED_NEW'] = 0;
			}
			else{
				$hamburgerDetails['BOOKMARK']=$profileMemcacheObj->get("BOOKMARK");
				$hamburgerDetails['JUST_JOINED_COUNT'] = $profileMemcacheObj->get('JUST_JOINED_MATCHES');
				$hamburgerDetails['JUST_JOINED_NEW'] = $profileMemcacheObj->get('JUST_JOINED_MATCHES_NEW');
			}
				$hamburgerDetails['INTEREST_PENDING'] = $profileMemcacheObj->get('AWAITING_RESPONSE')+$profileMemcacheObj->get('NOT_REP');
				$hamburgerDetails['ACCEPTED_MEMBERS'] = $profileMemcacheObj->get('ACC_ME')+$profileMemcacheObj->get('ACC_BY_ME');
				$hamburgerDetails['ACC_ME'] = $profileMemcacheObj->get('ACC_ME');
				$hamburgerDetails['ACC_BY_ME'] = $profileMemcacheObj->get('ACC_BY_ME');
				if(MobileCommon::isApp() == "I" || $isNewMobileSite)
				{
					$request->setParameter("perform","count");
					ob_start();
					$jsonData = sfContext::getInstance()->getController()->getPresentationFor("search", "saveSearchCallV1");
					$output = ob_get_contents();
					ob_end_clean();
					$savedSearchCountData = json_decode($output,true);
					if($savedSearchCountData['saveDetails']['count'])
						$hamburgerDetails['SAVE_SEARCH'] = $savedSearchCountData['saveDetails']['count'];
					else
						$hamburgerDetails['SAVE_SEARCH'] = 0;
				}
			if(sfContext::getInstance()->getRequest()->getParameter("androidMyjsNew")){
				if(JsConstants::$hideUnimportantFeatureAtPeakLoad >= 2)
	         		$hamburgerDetails['PHOTO_REQUEST_NEW']=0;
		     	else
		     		$hamburgerDetails['PHOTO_REQUEST_NEW']=JsCommon::convert99($profileMemcacheObj->get("PHOTO_REQUEST_NEW"));

		     	$declinedMeNewMemcacheCount=$profileMemcacheObj->get('DEC_ME_NEW');
				 if($declinedMeNewMemcacheCount)
					$hamburgerDetails['DEC_ME_NEW']=JsCommon::convert99($declinedMeNewMemcacheCount);
				else
					$hamburgerDetails['DEC_ME_NEW'] = 0;
				
				$declinedMeCount=$profileMemcacheObj->get('DEC_ME');
				 if($declinedMeCount)
					$hamburgerDetails['DEC_ME']=JsCommon::convert99($declinedMeCount);
				else
					$hamburgerDetails['DEC_ME'] = 0;
				
				$declinedByMeCount=$profileMemcacheObj->get('DEC_BY_ME');
				 if($declinedByMeCount)
					$hamburgerDetails['DEC_BY_ME']=JsCommon::convert99($declinedByMeCount);
				else
					$hamburgerDetails['DEC_BY_ME'] = 0;
				
			$hamburgerDetails['TOTAL_NEW']=JsCommon::convert99($hamburgerDetails['AWAITING_RESPONSE_NEW'] + $hamburgerDetails['ACC_ME_NEW'] + $$hamburgerDetails['MESSAGE_NEW'] + $hamburgerDetails['PHOTO_REQUEST_NEW'] + $hamburgerDetails['JUST_JOINED_NEW'] + $hamburgerDetails["FILTERED_NEW"] + $hamburgerDetails['DEC_ME_NEW']);
		     }
				
			return $hamburgerDetails;
		}
        }
}
?>
