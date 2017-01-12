<?php
/**
 * Description of LoggingEnums
 * Class which contain all the defined constants and enums
 * related to LoggingManager
 * @package     cache
 * @author      Kunal Verma
 * @created     14th July 2016
 */

class LoggingEnums
{   
    const MASTER_FLAG = true;
    const LOG_LEVEL = 0;
    const LOG_INFO = 2;
    const LOG_DEBUG = 1;
    const LOG_ERROR = 0;
    const SERVER_FLAG = false;
    // 1 for Enabling config of modules, 0 for not
    const CONFIG_ON = true;
    // Info For VA, on for referer in logs
    const CONFIG_INFO_VA = true;
    // 1 for logging all modules in same file, 0 different
    const LOG_TOGETHER = false;
    const LOG_TRACE = false;
    // module names
    const JSADMIN = 'jsadmin';
    const COMMONLOG = 'common';
    const EX500 = '500';
    const EX404 = '404';
    // name of channels returned
    const P = 'P';
    const A = 'A';
    const I = 'I';
    const MS = 'MS';
    const OMS = 'OMS';
    public static $Referer_ignore = array('jeevansathi.com', 'google.com', 'google.co.in', 'yahoo.com', 'yahoo.co.in', 'rediffmail.com', 'rediff.com', 'bing.com', 'outlook.com', 'infoedge.com');
    // exceptions type
    const MYSQL_EXCEPTION = "MYSQL";
    const PDO_EXCEPTION = "PDO";
    const REDIS_EXCEPTION = "REDIS";
    const AMQP_EXCEPTION = "AMQP";
    const UNKNOWN_EXCEPTION = "UNKNOWN";
    const EXCEPTION = "EXCEPTION";
    // logArray parameters
    const CLIENT_IP = "clientIP";
    const STATUS_CODE = "statusCode";
    const API_VERSION = "apiVersion";
    const MESSAGE = "LogMessage";
    const CHANNEL_NAME = "channelName";
    const ACTION_NAME = "actionName";
    const MODULE_NAME = "moduleName";
    const TYPE_OF_ERROR = "typeOfError";
    const LOG_ID = "requestId";
    const LOG_EXCEPTION = "exception";
    const TIME = "time";
    const LOG_TYPE = "logType";
    const UNIQUE_REQUEST_SUB_ID = "requestSubId";
    // config array keys
    const LOGGING = 'logging';
    const LEVEL = 'level';
    const DIRECTORY = 'directory';
    const STACKTRACE = 'stackTrace';
    const SERVER_PARAM = 'serverParam';
    const RIFT = 'REQUEST_ID_FOR_TRACKING';
    const RAJX = 'RID_AJAX';
    const AJXRSI = 'AJAX_REQUEST_SUB_ID';
    const SEO = 'seo';
    const MYJS = 'myjs';
    const HOMEPAGE = 'homepage';
    const STATIC_MODULE = 'static';
    const REQUEST_URI = 'REQUEST_URI';
    const DOMAIN = 'Domain';
    const REFERER = 'Referer';
    const LOG_REFERER = 'Log_Referer';
    const LOG_VA_MODULE = 'VA_Info';
    const JPC = 'Jprofile_Contact';

    public static $MappingNames = array(
        // JSC module names
        0 => 'Chat',
        1 => 'Notification',
        2 => 'SEO',
        3 => 'CRM',
        4 => 'Membership',
        5 => 'SuccessStory',
        6 => 'ContactUs',
        // JSI module names
        7 => 'Api',
        8 => 'Sms',
        9 => 'Homepage',
        10 => 'Myjs',
        11 => 'Profile',
        12 => 'Contact Engine',
        13 => 'Login',
        // JSM module names
        14 => 'Listing',
        15 => 'Search',
        16 => 'Registration',
        17 => 'Picture',
        18 => 'Screening',
        // others
        19 => 'Static',
        20 => '404',
        21 => 'Others'
        );

    public static $ModuleMapping = array(

        "inbox" => 14,
        "404_uploads" => 20,
        "chat_api" => 0,
        "404" => 20,
        "profile" => 11,
        "profile_api" => 11,
        "404_profile" => 20,
        "api" => 7,
        "ShortURL" => 8,
        "homepage" => 9,
        "myjs_api" => 10,
        "notification_api" => 1,
        "search_api" => 15,
        "ProfileCache" => 11,
        "api_api" => 7,
        "inbox_api" => 14,
        "register_api" => 16,
        "contacts_api" => 12,
        "social_api" => 17,
        "404_search" => 20,
        "search" => 15,
        "static" => 19,
        "social" => 17,
        "common_api" => 7,
        "404_min" => 20,
        "AutoLogin" => 13,
        "404_myjs" => 20,
        "404_static" => 20,
        "404_e" => 20,
        "404_api" => 20,
        "404_wp-login.php" => 20,
        "404_social" => 20,
        "register" => 16,
        "404_matrimonials" => 20,
        "404_browserconfig.xml" => 20,
        "404_inbox" => 20,
        "404_images" => 20,
        "myjs" => 10,
        "404_autodiscover" => 20,
        "404_register" => 20,
        "404_js" => 20,
        "contacts" => 12,
        "e" => 13,
        "crmAllocation" => 3,
        "404_undefined" => 20,
        "membership_api" => 4,
        "membership" => 4,
        "autologin" => 13,
        "404_jsadmin" => 20,
        "photoScreening" => 18,
        "seo" => 2,
        "404_null" => 20,
        "404_P" => 20,
        "404_membership" => 20,
        "404_Most%20trusted%20Indian%20matrimonials%20website.%20Lakhs%20of%20verified%20matrimony%20profiles.%20Search%20by%20caste%20and%20community.%20Register%20now%20for%20FREE%20at%20Jeevansathi.com" => 20,
        "404_jsmb" => 20,
        "crm" => 3,
        "404_successStory" => 20,
        "404_includes" => 20,
        "404_crm" => 20,
        "404_jshelp" => 20,
        "404_https:" => 20,
        "404_" => 20,
        "commoninterface" => 21,
        "404_wan_redirect_status?www.jeevansathi.com" => 20,
        "404_successstory" => 20,
        "404_contactus" => 20,
        "404_adServe" => 20,
        "404_faq" => 20,
        "successStory" => 5,
        );
}