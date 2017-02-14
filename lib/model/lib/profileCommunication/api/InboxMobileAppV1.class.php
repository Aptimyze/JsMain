<?php
/**
 * @brief This class is for myjs mobile App version 1 functions
 * @author Reshu Rajput
 * @created 2013-12-19
 */
class InboxMobileAppV1
{
	static public $informationTupleFields;
	static public $informationTypeFields;
	static public $myProfileIncompleteFields;
	static public $tupleTitleField;
	static public $noresultArray = Array("INTEREST_RECEIVED","ACCEPTANCES_RECEIVED","ACCEPTANCES_SENT","INTEREST_SENT","VISITORS","SHORTLIST","MY_MESSAGE","MATCH_ALERT","NOT_INTERESTED","NOT_INTERESTED_BY_ME","FILTERED_INTEREST","PEOPLE_WHO_VIEWED_MY_CONTACTS","CONTACTS_VIEWED","IGNORED_PROFILES","INTEREST_EXPIRING","INTEREST_ARCHIVED");
	const IGNORED_PROFILES = "Members blocked by you will appear here";
	const INTEREST_RECEIVED = "You have no interests left to respond to";
	const INTEREST_EXPIRING = "Interests which will expire within the next 7 days will appear here.";
	const ACCEPTANCES_RECEIVED = "No one has yet accepted your interest";
	const ACCEPTANCES_SENT = "You haven't yet accepted any interests sent to you";
	const INTEREST_SENT = "You haven't sent any interests yet";
	const VISITORS = "No one has visited your profile recently";
	const SHORTLIST = "You haven't shortlisted any profile yet";
	const MY_MESSAGE = "You have not sent or received any messages yet";
	const MATCH_ALERT = "Profiles you would see here are Matches sent to you every day on your Email ID. We have not sent you any 'Match Alert' emails yet.";
	const CONTACTS_VIEWED = "As a paid member you can :<br><br>See all phone numbers in one list <br>Call people instantly<br>No need to note down contact details <br>";
	const CONTACTS_VIEWED_PAID = "Contacts viewed by you would be shown here";
	const NOT_INTERESTED = "People who have declined the interest sent by you or have cancelled the interest sent to you will appear here";
	const NOT_INTERESTED_BY_ME = 'Interests you have declined/cancelled will appear here';
	const FILTERED_INTEREST ="People who have expressed interest in you but don't meet your filter criteria will appear here";
	const PEOPLE_WHO_VIEWED_MY_CONTACTS="People who viewed your contacts will appear here";
	const INTEREST_ARCHIVED = "Interests received more than 90 days earlier will appear here.";
	static public function init()
	{
		self::$informationTupleFields    = Array(
			"INTEREST_RECEIVED"=>Array(
				"PROFILECHECKSUM",
				"USERNAME",
				"OCCUPATION",
				"LOCATION",
				"AGE",
				"HEIGHT",
				"RELIGION",
				"CASTE",
				"MTONGUE",
				"INCOME",
				"subscription_icon",
				"subscription_text",
				"TIME",
				"MESSAGE",
				"SEEN",
				"edu_level_new",
				"userloginstatus",
				"VERIFICATION_SEAL",
                                "VERIFICATION_STATUS",
                                "NATIVE_CITY",
                                "NATIVE_STATE",
                                "ANCESTRAL_ORIGIN",
                                "NAME_OF_USER",
				),
			"ACCEPTANCES_RECEIVED"=>Array(
				"PROFILECHECKSUM",
				"USERNAME",
				"OCCUPATION",
				"LOCATION",
				"AGE",
				"HEIGHT",
				"RELIGION",
				"CASTE",
				"MTONGUE",
				"INCOME",
				"subscription_icon",
				"subscription_text",
				"TIME",
				"MESSAGE",
				"SEEN",
				"edu_level_new",
				"userloginstatus",
				"VERIFICATION_SEAL",
                                "VERIFICATION_STATUS",
                                "NATIVE_CITY",
                                "NATIVE_STATE",
                                "ANCESTRAL_ORIGIN",
                                "NAME_OF_USER",
				),
			"INTEREST_SENT"=>Array(
				"PROFILECHECKSUM",
				"USERNAME",
				"OCCUPATION",
				"LOCATION",
				"AGE",
				"HEIGHT",
				"RELIGION",
				"CASTE",
				"MTONGUE",
				"INCOME",
				"subscription_icon",
				"subscription_text",
				"TIME",
				"MESSAGE",
				"edu_level_new",
				"userloginstatus",
				"VERIFICATION_SEAL",
        "VERIFICATION_STATUS",
        "INTEREST_VIEWED_DATE",
				"SEEN",
                                "NATIVE_CITY",
                                "NATIVE_STATE",
                                "ANCESTRAL_ORIGIN",
                                "NAME_OF_USER",
        ),
			"ACCEPTANCES_SENT"=>Array(
				"PROFILECHECKSUM",
				"USERNAME",
				"OCCUPATION",
				"LOCATION",
				"AGE",
				"HEIGHT",
				"RELIGION",
				"CASTE",
				"MTONGUE",
				"INCOME",
				"subscription_icon",
				"subscription_text",
				"TIME",
				"MESSAGE",
				"edu_level_new",
				"userloginstatus",
				"VERIFICATION_SEAL",
                                "VERIFICATION_STATUS",
                                "NATIVE_CITY",
                                "NATIVE_STATE",
                                "ANCESTRAL_ORIGIN",
                                "NAME_OF_USER",
				),
	"INTEREST_EXPIRING"=>Array(
		"PROFILECHECKSUM",
	                    "USERNAME",
	                    "GENDER",
	                    "OCCUPATION",
	                    "LOCATION",
	                    "AGE",
	                    "HEIGHT",
	                    "RELIGION",
	                    "CASTE",
	                    "MTONGUE",
	                    "INCOME",
	                    "subscription_icon",
	                    "subscription_text",
	                    "TIME",
	                    "SEEN",
	                    "edu_level_new",
	                    "userloginstatus",
	                    "ProfilePic120Url",
	                    "ProfilePic450Url",
	                    "MSTATUS",
	                    "VERIFICATION_SEAL",
	                    "VERIFICATION_STATUS",
	                    "NATIVE_CITY",
	                    "NATIVE_STATE",
	                    "ANCESTRAL_ORIGIN",
	                    "NAME_OF_USER",
	                    ),
		"INTEREST_ARCHIVED"=>Array(
		"PROFILECHECKSUM",
	                    "USERNAME",
	                    "GENDER",
	                    "OCCUPATION",
	                    "LOCATION",
	                    "AGE",
	                    "HEIGHT",
	                    "RELIGION",
	                    "CASTE",
	                    "MTONGUE",
	                    "INCOME",
	                    "subscription_icon",
	                    "subscription_text",
	                    "TIME",
	                    "SEEN",
	                    "edu_level_new",
	                    "userloginstatus",
	                    "ProfilePic120Url",
	                    "ProfilePic450Url",
	                    "MSTATUS",
	                    "VERIFICATION_SEAL",
	                    "VERIFICATION_STATUS",
	                    "NATIVE_CITY",
	                    "NATIVE_STATE",
	                    "ANCESTRAL_ORIGIN",
	                    "NAME_OF_USER",
	                    ),
			"MATCH_ALERT"=>Array(
				"PROFILECHECKSUM",
				"USERNAME",
				"OCCUPATION",
				"LOCATION",
				"AGE",
				"HEIGHT",
				"RELIGION",
				"CASTE",
				"MTONGUE",
				"INCOME",
				"subscription_icon",
				"subscription_text",
				"TIME",
				"MESSAGE",
				"edu_level_new",
				"userloginstatus",
				"IS_BOOKMARKED",
				"VERIFICATION_SEAL",
                                "VERIFICATION_STATUS",
                                "NATIVE_CITY",
                                "NATIVE_STATE",
                                "ANCESTRAL_ORIGIN",
                                "NAME_OF_USER",
                                ),
				"VISITORS"=>Array(
				"PROFILECHECKSUM",
				"USERNAME",
				"OCCUPATION",
				"LOCATION",
				"AGE",
				"HEIGHT",
				"RELIGION",
				"CASTE",
				"MTONGUE",
				"INCOME",
				"subscription_icon",
				"subscription_text",
				"TIME",
				"edu_level_new",
				"userloginstatus",
				"VERIFICATION_SEAL",
                                "VERIFICATION_STATUS",
                                "NATIVE_CITY",
                                "NATIVE_STATE",
                                "ANCESTRAL_ORIGIN",
                                "NAME_OF_USER",
				),
				"SHORTLIST"=>Array(
				"PROFILECHECKSUM",
				"USERNAME",
				"OCCUPATION",
				"LOCATION",
				"AGE",
				"HEIGHT",
				"RELIGION",
				"CASTE",
				"MTONGUE",
				"INCOME",
				"subscription_icon",
				"subscription_text",
				"TIME",
				"edu_level_new",
				"userloginstatus",
				"VERIFICATION_SEAL",
                                "VERIFICATION_STATUS",
                                "NATIVE_CITY",
                                "NATIVE_STATE",
                                "ANCESTRAL_ORIGIN",
                                "NAME_OF_USER",
				),
				"NOT_INTERESTED"=>Array(
				"PROFILECHECKSUM",
				"USERNAME",
				"OCCUPATION",
				"LOCATION",
				"AGE",
				"HEIGHT",
				"RELIGION",
				"CASTE",
				"MTONGUE",
				"INCOME",
				"subscription_icon",
				"subscription_text",
				"TIME",
				"SEEN",
				"edu_level_new",
				"userloginstatus",
				"VERIFICATION_SEAL",
                                "VERIFICATION_STATUS",
                                "NATIVE_CITY",
                                "NATIVE_STATE",
                                "ANCESTRAL_ORIGIN",
                                "NAME_OF_USER",
				),
                                "NOT_INTERESTED_BY_ME"=>Array(
                                "PROFILECHECKSUM",
                                "USERNAME",
                                "OCCUPATION",
                                "LOCATION",
                                "AGE",
                                "HEIGHT",
                                "RELIGION",
                                "CASTE",
                                "MTONGUE",
                                "INCOME",
                                "subscription_icon",
								"subscription_text",
                                "TIME",
                                "edu_level_new",
                                "userloginstatus",
                                "VERIFICATION_SEAL",
                                "VERIFICATION_STATUS",
                                "NATIVE_CITY",
                                "NATIVE_STATE",
                                "ANCESTRAL_ORIGIN",
                                "NAME_OF_USER",
                                ),
				"PHOTO_REQUEST_RECEIVED"=>Array(
				"PROFILECHECKSUM",
				"USERNAME",
				"AGE",
				"HEIGHT",
				"RELIGION",
				"CASTE",
				"MTONGUE",
				"INCOME",
				"TIME",
				"ThumbailUrl",
				"tuple_title_field",
				"VERIFICATION_SEAL",
                                "VERIFICATION_STATUS",
                                "NAME_OF_USER",),
				"MY_MESSAGE" => Array(
				"PROFILECHECKSUM",
				"USERNAME",
				"LAST_MESSAGE",
				"COUNT",
				"TIME",
				"ThumbailUrl",
				"VERIFICATION_SEAL",
                                "VERIFICATION_STATUS",
                                "NATIVE_CITY",
                                "NATIVE_STATE",
                                "ANCESTRAL_ORIGIN",
                                "NAME_OF_USER",
				),
				
				"CONTACTS_VIEWED"=>Array(
                                "PROFILECHECKSUM",
                                "USERNAME",
                                "OCCUPATION",
                                "LOCATION",
                                "AGE",
                                "HEIGHT",
                                "RELIGION",
                                "CASTE",
                                "MTONGUE",
                                "INCOME",
                                "subscription_icon",
								"subscription_text",
                                "TIME",
                                "edu_level_new",
                                "userloginstatus",
                                "VERIFICATION_SEAL",
                                "VERIFICATION_STATUS",
                                "NATIVE_CITY",
                                "NATIVE_STATE",
                                "ANCESTRAL_ORIGIN",
                                "NAME_OF_USER",),
				"PEOPLE_WHO_VIEWED_MY_CONTACTS"=>Array(
                                "PROFILECHECKSUM",
                                "USERNAME",
                                "OCCUPATION",
                                "LOCATION",
                                "AGE",
                                "HEIGHT",
                                "SEEN",
                                "RELIGION",
                                "CASTE",
                                "MTONGUE",
                                "INCOME",
                                "subscription_icon",
								"subscription_text",
                                "TIME",
                                "edu_level_new",
                                "userloginstatus",
                                "VERIFICATION_SEAL",
                                "VERIFICATION_STATUS",
                                "NATIVE_CITY",
                                "NATIVE_STATE",
                                "ANCESTRAL_ORIGIN",
                                "NAME_OF_USER",),

				"FILTERED_INTEREST"=>Array(
				"PROFILECHECKSUM",
				"USERNAME",
				"OCCUPATION",
				"LOCATION",
				"AGE",
				"HEIGHT",
				"RELIGION",
				"CASTE",
				"MTONGUE",
				"INCOME",
				"subscription_icon",
				"subscription_text",
				"TIME",
				"MESSAGE",
				"SEEN",
				"edu_level_new",
				"userloginstatus",
				"VERIFICATION_SEAL",
                                "VERIFICATION_STATUS",
                                "NATIVE_CITY",
                                "NATIVE_STATE",
                                "ANCESTRAL_ORIGIN",
                                "NAME_OF_USER",
				),
				"IGNORED_PROFILES" => Array(
            	"PROFILECHECKSUM",
				"USERNAME",
				"GENDER",
				"OCCUPATION",
				"LOCATION",
				"AGE",
				"HEIGHT",
				"RELIGION",
				"CASTE",
				"MTONGUE",
				"INCOME",
				"subscription_icon",
				"subscription_text",
				"TIME",
				"MESSAGE",
				"SEEN",
				"edu_level_new",
				"userloginstatus",
				"VERIFICATION_SEAL",
                                "VERIFICATION_STATUS",
                                "NATIVE_CITY",
                                "NATIVE_STATE",
                                "ANCESTRAL_ORIGIN",
                                "NAME_OF_USER",),
                    "MATCH_OF_THE_DAY"=>Array(
				"PROFILECHECKSUM",
				"USERNAME",
				"tuple_title_field",
				"LOCATION",
				"AGE",
				"HEIGHT",
				"RELIGION",
				"CASTE",
				"MTONGUE",
				"INCOME",
				"subscription_icon",
				"subscription_text",
				"TIME",
				"MESSAGE",
				"SEEN",
				"edu_level_new",
				"userloginstatus",
				"FILTERED",
				"ProfilePic120Url",
				"ProfilePic450Url",
				"MSTATUS",
				"VERIFICATION_SEAL",
                                "VERIFICATION_STATUS",
                                "NATIVE_CITY",
                                "NATIVE_STATE",
                                "ANCESTRAL_ORIGIN",
                                "NAME_OF_USER",
				)
			);
		self::$informationTypeFields     = Array(
				"NEW_COUNT",
				"TOTAL_COUNT",
				"TITLE",
				"TUPLES",
				"SUBTITLE",
			);
		self::$tupleTitleField           = Array(
			"INTEREST_RECEIVED" => "OCCUPATION",
			"MATCH_ALERT" => "OCCUPATION"
		);
	}
	public function getJsonAppV1($displayObj,$profileId,$gender="",$subscription="")
	{	
		$infoKey = key($displayObj); 
		$finalResponse = Array();
		self::init();
		if (!empty($displayObj[$infoKey]["TUPLES"]))
		 {
			if($infoKey == "MATCH_ALERT")
			{
				$profiles = array_keys($displayObj[$infoKey]["TUPLES"]);
				$bookmarkObj  = new Bookmarks();
				$isBookmarked = $bookmarkObj->getProfilesBookmarks($profileId,$profiles);
				if(is_array($isBookmarked))
				foreach($isBookmarked as $key=>$value)
				{
					$displayObj[$infoKey]["TUPLES"][$value]->setIS_BOOKMARKED(1);
				}
			}
			$tracking = $displayObj[$infoKey]["TRACKING"];
			$tracking = explode("&",$tracking);
			foreach($tracking as $key=>$value)
			{
				$value = explode("=",$value);
				$track = $value[0];
				$$track = $value[1];
				$page[$value[0]] = $value[1];
				
			}
			$count =0;
			foreach($displayObj[$infoKey]["TUPLES"] as $key=>$value)
			{
				$tupleObj = $value;
                                
				foreach (self::$informationTupleFields[$infoKey] as $i => $field) {
					eval('$profile[$count][strtolower($field)] =$tupleObj->get' . $field . '();');
				}
//print_r($tupleObj);die;
				$profile[$count]["time"] = $tupleObj->getDecoratedTime();
                                $picSize=$tupleObj->getMOBPHOTOSIZE();
                        	$profile[$count]["size"]=$picSize;
                                $timeText = $tupleObj->getDecoratedTime();
                                $profile[$count]["time"] = ucfirst($timeText); 
                               if(stripos($timeText,'today') === false){
                                 $profile[$count]["timetext"] = $this->getDisplaylayerText($tupleObj->getGENDER(),$infoKey,$tupleObj->getCOUNT(),$tupleObj->getCONTACTS()["TYPE"])." on ".$timeText;
                               }else{
                                 $profile[$count]["timetext"] = $this->getDisplaylayerText($tupleObj->getGENDER(),$infoKey,$tupleObj->getCOUNT(),$tupleObj->getCONTACTS()["TYPE"])." ".$timeText;
                               }
				$buttonDetails = array();
                               	$profile[$count]["album_count"] = $tupleObj->getPHOTO_COUNT();
                                
        if($infoKey=="INTEREST_SENT")
				{
							$heshe = $tupleObj->getGENDER()=="F"?"She":"He";
							$viewedDate=$tupleObj->getINTEREST_VIEWED_DATE();
							if($viewedDate!='')
							{
								$profile[$count]["timetext"] = "$heshe viewed your interest ".((stripos($viewedDate,'today')=== false)?'on ':"").$viewedDate;
							}
							elseif($profile[$count]["seen"]!=null && $profile[$count]["seen"]=="Y")
							{
								$profile[$count]["timetext"] = "$heshe viewed your interest";
							
							}
							$profile[$count]["seen"]=null; // We are not required to show New so setting it to blank
							$profile[$count]["interest_viewed_date"] = null;
							
				}                  
				     
				if($infoKey!="MY_MESSAGE" && $infoKey!="PHOTO_REQUEST_RECEIVED")
				{
					 if($infoKey=="NOT_INTERESTED" || $infoKey=="NOT_INTERESTED_BY_ME")
					{
						$profile[$count]["photo"] = PictureFunctions::mapUrlToMessageInfoArr($tupleObj->getThumbailUrl(),'ThumbailUrl',$tupleObj->getIS_PHOTO_REQUESTED());
					}
					else
						$profile[$count]["photo"] = PictureFunctions::mapUrlToMessageInfoArr($tupleObj->getMobileAppPicUrl(),'MobileAppPicUrl',$tupleObj->getIS_PHOTO_REQUESTED(),$tupleObj->getGENDER());
					if($infoKey!="SHORTLIST" && $infoKey!="PEOPLE_WHO_VIEWED_MY_CONTACTS")
					{
						$buttons = explode("|",$tupleObj->getBUTTONS());
						$buttonObj = new ButtonResponse();
						$button = array();
						foreach($buttons as $key=>$value)
						{
							if($value == "SHORTLIST")
							{
								$button[] = $buttonObj->getShortListButton('','',$tupleObj->getIS_BOOKMARKED());
							}
							elseif($value == "UNBLOCK")
								$button[] = $buttonObj->getIgnoreButton('','',1);
							else if($value!="PHOTO")
								$button[] = $buttonObj->getCustomButtonByBName($value,$page);
							else
								$button[] = ButtonResponse::getAlbumButton($tupleObj->getPHOTO_COUNT(),$tupleObj->getGENDER());
						}
						$buttonDetails["buttons"] = $button;
						$profile[$count]["buttonDetails"] = ButtonResponse::buttonDetailsMerge($buttonDetails);

							}
				} 
				else if ($infoKey=="MY_MESSAGE")
				{

					$profile[$count]["thumbailurl"] = PictureFunctions::mapUrlToMessageInfoArr($tupleObj->getThumbailUrl(),'ThumbailUrl',$tupleObj->getIS_PHOTO_REQUESTED());
				}

				else if ($infoKey=="PHOTO_REQUEST_RECEIVED") {
					$profile[$count]["thumbailurl"]=PictureFunctions::mapUrlToMessageInfoArr($tupleObj->getThumbailUrl(),'ThumbailUrl',$tupleObj->getIS_PHOTO_REQUESTED())['url'];
					if (!$profile[$count]["thumbailurl"])
						$profile[$count]["thumbailurl"]=PictureFunctions::getNoPhotoJSMS($tupleObj->getGENDER());					
				}
                                //$profile[$count]["location"] .= $this->getlocationWithNativeCity($profile[$count]);
				if($infoKey=="SHORTLIST" || $infoKey == "NOT_INTERESTED" || $infoKey == "NOT_INTERESTED_BY_ME" || $infoKey == "PEOPLE_WHO_VIEWED_MY_CONTACTS" )
				{
					
					if(is_array($tupleObj->getCONTACTS()))
					{
						$buttonDetails = ButtonResponse::getContactsButton($tupleObj->getCONTACTS(),$tupleObj->getGENDER(),$page);
					}
					else
					{
						if ($infoKey=="PEOPLE_WHO_VIEWED_MY_CONTACTS") {
							$loggedInProfileObject=new Profile('',$profileId);
							$otherProfileObj= new Profile('',$tupleObj->getPROFILEID());
						}
						else {
							$loggedInProfileObject='';
							$otherProfileObj='';
						}
						$button[] = ButtonResponse::getInitiateButton($page);
						$button[] = ButtonResponse::getShortListButton($loggedInProfileObject,$otherProfileObj,1);
						$button[] = ButtonResponse::getContactDetailsButton();
						$buttonDetails["buttons"] = $button;
					}
					$profile[$count]["buttonDetails"] = ButtonResponse::buttonDetailsMerge($buttonDetails);
					
				}
                    
				$profile[$count]['edu_level_new']=$tupleObj->getedu_level_new();
			

					$count++;
				unset($button);
				
				
			}			
			$finalResponse["profiles"] = array_change_key_case($profile,CASE_LOWER);
			$finalResponse["title"] = $displayObj[$infoKey]["TITLE"];
			$finalResponse["subtitle"] = $displayObj[$infoKey]["SUBTITLE"];
			if($infoKey=="PHOTO_REQUEST_RECEIVED")
			{
				//if($tupleObj->getHAVEPHOTO() == "" || $tupleObj->getHAVEPHOTO()=="N"){
					$button["label"] = "UPLOAD YOUR PHOTO";
					$button["action"] = "PHOTO_UPLOAD";
					$button = ButtonResponse::buttonMerge($button);
					$finalResponse["button"] = $button;
				//}
			}
            
		 
			//print_r($tupleObj);
//die();
			
		}
		//print_r(self::$noresultArray);die;
		if(in_array($infoKey,self::$noresultArray) && $displayObj[$infoKey]["VIEW_ALL_COUNT"]==0)
		{
			$finalResponse["noresultmessage"] = constant('self::'.$infoKey);
			$finalResponse["paid"]=0;
			if($infoKey=="CONTACTS_VIEWED")
			{
				
				if(CommonFunction::isPaid($subscription)){
					$finalResponse["noresultmessage"] = constant('self::'.$infoKey.'_PAID');
					$finalResponse["paid"]=1;
				}
				if($gender=="M")
					$finalResponse["backgroundImgUrl"]=JsConstants::$imgUrl."/images/jsms/commonImg/img_female.jpg";
				else
					$finalResponse["backgroundImgUrl"]=JsConstants::$imgUrl."/images/jsms/commonImg/img_male.jpg";
			}
		}
		else
			$finalResponse["noresultmessage"] = null;
		if(!isset($finalResponse["profiles"]))
			$finalResponse["profiles"] = null;			 
		if(isset($displayObj[$infoKey]["VIEW_ALL_COUNT"]) && !in_array($infoKey, array(
			'NOT_INTERESTED',
			'NOT_INTERESTED_BY_ME',
			'ACCEPTANCES_RECEIVED',
			'ACCEPTANCES_SENT')))
		{
			$finalResponse["title"] = $displayObj[$infoKey]["TITLE"]." ".$displayObj[$infoKey]["VIEW_ALL_COUNT"]; 
			if($infoKey == "VISITORS" && MobileCommon::isApp() == "A")
			{
				$apiVersion = sfContext::getInstance()->getRequest()->getParameter('API_APP_VERSION');
				if($apiVersion>74)
				{
					$finalResponse["title"] = $displayObj[$infoKey]["TITLE"]; 
				}
			}
		} 
		else
			$finalResponse["title"] = $displayObj[$infoKey]["TITLE"];
		$finalResponse["infotype"] = $infoKey;		
		$finalResponse["currentPage"] = $displayObj[$infoKey]["CURRENT_NAV"];
		$finalResponse["newCount"] = $displayObj[$infoKey]["NEW_COUNT"]?$displayObj[$infoKey]["NEW_COUNT"]:'0';
		$finalResponse["nextPossible"] = $displayObj[$infoKey]["SHOW_NEXT"]?"true":"false";
		
		$finalResponse["contact_id"] = $displayObj[$infoKey]["contact_id"];
		$temp= $displayObj[$infoKey]['VIEW_ALL_COUNT']?$displayObj[$infoKey]['VIEW_ALL_COUNT']:"0";	
		$finalResponse["total"]="$temp";
		$finalResponse["tracking"] = $displayObj[$infoKey]["TRACKING"];	
		$finalResponse = array_change_key_case($finalResponse,CASE_LOWER);
    //Request Call Back Communication
    $arrAllowedRcbCommunication = array("ACCEPTANCES_RECEIVED","ACCEPTANCES_SENT");
    if (in_array($infoKey, $arrAllowedRcbCommunication)) {
      $loggedInProfileObject = LoggedInProfile::getInstance();
      $rcbObj = new RequestCallBack($loggedInProfileObject);
      $finalResponse['display_rcb_comm'] = ($rcbObj->getRCBStatus())?'true':'false';
	$finalResponse['display_rcb_comm_message']="To reach out to your accepted members, you may consider upgrading your membership. Would you like us to call you to explain the benefits of our membership plans?";
      unset($rcbObj);
    }
        return $finalResponse;
	}
	private function getDisplaylayerText($gender,$infokey,$count,$contactType='')
	{
		$hisher = $gender=="F"?"her":"his";
		$himher = $gender=="F"?"her":"him";
		$heshe = $gender=="F"?"She":"He";
		switch($infokey){
			case "INTEREST_RECEIVED":
			case "INTEREST_EXPIRING":
			case "INTEREST_ARCHIVED":
			case "FILTERED_INTEREST":
				if($count>1)
				{
					$text = $heshe." sent an interest reminder";
				}
				else
					$text = $heshe." sent an interest";
				break;
			case "ACCEPTANCES_RECEIVED":
				$text = "$heshe accepted your interest";
				break;
			case "INTEREST_SENT":
				if($count>1)
				{
					$text = "you sent $himher an interest reminder";
				}
				else
					$text = "You sent $himher an interest";
				break;
			case "ACCEPTANCES_SENT":
				$text = "You accepted $hisher interest";
				break;
			case "MATCH_ALERT":
				$text = "Sent to you";
				break;
			case "SHORTLIST":
				$text = "You shortlisted $himher";
				break;
			case "VISITORS":
				$text = $heshe." visited to your profile";
				break;
			case "NOT_INTERESTED":
				$contactText = $contactType=="D"?"Declined":"Cancelled";
				$text = "$heshe $contactText";
				break;
			case "NOT_INTERESTED_BY_ME":
				$contactText = $contactType=="D"?"Declined":"Cancelled";
				$text = "You $contactText";
				break;
			case "PEOPLE_WHO_VIEWED_MY_CONTACTS":
				$text = "$heshe viewed your contact details";
				break;
			case "IGNORED_PROFILES":
				$text = "Blocked";
				break;
			
			default:
				$text = "";
				break;
			}
			return $text;
		}
                
}
?>
