<?php

/**
 * desktopRegister2 class
 *
 * @author Esha Jain <esha.jain@jeevansathi.com>
 * @package jeevansathi
 * @subpackage registration
 */
class desktopRegister2 extends registrationBaseClass{

  /*
   * Declaring and Defining Member Function
   */
  public function __construct($objController) {
    $this->PAGE_ID = RegistrationEnums::$JSPC_REG_PAGE[2];
    $this->NEXT_PAGE_ID = RegistrationEnums::$JSPC_REG_PAGE[3];
    parent::__construct($objController);    
    $this->profileId = $this->loginProfile->getPROFILEID();
    $this->loginProfile->getDetail($this->profileId,"PROFILEID");
	$this->initMe();
  }
  /*
   * initatialize this class object
   */
  private function initMe(){
	if(!$this->loginProfile)
	{
		$this->request->setParameter("page",RegistrationEnums::$JSPC_REG_PAGE[2]);
		sfContext::getInstance()->getController()->forward("register","regPage");
	}
	if ($this->isSubmit())
	{
		$this->arrFormValues['horoscope_match']=$this->arrFormValues['horoscopeMatch'];
		unset($this->arrFormValues['horoscopeMatch']);
	}
    $this->setSlot(Jsb9Enum::jsRegPage2Url);
  }
  /*
   * submit information -
   * 1) user filled data
   * 2) Keywords
   */
  public function submit(){
    $now = date("Y-m-d G:i:s");
    $today = CommonUtility::makeTime(date("Y-m-d"));
    
    //update gender with stored value of gender
    $this->arrFormValues['gender'] = $this->loginProfile->getGENDER();
    $keywords = RegistrationMisc::getKeywords($this->arrFormValues);
    
    $values_that_are_not_in_form = array('INCOMPLETE' => 'Y','MOD_DT' => $now, 'LAST_LOGIN_DT' => $today, 'KEYWORDS' => $keywords);
    $this->form->updateData($this->profileId,$values_that_are_not_in_form);
  }
  /*
   * for things to be done before display
   */
  public function preDisplay()
  {
    parent::preDisplay();
    if($this->request->getParameter("incompleteUser")){
      $this->isIncomplete = true;
      $this->incompletePrefilledData = RegistrationFunctions::getPrefilledDataForUser($this->loginProfile,$this->getPageName());
    }
  }
  /*
   * for things to be done after submit like setting dpp suggest
   */
  public function postSubmit(){
        $dbRegCount = new MIS_REG_COUNT(); 
        $dbRegCount->updateEntryRegPage("PAGE2", 'Y', $this->profileId);
        parent::postSubmit(); 
        $partnerField = new PartnerField();
        RegistrationFunctions::UpdateFilter($partnerField);
  }
  
  /*
   * before validating bind the form
   */
  public function preValidate() {
    if($this->arrFormValues[JsFormFieldsEnums::COUNTRY]!=51){
      unset($this->form[JsFormFieldsEnums::CITY]);
    }
    parent::preValidate();
  }
  
  /*
   * to check if request is post and has form data filled
   * @return - true or false boolean
   */
  protected function isSubmit() {
    return ($this->request->isMethod("POST") && $this->getFormValues() );
  }
  protected function preSubmit(){}

  protected function preProcess()
  {
  }
  /*
   * assign variables for usage in template
   */
  protected function assignTemplateVariables(){
    parent::assignTemplateVariables();
    $this->objController->templateVars["gender_value"] = $this->loginProfile->getGENDER();
    $this->objController->templateVars["source"] = $this->arrFormValues["source"];
    $this->nameOfUserObj = new incentive_NAME_OF_USER();
    $this->objController->templateVars["name"] = $this->nameOfUserObj->getName($this->profileId);
  }


}
