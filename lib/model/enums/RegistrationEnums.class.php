<?php

class RegistrationEnums {

  public static $relationIdForMale = array("1", "2", "4", "6");

  public static $relationIdForFemale = array("1D", "2D", "4D", "6D");

  public static $pageFieldMap = array(
    "DP3" => array("HIJAB_MARRIAGE", "HIJAB", "SUNNAH_CAP", "SUNNAH_BEARD", "UMRAH_HAJJ", "QURAN", "ZAKAT", "FASTING", "NAMAZ", "MATHTHAB", "HOROSCOPE_MATCH", "CLEAN_SHAVEN", "WEAR_TURBAN", "TRIM_BEARD", "CUT_HAIR", "AMRITDHARI", "NAKSHATRA", "ANCESTRAL_ORIGIN", "MANGLIK", "RASHI", "GOTHRA", "SUBCASTE", "FAMILYINFO", "PARENT_CITY_SAME", "M_SISTER", "T_SISTER", "M_BROTHER", "T_BROTHER", "MOTHER_OCC", "FAMILY_BACK", "FAMILY_STATUS", "FAMILY_TYPE", "FAMILY_VALUES", "SAMPRADAY", "NAME_OF_USER", "RECORD_ID", "NATIVE_CITY", "WORKING_MARRIAGE", "SPEAK_URDU", "DIOCESE", "BAPTISED", "READ_BIBLE", "OFFER_TITHE", "SPREADING_GOSPEL", "ZARATHUSHTRI", "PARENTS_ZARATHUSHTRI", "NATIVE_COUNTRY", "NATIVE_STATE"),
    "MP3" => array("CITY_RES", "INCOME", "OCCUPATION", "EDU_LEVEL_NEW", "PINCODE", "MTONGUE", "DTOFBIRTH"),
    "MR" => array("MTONGUE", "DTOFBIRTH", "RELATIONSHIP", "PHONE_MOB", "EMAIL", "SOURCE"),
    "DP6" => array("F_INCOME", "F_MTONGUE", "F_CITY_RES", "F_COUNTRY_RES", "F_CASTE", "F_RELIGION", "F_MSTATUS", "F_AGE", "NATURE_HANDICAP", "CONTACT", "SHOWADDRESS", "SHOWMESSENGER", "MESSENGER_CHANNEL", "MESSENGER_ID", "HANDICAPPED", "HIV", "BLOOD_GROUP", "WORK_STATUS", "JOB_INFO", "EDUCATION"),
    "DP5" => array("P_HRS", "P_LRS", "P_LDS", "P_HHEIGHT", "P_LHEIGHT", "P_HDS", "P_HAGE", "P_LAGE", "SPOUSE"),
    "MP2" => array("HEIGHT", "COUNTRY_RES", "HAVECHILD", "MSTATUS", "CASTE", "RELIGION", "REG_ID"),
    "DP2" => array("YOURINFO", "BTYPE", "RES_STATUS", "COMPLEXION", "DRINK", "SMOKE", "INCOME", "DIET", "OCCUPATION", "EDU_LEVEL_NEW"),
    "DP1" => array("SOURCE", "PROMO", "PHONE_RES", "CASTE", "RELIGION", "HAVECHILD", "MSTATUS", "CITY_RES", "COUNTRY_RES", "HEIGHT", "EMAIL", "PASSWORD", "RELATIONSHIP", "GENDER", "DTOFBIRTH", "MTONGUE", "PHONE_MOB", "SHOWPHONE", "SHOWMOBILE", "PINCODE", "TERMSANDCONDITIONS", "RECORD_ID"),
    "MP1" => array("PHONE_MOB", "MTONGUE", "DTOFBIRTH", "GENDER", "RELATIONSHIP", "EMAIL", "PASSWORD", "SOURCE"),
    "MP5" => array("NATIVE_COUNTRY", "NATIVE_CITY", "NATIVE_STATE", "ANCESTRAL_ORIGIN", "GOTHRA", "FAMILY_BACK", "MOTHER_OCC", "T_BROTHER", "M_BROTHER", "T_SISTER", "M_SISTER", "FAMILYINFO"),
    "MP4" => array("YOURINFO"),
    "APP1" => array("RELATIONSHIP", "GENDER", "DTOFBIRTH", "HEIGHT", "COUNTRY_RES", "CITY_RES", "PINCODE", "MSTATUS", "HAVECHILD", "MTONGUE", "RELIGION", "CASTE", "EDU_LEVEL_NEW", "OCCUPATION", "INCOME", "EMAIL", "PASSWORD", "PHONE_MOB", "SOURCE"),
    "APP2" => array("YOURINFO"),
    "CP" => array("PASSWORD"),
    "APP3" => array("GOTHRA", "FAMILYINFO", "M_SISTER", "T_SISTER", "M_BROTHER", "T_BROTHER", "MOTHER_OCC", "FAMILY_BACK", "FAMILY_STATUS", "FAMILY_TYPE", "FAMILY_VALUES", "FAMILY_INCOME"),
    "JSPCR1" => array("SOURCE", "EMAIL", "PASSWORD", "RELATIONSHIP", "NAME_OF_USER", "DTOFBIRTH", "PHONE_MOB", "GENDER","DISPLAYNAME"),
    "JSPCR2" => array("MTONGUE", "RELIGION", "CASTE", "SUBCASTE", "MANGLIK", "MSTATUS", "HAVECHILD", "HEIGHT", "COUNTRY_RES", "CITY_RES", "PINCODE","HOROSCOPE_MATCH","CASTE_NO_BAR"),
    "JSPCR3" => array("YOURINFO", "INCOME", "OCCUPATION", "EDU_LEVEL_NEW","COLLEGE","PG_COLLEGE","DEGREE_PG","DEGREE_UG","OTHER_PG_DEGREE","OTHER_UG_DEGREE"),
    "JSPCR4" => array("FAMILY_TYPE", "M_SISTER", "M_BROTHER", "T_SISTER", "T_BROTHER", "MOTHER_OCC", "FAMILY_BACK", "CONTACT","NATIVE_CITY","NATIVE_COUNTRY","NATIVE_STATE","ANCESTRAL_ORIGIN","FAMILYINFO"),

  );

  public static $lengthArr = array('YOURINFO' => '3000', 'FAMILYINFO' => '1000', 'JOB_INFO' => '1000', 'EDUCATION' => '1000', 'CONTACT' => '1000', 'EMAIL' => '100', 'PASSWORD' => '40', 'USERNAME' => '40', 'MESSENGER_ID' => '255', 'SPOUSE' => '1000', 'SUBCASTE' => '250', 'GOTHRA' => '250', 'ANCESTRAL_ORIGIN' => '100');

  public static $tieupCookieList = array("1" => "JS_ADNETWORK", "2" => "JS_ACCOUNT", "3" => "JS_CAMPAIGN", "4" => "JS_ADGROUP", "5" => "JS_KEYWORD", "6" => "JS_MATCH", "7" => "JS_LMD");

  public static $tieupGetterList = array("1" => "adnetwork", "2" => "account", "3" => "campaign", "4" => "adgroup", "5" => "keyword", "6" => "match", "7" => "lmd");

  public static $sourceParamList = array('newsource', 'tieup_source', 'hit_source');

  public static $usGroupList = array("Google NRI US", "rediff_us_fm", "yahoo_nri", "sulekha_us_fm");

  public static $fieldsToFetchData = array(
    "JSPCR1" => "RELATIONSHIP_REG",
    "JSPCR2" => "RELIGION,SUBCASTE,MSTATUS,HEIGHT_JSON",
    "JSPCR3" => "INCOME,OCCUPATION,EDU_LEVEL_NEW,DEGREE_UG,DEGREE_PG,YOURINFO"
  );

  public static $fieldsOnPage = array(
    "JSPCR1" => array("gender", "cpf", "dob", "month"),
    "JSPCR2" => array("mtongue", "religion", "caste", "subcaste", "manglik","horoscopeMatch", "mstatus","mstatus_muslim", "haveChildren", "height", "cityReg", "countryReg", "pincode","stateReg","casteNoBar"),
    "JSPCR3" => array("hdegree","ugDegree","pgDegree","ugCollege","pgCollege","occupation","income"),
    "JSPCR4" => array("familyType","brother","sister","fatherOccupation","motherOccupation","familyState","familyCity","country","ancestral_origin","familyinfo"),
  );

  public static $defaultFieldVal = array(
    "gender" => array(0 => array(M => "Male"), 1 => array(F => "Female")),
    "dob" => array(1 => "date", 2 => "month", 3 => "year"),
    "month" => array(1 => "Jan", 2 => "Feb", 3 => "Mar", 4 => "April", 5 => "May", 6 => "Jun", 7 => "Jul", 8 => "Aug", 9 => "Sep", 10 => "Oct", 11 => "Nov", 12 => "Dec"),
    "manglik" => array(0=>array(M=>"Manglik"),1=>array(N=>"Non-manglik"),2=>array(A=>"Anshik manglik")),
    "haveChildren" => array(0=>array(N => "No"),1=>array(YT => "Yes, living together"),2=>array(YS => "Yes, living separately"))
  );

  public static $templateForm = array(
    "JSPCR1" => "_jspcRegFormPage1",
    "JSPCR2" => "_jspcRegFormPage2",
    "JSPCR3" => "_jspcRegFormPage3",
    "JSPCR4" => "_jspcRegFormPage4",    
  );
  public static $errorLogInList = array("FILE"=>"FILE","TABLE"=>"TABLE");
  public static $legalVariableList = array('service_email', 'service_sms', 'service_call', 'promo_email', 'memb_sms', 'memb_ivr', 'memb_mails');
  public static $errorLoggingDetails = array(
						"JSPCR1"=>array("IN"=>"FILE","NAME"=>"/tmp/regError.txt","ACTION"=>array("LOG","SHOW_404")),
						"JSPCR2"=>array("IN"=>"FILE","NAME"=>"/tmp/regError.txt","ACTION"=>array("LOG","SHOW_404")),
						"JSPCR3"=>array("IN"=>"FILE","NAME"=>"/tmp/regError.txt","ACTION"=>array("LOG","SHOW_404")),
						"JSPCR4"=>array("IN"=>"FILE","NAME"=>"/tmp/regError.txt","ACTION"=>array("LOG","SHOW_404")),
                                                "INVALID_PARAM_REQUEST"=>array("ACTION"=>array("SHOW_404")),
						"COMMON"=>array("IN"=>"FILE","NAME"=>"/tmp/commError.txt","ACTION"=>array("SHOW_404"))
);
  public static $errorMapping  = array("INVALID_PAGENAME"=> array("label"=>"INVALID_PARAM_REQUEST","errorMessage"=>"pageName provided is invalid"));
  public static $errorActionOrder = array("LOG","SITE_DOWN_URL","SHOW_404","SHOW_500");

  public static $JSPC_REG_PAGE = array(1=>"JSPCR1",2=>"JSPCR2",3=>"JSPCR3",4=>"JSPCR4",5=>"JSPCR5",6=>"JSPCR6");
  public static $JSMS_REG_PAGE = array(1=>"JSMS");
  const LIVE_HELP_CHAT_URL = "http://server.iad.liveperson.net/hc/13507809/?cmd=file&file=visitorWantsToChat&offlineURL=<SITE_URL>/P/faq_redirect.htm&site=13507809&byhref=1&imageUrl=<SITE_URL>/images_try/liveperson";

  public static $pageHiddenFields = array(
    "JSPCR1"=>array("tieup_source","hit_source","adnetwork","adnetwork1","fname","newip","account","campaign","adgroup","keyword","match","lmd","showlogin","frommarriagebureau","groupname","affiliateid","id","leadid","secondary_source","email_is_ok","source","record_id","_csrf_token","submitPage"),
    "JSPCR2"=>array("_csrf_token","leadid"),
    "JSPCR3"=>array("_csrf_token","leadid"),
    "JSPCR4"=>array("_csrf_token","leadid"),
  );
  public static $pageFields = array(
    "JSPCR1"=>array("email","password","relationship","gender","name_of_user","dtofbirth","phone_mob","source","_csrf_token","displayname"),
    "JSPCR2"=>array("mtongue", "religion", "caste", "subcaste", "manglik", "horoscope_match","mstatus","havechild", "height", "city_res", "country_res", "pincode","_csrf_token","casteNoBar"),
    "JSPCR3"=>array("edu_level_new","occupation","income","yourinfo","college","degree_pg","degree_ug","pg_college","other_pg_degree","other_ug_degree","_csrf_token"),
    "JSPCR4"=>array("family_type", "m_sister", "t_sister", "m_brother", "t_brother", "mother_occ", "family_back","contact","native_city", "native_country","_csrf_token","native_state","ancestral_origin","familyinfo"),    
  );
  public static $errorEnums = array("WHITELIST"=>"WHITELIST","VALIDATION"=>"VALIDATION");
  public static $jpartnerfields = array(
		"JSPCR1"=>array("AGE"),
		"JSPCR2"=>array("AGE","MSTATUS","MTONGUE","CASTE","COUNTRY_RES","CITY_RES","RELIGION","HEIGHT"),
		"JSPCR3"=>array("COUNTRY_RES","AGE"),
		"JSPCR4"=>array("COUNTRY_RES","AGE")
	);
  public static $emailModification = "_deleted";
  public static $otherText = "Other";
  public static $marathiValue = "'20'";
}
?>
