<?php
Class ButtonResponseJSMS
{
	private $page;
	private $buttonObj;
	private $contactObj;
	private $contactHandlerObj;
	private $privilageObj;
        const contactAcceptedlabel    = 'Accepeted';
        const contactDeclinedLabel    = 'Declined';
        const contactCancelLabel      = 'Cancelled';
        const contactReceivedLabel    = 'Interest Recvd';
        const contactSentLabel        = 'Interest Sent';
        const contactNoLabel          = 'Send Interest';
	public function __construct($loginProfile='', $otherProfile='', $page='', $contactHandler = "")
	{
		$this->page         = $page;
		$this->loginProfile = $loginProfile;
		$this->otherProfile = $otherProfile;
		if (is_a($contactHandler, ContactHandler)) {
			$this->contactHandlerObj = $contactHandler;
			$this->contactObj        = $this->contactHandlerObj->getContactObj();
		} elseif(is_a ($this->loginProfile,Profile)) {
			if($this->loginProfile->getPROFILEID())
			{
				$this->contactObj        = new Contacts($loginProfile, $otherProfile);
				$this->contactHandlerObj = new contactHandler($loginProfile, $otherProfile, "EOI", $this->contactObj, $this->contactObj->getTYPE(), ContactHandler::PRE);
			}
		}
		if($this->contactHandlerObj)
		{
			$this->privilageObj = $this->contactHandlerObj->getPrivilegeObj();
			$gender         = $this->contactHandlerObj->getViewed()->getGENDER();
			$this->hisher         = $gender == "F" ? "her" : "his";
			$this->himher         = $gender == "F" ? "her" : "him";
			$this->heshe          = $gender == "F" ? "she" : "he";
		}
	}
	public function getButtonArray($params)
	{	
		if($this->loginProfile->getPROFILEID())
		{
			$privilageArray = $this->privilageObj->getPrivilegeArray();
			$date           = date_create($this->contactObj->getTIME());
			$date           = date_format($date, 'jS M Y');
			
			if ($this->contactObj->getsenderObj()->getPROFILEID() == $this->contactHandlerObj->getViewer()->getPROFILEID()) {
				//echo $this->contactObj->getTYPE();
				switch ($this->contactObj->getTYPE()) {
					case ContactHandler::NOCONTACT:
						$buttonPrimary[]                 = self::getInitiateButton($this->page);
						$button[]                 = self::getInitiateButton($this->page);
						$button[]                 = self::getContactDetailsButton();
						$button[]                 = self::getShortListButton($this->loginProfile, $this->otherProfile,$params["BOOKMARKED"]);
						$button[]                 = self::getIgnoreButton($this->loginProfile, $this->otherProfile,$params["IGNORED"]);
						if (MobileCommon::isApp()=='I')
						$button[]				  = self::getReportAbuseButton();
						$responseArray["buttons"]["primary"] = $buttonPrimary;
						$responseArray["buttons"]["others"] = $button;
						$responseArray["photo"] = self::getPhotoDetail($params["PHOTO"]);
						$responseArray["topmsg"] = "Connect with ".$this->contactHandlerObj->getViewed()->getUSERNAME();
						if ($this->contactHandlerObj->getViewer()->getPROFILE_STATE()->getActivationState()->getUNDERSCREENED() == "Y"){
							$responseArray["topmsg"] = "";
							$responseArray["topmsg2"] = "Interest will be delivered once your profile is screened";
						}
						//echo "NOCONTACT";
						break;
					case ContactHandler::INITIATED:
						$buttonPrimary[]                 = self::getSendReminderButton($this->contactObj->getCOUNT(),$this->himher);
						$button[]                 = self::getSendReminderButton($this->contactObj->getCOUNT(),$this->himher);
						$button[]                 = self::getContactDetailsButton();
						$button[]                 = self::getShortListButton($this->loginProfile, $this->otherProfile,$params["BOOKMARKED"]);//,0,false);
						$button[]                 = self::getCancelInterestButton();
						if (MobileCommon::isApp()=='I')
						{
						$button[]                 = self::getIgnoreButton($this->loginProfile, $this->otherProfile,$params["IGNORED"]);
						$button[]				  = self::getReportAbuseButton();
						}
						$responseArray["buttons"]["primary"] = $buttonPrimary;
						$responseArray["buttons"]["others"] = $button;
						$responseArray["photo"] = self::getPhotoDetail($params["PHOTO"]);
						if($this->contactObj->getSEEN()=="Y")
							$responseArray["topmsg"] = $this->contactHandlerObj->getViewed()->getUSERNAME()." has seen your interest & is yet to reply";
						else
							$responseArray["topmsg"] = $this->contactHandlerObj->getViewed()->getUSERNAME()." is yet to read your interest";
						if ($this->contactObj->getCOUNT() >= ErrorHandler::REMINDER_COUNT)
							$responseArray["infobtnlabel"] = "You cannot send more than 2 reminders, you may call the user directly";
						//echo "INITIATE";
						break;
					case ContactHandler::CANCEL_CONTACT:
						$buttonPrimary[]                 = self::getCustomButton("You cancelled interest","","","","",false);
						//echo "CANCEL_CONTACT";
						$button[]                 = self::getInitiateButton($this->page);
						$button[]                 = self::getContactDetailsButton(false);
						$button[]                 = self::getShortListButton($this->loginProfile, $this->otherProfile,$params["BOOKMARKED"],false);
						$button[]                 = self::getIgnoreButton($this->loginProfile, $this->otherProfile,0,false);
						if (MobileCommon::isApp()=='I')
						$button[]				  = self::getReportAbuseButton();
						
						$responseArray["buttons"]["primary"]      = $buttonPrimary;
						$responseArray["buttons"]["others"]      = $button;
						$responseArray["photo"] = self::getPhotoDetail($params["PHOTO"]);
						$responseArray["infobtnlabel"] = "You Cancelled your interest on " . $date;
						$responseArray["topmsg"] = "Connect with ".$this->contactHandlerObj->getViewed()->getUSERNAME();
						break;
					case ContactHandler::ACCEPT:
						//echo "ACCEPT";
						$buttonPrimary[]                 = self::getSendMessageButton();
						$button[]                 = self::getSendMessageButton();
						$button[]                 = self::getContactDetailsButton();
						$button[]                 = self::getShortListButton($this->loginProfile, $this->otherProfile,$params["BOOKMARKED"]);//,0,false);
						$button[]                 = self::getCancelInterestButton();
						
						if (MobileCommon::isApp()=='I')
						{
						$button[]                 = self::getIgnoreButton($this->loginProfile, $this->otherProfile,$params["IGNORED"]);
						$button[]				  = self::getReportAbuseButton();
						}


						$responseArray["buttons"]["primary"] = $buttonPrimary;
						$responseArray["buttons"]["others"] = $button;
						$responseArray["topmsg"] = "Interact with ".$this->contactHandlerObj->getViewed()->getUSERNAME();
						if ($privilageArray["0"]["COMMUNICATION"]["MESSAGE"] == "Y") {
						{	
							$responseArray["photo"] = self::getPhotoDetail($params["PHOTO"]);
							$responseArray["canwrite"] = 1;
						}
						} else {
							$responseArray["infobtnlabel"]  = "Buy paid membership to Write messages or view contact details";
							$responseArray["infobtnvalue"]  = "";
							$responseArray["infobtnaction"] = "MEMBERSHIP";
							$responseArray["photo"] = self::getPhotoDetail($params["PHOTO"]);
						}
						break;
					case ContactHandler::DECLINE:
						
						if (MobileCommon::isApp()=='I')
						{
						$button[]                 = self::getIgnoreButton($this->loginProfile, $this->otherProfile,$params["IGNORED"]);
						$button[]				  = self::getReportAbuseButton();
						}


						$buttonPrimary[]                      = self::getCustomButton("They declined interest","","","","",false);
						$responseArray["buttons"]["primary"]      = $buttonPrimary;
						$responseArray["buttons"]["others"]      = $button;
						$responseArray["infobtnlabel"] = "They declined interest on " . $date;
						$responseArray["photo"] = self::getPhotoDetail($params["PHOTO"]);
						
						break;
					case ContactHandler::CANCEL:
						$button[]                      = self::getAcceptButton("Accept Again");
						$button[]                 = self::getDeclineButton("",true,false);
						if (MobileCommon::isApp()=='I')
						{
						$button[]                 = self::getIgnoreButton($this->loginProfile, $this->otherProfile,$params["IGNORED"]);
						$button[]				  = self::getReportAbuseButton();
						}
                                                //$button[]                 = self::getContactDetailsButton(false);
                                                //$button[]                 = self::getShortListButton($this->loginProfile, $this->otherProfile);//,0,false);
                                                //$button[]                 = self::getIgnoreButton($this->loginProfile, $this->otherProfile,0,false);
						$buttonPrimary[]                      = self::getCustomButton("You cancelled interest","","","","",false);
						$responseArray["topmsg"] = "Connect with ".$this->contactHandlerObj->getViewed()->getUSERNAME();
						$responseArray["buttons"]["primary"]      = $buttonPrimary;
						$responseArray["buttons"]["others"]      = $button;
						$responseArray["infobtnlabel"] = "You cancelled interest on " . $date;
						$responseArray["photo"] = self::getPhotoDetail($params["PHOTO"]);
						break;
				}
			} 
			else {
				//echo "receiver";die;
				switch ($this->contactObj->getTYPE()) {
					case ContactHandler::NOCONTACT:
						//$button["shortlist"] = self::buttonMerge(self::getShortListButton($this->loginProfile, $this->otherProfile));
                                                $buttonPrimary[]                 = self::getInitiateButton($this->page);
                                                $button[]                 = self::getInitiateButton($this->page);
                                                $button[]                 = self::getContactDetailsButton();
                                                $button[]                 = self::getShortListButton($this->loginProfile, $this->otherProfile,$params["BOOKMARKED"]);
                                                $button[]                 = self::getIgnoreButton($this->loginProfile, $this->otherProfile,$params["IGNORED"]);
                                      			
                                                if (MobileCommon::isApp()=='I')
                                      			$button[]				  = self::getReportAbuseButton();
						
                                                $responseArray["buttons"]["primary"] = $buttonPrimary;
                                                $responseArray["buttons"]["others"] = $button;
						$responseArray["photo"] = self::getPhotoDetail($params["PHOTO"]);
						$responseArray["topmsg"] = "Connect with ".$this->contactHandlerObj->getViewed()->getUSERNAME();
						//if ($this->contactHandlerObj->getViewer()->getPROFILE_STATE()->getActivationState()->getUNDERSCREENED() == "Y")
						//echo "NOCONTACT";
						break;
					case ContactHandler::INITIATED:
						//echo "INITIATE";die;
						$buttonPrimary[]                 = self::getAcceptButton("Accept Interest",$this->page);
						$button[]                 = self::getAcceptButton("Accept Interest",$this->page);
						$button[]                 = self::getDeclineButton($this->page);
						
						if (MobileCommon::isApp()=='I'){
						$button[]                 = self::getIgnoreButton($this->loginProfile, $this->otherProfile,$params["IGNORED"]);
						$button[]				  = self::getReportAbuseButton();
						}


						$responseArray["buttons"]["primary"] = $buttonPrimary;
						$responseArray["buttons"]["others"] = $button;
						$responseArray["photo"] = self::getPhotoDetail($params["PHOTO"]);
						$responseArray["topmsg"] = "Connect with ".$this->contactHandlerObj->getViewed()->getUSERNAME();
						break;
					case ContactHandler::CANCEL_CONTACT:
						$buttonPrimary[]                      = self::getCustomButton("They cancelled interest","","","","",false);
						
						if (MobileCommon::isApp()=='I')
						{
						$button[]                 = self::getIgnoreButton($this->loginProfile, $this->otherProfile,$params["IGNORED"]);
						$button[]				  = self::getReportAbuseButton();
						}

						$responseArray["buttons"]["primary"]      = $buttonPrimary;
						$responseArray["buttons"]["others"] = $button;
						$responseArray["infobtnlabel"] = "They cancelled interest on " . $date;
						$responseArray["photo"] = self::getPhotoDetail($params["PHOTO"]);
						$responseArray["topmsg"] = "Connect with ".$this->contactHandlerObj->getViewed()->getUSERNAME();
						break;
					case ContactHandler::ACCEPT:
						//echo "ACCEPT";
						$buttonPrimary[]                 = self::getSendMessageButton();
						$button[]                 = self::getSendMessageButton();
						$button[]                 = self::getContactDetailsButton();
						$button[]                 = self::getShortListButton($this->loginProfile, $this->otherProfile,$params["BOOKMARKED"]);//,0,false);
						$button[]                 = self::getDeclineButton($this->page,true);
						
						if (MobileCommon::isApp()=='I')
						{
						$button[]                 = self::getIgnoreButton($this->loginProfile, $this->otherProfile,$params["IGNORED"]);
						$button[]				  = self::getReportAbuseButton();
						}


						$responseArray["buttons"]["primary"] = $buttonPrimary;
						$responseArray["buttons"]["others"] = $button;
						$responseArray["topmsg"] = "Interact with ".$this->contactHandlerObj->getViewed()->getUSERNAME();
						if ($privilageArray["0"]["COMMUNICATION"]["MESSAGE"] == "Y") {
							$responseArray["photo"] = self::getPhotoDetail($params["PHOTO"]);
							$responseArray["canwrite"] = 1;
						} else {
							$responseArray["infobtnlabel"]  = "Buy paid membership to Write messages or view contact details";
							$responseArray["infobtnvalue"]  = "";
							$responseArray["infobtnaction"] = "MEMBERSHIP";
							$responseArray["photo"] = self::getPhotoDetail($params["PHOTO"]);
						}
						break;
					case ContactHandler::DECLINE:
						//echo "DECLINE";
						$button[]                      = self::getAcceptButton("Accept Again");
						$button[]                 = self::getDeclineButton("",true,false);
                                                //$button[]                 = self::getContactDetailsButton(false);
                                                //$button[]                 = self::getShortListButton($this->loginProfile, $this->otherProfile);//,0,false);
                                                //$button[]                 = self::getIgnoreButton($this->loginProfile, $this->otherProfile,0,false);
						if (MobileCommon::isApp()=='I')
						{
						$button[]                 = self::getIgnoreButton($this->loginProfile, $this->otherProfile,$params["IGNORED"]);
						$button[]				  = self::getReportAbuseButton();
						}


						$buttonPrimary[]                      = self::getCustomButton("You declined interest","","","","",false);
						$responseArray["buttons"]["primary"]      = $buttonPrimary;
						$responseArray["buttons"]["others"]      = $button;
						$responseArray["infobtnlabel"] = "You declined interest on " . $date;
						$responseArray["topmsg"] = "Changed your mind? Connect with ".$this->contactHandlerObj->getViewed()->getUSERNAME();
						$responseArray["photo"] = self::getPhotoDetail($params["PHOTO"]);
						break;
					case ContactHandler::CANCEL:
						$buttonPrimary[]                      = self::getCustomButton("They cancelled interest","","","","",false);
						$responseArray["buttons"]["primary"]      = $buttonPrimary;
						$responseArray["buttons"]["others"]      = null;
						//echo "CANCEL";

						if (MobileCommon::isApp()=='I')
						{
						$button[]                 = self::getIgnoreButton($this->loginProfile, $this->otherProfile,$params["IGNORED"]);
						$button[]				  = self::getReportAbuseButton();
						}

						
						$responseArray["buttons"]["others"]      = $button;
						
						$responseArray["infobtnlabel"] = "They cancelled interest on " . $date;
						$responseArray["photo"] = self::getPhotoDetail($params["PHOTO"]);
						break;
				}
			}
			
                        if(MobileCommon::isApp()=="I" && !$params["PHOTO"]){
                                $pictureServiceObj=new PictureService($this->contactHandlerObj->getViewed());
                                $profilePicObj = $pictureServiceObj->getProfilePic();
                                if($profilePicObj)
                                        $thumbNail = $profilePicObj->getThumbailUrl();
                                $iphoto = PictureFunctions::mapUrlToMessageInfoArr($thumbNail,"ThumbailUrl","",$this->contactHandlerObj->getViewed()->getGENDER())['url'];
                                $responseArray["photo"]=self::getPhotoDetail($iphoto);
			}
			if($params['CC_LISTING']=="PHOTO_REQUEST_RECEIVED"){
				$buttonPrimary = self::getUploadPhotoButton();
				$responseArray["buttons"]["primary"][0] = $buttonPrimary;
			}
                        
                        if(is_array($responseArray["buttons"]["others"]))
                        foreach ($responseArray["buttons"]["others"] as $key => $value) {
                            if($key!=0)
                                $responseArray["buttons"]["others"][$key]['secondary'] = 'true';
                        }
		}
		else
		{
			$responseArray = $this->getLogoutButtonArray(array("USERNAME"=>$this->otherProfile->getUSERNAME(),"PHOTO"=>array("url"=>$params["PHOTO"])));
		}
		
		$finalResponse = self::buttonDetailsMerge($responseArray);
		return $finalResponse;
	}



	public function jsmsRestButtonsrray($params)
        {
        	//var_dump($viewer);
        	//$loginProfile = LoggedInProfile::getInstance('newjs_master');
        	//print_r($logInProfile);die;
        	//$otherProfile =  new Profile("", $otherProfileID);
        	//print_r($otherProfileObj);die;
        	//$this->contactObj        = new Contacts($loginProfile, $otherProfile);
        	//print_r($this->contactObj->getTYPE());die;
			//print_r($this->contactHandlerObj);die;
			//print_r($this->contactHandlerObj->getViewer()->getPROFILEID());die;
			if($this->loginProfile->getPROFILEID())
			{
			$date           = date_create($this->contactObj->getTIME());
			$date           = date_format($date, 'jS M Y');
			if ($this->contactObj->getsenderObj()->getPROFILEID() == $this->contactHandlerObj->getViewer()->getPROFILEID()) 
			{
				//print_r($this->contactObj->getTYPE());die;
				switch ($this->contactObj->getTYPE()) {
					case ContactHandler::NOCONTACT:
					$restResponseArray["photo"] = self::getPhotoDetail($params["PHOTO"]);
						$restResponseArray["topmsg"] = "Connect with ".$this->contactHandlerObj->getViewed()->getUSERNAME();
						if ($this->contactHandlerObj->getViewer()->getPROFILE_STATE()->getActivationState()->getUNDERSCREENED() == "Y"){
							$restResponseArray["topmsg"] = "";
							$restResponseArray["topmsg2"] = "Interest will be delivered once your profile is screened";
						}
						//echo "NOCONTACT";
						break;

						case ContactHandler::INITIATED:
						$restResponseArray["photo"] = self::getPhotoDetail($params["PHOTO"]);
						if($this->contactObj->getSEEN()=="Y")
							$restResponseArray["topmsg"] = $this->contactHandlerObj->getViewed()->getUSERNAME()." has seen your interest & is yet to reply";
						else
							$restResponseArray["topmsg"] = $this->contactHandlerObj->getViewed()->getUSERNAME()." is yet to read your interest";
						if ($this->contactObj->getCOUNT() >= ErrorHandler::REMINDER_COUNT)
							$restResponseArray["infobtnlabel"] = "You cannot send more than 2 reminders, you may call the user directly";
						//echo "INITIATE";
						break;
						case ContactHandler::CANCEL_CONTACT:
						//echo "string";die;
						$restResponseArray["photo"] = self::getPhotoDetail($params["PHOTO"]);
						$restResponseArray["infobtnlabel"] = "You Cancelled your interest on " . $date;
						$restResponseArray["topmsg"] = "Connect with ".$this->contactHandlerObj->getViewed()->getUSERNAME();
						break;

						case ContactHandler::ACCEPT:
						$restResponseArray["topmsg"] = "Interact with ".$this->contactHandlerObj->getViewed()->getUSERNAME();
						if ($privilageArray["0"]["COMMUNICATION"]["MESSAGE"] == "Y") {
						{	
							$restResponseArray["photo"] = self::getPhotoDetail($params["PHOTO"]);
							$restResponseArray["canwrite"] = 1;
						}
						} else {
							$restResponseArray["infobtnlabel"]  = "Buy paid membership to Write messages or view contact details";
							$restResponseArray["infobtnvalue"]  = "";
							$restResponseArray["infobtnaction"] = "MEMBERSHIP";
							$restResponseArray["photo"] = self::getPhotoDetail($params["PHOTO"]);
						}
						break;

						case ContactHandler::DECLINE:
						$restResponseArray["infobtnlabel"] = "They declined interest on " . $date;
						$restResponseArray["photo"] = self::getPhotoDetail($params["PHOTO"]);
						$responseArray["topmsg"] = "Connect with ".$this->contactHandlerObj->getViewed()->getUSERNAME();
						break;

						case ContactHandler::CANCEL:
						$restResponseArray["topmsg"] = "Connect with ".$this->contactHandlerObj->getViewed()->getUSERNAME();
						$restResponseArray["infobtnlabel"] = "You cancelled interest on " . $date;
						$restResponseArray["photo"] = self::getPhotoDetail($params["PHOTO"]);
						break;
					}
						
				}
			else
			{//print_r(ContactHandler::INITIATED);die;
				switch ($this->contactObj->getTYPE()) {
				case ContactHandler::NOCONTACT:
					$restResponseArray["photo"] = self::getPhotoDetail($params["PHOTO"]);
						$restResponseArray["topmsg"] = "Connect with ".$this->contactHandlerObj->getViewed()->getUSERNAME();
						//if ($this->contactHandlerObj->getViewer()->getPROFILE_STATE()->getActivationState()->getUNDERSCREENED() == "Y")
						//echo "NOCONTACT";
						break;
				case ContactHandler::INITIATED:

					$restResponseArray["photo"] = self::getPhotoDetail($params["PHOTO"]);
						$restResponseArray["topmsg"] = "Connect with ".$this->contactHandlerObj->getViewed()->getUSERNAME();
						break;
				case ContactHandler::CANCEL_CONTACT:
					$restResponseArray["infobtnlabel"] = "They cancelled interest on " . $date;
						$restResponseArray["photo"] = self::getPhotoDetail($params["PHOTO"]);
						$restResponseArray["topmsg"] = "Connect with ".$this->contactHandlerObj->getViewed()->getUSERNAME();
						break;
					case ContactHandler::ACCEPT:
					$restResponseArray["topmsg"] = "Interact with ".$this->contactHandlerObj->getViewed()->getUSERNAME();
						if ($privilageArray["0"]["COMMUNICATION"]["MESSAGE"] == "Y") {
							$restResponseArray["photo"] = self::getPhotoDetail($params["PHOTO"]);
							$restResponseArray["canwrite"] = 1;
						} else {
							$restResponseArray["infobtnlabel"]  = "Buy paid membership to Write messages or view contact details";
							$restResponseArray["infobtnvalue"]  = "";
							$restResponseArray["infobtnaction"] = "MEMBERSHIP";
							$restResponseArray["photo"] = self::getPhotoDetail($params["PHOTO"]);
						}
						break;
					case ContactHandler::DECLINE:
					$restResponseArray["infobtnlabel"] = "You declined interest on " . $date;
						$restResponseArray["topmsg"] = "Changed your mind? Connect with ".$this->contactHandlerObj->getViewed()->getUSERNAME();
						$restResponseArray["photo"] = self::getPhotoDetail($params["PHOTO"]);
						break;
					case ContactHandler::CANCEL:
					$restResponseArray["infobtnlabel"] = "They cancelled interest on " . $date;
						$restResponseArray["photo"] = self::getPhotoDetail($params["PHOTO"]);
						break;
				}



			}
			}
		else
		{
			$responseArray = $this->getNewLogoutButtonArray(array("USERNAME"=>$this->otherProfile->getUSERNAME(),"PHOTO"=>array("url"=>$params["PHOTO"])));
			//var_dump($responseArray);die;
			$restResponseArray = self::buttonDetailsMerge($responseArray);
		}
			//print_r($restResponseArray);die;
			return $restResponseArray;
        }
	
	//params["USERNAME","PHOTO"]
/*
        public function jsmsSearchRestButtonsarray($params)
        {
        	$topmsg = "Connect with ".$params["USERNAME"];
				if($loginObj->getPROFILEID() && $loginObj->getPROFILE_STATE()->getActivationState()->getUNDERSCREENED() == "Y"){
					$topmsg2 = "Interest will be delivered once your profile is screened";
					$topmsg = "";
				}
				break;
		}
		//Tanu: To be changed
		$buttonFinal["photo"]["url"]=$params["PHOTO"]["url"]?$params["PHOTO"]["url"]:PictureFunctions::getNoPhotoJSMS($param["GENDER"]);
		$buttonFinal["topmsg"]=$topmsg;
		$buttonFinal["topmsg2"]=$topmsg2;
		$button = self::buttonDetailsMerge($buttonFinal
        }
*/


    	public function getNewButtonArray($params)
	{	
//print_r($params);die;
		if($this->loginProfile->getPROFILEID())
		{

			$privilageArray = $this->privilageObj->getPrivilegeArray();
			$date           = date_create($this->contactObj->getTIME());
			$date           = date_format($date, 'jS M Y');
			$type 			= $this->contactObj->getTYPE();
			$count          = $this->contactObj->getCount();
			//print_r($count);die;
			//$params["count"] = $count;
			//print_r($this->contactObj);die;
			/*$page['PHOTO'] = PictureFunctions::mapUrlToMessageInfoArr($tupleObj->getThumbailUrl(),'ThumbailUrl',$tupleObj->getIS_PHOTO_REQUESTED(),$tupleObj->getGENDER())['url'];
			$page['CC_LISTING'] = $infoKey;
			$page['isBookmarked'] = $tupleObj->getIS_BOOKMARKED();
			$page["tracking"] = $this->getTracking($infoKey);*/
			//echo $count;die;
			if ($this->contactObj->getsenderObj()->getPROFILEID() == $this->contactHandlerObj->getViewer()->getPROFILEID()) {
				$viewer = "S";
			}
			else
			{
				$viewer = "R";
			}
			/*if($params["IGNORED"] == 1)
			{//echo "string";die;
				$type = "B";
				$viewer = "S";
			}*/
			//print_r($this->contactObj->getTYPE());die;
			if($type=="I" && $count>=1)
				$type = "R";
			$source = $this->page['page_source']?$this->page['page_source']:$this->page['source'];
			$infoKey="VDP";
			if($source == "VSP")
			{
				$source = "S";
			}
			if($source=="VDP_VSP")
			{
				if($this->contactObj->getCount()>1)
					$type="R";
			}
			//echo "source=>".$source." channel=> ".$this->channel." viewer=> ".$viewer." type=>".$type;die;
			//var_dump($viewer);
			//var_dump($type);die;

                        $params["isIgnored"] = $params["IGNORED"];
			$buttons = ButtonResponseFinal::getListingButtons($infoKey, "M", $viewer,$type,$params,$count);
			if($params["IGNORED"] == 1){
			
			$buttons["buttons"]["3"]["label"]="Unblock";
			
			}

			$responseArray = $buttons;

		}
		else
		{
			$params["source"] = $this->page['page_source']?$this->page['page_source']:$this->page['source'];
			$params["channel"] = $this->channel;
			$responseArray = $this->getNewLogoutButtonArray($params);
		}
		$restResponseArray=self::jsmsRestButtonsrray($params);
        $responseArray["photo"]=$restResponseArray["photo"];
        $responseArray["topmsg"]=$restResponseArray["topmsg"];
        $responseArray["infobtnlabel"]=$restResponseArray["infobtnlabel"];
//        print_r($responseArray); die;
		//$finalResponse = self::buttonDetailsMerge($responseArray);
		//print_r($finalResponse);die;
		return $responseArray;
	}



	public static function getLogoutButtonArray($params)
	{
		$buttonPrimary = self::getCustomButtonByBName("INITIATE");
		$topmsg = "Connect with ".$params["USERNAME"];
                $buttonP[] = $buttonPrimary;
                $buttonP[0]["action"] = "INITIATE";
                $buttonOthers[] = $buttonOther?$buttonOther:$buttonPrimary;
                $buttonOthers[0]["action"]="INITIATE";
                $buttonOthers[] = ButtonResponse::getContactDetailsButton();
                $buttonOthers[1]["action"]="CONTACTDETAIL";
                $buttonOthers[] = ButtonResponse::getShortListButton('','',0);
                $buttonOthers[2]["action"]="SHORTLIST";
                $buttonOthers[] = ButtonResponse::getIgnoreButton('','',0);
                $buttonOthers[3]["action"]="IGNORE";
                $buttonFinal["buttons"]["primary"]=$buttonP;
                $buttonFinal["buttons"]["others"]=$buttonOthers;
		$photoUrl = self::getPhotoDetail($params["PHOTO"]["url"])["url"];
                $buttonFinal["photo"]["url"]=$photoUrl?$photoUrl:PictureFunctions::getNoPhotoJSMS($param["GENDER"]);
                $buttonFinal["topmsg"]=$topmsg;
                $button = self::buttonDetailsMerge($buttonFinal);
                return $button;
	}


	public static function getNewLogoutButtonArray($params)
	{
		$buttonPrimary = self::getCustomButtonByBName("INITIATE");
		$topmsg = "Connect with ".$params["USERNAME"];
                //$buttonP[] = $buttonPrimary;
                //$buttonP[0]["action"] = "INITIATE";
                $buttonOthers[] = $buttonOther?$buttonOther:$buttonPrimary;
                $buttonOthers[0]["action"]="INITIATE";
                $buttonOthers[] = ButtonResponse::getContactDetailsButton();
                $buttonOthers[1]["action"]="CONTACTDETAIL";
                $buttonOthers[] = ButtonResponse::getShortListButton('','',0);
                $buttonOthers[2]["action"]="SHORTLIST";
                $buttonOthers[] = ButtonResponse::getIgnoreButton('','',0);
                $buttonOthers[3]["action"]="IGNORE";
               // $buttonFinal["buttons"]["primary"]=$buttonP;
                $buttonFinal["buttons"]=$buttonOthers;
                $buttonFinal["buttons"][0]["primary"]="true";
                $buttonFinal["buttons"][1]["primary"]="false";
                $buttonFinal["buttons"][1]["action"]="CONTACT_DETAIL";
                $buttonFinal["buttons"][2]["primary"]="false";
                $buttonFinal["buttons"][3]["primary"]="false";
                $buttonFinal["buttons"][0]["secondary"]="true";
                $buttonFinal["buttons"][1]["secondary"]="true";
                $buttonFinal["buttons"][2]["secondary"]="true";
                $buttonFinal["buttons"][3]["secondary"]="true";
		$photoUrl = self::getPhotoDetail($params["PHOTO"]["url"])["url"];
                $buttonFinal["photo"]["url"]=$photoUrl?$photoUrl:PictureFunctions::getNoPhotoJSMS($param["GENDER"]);
                $buttonFinal["topmsg"]=$topmsg;
                $button = self::buttonDetailsMerge($buttonFinal);
                return $button;
	}

	public static function getShortListButton($loginProfile='', $otherProfile='',$isBookmarked=null,$enable=true)
	{//echo "string";
	//var_dump($isBookmarked);
		if(!isset($isBookmarked)){		
			$bookmarkObj  = new Bookmarks();
			if(is_a($loginProfile,Profile))
				$loginProfileId = $loginProfile->getPROFILEID();
			if(is_a($otherProfile,Profile))
				$otherProfileId = $otherProfile->getPROFILEID();
			if($loginProfileId)
			$isBookmarked = $bookmarkObj->getProfilesBookmarks($loginProfileId, array(
				$otherProfileId
			));
			$isBookmarked = count($isBookmarked);
		}
		if ($isBookmarked == 1) {
			$button["iconid"] = IdToAppImagesMapping::SHORTLISTEDBUTTON;
			$button["label"]  = "Shortlisted";
			$button["action"] = "SHORTLIST";
			$button["params"] = "&shortlist=true";
		} 
		else{
			$button["iconid"] = IdToAppImagesMapping::SHORTLISTBUTTON;
			$button["label"]  = "Shortlist";
			$button["action"] = "SHORTLIST";
			$button["params"]  = "&shortlist=false";
		}
		return (self::buttonMerge($button,$enable));
	}

        public static function getIgnoreButton($loginProfile='', $otherProfile='',$isIgnored=null,$enable=true)
        {
        	
			if ($isIgnored) {
               $button["label"]  = "Unblock";
			if(MobileCommon::isApp()=="I")
			{
				$button["iconid"] = IdToAppImagesMapping::UNDO_IGNORE;
				$button["action"] = "UNDO_IGNORE";
			}
			else
			{
				$button["iconid"] = IdToAppImagesMapping::IGNORE;
				$button["action"] = "IGNORE";
			}
                        $button["params"] = "&ignore=0";
                }
                else{
                        $button["iconid"] = IdToAppImagesMapping::IGNORE;
                        $button["label"]  = "Block";
                        $button["action"] = "IGNORE";
                        $button["params"]  = "&ignore=1";
                }
                return (self::buttonMerge($button,$enable));
        }

 public static function getReportAbuseButton($enable=true)
        {
        	
                
                        $button["iconid"] = IdToAppImagesMapping::REPORTABUSE;
                        $button["label"]  = "Report Abuse";
                        $button["action"] = "REPORTABUSE";
                
                return (self::buttonMerge($button,$enable));
        }

	public static function getContactDetailsButton($enable=true)
	{
		$button["iconid"] = IdToAppImagesMapping::CONTACTDETAILBUTTON;
		$button["label"]  = "View Contacts";
		$button["action"] = "CONTACTDETAIL";
		$button           = self::buttonMerge($button,$enable);
		return $button;
	}

        public static function getPhotoDetail($photo)
        {
                $button["url"] = $photo;
                $button["label"]  = null;
                $button["action"] = null;
                $button           = self::buttonMerge($button);
                return $button;
        }

	public static function getUploadPhotoButton(){
		$button["iconid"] = IdToAppImagesMapping::CONTACTDETAILBUTTON;
                $button["label"]  = "Uoload Your Photo";
                $button["action"] = "PHOTO_UPLOAD";
                $button           = self::buttonMerge($button);
                return $button;
	}

	public static function getAcceptButton($str,$page='')
	{
		$button["iconid"] = IdToAppImagesMapping::ACCEPT;
		$button["label"]  = $str;
		$button["action"] = "ACCEPT";
		if (isset($page["responseTracking"]) && $str == "Accept Interest")
			$button["params"] = "&responseTracking=" . $page["responseTracking"];
		if($page["tracking"] && $str == "Accept Interest")
		{
			$button["params"] = $params["tracking"];
		}
                
		$button = self::buttonMerge($button);
		return $button;
	}
	public static function getInitiateButton($page='')
	{
		$button["iconid"] = IdToAppImagesMapping::ENABLE_CONTACT;
		$button["label"]  = "Send Interest";
		$button["action"] = "INITIATE";
		if (isset($page["stype"]))
			$button["params"] = "&stype=" . $page["stype"];
		if (isset($page["page_source"]))
			$button["params"] .= "&page_source=" . $page["page_source"];
		if($page["tracking"])
		{
			$button["params"] = $params["tracking"];
		}
                if($page['primary'])  
                    $button["primary"] = 'true';

		$button = self::buttonMerge($button);
		return $button;
	}
	public static function getDeclineButton($page='',$declineAfterAccept = false,$enable=true)
	{
		$button["iconid"] = IdToAppImagesMapping::DECLINE;
		$button["label"]  = $declineAfterAccept?"Decline Interest":"Decline";
		$button["action"] = "DECLINE";
		if (isset($page["responseTracking"]))
			$button["params"] = "&responseTracking=" . $page["responseTracking"];
		if($page["tracking"])
		{
			$button["params"] = $params["tracking"];
		}
		$button = self::buttonMerge($button,$enable);
		return $button;
	}
	public static function getCancelInterestButton()
	{
		$button["iconid"] = IdToAppImagesMapping::CANCEL_INTEREST;
		$button["label"]  = "Cancel Interest";
		$button["action"] = "CANCEL_INTEREST";
		$button           = self::buttonMerge($button);
		return $button;
	}
	public static function getSendMessageButton()
	{
		$button["iconid"] = IdToAppImagesMapping::MESSAGE;
		$button["label"]  = "Write Message";
		$button["action"] = "WRITE_MESSAGE";
		$button           = self::buttonMerge($button);
		return $button;
	}
	public static function getSendReminderButton($count='',$user="user")
	{
		$user = ucfirst($user);
		if($count){
			if ($count < ErrorHandler::REMINDER_COUNT) {
				$button["iconid"] = IdToAppImagesMapping::SEND_REMINDER;
				if((ErrorHandler::REMINDER_COUNT - $count) == 1)
					$button["label"]  = "Remind ".$user." Again";
				else
					$button["label"]  = "Remind ".$user;
				$button["action"] = "REMINDER";
			} else {
				$button["iconid"] = IdToAppImagesMapping::REMINDER_SENT;
				$button["label"]  = "Reminder 2/2 Sent";
				$button["enable"] = false;
			}
		}else{
			$button["iconid"] = IdToAppImagesMapping::SEND_REMINDER;
			$button["label"]  = "Remind ".$user;
			$button["action"] = "REMINDER";
		}
		$button = self::buttonMerge($button);
		return $button;
	}
	public function getInitiatedButton()
	{
		if($this->contactObj->getTYPE() == ContactHandler::INITIATED)
		{
			$button["iconid"] = IdToAppImagesMapping::TICK_CONTACT;
			$button["label"]  = "Interest Sent";
			$button["enable"]  = false;
		}
		else if(($this->contactObj->getTYPE() == ContactHandler::NOCONTACT) && ($this->contactHandlerObj->getViewer()->getPROFILE_STATE()->getActivationState()->getUNDERSCREENED() == "Y"))
		{
			$button["iconid"] = IdToAppImagesMapping::UNDERSCREENING;
			$button["label"]  = "Interest Saved";
			$button["enable"]  = false;
		}
		else
		{
			$button = $this->getInitiateButton($this->page);
		}
		$button = self::buttonMerge($button);
		return $button;
	}
	public static function buttonMerge($button,$enable=true)
	{
		$buttonArr["iconid"] = null;
		$buttonArr["label"]  = null;
		$buttonArr["action"] = null;
		$buttonArr["value"]  = null;
		$buttonArr["params"] = null;
		$buttonArr["enable"] = $enable?true:false;
		return array_merge($buttonArr, $button);
	}
	public function getAfterActionButton($actionType)
	{
		$gender         = $this->contactHandlerObj->getViewed()->getGENDER();
		$privilageArray = $this->privilageObj->getPrivilegeArray();
		$date           = date_create($this->contactObj->getTIME());
		$date           = date_format($date, 'jS M Y');
		$username	=$this->contactHandlerObj->getViewed()->getUSERNAME();
		if ($this->contactObj->getsenderObj()->getPROFILEID() == $this->contactHandlerObj->getViewer()->getPROFILEID()) {
			//echo "sender";
			
			switch ($this->contactObj->getTYPE()) {
				case ContactHandler::REMINDER:
					//$button                        = $this->getSendReminderButton("",$this->himher);
					$button                        = self::getCustomButton("Reminder ".($this->contactObj->getCOUNT()-1)."/".(ErrorHandler::REMINDER_COUNT-1)." sent","","","","",false);;
					$responseArray["button"]      = $button;
					$responseArray["infomsglabel"] = "Reminder of interest Sent";
					//echo "NOCONTACT";
					break;
				case ContactHandler::INITIATED:
					if($this->contactObj->getCOUNT()>1)
					{
						//$button                        = $this->getSendReminderButton(3,$this->himher);
						$button                        = self::getCustomButton("Reminder ".($this->contactObj->getCOUNT()-1)."/".(ErrorHandler::REMINDER_COUNT-1)." sent","","","","",false);;
						$responseArray["button"]      = $button;
						if($privilageArray["0"]["SEND_REMINDER"]["MESSAGE"] != "Y" &&  $this->contactObj->getCOUNT() < ErrorHandler::REMINDER_COUNT)
							$responseArray["infomsglabel"] = "Reminder of interest Sent";
					}
					else
					{
						$button                   = $this->getInitiatedButton($this->page);
						$responseArray["button"] = $button;
					}
					//echo "INITIATE";
					break;
				case ContactHandler::CANCEL_CONTACT:
					//echo "CANCEL_CONTACT";
					//$button                 = $this->getInitiateButton($this->page);
					$button                 = self::getCustomButton("You cancelled interest","","","","",false);
					$responseArray["button"] = $button;
					$responseArray["infobtnlabel"] = "You Cancelled your interest on " . $date;
					$responseArray["confirmLabelHead"] = "Your interest has been cancelled";
					$responseArray["confirmLabelMsg"] = "Profile moved to Declined list";
					break;
				case ContactHandler::CANCEL:
					//echo "CANCEL";
					$button                 = self::getCustomButton("You cancelled interest","","","","",false);
					$responseArray["button"] = $button;
					$responseArray["infobtnlabel"] = "You Cancelled your interest on " . $date;
					$responseArray["confirmLabelHead"] = "$username"."'s profile did not match your expectations.";
					$responseArray["confirmLabelMsg"] = "Profile moved to Cancelled list";
					break;
                                case ContactHandler::ACCEPT:
                                                $button                      = $this->getSendMessageButton();
                                                $responseArray["button"]      = $button;
                                                $responseArray["infomsglabel"] = "You are now connected with " . $this->contactHandlerObj->getViewed()->getUSERNAME();
                                                $responseArray["infomsgiconid"] = '023';
                                                if ($privilageArray["0"]["COMMUNICATION"]["MESSAGE"] != "Y") {
                                                        $responseArray["infobtnlabel"]  = "Buy paid membership to Write messages or view contact details";
                                                        $responseArray["infobtnvalue"]  = "";
                                                        $responseArray["infobtnaction"] = "MEMBERSHIP";
                                                }
                                                $responseArray["infomsglabel"]  = "You are now connected with " . $this->contactHandlerObj->getViewed()->getUSERNAME();
                                                $responseArray["infomsgiconid"] = '023';
                                                break;
			}
		} else {
			//echo "receiver";
			switch ($this->contactObj->getTYPE()) {
				case ContactHandler::ACCEPT:
						$button                      = $this->getSendMessageButton();
						$responseArray["button"]      = $button;
						$responseArray["infomsglabel"] = "You are now connected with " . $this->contactHandlerObj->getViewed()->getUSERNAME();
						$responseArray["infomsgiconid"] = '023';
						if ($privilageArray["0"]["COMMUNICATION"]["MESSAGE"] != "Y") {
							$responseArray["infobtnlabel"]  = "Buy paid membership to Write messages or view contact details";
							$responseArray["infobtnvalue"]  = "";
							$responseArray["infobtnaction"] = "MEMBERSHIP";
						}
						$responseArray["infomsglabel"]  = "You are now connected with " . $this->contactHandlerObj->getViewed()->getUSERNAME();
						$responseArray["infomsgiconid"] = '023';
						break;
				case ContactHandler::DECLINE:
					//echo "DECLINE";
					//$button                      = $this->getAcceptButton("Accept Again");
					$button                      = self::getCustomButton("You declined interest","","","","",false);
					$responseArray["button"]      = $button;
					$responseArray["infobtnlabel"] = "You declined interest on " . $date;
					$responseArray["confirmLabelHead"] = "$username"."'s profile did not match your expectations.";
					$responseArray["confirmLabelMsg"] = "Profile moved to Declined list";
					break;
				case ContactHandler::CANCEL:
					//echo "CANCEL";
					$responseArray["infobtnlabel"] = "They cancelled interest on " . $date;
					break;
			}
		}
                if(MobileCommon::isApp()=="I"){
                                $pictureServiceObj=new PictureService($this->contactHandlerObj->getViewed());
                                $profilePicObj = $pictureServiceObj->getProfilePic();
                                if($profilePicObj)
                                        $thumbNail = $profilePicObj->getThumbailUrl();
                                $iphoto = PictureFunctions::mapUrlToMessageInfoArr($thumbNail,"ThumbailUrl","",$this->contactHandlerObj->getViewed()->getGENDER())['url'];
                                $responseArray["photo"]=self::getPhotoDetail($iphoto);
                }
		$finalResponse = self::buttonDetailsMerge($responseArray);
		return $finalResponse;
	}
	
	public static function actionDetailsMerge($actionDetails)
	{
		if(!$actionDetails) $actionDetails=array();
		$responseSet["contactdetailmsg"]     = null;
		$responseSet["contact1"]             = null;
		$responseSet["contact2"]             = null;
		$responseSet["contact3"]             = null;
		$responseSet["contact4"]             = null;
		$responseSet["footerbutton"]         = null;
		$responseSet["headerthumbnailurl"]   = null;
		$responseSet["headerlabel"]          = null;
		$responseSet["errmsgiconid"]         = null;
		$responseSet["errmsglabel"]          = null;
		$responseSet["writemsgbutton"]       = null;
		$responseSet["selfthumbnailurl"]     = null;
		$finalResponse = array_merge($responseSet,$actionDetails);
		return $finalResponse;
	}
	
	public static function buttonDetailsMerge($buttonDetails)
	{
		$responseSet["buttons"]["primary"]       = null;
		$responseSet["buttons"]["others"]       = null;
		$responseSet["button"]       = null;
		$responseSet["infomsgiconid"] = null;
		$responseSet["infomsglabel"]  = null;
		$responseSet["infobtnlabel"]  = null;
		$responseSet["infobtnvalue"]  = null;
		$responseSet["infobtnaction"] = null;
		$responseSet["photo"] = null;
		$responseSet["topmsg"] = null;
		$responseSet["topmsg2"] = null;
		$finalResponse                = array_merge($responseSet, $buttonDetails);
		return $finalResponse;
	}

        public static function getCustomButton($label="",$value="",$action="",$param="",$iconid="",$enable=true)
        {
                $button["label"] = $label==""?null:$label;
                $button["value"] = $value==""?null:$value;
                $button["action"] = $action==""?null:$action;
                $button["params"] = $param==""?null:$param;
                $button["iconid"] = $iconid==""?null:$iconid;
                $button["enable"] = $enable==true?true:false;
                return $button;
        }
	
	//params = array("SHORTLIST","IGNORE","PAGE","CONTACT_COUNT","PHOTO","USERNAME","GENDER","LOGIN","UNDERSCREENED","STYPE","OTHER_PROFILEID")
	public static function getButtons($contactStat="",$params="")
	{
		//Logout functionality
		if(!$params["LOGIN"]) return self::getLogoutButtonArray($params);
		//Logout functionality ends here
	
		//$contactStat=ContactHandler::INTEREST_RECEIVED;
		switch($contactStat)
		{
			case ContactHandler::ACCEPTANCES_SENT:
			case ContactHandler::ACCEPTANCES_RECEIVED:
				$buttonPrimary[] = self::getCustomButton("Accepted","","","","",false);;
				$buttonFinal["buttons"]["primary"]=$buttonPrimary;
				$buttonFinal["buttons"]["others"]=null;
				break;
			case ContactHandler::DECLINED_SENT:
			case ContactHandler::DECLINED_RECEIVED:
				$buttonPrimary[] = self::getCustomButton("Declined","","","","",false);;
				$buttonFinal["buttons"]["primary"]=$buttonPrimary;
				$buttonFinal["buttons"]["others"]=null;
				break;
			case ContactHandler::CANCEL_SENT:
			case ContactHandler::CANCEL_RECEIVED:
			case ContactHandler::CANCEL_EOI_SENT:
			case ContactHandler::CANCEL_EOI_RECEIVED:
				$buttonPrimary[] = self::getCustomButton("You cancelled interest","","","","",false);;
				$buttonFinal["buttons"]["primary"]=$buttonPrimary;
				$buttonFinal["buttons"]["others"]=null;
				break;
			case ContactHandler::INTEREST_RECEIVED:
				$buttonPrimary[] = self::getCustomButton("Interest Received","","","","",false);;
				$buttonFinal["buttons"]["primary"]=$buttonPrimary;
				$buttonFinal["buttons"]["others"]=null;
				break;
			case ContactHandler::INTEREST_SENT:
				$buttonPrimary[] = self::getCustomButton("Interest Sent","","","","",false);;
				$buttonFinal["buttons"]["primary"]=$buttonPrimary;
				$buttonFinal["buttons"]["others"]=null;
				break;
			default:
				$loginObj = LoggedInProfile::getInstance('newjs_master');
				$buttonP = self::getCustomButtonByBName("INITIATE",$params["PAGE"]);
				$buttonPrimary[] = $buttonP;
				$buttonOthers[] = $buttonP;
				$buttonOthers[] = self::getContactDetailsButton();
				$buttonOthers[] = self::getShortListButton('','',$params["SHORTLIST"]=="Y"?1:0);
				$pObj = new Profile("",$params["OTHER_PROFILEID"]);
				$buttonOthers[] = self::getIgnoreButton($loginObj,$pObj,$param["IGNORE"]);
				
				if (MobileCommon::isApp()=='I')
				$buttonOthers[] = self::getReportAbuseButton();
				
				$buttonFinal["buttons"]["primary"]=$buttonPrimary;
				$buttonFinal["buttons"]["others"]=$buttonOthers;
				$topmsg = "Connect with ".$params["USERNAME"];
				if($loginObj->getPROFILEID() && $loginObj->getPROFILE_STATE()->getActivationState()->getUNDERSCREENED() == "Y"){
					$topmsg2 = "Interest will be delivered once your profile is screened";
					$topmsg = "";
				}
				break;
		}
		//Tanu: To be changed
		$buttonFinal["photo"]["url"]=$params["PHOTO"]["url"]?$params["PHOTO"]["url"]:PictureFunctions::getNoPhotoJSMS($param["GENDER"]);
		$buttonFinal["topmsg"]=$topmsg;
		$buttonFinal["topmsg2"]=$topmsg2;
		$button = self::buttonDetailsMerge($buttonFinal);
		return $button;
	}
	
	
	public static function getCustomButtonByBName($type,$page='')
	{
		$button = array();
		switch($type)
		{
			case "ACCEPT":
				$button = self::getAcceptButton("Accept Interest",$page);
				break;
			case "DECLINE":
				$button = self::getDeclineButton($page);
				break;
			case "MESSAGE":
				$button = self::getSendMessageButton();
				break;			
			case "CONTACT":
				$button = self::getContactDetailsButton();
				break;
			case "INITIATE":
				$button = self::getInitiateButton($page);
				break;
			case "SHORTLIST":
				$button = self::getShortListButton("","");
				break;
			case "IGNORE":
				$button = self::getIgnoreButton("","");
				break;
			case "REMINDER":
				$button = self::getSendReminderButton();
				break;
			case "CANCEL":
				$button = self::getCancelInterestButton();
				break;
		}
		return $button;
	}
	
	
	public static function getAlbumButton($count,$gender)
	{
		if($count<=1)
			$button["label"] = "Photo";
		else
			$button["label"] = "Album";
		$button["value"] = $count;
		if($count>=1)
			$button["action"] = "ALBUM";
		if($count>=1)
		{
			if($gender == "F")
				$button["iconid"] = IdToAppImagesMapping::ALBUM;
			else
				$button["iconid"] = IdToAppImagesMapping::MALE_ALBUM;
		}
		else
		{
			if($gender == "F")
				$button["iconid"] = IdToAppImagesMapping::DISABLE_ALBUM;
			else
				$button["iconid"] = IdToAppImagesMapping::DISABLE_MALE_ALBUM;
		}
		$button = self::buttonMerge($button);
		return $button;
	}
	
	/*public static function getContactsButton($contact,$gender,$page='')
	{
		$button 		= Array(); 
		$responseArray	= Array();
		$date           = date_create($contact["TIME"]);
		$date           = date_format($date, 'jS M Y');
		if($contact["SELF"] == "S")
		{
			switch ($contact["TYPE"])
			{
				case ContactHandler::ACCEPT:
					$button[] = self::getSendMessageButton();
					$button[] = self::getContactDetailsButton();
					$responseArray["buttons"]["primary"] = $button;
					break;
				case ContactHandler::INITIATED:
					$button[] = self::getSendReminderButton($contact["COUNT"]);
					$button[] = self::getCancelInterestButton();
					$button[] = self::getContactDetailsButton();
					$responseArray["buttons"]["primary"]      = $button;
					break;
				case ContactHandler::CANCEL_CONTACT:
					//echo "CANCEL_CONTACT";
					$button[]                 = self::getInitiateButton($page);
					$button[]                 = self::getShortListButton('','',1);
					$button[]                 = self::getContactDetailsButton();
					$responseArray["buttons"]["primary"]      = $button;
					$responseArray["infobtnlabel"] = "You Cancelled your interest on " . $date;
					break;
				case ContactHandler::DECLINE:
					//echo "DECLINE";
					$responseArray["infobtnlabel"] = ucwords($this->heshe) . " Declined your interest on " . $date;
					break;
				case ContactHandler::CANCEL:
					$responseArray["infobtnlabel"] = "You Cancelled your interest on " . $date;
					break;
			}
		}
		else
		{
			switch ($contact["TYPE"])
			{
				case ContactHandler::INITIATED:
					$button[]                 = self::getAcceptButton("Accept Interest",$page);
					$button[]                 = self::getDeclineButton($page);
					$responseArray["buttons"]["primary"] = $button;
					break;
				case ContactHandler::CANCEL_CONTACT:
					//echo "CANCEL_CONTACT";
					$responseArray["infobtnlabel"] = ucwords($this->heshe) . " Cancelled " . $this->hisher . " interest on " . $date;
					break;
				case ContactHandler::ACCEPT:
					//echo "ACCEPT";
					$button[]                 = self::getSendMessageButton();
					$button[]                 = self::getContactDetailsButton();
					$responseArray["buttons"]["primary"] = $button;
					break;
				case ContactHandler::DECLINE:
					//echo "DECLINE";
					$button[]                      = self::getAcceptButton("Accept Again");
					$responseArray["buttons"]["primary"]      = $button;
					$responseArray["infobtnlabel"] = "You Decline " . $this->hisher . " interest on " . $date;
					break;
				case ContactHandler::CANCEL:
					//echo "CANCEL";
					$responseArray["infobtnlabel"] = ucwords($this->heshe) . " Cancelled " . $this->hisher . " interest on " . $date;
					break;
			}
		}
		return $responseArray;
	}*/
				
	 
}
