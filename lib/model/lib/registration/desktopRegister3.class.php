<?php

/**
 * desktopRegister3 class
 *
 * @author Esha Jain <esha.jain@jeevansathi.com>
 * @package jeevansathi
 * @subpackage registration
 */
class desktopRegister3 extends registrationBaseClass {
 
  /*
   * Declaring Memeber Varibales
   */

  /*
   * Declaring and Defining Member Function
   */
   
  public function __construct($objController) {
    $this->PAGE_ID = RegistrationEnums::$JSPC_REG_PAGE[3];
    $this->NEXT_PAGE_ID = RegistrationEnums::$JSPC_REG_PAGE[4];
    //Call parent constructor
    parent::__construct($objController);
    $this->profileId = $this->loginProfile->getPROFILEID();
    $this->loginProfile->getDetail($this->profileId,"PROFILEID");
    //InitMe
    $this->initMe();
  }
  
  private function initMe(){
    RegistrationFunctions::setLegalVariables($this->request);
    
    $this->setSlot(Jsb9Enum::jsRegPage3Url);
    
    $this->reg_params['source'] = $this->sourceVar['source'];
    sfContext::getInstance()->getResponse()->setCanonical(sfConfig::get("app_site_url") . "/register/page3");
    
  }

  public function preDisplay() {
    parent::preDisplay();
    if($this->request->getParameter("incompleteUser")){
      $this->isIncomplete = true;
      $this->incompletePrefilledData = RegistrationFunctions::getPrefilledDataForUser($this->loginProfile,$this->getPageName());
    }
    $degreeGrouping = FieldMap::getFieldLabel("degree_grouping_reg",'','1');
    $this->ugGroup  = $degreeGrouping['ug'];
    $this->gGroup  = $degreeGrouping['g'];
    $this->pgGroup  = $degreeGrouping['pg'];
    $this->phdGroup  = $degreeGrouping['phd'];
  }

  public function submit() {
	$now = date("Y-m-d G:i:s");
	$today = CommonUtility::makeTime(date("Y-m-d"));
	$values_that_are_not_in_form = array('INCOMPLETE' => 'N','ENTRY_DT' => $now, 'MOD_DT' => $now, 'LAST_LOGIN_DT' => $today);
  $this->form->updateData($this->loginProfile->getPROFILEID(),$values_that_are_not_in_form);
  $this->redisQueueJunkIncompleteProfile($this->loginProfile->getPROFILEID());
  }

  public function postSubmit() {
//	parent::postSubmit();  no field to affect jpartner in page 3
	$dbRegCount = new MIS_REG_COUNT();
	$dbRegCount->updateEntryRegPage("PAGE3", 'Y', $this->loginProfile->getPROFILEID());
	$dbRegLead = new MIS_REG_LEAD();
	$dbRegLead->updateRegisterEmail("INCOMPLETE='N'", $this->loginProfile->getEMAIL());
        $authWeb = new WebAuthentication();
        $authWeb->loginFromReg();
	RegistrationCommunicate::sendEmailAfterRegCompletion($this->loginProfile->getPROFILEID());
	RegistrationCommunicate::sendSms($this->loginProfile->getPROFILEID());
	if ($this->leadid) {
		$dbLeadConversion = new MIS_LEAD_CONVERSION();
		$dbLeadConversion->updateLead($this->leadid);
	}
  }

  /*
   * before validating bind the form
   */
  public function preValidate() {    
    parent::preValidate();
  }
  protected function preProcess()
  {
	$this->sourceVar['source']=$this->request->getParameter('source');
	$this->pageVar[groupNameParams]=$this->request->getParameter('groupname');
	$this->affiliateid=$this->request->getParameter('affiliateid');
  }
  public function preSubmit() {
    
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
   * Function to assign templates Varibales
   */
  protected function assignTemplateVariables(){
    parent::assignTemplateVariables();
    $this->objController->source = $this->source;
    $this->nameOfUserObj = new incentive_NAME_OF_USER();
    $this->objController->templateVars["name"] = $this->nameOfUserObj->getName($this->profileId);
  }

  public function redisQueueJunkIncompleteProfile($profileId)
  {
    $memcacheObj = JsMemcache::getInstance();

    $minute = date("i");


    $key = JunkCharacterEnums::JUNK_CHARACTER_KEY;


    $redisQueueInterval = JunkCharacterEnums::REDIS_QUEUE_INTERVAL;

    $startIndex = floor($minute/$redisQueueInterval);

    $key = $key.(($startIndex) * $redisQueueInterval)."_".(($startIndex + 1) * $redisQueueInterval);

    $memcacheObj->lpush($key,$profileId);
  }
}
