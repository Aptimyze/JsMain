<?php

/**
 * desktopRegister1 class
 *
 * @author Esha Jain <esha.jain@jeevansathi.com>
 * @package jeevansathi
 * @subpackage registration
 */
class desktopRegister1 extends registrationBaseClass {
 
  /*
   * Declaring Memeber Varibales
   */

  /*
   * Declaring and Defining Member Function
   */
   
  public function __construct($objController) 
  {
    $this->PAGE_ID = RegistrationEnums::$JSPC_REG_PAGE[1];
    $this->NEXT_PAGE_ID = RegistrationEnums::$JSPC_REG_PAGE[2];

    //Call parent constructor
    parent::__construct($objController);

    //Init Source
    $this->initSource();

    //InitMe
    $this->initMe();
  }
  /**
   * function initializing page 1 related variables like source setting legal variables, jsb9 tracking
   * @access Private
   * @return Void
   * <p>
   * </p>
   */
  
  private function initMe(){
    RegistrationFunctions::setLegalVariables($this->request);
    
    $this->setSlot(Jsb9Enum::jsRegPage1Url);
    
    $this->reg_params['source'] = $this->sourceVar['source'];    
    sfContext::getInstance()->getResponse()->setCanonical(sfConfig::get("app_site_url") . "/register/page1");
    
  }
  /**
   * function called before first time display of the page to set some pre display variables and pixel code
   * @access Public
   * @return Void
   * <p>
   * </p>
   */

  public function preDisplay() 
  {
    parent::preDisplay();
    $authenticationObj = new WebAuthentication;
    $authenticationObj->removeLoginCookies();
    $campaignData = RegistrationFunctions::getCampaignVars($this->request,$jsonFormat=1);
    if($campaignData)
        $this->campaignData = $campaignData;
  }
  /**
   * function called to update jprofile and insert name
   * @access Public
   * @return Void
   * <p>
   * </p>
   */

  public function submit() 
  {
    if ($this->sourceVar['hit_source'] != '0') 
    {
      $dbSource = new MIS_SOURCE();
      $force_mail = $dbSource->getSourceFields("FORCE_EMAIL", $this->sourceVar['tieup_source']);

      if ($force_mail["FORCE_EMAIL"] == 'Y')
        $this->email_validation = 'Y';
      
      $jprofileDefaultData = $this->getJprofileDefaultData();
      $this->id = $this->form->updateData('', $jprofileDefaultData);
    }
  }
/*
 * things to be done after page data submit
 * like jpartner updates,source,lead creation and updation
 *  
 */
  public function postSubmit() 
  {
    $this->loginProfile->getDetail($this->id, "PROFILEID");
    $username = $this->loginProfile->getUSERNAME();

    $this->request->setAttribute('username', $username);
    $this->request->setAttribute('profileid', $this->id);
    $this->request->setAttribute('loginData', array('PROFILEID' => $this->id));

    $loginObj=AuthenticationFactory::getAuthenicationObj();
    $loginObj->login($this->arrFormValues['email'],$this->arrFormValues['password'],true);

    RegistrationMisc::updateAlertData($this->id, $this->alertArr);

    parent::postSubmit();

    $this->sourceVar['source'] = RegistrationFunctions::setSourceHomeCookie($this->id);
    
    RegistrationFunctions::putCampaignVars($this->id,$this->request->getParameter("campaignData"));

    RegistrationMisc::contactArchiveUpdate($this->loginProfile, $this->pageVar['ip']);

    if ($_COOKIE['OPERATOR'] != '')
      RegistrationFunctions::operatorAssigning($this->id, $_COOKIE['OPERATOR'], $this->sourceVar['tieup_source']);

    else if ($this->sourceVar['source'] == 'onoffreg' && $_COOKIE['JS_LEAD'])
      RegistrationMisc::updateSugarLead($id, $username, $this->sourceVar['source']);

    RegistrationFunctions::trackTieupParams($this->tieupParams, $this->id);

    if ($this->pageVar['suspectedCheck'])
      SendMail::send_email('esha.jain@jeevansathi.com', $id, "Profileid of suspected email-id", "register@jeevansathi.com");

    RegistrationMisc::insertInIncompleteProfileAndNames($this->loginProfile);

    RegistrationFunctions::unsetCampaignCookies($this->request->getParameter("domain"));

// email for verification
          $emailUID=(new NEWJS_EMAIL_CHANGE_LOG())->insertEmailChange($this->loginProfile->getPROFILEID(),$this->loginProfile->getEMAIL());
          
          (new emailVerification())->sendVerificationMail($this->id,$emailUID);
          ////////
                    

    if ('C' == $this->pageVar['secondary_source'])
      RegistrationCommunicate::sendEmailAfterRegistrationIncomplete($this->loginProfile);
    $this->regLead();
//    RegistrationMisc::updateLeadConversion($this->loginProfile->getEMAIL(), $this->leadid);

    unset($this->form);
    //$this->forward("register", "page2");
  }
  protected function regLead()
  {
                $paramArr=array("EMAIL"=>$this->loginProfile->getEMAIL(),"RELATIONSHIP"=>"","GENDER"=>"","DTOFBIRTH"=>'',"MTONGUE"=>'',"SOURCE"=>$source,"ISD"=>'',"PHONE_MOB"=>$this->loginProfile->getPHONE_MOB(),"LEAD_CONVERSION"=>'Y');
                $dbRegLead = new MIS_REG_LEAD();
                $leadFlag=$dbRegLead->insert($paramArr);
		if($leadFlag)
		{
			$this->leadid = $dbRegLead->getLastInsertId();
		}
                else
                {
                        $lead_flag=$dbRegLead->selectValues($this->loginProfile->getEMAIL());

                        if($lead_flag=='N')
                                $this->leadid=$dbRegLead->replaceValues($paramArr);
                }
                $dbMiniAjaxLead = new MIS_MINI_REG_AJAX_LEAD();
                $dbMiniAjaxLead->updateRegisterEmail($this->loginProfile->getEMAIL());
                if ($this->leadid) {
                        $dbLeadConversion = new MIS_LEAD_CONVERSION();
                        $dbLeadConversion->insertConvertedLead($this->leadid);
                }
  }
  protected function preProcess()
  {
	$this->pageVar[groupNameParams]=RegistrationFunctions::assignGroupName($this->sourceVar['source']);
	$this->groupname = $this->pageVar['groupNameParams'][GROUPNAME];
	$this->sourcename = $this->sourceVar['source'];
  }

  public function preSubmit() {
    
    //Tieup Params
    $this->tieupParams = RegistrationFunctions::getTieupParams($this->request);
    
    //IP ADDRESS
    $this->pageVar['ip'] = CommonFunction::getIP();
    //****to check suspected registration from ip address****
    $this->pageVar['suspectedCheck'] = CommonFunction::suspectedIP($this->pageVar['ip']);
    
    $this->affiliateid=$this->request->getParameter('affiliateid');
        
    $dbBlockIP = new NEWJS_BLOCK_IP();
    if ($dbBlockIP->blockIP($this->pageVar['ip']))
      die("Too many requests !");
    else
      $dbBlockIP->insertIP($this->pageVar['ip']);
  }

  /*
   * Function to Process Source
   */
  private function processSource() {
    
    if ($this->sourceVar['source'] == 'mailer_adc') 
    {
      $siteDownUrl = JsConstants::$siteUrl . "/site_down.htm";
      $this->objController->redirect($siteDownUrl);
      die;
    }
    
    $this->pageVar['crm'] = RegistrationFunctions::getCrm($this->sourceVar['source']);
		$this->pageVar['secondary_source'] = RegistrationFunctions::getSecondarySource($this->request);
    $this->pageVar['LIVE_CHAT_URL'] =  str_replace("<SITE_URL>", JsConstants::$siteUrl, RegistrationEnums::LIVE_HELP_CHAT_URL);

    $this->pageVar['operator'] = RegistrationFunctions::setOperatorCookie($this->request);
  }
/*
 * get jprofile data like keywords,incomplete,activated,age,etc
 */
  public function getJprofileDefaultData() 
  {
    //get keywords for age, gender,caste,height
    $keywords = RegistrationMisc::getKeywords($this->arrFormValues);
    $this->alertArr = RegistrationMisc::getLegalVariables(sfContext::getInstance()->getRequest());

    $matchDef = ($this->alertArr[SERVICE_EMAIL] == 'S') ? 'A' : 'U';
    $smsDef = ($this->alertArr[SERVICE_SMS] == 'S') ? 'Y' : 'N';

    $age = CommonFunction::getAge($this->form->getValue('dtofbirth'));

    $phone = $this->form->getValue('phone_res');
    if ($phone[landline])
      $phone_with_std = $phone[std] . $phone[landline];

    $now = date("Y-m-d G:i:s");
    $today = CommonUtility::makeTime(date("Y-m-d"));

    return $jprofileArr = array('INCOMPLETE' => 'Y', 'ACTIVATED' => 'N', 'SCREENING' => 0, 'SERVICE_MESSAGES' => $this->alertArr[SERVICE_EMAIL], 'ENTRY_DT' => $now, 'MOD_DT' => $now, 'LAST_LOGIN_DT' => $today, 'SORT_DT' => $now, 'IPADD' => $this->pageVar['ip'], 'PROMO_MAILS' => $this->alertArr[PROMO_MAIL], 'CRM_TEAM' => $this->pageVar['crm'], 'PERSONAL_MATCHES' => $matchDef, 'GET_SMS' => $smsDef, 'SEC_SOURCE' => $this->pageVar['secondary_source'], 'KEYWORDS' => $keywords, 'AGE' => $age, 'PHONE_WITH_STD' => "$phone_with_std","SHOWPHONE_MOB" =>"Y");
  }
  /*
   * isSubmit()
   * Method to check is Form Submited or not
   * @return Boolean Value
   */
  protected function isSubmit() {
    return ($this->request->isMethod("POST") && $this->getFormValues() );
  }
  /*
   * before validating bind the form
   */
  public function preValidate() {
	parent::preValidate();
  }

  /*
   * Method for intializting source related all params, cookies and logic
   */
  private function initSource(){    
    //checking if source set in cookie
    if($_COOKIE["source"])
    {
      $this->request->setParameter("source",$_COOKIE["source"]);
    }
    //Create source tracking object to track source and set cookie
    $this->sourceVar = RegistrationFunctions::getSourceParams($this->request);    
    
    //If form is not submit in this request then assign source params
    if(false === $this->isSubmit()){
      
      $this->sourceVar['sourceTracking']->SourceTracking();
      $this->sourceVar['source'] = $this->sourceVar['sourceTracking']->getSource();
    }   
    $this->processSource();
    
  }
  
  /*
   * Function to assign templates Varibales
   */
  protected function assignTemplateVariables(){
    parent::assignTemplateVariables();    
    $this->objController->source = $this->source;
  }

}
