<?php
/*
include_once(dirname(__FILE__).'/../CONSTANTS.class.php');
include_once("/var/www/mailer/web/profile/connect.inc");
include_once("/var/www/mailer/lib/model/lib/Profile.class.php");
include_once("CacheableInterface.class.php");
 */
/**
 * @class LRUObjectCache
 * @implements CacheableInterface
 * @brief This is the class implementing the LRU cache for our mailer system. This class holds the profile objects in it's local array
 **/

class LRUObjectCache implements CacheableInterface{

	private static $__container; //Static Array to hold the profile objects
	private static $__i_container_map; //Static Array to hold index based mapping for $__container;
	private $__stats; //Holds cache stats
	private $__max_objects; //Maximum allowed objects which 
	private static $__curr_obj_count = 0; //Holds the current count of objects in $__container
	private static $__instance; //Points to the only instance of this class

	/**
	 * @fn __construct
	 * @brief Private constructor for LRUObjectCache class
	 * @params Optional. The object count cannot exceed CONSTANTS::_LIMIT. However if not specified, the count will be initialized with CONSTANTS::_COUNT
	 **/

	private function __construct($count = CONSTANTS::_COUNT) {
		self::$__container = array(); //Initializing the $__container
		self::$__i_container_map = array();
		$this->__stats = array( //Array to hold the current cache Stats
			"cacheHits" => 0,
			"cacheMisses" => 0
		); 
		if ($count <= CONSTANTS::_LIMIT) { //Should not exceed the maximum allowed range
			$this->__max_objects = $count; //Number of maximum objects that can be stored in $__container
		}
		else //Throw an exception
			throw new MaximumLimitReachedException("Only " . CONSTANTS::_LIMIT . " objects can be created in pool\n");
	} //end __construct()

	/**
	 * @fn getInstance
	 * @breif Returns the instance of LRUObjectCache class (Singleton Design Pattern)
	 * @return The instance of this LRUObjectCache class.
	 **/

	public static function getInstance() {
		if (!isset(self::$__instance)) { //If instance is not set
			self::$__instance = new LRUObjectCache(CONSTANTS::_COUNT); //set it
		}
		return self::$__instance; //return the instance
	} //end getInstance()

	/**
	 * @fn exists
	 * @brief This function checks for the existence of the profile object corresponding to $profile_id in $__container
	 * @param $profile_id
	 * @return list(bool, $index) where bool is boolean for existence, $index is the location.
	 **/

	public function exists($profile_id,$loggedin=false) {
		if ($profile_id) { //If Profile ID is valid
			$indexes = array_keys( self::$__i_container_map,$profile_id); //Search in the array
			if (count($indexes)) { //Found
				if($loggedin) {
					if (self::$__container[$profile_id] instanceof LoggedInProfile) {
						return array(true, $index);
					}
				}
				else if (self::$__container[$profile_id] instanceof Profile) {
					return array(true, $index); //return True and index
				}
			}
		}
		return array(false, -1); //Not found
	} //end exists()

	/**
	 * @fn get
	 * @brief This function return the object from the $__container if it exists else sets it in the $__container
	 * @param $profile_id
	 * @return Profile object corresponding to $profile_id
	 **/

	public function get($profile_id,$loggedin=false) {
		//set_time_limit(100); //In case we require more time to execute.

		list($ret_val, $index) = $this->exists($profile_id,$loggedin); //Check for the existence
		if ($ret_val) { //Profile Object Exists
			$this->__moveToHead($index, $profile_id); //Move this object to the 0th index as this is recently accessed
			$this->__stats["cacheHits"]++; //We got a hit on our cache
			return self::$__container[$profile_id]; //return the desired object
		} else { 
			$this->__stats["cacheMisses"]++; //Cache Miss(Local cache)
			return $this->set($profile_id,$loggedin); //Set the object for profile_id and return the object
		}
	} //end get()

	/**
	 * @fn set
	 * @brief This function sets the object corresponding to the $profile_id in the cache.
	 * @param $profile_id, $dummy (Dummy is used just to make the function definition compatible to the interface (CacheableInterface)
	 * @return The profile object corresponding to the $profile_id.
	 **/ 

	public function set($profile_id,$loggedin=false) {
		$this->__evict(); //Evict some nodes before setting to ensure that there is always some room to insert.
		$node = $this->__createNode($profile_id,$loggedin); //Create object corresponding to the $profile_id
		self::$__container[$profile_id] = $node; //Assign this object to our $__container
		self::$__i_container_map[self::$__curr_obj_count] = $profile_id; //Create a map for this profile id.
		return $node; //return the object
	} //end set()

	/**
	 * @fn __isCacheFull
	 * @brief Private Function to check whether our cache is full or not
	 * @return boolean
	 **/

	private function __isCacheFull() {
		if (self::$__curr_obj_count >= $this->__max_objects) { //Current object count is equal to or exceeds the max limit
			return true;
		} else { //we still have space
			return false;
		}
	} //end __isCacheFull()

	/**
	 * @fn __reversePopReverse
	 * @brief This function is an implementation just to confirm which is the faster method for emptying $__container
	 * @param $input_array. The array to work upon.
	 * @return The popped array
	 * @exception Throws Null pointer exception in case the array is not set or undefined.
	 **/

	private function __reversePopReverse($input_array) {
		if (is_array($input_array) && isset($input_array)) { //Check whether the parameter is an array and is set
			$temp_array = array_reverse($input_array); //Reverse the input array.
			array_pop($temp_array); //Pop the element from the array. array_pop() pops the last element from the array.
			return array_reverse($temp_array); //Reverse and return.
		} else { //Throw an exception
			throw new NullPointerException("Array object is empty\n");
		}
	} //end __reversePopReverse()

	/**
	 * @fn __evict()
	 * @brief This function is used to evict elements from our local cache.
	 *
	 **/

	private function __evict() {
		if ($this->__isCacheFull()) { //Is our cache full?
			if (1) { //Conditional Function Call.
				$iteration = CONSTANTS::_POP_COUNT; //number of iterations to perform.
				while($iteration--) {
					$element = array_pop(self::$__i_container_map); //Pop self::$__i_container_map
					unset(self::$__container[$element]); //Unset the same entry from self::$__container
				}
				if ((self::$__curr_obj_count - CONSTANTS::_POP_COUNT) >= 0) {
					self::$__curr_obj_count = self::$__curr_obj_count - CONSTANTS::_POP_COUNT; //Adjust the current object count.
				} else {
					self::$__curr_obj_count = 0;
				}
				return;
			} else { //conditional call to function __reversePopReverse not fully and functionally implemented.
				self::$__container = $this->__reversePopReverse(self::$__container);
			}
		} else if ($this->__isCacheEmpty()) { //or is our cache empty?
			return; //Do nothing... we are good to go.
		}
	} //end __evict()

	/**
	 * @fn __isCacheEmpty()
	 * @brief This is a private function which checks whether our cache is empty or not.
	 * @return boolean.
	 **/

	private function __isCacheEmpty() {
		if (self::$__curr_obj_count == 0) //If current object count is zero
			return true;
		else
			return false;
	} //end __isCacheEmpty()

	/**
	 * @fn __createNode
	 * @brief Private function to create new profile object in $__container.
	 * @param $profile_id The profile_id for which the object is to be created.
	 * @return The profile object for $profile_id.
	 **/

	private function __createNode($profile_id,$loggedin) {
		if (!$this->__isCacheFull()) { //Our cache still has space.
			if($loggedin)
				$obj=new LoggedInProfile("",$profile_id);
			else
				$obj = new Profile("", $profile_id); //Get a new profile object
			$obj->getDetail("","","PROFILEID,USERNAME,PASSWORD,GENDER,RELIGION,CASTE,SECT,MANGLIK,MTONGUE,MSTATUS,DTOFBIRTH,OCCUPATION,COUNTRY_RES,CITY_RES,HEIGHT,EDU_LEVEL,EMAIL,IPADD,ENTRY_DT,MOD_DT,RELATION,COUNTRY_BIRTH,SOURCE,INCOMPLETE,PROMO,DRINK,SMOKE,HAVECHILD,RES_STATUS,BTYPE,COMPLEXION,DIET,HEARD,INCOME,CITY_BIRTH,BTIME,HANDICAPPED,NTIMES,SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,ACTIVATED,ACTIVATE_ON,AGE,GOTHRA,GOTHRA_MATERNAL,NAKSHATRA,MESSENGER_ID,MESSENGER_CHANNEL,PHONE_RES,PHONE_MOB,FAMILY_BACK,SCREENING,CONTACT,SUBCASTE,YOURINFO,FAMILYINFO,SPOUSE,EDUCATION,LAST_LOGIN_DT,SHOWPHONE_RES,SHOWPHONE_MOB,HAVEPHOTO,PHOTO_DISPLAY,PHOTOSCREEN,PREACTIVATED,KEYWORDS,PHOTODATE,PHOTOGRADE,TIMESTAMP,PROMO_MAILS,SERVICE_MESSAGES,PERSONAL_MATCHES,SHOWADDRESS,UDATE,SHOWMESSENGER,PINCODE,PARENT_PINCODE,PRIVACY,EDU_LEVEL_NEW,FATHER_INFO,SIBLING_INFO,WIFE_WORKING,JOB_INFO,MARRIED_WORKING,PARENT_CITY_SAME,PARENTS_CONTACT,SHOW_PARENTS_CONTACT,FAMILY_VALUES,SORT_DT,VERIFY_EMAIL,SHOW_HOROSCOPE,GET_SMS,STD,ISD,MOTHER_OCC,T_BROTHER,T_SISTER,M_BROTHER,M_SISTER,FAMILY_TYPE,FAMILY_STATUS,FAMILY_INCOME,CITIZENSHIP,BLOOD_GROUP,HIV,THALASSEMIA,WEIGHT,NATURE_HANDICAP,ORKUT_USERNAME,WORK_STATUS,ANCESTRAL_ORIGIN,HOROSCOPE_MATCH,SPEAK_URDU,PHONE_NUMBER_OWNER,PHONE_OWNER_NAME,MOBILE_NUMBER_OWNER,MOBILE_OWNER_NAME,RASHI,TIME_TO_CALL_START,TIME_TO_CALL_END,PHONE_WITH_STD,MOB_STATUS,LANDL_STATUS,PHONE_FLAG,CRM_TEAM,activatedKey,PROFILE_HANDLER_NAME,GOING_ABROAD,OPEN_TO_PET,HAVE_CAR,OWN_HOUSE,COMPANY_NAME,HAVE_JCONTACT,HAVE_JEDUCATION,SUNSIGN"); //Get details on the basis of the parameters requested.
			self::$__curr_obj_count++; //increment the current object count.
			return $obj; //return object
		} else { //Throw exception.
			throw new MaximumLimitReachedException("Only " . $this->__max_objects . " can be created\n");
		}
	} //end __createNode()

	/**
	 * @fn __moveToHead
	 * @brief Private function which moves the profile object 
	 *
	 *
	 **/

	private function __moveToHead($index, $profile_id) {
		if ($index == 0) { //We are already at index 0. No need to splice.
			return;
		}
		if (!$profile_id) { //For some junk profile_id, throw exception
			throw new NullPointerException('Profile ID is null'); 
		}
		$element = array_splice(self::$__i_container_map, $index, 1); //Get the element to be moved
		//unset(self::$__container[$index][$profile_id]);
		array_unshift(self::$__i_container_map, $element[0]); //Place at top of the array.
	}

/*
  private function __array_unshift_assoc($key, $val) {
	$temp_array[$key] = $val;
	foreach (self::$__container as $profile_id => $object) {
	  $temp_array[$profile_id] = $object;
	}
	self::$__container = $temp_array;
   // var_dump(self::$__container[$key]); die;
	//self::$__container = array_reverse(self::$__container, true);
	//self::$__container[$key] = $val;
	//self::$__container = array_reverse(self::$__container, true);
  }
 */

	/**
	 * @fn removePoolEntry
	 * @brief This function removes cache entry for the requested profile id
	 * @param $profile_id : The profile object corresponding to profile id to remove from cache
	 * @exception Throws ObjectNotFoundException if the profile object corresponding to profile id doesn't exist.
	 **/

	public function removePoolEntry($profile_id) {
		list($ret_val, $index) = $this->exists($profile_id); //check whether the profile object for profile id exists
		if ($ret_val) { //if object exists
			unset(self::$__container[$profile_id]); //unset it
			if (self::$__curr_obj_count > 0) {
				self::$__curr_obj_count -= 1;
			} else {
				$this->flushCache();
			}
		}
		else { //throw exception
			throw new ObjectNotFoundException('Profile Object with profile id ' . $profile_id . ' does not exist in cache.');
		}
	}

	/**
	 * @fn getContents
	 * @brief This function dumps all the contents of the container array. Dummy function for testing purposes only.
	 **/

	public function getContents() {
		var_dump(self::$__i_container_map); //dump the container contents
		//echo self::$__curr_obj_count;
	}

	/**
	 * @fn getCacheStats
	 * @brief This function will return current cache stats
	 * @return The cache stats array
	 **/

	public function getCacheStats() {
		return $this->__stats; //stats is the array (part of the interface)
	}

	/**
	 * @fn flushCache
	 * @brief This function flushes all the contents of the Cache
	 **/

	public function flushCache() {
		for ($index = 0; $index < count(self::$__i_container_map); ++$index) {
			$profile_id = self::$__i_container_map[$index]; //get Profile_id
			unset(self::$__container[$profile_id]); //unset the entry corresponding to profile_id
			unset(self::$__i_container_map[$index]); //unset the index => profile_id entry.
		}
		self::$__container = null; //unsetting container
		self::$__curr_obj_count = 0; //setting the current object count to 0
	}
}
