<?php
Class ButtonResponseApi
{
	private $page;
	private $buttonObj;
	private $contactObj;
	private $contactHandlerObj;
	private $privilageObj;
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
		}
	}
	public function getButtonArray($params)
	{
		if($this->loginProfile->getPROFILEID())
		{  
			$gender         = $this->contactHandlerObj->getViewed()->getGENDER();
			$hisher         = $gender == "F" ? "her" : "his";
			$himher         = $gender == "F" ? "her" : "him";
			$heshe          = $gender == "F" ? "she" : "he";
			$privilageArray = $this->privilageObj->getPrivilegeArray();
			$date           = date_create($this->contactObj->getTIME());
			$date           = date_format($date, 'jS M Y');
			if($params["IGNORED"] == 1)
			{
				$button[] = self::getIgnoreButton('','',1);
				$responseArray["buttons"] = $button;
			}
			else
			{
				if ($this->contactObj->getsenderObj()->getPROFILEID() == $this->contactHandlerObj->getViewer()->getPROFILEID()) {
					//echo "sender";
					switch ($this->contactObj->getTYPE()) {
						case ContactHandler::NOCONTACT:
							$button[]                 = self::getInitiateButton($this->page);
							$button[]                 = self::getShortListButton($this->loginProfile, $this->otherProfile);
							$button[]                 = self::getContactDetailsButton();
							
							$responseArray["buttons"] = $button;
							//echo "NOCONTACT";
							break;
						case ContactHandler::INITIATED:
							$button[]                 = self::getSendReminderButton($this->contactObj->getCOUNT());
							$button[]                 = self::getCancelInterestButton();
							$button[]                 = self::getContactDetailsButton();
							$responseArray["buttons"] = $button;
							if ($this->contactObj->getCOUNT() >= ErrorHandler::REMINDER_COUNT)
								$responseArray["infobtnlabel"] = "You cannot send more than 2 reminders, you may call the user directly";
							//echo "INITIATE";
							break;
						case ContactHandler::CANCEL_CONTACT:
							//echo "CANCEL_CONTACT";

							$button[]                 = self::getInitiateButton($this->page);
							$button[]                 = self::getShortListButton($this->loginProfile, $this->otherProfile);
							$button[]                 = self::getContactDetailsButton();
							$responseArray["buttons"]      = $button;
							$responseArray["infobtnlabel"] = "You cancelled interest on " . $date;
							break;
						case ContactHandler::ACCEPT:
							//echo "ACCEPT";
								$button[]                 = self::getSendMessageButton();
								$button[]                 = self::getContactDetailsButton();
								$button[]                 = self::getCancelInterestButton();
								$responseArray["buttons"] = $button;
							break;
						case ContactHandler::DECLINE:
							//echo "DECLINE";
							$responseArray["infobtnlabel"] = "They declined your interest on " . $date;
							break;
						case ContactHandler::CANCEL:
							$button[] = self::getAcceptButton("Accept Again");
							$responseArray["infobtnlabel"] = "You cancelled your interest on " . $date;
							$responseArray["buttons"] = $button;
							break;
					}
				} else {
					//echo "receiver";
					switch ($this->contactObj->getTYPE()) {
						case ContactHandler::NOCONTACT:
							$button["shortlist"] = self::buttonMerge(self::getShortListButton($this->loginProfile, $this->otherProfile));
							//echo "NOCONTACT";
							break;
						case ContactHandler::INITIATED:
							//echo "INITIATE";
							$button[]                 = self::getAcceptButton("Accept",$this->page);
							$button[]                 = self::getDeclineButton($this->page);
							$responseArray["buttons"] = $button;
							break;
						case ContactHandler::CANCEL_CONTACT:
							//echo "CANCEL_CONTACT";
							$responseArray["infobtnlabel"] = "They cancelled interest on " . $date;
							break;
						case ContactHandler::ACCEPT:
							//echo "ACCEPT";

								$button[]                 = self::getSendMessageButton();
								$button[]                 = self::getContactDetailsButton();
								$button[]                 = self::getDeclineButton($this->page);
								$responseArray["buttons"] = $button;
							break;
						case ContactHandler::DECLINE:
							//echo "DECLINE";
							$button[]                      = self::getAcceptButton("Accept Again");
							$responseArray["buttons"]      = $button;
							$responseArray["infobtnlabel"] = "You declined interest on " . $date;
							break;
						case ContactHandler::CANCEL:
							//echo "CANCEL";
							$responseArray["infobtnlabel"] = "They cancelled interest on " . $date;
							$responseArray["buttons"] = $button;
							break;
					}
				}
			}

		}
		else
		{
			$responseArray = $this->getLogoutButtonArray();
		}
		
		$finalResponse = self::buttonDetailsMerge($responseArray);
                if($this->contactObj)
                    $finalResponse["contactType"] = $this->contactObj->getTYPE();
		return $finalResponse;
	}
	public function getLogoutButtonArray()
	{
		$button[] = $this->getInitiateButton();
		$button[0]["action"] = "LOGIN";
		$button[] = $this->getShortListButton($this->loginProfile,$this->Profile);
		$button[1]["action"] = "LOGIN";
		$button[] = self::getContactDetailsButton();
		$button[2]["action"] = "LOGIN";
		$responseArray["buttons"] = $button;
		return $responseArray;
	}
	public static function getShortListButton($loginProfile='', $otherProfile='',$isBookmarked=null)
	{
		if(is_null($isBookmarked) || !($isBookmarked==0 || $isBookmarked==0)){		
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
		return (self::buttonMerge($button));
	}
	public static function getContactDetailsButton()
	{
		$button["iconid"] = IdToAppImagesMapping::CONTACTDETAILBUTTON;
		$button["label"]  = "Contact";
		$button["action"] = "CONTACTDETAIL";
		$button           = self::buttonMerge($button);
		return $button;
	}
	public static function getAcceptButton($str,$page='')
	{
		$button["iconid"] = IdToAppImagesMapping::ACCEPT;
		$button["label"]  = $str;
		$button["action"] = "ACCEPT";
		if (isset($page["responseTracking"]) && $str == "Accept")
			$button["params"] = "&responseTracking=" . $page["responseTracking"];
		if($rtype = sfContext::getInstance()->getRequest()->getParameter("retainResponseType"))
 		{
			$button["params"] = "&responseTracking=".$rtype;
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
			$button["params"] = "&page_source=" . $page["page_source"];
		$button = self::buttonMerge($button);
		return $button;
	}
	public static function getDeclineButton($page='')
	{
		$button["iconid"] = IdToAppImagesMapping::DECLINE;
		$button["label"]  = "Decline";
		$button["action"] = "DECLINE";
		if (isset($page["responseTracking"]))
			$button["params"] = "&responseTracking=" . $page["responseTracking"];
                if($rtype = sfContext::getInstance()->getRequest()->getParameter("retainResponseType"))
                {
                        $button["params"] = "&responseTracking=".$rtype;
                }
		$button = self::buttonMerge($button);
		return $button;
	}
	public static function getIgnoreButton($loginProfile='', $otherProfile='',$isIgnored=null,$enable=true,$label)
    {
    	
		if ($isIgnored) {
           $button["label"]  = $label ? $label : "Unblock";
		if(MobileCommon::isApp()=="I")
		{
			$button["iconid"] = IdToAppImagesMapping::UNDO_IGNORE;
			$button["action"] = "UNDO_IGNORE";
		}
		else
		{
			$button["iconid"] = IdToAppImagesMapping::A_IGNORE;
			$button["action"] = "IGNORE";
		}
                    $button["params"] = "&ignore=0";
            }
            else{
                    $button["iconid"] = IdToAppImagesMapping::A_IGNORE;
                    $button["label"]  = $label? $label:"Block";
                    $button["action"] = "IGNORE";
                    $button["params"]  = "&ignore=1";
            }
            return (self::buttonMerge($button,$enable));
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
		$button["label"]  = "Message";
		$button["action"] = "WRITE_MESSAGE";
		$button           = self::buttonMerge($button);
		return $button;
	}
	public static function getSendReminderButton($count='')
	{
		if ($count < ErrorHandler::REMINDER_COUNT) {
			$button["iconid"] = IdToAppImagesMapping::SEND_REMINDER;
			$button["label"]  = "Send Reminder";
			$button["action"] = "REMINDER";
		} else {
			$button["iconid"] = IdToAppImagesMapping::REMINDER_SENT;
			$button["label"]  = "Reminder Sent";
		}
		$button = self::buttonMerge($button);
		return $button;
	}
	public function getInitiatedButton($androidText = false,$privilageArray="")
	{
		if($this->contactObj->getTYPE() == ContactHandler::INITIATED)
		{
			$button = self::getCancelInterestButton();
			$responseArray["canChat"] = false;
			if($androidText && $privilageArray["0"]["SEND_REMINDER"]["MESSAGE"] != "Y")
			{
				$responseArray["infobtnlabel"]  = "BECOME A PAID MEMBER";
				$responseArray["infobtnvalue"]  = "";
				$responseArray["infobtnaction"] = "MEMBERSHIP";
				$responseArray["infomsglabel"] = "Only paid members can start the chat";
			}
			else{
				$responseArray["canChat"] = true;
			}
		}
		else if(($this->contactObj->getTYPE() == ContactHandler::NOCONTACT) && ($this->contactHandlerObj->getViewer()->getPROFILE_STATE()->getActivationState()->getUNDERSCREENED() == "Y"))
		{
			$button["iconid"] = IdToAppImagesMapping::TICK_CONTACT;
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
	public static function buttonMerge($button)
	{
		$buttonArr["iconid"] = null;
		$buttonArr["label"]  = null;
		$buttonArr["action"] = null;
		$buttonArr["value"]  = null;
		$buttonArr["params"] = null;
		return array_merge($buttonArr, $button);
	}
	public function getAfterActionButton($actionType)
	{  
		$gender         = $this->contactHandlerObj->getViewed()->getGENDER();
		$hisher         = $gender == "F" ? "her" : "his";
		$himher         = $gender == "F" ? "her" : "him";
		$heshe          = $gender == "F" ? "she" : "he";
		$privilageArray = $this->privilageObj->getPrivilegeArray();
		$date           = date_create($this->contactObj->getTIME());
		$date           = date_format($date, 'jS M Y');
		if ($this->contactObj->getsenderObj()->getPROFILEID() == $this->contactHandlerObj->getViewer()->getPROFILEID()) {
			//echo "sender";
			
			switch ($this->contactObj->getTYPE()) {
				case ContactHandler::REMINDER:
					$button                        = $this->getSendReminderButton();
					$responseArray["button"]      = $button;
					$responseArray["infomsglabel"] = "Reminder of interest Sent";
					//echo "NOCONTACT";
					break;
				case ContactHandler::INITIATED:
					if($this->contactObj->getCOUNT()>1)
					{
						$button                        = $this->getSendReminderButton(3);
						$responseArray["button"]      = $button;
						if($privilageArray["0"]["SEND_REMINDER"]["MESSAGE"] != "Y" &&  $this->contactObj->getCOUNT() < ErrorHandler::REMINDER_COUNT)
							$responseArray["infomsglabel"] = "Reminder of interest Sent";
					}
					else
					{
						$button                   = $this->getInitiatedButton();
						$responseArray["button"] = $button;
					}
					//echo "INITIATE";
					break;
				case ContactHandler::CANCEL_CONTACT:
					//echo "CANCEL_CONTACT";
					$button[]                 = $this->getInitiateButton($this->page);
					$button[]                 = self::getShortListButton($this->loginProfile, $this->otherProfile);
					$button[]                 = self::getContactDetailsButton();
					$responseArray["buttons"] = $button;
					$responseArray["infobtnlabel"] = "You cancelled your interest on " . $date;
					break;
				case ContactHandler::CANCEL:
					//echo "CANCEL";
					$button[] = self::getAcceptButton("Accept Again");
					$responseArray["infobtnlabel"] = "You cancelled your interest on " . $date;
					$responseArray["buttons"] = $button;
					break;

				case ContactHandler::ACCEPT:
						$button[]                      = $this->getSendMessageButton();
						$button[]                      = self::getContactDetailsButton();
						$button[]                 	   = self::getCancelInterestButton();
						$responseArray["buttons"]      = $button;
						$responseArray["infomsglabel"] = "You are now connected with " . $this->contactHandlerObj->getViewed()->getUSERNAME();
						$responseArray["infomsgiconid"] = '023';
					break;
			}
		} else {
			//echo "receiver";
			switch ($this->contactObj->getTYPE()) {
				case ContactHandler::ACCEPT:
                                        if($actionType == "CHATACCEPT")
                                        {

                                        if ($privilageArray["0"]["COMMUNICATION"]["MESSAGE"] == "Y") {
                                                $button[]                       = self::getCustomButton("Interest accepted, Continue chat","ACCEPT","","","","91");
                                                $responseArray["canchat"]       = "false";
						$responseArray["buttons"]      = $button;

					} else {
							$button[]                       = self::getCustomButton("Interest accepted","ACCEPT","","","","91");
							$responseArray["canchat"]       = "false";
						if(strpos(sfContext::getInstance()->getRequest()->getParameter("newActions"), "MEMBERSHIP")!== false )
						{
							$responseArray["infobtnlabel"]  = "Buy paid membership to Write messages or view contact details";
							$responseArray["infobtnvalue"]  = "";
							$responseArray["infobtnaction"] = "MEMBERSHIP";
						}
						else{
							$responseArray["infobtnlabel"]  = "Call us to Buy paid membership to Write messages or view contact details";
							$responseArray["infobtnvalue"]  = "18004196299";
							$responseArray["infobtnaction"] = "CALL";		
						}

                                            }
                                        }
                                    else {
						$button[]                      = $this->getSendMessageButton();
						$button[]                      = self::getContactDetailsButton();
						$button[]                 	   = self::getDeclineButton($this->page);
						$responseArray["buttons"]      = $button;
						$responseArray["infomsglabel"] = "You are now connected with " . $this->contactHandlerObj->getViewed()->getUSERNAME();
						$responseArray["infomsgiconid"] = '023';
                                        }				
                                break;
				case ContactHandler::DECLINE:
					//echo "DECLINE";
					if($actionType == "CHATDECLINE") {
						$button[] = self::getCustomButton("Interest declined", "DECLINE", "", "", "", "");
						$responseArray["infomsglabel"] = "Interest declined, you can't chat with this member any more";
						$responseArray["buttons"] = $button;
					}
					else {
						$button[] = $this->getAcceptButton("Accept Again");
						$responseArray["buttons"] = $button;
						$responseArray["infobtnlabel"] = "You declined interest on " . $date;
					}
					break;
				case ContactHandler::CANCEL:
					//echo "CANCEL";
					$responseArray["infobtnlabel"] = "They cancelled interest on " . $date;
					$responseArray["buttons"] = $button;
					break;


			}
		}
		$finalResponse = self::buttonDetailsMerge($responseArray);
		return $finalResponse;
	}
	
	public static function actionDetailsMerge($actionDetails)
	{
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
		$responseSet["buttons"]       = null;
		$responseSet["button"]       = null;
		$responseSet["infomsgiconid"] = null;
		$responseSet["infomsglabel"]  = null;
		$responseSet["infobtnlabel"]  = null;
		$responseSet["infobtnvalue"]  = null;
		$responseSet["infobtnaction"] = null;
		$finalResponse                = array_merge($responseSet, $buttonDetails);
		return $finalResponse;
	}
	
	
	public static function getCustomButton($label="",$value="",$action="",$param="",$iconid="")
	{
		$button["label"] = $label==""?null:$label;
		$button["value"] = $value==""?null:$value;
		$button["action"] = $action==""?null:$action;
		$button["params"] = $param==""?null:$param;
		$button["iconid"] = $iconid==""?null:$iconid;
		return $button;
	}
	
	public function getCustomButtonByBName($type,$page='')
	{
		$button = array();
		switch($type)
		{
			case "ACCEPT":
				$button = $this->getAcceptButton("Accept",$page);
				break;
			case "DECLINE":
				$button = $this->getDeclineButton($page);
				break;
			case "MESSAGE":
				$button = $this->getSendMessageButton();
				break;			
			case "CONTACT":
				$button = self::getContactDetailsButton();
				break;
			case "INITIATE":
				$button = self::getInitiateButton($page);
				break;
			case "SHORTLIST":
				$button = self::getShortListButton("","",0);
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
	
	public static function getContactsButton($contact,$gender,$page='')
	{  
		$button 		= Array(); 
		$responseArray	= Array();
		$hisher         = $gender == "F" ? "her" : "his";
		$himher         = $gender == "F" ? "her" : "him";
		$heshe          = $gender == "F" ? "she" : "he";
		$date           = date_create($contact["TIME"]);
		$date           = date_format($date, 'jS M Y');
		if($contact["SELF"] == "S")
		{
			switch ($contact["TYPE"])
			{
				case ContactHandler::ACCEPT:
					$button[] = self::getSendMessageButton();
					$button[] = self::getContactDetailsButton();
					$button[] = self::getCancelInterestButton();
					$responseArray["buttons"] = $button;
					break;
				case ContactHandler::INITIATED:
					$button[] = self::getSendReminderButton($contact["COUNT"]);
					$button[] = self::getCancelInterestButton();
					$button[] = self::getContactDetailsButton();
					$responseArray["buttons"]      = $button;
					break;
				case ContactHandler::CANCEL_CONTACT:
					//echo "CANCEL_CONTACT";	
					$loggedInProfileObj=new Profile('',$contact['SENDER']);
					$otherProfileObj=new Profile('',$contact['RECEIVER']);
					$button[]                 = self::getInitiateButton($page);
					$button[]                 = self::getShortListButton($loggedInProfileObj,$otherProfileObj,1);
					$button[]                 = self::getContactDetailsButton();
					$responseArray["buttons"]      = $button;
					$responseArray["infobtnlabel"] = "You cancelled your interest on " . $date;
					break;
				case ContactHandler::DECLINE:
					//echo "DECLINE";
					$responseArray["infobtnlabel"] = "They declined interest on " . $date;
					break;
				case ContactHandler::CANCEL:
					$button[]                      = self::getAcceptButton("Accept Again");
					$responseArray["infobtnlabel"] = "You cancelled your interest on " . $date;
					$responseArray["buttons"] = $button;
					break;
			}
		}
		else
		{
			switch ($contact["TYPE"])
			{
				case ContactHandler::INITIATED:
					$button[]                 = self::getAcceptButton("Accept",$page);
					$button[]                 = self::getDeclineButton($page);
					$responseArray["buttons"] = $button;
					break;
				case ContactHandler::CANCEL_CONTACT:
					//echo "CANCEL_CONTACT";
					$responseArray["infobtnlabel"] = "They cancelled interest on " . $date;
					break;
				case ContactHandler::ACCEPT:
					//echo "ACCEPT";
					$button[]                 = self::getSendMessageButton();
					$button[]                 = self::getContactDetailsButton();
					$button[]                 = self::getDeclineButton($page);
					$responseArray["buttons"] = $button;
					break;
				case ContactHandler::DECLINE:
					//echo "DECLINE";
					$button[]                      = self::getAcceptButton("Accept Again");
					$responseArray["buttons"]      = $button;
					$responseArray["infobtnlabel"] = "You declined interest on " . $date;
					break;
				case ContactHandler::CANCEL:
					//echo "CANCEL";
					$responseArray["infobtnlabel"] = "They cancelled interest on " . $date;
					$responseArray["buttons"] = $button;	
					break;
			}
		}

		return $responseArray;
	}
				
	 
}
