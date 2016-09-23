<?php

/**
 * This api is used to make Dpp Suggestions on certain fields
 *
 * @package    jeevansathi
 * @subpackage profile
 * @author     Sanyam Chopra
 * @date	   15th September 2016
 */

class dppSuggestionsV1Action extends sfActions 
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
		$percentileFields = DppAutoSuggestEnum::$TRENDS_FIELDS;
		$profileId = $this->loggedInProfileObj->getPROFILEID();
		
		//---- write redis code---
		$trendsObj = new TWOWAYMATCH_TRENDS("newjs_slave");
		$trendsArr = $trendsObj->getTrendsScore($profileId,$percentileFields);

		//JsMemcache::getInstance()->remove($profileId,$trendsArr);
		// foreach($trendsArr as $key=>$val)
		// {
		// 	if($key == "CASTE_VALUE_PERCENTILE")
		// 	{				
		// 		$casteTrend = $this->getTrendsValues($val);					
		// 	}
		// 	if($key == "MTONGUE_VALUE_PERCENTILE")
		// 	{				
		// 		$mtongueTrend=$this->getTrendsValues($val);	
		// 	}
		// 	if($key == "EDUCATION_VALUE_PERCENTILE")
		// 	{
		// 		$educationTrend = $this->getTrendsValues($val);	
		// 	}
		// 	if($key == "OCCUPATION_VALUE_PERCENTILE")
		// 	{
		// 		$occupationTrend = $this->getTrendsValues($val);
		// 	}
		// 	if($key == "CITY_VALUE_PERCENTILE")
		// 	{
		// 		$cityTrend = $this->getTrendsValues($val);
		// 	}
		// }
		
		// echo("caste\n");print_r($casteTrend);
		// echo("mtongue\n");print_r($mtongueTrend);
		// echo("education\n");print_r($educationTrend);
		// echo("occupation\n");print_r($occupationTrend);
		// echo("city\n");print_r($cityTrend);
		$data = $request->getParameter("Param");
		$decodedData = json_decode($data);
		foreach($decodedData as $key=>$val)
		{
			foreach($val as $key1=>$val1)
			{
				if($key1 == "type") //what if data is before type? 
				{										
					$type = $val1;	
				}
				if($key1 == "data")
				{					
					if($type == "CASTE")
					{
						//This code is to be run if condition 1 is not satified.
						$percentileArr = $trendsArr[$type."_VALUE_PERCENTILE"];
						$casteTrend = $this->getTrendsValues($percentileArr);
						if(count($casteTrend)>=5)
						{
							$valueArr = $this->getDppSuggestionsFromTrends($casteTrend,$type);							
						}						
						// else
						// {
						// 	foreach($val1 as $k2=>$v2)
						// 	{
						// 		$this->getDppSuggestionsForFilledValues($type,$v2);
						// 	}
						// }
						

						$valueArr["type"] = $type;
						//print_r($valueArr);die;
						//$valueArr = $this->getRelatedDppValues($type);
						
						// foreach($arr as $key=>$val)
						// {
						// 	$valueArr["data"][$val] = FieldMap::getFieldlabel("caste",$val,''); 
						// }
						// $valueArr["type"] = $type;
						// $casteValue = DppAutoSuggestValue::getAutoSuggestValue("CASTE",15,$this->loggedInProfileObj);
						// print_r($casteValue);echo("---\n\n");
					}
					if($type == "MTONGUE") //CHANGE THIS
					{
						$mtongueValue = $this->getRelatedDppValues("MTONGUE");
						$percentileArr = $trendsArr[$type."_VALUE_PERCENTILE"];
						$mtongueTrend = $this->getTrendsValues($percentileArr);
						$mtongueValue["type"] = "MTONGUE";
						//print_r($mtongueValue);die;
						// $mtongueValue = DppAutoSuggestValue::getAutoSuggestValue("MTONGUE",13,$this->loggedInProfileObj);
						// print_r($mtongueValue);echo("----\n\n");die;
					}

					//other if conditions to be added similarly
				}
				// if($key1 == "data")
				// {
				// 	foreach($val1 as $key2 => $val2)
				// 	{
				// 		// echo($val2."\n");
				// 	}
				// }
			}
		}
		
		if(is_array($valueArr))
			{
				$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
				$apiResponseHandlerObj->setResponseBody(json_encode($valueArr));
				$apiResponseHandlerObj->generateResponse();
			}

		// if(!$request->getParameter('profilechecksum'))
		// {
		// 	$apiResponseHandlerObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
		// 	$apiResponseHandlerObj->generateResponse();
		// 	die;
		// }
		
	}

	public function getRelatedDppValues($type)
	{
		$dppData = DppAutoSuggestEnum::$FIELD_ID_ARRAY;
		foreach($dppData as $key=>$value)
		{
			if($value == $type)
			{
				$suggestedValue = DppAutoSuggestValue::getAutoSuggestValue($type,$key,$this->loggedInProfileObj);
			}
		}
		$suggestedValue = trim($suggestedValue,"'");			
		$suggestedArr = explode("','",$suggestedValue);
		$type = strtolower($type);
		if($type == "mtongue")
		{
			$type = "community";
		}
		
		foreach($suggestedArr as $key=>$val)
		{
			$valueArr["data"][$val] = FieldMap::getFieldlabel($type,$val,''); 
		}
		$valueArr["type"] = $type;
		return $valueArr;
	}

	public function getTrendsValues($val)
	{
		$tempArray=explode("|",$val);
		$count = count($tempArray);
		unset($tempArray[0]);
		unset($tempArray[$count-1]);
		foreach($tempArray as $value)
		{
			list($value,$trend)=explode("#",$value);
			$resultTrend[$value]=$trend;

		}
		return $resultTrend;
	}

	public function getDppSuggestionsFromTrends($trendsArr,$type)
	{
		$count = 0;
		foreach($trendsArr as $k1=>$v1)
		{
			if($count < 5)
			{
				$valueArr["data"][$k1] = $this->getFieldMapValueForTrends($k1,$type);
				$count++;
			}
			else
			{
				break; //check 
			}
		}
		return $valueArr;


	}
	public function getFieldMapValueForTrends($key,$type)
	{
		$type = strtolower($type);
		if($type == "mtongue")
		{
			$type = "community";
		}

		$returnValue = FieldMap::getFieldlabel($type,$key,'');
		return $returnValue;
	}

	public function getDppSuggestionsForFilledValues($type,$value)
	{

	}
		
}
?>