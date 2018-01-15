<?php
/**
* This class is used to generate the data for top search band
* @author : Lavesh Rawat
* @package Search
* @subpackage SearchBand
* @link http://devjs.infoedge.com/mediawiki/index.php/SEARCH
* @since 2012-07-25
*/
class TopSearchBandPopulate
{
	private $xmlObj;
	private $domtree;
	private $xmlRoot;
	private $selectedGender;
	private $selectedLage;
	private $selectedHage;
	private $selectedMstatus;
	private $selectedCaste;
	private $selectedReligion;
	private $selectedMtongue;
       private $selectedCity_Country;
	private $selectedHavePhoto;
	private $bigBand;
	private $dataArray;
	private $selectedLheight;
	private $selectedHheight;
	private $selectedOccupationGrouping;
	private $selectedEducationGrouping;
	private $selectedDiet;
	private $selectedLincome;
	private $selectedHincome;
	private $selectedManglik;
	private $selectedOccupationJSMS;
	private $selectedEducationJSMS;
	private $isNewApp = 0;

	/**
	*Constructor to set the class variables
	* @access public
	* @param Array $parameters:  array that has the values passed in the top search band url
	* @see PartnerProfile
	* @see TopSearchBandConfig
	* @see SearchLogger
	*/
	public function __construct($parameters='')
	{
                if(array_key_exists("app54",$parameters) && $parameters["app54"] == 1)
                       $this->isNewApp = 1;
                        
		if(is_array($parameters) && $parameters["forClusters"]=='1')
			return;

		if(!$parameters || !is_array($parameters) || $parameters["BIGBAND"]!="N")
                        $parameters["BIGBAND"] = "Y";

                $loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
                if($loggedInProfileObj && $loggedInProfileObj->getPROFILEID()!='')
		{
			
			$loggedInProfileObj->getDetail("","","GENDER,HAVEPHOTO");
				
		}
                
                if(!$parameters["SEARCHID"] && $loggedInProfileObj && $loggedInProfileObj->getPROFILEID())    //LOGGEDIN and no SEARCHID -> show JPARTNER data
                {
			
			$searchObj = new SearchLogger();
                        $flag = $searchObj->getLastSearchCriteria($loggedInProfileObj->getPROFILEID());
                        if(!$flag)
                        {
				$searchObj = new PartnerProfile($loggedInProfileObj);
				$searchObj->getDppCriteria();
				$flag = 1;
				
			}
			
                        
                }
                elseif($parameters["SEARCHID"])       //SEARCHID is present -> SHOW SEARCHID data
                {
			$searchObj = new SearchLogger();
                        $searchObj->getSearchCriteria($parameters["SEARCHID"]);
                        $flag = 1;
                }
                else    //DEFAULT BEHAVIOR and COMMUNITY PAGES handling
                {
			$param["GENDER"] = TopSearchBandConfig::$femaleGenderValue;
                        $param["LAGE"] = TopSearchBandConfig::$minDefaultAge;
                        $param["HAGE"] = TopSearchBandConfig::$maxDefaultAge;
                        if(MobileCommon::isMobile("JS_MOBILE") || MobileCommon::isApp())
                        {
				$param["LHEIGHT"] = TopSearchBandConfig::$minDefaultHeight;
				$param["HHEIGHT"] = TopSearchBandConfig::$maxDefaultHeight;
				$param["LINCOME"] = TopSearchBandConfig::$minDefaultIncome;
				$param["HINCOME"] = TopSearchBandConfig::$maxDefaultIncome;
			}
                        $param["HAVEPHOTO"] = "Y";
                        if($parameters["SEO"])
                        {
                                $field=explode('-',$parameters["SEO_FIELD"]);
                                $value=explode('-',$parameters["SEO_VALUE"]);
                                foreach($field as $k=>$v)
                                {
                                        if($v=="CASTE")
                                                $field[$k] = "CASTE_DISPLAY";
                                        if($v=="CITY")
                                                $field[$k] = "CITY_INDIA";
                                        if($v=="CITY_RES")
                                                $field[$k] = "COUNTRY_RES";
                                }
                                if($field[0] && $value[0])
                                        $param[$field[0]] = $value[0];
                                if($field[1] && $value[1])
                                        $param[$field[1]] = $value[1];
                                if($field[2] && $value[2])
                                        $param[$field[2]] = $value[2];
                        }
		}
		if($flag==1)
                {
			if($searchObj->getGENDER())
                                $param["GENDER"] = $searchObj->getGENDER();
                        if($loggedInProfileObj && $loggedInProfileObj->getPROFILEID())
                        {
				if($loggedInProfileObj->getGENDER() == TopSearchBandConfig::$femaleGenderValue)
					$param["GENDER"] = TopSearchBandConfig::$maleGenderValue;
				else
					$param["GENDER"] = TopSearchBandConfig::$femaleGenderValue;
			}
			
                        if($searchObj->getLAGE())
                                $param["LAGE"] = $searchObj->getLAGE();
                        if($searchObj->getHAGE())
                                $param["HAGE"] = $searchObj->getHAGE();
                        if($searchObj->getMSTATUS())
                                $param["MSTATUS"] = $searchObj->getMSTATUS();
                        if($searchObj->getCASTE_DISPLAY())
                                $param["CASTE_DISPLAY"] = $searchObj->getCASTE_DISPLAY();
                        if($searchObj->getRELIGION())
                                $param["RELIGION"] = $searchObj->getRELIGION();
                        if($searchObj->getMTONGUE())
                                $param["MTONGUE"] = $searchObj->getMTONGUE();
                        if($searchObj->getCOUNTRY_RES())
                                $param["COUNTRY_RES"] = $searchObj->getCOUNTRY_RES();
                        if($searchObj->getCITY_INDIA())
                                $param["CITY_INDIA"] = $searchObj->getCITY_INDIA();
                        if($searchObj->getCITY_RES())
                                $param["CITY_RES"] = $searchObj->getCITY_RES();
                        if($searchObj->getSTATE())
																 $param["STATE"] = $searchObj->getSTATE();
			
                        if($searchObj->getHAVEPHOTO())
			{
                                $param["HAVEPHOTO"] = $searchObj->getHAVEPHOTO();
			}
			else
			{
				if(!$parameters["SEARCHID"] && $loggedInProfileObj && $loggedInProfileObj->getPROFILEID())
				{
					if($loggedInProfileObj->getHAVEPHOTO()=="Y")
						$param["HAVEPHOTO"] = $loggedInProfileObj->getHAVEPHOTO();
				}
			}
                        if($searchObj->getLHEIGHT())
                                $param["LHEIGHT"] = $searchObj->getLHEIGHT();
                        if($searchObj->getHHEIGHT())
                                $param["HHEIGHT"] = $searchObj->getHHEIGHT();
                        if($searchObj->getLINCOME()!="")
                                $param["LINCOME"] = $searchObj->getLINCOME();
                        if($searchObj->getHINCOME())
                                $param["HINCOME"] = $searchObj->getHINCOME();
                        if($searchObj->getOCCUPATION())
                                $param["OCCUPATION"] = $searchObj->getOCCUPATION();
                        if($searchObj->getEDU_LEVEL_NEW())
                                $param["EDUCATION"] = $searchObj->getEDU_LEVEL_NEW();
                        if($searchObj->getMANGLIK())
                                $param["MANGLIK"] = $searchObj->getMANGLIK();
                        unset($searchObj);
                        unset($flag);
                }
		unset($loggedInProfileObj);
                $param["BIGBAND"] = $parameters["BIGBAND"];
		
		if($param["GENDER"])
			$this->selectedGender = $param["GENDER"];
		else
			$this->selectedGender = TopSearchBandConfig::$femaleGenderValue;

		if($param["LAGE"])
			$this->selectedLage = $param["LAGE"];
		else
		{
			if($this->selectedGender==TopSearchBandConfig::$maleGenderValue)
				$this->selectedLage = TopSearchBandConfig::$minAgeMale;
			else
				$this->selectedLage = TopSearchBandConfig::$minAgeFemale;
		}

		if($param["HAGE"])
			$this->selectedHage = $param["HAGE"];
		else
			$this->selectedHage = TopSearchBandConfig::$maxAge;

		if($param["MSTATUS"])
		{
			$mstatusArr = FieldMap::getFieldLabel('mstatus',1,1);
                        unset($mstatus);
                        foreach($mstatusArr AS $k=>$v)
                        {
                                if($k!=TopSearchBandConfig::$neverMarriedValue)
                                        $mstatus[]=$k;
                        }
			$marriedEarlierString = implode(",",$mstatus);
			unset($mstatus);
			if($param["MSTATUS"]==$marriedEarlierString)
				$this->selectedMstatus = TopSearchBandConfig::$marriedEarlierValue;
			else
				$this->selectedMstatus = $param["MSTATUS"];
		}
		if($param["CASTE_DISPLAY"])
			$this->selectedCaste = $param["CASTE_DISPLAY"];
		if($param["RELIGION"])
			$this->selectedReligion = $param["RELIGION"];
		if($param["MTONGUE"])
			$this->selectedMtongue = $param["MTONGUE"];
			
		if($parameters && is_array($parameters) && (array_key_exists("SETMULTIPLE",$parameters) || $this->isNewApp == 1))
                {
									
                        if($this->isNewApp == 1){
                                if($param["CITY_INDIA"])
                                        $this->selectedCity_Country .= $param["CITY_INDIA"].",";
                                if($param["CITY_RES"])
                                        $this->selectedCity_Country .= $param["CITY_RES"].",";
                                if($param["STATE"])
                                        $this->selectedCity_Country .= $param["STATE"].",";
                                
                                $this->selectedCity_Country .= $param["COUNTRY_RES"];
                                
                                $this->selectedCity_Country = trim($this->selectedCity_Country,",");
                        }else{
                                if($param["CITY_INDIA"])
                                        $this->selectedCity_Country = $param["CITY_INDIA"].",".$param["COUNTRY_RES"];
                                elseif($param["CITY_RES"])
                                        $this->selectedCity_Country = $param["CITY_RES"].",".$param["COUNTRY_RES"];
                                elseif($param["STATE"])
                                        $this->selectedCity_Country = $param["STATE"].",".$param["COUNTRY_RES"];
                                else
                                        $this->selectedCity_Country = $param["COUNTRY_RES"];
//                                if($param["CITY_INDIA"] || $param["CITY_RES"] || $param["STATE"])
//                                        $this->selectedCity_Country = str_replace("51","",$this->selectedCity_Country); // India any city remove
                                $this->selectedCity_Country = trim($this->selectedCity_Country,",");
                        }
                }
		else
		{
			if(in_array($param["CITY_INDIA"],TopSearchBandConfig::$cities) || self::if_two_string_contains_same_values($param["CITY_INDIA"],TopSearchBandConfig::$mumbaiRegion) || self::if_two_string_contains_same_values($param["CITY_INDIA"],implode(",",FieldMap::getFieldLabel("delhiNcrCities",1,1))))
				$this->selectedCity_Country[] = $param["CITY_INDIA"];
			
                        if(in_array($param["CITY_RES"],TopSearchBandConfig::$cities) || self::if_two_string_contains_same_values($param["CITY_RES"],TopSearchBandConfig::$mumbaiRegion) || self::if_two_string_contains_same_values($param["CITY_RES"],implode(",",FieldMap::getFieldLabel("delhiNcrCities",1,1))))
				$this->selectedCity_Country[] = $param["CITY_RES"];
			
                        if(in_array($param["COUNTRY_RES"],TopSearchBandConfig::$countries))
				$this->selectedCity_Country[] = $param["COUNTRY_RES"];
                        
                        if(array_key_exists($param["STATE"],FieldMap::getFieldLabel("state_india",1,1)))
				$this->selectedCity_Country[] = $param["STATE"];
			//else
				//$this->selectedCity_Country[] = $param["CITY_INDIA"];
                        $this->selectedCity_Country = implode(",",$this->selectedCity_Country);
		}
                
		
		if($param["HAVEPHOTO"])
			$this->selectedHavePhoto = $param["HAVEPHOTO"];
		if($param["BIGBAND"])
			$this->bigBand = $param["BIGBAND"];
		if($param["LHEIGHT"])
			$this->selectedLheight = $param["LHEIGHT"];
		if($param["HHEIGHT"])
			$this->selectedHheight = $param["HHEIGHT"];
		if($param["LINCOME"] || $param["LINCOME"]==0 )
			$this->selectedLincome = $param["LINCOME"];
		if($param["HINCOME"])
			$this->selectedHincome = $param["HINCOME"];
		if($param["MANGLIK"])
			$this->selectedManglik = $param["MANGLIK"];
		if($param["EDUCATION"])
			$this->selectedEducationJSMS = $param["EDUCATION"];
		if($param["OCCUPATION"])
			$this->selectedOccupationJSMS = $param["OCCUPATION"];
					
	}

	/**
	This function is used to generate the XML file for the top search band (used for desktop site)
	* @access public
	* @see JsMemcache
	* @return XML $topSearchBandXml
	*/
	public function generateXML()
	{
		if(JsMemcache::getInstance()->get("TOP_SEARCH_BAND_CONTENT"))
		{
			$content = JsMemcache::getInstance()->get("TOP_SEARCH_BAND_CONTENT");
		}
		else
		{
			$this->xmlObj = new CreateXml;
			$this->domtree = $this->xmlObj->createDoc();

			$this->xmlRoot = $this->xmlObj->addChildWithoutValue($this->domtree,$this->domtree,"topSearchBand",1);
			$this->populateAge();
			$this->populateGender();
			$this->populateMstatus();
			$this->populateCaste();
			$this->populateReligion();
			$this->populateMtongue();
			$this->populateCity_Country();
			$this->populateReligionCasteDependency();
	
			$content = $this->xmlObj->saveDoc($this->domtree);
			JsMemcache::getInstance()->set("TOP_SEARCH_BAND_CONTENT",$content);
		}

		$topSearchBandXml = new SimpleXMLElement($content);
		$this->populateSelectedValues($topSearchBandXml);
		$this->checkAgeBasedOnGender($topSearchBandXml);
		$this->checkCasteBasedOnReligion($topSearchBandXml);
		return $topSearchBandXml->saveXML();
	}

	/**
	This function is used to generate the data arrays for the top search band (used for mobile)
	* @access public
	* @see JsMemcache
	* @return Array $this->dataArray
	*/
	public function generateDataArray()
	{
		if(JsMemcache::getInstance()->get("TOP_SEARCH_BAND_CONTENT_MOBILE"))
                {
                        $this->dataArray = unserialize(JsMemcache::getInstance()->get("TOP_SEARCH_BAND_CONTENT_MOBILE"));
                }
		else
		{
			$this->dataArray["age"] = $this->populateAge(1);
			$this->dataArray["gender"] = $this->populateGender(1);
			$this->dataArray["mstatus"] = $this->populateMstatus(1);
			$this->dataArray["caste"] = $this->populateCaste(1);
			$this->dataArray["religion"] = $this->populateReligion(1);
			$this->dataArray["mtongue"] = $this->populateMtongue(1);
			$this->dataArray["location"] = $this->populateCity_Country(1);
			$this->dataArray["religionCasteDependency"] = $this->populateReligionCasteDependency(1);
			$this->dataArray["height"] = $this->populateHeight(1);
			$this->dataArray["occupation"] = $this->populateOccupation(1);
			$this->dataArray["education"] = $this->populateEducation(1);
			$this->dataArray["diet"] = $this->populateDiet(1);
			$this->dataArray["income"] = $this->populateIncome(1);
			
			JsMemcache::getInstance()->set("TOP_SEARCH_BAND_CONTENT_MOBILE",serialize($this->dataArray));
		}
		$this->dataArray["selectedValues"] = $this->populateSelectedValues();
		$this->checkAgeBasedOnGender();
                $this->checkCasteBasedOnReligion();

		return $this->dataArray;
	}
	
	/**
	This function is used to generate the data arrays for the top search band (used for app)
	* @access public
	* @see JsMemcache
	* @return Array $this->dataArray
	*/
	public function generateDataArrayApp()
	{
		
		if(JsMemcache::getInstance()->get("TOP_SEARCH_BAND_CONTENT_APP")  && MobileCommon::isApp() != 'I')
                {
                        $this->dataArray = unserialize(JsMemcache::getInstance()->get("TOP_SEARCH_BAND_CONTENT_APP"));
                }
		else
		{
			$this->dataArray["age"] = $this->populateAge(1,1);
			$this->dataArray["gender"] = $this->populateGender(1);
			$this->dataArray["caste"] = $this->populateCaste(1,1);
			$this->dataArray["religion"] = $this->populateReligion(1,1);
			$this->dataArray["mtongue"] = $this->populateMtongue(1,1);
			$this->dataArray["location"] = $this->populateCity_Country(1,1);
			$this->dataArray["religionCasteDependency"] = $this->populateReligionCasteDependencyApp(1);
			$this->dataArray["height"] = $this->populateHeight(1,null,1);
			$this->dataArray["income"] = $this->populateIncome(1,null,1);
			
			//ADD SORTBY column as required by ios team and it has no effect on adroid team
			foreach($this->dataArray as $k=>$v)
			{
				$i=1;
				foreach($v as $kk=>$vv)
				{
					if($k=="religionCasteDependency")
					{
						$j=1;
						foreach($vv["CASTE_STRING"] as $kkk=>$vvv)
						{
							$this->dataArray[$k][$kk]["CASTE_STRING"][$kkk]["SORTBY"] = $j;
							$j++;
						}
					}
					else
					{
						$this->dataArray[$k][$kk]["SORTBY"] = $i;
					}					
					$i++;
				}
			}
			// ADD SORTBY column ends

			JsMemcache::getInstance()->set("TOP_SEARCH_BAND_CONTENT_APP",serialize($this->dataArray));
		}

		return $this->dataArray;
	}
	
	/**
	This function is used to generate the data arrays for the top search band (used for app)
	* @access public
	* @see JsMemcache
	* @return Array $this->dataArray
	*/
	public function generateDataArrayMobile()
	{
		if(JsMemcache::getInstance()->get("TOP_SEARCH_BAND_CONTENT_JSMS"))
                {
                        $this->dataArray = unserialize(JsMemcache::getInstance()->get("TOP_SEARCH_BAND_CONTENT_JSMS"));
                }
		else
		{
			$this->dataArray["age"] = $this->populateAge(1,null,1);
			$this->dataArray["height"] = $this->populateHeight(1,1);
			$this->dataArray["gender"] = $this->populateGender(1);
			$this->dataArray["mstatus"] = $this->populateMstatus(1,1);
			$this->dataArray["mtongue"] = $this->populateMtongueMobile();
			$this->dataArray["religion"] = $this->populateReligionJSMS();
			$this->dataArray["caste"] = $this->populateCasteJSMS();
			$this->dataArray["location"] = $this->populateCityCountryJSMS();
			$this->dataArray["location_cities"] = $this->populateCitiesJSMS();
			$this->dataArray["income"] = $this->populateIncome(1,1);
			$this->dataArray["income_dol"] = $this->populateIncomeDollar();
			$this->dataArray["manglik"] = $this->populateManglik();
			$this->dataArray["occupation"] = $this->populateOccupationForJSMS();
			$this->dataArray["education"] = $this->populateEducationForJSMS();
			JsMemcache::getInstance()->set("TOP_SEARCH_BAND_CONTENT_JSMS",serialize($this->dataArray));
		}

		return $this->dataArray;
	}
        
        /**
	This function is used to generate the data arrays for the top search band (used for app)
	* @access public
	* @see JsMemcache
	* @return Array $this->dataArray
	*/
	public function generateDataArrayPC()
	{
		if(JsMemcache::getInstance()->get("TOP_SEARCH_BAND_CONTENT_PC"))
                {
                        $this->dataArray = unserialize(JsMemcache::getInstance()->get("TOP_SEARCH_BAND_CONTENT_PC"));
                }
		else
		{
			$this->dataArray["age"] = $this->populateAge(1,null,1);
			$this->dataArray["height"] = $this->populateHeight(1,1);
			$this->dataArray["gender"] = $this->populateGender(1);
			$this->dataArray["mstatus"] = $this->populateMstatus(1,1);
			$this->dataArray["mtongue"] = $this->populateMtongueMobile();
			$this->dataArray["religion"] = $this->populateReligionJSMS();
			$this->dataArray["caste"] = $this->populateCasteJSMS();
			$this->dataArray["location"] = $this->populateCityCountryJSMS();
			$location_cities = $this->populateCitiesJSMS();
                        $this->dataArray["location"] = array_merge($this->dataArray["location"],$location_cities);
			$this->dataArray["income"] = $this->populateIncome(1,1);
			$this->dataArray["income_dol"] = $this->populateIncomeDollar();
			$this->dataArray["manglik"] = $this->populateManglik();
			$this->dataArray["occupation"] = $this->populateOccupationForJSMS();
			$this->dataArray["education"] = $this->populateEducationForJSMS();
			JsMemcache::getInstance()->set("TOP_SEARCH_BAND_CONTENT_PC",serialize($this->dataArray));
		}

		return $this->dataArray;
	}
	/**
	*This function is used to populate age data for top search band
	* @access public
	* @staticVar TopSearchBandConfig::$minAgeFemale
	* @staticVar TopSearchBandConfig::$maxAge
	* @param String $notXml - if xml is not required (optional),
	* @param String $mobileApp - (optional)
	*/
	public function populateAge($notXml='',$mobileApp='',$jsms='')
	{
		$mobileApp = $mobileApp?$mobileApp:'';
		$minAge = TopSearchBandConfig::$minAgeFemale;
			
		$maxAge = TopSearchBandConfig::$maxAge;
		$j=0;
		for($i=$minAge;$i<=$maxAge;$i++)
		{
			if($mobileApp)
				$output[]["VALUE"] = $i;
			elseif($jsms)
			{
				$output[$j]["VALUE"] = $i;
				$output[$j]["LABEL"] = $i;
				$output[$j]["IN_GROUP"] = "";
				$j++;
			}
			else
				$output[] = $i;
		}

		if($notXml)
			return $output;
		else
		{
			$currentTrack = $this->xmlObj->addChildWithoutValue($this->domtree,$this->xmlRoot,"age",1);
			foreach($output as $k=>$v)
				$this->xmlObj->addChildWithValue($this->domtree,$currentTrack,"value",$v);
		}
	}
	
	/**
	This function is used to populate gender data for top search band
	* @access public
	* @staticVar TopSearchBandConfig::$maleGenderValue
	* @staticVar TopSearchBandConfig::$maleLabel
	* @staticVar TopSearchBandConfig::$femaleGenderValue
	* @staticVar TopSearchBandConfig::$femaleLabel
	* @param String $notXml - if xml is not required (optional),
	*/
	public function populateGender($notXml='')
	{
		$output[0]["VALUE"] = TopSearchBandConfig::$maleGenderValue; 
		$output[0]["LABEL"] = TopSearchBandConfig::$maleLabel;
		$output[1]["VALUE"] = TopSearchBandConfig::$femaleGenderValue;
		$output[1]["LABEL"] = TopSearchBandConfig::$femaleLabel;

		if($notXml)
			return $output;
		else
		{
			$genderRoot = $this->xmlObj->addChildWithoutValue($this->domtree,$this->xmlRoot,"gender",1);
			foreach($output as $k=>$v)
			{
				$currentTrack = $this->xmlObj->addChildWithoutValue($this->domtree,$genderRoot,"data",1);
				$this->xmlObj->addChildWithValue($this->domtree,$currentTrack,"value",$v["VALUE"]);
				$this->xmlObj->addChildWithValue($this->domtree,$currentTrack,"label",$v["LABEL"]);
			}
		}
	}

	/**
	This function is used to populate pre selected values for top search band
	* @access public
	* @param String $topSearchBandXml- if xml is required, pass the xml object (optional)
	* @return Array $output - Only when not $topSearchBandXml
	*/
	public function populateSelectedValues($topSearchBandXml='')
	{
		
		if($this->selectedGender)
		{
			if($topSearchBandXml)
				$topSearchBandXml->addChild("selectedGender",$this->selectedGender);
			else
				$output["selectedGender"] = $this->selectedGender;
		}
		if($this->selectedLage)
		{
			if($topSearchBandXml)
				$topSearchBandXml->addChild("selectedLage",$this->selectedLage);
			else
				$output["selectedLage"] = $this->selectedLage;
		}
		if($this->selectedHage)
		{
			if($topSearchBandXml)
				$topSearchBandXml->addChild("selectedHage",$this->selectedHage);
			else
				$output["selectedHage"] = $this->selectedHage;
		}
		if($this->selectedMstatus)
		{
			if($topSearchBandXml)
				$topSearchBandXml->addChild("selectedMstatus",$this->selectedMstatus);
			else
				$output["selectedMstatus"] = $this->selectedMstatus;
		}
		if($this->selectedCaste)
		{
			if($topSearchBandXml)
				$topSearchBandXml->addChild("selectedCaste",$this->selectedCaste);
			else
				$output["selectedCaste"] = $this->selectedCaste;
		}
		if($this->selectedReligion)
		{
			if($topSearchBandXml)
				$topSearchBandXml->addChild("selectedReligion",$this->selectedReligion);
			else
				$output["selectedReligion"] = $this->selectedReligion;
		}
		if($this->selectedMtongue)
		{
			if($topSearchBandXml)
				$topSearchBandXml->addChild("selectedMtongue",$this->selectedMtongue);
			else
				$output["selectedMtongue"] = $this->selectedMtongue;
		}
		if($this->selectedCity_Country)
		{
			if($topSearchBandXml)
				$topSearchBandXml->addChild("selectedCity_Country",$this->selectedCity_Country);
			else
				$output["selectedCity_Country"] = $this->selectedCity_Country;
		}
		if($this->selectedHavePhoto)
		{
			if($topSearchBandXml)
				$topSearchBandXml->addChild("selectedHavePhoto",$this->selectedHavePhoto);
			else
				$output["selectedHavePhoto"] = $this->selectedHavePhoto;
		}
		if($this->bigBand)
		{
			if($topSearchBandXml)
				$topSearchBandXml->addChild("bigBand",$this->bigBand);
			else
				$output["bigBand"] = $this->bigBand;
		}
		if($this->selectedLheight)
                {
                        if($topSearchBandXml)
                                $topSearchBandXml->addChild("selectedLheight",$this->selectedLheight);
                        else
                                $output["selectedLheight"] = $this->selectedLheight;
                }
                if($this->selectedHheight)
                {
                        if($topSearchBandXml)
                                $topSearchBandXml->addChild("selectedHheight",$this->selectedHheight);
                        else
                                $output["selectedHheight"] = $this->selectedHheight;
                }
		if($this->selectedOccupationGrouping)
                {
                        if($topSearchBandXml)
                                $topSearchBandXml->addChild("selectedOccupationGrouping",$this->selectedOccupationGrouping);
                        else
                                $output["selectedOccupationGrouping"] = $this->selectedOccupationGrouping;
                }
		if($this->selectedEducationGrouping)
                {
                        if($topSearchBandXml)
                                $topSearchBandXml->addChild("selectedEducationGrouping",$this->selectedEducationGrouping);
                        else
                                $output["selectedEducationGrouping"] = $this->selectedEducationGrouping;
                }
		if($this->selectedDiet)
                {
                        if($topSearchBandXml)
                                $topSearchBandXml->addChild("selectedDiet",$this->selectedDiet);
                        else
                                $output["selectedDiet"] = $this->selectedDiet;
                }
		if($this->selectedLincome || $this->selectedLincome == '0')
                {
                        if($topSearchBandXml)
                                $topSearchBandXml->addChild("selectedLincome",$this->selectedLincome);
                        else
                                $output["selectedLincome"] = $this->selectedLincome;
                }
                if($this->selectedHincome || $this->selectedHincome == '0')
                {
                        if($topSearchBandXml)
                                $topSearchBandXml->addChild("selectedHincome",$this->selectedHincome);
                        else
                                $output["selectedHincome"] = $this->selectedHincome;
                }
                if(!$topSearchBandXml)
			return $output;
	}

	/**
	This function is used to populate marital status data for top search band
	* @access public
	* @staticVar TopSearchBandConfig::$mstatusArr
	* @param String $notXml- if xml is required, pass the xml object (optional)
	* @return Array $output - Only when not $notXml
	*/
	public function populateMstatus($notXml='',$api='')
	{
		$i=0;
		if(!$api)
		{
			$output[$i]["VALUE"] = "";
			$output[$i]["LABEL"] = "Select Marital Status";
			$i++;
		}
		foreach(TopSearchBandConfig::$mstatusArr as $k=>$v)
                {
			$output[$i]["VALUE"] = $k;
			$output[$i]["LABEL"] = $v;
			$i++;
		}
		
		if($notXml)
			return $output;
		else
		{
			$mstatusRoot = $this->xmlObj->addChildWithoutValue($this->domtree,$this->xmlRoot,"mstatus",1);
			foreach($output as $k=>$v)
			{
				$currentTrack = $this->xmlObj->addChildWithoutValue($this->domtree,$mstatusRoot,"data",1);
				$this->xmlObj->addChildWithValue($this->domtree,$currentTrack,"value",$v["VALUE"]);
				$this->xmlObj->addChildWithValue($this->domtree,$currentTrack,"label",$v["LABEL"]);
			}
		}
	}

	/**
	This function is used to populate caste data for top search band
	* @access public
	* @see TopSearchBandConfig
	* @see NEWJS_CASTE
	* @see FieldMap
	* @param String $notXml- if xml is required, pass the xml object (optional)
	* @param String $mobileApp- if is mobileApp
	* @return Array $output - Only when not $notXml
	*/
	public function populateCaste($notXml='',$mobileApp='')
	{
		if($mobileApp)		//Needed by IOS Team To mark if a particular caste in search band is present in registration or not
		{
			$ncObj = new NEWJS_CASTE;
                      	$casteArr = $ncObj->getFullTableForRegistration();
                       	unset($ncObj);
			foreach($casteArr as $k=>$v)
			{
				$casteRegArr[$v["VALUE"]] = 1;
			}
			unset($casteArr);
		}

		$i=0;
                $output[$i]["VALUE"] = "";
		$output[$i]["LABEL"] = "Select Caste";
		if($mobileApp)
		{
			$output[$i]["ISGROUP"] = "";
			$output[$i]["ISALL"] = "";
			$output[$i]["PARENT"] = "";
			$output[$i]["ISCHILD"] = "";
			$output[$i]["IN_REG"] = "";
		}
		
                $i++;
                $output[$i]["VALUE"] = TopSearchBandConfig::$doesNotMatterValue;
		$output[$i]["LABEL"] = TopSearchBandConfig::$doesNotMatterLabel;
		if($mobileApp)
		{
			$output[$i]["ISGROUP"] = "";
			$output[$i]["ISALL"] = "";
			$output[$i]["PARENT"] = "";
			$output[$i]["ISCHILD"] = "";
			$output[$i]["IN_REG"] = "";
		}
		$i++;

		$casteObj = new NEWJS_CASTE;
             	$caste_arr = $casteObj->getTopSearchBandCasteData();
                unset($casteObj);

		if($caste_arr && is_array($caste_arr))
		{
			foreach($caste_arr as $k=>$v)
			{
				$output[$i]["VALUE"] = $v["VALUE"];
				if(strstr($v["LABEL"],":"))
                                {
					$output[$i]["LABEL"] = trim(ltrim(strstr($v["LABEL"],":"),":")); 
                                }
                                else
					$output[$i]["LABEL"] = $v["LABEL"];
				if($mobileApp)
				{
					if($v["ISGROUP"]=="Y")
						$output[$i]["LABEL"] = $output[$i]["LABEL"]." - All";
				} 
				$output[$i]["ISGROUP"] = $v["ISGROUP"];
				$output[$i]["ISALL"] = $v["ISALL"];
				$output[$i]["PARENT"] = $v["PARENT"];
				if($mobileApp)
				{
					$output[$i]["ISCHILD"] = "";
					if($casteRegArr[$v["VALUE"]])
						$output[$i]["IN_REG"] = "Y";
					else
						$output[$i]["IN_REG"] = "";
				}
				$i++;
				if($v["ISGROUP"]=="Y")
				{
					$caste_in_group_arr = explode(",",FieldMap::getFieldLabel("caste_group_array",$v["VALUE"]));
					foreach($caste_in_group_arr as $kk=>$vv)
					{
						$output[$i]["VALUE"] = $vv;
						if(strstr(FieldMap::getFieldLabel("caste",$vv),":") && strstr(FieldMap::getFieldLabel("caste",$vv),"Others")===false)
                                		{
                                        		$output[$i]["LABEL"] = trim(ltrim(strstr(FieldMap::getFieldLabel("caste",$vv),":"),":"));
                               	 		}
                                		else
                                        		$output[$i]["LABEL"] = FieldMap::getFieldLabel("caste",$vv);
						$output[$i]["ISGROUP"] = "";
                                		$output[$i]["ISALL"] = "";
                                		$output[$i]["PARENT"] = $v["PARENT"];
						$output[$i]["ISCHILD"] = "Y";
						if($mobileApp)
						{
							if($casteRegArr[$vv])
								$output[$i]["IN_REG"] = "Y";
							else
								$output[$i]["IN_REG"] = "";
						}
						$i++;
					}
				}
			}
		}
		unset($caste_arr);

		if($notXml)
			return $output;
		else
		{
			$casteRoot = $this->xmlObj->addChildWithoutValue($this->domtree,$this->xmlRoot,"caste",1);
			foreach($output as $k=>$v)
			{
				$currentTrack = $this->xmlObj->addChildWithoutValue($this->domtree,$casteRoot,"data",1);
				$this->xmlObj->addChildWithValue($this->domtree,$currentTrack,"value",$v["VALUE"]);
				$this->xmlObj->addChildWithValue($this->domtree,$currentTrack,"label",$v["LABEL"]);
                                $this->xmlObj->addChildWithValue($this->domtree,$currentTrack,"isGroup",$v["ISGROUP"]);
                                $this->xmlObj->addChildWithValue($this->domtree,$currentTrack,"isAll",$v["ISALL"]);
                                $this->xmlObj->addChildWithValue($this->domtree,$currentTrack,"parent",$v["PARENT"]);
                                $this->xmlObj->addChildWithValue($this->domtree,$currentTrack,"isChild",$v["ISCHILD"]);
			}
		}
	}

	/**
	This function is used to populate religion data for top search band
	* @access public
	* @see TopSearchBandConfig
	* @see NEWJS_RELIGION
	* @see FieldMap
	* @param String $notXml- if xml is required, pass the xml object (optional)
	* @param String $mobileApp- if is mobileApp
	* @return Array $output - Only when not $notXml
	*/
	public function populateReligion($notXml='',$mobileApp='')
	{
		if($mobileApp)          //Needed by IOS Team To mark if a particular religion in search band is present in registration or not
                {
			$nrObj = new NEWJS_RELIGION;
                       	$religionArr = $nrObj->getDATA();
                        unset($nrObj);
                        foreach($religionArr as $k=>$v)
                        {
                                $religionRegArr[$v["VALUE"]] = 1;
                        }
                        unset($religionArr);
                }

		$i=0;
                $output[$i]["VALUE"] = "";
              	$output[$i]["LABEL"] = "Select Religion";
		if($mobileApp)
			$output[$i]["IN_REG"] = "";
                $i++;
                $output[$i]["VALUE"] = TopSearchBandConfig::$doesNotMatterValue;
                $output[$i]["LABEL"] = TopSearchBandConfig::$doesNotMatterLabel;
		if($mobileApp)
			$output[$i]["IN_REG"] = "";
                $i++;

		$religion_arr = FieldMap::getFieldLabel("religion",1,1);
		if($religion_arr && is_array($religion_arr))
		{
			foreach($religion_arr as $k=>$v)
			{
				if($mobileApp)
					$output[$i]["VALUE"] = (string) $k;
				else
					$output[$i]["VALUE"] = $k;
                		$output[$i]["LABEL"] = $v;
				if($mobileApp)
				{
					if($religionRegArr[$k])
						$output[$i]["IN_REG"] = "Y";
					else
						$output[$i]["IN_REG"] = "";
				}
				$i++;
			}
		}

		if($notXml)
			return $output;
		else
		{
			$religionRoot = $this->xmlObj->addChildWithoutValue($this->domtree,$this->xmlRoot,"religion",1);
			foreach($output as $k=>$v)
			{
				$currentTrack = $this->xmlObj->addChildWithoutValue($this->domtree,$religionRoot,"data",1);
                                $this->xmlObj->addChildWithValue($this->domtree,$currentTrack,"value",$v["VALUE"]);
                                $this->xmlObj->addChildWithValue($this->domtree,$currentTrack,"label",$v["LABEL"]);
			}
		}
	}

	/**
	This function is used to populate mother tongue data for top search band
	* @access public
	* @see TopSearchBandConfig
	* @see NEWJS_MTONGUE
	* @see FieldMap
	* @param String $notXml- if xml is required, pass the xml object (optional)
	* @param String $mobileApp- if is mobileApp
	* @return Array $output - Only when not $notXml
	*/
	public function populateMtongue($notXml='',$mobileApp='')
	{
		if($mobileApp)          //Needed by IOS Team To mark if a particular mother tongue in search band is present in registration or not
                {
			
                        $nmObj = new NEWJS_MTONGUE;
                        $mtongueArr = $nmObj->getFullTableForRegistration();
                        unset($nmObj);
                        foreach($mtongueArr as $k=>$v)
                        {
                                $mtongueRegArr[$v["VALUE"]] = 1;
                        }
                        unset($mtongueArr);
                }

		$i=0;
		
		$output[$i]["VALUE"] = "";
		$output[$i]["LABEL"] = "Select Mother tongue";
		if($mobileApp)
		{
			$output[$i]["ISREGION"] = "";
			$output[$i]["IN_REG"] = "";
		}
			
                $i++;
	        
                $output[$i]["VALUE"] = TopSearchBandConfig::$doesNotMatterValue;
                $output[$i]["LABEL"] = TopSearchBandConfig::$doesNotMatterLabel;
		if($mobileApp)
		{
			$output[$i]["ISREGION"] = "";
			$output[$i]["IN_REG"] = "";
		}
		$i++;

		$mtongue_arr = FieldMap::getFieldLabel("mtongue_region",1,1);
		if($mtongue_arr && is_array($mtongue_arr))
		{
			foreach($mtongue_arr as $k=>$v)
			{
				if($k==5)
					continue;
				else
				{
					
					$output[$i]["VALUE"] = $v;
					if(FieldMap::getFieldLabel("mtongue_region_label",$k)=="Others")
						$output[$i]["LABEL"] = FieldMap::getFieldLabel("mtongue_region_label",$k);
					else
						$output[$i]["LABEL"] = "All ".FieldMap::getFieldLabel("mtongue_region_label",$k);
					$output[$i]["ISREGION"] = "Y";
					if($mobileApp)
					{
						if($mtongueRegArr[$v])
							$output[$i]["IN_REG"] = "Y";
						else
							$output[$i]["IN_REG"] = "";
					}
					$i++;	
					
					if($k==4)
					{
						$output[$i]["VALUE"] = $mtongue_arr[5];
						$output[$i]["LABEL"] = TopSearchBandConfig::$allHindiLabel;
						if($mobileApp)
						{
							$output[$i]["ISREGION"] = "";
							if($mtongueRegArr[$mtongue_arr[5]])
                                                        	$output[$i]["IN_REG"] = "Y";
                                                	else
                                                        	$output[$i]["IN_REG"] = "";
						}
						$i++;
					}

					$tempArr = explode(",",$v);
					foreach($tempArr as $kk=>$vv)
					{
						$output[$i]["VALUE"] = $vv;
						if($notXml)
                                                	$output[$i]["LABEL"] = str_replace("&amp;","&",FieldMap::getFieldLabel("community_small",$vv));
						else
							$output[$i]["LABEL"] = FieldMap::getFieldLabel("community_small",$vv);
						if($mobileApp)
						{
							$output[$i]["ISREGION"] = "";
							if($mtongueRegArr[$vv])
                                                                $output[$i]["IN_REG"] = "Y";
                                                        else
                                                                $output[$i]["IN_REG"] = "";
						}
                                                $i++;
					}
				}
			}
		}
		
		if($notXml)
                        return $output;
                else
                {
			$mtongueRoot = $this->xmlObj->addChildWithoutValue($this->domtree,$this->xmlRoot,"mtongue",1);
                        foreach($output as $k=>$v)
                        {
                                $currentTrack = $this->xmlObj->addChildWithoutValue($this->domtree,$mtongueRoot,"data",1);
                                $this->xmlObj->addChildWithValue($this->domtree,$currentTrack,"value",$v["VALUE"]);
                                $this->xmlObj->addChildWithValue($this->domtree,$currentTrack,"label",$v["LABEL"]);
                                $this->xmlObj->addChildWithValue($this->domtree,$currentTrack,"isRegion",$v["ISREGION"]);
                        }
                }
	}
	
	
	/**
	* This function is used to populate mother tongue data for top search band in jsms
	* @access public
	* @see FieldMap
	* @see SearchFieldMapLib
	* @return Array $output 
	*/
	public function populateMtongueMobile()
	{
		$i=0;
		$mtongue_arr = SearchFieldMapLib::getFieldLabel("mtongue",1,1);
		if($mtongue_arr && is_array($mtongue_arr))
		{
			foreach($mtongue_arr as $k=>$v)
			{
									
					if($k==1)
					{
						$output[$i]["VALUE"] = $mtongue_arr[1];
						$output[$i]["LABEL"] = "Hindi";
						$output[$i]["ISGROUP"] = "";
						$output[$i]["IN_GROUP"] = "";
						$output[$i]["IS_GROUP_HEADING"]="Y";
						$output[$i]["GROUP"] = $mtongue_arr[1];
						$i++;
						$output[$i]["VALUE"] = $mtongue_arr[1];
						$output[$i]["LABEL"] = "Hindi - All";
						$output[$i]["ISGROUP"] = "Y";
						$output[$i]["IN_GROUP"] = "";
						$output[$i]["IS_GROUP_HEADING"]="";
						$output[$i]["GROUP"] = $mtongue_arr[1];
						$i++;
					}

					$tempArr = explode(",",$v);
					foreach($tempArr as $kk=>$vv)
					{
						$output[$i]["VALUE"] = $vv;
						$output[$i]["LABEL"] = str_replace("&amp;","&",FieldMap::getFieldLabel("community_small",$vv));
						$output[$i]["ISGROUP"] = "";
						if($k==1)
						{
							$output[$i]["IN_GROUP"] = "Y";
							$output[$i]["GROUP"] = $mtongue_arr[1];
						}
                                                else
                                                {
                                                        $output[$i]["IN_GROUP"] = "";
                                                        $output[$i]["GROUP"] = "";
                                                 }
                                               
						$output[$i]["IS_GROUP_HEADING"]="";
						
                                                $i++;
					}
				
			}
		}
		
		return $output;
                
	}

	/**
	* This function is used to populate country/city data for top search band
	* @access public
	* @see TopSearchBandConfig
	* @see NEWJS_MTONGUE
	* @see FieldMap
	* @param String $notXml- if xml is required, pass the xml object (optional)
	* @param String $mobileApp- if is mobileApp
	* @return Array $output
	*/
	public function populateCity_Country($notXml='',$mobileApp='')
	{
		$i=0;
                $output[$i]["VALUE"] = "";
                $output[$i]["LABEL"] = "Select City/Country";
                $i++;
                $output[$i]["VALUE"] = TopSearchBandConfig::$doesNotMatterValue;
                $output[$i]["LABEL"] = TopSearchBandConfig::$doesNotMatterLabel;
                $i++;

		foreach(TopSearchBandConfig::$countries as $k=>$v)
		{
			if($mobileApp)
				$output[$i]["VALUE"] = (string) $v;
			else
				$output[$i]["VALUE"] = $v;
			if($v==51)
				$output[$i]["LABEL"] = "All ".FieldMap::getFieldLabel("country",$v);
			else
				$output[$i]["LABEL"] = FieldMap::getFieldLabel("country",$v);
			$i++;
		}

		foreach(TopSearchBandConfig::$cities as $k=>$v)
                {
			if($v=="DE00")
			{
				$output[$i]["VALUE"] = implode(",",FieldMap::getFieldLabel("delhiNcrCities",1,1));
				$output[$i]["LABEL"] = TopSearchBandConfig::$ncrLabel;
			}
			elseif($v=="MH04")
			{
				$output[$i]["VALUE"] = TopSearchBandConfig::$mumbaiRegion;
				$output[$i]["LABEL"] = TopSearchBandConfig::$mumbaiRegionLabel;
			}
			else
			{
				$output[$i]["VALUE"] = $v;
				$output[$i]["LABEL"] = FieldMap::getFieldLabel("city",$v);
			}
			$i++;
		}
                foreach(FieldMap::getFieldLabel("state_india","","true") as $k=>$v)
                {
                        $output[$i]["VALUE"] = $k;
                        $output[$i]["LABEL"] = $v;
                        $i++;
                }
                $indiaCityString = FieldMap::getFieldLabel("country_city","","true")["51"];
                $indiaCityArr = explode(",",$indiaCityString);
                foreach($indiaCityArr as $k=>$v)
                {
			if(!(in_array($v,TopSearchBandConfig::$citiesExcludeApp) && $mobileApp))
	                {
				$output[$i]["VALUE"] = $v;
				$output[$i]["LABEL"] = FieldMap::getFieldLabel("city",$v);
				$i++;
			}
	                
                }
                

		if($notXml)
                        return $output;
                else
                {
			$locationRoot = $this->xmlObj->addChildWithoutValue($this->domtree,$this->xmlRoot,"location",1);
                        foreach($output as $k=>$v)
                        {
				$currentTrack = $this->xmlObj->addChildWithoutValue($this->domtree,$locationRoot,"data",1);
				$this->xmlObj->addChildWithValue($this->domtree,$currentTrack,"value",$v["VALUE"]);
				$this->xmlObj->addChildWithValue($this->domtree,$currentTrack,"label",$v["LABEL"]);
                        }
                }
	}
        public function populateManglik(){
                $i=0;
		foreach(FieldMap::getFieldLabel("manglik_label",'',1) as $k=>$v)
                {
                        if($k !== 'D'){
                                $output[$i]["VALUE"] = $k;
                                $output[$i]["LABEL"] = $v;
                                $i++;
                        }
		}
                return $output;
        }
	/**
	* This function is used to populate country/city data for top search band for jsms
	* @access public
	* @see TopSearchBandConfig
	* @see FieldMap
	* @return Array $output
	*/
	public function populateCityCountryJSMS()
	{
		$i=0;
		foreach(TopSearchBandConfig::$countries as $k=>$v)
		{
			$output[$i]["VALUE"] = (string) $v;
			
                        $output[$i]["LABEL"] = FieldMap::getFieldLabel("country",$v);
			$output[$i]["ISGROUP"] = "";
			$output[$i]["IN_GROUP"] = "" ;
			$output[$i]["IS_GROUP_HEADING"] ="";
			
			$i++;
		}
		
		foreach(FieldMap::getFieldLabel("country",'',1) as $s=>$l)
		{
                        $output[$i]["VALUE"] = $s;
                        $output[$i]["LABEL"] = $l;
                        $output[$i]["ISGROUP"] = "";
                        $output[$i]["IN_GROUP"] = "" ;
                        $output[$i]["IS_GROUP_HEADING"] ="";
                        $i++;
		}
		return $output;
	}
	public function populateCitiesJSMS()
	{
		$i=0;
                $output[$i]["VALUE"] = implode(",",FieldMap::getFieldLabel("delhiNcrCities",1,1));
                $output[$i]["LABEL"] = TopSearchBandConfig::$ncrLabel;
                $i++;
                
                $output[$i]["VALUE"] = TopSearchBandConfig::$mumbaiRegion;
                $output[$i]["LABEL"] = TopSearchBandConfig::$mumbaiRegionLabel;
                $i++;
                
                foreach(TopSearchBandConfig::$topCities as $k=>$v){
                        $output[$i]["VALUE"] = $v;
                        $output[$i]["LABEL"] = FieldMap::getFieldLabel("city_india",$v);
                        $i++;
                }
                $output[$i]["VALUE"] = "";
                $output[$i]["LABEL"] = "States";
                $output[$i]["IS_LIST_HEADING"] ="Y";
                $i++;
                foreach(FieldMap::getFieldLabel("state_india",1,1) as $s=>$l)
		{
                        $output[$i]["VALUE"] = $s;
                        $output[$i]["LABEL"] = $l;
                        $i++;
                }
                $output[$i]["VALUE"] = "";
                $output[$i]["LABEL"] = "Cities";
                $output[$i]["IS_LIST_HEADING"] ="Y";
                $i++;
		foreach(FieldMap::getFieldLabel("city_india",1,1) as $s=>$l)
		{
                                $output[$i]["VALUE"] = $s;
                                $output[$i]["LABEL"] = $l;
                                $i++;
		}
		return $output;
	}

	/**
	*This function is used to populate religion/caste dependency data for top search band
	* @access public
	* @see TopSearchBandConfig
	* @param String $notXml- if xml is required, pass the xml object (optional)
	* @see FieldMap
	* @see NEWJS_CASTE
	* @return Array $output
	*/
	
	public function populateReligionCasteDependency($notXml='')
	{
		$religion_arr = FieldMap::getFieldLabel("religion",1,1);
		$religion_arr[0] = '';
		$i=0;
	
		$casteObj = new NEWJS_CASTE;
		$output = $casteObj->getTopSearchBandReligionCasteData();
		$caste_arr = $casteObj->getTopSearchBandCasteData();
                unset($casteObj);
		if($output && is_array($output))
		{
			foreach($output as $k=>$v)
			{
				$religion_caste_arr[$v["PARENT"]] = $v["VALUE"];
			}
		}
		unset($output);
	
		foreach($religion_arr as $k=>$v)
		{
			$output[$i]["RELIGION_VALUE"] = $k;
			$casteDropdownStr = '';
			if(in_array($k,TopSearchBandConfig::$sectLabelReligions))
				$casteDropdownStr = $casteDropdownStr."<option value = ''>Select Sect</option>";
			else
				$casteDropdownStr = $casteDropdownStr."<option value = ''>Select Caste</option>";

			$casteDropdownStr = $casteDropdownStr."<option value = '".TopSearchBandConfig::$doesNotMatterValue."'>".TopSearchBandConfig::$doesNotMatterLabel."</option>";
		
			if($k==0)
			{
				if($caste_arr && is_array($caste_arr))
				{
					foreach($caste_arr as $kk=>$vv)
					{
						if($vv["ISALL"]=="Y")
						{
							$casteDropdownStr = $casteDropdownStr."<option value = '' disabled = 'yes'></option>";
							if(strstr($vv["LABEL"],":"))
								$casteDropdownStr = $casteDropdownStr."<option value = '".$vv["VALUE"]."' style = 'background-color:#FFD84F'>".trim(ltrim(strstr($vv["LABEL"],":"),":"))."</option>";
							else
								$casteDropdownStr = $casteDropdownStr."<option value = '".$vv["VALUE"]."' style = 'background-color:#FFD84F'>".$vv["LABEL"]."</option>";
						}
						elseif($vv["ISGROUP"]=="Y")
						{
							if(strstr($vv["LABEL"],":"))
								$casteDropdownStr = $casteDropdownStr."<option value = '".$vv["VALUE"]."' style = 'color:#E06400'>".trim(ltrim(strstr($vv["LABEL"],":"),":"))." - All</option>";
							else
								$casteDropdownStr = $casteDropdownStr."<option value = '".$vv["VALUE"]."' style = 'color:#E06400'>".$vv["LABEL"]." - All</option>";

							$caste_in_group_arr = explode(",",FieldMap::getFieldLabel("caste_group_array",$vv["VALUE"]));
							foreach($caste_in_group_arr as $kkk=>$vvv)
							{
								if(strstr(FieldMap::getFieldLabel("caste",$vvv),":") && strstr(FieldMap::getFieldLabel("caste",$vvv),"Others")===false)
									$casteDropdownStr = $casteDropdownStr."<option value = '".$vvv."' style = 'padding-left:25px;'>- ".trim(ltrim(strstr(FieldMap::getFieldLabel("caste",$vvv),":"),":"))."</option>";
								else
									$casteDropdownStr = $casteDropdownStr."<option value = '".$vvv."' style = 'padding-left:25px;'>- ".FieldMap::getFieldLabel("caste",$vvv)."</option>";
							}
						}
						else
						{
							if(strstr($vv["LABEL"],":"))
								$casteDropdownStr = $casteDropdownStr."<option value = '".$vv["VALUE"]."'>".trim(ltrim(strstr($vv["LABEL"],":"),":"))."</option>";
							else
								$casteDropdownStr = $casteDropdownStr."<option value = '".$vv["VALUE"]."'>".$vv["LABEL"]."</option>";
						}
					}
				}
                		unset($caste_arr);
			}
			else
			{
				if($religion_caste_arr && is_array($religion_caste_arr) && $religion_caste_arr[$k])
				{
					$casteArr = explode(",",$religion_caste_arr[$k]);
					{
						foreach($casteArr as $kk=>$vv)
						{
							if(FieldMap::getFieldLabel("caste_group_array",$vv))
							{
								if(strstr(FieldMap::getFieldLabel("caste",$vv),":"))
									$casteDropdownStr = $casteDropdownStr."<option value = '".$vv."' style = 'color:#E06400'>".trim(ltrim(strstr(FieldMap::getFieldLabel("caste",$vv),":"),":"))." - All</option>";
								else
									$casteDropdownStr = $casteDropdownStr."<option value = '".$vv."' style = 'color:#E06400'>".FieldMap::getFieldLabel("caste",$vv)." - All</option>";

								$caste_in_group_arr = explode(",",FieldMap::getFieldLabel("caste_group_array",$vv));
								foreach($caste_in_group_arr as $kkk=>$vvv)
								{
									if(strstr(FieldMap::getFieldLabel("caste",$vvv),":") && strstr(FieldMap::getFieldLabel("caste",$vvv),"Others")===false)
									{
										$casteDropdownStr = $casteDropdownStr."<option value = '".$vvv."' style = 'padding-left:25px;'>- ".trim(ltrim(strstr(FieldMap::getFieldLabel("caste",$vvv),":"),":"))."</option>";	
									}
									else
									{
										$casteDropdownStr = $casteDropdownStr."<option value = '".$vvv."' style = 'padding-left:25px;'>- ".FieldMap::getFieldLabel("caste",$vvv)."</option>";
									}
								}
									
							}
							else
							{
								if(strstr(FieldMap::getFieldLabel("caste",$vv),":"))
									$casteDropdownStr = $casteDropdownStr."<option value = '".$vv."'>".trim(ltrim(strstr(FieldMap::getFieldLabel("caste",$vv),":"),":"))."</option>";
								else
									$casteDropdownStr = $casteDropdownStr."<option value = '".$vv."'>".FieldMap::getFieldLabel("caste",$vv)."</option>";
							}
						}
					}
					unset($casteArr);
				}
			}
			$output[$i]["CASTE_STRING"]= $casteDropdownStr;
			$i++;
		}

		if($notXml)
                        return $output;
                else
                {
			$religionCasteRoot = $this->xmlObj->addChildWithoutValue($this->domtree,$this->xmlRoot,"religion_caste",1);
                        foreach($output as $k=>$v)
                        {
				$currentTrack = $this->xmlObj->addChildWithoutValue($this->domtree,$religionCasteRoot,"data",1);
				$this->xmlObj->addChildWithValue($this->domtree,$currentTrack,"religionValue",$v["RELIGION_VALUE"]);
				$this->xmlObj->addChildWithValue($this->domtree,$currentTrack,"casteString",$v["CASTE_STRING"]);
                        }
                }
	}

	/**
	* This function is used to populate religion/caste dependency data for top search band for app
	* @access public
	* @see TopSearchBandConfig
	* @param String $notXml- if xml is required, pass the xml object (optional)
	* @see FieldMap
	* @see NEWJS_CASTE
	* @return Array $output
	*/
	public function populateReligionCasteDependencyApp($notXml='')
	{
		$religion_arr = FieldMap::getFieldLabel("religion",1,1);
		$religion_arr[0] = '';
		$i=0;
	
		$casteObj = new NEWJS_CASTE;
		$output = $casteObj->getTopSearchBandReligionCasteData();
		$caste_arr = $casteObj->getTopSearchBandCasteData();
                unset($casteObj);
		if($output && is_array($output))
		{
			foreach($output as $k=>$v)
			{
				$religion_caste_arr[$v["PARENT"]] = $v["VALUE"];
			}
		}
		unset($output);
	
		foreach($religion_arr as $k=>$v)
		{
			$counter = 0;
			$output[$i]["RELIGION_VALUE"] = $k;

			if(in_array($k,TopSearchBandConfig::$sectLabelReligions))
			{
				$casteDropdownStr[$counter]["VALUE"] = "";
				$casteDropdownStr[$counter]["LABEL"] = "Select Sect";
				$casteDropdownStr[$counter]["ISALL"] = "";
				$casteDropdownStr[$counter]["ISGROUP"] = "";
				$casteDropdownStr[$counter]["ISCHILD"] = "";
			}
			else
			{
				$casteDropdownStr[$counter]["VALUE"] = "";
				$casteDropdownStr[$counter]["LABEL"] = "Select Caste";
				$casteDropdownStr[$counter]["ISALL"] = "";
				$casteDropdownStr[$counter]["ISGROUP"] = "";
				$casteDropdownStr[$counter]["ISCHILD"] = "";
			}
			$counter++;

			$casteDropdownStr[$counter]["VALUE"] = TopSearchBandConfig::$doesNotMatterValue;
                      	$casteDropdownStr[$counter]["LABEL"] = TopSearchBandConfig::$doesNotMatterLabel;
			$casteDropdownStr[$counter]["ISALL"] = "";
			$casteDropdownStr[$counter]["ISGROUP"] = "";
			$casteDropdownStr[$counter]["ISCHILD"] = "";
			$counter++;
	
			if($k==0)
			{
				if($caste_arr && is_array($caste_arr))
				{
					foreach($caste_arr as $kk=>$vv)
					{
						if($vv["ISALL"]=="Y")
						{
							$casteDropdownStr[$counter]["VALUE"] = $vv["VALUE"];
							if(strstr($vv["LABEL"],":"))
								$casteDropdownStr[$counter]["LABEL"] = trim(ltrim(strstr($vv["LABEL"],":"),":"));
							else
								$casteDropdownStr[$counter]["LABEL"] = $vv["LABEL"];
							$casteDropdownStr[$counter]["ISALL"] = "Y";
							$casteDropdownStr[$counter]["ISGROUP"] = "";
							$casteDropdownStr[$counter]["ISCHILD"] = "";
							$counter++;
						}
						elseif($vv["ISGROUP"]=="Y")
						{
							$casteDropdownStr[$counter]["VALUE"] = $vv["VALUE"];
							if(strstr($vv["LABEL"],":"))
								$casteDropdownStr[$counter]["LABEL"] = trim(ltrim(strstr($vv["LABEL"],":"),":"))." - All";
							else
								$casteDropdownStr[$counter]["LABEL"] = $vv["LABEL"]." - All";
							$casteDropdownStr[$counter]["ISALL"] = "";
							$casteDropdownStr[$counter]["ISGROUP"] = "Y";
							$casteDropdownStr[$counter]["ISCHILD"] = "";
							$counter++;

							$caste_in_group_arr = explode(",",FieldMap::getFieldLabel("caste_group_array",$vv["VALUE"]));
							foreach($caste_in_group_arr as $kkk=>$vvv)
							{
								$casteDropdownStr[$counter]["VALUE"] = $vvv;
								if(strstr(FieldMap::getFieldLabel("caste",$vvv),":") && strstr(FieldMap::getFieldLabel("caste",$vvv),"Others")===false)
									$casteDropdownStr[$counter]["LABEL"] = "- ".trim(ltrim(strstr(FieldMap::getFieldLabel("caste",$vvv),":"),":"));
								else
									$casteDropdownStr[$counter]["LABEL"] = "- ".FieldMap::getFieldLabel("caste",$vvv);
								$casteDropdownStr[$counter]["ISALL"] = "";
								$casteDropdownStr[$counter]["ISGROUP"] = "";
								$casteDropdownStr[$counter]["ISCHILD"] = "Y";
								$counter++;
							}
						}
						else
						{
							$casteDropdownStr[$counter]["VALUE"] = $vv["VALUE"];
							if(strstr($vv["LABEL"],":"))
								$casteDropdownStr[$counter]["LABEL"] = trim(ltrim(strstr($vv["LABEL"],":"),":"));
							else
								$casteDropdownStr[$counter]["LABEL"] = $vv["LABEL"];
							$casteDropdownStr[$counter]["ISALL"] = "";
							$casteDropdownStr[$counter]["ISGROUP"] = "";
							$casteDropdownStr[$counter]["ISCHILD"] = "";
							$counter++;
						}
					}
				}
                		unset($caste_arr);
			}
			else
			{
				if($religion_caste_arr && is_array($religion_caste_arr) && $religion_caste_arr[$k])
				{
					$casteArr = explode(",",$religion_caste_arr[$k]);
					{
						foreach($casteArr as $kk=>$vv)
						{
							if(FieldMap::getFieldLabel("caste_group_array",$vv))
							{
								$casteDropdownStr[$counter]["VALUE"] = $vv;
								if(strstr(FieldMap::getFieldLabel("caste",$vv),":"))
									$casteDropdownStr[$counter]["LABEL"] = trim(ltrim(strstr(FieldMap::getFieldLabel("caste",$vv),":"),":"))." - All";
								else
									$casteDropdownStr[$counter]["LABEL"] = FieldMap::getFieldLabel("caste",$vv)." - All";
								$casteDropdownStr[$counter]["ISALL"] = "";
								$casteDropdownStr[$counter]["ISGROUP"] = "Y";
								$casteDropdownStr[$counter]["ISCHILD"] = "";
                                                        	$counter++;

								$caste_in_group_arr = explode(",",FieldMap::getFieldLabel("caste_group_array",$vv));
								foreach($caste_in_group_arr as $kkk=>$vvv)
								{
									$casteDropdownStr[$counter]["VALUE"] = $vvv;
									if(strstr(FieldMap::getFieldLabel("caste",$vvv),":") && strstr(FieldMap::getFieldLabel("caste",$vvv),"Others")===false)
										$casteDropdownStr[$counter]["LABEL"] = trim(ltrim(strstr(FieldMap::getFieldLabel("caste",$vvv),":"),":"));
									else
										$casteDropdownStr[$counter]["LABEL"] = FieldMap::getFieldLabel("caste",$vvv);
									$casteDropdownStr[$counter]["ISALL"] = "";
									$casteDropdownStr[$counter]["ISGROUP"] = "";
									$casteDropdownStr[$counter]["ISCHILD"] = "Y";
                                                                	$counter++;
								}
									
							}
							else
							{
								$casteDropdownStr[$counter]["VALUE"] = $vv;
								if(strstr(FieldMap::getFieldLabel("caste",$vv),":"))
									$casteDropdownStr[$counter]["LABEL"] = trim(ltrim(strstr(FieldMap::getFieldLabel("caste",$vv),":"),":"));
								else
									$casteDropdownStr[$counter]["LABEL"] = FieldMap::getFieldLabel("caste",$vv);
								$casteDropdownStr[$counter]["ISALL"] = "";
								$casteDropdownStr[$counter]["ISGROUP"] = "";
								$casteDropdownStr[$counter]["ISCHILD"] = "";
                                                                $counter++;
							}
						}
					}
					unset($casteArr);
				}
			}
			$output[$i]["CASTE_STRING"]= $casteDropdownStr;
			$i++;
			unset($casteDropdownStr);
		}

		if($notXml)
                        return $output;
                else
                {
			$religionCasteRoot = $this->xmlObj->addChildWithoutValue($this->domtree,$this->xmlRoot,"religion_caste",1);
                        foreach($output as $k=>$v)
                        {
				$currentTrack = $this->xmlObj->addChildWithoutValue($this->domtree,$religionCasteRoot,"data",1);
				$this->xmlObj->addChildWithValue($this->domtree,$currentTrack,"religionValue",$v["RELIGION_VALUE"]);
				$this->xmlObj->addChildWithValue($this->domtree,$currentTrack,"casteString",$v["CASTE_STRING"]);
                        }
                }
	}
	
	
	/**
	* This function is used to populate religion data for top search band for jsms
	* @access public
	* @see TopSearchBandConfig
	* @see FieldMap
	* @see SearchFieldMapLib
	* @return Array $religionArr
	*/
	public function populateReligionJSMS()
	{
		$religion_arr = SearchFieldMapLib::getFieldLabel("religion",1,1);
		$output = FieldMap::getFieldLabel("religion_caste",1,1);
		
		$i =0;
		foreach($religion_arr as $k=>$v)
		{
			$religionArr[$i]["VALUE"] = $k;
			$religionArr[$i]["LABEL"] = $v;
			if(array_key_exists($k,$output))
			{
				$religionArr[$i]["HAS_DEPENDENT"]="Y";
				$religionArr[$i]["IS_GROUP_HEADING"]="Y";
			}
			else
				$religionArr[$i]["HAS_DEPENDENT"]="";
			 $i++;
		}
		return $religionArr;
	}
	
	/**
	* This function is used to populate religion/caste dependency data for top search band for jsms
	* @access public
	* @see TopSearchBandConfig
	* @see FieldMap
	* @see NEWJS_CASTE
	* @return Array $output
	*/
	public function populateCasteJSMS()
	{
		
		$religion_arr = FieldMap::getFieldLabel("religion_caste",1,1);
		
		$i=0;
	
		$casteObj = new NEWJS_CASTE;
		$output = $casteObj->getTopSearchBandReligionCasteData();
		unset($casteObj);
		if($output && is_array($output))
		{
			foreach($output as $k=>$v)
			{
				$religion_caste_arr[$v["PARENT"]] = $v["VALUE"];
			}
		}
		unset($output);
		
		foreach($religion_arr as $k=>$v)
		{
			$counter = 0;
			
				if($religion_caste_arr && is_array($religion_caste_arr) && $religion_caste_arr[$k])
				{
					$casteArr = explode(",",$religion_caste_arr[$k]);
					if(is_array($casteArr))
					{
						if($k==1)
						{
							$casteDropdownStr[$counter]["VALUE"] = "14";
									
						}
						else               
						{
							if(MobileCommon::isDesktop())
								$casteDropdownStr[$counter]["VALUE"] = TopSearchBandConfig::$religionAllCasteMapping[$k];
							else
								$casteDropdownStr[$counter]["VALUE"] = TopSearchBandConfig::$doesNotMatterValue;
						}
						$casteDropdownStr[$counter]["LABEL"] = FieldMap::getFieldLabel("religion",$k)." - All";
						$casteDropdownStr[$counter]["IS_GROUP_HEADING"] = "";
						$casteDropdownStr[$counter]["ISGROUP"] = "";
						$casteDropdownStr[$counter]["IN_GROUP"] = "";
						$casteDropdownStr[$counter]["GROUP"] = "";
                                                $counter++;
						
						foreach($casteArr as $kk=>$vv)
						{
							if(FieldMap::getFieldLabel("caste_group_array",$vv))
							{
								$casteDropdownStr[$counter]["VALUE"] = $vv;
								if(strstr(FieldMap::getFieldLabel("caste",$vv),":"))
									$casteDropdownStr[$counter]["LABEL"] = trim(ltrim(strstr(FieldMap::getFieldLabel("caste",$vv),":"),":"))." - All";
								else
									$casteDropdownStr[$counter]["LABEL"] = FieldMap::getFieldLabel("caste",$vv)." - All";
								$casteDropdownStr[$counter]["IS_GROUP_HEADING"] = "Y";
								$casteDropdownStr[$counter]["ISGROUP"] = "Y";
								$casteDropdownStr[$counter]["IN_GROUP"] = "";
								$casteDropdownStr[$counter]["GROUP"] = $vv;
                                                        	$counter++;

								$caste_in_group_arr = explode(",",SearchFieldMapLib::getFieldLabel("caste_group_array",$vv));
								
								foreach($caste_in_group_arr as $kkk=>$vvv)
								{
									$casteDropdownStr[$counter]["VALUE"] = $vvv;
									if(strstr(FieldMap::getFieldLabel("caste",$vvv),":") && strstr(FieldMap::getFieldLabel("caste",$vvv),"Others")===false)
										$casteDropdownStr[$counter]["LABEL"] = trim(ltrim(strstr(FieldMap::getFieldLabel("caste",$vvv),":"),":"));
									else
										$casteDropdownStr[$counter]["LABEL"] = FieldMap::getFieldLabel("caste",$vvv);
									$casteDropdownStr[$counter]["IS_GROUP_HEADING"] = "";
									$casteDropdownStr[$counter]["ISGROUP"] = "";
									$casteDropdownStr[$counter]["IN_GROUP"] = "Y";
									$casteDropdownStr[$counter]["GROUP"] = $vv;
									
                                                                	$counter++;
								}
									
							}
							else
							{
								$casteDropdownStr[$counter]["VALUE"] = $vv;
								if(strstr(FieldMap::getFieldLabel("caste",$vv),":"))
									$casteDropdownStr[$counter]["LABEL"] = trim(ltrim(strstr(FieldMap::getFieldLabel("caste",$vv),":"),":"));
								else
									$casteDropdownStr[$counter]["LABEL"] = FieldMap::getFieldLabel("caste",$vv);
								$casteDropdownStr[$counter]["IS_GROUP_HEADING"] = "";
								$casteDropdownStr[$counter]["ISGROUP"] = "";
								$casteDropdownStr[$counter]["IN_GROUP"] = "";
								$casteDropdownStr[$counter]["GROUP"] = "";
                                                                $counter++;
							}
						}
					}
					unset($casteArr);
				}
			
			$output[$k]= $casteDropdownStr;
			$i++;
			unset($casteDropdownStr);
		}
		
		return $output;
	}

	/**
	* This function is used to populate hight data for top search band
	* @access public
	* @see FieldMap
	* @param String $notXml- if xml is required, pass the xml object (optional)
	* @param String $jsms- if is jsms
	* @return Array $output
	*/
	
	public function populateHeight($notXml='',$jsms='',$mobileApp='')
	{
		$i=0;
		if(!$jsms && !$mobileApp)
		{
			$output[$i]["VALUE"] = "";
			$output[$i]["LABEL"] = "Select Height";
			$i++;
		}

		$height_arr = FieldMap::getFieldLabel("height_without_meters",1,1);
		foreach($height_arr as $k=>$v)
		{
			if($mobileApp)
                                $output[$i]["VALUE"] = (string) $k;
                        else
                                $output[$i]["VALUE"] = $k;
			$output[$i]["LABEL"] = str_replace("&quot;","\"",$v);
			if($jsms)
				$output[$i]["IN_GROUP"] = "";
			$i++;
		}
		unset($height_arr);

		if($notXml)
			return $output;
		else
		{
			$currentTrack = $this->xmlObj->addChildWithoutValue($this->domtree,$this->xmlRoot,"height",1);
			foreach($output as $k=>$v)
			{
				$this->xmlObj->addChildWithValue($this->domtree,$currentTrack,"value",$v["VALUE"]);
				$this->xmlObj->addChildWithValue($this->domtree,$currentTrack,"label",str_replace("&quot;","\"",$v["LABEL"]));
			}
		}
	}
        public function populateOccupationForJSMS(){
                $i=0;
                $occGroupingArr = FieldMap::getFieldLabel("occupation_grouping",1,1);
                $occupationData = FieldMap::getFieldLabel("occupation",1,1);
                foreach($occGroupingArr as $k=>$v)
                {
                        $output[$i]["VALUE"] = $k;
                        $output[$i]["LABEL"] = $v;
                        $output[$i]["ISGROUP"] = "";
                        $output[$i]["IN_GROUP"] = "" ;
                        $output[$i]["IS_GROUP_HEADING"] ="Y";
                        $output[$i]["GROUP"] =$k;
                        $i++;
                        $occMappingArray = FieldMap::getFieldLabel("occupation_grouping_mapping_to_occupation",$k);
                        $output[$i]["VALUE"] = $occMappingArray;
                        $output[$i]["LABEL"] = $v. " - All";
                        $output[$i]["ISGROUP"] = "Y";
                        $output[$i]["IN_GROUP"] = "" ;
                        $output[$i]["IS_GROUP_HEADING"] ="";
                        $output[$i]["GROUP"] =$k;
                        $i++;
                        $occupation = explode(',',$occMappingArray);
                        foreach($occupation as $key=>$value){
                          $output[$i]["VALUE"] = $value;
                          $output[$i]["LABEL"] = $occupationData[$value];
                          $output[$i]["ISGROUP"] = "";
                          $output[$i]["IN_GROUP"] = "Y" ;
                          $output[$i]["IS_GROUP_HEADING"] ="";
                          $output[$i]["GROUP"] =$k;
                          $i++;
                        }
                }
                return($output);
        }
        public function populateEducationForJSMS(){
                $i=0;
                $occGroupingArr = FieldMap::getFieldLabel("education_grouping",1,1);
                $occupationData = FieldMap::getFieldLabel("education",1,1);
                foreach($occGroupingArr as $k=>$v)
                {
                        $output[$i]["VALUE"] = $k;
                        $output[$i]["LABEL"] = $v;
                        $output[$i]["ISGROUP"] = "";
                        $output[$i]["IN_GROUP"] = "" ;
                        $output[$i]["IS_GROUP_HEADING"] ="Y";
                        $output[$i]["GROUP"] =$k;
                        $i++;
                        $occMappingArray = FieldMap::getFieldLabel("education_grouping_mapping_to_edu_level_new",$k);
                        $output[$i]["VALUE"] = $occMappingArray;
                        $output[$i]["LABEL"] = $v. " - All";
                        $output[$i]["ISGROUP"] = "Y";
                        $output[$i]["IN_GROUP"] = "" ;
                        $output[$i]["IS_GROUP_HEADING"] ="";
                        $output[$i]["GROUP"] =$k;
                        $i++;
                        $occupation = explode(',',$occMappingArray);
                        foreach($occupation as $key=>$value){
                          $output[$i]["VALUE"] = $value;
                          $output[$i]["LABEL"] = $occupationData[$value];
                          $output[$i]["ISGROUP"] = "";
                          $output[$i]["IN_GROUP"] = "Y" ;
                          $output[$i]["IS_GROUP_HEADING"] ="";
                          $output[$i]["GROUP"] =$k;
                          $i++;
                        }
                }
                return($output);
        }
	/**
	* This function is used to populate occupation data for top search band
	* @access public
	* @see FieldMap
	* @param String $notXml- if xml is required, pass the xml object (optional)
	* @return Array $output
	*/
	public function populateOccupation($notXml='')
	{
		$i=0;
                $output[$i]["VALUE"] = "";
                $output[$i]["LABEL"] = "Select Occupation";
                $i++;

		$occ_arr = FieldMap::getFieldLabel("occupation_grouping",1,1);

                foreach($occ_arr as $k=>$v)
                {
                        $output[$i]["VALUE"] = $k;
                        $output[$i]["LABEL"] = $v;
                        $i++;
                }
                unset($occ_arr);

		if($notXml)
                        return $output;
                else
                {
                        $currentTrack = $this->xmlObj->addChildWithoutValue($this->domtree,$this->xmlRoot,"occupation",1);
                        foreach($output as $k=>$v)
                        {
                                $this->xmlObj->addChildWithValue($this->domtree,$currentTrack,"value",$v["VALUE"]);
                                $this->xmlObj->addChildWithValue($this->domtree,$currentTrack,"label",$v["LABEL"]);
                        }
                }
	}

	/**
	* This function is used to populate education data for top search band
	* @access public
	* @see FieldMap
	* @param String $notXml- if xml is required, pass the xml object (optional)
	* @return Array $output
	*/
	public function populateEducation($notXml='')
	{
		$i=0;
                $output[$i]["VALUE"] = "";
                $output[$i]["LABEL"] = "Select Education";
                $i++;

		$occ_arr = FieldMap::getFieldLabel("education_grouping",1,1);

                foreach($occ_arr as $k=>$v)
                {
                        $output[$i]["VALUE"] = $k;
                        $output[$i]["LABEL"] = $v;
                        $i++;
                }
                unset($occ_arr);

		if($notXml)
                        return $output;
                else
                {
                        $currentTrack = $this->xmlObj->addChildWithoutValue($this->domtree,$this->xmlRoot,"education",1);
                        foreach($output as $k=>$v)
                        {
                                $this->xmlObj->addChildWithValue($this->domtree,$currentTrack,"value",$v["VALUE"]);
                                $this->xmlObj->addChildWithValue($this->domtree,$currentTrack,"label",$v["LABEL"]);
                        }
                }
	}

	/**
	* This function is used to populate diet data for top search band
	* @access public
	* @see FieldMap
	* @param String $notXml- if xml is required, pass the xml object (optional)
	* @return Array $output
	*/
	public function populateDiet($notXml='')
	{
		$i=0;
                $output[$i]["VALUE"] = "";
                $output[$i]["LABEL"] = "Select Diet";
                $i++;

		$occ_arr = FieldMap::getFieldLabel("diet",1,1);

                foreach($occ_arr as $k=>$v)
                {
                        $output[$i]["VALUE"] = $k;
                        $output[$i]["LABEL"] = $v;
                        $i++;
                }
                unset($occ_arr);

		if($notXml)
                        return $output;
                else
                {
                        $currentTrack = $this->xmlObj->addChildWithoutValue($this->domtree,$this->xmlRoot,"diet",1);
                        foreach($output as $k=>$v)
                        {
                                $this->xmlObj->addChildWithValue($this->domtree,$currentTrack,"value",$v["VALUE"]);
                                $this->xmlObj->addChildWithValue($this->domtree,$currentTrack,"label",$v["LABEL"]);
                        }
                }
	}

	/**
	* This function is used to populate income data
	* @access public
	* @see FieldMap
	* @staticVar TopSearchBandConfig::$noIncomeLabel
	* @param String $notXml- if xml is required, pass the xml object (optional)
	* @return Array $output
	*/
	public function populateIncome($notXml='',$jsms='',$mobileApp='')
	{
		$i=0;
		if(!$jsms && !$mobileApp)
		{
			$output[$i]["VALUE"] = "";
			$output[$i]["LABEL"] = "Select Income";
			$i++;
		}
		$income_arr = FieldMap::getFieldLabel("hincome",1,1);

		foreach($income_arr as $k=>$v)
		{
			$output[$i]["VALUE"] = $k;
			if($k==0)
			{
				if(!$jsms && !$mobileApp)
					$output[$i]["LABEL"] = TopSearchBandConfig::$noIncomeLabel;
				else
					$output[$i]["LABEL"] = "Rs. 0";
			}
			else
                        	$output[$i]["LABEL"] = $v;
                        if($jsms)
				$output[$i]["IN_GROUP"] = "";
                        $i++;
		}
		unset($income_arr);

		if($notXml)
                        return $output;
                else
                {
                        $currentTrack = $this->xmlObj->addChildWithoutValue($this->domtree,$this->xmlRoot,"income",1);
                        foreach($output as $k=>$v)
                        {
                                $this->xmlObj->addChildWithValue($this->domtree,$currentTrack,"value",$v["VALUE"]);
                                $this->xmlObj->addChildWithValue($this->domtree,$currentTrack,"label",$v["LABEL"]);
                        }
                }
	}
	
	
	/**
	* This function is used to populate income data in dollar
	* @access public
	* @see FieldMap
	* @staticVar TopSearchBandConfig::$noIncomeLabel
	* @return Array $output
	*/
	public function populateIncomeDollar()
	{
		$i=0;
		
		$output[$i]["VALUE"] = "0";
		$output[$i]["LABEL"] = "$0";//TopSearchBandConfig::$noIncomeLabel;
		$i++;
	
		$income_arr = FieldMap::getFieldLabel("hincome_dol",1,1);

		foreach($income_arr as $k=>$v)
		{
			$output[$i]["VALUE"] = (string) $k;
			$output[$i]["LABEL"] = $v;
      $i++;
		}
		unset($income_arr);

		return $output;
              
	}


	/**
	* This function is used to delete some age values based on the gender selected in the top search band
	* @access public
	* @see TopSearchBandConfig
	* @param String $topSearchBandXml- if xml is required, set the xml object (optional)
	*/
	public function checkAgeBasedOnGender($topSearchBandXml='')
	{
		if($this->selectedGender == TopSearchBandConfig::$maleGenderValue)
		{
			$ageDiff = TopSearchBandConfig::$minAgeMale - TopSearchBandConfig::$minAgeFemale;
			for($i=0;$i<$ageDiff;$i++)
			{
				if($topSearchBandXml)
					unset($topSearchBandXml->age->value[0]);
				else
					unset($this->dataArray["age"][$i]);
			}
		}
	}
		
	/**
	* This function is used to delete some caste values based on the religion selected in the top search band
	* @access public
	* @see TopSearchBandConfig
	* @param String $topSearchBandXml- xml root if xml is being generated(optional)
	*/
	public function checkCasteBasedOnReligion($topSearchBandXml='')
	{
		if($topSearchBandXml)
		{
			$result = $topSearchBandXml->xpath("/topSearchBand/caste/data/parent");
			$i=0;
			$j=0;
			foreach($result as $k=>$v)
			{
				if($this->selectedReligion && (((string) $v[0] != $this->selectedReligion && (string) $v[0] !="") || (string) $topSearchBandXml->caste->data[$i-$j]->isAll[0] == "Y"))
				{
					unset($topSearchBandXml->caste->data[$i-$j]);
					$j++;
				}
				$i++;
			}

			if(in_array($this->selectedReligion,TopSearchBandConfig::$sectLabelReligions))
                        {
				$result = $topSearchBandXml->xpath("/topSearchBand/caste/data/label");
				foreach($result as $k=>$v)
				{
					if((string) $v[0] == "Select Caste")
					{
						$topSearchBandXml->caste->data[0]->label[0] = "Select Sect";
						break;
					}
				}
                        }
		}
		else
		{
			foreach($this->dataArray["caste"] as $k=>$v)
			{
				if($this->selectedReligion && (($v["PARENT"]!=$this->selectedReligion && $v["PARENT"]!="") || $v["ISALL"]=="Y"))
					unset($this->dataArray["caste"][$k]);
			}

			if(in_array($this->selectedReligion,TopSearchBandConfig::$sectLabelReligions))
			{
				$this->dataArray["caste"][0]["LABEL"] = "Select Sect";
			}
		}
	}

	/**
	* This function is used to retreive pre selected values for top search band in app
	* if(!$output["religion_label"]) $output["religion"] = NULL; condition like this added as there are invalid entries in dpp on live.
	* @access public
	* @see TopSearchBandConfig
	* @see FieldMap
	*/
	public function populateSelectedValuesForApp()
	{
		$output["gender"] = $this->selectedGender;
		$output["lage"] = $this->selectedLage?$this->selectedLage:NULL;
		$output["hage"] = $this->selectedHage?$this->selectedHage:NULL;
		
		if($this->selectedLheight)
			$output["lheight"] = $this->selectedLheight;
		else
			$output["lheight"] = TopSearchBandConfig::$minDefaultHeight;
		$output["lheight_label"]= str_replace("&quot;","\"",FieldMap::getFieldLabel("height_without_meters",$output["lheight"]));
		if($this->selectedHheight)
			$output["hheight"] = $this->selectedHheight;
		else
			$output["hheight"] = TopSearchBandConfig::$maxDefaultHeight;
		$output["hheight_label"]=str_replace("&quot;","\"",FieldMap::getFieldLabel("height_without_meters",$output["hheight"]));
		if($this->selectedHincome!="")
			$output["hincome"] = $this->selectedHincome;
		else
			$output["hincome"] = TopSearchBandConfig::$maxDefaultIncome;
		$output["hincome_label"]=FieldMap::getFieldLabel("hincome",$output["hincome"]);
		if($this->selectedLincome!="")
			$output["lincome"] = $this->selectedLincome;
		else
			$output["lincome"] = TopSearchBandConfig::$minDefaultIncome;
		if($output["lincome"]=="0")
			$output["lincome_label"]="Rs. 0";
		else 
			$output["lincome_label"] = FieldMap::getFieldLabel("lincome",$this->selectedLincome);

		/** caste/religion */
		if($this->selectedCaste=="" || strpos($this->selectedCaste,",")>0)
		{
			$output["caste"] = NULL;
			$output["caste_label"] = NULL;
		}
		if($this->selectedCaste!="")
		{
			if(strpos($this->selectedCaste,",")=='')
			{
				$output["caste"] = $this->selectedCaste;
				$output["caste_label"] = trim(ltrim(strstr(FieldMap::getFieldLabel("caste",$this->selectedCaste),":"),":"));
			}else{
                                $output["caste"] = $this->selectedCaste;
                                $castes = explode(",",$this->selectedCaste);
                                foreach($castes as $caste){
                                        $output["caste_label"][] = trim(ltrim(strstr(FieldMap::getFieldLabel("caste",$caste),":"),":"));
                                }
                                if($this->isNewApp == 1){
                                        $output["caste_label"] = implode(",",$output["caste_label"]);
                                }else{
                                        $output["caste_label"] = $output["caste_label"][0];  
                                        $output["caste"] = $castes[0];  
                                }
                        }
                        if($this->isNewApp == 1){
                                $casteObj = new RevampCasteFunctions();
                                $castes = explode(",",$this->selectedCaste);
                                foreach($castes as $caste){
                                        $religion = $casteObj->getParentIfSingle($caste);
                                        if($religion && !in_array($religion,$output["religion"])){
                                                $output["religion"][] = $religion;
                                                $output["religion_label"][] = FieldMap::getFieldLabel("religion",$religion);
                                        }
                                }
                                $output["religion"] = implode(',',$output["religion"]);
                                $output["religion_label"] =implode(',',$output["religion_label"]); 
                        }else{
                                $casteObj = new RevampCasteFunctions();
                                $output["religion"] = $casteObj->getParentIfSingle($this->selectedCaste);
                                if($output["religion"])
                                        $output["religion_label"] =FieldMap::getFieldLabel("religion",$output["religion"]);
                                else
                                        $output["religion_label"] =NULL;
                        }
		}
		if(!$output["religion"])
		{
                        
			if($this->selectedReligion=="")
			{
				$output["religion"]=NULL;
				$output["religion_label"] =NULL;
			}elseif(strpos($this->selectedReligion,",")>0){
                                if($this->isNewApp == 1){
                                        $religions = explode(',',$this->selectedReligion);
                                        foreach($religions as $religion){
                                                $output["religion"][] = $religion;
                                                $output["religion_label"][] =FieldMap::getFieldLabel("religion",$religion); 
                                        }
                                        $output["religion"] = implode(',',$output["religion"]);
                                        $output["religion_label"] =implode(',',$output["religion_label"]); 
                                }else{
                                        $output["religion"]=NULL;
                                        $output["religion_label"] =NULL;
                                }
                        }else
			{
				$output["religion"] = $this->selectedReligion;
				$output["religion_label"] =FieldMap::getFieldLabel("religion",$this->selectedReligion);
			}
		}
		if(!$output["caste_label"])
			$output["caste"] = NULL;
		if(!$output["religion_label"])
			$output["religion"] = NULL;
		/** caste/religion */

		if($this->selectedMtongue=="" || strpos($this->selectedMtongue,",")>0)
		{
			$output["mtongue"] = NULL;
			$output["mtongue_label"] = NULL;

			if(strpos($this->selectedMtongue,",")>0)
			{
				$mstatusArr = FieldMap::getFieldLabel('mtongue_region','',1);
                                $forlabelArr = array();
                                $oMtongue = $this->selectedMtongue;
				foreach($mstatusArr as $k=>$v)
				{
                                        if(count(array_intersect(explode(',',$v),explode(',',$this->selectedMtongue))) == sizeOf(explode(',',$v))){
						$forLabel=$k;	
                                                $forlabelArr[] = $k;
                                                $oMtongue = array_diff(explode(',',$oMtongue),explode(',',$v));
                                        }
				}
				if($forLabel || $forlabelArr)
				{
                                        $mstatusArr = FieldMap::getFieldLabel('mtongue_region_label','',1);
                                        $output["mtongue"] = explode(',',$this->selectedMtongue);
                                        foreach($forlabelArr as $forlabelAr){
                                                if($forlabelAr>0 && $forlabelAr<5)
                                                        $output["mtongue_label"][] = "All ".$mstatusArr[$forlabelAr];
                                                elseif($forlabelAr==0)
                                                        $output["mtongue_label"][] = $mstatusArr[$forlabelAr];
                                                elseif($forlabelAr==5)
                                                        $output["mtongue_label"][] = TopSearchBandConfig::$allHindiLabel;
                                        }
				}
                                if($this->isNewApp == 1){
                                        if(!is_array($oMtongue)){
                                                $oMtongue = explode(',',$oMtongue);
                                        }
                                        foreach($oMtongue as $mt){
                                                $output["mtongue"][] = $mt;
                                                $output["mtongue_label"][] =FieldMap::getFieldLabel("community_small",$mt); 
                                        }
                                        $output["mtongue"] = trim(implode(',',$output["mtongue"]),",");
                                        $output["mtongue_label"] =trim(implode(',',$output["mtongue_label"]),","); 
                                }else{
                                        $output["mtongue"] = $output["mtongue"][0];
                                        $output["mtongue_label"] = $output["mtongue_label"][0];
                                }
			}
		}
		else
		{
			$output["mtongue"] = $this->selectedMtongue;
			$output["mtongue_label"] = FieldMap::getFieldLabel("community_small",$this->selectedMtongue);
			if(!$output["mtongue_label"])
				$output["mtongue"] = NULL;
		}

		if(!$this->selectedCity_Country || strpos($this->selectedCity_Country,",")>0)
		{
			$output["location"] = NULL;
			$output["location_label"] =NULL;
                        
			$output["location_cities"] = NULL;
			$output["location_cities_label"] =NULL;
			
			if(strpos($this->selectedCity_Country,",")>0)
			{
                                $this->selectedCity_Country = explode(",",$this->selectedCity_Country);
                                $this->selectedCity_Country = array_unique($this->selectedCity_Country);
				if(count(array_intersect(explode(',',TopSearchBandConfig::$mumbaiRegion),$this->selectedCity_Country)) == sizeOf(explode(',',TopSearchBandConfig::$mumbaiRegion)))
				{
					$output["location_cities"][] = TopSearchBandConfig::$mumbaiRegion;
                                        $this->selectedCity_Country = array_diff($this->selectedCity_Country,explode(',',TopSearchBandConfig::$mumbaiRegion));
					$output["location_cities_label"][] = TopSearchBandConfig::$mumbaiRegionLabel;
				}
				elseif(count(array_intersect(FieldMap::getFieldLabel("delhiNcrCities",1,1),$this->selectedCity_Country)) == sizeOf(FieldMap::getFieldLabel("delhiNcrCities",1,1)))
				{
					$output["location_cities"][] = implode(',',FieldMap::getFieldLabel("delhiNcrCities",1,1));
					$output["location_cities_label"][] = TopSearchBandConfig::$ncrLabel;	
                                        $this->selectedCity_Country = array_diff($this->selectedCity_Country,FieldMap::getFieldLabel("delhiNcrCities",1,1));
				}
                                foreach($this->selectedCity_Country as $s){
                                        if(ctype_alpha($s)){
                                                $cities = FieldMap::getFieldLabel("state_CITY",$s).",".$s."000";
                                                $stateCities = explode(",",$cities);
                                                $selectedValues = array_intersect($this->selectedCity_Country,$stateCities);
                                                if(count($stateCities) == count(array_unique($selectedValues))){
                                                     $this->selectedCity_Country = array_diff($this->selectedCity_Country,$stateCities);
                                                }else{
                                                        if($selectedValues && ($key = array_search($s, $this->selectedCity_Country)) !== false) {
                                                                unset($this->selectedCity_Country[$key]);
                                                        }
                                                }
                                        }
                                }
                                foreach($this->selectedCity_Country as $v){
                                        if(is_numeric($v)){
                                                $tempField ="country";
                                                $output["location"][] = $v;
                                                $output["location_label"][] = FieldMap::getFieldLabel($tempField,$v);
                                        }else{
                                                if(ctype_alpha($v)){
                                                        $tempField ="state_india";
                                                }else{
                                                        $tempField ="city_india";
                                                }
                                                $output["location_cities"][] = $v;
                                                $output["location_cities_label"][] = FieldMap::getFieldLabel($tempField,$v);
                                        }
                                }                                
                                if($this->isNewApp == 1){
                                        $output["location"] = implode(',',$output["location"]);
                                        $output["location_label"] = implode(',',$output["location_label"]);
                                        $output["location_cities"] = implode(',',$output["location_cities"]);
                                        $output["location_cities_label"] = implode(',',$output["location_cities_label"]);
                                }else{
                                        if(!empty($output["location_cities"])){
                                                $output["location"] = $output["location_cities"][0];
                                                $output["location_label"] = $output["location_cities_label"][0];
                                        }else{
                                                $output["location"] = $output["location"][0];
                                                $output["location_label"] = $output["location_label"][0];
                                        }
                                }
			}
		}
		else
		{
			if(is_numeric($this->selectedCity_Country)){
				$tempField ="country";
                                $output["location"] = $this->selectedCity_Country;
                                $output["location_label"] = FieldMap::getFieldLabel($tempField,$this->selectedCity_Country);
                                if(!$output["location_label"])
                                        $output["location"] = NULL;
                        }else{
				$tempField ="city_india";
                                if($this->isNewApp == 1){
                                        $output["location_cities"] = $this->selectedCity_Country;
                                        $output["location_cities_label"] = FieldMap::getFieldLabel($tempField,$this->selectedCity_Country);
                                        if(!$output["location_cities_label"])
                                                $output["location_cities"] = NULL;
                                }else{
                                        $output["location"] = $this->selectedCity_Country;
                                        $output["location_label"] = FieldMap::getFieldLabel($tempField,$this->selectedCity_Country);
                                        if(!$output["location_label"])
                                                $output["location"] = NULL;    
                                }
                        }
		}

		$output["photo"] = $this->selectedHavePhoto?$this->selectedHavePhoto:NULL;
		return $output;
	}

	/** 
	 * This function is used to test if 2 strings have same value
	 * @access public
	 * @param String $a
	 * @param String $b
	 * @return bool 
	 */ 
	public static function if_two_string_contains_same_values($a, $b) 
	{
		$a = explode(",",$a);
		$b = explode(",",$b);
		$diff = array_diff($a,$b);
		if(array_diff($a,$b) == array_diff($b,$a))
			return true;
		return false;
	}

	/**
	* This function is used to retreive pre selected values for top search band in jsms
	* if(!$output["religion_label"]) $output["religion"] = NULL; condition like this added as there are invalid entries in dpp on live.
	* @access public
	* @see TopSearchBandConfig
	* @see FieldMap
	*/
	public function populateMultiSelectValues()
	{
		$output["gender"] = $this->selectedGender?$this->selectedGender:"F"; // Female by default
		
		if($output["gender"]==TopSearchBandConfig::$maleGenderValue)
			$minAgeByGender = TopSearchBandConfig::$minAgeMale;
		else
			$minAgeByGender =  TopSearchBandConfig::$minAgeFemale;
		if($this->selectedLage && $this->selectedLage >= $minAgeByGender)
			$output["lage"] = $this->selectedLage;
		else
			$output["lage"] = TopSearchBandConfig::$minDefaultAge;
			
		$output["lage_label"] = $output["lage"];
		if($this->selectedHage)
		{ 
			if($this->selectedHage > $minAgeByGender)
				$output["hage"] = $this->selectedHage;
			else
				$output["hage"] = $minAgeByGender;
		}
		else
			$output["hage"] = TopSearchBandConfig::$maxDefaultAge;
		$output["hage_label"]= $output["hage"]; 
		if($this->selectedLheight)
			$output["lheight"] = $this->selectedLheight;
		else
			$output["lheight"] = TopSearchBandConfig::$minDefaultHeight;
		$output["lheight_label"]= str_replace("&quot;","\"",FieldMap::getFieldLabel("height_without_meters",$output["lheight"]));
		if($this->selectedHheight)
			$output["hheight"] = $this->selectedHheight;
		else
			$output["hheight"] = TopSearchBandConfig::$maxDefaultHeight;
		$output["hheight_label"]=str_replace("&quot;","\"",FieldMap::getFieldLabel("height_without_meters",$output["hheight"]));
		if($this->selectedHincome!="")
			$output["hincome"] = $this->selectedHincome;
		else
			$output["hincome"] = TopSearchBandConfig::$maxDefaultIncome;
		$output["hincome_label"]=FieldMap::getFieldLabel("hincome",$output["hincome"]);
		if($this->selectedLincome!="")
			$output["lincome"] = $this->selectedLincome;
		else
			$output["lincome"] = TopSearchBandConfig::$minDefaultIncome;
		if($output["lincome"]=="0")
			$output["lincome_label"]="Rs. 0";
		else
			$output["lincome_label"] = FieldMap::getFieldLabel("lincome",$this->selectedLincome);
		if(!MobileCommon::isDesktop())
		{
			
		/** caste/religion */
			if($this->selectedCaste=="" || strpos($this->selectedCaste,",")>0)
			{
				$output["caste"] = NULL;
				$output["caste_label"] = NULL;
			}
			if($this->selectedCaste!="" || $this->selectedReligion!="")
			{
				if($this->selectedReligion!="")
				{
					foreach(explode(",",$this->selectedReligion) as $k=>$r)
					{
						$output["religion"][] = $r;
						$output["religion_label"][] =SearchFieldMapLib::getFieldLabel("religion",$r);
						
					}
				}
				$i =0;
				$casteReligionArr = array();
				$religion_arr = FieldMap::getFieldLabel("religion_caste",1,1);
				$hinduCasteArr = array_keys(SearchFieldMapLib::getFieldLabel("caste_group_array",1,1));
				$religion_arr[1] = $religion_arr[1].",".implode(",",$hinduCasteArr);
				if($this->selectedCaste!="")
				{
					$casteArray = explode(",",$this->selectedCaste);
					foreach($casteArray as $k=>$v)
					{
						$i++;
						foreach($religion_arr as $r=>$c)
						{
							if(in_array($v,explode(",",$c)))
								$casteReligionArr[$r][]=$v;
						}
						
					}
				}
				
				// Loop to check if religion is selected and no corresponding caste
				foreach($output["religion"] as $k=>$r)
				{
					if(array_key_exists($r,$religion_arr) && !array_key_exists($r,$casteReligionArr))
					{
						if($r=="1")
							$casteReligionArr[$r][]="14"; //Hindu any caste handling
						else
							$casteReligionArr[$r][]=TopSearchBandConfig::$doesNotMatterValue;
					}
					
				}
				$j=0;
				$religion ="";
				foreach($casteReligionArr as $r=>$c)
				{
					if($j==0)
						$religion=$r;
					if(!in_array($r,$output["religion"]))
					{						
						$output["religion"][] =$r;
						$output["religion_label"][] =FieldMap::getFieldLabel("religion",$r);
					}
					$output["caste"][] = "\"".$r."\":\"".implode(",",$c)."\"";
				}
				
				if(is_array($output["caste"]))
				{
					$output["caste"] = "{".implode(",",$output["caste"])."}";
					if($i>0)
					{
						if($religion!=2 && $religion!=3)
							$casteLabel =" Caste";
						else
							$casteLabel =" Sect";
						
						$output["caste_label"] = $i.$casteLabel;
						if($i>1)
							$output["caste_label"].="s";
					}
					else
						$output["caste_label"]=NULL;
				}
				else
				{
					$output["caste"]=NULL;
					$output["caste_label"]=NULL;				
				}
				
			}
			
			if(!$output["religion"])
			{
				$output["religion"]=NULL;
				$output["religion_label"] ="Any Religion";
			}
			else
			{
			$output["religion"] = implode(",",$output["religion"]);
			$output["religion_label"] = implode(",",$output["religion_label"]);
		}
		}
		else
		{
					if($this->selectedCaste=="" || strpos($this->selectedCaste,",")>0)
					{
						$output["caste"] = NULL;
						$output["caste_label"] = NULL;
					}
					else
					{
							$output["caste"] = $this->selectedCaste;
							$output["caste_label"] = trim(ltrim(strstr(FieldMap::getFieldLabel("caste",$this->selectedCaste),":"),":"));
							if(array_key_exists($this->selectedCaste,FieldMap::getFieldLabel("caste_group_array",1,1)))
								$output["caste_label"] =$output["caste_label"] ." - All";
							if(!$output["caste_label"])
							{
								if(in_array($output["caste"],TopSearchBandConfig::$religionAllCasteMapping))
									$output["caste_label"] = FieldMap::getFieldLabel("religion", array_search ($output["caste"], TopSearchBandConfig::$religionAllCasteMapping)) ." - All";
							}
					}
					
					if($this->selectedReligion=="" || strpos($this->selectedReligion,",")>0)
					{
						$output["religion"]=NULL;
						$output["religion_label"] =NULL;
					}
					else
					{
						$output["religion"] = $this->selectedReligion;
						$output["religion_label"] =SearchFieldMapLib::getFieldLabel("religion",$this->selectedReligion);
					}
						
		}
		if($this->selectedMtongue=="" || strpos($this->selectedMtongue,",")>0)
		{
			$output["mtongue"] = NULL;
			$output["mtongue_label"] = "Any Mother Tongue";
			$output["mtongue_label_dep"]= NULL;
			
			if(strpos($this->selectedMtongue,",")>0)
			{
                                $output["mtongue"] = $this->selectedMtongue;
                                $mtongue_arr = SearchFieldMapLib::getFieldLabel("mtongue",1);
				if(strpos($this->selectedMtongue,$mtongue_arr)===false)
				{
					$temp = str_replace("'","",$this->selectedMtongue);
					$temp1Arr = explode(",",$temp);
					$output["mtongue_label"]= FieldMap::getFieldLabel("community_small",$temp1Arr[0]);
				
					
				}
				else
				{
					$output["mtongue_label"] = "Hindi - All";
					$temp= str_replace($mtongue_arr,"",$this->selectedMtongue);
					$temp = str_replace("'","",$temp);
					$temp1Arr = explode(",",$temp);
				}
				if(sizeOf($temp1Arr)>1)
				{
					if(MobileCommon::isDesktop())
					{
							$output["mtongue"] = NULL;
							$output["mtongue_label"] = "Any Mother Tongue";
							$output["mtongue_label_dep"]= NULL;
					}
					else
						$output["mtongue_label_dep"] = "+".(sizeOf($temp1Arr)-1)." more";
				}
				unset($temp);unset($tempArr);
			}
		}
		else
		{
			$output["mtongue"] = $this->selectedMtongue;
			$output["mtongue_label"] = FieldMap::getFieldLabel("community_small",$this->selectedMtongue);
			if(!$output["mtongue_label"])
				$output["mtongue"] = NULL;
		}

		if($this->selectedCity_Country!="")
		{
                        $output["location_cities"] = '';
                        $output["location"] = '';
                        $city_country_resArr = explode(",",$this->selectedCity_Country);
        	        foreach($city_country_resArr as $v)
                	{
	                        if(is_numeric($v))
					$tempCountry[] = $v;
				elseif(ctype_alpha($v))
					$tempState[] = $v;
				else
					$tempCity[] = $v;
                	}
                        
                        if($tempCountry){
                                $countryArr = array();
                                $tempCountry = array_unique($tempCountry);
                                foreach($tempCountry as $country){
                                        $countryArr[] = FieldMap::getFieldLabel("country",$country);
                                }
                                $output["location"] = implode(",",$tempCountry); 
                                if(count($countryArr)>1){
                                    $output["location_label"] = $countryArr[0]; 
                                    $output["location_label_dep"] = " +".(count($countryArr) - 1)." more"; 
                                }else{
                                    $output["location_label"] = $countryArr[0]; 
                                    $output["location_label_dep"] = "";     
                                }
                        }
                        if($tempState){
                                $output["location_cities"] = implode(",",$tempState);
                        }
                        if($tempCity){
                                if($output["location_cities"] != ''){
                                        $output["location_cities"] .= ",";
                                }
                                $output["location_cities"] .= implode(",",$tempCity);
                        }
                        $output["location_cities"] = explode(',',$output["location_cities"]);
			if(count(array_intersect(TopSearchBandConfig::$metroCities,$tempCity))== sizeOf(TopSearchBandConfig::$metroCities))
			{
				$output["location_cities_label"] = "Metro Cities - All";
				$metroCities = array_merge(TopSearchBandConfig::$metroCities,FieldMap::getFieldLabel("delhiNcrCities",1,1),explode(",",TopSearchBandConfig::$mumbaiRegion));
				$location = array_diff($output["location_cities"],$metroCities);
				$locationSize = sizeOf($location);
				
			}
			elseif(count(array_intersect(FieldMap::getFieldLabel("delhiNcrCities",1,1),$tempCity)) == sizeOf(FieldMap::getFieldLabel("delhiNcrCities",1,1)))
			{
				$output["location_cities_label"] = TopSearchBandConfig::$ncrLabel;
				$location = array_diff($output["location_cities"],FieldMap::getFieldLabel("delhiNcrCities",1,1));
				if($locationSize){
        				$locationSize = ($locationSize + 2) - count(explode(",",TopSearchBandConfig::$ncrLabel));
                                }else{
                                        $locationSize = sizeOf($location);
                                }
				
			}
			elseif(count(array_intersect($tempCity,explode(",",TopSearchBandConfig::$mumbaiRegion))) == sizeOf(explode(",",TopSearchBandConfig::$mumbaiRegion)))
			{
				$output["location_cities_label"] = TopSearchBandConfig::$mumbaiRegionLabel;
				$location = array_diff($output["location_cities"],explode(",",TopSearchBandConfig::$mumbaiRegion));
                                if($locationSize){
        				$locationSize = ($locationSize + 2) - count(explode(",",TopSearchBandConfig::$mumbaiRegion));
                                }else{
                                        $locationSize = sizeOf($location);
                                }
				
			}else{
				if(!empty($tempState)){
					$tempField ="state_india";
                                        $locationArray = $tempState;
                                }elseif(!empty($tempCity)){
					$tempField ="city_india";
                                        $locationArray = $tempCity;
                                }else{
                                       $output["location_cities"] = NULL;
                                       $output["location_cities_label"] ="Any State/City";
                                       $output["location_cities_label_dep"] = NULL; 
                                }
                                
                                if(!empty($tempState) || !empty($tempCity)){
                                        if(!empty($tempState)){
                                                //$output["location_cities"] = explode(',',$output["location_cities"]);
                                                foreach($tempState as $s){
                                                        $cities = FieldMap::getFieldLabel("state_CITY",$s).",".$s."000";
                                                        $stateCities = explode(",",$cities);
                                                        $selectedValues = array_intersect($tempCity,$stateCities);
                                                        if(count($stateCities) == count(array_unique($selectedValues))){
                                                             $tempCity = array_diff($tempCity,$stateCities);
                                                             $output["location_cities"] = array_diff($output["location_cities"],$stateCities);
                                                        }else{
                                                                if($selectedValues && ($key = array_search($s, $output["location_cities"])) !== false) {
                                                                        unset($output["location_cities"][$key]);
                                                                }
                                                                if($selectedValues && ($key = array_search($s, $tempState)) !== false) {
                                                                        unset($tempState[$key]);
                                                                }
                                                        }
                                                }
                                                $tempCity = array_unique($tempCity);
                                                $output["location_cities_label"] = FieldMap::getFieldLabel($tempField,$locationArray[0]);
                                                $locationSize = (sizeOf($tempState) + sizeOf($tempCity))-1;
                                        }else{
                                                $output["location_cities_label"] = FieldMap::getFieldLabel($tempField,$locationArray[0]);
                                                $locationSize = (sizeOf($tempState) + sizeOf($tempCity))-1;
                                        }
                                }
                        }
			$output["location_cities"] = implode(",",array_unique($output["location_cities"]));
			if($locationSize>=1)
				$output["location_cities_label_dep"] = "+".($locationSize)." more";
			else
				$output["location_cities_label_dep"] = NULL;

                        
		}
		else
		{
			$output["location"] = NULL;
			$output["location_label"] ="Any Country";
			$output["location_label_dep"] = NULL;
                        
                        
                        $output["location_cities"] = NULL;
                        $output["location_cities_label"] ="Any State/City";
                        $output["location_cities_label_dep"] = NULL;
			
		}
                        
                if($this->selectedOccupationJSMS!="")
		{
                  $output["occupation"] = $this->selectedOccupationJSMS;
                  $occGroupingArr = FieldMap::getFieldLabel("occupation_grouping",1,1);
                  foreach($occGroupingArr as $k=>$v){
                    $occMappingArray = FieldMap::getFieldLabel("occupation_grouping_mapping_to_occupation",$k);
                    $mappingOccupations = explode(",",$occMappingArray);
                    $selectedValues = array_intersect(explode(",",$this->selectedOccupationJSMS),$mappingOccupations);
                    if(count($selectedValues) == sizeOf($mappingOccupations)){
                      $occupation[] = $v. " - All";
                    }else{
                      foreach($selectedValues as $selectedVal){
                        $occupationData = FieldMap::getFieldLabel("occupation",$selectedVal);
                        $occupation[] = $occupationData;
                      }
                    }
                  }
                  $occupationSize = sizeof($occupation);
                  if($occupationSize>1){
                          $output["occupation_label_dep"] = "+".($occupationSize - 1)." more";
                          $output["occupation_label"] =$occupation[0];
                  }else{
                          $output["occupation_label_dep"] = NULL;
                          $output["occupation_label"] =implode(',',$occupation);
                  }
                }else{
                  $output["occupation"] = NULL;
                  $output["occupation_label"] ="Doesn't Matter";
                  $output["occupation_label_dep"] = NULL;
                }
                if($this->selectedEducationJSMS!="")
		{
                  $output["education"] = $this->selectedEducationJSMS;
                  $occGroupingArr = FieldMap::getFieldLabel("education_grouping",1,1);
                  foreach($occGroupingArr as $k=>$v){
                    $occMappingArray = FieldMap::getFieldLabel("education_grouping_mapping_to_edu_level_new",$k);
                    $mappingOccupations = explode(",",$occMappingArray);
                    $selectedValues = array_intersect(explode(",",$this->selectedEducationJSMS),$mappingOccupations);
                    if(count($selectedValues) == sizeOf($mappingOccupations)){
                      $education[] = $v. " - All";
                    }else{
                      foreach($selectedValues as $selectedVal){
                        $occupationData = FieldMap::getFieldLabel("education",$selectedVal);
                        $education[] = $occupationData;
                      }
                    }
                  }
                  $educationSize = sizeof($education);
                  if($educationSize>1){
                          $output["education_label_dep"] = "+".($educationSize - 1)." more";
                          $output["education_label"] =$education[0];
                  }else{
                          $output["education_label_dep"] = NULL;
                          $output["education_label"] =implode(',',$education);
                  }
                }else{
                  $output["education"] = NULL;
                  $output["education_label"] ="Doesn't Matter";
                  $output["education_label_dep"] = NULL;
                }
                
                if($this->selectedManglik!="")
		{
                  $output["manglik"] = $this->selectedManglik;
                  $manglikArr = FieldMap::getFieldLabel("manglik_label",'',1);
                  $manglikArray = explode(",",$this->selectedManglik);
                  foreach($manglikArray as $selectedVal){
                          if($selectedVal != 'D')
                                $manglik[] = $manglikArr[$selectedVal];
                  }
                  $manglikSize = sizeof($manglik);
                  if($manglikSize>1){
                          $output["manglik_label_dep"] = "+".($manglikSize - 1)." more";
                          $output["manglik_label"] =$manglik[0];
                  }else{
                          $output["manglik_label_dep"] = NULL;
                          $output["manglik_label"] =implode(',',$manglik);
                  }
                }else{
                  $output["manglik"] = NULL;
                  $output["manglik_label"] ="Doesn't Matter";
                  $output["manglik_label_dep"] = NULL;
                }
		$output["havephoto"] = $this->selectedHavePhoto; // With photo by default
		$output["mstatus"] = $this->selectedMstatus;
		$mstatus = $this->selectedMstatus;
		$output["mstatus_label"] = TopSearchBandConfig::$mstatusArr[$mstatus];
		//print_r($output); die;
		return $output;
	}

}
?>
