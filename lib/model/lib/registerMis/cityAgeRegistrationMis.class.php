<?php
class cityAgeRegistrationMis
{
	public function getRegistrationMisData($params)
	{
		if($params['report_type'] == 'Age_Gender')
		{
			$params['report_type'] = 'GENDER,AGE';
		}
		$jprofileObj = JPROFILE::getInstance();
		if($params['range_format']=="Q")
		{
			$nextYear = $params['quarter_year']+1;
			$fromDate = $params['quarter_year']."-04-01";
			$toDate = $nextYear."-03-31";
			$resultArr = $jprofileObj->getRegistrationMisGroupedData($fromDate,$toDate,'',$params['report_type']);
			//print_r($resultArr);die;
			$finalArr = $this->organiseRegistrationData($params['range_format'],$params['report_type'],$params['report_format'],$resultArr);
			//print_r($finalArr);die;
			return ($finalArr);
		}
		else if($params['range_format']=="M")
		{
			$nextYear = $params['month_year']+1;
			$fromDate = $params['month_year']."-04-01";
			$toDate = $nextYear."-03-31";
			$resultArr = $jprofileObj->getRegistrationMisGroupedData($fromDate,$toDate,'',$params['report_type']);
			//print_r($resultArr);die;
			$finalArr = $this->organiseRegistrationData($params['range_format'],$params['report_type'],$params['report_format'],$resultArr);
			return($finalArr);
		}
		else
		{
			$fromDate = $params['day_year']."-".$params['day_month']."-01";
			$toDate = $params['day_year']."-".$params['day_month']."-31";
			$resultArr = $jprofileObj->getRegistrationMisGroupedData($fromDate,$toDate,$params['day_month'],$params['report_type']);
			//print_r($resultArr);die;
			$finalArr = $this->organiseRegistrationData($params['range_format'],$params['report_type'],$params['report_format'],$resultArr);
			return($finalArr);
		}
	}

	public function organiseRegistrationData($range_format,$report_type,$report_format,$dataArr)
	{
		$alteredArr = array();
		$totalCountArr = array();
		$sortedArr = array();
		$finalArr = array();
		$top50cityArr = array();

		foreach($dataArr as $key=>$value)
		{
			if($value['MONTH']<=3)
			{
				$value['MONTH'] = $value['MONTH']+12;
			}
			if($report_type !="GENDER,AGE")
			{
				if($value[$report_type]!= " " || $value[$report_type]!="-1")
				{
					if($range_format == "M")
					{
						$alteredArr[$value['MONTH']][$value[$report_type]] = $value['COUNT'];
					}
					else if($range_format == "D")
					{
						$alteredArr[$value['DAY']][$value[$report_type]] = $value['COUNT'];
					}
					else
					{
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
			//change this else part to make 2-D array of AGE_GENDER as well. As per the buckets and den apply the total count array accordingly
			else
			{
				if($range_format == "M")
				{
					foreach(RegistrationMisEnums::$ageBucket as $k=>$v)
					{
						if($value['GENDER'] == $v['GENDER'] && $value['AGE']>=$v['LOW'] && $value['AGE']<=$v['HIGH'])
						{
							$alteredArr[$value['MONTH']][$k] += $value['COUNT'];
						}
					}
				}
				else if($range_format == 'D')
				{
					foreach(RegistrationMisEnums::$ageBucket as $k=>$v)
					{
						if($value['GENDER'] == $v['GENDER'] && $value['AGE']>=$v['LOW'] && $value['AGE']<=$v['HIGH'])
						{
							$alteredArr[$value['DAY']][$k] += $value['COUNT'];
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
		// print_r($alteredArr);die;
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
		//print_r($totalCountArr);die;
		$alteredArr['totalCount'] = $totalCountArr;
		//print_r($alteredArr);die;
		

		if($report_type == "CITY_RES")
		{//print_r($totalCountArr);die;
			$sortedArr = $totalCountArr;
			rsort($sortedArr);
			// print_r($sortedArr);die;
			foreach($sortedArr as $key=>$value)
			{
				if($key<'50')
				{
					foreach($totalCountArr as $k => $v)
					{
						if($value == $v)
						{
							$top50CityArr[$k]=$v;
						}
					}
				}
				else
				{
					break;
				}
			}
			
			foreach($alteredArr as $key => $value)
			{
				foreach($value as $k1 => $v1)
				{
					foreach($top50CityArr as $k2 => $v2)
					{
						if($k2 == $k1)
						{
							$finalArr[$key][$k2]=$v1;
						}
					}
				}
			}
			$citiesArr = array_keys($top50CityArr);
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
			
			//print_r($finalArr);die;
		}
		//print_r($finalArr);die;
		else if($report_type == 'MTONGUE')
		{	//print_r($alteredArr);die;
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
		else if($range_format == 'M')
		{
			$finalArr['iterate'] = RegistrationMisEnums::$monthIterate;
		}
		else
		{
			$finalArr['iterate'] = RegistrationMisEnums::$dayIterate;
		}

		return($finalArr);
	}

	public function createCSVFromatData($params,$groupedData,$displayDate)
	{
		$csvData = 'Registration MIS'."\n";
		if($params['range_format']=="M")
		{
			$csvData .= "For the Year of " .$displayDate."\n";
			$csvData .="Month".",";
			foreach(RegistrationMisEnums::$monthNames as $key=>$value)
			{
				$csvData .=$value.",";
			}
			$csvData .= "Total"."\n";
		}
		else if($params['range_format']=="Q")
		{
			$csvData .= "For the Year of " .$displayDate."\n";
			$csvData .="Month".",";
			foreach(RegistrationMisEnums::$quarterNames as $key=>$value)
			{
				$csvData .=$value.",";
			}
			$csvData .= "Total"."\n";
			//echo($csvData);die;
		}
		else
		{
			$csvData .= "For the Month of " .$displayDate."\n";
			$csvData .= "Days".",";
			for($i=1;$i<=31;$i++)
			{
				$csvData .=$i.",";
			}
			$csvData .= "Total"."\n";
			//echo($csvData);die;
		}

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