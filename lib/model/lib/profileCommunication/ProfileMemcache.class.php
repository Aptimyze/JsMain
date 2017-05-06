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
            $this->getMemcacheData();            
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
        $this->CONTACTED_BY_ME            = $this->_memcache["CONTACTED_BY_ME"] ? $this->_memcache["CONTACTED_BY_ME"] : "";
        $this->CONTACTED_ME            = $this->_memcache["CONTACTED_ME"] ? $this->_memcache["CONTACTED_ME"] : "";
        $this->IGNORED           = $this->_memcache["IGNORED"] ? $this->_memcache["IGNORED"] : "";
    
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
     * Get the Profile Key for which cache will be set.
     * 
     * <p>
     * This function returns the key for the profileid of the logged in user for which Cached Data was requested. 
     * It gets the value of {@link $_profileid}
     * </p>
     * 
     * @access private
     * @return String
     */
    private function _getProfileKey()
    {
        
        return "_k_".$this->_profileid;
        
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
    private function _setMemcacheData()
    {
        // Get TTL of Key
        $lifetime = JsMemcache::getInstance()->ttl($this->_getProfileKey());
        foreach($this->_updatedFields as $key =>$value)
        {
           $tempArr[$value] = $this->get($value);
        }
        if($lifetime < 0)
        {
            // key doesnot exist or expire time is not defined
            $lifetime = 1800;
            JsMemcache::getInstance()->setHashObject($this->_getProfileKey(),$tempArr,$lifetime) ;

        }
        else
            JsMemcache::getInstance()->setHashObjectWithoutExp($this->_getProfileKey(),$tempArr) ;
        $this->_updatedFields = null;
//        $ret_val = JsMemcache::getInstance()->set($this->_getProfileKey(), $data_variables, $lifetime);
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
    private function _updateMemcacheVariables()
    {
        $this->_setMemcacheData();
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
            JsMemcache::getInstance()->delete(self::$_instance[$profileid]->_getProfileKey());
            unset(self::$_instance[$profileid]);
        } else {
            //      jsException::log("Please set the instance first by calling \"getInstance\" method.");
        }
    }
    
    public function getGROUPS_UPDATED()
    {
        return $this->GROUPS_UPDATED ? $this->GROUPS_UPDATED : 1;
    }

    /**
     * 
     * Set data for a key in object
     * 
     * <p>
     * This function sets the member variable value to the specified $value value.
     * </p>
     * 
     * @access public
     * @param $newValue integer
     */
    
    public function set($key,$value)    {
        $this->$key  = $value;
        $this->setFieldUpdated($key);

    }
    /**
     * 
     * Get data for a key in object
     * 
     * <p>
     * This function gets the member variable value .
     * </p>
     * 
     * @access public
     * @param $newValue integer
     */
    
    public function get($key)    {
        return $this->$key  ;

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
        
        $this->_setMemcacheData();        
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
        $this->_memcache      = JsMemcache::getInstance()->getHashAllValue($this->_getProfileKey());
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
    

    public function setFieldUpdated($key)
    {
        
        $this->_updatedFields[] = $key;
        
    }
    public static function unsetInstance($profileid)
    {
            unset(self::$_instance[$profileid]);
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
    
}
