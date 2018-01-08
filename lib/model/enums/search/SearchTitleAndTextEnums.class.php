<?php
/**
* This class contains the search response related enums
* @author : Reshu 
* @package Search
* @subpackage Search
* @copyright 2015 Reshu Rajput
* @link http://devjs.infoedge.com/mediawiki/index.php/SEARCH
* @since 2015-05-26
*/
class SearchTitleAndTextEnums
{
        
        static private $TITLE_MAPPING = array();
        static private $SEARCH_CRITERIA_TEXT_MAPPING = array();
        static private $HEADING_MAPPING = array();	
        static private $SUBHEADING_MAPPING = array();
        static private $HEADING_0RESULT_MAPPING = array();
        static public $MESSAGE_0RESULT_MAPPING = array();
        static private $DEFAULT_PIC_SIZE = array();
        static private $CUSTOM_SUBHEADING_MAPPING = array();
        static private $GA_TRACKING_MAPPING = array();
        const JUST_JOIN_CUSTOM_MESSAGE_COUNT = 5;
        const DPP_CUSTOM_MESSAGE_COUNT = 50;
       /*
	* This function initiaize different title and text arrays.
	* @access private
	* @access static
	*/
 
	static private function initTitleAndText()
        {
                self::$TITLE_MAPPING["V1"]["PC"]["DEFAULT"] = "";
                self::$TITLE_MAPPING["V1"]["PC"]["reverseDpp"] = "Members looking for me - Jeevansathi.com";
                self::$TITLE_MAPPING["V1"]["PC"]["partnermatches"] = "Desired Partner Matches - Jeevansathi.com";
                self::$TITLE_MAPPING["V1"]["PC"]["twowaymatch"] = "Mutual Matches - Jeevansathi.com";
                self::$TITLE_MAPPING["V1"]["PC"]["justJoinedMatches"] = "Just Joined Matches - Jeevansathi.com";
                self::$TITLE_MAPPING["V1"]["PC"]["kundlialerts"] = "Kundli Matches - Jeevansathi.com";
                //self::$TITLE_MAPPING["V1"]["PC"]["matchalerts"] = "Daily Recommendations - Jeevansathi.com";
                self::$TITLE_MAPPING["V1"]["PC"]["matchalerts"] = "Search Matrimonial Profile - Find Matrimony Profile - Jeevansathi.com";
                self::$TITLE_MAPPING["V1"]["PC"]["verifiedMatches"] = "Verified Matches - Jeevansathi.com";
                self::$TITLE_MAPPING["V1"]["PC"]["contactViewAttempts"] = "Contacts view attempts- Jeevansathi.com";
                
                self::$SEARCH_CRITERIA_TEXT_MAPPING["V1"]["PC"]["DEFAULT"] = "You searched for";
                self::$SEARCH_CRITERIA_TEXT_MAPPING["V1"]["PC"]["partnermatches"] = "Your Desired Partner Profile";
                self::$SEARCH_CRITERIA_TEXT_MAPPING["V1"]["PC"]["twowaymatch"] = "Your Desired Partner Profile";
                self::$SEARCH_CRITERIA_TEXT_MAPPING["V1"]["PC"]["justJoinedMatches"] = "Your Desired Partner Profile";
                self::$SEARCH_CRITERIA_TEXT_MAPPING["V1"]["PC"]["verifiedMatches"] = "Your Desired Partner Profile";
                self::$SEARCH_CRITERIA_TEXT_MAPPING["V1"]["PC"]["contactViewAttempts"] = "People who contacted your profile";
                
                self::$HEADING_MAPPING["V1"]["PC"]["DEFAULT"] = "<formatNumber_format> Match<es>";
				self::$HEADING_MAPPING["V1"]["PC"]["kundlialerts"] = "Kundli Matches";
		/*
                self::$HEADING_MAPPING["V1"]["PC"]["reverseDpp"] = "<formatNumber_format>  Matches";
                self::$HEADING_MAPPING["V1"]["PC"]["partnermatches"] = "<formatNumber_format> Desired Partner Matches";
                self::$HEADING_MAPPING["V1"]["PC"]["twowaymatch"] = "<formatNumber_format> Mutual Match<es>";
                self::$HEADING_MAPPING["V1"]["PC"]["justJoinedMatches"] = "<formatNumber_format> Just Joined Matches";
                
                self::$HEADING_MAPPING["V1"]["PC"]["matchalerts"] = "<formatNumber_format> Daily Recommendations";
		*/
                
                self::$HEADING_MAPPING["V1"]["APP"]["DEFAULT"] = "<cnt> match<es> found";
                self::$HEADING_MAPPING["V1"]["APP"]["reverseDpp"] = "Members Lookin.. <cnt>";
                self::$HEADING_MAPPING["V1"]["APP"]["partnermatches"] = "Desired Partner.. (<cnt>)";
                self::$HEADING_MAPPING["V1"]["APP"]["twowaymatch"] = "Mutual Matches <cnt>";
                self::$HEADING_MAPPING["V1"]["APP"]["justJoinedMatches"] = "Just Joined Matches (<cnt>)";
                self::$HEADING_MAPPING["V1"]["APP"]["verifiedMatches"] = "Verified Matches <cnt>";
		self::$HEADING_MAPPING["V1"]["APP"]["matchalerts"] = "Daily Recommen.. <cnt>";
								self::$HEADING_MAPPING["V1"]["APP"]["kundlialerts"] = "Kundli Matches";
                self::$HEADING_MAPPING["V1"]["JSMS"]["DEFAULT"] = "<cnt> Match<es>";
                self::$HEADING_MAPPING["V1"]["JSMS"]["reverseDpp"] = "Members Lookin.. <cnt>";
                self::$HEADING_MAPPING["V1"]["JSMS"]["partnermatches"] = "Desired Partner.. <cnt>";
                self::$HEADING_MAPPING["V1"]["JSMS"]["twowaymatch"] = "Mutual Matches <cnt>";
                self::$HEADING_MAPPING["V1"]["JSMS"]["justJoinedMatches"] = "Just Joined <cnt>";
                self::$HEADING_MAPPING["V1"]["JSMS"]["verifiedMatches"] = "Verified Matches <cnt>";
		        self::$HEADING_MAPPING["V1"]["JSMS"]["matchalerts"] = "Daily Recommen.. <cnt>";
								self::$HEADING_MAPPING["V1"]["JSMS"]["kundlialerts"] = "Kundli Matches";
                
                
                self::$SUBHEADING_MAPPING["V1"]["PC"]["DEFAULT"] = "";
                self::$SUBHEADING_MAPPING["V1"]["PC"]["reverseDpp"] = "Below are members where you match their Desired Partner Profile";
                self::$SUBHEADING_MAPPING["V1"]["PC"]["partnermatches"] = 'Shown below are members who match your Desired Partner Profile <a href="/profile/dpp"><span class="disp_ip color5 pl10 f15 pause-rel">Modify</span></a>';
                self::$SUBHEADING_MAPPING["V1"]["PC"]["verifiedMatches"] = 'Shown below are members<a href="/static/agentinfo"><span class="disp_ip color5 pause-rel"> verified by a personal visit</span></a> and match your Desired Partner Profile';
                self::$SUBHEADING_MAPPING["V1"]["PC"]["twowaymatch"] = "Shown below are people where both of you match each other's criteria";
                self::$SUBHEADING_MAPPING["V1"]["PC"]["justJoinedMatches"] = "Shown below are your desired partner matches who joined in the last week";
                self::$SUBHEADING_MAPPING["V1"]["PC"]["kundlialerts"] = "Shown here are matches with a compatible guna score based on horoscope matching";
                self::$SUBHEADING_MAPPING["V1"]["PC"]["matchalerts"]["trend"] = array(0=>"Matches are based on history of your interests & acceptances.",1=>"Matches are based strictly on your Desired Partner Preferences.");
                self::$SUBHEADING_MAPPING["V1"]["PC"]["matchalerts"]["dpp"] = array(0=>"Matches are based strictly on your Desired Partner Preferences.",1=>"Matches are based on history of your interests & acceptances.");
                
                self::$CUSTOM_SUBHEADING_MAPPING["V1"]["PC"]["partnermatches"] = 'Please broaden your partner preference slightly to receive more matches in this list <a href="/profile/dpp"><span class="disp_ip color5 pl10 f15 pause-rel">Modify</span></a>';
                self::$CUSTOM_SUBHEADING_MAPPING["V1"]["PC"]["justJoinedMatches"] = 'Please broaden your partner preference slightly to receive more matches in this list <a href="/profile/dpp"><span class="disp_ip color5 pl10 f15 pause-rel">Modify</span></a>';
                
                self::$HEADING_0RESULT_MAPPING["V1"]["PC"]["DEFAULT"] = "0 People Match your Search Criteria";
                self::$HEADING_0RESULT_MAPPING["V1"]["PC"]["contactViewAttempts"] = "0 Contact View Attempts";
                self::$HEADING_0RESULT_MAPPING["V1"]["PC"]["reverseDpp"] = "0  Members Looking for Me";
                self::$HEADING_0RESULT_MAPPING["V1"]["PC"]["partnermatches"] = "0 Desired Partner Matches";
                self::$HEADING_0RESULT_MAPPING["V1"]["PC"]["twowaymatch"] = "0 Mutual Matches";
                self::$HEADING_0RESULT_MAPPING["V1"]["PC"]["justJoinedMatches"] = "0 Just Joined Matches";
                self::$HEADING_0RESULT_MAPPING["V1"]["PC"]["kundlialerts"] = "Kundli Matches";
                self::$HEADING_0RESULT_MAPPING["V1"]["PC"]["matchalerts"] = "0 Daily Recommendations";
                self::$HEADING_0RESULT_MAPPING["V1"]["APP"]["DEFAULT"] = "My Matches";
                self::$HEADING_0RESULT_MAPPING["V1"]["APP"]["reverseDpp"] = "No Matches";
                self::$HEADING_0RESULT_MAPPING["V1"]["APP"]["twowaymatch"] = "No Matches";
                self::$HEADING_0RESULT_MAPPING["V1"]["APP"]["justJoinedMatches"] = "No Matches";
                self::$HEADING_0RESULT_MAPPING["V1"]["APP"]["verifiedMatches"] = "No Matches";
                self::$HEADING_0RESULT_MAPPING["V1"]["APP"]["kundlialerts"] = "0 Kundli Matches";
		self::$HEADING_0RESULT_MAPPING["V1"]["APP"]["matchalerts"] = "Daily Recommendations 0";
                self::$HEADING_0RESULT_MAPPING["V1"]["JSMS"]["DEFAULT"] = "No Matches";
                self::$HEADING_0RESULT_MAPPING["V1"]["JSMS"]["twowaymatch"] = "Mutual Matches 0";
                self::$HEADING_0RESULT_MAPPING["V1"]["JSMS"]["justJoinedMatches"] = "Just Joined Matches 0";
                self::$HEADING_0RESULT_MAPPING["V1"]["JSMS"]["reverseDpp"] = "Members Looking for Me 0";
                self::$HEADING_0RESULT_MAPPING["V1"]["JSMS"]["verifiedMatches"] = "0 Verified Matches";
                self::$HEADING_0RESULT_MAPPING["V1"]["JSMS"]["kundlialerts"] = "0 Kundli Matches";
		self::$HEADING_0RESULT_MAPPING["V1"]["JSMS"]["matchalerts"] = "Daily Recommendations 0";
                
                self::$MESSAGE_0RESULT_MAPPING["V1"]["PC"]["DEFAULT"] = "Kindly relax your criteria and search again";
		self::$MESSAGE_0RESULT_MAPPING["V1"]["PC"]["reverseDpp"] = "People whose Desired Partner Profile you match will appear here";
		self::$MESSAGE_0RESULT_MAPPING["V1"]["PC"]["partnermatches"] = "People who match your Desired Partner Profile will appear here<br>Please relax your Desired Partner Profile to see results";
                self::$MESSAGE_0RESULT_MAPPING["V1"]["PC"]["verifiedMatches"] = "People who match your Desired Partner Profile and are Verified By Visit will appear here<br>Please relax your Desired Partner Profile to see results";
                self::$MESSAGE_0RESULT_MAPPING["V1"]["PC"]["contactViewAttempts"] = "People who attempted to view your contact will appear here.";
		self::$MESSAGE_0RESULT_MAPPING["V1"]["PC"]["twowaymatch"] = "Profiles where both of you match each other's criteria will appear here<br>Please relax your Desired Partner Profile to see results";
		self::$MESSAGE_0RESULT_MAPPING["V1"]["PC"]["justJoinedMatches"] = "People matching your desired partner profile and have joined in last one week will appear here<br>Please relax your Desired Partner Profile to see results";
		self::$MESSAGE_0RESULT_MAPPING["V1"]["PC"]["kundlialerts"] = array(
																			"withoutHoro"=>"Please <a href='/profile/viewprofile.php?ownview=1&EditWhatNew=uploadhoroscope'><span class='color5'>create your horoscope</span></a> to see your Kundli matches",
																			"withHoro"=>"Kindly relax the criteria present in your Desired Partner Profile",
																		);
		self::$MESSAGE_0RESULT_MAPPING["V1"]["PC"]["matchalerts"] = "We are finding the best recommendations for you. It may take a while.";
                self::$MESSAGE_0RESULT_MAPPING["V1"]["APP"]["DEFAULT"] = "No Matches Found";
                self::$MESSAGE_0RESULT_MAPPING["V1"]["APP"]["partnermatches"] = "There are no profiles matching your preference. Please broaden your 'Desired Partner' Preference to see profiles here.";
                self::$MESSAGE_0RESULT_MAPPING["V1"]["APP"]["verifiedMatches"] = "People who match your Desired Partner Profile and are Verified By Visit will appear here";
		self::$MESSAGE_0RESULT_MAPPING["V1"]["APP"]["matchalerts"] = "Profile you would see here are Matches sent to you every day on your Email ID. We have not sent you any 'Daily Recommendations' email yet.";
                self::$MESSAGE_0RESULT_MAPPING["V1"]["APP"]["kundlialerts"] = array(
																			"withoutHoro"=>"Please create your horoscope to see your Kundli matches",
																			"withHoro"=>"Kindly relax the criteria present in your Desired Partner Profile",
																		);
                self::$MESSAGE_0RESULT_MAPPING["V1"]["JSMS"]["DEFAULT"] = "No Matches Found";
                self::$MESSAGE_0RESULT_MAPPING["V1"]["JSMS"]["twowaymatch"] = " People where both of you match each other's criteria appear here";
                self::$MESSAGE_0RESULT_MAPPING["V1"]["JSMS"]["justJoinedMatches"] = "Matching profiles who have registered in the last 7 days appear here";
                self::$MESSAGE_0RESULT_MAPPING["V1"]["JSMS"]["reverseDpp"] = "People whose criteria you match appear here.";
                self::$MESSAGE_0RESULT_MAPPING["V1"]["JSMS"]["verifiedMatches"] = "People who match your Desired Partner Profile and are Verified By Visit will appear here";
               self::$MESSAGE_0RESULT_MAPPING["V1"]["JSMS"]["matchalerts"] = "Daily Recommendations will appear here"; 
               self::$MESSAGE_0RESULT_MAPPING["V1"]["JSMS"]["kundlialerts"] = array(
																			"withoutHoro"=>"Please <a href='/profile/mobhoroscope' class='color2'>create your horoscope </a>  to see your Kundli matches",
																			"withHoro"=>"Kindly relax the criteria present in your <a href='/profile/viewprofile.php?ownview=1&section=dpp' class='color2'>Desired Partner Profile</a>",
																		);
               self::$MESSAGE_0RESULT_MAPPING["V1"]["IOS"]["kundlialerts"] = array(

         "withoutHoro"=>"Please create your horoscope to see your Kundli matches",

         "withHoro"=>"Kindly relax the criteria present in your Desired Partner Profile",

 );

                self::$DEFAULT_PIC_SIZE["V1"]["PC"]["DEFAULT"] = "ProfilePic450Url";
                self::$DEFAULT_PIC_SIZE["V1"]["JSMS"]["DEFAULT"] = "MobileAppPicUrl";
                self::$DEFAULT_PIC_SIZE["V1"]["APP"]["DEFAULT"] = "MobileAppPicUrl";
                self::$DEFAULT_PIC_SIZE["V1"]["IOS"]["DEFAULT"] = "MobileAppPicUrl";
                
                self::$GA_TRACKING_MAPPING["V1"]["PC"]["DEFAULT"] = "";
                self::$GA_TRACKING_MAPPING["V1"]["APP"]["partnermatches"] = "Desired partner matches";
                self::$GA_TRACKING_MAPPING["V1"]["APP"]["twowaymatch"] = "Mutual matches";
                self::$GA_TRACKING_MAPPING["V1"]["APP"]["justJoinedMatches"] = "Just Joined";
                self::$GA_TRACKING_MAPPING["V1"]["APP"]["verifiedMatches"] = "Matches verified by visit";
                self::$GA_TRACKING_MAPPING["V1"]["APP"]["reverseDpp"] = "Members looking for me";
                self::$GA_TRACKING_MAPPING["V1"]["APP"]["matchalerts"] = "Daily recommendations"; 
				self::$GA_TRACKING_MAPPING["V1"]["APP"]["kundlialerts"] = "Kundli matches";
              
        }
        
        /*
	* This function will return title.
	* @access public
	* @param string $params will include version of api,channel calling api, search type, cnt number of results 
	* @return string title 
	*/
        static public function getTitle($params)
        {
		return self::getValue("TITLE_MAPPING",$params);
	}
	
	 /*
	* This function will return default pic size .
	* @access public
	* @param string $params will include version of api,channel calling api, search type, cnt number of results 
	* @return string title 
	*/
        static public function getDefaultPicSize($params)
        {
			return self::getValue("DEFAULT_PIC_SIZE",$params);
		}
		
	 /*
	* This function will return GA tracking.
	* @access public
	* @param string $params will include version of api,channel calling api, search type, cnt number of results 
	* @return string ga tracking string
	*/
        static public function getGATracking($params)
        {
			return self::getValue("GA_TRACKING_MAPPING",$params);
		}
	
	/*
	* This function will return search criteria.
	* @access public
	* @param string $version version of api
	* @param string $channel channel calling api
	* @param string $search search type 
	* @return string search criteria
	*/
	static public function getSearchCriteriaText($params)
        {
		return self::getValue("SEARCH_CRITERIA_TEXT_MAPPING",$params);
	}
	
	/*
	* This function will return title.
	* @access public
	* @param string $params will include version of api,channel calling api, search type, cnt number of results 
	* @return string heading
	*/
	static public function getHeading($params)
        {
		if($params["Count"]==0)
		{
			return self::getValue("HEADING_0RESULT_MAPPING",$params);
		}
		else
		{
			
			$heading = self::getValue("HEADING_MAPPING",$params);
			if(strpos($heading,"<formatNumber_format>")>=0)
			{
				$countFormat = CommonUtility::moneyFormatIndia($params["Count"]);
				$heading = str_replace('<formatNumber_format>',$countFormat,$heading);
			}
			
			
			if($params["Count"]==1)
				return str_replace('<es>','',$heading);
			else
				return str_replace('<es>','es',$heading);
		}
	}
	
	static public function getSubHeading($params)
        {
                if(
                        ($params['SearchType'] == 'partnermatches' && $params['Count'] <=self::DPP_CUSTOM_MESSAGE_COUNT) || 
                        ($params['SearchType'] == 'justJoinedMatches' && $params['Count'] <=self::JUST_JOIN_CUSTOM_MESSAGE_COUNT)
                ){
                        return self::getValue("CUSTOM_SUBHEADING_MAPPING",$params);
                }
		/** TEMP **/
		$temp = self::getValue("SUBHEADING_MAPPING",$params);
		if(is_array($temp))
		{
			if($params["matLogic"]==1)
				return $temp["trend"][1];
			return $temp["trend"][0];
		}
		/** TEMP **/
		return self::getValue("SUBHEADING_MAPPING",$params);
	}
	
	/*
	* This function will return message.
	* @access public
	* @param string $params will include version of api,channel calling api, search type, cnt number of results 
	* @return string message or null
	*/
	static public function getMessageResult($params)
	{
		if($params["Count"] >0)
		{
			return null;
		}
		else
		{
			if($params["SearchType"] == 'kundlialerts')
			{
				$horoscope = $params["horoscope"];
				if(MobileCommon::isApp()=="I")
				{
					return self::$MESSAGE_0RESULT_MAPPING["V1"]["IOS"]["kundlialerts"][$horoscope];
				}
				else
				{
					return self::$MESSAGE_0RESULT_MAPPING["V1"][$params["Channel"]]["kundlialerts"][$horoscope];
				}
			}
			return self::getValue("MESSAGE_0RESULT_MAPPING",$params);
		}
	}
	
        /*
	* This function will return value from the desired array.
	* @access public
	* @param string $arrayName static array name to be used to get value
	* @param string $params will include version of api,channel calling api, search type, cnt number of results 
	* @return string value of the array 
	*/
        static private function getValue($arrayName,$params)
        {
		
		if(!is_array($params) || !array_key_exists("Version",$params) || !array_key_exists("Channel",$params) || !array_key_exists("SearchType",$params))
			throw new JsException("Required params not set in SearchTitleAndTextEnums class");

		self::initTitleAndText();
		$array= self::$$arrayName;
		$version = $params["Version"];
		$channel = $params["Channel"];
		$search = $params["SearchType"];

		if (!array_key_exists($version,$array))
		{
			$version = "V1";
		}
                if (!array_key_exists($channel, $array[$version]))
                {
			$channel = "PC";
		}
                if (!array_key_exists($search, $array[$version][$channel]))
                {
			$search = "DEFAULT";
		}
		return str_replace('<cnt>',$params["Count"],$array[$version][$channel][$search]);
        }

}
?>
