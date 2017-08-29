<?php
/**
 * ContactDetails contains  methods to calculate individual profile viewcontact details
 * and set the required attributes for creating Pre Post component respectively
 * <code>
* $this->loginProfile=LoggedInProfile::getInstance();
* $this->profile=Profile::getInstance();
* $this->contactObj = new Contacts($this->loginProfile, $this->profile);
* $contactHandlerObj = new ContactHandler($this->loginProfile,$this->profile,"INFO",$this->contactObj,'CONTACT_DETAIL',ContactHandler::PRE);
* $this->viewContactsObj=ContactFactory::event($contactHandlerObj);
* $contactDetailsArr=$viewContactsObj->getComponent()->contactDetailsObj->getContactDetailArr();
* </code>
 * @package    jeevansathi
 * @subpackage contacts
 * @author     Nitesh Sethi
 * @version 1.0   SVN: $Id:  Privilege 23810 2012-11-16 nitesh.s $
 */

class ContactDetails {
	/**
   *
   * This holds Profile Object
   * @access private
   * @var Profile
   */
	private $profileObj;
	/** 
 	*  
 	* This holds Viewer's profile Objedt 
 	* @access private 
 	* @var Profile 
 	*/ 
 	private $viewerProfileObj; 
 	/**
   *
   * This holds whether the post action is of direct_call
   * @access private
   * @var string
   */
	private $directCall;
	/**
   *
   * This holds whether the post action is from mobile
   * @access private
   * @var string
   */
	private $fromMobile;
	
	/**
   *
   * This holds the owner of alternate mobile number
   * @access private
   * @var string
   */
	private $ALT_MOBILE_LABEL;
	/**
   *
   * This holds the alternate mobile number
   * @access private
   * @var string
   */
	private $ALT_MOBILE;
	/**
   *
   * This holds the SHOW_ADDRESS field of the profile 
   * @access private
   * @var char
   */
	private $SHOW_ADDRESS;
	/**
   *
   * This holds the SHOW_PARENTS_ADDRESS field of the profile 
   * @access private
   * @var string
   */
	private $SHOW_PARENTS_ADDRESS;
	/**
   *
   * This holds the TIME_TO_CALL_START field of the profile 
   * @access private
   * @var string
   */
	private $TIME_TO_CALL_START;
	/**
   *
   * This holds the TIME_TO_CALL_END field of the profile 
   * @access private
   * @var string
   */
	private $TIME_TO_CALL_END;
	/**
   *
   * This holds the RELATION_NAME field of the profile 
   * @access private
   * @var string
   */
	private $RELATION_NAME;
	/**
   *
   * This holds the SHOW_MESSENGER field of the profile 
   * @access private
   * @var char
   */
	private $SHOW_MESSENGER;
	/**
   *
   * This holds the SHOW_MESSENGER2 field of the profile 
   * @access private
   * @var char
   */
	private $SHOW_MESSENGER2;
	/**
   *
   * This holds the RES_PHONE_NO field of the profile 
   * @access private
   * @var string
   */
	private $RES_PHONE_NO;
	/**
   *
   * This holds the MOB_PHONE_NO field of the profile 
   * @access private
   * @var string
   */
	private $MOB_PHONE_NO;
	/**
   *
   * This holds the RES_PHONE_OWNER_NAME field of the profile 
   * @access private
   * @var string
   */
	private $RES_PHONE_OWNER_NAME;
	/**
   *
   * This holds the RES_PHONE_OWNER_NUMBER field of the profile 
   * @access private
   * @var string
   */
	private $RES_PHONE_OWNER_NUMBER;
	/**
   *
   * This holds the MOB_PHONE_OWNER_NAME field of the profile 
   * @access private
   * @var string
   */
	private $MOB_PHONE_OWNER_NAME;
	/**
   *
   * This holds the MOB_PHONE_OWNER_NUMBER field of the profile 
   * @access private
   * @var string
   */
	private $MOB_PHONE_OWNER_NUMBER;
	/**
   *
   * This holds the EMAIL_ID field of the profile 
   * @access private
   * @var string
   */
	private $EMAIL_ID;
	/**
   *
   * This holds the CONTACT_LOCKED field of the profile 
   * @access private
   * @var char
   */
	private $CONTACT_LOCKED;
	/**
   *
   * This holds the VERIFIED_LANDLINE field of the profile 
   * @access private
   * @var string
   */
	private $VERIFIED_LANDLINE;
	/**
   *
   * This holds the VERIFIED_MOB field of the profile 
   * @access private
   * @var char
   */
	private $VERIFIED_MOB;
	/**
   *
   * This holds the USERNAME field of the profile 
   * @access private
   * @var string
   */
	private $PROFILENAME;
	
	/**
   *
   * This holds the LEFT_ALLOTED count of a  profile 
   * @access private
   * @var integer
   */
	
	private $LEFT_ALLOTED;
	/**
   *
   * This holds the Blackberry pin  of a  profile 
   * @access private
   * @var string
   */
	
	private $BLACKBERRY;
	/**
   *
   * This holds the LinkedIn URL of a  profile 
   * @access private
   * @var string
   */
	
	private $LINKEDIN;
	/**
   *
   * This holds the Facebook URL  of a  profile 
   * @access private
   * @var string
   */
	
	private $FACEBOOK;
	/**
   *
   * This holds all atribute which need to display for a  profile 
   * @access public
   * @var array
   */
	
	public $contactDetailsArr;
	/**
   *
   * This holds the atribute which is used in contact detail template to check whether it is a post direct call
   * @access public
   * @var array
   */
	
	public $postDirectCall;
	/**
   *
   * This holds the evalueLimitUser field of the viewer if he is free and viewieng evalue member contact details 
   * @access private
   * @var string
   */
	private $evalueLimitUser=0;
	/**
   *
   * This holds the whether to show hidden message to paid user
   * @access private
   * @var string
   */
	private $hiddenPhoneMsg="N";
		/**
   *
   * This holds the whether to show hidden message to paid user
   * @access private
   * @var string
   */
	private $RM_LABEL;
		/**
   *
   * This holds the whether to show hidden message to paid user
   * @access private
   * @var string
   */
	private $RM_VALUE;
	/**
	 * This function used to initilaize the ContactDetails Class object.
	 * @param ContactHandler
	 * @param string
	 * @param string
	 * @return  void
	 * @access public
	 */
	public function __construct($contactHandler,$directCall="",$postDirectCall="")
	{
		$this->profileObj=$contactHandler->getViewed();
		$this->viewerProfileObj = $contactHandler->getViewer();
		$this->directCall=$directCall;
		if($contactHandler->getPageSource()=="MOBILE")
		$this->fromMobile="MOBILE";
		$this->contactHandlerObj=$contactHandler;
		$this->setContactDetail();
		$this->postDirectCall=$postDirectCall;
		//$this->checkDetails();
		//$this->displayContactDetailsArray();
	}
	/**
	 * This function used to set the ALT_MOBILE_LABEL attribute of {@link ContactDetails} Class
	 * @param string $ALT_MOBILE_LABEL
	 * @return  void
	 * @access public
	 */
	public function setALT_MOBILE_LABEL($ALT_MOBILE_LABEL){ $this->ALT_MOBILE_LABEL=$ALT_MOBILE_LABEL; }
	/**
	 * This function used to set the ALT_MOBILE attribute of {@link ContactDetails} Class
	 * @param string $ALT_MOBILE
	 * @return  void
	 * @access public
	 */
	public function setALT_MOBILE($ALT_MOBILE){ $this->ALT_MOBILE=$ALT_MOBILE; }
	/**
	 * This function used to set the RM_LABEL attribute of {@link ContactDetails} Class
	 * @param string $RM_LABEL
	 * @return  void
	 * @access public
	 */	
	public function setRM_LABEL($RM_LABEL){ $this->RM_LABEL=$RM_LABEL; }
	

	/**
	 * This function used to set the RM_VALUE attribute of {@link ContactDetails} Class
	 * @param string $RM_VALUE
	 * @return  void
	 * @access public
	 */
	public function setRM_VALUE($RM_VALUE){ $this->RM_VALUE=$RM_VALUE; }

	/**
	 * This function used to set the SHOW_ADDRESS attribute of {@link ContactDetails} Class
	 * @param char $SHOW_ADDRESS
	 * @return  void
	 * @access public
	 */
	public function setSHOW_ADDRESS($SHOW_ADDRESS){ $this->SHOW_ADDRESS=$SHOW_ADDRESS; }
	/**
	 * This function used to set the SHOW_PARENTS_ADDRESS attribute of {@link ContactDetails} Class
	 * @param char $SHOW_PARENTS_ADDRESS
	 * @return  void
	 * @access public
	 */
	public function setSHOW_PARENTS_ADDRESS($SHOW_PARENTS_ADDRESS){ $this->SHOW_PARENTS_ADDRESS=$SHOW_PARENTS_ADDRESS; }
	/**
	 * This function used to set the TIME_TO_CALL_START attribute of {@link ContactDetails} Class
	 * @param string $TIME_TO_CALL_START
	 * @return  void
	 * @access public
	 */
	public function setTIME_TO_CALL_START($TIME_TO_CALL_START){ $this->TIME_TO_CALL_START=$TIME_TO_CALL_START; }
	/**
	 * This function used to set the TIME_TO_CALL_END attribute of {@link ContactDetails} Class
	 * @param string $TIME_TO_CALL_END
	 * @return  void
	 * @access public
	 */
	public function setTIME_TO_CALL_END($TIME_TO_CALL_END){ $this->TIME_TO_CALL_END=$TIME_TO_CALL_END; }
	/**
	 * This function used to set the RELATION_NAME attribute of {@link ContactDetails} Class
	 * @param string $RELATION_NAME
	 * @return  void
	 * @access public
	 */
	public function setRELATION_NAME($RELATION_NAME){ $this->RELATION_NAME=$RELATION_NAME; }
	/**
	 * This function used to set the SHOW_MESSENGER attribute of {@link ContactDetails} Class
	 * @param char $SHOW_MESSENGER
	 * @return  void
	 * @access public
	 */
	public function setSHOW_MESSENGER($SHOW_MESSENGER){ $this->SHOW_MESSENGER=$SHOW_MESSENGER; }
	/**
	 * This function used to set the SHOW_MESSENGER2 attribute of {@link ContactDetails} Class
	 * @param char $SHOW_MESSENGER
	 * @return  void
	 * @access public
	 */
	public function setSHOW_MESSENGER2($SHOW_MESSENGER2){ $this->SHOW_MESSENGER2=$SHOW_MESSENGER2; }
	/**
	 * This function used to set the RES_PHONE_NO attribute of {@link ContactDetails} Class
	 * @param string $RES_PHONE_NO
	 * @return  void
	 * @access public
	 */
	public function setRES_PHONE_NO($RES_PHONE_NO){ $this->RES_PHONE_NO=$RES_PHONE_NO; }
	/**
	 * This function used to set the MOB_PHONE_NO attribute of {@link ContactDetails} Class
	 * @param string $MOB_PHONE_NO
	 * @return  void
	 * @access public
	 */
	public function setMOB_PHONE_NO($MOB_PHONE_NO){ $this->MOB_PHONE_NO=$MOB_PHONE_NO; }
	/**
	 * This function used to set the RES_PHONE_OWNER_NAME attribute of {@link ContactDetails} Class
	 * @param string $RES_PHONE_OWNER_NAME
	 * @return  void
	 * @access public
	 */
	public function setRES_PHONE_OWNER_NAME($RES_PHONE_OWNER_NAME){ $this->RES_PHONE_OWNER_NAME=$RES_PHONE_OWNER_NAME; }
	/**
	 * This function used to set the RES_PHONE_OWNER_NUMBER attribute of {@link ContactDetails} Class
	 * @param string $RES_PHONE_OWNER_NUMBER
	 * @return  void
	 * @access public
	 */
	public function setRES_PHONE_OWNER_NUMBER($RES_PHONE_OWNER_NUMBER){ $this->RES_PHONE_OWNER_NUMBER=$RES_PHONE_OWNER_NUMBER; }
	/**
	 * This function used to set the MOB_PHONE_OWNER_NAME attribute of {@link ContactDetails} Class
	 * @param string $MOB_PHONE_OWNER_NAME
	 * @return  void
	 * @access public
	 */
	public function setMOB_PHONE_OWNER_NAME($MOB_PHONE_OWNER_NAME){ $this->MOB_PHONE_OWNER_NAME=$MOB_PHONE_OWNER_NAME; }
	/**
	 * This function used to set the MOB_PHONE_OWNER_NUMBER attribute of {@link ContactDetails} Class
	 * @param string $MOB_PHONE_OWNER_NUMBER
	 * @return  void
	 * @access public
	 */
	public function setMOB_PHONE_OWNER_NUMBER($MOB_PHONE_OWNER_NUMBER){ $this->MOB_PHONE_OWNER_NUMBER=$MOB_PHONE_OWNER_NUMBER; }
	/**
	 * This function used to set the EMAIL_ID attribute of {@link ContactDetails} Class
	 * @param string $EMAIL_ID
	 * @return  void
	 * @access public
	 */
	public function setEMAIL_ID($EMAIL_ID){ $this->EMAIL_ID=$EMAIL_ID; }
	/**
	 * This function used to set the CONTACT_LOCKED attribute of {@link ContactDetails} Class
	 * @param string $CONTACT_LOCKED
	 * @return  void
	 * @access public
	 */
	public function setCONTACT_LOCKED($CONTACT_LOCKED){ $this->CONTACT_LOCKED=$CONTACT_LOCKED; }
	/**
	 * This function used to set the VERIFIED_LANDLINE attribute of {@link ContactDetails} Class
	 * @param string $VERIFIED_LANDLINE
	 * @return  void
	 * @access public
	 */
	public function setVERIFIED_LANDLINE($VERIFIED_LANDLINE){ $this->VERIFIED_LANDLINE=$VERIFIED_LANDLINE; }
	/**
	 * This function used to set the VERIFIED_MOB attribute of {@link ContactDetails} Class
	 * @param string $VERIFIED_MOB
	 * @return  void
	 * @access public
	 */
	public function setVERIFIED_MOB($VERIFIED_MOB){ $this->VERIFIED_MOB=$VERIFIED_MOB; }
	/**
	 * This function used to set the VERIFIED_ALT_MOB attribute of {@link ContactDetails} Class
	 * @param string $VERIFIED_MOB
	 * @return  void
	 * @access public
	 */
	public function setVERIFIED_ALT_MOB($VERIFIED_ALT_MOB){ $this->VERIFIED_ALT_MOB=$VERIFIED_ALT_MOB; }
	/**
	 * This function used to set the PROFILENAME attribute of {@link ContactDetails} Class
	 * @param string $PROFILENAME
	 * @return  void
	 * @access public
	 */
	public function setPROFILENAME($PROFILENAME){ $this->PROFILENAME=$PROFILENAME; }
	
	/**
	 * This function used to set the LEFT_ALLOTED attribute of {@link ContactDetails} Class
	 * @param integer $LEFT_ALLOTED
	 * @return  void
	 * @access public
	 */
	public function setLEFT_ALLOTED($LEFT_ALLOTED){ $this->LEFT_ALLOTED=$LEFT_ALLOTED; }
	/**
	 * This function used to set the BLACKBERRY attribute of {@link ContactDetails} Class
	 * @param string $BLACKBERRY
	 * @return  void
	 * @access public
	 */
	public function setBLACKBERRY($BLACKBERRY){ $this->BLACKBERRY=$BLACKBERRY; }
	
	/**
	 * This function used to set the LINKEDIN URL attribute of {@link ContactDetails} Class
	 * @param string $LINKEDIN
	 * @return  void
	 * @access public
	 */
	public function setLINKEDIN($LINKEDIN){ $this->LINKEDIN=$LINKEDIN; }
	/**
	 * This function used to set the FACEBOOK attribute of {@link ContactDetails} Class
	 * @param string $FACEBOOK
	 * @return  void
	 * @access public
	 */
	public function setFACEBOOK($FACEBOOK){ $this->FACEBOOK=$FACEBOOK; }
	/**
	 * This function used to set the contact details attribute of {@link ContactDetails} Class
	 * @param array $contactDetailsArr
	 * @return  void
	 * @access public
	 */
	public function setContactDetailArr($contactDetailsArr){ $this->contactDetailsArr=$contactDetailsArr; }
	/**
	 * This function used to set the whether the free user is viewing details of EValue member
	 * @param Integer 1 or 0
	 * @return  void
	 * @access public
	 */
	public function setEvalueLimitUser($evalueLimitUser){ $this->evalueLimitUser=$evalueLimitUser; }
	/**
	 * This function used to set the whether the paid user is viewing details of free member
	 * @param Integer 1 or 0
	 * @return  void
	 * @access public
	 */
	public function setPostDirectCall($postDirectCall){ $this->postDirectCall=$postDirectCall; }
	
	
	/**
	 * This function used to set the whether to show hidden number message or not
	 * @param Integer 1 or 0
	 * @return  void
	 * @access public
	 */
	public function setHiddenPhoneMsg($hiddenPhoneMsg){ $this->hiddenPhoneMsg=$hiddenPhoneMsg; }

        /**
	 * This function used to set the message shown for primary mobile hidden or visible on accept
	 * @param String
	 * @return  void
	 * @access public
	 */
	
        public function setPrimaryMobileHiddenMessage($message){$this->primaryMobileHiddenMessage=$message;}
        /**
	 * This function used to set the message shown for  landline number hidden or visible on accept
	 * @param String
	 * @return  void
	 * @access public
	 */
	
        public function setLandlMobileHiddenMessage($message){$this->landlMobileHiddenMessage=$message;}
        
        /**
	 * This function used to set the message shown for alt mobile hidden or visible on accept
	 * @param String
	 * @return  void
	 * @access public
	 */
	
        public function setAltMobileHiddenMessage($message){$this->altMobileHiddenMessage=$message;}
        
        /**
	 * This function used to get the ALT_MOBILE_LABEL attribute of {@link ContactDetails} Class
	 * @return string
	 * @access public
	 */
	
        
	public function getALT_MOBILE_LABEL(){ return $this->ALT_MOBILE_LABEL; }
	/**
	 * This function used to get the ALT_MOBILE attribute of {@link ContactDetails} Class
	 * @return string
	 * @access public
	 */
	public function getALT_MOBILE(){ return $this->ALT_MOBILE; }
	/**
	 * This function used to get the SHOW_ADDRESS attribute of {@link ContactDetails} Class
	 * @return string
	 * @access public
	 */
	public function getRM_LABEL(){ return $this->RM_LABEL; }
	/**
	 * This function used to get the SHOW_ADDRESS attribute of {@link ContactDetails} Class
	 * @return string
	 * @access public
	 */
	public function getRM_VALUE(){ return $this->RM_VALUE; }
	/**
	 * This function used to get the SHOW_ADDRESS attribute of {@link ContactDetails} Class
	 * @return char
	 * @access public
	 */
	public function getSHOW_ADDRESS(){ return $this->SHOW_ADDRESS; }
	/**
	 * This function used to get the SHOW_PARENTS_ADDRESS attribute of {@link ContactDetails} Class
	 * @return char
	 * @access public
	 */
	public function getSHOW_PARENTS_ADDRESS(){ return $this->SHOW_PARENTS_ADDRESS; }
	/**
	 * This function used to get the TIME_TO_CALL_START attribute of {@link ContactDetails} Class
	 * @return string
	 * @access public
	 */
	public function getTIME_TO_CALL_START(){ return $this->TIME_TO_CALL_START; }
	/**
	 * This function used to get the TIME_TO_CALL_END attribute of {@link ContactDetails} Class
	 * @return string
	 * @access public
	 */
	public function getTIME_TO_CALL_END(){ return $this->TIME_TO_CALL_END; }
	/**
	 * This function used to get the RELATION_NAME attribute of {@link ContactDetails} Class
	 * @return string
	 * @access public
	 */
	public function getRELATION_NAME(){ return $this->RELATION_NAME; }
	/**
	 * This function used to get the SHOW_MESSENGER attribute of {@link ContactDetails} Class
	 * @return string
	 * @access public
	 */
	public function getSHOW_MESSENGER(){ return $this->SHOW_MESSENGER; }
	/**
	 * This function used to get the SHOW_MESSENGER2 attribute of {@link ContactDetails} Class
	 * @return string
	 * @access public
	 */
	public function getSHOW_MESSENGER2(){ return $this->SHOW_MESSENGER2; }
	/**
	 * This function used to get the RES_PHONE_NO attribute of {@link ContactDetails} Class
	 * @return string
	 * @access public
	 */
	public function getRES_PHONE_NO(){ return $this->RES_PHONE_NO; }
	/**
	 * This function used to get the MOB_PHONE_NO attribute of {@link ContactDetails} Class
	 * @return string
	 * @access public
	 */
	public function getMOB_PHONE_NO(){ return $this->MOB_PHONE_NO; }
	/**
	 * This function used to get the RES_PHONE_OWNER_NAME attribute of {@link ContactDetails} Class
	 * @return string
	 * @access public
	 */
	public function getRES_PHONE_OWNER_NAME(){ return $this->RES_PHONE_OWNER_NAME; }
	/**
	 * This function used to get the RES_PHONE_OWNER_NUMBER attribute of {@link ContactDetails} Class
	 * @return string
	 * @access public
	 */
	public function getRES_PHONE_OWNER_NUMBER(){ return $this->RES_PHONE_OWNER_NUMBER; }
	/**
	 * This function used to get the MOB_PHONE_OWNER_NAME attribute of {@link ContactDetails} Class
	 * @return string
	 * @access public
	 */
	public function getMOB_PHONE_OWNER_NAME(){ return $this->MOB_PHONE_OWNER_NAME; }
	/**
	 * This function used to get the MOB_PHONE_OWNER_NUMBER attribute of {@link ContactDetails} Class
	 * @return string
	 * @access public
	 */
	public function getMOB_PHONE_OWNER_NUMBER(){ return $this->MOB_PHONE_OWNER_NUMBER; }
	/**
	 * This function used to get the EMAIL_ID attribute of {@link ContactDetails} Class
	 * @return string
	 * @access public
	 */
	public function getEMAIL_ID(){ return $this->EMAIL_ID; }//**
	/**
	 * This function used to get the CONTACT_LOCKED attribute of {@link ContactDetails} Class
	 * @return string
	 * @access public
	 */
	public function getCONTACT_LOCKED(){ return $this->CONTACT_LOCKED; }//**v
	/**
	 * This function used to get the VERIFIED_LANDLINE attribute of {@link ContactDetails} Class
	 * @return string
	 * @access public
	 */
	public function getVERIFIED_LANDLINE(){ return $this->VERIFIED_LANDLINE; }
	/**
	 * This function used to get the VERIFIED_MOB attribute of {@link ContactDetails} Class
	 * @return string
	 * @access public
	 */
	public function getVERIFIED_MOB(){ return $this->VERIFIED_MOB; }
	/**
	 * This function used to get the VERIFIED_ALT_MOB attribute of {@link ContactDetails} Class
	 * @return string
	 * @access public
	 */
	public function getVERIFIED_ALT_MOB(){ return $this->VERIFIED_ALT_MOB; }	
	/**
	 * This function used to get the PROFILENAME attribute of {@link ContactDetails} Class
	 * @return string
	 * @access public
	 */
	public function getPROFILENAME(){ return $this->PROFILENAME; }
	
	/**
	 * This function used to get the LEFT_ALLOTED attribute of {@link ContactDetails} Class
	 * @return string
	 * @access public
	 */
	public function getLEFT_ALLOTED(){ return $this->LEFT_ALLOTED; }
	/**
	 * This function used to get the BLACKBERRY attribute of {@link ContactDetails} Class
	 * @return string
	 * @access public
	 */
	public function getBLACKBERRY(){ return $this->BLACKBERRY; }
	/**
	 * This function used to get the FACEBOOK attribute of {@link ContactDetails} Class
	 * @return string
	 * @access public
	 */
	public function getLINKEDIN(){ return $this->LINKEDIN; }
	/**
	 * This function used to get the FACEBOOK attribute of {@link ContactDetails} Class
	 * @return string
	 * @access public
	 */
	public function getFACEBOOK(){ return $this->FACEBOOK; }
	/**
	 * This function used to get the contactDcetails Array  of {@link ContactDetails} Class
	 * @return array
	 * @access public
	 */
	public function getContactDetailArr(){ return $this->contactDetailsArr; }
	
	/**
	 * This function used to get the contactDcetails Array  of {@link ContactDetails} Class
	 * @return array
	 * @access public
	 */
	public function getEvalueLimitUser(){ return $this->evalueLimitUser; }
	
	
	/**
	 * This function used to get the postDirectCall variable {@link ContactDetails} Class
	 * @return array
	 * @access public
	 */
	public function getPostDirectCall(){ return $this->postDirectCall; }
	
	
	
	/**
	 * This function used to get the hiddenPhoneMessage variable {@link ContactDetails} Class
	 * @return array
	 * @access public
	 */
	public function getHiddenPhoneMsg(){ return $this->hiddenPhoneMsg; }
      /**
	 * This function used to get the message shown for  primary mobile number hidden or visible on accept
	 * @param String
	 * @return  void
	 * @access public
	 */
        public function getPrimaryMobileHiddenMessage(){return $this->primaryMobileHiddenMessage;}
        /**
	 * This function used to get the message shown for  landline number hidden or visible on accept
	 * @param String
	 * @return  void
	 * @access public
	 */
	
        public function getLandlMobileHiddenMessage(){return $this->landlMobileHiddenMessage;}
        
        /**
	 * This function used to get the message shown for alt mobile hidden or visible on accept
	 * @param String
	 * @return  void
	 * @access public
	 */
	
        public function getAltMobileHiddenMessage(){return $this->altMobileHiddenMessage;}
        
        
        
        /**
	* calculate the view contact details of a viewed member and set the corresponding attribute
	* @uses profileObj dbPrivilegeObj
	* @uses jsadmin_CONTACTS_ALLOTED 
	* @uses NEWJS_JPROFILE_CONTACT
	* @return void
	* @access public
	*/
	
	public function setContactDetail()
	{
		// DIRECT CALL 
		if($this->directCall)
		{
			//invalid phone condition: 
			if(!JsCommon::isPhoneValid($this->profileObj))
			{
			$PHONE_MOB = "I";
			$PHONE_RES = "I";
			$ALT_NUM="I";
			$chk_landlStatus="I";
			$chk_mobStatus="I";
			}
			else // verified status
			{
			if($this->profileObj->getPHONE_MOB()!='')
				$chk_mobStatus=$this->profileObj->getMOB_STATUS();
			if($this->profileObj->getPHONE_RES()!='')
				$chk_landlStatus=$this->profileObj->getLANDL_STATUS();
			}
			
			if($chk_landlStatus ==Messages::YES)
				$this->setVERIFIED_LANDLINE(Messages::YES);
			else
				$this->setVERIFIED_LANDLINE(Messages::NO);	
					
			if($chk_mobStatus ==Messages::YES)
					$this->setVERIFIED_MOB(Messages::YES);
			else
				$this->setVERIFIED_MOB(Messages::NO);
			
		}
		
		/*********DIRECT CALL Ends here******************/
		
		//Residence phone number and Owner details
		
        if(($this->profileObj->getSHOWPHONE_RES()==Messages::YES || $this->profileObj->getSHOWPHONE_RES()==""|| ($this->profileObj->getSHOWPHONE_RES()=="C" && ($this->contactHandlerObj->getContactType() == ContactHandler::ACCEPT ||($this->contactHandlerObj->getContactType() == ContactHandler::INITIATED && $this->contactHandlerObj->getContactInitiator() == ContactHandler::RECEIVER))))&& $PHONE_RES!="I" && $this->profileObj->getPHONE_RES()!="") 
		{
			$this->setRES_PHONE_OWNER_NUMBER($this->profileObj->getDecoratedLandlineNumberOwner());
			$this->setRES_PHONE_OWNER_NAME($this->profileObj->getPHONE_OWNER_NAME());
			$res_phone=$this->profileObj->getSTD()."-".$this->profileObj->getPHONE_RES();
			if($this->profileObj->getISD())
			$this->setRES_PHONE_NO($this->profileObj->getISD()."-".$res_phone);
        }
        
        /*********Ends here******************/
		
		//Mobile phone number and Owner details
		
        if(($this->profileObj->getSHOWPHONE_MOB()==Messages::YES || $this->profileObj->getSHOWPHONE_MOB()==""|| ($this->profileObj->getSHOWPHONE_MOB()=="C" && ($this->contactHandlerObj->getContactType() == ContactHandler::ACCEPT ||($this->contactHandlerObj->getContactType() == ContactHandler::INITIATED && $this->contactHandlerObj->getContactInitiator() == ContactHandler::RECEIVER))))&& $PHONE_MOB!="I" && $this->profileObj->getPHONE_MOB()!="") 
        {
			$this->setMOB_PHONE_OWNER_NUMBER($this->profileObj->getDecoratedMobileNumberOwner());
			$this->setMOB_PHONE_OWNER_NAME($this->profileObj->getMOBILE_OWNER_NAME());
			$mob_phone=$this->profileObj->getPHONE_MOB(); 
			if($this->profileObj->getISD())
			$this->setMOB_PHONE_NO($this->profileObj->getISD()."-".$mob_phone);
        }
                
		/*********Ends here******************/
		
		//Email Detail
		
		if($this->profileObj->getVERIFY_EMAIL()==Messages::YES)
        {
			
		}
		$this->setEMAIL_ID($this->profileObj->getEMAIL());
		/*******Ends Here********************/
		
		//calculating left allotted count
			
			$jsadmin_CONTACTS_ALLOTED_OBJ =new jsadmin_CONTACTS_ALLOTED();
			
			$this->setLEFT_ALLOTED($jsadmin_CONTACTS_ALLOTED_OBJ->getViewedContacts($this->contactHandlerObj->getViewer()->getPROFILEID()));
			/************Ends here********/
		
		//show viewer address
		
        if($this->profileObj->getCONTACT()!="" && $this->profileObj->getSHOWADDRESS()==Messages::YES)
			{
				$address=$this->profileObj->getCONTACT();
				if($this->profileObj->getPINCODE())
					$address=$address."\n Pincode:".$this->profileObj->getPINCODE();
				$this->setSHOW_ADDRESS(nl2br($address));
			}
			
		/*********Ends here******************/
			
		//show parent Address	
		
        if($this->profileObj->getSHOW_PARENTS_CONTACT()==Messages::YES && $this->profileObj->getPARENTS_CONTACT()!="")
        {
			$address=$this->profileObj->getPARENTS_CONTACT();
				if($this->profileObj->getPARENT_PINCODE())
					$address=$address."\n Pincode:".$this->profileObj->getPARENT_PINCODE();
			$this->setSHOW_PARENTS_ADDRESS(nl2br($address));
		}
			
		/*********Ends here******************/
		
		 //Time to call details
			if($this->profileObj->getTIME_TO_CALL_START() && $this->profileObj->getTIME_TO_CALL_END())
                {
					$this->setTIME_TO_CALL_START($this->profileObj->getTIME_TO_CALL_START());
					$this->setTIME_TO_CALL_END($this->profileObj->getTIME_TO_CALL_END());
                }
         /*********Ends here******************/
                
        //Relation details  
        
        if( $this->profileObj->getRELATION())
			$this->setRELATION_NAME($this->profileObj->getDecoratedRelation());
			
		/*********Ends here******************/
			
		//messenger details	
		
		if($this->profileObj->getSHOWMESSENGER()==Messages::YES && $this->profileObj->getMESSENGER_CHANNEL() && $this->profileObj->getMESSENGER_ID())
			{
                $messenger=$this->profileObj->getMESSENGER_ID();
                if(!strstr($messenger,"@"))
					$messenger=$messenger."@".FieldMap::getFieldLabel("messenger_channel",$this->profileObj->getMESSENGER_CHANNEL());
				$this->setSHOW_MESSENGER($messenger);
			}
			
		/*********Ends here******************/
		
		/*********Alternative number********/
		$altMob = "";
		$altMobDetail = "";
		$jProfileContactObj= new ProfileContact();
		$altArr=$jProfileContactObj->getProfileContacts($this->profileObj->getPROFILEID()); 
		if($altArr["ALT_MOB_STATUS"]==Messages::YES || $altArr["SHOWALT_MOBILE"] == "C")
			$this->setVERIFIED_ALT_MOB(Messages::YES);
		else
			$this->setVERIFIED_ALT_MOB(Messages::NO);
			
		if(($altArr["SHOWALT_MOBILE"]==Messages::YES || !$altArr["SHOWALT_MOBILE"] || ($altArr["SHOWALT_MOBILE"]=="C" && ($this->contactHandlerObj->getContactType() == ContactHandler::ACCEPT ||($this->contactHandlerObj->getContactType() == ContactHandler::INITIATED && $this->contactHandlerObj->getContactInitiator() == ContactHandler::RECEIVER))))&& $altArr["ALT_MOBILE"] && $ALT_NUM!="I") 
		{
				if($altArr["ALT_MOBILE_ISD"])
					$altIsd = $altArr["ALT_MOBILE_ISD"]."-";
				else
					$altIsd =$this->profileObj->getISD()."-";
					$altMob = $altIsd.$altArr["ALT_MOBILE"];
				if($altArr["ALT_MOBILE_OWNER_NAME"])
				{ 
					$altMobDetail.=" of ".$altArr["ALT_MOBILE_OWNER_NAME"];
				if($altArr["ALT_MOBILE_NUMBER_OWNER"])
					$altMobDetail.=" (".FieldMap::getFieldLabel("number_owner",$altArr["ALT_MOBILE_NUMBER_OWNER"]).")";
				}
				if($altArr["SHOW_ALT_MESSENGER"]==Messages::YES && $altArr["ALT_MESSENGER_ID"] && $altArr["ALT_MESSENGER_CHANNEL"])
				{
					$altMessenger=$altArr["ALT_MESSENGER_ID"];
                if(!strstr($altMessenger,"@"))
					$altMessenger=$altMessenger."@".FieldMap::getFieldLabel("messenger_channel",$altArr["ALT_MESSENGER_CHANNEL"]);
				$this->setSHOW_MESSENGER2($altMessenger);
				}
		}
		if($altMob)
		{
			$this->setALT_MOBILE_LABEL($altMobDetail);
			$this->setALT_MOBILE($altMob);
		}
       // block for setting  hidden contacts messages         
                
                if(!($this->contactHandlerObj->getContactType() == ContactHandler::ACCEPT) && !($this->contactHandlerObj->getContactType() == ContactHandler::INITIATED && $this->contactHandlerObj->getContactInitiator() == ContactHandler::RECEIVER))
                    $contactedFlag=0;
                else 
                    $contactedFlag=1;
                
                    
                    if($altArr['ALT_MOBILE'] && $altArr["ALT_MOB_STATUS"]=='Y')
                    {
                        if($altArr["SHOWALT_MOBILE"]=="C" && !$contactedFlag)
                            $this->setAltMobileHiddenMessage(Messages::PHONE_VISIBLE_ON_ACCEPT);
                        elseif($altArr["SHOWALT_MOBILE"]=='N')
                            $this->setAltMobileHiddenMessage(Messages::PHONE_HIDDEN);
                    }      

                    if($this->profileObj->getPHONE_RES() && $this->profileObj->getLANDL_STATUS()=='Y' )
                    {
                        $showPhoneRes = $this->profileObj->getSHOWPHONE_RES();
                        if($showPhoneRes=="C" && !$contactedFlag)
                            $this->setLandlMobileHiddenMessage(Messages::PHONE_VISIBLE_ON_ACCEPT);
                        elseif($showPhoneRes=='N')
                            $this->setLandlMobileHiddenMessage(Messages::PHONE_HIDDEN);
                    }      

                    if($this->profileObj->getPHONE_MOB() && $this->profileObj->getMOB_STATUS()=='Y' )
                    {
                        $showPhoneMob = $this->profileObj->getSHOWPHONE_MOB();
                        if($showPhoneMob=="C" && !$contactedFlag)
                        $this->setPrimaryMobileHiddenMessage(Messages::PHONE_VISIBLE_ON_ACCEPT);
                        elseif($showPhoneMob=='N')
                            $this->setPrimaryMobileHiddenMessage(Messages::PHONE_HIDDEN);
                    }      
             // block for setting  hidden contacts messages ends here
                    
                    
		if($altArr["SHOWBLACKBERRY"] == Messages::YES && $altArr["BLACKBERRY"])
		{
			$this->setBlackberry($altArr["BLACKBERRY"]);
		}
		if($altArr["SHOWLINKEDIN"] == Messages::YES && $altArr["LINKEDIN_URL"])
		{
			$this->setLinkedIn($altArr["LINKEDIN_URL"]);
		}
		if($altArr["SHOWFACEBOOK"] == Messages::YES && $altArr["FB_URL"])
		{
			$this->setFacebook($altArr["FB_URL"]);
		}
		
		
		/*********Ends here******************/
		
			//call directly from mobile
		if($this->fromMobile)
		{
			//Mobile/lanline number formatting to make it work for adding to phonebook
			if($this->getMOB_PHONE_NO())
			$this->setMOB_PHONE_NO("+".str_replace("-","",$this->getMOB_PHONE_NO()));
			
			if($this->getRES_PHONE_NO())
			$this->setRES_PHONE_NO("+".str_replace("-","",$this->getRES_PHONE_NO()));
			if(substr($this->getRES_PHONE_NO(),3,1) ==='0')
				$this->setRES_PHONE_NO(substr($this->getRES_PHONE_NO(),0,3).substr($this->getRES_PHONE_NO(),4));
			
			if($this->getALT_MOBILE())
			$this->setALT_MOBILE("+".str_replace("-","",$this->getALT_MOBILE()));
			if(substr($this->getALT_MOBILE(),3,1) ==='0')
				$this->setALT_MOBILE(substr($this->getALT_MOBILE(),0,3).substr($this->getALT_MOBILE(),4));		
				
			$this->setPROFILENAME($this->profileObj->getUSERNAME());
				
		}

		if(MembershipHandler::isEligibleForRBHandling($this->profileObj->getPROFILEID()))
		{
			$exclusiveFunctionsObj=new ExclusiveFunctions();
			$execDetails=$exclusiveFunctionsObj->getRMDetails($this->profileObj->getPROFILEID());
			$rmPhone = $execDetails["PHONE"];
			if($rmPhone){
				$this->setRM_LABEL("Relationship manager");
			$this->setRM_VALUE("+91-".$rmPhone);
			}
		}
		/************Ends here********/
		$this->setContactDetailArr( $this->displayContactDetailsArray());
		//print_r($this->displayContactDetailsArray());die;
		

		
	}
	
/**
	* used for testing purpose ->print all the attributes value of ContactDetails
	* @return void
	* @access public
	*/
	/*private function checkDetails()
	{
	echo "getALT_MOBILE_LABEL : ".$this->getALT_MOBILE_LABEL()."\n";
	echo "getALT_MOBILE : ".$this->getALT_MOBILE()."\n";
	echo "getSHOW_ADDRESS : ".$this->getSHOW_ADDRESS()."\n";
	echo "getSHOW_PARENTS_ADDRESS : ".$this->getSHOW_PARENTS_ADDRESS()."\n";
	echo "getTIME_TO_CALL_START : ".$this->getTIME_TO_CALL_START()."\n";
	//echo "getTIME_TO_CALL_END : ".$this->getTIME_TO_CALL_END()."\n";
	echo "getRELATION_NAME : ".$this->getRELATION_NAME()."\n";
	echo "getSHOW_MESSENGER : ".$this->getSHOW_MESSENGER()."\n";
	echo "getRES_PHONE_NO : ".$this->getRES_PHONE_NO()."\n";
	echo "getMOB_PHONE_NO : ".$this->getMOB_PHONE_NO()."\n";
	echo "getRES_PHONE_OWNER_NAME : ".$this->getRES_PHONE_OWNER_NAME()."\n";
	echo "getRES_PHONE_OWNER_NUMBER : ".$this->getRES_PHONE_OWNER_NUMBER()."\n";
	echo "getMOB_PHONE_OWNER_NAME : ".$this->getMOB_PHONE_OWNER_NAME()."\n";
	echo "getMOB_PHONE_OWNER_NUMBER : ".$this->getMOB_PHONE_OWNER_NUMBER()."\n";
	echo "getEMAIL_ID : ".$this->getEMAIL_ID()."\n";
	echo "getCONTACT_LOCKED : ".$this->getCONTACT_LOCKED()."\n";
	echo "getVERIFIED_LANDLINE : ".$this->getVERIFIED_LANDLINE()."\n";
	echo "getVERIFIED_MOB : ".$this->getVERIFIED_MOB()."\n";
	echo "getPROFILENAME : ".$this->getPROFILENAME()."\n";
	echo "getNO_SHIFT_MES : ".$this->getNO_SHIFT_MES()."\n";
	echo "getSHOW_CONTACT : ".$this->getSHOW_CONTACT()."\n";
	echo "getPAID : ".$this->getPAID()."\n";
	echo "getLEFT_ALLOTED : ".$this->getLEFT_ALLOTED()."\n";
	echo "getBLACKBERRY : ".$this->getBLACKBERRY()."\n";
	echo "getLINKEDIN : ".$this->getLINKEDIN()."\n";
	echo "getFACEBOOK : ".$this->getFACEBOOK()."\n";
}*/
	public function displayContactDetailsArray()
	{
		$contactDetailsArr = array();
		$count = 1;
		if(JsCommon::isPhoneValid($this->profileObj))
		{
			$flagNumber=0;
			
				if($this->getALT_MOBILE() || $this->getMOB_PHONE_NO() || $this->getRES_PHONE_NO())
				{
					$flagSuitableTime=1;
					$flagIsd=1;
					if($this->getALT_MOBILE())
					{
						if($this->fromMobile)
						{
							if(substr($this->getALT_MOBILE(),1,2)!=91)
							$flagIsd=1;
						}
						elseif(substr($this->getALT_MOBILE(),0,2)!=91)
							$flagIsd=1;
						else
						$flagIsd=0;
					}
					if($this->getMOB_PHONE_NO() && $flagIsd==1)
					{
						if($this->profileObj->getISD()!=91)
							$flagIsd=1;
						else
							$flagIsd=0;
					}
					if($this->getRES_PHONE_NO() && $flagIsd==1)
					{
						if($this->profileObj->getISD()!=91)
							$flagIsd=1;
						else
							$flagIsd=0;
					}

					if($flagIsd==0)
					{
						$flagVerified=0;
						if(($this->getALT_MOBILE() && $this->getVERIFIED_ALT_MOB()==Messages::YES))
							$flagVerified=1;
						if(($this->getRES_PHONE_NO() && $this->getVERIFIED_LANDLINE()==Messages::YES) && $flagVerified==0)
							$flagVerified=1;
						if(($this->getMOB_PHONE_NO() && $this->getVERIFIED_MOB()==Messages::YES) && $flagVerified==0)
							$flagVerified=1;
						
						if($flagVerified==1)
						{
							$emailMessengerFlag=0;
						}
						else
						{
							$emailMessengerFlag=1;
						}
						
						
					}
					else
					{
						$emailMessengerFlag=1;
					}
				}
			else
				$flagNumber=1;
		}
		else
			$flagNumber=1;

			if(!$flagNumber)
			{	
				if($this->getMOB_PHONE_NO())
				{
					$contactDetailsArr[$count]["LABEL"] = "Mobile No.";
					if($this->getMOB_PHONE_OWNER_NAME())
					{
						$contactDetailsArr[$count]["LABEL"] .= "of ".$this->getMOB_PHONE_OWNER_NAME();
						if($this->getMOB_PHONE_OWNER_NUMBER())
						{
							$contactDetailsArr[$count]["LABEL"] .= " (".$this->getMOB_PHONE_OWNER_NUMBER().")";
						}
					}
					$contactDetailsArr[$count]["VALUE"] = $this->getMOB_PHONE_NO();
					$contactDetailsArr[$count]["REPORT"] = 1;
					if($this->fromMobile)
					$contactDetailsArr[$count]["mobileTag"]=1;
					$count++;
				}
				
				if($this->getALT_MOBILE())
				{
					$contactDetailsArr[$count]["LABEL"] = "Alternate Mobile No.";
					if($this->getALT_MOBILE_LABEL())
					{
						$contactDetailsArr[$count]["LABEL"] .= $this->getALT_MOBILE_LABEL();
					}
					$contactDetailsArr[$count]["VALUE"] = $this->getALT_MOBILE();
					$contactDetailsArr[$count]["REPORT"] = 1;
					if($this->fromMobile)
					$contactDetailsArr[$count]["mobileTag"]=1;
					$count++;
				}
				
				if($this->getRES_PHONE_NO())
				{
					$contactDetailsArr[$count]["LABEL"] = "LandLine No.";
					if($this->getRES_PHONE_OWNER_NAME())
					{
						$contactDetailsArr[$count]["LABEL"] .= "of ".$this->getRES_PHONE_OWNER_NAME();
						if($this->getRES_PHONE_OWNER_NUMBER())
						{
							$contactDetailsArr[$count]["LABEL"] .= " (".$this->getRES_PHONE_OWNER_NUMBER().")";
						}
					}
					$contactDetailsArr[$count]["VALUE"] = $this->getRES_PHONE_NO();
					$contactDetailsArr[$count]["REPORT"] = 1;
					if($this->fromMobile)
					$contactDetailsArr[$count]["mobileTag"]=1;
					$count++;
				}
			
				if($flagSuitableTime)
				{
					if($this->getTIME_TO_CALL_START())
						{
							$contactDetailsArr[$count]["LABEL"] = "Suitable time to call";
							$contactDetailsArr[$count]["VALUE"] = $this->getTIME_TO_CALL_START(). " to ". $this->getTIME_TO_CALL_END();
							$count++;
						}
				}
			}
		//if($flagNumber || $emailMessengerFlag)
		if(1)//Ticket #1903
		{
			if($this->getEMAIL_ID())
			{
				$contactDetailsArr[$count]["LABEL"] = "Email Id";
				$contactDetailsArr[$count]["VALUE"] = $this->getEMAIL_ID();
				$count++;
			}
		
			if($this->getSHOW_MESSENGER())
			{
				$contactDetailsArr[$count]["LABEL"] = "Messanger ID-1";
				$contactDetailsArr[$count]["VALUE"] = $this->getSHOW_MESSENGER();
				$count++;
			}
			if($this->getSHOW_MESSENGER2())
			{
				$contactDetailsArr[$count]["LABEL"] = "Messanger ID-2";
				$contactDetailsArr[$count]["VALUE"] = $this->getSHOW_MESSENGER2();
				$count++;
			}
			
		}
		if($this->getBLACKBERRY())
		{
			$contactDetailsArr[$count]["LABEL"] = "Blackberry Pin";
			$contactDetailsArr[$count]["VALUE"] = $this->getBLACKBERRY();
			$count++;
		}
		if($this->getLINKEDIN())
		{
			$contactDetailsArr[$count]["LABEL"] = "Linkedin URL";
			$contactDetailsArr[$count]["VALUE"] = $this->getLINKEDIN();
			$count++;
		}
		if($this->getFACEBOOK())
		{
			$contactDetailsArr[$count]["LABEL"] = "Facebook URL";
			$contactDetailsArr[$count]["VALUE"] = $this->getFACEBOOK();
			$count++;
		}

		if($this->getSHOW_PARENTS_ADDRESS())
		{
			$contactDetailsArr[$count]["LABEL"] = "Parent's address";
			$contactDetailsArr[$count]["VALUE"] = $this->getSHOW_PARENTS_ADDRESS();
			$count++;
		} 
		if($this->getSHOW_ADDRESS())
		{
			$contactDetailsArr[$count]["LABEL"] = "Address";
			$contactDetailsArr[$count]["VALUE"] = $this->getSHOW_ADDRESS();
			$count++;
		}
		if($this->getRELATION_NAME())
		{
			$contactDetailsArr[$count]["LABEL"] = "Profile posted by";
			$contactDetailsArr[$count]["VALUE"] = $this->getRELATION_NAME();
			$count++;
		}
		if($this->getRM_VALUE() && $this->getRM_LABEL())
		{
			$contactDetailsArr[$count]["LABEL"] = $this->getRM_LABEL();
			$contactDetailsArr[$count]["VALUE"] = $this->getRM_VALUE();
			$count++;
		}
		return $contactDetailsArr;
	}
			
	
}
