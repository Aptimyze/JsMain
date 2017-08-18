<?php
/**
 * Userfilter class handles the filtered condition of a user against Multiple Users.
 * It checks whether he/she is passing filters of multiple users.
 * 
 * @package    jeevansathi
 * @subpackage apps
 * @author     Prinka Wadhwa
 */

class MultipleUserFilter
{
	private $viewerProfile;
	private $viewedProfileidArr;
	private $viewerParameters;
	private $viewedDppArr;
	private $viewedFilterParameters;

	/**
	  * @param - $viewerParameters - filter parameters of viewer's profiles - got from the function getFilterParameters() in logged-in profile class
	  * @param - $viewedFilterParameters - filter parameters of viewed user's profiles - got from getFilterParameters() in this class
	  * @param - $viewedDppArr - array of DPP values of viewer user's profiles 
	  * @param - $viewerProfile - viewer's profileid
	  * @param - $viewedProfileidArr - array of viewed user's profileids
	**/
	function __construct($viewerParameters,$viewedFilterParameters,$viewedDppArr,$viewerProfile,$viewedProfileidArr)
	{
		$this->viewerParameters=$viewerParameters;
		$this->viewedFilterParameters=$viewedFilterParameters;
		$this->viewedDppArr=$viewedDppArr;
		$this->viewerProfile=$viewerProfile;
		$this->viewedProfileidArr=$viewedProfileidArr;
	}

	/**
	  * This function checks the profiles whose filters the viewer profile satifies and returns the list of those ids
	  * @return $notFilteredProfiles - result array with key as profileid and value as 1.
	**/

	public function checkIfProfileMatchesDpp()
	{
		$fieldsArr = array("AGE","INCOME","MSTATUS","RELIGION","CASTE","MTONGUE","COUNTRY_RES","CITY_RES");

		foreach($this->viewedProfileidArr as $viewedId)
		{
			$filterNotPassed = 1;
			foreach($fieldsArr as $fieldName)
			{
				if(!$this->checkIfFilterPassed($viewedId,$fieldName))
				{
					$filterNotPassed = 0;
					break;
				}
			}

			if($filterNotPassed == 1)
			{
				$notFilteredProfiles[$viewedId]=1;
			}
		}

		return $notFilteredProfiles;
	}

	/**
	  * This function checks if a logged in user passes a single filter of the viewed profile (called from function checkIfProfileMatchesDpp() from this class.)
	  * @return 1 if filter is passed and 0 if filter not passed
	**/

	private function checkIfFilterPassed($viewedId,$fieldName)
	{
		if($fieldName == 'AGE')
		{
//			if($this->viewedFilterParameters[$viewedId][$fieldName] == 'N' || $this->viewedFilterParameters[$viewedId][$fieldName] == '' || ($this->viewedFilterParameters[$viewedId][$fieldName] == 'Y' && $this->viewerParameters['LAGE'] <= $this->viewedDppArr[$viewedId][$fieldName] && $this->viewerParameters['HAGE'] >= $this->viewedDppArr[$viewedId][$fieldName]))
			if($this->viewedFilterParameters[$viewedId][$fieldName] == 'N' || $this->viewedFilterParameters[$viewedId][$fieldName] == '' || ($this->viewedFilterParameters[$viewedId][$fieldName] == 'Y' && $this->viewerParameters['AGE'] <= $this->viewedDppArr[$viewedId]['HAGE'][0] && $this->viewerParameters['AGE'] >= $this->viewedDppArr[$viewedId]['LAGE'][0]))
			{
				return 1;
			}
		}
		elseif($fieldName == 'INCOME')
		{
			if($this->viewedFilterParameters[$viewedId][$fieldName] == 'N' || $this->viewedFilterParameters[$viewedId][$fieldName] == '')
				return 1;
			elseif($this->viewedFilterParameters[$viewedId][$fieldName] == 'Y')
			{
				$incomeStr = $this->getIncomeDppFilter(implode(",",$this->viewedDppArr[$viewedId][$fieldName]));
				if(in_array($this->viewerParameters[$fieldName],$incomeStr))
					return 1;
			}
		}
                
                elseif($fieldName == "CITY_RES"){
                        if($this->viewedDppArr[$viewedId]['STATE'][0] != "" && $this->viewerParameters['COUNTRY_RES']=='51'){
                            $citiesOfState = CommonFunction::getCitiesForStates($this->viewedDppArr[$viewedId]['STATE']);
                        }
                        $countryOtherThanIndia = $this->checkIfCountryOtherThanIndia($this->viewedDppArr[$viewedId]['COUNTRY_RES']);
                        if($this->viewedFilterParameters[$viewedId][$fieldName] == 'N' || $this->viewedFilterParameters[$viewedId][$fieldName] == '' || (!is_array($this->viewedDppArr[$viewedId][$fieldName]) && !$citiesOfState) || ($this->viewedFilterParameters[$viewedId][$fieldName] == 'Y'  && is_array($this->viewedDppArr[$viewedId][$fieldName])  && (in_array($this->viewerParameters[$fieldName],$this->viewedDppArr[$viewedId][$fieldName]) || ($citiesOfState && in_array($this->viewerParameters[$fieldName],$citiesOfState)))) || ($this->viewedFilterParameters[$viewedId][$fieldName] == 'Y' && $this->viewedDppArr[$viewedId][$fieldName][0] == '' && !$citiesOfState) || (($countryOtherThanIndia || $this->viewedFilterParameters[$viewedId][$COUNTRY_RES] != 'Y') && $this->viewerParameters['COUNTRY_RES']!=51))
                          return 1;
                }
		else
		{
//			 if($this->viewedFilterParameters[$viewedId][$fieldName] == 'N' || $this->viewedFilterParameters[$viewedId][$fieldName] == '' || $this->viewedDppArr[$viewedId][$fieldName] == '' || ($this->viewedFilterParameters[$viewedId][$fieldName] == 'Y'  && in_array($this->viewerParameters[$fieldName],explode(",",str_replace("'","",$this->viewedDppArr[$viewedId][$fieldName])))))
			if($this->viewedFilterParameters[$viewedId][$fieldName] == 'N' || $this->viewedFilterParameters[$viewedId][$fieldName] == '' || !is_array($this->viewedDppArr[$viewedId][$fieldName]) || ($this->viewedFilterParameters[$viewedId][$fieldName] == 'Y'  && is_array($this->viewedDppArr[$viewedId][$fieldName])  && in_array($this->viewerParameters[$fieldName],$this->viewedDppArr[$viewedId][$fieldName])) || ($this->viewedFilterParameters[$viewedId][$fieldName] == 'Y' && $this->viewedDppArr[$viewedId][$fieldName][0] == ''))
			{
				return 1;
			}
		}
		return 0;
	}

	/**
	  * This function returns details from newjs.FILTERS for an array of profileids passed.
	  * @param - $profileIdArr - array of profileids whose filter values need to be returned
	  * @return - $filter_parameters - array of filters for the profileids passed
	**/

	public static function getFilterParameters($profileIdArr,$dbname="")
	{
		$filterObj=new ProfileFilter($dbname);
		$results = $filterObj->fetchFilterDetailsForMultipleProfiles($profileIdArr);
		if(is_array($results))
		{
			foreach($profileIdArr as $profileid)
			{
				if(is_array($results[$profileid]) && in_array("Y",$results[$profileid]))
				{
					$filter_parameters[$profileid]=array("AGE"=>$results[$profileid]["AGE"],
							"MSTATUS"=>$results[$profileid]["MSTATUS"],
							"MTONGUE"=>$results[$profileid]["MTONGUE"],
							"COUNTRY_RES"=>$results[$profileid]["COUNTRY_RES"],
							"CITY_RES"=>$results[$profileid]["CITY_RES"],
							"RELIGION"=>$results[$profileid]["RELIGION"],
							"INCOME"=>$results[$profileid]["INCOME"],
							"CASTE"=>$results[$profileid]["CASTE"]);
				}
			}
			return $filter_parameters;
		}
		return NULL;
	}
	
	public function getIncomeDppFilter($incomeStr)
	{
		$income_data=FieldMap::getFieldLabel("income_data","","1");
		$i=0;$j=0;
		$incomeArr=explode(",",$incomeStr);
		
		foreach($incomeArr as $key=>$val){
			foreach($income_data as $k=>$v){
				if($val ==$v["VALUE"])
				{
					if($v["TYPE"]=="RUPEES")
					{
						if($i==0){
							$minRupee=$v["MIN_VALUE"];
							$i++;
						}
						if($income_data[$val]["MIN_VALUE"]<$minRupee)
                                                        $minRupee=$v["MIN_VALUE"];
					}
					else
					{
						if($j==0){
							$minDollar=$v["MIN_VALUE"];
							$j++;
						}
						if($income_data[$val]["MIN_VALUE"]<$minDollar)
							$minDollar=$v["MIN_VALUE"];
					}
				}
			}
		}
                        if($minRupee && $minDollar){
                                $rsArray=array('minIR'=>$minRupee,'maxIR'=>19);
                                $doArray=array('minID'=>$minDollar,'maxID'=>19);
				$incomeMapping= new IncomeMapping($rsArray,$doArray);
                        }
			else if($minRupee)
			{
				$rsArray=array('minIR'=>$minRupee,'maxIR'=>19);
				$incomeMapping= new IncomeMapping($rsArray);
			}
			else
			{
				$doArray=array('minID'=>$minDollar,'maxID'=>19);
				$incomeMapping= new IncomeMapping("",$doArray);	
			}
			
			$incomeArr=$incomeMapping->incomeMapping();
			$incomeString = $incomeArr[istr] .",". $incomeStr;
			$incomeArr = explode(",",str_replace("'","",$incomeString));
			return array_unique($incomeArr);
		
	}
        
        private function checkIfCountryOtherThanIndia($countryArr) {
            foreach ($countryArr as $key=>$val){
                if($val != 51)
                    return true;
            }
            return false;
        }
}
?>
