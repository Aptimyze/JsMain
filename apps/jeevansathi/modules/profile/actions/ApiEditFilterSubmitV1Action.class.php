<?php

/**
 * profile actions.
 * ApiEditV1
 * Controller to register a new device
 * @package    jeevansathi
 * @subpackage api
 * @author     Nitesh Sethi
 */
class ApiEditFilterSubmitV1Action extends sfActions
{ 
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	public function execute($request)
	{
		

		//Contains login credentials
		$this->loginData = $request->getAttribute("loginData");
		$apiResponseHandlerObj=ApiResponseHandler::getInstance();
		if(!$request->getAttribute("profileid"))
		{
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$LOGOUT_PROFILE);
			$apiResponseHandlerObj->generateResponse();
			die;
		}
		//Contains loggedin Profile information;
		$this->loginProfile = LoggedInProfile::getInstance();
    if($this->loginProfile->getAGE()== "")
      $this->loginProfile->getDetail($request->getAttribute("profileid"),"PROFILEID","*");
		$filterArr=$request->getParameter("filterArr");
		if(is_array($filterArr))
		{
			if($this->ValidateFilterArr($filterArr))
			{
        $apiResponseStatus = ResponseHandlerConfig::$FAILURE;
        try {
          $dbFilter=new ProfileFilter();
          $bResult = $dbFilterArr=$dbFilter->updateRecord($this->loginProfile->getPROFILEID(),$filterArr);
          if(false === $bResult)
          {
            $bResult1=$dbFilter->insertRecord($this->loginProfile->getPROFILEID(),$filterArr);
          }
          if($bResult || $bResult1>=0) {
            $apiResponseStatus = ResponseHandlerConfig::$SUCCESS;
          }
        } catch (Exception $ex) {
          jsException::log($ex);
          $apiResponseHandlerObj->setResponseBody(array("ERROR"=>$ex->getMessage()));
        }
				$apiResponseHandlerObj->setHttpArray($apiResponseStatus);
			}
			else
			{
				$errorArr["ERROR"]="Filter Fields Array is not valid";
				$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
				$apiResponseHandlerObj->setResponseBody($errorArr);
				ValidationHandler::getValidationHandler("","Filter Fields Array is not valid");
			}
		}
		else
		{
				$errorArr["ERROR"]="Please Provide Filter Fields Array ";
				$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
				$apiResponseHandlerObj->setResponseBody($errorArr);
				ValidationHandler::getValidationHandler("","Please Provide Filter Fields Array");
		}
		
		$apiResponseHandlerObj->generateResponse();
		die;
	}

	
	public function ValidateFilterArr($filterArr)
	{
		$filterArrEnum=array("AGE","MSTATUS","COUNTRY_RES","CITY_RES","RELIGION","CASTE","MTONGUE","INCOME");
		$count=count($filterArr);
    $arrAllowedValues = array('Y','N');
		foreach ($filterArr as $key=>$val)
		{
			if(!in_array($key,$filterArrEnum) || !in_array($val, $arrAllowedValues)) {
				return false;
      }
		}
		if($count!=8)
		{
			return false;
		}
		else
			return true;
	}
	public function filterApiValFormat($key,$val)
	{
		$arr["key"]=$key;
		$arr["val"]=$val;
		return $arr;
	}
}
