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
	const MAILBODY = "Dpp Suggestions CAL VALUE NUll";
	const RECEIVER = "sanyam1204@gmail.com";    
    const SENDER = "info@jeevansathi.com";
    const SUBJECT = "dpp suggestion CAL null value";
	/**
	* Executes index action
	*
	* @param sfRequest $request A request object
	*/
	public function execute($request)
	{
		$calLayer = 1;
		$apiResponseHandlerObj=ApiResponseHandler::getInstance();
		$this->loginProfile = LoggedInProfile::getInstance();			
		$apiProfileSectionObj=  ApiProfileSections::getApiProfileSectionObj($this->loginProfile);		
		
		//created obj of EditDetails
		$editDetailsObj = new EditDetails();
		$jpartnerObj=$editDetailsObj->getJpartnerObj($this);
		$this->loginProfile->setJpartner($jpartnerObj);

		//This decoded data is an array and not an object. Therefore, is_array checks need to be applied and forloops need to be altered
		$decodedData =  $editDetailsObj->getDppValuesArr($apiProfileSectionObj,'1');				
			
		//getDppDataArr is used to format the data in the required format.
		$dppDataArr = $this->getDppDataArr($decodedData);		
		$percentileFields = DppAutoSuggestEnum::$TRENDS_FIELDS;
		$profileId = $this->loginProfile->getPROFILEID();
		$dppSuggestionsObj = new dppSuggestions();
		$trendsObj = new TWOWAYMATCH_TRENDS("newjs_masterRep");

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

		
		if(MobileCommon::isApp())
		{
			$finalArr = $this->getFormattedArrForApp($finalArr);									
		}
		else
		{
			$finalArr = $this->getFormattedArrForMobileSite($finalArr);
		}
		$finalArr["Description"] = DppAutoSuggestEnum::$descriptionText;				
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
						if(MobileCommon::isApp()=="A")
						{
							if($v2 != null && $v2 != "")
							{
								$finalArrApp["dppData"][$key][$k1][$i]["id"] = $k2;
								$finalArrApp["dppData"][$key][$k1][$i]["value"] = $v2;	
								$i++;
							}
							// else
							// {
							// 	$mailBody = self::MAILBODY."on: ".$value["type"]." with key: ".$k2."\n ".print_r($_SERVER,true);
							// 	SendMail::send_email(self::RECEIVER,$mailBody,self::SUBJECT,self::SENDER);								
							// }
						}
						else
						{
							$finalArrApp["dppData"][$key][$k1][$i]["id"] = $k2;
							$finalArrApp["dppData"][$key][$k1][$i]["value"] = $v2;	
							$i++;
						}
											
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
			if(is_array($decodedData)) //Is array checks have been added before loops
			{
				foreach($decodedData as $key=>$value) 
				{
					if(is_array($value))
					{
						foreach($value as $k1=>$v1)
						{
							if($k1 == DppAutoSuggestEnum::$OnClickLabel) 
							{
								if(is_array($v1))
								{
									foreach($v1 as $k2=>$v2)
									{
										if(in_array($v2["key"],DppAutoSuggestEnum::$SUGGESTION_FIELDS) && strpos($v2["value"],"DM") === false)
										{
											if(in_array($v2["key"],DppAutoSuggestEnum::$incomeFieldJSMS))
											{						
												$incomeArr[] = $v2["value"];
											}
											else
											{
												$dppDataArr[$i]["type"] = substr($v2["key"],2);
												$dppDataArr[$i]["data"] = explode(",",$v2["value"]);
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
			if(is_array($decodedData))
			{
				foreach($decodedData as $key=>$value)
				{	
					if(in_array($value["key"],DppAutoSuggestEnum::$SUGGESTION_FIELDS) && strpos($value->value,"DM") === false)
					{
						$dppDataArr[$key]["type"] = substr($value["key"],2);
						$dppDataArr[$key]["data"] = explode(",",$value["value"]);
					}
				}
			}			
		}

		return $dppDataArr;
	}

	public function getFormattedArrForMobileSite($dppSuggestionArr)
	{
		$finalArr["dppData"] = $dppSuggestionArr;
		return $finalArr;
	}
}
?>