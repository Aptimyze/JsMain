<?php
	
	/**
	 * DeviceRegistration.
	 * Class for the the authentication and registration of the device
	 * @package    jeevansathi
	 * @subpackage Api
	 * @author     Nitesh Sethi
	*/


class DeviceRegistration {
	
	private $api_identifier;
	
	/**
     * Set the api_identifier for verification of a device
     * @param void
     * @return void
     */
	
	public function __construct()
	{
		$this->api_identifier =  JsConstants::$api_identifier;
	}
	
	/**
     * Function to generate UID for each device.
     * @param string identifier
     * @return string authkey
     */
     
    function generateUID($identifier) {
        $responseData = false;
        
        if ($this->verifyRequest($identifier)) {
			//generates the unique id
            $uid = uniqid();
            
            $encryptDecryptObj = new Encrypt_Decrypt();
            //auth key generated
            $authkey = $encryptDecryptObj->encrypt($uid);
            
            if (isset($uid) && !empty($uid)) {
                if ($this->insertDeviceInfo($uid, $authkey))
                    $responseData[authKey] = $authkey;
            }
        }
        return $responseData;
    }
	
	/**
     * Function to confirm user registration.
     * @param string decrypted reverse authkey
     * @return bool 
     */	
    
    function confirmRegistration($revAuthKey) {
        $responseData = false;
        $encryptDecryptObj = new Encrypt_Decrypt();
		//decrypt the reverse auth key to get reverse uid
        $decryptRevUID = $encryptDecryptObj->decrypt($revAuthKey);

        //reverse the decrypted UID to get original uid.
        $uid = strrev($decryptRevUID);

         //Update Entry in the database
        $dboMobileApiDeviceRegObj = new MOBILE_API_CLIENT_INFO(); 
        $update = $dboMobileApiDeviceRegObj->updateDeviceStatus($uid);        
        if ($update) {
            $responseData=true;
        }        
        return $responseData;
    }
	
	/**
     * Function to Verify that the identifier coming in request is valid
     * @param string identifier
     * @return bool 
     */

    private function verifyRequest($identifier) {

        $encryptDecryptObj = new Encrypt_Decrypt();

        if ($this->api_identifier == $encryptDecryptObj->decrypt($identifier)) {
            return true;
        } else {
            return false;
        }
    }
	
	
	/**
     * Function to insert the new device information in the database.
     * @param string uid,string authkey
     * @return bool 
     */
    private function insertDeviceInfo($uid, $authkey) {
        $deviceInfo = array();
        $deviceInfo['uid'] = $uid;
        $deviceInfo['authkey'] = $authkey;
        //$deviceInfo['ip_address'] = $_SERVER ["REMOTE_ADDR"];
        $deviceInfo['ip_address'] = FetchClientIP();

        $dboMobileApiDeviceRegObj = new MOBILE_API_CLIENT_INFO();

        $returnId = $dboMobileApiDeviceRegObj->registerDevice($deviceInfo);
        if ($returnId > 0) {
            return true;
        } else {
            return false;
        }
    }

}

?>
