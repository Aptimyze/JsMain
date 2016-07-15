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
    const ENABLE_PROFILE_CACHE_LOGS = true;
    const LOG_LEVEL = 3;
    const CACHE_CRITERIA = 'PROFILEID';
    const ALL_FIELDS_SYM = '*';
    const PROFILE_CACHE_PREFIX = '_p_';
    const CACHE_HASH_KEY = 'PROFILEID';
    const ACTIVATED_KEY = 'activatedKey';
    const PROFILE_LOG_PATH = 'ProfileCache';
    //Acceptable or Relevant Fields which will be allowed as a subkeys in Hash
    //public static $arrHashSubKeys = array('USERNAME', 'AGE', 'CITY_RES', 'COUNTRY_RES', 'CAST', 'RELIGION');

    public static $arrHashSubKeys = array(
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
}
?>