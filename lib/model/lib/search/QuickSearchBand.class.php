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
        private $skipFields = array("GENDER","AGE","INCOME","HEIGHT","RELIGION","CASTE","CASTE_GROUP","MATCHALERTS_DATE_CLUSTER","HAVEPHOTO","MTONGUE","CITY_RES","COUNTRY_RES","STATE","MSTATUS");

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

                $solr_clusters = FieldMap::getFieldLabel("solr_clusters",1,1);
                $applyClusters = array_diff($solr_clusters,$this->skipFields);
                foreach($applyClusters as $clusterFields){
                        if($cluster = $jsonArr[$clusterFields]){
                                if($clusterFields == "KNOWN_COLLEGE"){
                                        if($cluster == "Any")
                                                $searchParamsSetter['KNOWN_COLLEGE_IGNORE'] = "000";
                                        else
                                                $searchParamsSetter['KNOWN_COLLEGE'] = $cluster;
                                }else{
                                        $searchParamsSetter[$clusterFields] = $cluster;
                                }
                        }
                }
                
                if($request->getParameter('edu_level_new'))
                        $searchParamsSetter['EDU_LEVEL_NEW'] = $request->getParameter('edu_level_new');
                
		if(isset($jsonArr["LINCOME"]) && isset($jsonArr["HINCOME"]))
                {
                        $rArr["minIR"] = 0;
                        $rArr["maxIR"] = 19;
                        if($jsonArr["LINCOME"] != ""){
                                $rArr["minIR"] = $jsonArr["LINCOME"];
                        }
                        if($jsonArr["HINCOME"] != ''){
                                $rArr["maxIR"] = $jsonArr["HINCOME"];
                        }
                
                        $dArr = '';
                        $incomeType = "R";
                        $typeOfI='';
                        /*if($formArr["partner_country_arr"]==51 && $jsonArr["LINCOME"] && $jsonArr["LINCOME"]!='0' && $jsonArr["LINCOME_DOL"]=='0'){
                                        $jsonArr["LINCOME_DOL"] = 12;
                        }*/
                        if(isset($jsonArr["LINCOME_DOL"]) && isset($jsonArr["HINCOME_DOL"]))
                        {
                                $dArr["minID"] = 0;
                                $dArr["maxID"] = 19;
                                if($jsonArr["LINCOME_DOL"] != ''){
                                        $dArr["minID"] = $jsonArr["LINCOME_DOL"];
                                }
                                if($jsonArr["HINCOME_DOL"] != ''){
                                        $dArr["maxID"] = $jsonArr["HINCOME_DOL"];
                                }
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
                                if(empty($json1Arr)){
                                     $json1Arr = explode(',',$json1);
                                }
                                if(is_array($json1Arr))
                                {
                                        $tempStr = implode(",",$json1Arr);
                                        $searchParamsSetter['CASTE'] = $tempStr;
                                }else{
                                    $json1Arr = explode(',',$json1Arr);
                                    $json1Arr = implode(',',$json1Arr);
                                    $searchParamsSetter['CASTE'] = $json1Arr;    
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
				//$searchParamsSetter['CITY_INDIA'] = implode(",",$tempCity);
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
                $doesntMAtterArray = array('DONT_MATTER');
		if(!MobileCommon::isDesktop())
		{
			foreach($searchParamsSetter as $k=>$v)
			{
                        	if($v=='DONT_MATTER'){
                                	$searchParamsSetter[$k] = '';
                                }else{
                                        if(strstr($v,'DONT_MATTER')){
                                                $searchParamsSetter[$k] = implode(',',array_diff(explode(",",$searchParamsSetter[$k]),$doesntMAtterArray));
                                        }
                                }
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
