<?php

class dppSuggestions
{
	//This function fetches dppSuggestion values to be shown and returns it to the calling function
	public function getDppSuggestions($trendsArr,$type,$valArr,$calLayer="",$loggedInProfileObj = "")
	{				//echo "<pre>";print_R($trendsArr);print_R($type);print_R($valArr);
                if($loggedInProfileObj == ""){
                        $loggedInProfileObj = LoggedInProfile::getInstance();
                }
		$this->age = $loggedInProfileObj->getAGE();
		$this->gender = $loggedInProfileObj->getGENDER();
		$this->income = $loggedInProfileObj->getINCOME();
		$this->religion = $loggedInProfileObj->getRELIGION();
		$this->calLayer = $calLayer;
		if($this->calLayer)
		{
			$this->countForComparison = DppAutoSuggestEnum::$NO_OF_DPP_SUGGESTIONS_CAL;
		}
		else
		{
			$this->countForComparison = DppAutoSuggestEnum::$NO_OF_DPP_SUGGESTIONS;
		}

		if($type == "CITY")
		{
			$valueArr["data"] = $this->getDelhiMumbaiSuggestions($valArr);						
		}
		if($type == "MTONGUE")
		{
			$valueArr["data"] = $this->getHindiAllSuggestions($valArr);
			$valueArr["data"] = $this->getUrduHindiDelhiSuggestions($valArr,$valueArr["data"]);
		}
		if(is_array($trendsArr) && !empty($trendsArr))
		{
			$percentileArr = $trendsArr[$type."_VALUE_PERCENTILE"];
			$trendVal = $this->getTrendsValues($percentileArr);	
			$valueArr = $this->getDppSuggestionsFromTrends($trendVal,$type,$valArr,$valueArr);			
		}
		if($type == "AGE")
		{
			$valueArr = $this->getSuggestionForAge($type,$valArr);
		}
		if($type == "INCOME")
		{
			$valueArr = $this->getSuggestionForIncome($type,$valArr,$calLayer);
		}
		if($type == "RELIGION")
		{
			$valueArr["data"] = $this->getSuggestionsForReligion($type,$valArr);
		}	
                if($type == "HEIGHT"){
                        $valueArr["data"] = $this->getSuggestionForHeight($loggedInProfileObj, $valArr);
                }
                if($type == "DRINK"){
                        $valueArr = $this->getSuggestionForDrinkOrSmoke('drink',$valArr);
                }
                if($type == "SMOKE"){
                        $valueArr = $this->getSuggestionForDrinkOrSmoke('smoke',$valArr);
                }
		if(count($valueArr["data"])< $this->countForComparison)
		{
			if ($type == "EDUCATION")
			{
				$valueArr = $this->getSuggestionsFromGroupings($valueArr,$type,$valArr);
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
		if($type == "AGE" || $type == "INCOME" || $type == "HEIGHT")
		{
			$valueArr["range"] = 1;
		}
		else
		{
			$valueArr["range"] = 0;
		}
		if(MobileCommon::isApp() || MobileCommon::isNewMobileSite())
		{
			$valueArr["heading"] = DppAutoSuggestEnum::$headingForApp[$type];
		}
                
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
	public function getDppSuggestionsFromTrends($trendsArr=array(),$type,$valArr=array(),$valueArr=array())
	{
		$count = count($valueArr["data"]);
		
		foreach($trendsArr as $k1=>$v1)
		{
			if($count < $this->countForComparison)
			{
				if(!in_array($k1,$valArr) && !in_array($type,DppAutoSuggestEnum::$typeArr))//($type != "CITY" || $type != "MTONGUE"))
				{
					$valueArr["data"][$k1] = $this->getFieldMapValueForTrends($k1,$type);
					$count++;
				}
				elseif(!in_array($k1,$valArr) && $type == "CITY")
				{						
					$this->stateIndiaArr = $this->getFieldMapLabels("state_india",'',1);
					$this->cityIndiaArr = $this->getFieldMapLabels("city_india",'',1);
					
					//if Delhi NCR or Mumbai Region is selected, then Mumbai region cities and Delhi NCR cities should not be shown
					if(array_key_exists($k1, $this->stateIndiaArr) || array_key_exists($k1, $this->cityIndiaArr))
					{
						if(in_array(DppAutoSuggestEnum::$delhiNCRCitiesStr,$valArr) || in_array(DppAutoSuggestEnum::$mumbaiRegionStr,$valArr))
						{
							if(!in_array($k1, DppAutoSuggestEnum::$delhiNCRCities) && !in_array($k1, DppAutoSuggestEnum::$mumbaiRegion))
							{
								$valueArr["data"][$k1] = $this->getFieldMapValueForTrends($k1,$type);
								$count++;
							}
						}
						else
						{
							$valueArr["data"][$k1] = $this->getFieldMapValueForTrends($k1,$type);
							$count++;
						}
						
					}
				}
				elseif(!in_array($k1,$valArr) && $type == "MTONGUE")
				{
					//not to show subset value if Hindi-All is selected
					if(in_array(implode(FieldMap::getFieldLabel("allHindiMtongues","",1),","),$valArr))
					{
						if(!in_array($k1, FieldMap::getFieldLabel("allHindiMtongues","",1)))
						{
							$valueArr["data"][$k1] = $this->getFieldMapValueForTrends($k1,$type);
							$count++;
						}
					}
					else
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
		$type = $this->getType($type);		
		if($type == "community")
		{
			$type = $type."_small";
		}
		if($type != "city")
		{
			$returnValue = $this->getFieldMapLabels($type,$key,'');//FieldMap::getFieldlabel($type,$key,'');
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
				$suggestedValue = $this->getValueFromDppAutoSuggestValue($type,$key,$fieldValue); //DppAutoSuggestValue::getDppSuggestionsForFilledValues($type,$key,$fieldValue);
			}
		}
		$suggestedValue = explode("','",trim($suggestedValue,"'"));
		return $suggestedValue;
	}

	//For the suggestedValueArr formed, frequency distribution is calculated and sorted array is then picked to fill in the remaining values.
	public function getRemainingSuggestionValues($suggestedValueArr,$type,$valueArrDataCount,$valueArr,$valArr)
	{
		$type = $this->getType($type);		
		if($type == "community")
		{
			$type = $type."_small";
		}

		$allHindiMtongues = FieldMap::getFieldLabel("allHindiMtongues","",1);
		$hindiAllVal = implode($allHindiMtongues,",");
		$hindiMtongueCount = count(array_intersect($valArr, $allHindiMtongues));
		//frequency distribution calculation
		$suggestedValueCountArr = $this->getFrequencyDistributedArrForCasteMtongue($suggestedValueArr);
		$suggestedValueCountArr = $this->getSortedSuggestionArr($suggestedValueCountArr);
		
		if(in_array($hindiAllVal,$valArr) || $hindiMtongueCount >= count($allHindiMtongues))
		{
			foreach($allHindiMtongues as $k=>$v)
			{
				unset($suggestedValueCountArr[$v]);
			}
		}		
		$remainingCount = $this->countForComparison - $valueArrDataCount;
		foreach($suggestedValueCountArr as $fieldId=>$freqDistribution)
		{
			if($remainingCount != 0)
			{
				if(!array_key_exists($fieldId, $valueArr["data"]) && !in_array($fieldId,$valArr))
				{
					$valueArr["data"][$fieldId] =  $this->getFieldMapLabels($type,$fieldId,'');//FieldMap::getFieldlabel($type,$fieldId,'');
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
		$GroupingArr = $this->getGroupingArr($type);
		//to find the frequency distribution of grouping array based on the input values sent
		$suggestionArr = $this->getFrequencyDistributedArr($valArr,$GroupingArr);
		$suggestionArr = $this->getSortedSuggestionArr($suggestionArr);
		$remainingCount = $this->countForComparison - count($valueArr["data"]);
		$valueArr = $this->fillRemainingValuesInEduOccValueArr($remainingCount,$suggestionArr,$valueArr,$valArr,$GroupingArr,$type);	
		return $valueArr;
	}

	//This function checks redis if a value exists corresponding to the key specified or else sends a query to fetch trendsArr
	public function getTrendsArr($profileId,$percentileFields,$trendsObj)
	{
		$pidKey = $profileId."_dppSuggestions";
		$trendsArr = dppSuggestionsCacheLib::getInstance()->getHashValueForKey($pidKey);	
		if($trendsArr == "noKey" || $trendsArr == false)
		{			
			$trendsArr = $trendsObj->getTrendsScore($profileId,$percentileFields);		
			if(!is_array($trendsArr))
				$trendsArr= Array('0'=>'0');
			dppSuggestionsCacheLib::getInstance()->storeHashValueForKey($pidKey,$trendsArr);
			return $trendsArr;
		}
		else
		{      
			unset($trendsArr['0']);  		
			return $trendsArr;        		
		}
	}

	//This function gets labels from FieldMapLib depending on $labels,$value,$returnArr
	public function getFieldMapLabels($label,$value,$returnArr='')
	{
		return FieldMap::getFieldlabel($label,$value,$returnArr);
	}

	//This function calls function of dppAutoSuggestValue to getDppSuggestion values
	public function getValueFromDppAutoSuggestValue($type,$key,$fieldValue)
	{
		return DppAutoSuggestValue::getDppSuggestionsForFilledValues($type,$key,$fieldValue);
	}

	//This function is used to get frequency distribution array
	public function getFrequencyDistributedArr($valArr,$GroupingArr)
	{
		$suggestionArr = array();
		foreach($valArr as $k1=>$v1)
		{
			foreach($GroupingArr as $groupingKey=>$vArr)
			{
				foreach($vArr as $k2=>$v2)
				{
					if($v1 == $v2)
					{
						if(array_key_exists($groupingKey, $suggestionArr))
						{
							$suggestionArr[$groupingKey]++;
						}
						else
						{
							$suggestionArr[$groupingKey]=1;
						}
					}
				}
			}
		}
		return $suggestionArr;
	}	

	// This function sorts the array
	public function getSortedSuggestionArr($suggestionArr)
	{
		arsort($suggestionArr);
		return $suggestionArr;
	}

	//This function checks if mumbai region or delhi region value exists in $value and accordingly puts value in $value
	public function getNCRMumbaiCity($value)
	{
		if(in_array($value,DppAutoSuggestEnum::$delhiNCRCities))
		{
			$key = implode(',',DppAutoSuggestEnum::$delhiNCRCities);
			$city['VALUE'] = "Delhi NCR";
			$city['KEY']=$key;
		}
		if(in_array($value,DppAutoSuggestEnum::$mumbaiRegion))
		{
			$key = implode(',',DppAutoSuggestEnum::$mumbaiRegion);
			$city['VALUE'] = "Mumbai Region";
			$city['KEY']=$key;
		}
		return $city;
	}

	//This function gets Frequency Distribution for Caste and Mtongue
	public function getFrequencyDistributedArrForCasteMtongue($suggestedValueArr)
	{	
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
		return $suggestedValueCountArr;
	}

	// This function fills remaining values in Education and occupation section of value array
	public function fillRemainingValuesInEduOccValueArr($remainingCount,$SuggestionArr,$valueArr,$valArr,$GroupingArr,$type)
	{
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
					if(!array_key_exists($v, $valueArr["data"]) && !in_array($v,$valArr) && $remainingCount >0)
					{
						$valueArr["data"][$v] =  $this->getFieldMapLabels(strtolower($type),$v,'');//FieldMap::getFieldlabel(strtolower($type),$v,'');
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

	public function getType($type)
	{
		$type = strtolower($type);
		if($type == "mtongue")
		{
			$type = "community";
		}
		return $type;
	}

	public function getGroupingArr($type)
	{
		if($type == "EDUCATION")
		{
			$GroupingArr  = $this->getFieldMapLabels(DppAutoSuggestEnum::$eduGrouping,'',1);//FieldMap::getFieldlabel(DppAutoSuggestEnum::$eduGrouping,'',1);
		}
		/*if($type == "OCCUPATION")
		{
			$GroupingArr  = $this->getFieldMapLabels(DppAutoSuggestEnum::$occupationGrouping,'',1);//FieldMap::getFieldlabel(DppAutoSuggestEnum::$occupationGrouping,'',1);
		}*/
		foreach($GroupingArr as $groupingKey => $stringVal)
		{
			$GroupingArr[$groupingKey] = explode(",",$stringVal);
		}

		return $GroupingArr;
	}

	public function getSuggestionForAge($type,$valArr)
        {
		$valArr = array_combine(DppAutoSuggestEnum::$keyReplaceAgeArr,$valArr);
		if($this->gender == "F")
		{
			$minAge = min($valArr["LAGE"],$this->age);
			$maxAge = max($valArr["HAGE"],$this->age+DppAutoSuggestEnum::$ageDiffNo);
		}
		elseif($this->gender == "M")
		{		
			$minAge = min($valArr["LAGE"],$this->age - DppAutoSuggestEnum::$ageDiffNo);
			$maxAge = max($valArr["HAGE"],$this->age);
		}

		if($minAge < $valArr["LAGE"] || $maxAge > $valArr["HAGE"])
		{
			$valueArr["data"]["LAGE"] = $minAge;
			$valueArr["data"]["HAGE"] = $maxAge;
		}
		return $valueArr;
	}

	//Mapping of income needs to be changed.
	public function getSuggestionForIncome($type,$valArr,$calLayer="")
	{	
		
		$valArr = array_combine(DppAutoSuggestEnum::$keyReplaceIncomeArr,$valArr);			
		
		$hIncomeDol = $this->getFieldMapLabels("hincome_dol",'',1);
		$hIncomeRs = $this->getFieldMapLabels("hincome",'',1);		
		if($calLayer)
		{
			foreach($valArr as $key=>$val)
			{
				if($val == 0)
				{
					$valArr[$key] = TopSearchBandConfig::$noIncomeLabel;
				}
				elseif(array_key_exists($val, $hIncomeRs))
				{					
						$valArr[$key] = $hIncomeRs[$val];
				}
				elseif(array_key_exists($val, $hIncomeDol))
				{
					$valArr[$key] = $hIncomeDol[$val];	
				}
			}
		}
		if(!is_array($valArr)) // in case the value in income is not set , we put no income in LDS and LRS and then suggest noIncome to an above
		{
			$valArr["LDS"] = TopSearchBandConfig::$noIncomeLabel;
			$valArr["LRS"] = TopSearchBandConfig::$noIncomeLabel;
		}

		$incomeLevel = $this->getFieldMapLabels("income_level",'',1);
		$annualIncome = $incomeLevel[$this->income];
		$mappedIncome = DppAutoSuggestEnum::$incomeMappingArr[$this->income];

		if($this->gender == "M")
		{
			if(!in_array($annualIncome,DppAutoSuggestEnum::$rupeeIncomeArr))
			{
				$key = array_search($valArr["HDS"],$hIncomeDol);				
				if($mappedIncome > $key && $key!="19")
				{	
					$valueArr["data"]["LDS"] = $valArr["LDS"];
					$valueArr["data"]["HDS"] = $hIncomeDol[$mappedIncome];
				}
			}
			else
			{
				$key = array_search($valArr["HRS"],$hIncomeRs);				
				if($mappedIncome > $key && $key!="19")
				{
					$valueArr["data"]["LRS"] = $valArr["LRS"];
					$valueArr["data"]["HRS"] = $hIncomeRs[$mappedIncome];					
				}
			}		
		}
		elseif($this->gender == "F")
		{
			if($valArr["HRS"] != "and above")
			{
				$valueArr["data"]["LRS"] = $valArr["LRS"];
				$valueArr["data"]["HRS"] = "and above";				
			}
			if($valArr["HDS"] !="and above")
			{
				$valueArr["data"]["LDS"] = $valArr["LDS"];
				$valueArr["data"]["HDS"] = "and above";				
			}
		}
		return $valueArr;
	}

	public function getDelhiMumbaiSuggestions($valArr)
	{
		$delhiCityCount = count(array_intersect($valArr, DppAutoSuggestEnum::$delhiNCRCities));
		$mumbaiCityCount = count(array_intersect($valArr, DppAutoSuggestEnum::$mumbaiRegion));		
		foreach($valArr as $key=>$val)
		{
			if($delhiCityCount < count(DppAutoSuggestEnum::$delhiNCRCities))
			{
				if(in_array($val,DppAutoSuggestEnum::$delhiNCRCities) && !in_array(TopSearchBandConfig::$ncrLabel,$valueArr["data"]) && !in_array(DppAutoSuggestEnum::$delhiNCRCitiesStr,$valArr))
				{
					$arr[DppAutoSuggestEnum::$delhiNCRCitiesStr] = TopSearchBandConfig::$ncrLabel;
				}
			}
			
			if($mumbaiCityCount < count(DppAutoSuggestEnum::$mumbaiRegion))
			{
				if(in_array($val,DppAutoSuggestEnum::$mumbaiRegion) && !in_array(TopSearchBandConfig::$mumbaiRegionLabel,$valueArr["data"])  && !in_array(DppAutoSuggestEnum::$mumbaiRegionStr,$valArr))
				{
					$arr[DppAutoSuggestEnum::$mumbaiRegionStr] = TopSearchBandConfig::$mumbaiRegionLabel;
				}
			}			
		}
		return $arr;
	}
        public function getUrduHindiDelhiSuggestions($valArr,$valueArr=array()){
                $valArr = array_unique($valArr);
                $checkHindiMtongueValues = array("36","10","19","33","28");
                foreach ($valArr as $key => $value) {
                        if (in_array(trim($value, ' '), $checkHindiMtongueValues) && $this->religion == 2){
                                $mtongueHindiUrduFlag = 1;
                        }
                }
                if($mtongueHindiUrduFlag == 1){
                        foreach($checkHindiMtongueValues as $mtongue){
                                $valueArr[$mtongue] = FieldMap::getFieldlabel("community_small",$mtongue);
                        }
                }
                return $valueArr;
        }
	public function getHindiAllSuggestions($valArr)
	{		
		$valArr = array_unique($valArr);
		$allHindiMtongues = FieldMap::getFieldLabel("allHindiMtongues","",1);
		$hindiAllVal = implode($allHindiMtongues,",");
		$hindiMtongueCount = count(array_intersect($valArr, $allHindiMtongues));
		
		$mtongueArr = array();

		//add Hindi-All if it doesnt already exist in the selected array and if all the individual fields are not selected
		if(!in_array($hindiAllVal, $valArr) && $hindiMtongueCount < count($allHindiMtongues))
		{
			if(is_array($valArr))
			{
				foreach($valArr as $key=>$val)
				{
					if(in_array($val, $allHindiMtongues))
					{
						$mtongueArr[$hindiAllVal] = DppAutoSuggestEnum::$hindiAllLabel;
						break;
					}
				}
			}			
		}
		return $mtongueArr;
	}

	public function getSuggestionsForReligion($type,$valArr)
	{
		$religionArr = array();
		$religionValues = FieldMap::getFieldLabel("religion","",1);		
		$religionValuesArr = array_flip($religionValues);

		if(is_array($valArr))
		{
			if(in_array($religionValuesArr["Hindu"],$valArr))
			{
				$religionArr[] = $religionValuesArr["Sikh"];
				$religionArr[] = $religionValuesArr["Jain"];				
			}
			if(in_array($religionValuesArr["Sikh"],$valArr) || in_array($religionValuesArr["Jain"],$valArr))
			{
				$religionArr[]=$religionValuesArr["Hindu"];				
			}
			unset($religionValuesArr);
			if(is_array($religionArr) && !empty($religionArr))
			{
				$result = array_diff($religionArr, $valArr);
			}
			foreach($result as $k=>$v)
			{
				$finalArr[$v] = $religionValues[$v];
			}
			unset($religionValues);
		}	
		return $finalArr;
	}
        public function getSuggestionForHeight($loggedInProfileObj,$valueArr=array()){
            $arr=DppAutoSuggestEnum::$HEIGHT_ARRAY;
            $height=$loggedInProfileObj->getHEIGHT();
            $gender=$loggedInProfileObj->getGENDER();
            foreach($arr as $K=>$V)
            {
                foreach($V as $k=>$v)
                {
                    if($K==$gender)
                        if($k==$height){
                            foreach ($v as $k1=>$v1){
                                $sugHgt[0] = $k1;
                                $sugHgt[1] = $v1;
                            }
                        }
                }
            }
            if($valueArr[0] < $sugHgt[0])
                $sugHgt[0] = $valueArr[0];
            if($valueArr[1] > $sugHgt[1])
                $sugHgt[1] = $valueArr[1];
            $mapValues = FieldMap::getFieldLabel("height_json","",1);
            $finalRet['LHEIGHT'] = $mapValues[$sugHgt[0]];
            $finalRet['HHEIGHT'] = $mapValues[$sugHgt[1]];
            return $finalRet;
        }
        
        public function getSuggestionForDrinkOrSmoke($type,$valueArr=array()){
            if(!in_array('Y', $valueArr)){
                if(!in_array('N', $valueArr) && !in_array('O', $valueArr))
                    $toReturn = array('O','N');
                else if(!in_array('O', $valueArr))
                    $toReturn = array('O');
                else if(!in_array('N', $valueArr))
                    $toReturn = array('N');
            }
            else if(!in_array('O', $valueArr)){
                if(!in_array('N', $valueArr))
                    $toReturn = array('N');
            }
            else
                $toReturn = array();
            $mapValues = FieldMap::getFieldLabel($type,"",1);
            foreach($toReturn as $k=>$v){
                $finalReturn[$v] = $mapValues[$v];
            }
            return $finalReturn;
        }
}
?>
