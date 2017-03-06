<?php
/**
 * @brief This class is for myjs mobile App version 1 functions
 * @author Reshu Rajput
 * @created 2014-05-09
 */

class MyJsIOSV1 extends MyJsMobileAppV1
{
	static public function init()
        {
        	LoggingManager::getInstance()->logThis(LoggingEnums::LOG_INFO, "class MyJsIOSV1 for IOS and JSMS "); 
		self::$informationTupleFields= Array(
			"INTEREST_RECEIVED"=>Array("PROFILECHECKSUM","OCCUPATION","AGE", "HEIGHT", "RELIGION","CASTE","MTONGUE","INCOME","ProfilePic120Url","EDUCATION","BUTTONS","USERNAME","NAME_OF_USER"),
			"VISITORS"=>Array("PROFILECHECKSUM","ThumbailUrl"),
			"INTEREST_EXPIRING"=>Array("PROFILECHECKSUM","ThumbailUrl"),
			"MATCH_ALERT"=>Array("PROFILECHECKSUM","OCCUPATION","AGE", "HEIGHT", "RELIGION","CASTE","MTONGUE","INCOME","ProfilePic120Url","EDUCATION","USERNAME","NAME_OF_USER"),
			"MATCH_OF_THE_DAY"=>Array("PROFILECHECKSUM","OCCUPATION","AGE", "HEIGHT", "RELIGION","CASTE","MTONGUE","INCOME","ProfilePic120Url","EDUCATION","USERNAME","NAME_OF_USER"));
		self::$informationTypeFields= Array(
			"INTEREST_RECEIVED"=>Array("NEW_COUNT","TITLE","TUPLES","TRACKING","SHOW_NEXT","VIEW_ALL_COUNT","CONTACT_ID"),
			"VISITORS"=>Array("NEW_COUNT","TITLE","TUPLES","TRACKING","VIEW_ALL_COUNT","CONTACT_ID"),
			"MATCH_ALERT"=>Array("TITLE","TUPLES","MY_MATCHES_FLAG","TRACKING","SHOW_NEXT","NEW_COUNT","VIEW_ALL_COUNT","CONTACT_ID"),
			"MESSAGE_RECEIVED"=>Array("NEW_COUNT","VIEW_ALL_COUNT"),
			"ACCEPTANCES_SENT"=>Array("TITLE","SUBTITLE","VIEW_ALL_COUNT"),
			"ACCEPTANCES_RECEIVED"=>Array("NEW_COUNT","TITLE","SUBTITLE","VIEW_ALL_COUNT"),
			"JUST_JOINED_MATCHES"=>Array("NEW_COUNT","VIEW_ALL_COUNT","TITLE","SUBTITLE"),
			"ALL_ACCEPTANCE"=>Array("NEW_COUNT","VIEW_ALL_COUNT","TITLE","SUBTITLE"),
			"INTEREST_EXPIRING"=>Array("NEW_COUNT","TITLE","TUPLES","TRACKING","VIEW_ALL_COUNT","CONTACT_ID"),
			"MATCH_OF_THE_DAY"=>Array("TITLE","TUPLES","MY_MATCHES_FLAG","TRACKING","CONTACT_ID","VIEW_ALL_COUNT"),
			);
		self::$myProfileIncompleteFields =Array("PHOTO","CAREER","FAMILY","SOCIAL","ASTRO","LIFESTYLE","HOBBY");
		self::$tupleTitleField= Array(
                        "INTEREST_RECEIVED"=>"OCCUPATION",
                        "MATCH_ALERT"=>"OCCUPATION",
                     "MATCH_OF_THE_DAY"=>"OCCUPATION");
		self::$noTupleText= Array(
                        "INTEREST_RECEIVED"=>"There are no People to Respond to",
                        "VISITORS"=>"No one visited your profile recently",
                        "MATCH_ALERT"=>"Go to My Matches");

	}
}
?>
