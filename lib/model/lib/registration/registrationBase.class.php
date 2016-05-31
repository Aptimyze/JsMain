<?php

/**
 * CLASS registrationBase
 * This class decide first carries all common and factory functionalities for pages.</p>
 * @package   jeevansathi
 * @subpackage   registration
 * @author    Esha Jain <esha.jain@jeevansathi.com>
 * @copyright 2015 Esha Jain
 */
abstract class registrationBaseClass {
  /*
   * Declaring Memeber Varibales
   */
  /*
   * @access protected Page Id
   */
  protected $groupname;
  protected $sourcename;
  protected $leadid;
  protected $PAGE_ID;

  /*
   * @access protected next Page Id
   */

  protected $NEXT_PAGE_ID;

  /*
   * Object of Core Class of Controller
   * For template Configuration
   * @access Protected
   * @var object
   */

  protected $objController;

  /*
   * Object of Core Class of Forms
   * @access Protected
   * @var object
   */

  protected $form;

  /*
   * sfWebRequest Object
   * @access private
   */

  protected $request = null;
  
  /*
   * Array of defualt value of fiels in form
   * Its contain default value
   * @access Protected
   * @var Arrays
   */
  protected $arrDefaultValues;

  /*
   * Array of optional value of fields in form
   * Its contain optional value
   * @access Protected
   * @var Arrays
   */
  protected $arrFormConfiguration;

  /*
   * Array of form values of fields in form given by user
   * 
   * @access Protected
   * @var Arrays
   */
  protected $arrFormValues = false;
  
  /*
   * Array of hidden form values of fields in form
   * 
   * @access Protected
   * @var Arrays
   */
  protected $arrHiddenFormValues = false;

  /*
   * Const Form Related Values Will Be come in Array Name as 
   */
  const ARR_FORM_PARAMS = "formValues";

  /*
   * Const Form Related hidden Values Will Be come in Array Name as 
   */
  const ARR_HIDDEN_FORM_PARAMS = "hiddenValues";

  /*
   * Declaring and Defining Member Function
   */

  /**
   * Constructor for this class.
   * @access Public
   * @return Void
   * <p>
   * </p>
   */
  public function __construct($objController) {
    
    //Assign Controller for template configuration
    $this->objController = $objController;
    
    //Assign Request Param
    $this->request = $this->objController->getRequest();
    
    //creating loggedIn Object
    $this->loginProfile = LoggedInProfile::getInstance();
    
    //Init Form
    $this->initForm();
  }

  /*
   * Function to init Form
   */

  protected function initForm() {

    //Get Page Params
    $this->arrDefaultValues = '';

    $this->getHiddenFormValues();

    //Get Form Configuration 
    $this->arrFormConfiguration = $this->getFormConfiguration();

    //Get CSRF Secret
    $csrfSecret = $this->getCSRFSecret();

    //Create object of Form
    $this->form = new JsForm($this->arrDefaultValues, $this->arrFormConfiguration, $csrfSecret);
  }

  /*
   * Abstract Method of getting Form Configuration Values of form, should be defined by each child class 
   * @return Array of form configuration values
   */

  protected function getFormConfiguration()
  {
    return array( JsFormConfigEnums::FORM_PARAM_PAGE                => $this->getPageName(),
                  JsFormConfigEnums::FORM_PARAM_VALUES              => $this->getFormValues(),
                  JsFormConfigEnums::FORM_PARAM_ALLOW_EXTRA_FIELDS  => true);
  }

  /*
   * Method of getting CSRF Secret, should be defined by each child class, by defualt no CSRF Secret defined by parent
   * @return String or null
   */

  protected function getCSRFSecret() {
    return JsFormConfigEnums::FORM_CSRF_SECRET;
  }

  /*
   * Method of getting Form Values entered by User, should be defined by each child class, 
   * @return String or null
   */

  protected function getFormValues(){
    //For first time
    if ( false === $this->arrFormValues ) {
      $this->arrFormValues = $this->request->getParameter(self::ARR_FORM_PARAMS);
    }
    
    return $this->arrFormValues ;
  }

  /*
   * Method of getting Form Values entered by User, should be defined by each child class, 
   * @return String or null
   */

  protected function getHiddenFormValues(){
    //For first time
    if ( false === $this->arrHiddenFormValues ) {
      $this->arrHiddenFormValues = $this->request->getParameter(self::ARR_HIDDEN_FORM_PARAMS);
    }

    return $this->arrHiddenFormValues ;
  }
 
  /*
   * Function to assign templates Varibales
   */
  protected function assignTemplateVariables(){
    $this->objController->templateVars = array();
    $this->objController->LIVE_HELP_CHAT = str_replace("<SITE_URL>", JsConstants::$siteUrl, RegistrationEnums::LIVE_HELP_CHAT_URL);
  }
  /*
   * check if form is valid
   * @return - true or false boolean if it valid or invalid
   */
  protected function validate() {
    return $this->form->isValid();
  }
  /*
   * setting slot for jsb9 tracking
   */
  protected function setSlot($slot) {
    sfContext::getInstance()->getResponse()->setSlot("optionaljsb9Key", $slot);
  }
  /*
   * form and data submit related issues
   */
  abstract protected function submit();
  /*
   * checks if the form is submitted correctly and sets Jpartner fields
   */
  protected function postSubmit(){
    RegistrationMisc::setJpartnerAfterRegistration($this->loginProfile,RegistrationEnums::$jpartnerfields[$this->getPageName()]);
  }

  protected function preDisplay(){
    $pageName = $this->getPageName();
    if(!$this->groupname && ($temp=$this->request->getParameter("groupname")))
{
	$this->groupname= $temp;
}
    if($this->loginProfile instanceof LoggedInProfile)
    {
	$pixelcodeObj = new PixelCodeHandler($this->groupname,'',$pageName,$this->loginProfile);
	$this->objController->pixelcode = $pixelcodeObj->getPixelCode();
	if($this->loginProfile->getSOURCE())
		$this->sourcename = $this->loginProfile->getSOURCE();
	unset($pixelcodeObj);
    }
    $fieldValObj = new getFieldValues;
    $this->leadid = $this->request->getParameter("leadid");
    $this->objController->leadid = $this->leadid;
    $this->objController->groupname = $this->groupname;
    $this->objController->sourcename = $this->sourcename;
    $this->fieldsArray = $fieldValObj->getListValues(RegistrationEnums::$fieldsOnPage[$pageName],$pageName);
    if(!$this->fieldsArray){
      $msgReqForDebug[]=$_SERVER;
      $subject="static data not fetched in registration";
      SendMail::send_email("kunal.test02@gmail.com,ankitshukla125@gmail.com",print_r($msgReqForDebug,true),$subject);  
    }
      
  }
  
  /*
   * checks if the form is submitted correctly
   */
  abstract protected function isSubmit();
  /*
   * for the conditions before validation like form binding
   */
  protected function preValidate()
  {
    $this->form->bind($this->arrFormValues);
  }
  /*
   * for suspected checks , ip block type functions to be carried before submission of data
   */
  abstract protected function preSubmit();
  /*
   * invalid form handling like server side errors
   */
  public function handleError()
  {
    RegistrationMisc::logServerSideValidationErrors($this->getPageName(), $this->form);
	$errorObj=$this->form->getErrorSchema();

	foreach ($errorObj as  $name => $error)
	{
		$errMes=$error->getMessage();//print_r($val);   
		$err_text[$name]=$errMes;
	}
    $errorArr["error"] = $err_text;
    
    $apiObj=ApiResponseHandler::getInstance();
    $apiObj->setHttpArray(ResponseHandlerConfig::$APP_REG_FAILED);    
    $apiObj->setResponseBody($errorArr);
    $apiObj->generateResponse();
    die;
  }
  /*
   * anything which has to be done everywhere without depending on process like assign groupname
   */
  abstract protected function preProcess();
/*
 * this function creates a process to be followed
 * i.e. sequence of function calls in case of submit and not submit
 */
  public function process(){
      $this->preProcess();
      // if the form has not been submitted
      if (!$this->isSubmit()) {
        $this->preDisplay();
      }
      else {
	if(is_array($this->arrFormValues))
		$formInputFields = array_keys($this->arrFormValues);

	if(is_array($this->arrHiddenFormValues))
		$formHiddenInputFields = array_keys($this->arrHiddenFormValues);
	$this->leadid = $this->request->getParameter("leadid");
        //whitelisting of values for a page that are sent in requests
        $whiteFormObj = new WhitelistForm($formInputFields,$formHiddenInputFields,$this->getPageName());
	$error = $whiteFormObj->getError();
        //if whitelisting is without error
	if(!$error)
	{
		$this->preValidate();
		$this->valid = $this->validate();
                //if form is valid
		if ($this->valid) {
                  // this function has things to be done before data submit
		  $this->preSubmit();
                  // to save values in db
		  $this->submit();
		  $this->postSubmit();
		    $this->objController->leadid = $this->leadid;
                  // to send success response to the ajax call
		  $this->handleSuccess();
		}
                //error handling of invalid form
		else
		  $this->handleError();
	}
	else
	{
		$errorHandlerObj = new HandleError($error,$this->getPageName(),RegistrationEnums::$errorEnums['WHITELIST']);
		$errorHandlerObj->takeAction();
	}
      }
  $this->assignTemplateVariables();
  }
  /*
   * handleSuccess
   * @return return success json for ajax call
   */
  public function handleSuccess() {
    $apiObj=ApiResponseHandler::getInstance();
    $respArr = array("leadid"=>$this->leadid);
    $apiObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
    $apiObj->setResponseBody($respArr);
    $apiObj->generateResponse();
    die;
  }
  /*
   * getForm
   * @return Form
   */

  public function getForm()
  {
    return $this->form;
  }
  /*
   * getPageName
   * @return Page ID
   */
  public function getPageName()
  {
      return $this->PAGE_ID;
  }
  /*
   * getNextPageName
   * @return next Page ID
   */
  public function getNextPageName()
  {
      return $this->NEXT_PAGE_ID;
  }
}
?>
