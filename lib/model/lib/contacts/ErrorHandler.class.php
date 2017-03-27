<?php
/**
 * 
/**
 *CLASS ErrorHandler
 * The ErrorHandler class contains the functions to check any kind of error while doing the contact
 * between sender and receiver.
 *<BR>
 * How to call this file<BR> 
 * <code>
    * ***Fetch array of error function to call
    * $errorHandlerObj=new ErrorHandler(ContactHandler $contactHandlerObj);
		* $errorHandlerObj->checkError();
		* //to retrieve error message
		* $errorHandlerObj->getErrorMessage();
		* </code>
 * PHP versions 4 and 5
 * @package   jeevansathi
 * @subpackage   contacts
 * @author    hemant agarwal, rohit khandelwal
 * @copyright 2012 
 * @version   SVN: 9619
 * @link      http://devjs.infoedge.com/mediawiki/index.php/FTO
  */
class ErrorHandler
{
	/** constants
	 */
	const DAY = 'day';
	const WEEK = 'week';
	const MONTH = 'month';
	const OVERALL = 'overall';
	const DUP_LIVE_DATE = '2012-08-13';
	const INCOMPLETE = 'INCOMPLETE';
	const UNDERSCREENING = 'UNDERSCREENING';
	const SAMEGENDER = 'SAMEGENDER';
	const FILTERED = 'FILTERED';
	const PHONE_NOT_VERIFIED = 'PHONE_NOT_VERIFIED';
	const CONT_VIEW_LIMIT = 'VIEW_LIMIT';
	const REMINDER_LIMIT = 'REMINDER_LIMIT';
	const ALREADY_CONTACTED_IU='ALREADY_CONTACTED_IU';
	const DECLINED = 'DECLINED';
	const DELETED = 'DELETED';
	const PRIVILEGE = 'PRIVILEGE';
	const POST = 'POST';
	const PRE = 'PRE';
	const VIEWED_LIMIT_COUNT = 100;
	const REMINDER_COUNT = 3;
	const KEY_NOT_PRESENT = 'ErrorHandler class key not present';
	const ERROR_FOUND = 2;
	const EOI_ACTION_TYPE="ContactHandler::ACCEPT,ContactHandler::CANCEL,ContactHandler::DECLINE,ContactHandler::CANCELINITIATED,ContactHandler::SEND_REMINDER,ContactHandler::INITIATED,ContactHandler::COMMUNICATION";
	const EOI_CONTACT_LIMIT = 'EOI_CONTACT_LIMIT';
	const PROFILE_HIDDEN ='PROFILE_HIDDEN';
	const PROFILE_VIEWED_HIDDEN ='PROFILE_VIEWED_HIDDEN';
	const PROFILE_IGNORE = "PROFILE_IGNORE";
	
	const USERNAME='USERNAME';
	const FROMSEARCH='FROMSEARCH';
	const SEARCHID='SEARCHID';
	const ENGINETYPE='ENGINETYPE';
	const HISHER = 'HISHER';
	const LIMIT = 'LIMIT';
	const PAID_FILTERED_INTEREST_NOT_SENT = 'PAID_FILTERED_INTEREST_NOT_SENT';
	const PAID_FILTERED_INTEREST_SENT = 'PAID_FILTERED_INTEREST_SENT';
	const REMINDER_SENT_BEFORE_TIME = 'REMINDER_SENT_BEFORE_TIME';
	const SECOND_REMINDER_BEFORE_TIME ='SECOND_REMINDER_BEFORE_TIME';
	/**
	 * 
	 * Used to initialize object of ErrorHandler class.
	 * @param ContactHandler $contactHandlerObj
	 * @uses $contactHandlerObj
	 * @uses $errorTypeArr
	 * @uses updateErrorBits()
	 */
	function __construct($contactHandlerObj)
	{
		Messages::setViewerChecksum(CommonFunction::createChecksumForProfile($contactHandlerObj->getviewer()->getPROFILEID()));
		Messages::setViewedChecksum(CommonFunction::createChecksumForProfile($contactHandlerObj->getviewed()->getPROFILEID()));
		$this->contactHandlerObj = $contactHandlerObj;
		$this->errorTypeArr = array(ErrorHandler::SAMEGENDER=>0,ErrorHandler::FILTERED=>0,ErrorHandler::EOI_CONTACT_LIMIT=>0,ErrorHandler::INCOMPLETE=>1,ErrorHandler::UNDERSCREENING=>1,ErrorHandler::PHONE_NOT_VERIFIED=>0,ErrorHandler::DECLINED=>0,ErrorHandler::DELETED=>0,ErrorHandler::PRIVILEGE=>1,ErrorHandler::POST=>0,ErrorHandler::PRE=>0,ErrorHandler::PROFILE_HIDDEN=>0,ErrorHandler::CONT_VIEW_LIMIT=>0,ErrorHandler::REMINDER_LIMIT=>0,ErrorHandler::ALREADY_CONTACTED_IU=>0,ErrorHandler::LIMIT=>0,ErrorHandler::PROFILE_IGNORE=>0,ErrorHandler::REMINDER_SENT_BEFORE_TIME=>1,ErrorHandler::SECOND_REMINDER_BEFORE_TIME=>1); 
		$this->updateErrorBits();
		
	}
	
	/******************** getter setters  *******************/
	
	/**
    * Used to set error message from Messages class.
    * @param string $error
    * @return void
    * @access public
    */	
	public function setErrorMessage($error)
	{
		$this->error_msg = Messages::getMessage($error);
	}
	/**
    *
    * 
    * @return string
    * @access public
    */		
	public function getErrorMessage()
	{ 
		return $this->error_msg; 
	}
	/**
    * set the array errorTypeArr value for the given type.
    * @param string $type
    * @param int $value
    * @return void
    * @access public
    */	
	public function setErrorType($type,$value)
	{
		$this->errorTypeArr[$type]=$value;
	}
	/**
    *returns the error array having  bits set for no error,probable errors and error occured of all kinds of error.
    * 
    * @return array
    * @access public
    */
	public function getErrorType()
	{
		return $this->errorTypeArr;
	}
	/**
    * returns the error array having bits set for error occured of all kinds of error.
    * @return array
    * @access public
    */
	public function getAllError()
	{		
		$errorArr = array();
		foreach($this->errorTypeArr as $key=>$val)
		{
			if($val == ErrorHandler::ERROR_FOUND)
				$errorArr[]=$key;
		}
		return $errorArr;
	}
	
	/**
	 *Update bits for probable erros
	 *@param bool $errorKey
	 *@return void
	 *@uses newjs_CONTACT_ERROR
	 *
	 */	
	function updateErrorBits($errorKey = "")
	{
		//Based on input parameters ,array elements will be set for possible errors.
			
		if($errorKey)
		{
			if(isset($this->errorTypeArr[$errorKey]))
				$this->errorTypeArr[constant("ErrorHandler::".$errorKey)] = 1;
			else
					throw new JsException("",ErrorHandler::KEY_NOT_PRESENT);
		}
		else
		{
			$contactErrorObj = new ContactError();
			$errorArr = $contactErrorObj->getErrorValues($this->contactHandlerObj);
			
			if(is_array($errorArr))
			{
				foreach($errorArr as $key=>$val)
				{
					$this->errorTypeArr[$val] = 1;
				}
			}
		}	
	}
	
	
	/**
	 * Returns false when error exists and action must be stopped.
	 * @return bool true of false
	 * @uses checkPrivilegeError()
	 * @uses checkSameGender()
	 * @uses checkDeleted()
	 * @uses checkViewLimit()
	 * @uses checkContactlimit()
	 * @uses checkUnderScreening()
	 * @uses checkIncomplete()
	 * @uses checkHiddenProfile()
	 * @uses checkProfileFiltered
	 * @uses reminderLimit()
	 * @uses checkDeclineError()
	 * @uses setErrorMessage()
	 * @uses setErrorType()
	 * @uses checkIgnored()
	 * @access public
	 * 
	 *
	 * 
     */	
	function checkError()
	{	 
		//11. Ignored Profile
		$error = $this->checkIgnoreProfile();
		if($error)
		{	

			$this->setErrorMessage($error);
			$this->setErrorType(ErrorHandler::PROFILE_IGNORE,ErrorHandler::ERROR_FOUND);
			return false;
		}

		$error = $this->checkViewedHiddenProfile();
		if($error)
		{
			$this->setErrorMessage($error);
			$this->setErrorType(ErrorHandler::PROFILE_VIEWED_HIDDEN,ErrorHandler::ERROR_FOUND);
			return false;
		}

		//0. Privilege error	
		$error = $this->checkPrivilegeError();
		if($error)
		{
				
			$this->setErrorMessage($error);	
			$this->setErrorType(ErrorHandler::PRIVILEGE,ErrorHandler::ERROR_FOUND);
			return false;
		}
		
		//1.Same Gender,Set Mesage and return
						
		$error = $this->checkSameGender();
		if($error)
		{
			$this->setErrorMessage($error);	
			$this->setErrorType(ErrorHandler::SAMEGENDER,ErrorHandler::ERROR_FOUND);
			return false;
		}	
		
		
		//2. Contact Deleted error
		$error = $this->checkDeleted();
		if($error)
		{	
			
			$this->setErrorMessage($error);
			$this->setErrorType(ErrorHandler::DELETED,ErrorHandler::ERROR_FOUND);
			return false;
		}
		
		//3. View Limit
		$error = $this->checkViewLimit();
		if($error)
		{
			$this->setErrorMessage($error);
			$this->setErrorType(ErrorHandler::CONT_VIEW_LIMIT,ErrorHandler::ERROR_FOUND);
			return false;
		}
				
		//4. Contact limit		
		$error = $this->checkContactlimit();
		if($error)
		{
			$this->setErrorMessage($error);
			$this->setErrorType(ErrorHandler::EOI_CONTACT_LIMIT,ErrorHandler::ERROR_FOUND);
			return false;
		}
		
		// 5. Underscreening/Incomplete/Hidden		
		$error = $this->checkUnderScreening();
		if($error)
		{
				$this->updateErrorBits(ErrorHandler::ALREADY_CONTACTED_IU);
				$this->setErrorType(ErrorHandler::UNDERSCREENING,ErrorHandler::ERROR_FOUND);
					
				if($this->contactHandlerObj->getEngineType() == ContactHandler::INFO)
				{
					$this->setErrorMessage($error);
					return false;
				}
		}		
		
		$error = $this->checkIncomplete();
		if($error)
		{
			  $this->updateErrorBits(ErrorHandler::ALREADY_CONTACTED_IU);
				$this->setErrorType(ErrorHandler::INCOMPLETE,ErrorHandler::ERROR_FOUND);
			
				if($this->contactHandlerObj->getEngineType() == ContactHandler::INFO)
				{
					$this->setErrorMessage($error);
					return false;
				}
		}
		
		//Already contacted by incomplete underscreening user.
		$error=$this->IncompleteUnderScreeningAlreadyContacted();
		if($error)
		{
			$this->setErrorType(ErrorHandler::ALREADY_CONTACTED_IU,ErrorHandler::ERROR_FOUND);
			$this->setErrorMessage($error);
			return false;
		}
		
		$error = $this->checkHiddenProfile();
		if($error)
		{
				$this->setErrorType(ErrorHandler::PROFILE_HIDDEN,ErrorHandler::ERROR_FOUND);
				$this->setErrorMessage($error);
				return false;
		}
		
		//6. Filtered profile
		
		if($this->checkProfileFiltered())
		{
				$this->setErrorType(ErrorHandler::FILTERED,ErrorHandler::ERROR_FOUND);
				if($this->contactHandlerObj->getEngineType()==ContactHandler::INFO)
				{					
					if($this->checkPaid())
					{	
							$name = $this->contactHandlerObj->getViewed()->getUSERNAME();
							
							if($this->interestNotSent())
							{
								$this->setErrorType(ErrorHandler::PAID_FILTERED_INTEREST_NOT_SENT,ErrorHandler::ERROR_FOUND);
								$error = Messages::PAID_FILTERED_INTEREST_NOT_SENT;
								$error = str_replace("{{UNAME}}",$name, $error);
								$this->setErrorMessage($error);
								return false;
							}
							else
							{ 
								$this->setErrorType(ErrorHandler::PAID_FILTERED_INTEREST_SENT,ErrorHandler::ERROR_FOUND);
								$error = Messages::PAID_FILTERED_INTEREST_SENT;
								$error = str_replace("{{UNAME}}",$name, $error);
								$this->setErrorMessage($error);
								return false;
							}

					}
					$error = Messages::FILTERED;
					$this->setErrorMessage($error);
					return false;
				}			
		}
		
		//7. Reminder limit		
		$error = $this->reminderLimit();
		if($error)
		{
			$this->setErrorMessage($error);
			$this->setErrorType(ErrorHandler::REMINDER_LIMIT,ErrorHandler::ERROR_FOUND);
			return false;
		}
		
		//8. Contact Decline error
		/*$error = $this->checkDeclineError();
		if($error)
		{		
			$this->setErrorMessage($error);
			$this->setErrorType(ErrorHandler::DECLINED,ErrorHandler::ERROR_FOUND);
			return false;
		}*/
		
		//9. Incomplete,hidden,deleted,underscreening viewed profile
		$error = $this->checkViewedStatus();
		if($error)
		{		
			$this->setErrorMessage($error);
			$this->setErrorType(ErrorHandler::DECLINED,ErrorHandler::ERROR_FOUND);
			return false;
		}
		
		//10. Unverified phone for Paid Members.
	    $error = $this->checkPhoneVerificationForPaid();
		if($error)
		{
			$this->setErrorMessage($error);
			$this->setErrorType(ErrorHandler::PHONE_NOT_VERIFIED,ErrorHandler::ERROR_FOUND);
			return false;
		}

		//11. Check if the reminder was sent before 24 hours or not.
		$error = $this->checkReminderSentBeforeDay();
		if($error)
		{
			$this->setErrorMessage($error);
			if($error['ID'] == 1)
			$this->setErrorType(ErrorHandler::REMINDER_SENT_BEFORE_TIME,ErrorHandler::ERROR_FOUND);
			else
				$this->setErrorType(ErrorHandler::SECOND_REMINDER_BEFORE_TIME,ErrorHandler::ERROR_FOUND);
			return false;
		}

		return true;
	}	
	
/**************** BEGIN: error function definitions ********************/

	/**
	 * Already contacted by incomplete underscreening user
	 * @return String $Message
	 */
	 function IncompleteUnderScreeningAlreadyContacted()
	 {
		if($this->errorTypeArr[ErrorHandler::ALREADY_CONTACTED_IU])
		{
			$viewerObj=$this->contactHandlerObj->getViewer();
			$viewedObj=$this->contactHandlerObj->getViewed();
			$contactType=$this->contactHandlerObj->getContactType();
		 	$hisher="his";
			if($viewedObj->getGENDER()=="F")
			{				
				$hisher="her";
			}
			if($contactType!=ContactHandler::NOCONTACT && $contactType!="")
			{
				if($viewerObj->getPROFILE_STATE()->getActivationState()->getUNDERSCREENED()==Messages::NO)
					$Message=Messages::getIncompleteMessagewithButton(MESSAGES::ANY_INCOMPLETE_EXP);
				else
				{			 		
					$Message=Messages::getMessage(Messages::CONTACT_UNDERSCREENING,array(self::HISHER=>$hisher));
				}
			}
			else
			{
				 $contacts_temp_obj = new NEWJS_CONTACTS_TEMP();
		
				 if($contacts_temp_obj->getTempContacts($viewerObj->getPROFILEID(),$viewedObj->getPROFILEID()))
				{
					if($viewerObj->getPROFILE_STATE()->getActivationState()->getUNDERSCREENED()==Messages::YES)
						$Message=Messages::getMessage(Messages::CONTACT_UNDERSCREENING,array(self::HISHER=>$hisher));
					else
						$Message=Messages::getIncompleteMessagewithButton(Messages::CONTACT_INCOMPLETE);
				}
			}
			 return $Message;
		} 
	 }
	/**
	 * Check if Incomplete,hidden,deleted,underscreening viewed profile
	 * @return boolean true/false
	 */
	 function checkViewedStatus()
	 {
		 $viewedObj=$this->contactHandlerObj->getViewed();
		 //Underscreening
		 if($viewedObj->getPROFILE_STATE()->getActivationState()->getUNDERSCREENED()==Messages::YES)
		 {
			 $error = Messages::OTHER_SCREENING;
		 }
		 //Incomplete
		 if($viewedObj->getPROFILE_STATE()->getActivationState()->getINCOMPLETE()==Messages::YES)
		 {
			 $error = Messages::getMessage(Messages::OTHER_INCOMPLETE,array(self::USERNAME=>$viewedObj->getUSERNAME()));
		 }
		 //hidden
		 if($viewedObj->getPROFILE_STATE()->getActivationState()->getHIDDEN()==Messages::YES)
		 {
			 $heshe="he";
			 $hisher="his";
			 if($viewedObj->getGENDER()=="F")
			{
				$heshe="she";
				$hisher="her";
			}
			 $error = Messages::getMessage(Messages::OTHER_HIDDEN,array(self::USERNAME=>$viewedObj->getUSERNAME(),"heshe"=>$heshe,"hisher"=>$hisher));
		 }
		 //deleted
		 if($viewedObj->getPROFILE_STATE()->getActivationState()->getDELETED()==Messages::YES)
		 {
			 $error = Messages::getMessage(Messages::OTHER_DELETED,array(self::USERNAME=>$viewedObj->getUSERNAME()));	 
		 }
		 return $error;
	 }
	/**
	 * Check if both the viewer and viewed are of same gender and set error message.
	 * @return string
	 * @uses $contactHandlerObj
	 * @uses $errorTypeArr
	 *
	 */
	function checkSameGender()
	{
		$error = '';
		if($this->errorTypeArr[ErrorHandler::SAMEGENDER])
		{
			if($this->contactHandlerObj->getViewer()->getGENDER()==$this->contactHandlerObj->getViewed()->getGENDER())
				$error = Messages::SAMEGENDER;
		}
		return $error;
	}
	
	/**
	 * Check if the viewed profile is hidden and set error message.
	 * @return string
	 * @uses $contactHandlerObj
	 * @uses $errorTypeArr
	 */
	function checkViewedHiddenProfile()
	{
		$error = '';
		if($this->contactHandlerObj->getViewed()->getActivated() == 'H')
		{
			$POGID = $this->contactHandlerObj->getViewed()->getUSERNAME();
			$error = Messages::getMessage(Messages::HIDDEN_ERROR,array('POGID'=> $POGID));
		}
		return $error;
	}

	/**
	 * Check if the profile is incomplete and set the error message
	 * @return string 
	 * @uses $errorTypeArr
	 * @uses temporaryInterest()
	 * @uses $contactHandlerObj
	 * 
	 */		
	function checkIncomplete()
	{
		$error = '';
		if($this->errorTypeArr[ErrorHandler::INCOMPLETE] )
		{
			$tempParam = $this->temporaryInterest();
			
			if($tempParam == ErrorHandler::INCOMPLETE)
			{
				if($this->contactHandlerObj->getEngineType() == ContactHandler::EOI)
				{
					$error = Messages::EOI_INCOMPLETE;
				}
				elseif($this->contactHandlerObj->getEngineType() == ContactHandler::INFO)
				{				
					$error = Messages::getIncompleteMessagewithButton(Messages::DETAILS_INCOMPLETE);
				}				
			}
		}
		return $error;		
	}
	
	/**
	 * Check if the profile is under screening and set the error message
	 * @return string
	 * @uses $contactHandlerObj
	 * @uses $errorTypeArr
	 * @uses temporaryInterest()
	 */	
	function checkUnderScreening()
	{
		$error = '';
		
		if($this->errorTypeArr[ErrorHandler::UNDERSCREENING] )
		{
			$tempParam = $this->temporaryInterest();			
			
			if($tempParam == ErrorHandler::UNDERSCREENING )
			{
				if($this->contactHandlerObj->getEngineType() == ContactHandler::EOI)
				{
					$error = Messages::EOI_UNDERSCREENING;
				}
				if($this->contactHandlerObj->getEngineType() == ContactHandler::INFO)
				{
					Messages::setUserChecksum(CommonFunction::createChecksumForProfile($this->contactHandlerObj->getviewed()->getPROFILEID()));
					$error = Messages::getScreeningDetailsMessage();
				}				
			}
		}
		
		return $error;
	}
	
	/** 
	 * Check if the profile is currently hidden and set the error message
	 * @return string
	 * @uses $contactHandlerObj
	 * @uses $errorTypeArr
	 */	
	function checkHiddenProfile()
	{
		$error = '';	
		if($this->errorTypeArr[ErrorHandler::PROFILE_HIDDEN])
		{
			if($this->contactHandlerObj->getViewer()->getPROFILE_STATE()->getActivationState()->getHIDDEN()==Messages::YES)
			{
				$error = Messages::SELF_HIDDEN;
			}
		}
		return $error;		
	}
	
	/**
	 * Check if the profile is filtered.
	 * @return bool
	 * @uses $errorTypeArr
	 * @uses $contactHandlerObj
	 */		
	function checkProfileFiltered()
	{
		if($this->errorTypeArr[ErrorHandler::FILTERED])
		{
			$whyFlag = 0;
			if($this->contactHandlerObj->getPageSource() == 'search' || $this->contactHandlerObj->getPageSource() == 'cc' || $this->contactHandlerObj->getAction()=='POST' || $this->contactHandlerObj->getPageSource() == 'VSM')
				$whyFlag = 1;
			
			$filterObj = UserFilterCheck::getInstance($this->contactHandlerObj->getContactObj()->getSenderObj(),$this->contactHandlerObj->getContactObj()->getReceiverObj(),$whyFlag);
			if($filterObj->getFilteredContact($this->contactHandlerObj->getEngineType()))
			{ 
				return true;
			}
		} 
		return false;
	}
	
	/**
	 * Check if the reminder has been sent more than given limit.
	 * @return string
	 * @uses $errorTypeArr
	 * @uses $contactHandlerObj
	*/
	function reminderLimit()
	{
		$error = '';
		if($this->errorTypeArr[ErrorHandler::REMINDER_LIMIT] && $this->contactHandlerObj->getToBeType()!='E')
		{
			$reminderCount = $this->contactHandlerObj->getContactObj()->getCOUNT();
			if($reminderCount >= ErrorHandler::REMINDER_COUNT)
			{
				if($this->contactHandlerObj->getViewer()->getPROFILE_STATE()->getPaymentStates()->isPAID())
				{
					$error = Messages::REMINDER_LIMIT_PAID;
				}
				else
				{
					$error = Messages::REMINDER_LIMIT_FREE;
				}
			}
		}
		
		return $error;
	}
	
	
	/**
	 * Check if the viewed profile is deleted and set the error message.
	 * @return string 
	 * @uses $errorTypeArr
	 * @uses $contactHandlerObj
	 */
	function checkDeleted()
	{

		$error = '';
		if($this->errorTypeArr[ErrorHandler::DELETED])
		{
			if($this->contactHandlerObj->getViewer()->getPROFILE_STATE()->getActivationState()->getDELETED()==Messages::YES)
			{			
				$error = Messages::SELF_DELETED;
			}
		}
		return $error;	
	}
	
	/**
	 * Check if the viewer is already been declined and set the error message.
	 * @return string
	 * @uses $errorTypeArr	 
	 */	
	function checkDeclineError()
	{
		$error = '';
		if($this->errorTypeArr[ErrorHandler::DECLINED])
		{
			$error = Messages::DECLINED;
		}
		return $error;	
	}
	
	/**
	 *Check if the profile is incomplete or underscreening.
	 * @return string Underscreening or Incomplete
	 * @uses $contactHandlerObj
	 */	
	function temporaryInterest()
	{
		if($this->contactHandlerObj->getViewer()->getPROFILE_STATE()->getActivationState()->getINCOMPLETE()==Messages::YES)
			$success = ErrorHandler::INCOMPLETE;
		else if($this->contactHandlerObj->getViewer()->getPROFILE_STATE()->getActivationState()->getUNDERSCREENED()==Messages::YES)
			$success = ErrorHandler::UNDERSCREENING;
		return $success;
	}
	
	/** 
	 * Check if the contact limit has been reached and set the error message.
	 * @return string 
	 * @uses $errorTypeArr
	 * @uses ProfileMemcacheService to get contacts limits data
	 * @uses CommonFunction::getContactLimits()
	 * @uses CommonFunction::isContactVerified()
	 * @uses $contactHandlerObj
	 */	
	function checkContactlimit()
	{
		$error ='';
		
		//Not to be checked for AP users.
		if($this->contactHandlerObj->getPageSource()=='AP')
					return $error;
					
		$profileMemcacheServiceObj = new ProfileMemcacheService($this->contactHandlerObj->getViewer());
		
    	if($this->errorTypeArr[ErrorHandler::EOI_CONTACT_LIMIT] && $this->contactHandlerObj->getAction()==ContactHandler::POST)
		{
			$limitArr = CommonFunction::getContactLimits($this->contactHandlerObj->getViewer()->getSUBSCRIPTION(),$this->contactHandlerObj->getViewer()->getPROFILEID());
			
			$today_initiated = $profileMemcacheServiceObj->get("TODAY_INI_BY_ME");
			$monthly_initiated = $profileMemcacheServiceObj->get("MONTH_INI_BY_ME");
			$weekly_initiated =  $profileMemcacheServiceObj->get("WEEK_INI_BY_ME");
			$total_contacts = $profileMemcacheServiceObj->get("TOTAL_CONTACTS_MADE");
			$computeAfterDate = $profileMemcacheServiceObj->get("CONTACTS_MADE_AFTER_DUP");
			
			if(($limitArr['DAY_LIMIT']-$today_initiated) <= 0)
			{
				$error = Messages::DAY_LIMIT;
				$this->setErrorType('LIMIT','DAY');
			}
			else if($limitArr['WEEKLY_LIMIT']-$weekly_initiated<=0)
			{
				$error = Messages::WEEK_LIMIT;
				$this->setErrorType('LIMIT','WEEK');
			}
			else if($limitArr['MONTH_LIMIT']-$monthly_initiated<=0)
			{	
				$error = Messages::MON_LIMIT;
				$this->setErrorType('LIMIT','MONTH');
			}
			else if($limitArr['OVERALL_LIMIT']-$total_contacts<=0)
			{ 
				if($this->contactHandlerObj->getViewer()->getPROFILE_STATE()->getPaymentStates()->isPAID())
					$error = Messages::PAID_OVERALL_LIMIT;
			    else
					$error = Messages::getFreeOverAllLimitMessage(Messages::FREE_OVERALL_LIMIT);
				$this->setErrorType('LIMIT','TOTAL');
			}
			else if(!(CommonFunction::isContactVerified($this->contactHandlerObj->getViewer())) && $limitArr['NOT_VALIDNUMBER_LIMIT']-$computeAfterDate<=0)
			{
				
				if($this->contactHandlerObj->getPageSource()=='Search')
				
					$error = Messages::getVerifyPhoneMessage(array(self::FROMSEARCH=>'1',self::SEARCHID=>'',self::ENGINETYPE=>''));
					
				elseif($this->contactHandlerObj->getEngineType()==ContactHandler::EOI)				
					$error = Messages::getVerifyPhoneMessage(array(self::FROMSEARCH=>'',self::SEARCHID=>'',self::ENGINETYPE=>'EOI'));
				
				elseif($this->contactHandlerObj->getEngineType()==ContactHandler::INFO)
					$error = Messages::getVerifyPhoneMessage(array(self::FROMSEARCH=>'',self::SEARCHID=>'',self::ENGINETYPE=>'CONTACT'));
			}
		}
		return $error;		
	}
	
	/**
	 * Check if the alloted count limit has been reached and set the error message.
	 * @return string 
	 * @uses $errorTypeArr
	 * @uses $contactHandlerObj
	 * @uses jsadmin_CONTACTS_ALLOTED
	 * @uses jsadmin_VIEW_CONTACTS_LOG
	 */	
	function checkViewLimit()
	{
		$error = '';
		if($this->errorTypeArr[ErrorHandler::CONT_VIEW_LIMIT] && $this->contactHandlerObj->getViewer()->getPROFILE_STATE()->getPaymentStates()->isPAID())
		{
			$privilegeArray = $this->contactHandlerObj->getPrivilegeObj()->getPrivilegeArray();			
			if($privilegeArray[0]['CONTACT_DETAIL']['VISIBILITY'] == 'N')
			{
				$contactAllotedObj = new jsadmin_CONTACTS_ALLOTED();
				$viewContactLogObj = new jsadmin_VIEW_CONTACTS_LOG();
				
				// check if contact view limit has been exhausted
				$allotedContactFlag = $contactAllotedObj->getViewedContacts($this->contactHandlerObj->getViewer()->getPROFILEID());
				
				//check if profile has been viewed more than alotted no of times
				$spamFlagForReceiver = $viewContactLogObj->checkSpamForReceiver($this->contactHandlerObj->getViewed()->getPROFILEID(), ErrorHandler::VIEWED_LIMIT_COUNT);
				
	            if($allotedContactFlag<=0)
	            {
					$error = Messages::CONT_VIEW_LIMIT;
				}
				elseif($spamFlagForReceiver)
				{
					$error = Messages::CONT_VIEWED_LIMIT;
				}
				
			}
		}
		
		return $error;
	}
	
/**
 *check privilege post action error
 * @return string
 * @access public
 * @uses $errorTypeArr
 * @uses $contactHandlerObj
 */	
	function checkPrivilegeError()
	{	
		$error = '';
		if($this->errorTypeArr[ErrorHandler::PRIVILEGE])
		{
		/*
		 * privArr= contains all privilege user has
		 * actionPrivArr= contains actiontype privileges he can perform based on this state
		 */
			$privArr = $this->contactHandlerObj->getPrivilegeObj()->getPrivilegeArray();
			$actionType=constant("Privilege::".$this->contactHandlerObj->getToBeType());
			// check whether the action  performed(Accept, Delete etc) by the user is allowed or not 
				$arr=$privArr[0][$actionType];
				if(is_array($arr))
				{	
					foreach($arr as $key=>$val)
					{
						
						$userInput=$this->contactHandlerObj->getElements(constant("CONTACT_ELEMENTS::$key"));

						if($val==Messages::NO && $userInput!=Messages::NO && $userInput)
						{
							if($this->checkIfNotPresetMessage($key,$userInput))
							{	
								$error= constant("Messages::$key");	
								return $error;
							}	
						}
					}
				}
				else
				{
				//	print_r($privArr);echo $actionType;die;
					$error = Messages::ACTION_NOT_ALLOWED;	
					return $error;	
				}
						
		}
	}
	/**
 *check privilege Message is Preset Message
 * @return True/False
 * @access public
 * @uses $userInput
 * @uses $contactHandlerObj
 * @uses ProfileDrafts::getInstance()
 */
	function checkIfNotPresetMessage($key,$userInput)
	{
		
		if($key=="MESSAGE")
		{
			$profileObj=$this->contactHandlerObj->getViewer();
			
			//No check for ap  JS Exclusive members.
			if($this->contactHandlerObj->getPageSource()=='AP')
				return false;
				
			$userInput=str_replace("\n","",$userInput);
			$userInput=str_replace("\r","",$userInput);
			
			$draftObj=ProfileDrafts::getInstance($profileObj);
			$pre=$draftObj->getAcceptDrafts();
			$messageAccept=ProfileDrafts::getMessage($pre,ProfileDrafts::PRESET_ACCEPT_DRAFTID,1);
			$pre=$draftObj->getDeclineDrafts();
			$messageDecline=ProfileDrafts::getMessage($pre,ProfileDrafts::PRESET_DECLINE_DRAFTID,1);
			$pre=$draftObj->getEoiDrafts();
			$messageEoi=ProfileDrafts::getMessage($pre,ProfileDrafts::PRESET_DRAFTID,1);
			$systemMes=PresetMessage::getSystemPreset($profileObj);	
			$systemAPMessage=Messages::getMessage(Messages::AP_MESSAGE,array('USERNAME'=>$profileObj->getUSERNAME()));
	
			if(!strcmp($messageAccept,$userInput) || !strcmp($messageDecline,$userInput)|| !strcmp($messageEoi,$userInput) || !strcmp($systemMes,$userInput) || !strcmp($systemAPMessage,$userInput))
			{
				return false;
			}
			else
				return true;
			
		}
			return false;
	}
	/**
	 * check unverify phone for paid user and set the error message.
	 * return string
	 */
	function checkPhoneVerificationForPaid()
	{
		$error = '';
		if($this->errorTypeArr[ErrorHandler::PHONE_NOT_VERIFIED] && $this->contactHandlerObj->getViewer()->getPROFILE_STATE()->getPaymentStates()->isPAID() && $this->contactHandlerObj->getAction()==ContactHandler::PRE)
		{
			if(!(CommonFunction::isContactVerified($this->contactHandlerObj->getViewer())))
				$error= Messages::getUnverifiedPaidMessage(Messages::PAID_UNVERIFY);		
		}
		return $error;
	}


	/**
	 * check Ignore Profile.
	 * return string
	 */

	function checkIgnoreProfile()
	{
		$error ="";
		$ignoreObj = new IgnoredProfiles();
		if($ignoreObj->ifIgnored($this->contactHandlerObj->getViewer()->getPROFILEID(),$this->contactHandlerObj->getViewed()->getPROFILEID()))
			$error = Messages::getMessage(Messages::I_IGNORE_MESSAGE,array("USERNAME"=>$this->contactHandlerObj->getViewed()->getUSERNAME()));
		else if($ignoreObj->ifIgnored($this->contactHandlerObj->getViewed()->getPROFILEID(),$this->contactHandlerObj->getViewer()->getPROFILEID()))
			$error = Messages::getMessage(Messages::IGNORED_MESSAGE,array("USERNAME"=>$this->contactHandlerObj->getViewer()->getUSERNAME()));
		return $error;
	}

	private function checkPaid()
	{
		if($this->contactHandlerObj->getViewer()->getPROFILE_STATE()->getPaymentStates()->isPAID())
		return true;
	 	return false;
	}

	private function interestNotSent()
	{
		if($this->contactHandlerObj->getContactObj()->getCOUNT() == 0)
		return true;
		return false;
	}	


	private function checkReminderSentBeforeDay()
	{  
		$error = '';

		$contactObj = $this->contactHandlerObj->getContactObj();

		if($this->errorTypeArr[ErrorHandler::REMINDER_SENT_BEFORE_TIME] && $this->contactHandlerObj->getToBeType()=="R" && $contactObj->getCOUNT() == 1 )
		{		
 		
		$timeOfLastContact = strtotime($contactObj->getTIME());
		$timeDayAgo = (time() - (3600*24));

		if($timeDayAgo < $timeOfLastContact){
		$error['MSG']= Messages::getReminderSentBeforeTimeMessage(Messages::REMINDER_SENT_BEFORE_TIME);
		$error['ID'] = 1;
		}
		
		}
		else if($this->errorTypeArr[ErrorHandler::SECOND_REMINDER_BEFORE_TIME] &&
$this->contactHandlerObj->getToBeType()=="R" && $contactObj->getCOUNT() == 2)
		{	

		$timeOfLastContact = strtotime($contactObj->getTIME());
		$timeDayAgo = (time() - (3600*24));

		if($timeDayAgo < $timeOfLastContact){
		$error['MSG']= Messages::getReminderSentBeforeTimeMessage(Messages::SECOND_REMINDER_BEFORE_TIME);
		$error['ID'] =2;
		}
		
		}
		return $error;
	}


}
