<?php
/**
 * ContactsMemcache Class Description
 * Page Level Block
 * 
 *
 * <p>
 * This class is used act as a dummy of ContactMemcache, used when you don't want to instantiate actual class
 * </p>
 *
 * This class will be used to handle JsMemcache related operations for Contact Engine
 *
 * <p>
 * Key for JsMemcache : Profileid of logged in user
 * 
 * <br />
 * <br />
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
class ContactsMemcacheDummy {

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

    return new ContactsMemcacheDummy($profileid);

  }

  /**
   * 
   */
  public function getFirstTime() {
    
  }

  /**
   * please ensure Y-m-d format. 
   */
  public function getEntryDate() {

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

  
  }

  /**
   * 
   * Get Contacts Count.
   * 
   * <p>
   * This function gets the contact counts for various fields for current profileid when there is no JsMemcache Object for the same. 
   * </p>
   * return bool
   */
  public function getContactCount()
  {

    
  }
}

