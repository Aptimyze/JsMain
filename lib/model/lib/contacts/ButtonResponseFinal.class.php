<?php

Class ButtonResponseFinal
{
	private $page;
	private $buttonObj;
	private $contactObj;
	private $contactHandlerObj;
	private $privilageObj;
	private $channel;
	private $source;
	private $viewer;
	private $contactType;
	private $loginProfile;
	private $otherProfile;
	public function __construct($loginProfile='', $otherProfile='', $page='', $contactHandler = "")
	{
		$this->page         = $page;
		$this->loginProfile = $loginProfile;
		$this->otherProfile = $otherProfile;
		$this->channel = MobileCommon::getChannel();
		if($this->channel == "MS")
			$this->channel = "M";
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
			$this->page["count"] = $this->contactObj->getCOUNT();
		}
	}
	public function getButtonArray()
	{
		if($this->loginProfile->getPROFILEID())
		{

			$privilageArray = $this->privilageObj->getPrivilegeArray();
			$date           = date_create($this->contactObj->getTIME());
			$date           = date_format($date, 'jS M Y');
			$type 			= $this->contactObj->getTYPE();

			if ($this->contactObj->getsenderObj()->getPROFILEID() == $this->contactHandlerObj->getViewer()->getPROFILEID()) {
				$viewer = "S";
			}
			else
			{
				$viewer = "R";
			}
			if($this->page["isIgnored"] == 1)
			{
				$type = "B";
				$viewer = "S";
			}
			$source = $this->page['page_source']?$this->page['page_source']:$this->page['source'];
			if($source == "VSP")
			{
				$source = "S";
			}
			if($source=="VDP_VSP")
			{
				if($this->contactObj->getCount()>1)
					$type="R";
			}
			$this->page["CHAT_GROUP"] = $type;
		  //echo "source=>".$source." channel=> ".$this->channel." viewer=> ".$viewer." type=>".$type;die;
			$buttonsResponse = self::getButtons($source,$this->channel,$viewer,$type);
			foreach($buttonsResponse as $key=>$val)
			{
				if($val->TYPE == "TEXT")
					$responseArray["infomsglabel"] = $this->getButtonsFinalResponse($val,$this->page,$this->loginProfile, $this->otherProfile);
				else if($val->TYPE == "EXTRA_TEXT")
						$responseArray = $responseArray + $this->getExtraText($val);

				else
					$buttons[] = $this->getButtonsFinalResponse($val,$this->page,$this->loginProfile, $this->otherProfile);
			}
			$responseArray['buttons'] = $buttons;
                        $responseArray['contactType'] = $type;

		}
		else
		{
			$params["source"] = $this->page['page_source']?$this->page['page_source']:$this->page['source'];
			$params["channel"] = $this->channel;
			$responseArray = $this->getLogoutButtonArray($params);
		}

		$finalResponse = self::buttonDetailsMerge($responseArray);
		return $finalResponse;
	}
	public function getAfterActionButton($type)
	{
		$source = $this->page['page_source']?$this->page['page_source']:$this->page['source'];
		$channel = $this->channel;
		if ($this->contactObj->getsenderObj()->getPROFILEID() == $this->contactHandlerObj->getViewer()->getPROFILEID()) {
			$viewer = "S";
		}
		else
		{
			$viewer = "R";
		}
		if($source == "VSP")
		{
			$source = "S";
		}
		elseif($source == "VSP_VDP")
		{
			$source = "VDP";
		}
//		echo "source=>".$source." Channel=>".$channel." Viewer=>".$viewer." type=>".$type;
		$buttonsResponse = self::getButtons($source,$channel,$viewer,$type);//die;
		foreach($buttonsResponse as $key=>$val)
		{
			if($val->TYPE == "REMINDER")
			{
				$this->page["COUNT"] = $this->contactObj->getCOUNT();
			}
			if($val->TYPE == "TEXT")
				$responseArray["infomsglabel"] = $this->getButtonsFinalResponse($val,$this->page,$this->loginProfile, $this->otherProfile);
			else if($val->TYPE == "EXTRA_TEXT")
			{
				$responseArray = $responseArray + $this->getExtraText($val);
			}
				else
				$buttons[] = $this->getButtonsFinalResponse($val,$this->page,$this->loginProfile, $this->otherProfile);
		}
		$responseArray['buttons'] = $buttons;
		$finalResponse = self::buttonDetailsMerge($responseArray);
		return $finalResponse;

	}

	public static function getListingButtons($source,$channel,$viewer="",$type="",$page="",$count="")
	{
		//var_dump($type);die;
		/*$exclude_list = array("VDP","VDP_VSP","S","SHORTLIST","PEOPLE_WHO_VIEWED_MY_CONTACTS","ACCEPTANCES_SENT","ACCEPTANCES_RECEIVED","NOT_INTERESTED_BY_ME","NOT_INTERESTED");
		if($channel=="M")
		{
			if(!in_array($source, $exclude_list))
			{echo "string";die;
					$type = $this->contactObj->getTYPE();
					if($type=="I")
					{
						$count = $this->contactObj->getCOUNT();
					}
				if ($this->contactObj->getsenderObj()->getPROFILEID() == $this->contactHandlerObj->getViewer()->getPROFILEID()) {
					$viewer = "S";
				}
				else
				{
					$viewer = "R";
				}
			}
		}*/
		//echo $source." ".$channel." ".$viewer.",".$type;die;
		$type = $type?$type:"N";
		$buttonsResponse = self::getButtons($source,$channel,$viewer,$type);
		$responseArray = array();

		if(is_array($buttonsResponse))
		{
			foreach($buttonsResponse as $key=>$val)
			{

				if($val->TYPE == "TEXT")
				{
					$buttons[] = self::getButtonsFinalResponse($val,$page,"","",$channel);
					$responseArray["infomsglabel"] = self::getButtonsFinalResponse($val,$page);

				}
				else
				$buttons[] = self::getButtonsFinalResponse($val,$page,"","","",$count);
			}
		}
		$responseArray['buttons'] = $buttons;
		$finalResponse = self::buttonDetailsMerge($responseArray);
		return $finalResponse;

	}


	public function jsmsSearchRestButtonsarray($loginObj,$params)
        {
        	$topmsg = "Connect with ".$params["USERNAME"];
				if($loginObj->getPROFILEID() && $loginObj->getPROFILE_STATE()->getActivationState()->getUNDERSCREENED() == "Y"){
					$topmsg2 = "Interest will be delivered once your profile is screened";
					$topmsg = "";
				}
				$buttonFinal["photo"]["url"]=$params["PHOTO"]["url"]?$params["PHOTO"]["url"]:PictureFunctions::getNoPhotoJSMS($param["GENDER"]);
				$buttonFinal["topmsg"]=$topmsg;
				$buttonFinal["topmsg2"]=$topmsg2;
				return $buttonFinal;
		}


		public function jsmsRestButtonsrray($params,$type,$infoKey, $source, $viewer,$username,$count)
		{
			if($infoKey=="ACCEPTANCES_RECEIVED" || $infoKey=="ACCEPTANCES_SENT")
			{

				$restResponseArray["photo"] = self::getPhotoDetail($params["PHOTO"]);
				$restResponseArray["topmsg"] = "Interact with ".$username;


			}

			elseif($type=="D")
			{

				$restResponseArray["photo"] = self::getPhotoDetail($params["PHOTO"]);
				$restResponseArray["topmsg"] = "Changed your mind? Connect with ".$username;

			}

			else
			{

				$restResponseArray["photo"] = self::getPhotoDetail($params["PHOTO"]);
				$restResponseArray["topmsg"] = "Connect with ".$username;
				if($type=="I" && $count>1)
				{
					$restResponseArray["topmsg"] = $username." is yet to read your interest";
				}
			}

				return $restResponseArray;
		}

		public static function getPhotoDetail($photo)
        {
                $button["url"] = $photo;
                $button["label"]  = null;
                $button["action"] = null;
                $button           = self::buttonMerge($button);
                return $button;
        }

	public static function getButtons($source,$channel="",$viewer="",$contactType="")
	{
		$arr = ContactEngineMap::getFieldLabel("BUTTON_RESPONSE","",1);
		foreach($arr as $key=>$value)
		{
			if($value["SOURCE"] == $source)
			{
				if($channel && $value["CHANNEL"] != $channel)
				{
					continue;
				}
				if(($viewer && $value["VIEWER"] && $value["VIEWER"] != $viewer))
					continue;
				if($contactType && $value["CONTACT_TYPE"] && $value["CONTACT_TYPE"]!= $contactType)
					continue;
				$return = $value;

			}
		}

		$buttonsDetails = json_decode($return["BUTTONS"]);
		return $buttonsDetails;
	}

	public static function getButtonsFinalResponse($button,$params,$loginProfile='', $otherProfile='',$source='',$count='')
	{
		if($button->TYPE=="TEXT" && $source=="M")
		{
			$button->TYPE="DEFAULT";
		}
		switch($button->TYPE){
			case "INITIATE":
			$buttons = self::getSendInterestButton($button,$params);
			break;
			case "CONTACT_DETAIL":
			$buttons = self::getContactDetailsButton($button,$params);
			break;
			case "ACCEPT":
			$buttons = self::getAcceptButton($button,$params);
			break;
			case "DECLINE":
			$buttons = self::getDeclineButton($button,$params);
			break;
			case "CANCEL":
			$buttons = self::getCancelButton($button,$params);
			break;
			case "CANCEL_INTEREST":
			$buttons = self::getCancelInterestButton($button,$params);
			break;
			case "REMINDER":
			$buttons = self::getReminderButton($button,$params,$count);
			break;
			case "SHORTLIST":
			$buttons = self::getShortlistButton($button,$params,$loginProfile, $otherProfile);
			break;
			case "BLOCK":
			case "IGNORE":
			$buttons = self::getIgnoreButton($button,$params);
			break;
			case "MESSAGE":
			$buttons = self::getMessageButton($button,$params);
			break;
			case "TEXT":
			$buttons = self::getText($button,$params);
			break;
			case "CHAT":
			$buttons = self::getChatButton($button,$params);
			break;
			case "REPORT_ABUSE":
			$buttons = self::getReportAbuseButton($button,$params);
			break;
			default:
			$buttons = self::getdefaultButton($button,$params);
			break;
		}
		return $buttons;
	}

	public static function getSendInterestButton($button,$params)
	{
		if($params["stype"] || $params['STYPE'])
		{
			$stype = ($params["stype"])?$params["stype"]:$params["STYPE"];
			$tracking = "&stype=".$stype;
		}
		if($params["tracking"])
		{
			$tracking = $params["tracking"];
		}
		$buttons["action"] 		= $button->TYPE;
		$buttons["label"] 		= $button->label;
		$buttons["iconid"] 		= $button->icon;
		$buttons["primary"] 	= $button->primary;
		$buttons["secondary"] 	= $button->secondary;
		$buttons["params"] 		= $tracking;
		$buttons['enable']		= $button->active=="true"?true:false;
		$buttons['id'] 			= $button->TYPE;
		$button = self::buttonMerge($buttons);
		return $buttons;
	}

	public static function getChatbutton($button,$params)
	{
		$buttons["action"] 		= $button->TYPE;
		$buttons["label"] 		= $button->label;
		$buttons["iconid"] 		= $button->icon;
		$buttons["primary"] 	= $button->primary;
		$buttons["secondary"] 	= $button->secondary;
		$buttons['enable']		= $button->active=="true"?true:false;
		$buttons['id'] 			= $button->TYPE;
		$buttons["params"]		= $params['USERNAME'].",".$params["OTHER_PROFILEID"].",".$params["PHOTO"]["url"].",".$params["CHAT_GROUP"];
		$button = self::buttonMerge($buttons);
		return $buttons;
	}

	public static function getText($button,$params)
	{

		return $button->label;
	}

	public static function getMessageButton($button,$params)
	{
		$buttons["action"] 		= $button->TYPE;
		$buttons["label"] 		= $button->label;
		$buttons["iconid"] 		= $button->icon;
		$buttons["primary"] 	= $button->primary;
		$buttons["secondary"] 	= $button->secondary;
		$buttons['enable']		= $button->active=="true"?true:false;
		$buttons['id'] 			= $button->TYPE;
		$button = self::buttonMerge($buttons);
		return $buttons;

	}

	public static function getdefaultButton($button,$params)
	{
		$buttons["action"] 		= $button->TYPE;
		$buttons["label"] 		= $button->label;
		$buttons["iconid"] 		= $button->icon;
		$buttons["primary"] 	= $button->primary;
		$buttons["secondary"] 	= $button->secondary;
		$buttons['enable']		= $button->active=="true"?true:false;
		$buttons['id'] 			= $button->TYPE;
		$button = self::buttonMerge($buttons);
		return $buttons;
	}


	public static function getIgnoreButton($button,$params)
	{
		$ignored = $params["isIgnored"];
		if($ignored)
		{
			$buttons["label"] 		= "Unblock"; ;
			$buttons["params"] 		= "&ignore=0";
		}
		else
		{
			$buttons["label"] 		= "Block";
			$buttons["params"] 		= "&ignore=1";
		}
		if($button)
		{
			$buttons["iconid"]              = $button->icon;
			$buttons["primary"]     		= $button->primary;
			$buttons["secondary"]   		= $button->secondary;
			$buttons['enable']              = $button->active=="true"?true:false;
			$buttons['id']                  = $button->TYPE;
			$buttons["action"]              = "IGNORE";
		}
		else
		{
			$buttons["action"]              = "IGNORE";
			$buttons['enable']              = true;
			$buttons['id']                  = "IGNORE";
		}
		$button = self::buttonMerge($buttons);
		return $buttons;

	}

	public static function getContactDetailsButton($button,$params)
	{
		$buttons["action"] 		= $button->TYPE;
		$buttons["label"] 		= $button->label;
		$buttons["iconid"] 		= $button->icon;
		$buttons["primary"] 	= $button->primary;
		$buttons["secondary"] 	= $button->secondary;
		$buttons['enable']		= $button->active=="true"?true:false;
		$buttons['id'] 			= $button->TYPE;
		$buttons           = self::buttonMerge($buttons);
		return $buttons;
	}

	public static function getAcceptButton($button,$params)
	{
		if($params["responseTracking"] )
		{
			$responseTracking = $params["responseTracking"];
			$tracking = "&responseTracking=".$responseTracking;
		}
		if($params["tracking"])
		{
			$tracking = $params["tracking"];
		}
		$buttons["action"] 		= $button->TYPE;
		$buttons["label"] 		= $button->label;
		$buttons["iconid"] 		= $button->icon;
		$buttons["primary"] 	= $button->primary;
		$buttons["secondary"] 	= $button->secondary;
		$buttons["params"]		= $tracking;
		$buttons['enable']		= $button->active=="true"?true:false;
		$buttons['id'] 			= $button->TYPE;
		$button = self::buttonMerge($buttons);
		return $buttons;
	}

	public static function getDeclineButton($button,$params)
	{
		if($params["responseTracking"] )
		{
			$responseTracking = $params["responseTracking"];
			$tracking = "&responseTracking=".$responseTracking;
		}
		if($params["tracking"])
		{
			$tracking = $params["tracking"];
		}
		$buttons["action"] 		= $button->TYPE;
		$buttons["label"] 		= $button->label;
		$buttons["iconid"] 		= $button->icon;
		$buttons["primary"] 	= $button->primary;
		$buttons["secondary"] 	= $button->secondary;
		$buttons["params"]		= $tracking;
		$buttons['enable']		= $button->active=="true"?true:false;
		$buttons['id'] 			= $button->TYPE;
		$button = self::buttonMerge($buttons);
		return $buttons;

	}

	public static function getCancelButton($button,$params)
	{
		$buttons["action"] 		= $button->TYPE;
		$buttons["label"] 		= $button->label;
		$buttons["iconid"] 		= $button->icon;
		$buttons["primary"] 	= $button->primary;
		$buttons["secondary"] 	= $button->secondary;
		$buttons['enable']		= $button->active=="true"?true:false;
		$buttons['id'] 			= $button->TYPE;
		$button = self::buttonMerge($buttons);
		return $buttons;

	}

	public static function getCancelInterestButton($button,$params)
	{
		$buttons["action"] 		= $button->TYPE;
		$buttons["label"] 		= $button->label;
		$buttons["iconid"] 		= $button->icon;
		$buttons["primary"] 	= $button->primary;
		$buttons["secondary"] 	= $button->secondary;
		$buttons['enable']		= $button->active=="true"?true:false;
		$buttons['id'] 			= $button->TYPE;
		$button = self::buttonMerge($buttons);
		return $buttons;

	}

	public static function getReminderButton($button,$params,$count)
	{
		if(!$count)
		$count = $params["count"];
		if($count){
			if ($count < ErrorHandler::REMINDER_COUNT) {
				if((ErrorHandler::REMINDER_COUNT - $count) == 1)
				{
					$buttons["label"]  = "Remind Again";
					$buttons["enable"] = true;
				}
				else
				{
					$buttons["label"]  = $button->label;
					$buttons["enable"] = true;

				}
				$buttons["action"] = "REMINDER";
			} else {

				$buttons["label"]  = "Reminder ".(ErrorHandler::REMINDER_COUNT - 1)."/".(ErrorHandler::REMINDER_COUNT - 1);
				$buttons["enable"] = false;
				$buttons["action"] = "DEFAULT";
			}
		}else{
			$buttons["iconid"] = IdToAppImagesMapping::SEND_REMINDER;
			$buttons["label"]  = $button->label;
			$buttons["action"] = "REMINDER";
		}
		if($params["page_source"] == "VDP_VSP")
		{
			$buttons["enable"] = false;
		}
		else
			$buttons["enable"] = $button->active=='true' ? true : false;

		$buttons["iconid"] 		= $button->icon;
		$buttons["primary"] 	= $button->primary;
		$buttons["secondary"] 	= $button->secondary;
		$buttons['id'] 			= $button->TYPE;
		$button = self::buttonMerge($buttons);
		return $buttons;
	}

	public static function getShortlistButton($button,$params,$loginProfile='', $otherProfile='')
	{
		if(isset($params['SHORTLIST']))
		{
			$params['isBookmarked'] = $params['SHORTLIST'] == "Y"?1:0;
		}
		if(!isset($params['isBookmarked'])) {
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
		else
		{
			$isBookmarked = $params['isBookmarked'];
		}
		$buttons["action"] 		= $button->TYPE!=""?$button->TYPE:"SHORTLIST";
		if ($isBookmarked == 1) {
			$buttons["iconid"] = IdToAppImagesMapping::SHORTLISTEDBUTTON;
			$buttons["label"]  = "Remove Shortlist";
			$buttons["params"] = "&shortlist=true";
		}
		else{
			$buttons["iconid"] = IdToAppImagesMapping::SHORTLISTBUTTON;
			$buttons["label"]  = "Shortlist";
			$buttons["params"]  = "&shortlist=false";
		}
		$buttons["primary"] 	= $button->primary;
		$buttons["secondary"] 	= $button->secondary;
		$buttons['enable']		= !$button ? true : ($button->active=='true'  ? true : false);
		$buttons['id'] 			= $buttons["action"];
		$button = self::buttonMerge($buttons);
		return $buttons;
	}
	public function getInitiatedButton()
	{
		if($this->contactObj->getTYPE() == ContactHandler::INITIATED)
		{
			$button["iconid"] = IdToAppImagesMapping::TICK_CONTACT;
			$button["label"]  = "Interest Sent";
			$button["enable"]  = false;
			$button['id'] 			= "INITIATE";
			$buttons = self::buttonMerge($button);
			return $buttons;
		}
		else if($this->contactObj->getTYPE() == ContactHandler::NOCONTACT && ($this->contactHandlerObj->getViewer()->getPROFILE_STATE()->getActivationState()->getUNDERSCREENED() == "Y"))
		{
			$button["iconid"] = IdToAppImagesMapping::UNDERSCREENING;
			$button["label"]  = "Interest Saved";
			$button["enable"]  = false;
			$button['id'] 			= "INITIATE";
			$buttons = self::buttonMerge($button);
			return $buttons;
		}
		return null;
	}


	public static function buttonMerge($button)
	{
		$buttonArr["iconid"] = null;
		$buttonArr["label"]  = null;
		$buttonArr["action"] = null;
		$buttonArr["value"]  = null;
		$buttonArr["params"] = null;
		$buttonArr['enable'] = null;
		$buttonArr['primary'] = null;
		$buttonArr['secondary']=null;
		$buttonArr['id'] = null;
		return array_merge($buttonArr, $button);
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
	public function getLogoutButtonArray($params)
	{
		$buttonsResponse = self::getButtons($params["source"],$params["channel"],'','N');
		foreach($buttonsResponse as $key=>$val)
		{
			$buttons[] = self::getButtonsFinalResponse($val,$page);
		}
		foreach ($buttons as $key => $value) {
			$buttons[$key]->action = "LOGIN";
		}
		$responseArray["buttons"] = $buttons;
		$finalResponse = self::buttonDetailsMerge($responseArray);
		return $finalResponse;
	}
	public static function getCustomButton($label="",$value="",$action="",$param="",$iconid="")
	{
		$button["label"] = $label==""?null:$label;
		$button["value"] = $value==""?null:$value;
		$button["action"] = $action==""?null:$action;
		$button["params"] = $param==""?null:$param;
		$button["iconid"] = $iconid==""?null:$iconid;
		$button["id"] = $action==""?null:$action;
		$buttons = self::buttonMerge($button);
		return $buttons;
	}

public function getExtraText($button){
	$arr = array();
	if(!$button) return $arr;
	foreach ($button->KEYS as $key => $value) {
		# code...
			$arr[$value->KEYNAME] = $value->STATIC ? $value->TEXT : $this->replaceText($value->TEXT);
	}
	return $arr;
}
public static function getReportAbuseButton($button,$params)
{
	$buttons["action"] 		= $button->TYPE;
	$buttons["label"] 		= $button->label;
	$buttons["iconid"] 		= $button->icon;
	$buttons["primary"] 	= $button->primary;
	$buttons["secondary"] 	= $button->secondary;
	$buttons['enable']		= $button->active=="true"?true:false;
	$buttons['id'] 			= $button->TYPE;
	$button = self::buttonMerge($buttons);
	return $buttons;
}

private function replaceText($value){
	return str_replace(array("{OTHER_USERNAME}","{USERNAME}","{DATE}"),array($this->contactHandlerObj->getViewer()->getUSERNAME(),$this->contactHandlerObj->getViewed()->getUSERNAME(),$this->contactObj->getTIME()) ,$value);
}
}
