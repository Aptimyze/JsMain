<?php
/**
 * Description of ProfileCacheConstants
 * Class which contain all the defined constants and enums
 *
 * @package     cache
 * @author      Kunal Verma
 * @created     7th July 2016
 */

class ProfileCacheConstants
{
    const ENABLE_PROFILE_CACHE = true;
    const CONSUME_PROFILE_CACHE = true;
    const ENABLE_PROFILE_CACHE_LOGS = true;
    const LOG_LEVEL = 0;
    const CACHE_CRITERIA = 'PROFILEID';
    const ALL_FIELDS_SYM = '*';
    const PROFILE_CACHE_PREFIX = '_p_';
    const CACHE_HASH_KEY = 'PROFILEID';
    const ACTIVATED_KEY = 'activatedKey';
    const PROFILE_LOG_PATH = 'ProfileCache';
    const COMMAND_LINE = 'cli';
    const CACHE_EXPIRE_TIME = 86400;
    const CACHE_MAX_ATTEMPT_COUNT = 3;
    const NOT_FILLED = "-NF-";
    const DUPLICATE_FIELD_DELIMITER = "-d-";
    const GETARRAY_PROFILEID_LIMIT = 50;
    const KEY_PREFIX_DELIMITER = ".";
    const ALLOW_CLI_SET = 0;

        public static $arrJProfileColumns = array(
                                        "PROFILEID",
                                        "USERNAME",
                                        "PASSWORD",
                                        "GENDER",
                                        "RELIGION",
                                        "CASTE",
                                        "SECT",
                                        "MANGLIK",
                                        "MTONGUE",
                                        "MSTATUS",
                                        "DTOFBIRTH",
                                        "OCCUPATION",
                                        "COUNTRY_RES",
                                        "CITY_RES",
                                        "HEIGHT",
                                        "EDU_LEVEL",
                                        "EMAIL",
                                        "IPADD",
                                        "ENTRY_DT",
                                        "MOD_DT",
                                        "RELATION",
                                        "COUNTRY_BIRTH",
                                        "SOURCE",
                                        "INCOMPLETE",
                                        "PROMO",
                                        "DRINK",
                                        "SMOKE",
                                        "HAVECHILD",
                                        "RES_STATUS",
                                        "BTYPE",
                                        "COMPLEXION",
                                        "DIET",
                                        "HEARD",
                                        "INCOME",
                                        "CITY_BIRTH",
                                        "BTIME",
                                        "HANDICAPPED",
                                        "NTIMES",
                                        "SUBSCRIPTION",
                                        "SUBSCRIPTION_EXPIRY_DT",
                                        "ACTIVATED",
                                        "ACTIVATE_ON",
                                        "AGE",
                                        "GOTHRA",
                                        "GOTHRA_MATERNAL",
                                        "NAKSHATRA",
                                        "MESSENGER_ID",
                                        "MESSENGER_CHANNEL",
                                        "PHONE_RES",
                                        "PHONE_MOB",
                                        "FAMILY_BACK",
                                        "SCREENING",
                                        "CONTACT",
                                        "SUBCASTE",
                                        "YOURINFO",
                                        "FAMILYINFO",
                                        "SPOUSE",
                                        "EDUCATION",
                                        "LAST_LOGIN_DT",
                                        "SHOWPHONE_RES",
                                        "SHOWPHONE_MOB",
                                        "HAVEPHOTO",
                                        "PHOTO_DISPLAY",
                                        "PHOTOSCREEN",
                                        "PREACTIVATED",
                                        "KEYWORDS",
                                        "PHOTODATE",
                                        "PHOTOGRADE",
                                        "TIMESTAMP",
                                        "PROMO_MAILS",
                                        "SERVICE_MESSAGES",
                                        "PERSONAL_MATCHES",
                                        "SHOWADDRESS",
                                        "UDATE",
                                        "SHOWMESSENGER",
                                        "PINCODE",
                                        "PARENT_PINCODE",
                                        "PRIVACY",
                                        "EDU_LEVEL_NEW",
                                        "FATHER_INFO",
                                        "SIBLING_INFO",
                                        "WIFE_WORKING",
                                        "JOB_INFO",
                                        "MARRIED_WORKING",
                                        "PARENT_CITY_SAME",
                                        "PARENTS_CONTACT",
                                        "SHOW_PARENTS_CONTACT",
                                        "FAMILY_VALUES",
                                        "SORT_DT",
                                        "VERIFY_EMAIL",
                                        "SHOW_HOROSCOPE",
                                        "GET_SMS",
                                        "STD",
                                        "ISD",
                                        "MOTHER_OCC",
                                        "T_BROTHER",
                                        "T_SISTER",
                                        "M_BROTHER",
                                        "M_SISTER",
                                        "FAMILY_TYPE",
                                        "FAMILY_STATUS",
                                        "FAMILY_INCOME",
                                        "CITIZENSHIP",
                                        "BLOOD_GROUP",
                                        "HIV",
                                        "THALASSEMIA",
                                        "WEIGHT",
                                        "NATURE_HANDICAP",
                                        "ORKUT_USERNAME",
                                        "WORK_STATUS",
                                        "ANCESTRAL_ORIGIN",
                                        "HOROSCOPE_MATCH",
                                        "SPEAK_URDU",
                                        "PHONE_NUMBER_OWNER",
                                        "PHONE_OWNER_NAME",
                                        "MOBILE_NUMBER_OWNER",
                                        "MOBILE_OWNER_NAME",
                                        "RASHI",
                                        "SUNSIGN",
                                        "TIME_TO_CALL_START",
                                        "TIME_TO_CALL_END",
                                        "MOB_STATUS",
                                        "LANDL_STATUS",
                                        "PHONE_FLAG",
                                        "PHONE_WITH_STD",
                                        "CRM_TEAM",
                                        "activatedKey",
                                        "PROFILE_HANDLER_NAME",
                                        "GOING_ABROAD",
                                        "OPEN_TO_PET",
                                        "HAVE_CAR",
                                        "OWN_HOUSE",
                                        "COMPANY_NAME",
                                        "HAVE_JCONTACT",
                                        "HAVE_JEDUCATION",
                                        "JSARCHIVED",
                                        "SEC_SOURCE",
                                        "SERIOUSNESS_COUNT",
                                        "ID_PROOF_TYP",
                                        "ID_PROOF_NO",
                                        "VERIFY_ACTIVATED_DT",
                                    );
        
    public static $arrJProfile_EducationColumns = array(
                                        "PROFILEID",
                                        "PG_COLLEGE",
                                        "PG_DEGREE",
                                        "UG_DEGREE",
                                        "OTHER_UG_DEGREE",
                                        "OTHER_PG_DEGREE",
                                        "SCHOOL",
                                        "COLLEGE",
                                        "OTHER_UG_COLLEGE",
                                        "OTHER_PG_COLLEGE",
                                    );
    
    public static $arrNativePlaceColumns = array(
                                        "PROFILEID",
                                        "NATIVE_COUNTRY",
                                        "NATIVE_STATE",
                                        "NATIVE_CITY",
                                    );
    
    public static $arrAstroDetailsColumns = array(
                                        "PROFILEID",
                                        "TYPE",
                                        "DATE",
                                        "HOROSCOPE_SCREENING",
                                        "CITY_BIRTH",
                                        "DTOFBIRTH",
                                        "BTIME",
                                        "SHOW_HOROSCOPE",
                                        "COUNTRY_BIRTH",
                                        "PLACE_BIRTH",
                                        "LATITUDE",
                                        "LONGITUDE",
                                        "TIMEZONE",
                                        "DST",
                                        "LAGNA_DEGREES_FULL",
                                        "SUN_DEGREES_FULL",
                                        "MOON_DEGREES_FULL",
                                        "MARS_DEGREES_FULL",
                                        "MERCURY_DEGREES_FULL",
                                        "JUPITER_DEGREES_FULL",
                                        "VENUS_DEGREES_FULL",
                                        "SATURN_DEGREES_FULL",
                                        "RAHU_DEGREES_FULL",
                                        "KETU_DEGREES_FULL",
                                        "MOON_RETRO_COMBUST",
                                        "MARS_RETRO_COMBUST",
                                        "MERCURY_RETRO_COMBUST",
                                        "JUPITER_RETRO_COMBUST",
                                        "VENUS_RETRO_COMBUST",
                                        "SATURN_RETRO_COMBUST",
                                        "VARA",
                                        "MASA",
                                        "HAVE_ASTRO"
                                    );
    
    public static $arrDuplicateFieldsMap = array(
                                        'COUNTRY_BIRTH',
                                        'SHOW_HOROSCOPE',
                                        'AGE',
                                        'MSTATUS',
                                        'RELIGION',
                                        'CASTE',
                                        'COUNTRY_RES',
                                        'CITY_RES',
                                        'MTONGUE',
                                        'INCOME',
                                    );

    public static $arrFSOColumns = array(
                                        'PROFILEID',
					'FSO_EXISTS' 
                                    );
    public static $arrCommonFieldsMap = array(
                                        'PROFILEID',
                                        'DTOFBIRTH',
                                        'CITY_BIRTH',
                                        'BTIME',
    );

      public static $arrJProfileAlertsColumn = array(
                                        'PROFILEID',
                                        'MEMB_CALLS',
                                        'OFFER_CALLS',
                                        'SERV_CALLS_SITE',
                                        'SERV_CALLS_PROF',
                                        'MEMB_MAILS',
                                        'CONTACT_ALERT_MAILS',
                                        'KUNDLI_ALERT_MAILS',
                                        'PHOTO_REQUEST_MAILS',
                                        'NEW_MATCHES_MAILS',
                                        'SERVICE_SMS',
                                        'SERVICE_MMS',
                                        'SERVICE_USSD',
                                        'PROMO_USSD',
                                        'SERVICE_MAILS',
                                        'PROMO_MMS',
        
    );


    public static $arrJProfileContact = array(
                                        "PROFILEID",
                                        "ALT_MOBILE",
                                        "ALT_MOBILE_ISD",
                                        "SHOWALT_MOBILE",
                                        "ALT_MOBILE_OWNER_NAME",
                                        "ALT_MOBILE_NUMBER_OWNER",
                                        "ALT_MESSENGER_ID",
                                        "ALT_MESSENGER_CHANNEL",
                                        "SHOW_ALT_MESSENGER",
                                        "BLACKBERRY",
                                        "LINKEDIN_URL",
                                        "FB_URL",
                                        "CALL_ANONYMOUS",
                                        "SHOWBLACKBERRY",
                                        "SHOWLINKEDIN",
                                        "SHOWFACEBOOK",
                                        "ALT_MOB_STATUS",
                                        "ALT_EMAIL",
                                        "ALT_EMAIL_STATUS",

     );
    public static $arrAUTO_EXPIRY = array(
                                        'PROFILEID',
                                        'TYPE',
                                        'DATE'   
                                    );
    
    public static $arrJHobbyColumns = array(
                                        'PROFILEID',
                                        "HOBBY",
                                        /*"ALLMUSIC",
                                        "ALLBOOK",
                                        "ALLMOVIE",
                                        "ALLSPORTS",
                                        "ALLCUISINE",*/
                                        "FAV_MOVIE",
                                        "FAV_TVSHOW",
                                        "FAV_FOOD",
                                        "FAV_BOOK",
                                        "FAV_VAC_DEST"
                                    );

    public static $arrOldYourInfo = array(
                                   'PROFILEID',
                                   "YOUR_INFO_OLD",
                                   );
    public static $arrAutoExpiry = array(
                                   'PROFILEID',
                                   "AUTO_EXPIRY_DATE",
                                   );
    
    public static $arrProfileFilter = array(
                                    'PROFILEID',
                                    'FILTERID',
                                    'AGE',
                                    'MSTATUS',
                                    'RELIGION',
                                    'CASTE',
                                    'COUNTRY_RES',
                                    'CITY_RES',
                                    'MTONGUE',
                                    'INCOME',
                                    'COUNT',
                                    'HARDSOFT',
                                    );
    public static $arrJpartnerColumns = array('PROFILEID',
                                    'GENDER',
                                    'CHILDREN',
                                    'LAGE',
                                    'HAGE',
                                    'LHEIGHT',
                                    'HHEIGHT',
                                    'HANDICAPPED',
                                    'DATE',
                                    'ALERTS',
                                    'PAGE',
                                    'DPP',
                                    'CASTE_MTONGUE',
                                    'PARTNER_BTYPE',
                                    'PARTNER_CASTE',
                                    'PARTNER_CITYRES',
                                    'PARTNER_COUNTRYRES',
                                    'PARTNER_DIET',
                                    'PARTNER_DRINK',
                                    'PARTNER_ELEVEL_NEW',
                                    'PARTNER_INCOME',
                                    'PARTNER_MANGLIK',
                                    'PARTNER_MSTATUS',
                                    'PARTNER_MTONGUE',
                                    'PARTNER_NRI_COSMO',
                                    'PARTNER_OCC',
                                    'PARTNER_RELATION',
                                    'PARTNER_RES_STATUS',
                                    'PARTNER_SMOKE',
                                    'PARTNER_COMP',
                                    'PARTNER_RELIGION',
                                    'PARTNER_NAKSHATRA',
                                    'NHANDICAPPED',
                                    'LINCOME',
                                    'HINCOME',
                                    'LINCOME_DOL',
                                    'HINCOME_DOL',
                                    'PARTNER_DEGREE',
                                    'HAVEPHOTO',
                                    'HIV',
                                    'HIJAB_MARRIAGE',
                                    'SPEAK_URDU',
                                    'SAMPRADAY',
                                    'ZARATHUSHTRI',
                                    'HOROSCOPE',
                                    'AMRITDHARI',
                                    'CUT_HAIR',
                                    'MATHTHAB',
                                    'WEAR_TURBAN',
                                    'LIVE_PARENTS',
                                    'EDUCATION_GROUPING',
                                    'LAST_ACTIVITY',
                                    'OCCUPATION_GROUPING',
                                    'INDIA_NRI',
                                    'STATE',
                                    'CITY_INDIA',
                                    'MARRIED_WORKING',
                                    'GOING_ABROAD',
                                    'CASTE_GROUP',
                                    'VIEWED',
                                    'MAPPED_TO_DPP');


    public static $arrAadharVerifyColumns = array(
                                        'PROFILEID',
                                        'AADHAR_NO',
                                        'REQUEST_ID',
                                        'VERIFY_STATUS',
                                    );
    public static $prefixMapping = array(
					"JPROFILE"=>"bi",
					"ProfileEducation"=>"ei",
					"ProfileAstro"=>"ai",
					"ProfileNativePlace"=>"ni",
					"JHOBBYCacheLib"=>"hi",
					"ProfileAUTO_EXPIRY"=>"xi",
					"ProfileFilter"=>"fl",
					"ProfileYourInfoOld"=>"yo",
					"JprofileAlertsCache"=>"al",
					"ProfileContact"=>"ci",
					"ProfileFSO"=>"fs",
					"aadharVerification"=>"av",
                                        "Jpartner"=>"di", // nameofuser.nu,profileVerification.pv,apcallhistoryinfo.ch,coverphoto.co,additionalreligioninfo.ri,rel_info_muslim.rim,rel_info_sikh.ris,rel_info_chirstian.ric,rel_info_parsi.rip
					/*""=>"",
					""=>"",*/
				);
    public static $storeKeys = array(
                                        "JPROFILE"=>"arrJProfileColumns",
                                        "ProfileEducation"=>"arrJProfile_EducationColumns",
                                        "ProfileNativePlace"=>"arrNativePlaceColumns",
                                        "ProfileAstro"=>"arrAstroDetailsColumns",
                                        "ProfileContact"=>"arrJProfileContact",
                                        "JHOBBYCacheLib"=>"arrJHobbyColumns",
                                        "JprofileAlertsCache"=>"arrJProfileAlertsColumn",
                                        "ProfileYourInfoOld"=>"arrOldYourInfo",
                                        "ProfileAUTO_EXPIRY"=>"arrAutoExpiry",
                                        "ProfileFilter"=>"arrProfileFilter",
					"ProfileFSO"=>"arrFSOColumns"
                                );
    public static function getKeyData($storeName){
            
    }
}
?>
