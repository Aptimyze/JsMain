<?php
/*
 * @brief This class is used to set search paramters based on user input from Quick Search Band (multiple options)
 * @author Lavesh Rawat
 * @created 2014-11-04
*/
class QuickSearchBand extends SearchParamters
{	
	private $stype;
	const blankValue = "DONT_MATTER";
        const neverMarried = 'N';
        const male = 'M';
        const female = 'F';
        const manglik_blank = '';
        const occupation_blank = '';
        const education_blank = '';

	public function __construct($loggedInProfileObj="")
        {
                $this->loggedInProfileObj = $loggedInProfileObj;
                parent::__construct();
        }
	

	/*
        * @param $request request array
        * @return $SearchParamtersObj array containing object of class SearchParamters
        */
	public function getSearchCriteria($request)
	{
		$json = $request->getParameter('json');
		$jsonArr = json_decode($json,true);
		$searchParamsSetter['SEARCH_TYPE'] = self::getSearchType($request->getParameter('MOBILE_SEARCH'));


                /** 
                * If profile is logged in , then gender is of opposite gender
                */
		if($jsonArr["GENDER"])
			$searchParamsSetter['GENDER'] = $jsonArr["GENDER"];
		else
                {
                        if($this->loggedInProfileObj->getGENDER()==self::male)
                                $searchParamsSetter['GENDER'] = self::female;
                        else
                                $searchParamsSetter['GENDER'] = self::male;
                }

		$searchParamsSetter['LAGE'] = $jsonArr["LAGE"];
		$searchParamsSetter['HAGE'] = $jsonArr["HAGE"];

		$searchParamsSetter['LHEIGHT'] = $jsonArr["LHEIGHT"];
		$searchParamsSetter['HHEIGHT'] = $jsonArr["HHEIGHT"];


		if(isset($jsonArr["LINCOME"]) && isset($jsonArr["HINCOME"]))
                {
                        $rArr["minIR"] = $jsonArr["LINCOME"];
                        $rArr["maxIR"] = $jsonArr["HINCOME"];
                
                        $dArr = '';
                        $incomeType = "R";
                        $typeOfI='';
                        if(isset($jsonArr["LINCOME_DOL"]) && isset($jsonArr["HINCOME_DOL"]))
                        {
                                $dArr["minID"] = $jsonArr["LINCOME_DOL"];
                                $dArr["maxID"] = $jsonArr["HINCOME_DOL"];
                                $incomeType = "B";
                                $typeOfI=1;
                        }       
                                
                        $incomeMappingObj = new IncomeMapping($rArr,$dArr);
                        $incomeValues = $incomeMappingObj->getAllIncomes($typeOfI);
                        unset($incomeMappingObj);
                        $searchParamsSetter['INCOME'] = implode(",",$incomeValues);
                        $searchParamsSetter['LINCOME'] = $jsonArr["LINCOME"];
                        $searchParamsSetter['HINCOME'] = $jsonArr["HINCOME"];
                        if($incomeType == "B")
                        {
                                $searchParamsSetter['LINCOME_DOL'] = $jsonArr["LINCOME_DOL"];
                                $searchParamsSetter['HINCOME_DOL'] = $jsonArr["HINCOME_DOL"];
                        }
                }

		if($jsonArr["PHOTO"])
			$searchParamsSetter['HAVEPHOTO'] = $jsonArr["PHOTO"];
		if($jsonArr["MTONGUE"])
			$searchParamsSetter['MTONGUE'] = $jsonArr["MTONGUE"];
		if($jsonArr["RELIGION"])
			$searchParamsSetter['RELIGION'] = $jsonArr["RELIGION"];
		if($jsonArr["MANGLIK"] && $jsonArr["MANGLIK"] !=self::manglik_blank)
			$searchParamsSetter['MANGLIK'] = $jsonArr["MANGLIK"];
		if($jsonArr["CASTE"])
		{
			if(MobileCommon::isDesktop())
				$searchParamsSetter['CASTE'] = $jsonArr["CASTE"];
			else
			{
				$json1 = $jsonArr["CASTE"];
				$json1Arr = json_decode($json1,true);
				if(is_array($json1Arr))
				{
					$tempStr = implode(",",$json1Arr);
					$searchParamsSetter['CASTE'] = $tempStr;
				}
			}
		}
                if($jsonArr["EDUCATION"] && $jsonArr["EDUCATION"]!=self::education_blank)
		{
                  $searchParamsSetter['EDU_LEVEL_NEW'] = $jsonArr["EDUCATION"];
                }
                if($jsonArr["OCCUPATION"] && $jsonArr["OCCUPATION"]!=self::occupation_blank)
		{
                  $searchParamsSetter['OCCUPATION'] = $jsonArr["OCCUPATION"];
                }
                
                if($jsonArr["LOCATION"]){
                        $jsonArr["LOCATION"] .= ",".$jsonArr["LOCATION_CITIES"];
                }else{
                        $jsonArr["LOCATION"] = $jsonArr["LOCATION_CITIES"];
                }
                $jsonArr["LOCATION"] = trim($jsonArr["LOCATION"],',');
                //echo '<pre>';print_r($jsonArr);die;
		if($jsonArr["LOCATION"] && $jsonArr["LOCATION"]!=self::blankValue)
		{
			$city_country_resArr = explode(",",$jsonArr["LOCATION"]);
        	        foreach($city_country_resArr as $v)
                	{
	                        if(is_numeric($v))
					$tempCountry[] = $v;
				elseif(ctype_alpha($v))
					$tempState[] = $v;
				else
					$tempCity[] = $v;
                	}
			if($tempCountry)
		                $searchParamsSetter['COUNTRY_RES'] = implode(",",$tempCountry);
			if($tempCity)
			{
				$searchParamsSetter['CITY_RES'] = implode(",",$tempCity);
                                $tempCountry[] = 51;
				$searchParamsSetter['COUNTRY_RES'] = implode(",",$tempCountry);
			}
			if($tempState)
			{
				$searchParamsSetter['STATE'] = implode(",",$tempState);
				$tempCountry[] = 51;
				$searchParamsSetter['COUNTRY_RES'] = implode(",",$tempCountry);
			}
		}

                /*
                * For app mstatus is not based on user input.
                * We will set the status to be 
                * 1) Never married for logged-out case
                * 2) Never married if user mstatus is never married
                * 3) All Maried Earlier Options if user mstatus is not never married.
                */
               
                if(MobileCommon::isDesktop() && $jsonArr["MSTATUS"]!="E")//TopSearchBandConfig::$mstatusArr["Married Earlier"]
                		$mstatus[] = $jsonArr["MSTATUS"];
                elseif(!MobileCommon::isDesktop() && (!$this->loggedInProfileObj || $this->loggedInProfileObj->getPROFILEID()=='' || $this->loggedInProfileObj->getMSTATUS()==self::neverMarried))
			$mstatus[] = self::neverMarried;
                else
                {
									
                        $mstatusArr = FieldMap::getFieldLabel('mstatus','',1);
                        unset($mstatus);
                        foreach($mstatusArr AS $k=>$v)
                        {
                                if($k!=self::neverMarried)
                                        $mstatus[]=$k;
                        }
                }
                $searchParamsSetter['MSTATUS'] = implode(",",$mstatus);
		if(!MobileCommon::isDesktop())
		{
			foreach($searchParamsSetter as $k=>$v)
			{
                        	if($v=='DONT_MATTER')
                                	$searchParamsSetter[$k] = '';
			}
                }
               
		$this->setter($searchParamsSetter);
	}

	private static function getSearchType($searchSource)
	{
		if(MobileCommon::isMobile())
			return SearchTypesEnums::MobileSearchBand;
		elseif(MobileCommon::isApp()=='I')
			return SearchTypesEnums::iOS;
		return SearchTypesEnums::Quick;
	}
} 
?>
