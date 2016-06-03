<?php

/**
 * ApiContactUs
 * Controller to register a new device
 * @package    jeevansathi
 * @subpackage api
 * @author     Kunal Verma
 * @date	   24th March 2014
 */
class ApiContactUsV1Action extends sfActions
{ 
	//Member Variables
	private $m_arrOut;// Array used to store api response
	private $m_arrCombine;// Final response array
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	//Member Function
	public function execute($request)
	{
		
		$loginData=$request->getAttribute("loginData");
		if($loginData[PROFILEID])
		{
			$loginProfile=LoggedInProfile::getInstance();
			$loginProfile->getDetail($loginData['PROFILEID'],"PROFILEID");
			$this->USERNAME=$loginData[USERNAME];
		}
				
		$this->m_arrOut = null;
		$apiResponseHandlerObj=ApiResponseHandler::getInstance();	
		if($this->BakeApiView())
		{
			//$apiResponseHandlerObj=ApiResponseHandler::getInstance();
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
			$apiResponseHandlerObj->setResponseBody($this->m_arrCombine);
		}
		else
		{
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
		}
			
		$apiResponseHandlerObj->generateResponse();
        if ($request->getParameter('INTERNAL') == 1) {
            return sfView::NONE;
        }
        else{
            die;
        }
	}
	
	/**
	 * BakeApiView
	 * Main Function to generating API Response 
	 */
	private function BakeApiView()
	{
		$contactUSObj = new ContactUS();
		
		if($contactUSObj instanceof ContactUS)
		{
			$request = sfContext::getInstance()->getRequest();
			$contactUSObj->processData($request);
			
			$arrStates 		= $contactUSObj->getListState();
			$arrInfo		= $contactUSObj->getInfo();
			$arrHeadOffice	= $contactUSObj->getInfoSel();
			
			if(!is_array($arrStates) || !is_array($arrInfo) || !is_array($arrHeadOffice))
				return false;
			$this->m_arrOut = null;	
			
			//Head Office
			$this->getDecoratedHeadOffice($arrHeadOffice);
			
			//States
			foreach($arrStates as $szVal)
			{
				$this->m_arrOut[$szVal] = null;
			}
			
			foreach($arrInfo as $szKey=>$Val)
			{
				if(is_array($Val))
				{
					if(array_key_exists(ucfirst(strtolower($szKey)),$Val))
						$this->getDecoratedInfo($Val);
					else
						$this->getDecoratedInfo($Val,$szKey);
				}
			}
			// As per API requirement 
			// Converting Output array as a object
			$arrTemp = array();
			$this->m_arrCombine = array();
			$arrCombine = array();
			
			foreach($this->m_arrOut as $szKey=>$arrVal)
			{
				$arrTemp["header"] = $szKey;
				$arrTemp["office"] =  json_decode(json_encode(array_values($arrVal)), FALSE);
				$arrTollFree = null;
				if($szKey === "Head Office")
				{
					$arrTollFree = array('msg'=>'Contact us at toll free number - 1-800-4196299','label'=>'Call','value'=>'18004196299','action'=>'CALL');
				}
				$arrTemp["tollfree"] = json_decode(json_encode($arrTollFree),FALSE);
				$arrCombine[] = $arrTemp;
			}
			$this->m_arrCombine["details"] = json_decode(json_encode($arrCombine), FALSE);
			return true;
		}
		return false;
	}
	
	/**
	 * Processing stored office details
	 */
	private function getDecoratedInfo($arrInfo,$szString="")
	{
		if($szString != "")
			$szPrependString = $szString . " - ";
		if(is_array($arrInfo))
		{
			$arrTemp1 = array();
			foreach($arrInfo as $szKey=>$szVal)
			{
				$key = $szVal[STATE];
				$nameKey = $szPrependString . $szVal[NAME];
				$arrTemp = $this->getDetails($szVal,$nameKey);
				$arrTemp1[$nameKey] = $arrTemp;
			}
			
			if($this->m_arrOut[$key] != null)
			{
				$arrTemp1 = array_merge($this->m_arrOut[$key],$arrTemp1);
			}
			
			$this->m_arrOut[$key] = $arrTemp1;
		}
	}
	/**
	 * getHeadOffice
	 * To Process stored head office details and return Output as per API requirement 
	 */
	private function getDecoratedHeadOffice($arrHeadOffice)
	{
		foreach($arrHeadOffice as $szKey=>$szVal)
		{
			$nameKey = $szVal[NAME]== "HO" ? "Noida" : "Noida - Match Point Office";
			$arrTemp = $this->getDetails($szVal,$nameKey);
			$arrTemp1[$nameKey] = $arrTemp;
		}
		$this->m_arrOut["Head Office"] = $arrTemp1;
	}
	
	/**
	 * getDetails
	 * To Process stored office details and return Output as per API requirement 
	 */
	private function getDetails($arrInfo,$cityName)
	{
		$arrTemp = array();
		if(is_array($arrInfo) && $cityName)
		{
			$arrTemp[contact_person] 		= $arrInfo[CONTACT];
			$arrTemp[address]				= strlen($arrInfo[ADDRESS])!=0 ? $arrInfo[ADDRESS] : null ;
			$arrTemp[phone]					= $this->getDecoratedPhone($arrInfo[PHONE],$arrInfo[MOBILE]);
			$arrTemp[city_suffix] 			= $this->getDeocratedMatchPointService($arrInfo[MATCH_POINT_SERVICE]);
			$arrTemp[email]					= null;
			$arrTemp[city]					= $cityName;
			$arrTemp[city_id]               =$arrInfo[CITY_ID];
            $arrTemp[state_val]             =$arrInfo[STATE_VAL];
			return $arrTemp;
		}
		return null;
	}
	
	/**
	 * getDeocratedMatchPointService
	 */
	private function getDeocratedMatchPointService($bValue)
	{
		if($bValue)
			return "(Match Point Service)";
		
		return null;
	}
	
	/**
	 * getDecoratedPhone
	 *Function to merger Phone string and mobile string in  one object for API output
	 */
	private function getDecoratedPhone($szPhone,$szMobile)
	{
		$arrPhoneDir = array();
		//Phone
		$arrPhoneDir = $this->processPhoneNumber($szMobile);
		//Mobile
		//$arrPhoneDir = array_merge($this->processPhoneNumber($szMobile),$arrPhoneDir); //Merged removed as phone number column is not being used (JSC-1028)
		
		$obj = json_decode(json_encode($arrPhoneDir), FALSE);
		return $obj;
	}
	
	/**
	 * processPhoneNumber
	 *Processing Phone number(and mobile number stored) Stored in DB and extraction them from string
	 *Input String : 99999999//99999998,999999997(toll-free)////9999996(T/F)  
	 *Output : Array of Phone numbers only {"99999999","99999998","99999997","99999996"}
	 */
	private function processPhoneNumber($szString)
	{
		$arrPhoneDir = array();
		if(strlen($szString)!=0)
		{
			$arrPhone = explode("/",$szString);
			if(count($arrPhone) >= 1)
			{
				foreach($arrPhone as $szKey=>$szVal)
				{
					if(strlen($szVal)!=0 && strlen($szVal) >9)
					{
						$arrPhoneTemp = explode(",",$szVal);
						foreach($arrPhoneTemp as $szKey1=>$szVal1)
						{
							if(strlen($szVal1)!=0 && strlen($szVal1) >9)
							{
								$arrRes = explode("(",$szVal1);
								$arrPhoneDir[] = trim($arrRes[0]);
							}
						}
					}
				}
			}
		}
		
		return $arrPhoneDir;
	}
}
