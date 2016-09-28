<?php

class dppSuggestions
{
	//This function fetches dppSuggestion values to be shown and returns it to the calling function
	public function getDppSuggestions($trendsArr,$type,$valArr)
	{
		$percentileArr = $trendsArr[$type."_VALUE_PERCENTILE"];
		$trendVal = $this->getTrendsValues($percentileArr);		
		$valueArr = $this->getDppSuggestionsFromTrends($trendVal,$type,$valArr);
		if(is_array($valueArr) && count($valueArr["data"])< DppAutoSuggestEnum::$NO_OF_DPP_SUGGESTIONS)
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
			}
			else
			{
				foreach($valArr as $k2=>$v2)
				{
					$suggestedValueArr[$v2] = $this->getDppSuggestionsForFilledValues($type,$v2);
				}
				if(is_array($suggestedValueArr))
				{
					$valueArr = $this->getRemainingSuggestionValues($suggestedValueArr,$type,count($valueArr["data"]),$valueArr,$valArr);	
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

	//This function uses the array in $trendsArr and converts in into the desired key=>value paired Array
	public function getTrendsValues($val)
	{
		$tempArray=explode("|",$val);
		$count = count($tempArray);
		unset($tempArray[0]);
		unset($tempArray[$count-1]);
		if(is_array($tempArray))
		{
			foreach($tempArray as $value)
			{
				list($value,$trend)=explode("#",$value);
				$resultTrend[$value]=$trend;

			}
		}
		return $resultTrend;
	}

	//This function takes the trendsArr for each $type and gets the trends data to be sent as apiResponse
	public function getDppSuggestionsFromTrends($trendsArr,$type,$valArr)
	{
		$count = 0;
		foreach($trendsArr as $k1=>$v1)
		{
			if($count < DppAutoSuggestEnum::$NO_OF_DPP_SUGGESTIONS)
			{
				if(!in_array($k1,$valArr) && $type != "CITY")
				{
					$valueArr["data"][$k1] = $this->getFieldMapValueForTrends($k1,$type);
					$count++;
				}
				elseif(!in_array($k1,$valArr))
				{
					$this->stateIndiaArr = FieldMap::getFieldLabel("state_india",'',1);
					$this->cityIndiaArr = FieldMap::getFieldLabel("city_india",'',1);
					if(array_key_exists($k1, $this->stateIndiaArr) || array_key_exists($k1, $this->cityIndiaArr))
					{
						$valueArr["data"][$k1] = $this->getFieldMapValueForTrends($k1,$type);
						$count++;
					}
				}
			}
			else
			{
				break; //check 
			}
		}
		return $valueArr;


	}

	//This function gets the value for the $key specified for the given $type
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
			if(array_key_exists($key, $this->stateIndiaArr))
			{
				$returnValue = $this->stateIndiaArr[$key];
			}
			elseif(array_key_exists($key, $this->cityIndiaArr))
			{
				$returnValue = $this->cityIndiaArr[$key];
			}
		}
		return $returnValue;
	}

	//this functions calls the dppAutoSuggestValue function to get the suggested Values corresponding to each input value based on the key and type
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

	//For the suggestedValueArr formed, frequency distribution is calculated and sorted array is then picked to fill in the remaining values.
	public function getRemainingSuggestionValues($suggestedValueArr,$type,$valueArrDataCount,$valueArr,$valArr)
	{
		$type = strtolower($type);
		if($type == "mtongue")
		{
			$type = "community";
		}

		//frequency distribution calculation
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
				if(!array_key_exists($fieldId, $valueArr["data"]) && !in_array($fieldId,$valArr))
				{
					$valueArr["data"][$fieldId] =  FieldMap::getFieldlabel($type,$fieldId,'');
					$remainingCount--;
				}				
			}									
			else
			{
				break;
			}
		}
		return $valueArr;	
	}

	//This functions finds dppSuggestions values for Education and Occupation depending on the frequency distribution of groupings that the input values belong to
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

		//to find the frequency distribution of grouping array based on the input values sent
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
		$remainingCount = DppAutoSuggestEnum::$NO_OF_DPP_SUGGESTIONS - count($valueArr["data"]);
		
		//This loop is on sorted grouping array based on which values corresponding each grouping are fetched and evaluated
		foreach($SuggestionArr as $groupingKey=>$freqDistribution)
		{
			if($remainingCount != 0)
			{
				if(array_key_exists($groupingKey, $GroupingArr))
				{
					$ValArr1 = $GroupingArr[$groupingKey];
				}
				foreach($ValArr1 as $k=>$v)
				{
					if(!array_key_exists($v, $valueArr["data"]) && $remainingCount >0 && !in_array($v,$valArr))
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

	//This function checks redis if a value exists corresponding to the key specified or else sends a query to fetch trendsArr
	public function getTrendsArr($profileId,$percentileFields)
	{
		$pidKey = $profileId."_dppSuggestions";
		$trendsArr = dppSuggestionsCacheLib::getInstance()->getHashValueForKey($pidKey);
		if($trendsArr == "noKey" || $trendsArr == false)
		{
			$trendsObj = new TWOWAYMATCH_TRENDS("newjs_slave");
			$trendsArr = $trendsObj->getTrendsScore($profileId,$percentileFields);
			dppSuggestionsCacheLib::getInstance()->storeHashValueForKey($pidKey,$trendsArr);
			return $trendsArr;
		}
		else
		{        		
			return $trendsArr;        		
		}
	}

}
?>