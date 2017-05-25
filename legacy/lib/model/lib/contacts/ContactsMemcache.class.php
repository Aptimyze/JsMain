<?php
/**
 * 
 * Class ContactsMemcache 
 * <p>
 * This class is used to set and get values from JsMemcache for a profileid
 * </p>
 * File ContactsMemcache 
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
 * <li>OVERALL_CONTACTS_MADE</li>
 * <li>DUP_LIVE_DATE</li>
 * <li>DAYS_AFTER_DUP_LIVE_DATE</li>
 * <li>CONTACTS_MADE_AFTER_DUP</li>
 * <li>NOT_REP</li>
 * <li>OPEN_CONTACTS</li>
 * <li>CANCELLED_EOI</li>
 * <li>FILTERED</li>
 * <li>AWAITING_RESPONSE</li>
 * </ol>
 * Below is the demonstration on how to use this class
 * <code>
 * <br />
 * $contactsMemcacheObj = ContactsMemcache::getInstance($profileid);
 * <br />
 * $contactsMemcacheObj->setAcceptedByMe(1); 
 * <br />
 * $contactsMemcacheObj->setDeclinedByMe(-1); 
 * <br />
 * $variable = $contactsMemcacheObj->getAcceptedMe();
 * <br />
 * $contactsMemcacheObj->updateMemcacheData(); 
 * <br />
 * </code>
 * </p>
 * @package jeevansathi
 * @subpackage contacts
 * @author Ankit Garg <ankit.garg@jeevansathi.com>
 */
class ContactsMemcache {

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
  private $_accByMe;

  /**
   *
   * This holds the count of JsMemcache variable ACC_ME
   *
   * @var integer
   */
  private $_accMe;

  /**
   *
   * This holds the count of JsMemcache variable DEC_BY_ME
   *
   * @var integer
   */
  private $_decByMe;

  /**
   *
   * This holds the count of JsMemcache variable DEC_ME
   *
   * @var integer
   */
  private $_decMe;

  /**
   *
   * This holds the count of JsMemcache variable TODAY_INI_BY_ME
   *
   * @var integer
   */
  private $_todayIniByMe;

  /**
   *
   * This holds the count of JsMemcache variable WEEK_INI_BY_ME
   *
   * @var integer
   */
  private $_weekIniByMe;

  /**
   *
   * This holds the count of JsMemcache variable MONTH_INI_BY_ME
   *
   * @var integer
   */
  private $_monthIniByMe;

  /**
   *
   * This holds the count of JsMemcache variable TOTAL_CONTACTS_MADE
   *
   * @var integer
   */
  private $_totalContactsMade;

  /**
   *
   * This holds the count of JsMemcache variable OVERALL_CONTACTS_MADE
   *
   * @var integer
   */
  private $_overallContactsMade;

  /**
   *
   * This holds the count of JsMemcache variable NOT_REP
   *
   * @var integer
   */
  private $_notRep;

  /**
   *
   * This holds the count of JsMemcache variable OPEN_CONTACTS
   *
   * @var integer
   */
  private $_openContacts;

  /**
   *
   * This holds the count of JsMemcache variable CANCELLED_EOI
   *
   * @var integer
   */
  private $_cancelledEOI;

  /**
   *
   * This holds the count of JsMemcache variable CONTACTS_MADE_AFTER_DUP
   *
   * @var integer
   */
  private $_contactsMadeAfterDupLiveDate;

  /**
   *
   * This holds the count of JsMemcache variable DAYS_AFTER_DUP_LIVE_DATE
   *
   * @var integer
   */
  private $_daysAfterDupLiveDate;

  /**
   * 
   */
  private $_firstTime;

  /**
   * 
   */
  private $_entryDate;

  /**
   * This holds the count of EOI received which are filtered
   *
   * @var integer
   */
  private $_filtered;

  /**
   * This holds the count of EOI received which are not filtered
   *
   * @var integer
   */
  private $_awaitingResponse;

  /**
   *
   * This holds JsMemcache variable DUP_LIVE_DATE
   *
   * @var date
   */
  private $_dupLiveDate;

  /**
   *
   * This is used to store the lock obtained on JsMemcache Object for the current profileid
   *
   * @var Object
   */
  private $_lock;

  /**
   *
   * This holds instance of the ContactsMemcache class for the current profileid
   *
   * @var Object
   */
  private static $_instance;
  /**#@-*/
  const DUP_LIVE_DATE = "2012-W33-1";
  const ACCEPTED = "A";
  const DECLINED = "D";
  const CANCELLED = "E";
  const INITIATED = "I";
  const RECEIVER = "RECEIVER";
  const TIME = "TIME";
  const TYPE = 'TYPE';
  const COUNT = 'COUNT';
  const FILTERED = "FILTERED";
  const CANCELACCEPT="C";

  /**
   * 
   * Constructor for instantiating object of ContactsMemcache class
   * 
   * <p>
   * It instantiates the ContactsMemcache object for the profileid requested. It performs the following functions:
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
  private function __construct($profileid) {

    if (true === is_numeric($profileid)) {
      $this->_profileid = $profileid;
      $this->_lock = null;
      $this->_updatedFields = null;

      $reload=0;
      //JsMemcache::getInstance()->delete($this->_profileid);
      $this->_memcache = unserialize(JsMemcache::getInstance()->get($this->_getProfileId()));
      if(is_array($this->_memcache))
      {
        if($this->_memcache["ENTRY_DATE"] != $this->getEntryDate() || $this->_memcache["FIRST_TIME"] == 1)
          $reload=1; 
      }
      if (!is_array($this->_memcache) || $reload ) {         
        if (false === $this->getContactCount()) {
          $this->_initializeMemcacheData();
        }
      }
      else {
        $this->_accByMe                       =   $this->_memcache["ACC_BY_ME"];
        $this->_accMe                         =   $this->_memcache["ACC_ME"];
        $this->_decByMe                       =   $this->_memcache["DEC_BY_ME"];
        $this->_decMe                         =   $this->_memcache["DEC_ME"];
        $this->_todayIniByMe                  =   $this->_memcache["TODAY_INI_BY_ME"];
        $this->_weekIniByMe                   =   $this->_memcache["WEEK_INI_BY_ME"];
        $this->_monthIniByMe                  =   $this->_memcache["MONTH_INI_BY_ME"];
        $this->_totalContactsMade             =   $this->_memcache["TOTAL_CONTACTS_MADE"];
        $this->_overallContactsMade           =   $this->_memcache["OVERALL_CONTACTS_MADE"];
        $this->_notRep                        =   $this->_memcache["NOT_REP"];
        $this->_openContacts                  =   $this->_memcache["OPEN_CONTACTS"];
        $this->_cancelledEOI                  =   $this->_memcache["CANCELLED_EOI"];
        $this->_dupLiveDate                   =   $this->_memcache["DUP_LIVE_DATE"];
        $this->_contactsMadeAfterDupLiveDate  =   $this->_memcache["CONTACTS_MADE_AFTER_DUP"];
        $this->_firstTime                     =   $this->_memcache["FIRST_TIME"];
        $this->_entryDate                     =   $this->_memcache["ENTRY_DATE"];
        $this->_awaitingResponse              =   $this->_memcache["AWAITING_RESPONSE"];
        $this->_filtered                      =   $this->_memcache["FILTERED"];

        $this->_setDaysAfterDCMLiveDate();

        $this->_daysAfterDupLiveDate          =   $this->getDaysAfterDCMLiveDate();
      }
    }
    else {
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
  private function _initializeMemcacheData() {

    $this->_accByMe                       = 0;
    $this->_accMe                         = 0;
    $this->_decByMe                       = 0;
    $this->_decMe                         = 0;
    $this->_todayIniByMe                  = 0;
    $this->_weekIniByMe                   = 0;
    $this->_monthIniByMe                  = 0;
    $this->_overallContactsMade           = 0;
    $this->_notRep                        = 0;
    $this->_openContacts                  = 0;
    $this->_cancelledEOI                  = 0;
    $this->_contactsMadeAfterDupLiveDate  = 0;
    $this->_dupLiveDate                   = date("Y-m-d", JSstrToTime(ContactsMemcache::DUP_LIVE_DATE));
    $this->_daysAfterDupLiveDate          = floor(abs(JSstrToTime(date("Y-m-d")) - JSstrToTime($this->_dupLiveDate)) / (60 * 60 * 24));

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
  private function _getProfileId() {

    return $this->_profileid;

  }

  /**
   * 
   * Get Lock on current profileid's JsMemcache Object
   * 
   * <p>
   * This function gets lock on current profileid's JsMemcache Object and stores the lock obtained in memeber variable _lock. The lock is Exclusive lock and file based locking is used.
   * </p>
   * 
   * @access private
   */
  private function _getMemcacheLock() {

    $this->_lock = JsMemcache::getInstance()->getLock($this->_getProfileId());

  }

  /**
   * 
   * Check whether lock is set.
   * 
   * <p>
   * This function checks whether the Lock is set. Returns true if set, false otherwise
   * </p>
   * 
   * @access private
   * @return bool
   */
  private function _isLockSet() {

    if (isset($this->_lock)) {
      return true;
    }
    else {
      return false;
    }

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
  private function _setMemcacheData($data_variables) {

    $ret_val = JsMemcache::getInstance()->set($this->_getProfileId(), $data_variables);
    return $ret_val;

  }

  /**
   * 
   * Release Lock on current profileid's JsMemcache Object
   * 
   * <p>
   * This function releases the exclusive lock which was previously obtained. Returns true if successful, false otherwise.
   * </p>
   * 
   * @access private
   * @return bool
   */
  private function _releaseMemcacheLock() {

    return JsMemcache::getInstance()->releaseLock($this->_lock);

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
  private function _updateMemcacheVariables($data_variables) {

    $this->_getMemcacheLock();
    if (true === $this->_isLockSet()) {
      if (false === $this->_setMemcacheData($data_variables)) {
        if (false === $this->_releaseMemcacheLock()) {
          jsException::log("Memcache Lock with id = $this->_lock, could not be released.");
        }
      }
      if (false === $this->_releaseMemcacheLock()) {
        jsException::log("Memcache Lock with id = $this->_lock could not be released.");
      }
    }
    else {
      //$this->_releaseMemcacheLock();
      jsException::log("Memcache lock could not be obtained for profileid = ".$this->_getProfileId());
    }

  }

  /**
   * 
   * Update ContactsMemcache Member variable.
   * 
   * <p>
   * This function updates ContactsMemcache member variable count by number of steps specified.
   * </p>
   * 
   * @param $previous The previous value of the member variable.
   * @param $current The value by which the member variable's value needs to be updated.
   * @param $variableName The name of the JsMemcache Object variable for which update on corresponding member variable is performed.
   * @access private
   * @throws Exception
   */
  private function _updateMemberVariable($previous, $updateBy, $variableName) {

    if (true === is_numeric($updateBy)) {

      $previous = $previous ? $previous : 0;

      $new_value = $previous + $updateBy;

      if ($new_value >= 0) {
        return $new_value;
      }
      else {
        //jsException::log("ProfileID: " . $this->_getProfileId() . ", value for variable name = $variableName is negative. New Value = $new_value");
        $new_value = 0;
        return $new_value;
      }
    }
    else {
      jsException::log("Update By step size is not an integer for variable = $variableName . Step size provided = $updateBy");
    }

  }

  /**
   * 
   * Get the Instance of ContactsMemcache class.
   * 
   * <p>
   * This function returns the instance of ContactsMemcache class for the profileid supplied.
   * </p>
   *
   * @param $profileid
   * @access public
   * @return Object
   * @throws Exception
   */
  public static function getInstance($profileid) {

    if (isset(self::$_instance[$profileid])) {
      if ($profileid && (self::$_instance[$profileid]->_getProfileId() !== $profileid)) {
        self::$_instance[$profileid] = new ContactsMemcache($profileid);
      }
    }
    else {
      self::$_instance[$profileid] = new ContactsMemcache($profileid);
    }

    if(isset(self::$_instance[$profileid])) {
      return self::$_instance[$profileid];
    }
    else {
      jsException::log("Object cannot be instantiated.");
    }

  }

  /**
   * 
   */
  public function getFirstTime() {
    return $this->_firstTime;
  }

  /**
   * please ensure Y-m-d format. 
   */
  public function getEntryDate() {
    $this->_entryDate = date("Y-m-d");
    return $this->_entryDate;
  }
  /**
   * 
   * Clears the Instance of ContactsMemcache class.
   * 
   * <p>
   * This function clears the current instance of ContactsMemcache class.
   * </p>
   *
   * @access public
   * @throws Exception
   */
  public static function clearInstance($profileid) {
    if (isset(self::$_instance[$profileid])) {
      JsMemcache::getInstance()->delete(self::$_instance[$profileid]->_getProfileId());
      unset(self::$_instance[$profileid]);
    }
    else {
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
  public function getAcceptedByMe() {

    return $this->_accByMe ? $this->_accByMe : 0;

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
  public function setAcceptedByMe($current = 0) {

    $this->_accByMe = $this->_updateMemberVariable($this->getAcceptedByMe(), $current, "ACC_BY_ME");
    $this->_updatedFields["ACC_BY_ME"] = $this->_accByMe;

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
  public function getAcceptedMe() {

    return $this->_accMe ? $this->_accMe : 0;

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
  public function setAcceptedMe($current = 0) {

    $this->_accMe = $this->_updateMemberVariable($this->getAcceptedMe(), $current, "ACC_ME");
    $this->_updatedFields["ACC_ME"] = $this->_accMe;

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
  public function getDeclinedByMe() {

    return $this->_decByMe ? $this->_decByMe : 0;

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
  public function setDeclinedByMe($current = 0) {

    $this->_decByMe = $this->_updateMemberVariable($this->getDeclinedByMe(), $current, "DEC_BY_ME");
    $this->_updatedFields["DEC_BY_ME"] = $this->_decByMe;

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
  public function getDeclinedMe() {

    return $this->_decMe ? $this->_decMe : 0;

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
  public function setDeclinedMe($current = 0) {

    $this->_decMe = $this->_updateMemberVariable($this->getDeclinedMe(), $current, "DEC_ME");
    $this->_updatedFields["DEC_ME"] = $this->_decMe;

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
  public function getAwaitingResponse() {
    
    return $this->_awaitingResponse ? $this->_awaitingResponse : 0;

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
  public function setAwaitingResponse($current = 0) {
    
    $this->_awaitingResponse = $this->_updateMemberVariable($this->getAwaitingResponse(), $current, "AWAITING_RESPONSE");
    $this->_updatedFields["AWAITING_RESPONSE"] = $this->_awaitingResponse;

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
  public function getFiltered() {
    
    return $this->_filtered ? $this->_filtered : 0;

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
  public function setFiltered($current = 0) {
  
    $this->_filtered = $this->_updateMemberVariable($this->getFiltered(), $current, "FILTERED");
    $this->_updatedFields["FILTERED"] = $this->_filtered;
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
  public function getTodayInitiatedByMe() {

    return $this->_todayIniByMe ? $this->_todayIniByMe : 0;

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
  public function setTodayInitiatedByMe($current = 0) {

    $this->_todayIniByMe = $this->_updateMemberVariable($this->getTodayInitiatedByMe(), $current, "TODAY_INI_BY_ME");
    $this->_updatedFields["TODAY_INI_BY_ME"] = $this->_todayIniByMe;

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
  public function getWeekInitiatedByMe() {

    return $this->_weekIniByMe ? $this->_weekIniByMe : 0;

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
  public function setWeekInitiatedByMe($current = 0) {

    $this->_weekIniByMe = $this->_updateMemberVariable($this->getWeekInitiatedByMe(), $current, "WEEK_INI_BY_ME");
    $this->_updatedFields["WEEK_INI_BY_ME"] = $this->_weekIniByMe;

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
  public function getMonthInitiatedByMe() {

    return $this->_monthIniByMe ? $this->_monthIniByMe : 0;

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
  public function setMonthInitiatedByMe($current = 0) {

    $this->_monthIniByMe = $this->_updateMemberVariable($this->getMonthInitiatedByMe(), $current, "MONTH_INI_BY_ME");
    $this->_updatedFields["MONTH_INI_BY_ME"] = $this->_monthIniByMe;

  }

  /**
   * 
   * Get count of OVERALL_CONTACTS_MADE.
   * 
   * <p>
   * This function returns the count of OVERALL_CONTACTS_MADE JsMemcache Object variable. If set, returns the same. Otherwise 0.
   * </p>
   * 
   * @access public
   * @return integer
   */
  public function getOverallContactsMade() {

    return $this->_overallContactsMade ? $this->_overallContactsMade : 0;

  }

  /**
   * 
   * Set count of OVERALL_CONTACTS_MADE.
   * 
   * <p>
   * This function incrementally sets the member variable count to the specified $current value.
   * </p>
   * 
   * @access public
   * @param $current integer
   */
  public function setOverallContactsMade($current = 0) {

    $this->_overallContactsMade = $this->_updateMemberVariable($this->getOverallContactsMade(), $current, "OVERALL_CONTACTS_MADE");
    $this->_updatedFields["OVERALL_CONTACTS_MADE"] = $this->_overallContactsMade;

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
  public function getTotalContactsMade() {

    return $this->_totalContactsMade ? $this->_totalContactsMade : 0;

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
  public function setTotalContactsMade($current = 0) {

    $this->_totalContactsMade = $this->_updateMemberVariable($this->getTotalContactsMade(), $current, "TOTAL_CONTACTS_MADE");
    $this->_updatedFields["TOTAL_CONTACTS_MADE"] = $this->_totalContactsMade;

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
  public function getNotReplied() {

    return $this->_notRep ? $this->_notRep : 0;

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
  public function setNotReplied($current = 0) {

    $this->_notRep = $this->_updateMemberVariable($this->getNotReplied(), $current, "NOT_REP");
    $this->_updatedFields["NOT_REP"] = $this->_notRep;

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
  public function getOpenContacts() {

    return $this->_openContacts ? $this->_openContacts : 0;

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
  public function setOpenContacts($current = 0) {

    $this->_openContacts = $this->_updateMemberVariable($this->getOpenContacts(), $current, "OPEN_CONTACTS");
    $this->_updatedFields["OPEN_CONTACTS"] = $this->_openContacts;

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
  public function getCancelledEOI() {

    return $this->_cancelledEOI ? $this->_cancelledEOI : 0;

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
  public function setCancelledEOI($current = 0) {

    $this->_cancelledEOI = $this->_updateMemberVariable($this->getcancelledEOI(), $current, "CANCELLED_EOI");
    $this->_updatedFields["CANCELLED_EOI"] = $this->_cancelledEOI;

  }

  /**
   * 
   * Get count of CONTACTS_MADE_AFTER_DUP
   * 
   * <p> 
   * This function returns the count of CONTACTS_MADE_AFTER_DUP JsMemcache Object variable. If set, returns the same. Otherwise 0.
   * </p>
   * 
   * @access public
   * @return integer
   */
  public function getContactsCountMadeAfterDCMLiveDate() {

    return $this->_contactsMadeAfterDupLiveDate ? $this->_contactsMadeAfterDupLiveDate : 0;

  }

  /**
   * 
   * Set count of CONTACTS_MADE_AFTER_DUP
   * 
   * <p>
   * This function incrementally sets the member variable count to the specified $current value.
   * </p>
   * 
   * @access public
   * @param $current integer
   */
  public function setContactsCountMadeAfterDCMLiveDate($current = 0) {

    $this->_contactsMadeAfterDupLiveDate = $this->_updateMemberVariable($this->getContactsCountMadeAfterDCMLiveDate(), $current, "CONTACTS_MADE_AFTER_DUP");
    $this->_updatedFields["CONTACTS_MADE_AFTER_DUP"] = $this->_contactsMadeAfterDupLiveDate;

  }

  /**
   * 
   * Get value of DUP_LIVE_DATE.
   * 
   * <p>
   * This function returns the value of DUP_LIVE_DATE JsMemcache Object variable. If set, returns the same. Otherwise sets to default value.
   * </p>
   * 
   * @access public
   * @return date(YYYY-MM-DD)
   */
  public function getDCMLiveDate() {

    $this->_setDCMLiveDate();
    return $this->_dupLiveDate ? $this->_dupLiveDate : date("Y-m-d", JSstrToTime(ContactsMemcache::DUP_LIVE_DATE));

  }

  /**
   * 
   * Set value of DUP_LIVE_DATE
   * 
   * <p>
   * This function sets the value of DUP_LIVE_DATE to 2012-08-13.
   * </p>
   * 
   * @access private
   * @throws Exception
   */
  private function _setDCMLiveDate() {

    $default = date("Y-m-d", JSstrToTime(ContactsMemcache::DUP_LIVE_DATE));
    $tokens = explode("-", $default);
    $year = $tokens[0];
    $month = $tokens[1];
    $day = $tokens[2];

    if (true === checkDate($month, $day, $year)) {
      $this->_dupLiveDate = $default;
    }
    else {
      jsException::log("Format for date is invalid.");
    }

  }

  /**
   * 
   * Get count of DAYS_AFTER_DUP_LIVE_DATE
   * 
   * <p>
   * This function returns the count of DAYS_AFTER_DUP_LIVE_DATE JsMemcache Object variable. If set, returns the same. Otherwise 0.
   * </p>
   * 
   * @access public
   * @return integer
   */
  public function getDaysAfterDCMLiveDate() {

    return $this->_daysAfterDupLiveDate ? $this->_daysAfterDupLiveDate : 0;

  }

  /**
   * 
   * Set value of DAYS_AFTER_DUP_LIVE_DATE.
   * 
   * <p>
   * This function sets the value of DAYS_AFTER_DUP_LIVE_DATE to the difference between today's date and duplication checks milestone live date.
   * </p>
   * 
   * @access private
   */
  private function _setDaysAfterDCMLiveDate() {

    $this->_daysAfterDupLiveDate = floor(abs(JSstrToTime(date("Y-m-d")) - JSstrToTime($this->getDCMLiveDate())) / (60 * 60 * 24));
    $this->_updatedFields["DAYS_AFTER_DUP_LIVE_DATE"] = $this->_daysAfterDupLiveDate;

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
  public function updateMemcacheData() {

    $data_variables = array (
        "ACC_BY_ME"                 =>  call_user_func(array($this, getAcceptedByMe)),
        "ACC_ME"                    =>  call_user_func(array($this, getAcceptedMe)),
        "DEC_BY_ME"                 =>  call_user_func(array($this, getDeclinedByMe)),
        "DEC_ME"                    =>  call_user_func(array($this, getDeclinedMe)),
        "TODAY_INI_BY_ME"           =>  call_user_func(array($this, getTodayInitiatedByMe)),
        "WEEK_INI_BY_ME"            =>  call_user_func(array($this, getWeekInitiatedByMe)),
        "TOTAL_CONTACTS_MADE"       =>  call_user_func(array($this, getTotalContactsMade)),
        "CONTACTS_MADE_AFTER_DUP"   =>  call_user_func(array($this, getContactsCountMadeAfterDCMLiveDate)),
        "MONTH_INI_BY_ME"           =>  call_user_func(array($this, getMonthInitiatedByMe)),
        "OVERALL_CONTACTS_MADE"     =>  call_user_func(array($this, getOverallContactsMade)),
        "NOT_REP"                   =>  call_user_func(array($this, getNotReplied)),
        "OPEN_CONTACTS"             =>  call_user_func(array($this, getOpenContacts)),
        "CANCELLED_EOI"             =>  call_user_func(array($this, getCancelledEOI)),
        "DUP_LIVE_DATE"             =>  call_user_func(array($this, getDCMLiveDate)),
        "FIRST_TIME"                =>  call_user_func(array($this, getFirstTime)),
        "ENTRY_DATE"                =>  call_user_func(array($this, getEntryDate)),
        "FILTERED"                  =>  call_user_func(array($this, getFiltered)),
        "AWAITING_RESPONSE"         =>  call_user_func(array($this, getAwaitingResponse)),
        "DAYS_AFTER_DUP_LIVE_DATE"  =>  call_user_func(array($this, getDaysAfterDCMLiveDate))
        );

    $this->_updateMemcacheVariables(serialize($data_variables));
    $this->_memcache = unserialize(JsMemcache::getInstance()->get($this->_getProfileId()));

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
  public function getMemcacheData() {

    if ($this->_memcache && is_array($this->_memcache)) {
      return $this->_memcache;
    }
    else {
      jsException::log("Object is not set.");
    }

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
  public function getUpdatedFields() {

    if (true === is_array($this->_updatedFields)) {
      return $this->_updatedFields;  
    }
    else {
      jsException::log("Updated fields are not in an array format.");
    }
  }

  /**
   * 
   * Get Contacts Count.
   * 
   * <p>
   * This function gets the contact counts for various fields for current profileid when there is no JsMemcache Object for the same. 
   * </p>
   * 
   * return bool
   */
  public function getContactCount()
  {

    $acc_by_me = 0;
    $acc_me = 0;
    $dec_by_me = 0;
    $dec_me = 0;
    $today_ini_by_me = 0;
    $month_ini_by_me = 0;
    $week_ini_by_me = 0;
    $computeAfterDate = 0;
    $total_contacts = 0;
    $tempDayContactCount = 0;
    $tempOverAllContactCount = 0;
    $not_rep = 0;
    $open_contacts = 0;
    $cancelled_eoi = 0;
    $awaiting_resp = 0;
    $filtered = 0;

    $this->_setDCMLiveDate();
    $this->_setDaysAfterDCMLiveDate();

    $dbName = JsDbSharding::getShardNo($this->_getProfileId());
    $dbObj = new newjs_CONTACTS($dbName);
    $dec_by_me=0;
    $dec_me=0;
    $respondedArr = $dbObj->getRespondedCount($this->_getProfileId());
    if(is_array($respondedArr))
    {
      foreach ($respondedArr as $key=>$val)
      {
        if($val[ContactsMemcache::TYPE] == ContactsMemcache::ACCEPTED)
		$acc_by_me = $val[ContactsMemcache::COUNT];
        else if($val[ContactsMemcache::TYPE] == ContactsMemcache::DECLINED)
		$dec_by_me+= $val[ContactsMemcache::COUNT];
	else if($val[ContactsMemcache::TYPE] == ContactsMemcache::CANCELLED || $val[ContactsMemcache::TYPE] == ContactsMemcache::CANCELACCEPT)
		$dec_me+= $val[ContactsMemcache::COUNT];
      }
    }
    $b90 = mktime(0, 0, 0, date("m"), date("d") - 90, date("Y"));
    $back_90_days = date("Y-m-d", $b90);
    $responsedArr = $dbObj->getRespondedCount($this->_getProfileId(), "TIME > '$back_90_days 00:00:00'");
    if (is_array($responsedArr)) {
      foreach ($responsedArr as $key => $val) {
        if ($val[ContactsMemcache::TYPE] === ContactsMemcache::INITIATED) {
          if ($val[ContactsMemcache::FILTERED] !== "Y") {
            $awaiting_response += $val[ContactsMemcache::COUNT];
          }
          else {
            $filtered = $val[ContactsMemcache::COUNT];
          }
        }
      }
    }
    $responseArr = $dbObj->getResponseCount($this->_getProfileId());
    if(is_array($responseArr))
    {
      foreach ($responseArr as $key=>$val)
      {
        if($val[ContactsMemcache::TYPE] == ContactsMemcache::ACCEPTED)
          $acc_me = $val[ContactsMemcache::COUNT];
        else if($val[ContactsMemcache::TYPE] == ContactsMemcache::DECLINED)
          $dec_me+= $val[ContactsMemcache::COUNT];
	else if($val[ContactsMemcache::TYPE] == ContactsMemcache::CANCELACCEPT || $val[ContactsMemcache::TYPE] == ContactsMemcache::CANCELLED)
		$dec_by_me+=$val[ContactsMemcache::COUNT];
        else if($val[ContactsMemcache::TYPE] == ContactsMemcache::CANCELLED)
          $cancelled_eoi = $val[ContactsMemcache::COUNT];
        else if($val[ContactsMemcache::TYPE]==ContactsMemcache::INITIATED)
          $not_rep = $val[ContactsMemcache::COUNT];
      }
    }

    $open_contacts = $dbObj->getOpenContactsCount($this->_getProfileId());

    $dbInstance = new BILLING_SERVICE_STATUS();
    list($expDate, $expDays) = $dbInstance->getLastExpiryDate($this->_getProfileId());

    $messageLogInstance = new newjs_MESSAGE_LOG($dbName);
    $contactData = $messageLogInstance->getInitiatedContact($this->_getProfileId());

    if(is_array($contactData)) {
      foreach($contactData as $key=>$val)
      {
        if(isset($expDays) && $expDays >= 0)
        {
          if($val[ContactsMemcache::TIME] < $expDays)
          {
            $overAllLimitArr[$val[ContactsMemcache::RECEIVER]] = $val[ContactsMemcache::TIME];
          }
          else
          {
            unset($overAllLimitArr[$val[ContactsMemcache::RECEIVER]]); 
          }		
        }

        // this array will contain all the contacts initiated by user
        $contactArr[$val[ContactsMemcache::RECEIVER]] = $val[ContactsMemcache::TIME];
      }	
    }

    if (is_array($contactArr))
      foreach($contactArr as $key=>$val)
      {
        if($val == 0)
        {
          $today_ini_by_me++;
        }
        if(date('w') >= $val)
        {
          $week_ini_by_me++;
        }
        if(date('d') >= $val)
        {
          $month_ini_by_me++;
        }
        if($this->getDaysAfterDCMLiveDate() >= $val)
        {
          $computeAfterDate++;
        }
        //if user expiry date of subscription is over
        if(isset($expDays) && $expDays >= 0)
        {
          if(is_array($overAllLimitArr))
            $overall_contacts_made = count($overAllLimitArr);
          else
            $overall_contacts_made = 0;
        }
        else	
          $total_contacts++;

      }
      
    // get temporary contact count logic
    $tempContactObj = new NEWJS_CONTACTS_TEMP();
    list($tempOverAllContactCount, $tempDayContactCount) = $tempContactObj->getTemporaryContactsCount($this->_getProfileId());

    $today_ini_by_me += $tempDayContactCount;
    $week_ini_by_me += $tempDayContactCount;
    $month_ini_by_me += $tempDayContactCount;
    $overall_contacts_made += $tempOverAllContactCount;

    if(!$overall_contacts_made)
      $overall_contacts_made = 0;

    if(!$total_contacts)
      $total_contacts = $overall_contacts_made;

    $this->_firstTime = 0;
    $this->setAcceptedByMe($acc_by_me ? $acc_by_me : 0);
    $this->setAcceptedMe($acc_me ? $acc_me : 0);
    $this->setDeclinedByMe($dec_by_me ? $dec_by_me : 0);
    $this->setDeclinedMe($dec_me ? $dec_me : 0);
    $this->setTodayInitiatedByMe($today_ini_by_me ? $today_ini_by_me : 0);
    $this->setWeekInitiatedByMe($week_ini_by_me ? $week_ini_by_me : 0);
    $this->setMonthInitiatedByMe($month_ini_by_me ? $month_ini_by_me : 0);
    $this->setTotalContactsMade($total_contacts ? $total_contacts : 0);
    $this->setOverallContactsMade($overall_contacts_made ? $overall_contacts_made : 0);
    $this->setContactsCountMadeAfterDCMLiveDate($computeAfterDate ? $computeAfterDate : 0);
    $this->setNotReplied($not_rep ? $not_rep :0);
    $this->setOpenContacts($open_contacts ? $open_contacts :0);
    $this->setCancelledEOI($cancelled_eoi ? $cancelled_eoi : 0);
    $this->setFiltered($filtered ? $filtered : 0);
    $this->setAwaitingResponse($awaiting_response ? $awaiting_response : 0);
    $this->updateMemcacheData();
    $this->updateRecords();
    return true;
  }

  private function updateRecords()
  {
    $original_arr=array(
        'TOTAL_CONTACTS_MADE'   => $this->getTotalContactsMade(),
        'TODAY_INI_BY_ME'       => $this->getTodayInitiatedByMe(),
        'MONTH_INI_BY_ME'       => $this->getMonthInitiatedByMe(),
        'OPEN_CONTACTS'         => $this->getOpenContacts(),
        'ACC_BY_ME'             => $this->getAcceptedByMe(),
        'ACC_ME'                => $this->getAcceptedMe(),
        'NOT_REP'               => $this->getNotReplied(),
        'DEC_ME'                => $this->getDeclinedMe(),
        'DEC_BY_ME'             => $this->getDeclinedByMe(),
        'EXPIRY_DT'             => "",
        'SAVE_SEARCH'           => "");
    $DataObj=new NEWJS_CONTACTS_STATUS();
    $DataObj->replace($original_arr,$this->_getProfileId());

  }
}

