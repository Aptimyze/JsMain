<?php
/**
  * class CrawlerDuplicateDetector
  * This class is used to find duplicate profiles using crawler logic and set them in RawDuplicate object.
**/
class CrawlerDuplicateDetector extends DuplicateDetector  
{
	private  $crawlerDuplicateDetector;

	private $rawCrawlerDuplicate;
	private $TYPE="CRAWLER";

	/**
	  * This sets the profileid whose duplicates are to be found and the reason in the RawDuplicate object
	**/
	public function __construct(DuplicateDetector $duplicateDetector)
	{
		$this->crawlerDuplicateDetector=$duplicateDetector;
		$this->rawCrawlerDuplicate=new RawDuplicate();
		$this->rawCrawlerDuplicate->setProfileid1(LoggedInProfile::getInstance()->getPROFILEID()); //profile whose duplicates are to be found
		$this->rawCrawlerDuplicate->setReason($this->TYPE);
	}


	/**
	  * This function gets the duplicate profiles and sets them in the rawDuplicate object.
	**/
	public function checkDuplicate() 
	{
		$duplicateObj=$this->crawlerDuplicateDetector->checkDuplicate();

/*
		//Get details of profile to be matched
		$jprofileFields = CrawlerConfig::$JprofileFields;

		//array with fieldName and fieldValues as key=>value pair
		$this->profileParameters = LoggedInProfile::getInstance()->getDetail("","",$jprofileFields);
print_r($this->profileParameters); 
*/

		//Get details of profile to be matched
		$jprofileFields = explode(",",CrawlerConfig::$JprofileFields);

		//array with fieldName and fieldValues as key=>value pair
		foreach($jprofileFields as $paramName)
		{
			$functionName = "get".$paramName;
			$this->profileParameters["$paramName"] = LoggedInProfile::getInstance()->$functionName();
		}

		//profiles to be excluded while searching for duplicates
		$this->excludeArray['PROFILEID']=LoggedInProfile::getInstance()->getPROFILEID();

		$this->getDuplicateProfiles();
		if(is_array($this->duplicateProfiles))
		{
			foreach($this->duplicateProfiles as $dup)
			{
				if(is_array($dup))
				foreach($dup as $duplicateId => $reason)
				{
					//setting duplicate profiles data in rawDuplicate object
					$objName = "rawCrawlerDuplicate".$duplicateId;
					$this->$objName = clone($this->rawCrawlerDuplicate);
					$this->$objName->setComments("$reason");
					$this->$objName->setProfileid2($duplicateId); //profile found as a duplicate
					if(strstr(CrawlerDuplicateKeyword::DUPLICATE,"'".$reason."'") != '')
						$this->$objName->setIsDuplicate(IS_DUPLICATE::YES);
					else
						$this->$objName->setIsDuplicate(IS_DUPLICATE::PROBABLE);
					$duplicateObj->addRawDuplicateObj($this->$objName);
				}
			}
		}
//print_r($duplicateObj);
//print_r($this->duplicateProfiles);
//die;
		return $duplicateObj;
	} // end of member function checkDuplicate


	/**
	  * This function calls all the functions that fetch duplicate profiles and sets them in an array
	**/
	public function getDuplicateProfiles()
	{
		$this->multipleProfileObj = new ProfileArray();

		$noOfMonths = CrawlerConfig::$greaterThanConditions["LAST_LOGIN_DT"];
		$dateValue = date("Y-m-d", JSstrToTime("- $noOfMonths month",JSstrToTime(date("Y-m-d"))));
		$this->greaterThanCond['LAST_LOGIN_DT'] = $dateValue;

                foreach(CrawlerConfig::$crawlerChecks as $crawlerCheck)
                {
			$functionName = 'getDuplicatesBasedOn'.$crawlerCheck;
			$this->$functionName($crawlerCheck);
			if(is_array($this->duplicateProfiles[$crawlerCheck]))
			{
				//excluding already found duplicates from further queries
				$this->excludeArray['PROFILEID'].=",".implode(',', array_keys($this->duplicateProfiles[$crawlerCheck]));
			}
		}
	}

	/**
	  * This function get duplicate profiles based on the birth details and other jprofile values (mentioned in CrawlerConfig)
	  * @param - $crawlerCheck - name of the logic through which duplicates are to be found.
	**/
	private function getDuplicatesBasedOnBirthDetails($crawlerCheck)
	{
		//query only if birth details are present
		if($this->profileParameters['BTIME']!='' && $this->profileParameters['CITY_BIRTH'])
		{
			//get where conditions after considering the (plus/minus) range of values
			$whereConditions = $this->getParametersToSearchInJprofile(CrawlerConfig::$birthDetailMatches);

			$duplicatesProfiles = $this->multipleProfileObj->getResultsBasedOnJprofileFields($whereConditions,$this->excludeArray,$this->greaterThanCond,"PROFILEID","JPROFILE_FOR_DUPLICATION","newjs_slave");
			if(is_array($duplicatesProfiles))
			{
				foreach($duplicatesProfiles as $duplicates)
				{
					$this->duplicateProfiles[$crawlerCheck][$duplicates->getPROFILEID()]=CrawlerResult::DUPLICATE_BIRTH_DETAILS;
				}
			}
		}
	}

	/**
	  * This function get duplicate profiles based on the date of birth and other jprofile values (mentioned in CrawlerConfig)
	  * @param - $crawlerCheck - name of the logic through which duplicates are to be found.
	**/
	public function getDuplicatesBasedOnDtOfBirth($crawlerCheck)
	{
		//get where conditions after considering the (plus/minus) range of values
		$whereConditions = $this->getParametersToSearchInJprofile(CrawlerConfig::$dtOfBirthMatches);
		$duplicatesProfiles = $this->multipleProfileObj->getResultsBasedOnJprofileFields($whereConditions,$this->excludeArray,$this->greaterThanCond,"PROFILEID","JPROFILE_FOR_DUPLICATION","newjs_slave");
		if(is_array($duplicatesProfiles))
		{
			foreach($duplicatesProfiles as $duplicates)
			{
				$this->duplicateProfiles[$crawlerCheck][$duplicates->getPROFILEID()]=CrawlerResult::DUPLICATE_DTOFBIRTH;
			}
		}
	}

	/**
	  * This function get duplicate profiles based on age and other jprofile values (mentioned in CrawlerConfig)
	  * @param - $crawlerCheck - name of the logic through which duplicates are to be found.
	**/
	private function getDuplicatesBasedOnAge($crawlerCheck)
	{
		$whereConditions = $this->getParametersToSearchInJprofile(CrawlerConfig::$ageMatches);
		$duplicatesProfiles = $this->multipleProfileObj->getResultsBasedOnJprofileFields($whereConditions,$this->excludeArray,$this->greaterThanCond,"PROFILEID","JPROFILE_FOR_DUPLICATION","newjs_slave");
		if(is_array($duplicatesProfiles))
		{
			$duplicateIds = '';
			foreach($duplicatesProfiles as $duplicates)
			{
				if($duplicateIds == '')
					$duplicateIds.=$duplicates->getPROFILEID();
				else
					$duplicateIds.=",".$duplicates->getPROFILEID();
			}
		}
		if(trim($duplicateIds != ''))
			$this->duplicateProfiles[$crawlerCheck] = $this->getDuplicatesAfterSecondaryParamsMatching($duplicateIds,LoggedInProfile::getInstance()->getPROFILEID());
	}

	/**
	  * This function is used to match secondary parameters(mentioned in CrawlerConfig) of the profiles found duplicate through age logic with a reference profileid
	  * @param - $duplicateProfiles - profileids returned by the function getDuplicatesBasedOnAge (age logic)
	  * @param - $referenceProfileId - the profileid whose duplicates are to be found
	  * @return - $result - array containing the profileids and their secondary parameter matching result.
	**/
	public function getDuplicatesAfterSecondaryParamsMatching($duplicateProfiles,$referenceProfileId)
	{
		$profileids["PROFILEID"] = $referenceProfileId.",".$duplicateProfiles;

		foreach(CrawlerConfig::$secondaryParams as $table=>$columns)
		{
			$secondaryData = $this->multipleProfileObj->getResultsBasedOnJprofileFields($profileids,'','',$columns,$table,"newjs_master");
			if(is_array($secondaryData))
			{
				foreach($secondaryData as $paramValues)
				{
					$fieldVal = explode(",",$columns);
					foreach($fieldVal as $fieldName)
					{
						$caseInsensitive = in_array($fieldName,CrawlerConfig::$caseInsensitiveFields);
						$excludeSpecialChar = in_array($fieldName,CrawlerConfig::$excludeSpecialCharFields);
						$getValuesBeforeAtTheRate = in_array($fieldName,CrawlerConfig::$getValuesBeforeAtTheRate);
						$fieldName = str_replace(" ","",$fieldName);
						$function = "get".$fieldName;
						$fieldValue = $paramValues->$function();
						$modifiedValue = $this->changeToComparisonForm($fieldValue,$excludeSpecialChar,$caseInsensitive,$getValuesBeforeAtTheRate);
						if($paramValues->getPROFILEID() != $referenceProfileId)
							$secondary[$paramValues->getPROFILEID()][$fieldName]=$modifiedValue;
						else
							$reference[$paramValues->getPROFILEID()][$fieldName]=$modifiedValue;
					}
				}
			}
		}
		if(is_array($secondary))
		{
			foreach($secondary as $id=>$profile2)
			{
				$matchingResult = $this->matchSecondaryParams($reference,$profile2);
				if($matchingResult != CrawlerResult::NOT_DUPLICATE)
				{
					$result[$id]=$matchingResult;
				}
			}
		}
		return $result;

	}

	/**
	  * This function is used to match secondary parameters(mentioned in CrawlerConfig) of the profiles found duplicate through age logic with a reference profileid
	  * @param - $profile1 - secondary parameters of the profile which was found through the age logic
	  * @param - $profile2 - secondary parameters of the profileid whose duplicates are to be found
	  * @return - $result - (P1,P2,etc) - P denotes Probable, and the number following it denotes the no of secondary parameter matches.
	**/
	public function matchSecondaryParams($profile1,$profile2)
	{
/*
echo "\n\n**********\n\n";
print_r($profile1);
echo "\n\n**********\n\n";
print_r($profile2);
echo "\n\n**********\n\n";
*/
		$subcasteMatch = 0;
		$occupationMatch = 0;
		$score = 0;
		$matches="__PARAMETERS=";

		foreach($profile1 as $pid => $secArr)
		{
			foreach($secArr as $parameter=>$value)
			{
				if($value != '' && ($parameter == 'EMAIL' || $parameter == 'MESSENGER_ID'))
				{
					if($value == $profile2['EMAIL'] || $value == $profile2['MESSENGER_ID'])
					{
						$score++;
						$matches.=$parameter.",";
					}
				}
				elseif($value != '' && $value == $profile2[$parameter])
				{
					if(in_array($parameter,CrawlerConfig::$directComparison))
					{
						$score++;
						$matches.=$parameter.",";
					}
					elseif($parameter == 'IPADD')
					{
						if(!in_array($value,CrawlerConfig::$ipsIgnore))
						{
							$score++;
							$matches.=$parameter.",";
						}
					}
/*
					elseif($parameter == 'PASSWORD')
					{
						if(!in_array($value,CrawlerConfig::$passwordsIgnore))
						{
							$score++;
							$matches.=$parameter.",";
						}
					}
*/
					if($parameter == 'SUBCASTE')
					{
						$subcasteMatch = 1;
						if($profile1[$pid]['OCCUPATION'] != '' && ($profile1[$pid]['OCCUPATION'] == $profile2['OCCUPATION']))
						{
							if(abs($profile1[$pid]['HEIGHT'] - $profile2['HEIGHT']) <= 1)
							{
								$occupationMatch = 1;
							}
						}
					}
				}
			}
		}
//echo "\n^^^^^^^^^^^^^^^ $score $matches^^^^^^^^^^^^\n";
		if($score == 1 && $subcasteMatch == 1)
		{
			if($occupationMatch == 1)
			{
				$matches.=$parameter.",";
				return CrawlerResult::PROBABLE.$score.$matches;
			}
			else
				return CrawlerResult::NOT_DUPLICATE;
		}

		if($score >=CrawlerConfig::$minimumMatchesRequiredToMarkProbable)
			return CrawlerResult::PROBABLE.$score.$matches;
		else
			return CrawlerResult::NOT_DUPLICATE;
	}

	/**
	  * This function is used to remove spaces, special characters and change the case of letters to uppercase of the passed value
	  * @param - $value - value for which any of the above change is required
	  * @param - $excludeSpecialChars - if this value is '1', then special characters and spaces are removed
	  * @param - $excludeSpecialChars - if this value is '1', then the value is changed to upper case
	  * @return - $value - the passed value after the changes.
	**/
	public function changeToComparisonForm($value,$excludeSpecialChars='',$caseInsensitive='',$getValuesBeforeAtTheRate)
	{
		if($getValuesBeforeAtTheRate == 1)
		{
			$position = strpos($value,'@');
			if($position)
				$value = substr($value,0,$position);
		}
		if($excludeSpecialChars == 1)
			$value = preg_replace("/[^A-Za-z0-9]/","",$value); //removing all special characters and spaces
		if($caseInsensitive == 1)
			$value = strtoupper($value);
		return $value;
	}

	/**
	  * While finding duplicates of a profile, a search needs to be done on various parameters of te profile whose duplicates are to be found.
	  * This function returns the parameters and the values to be searched.
	  * @param - $parameterNames - names of parameters on which the query needs to be executed.
	  * @return - $whereConditions - an array with the parameters as the key and parameter-values as value.
	**/
	public function getParametersToSearchInJprofile($parameterNames)
	{
		foreach($parameterNames as $key=>$parameters)
		{
			if(is_array($parameters))
			{
				if($key == 0)
				//This will match the exact paramters
				{
					foreach($parameters as $param)
					{
						if($this->profileParameters[$param] != '')
						{
							if($param=='CITY_BIRTH')
								$whereConditions["$param"]="'".addslashes(stripslashes($this->profileParameters[$param]))."'";
							elseif($param=='BTIME' && $this->profileParameters[$param]==':')
								;
							else
								$whereConditions["$param"]="'".$this->profileParameters[$param]."'";
						}
					}
				}
				else
				//This will match the plus/minus of a param value
				{
					foreach($parameters as $param)
					{
						$functionName = strtolower($param)."PlusMinus";
						$value = $this->$functionName($this->profileParameters[$param],$key);
						if($value != '')
						{
							$whereConditions["$param"] = $value;
						}
					}
				}
			}
		}
		return $whereConditions;
	}

	/**
	  * This function returns a string containing all values ranging between (age-plusminusvalue) and (age+plusminusvalue).
	  * @param - $ageValue - age of the profile whose duplicates are to be found.
	  * @param - $plusMinusValue - an integer which would be used in the string to be returned
	  * @return - a string containing all values ranging between ($age-$plusminusvalue) and ($age+$plusminusvalue).
	**/
	public function agePlusMinus($ageValue,$plusMinusValue)
	{
		$ageValues = "'$ageValue'";
		if($plusMinusValue > 0)
		{
			for($i = $plusMinusValue; $i>0; $i--)
			{
				$ageValues.=",'".($ageValue-$i)."','".($ageValue+$i)."'";
			}
		}

		return $ageValues;
	}

	/**
	  * This function returns a string containing all values ranging between (height-plusminusvalue) and (height+plusminusvalue).
	  * @param - $heightValue - height of the profile whose duplicates are to be found.
	  * @param - $plusMinusValue - an integer which would be used in the string to be returned
	  * @return - a string containing all values ranging between ($heightValue-$plusminusvalue) and ($heightValue+$plusminusvalue).
	**/
	public function heightPlusMinus($heightValue,$plusMinusValue)
	{
		if($plusMinusValue > 0)
		{
			$this->MAX_HEIGHT = FieldMap::getFieldLabel('max_height', '0');
			$this->MIN_HEIGHT = FieldMap::getFieldLabel('min_height', '0');
			$heightValues[] = $heightValue;
			for($i = $plusMinusValue; $i>0; $i--)
			{
				if(($heightValue-$i) >= $this->MIN_HEIGHT && ($heightValue-$i) <= $this->MAX_HEIGHT)
					$heightValues[]=($heightValue-$i);
				if(($heightValue+$i) >= $this->MIN_HEIGHT && ($heightValue+$i) <= $this->MAX_HEIGHT)
					$heightValues[]=($heightValue+$i);
			}
			$heightString = "'".implode("','",$heightValues)."'";
			return $heightString;
		}
		else
			return $heightValue;
	}

	/**
	  * This function returns a string containing all values ranging between (income-plusminusvalue) and (income+plusminusvalue).
	  * @param - $incomeValue - income of the profile whose duplicates are to be found.
	  * @param - $plusMinusValue - an integer which would be used in the string to be returned
	  * @return - a string containing all values ranging between ($incomeValue-$plusminusvalue) and ($incomeValue+$plusminusvalue).
	**/
	public function incomePlusMinus($incomeValue,$plusMinusValue)
	{
		if($plusMinusValue > 0)
		{
			$incomeValues[] = $incomeValue;
			$this->INCOME_DUPLICATION_CHECK = FieldMap::getFieldLabel('income_duplication_check', '', 1);
			$sortBy = array_search($incomeValue,$this->INCOME_DUPLICATION_CHECK['RUPEES']); //sortby value corresp to the above income
			if($sortBy == '' && $sortBy !=0)
			{
				$sortBy = array_search($incomeValue,$this->INCOME_DUPLICATION_CHECK['DOLLARS']); //sortby value corresp to the above income
				$currencyType = 'DOLLARS';
			}
			else
			{
				$currencyType = 'RUPEES';
			}

			for($i = $plusMinusValue; $i>0; $i--)
			{
				if($this->INCOME_DUPLICATION_CHECK[$currencyType][($sortBy-$i)])
					$incomeValues[] = $this->INCOME_DUPLICATION_CHECK[$currencyType][($sortBy-$i)];
				if($this->INCOME_DUPLICATION_CHECK[$currencyType][($sortBy+$i)])
					$incomeValues[] = $this->INCOME_DUPLICATION_CHECK[$currencyType][($sortBy+$i)];
			}
			$incomeString = "'".implode("','",$incomeValues)."'";
			return $incomeString;
		}
		else
			return "'".$incomeValue."'";
	}

} // end of CrawlerDuplicateDetector

?>
