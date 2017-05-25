<?php
/**
 * Description of PictureNewCacheConstants
 * Class which contain all the defined constants and enums
 *
 * @package     cache
 * @author      Esha Jain
 * @created     17th March 2017
 */

class PictureNewCacheConstants
{
    const ENABLE_CACHE = true;
    const CONSUME_CACHE = true;
    const ENABLE_CACHE_LOGS = true;
    const LOG_LEVEL = 0;
    const CACHE_CRITERIA = 'PROFILEID';
    const ALL_FIELDS_SYM = '*';
    const PROFILE_CACHE_PREFIX = 'PIC_NEW';
    const CACHE_HASH_KEY = 'PROFILEID';
    const ACTIVATED_KEY = 'activatedKey';
    const PROFILE_LOG_PATH = 'ProfileCache';
    const COMMAND_LINE = 'cli';
    const CACHE_EXPIRE_TIME = 604800;
    const CACHE_MAX_ATTEMPT_COUNT = 3;
    const NOT_FILLED = "-NF-";
    const NO_PHOTO = "NP";
    const DUPLICATE_FIELD_DELIMITER = "-d-";
    public static $POSSIBLE_CRITERIA = array("PROFILEID");
    public static $arrHashSubKeys = array(
                                        "YOUR_INFO_OLD",

                                    );
    
    public static $arrJProfileColumns = array(
                                        "PROFILEID",
                                    );
        
    public static $arrDuplicateFieldsMap = array(
                                        //'BTIME',
                                        'COUNTRY_BIRTH',
                                        'SHOW_HOROSCOPE',
                                    );

    public static $arrFSOColumns = array(
                                        'PROFILEID'    
                                    );
    public static $arrCommonFieldsMap = array(
                                        'PROFILEID',
    );
}
?>
