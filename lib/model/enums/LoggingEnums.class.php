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
    const LOG_LEVEL = 2;
    const LOG_INFO = 2;
    const LOG_DEBUG = 1;
    const LOG_ERROR = 0;
    // 1 for logging all modules, 0 for not
    const LOG_ALL = 1;
    // 1 for logging all modules in same file, 0 different
    const LOG_TOGETHER = 0;
    // module names
    const JSA = 'jsadmin';
    const COMMONLOG = 'common';
    const EX500OR404 = '500-404';
    // name of channels returned
    const P = 'P';
    const A = 'A';
    const I = 'I';
    const MS = 'MS';
    // exceptions type
    const MYSQL_EXCEPTION = "MYSQL";
    const PDO_EXCEPTION = "PDO";
    const REDIS_EXCEPTION = "REDIS";
    const AMQP_EXCEPTION = "AMQP";
    const UNKNOWN_EXCEPTION = "UNKNOWN";
}