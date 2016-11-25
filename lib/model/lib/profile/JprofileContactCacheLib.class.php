<?php 

/**
 * ProfileJprofileContact
 * Library Class for store JPROFILE_CONTACT Table
 */

class ProfileJprofileContact
{
	
	/**
	 * @var Static Instance of this class
	 */
	private static $instance;

	/**
	 * Object of Store class
	 * @var instance of NEWJS_Jprofile_Contact|null
	 */
	private static $objJprofileContact = null;

	/**
     * @param $dbName - Database to which the connection would be made
     */
    private function __construct($dbname = "")
    {
        self::$objJprofileContact = new NEWJS_Jprofile_Contact($dbname);
    }

    /**
     * To Stop clone of this class object
     */
    private function __clone() {}

    /**
     * To stop unserialize for this class object
     */
    private function __wakeup() {}
    
}
?>