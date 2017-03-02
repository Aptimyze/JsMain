<?php
/**
 * 
 * Class ProfileMemcache
 * <p>
 * This class is used to set and get values from JsMemcache for a profileid
 * </p>
 * File ProfileMemcache 
 *
 * This class will be used to handle JsMemcache related operations for Contact Engine
 *
 * <p>
 * Key for JsMemcache : Profileid of logged in user
 * 
 * <br />
 * <br />
 * Following is the list of variables that are currently being stored in JsMemcache:
 * <ol>
 * <li>ACC_BY_ME</li>
 * <li>ACC_ME</li>
 * <li>DEC_BY_ME</li>
 * <li>DEC_ME</li>
 * <li>TODAY_INI_BY_ME</li>
 * <li>WEEK_INI_BY_ME</li>
 * <li>MONTH_INI_BY_ME</li>
 * <li>TOTAL_CONTACTS_MADE</li>
 * <li>NOT_REP</li>
 * <li>OPEN_CONTACTS</li>
 * <li>CANCELLED_EOI</li>
 * <li>FILTERED</li>
 * <li>AWAITING_RESPONSE</li>
 * </ol>
 * Below is the demonstration on how to use this class
 * <code>
 * <br />
 * $contactsMemcacheObj = ProfileMemcache::getInstance($profileid);
 * <br />
 * $contactsMemcacheObj->setACC_BY_ME(1); 
 * <br />
 * $contactsMemcacheObj->setDEC_BY_ME(-1); 
 * <br />
 * $variable = $contactsMemcacheObj->getACC_ME();
 * <br />
 * $contactsMemcacheObj->updateMemcacheData(); 
 * <br />
 * </code>
 * </p>
 * @package jeevansathi
 * @subpackage contacts
 * @author Esha Jain
 */
class ProfileMemcache
{
    
    /**#@+
     * @access private 
     */
    /**
     *
     * This holds JsMemcache object for the profileid supplied
     *
     * @var Object
     */
    private $_memcache;
    
    /**
     *
     * This holds the profileid of the loggedin user for which the JsMemcache Object is requested
     *
     * @var integer
     */
    private $_profileid;
    
    /**
     * 
     * This holds updated fields for a profile, with their current value. 
     *
     * @var array
     */
    private $_updatedFields;
    
    /**
     *
     * This holds the count of JsMemcache variable ACC_BY_ME
     *
     * @var integer
     */
    private $ACC_BY_ME;
    private $MATCHALERT;
    private $MATCHALERT_TOTAL;
    private $VISITOR_ALERT;
    private $VISITORS_ALL;
    private $CHAT_REQUEST;
    private $BOOKMARK;
    private $SAVED_SEARCH;
    
    /**
     *
     * This holds the count of JsMemcache variable ACC_ME
     *
     * @var integer
     */
    private $ACC_ME;
    private $ACC_ME_NEW;
    /**
     *
     * This holds the count of JsMemcache variable DEC_BY_ME
     *
     * @var integer
     */
    private $DEC_BY_ME;
    
    /**
     *
     * This holds the count of JsMemcache variable DEC_ME
     *
     * @var integer
     */
    private $DEC_ME;
    private $DEC_ME_NEW;
    /**
     *
     * This holds the count of JsMemcache variable TODAY_INI_BY_ME
     *
     * @var integer
     */
    private $TODAY_INI_BY_ME;
    
    /**
     *
     * This holds the count of JsMemcache variable WEEK_INI_BY_ME
     *
     * @var integer
     */
    private $WEEK_INI_BY_ME;
    
    /**
     *
     * This holds the count of JsMemcache variable MONTH_INI_BY_ME
     *
     * @var integer
     */
    private $MONTH_INI_BY_ME;
    
    /**
     *
     * This holds the count of JsMemcache variable TOTAL_CONTACTS_MADE
     *
     * @var integer
     */
    private $TOTAL_CONTACTS_MADE;
    
    private $CONTACTS_MADE_AFTER_DUP;
    /**
     *
     * This holds the count of JsMemcache variable NOT_REP
     *
     * @var integer
     */
    private $NOT_REP;
    
    /**
     *
     * This holds the count of JsMemcache variable OPEN_CONTACTS
     *
     * @var integer
     */
    private $OPEN_CONTACTS;
    
    /**
     *
     * This holds the count of JsMemcache variable CANCELLED_EOI
     *
     * @var integer
     */
    private $CANCELLED_EOI;
    
    private $GROUPS_UPDATED;
    
    /**
     * This holds the count of EOI received which are filtered
     *
     * @var integer
     */
    private $FILTERED;
    private $FILTERED_NEW;
    
    /**
     * This holds the count of EOI received which are not filtered
     *
     * @var integer
     */
    private $AWAITING_RESPONSE;
    private $AWAITING_RESPONSE_NEW;
    private $PHOTO_REQUEST;
    private $PHOTO_REQUEST_NEW;
    private $PHOTO_REQUEST_BY_ME;
    private $MESSAGE;
    private $MESSAGE_NEW;
    private $MESSAGE_ALL;
    private $HOROSCOPE;
    private $INTRO_CALLS;
    private $INTRO_CALLS_COMPLETE;
    private $HOROSCOPE_NEW;
    private $HOROSCOPE_REQUEST_BY_ME;
    //private $HOROSCOPE_NEW;
    private $contactedProfile;
    private $INTEREST_EXPIRING;
    
    
    /**
     * This holds the count of counts of profile Added new to Jeevansathi
     *
     * @var integer
     */
    private $JUST_JOINED_MATCHES;
    private $JUST_JOINED_MATCHES_NEW;
     /**
     * This holds the count of counts of profile Viewed by a user in Jeevansathi
     *
     * @var integer
     */
    private $CONTACTS_VIEWED;
    private $PEOPLE_WHO_VIEWED_MY_CONTACTS;
    //private $CONTACTS_VIEWED_NEW;
    
    /**
     * This holds the profiles Skipped for a user in Jeevansathi
     *
     * @var integer
     */
    private $CONTACTED_BY_ME;
    private $CONTACTED_ME;
    private $IGNORED;
    
    
    /**
     *
     * This holds instance of the ProfileMemcache class for the current profileid
     *
     * @var Object
     */
    private static $_instance;
    /**
     * 
     * Constructor for instantiating object of ProfileMemcache class
     * 
     * <p>
     * It instantiates the ProfileMemcache object for the profileid requested. It performs the following functions:
     * <ol>
     * <li>Get instance of JsMemcache Object for the profileid.</li>
     * <li>If found, set member variables' value from JsMemcache Object.</li>
     * <li>If not found, <BR>
     * 1. Get the Contacts Count.<BR>
     * 2. If contacts count does not exist, initialize member variables' value.<BR>
     * </li>
     * </ol>
     * </p>
     * 
     * @access private
     * @param $profileid Profileid (Integer only)
     * @throws Exception
     */
    private function __construct($profileid)
    {
        
        if (true === is_numeric($profileid)) {
            $this->_profileid     = $profileid;
            $this->_updatedFields = null;
            //JsMemcache::getInstance()->delete($this->_profileid);
            $this->_memcache      = unserialize(JsMemcache::getInstance()->get($this->_getProfileId()));
            $this->_initializeMemcacheData();
            
        } else {
            jsException::log("ProfileId is not numeric. Supplied profileid = $profileid");
        }
    }
    
    /**
     * 
     * Initialize member variables' value to their defaults.
     * 
     * <p>
     * This function initializes the member variables' vale to their default values.
     * </p>
     * 
     * @access private
     */
    private function _initializeMemcacheData()
    {
        $this->GROUPS_UPDATED          = $this->_memcache["GROUPS_UPDATED"] ? $this->_memcache["GROUPS_UPDATED"] : 1;
        $this->ACC_BY_ME               = $this->_memcache["ACC_BY_ME"] ? $this->_memcache["ACC_BY_ME"] : 0;
        $this->ACC_ME                  = $this->_memcache["ACC_ME"] ? $this->_memcache["ACC_ME"] : 0;
        $this->ACC_ME_NEW              = $this->_memcache["ACC_ME_NEW"] ? $this->_memcache["ACC_ME_NEW"] : 0;
        $this->DEC_BY_ME               = $this->_memcache["DEC_BY_ME"] ? $this->_memcache["DEC_BY_ME"] : 0;
        $this->DEC_ME                  = $this->_memcache["DEC_ME"] ? $this->_memcache["DEC_ME"] : 0;
        $this->DEC_ME_NEW              = $this->_memcache["DEC_ME_NEW"] ? $this->_memcache["DEC_ME_NEW"] : 0;
        $this->TODAY_INI_BY_ME         = $this->_memcache["TODAY_INI_BY_ME"] ? $this->_memcache["TODAY_INI_BY_ME"] : 0;
        $this->WEEK_INI_BY_ME          = $this->_memcache["WEEK_INI_BY_ME"] ? $this->_memcache["WEEK_INI_BY_ME"] : 0;
        $this->MONTH_INI_BY_ME         = $this->_memcache["MONTH_INI_BY_ME"] ? $this->_memcache["MONTH_INI_BY_ME"] : 0;
        $this->TOTAL_CONTACTS_MADE     = $this->_memcache["TOTAL_CONTACTS_MADE"] ? $this->_memcache["TOTAL_CONTACTS_MADE"] : 0;
        $this->CONTACTS_MADE_AFTER_DUP = $this->_memcache["CONTACTS_MADE_AFTER_DUP"] ? $this->_memcache["CONTACTS_MADE_AFTER_DUP"] : 0;
        $this->NOT_REP                 = $this->_memcache["NOT_REP"] ? $this->_memcache["NOT_REP"] : 0;
        $this->OPEN_CONTACTS           = $this->_memcache["OPEN_CONTACTS"] ? $this->_memcache["OPEN_CONTACTS"] : 0;
        $this->CANCELLED_EOI           = $this->_memcache["CANCELLED_EOI"] ? $this->_memcache["CANCELLED_EOI"] : 0;
        $this->AWAITING_RESPONSE       = $this->_memcache["AWAITING_RESPONSE"] ? $this->_memcache["AWAITING_RESPONSE"] : 0;
        $this->AWAITING_RESPONSE_NEW   = $this->_memcache["AWAITING_RESPONSE_NEW"] ? $this->_memcache["AWAITING_RESPONSE_NEW"] : 0;
        $this->FILTERED                = $this->_memcache["FILTERED"] ? $this->_memcache["FILTERED"] : 0;
        $this->FILTERED_NEW            = $this->_memcache["FILTERED_NEW"] ? $this->_memcache["FILTERED_NEW"] : 0;
        $this->HOROSCOPE               = $this->_memcache["HOROSCOPE"] ? $this->_memcache["HOROSCOPE"] : 0;
        $this->HOROSCOPE_REQUEST_BY_ME           = $this->_memcache["HOROSCOPE_REQUEST_BY_ME"] ? $this->_memcache["HOROSCOPE_REQUEST_BY_ME"] : 0;
        $this->HOROSCOPE_NEW       = $this->_memcache["HOROSCOPE_NEW"] ? $this->_memcache["HOROSCOPE_NEW"] : 0;
        $this->INTRO_CALLS               = $this->_memcache["INTRO_CALLS"] ? $this->_memcache["INTRO_CALLS"] : 0;
        $this->INTRO_CALLS_COMPLETE      = $this->_memcache["INTRO_CALLS_COMPLETE"] ? $this->_memcache["INTRO_CALLS_COMPLETE"] : 0;
        $this->PHOTO_REQUEST           = $this->_memcache["PHOTO_REQUEST"] ? $this->_memcache["PHOTO_REQUEST"] : 0;
        $this->PHOTO_REQUEST_NEW       = $this->_memcache["PHOTO_REQUEST_NEW"] ? $this->_memcache["PHOTO_REQUEST_NEW"] : 0;
        $this->PHOTO_REQUEST_BY_ME          = $this->_memcache["PHOTO_REQUEST_BY_ME"] ? $this->_memcache["PHOTO_REQUEST_BY_ME"] : 0;
        $this->MESSAGE                 = $this->_memcache["MESSAGE"] ? $this->_memcache["MESSAGE"] : 0;
        $this->MESSAGE_NEW             = $this->_memcache["MESSAGE_NEW"] ? $this->_memcache["MESSAGE_NEW"] : 0;
        $this->MESSAGE_ALL                 = $this->_memcache["MESSAGE_ALL"] ? $this->_memcache["MESSAGE_ALL"] : 0;
        $this->MATCHALERT              = $this->_memcache["MATCHALERT"] ? $this->_memcache["MATCHALERT"] : 0;
        $this->MATCHALERT_TOTAL              = $this->_memcache["MATCHALERT_TOTAL"] ? $this->_memcache["MATCHALERT_TOTAL"] : 0;
        $this->VISITOR_ALERT           = $this->_memcache["VISITOR_ALERT"] ? $this->_memcache["VISITOR_ALERT"] : 0;
        $this->VISITORS_ALL           = $this->_memcache["VISITORS_ALL"] ? $this->_memcache["VISITORS_ALL"] : 0;
        $this->CHAT_REQUEST            = $this->_memcache["CHAT_REQUEST"] ? $this->_memcache["CHAT_REQUEST"] : 0;
        $this->BOOKMARK                = $this->_memcache["BOOKMARK"] ? $this->_memcache["BOOKMARK"] : 0;
        $this->SAVED_SEARCH                = $this->_memcache["SAVED_SEARCH"] ? $this->_memcache["SAVED_SEARCH"] : 0;
        
        $this->JUST_JOINED_MATCHES            = $this->_memcache["JUST_JOINED_MATCHES"] ? $this->_memcache["JUST_JOINED_MATCHES"] : 0;
        $this->JUST_JOINED_MATCHES_NEW                = $this->_memcache["JUST_JOINED_MATCHES_NEW"] ? $this->_memcache["JUST_JOINED_MATCHES_NEW"] : 0;
        $this->CONTACTS_VIEWED            = $this->_memcache["CONTACTS_VIEWED"] ? $this->_memcache["CONTACTS_VIEWED"] : 0;
        $this->PEOPLE_WHO_VIEWED_MY_CONTACTS            = $this->_memcache["PEOPLE_WHO_VIEWED_MY_CONTACTS"] ? $this->_memcache["PEOPLE_WHO_VIEWED_MY_CONTACTS"] : 0;
         $this->CONTACTED_BY_ME            = $this->_memcache["CONTACTED_BY_ME"] ? $this->_memcache["CONTACTED_BY_ME"] : "";
        $this->CONTACTED_ME            = $this->_memcache["CONTACTED_ME"] ? $this->_memcache["CONTACTED_ME"] : "";
        $this->INTEREST_ARCHIVED           = $this->_memcache["INTEREST_ARCHIVED"] ? $this->_memcache["INTEREST_ARCHIVED"] : "";
        $this->IGNORED           = $this->_memcache["IGNORED"] ? $this->_memcache["IGNORED"] : "";
        $this->INTEREST_EXPIRING                = $this->_memcache["INTEREST_EXPIRING"] ? $this->_memcache["INTEREST_EXPIRING"] : 0;
    
    
    }
    
    /**
     * 
     * Get the Profile id.
     * 
     * <p>
     * This function returns the profileid of the logged in user for which JsMemcache Data was requested. 
     * It gets the value of {@link $_profileid}
     * </p>
     * 
     * @access private
     * @return integer
     */
    private function _getProfileId()
    {
        
        return $this->_profileid;
        
    }
    
    /**
     * 
     * Set Data in JsMemcache object for current profileid
     * 
     * <p>
     * This function sets the data for JsMemcache Object. Returns true on success, false otherwise.
     * </p>
     * 
     * @param $data_variables, Must be serialized data only.
     * @access private
     * @return bool
     */
    private function _setMemcacheData($data_variables)
    {
        // Get TTL of Key
        $lifetime = JsMemcache::getInstance()->ttl($this->_getProfileId());
        if($lifetime < 0)
        {
            // key doesnot exist or expire time is not defined
            $lifetime = 1800;
        }
        $ret_val = JsMemcache::getInstance()->set($this->_getProfileId(), $data_variables, $lifetime);
        return $ret_val;
    }
    
    /**
     * 
     * Update JsMemcache Variables.
     * 
     * <p>
     * This function updates the variables for current profileid's JsMemcache Object.
     * </p>
     * 
     * @param $data_variables, Must be serialized only.
     * @access private
     * @throws Exception
     */
    private function _updateMemcacheVariables($data_variables)
    {
        $this->_setMemcacheData($data_variables);
    }
    
    /**
     * 
     * Get the Instance of ProfileMemcache class.
     * 
     * <p>
     * This function returns the instance of ProfileMemcache class for the profileid supplied.
     * </p>
     *
     * @param $profileid
     * @access public
     * @return Object
     * @throws Exception
     */
    public static function getInstance($profileid)
    {
        if (isset(self::$_instance[$profileid])) {
            if ($profileid && (self::$_instance[$profileid]->_getProfileId() !== $profileid)) {
                self::$_instance[$profileid] = new ProfileMemcache($profileid);
            }
        } else {
            self::$_instance[$profileid] = new ProfileMemcache($profileid);
        }
        
        if (isset(self::$_instance[$profileid])) {
            return self::$_instance[$profileid];
        } else {
            jsException::log("Object cannot be instantiated.");
        }
        
    }
    
    /**
     * 
     * Clears the Instance of ProfileMemcache class.
     * 
     * <p>
     * This function clears the current instance of ProfileMemcache class.
     * </p>
     *
     * @access public
     * @throws Exception
     */
    public static function clearInstance($profileid)
    {
        if (isset(self::$_instance[$profileid])) {
            JsMemcache::getInstance()->delete(self::$_instance[$profileid]->_getProfileId());
            unset(self::$_instance[$profileid]);
        } else {
            //      jsException::log("Please set the instance first by calling \"getInstance\" method.");
        }
    }
    
    /**
     * 
     * Get count of ACC_BY_ME.
     * 
     * <p>
     * This function returns the count of ACC_BY_ME JsMemcache Object variable. If set, returns the same. Otherwise 0.
     * </p>
     * 
     * @access public
     * @return integer
     */
    public function getACC_BY_ME()
    {
        
        return $this->ACC_BY_ME ? $this->ACC_BY_ME : 0;
        
    }
    
    /**
     * 
     * Set count of ACC_BY_ME
     * 
     * <p>
     * This function incrementally sets the member variable count to the specified $current value.
     * </p>
     * 
     * @access public
     * @param $current integer
     */
    public function setACC_BY_ME($current = 0)
    {
        $this->ACC_BY_ME = $current;
    }
    
    public function getCHAT_REQUEST()
    {
        
        return $this->CHAT_REQUEST ? $this->CHAT_REQUEST : 0;
        
    }
    
    public function setCHAT_REQUEST($current = 0)
    {
        $this->CHAT_REQUEST = $current;
    }
    public function getBOOKMARK()
    {
        
        return $this->BOOKMARK ? $this->BOOKMARK : 0;
        
    }
    
    public function setBOOKMARK($current = 0)
    {
        $this->BOOKMARK = $current;
    }
    public function getSAVED_SEARCH()
    {
        
        return $this->SAVED_SEARCH ? $this->SAVED_SEARCH : 0;
        
    }
    
    public function setSAVED_SEARCH($current = 0)
    {
        $this->SAVED_SEARCH = $current;
    }
    /**
     * 
     * Get count of ACC_ME. 
     * 
     * <p>
     * This function returns the count of ACC_ME JsMemcache Object variable. If set, returns the same. Otherwise 0.
     * </p>
     * 
     * @access public
     * @return integer
     */
    public function getACC_ME()
    {
        
        return $this->ACC_ME ? $this->ACC_ME : 0;
        
    }
    
    public function getACC_ME_NEW()
    {
        
        return $this->ACC_ME_NEW ? $this->ACC_ME_NEW : 0;
        
    }
    /**
     * 
     * Set count of ACC_ME
     * 
     * <p>
     * This function incrementally sets the member variable count to the specified $current value.
     * </p>
     * 
     * @access public
     * @param $current integer
     */
    public function setACC_ME($current = 0)
    {
        $this->ACC_ME = $current;
    }
    public function setACC_ME_NEW($current = 0)
    {
        $this->ACC_ME_NEW = $current;
    }
    
    /**
     * 
     * Get count of DEC_BY_ME
     * 
     * <p>
     * This function returns the count of DEC_BY_ME JsMemcache Object variable. If set, returns the same. Otherwise 0.
     * </p>
     * 
     * @access public
     * @return integer
     */
    public function getDEC_BY_ME()
    {
        
        return $this->DEC_BY_ME ? $this->DEC_BY_ME : 0;
        
    }
    
    /**
     * 
     * Set count of DEC_BY_ME
     * 
     * <p>
     * This function incrementally sets the member variable count to the specified $current value.
     * </p>
     * 
     * @access public
     * @param $current integer
     */
    public function setDEC_BY_ME($current = 0)
    {
        $this->DEC_BY_ME = $current;
        
    }
    
    /**
     * 
     * Get count of DEC_ME
     * 
     * <p>
     * This function returns the count of DEC_ME JsMemcache Object variable. If set, returns the same. Otherwise 0.
     * </p>
     * 
     * @access public
     * @return integer
     */
    public function getDEC_ME()
    {
        
        return $this->DEC_ME ? $this->DEC_ME : 0;
        
    }
    public function getDEC_ME_NEW()
    {
        
        return $this->DEC_ME_NEW ? $this->DEC_ME_NEW : 0;
        
    }
    /**
     * 
     * Set count of DEC_ME
     * 
     * <p>
     * This function incrementally sets the member variable count to the specified $current value.
     * </p>
     * 
     * @access public
     * @param $current integer
     */
    public function setDEC_ME($current = 0)
    {
        $this->DEC_ME = $current;
        
    }
    public function setDEC_ME_NEW($current = 0)
    {
        $this->DEC_ME_NEW = $current;
        
    }
    public function getMESSAGE()
    {
        
        return $this->MESSAGE ? $this->MESSAGE : 0;
        
    }
    public function setMESSAGE($current = 0)
    {
        $this->MESSAGE = $current;
        
    }
    
    public function getMESSAGE_NEW()
    {
        
        return $this->MESSAGE_NEW ? $this->MESSAGE_NEW : 0;
        
    }
    public function setMESSAGE_NEW($current = 0)
    {
        $this->MESSAGE_NEW = $current;
        
    }
    public function getHOROSCOPE()
    {
        
        return $this->HOROSCOPE ? $this->HOROSCOPE : 0;
        
    }
    public function setHOROSCOPE($current = 0)
    {
        $this->HOROSCOPE = $current;
        
    }
    public function getINTRO_CALLS()
    {
        
        return $this->INTRO_CALLS ? $this->INTRO_CALLS : 0;
        
    }
    public function setINTRO_CALLS($current = 0)
    {
        $this->INTRO_CALLS = $current;
        
    }
    public function getINTRO_CALLS_COMPLETE()
    {
        
        return $this->INTRO_CALLS_COMPLETE ? $this->INTRO_CALLS_COMPLETE : 0;
        
    }
    public function setINTRO_CALLS_COMPLETE($current = 0)
    {
        $this->INTRO_CALLS_COMPLETE = $current;
        
    }
    public function getHOROSCOPE_REQUEST_BY_ME()
    {
        
        return $this->HOROSCOPE_REQUEST_BY_ME ? $this->HOROSCOPE_REQUEST_BY_ME : 0;
        
    }
    public function setHOROSCOPE_REQUEST_BY_ME($current = 0)
    {
        $this->HOROSCOPE_REQUEST_BY_ME = $current;
        
    }
    
    /*public function getHOROSCOPE_NEW()
    {
        
        return $this->HOROSCOPE_NEW ? $this->HOROSCOPE_NEW : 0;
        
    }
    public function setHOROSCOPE_NEW($current = 0)
    {
        $this->HOROSCOPE_NEW = $current;
        
    }*/
    
    public function getPHOTO_REQUEST()
    {
        
        return $this->PHOTO_REQUEST ? $this->PHOTO_REQUEST : 0;
        
    }
    public function setPHOTO_REQUEST($current = 0)
    {
        $this->PHOTO_REQUEST = $current;
        
    }

    public function getINTEREST_ARCHIVED()
    {
        return $this->INTEREST_ARCHIVED ? $this->INTEREST_ARCHIVED : 0;
    }
    public function setINTEREST_ARCHIVED($current = 0)
    {
        $this->INTEREST_ARCHIVED = $current;
    }


    public function getPHOTO_REQUEST_BY_ME()
    {
        
        return $this->PHOTO_REQUEST_BY_ME ? $this->PHOTO_REQUEST_BY_ME : 0;
        
    }
    public function setPHOTO_REQUEST_BY_ME($current = 0)
    {

        $this->PHOTO_REQUEST_BY_ME = $current;
        
    }

    
    public function getPHOTO_REQUEST_NEW()
    {
        
        return $this->PHOTO_REQUEST_NEW ? $this->PHOTO_REQUEST_NEW : 0;
        
    }
    public function setPHOTO_REQUEST_NEW($current = 0)
    {
        $this->PHOTO_REQUEST_NEW = $current;
        
    }

    public function getHOROSCOPE_NEW()
    {
        
        return $this->HOROSCOPE_NEW ? $this->HOROSCOPE_NEW : 0;
        
    }
    public function setHOROSCOPE_NEW($current = 0)
    {
        $this->HOROSCOPE_NEW = $current;
        
    }
    
    
    public function getMATCHALERT()
    {
        
        return $this->MATCHALERT ? $this->MATCHALERT : 0;
    }
    public function setMATCHALERT($current = 0)
    {
        $this->MATCHALERT = $current;
    }
    public function getMATCHALERT_TOTAL()
    {
        return $this->MATCHALERT_TOTAL ? $this->MATCHALERT_TOTAL : 0;
    }
    public function setMATCHALERT_TOTAL($current = 0)
    {
        $this->MATCHALERT_TOTAL = $current;
    }
    
    public function getVISITOR_ALERT()
    {
        
        return $this->VISITOR_ALERT ? $this->VISITOR_ALERT : 0;
        
    }
    public function setVISITOR_ALERT($current = 0)
    {
        $this->VISITOR_ALERT = $current;
    }
    
    public function getVISITORS_ALL()
    {
        return $this->VISITORS_ALL ? $this->VISITORS_ALL : 0;
        
    }
    public function setVISITORS_ALL($current = 0)
    {
        $this->VISITORS_ALL = $current;
    }
    
    /**
     * Get count of AWAITING_RESPONSE
     *
     * <p>
     * This function gets the count of EOI received AWAITING_RESPONSE
     * </p>
     *
     * @access public
     * @return integer
     */
    public function getAWAITING_RESPONSE()
    {
        
        return $this->AWAITING_RESPONSE ? $this->AWAITING_RESPONSE : 0;
        
    }
    public function getAWAITING_RESPONSE_NEW()
    {
        
        return $this->AWAITING_RESPONSE_NEW ? $this->AWAITING_RESPONSE_NEW : 0;
        
    }
    /**
     * Set count of AWAITING_RESPONSE
     *
     * <p>
     * This function sets the count of EOI received AWAITING_RESPONSE
     * </p>
     *
     * @access public
     * @param $current integer
     */
    public function setAWAITING_RESPONSE($current = 0)
    {
        $this->AWAITING_RESPONSE = $current;
        
    }
    public function setAWAITING_RESPONSE_NEW($current = 0)
    {
        $this->AWAITING_RESPONSE_NEW = $current;
        
    }
    /**
     * Get count of FILTERED
     *
     * <p>
     * This function gets the count of EOI received FILTERED
     * </p>
     *
     * @access public
     * @return integer
     */
    public function getFILTERED()
    {
        
        return $this->FILTERED ? $this->FILTERED : 0;
        
    }
    
    /**
     * Set count of FILTERED
     *
     * <p>
     * This function sets the count of EOI received FILTERED
     * </p>
     *
     * @access public
     * @param $current integer
     */
    public function setFILTERED($current = 0)
    {
        $this->FILTERED = $current;
    }
    
    /**
     * Get count of FILTERED_NEW
     *
     * <p>
     * This function gets the count of EOI received FILTERED_NEW
     * </p>
     *
     * @access public
     * @return integer
     */
    public function getFILTERED_NEW()
    {
        
        return $this->FILTERED_NEW ? $this->FILTERED_NEW : 0;
        
    }
    
    /**
     * Set count of FILTERED_NEW
     *
     * <p>
     * This function sets the count of EOI received FILTERED_NEW
     * </p>
     *
     * @access public
     * @param $current integer
     */
    public function setFILTERED_NEW($current = 0)
    {
        $this->FILTERED_NEW = $current;
    }
    
    
    
    /**
     * 
     * Set count of JUST_JOINED_MATCHES
     * 
     * <p>
     * This function incrementally sets the member variable count to the specified $current value.
     * </p>
     * 
     * @access public
     * @param $current integer
     */
    public function setJUST_JOINED_MATCHES($current = 0)
    {
         $this->JUST_JOINED_MATCHES = $current;
        
    }
    /**
     * Get count of JUST_JOINED_MATCHES
     *
     * <p>
     * This function gets the count of JUST_JOINED_MATCHES 
     * </p>
     *
     * @access public
     * @return integer
     */
    public function getJUST_JOINED_MATCHES()
    {
        return $this->JUST_JOINED_MATCHES;
        
    }
    
    /**
     * 
     * Set count of JUST_JOINED_MATCHES_NEW
     * 
     * <p>
     * This function incrementally sets the member variable count to the specified $current value.
     * </p>
     * 
     * @access public
     * @param $current integer
     */
    public function setJUST_JOINED_MATCHES_NEW($current = 0)
    {
        $this->JUST_JOINED_MATCHES_NEW = $current;
        
    }
    /**
     * Get count of JUST_JOINED_MATCHES_NEW
     *
     * <p>
     * This function gets the count of JUST_JOINED_MATCHES_NEW 
     * </p>
     *
     * @access public
     * @return integer
     */
    public function getJUST_JOINED_MATCHES_NEW()
    {
        return $this->JUST_JOINED_MATCHES_NEW;
        
    }
    
    /**
     * 
     * Set count of CONTACTS_VIEWED
     * 
     * <p>
     * This function incrementally sets the member variable count to the specified $current value.
     * </p>
     * 
     * @access public
     * @param $current integer
     */
    public function setCONTACTS_VIEWED($current = 0)
    {
         $this->CONTACTS_VIEWED = $current;
        
    }
    /**
     * Get count of CONTACTS_VIEWED
     *
     * <p>
     * This function gets the count of CONTACTS_VIEWED 
     * </p>
     *
     * @access public
     * @return integer
     */
    public function getCONTACTS_VIEWED()
    {
        return $this->CONTACTS_VIEWED;
        
    }

/**
     * 
     * Set count of PEOPLE_WHO_VIEWED_MY_CONTACTS
     * 
     * <p>
     * This function incrementally sets the member variable count to the specified $current value.
     * </p>
     * 
     * @access public
     * @param $current integer
     */
    public function setPEOPLE_WHO_VIEWED_MY_CONTACTS($current = 0)
    {
         $this->PEOPLE_WHO_VIEWED_MY_CONTACTS = $current;
        
    }
    /**
     * Get count of PEOPLE_WHO_VIEWED_MY_CONTACTS
     *
     * <p>
     * This function gets the count of PEOPLE_WHO_VIEWED_MY_CONTACTS 
     * </p>
     *
     * @access public
     * @return integer
     */
    public function getPEOPLE_WHO_VIEWED_MY_CONTACTS()
    {
        return $this->PEOPLE_WHO_VIEWED_MY_CONTACTS;
        
    }

    
    /**
     * 
     * Get count of TODAY_INI_BY_ME
     * 
     * <p>
     * This function returns the count of TODAY_INI_BY_ME JsMemcache Object variable. If set, returns the same. Otherwise 0.
     * </p>
     * 
     * @access public
     * @return integer
     */
    public function getTODAY_INI_BY_ME()
    {
        
        return $this->TODAY_INI_BY_ME ? $this->TODAY_INI_BY_ME : 0;
        
    }
    
    /**
     * 
     * Set count of TODAY_INI_BY_ME
     * 
     * <p>
     * This function incrementally sets the member variable count to the specified $current value.
     * </p>
     * 
     * @access public
     * @param $current integer
     */
    public function setTODAY_INI_BY_ME($current = 0)
    {
        $this->TODAY_INI_BY_ME = $current;
        
    }
    
    /**
     * 
     * Get count of WEEK_INI_BY_ME.
     * 
     * <p>
     * This function returns the count of WEEK_INI_BY_ME JsMemcache Object variable. If set, returns the same. Otherwise 0.
     * </p>
     * 
     * @access public
     * @return integer
     */
    public function getWEEK_INI_BY_ME()
    {
        
        return $this->WEEK_INI_BY_ME ? $this->WEEK_INI_BY_ME : 0;
        
    }
    
    /**
     * 
     * Set count of WEEK_INI_BY_ME.
     * 
     * <p>
     * This function incrementally sets the member variable count to the specified $current value.
     * </p>
     * 
     * @access public
     * @param $current integer
     */
    public function setWEEK_INI_BY_ME($current = 0)
    {
        $this->WEEK_INI_BY_ME = $current;
        
    }
    
    /**
     * 
     * Get count of MONTH_INI_BY_ME
     * 
     * <p>
     * This function returns the count of MONTH_INI_BY_ME JsMemcache Object variable. If set, returns the same. Otherwise 0.
     * </p>
     * 
     * @access public
     * @return integer
     */
    public function getMONTH_INI_BY_ME()
    {
        
        return $this->MONTH_INI_BY_ME ? $this->MONTH_INI_BY_ME : 0;
        
    }
    
    /**
     * 
     * Set count of MONTH_INI_BY_ME
     * 
     * <p>
     * This function incrementally sets the member variable count to the specified $current value.
     * </p>
     * 
     * @access public
     * @param $current integer
     */
    public function setMONTH_INI_BY_ME($current = 0)
    {
        $this->MONTH_INI_BY_ME = $current;
        
    }
    public function getCONTACTS_MADE_AFTER_DUP()
    {
        return $this->CONTACTS_MADE_AFTER_DUP ? $this->CONTACTS_MADE_AFTER_DUP : 0;
    }
    public function setCONTACTS_MADE_AFTER_DUP($current = 0)
    {
        $this->CONTACTS_MADE_AFTER_DUP = $current;
    }
    
    /**
     * 
     * Get count of TOTAL_CONTACTS_MADE
     * 
     * <p>
     * This function returns the count of TOTAL_CONTACTS_MADE JsMemcache Object variable. If set, returns the same. Otherwise 0.
     * </p>
     * 
     * @access public
     * @return integer
     */
    public function getTOTAL_CONTACTS_MADE()
    {
        
        return $this->TOTAL_CONTACTS_MADE ? $this->TOTAL_CONTACTS_MADE : 0;
        
    }
    /**
     * 
     * Set count of TOTAL_CONTACTS_MADE 
     * 
     * <p>
     * This function incrementally sets the member variable count to the specified $current value.
     * </p>
     * 
     * @access public
     * @param $current integer
     */
    public function setTOTAL_CONTACTS_MADE($current = 0)
    {
        $this->TOTAL_CONTACTS_MADE = $current;
        
    }
    /**
     * 
     * Get count of GROUPS_UPDATED
     * 
     * <p>
     * This function returns the value of GROUPS_UPDATED JsMemcache Object variable. If set, returns the same. Otherwise 1.
     * </p>
     * 
     * @access public
     * @return integer
     */
    
    public function getGROUPS_UPDATED()
    {
        return $this->GROUPS_UPDATED ? $this->GROUPS_UPDATED : 1;
    }
    /**
     * 
     * Set data about groups updated in memcache
     * 
     * <p>
     * This function sets the member variable value to the specified $newValue value.
     * </p>
     * 
     * @access public
     * @param $newValue integer
     */
    
    public function setGROUPS_UPDATED($newValue = 1)
    {
        $this->GROUPS_UPDATED                   = $newValue;
        $this->_updatedFields['GROUPS_UPDATED'] = $this->GROUPS_UPDATED;
    }
    /**
     * 
     * Get count of NOT_REP.
     * 
     * <p>
     * This function returns the count of NOT_REP JsMemcache Object variable. If set, returns the same. Otherwise 0.
     * </p>
     * 
     * @access public
     * @return integer
     */
    public function getNOT_REP()
    { 
        return $this->NOT_REP ? $this->NOT_REP : 0;
        
    }
    
    /**
     * 
     * Set count of NOT_REP 
     * 
     * <p>
     * This function incrementally sets the member variable count to the specified $current value.
     * </p>
     * 
     * @access public
     * @param $current integer
     */
    public function setNOT_REP($current = 0)
    {
        $this->NOT_REP = $current;
        
    }
    
    /**
     * 
     * Get count of OPEN_CONTACTS.
     * 
     * <p>
     * This function returns the count of OPEN_CONTACTS JsMemcache Object variable. If set, returns the same. Otherwise 0.
     * </p>
     * 
     * @access public
     * @return integer
     */
    public function getOPEN_CONTACTS()
    {
        
        return $this->OPEN_CONTACTS ? $this->OPEN_CONTACTS : 0;
        
    }
    
    /**
     * 
     * Set count of OPEN_CONTACTS 
     * 
     * <p>
     * This function incrementally sets the member variable count to the specified $current value.
     * </p>
     * 
     * @access public
     * @param $current integer
     */
    public function setOPEN_CONTACTS($current = 0)
    {
        $this->OPEN_CONTACTS = $current;
        
    }

    /**
     * 
     * Set count of INTEREST EXPIRING 
     * 
     * <p>
     * This function sets the number of interest expiring values.
     * </p>
     * 
     * @access public
     * @param $current integer
     */
    public function setINTEREST_EXPIRING($current = 0)
    {
        $this->INTEREST_EXPIRING = $current;
        
    }
    
    /**
     * 
     * Get count of CANCELLED_EOI.
     * 
     * <p>
     * This function returns the count of CANCELLED_EOI JsMemcache Object variable. If set, returns the same. Otherwise 0.
     * </p>
     * 
     * @access public
     * @return integer
     */
    public function getCANCELLED_EOI()
    {
        
        return $this->CANCELLED_EOI ? $this->CANCELLED_EOI : 0;
        
    }
    
    /**
     * 
     * Set count of CANCELLED_EOI 
     * 
     * <p>
     * This function incrementally sets the member variable count to the specified $current value.
     * </p>
     * 
     * @access public
     * @param $current integer
     */
    public function setCANCELLED_EOI($current = 0)
    {
        $this->CANCELLED_EOI = $current;
        
    }
    public function getContactedProfiles()
    {
        return $this->contactedProfile;
    }
    
    public function setContactedProfile($contactedProfile)
    {
        
        $this->contactedProfile = $contactedProfile;
    }
    
    /**
     * 
     * Update JsMemcache Object's data.
     * 
     * <p>
     * This function updates the JsMemcache Object's variable values to the newly set values. This function must be called after we call setters for JsMemcache Object's variables. Otherwise the changes will not reflect in JsMemcache.
     * Also, this function saves the JsMemcache's instance in member variable after updating JsMemcache.
     * </p>
     * 
     * @access public
     */
    public function updateMemcacheData()
    {
        
        $data_variables = array(
            "ACC_BY_ME" => call_user_func(array(
                $this,
                getACC_BY_ME
            )),
            "ACC_ME" => call_user_func(array(
                $this,
                getACC_ME
            )),
            "ACC_ME_NEW" => call_user_func(array(
                $this,
                getACC_ME_NEW
            )),
            "DEC_BY_ME" => call_user_func(array(
                $this,
                getDEC_BY_ME
            )),
            "DEC_ME" => call_user_func(array(
                $this,
                getDEC_ME
            )),
            "DEC_ME_NEW" => call_user_func(array(
                $this,
                getDEC_ME_NEW
            )),
            "TODAY_INI_BY_ME" => call_user_func(array(
                $this,
                getTODAY_INI_BY_ME
            )),
            "WEEK_INI_BY_ME" => call_user_func(array(
                $this,
                getWEEK_INI_BY_ME
            )),
            "TOTAL_CONTACTS_MADE" => call_user_func(array(
                $this,
                getTOTAL_CONTACTS_MADE
            )),
            "MONTH_INI_BY_ME" => call_user_func(array(
                $this,
                getMONTH_INI_BY_ME
            )),
            "NOT_REP" => call_user_func(array(
                $this,
                getNOT_REP
            )),
            "OPEN_CONTACTS" => call_user_func(array(
                $this,
                getOPEN_CONTACTS
            )),
            "CANCELLED_EOI" => call_user_func(array(
                $this,
                getCANCELLED_EOI
            )),
            "GROUPS_UPDATED" => call_user_func(array(
                $this,
                getGROUPS_UPDATED
            )),
            "FILTERED" => call_user_func(array(
                $this,
                getFILTERED
            )),
            "FILTERED_NEW" => call_user_func(array(
                $this,
                getFILTERED_NEW
            )),
            "AWAITING_RESPONSE" => call_user_func(array(
                $this,
                getAWAITING_RESPONSE
            )),
            "AWAITING_RESPONSE_NEW" => call_user_func(array(
                $this,
                getAWAITING_RESPONSE_NEW
            )),
            "MESSAGE" => call_user_func(array(
                $this,
                getMESSAGE
            )),
            "MESSAGE_NEW" => call_user_func(array(
                $this,
                getMESSAGE_NEW
            )),
            "MESSAGE_ALL" => call_user_func(array(
                $this,
                getMESSAGE_ALL
            )),
            "PHOTO_REQUEST" => call_user_func(array(
                $this,
                getPHOTO_REQUEST
            )),
            "PHOTO_REQUEST_NEW" => call_user_func(array(
                $this,
                getPHOTO_REQUEST_NEW
            )),
            "PHOTO_REQUEST_BY_ME" => call_user_func(array(
                $this,
                getPHOTO_REQUEST_BY_ME
            )),
            "INTRO_CALLS" => call_user_func(array(
                $this,
                getINTRO_CALLS
            )),
            "INTRO_CALLS_COMPLETE" => call_user_func(array(
                $this,
                getINTRO_CALLS_COMPLETE
            )),
            "HOROSCOPE" => call_user_func(array(
                $this,
                getHOROSCOPE
            )),
            "HOROSCOPE_NEW" => call_user_func(array(
                $this,
                getHOROSCOPE_NEW
            )),
            "HOROSCOPE_REQUEST_BY_ME" => call_user_func(array(
                $this,
                getHOROSCOPE_REQUEST_BY_ME
            )),
            /*"HOROSCOPE_NEW" => call_user_func(array(
                $this,
                getHOROSCOPE_NEW
            )),*/
            "CONTACTS_MADE_AFTER_DUP" => call_user_func(array(
                $this,
                getCONTACTS_MADE_AFTER_DUP
            )),
            "MATCHALERT" => call_user_func(array(
                $this,
                getMATCHALERT
            )),
            "MATCHALERT_TOTAL" => call_user_func(array(
                $this,
                getMATCHALERT_TOTAL
            )),
            "VISITOR_ALERT" => call_user_func(array(
                $this,
                getVISITOR_ALERT
            )),
            "VISITORS_ALL" => call_user_func(array(
                $this,
                getVISITORS_ALL
            )),
            "CHAT_REQUEST" => call_user_func(array(
                $this,
                getCHAT_REQUEST
            )),
            "BOOKMARK" => call_user_func(array(
                $this,
                getBOOKMARK
            )),
            "SAVED_SEARCH" => call_user_func(array(
                $this,
                getSAVED_SEARCH
            )),
            "JUST_JOINED_MATCHES" => call_user_func(array(
                $this,
                getJUST_JOINED_MATCHES
            )),
            "JUST_JOINED_MATCHES_NEW" => call_user_func(array(
                $this,
                getJUST_JOINED_MATCHES_NEW
            )),
            "CONTACTS_VIEWED" => call_user_func(array(
                $this,
                getCONTACTS_VIEWED
            )),
            "PEOPLE_WHO_VIEWED_MY_CONTACTS" => call_user_func(array(
                $this,
                getPEOPLE_WHO_VIEWED_MY_CONTACTS
            )),
            "CONTACTED_BY_ME" => call_user_func(array(
                $this,
                getCONTACTED_BY_ME
            )),
            "CONTACTED_ME" => call_user_func(array(
                $this,
                getCONTACTED_ME
            )),
            "IGNORED" => call_user_func(array(
                $this,
                getIGNORED
            )),
             "INTEREST_ARCHIVED" => call_user_func(array(
                $this,
                getINTEREST_ARCHIVED
	    )),
            "INTEREST_EXPIRING" => call_user_func(array(
                $this,
                getINTEREST_EXPIRING
            )),
        );
        
        $this->_updateMemcacheVariables(serialize($data_variables));
        // $this->_memcache = unserialize(JsMemcache::getInstance()->get($this->_getProfileId()));
        
    }
    
    /**
     * 
     * Get JsMemcache Data.
     * 
     * <p>
     * This function gets JsMemcache Object's data. The data returned in unserialized.
     * </p>
     * 
     * @access public
     * @return array
     * @throws Exception
     */
    public function getMemcacheData()
    {
        $this->_memcache = unserialize(JsMemcache::getInstance()->get($this->_getProfileId()));
        if ($this->_memcache && is_array($this->_memcache))
            return $this->_memcache;
        else
            jsException::log("Object is not set.");
    }
    
    /**
     *
     * Get updated fields.
     *
     * <p>
     * This function returns updated fields of JsMemcache's Object for the logged in profileid. This is for future use when the requirement like newjs.CONTACTS_STATUS arises.
     * </p>
     *
     * @access public
     * @return array
     * @throws Exception
     */
    public function getUpdatedFields()
    {
        
        if (true === is_array($this->_updatedFields)) {
            return $this->_updatedFields;
        } else {
            jsException::log("Updated fields are not in an array format.");
        }
    }
    
    
    
     /**
     * 
     * Set CONTACTED_BY_ME profiles.
     * 
     * <p>
     * This function set CONTACTED_BY_ME.
     * </p>
     * 
     * @access public
     * @param $current string
     */
    public function setCONTACTED_BY_ME($current = '')
    {
        $this->CONTACTED_BY_ME = $current;
        
    }
    
    /**
     * 
     * Get CONTACTED_BY_ME profiles.
     * 
     * <p>
     * This function returns the CONTACTED_BY_ME profiles.
     * </p>
     * 
     * @access public
     * @return string
     */
    public function getCONTACTED_BY_ME()
    {
        
        return $this->CONTACTED_BY_ME;
        
    }
    
     /**
     * 
     * Set CONTACTED_ME profiles.
     * 
     * <p>
     * This function set CONTACTED_ME.
     * </p>
     * 
     * @access public
     * @param $current string
     */
    public function setCONTACTED_ME($current = '')
    {
        $this->CONTACTED_ME = $current;
        
    }
    
    /**
     * 
     * Get CONTACTED_ME profiles.
     * 
     * <p>
     * This function returns the CONTACTED_ME profiles.
     * </p>
     * 
     * @access public
     * @return string
     */
    public function getCONTACTED_ME()
    {
        
        return $this->CONTACTED_ME;
        
    }
    
     /**
     * 
     * Set IGNORED profiles.
     * 
     * <p>
     * This function set IGNORED.
     * </p>
     * 
     * @access public
     * @param $current string
     */
    public function setIGNORED($current = '')
    {
        $this->IGNORED = $current;
        
    }
    
    /**
     * 
     * Get IGNORED profiles.
     * 
     * <p>
     * This function returns the IGNORED profiles.
     * </p>
     * 
     * @access public
     * @return string
     */
    public function getIGNORED()
    {
        
        return $this->IGNORED;
        
    }

    /**
     * 
     * Get interest expiring profiles count.
     * 
     * <p>
     * This function returns the interest expiring profiles count.
     * </p>
     * 
     * @access public
     * @return count
     */
    public function getINTEREST_EXPIRING()
    {
        return $this->INTEREST_EXPIRING;
    }
    
    
    /**
     * 
     * Set MESSAGE_ALL profiles.
     * 
     * <p>
     * This function set MESSAGE_ALL.
     * </p>
     * 
     * @access public
     * @param $current int
     */
    public function getMESSAGE_ALL()
    {
        return $this->MESSAGE_ALL ? $this->MESSAGE_ALL : 0;
    }
    public function setMESSAGE_ALL($current = 0)
    {
        $this->MESSAGE_ALL = $current;
    }
    
   
}
