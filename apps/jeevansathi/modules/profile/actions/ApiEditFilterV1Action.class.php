<?php

/**
 * profile actions.
 * ApiEditV1
 * Controller to register a new device
 * @package    jeevansathi
 * @subpackage api
 * @author     Nitesh Sethi
 */
class ApiEditFilterV1Action extends sfActions
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
		$dbFilter=new NEWJS_FILTER();
		$dbFilterArr=$dbFilter->fetchEntry($this->loginProfile->getPROFILEID());
		if(is_array($dbFilterArr))
		{
			$filterArr[FILTER]=$this->getFilterFormatArr($dbFilterArr,1);
		}
		else
		{
			$filterArr[FILTER]=$this->getFilterFormatArr($dbFilterArr,0);
		}
		
		$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
		$apiResponseHandlerObj->setResponseBody($filterArr);
		$apiResponseHandlerObj->generateResponse();
                if($request->getParameter("internal")){
                  return sfView::NONE;
                }
		die;
	}

	
	public function getFilterFormatArr($filterArr,$filterFlag)
	{
		if($filterFlag)
		{
			
			$arrout[]=$this->filterApiValFormat("AGE",$filterArr["AGE"]);
			$arrout[]=$this->filterApiValFormat("MSTATUS",$filterArr["MSTATUS"]);
			$arrout[]=$this->filterApiValFormat("COUNTRY_RES",$filterArr["COUNTRY_RES"]);
			$arrout[]=$this->filterApiValFormat("CITY_RES",$filterArr["CITY_RES"]);
			$arrout[]=$this->filterApiValFormat("RELIGION",$filterArr["RELIGION"]);
			$arrout[]=$this->filterApiValFormat("CASTE",$filterArr["CASTE"]);
			$arrout[]=$this->filterApiValFormat("MTONGUE",$filterArr["MTONGUE"]);
			$arrout[]=$this->filterApiValFormat("INCOME",$filterArr["INCOME"]);
		}
		else
		{
			$arrout[]=$this->filterApiValFormat("AGE","N");
			$arrout[]=$this->filterApiValFormat("MSTATUS","N");
			$arrout[]=$this->filterApiValFormat("COUNTRY_RES","N");
			$arrout[]=$this->filterApiValFormat("CITY_RES","N");
			$arrout[]=$this->filterApiValFormat("RELIGION","N");
			$arrout[]=$this->filterApiValFormat("CASTE","N");
			$arrout[]=$this->filterApiValFormat("MTONGUE","N");
			$arrout[]=$this->filterApiValFormat("INCOME","N");
		}
		return $arrout;
	}
	public function filterApiValFormat($key,$val)
	{
		$arr["key"]=$key;
		if($val=='')
			$val = "N";
		$arr["val"]=$val;
		return $arr;
	}
}
