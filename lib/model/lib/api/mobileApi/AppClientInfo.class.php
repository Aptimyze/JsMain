<?php
/**
 * The AppClientInfo class contains get, set,insert,update and delete methods for CLIENT_INFO table
 * Example how to call
 * <code>
 * $obj=new AppClientInfo();
 * $obj->setAuthkey('12093128309712094asdas');
 * $obj->insert();
 * </code>
 * @package jeevansathi
 * @subpackage ApiClientInfo
 * @author Nitesh Sethi
 */
class AppClientInfo {
	
	
	/**
	 * 
	 * This variable holds the APPID value of the CLIENT_INFO table.
	 * @access private
	 * @var integer
	 */
	private $APPID;
	/**
	 * 
	 * Holds the info about the client device .
	 * @access private
	 * @var string
	 */
	private $CLIENT;
	/**
	 * 
	 * This variable holds the Email of the client user.
	 * @access private
	 * @var string
	 */
	private $EMAIL;
	/**
	 * 
	 * Holds the info about the client mobile number.
	 * @access private
	 * @var numeric
	 */
	private $MOBILE;
	/**
	 * 
	 * This variable holds the authKey of the the particular client user.
	 * @access private
	 * @var alphanumeric
	 */
	private $AUTHKEY;
	/**
	 * 
	 * Holds the info about the status of end user of a particular client.
	 * @access private
	 * @var numeric
	 */
	private $STATUS;
	/**
	 * 
	 * This variable holds the time stamp of the client info created in the table.
	 * @access private
	 * @var dateTime
	 */
	private $ADD_TIME;
	/**
	 * 
	 * Holds the info about the unique identifier of a  particular client(uid).
	 * @access private
	 * @var alphanumeric
	 */
	private $UID;
	/**
	 * 
	 * This variable holds the current request ip adddress from a particular client.
	 * @access private
	 * @var string
	 */
	private $CURRENT_IP;
	/**
	 * 
	 * Holds the info about the count of number of request from a particular client from the current ip_adddress.
	 * @access private
	 * @var int
	 */
	private $IP_COUNT;
    /**
     * 
     * db object of CLIENT_INFO table.
     * @access private
     * @var object
     */
    private $dbMobileApiClientInfo;
    
	
	//const AUTHKEY_ERROR="Null Authkey";
	//const DB_NO_RECORD_ERROR="No record found for this auth key";
	
	/**
	 * 
	 * Constructor for initializing object of AppClientInfo class
	 * @param Profile $profileObj1
	 * @param Profile $profileObj2
	 * @throws jsException
	 */
	public function __construct($authKey)
	{
		if(!$authKey){
			throw new jsException("",ResponseHandlerConfig::$AUTHKEY_ERROR);
			ValidationHandler::getValidationHandler("",ResponseHandlerConfig::$AUTHKEY_ERROR);
		}
		$this->setAUTHKEY($authKey);
		$this->setdbMobileApiClientInfo();
		$this->_setDefault();			
	}	
	/*************************************Setters*****************************************************/
	
	/**
    *
    * @param string  appId
    * @return void
    * @access public
    */
	public function setAPPID($appId)
	{
		$this->APPID=$appId;
	}
	
	/**
    *
    * @param string $client
    * @return void
    * @access public
    */
	public function setCLIENT($client)
	{
		$this->CLIENT=$client;
	}
	
	/**
    *
    * @param string email
    * @return void
    * @access public
    */
	public function setEMAIL($email)
	{
		$this->EMAIL=$email;
	}
	
	/**
    *
    * @param string 
    * @return void
    * @access public
    */
	public function setMOBILE($mobile)
	{
		$this->MOBILE=$mobile;
	}
	
	/**
    *
    * @param string authkey
    * @return void
    * @access public
    */
	public function setAUTHKEY($authkey)
	{
		$this->AUTHKEY=$authkey;
	}
	
	/**
    *
    * @param string status
    * @return void
    * @access public
    */
	
	public function setSTATUS($status)
	{
		$this->STATUS=$status;
	}
	
	/**
    *
    * @param string addTime
    * @return void
    * @access public
    */
	
	public function setADD_TIME($addTime)
	{
		$this->ADD_TIME=$addTime;
	}

	/**
    *
    * @param string uid
    * @return void
    * @access public
    */

	public function setUID($uid)
	{
		$this->UID=$uid;
	}

	/**
    *
    * @param string current_ip
    * @return void
    * @access public
    */
	
	public function setCURRENT_IP($currentIp)
	{
		$this->CURRENT_IP=$currentIp;
	}
	
	/**
    *
    * @param string ipCount
    * @return void
    * @access public
    */
	public function setIP_COUNT($ipCount)
	{
		$this->IP_COUNT=$ipCount;
	}

	/**
    *
    * @param object 
    * @return void
    * @access public
    */
	
	public function setdbMobileApiClientInfo()
	{		
		$this->dbMobileApiClientInfo=new MOBILE_API_CLIENT_INFO();		
	}


	/*************************************Getters*****************************************************/
   /**
    *
    * @param string 
    * @return int
    * @access public
    */
	
	public function getAPPID()
	{
		return $this->APPID;
	}
	
	/**
    *
    * @param void 
    * @return string
    * @access public
    */
	public function getCLIENT()
	{
		return $this->CLIENT;
	}
	/**
    *
    * @param void 
    * @return string
    * @access public
    */
	public function getEMAIL()
	{
		return $this->EMAIL;
	}
	/**
    *
    * @param void 
    * @return numeric
    * @access public
    */
	public function getMOBILE()
	{
		return $this->MOBILE;
	}
	/**
    *
    * @param void 
    * @return string
    * @access public
    */
	public function getAUTHKEY()
	{
		return $this->AUTHKEY;
	}
	/**
    *
    * @param void 
    * @return string
    * @access public
    */
	public function getSTATUS()
	{
		return $this->STATUS;
	}
	/**
    *
    * @param void 
    * @return string
    * @access public
    */
	public function getADD_TIME()
	{
		return $this->ADD_TIME;
	}
	/**
    *
    * @param void 
    * @return string
    * @access public
    */
	public function getUID()
	{
		return $this->UID;
	}
	/**
    *
    * @param void 
    * @return string
    * @access public
    */
	public function getCURRENT_IP()
	{
		return $this->CURRENT_IP;
	}
	/**
    *
    * @param void 
    * @return int
    * @access public
    */
	public function getIP_COUNT()
	{
		return $this->IP_COUNT;
	}
	/**
    *
    * @param void 
    * @return object
    * @access public
    */
	public function getdbMobileApiClientInfo()
	{
		return $this->dbMobileApiClientInfo;
	}
	/**
    *Set default values for all the setter.
    *
    * @param void
    * @return void
	* @uses  setAPPID()
	* @uses  setCLIENT()
	* @uses  setEMAIL()
	* @uses  setMOBILE()
	* @uses  setAUTHKEY()
	* @uses  setSTATUS()
	* @uses  setADD_TIME()
	* @uses  setUID()
	* @uses  setCURRENT_IP()
	* @uses  setIP_COUNT()
	* @uses  setdbMobileApiClientInfo()
    */	
	private function _setDefault()
	{
		$apiClientInfo=$this->dbMobileApiClientInfo->checkDeviceEntry($this->AUTHKEY);
		if(is_array($apiClientInfo))
		{
			$this->setAPPID($apiClientInfo["APPID"]);
			$this->setCLIENT($apiClientInfo["CLIENT"]);
			$this->setEMAIL($apiClientInfo["EMAIL"]);
			$this->setMOBILE($apiClientInfo["MOBILE"]);		
			$this->setSTATUS($apiClientInfo["STATUS"]);
			$this->setADD_TIME($apiClientInfo["ADD_TIME"]);
			$this->setUID($apiClientInfo["UID"]);
			$this->setCURRENT_IP($apiClientInfo["CURRENT_IP"]);
			$this->setIP_COUNT($apiClientInfo["IP_COUNT"]);
		}
		else{
			//throw new jsException("",ResponseHandlerConfig::$DB_NO_RECORD_ERROR);	
			ValidationHandler::getValidationHandler("",ResponseHandlerConfig::$DB_NO_RECORD_ERROR["message"]);
		}
	}
	
	/****************************************************************************************************/
	
	
	/**
	 * Update client ifno 
	 * @return void
	 * @uses $dbSender
	 * @uses $dbReciever
	 * @uses $dbObjSender
	 * @uses $dbObjReciver
	 * @uses setTIME()
	 * @uses getShardConnection()
	 * @access public
	 * 
	*/
	public function updateClientInfo()
	{				
		return $this->dbMobileApiClientInfo->update($this);
	}
	
}
?>
