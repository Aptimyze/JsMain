<?php
/**
 * @brief This class is for myjs mobile App version 1 functions
 * @author Reshu Rajput
 * @created 2013-12-19
 */
class InboxMobileAppV2
{
	static public $informationTupleFields;
	static public $informationTypeFields;
	static public $myProfileIncompleteFields;
	static public $tupleTitleField;
	const IGNORED_PROFILES = "Members blocked by you will appear here";
	static public $noresultArray = Array("INTEREST_RECEIVED","INTEREST_EXPIRING","INTEREST_ARCHIVED","ACCEPTANCES_RECEIVED","ACCEPTANCES_SENT","INTEREST_SENT","VISITORS","SHORTLIST","MY_MESSAGE","MY_MESSAGE_RECEIVED","MATCH_ALERT","PHOTO_REQUEST_RECEIVED","PHOTO_REQUEST_SENT","HOROSCOPE_REQUEST_RECEIVED","HOROSCOPE_REQUEST_SENT","NOT_INTERESTED","NOT_INTERESTED_BY_ME","FILTERED_INTEREST","CONTACTS_VIEWED","PEOPLE_WHO_VIEWED_MY_CONTACTS","IGNORED_PROFILES","INTRO_CALLS","INTRO_CALLS_COMPLETE");
	const INTEREST_RECEIVED = "You have no interests left to respond to";
	const IOS_INTEREST_RECEIVED = "Interests received in the last <TIME> days but not responded to will appear here.";
	const INTEREST_EXPIRING = "Interests which will expire within the next 7 days will appear here.";
	const ACCEPTANCES_RECEIVED = "No one has yet accepted your interest";
	const ACCEPTANCES_SENT = "You haven't yet accepted any interests sent to you";
	const INTEREST_SENT = "You haven't sent any interests yet";
	const VISITORS = "No one has visited your profile recently";
	const VISITORS_PC = "People who visited your profile in the last week will appear here";
	const SHORTLIST = "Members you shortlist will appear here";
	const SHORTLIST_PC = "People you shortlist will appear here";
	const MY_MESSAGE = "You have not sent or received any messages yet";
	const MY_MESSAGE_RECEIVED = "You have not received any messages yet";
	//const MATCH_ALERT = "Profiles you would see here are Matches sent to you every day on your Email ID. We have not sent you any 'Match Alert' emails yet.";
	const MATCH_ALERT = "Daily Recommendations will appear here";
	const PHOTO_REQUEST_RECEIVED = "No Photo Request Received";
	const PHOTO_REQUEST_SENT = "No Photo Requests sent";
	const INTRO_CALLS = "Members to be called will appear here";
	const INTRO_CALLS_COMPLETE = "Members already called will appear here";
	const HOROSCOPE_REQUEST_RECEIVED = "No Horoscope Requests received";
	const HOROSCOPE_REQUEST_SENT = "No Horoscope Requests sent";
	const NOT_INTERESTED = "People who have declined the interest sent by you or have cancelled the interest sent to you will appear here";
	const NOT_INTERESTED_BY_ME = 'Interests you have declined/cancelled will appear here';
	const FILTERED_INTEREST ="People who have expressed interest in you but don't meet your filter criteria will appear here";
	const PEOPLE_WHO_VIEWED_MY_CONTACTS="People who viewed your contacts will appear here";
	const CONTACTS_VIEWED = "As a paid member you can :<br><br>See all phone numbers in one list <br>Call people instantly<br>No need to note down contact details <br>";
	const CONTACTS_VIEWED_PAID = "Contacts viewed by you would be shown here";
	const CONTACTS_VIEWED_UNPAID_V2 = "<span style='color:#666'> Upgrade membership to view contact details and connect to your match instantly.</span>";
	const CONTACTS_VIEWED_UNPAID_V2_IOS = "Upgrade membership to view contact details and connect to your match instantly.";
	// todo: need to be changed 
	const INTEREST_ARCHIVED = "Interests received more than 45 days earlier will appear here.";

	static public function init()
	{
		self::$informationTupleFields    = Array(
			"INTEREST_RECEIVED"=>Array(
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
			"ACCEPTANCES_RECEIVED"=>Array(
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
				"ProfilePic120Url",
				"ProfilePic235Url",
				"MSTATUS",
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
				"SENT_MESSAGE",
				"edu_level_new",
				"userloginstatus",
				"ProfilePic120Url",
				"MSTATUS",
				"VERIFICATION_SEAL",
				"INTEREST_VIEWED_DATE",
				"SEEN",
                                "VERIFICATION_STATUS",
                                "NATIVE_CITY",
                                "NATIVE_STATE",
                                "ANCESTRAL_ORIGIN",
                                "NAME_OF_USER",
				),
			"ACCEPTANCES_SENT"=>Array(
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
				"edu_level_new",
				"userloginstatus",
				"ProfilePic120Url",
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
				"edu_level_new",
				"userloginstatus",
				"ProfilePic120Url",
				"ProfilePic450Url",
				"IS_BOOKMARKED",
				"VERIFICATION_SEAL",
                                "VERIFICATION_STATUS",
                                "COMPANY_NAME",
                                "COLLEGE",
                                "PG_COLLEGE",
                                "NATIVE_CITY",
                                "NATIVE_STATE",
                                "ANCESTRAL_ORIGIN",
                                "NAME_OF_USER",
                                "MSTATUS",
                                ),
				"VISITORS"=>Array(
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
				"edu_level_new",
				"ProfilePic120Url",
				"ProfilePic235Url",
				"ProfilePic450Url",
				"userloginstatus",
				"VERIFICATION_SEAL",
                                "VERIFICATION_STATUS",
                                "COMPANY_NAME",
                                "COLLEGE",
                                "PG_COLLEGE",
                                "NATIVE_CITY",
                                "NATIVE_STATE",
                                "ANCESTRAL_ORIGIN",
                                "NAME_OF_USER",
                                "MSTATUS",
				),
				"SHORTLIST"=>Array(
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
				"edu_level_new",
				"ProfilePic120Url",
				"ProfilePic235Url",
				"ProfilePic450Url",
				"userloginstatus",
				"VERIFICATION_SEAL",
                                "VERIFICATION_STATUS",
                                "COMPANY_NAME",
                                "COLLEGE",
                                "PG_COLLEGE",
                                "NATIVE_CITY",
                                "NATIVE_STATE",
                                "ANCESTRAL_ORIGIN",
                                "NAME_OF_USER",
                                "MSTATUS",
				),
				"NOT_INTERESTED"=>Array(
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
				"MSTATUS",
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
                                "edu_level_new",
                                "userloginstatus",
                                "ProfilePic120Url",
				"MSTATUS",
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
                                "edu_level_new",
                                "userloginstatus",
                                "ProfilePic120Url",
								"MSTATUS",
								"VERIFICATION_SEAL",
                                                                "VERIFICATION_STATUS",
                                "NATIVE_CITY",
                                "NATIVE_STATE",
                                "ANCESTRAL_ORIGIN",
                                "NAME_OF_USER",
				),
				"PHOTO_REQUEST_SENT"=>Array(
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
                                "edu_level_new",
                                "userloginstatus",
                                "ProfilePic120Url",
								"MSTATUS",
								"VERIFICATION_SEAL",
                                                                "VERIFICATION_STATUS",
                                "NATIVE_CITY",
                                "NATIVE_STATE",
                                "ANCESTRAL_ORIGIN",
                                "NAME_OF_USER",
				),
				"HOROSCOPE_REQUEST_RECEIVED"=>Array(
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
                                "edu_level_new",
                                "userloginstatus",
                                "ProfilePic120Url",
								"MSTATUS",
								"VERIFICATION_SEAL",
                                                                "VERIFICATION_STATUS",
                                "NATIVE_CITY",
                                "NATIVE_STATE",
                                "ANCESTRAL_ORIGIN",
                                "NAME_OF_USER",
				),
				"HOROSCOPE_REQUEST_SENT"=>Array(
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
                                "edu_level_new",
                                "userloginstatus",
                                "ProfilePic120Url",
								"MSTATUS",
								"VERIFICATION_SEAL",
                                                                "VERIFICATION_STATUS",
                                "NATIVE_CITY",
                                "NATIVE_STATE",
                                "ANCESTRAL_ORIGIN",
                                "NAME_OF_USER",
				),
				"INTRO_CALLS"=>Array(
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
                                "edu_level_new",
                                "userloginstatus",
                                "ProfilePic120Url",
								"MSTATUS",
								"VERIFICATION_SEAL",
								"CALL_STATUS",
								"CALL_COMMENTS",
								"LAST_CALL_DATE",
                                                                "VERIFICATION_STATUS",
                                "NATIVE_CITY",
                                "NATIVE_STATE",
                                "ANCESTRAL_ORIGIN"),
				"INTRO_CALLS_COMPLETE"=>Array(
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
                                "edu_level_new",
                                "userloginstatus",
                                "ProfilePic120Url",
								"MSTATUS",
								"VERIFICATION_SEAL",
								"CALL_STATUS",
								"CALL_COMMENTS",
								"LAST_CALL_DATE",
                                                                "VERIFICATION_STATUS",
                                "NATIVE_CITY",
                                "NATIVE_STATE",
                                "ANCESTRAL_ORIGIN"),
				"CONTACTS_VIEWED"=>Array(
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
                                "edu_level_new",
                                "userloginstatus",
                                "ProfilePic120Url",
								"MSTATUS",
								"VERIFICATION_SEAL",
                                                                "VERIFICATION_STATUS",
                                "NATIVE_CITY",
                                "NATIVE_STATE",
                                "ANCESTRAL_ORIGIN",
                                "NAME_OF_USER",
				),
				"PEOPLE_WHO_VIEWED_MY_CONTACTS"=>Array(
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
				"MSTATUS",
				"VERIFICATION_SEAL",
                                "VERIFICATION_STATUS",
                                "NATIVE_CITY",
                                "NATIVE_STATE",
                                "ANCESTRAL_ORIGIN",
                                "NAME_OF_USER",
				),
	"FILTERED_INTEREST" => Array(
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
				"ProfilePic120Url",
                                "ProfilePic450Url",
				"userloginstatus",
				"MSTATUS",
				"VERIFICATION_SEAL",
                                "VERIFICATION_STATUS",
                                "NATIVE_CITY",
                                "NATIVE_STATE",
                                "ANCESTRAL_ORIGIN",
                                "NAME_OF_USER",
				),
	/*"MY_MESSAGE" => Array( //wrong, please use below
				"PROFILECHECKSUM",
				"USERNAME",
				"GENDER",
				"INCOME",
				"LAST_MESSAGE",
				"COUNT",
				"TIME",
				"ThumbailUrl",
				"MSTATUS"),*/
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
				"ProfilePic120Url",
				"MSTATUS",
				"VERIFICATION_SEAL",
                                "VERIFICATION_STATUS",
                                "NATIVE_CITY",
                                "NATIVE_STATE",
                                "ANCESTRAL_ORIGIN",
                                "NAME_OF_USER",
				),
	"MY_MESSAGE" => Array(
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
				"LAST_MESSAGE",
				"COUNT",
				"TIME",
				"ThumbailUrl",
				"ProfilePic120Url",
				"ProfilePic235Url",
				"MSTATUS",
				"VERIFICATION_SEAL",
                                "VERIFICATION_STATUS",
                                "NATIVE_CITY",
                                "NATIVE_STATE",
                                "ANCESTRAL_ORIGIN",
                                "NAME_OF_USER",
				),
	"MY_MESSAGE_RECEIVED" => Array(
					"PROFILECHECKSUM",
					"USERNAME",
					"GENDER",
					"LAST_MESSAGE",
					"COUNT",
					"TIME",
					"ThumbailUrl",
					"ProfilePic120Url",
					"ProfilePic235Url",
					"MSTATUS",
					"VERIFICATION_SEAL",
                                        "VERIFICATION_STATUS",
                                "NATIVE_CITY",
                                "NATIVE_STATE",
                                "ANCESTRAL_ORIGIN",
                                "NAME_OF_USER",
				),
			"INTEREST_RECEIVED_FILTER"=>Array(
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
				),
                    "MATCH_OF_THE_DAY"=>Array(
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
				"HEADING",
				"CCMESSAGE"
			);
		self::$tupleTitleField           = Array(
			"INTEREST_RECEIVED" => "OCCUPATION",
			"MATCH_ALERT" => "OCCUPATION"
		);
	}
	 
        
        
        public function getJsonAppV2($displayObj,$profileId,$profileObj)
	{//print_r($profileId);die;
		//echo "bb";die;
//print_r($displayObj); die;

		$infoKey = key($displayObj);
		//print_r($displayObj[$infoKey]["TUPLES"]);die;
		//print_r($infoKey);die;
		$finalResponse = Array();
		self::init();
		if (!empty($displayObj[$infoKey]["TUPLES"])) {
			$tracking=array();
			$finalResponse["tracking"] = $this->getTracking($infoKey);//$displayObj[$infoKey]["TRACKING"];	
			$tracking=explode("&",$finalResponse["tracking"]);
			
			/**
			 * Bookmark query commented as not specified in any listing in any channel by Reshu
			 */
			$arrShortlistAllowedInfoKey = array("MATCH_ALERT","ACCEPTANCES_RECEIVED","ACCEPTANCES_SENT","INTEREST_SENT","PEOPLE_WHO_VIEWED_MY_CONTACTS","CONTACTS_VIEWED","VISITORS");
			if((MobileCommon::isNewMobileSite() && in_array($infoKey, $arrShortlistAllowedInfoKey))|| ( MobileCommon::isDesktop() && $infoKey == 'VISITORS'))
			{
				foreach ($displayObj[$infoKey]["TUPLES"] as $key => $value) {
					$value->setIS_BOOKMARKED(0);
				}
				$profiles = array_keys($displayObj[$infoKey]["TUPLES"]);
				$bookmarkObj  = new Bookmarks();
				$isBookmarked = $bookmarkObj->getProfilesBookmarks($profileId,$profiles);
				if(is_array($isBookmarked))
				foreach($isBookmarked as $key=>$value)
				{
					$displayObj[$infoKey]["TUPLES"][$value]->setIS_BOOKMARKED(1);
				}
				
			}
			/**
			 * Bookmark comment end
			 */
			$count =0;
			foreach($displayObj[$infoKey]["TUPLES"] as $key=>$value)
			{
				$tupleObj = $value;
		

                               	if($tupleObj->getUSERNAME()) {
				foreach (self::$informationTupleFields[$infoKey] as $i => $field) {
					
					eval('$profile[$count][strtolower($field)] =$tupleObj->get' . $field . '();');
				}
                                $profile[$count]['last_message'] = addslashes(htmlspecialchars_decode($profile[$count]['last_message']));
                                
                               $profile[$count]["time"] = $tupleObj->getDecoratedTime();
                               $profile[$count]["size"]=$tupleObj->getMOBPHOTOSIZE();
                               $timeText = $tupleObj->getDecoratedTime();
                               $timeTextAppend = $timeText;
                               if(stripos($timeText,'today') === false){
                                 $timeTextAppend = 'on '.$timeTextAppend;
                               }
                               if($infoKey=="NOT_INTERESTED" || $infoKey=="NOT_INTERESTED_BY_ME"){
																	$profile[$count]["timetext"] = $timeText;

                               }else if($infoKey=="MATCH_ALERT"){
                                  $profile[$count]["timetext"] = $this->getDisplaylayerText($tupleObj->getGENDER(),$infoKey,$tupleObj->getCOUNT())." ".$timeTextAppend;
                                  
                               }else if($infoKey == "INTEREST_SENT"){
                                  $profile[$count]["timetext"] = "Sent ".$timeTextAppend;
                                  $profile[$count]["time"] = ucfirst ($timeText);
                                }elseif($infoKey == "VISITORS" || $infoKey == "SHORTLIST"){
                                        $profile[$count]["timetext"] = $this->getDisplaylayerText($tupleObj->getGENDER(),$infoKey,$tupleObj->getCOUNT())." ".$timeTextAppend;
                                }else{
                                  $profile[$count]["timetext"] = ucfirst ($timeText);
                                  $profile[$count]["time"] = ucfirst ($timeText);
                                }

                                  $profile[$count]["photo"] = PictureFunctions::mapUrlToMessageInfoArr($tupleObj->getMobileAppPicUrl(),'MobileAppPicUrl',$tupleObj->getIS_PHOTO_REQUESTED(),$tupleObj->getGENDER());
				
                                if(!$v[$vv]){
                                        $value = null;
                                }
                                if($profile[$count]["college"] != ""){
                                        $profile[$count]["college"] = $profile[$count]["college"];
                                }
                                if($profile[$count]["pg_college"] != ""){
                                        $profile[$count]["pg_college"] = $profile[$count]["pg_college"];
                                }
                                if($profile[$count]["company_name"] != ""){
                                        $profile[$count]["company_name"] = $profile[$count]["company_name"];
                                }

                                //echo'<pre>';print_r($infoKey);die;
				//$profile[$count]["location"] .= $this->getlocationWithNativeCity($profile[$count]);
				$profile[$count]['edu_level_new']=$tupleObj->getedu_level_new();
				// Interest viewed required only in case of interest sent
				if($infoKey=="INTEREST_SENT")
				{
							if($profile[$count]["interest_viewed_date"]!=null)
							{
								$eoiViewedText = "Interest viewed".((stripos($profile[$count]["interest_viewed_date"],'today')=== false)?' on ':" ").$profile[$count]["interest_viewed_date"];
								$profile[$count]["interest_viewed_date"] = $eoiViewedText;
								if(!MobileCommon::isDesktop())
									$profile[$count]["timetext"] = $profile[$count]["interest_viewed_date"];
							}
							elseif($profile[$count]["seen"]!=null && $profile[$count]["seen"]=="Y")
							{
								$eoiViewedText = "Interest viewed";
								$profile[$count]["interest_viewed_date"] = $eoiViewedText;
								if(!MobileCommon::isDesktop())
									$profile[$count]["timetext"] = $profile[$count]["interest_viewed_date"];
								
							}
							else
							{
								$profile[$count]["seen"]=null; // We are not required to show New so setting it to blank
								$profile[$count]["interest_viewed_date"] = null;
							}
				}
				/** to make consistent with search**/
				$profile[$count]['filter_reason'] = "";
				if($infoKey == "INTRO_CALLS" || $infoKey == "INTRO_CALLS_COMPLETE")
				{
					$profile[$count]["intro_call_details"] = $this->getDisplayCallDetails($tupleObj->getCALL_STATUS(),$tupleObj->getCALL_COMMENTS(),$tupleObj->getLAST_CALL_DATE());
				}
				if ($infoKey=="MY_MESSAGE" || $infoKey =="MY_MESSAGE_RECEIVED")
				{
					$profile[$count]["thumbailurl"] = PictureFunctions::mapUrlToMessageInfoArr($tupleObj->getThumbailUrl(),'ThumbailUrl',$tupleObj->getIS_PHOTO_REQUESTED());
				}
				if($infoKey == "SHORTLIST" || $infoKey == "SHORTLIST")
				{
					$tupleObj->setIS_BOOKMARKED(1);
				}
                                $profileObject=$tupleObj->getprofileObject();
        if(MobileCommon::isDesktop())
				{
					  if($infoKey=="INTEREST_RECEIVED" || $infoKey=="FILTERED_INTEREST")
							$profile[$count]['message'] =$this->getPersonalizedMessageOnly($profileObject,$profile[$count]['message']);
						elseif($infoKey=="INTEREST_SENT")
							$profile[$count]['message'] = $this->getPersonalizedMessageOnly(LoggedInProfile::getInstance('newjs_master'),$profile[$count]['sent_message']);
						else
							$profile[$count]['message'] = null;
							
				 }
				 
				foreach($tracking as $key=>$value)
				{
					$value = explode("=",$value);
					$track = $value[0];
					$$track = $value[1];
					$page[$value[0]] = $value[1];
				}
	            if(!MobileCommon::isDesktop())
					$buttonObj = new ButtonResponseJSMS(LoggedInProfile::getInstance('newjs_master'),$profileObject,$page);
				$ignoreButton = array();
                if($infoKey=="IGNORED_PROFILES" && !MobileCommon::isDesktop())
				{
					
					$ignoreButton["buttons"]["primary"][] = $buttonObj->getIgnoreButton("","",1,1);
					$ignoreButton["buttons"]["others"] = null;
					$profile[$count]["buttonDetailsJSMS"] = $buttonObj::buttonDetailsMerge($ignoreButton);
					$profile[$count]["seen"]="Y";
                                        if(stripos($profile[$count]["timetext"],'today') === false){
                                          $profile[$count]["timetext"] = "Blocked On ".$profile[$count]["timetext"];
                                        }else{
                                          $profile[$count]["timetext"] = "Blocked ".$profile[$count]["timetext"];
                                        }
					

				}
				else{
					if(MobileCommon::isDesktop() || MobileCommon::isNewMobileSite())
					{//print_r($tupleObj->CONTACTS["TYPE"]);die;
						if(MobileCommon::isDesktop())
							$source="P";
						else
							$source="M";
						//var_dump($infoKey);die;
						/*if($tupleObj->CONTACTS)
						{
							$type=$tupleObj->CONTACTS["TYPE"];
							$viewer=$tupleObj->CONTACTS["SELF"];
							if($type=="I")
								$countInitiate=$tupleObj->CONTACTS["COUNT"];
						}*/
						
						//print_r($count);die;
						$page['PHOTO'] = PictureFunctions::mapUrlToMessageInfoArr($tupleObj->getThumbailUrl(),'ThumbailUrl',$tupleObj->getIS_PHOTO_REQUESTED(),$tupleObj->getGENDER())['url'];
						$page['CC_LISTING'] = $infoKey;
						$page['isBookmarked'] = $tupleObj->getIS_BOOKMARKED();
						$page["tracking"] = $this->getTracking($infoKey);
						
						if($infoKey == "INTEREST_SENT")
							$page["count"] = $tupleObj->COUNT;
						if($infoKey == "NOT_INTERESTED_BY_ME" || $infoKey == "PEOPLE_WHO_VIEWED_MY_CONTACTS" || $infoKey=="CONTACTS_VIEWED" || $infoKey=="NOT_INTERESTED")
						{
							$type = $tupleObj->CONTACTS['TYPE'];
							$page["count"] = $tupleObj->CONTACTS['COUNT'];
						}
						else
							$type = "";
						if($infoKey == "PEOPLE_WHO_VIEWED_MY_CONTACTS" || $infoKey=="CONTACTS_VIEWED")
						{
							$viewer = $tupleObj->CONTACTS['SELF'];
						}
						else
							$viewer = "";
						if($infoKey=="IGNORED_PROFILES")
							$page["isIgnored"] = 1;
						else
							$page['isIgnored'] = $tupleObj->getIS_IGNORED();
						if($infoKey == "INTRO_CALLS_COMPLETE")
						{
							if($profile[$count]["intro_call_details"]["CC_CALL_COMMENTS"] != null)
								$type = "C";
							else
								$type = "N";
						}
						if($infoKey == "INTRO_CALLS")
						{
							if($profile[$count]["intro_call_details"]["CC_REMOVEFROMICLINK"] == true)
								$type = "C";
							else
								$type = "";
						}
						//print_r($infokey)
						if($infoKey == "SHORTLIST" || $infoKey == "PEOPLE_WHO_VIEWED_MY_CONTACTS" || $infoKey=="CONTACTS_VIEWED")
						{//print_r($tupleObj);die;
							$type=$tupleObj->CONTACTS["TYPE"];
							$viewer=$tupleObj->CONTACTS["SELF"];
							if($type=="I")
								$countInitiate=$tupleObj->CONTACTS["COUNT"];
						}
						//print_r($infoKey);die;
						if(sfContext::getInstance()->getRequest()->getParameter("myjs") && ($infoKey == "MY_MESSAGE_RECEIVED"||$infoKey=="ACCEPTANCES_RECEIVED"))
						{
							$buttonObj = new ButtonResponseJSMS(LoggedInProfile::getInstance('newjs_master'),$profileObject,$page);
	                		$profile[$count]["buttonDetailsJSMS"] = $buttonObj->getButtonArray(array('PHOTO'=> PictureFunctions::mapUrlToMessageInfoArr($tupleObj->getThumbailUrl(),'ThumbailUrl',$tupleObj->getIS_PHOTO_REQUESTED(),$tupleObj->getGENDER())['url'],'CC_LISTING'=>$infoKey,'BOOKMARKED'=>$tupleObj->getIS_BOOKMARKED(),'IGNORED'=>$tupleObj->getIS_IGNORED()));	
						}
						else{
							$profile[$count]["buttonDetailsJSMS"] = ButtonResponseFinal::getListingButtons($infoKey, $source, $viewer,$type, $page,$countInitiate);
						}
					}
					else
	                {    
	                	$buttonObj = new ButtonResponse(LoggedInProfile::getInstance('newjs_master'),$profileObject,$page);
	                	$profile[$count]["buttonDetailsJSMS"] = $buttonObj->getButtonArray(array('PHOTO'=> PictureFunctions::mapUrlToMessageInfoArr($tupleObj->getThumbailUrl(),'ThumbailUrl',$tupleObj->getIS_PHOTO_REQUESTED(),$tupleObj->getGENDER())['url'],'CC_LISTING'=>$infoKey,'BOOKMARKED'=>$tupleObj->getIS_BOOKMARKED(),'IGNORED'=>$tupleObj->getIS_IGNORED()));
	                }
                }

                
						if(MobileCommon::isNewMobileSite())
						{
							$restResponseArray= ButtonResponseFinal::jsmsRestButtonsrray(array('PHOTO'=> PictureFunctions::mapUrlToMessageInfoArr($tupleObj->getThumbailUrl(),'ThumbailUrl',$tupleObj->getIS_PHOTO_REQUESTED(),$tupleObj->getGENDER())['url'],'CC_LISTING'=>$infoKey,'BOOKMARKED'=>$tupleObj->getIS_BOOKMARKED(),'IGNORED'=>$tupleObj->getIS_IGNORED()),$type,$infoKey, $source, $viewer,$tupleObj->USERNAME,$countInitiate);
               
			                $profile[$count]["buttonDetailsJSMS"]["photo"]=$restResponseArray["photo"];
			                $profile[$count]["buttonDetailsJSMS"]["topmsg"]=$restResponseArray["topmsg"];
						}                //end


                $profile[$count]["profileObject"]=$profileObject;
                $profile[$count]["album_count"] = $tupleObj->getPHOTO_COUNT();
                
                
							

				//print_r($profile);die;
				unset($button);
			
				$count++;
                	}                
				
			}
		
			$finalResponse["profiles"] = array_change_key_case($profile,CASE_LOWER);
			$finalResponse["title"] = $displayObj[$infoKey]["TITLE"];
			$finalResponse["subtitle"] = $displayObj[$infoKey]["SUBTITLE"];
            
		}
		if(in_array($infoKey,self::$noresultArray) && $displayObj[$infoKey]["VIEW_ALL_COUNT"]==0)
		{
			$finalResponse["noresultmessage"] = constant('self::'.$infoKey);
			
			if($infoKey=="INTEREST_RECEIVED" && MobileCommon::isApp()=="I")
			{
				
				$finalResponse["noresultmessage"]= str_replace('<TIME>',Contacts::INTEREST_RECEIVED_UPPER_LIMIT,self::IOS_INTEREST_RECEIVED);
			}
			if($infoKey=="CONTACTS_VIEWED")
			{
				if(MobileCommon::isDesktop()||MobileCommon::isApp()=="I")
				{
					if($profileObj->getSUBSCRIPTION() && strstr($profileObj->getSUBSCRIPTION(),'F'))
					{
						$finalResponse["noresultmessage"] =  self::CONTACTS_VIEWED_PAID;
						$finalResponse["paid"] = 'Y';
					} 
					else
					{
						if(MobileCommon::isDesktop())
							$finalResponse["noresultmessage"] =  self::CONTACTS_VIEWED_UNPAID_V2;
						else
							$finalResponse["noresultmessage"] =  self::CONTACTS_VIEWED_UNPAID_V2_IOS;
						$finalResponse["paid"] = 'N';
					}
				}
				if($gender=="M")
					$finalResponse["backgroundImgUrl"]=JsConstants::$imgUrl."/images/jsms/commonImg/brides.jpg";
				else
					$finalResponse["backgroundImgUrl"]=JsConstants::$imgUrl."/images/jsms/commonImg/grooms.jpg";
			}
			else if(MobileCommon::isDesktop() && $infoKey=="SHORTLIST")
			{
				$finalResponse["noresultmessage"] =  self::SHORTLIST_PC;
			}
			else if(MobileCommon::isDesktop() && $infoKey=="VISITORS")
			{
				$finalResponse["noresultmessage"] =  self::VISITORS_PC;
			}
		}
		else
			$finalResponse["noresultmessage"] = null;
		if(!isset($finalResponse["profiles"]))
			$finalResponse["profiles"] = null;
		if($displayObj[$infoKey]["VIEW_ALL_COUNT"])
			$finalResponse["title"] = $displayObj[$infoKey]["TITLE"]." ".$displayObj[$infoKey]["VIEW_ALL_COUNT"];
		else
			$finalResponse["title"] = $displayObj[$infoKey]["TITLE"];
		$finalResponse["infotype"] = $infoKey;
		$finalResponse["heading"] = $displayObj[$infoKey]["HEADING"];
		$finalResponse["ccmessage"] = $displayObj[$infoKey]["CCMESSAGE"];
                
			$finalResponse["currentPage"] = $displayObj[$infoKey]["CURRENT_NAV"];
			$finalResponse["newCount"] = $displayObj[$infoKey]["NEW_COUNT"];
			$finalResponse["nextPossible"] = $displayObj[$infoKey]["SHOW_NEXT"]?"true":"false";
		
                
		$finalResponse["contact_id"] = $displayObj[$infoKey]["contact_id"];
		$finalResponse["self_profileid"] = $displayObj[$infoKey]["self_profileid"];
		$inboxParams = InboxEnums::getInboxParams($displayObj[$infoKey]["ID"]);
		$finalResponse["page"] = $inboxParams["page"];
		$viewProfileParamsArray = InboxEnums::getViewProfilePageParams($displayObj[$infoKey]["ID"]);
		$finalResponse["contact"] = $viewProfileParamsArray["contact"];
		$finalResponse["ccself"] = $viewProfileParamsArray["self"];
		$finalResponse["cctype"] = $viewProfileParamsArray["type"];
		$finalResponse["flag"] = $viewProfileParamsArray["flag"];
		$finalResponse["navigation_type"] = $viewProfileParamsArray["navigation_type"];

		//set navigator
		/*$searchid = $displayObj[$infoKey]["ID"];
		$navObj = new NAVIGATOR();
		$finalResponse["ccnavigator"] = $navObj->navigation($finalResponse["navigation_type"],"infoTypeId__$searchid@pageNo__$requestedPage",'','Symfony');
		*/
		$finalResponse["fromPage"] = InboxEnums::$fromPage;
		$finalResponse["total"] = $displayObj[$infoKey]["VIEW_ALL_COUNT"];
		if($infoKey == "INTEREST_RECEIVED_FILTER")
			$finalResponse['filterCount'] = $displayObj[$infoKey]["filterCount"];	
		$finalResponse = array_change_key_case($finalResponse,CASE_LOWER);
		//print_r($finalResponse);die;

		/** to make consistent with search**/
		$currentPage = $displayObj[$infoKey]["CURRENT_NAV"];
                if(!$currentPage)
                        $currentPage = 1;
                $finalResponse['paginationArray'] = CommonUtility::pagination($currentPage,$displayObj[$infoKey]["VIEW_ALL_COUNT"],'','ccPC');
		$finalResponse['listType'] = 'cc';
		$finalResponse['showSortingOption'] = 'N';
		//print_r($finalResponse);
		
    //Request Call Back Communication
    $arrAllowedRcbCommunication = array("ACCEPTANCES_RECEIVED","ACCEPTANCES_SENT");
    if (in_array($infoKey, $arrAllowedRcbCommunication)) {
      $loggedInProfileObject = LoggedInProfile::getInstance();
      $rcbObj = new RequestCallBack($loggedInProfileObject);
      $finalResponse['display_rcb_comm'] = $rcbObj->getRCBStatus();
      unset($rcbObj);
    }
        //var_dump($finalResponse["profiles"]["14"]["buttonDetailsJSMS"]);die;
      //  print_r($finalResponse["profiles"]);die;
     // die;
		return $finalResponse;
	}      
        private function getDisplaylayerText($gender,$infokey,$count,$contactType="")
	{
		$hisher = $gender=="F"?"her":"his";
		$himher = $gender=="F"?"her":"him";
		$heshe = $gender=="F"?"She":"He";
		$contactText = "";
		switch($infokey){
			case "FILTERED_INTEREST":
			case "INTEREST_RECEIVED":
				if($count>1)
				{
					$text = "Reminder received";
				}
				else
					$text = "Interest received";
				break;
			case "ACCEPTANCES_RECEIVED":
				$text = "Accepted";
				break;
			case "INTEREST_SENT":
				if($count>1)
				{
					$text = "Reminder sent";
				}
				else
					$text = "Interest sent";
				break;
			case "ACCEPTANCES_SENT":
				$text = "Accepted";
				break;
			case "MATCH_ALERT":
				$text = "Sent to you";
				break;
			case "SHORTLIST":
				$text = "Shortlisted";
				break;
			case "VISITORS":
				$text = "Visited";
				break;
			case "PHOTO_REQUEST_RECEIVED":
				$text = "Requested";
				break;
			case "PHOTO_REQUEST_SENT":
				$text = "Request sent";
				break;
			case "HOROSCOPE_REQUEST_RECEIVED":
				$text = "Requested";
				break;
			case "HOROSCOPE_REQUEST_SENT":
				$text = "Request sent";
				break;
			case "INTRO_CALLS":
				$text = "Intro call requested";
				break;
			case "INTRO_CALLS_COMPLETE":
				$text = "Intro call requested";
				break;
			case "NOT_INTERESTED":
				$contactText = $contactType=="D"?"Declined":"Cancelled";
				$text = "$heshe $contactText";
				break;
			case "NOT_INTERESTED_BY_ME":
				$contactText = $contactType=="D"?"Declined":"Cancelled";
				$text = "You $contactText";
				break;
			default:
				$text = "";
				break;
			}
			return $text;
		}
                
         
           
	private function getTracking($infoType){
		if($rtype = sfContext::getInstance()->getRequest()->getParameter("retainResponseType"))
		{
		return "responseTracking=".$rtype;
		}
		if(sfContext::getInstance()->getRequest()->getParameter("myjs"))
		{
			$trackingMap=array(
                                "INTEREST_RECEIVED_FILTER"=>"responseTracking=".JSTrackingPageType::MYJS_AWAITING,
                                "VISITORS"=>"stype=".SearchTypesEnums::MYJS_VISITOR_PC,
                                "MATCH_ALERT"=>"stype=".SearchTypesEnums::MyJsMatchAlertSection,
                                "SHORTLIST"=>"stype=".SearchTypesEnums::MYJS_SHORTLIST_PC."&responseTracking=".JSTrackingPageType::MYJS_SHORTLIST_PC,
                                "PHOTO_REQUEST_RECEIVED"=>"stype=".SearchTypesEnums::MYJS_PHOTOREQUEST_PC,
                                "FILTERED_INTEREST"=>"responseTracking=".JSTrackingPageType::FILTERED_INTEREST_MYJS_JSPC,
                                "INTEREST_EXPIRING"=>"responseTracking=".JSTrackingPageType::INTEREST_EXPIRING_JSPC_MYJS,
                                //"PHOTO_REQUEST_SENT"=>"stype=".SearchTypesEnums::MYJS_PHOTOREQUEST_PC,
                                //"HOROSCOPE_REQUEST_RECEIVED"=>"stype=".SearchTypesEnums::MYJS_HOROSCOPEREQUEST_PC,
                                //"HOROSCOPE_REQUEST_SENT"=>"stype=".SearchTypesEnums::MYJS_HOROSCOPEREQUEST_PC,
                                
                               );
		}
		else if(sfContext::getInstance()->getRequest()->getParameter("ContactCenterDesktop")==1)
		{
                        if(sfContext::getInstance()->getRequest()->getParameter("matchedOrAll")!="A")
                            $visitorsStype = SearchTypesEnums::MATCHING_VISITORS_JSPC;
                        else
                            $visitorsStype = SearchTypesEnums::VISITORS_JSPC;
			$trackingMap=array("INTEREST_RECEIVED"=>"responseTracking=".JSTrackingPageType::CONTACT_AWAITING,
				"INTEREST_ARCHIVED"=>"responseTracking=".JSTrackingPageType::INTEREST_ARCHIVED,
				           "INTEREST_EXPIRING"=>"responseTracking=".JSTrackingPageType::INTEREST_EXPIRING,
					   "VISITORS"=>"stype=".$visitorsStype."&responseTracking=".JSTrackingPageType::CONTACT_OTHER,
				"SHORTLIST"=>"stype=".SearchTypesEnums::SHORTLIST_JSPC."&responseTracking=".JSTrackingPageType::CONTACT_OTHER,
				"PHOTO_REQUEST_RECEIVED"=>"stype=".SearchTypesEnums::PHOTO_REQUEST_RECEIVED_CC_PC."&responseTracking=".JSTrackingPageType::CONTACT_OTHER,
				"PHOTO_REQUEST_SENT"=>"stype=".SearchTypesEnums::PHOTO_REQUEST_SENT_CC_PC."&responseTracking=".JSTrackingPageType::CONTACT_OTHER,
				"HOROSCOPE_REQUEST_RECEIVED"=>"stype=".SearchTypesEnums::HOROSCOPE_REQUEST_RECEIVED_CC_PC."&responseTracking=".JSTrackingPageType::CONTACT_OTHER,
				"HOROSCOPE_REQUEST_SENT"=>"stype=".SearchTypesEnums::HOROSCOPE_REQUEST_SENT_CC_PC."&responseTracking=".JSTrackingPageType::CONTACT_OTHER,
				"NOT_INTERESTED_BY_ME"=>"stype=".SearchTypesEnums::CANCELLED_LISTING_PC."&responseTracking=".JSTrackingPageType::CANCELLED_LISTING_PC,
				"FILTERED_INTEREST"=>"responseTracking=".JSTrackingPageType::CONTACT_OTHER,
				"INTRO_CALLS"=>"responseTracking=".JSTrackingPageType::CONTACT_OTHER,
                "INTRO_CALLS_COMPLETE"=>"responseTracking=".JSTrackingPageType::CONTACT_OTHER,
                "CONTACTS_VIEWED"=>"stype=".SearchTypesEnums::PHONEBOOK_CC_PC."&responseTracking=".JSTrackingPageType::CONTACT_OTHER,
                "PEOPLE_WHO_VIEWED_MY_CONTACTS"=>"stype=".SearchTypesEnums::CONTACTS_VIEWED_BY_CC_PC."&responseTracking=".JSTrackingPageType::CONTACT_OTHER
                 );
		}
		elseif(MobileCommon::isApp()=='I'){
                    if(sfContext::getInstance()->getRequest()->getParameter("matchedOrAll")=="M")
                        $visitorsStype = SearchTypesEnums::MATCHING_VISITORS_IOS;
                    else
                        $visitorsStype = SearchTypesEnums::VISITORS_IOS;
                        $trackingMap=array(
                                "INTEREST_RECEIVED"=>"responseTracking=".JSTrackingPageType::MOBILE_AWAITING_IOS,
                                "VISITORS"=>"stype=".$visitorsStype,
                                "MATCH_ALERT"=>"stype=".SearchTypesEnums::iOSMatchAlertsCC,
                                "SHORTLIST"=>"stype=".SearchTypesEnums::SHORTLIST_IOS."&responseTracking=".JSTrackingPageType::SHORTLIST_IOS,
                                "PHOTO_REQUEST_RECEIVED"=>"stype=".SearchTypesEnums::PHOTO_REQUEST_RECEIVED_IOS,
                                "CONTACTS_VIEWED"=>"stype=".SearchTypesEnums::PHONEBOOK_IOS,"responseTracking=".JSTrackingPageType::PHONEBOOK_IOS,
                                "FILTERED_INTEREST"=>"responseTracking=".JSTrackingPageType::FILTERED_INTEREST_IOS,
                                "PEOPLE_WHO_VIEWED_MY_CONTACTS"=>"stype=".SearchTypesEnums::CONTACT_VIEWERS_IOS,"responseTracking=".JSTrackingPageType::CONTACT_VIEWERS_IOS,
                                "INTEREST_EXPIRING"=>"responseTracking=".JSTrackingPageType::INTEREST_EXPIRING_IOS,
                                
                                "NOT_INTERESTED_BY_ME"=>"stype=".SearchTypesEnums::CANCELLED_LISTING_IOS."&responseTracking=".JSTrackingPageType::CANCELLED_LISTING_IOS,
                                 "INTEREST_ARCHIVED"=>"responseTracking=".JSTrackingPageType::INTEREST_ARCHIVED_IOS
					);
                }
		else{
                    if(sfContext::getInstance()->getRequest()->getParameter("matchedOrAll")!="A")
                        $visitorsStype = SearchTypesEnums::MATCHING_VISITORS_JSMS;
                    else
                        $visitorsStype = SearchTypesEnums::VISITORS_JSMS;
			$trackingMap=array(
				"INTEREST_RECEIVED"=>"responseTracking=".JSTrackingPageType::MOBILE_AWAITING,
				"INTEREST_EXPIRING"=>"responseTracking=".JSTrackingPageType::INTEREST_EXPIRING_JSMS,
				"VISITORS"=>"stype=".$visitorsStype,
				"MATCH_ALERT"=>"stype=".SearchTypesEnums::WapMatchAlertsCC,
				"SHORTLIST"=>"stype=".SearchTypesEnums::SHORTLIST_JSMS."&responseTracking=".JSTrackingPageType::SHORTLIST_JSMS,
				"PHOTO_REQUEST_RECEIVED"=>"stype=".SearchTypesEnums::PHOTO_REQUEST_RECEIVED_JSMS,
				"CONTACTS_VIEWED"=>"stype=".SearchTypesEnums::PHONEBOOK_JSMS."&responseTracking=".JSTrackingPageType::PHONEBOOK_JSMS,
				"FILTERED_INTEREST"=>"responseTracking=".JSTrackingPageType::FILTERED_INTEREST_JSMS,
				"PEOPLE_WHO_VIEWED_MY_CONTACTS"=>"stype=".SearchTypesEnums::CONTACT_VIEWERS_JSMS."&responseTracking=".JSTrackingPageType::CONTACT_VIEWERS_JSMS,
                                "NOT_INTERESTED_BY_ME"=>"stype=".SearchTypesEnums::CANCELLED_LISTING_MS."&responseTracking=".JSTrackingPageType::CANCELLED_LISTING_MS,
				
				"INTEREST_ARCHIVED"=>"responseTracking=".JSTrackingPageType::INTEREST_ARCHIVED_JSMS,
				);
                }
		return $trackingMap[$infoType]?$trackingMap[$infoType]:false;
	}

	/*get display intro call details for AP member
	* @param : $CALL_STATUS,$CALL_COMMENTS,$LAST_CALL_DATE
	* @return : array of display details
	*/         
    private function getDisplayCallDetails($CALL_STATUS,$CALL_COMMENTS,$LAST_CALL_DATE)
    {
		if($CALL_STATUS)
		{
			switch($CALL_STATUS)
			{
				case "Y":
						$status = "Communication done";
						if($CALL_COMMENTS)
						{	
							$message = "Profile called on ".CommonUtility::convertDateTimeToDisplayDate($LAST_CALL_DATE);
						}
						else
						{
							$message = "No comments yet";
						}
						$removeFromICLink = false;
						break;
				case "N":
						if($CALL_COMMENTS)
						{	
							$status = "Communication in progress";
							$message = "Profile called on ".CommonUtility::convertDateTimeToDisplayDate($LAST_CALL_DATE);
							$removeFromICLink = false;
						}
						else
						{
							$status = "Communication not started";
							$message = "Profile yet to be called";
							$removeFromICLink = true;
						}
						$break;
			}
		}
		else
		{
			$status="";
			$message="";
			$comments="";
			$removeFromICLink = false;
		}
		$output = array("CC_CALL_STATUS"=>$status,"CC_CALL_COMMENTS"=>$CALL_COMMENTS,"CC_CALL_MESSAGE"=>$message,"CC_REMOVEFROMICLINK"=>$removeFromICLink);
		return $output;
    } 
    
    /* This function is used to check if message is personalized or not*/
    private function getPersonalizedMessageOnly($profileObj,$message)
    {
			
			$presetMessage[] = str_ireplace("{{USERNAME}}",$profileObj->getUSERNAME(),Messages::EOI_PRESET_PAID_SELF);
			$presetMessage[] = str_ireplace("{{USERNAME}}",$profileObj->getUSERNAME(),Messages::EOI_PRESET_FREE);
			
			$messageCmp = trim(html_entity_decode($message,ENT_QUOTES));
			if(!in_array($messageCmp,$presetMessage))
			{
				if(strpos($message,"||")!==false || strpos($message,"--")!==false)
				{
					$messageArr=explode("||",$message);
					$eoiMsgCount = count($messageArr);
					$i=0;
					
					for($j=0;$j<$eoiMsgCount;$j++)
					{
						$splitmessage = explode("--",$messageArr[$j]);
						if($i==0)
							$eoiMessages=$splitmessage[0];
						else
							$eoiMessages.="\n".$splitmessage[0];
						$i++;							
					}
					if($eoiMessages)
						$message=$eoiMessages;
					else
						$message="";
				}
				$message= nl2br($message);
				$message =addslashes(htmlspecialchars_decode($message));
			}
			else
				$message = null;
		
			return $message;
		}
}
?>
