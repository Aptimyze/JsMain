<?php
class cityAgeRegistrationMis
{
	//This function gets the MIS Data based on the grouping selected
	public function getRegistrationMisData($params)
	{
		if($params['report_type'] == 'Age_Gender')
		{
			$params['report_type'] = 'GENDER,AGE';
		}
		//create object of JPROFILE
		$jprofileObj = JPROFILE::getInstance("newjs_slave");
		
		$dayMonth = "";
		if($params['range_format']=="Q")
		{
			$year = $params['quarter_year'];
		}
		elseif($params['range_format']=="M")
		{
			$year = $params['month_year'];
		}
		
		//If Quarterly or Monthly Grouping is Required
		if($params['range_format']=="Q" || $params['range_format']=="M")
		{
			$nextYear = $year+1;
			$fromDate = $year."-04-01";
			$toDate = $nextYear."-03-31";
		}
          //If day wise data is required
		else
		{
			$dayMonth = $params['day_month'];
			$fromDate = $params['day_year']."-".$dayMonth."-01";
			$toDate = $params['day_year']."-".$dayMonth."-31";
		}

		$resultArr = $jprofileObj->getRegistrationMisGroupedData($fromDate,$toDate,$dayMonth,$params['report_type']);
		if($params['report_type'] == 'GENDER,AGE')
		{
			$params['report_type'] = 'Age_Gender';
		}
		$finalArr = $this->organiseRegistrationData($params['range_format'],$params['report_type'],$params['report_format'],$resultArr);
		return ($finalArr);
	}

	//This function organises the data in the required format based on the report_type and range_format
	public function organiseRegistrationData($range_format,$report_type,$report_format,$dataArr)
	{
		$alteredArr = array();
		$totalCountArr = array();
		$sortedArr = array();
		$finalArr = array();
		$top50CityArr = array();
		$percentArr = array();

		//Looping on the data fetched from database to convert into required format
		foreach($dataArr as $key=>$value)
		{
			//To convert Jan, feb, March to month 13,14,15 as they give data of the next year
			if($value['MONTH']<=3)
			{
				$value['MONTH'] = $value['MONTH']+12;
			}

			if($range_format == "M" || $range_format == 'D')
			{
				if($range_format == "M")
				{
					$keyType = "MONTH";
				}
				elseif($range_format == "D")
				{
					$keyType = "DAY";
				}
			}
			// for report_type CITY_RES and MTONGUE
			if($report_type !="Age_Gender")
			{
				if($value[$report_type]!= " " || $value[$report_type]!="-1")
				{
					if($range_format == "M" || $range_format == "D")
					{
						$alteredArr[$value[$keyType]][$value[$report_type]] = $value['COUNT'];
					}
					else
					{	//clubbing data for quaters together
						switch($value['MONTH'])
						{
							case '4':
							case '5':
							case '6':
									$alteredArr['Q1'][$value[$report_type]] += $value['COUNT'];
									break;
							case '7':
							case '8':
							case '9':
									$alteredArr['Q2'][$value[$report_type]] += $value['COUNT'];
									break;
							case '10':
							case '11':
							case '12':
									$alteredArr['Q3'][$value[$report_type]] += $value['COUNT'];
									break;
							case '13':
							case '14':
							case '15':
									$alteredArr['Q4'][$value[$report_type]] += $value['COUNT'];
									break;

						}
					}
				}
			}
			
			// for range_type = Age_Gender
			else
			{
				if($range_format == "M" || $range_format == "D")
				{
					foreach(RegistrationMisEnums::$ageBucket as $k=>$v)
					{
						if($value['GENDER'] == $v['GENDER'] && $value['AGE']>=$v['LOW'] && $value['AGE']<=$v['HIGH'])
						{
							$alteredArr[$value[$keyType]][$k] += $value['COUNT'];
						}
					}
				}
				else
				{
					foreach(RegistrationMisEnums::$ageBucket as $k=>$v)
					{
						if($value['GENDER'] == $v['GENDER'] && $value['AGE']>=$v['LOW'] && $value['AGE']<=$v['HIGH'])
						{
							switch($value['MONTH'])
							{
								case '4':
								case '5':
								case '6':
										$alteredArr['Q1'][$k] += $value['COUNT'];
										break;
								case '7':
								case '8':
								case '9':
										$alteredArr['Q2'][$k] += $value['COUNT'];
										break;
								case '10':
								case '11':
								case '12':
										$alteredArr['Q3'][$k] += $value['COUNT'];
										break;
								case '13':
								case '14':
								case '15':
										$alteredArr['Q4'][$k] += $value['COUNT'];
										break;
							}
						}
					}
				}
			}
		}
		foreach($alteredArr as $key => $value)
		{
			foreach($value as $k1 => $v1)
			{
				if($k1!="")
				{
					$totalCountArr[$k1] += $v1; 	
				}
			}	
		}
		$totalCount = array_sum($totalCountArr);
		foreach($totalCountArr as $key => $val)
		{
			$percentArr[$key] = round(($val)/($totalCount)*100,2);
		}
		$alteredArr['totalCount'] = $totalCountArr;
		$alteredArr['percent'] = $percentArr;
		if($report_type == "CITY_RES")
		{
			$sortedArr = $totalCountArr;
			arsort($sortedArr);
			$top50CityArr = array_slice($sortedArr,0,50,true);
			$citiesArr = array_keys($top50CityArr);
			foreach($alteredArr as $key => $value)
			{
				foreach($top50CityArr as $k1=>$v1)
				{
						if(array_key_exists($k1, $value))
						{
							$finalArr[$key][$k1]=$value[$k1];
						}
						else
							$finalArr[$key][$k1]=0;

				}
			}
			$cityArr = FieldMap::getFieldLabel('city','',1);
			$stateIndiaArr = FieldMap::getFieldLabel('state_india','',1);
			foreach($citiesArr as $key => $val)
			{
				if($val!='0')
				{
					if($value = $stateIndiaArr[$val])
						$finalArr['loopOn'][$val] = $value;
					elseif($value = $cityArr[$val])
						$finalArr['loopOn'][$val] = $value;
				}
				else
				{
					$finalArr['loopOn']['0'] = 'Others';
				}
			}
		}
		
		elseif($report_type == 'MTONGUE')
		{	
			$finalArr = $alteredArr;
			$communityArr = FieldMap::getFieldLabel('community','',1);
			$finalArr['loopOn'] = $communityArr;
		}

		else
		{
			$ageGenderBucket = RegistrationMisEnums::$ageGenderBucket;
			$finalArr = $alteredArr;
			$finalArr['loopOn'] = $ageGenderBucket;
		}

		if($range_format == 'Q')
		{
			$finalArr['iterate'] = RegistrationMisEnums::$quaterIterate;
		}
		elseif($range_format == 'M')
		{
			$finalArr['iterate'] = RegistrationMisEnums::$monthIterate;
		}
		else
		{
			$finalArr['iterate'] = RegistrationMisEnums::$dayIterate;
		}
		
		$finalArr["totalCountValue"] = $totalCount;
		return($finalArr);
	}

	public function createCSVFromatData($params,$groupedData,$displayDate,$displayName)
	{
		$csvData = 'Registration MIS'."\n";
		$csvData .= 'Result Screen -'.$displayName."\n";
		if($params['range_format']=="M" || $params['range_format']=="Q")
		{
			$csvData .= "For the Year of " .$displayDate."\n";
			$csvData .="Month".",";
			if($params['range_format']=="M")
			{
				$iterationArr = RegistrationMisEnums::$monthNames;
			}
			elseif($params['range_format']=="Q")
			{
				$iterationArr = RegistrationMisEnums::$quarterNames;
			}
		}
		if($params['range_format']=="M" || $params['range_format']=="Q")
		{
			foreach($iterationArr as $key=>$value)
			{
				$csvData .=$value.",";
			}
		}
		else
		{
			$csvData .= "For the Month of " .$displayDate."\n";
			$csvData .= "Days".",";
			for($i=1;$i<=31;$i++)
			{
				$csvData .=$i.",";
			}
		}

		$csvData .= "Total,Percentage"."\n";

		foreach($groupedData['loopOn'] as $key=>$value)
		{
			$csvData .="\n".$value.",";
			foreach ($groupedData['iterate'] as $key1=>$val1)
			{
				if($groupedData[$val1][$key]!="")
				{
					$csvData .= $groupedData[$val1][$key].",";
				}
				else
				{
					$csvData .= "0".",";
				}
			}
			$csvData .="\n";
		}
		return($csvData);
	}
}
