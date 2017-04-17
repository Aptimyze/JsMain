<?php
/* this classs list all the enums required by profile communication design
 *Author : Reshu Rajput
 *Created On : 2013/09/27
 */
class PROFILE_COMMUNICATION_ENUM_INFO
{
	static public $moduleEnum = array();
	static public $informationTypeEnum = array();
	static public $tuplesEnum = array();
	
	/* All the enums for every module and information type and tuples combination required need to be mentioned in this initializing function*/
	static public function init()
	{
		self::$moduleEnum["MYJS"]                                = "MyJs";
		self::$moduleEnum["MYJSAPP"]                             = "MyJs";
		self::$moduleEnum["ContactCenterAPP"]                    = "Inbox";
		self::$moduleEnum["ContactCenterMYJS"]                    = "Inbox";
		self::$moduleEnum["ContactCenterDesktop"]                 = "Inbox";
		self::$informationTypeEnum["PHOTO_REQUEST_RECEIVED"]     = "PictureService";
		self::$informationTypeEnum["PHOTO_REQUEST_SENT"]     = "PictureService";
		self::$informationTypeEnum["JUST_JOINED_MATCHES"]     = "JustJoinedMatches";
		self::$informationTypeEnum["ALL_ACCEPTANCE"]     = "TotalAcceptances";
		self::$informationTypeEnum["HOROSCOPE_REQUEST_RECEIVED"] = "Horoscope";
		self::$informationTypeEnum["HOROSCOPE_REQUEST_SENT"] = "Horoscope";
		self::$informationTypeEnum["ACCEPTANCES_RECEIVED"]       = "ContactsRecord";
		self::$informationTypeEnum["INTEREST_RECEIVED"]          = "ContactsRecord";
		self::$informationTypeEnum["INTEREST_ARCHIVED"]          = "ContactsRecord";
		self::$informationTypeEnum["INTEREST_RECEIVED_FILTER"]          = "ContactsRecord";
		self::$informationTypeEnum["INTEREST_EXPIRING"]          = "ContactsRecord";
		self::$informationTypeEnum["INTEREST_SENT"]              = "ContactsRecord";
		self::$informationTypeEnum["DECLINE_RECEIVED"]           = "ContactsRecord";
		self::$informationTypeEnum["NOT_INTERESTED"]           = "ContactsRecord";
		self::$informationTypeEnum["FILTERED_INTEREST"]           = "ContactsRecord";
		self::$informationTypeEnum["NOT_INTERESTED_BY_ME"]           = "ContactsRecord";
		self::$informationTypeEnum["MESSAGE_RECEIVED"]           = "MessageRecord";
		self::$informationTypeEnum["MY_MESSAGE_RECEIVED"]           = "MessageRecord";
		self::$informationTypeEnum["VISITORS"]                   = "Visitors";
		self::$informationTypeEnum["INTRO_CALLS"]                   = "getIntroCallHistory";
		self::$informationTypeEnum["INTRO_CALLS_COMPLETE"]                   = "getIntroCallHistory";
		self::$informationTypeEnum["PROFILE_UPDATES"]            = "ProfileUpdatedContacts";
		self::$informationTypeEnum["SHORTLIST"]					 = "Bookmarks";
		self::$informationTypeEnum["CONTACTS_VIEWED"]           = "VIEW_CONTACTS_LOG";
		self::$informationTypeEnum["PEOPLE_WHO_VIEWED_MY_CONTACTS"]           = "VIEW_CONTACTS_LOG"; 
		self::$informationTypeEnum["IGNORED_PROFILES"]           = "IgnoredProfiles"; 
                self::$informationTypeEnum["MATCH_OF_THE_DAY"]           = "ContactsRecord";

		self::$tuplesEnum["SMALL_ICON_TUPLE"]                    = "SmallIconTuple";
		self::$tuplesEnum["BIG_ICON_TUPLE"]                      = "BigIconTuple";
		self::$tuplesEnum["INBOX_APP"]                           = "ContactEngineAppTuple";
		self::$tuplesEnum["MEDIUM_ICON_TUPLE"]                   = "MediumIconTuple";
		self::$tuplesEnum["NO_USERNAME_TUPLE"]                   = "NoUsernameTuple";
		self::$tuplesEnum["ONLY_PIC_TUPLE"]                      = "OnlyPicTuple";
		self::$tuplesEnum["MYJS_MESSAGE_APP"]                    = "MyjsMessageAppTuple";
		self::$tuplesEnum["INBOX_EOI_APP"]						 = "ContactEngineEOIAppTuple";
		self::$tuplesEnum["SHORTLIST_APP"]						 = "ContactEngineAppShortlistTuple";
		self::$tuplesEnum["VIEW_SIMILAR"]                  		 = "ViewSimilarEOITuple";
		self::$tuplesEnum["SIZE_120"]						 = "SIZE_120";
		
		self::$informationTypeEnum["ACCEPTANCES_SENT"]           = "ContactsRecord";
		self::$informationTypeEnum["MATCH_ALERT"]                = "matchalert";
		self::$informationTypeEnum["MY_MESSAGE"]                 = "MessageRecord";
		self::$tuplesEnum["MATCHALERT_MAILER_TUPLE"]                    = "MatchalertMailerTuple";
		self::$tuplesEnum["INBOX_VIEWED_DATE"]                    = "ContactEngineInterestViewedTuple";
		self::$tuplesEnum["INBOX_VIEWED_DATE_NO_MESSAGE"]                    = "ContactEngineInterestViewedNoMesageTuple";
		self::$tuplesEnum["FEATURED_PROFILE_MAILER_TUPLE"]                    = "featuredProfileDetailsTuple";

	}
	
	/* Exists functions below verify if particular module, information type and tuple exists respectively
	 *@param : key  to be searched
	 *@return : bool true or false for key existance 
	 */
	static public function ifModuleExists($key)
	{
		self::init();
		if (array_key_exists($key, self::$moduleEnum))
			return true;
		else
			throw new JsException("This module does not exists:" . $key . " in class PROFILE_COMMUNICATION_ENUM_INFO");
	}
	
	static public function ifInformationTypeExists($key)
	{
		self::init();
		if (array_key_exists($key, self::$informationTypeEnum))
			return true;
		else
			throw new JsException("This information type does not exists:" . $key . " in class PROFILE_COMMUNICATION_ENUM_INFO");
	}
	
	static public function ifTupleExists($key)
	{
		self::init();
		if (array_key_exists($key, self::$tuplesEnum))
			return true;
		else
			throw new JsException("This tuple does not exists:" . $key . " in class PROFILE_COMMUNICATION_ENUM_INFO");
	}
	
	
	/* retrieve enum form type 
	 *@param : key module/informationType/tuple 
	 *@return : enum corresponding to provided type , module
	 */
	static public function getClass($key)
	{
		self::init();
		if (array_key_exists($key, self::$moduleEnum))
			return self::$moduleEnum[$key];
		if (array_key_exists($key, self::$informationTypeEnum))
			return self::$informationTypeEnum[$key];
		if (array_key_exists($key, self::$tuplesEnum))
			return self::$tuplesEnum[$key];
		throw new JsException("This class does not exists:" . $key . " in class PROFILE_COMMUNICATION_ENUM_INFO");
	}
}
?>
