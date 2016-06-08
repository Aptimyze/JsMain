<?php
/**
 * @brief This class is for myjs mobile App version 1 functions
 * @author Reshu Rajput
 * @created 2014-05-09
 */

class MyJsAndroidV1 extends MyJsMobileAppV1
{
	static public function init()
        {
		self::$informationTupleFields= Array(
			"INTEREST_RECEIVED"=>Array("PROFILECHECKSUM","OCCUPATION","AGE", "HEIGHT", "RELIGION","CASTE","MTONGUE","INCOME","ThumbailUrl","EDUCATION"),
			"VISITORS"=>Array("PROFILECHECKSUM","ThumbailUrl"),
			"MATCH_ALERT"=>Array("PROFILECHECKSUM","OCCUPATION","AGE", "HEIGHT", "RELIGION","CASTE","MTONGUE","INCOME","ThumbailUrl","EDUCATION"));
		self::$informationTypeFields= Array(
			"INTEREST_RECEIVED"=>Array("NEW_COUNT","TITLE","TUPLES","TRACKING","SHOW_NEXT","VIEW_ALL_COUNT"),
			"VISITORS"=>Array("NEW_COUNT","TITLE","TUPLES","TRACKING"),
			"MATCH_ALERT"=>Array("TITLE","TUPLES","MY_MATCHES_FLAG","TRACKING"),
			"MESSAGE_RECEIVED"=>Array("NEW_COUNT","VIEW_ALL_COUNT"),
			"ACCEPTANCES_SENT"=>Array("TITLE","SUBTITLE","VIEW_ALL_COUNT"),
			"ACCEPTANCES_RECEIVED"=>Array("NEW_COUNT","TITLE","SUBTITLE","VIEW_ALL_COUNT"),
			"JUST_JOINED_MATCHES"=>Array("NEW_COUNT","VIEW_ALL_COUNT","TITLE","SUBTITLE"),
			"ALL_ACCEPTANCE"=>Array("NEW_COUNT","VIEW_ALL_COUNT","TITLE","SUBTITLE"),
			"NOT_INTERESTED_BY_ME"=>Array("VIEW_ALL_COUNT","TITLE","SUBTITLE"),
			"NOT_INTERESTED"=>Array("NEW_COUNT","VIEW_ALL_COUNT","TITLE","SUBTITLE"));
		self::$myProfileIncompleteFields =Array("PHOTO","CAREER","FAMILY","SOCIAL","ASTRO","LIFESTYLE","HOBBY");
		self::$tupleTitleField= Array(
	                "INTEREST_RECEIVED"=>"OCCUPATION",
                        "MATCH_ALERT"=>"OCCUPATION");
		self::$noTupleText= Array(
                        "INTEREST_RECEIVED"=>"There are no People to Respond to",
                        "VISITORS"=>"No one visited your profile recently",
                        "MATCH_ALERT"=>"Go to My Matches");


	}
}
?>
