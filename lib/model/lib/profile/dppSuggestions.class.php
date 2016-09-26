<?php

class dppSuggestions
{
	public function getDppSuggestions($trendsArr,$type)
	{
		$percentileArr = $trendsArr[$type."_VALUE_PERCENTILE"];
		$trendVal = $this->getTrendsValues($percentileArr);		
		$valueArr = $this->getDppSuggestionsFromTrends($trendVal,$type);							
		if(count($valueArr["data"])<10)			
		{
			foreach($val1 as $k2=>$v2)
			{
				$suggestedValueArr[$v2] = $this->getDppSuggestionsForFilledValues($type,$v2);
			}
			if(is_array($suggestedValueArr))
			{
				$valueArr = $this->getRemainingSuggestionValues($suggestedValueArr,$type,count($valueArr["data"]),$valueArr);	
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

	public function getDppSuggestionsForFilledValues($type,$fieldValue)
	{		
		//echo($type);echo($fieldValue);die;
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
}
?>