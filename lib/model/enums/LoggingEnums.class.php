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
    const LOG_LEVEL = 2;
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
}
