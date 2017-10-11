<?php
/**
 * ViewContacts contains  methods to calculate individual profile viewcontact details 
 * and set Pre Post component respectively
* <code>
* $this->loginProfile=LoggedInProfile::getInstance();
* $this->profile=Profile::getInstance();
* $this->contactObj = new Contacts($this->loginProfile, $this->profile);
* $contactHandlerObj = new ContactHandler($this->loginProfile,$this->profile,"INFO",$this->contactObj,'CONTACT_DETAIL',ContactHandler::PRE);
* $this->viewContactsObj=ContactFactory::event($contactHandlerObj);
* </code>
 * @package    jeevansathi
 * @subpackage contacts
 * @author     Nitesh Sethi
 * @version 1.0   SVN: $Id:  Privilege 23810 2012-11-16 nitesh.s $
 */

class ViewContacts extends ContactEvent{
	/**
   *
   * This holds ContactHandler Object
   * @access protected
   * @var ContactHandler
   */
	public $contactHandler;
	/**
   *
   * This holds ErrorHandler Object
   *
   * @access protected
   * @var ErrorHandler
   */
	protected $_errorHandlerObj;
	
	/**
   *
   * This holds ContactDetails object
   *
   * @access private
   * @var ContactDetails
   */
	public $contactDetailsObj;
	
	/**
	 * This function used to initilaize the ViewContacts Class object.
	 * @param ContactHandler $contactHandler
	 * @return  void
	 * @access public
	 */
	public function __construct(ContactHandler $contactHandler) {
		$this->contactHandler=$contactHandler;
		parent::__construct();
		
	}
	/**
	 * This function used to set PostComponent.
	 * @uses ContactDetails
	 * @return  void
	 * @access public
	 */
	public function setPostComponent()
	{
		
		$this->component = new PostComponent;
		$this->component->contactDetailsObj= new ContactDetails($this->contactHandler,"directcall",1);
		$privArr=$this->contactHandler->getPrivilegeObj()->getPrivilegeArray();
		
		$draftsObj	=	new ProfileDrafts($this->contactHandler->getViewer());
		$contactType=$this->contactHandler->getContactType();
		if($contactType=='C')
		$draftsArray = $draftsObj->getAcceptDrafts();
		else	
		$draftsArray = $draftsObj->getEoiDrafts();
		if (is_array($draftsArray)) {
		$this->component->drafts = $this->component->cancelDrafts = $draftsArray;
		}
		
		$this->component->innerTpl="profile_cd_paid";
   
	}
	/**
	 * This function used to set PreComponent.
	 * @uses ContactDetails
	 * @return  void
	 * @access public
	 */
	public function setPreComponent()
	{	
		//This will only come when contact details are not visible.
		$this->component = new PreComponent;
		$this->component->contactDetailsObj= new ContactDetails($this->contactHandler,"directcall");
		$privArr=$this->contactHandler->getPrivilegeObj()->getPrivilegeArray();
		
		$draftsObj	=	new ProfileDrafts($this->contactHandler->getViewer());
		$contactType=$this->contactHandler->getContactType();
		
		$JSADMIN_VIEW_CONTACTS_LOG_Obj=new JSADMIN_VIEW_CONTACTS_LOG();
		$viewedPid=$this->contactHandler->getViewed()->getPROFILEID();
		$viewerPid=$this->contactHandler->getViewer()->getPROFILEID();
		$source=CommonFunction::getViewContactDetailFlag($this->contactHandler);	
	
		
		if($contactType=='C')
		$draftsArray = $draftsObj->getAcceptDrafts();
		else	
		$draftsArray = $draftsObj->getEoiDrafts();
		if (is_array($draftsArray)) {
		$this->component->drafts = $this->component->cancelDrafts = $draftsArray;
		}
		if(($this->contactHandler->getViewed()->getPROFILE_STATE()->getPaymentStates()->getPaymentStatus()=="EVALUE"|| $this->contactHandler->getViewed()->getPROFILE_STATE()->getPaymentStates()->getPaymentStatus()=="JSEXCLUSIVE") && $privArr[0]['CONTACT_DETAIL']['VISIBILITY']=='Y')
		{
			$evalueTrackingObj = new EvalueTracking();
			if(strcmp($_SERVER['REDIRECT_URL'],"/profile/viewprofile.php")==0)
			{
				if(!MobileCommon::isMobile())
				$id = $evalueTrackingObj->updateTracking($this->contactHandler->getViewed()->getPROFILEID(),$this->contactHandler->getViewer()->getPROFILEID(),"N",$contactType,$this->contactHandler->getViewer()->getPROFILE_STATE()->getPaymentStates()->getPaymentStatus());
				sfContext::getInstance()->getRequest()->setAttribute('updateEvalueTracking',$id);
		
			}
			else
			{
				$id = $evalueTrackingObj->updateTracking($this->contactHandler->getViewed()->getPROFILEID(),$this->contactHandler->getViewer()->getPROFILEID(),"Y",$contactType,$this->contactHandler->getViewer()->getPROFILE_STATE()->getPaymentStates()->getPaymentStatus());
			}
		}
		if($privArr[0]['CONTACT_DETAIL']['VISIBILITY']=='Y')
		{
			// added for tracking any viewed contacts JSI-58
			
			
			
			if($this->contactHandler->getViewer()->getPROFILE_STATE()->getPaymentStates()->getPaymentStatus()=="FREE")
			{
				if($JSADMIN_VIEW_CONTACTS_LOG_Obj->FinalTotalContactsViewed($viewerPid)>=CONTACT_ELEMENTS::EVALUE_LIMIT)
				{
					$alreadyContactFlag=$JSADMIN_VIEW_CONTACTS_LOG_Obj->alreadyEvalueContactViewed($viewerPid,$viewedPid);
					if($alreadyContactFlag)
					{
						$this->component->innerTpl="profile_cd_paid";
						$this->component->contactDetailsObj->setEvalueLimitUser(CONTACT_ELEMENTS::EVALUE_SHOW);	
					}
					else
						$this->component->contactDetailsObj->setEvalueLimitUser(CONTACT_ELEMENTS::EVALUE_STOP);
				}
				else
				{
					$this->component->innerTpl="profile_cd_paid";
					$cScoreObject = ProfileCompletionFactory::getInstance(null,$this->contactHandler->getViewer(),null);
					$iPCS = $cScoreObject->getProfileCompletionScore();
					if($iPCS <=CONTACT_ELEMENTS::PCS_CHECK_VALUE)
						$this->component->contactDetailsObj->setEvalueLimitUser(CONTACT_ELEMENTS::EVALUE_PCS);
					else
						$this->component->contactDetailsObj->setEvalueLimitUser(CONTACT_ELEMENTS::EVALUE_SHOW);
					if(sfContext::getInstance()->getRequest()->getParameter("stopEvaluetracking") == CONTACT_ELEMENTS::EVALUE_STOP)
						$this->component->contactDetailsObj->setEvalueLimitUser(CONTACT_ELEMENTS::EVALUE_STOP);
					
						
					if($this->component->contactDetailsObj->getEvalueLimitUser()!=CONTACT_ELEMENTS::EVALUE_STOP && $this->component->contactDetailsObj->getEvalueLimitUser()!=CONTACT_ELEMENTS::EVALUE_PCS)
					{
						
						$JSADMIN_VIEW_CONTACTS_LOG_Obj->insertAllotedContacts($viewerPid,$viewedPid,$source);
						
						$profileMemcacheObj=new ProfileMemcacheService($viewerPid);
						$profileMemcacheObj->update('CONTACTS_VIEWED',1);
						$profileMemcacheObj->updateMemcache();
						unset($profileMemcacheObj);
						$profileMemcacheObj=new ProfileMemcacheService($viewedPid);
						$profileMemcacheObj->update('PEOPLE_WHO_VIEWED_MY_CONTACTS',1);
						$profileMemcacheObj->updateMemcache();	
					


						$key=$viewerPid."_CONTACTS_VIEWED"; 
						JsMemcache::getInstance()->remove($key);
					}
				}
			}
			else{
				if(sfContext::getInstance()->getRequest()->getParameter("stopEvaluetracking"))
					$evalueUserDetailiedStopTracking=sfContext::getInstance()->getRequest()->getParameter("stopEvaluetracking");
				else
					$evalueUserDetailiedStopTracking=CONTACT_ELEMENTS::EVALUE_SHOW;
					//echo $evalueUserDetailiedStopTracking;echo CONTACT_ELEMENTS::EVALUE_STOP;die;
				if($evalueUserDetailiedStopTracking!=CONTACT_ELEMENTS::EVALUE_STOP)
				{
					
					$JSADMIN_VIEW_CONTACTS_LOG_Obj->insertAllotedContacts($viewerPid,$viewedPid,$source);				
						$profileMemcacheObj=new ProfileMemcacheService($viewerPid);
						$profileMemcacheObj->update('CONTACTS_VIEWED',1);
						$profileMemcacheObj->updateMemcache();
						unset($profileMemcacheObj);
						$profileMemcacheObj=new ProfileMemcacheService($viewedPid);
						$profileMemcacheObj->update('PEOPLE_WHO_VIEWED_MY_CONTACTS',1);
						$profileMemcacheObj->updateMemcache();

				}
				$this->component->innerTpl="profile_cd_paid";
			}
		}
		else
			$this->component->contactDetailsObj->setEvalueLimitUser(CONTACT_ELEMENTS::EVALUE_NO);
		
		//if from Phonebook Track user 	
		if(sfContext::getInstance()->getRequest()->getParameter("fromPhonebook"))
		{
			$dbObjMIS_PHONEBOOK= new MIS_PHONEBOOK();
			$dbObjMIS_PHONEBOOK->trackUserUsingPhonebook($this->contactHandler->getViewer()->getPROFILEID());
		}
		
		$privArr1=ContactPrivilege::getPrivilegeArray($this->contactHandler);
		if($privArr[0]['CONTACT_DETAIL']['VISIBILITY']!='Y' && CommonFunction::isPaid($this->contactHandler->getViewer()->getSUBSCRIPTION()) &&  $privArr1[0]['CALL_DIRECT']['ALLOWED']=="Y" && $privArr1[0]['CONTACT_DETAIL']['VISIBILITY'] =="P")
		{
			if($this->component->contactDetailsObj->getALT_MOBILE() || $this->component->contactDetailsObj->getMOB_PHONE_NO() || $this->component->contactDetailsObj->getRES_PHONE_NO())
			{
				$this->component->contactDetailsObj->setHiddenPhoneMsg("N");
			}
			else
				$this->component->contactDetailsObj->setHiddenPhoneMsg("Y");
			if($this->component->contactDetailsObj->getHiddenPhoneMsg()=="N" && $this->component->contactDetailsObj->getLEFT_ALLOTED()>CONTACT_ELEMENTS::MIN_CONTACT_ALLOTED)
			{
				$jsadmin_CONTACTS_ALLOTED_OBJ =new jsadmin_CONTACTS_ALLOTED();
				$jsadmin_CONTACTS_ALLOTED_OBJ->updateViewedContactCount($viewerPid);
				$this->component->contactDetailsObj->setLEFT_ALLOTED($this->component->contactDetailsObj->getLEFT_ALLOTED()-1);
				$JSADMIN_VIEW_CONTACTS_LOG_Obj->insertAllotedContacts($viewerPid,$viewedPid,'D');
				$this->component->contactDetailsObj->setPostDirectCall(1);
				$this->component->innerTpl="profile_cd_paid";
			}
		}
		
	}
	/**
	 * This function used for psot action Call Directly.
	 * It uses to insert and update the Viewed count of the Paid profile 
	 * @uses JSADMIN_VIEW_CONTACTS_LOG::insertAllotedContacts()
	 * @uses jsadmin_CONTACTS_ALLOTED::updateViewedContactCount()
	 * @return  void
	 * @access public
	 */
	public function submit()
	{
		if(sfContext::getInstance()->getRequest()->getParameter("fromDetailedProfileAjaxCall"))
			$evalueUserDetailiedStopTracking=CONTACT_ELEMENTS::EVALUE_STOP;
		else
			$evalueUserDetailiedStopTracking=CONTACT_ELEMENTS::EVALUE_SHOW;
		//echo $evalueUserDetailiedStopTracking;echo CONTACT_ELEMENTS::EVALUE_STOP;die;
		if($evalueUserDetailiedStopTracking!=CONTACT_ELEMENTS::EVALUE_STOP)
		{
			$viewedPid=$this->contactHandler->getViewed()->getPROFILEID();
			$viewerPid=$this->contactHandler->getViewer()->getPROFILEID();
			
			//$source=$this->contactHandler->getPageSource();
			//db objects 
			$JSADMIN_VIEW_CONTACTS_LOG_Obj=new JSADMIN_VIEW_CONTACTS_LOG();
			$jsadmin_CONTACTS_ALLOTED_OBJ =new jsadmin_CONTACTS_ALLOTED();
			//insert into tables
			
			
			if(($this->contactHandler->getViewed()->getSHOWPHONE_RES()=="Y" && $this->contactHandler->getViewed()->getPHONE_RES()!="") || ($this->contactHandler->getViewed()->getSHOWPHONE_MOB()=="Y" && $this->contactHandler->getViewed()->getPHONE_MOB()!="") || ($this->contactHandler->getViewed()->getEMAIL()!=="")) {
				if($JSADMIN_VIEW_CONTACTS_LOG_Obj->alreadyContact($viewerPid,$viewedPid)==0)
				{
					$jsadmin_CONTACTS_ALLOTED_OBJ->updateViewedContactCount($viewerPid);
						$profileMemcacheObj=new ProfileMemcacheService($viewerPid);
						$profileMemcacheObj->update('CONTACTS_VIEWED',1);
						$profileMemcacheObj->updateMemcache();
						unset($profileMemcacheObj);
						$profileMemcacheObj=new ProfileMemcacheService($viewedPid);
						$profileMemcacheObj->update('PEOPLE_WHO_VIEWED_MY_CONTACTS',1);
						$profileMemcacheObj->updateMemcache();
				}
				
			}
			//tracking for contacts viewed JSI-58
			$source=CONTACT_ELEMENTS::CALL_DIRECTLY_TRACKING;
			$JSADMIN_VIEW_CONTACTS_LOG_Obj->insertReplaceAllotedContacts($viewerPid,$viewedPid,$source);
			$profileMemcacheObj=new ProfileMemcacheService($viewerPid);
						$profileMemcacheObj->clearInstance();
						$key=$viewerPid."_CONTACTS_VIEWED"; 
						JsMemcache::getInstance()->remove($key);
			//$this->sendMail();
		}
	}
	
	public function sendMail()
	{
		$viewerObj=$this->contactHandler->getViewer();
				$email_sender=new EmailSender(MailerGroup::CALLDIRECTLY);
				$emailTpl=$email_sender->setProfileId($this->contactHandler->getViewed()->getPROFILEID());
				$smartyObj = $emailTpl->getSmarty();
				   $smartyObj->assign("viewerprofileid",$this->contactHandler->getViewer()->getPROFILEID());

			$siblingInfo=$viewerObj->getSiblings();
				$smartyObj->assign("tBrother",$siblingInfo->tbrother);
				$smartyObj->assign("mBrother",$siblingInfo->mbrother);
				$smartyObj->assign("tSister",$siblingInfo->tsister);
				$smartyObj->assign("mSister",$siblingInfo->msister);

			$smallTag = $viewerObj->getAGE();
			 if($viewerObj->getHeight()) $smallTag.=", ".FieldMap::getFieldLabel("height",$viewerObj->getHeight());
			if($viewerObj->getDecoratedReligion()) $smallTag.=", ".$viewerObj->getDecoratedReligion();
			if($viewerObj->getMTONGUE()) $smallTag.=", ".$viewerObj->getDecoratedCommunity();
			if($viewerObj->getMSTATUS()) $smallTag.=", ".$viewerObj->getDecoratedMaritalStatus();			
			if($viewerObj->getDecoratedCaste()) $smallTag.=", ".$viewerObj->getDecoratedCaste();
			if($viewerObj->getEDU_LEVEL_NEW()) $smallTag.=", ".$viewerObj->getDecoratedEducation();
			if($viewerObj->getINCOME()) $smallTag.=", ".FieldMap::getFieldLabel("income_map",$viewerObj->getINCOME());
			if($viewerObj->getOccupation()) $smallTag.=", ".$viewerObj->getDecoratedOccupation();
			
				if($viewerObj->getOccupation() && $viewerObj->getCITY_RES())
						$smallTag.=" in ";
				if($viewerObj->getCITY_RES()&& !$viewerObj->getDecoratedOccupation())
						$smallTag.=", ";
				if($viewerObj->getCITY_RES())  $smallTag.=$viewerObj->getDecoratedCity();
				if($viewerObj->getCOUNTRY_RES())  $smallTag.=", ".$viewerObj->getDecoratedCountry();

				  $smartyObj->assign("SMALL_DETAILS",$smallTag);
		 $email_sender->send($this->contactHandler->getViewed()->getEMAIL());
	}
    
	

	

	
	
}
