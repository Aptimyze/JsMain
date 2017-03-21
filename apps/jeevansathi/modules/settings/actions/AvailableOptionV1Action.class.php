<?php
/**
 * Api for Available option in setting menu
 */
 
/**
 * @package    jeevansathi
 * @subpackage setting
 * @author     Kunal Verma
 * @date	     20th March 2017
 */
class AvailableOptionV1Action extends sfActions
{
  /**
   * 
   * @param type $request
   */
  public function execute($request)
	{
    //Contains login credentials
		$this->loginData=$request->getAttribute("loginData");
    
    $this->m_objLoginProfile = LoggedInProfile::getInstance();
		$this->profileId = $this->m_objLoginProfile->getPROFILEID();
    if($this->m_objLoginProfile->getAGE()== "")
      $this->m_objLoginProfile->getDetail($this->profileId ,"PROFILEID","ACTIVATED");
    
    $apiResponseHandlerObj=ApiResponseHandler::getInstance();
    
    $activated = $this->m_objLoginProfile->getACTIVATED();
    
    $options["hide_unhide"] = array("label"=> "Hide Profile","url"=>"");
    if($activated == ProfileEnums::PROFILE_HIDDEN) {
      $options["hide_unhide"] = array("label"=> "Unhide Profile","url"=>"");
    }
    $arrOut = array("options"=>$options);
    
    $apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
    $apiResponseHandlerObj->setResponseBody($arrOut);
    $apiResponseHandlerObj->generateResponse();
		die;
  }
}
