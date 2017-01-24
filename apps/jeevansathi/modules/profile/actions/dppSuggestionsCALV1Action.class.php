<?php

/**
 * This api is used to make Dpp Suggestions on certain fields
 *
 * @package    jeevansathi
 * @subpackage profile
 * @author     Sanyam Chopra
 * @date	   24th Jan 2017
 */

class dppSuggestionsCALV1Action extends sfActions 
{
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	public function execute($request)
	{
		
		$apiResponseHandlerObj=ApiResponseHandler::getInstance();
		$this->loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
		$calLayer = 1;

		//Call to fetch already filled data in dpp
		ob_start();
		$request->setParameter('sectionFlag','dpp');
		$request->setParameter("internal","1");
		$jsonData = sfContext::getInstance()->getController()->getPresentationFor("profile", "ApiEditV1");

		$output = ob_get_contents();
		ob_end_clean();
		$decodedData = json_decode($output);

		//getDppDataArr is used to format the data in the required format.
		$dppDataArr = $this->getDppDataArr($decodedData);		

		$percentileFields = DppAutoSuggestEnum::$TRENDS_FIELDS;
		$profileId = $this->loggedInProfileObj->getPROFILEID();
		$dppSuggestionsObj = new dppSuggestions();
		$trendsObj = new TWOWAYMATCH_TRENDS("newjs_slave");

		//Trends arr is fetched from twoWayMatches.Trends table
		$trendsArr = $dppSuggestionsObj->getTrendsArr($profileId,$percentileFields,$trendsObj);
		unset($trendsObj);
		if(is_array($dppDataArr))
		{
			foreach($dppDataArr as $key=>$val) 
			{
				if(is_array($val))
				{
					foreach($val as $key1=>$val1)
					{									
						$type = $val["type"];				
						if($key1 == "data")
						{	
							$finalArr[] = $dppSuggestionsObj->getDppSuggestions($trendsArr,$type,$val1,$calLayer);
						}					
					}
				}			
			}
		}

		$finalArr["Description"] = DppAutoSuggestEnum::$descriptionText;

		if(MobileCommon::isApp())
		{
			$finalArr = $this->getFormattedArrForApp($finalArr);						
		}
		if(is_array($finalArr))
		{
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
			$apiResponseHandlerObj->setResponseBody($finalArr);
		}
		else
		{
			$errorArr["ERROR"] = "Something went wrong";
			$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
			$apiResponseHandlerObj->setResponseBody($errorArr);
		}
		$apiResponseHandlerObj->generateResponse();
		return sfView::NONE;
	}

	public function getFormattedArrForApp($finalArr)
	{		
		$i=0;
		foreach($finalArr as $key => $value)
		{
			foreach($value as $k1=>$v1)
			{
				if($k1 == "data")
				{
					foreach($v1 as $k2=>$v2)
					{
						$finalArrApp["dppData"][$key][$k1][$i]["id"] = $k2;
						$finalArrApp["dppData"][$key][$k1][$i]["value"] = $v2;	
						$i++;
					}
					
					$i=0;
				}
				elseif($k1 == "type")
				{
					$finalArrApp["dppData"][$key][$k1] = $v1;
				}
				elseif($k1 == "heading")
				{
					$finalArrApp["dppData"][$key][$k1] = $v1;	
				}
				elseif($k1 == "range")
				{
					$finalArrApp["dppData"][$key][$k1] = $v1;	
				}
				
			}
		}
		return $finalArrApp;
	}

	public function getDppDataArr($decodedData)
	{
		$incomeStr= "";
		if(MobileCommon::isNewMobileSite())
		{
			if(is_object($decodedData)) //Is array checks have been added before loops
			{
				foreach($decodedData as $key=>$value) 
				{
					if(is_object($value))
					{
						foreach($value as $k1=>$v1)
						{
							if($k1 == DppAutoSuggestEnum::$OnClickLabel) 
							{
								if(is_array($v1))
								{
									foreach($v1 as $k2=>$v2)
									{
										if(in_array($v2->key,DppAutoSuggestEnum::$SUGGESTION_FIELDS) && strpos($v2->value,"DM") === false)
										{
											if(in_array($v2->key,DppAutoSuggestEnum::$incomeFieldJSMS))
											{						
												$incomeArr[] = $v2->value;
											}
											else
											{
												$dppDataArr[$i]["type"] = substr($v2->key,2);
												$dppDataArr[$i]["data"] = explode(",",$v2->value);
												$i++;
											}																
										}
									}
								}																				
							}
						}
					}
				}
			}
			
			$incomeStr = implode(",",$incomeArr);
			$dppDataArr[$i]["type"] = "INCOME";
			$dppDataArr[$i]["data"] = explode(",",$incomeStr);
		}
		else
		{
			if(is_object($decodedData))
			{
				foreach($decodedData as $key=>$value)
				{	
					if(in_array($value->key,DppAutoSuggestEnum::$SUGGESTION_FIELDS) && strpos($value->value,"DM") === false)
					{
						$dppDataArr[$key]["type"] = substr($value->key,2);
						$dppDataArr[$key]["data"] = explode(",",$value->value);
					}
				}
			}			
		}

		return $dppDataArr;
	}
}
?>