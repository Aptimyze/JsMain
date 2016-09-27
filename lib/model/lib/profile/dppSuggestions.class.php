<?php

class dppSuggestions
{
	public function getDppSuggestions($trendsArr,$type,$valArr)
	{
		//print_R($valArr);die;
		$percentileArr = $trendsArr[$type."_VALUE_PERCENTILE"];
		$trendVal = $this->getTrendsValues($percentileArr);		
		//print_R($trendVal);die;
		$valueArr = $this->getDppSuggestionsFromTrends($trendVal,$type,$valArr);
		//print_R($valueArr);die;
		if(count($valueArr["data"])<10)	// add in constants and change to 10		
		{
			if($type == "CITY")
			{
				foreach($valArr as $key=>$value)
				{
					if(in_array($value,DppAutoSuggestEnum::$delhiNCRCities))
					{
						$ncrKey = implode(',',DppAutoSuggestEnum::$delhiNCRCities);
						$valueArr["data"][$ncrKey] = "Delhi NCR";
					}
					if(in_array($value,DppAutoSuggestEnum::$mumbaiRegion))
					{
						$mumbaiRegionKey = implode(',',DppAutoSuggestEnum::$mumbaiRegion);
						$valueArr["data"][$mumbaiRegionKey] = "Mumbai Region";
					}
				}
				$valueArr["type"] = $type;
			}
			elseif ($type == "EDUCATION" || $type == "OCCUPATION")
			{
				$valueArr = $this->getSuggestionsFromGroupings($valueArr,$type,$valArr);
				$valueArr["type"] = $type;
				//print_r($valueArr);die;
			}
			else
			{
				foreach($valArr as $k2=>$v2)
				{
					$suggestedValueArr[$v2] = $this->getDppSuggestionsForFilledValues($type,$v2);
				}
				//print_R($suggestedValueArr);die;
				if(is_array($suggestedValueArr))
				{
					$valueArr = $this->getRemainingSuggestionValues($suggestedValueArr,$type,count($valueArr["data"]),$valueArr);	
				}
			}
										
		}


		$valueArr["type"] = $type;
		return $valueArr;
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

	public function getDppSuggestionsFromTrends($trendsArr,$type,$valArr)
	{
		$count = 0;
		foreach($trendsArr as $k1=>$v1)
		{
			if($count < 5)
			{
				if(!in_array($k1,$valArr))
				{
					$valueArr["data"][$k1] = $this->getFieldMapValueForTrends($k1,$type);
					$count++;
				}
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
		if($type != "city")
		{
			$returnValue = FieldMap::getFieldlabel($type,$key,'');
		}
		else
		{
			$stateIndiaArr = FieldMap::getFieldLabel("state_india",'',1);
			if(array_key_exists($key, $stateIndiaArr))
			{
				$returnValue = $stateIndiaArr[$key];
			}
			else
			{
				$returnValue = FieldMap::getFieldlabel($type,$key,'');
			}
		}
		return $returnValue;
	}

	public function getDppSuggestionsForFilledValues($type,$fieldValue)
	{		
		$dppData = DppAutoSuggestEnum::$FIELD_ID_ARRAY;		
		foreach($dppData as $key=>$value)
		{
			if($value == $type)
			{
				$suggestedValue = DppAutoSuggestValue::getDppSuggestionsForFilledValues($type,$key,$fieldValue);
			}
		}
		$suggestedValue = explode("','",trim($suggestedValue,"'"));
		return $suggestedValue;
	}

	public function getRemainingSuggestionValues($suggestedValueArr,$type,$valueArrDataCount,$valueArr)
	{
		$type = strtolower($type);
		if($type == "mtongue")
		{
			$type = "community";
		}
		foreach($suggestedValueArr as $fieldId =>$vArr)
		{
			foreach($vArr as $k3=>$v3)
			{
				if($v3!="")
				{
					if(array_key_exists($v3, $suggestedValueCountArr))
					{
						$suggestedValueCountArr[$v3]++;
					}
					else
					{
						$suggestedValueCountArr[$v3] = 1;
					}
				}
				
			}
		}
		arsort($suggestedValueCountArr);
		$remainingCount = DppAutoSuggestEnum::$NO_OF_DPP_SUGGESTIONS - $valueArrDataCount;

		foreach($suggestedValueCountArr as $fieldId=>$freqDistribution)
		{
			if($remainingCount != 0)
			{
				if(!array_key_exists($fieldId, $valueArr["data"]))
				{
					$valueArr["data"][$fieldId] =  FieldMap::getFieldlabel($type,$fieldId,'');
				}

				$remainingCount--;
			}									
			else
			{
				break;
			}
		}
		return $valueArr;	
	}

	public function getSuggestionsFromGroupings($valueArr,$type,$valArr)
	{	
		$SuggestionArr = array();
		if($type == "EDUCATION")
		{
			$GroupingArr  = FieldMap::getFieldlabel(DppAutoSuggestEnum::$eduGrouping,'',1);
		}
		if($type == "OCCUPATION")
		{
			$GroupingArr  = FieldMap::getFieldlabel(DppAutoSuggestEnum::$occupationGrouping,'',1);
		}
		foreach($GroupingArr as $groupingKey => $stringVal)
		{
			$GroupingArr[$groupingKey] = explode(",",$stringVal);
		}

		foreach($valArr as $k1=>$v1)
		{
			foreach($GroupingArr as $groupingKey=>$vArr)
			{
				foreach($vArr as $k2=>$v2)
				{
					if($v1 == $v2)
					{
						if(array_key_exists($groupingKey, $SuggestionArr))
						{
							$SuggestionArr[$groupingKey]++;
						}
						else
						{
							$SuggestionArr[$groupingKey]=1;
						}
					}
				}
			}
		}
		arsort($SuggestionArr);
				//print_R($educationSuggestionArr);die;
		$remainingCount = DppAutoSuggestEnum::$NO_OF_DPP_SUGGESTIONS - count($valueArr["data"]);
				//echo($remainingCount);die;
		foreach($SuggestionArr as $groupingKey=>$freqDistribution)
		{
			if($remainingCount != 0)
			{
				if(array_key_exists($groupingKey, $GroupingArr))
				{
					$ValArr = $GroupingArr[$groupingKey];
				}
						//print_r($eduValArr);die;
						//echo($type);
						//print_R($valueArr["data"]);die;
				foreach($ValArr as $k=>$v)
				{
					if(!array_key_exists($v, $valueArr["data"]) && $remainingCount >0)
					{
						$valueArr["data"][$v] =  FieldMap::getFieldlabel(strtolower($type),$v,'');
						$remainingCount--;
					}
				}						
			}									
			else
			{
				break;
			}
		}

		return $valueArr;
	}

	public function getTrendsArr($profileId,$percentileFields)
	{
		// $pidKey = $profileId."_dpp";
		// $resultArr = IgnoredProfileCacheLib::getInstance()->getSetsAllValue($pidKey);
		// if($resultArr == "noKey" || $resultArr == false)
		// {
		// 	$NEWJS_IGNOREObj = new newjs_IGNORE_PROFILE($this->dbname);
		// 	$resultArr = $NEWJS_IGNOREObj->listIgnoredProfile($pid,$seperator);
		// 	$this->addDataToFile("new");
		// 	IgnoredProfileCacheLib::getInstance()->storeDataInCache($pidKey,$resultArr);
		// 	return $resultArr;
		// }
		// else
		// {        		
		// 	return $resultArr;        		
		// }
		$trendsObj = new TWOWAYMATCH_TRENDS("newjs_slave");
		$trendsArr = $trendsObj->getTrendsScore($profileId,$percentileFields);
		return $trendsArr;
	}
}
?>