<?php

/**
 * common actions.
 *
 * @package    jeevansathi
 * @subpackage common
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class commonActions extends sfActions
{

	/*      
	* This Function perform the bookmark action from the search tupple
	*/
	public function executeAddBookmark(sfWebRequest $request)
	{
		//print_r($request->getParameterHolder()->getAll());
		$loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
		$bookmarker = $loggedInProfileObj->getPROFILEID();

		if($bookmarker != '')
		{
//			print_r($request->getParameterHolder()->getAll());
//                      $bookmarker = $request->getParameter('bookmarker');
			$bookmarkeeChecksum = $request->getParameter('bookmarkee');
			$bookmarkee = JsAuthentication::jsDecryptProfilechecksum($bookmarkeeChecksum);
			$bookmarknote = urldecode($request->getParameter('bookmarknote'));
			$bookmarknote = str_replace("**-**","/",$bookmarknote);
			$bookmarknote = str_replace("**--**",".",$bookmarknote);
			$bookmarkObj = new Bookmarks();
			if($bookmarknote == '')
			{
				$bookmarkObj->addBookmark($bookmarker,$bookmarkee);
				$profileMemcacheObj = new ProfileMemcacheService($bookmarker);
				$profileMemcacheObj->update("BOOKMARK",1);
				$profileMemcacheObj->updateMemcache();
			}
			else
				$bookmarkObj->addBookmark($bookmarker,$bookmarkee,$bookmarknote);
				
			
			echo "success";
		}
		else
			echo "logout";
		die;
	}

// this method sends the response for the various phone verification layers 
	public function executePhoneVerifyLayer(sfWebRequest $request)
	{	
		$layerType=$request->getParameter('layerType');
		$phoneType=$request->getParameter('phoneType') ? $request->getParameter('phoneType') : 'M';
		switch ($layerType)
		{
		case 'verify':
			$loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
			$profileid = $loggedInProfileObj->getPROFILEID();
			$isd=$loggedInProfileObj->getISD();
			if($loggedInProfileObj->getPHONE_MOB())
			$knowlarityObj=new phoneKnowlarity($loggedInProfileObj,$phoneType);
			$response[DIAL_NUMBER] =$knowlarityObj->getVirtualNumber();
			$this->dialNumber = $response[DIAL_NUMBER];
			$this->setTemplate("verifyLayer");
		break;

		case 'failed':
			$this->setTemplate("failedLayer");
		break;	
	
		case 'success' :
			$this->setTemplate("successLayer");
		break;
		}


		
	}



	/*      
	* This Function perform the forward profile action from the search tupple.
	*/
	public function executeForwardProfileLayer(sfWebRequest $request)
	{
		//print_r($request->getParameterHolder()->getAll());
		$this->forwardedProfileChecksum = $request->getParameter('forwardedProfileChecksum');

		$loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
		$loggedInProfileObj->getDetail("","","EMAIL");
		$this->loggedInEmail = $loggedInProfileObj->getEMAIL();
		$this->loggedInProfileid = $loggedInProfileObj->getPROFILEID();
	}
	public function executeGotItUpdate(sfWebRequest $request)
        {
		$pageToUpdate = $request->getParameter('GotItBandPage');
		if($pageToUpdate)
		{
			$loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
			$gotItBandObj = new GotItBand($loggedInProfileObj->getPROFILEID());
			$gotItBandObj->setPageBandDone($pageToUpdate);
			return true;
		}
		return;
	}
	public function executeAppPromotionDesktop(sfWebRequest $request)
	{
		if($request->getParameter("submit"))
		{
			if($this->phone = $request->getParameter("phone"))
			{
				
				if($request->getParameter("alreadySent") != $this->phone)
				{
					/*
					$PromoSmsObj= new sms_PromoSms("newjs_master");
					$count = $PromoSmsObj->getCount($this->phone);
					if(!$count)
						$count=0;
					if($count<10){
						$message="Dear Jeevansathi User, Download the new Jeevansathi Android  App at: ".sfConfig::get("app_site_url")."/SMS-Download-Android-App";
						include_once(sfConfig::get("sf_web_dir"). "/classes/SmsVendorFactory.class.php");
						$smsVendorObj = SmsVendorFactory::getSmsVendor("air2web");
	                    $xmlResponse = $smsVendorObj->generateXml(rand(10,10000),$this->phone,$message);
	                    $smsVendorObj->send($xmlResponse,"transaction");
	                    $count++;
	                    if($count==1)
							$PromoSmsObj->Insert($this->phone,$count,"");
						else
							$PromoSmsObj->Update($this->phone,$count);
						$this->sent = "Y";
						$this->alreadySent = $this->phone;
						$this->limit='0';
					}
					else
						$this->limit='1';*/
					$this->sent = "Y";
				}
				else{
					$this->alreadySent = $request->getParameter("alreadySent") ;
					$this->sent = "N";
				}
			}
		else
		{
			$this->alreadySent = 0;
			$this->sent = "N";
			if($request->getAttribute("profileid"))
			{
				$loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
				$loggedInProfileObj->getDetail("","","PHONE_MOB");
				$this->phone = $loggedInProfileObj->getPHONE_MOB();
			}
		}
	}
	$this->sent = "N";
}
	public function executeSmsDownloadAndroidApp(sfWebRequest $request)
	{
		$ua = $_SERVER['HTTP_USER_AGENT'];
		if(JsCommon::checkAppPromoValid($ua))
		{
			header("Location: https://play.google.com/store/apps/details?id=com.jeevansathi.android&referrer=utm_source%3Dorganic%26utm_medium%3Dmobile%26utm_content%3Dsms%26utm_campaign%3DJSAA");
			die;
		}
		else
		{
			$this->setTemplate('appNotCompatible');
		}
	}
	/*      
	* This Function perform the ignore action from the search tupple.
	*/
	public function executeIgnoreProfile(sfWebRequest $request)
	{
		$loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
		$profileid = $loggedInProfileObj->getPROFILEID();

		if($profileid != '')
		{
			$ignoredProfilechecksum = $request->getParameter('ignoredProfileid');
			$ignoredProfileid = JsAuthentication::jsDecryptProfilechecksum($ignoredProfilechecksum);

			$searchId = $request->getParameter('searchId');
			if($searchId)
			{
				$cookieVal = $_COOKIE["ignore_$searchId"]."*".$ignoredProfileid."*";
				setcookie ("ignore_$searchId","$cookieVal",time()+3600,"/");
			}

			$ignoreObj = new IgnoredProfiles();
			$ignoreObj->ignoreProfile($profileid, $ignoredProfileid);
		}
		else
			echo "loggedOut";

		die;
	}


	/*      
	* This Function perform the undo ignore action from the search tupple.
	*/
	public function executeUndoIgnoreProfile(sfWebRequest $request)
	{
		$loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
		$profileid = $loggedInProfileObj->getPROFILEID();

		if($profileid != '')
		{
			$ignoredProfilechecksum = $request->getParameter('ignoredProfileid');
			$ignoredProfileid = JsAuthentication::jsDecryptProfilechecksum($ignoredProfilechecksum);
					
			$searchId = $request->getParameter('searchId');
			if($searchId)
			{
				$cookieVal = str_replace("*$ignoredProfileid*","",$_COOKIE["ignore_$searchId"]);
				setcookie ("ignore_$searchId","$cookieVal",time()+3600,"/");
			}

			$ignoreObj = new IgnoredProfiles();
			$ignoreObj->undoIgnoreProfile($profileid, $ignoredProfileid);
		}
		else
			echo "loggedOut";

		die;
	}
	
	public function executeProfileMemcache($returnType="")
	{
		$loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
		$memcacheObj = new ProfileMemcacheService($loggedInProfileObj);
		$memcacheObj->setDataForGroup(ProfileMemcacheService::CONTACTS);
		$memcacheObj->setDataForGroup(ProfileMemcacheService::HOROSCOPE);
		$memcacheObj->setDataForGroup(ProfileMemcacheService::PHOTO_REQUEST);
		//$memcacheObj->setDataForGroup(ProfileMemcacheService::CUSTOM_MESSAGE);
		return true;
	}
	public function executeResetPassword(sfWebRequest $request)
	{       
		
		$this->d = $request->getParameter('d');
		$this->h = $request->getParameter('h');
		$SITE_URL = sfConfig::get('app_site_url');
		$this->getResponse()->setSlot("passwordReset",true);
		foreach(Password::$weakPasswords as $k=>$v)
		$this->weakPasswordArray[$k] = $v;
		if($request->getParameter("submitPassword"))
		{
			$password = $request->getParameter('password1');
			$valid = ResetPasswordAuthentication::validateResetLoginParams($this->d,$this->h);
			if(is_array($valid) && $valid['PROFILEID'])
			{
				$profileid = $valid['PROFILEID'];
				$jprofileObj = new Jprofile;
				$profileData = $jprofileObj->getArray(array("PROFILEID"=>$profileid),"","","EMAIL");
				$emailArr = explode("@",$profileData[0]['EMAIL']);
				$emailStr = $emailArr[0];
			 	if($password && $this->validatePassword($password,$emailStr)==true)
				{
					$this->done = PasswordUpdate::change($profileid,$password);
					$marked = ResetPasswordAuthentication::disableProfileidLinks($profileid);
                                        $dbObj=new jsadmin_AUTO_EXPIRY;
                                        $expireDt=date("Y-m-d H:i:s");
                                        $dbObj->replace($profileid,"P",$expireDt);
				}
				else
					$this->passwordInvalid="Password Entered is invalid";
			}
			else
			{
				if(MobileCommon::isMobile())
				{
                                            if(MobileCommon::isNewMobileSite())
                                            {
                                                $request->setParameter("expired","1");
                                                $this->forward("static","resetPass");
                                            }
                                            else
                                            {
                                                header("Location: $SITE_URL/jsmb/jsmb_forgotpassword.php?expire=Y");
                                                die;
                                            }
				}
				else
					$this->expired=1;
			}
			if(MobileCommon::isMobile())
			{
				if($this->done)
				{
                                        if(MobileCommon::isNewMobileSite())
                                        {
                                            $request->setParameter("success","1");
                                            $this->forward("static","resetPass");
                                        }
                                        else
                                        {
                                            header("Location: $SITE_URL/jsmb/login_home.php?passwordReset=Y");
                                            die;
                                        }
				}
				else
					$this->setTemplate("mobile/mobileResetPassword");
			}
		}
		else
		{
			$valid = ResetPasswordAuthentication::validateResetLoginParams($this->d,$this->h);
			if(is_array($valid))
			{
				$profileid = $valid['PROFILEID'];
				$profileObj= new JPROFILE;
				$profileData = $profileObj->getArray(array("PROFILEID"=>$profileid),"","","EMAIL");
				$emailArr = explode("@",$profileData[0]['EMAIL']);
				$this->emailStr = $emailArr[0];
				if(MobileCommon::isMobile())
                                {
                                    if(MobileCommon::isNewMobileSite())
                                    {
                                            $request->setParameter("d",$this->d);
                                            $request->setParameter("h",$this->h);
                                            $request->setParameter("emailStr", $profileData[0]['EMAIL']);
                                            $this->forward("static","resetPass");
                                    }    
                                    else
                                            $this->setTemplate("mobile/mobileResetPassword");
                                } 
			}
			else
			{
				if(MobileCommon::isMobile())
				{
                                        if(MobileCommon::isNewMobileSite())
                                        {
                                            $request->setParameter("expired","1");
                                            $this->forward("static","resetPass");
                                        }
                                        else
                                        {
                                            header("Location: $SITE_URL/jsmb/jsmb_forgotpassword.php?expire=Y");
                                            die;
                                        }
				}
				else
					$this->expired=1;
			}
		}
	}
	public function validatePassword($password,$emailStr)
	{
		$password1 = strtolower($password);
		if($password==$emailStr || in_array($password1,Password::$weakPasswords))
			return false;
		return true;
	}
	public function executeSendPasswordResetLink(sfWebRequest $request)
	{
	}
	public function executeUpdateEvalueTracking($request)
	{
		$id=$request->getParameter('id');
		$evalueTrackingObj = new EvalueTracking();
		$evalueTrackingObj->updateId($id);
		die;
	}
	

	/*      
	* This Function perform the bookmark action from the mobile App V1
	*/
	public function executeAddBookmarkv1(sfWebRequest $request)
	{
		$request=$this->getRequest();
		$this->loginData=$data=$request->getAttribute("loginData");
		
		//Contains logined Profile information;
		$this->loginProfile=LoggedInProfile::getInstance();
		$bookmarker = $this->loginData["PROFILEID"];
		$apiObj = ApiResponseHandler::getInstance();
		
		if($bookmarker != '')
		{
			$request->setParameter("caching",1);
			$ifApiCached = InboxUtility::cachedInboxApi('del',$request,$bookmarker);
//			print_r($request->getParameterHolder()->getAll());
//                      $bookmarker = $request->getParameter('bookmarker');
			$bookmarkeeChecksum = $request->getParameter('profilechecksum');
			$bookmarkee = JsAuthentication::jsDecryptProfilechecksum($bookmarkeeChecksum);
			$bookmarkObj = new Bookmarks();
			$shortlist = $request->getParameter('shortlist');
			$bookmarkerMemcacheObject = new ProfileMemcacheService($bookmarker);
			if($shortlist=="true")
			{
				$bookmarkObj->removeBookmark($bookmarker,$bookmarkee);
				$bookmarkerMemcacheObject->update("BOOKMARK",-1);
				if(MobileCommon::getChannel()=="P")
				{
					$array['button'] = ButtonResponse::getShortListButton('',array('isBookmarked'=>0));
				}
				else
				$array["button"] = ButtonResponse::getShortListButton('','',0);
				$responseSet = ButtonResponse::buttonDetailsMerge($array);
				$finalresponseArray["actiondetails"] = null;
				$finalresponseArray["buttondetails"] = ButtonResponse::buttonDetailsMerge($array);
				//Entry in Chat Roster
				try {
					$producerObj = new Producer();
					if ($producerObj->getRabbitMQServerConnected()) {
						$chatData = array('process' => 'CHATROSTERS', 'data' => array('type' => 'REMOVE_BOOKMARK', 'body' => array('sender' => array('profileid'=>$this->loginProfile->getPROFILEID(),'checksum'=>JsAuthentication::jsEncryptProfilechecksum($this->loginProfile->getPROFILEID()),'username'=>$this->loginProfile->getUSERNAME()), 'receiver' => array('profileid'=>$bookmarkee,'checksum'=>$bookmarkeeChecksum))), 'redeliveryCount' => 0);
						$producerObj->sendMessage($chatData);
					}
					unset($producerObj);
				} catch (Exception $e) {
					throw new jsException("Something went wrong while sending in chat queue for remove bookmark -" . $e);
				}
				//End
			}
			else
			{
				$bookmarkObj->addBookmark($bookmarker,$bookmarkee);
				$bookmarkerMemcacheObject->update("BOOKMARK",1);
				if(MobileCommon::getChannel()=="P")
				{
					$array['button'] = ButtonResponse::getShortListButton('',array('isBookmarked'=>1));
				}
				else
				$array["button"] = ButtonResponse::getShortListButton('','',1);
				$responseSet = ButtonResponse::buttonDetailsMerge($array);
				$finalresponseArray["actiondetails"] = null;
				$finalresponseArray["buttondetails"] = ButtonResponse::buttonDetailsMerge($array);
			}
			//Entry in Chat Roster
			try {
				$producerObj = new Producer();
				if ($producerObj->getRabbitMQServerConnected()) {
					$chatData = array('process' => 'CHATROSTERS', 'data' => array('type' => 'ADD_BOOKMARK', 'body' => array('sender' => array('profileid'=>$this->loginProfile->getPROFILEID(),'checksum'=>JsAuthentication::jsEncryptProfilechecksum($this->loginProfile->getPROFILEID()),'username'=>$this->loginProfile->getUSERNAME()), 'receiver' => array('profileid'=>$bookmarkee,'checksum'=>$bookmarkeeChecksum))), 'redeliveryCount' => 0);
					$producerObj->sendMessage($chatData);
				}
				unset($producerObj);
			} catch (Exception $e) {
				throw new jsException("Something went wrong while sending in chat queue for remove bookmark -" . $e);
			}
			//End
			$bookmarkerMemcacheObject->updateMemcache();
			$apiObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
			$apiObj->setResponseBody($finalresponseArray);
			$apiObj->generateResponse();
			die;
		}		
		else
			$apiObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
			$apiObj->generateResponse();
			die;
	}
	/*This action is Added by Reshu for MyJs module Login History*/
	public function executeLoginHistory(sfWebRequest $request)
	{
		$loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
                $profileid = $loggedInProfileObj->getPROFILEID();
                if($profileid != '')
                {
			$limit=20;
			$page=$request->getParameter('page');
			$limitStart= $limit*($page-1);
			$loginHistory = new LoginHistory();
		        $result = $loginHistory->getLogLoginHistory($profileid,true,$limit,$limitStart);
			if(is_array($result))
			{
				$totalCount= $result["FOUND_ROWS"];
				unset($result["FOUND_ROWS"]);
				foreach($result as $count => $row)
				{		
					$rowValues["IPADDR"]=$row["IPADDR"];
					$rowDate= new DateTime($row["TIME"]);
					$rowValues["TIME"]= $rowDate->format('d M Y h:i:s a');
					$loginValues[]=$rowValues;
					
				}
				if(is_array($loginValues))
				{
					$loginResult["VALUES"]=$loginValues;
					if($page >=1 && ($limit * $page) < $totalCount)
						$loginResult["NEXT"]=$page+1;
					if($page>1)
						$loginResult["PREV"] = $page-1;
					
				} 
											
			}
			$this->loginResult=json_encode($loginResult);
			print_r($this->loginResult);
			
		}
                else
                        echo "loggedOut";
                die;
	}
    
    /*
     * Action for handling request call back from Help Widget
     */
    public function executeRequestCallBack(sfWebRequest $request)
    {      
        $loginData=$request->getAttribute("loginData");
        $iProfileId = isset($loginData['PROFILEID'])? $loginData['PROFILEID'] : '';
        $userName = " "; 
        if($iProfileId){
            $userName =  "(".$loginData["USERNAME"].") ";
        }
        if($request->isMethod("POST")){
            $arrRequest = $request->getParameterHolder()->getAll();
            $arrValidQuery = array("P","M");
            $email = $arrRequest['email'];
            $phone = $arrRequest['phone'];
            $query = $arrRequest['query_type'];
            $device = $arrRequest['device'];
            $channel = $arrRequest['channel'];
            $callbackSource = $arrRequest['callbackSource'];
            $rcbResponse = $arrRequest['rcbResponse'];
            if(in_array($query, $arrValidQuery)){
                if($query == "P"){//Send Email
                    $to = "services@jeevansathi.com";

                    $from = "info@jeevansathi.com";//To Do Aliase Jeevansathi Support  Reply-to $email
                    
                    $subject = "$email".$userName."has requested a callback for assistance with his/her account";
                    $msgBody = "<html><body>Dear Support Team,<br> $email".$userName."has requested a callback from the support team for resolution of a service related issue. Please contact at $email,or $phone.<br> Regards<br> Team Jeevansathi</body></html>";
                    
                    SendMail::send_email($to,$msgBody,$subject,$from,"","","","","","","1",$email,"Jeevansathi Support");
                }
                else if ($query == "M"){//Do membership
                    
                    $objExecCallBack = new billing_EXC_CALLBACK;
                    $memHandlerObj = new MembershipHandler();
                    $objExecCallBack->addRecord($iProfileId,$phone,$email,$device,$channel,$callbackSource);
                    unset($objExecCallBack);
                    
                    $from = "webmaster@jeevansathi.com";
                    $to   = "inbound@jeevansathi.com";
                    
                    //Send Email
                    if($iProfileId){
                        $userName   =   $loginData["USERNAME"];
						$subject    =   "$userName is interested in Membership Plans";
						
                        $emailSend  =   $memHandlerObj->checkEmailSendForDay($iProfileId, $email);
                        $profileAllotedExecEmail = $memHandlerObj->getAllotedExecEmail($iProfileId);
						if(!$emailSend && $profileAllotedExecEmail){
                            $to = $profileAllotedExecEmail;
						}
                    } else {
						$subject = "Callback Request for Membership Plans";
                        $userName= "Someone";
                    }
                    
                    $msgBody = "<html><body>$userName is interested in knowing more about Membership Plans. Please contact at ".$email." or ".$phone.".</body></html>";
                    SendMail::send_email($to,$msgBody,$subject,$from);
                }
                
                //Update RCB Status if form is submit
                if(isset($rcbResponse) && $rcbResponse){
                  $this->updateRCBResponse($rcbResponse);
                }
                //Send Confirmation on Layer
                echo "Y";die;
            }
            
            //Update RCB Status if Only RCB Response is set
            if(isset($rcbResponse) && $rcbResponse){
              $this->updateRCBResponse($rcbResponse);
              echo "Y";die;
            }
        }
                
        $this->forward("seo", "404");        
        return sfView::NONE;
    }

    public function executeGetEngagementCountv1(sfWebRequest $request)
    {
    	$request=$this->getRequest();
		$this->loginData=$data=$request->getAttribute("loginData");
        $param = $request->getParameter("param");
		$profileid = $this->loginData["PROFILEID"];
        if($param == "header")
        {
            $this->count = BellCounts::getDetails($profileid);
        } 
        else if($param == "jspcHeader")
        {
            $this->count = BellCounts::getJSPCBellCounts($profileid);
        }
        else
        {
            $this->count = BellCounts::getEngagementBarCounts($profileid);
        }
		echo json_encode($this->count);
		die;
    }

    public function executeLogOut(sfWebRequest $request)
	{
		//echo("sgvs");die;
		$this->setTemplate("jspcLogout");
		$this->successStory();
		//print_r($this->successStoryData);die;
		$this->getResponse()->addMeta('canonical', sfConfig::get('app_site_url'));
		$this->getResponse()->addMeta('author', sfConfig::get('app_site_url'));
		$this->getResponse()->addMeta('copyright', date('Y').' jeevansathi.com');
		
	}
	private function successStory()
  	{
			$individualStoriesObj = new IndividualStories;
			$this->successStoryData = $individualStoriesObj->showSuccessPoolStory();
	
  	}
	public function executeSendAppLink(sfWebRequest $request)
	{
		//echo("sgvs");die;
		$mobile = $request->getParameter("mobile");
		//print_r($mobile);
		$PromoSmsObj= new sms_PromoSms("newjs_master");
		$count = $PromoSmsObj->getCount($mobile);
		if(!$count)
			$count=0;
		//print_r($count);die;
		if($count<10){
			$message="Dear User, Thank you for showing interest in the top rated Jeevansathi App. Visit : ".sfConfig::get("app_site_url")."/SMS-App and download the app for FREE.";
			include_once(sfConfig::get("sf_web_dir"). "/classes/SmsVendorFactory.class.php");
			$smsVendorObj = SmsVendorFactory::getSmsVendor("air2web");
            $xmlResponse = $smsVendorObj->generateXml(rand(10,10000),$mobile,$message);
            $smsVendorObj->send($xmlResponse,"transaction");
            $count++;
            if($count==1)
				$PromoSmsObj->Insert($mobile,$count,$source);
				else
					$PromoSmsObj->Update($mobile,$count);
				echo "1";die;
        }
        else
          echo "0";die;
	}




	public function executeCriticalActionLayerTracking($request)
	{
		$loginData=$request->getAttribute("loginData");
	if(!$loginData['PROFILEID'])
		{
			return;
		}

    else if($request->getParameter("button") && ($request->getParameter("layerR") || $request->getParameter("layerId"))) {
    	$layerToShow=$request->getParameter("layerR") ? $request->getParameter("layerR") : $request->getParameter("layerId"); 
 		CriticalActionLayerTracking::insertLayerType($loginData['PROFILEID'],$layerToShow,$request->getParameter("button"));
	 }
	 	
	 	$apiResponseHandlerObj = ApiResponseHandler::getInstance();
		$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);	
		$apiResponseHandlerObj->generateResponse();
		return sfView::NONE;

	}



	public function executeCALJSMS($request)
	{

        $calObject=$request->getAttribute('calObject');
        if (!$calObject) sfContext::getInstance()->getController()->redirect('/');
		$this->calObject=$calObject;
		$this->gender=$request->getAttribute('gender');
		if($calObject['LAYERID']==1)
			$this->showPhoto='1';
		else
			$this->showPhoto='0';


		$this->setTemplate('CALJSMS');	

	}

	public function executeRequestCallBackJSMS($request)
	{
		$request->setParameter('INTERNAL', 1);
		ob_start();
        $data = sfContext::getInstance()->getController()->getPresentationFor('common', 'ApiRequestCallbackV1');
        $output = ob_get_contents();
        ob_end_clean();
        $data = json_decode($output, true);
        $this->data = $data;
        $this->callbackSource = $request->getParameter('callbackSource');
		$this->referer = $request->getReferer();
		$this->setTemplate('requestCallBackJSMS');
	}
  
  /**
   * updateRCBResponse
   * @param type $bStatus
   */
  private function updateRCBResponse($bStatus)
  {
    $loggedInProfileObj = LoggedInProfile::getInstance();
    $rcbObject = new RequestCallBack($loggedInProfileObj);
    $rcbObject->updateThis($bStatus);
    unset($rcbObject);
  }

/**
 * 
 * @param sfWebRequest $request
 */
  public function executeTrackRCBV1(sfWebRequest $request)
  {
    //Api Response Object
		$apiResponseHandlerObj = ApiResponseHandler::getInstance();
    
    $loginData=$request->getAttribute("loginData");
		if(!$loginData['PROFILEID'])
		{
			//Set Error Message and return false
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$LOGOUT_PROFILE);		
      $apiResponseHandlerObj->generateResponse();
			die;
		}
    
    $arrRequest = $request->getParameterHolder()->getAll();
    $rcbResponse = $arrRequest['rcbResponse'];
    $arrAllowedRCBResponse = array('Y','N');

    if($request->isMethod("POST") && 
      isset($rcbResponse) && 
      in_array($rcbResponse, $arrAllowedRCBResponse)) {
      $this->updateRCBResponse($rcbResponse);
      $responseStatus = ResponseHandlerConfig::$SUCCESS;
    } else {
      //Invalid Request
      $responseStatus = ResponseHandlerConfig::$POST_PARAM_INVALID;
    }
    
    $apiResponseHandlerObj->setHttpArray($responseStatus);	
		$apiResponseHandlerObj->generateResponse();
    die;
  }

}