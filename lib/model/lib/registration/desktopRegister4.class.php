<?php

/**
 * desktopRegister4 class
 *
 * @author Esha Jain <esha.jain@jeevansathi.com>
 * @package jeevansathi
 * @subpackage registration
 */
class desktopRegister4 extends registrationBaseClass {
 
  /*
   * Declaring Memeber Varibales
   */

  /*
   * Declaring and Defining Member Function
   */
   
  public function __construct($objController) {
    $this->PAGE_ID = RegistrationEnums::$JSPC_REG_PAGE[4];
    $this->NEXT_PAGE_ID = RegistrationEnums::$JSPC_REG_PAGE[5];
    //Call parent constructor
    parent::__construct($objController);
    //InitMe
    $this->initMe();
    $this->loginProfile = LoggedInProfile::getInstance();
    $this->loginProfile->getDetail($this->loginProfile->getPROFILEID(),"PROFILEID");
  }
  
  private function initMe(){
    RegistrationFunctions::setLegalVariables($this->request);
    
    $this->setSlot(Jsb9Enum::jsRegPage4Url);
    
    $this->reg_params['source'] = $this->sourceVar['source'];
    sfContext::getInstance()->getResponse()->setCanonical(sfConfig::get("app_site_url") . "/register/page4");
    
  }

  public function preDisplay() {
sfContext::getInstance()->getResponse()->setSlot("disableFbRemarketing", true);
    parent::preDisplay();
  }

  public function submit() {
    $now = date("Y-m-d G:i:s");
    $today = CommonUtility::makeTime(date("Y-m-d"));
    $values_that_are_not_in_form = array('MOD_DT' => $now, 'LAST_LOGIN_DT' => $today);
    $this->form->updateData($this->loginProfile->getPROFILEID(),$values_that_are_not_in_form);
  }

  public function postSubmit() {
//    parent::postSubmit(); no field to affect jpartner in page 4
        $dbRegCount = new MIS_REG_COUNT();
        $dbRegCount->updateEntryRegPage("PAGE4", 'Y', $this->loginProfile->getPROFILEID());
  }

  /*
   * before validating bind the form
   */
  public function preValidate() {   
    parent::preValidate(); 
  }
  protected function preProcess()
  {
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
  }

}
