<?php
/**
 * ApiCALayer
 * To get the Cal Layer contents 
 * @package    jeevansathi
 * @subpackage api
 * @author     Palash Chordia
 * @date	   15th January 2016
 */
class ApiCALayerV1Action extends sfActions
{ 
	//Member Variables
	private $m_arrOut		= null;// Array used to store api response
	private $m_iResponseStatus;
	private $loginProfile;
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	//Member Functions
	public function execute($request)
	{

		// LoggingManager::getInstance()->logThis(LoggingEnums::LOG_INFO, "In ApiCALayer");

		$loginData=$request->getAttribute("loginData");

	if(!$loginData['PROFILEID'])
		{
			//Set Error Message and return false
			$this->m_iResponseStatus = ResponseHandlerConfig::$LOGOUT_PROFILE;
			
		}
		else 
		{	
		$this->loginProfile=LoggedInProfile::getInstance();
		$totalAwaiting=(new ProfileMemcacheService($this->loginProfile))->get('AWAITING_RESPONSE');
        
        $layerToShow = false;
        //As Per Peek Level Unset Some Listing Across Channels
        if(JsConstants::$hideUnimportantFeatureAtPeakLoad <=4) {
            $layerToShow = CriticalActionLayerTracking::getCALayerToShow($this->loginProfile,$totalAwaiting);
        }
		
		//print_r($layerToShow); die;
		if(!$layerToShow) {
			$apiResponseHandlerObj = ApiResponseHandler::getInstance();
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
			$apiResponseHandlerObj->setResponseBody(array('calObject'=>null));	

			$apiResponseHandlerObj->generateResponse();
			return sfView::NONE;
			die;
			
		}
		$layerData=CriticalActionLayerDataDisplay::getDataValue($layerToShow);
                if($layerToShow==9)
                {
                    $profileId=$this->loginProfile->getPROFILEID();
                    $nameData=(new NameOfUser())->getNameData($profileId);
                    $nameOfUser=$nameData[$profileId]['NAME'];
                    $namePrivacy=$nameData[$profileId]['DISPLAY'];
                }
        if($layerToShow==16)
        {
        	if($suggestions = $request->getParameter('dppSugg'))
        	{
        		$layerData['dppSuggObject'] = $suggestions;
        		$layerData['dppCALGeneric'] = 0;
        	}
	    }

	    if($layerToShow == 19)
	    {
      		
            $layerData['discountPercentage'] = $request->getParameter('DISCOUNT_PERCENTAGE');
            $layerData['discountSubtitle']  = $request->getParameter('DISCOUNT_SUBTITLE');
            $layerData['startDate']  = $request->getParameter('START_DATE');
            $layerData['oldPrice'] = $request->getParameter('OLD_PRICE');
            $layerData['newPrice'] = $request->getParameter('NEW_PRICE');
            $layerData['lightningCALTime'] = $request->getParameter('LIGHTNING_CAL_TIME');
            $layerData['symbol'] = $request->getParameter('SYMBOL');
     		$layerData['lightningCALTimeText']  = 'Hurry! Offer valid for';
	    }

	     if($layerToShow==21)
        {
	    $layerData['PREFERENCES'] = $request->getParameter('DPP_CASTE_BAR') ;
	    }

		$this->m_arrOut=$layerData;
                $this->m_arrOut['NAME_OF_USER']=$nameOfUser ? $nameOfUser : NULL;
                $this->m_arrOut['NAME_PRIVACY']=$namePrivacy ? $namePrivacy : NULL;
	    }

		//Api Response Object
		$apiResponseHandlerObj = ApiResponseHandler::getInstance();
		$this->m_iResponseStatus = ResponseHandlerConfig::$SUCCESS;                                     
		$apiResponseHandlerObj->setHttpArray($this->m_iResponseStatus);
		$apiResponseHandlerObj->setResponseBody(array('calObject'=>$this->m_arrOut));	
		$apiResponseHandlerObj->generateResponse();
		return sfView::NONE;
		die;
	}

}


