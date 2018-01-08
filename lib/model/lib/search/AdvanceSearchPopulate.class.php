<?php
/**
* This class is used to generate the data for advanced search
* @package Search
* @subpackage Search
* @link http://devjs.infoedge.com/mediawiki/index.php/SEARCH
* @since 16 Nov 2015
*/
class AdvanceSearchPopulate
{
	
	private $dataArray;
	private $searchFeilds;
	private $searchSection;
	private $selectedParams;
	private $religionWithAstro = Array("1","4","9","7"); // Array hindu,sikh, jain and buddhist
	private $CasteLabel ="Caste";
	private $minDefaultIncomeDol = '12';
	private $fieldMapLibValues = Array("occupation"=>"occupation",
																		 "manglik"=>"manglik_label",
																		 "diet"=>"diet","smoke"=>"smoke","drink"=>"drink",
																		 "handicapped"=>"handicapped_mobile",
																		 "workAfterMarriage"=>"career_after_marriage",
																		 "hiv"=>"hiv_edit","haveChildren"=>"children",
																		 "settleAbroad"=>"going_abroad"
																		
																	);
	private $fieldSearchQueryMapping = Array("LAGE"=>"Min_Age","HAGE"=>"Max_Age",
																		"LHEIGHT"=>"Min_Height","HHEIGHT"=>"Max_Height","MSTATUS"=>"partner_mstatus_arr",
																		"CASTE_DISPLAY"=>"partner_caste_arr","RELIGION"=>"partner_religion_arr",
																		"MTONGUE"=>"partner_mtongue_arr","COUNTRY_RES"=>"partner_country_arr",
																		"CITY_INDIA"=>"partner_city_arr","CITY_RES"=>"partner_city_arr","STATE"=>"partner_city_arr",
																		"HAVEPHOTO"=>"HAVEPHOTO","LINCOME"=>"rsLIncome","HINCOME"=> "rsHIncome",
																		"LINCOME_DOL"=> "doLIncome","HINCOME_DOL"=>"doHIncome","HAVECHILD"=>"partner_hchild_arr",
																		"MANGLIK"=>"partner_manglik_arr","HOROSCOPE"=>"Horoscope",
																		"EDU_LEVEL_NEW"=>"partner_education_arr","OCCUPATION"=>"partner_occupation_arr",
																		"DIET"=>"partner_diet_arr","DRINK"=>"partner_drink_arr","SMOKE"=>"partner_smoke_arr",
																		"MARRIED_WORKING"=>"partner_wstatus_arr","GOING_ABROAD"=>"partner_settle_abroad_arr",
																		"HANDICAPPED"=>"partner_handicapped_arr","HIV"=>"HIV","KEYWORD"=>"keywords","ONLINE"=>"Online",
																		"KEYWORD_TYPE"=>"kwd_rule","LAST_LOGIN_DT"=>"Login"
																	);

	/**
	*Constructor to set the class variables
	* @access public
	* @param Array $parameters:  array that has the values passed in the 
	* @see PartnerProfile
	* @see AdvanceSearchConfig
	* @see SearchLogger
	*/
	public function __construct($parameters='')
	{
		
		$loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
    
		if(!$parameters["SEARCHID"] && $loggedInProfileObj && $loggedInProfileObj->getPROFILEID())    //LOGGEDIN and no SEARCHID -> show JPARTNER data
		{
			$isDummy = PremiumMember::isDummyProfile($loggedInProfileObj->getPROFILEID());
			if(!$isDummy)
			{
				$searchObj = new SearchLogger();
				$flag = $searchObj->getLastSearchCriteria($loggedInProfileObj->getPROFILEID(),SearchTypesEnums::Advance);
				
			}

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
				if(!in_array($loggedInProfileObj->getRELIGION(),$this->religionWithAstro))
					$param["hideAstro"]="1";
				if($loggedInProfileObj->getRELIGION()=='2' || $loggedInProfileObj->getRELIGION()=='3')
					$this->CasteLabel = "Sect";
			}

			foreach($this->fieldSearchQueryMapping as $key=>$v)
			{
				eval('$myVal = $searchObj->get'.$key.'();');
					if($myVal || $myVal==0)
							$param[$key]=$myVal;
			}
			unset($searchObj);
			unset($loggedInProfileObj);
		}

		$this->selectedParams["GENDER"]= $param["GENDER"];
		foreach($this->fieldSearchQueryMapping as $key=>$v)
		{
			if($param[$key])
				$this->selectedParams[$key]=$param[$key];
			else
			{
				switch($key){
					case 'LAGE':
						$this->selectedParams[$key]=  TopSearchBandConfig::$minDefaultAge;
						break;
					case 'HAGE':
						$this->selectedParams[$key]=  TopSearchBandConfig::$maxDefaultAge;
						break;
					case 'LHEIGHT':
					$this->selectedParams[$key]=  TopSearchBandConfig::$minDefaultHeight;
				break;
				case 'HHEIGHT':
					$this->selectedParams[$key]=  TopSearchBandConfig::$maxDefaultHeight;
				break;
				case 'LINCOME':
					$this->selectedParams[$key]=  TopSearchBandConfig::$minDefaultIncome;
				break;
				case 'LINCOME_DOL':
					$this->selectedParams[$key]= ($flag!=1)?$this->minDefaultIncomeDol:0;
				break;
				case 'HINCOME':
				case 'HINCOME_DOL':
					$this->selectedParams[$key]=  TopSearchBandConfig::$maxDefaultIncome;
				break;
				}
			}
		  }
		  $this->selectedParams["hideAstro"]=$param["hideAstro"]?"1":"0";
	}
	/**
	This function is used to generate the data arrays 
	* @access public
	* @return Array $this->dataArray
	*/
	public function generateDataArray()
	{
		
			$this->dataArray["age"] = $this->populateAge();
			//$this->dataArray["gender"] = $this->populateGender();
			
			$this->dataArray["mtongue"] = $this->populateMtongue();
			//
			$this->dataArray["height"] = $this->populateHeight();
			$this->dataArray["religion"] = $this->populateReligion();
			
			$this->dataArray["caste"] = $this->populateCaste();
			$this->dataArray["location"] = $this->populateCity_State();
			$this->dataArray["country"] = $this->populateCountry();			
			$this->dataArray["mstatus"] = $this->populateMstatus();
			foreach($this->fieldMapLibValues as $key=>$value)
			{
					$this->dataArray[$key] = $this->populateFeild($value);
			}
			$this->dataArray["education"] = $this->populateEducation();
			$this->dataArray["Horoscope"] = $this->populateHoroscope();
			$this->dataArray["photo"] = $this->populatePhoto();
			$this->dataArray["lincome"] = $this->populateIncome(null,null);
			$this->dataArray["lincome_dol"] = $this->populateIncome(null,1);
			$this->dataArray["hincome"] = $this->populateIncome(1,null);
			$this->dataArray["hincome_dol"] = $this->populateIncome(1,1);
			
		  $this->dataArray["selectedValues"] = $this->populateSelectedValues();
	
		return $this->dataArray;
	}
	public function populateAge()
	{
		$minAge = TopSearchBandConfig::$minAgeFemale;
		$maxAge = TopSearchBandConfig::$maxAge;
		$j=0;
		for($i=$minAge;$i<=$maxAge;$i++)
		{
			$output[$i]["VALUE"] = $i;
			$output[$i]["LABEL"] = $i;
			$j++;
		}
		return $output;
			
	}
	
	public function populateGender()
	{
		$output[0]["VALUE"] = TopSearchBandConfig::$maleGenderValue; 
		$output[0]["LABEL"] = TopSearchBandConfig::$maleLabel;
		$output[1]["VALUE"] = TopSearchBandConfig::$femaleGenderValue;
		$output[1]["LABEL"] = TopSearchBandConfig::$femaleLabel;

		return $output;
	}
	
	public function populateFeild($feild)
	{
		$arr =FieldMap::getFieldLabel($feild,"",1);		
		$i=0;
		foreach($arr as $key=>$val)
		{
                        if($feild == 'manglik_label' && ($key == 'D' || $key == 'S0')){ // Do not show 'Dont know' in manglik status
                        }
                        elseif($key == '0' && $val == "Select"){} // remove select from search
                        else{
                                $output[$key]["VALUE"]=$key;
                                $output[$key]["LABEL"]=$val;
                        }
			
		}
		return $output;
			
	}
	
		
	public function populateMtongue()
	{
		$nmObj          = new NEWJS_MTONGUE;
    $mtongueArr     = $nmObj->getFullTableForRegistration();
    $outTemp        = array();
    $out            = array();
    $regionLabel    = FieldMap::getFieldLabel("mtongue_region_label",'',1);
    $hindiAll = array("VALUE"=>implode(FieldMap::getFieldLabel("allHindiMtongues",'',1),","),"LABEL"=>"Hindi- All");
    $outTemp[4][]=$hindiAll;
    foreach($mtongueArr as $key=>$val)
    {
        $outTemp[$val["REGION"]][]=array("VALUE"=>$val["VALUE"],"LABEL"=>$val["SMALL_LABEL"]);
    }
    
    foreach($regionLabel as $key=>$val)
    {
        $out[] = array("VALUE"=>"-1","LABEL"=>"$val");
        $out = array_merge($out,$outTemp[$key]);
    }
    //print_r($outTemp); die;
    unset($nmObj);
    return $out;
	}

	
	public function populateHeight()
	{
		$array=FieldMap::getFieldLabel("height_json",'',1);
	  foreach($array as $key=>$val)
			$arr[]=array("VALUE"=>$key,"LABEL"=>$val);
	 
	  $c=0;
    $heightOrdered = array();
    for($x=0;$x<=11;$x++) {
      $heightOrdered[$c++] = $arr[$x];
      $heightOrdered[$c++] = $arr[$x+12];
      $heightOrdered[$c++] = $arr[$x+24];
    }
    $heightOrdered[$c] = $arr[36];
    return $heightOrdered;
	}

	public function populateReligion()
	{
		$religion_arr = FieldMap::getFieldLabel("religion",1,1);
		$output = FieldMap::getFieldLabel("religion_caste",1,1);
		
		//$i =0;
		foreach($religion_arr as $k=>$v)
		{
			$religionArr[$k]["VALUE"] = $k;
			$religionArr[$k]["LABEL"] = $v;
			if(array_key_exists($k,$output))
			{
				$religionArr[$k]["HAS_DEPENDENT"]="Y";
			
			}
			else
				$religionArr[$k]["HAS_DEPENDENT"]="";
			// $i++;
		}
		return $religionArr;
	}
	
	public function populateCaste()
	{
		$arr=FieldMap::getFieldLabel("religion_caste",'',1);
	  $casteArr=FieldMap::getFieldLabel("caste_without_religion",'',1);
	  $casteObj = new NEWJS_CASTE;
    $caste_arr = $casteObj->getTopSearchBandCasteData();

	  foreach($caste_arr as $key=>$val)
	  {
			$parent = $val["PARENT"];
			if(array_key_exists($parent,$arr) && $val["ISALL"]!="Y")
			{
						$label = ($casteArr[$val["VALUE"]]?$casteArr[$val["VALUE"]]:$val["LABEL"]).(($val["ISGROUP"]=="Y")?"- All":"");
						$output[$parent][]=array("VALUE"=>$val["VALUE"],"LABEL"=>$label);
		  }
		}
	
		return $output;
		
		
	}
	public function populateCity_State()
	{
		$tempArray=FieldMap::getFieldLabel("topindia_city",'',1);
	  
	  $state = FieldMap::getFieldLabel("state_india",'',1);
	  $Arr[51][0]=Array();
	  $cityIndia=FieldMap::getFieldLabel("city_india",'',1);
	  
	  foreach($state as $key=>$value)
	  {
			$stateIndia[$key]=$value;
			unset($cityIndia[$key]);
		  
	  }
	  
	  foreach($tempArray as $key=>$val)
	  {
			
		  $temp=explode(",",$val);
		  foreach($temp as $key=>$val)
		  {
		
				$topIndia[$val]=$cityIndia[$val];
				unset($cityIndia[$val]);
			}
			
		  
	  }
	  $delhiNcrCities = implode(",",FieldMap::getFieldLabel("delhiNcrCities",1,1));
	 
	  $topIndia[$delhiNcrCities]=TopSearchBandConfig::$ncrLabel;
	  $topIndia[TopSearchBandConfig::$mumbaiRegion]=TopSearchBandConfig::$mumbaiRegionLabel;
	 		
		$Arr[51][2]=$cityIndia;
	  $Arr[51][0] = array_merge($topIndia,array('-1'=>'States'),$stateIndia,array('-1'=>'startAlpha'));
	  
	  $i=0;
	  $arrAlpha = array();
    $sym = "";
    $bStartAplha = false;
	  foreach($Arr[51] as $key=>$val)
	  {
      
			foreach($val as $k=>$v){
        if($v == "startAlpha"){
          $bStartAplha = true;
          continue;
        }
        $sym = strtoupper(substr($v, 0,1));
        if($bStartAplha && !in_array($sym, $arrAlpha)){
          $arrAlpha[] = $sym;
          $output[]=array("VALUE"=>"-1","LABEL"=>$sym);  
          $i++; 
        }
        if($v === "Others"){
          $output[]=array("VALUE"=>"-1","LABEL"=>"");
          ++$i;
        }
        if($v==="States")
        {
					$output[]=array("VALUE"=>"-1","LABEL"=>"States");
        
				}
				else
					$output[]=array("VALUE"=>$k,"LABEL"=>$v);
      }  
		$i++;		
	  }
    return $output;
	}
	
	public function populateCountry()
	{
		$tempArray=FieldMap::getFieldLabel("impcountry",'',1);
    $Arr[0]=Array();
    $country=FieldMap::getFieldLabel("country",'',1);
    
    if(!$partnerCountry)
    {
      foreach($tempArray as $key=>$val)
      {
          $topCountry[][$key]=$val;
          unset($country[$key]);       
      }
      $Arr[0] = array_merge($topCountry,array("-1 "=>"startAlpha"));
    }
    else
    {
      unset($Arr);
      $Arr[0]=$country;
    }

    $Arr[1]=$country;
    $i=0;
    $arrAlpha = array();
    $sym = "";
    $bStartAplha = false;
    foreach($Arr as $key=>$val)
    {
      foreach($val as $k=>$v){
        if($v == "startAlpha"){
          $bStartAplha = true;
          continue;
        }
				if(is_string($v))
					$sym = strtoupper(substr($v, 0,1));
        if($bStartAplha && !in_array($sym, $arrAlpha)){
          $arrAlpha[] = $sym;
          $output[]=array("VALUE"=>"-1","LABEL"=>$sym);  
          $i++; 
        }
        if($k == 136)
          $output[]=array("VALUE"=>"-1","LABEL"=>"");
        if(is_array($v))
        {
					foreach($v as $z=>$zz)
						$output[] = array("VALUE"=>$z,"LABEL"=>$zz);
        }
        else
          $output[]=array("VALUE"=>$k,"LABEL"=>$v);
        $i++;   
      }
    }
    
    return $output; 
		
	}
	
	public function populateMstatus()
	{
		$array = FieldMap::getFieldLabel("mstatus",'',1);
		$i = 0;
		foreach($array as $key=>$value)
		{
			if ($key == "M")
			{
				continue; // JSM-4631
			}
			$output[$i++]=array("VALUE"=>$key,"LABEL"=>$value);
		}
		return $output;
	}
	
	public function populateEducation()
	{
		$array=FieldMap::getFieldLabel("eduDppArray",'',1);
	  $i=0;
	  foreach($array as $key=>$value)
	  {
		  $Arr[$i]=array("VALUE"=>"-1","LABEL"=>$key);
		  $i++;
		  foreach($value as $kk=>$vv)
		  {
			  $Arr[$i]=array("VALUE"=>$vv,"LABEL"=>$kk);
			  $i++;
		  }
	  }
	 
	  return $Arr;
	}
	
	public function populateHoroscope()
	{
		$output[0]["VALUE"] = "Y"; 
		$output[0]["LABEL"] = "Yes";
		$output[1]["VALUE"] = "";
		$output[1]["LABEL"] = "Doesn't Matter";

		return $output;
	}
	public function populatePhoto()
	{
		$output[0]["VALUE"] = ""; 
		$output[0]["LABEL"] = "All Profiles";
		$output[1]["VALUE"] = "Y";
		$output[1]["LABEL"] = "Profiles with photo only";

		return $output;
	}
	public function populateIncome($h,$dol)
	{
		$label = "income";
		$label =  ($h?"h":"l").$label;
		$label .= $dol?"_dol":"";
		
	  $arr=FieldMap::getFieldLabel($label,'',1);
	  $i=0;
	 
	 foreach($arr as $key=>$val)
	 {
		 if($val)
		 {
				$val = ($key!=0)?$val:($dol?"$0":"Rs. 0");
				
				$Arr[]=array("VALUE"=>$key,"LABEL"=>$val);
			}
		 $i++;
	 }
	
	 return $Arr;
	}
	
	public function getSearchSection()
	{
		$this->searchSection = array("BASIC"=>Array("Min_Age","Max_Age","Min_Height","Max_Height",
																		"partner_religion_arr","partner_caste_arr","partner_mtongue_arr","partner_country_arr",
																		"partner_city_arr","rsLIncome","rsHIncome","doLIncome","doHIncome","partner_mstatus_arr","partner_hchild_arr"),
														"ASTRO"=>Array("partner_manglik_arr"),
														"EDUCATION_CAREER"=>Array("partner_education_arr","partner_occupation_arr"),
														"LIFESTYLE"=>Array("partner_diet_arr","partner_drink_arr","partner_smoke_arr"),
														"MORE"=>Array("partner_wstatus_arr","partner_settle_abroad_arr","partner_handicapped_arr","HIV"));
		return $this->searchSection;
	}
	
	public function getSearchFeilds()
	{
		$this->searchFeilds = array();
		
														
		$this->searchFeilds["Min_Age"]= Array("feild"=>"age","label"=>"Age");
		$this->searchFeilds["Max_Age"]= Array("feild"=>"age","label"=>"Age");
		$this->searchFeilds["Min_Height"]=Array("feild"=>"height","label"=>"Height");
		$this->searchFeilds["Max_Height"]=Array("feild"=>"height","label"=>"Height");
		$this->searchFeilds["partner_religion_arr"]=Array("feild"=>"religion","label"=>"Religion","attr"=>"multiple");
		$this->searchFeilds["partner_caste_arr"]=Array("feild"=>"","label"=>$this->CasteLabel,"attr"=>"multiple","isDependant"=>"true");
		$this->searchFeilds["partner_mtongue_arr"]=Array("feild"=>"mtongue","label"=>"Mother Tongue","attr"=>"multiple");
		$this->searchFeilds["partner_country_arr"]=Array("feild"=>"country","label"=>"Country","attr"=>"multiple");
		$this->searchFeilds["partner_city_arr"]=Array("feild"=>"location","label"=>"City / State","attr"=>"multiple","isDependant"=>"true");
		$this->searchFeilds["rsLIncome"]=Array("feild"=>"lincome","label"=>"Income");
		$this->searchFeilds["rsHIncome"]=Array("feild"=>"hincome","label"=>"Income");
		$this->searchFeilds["doLIncome"]=Array("feild"=>"lincome_dol","label"=>"");
		$this->searchFeilds["doHIncome"]=Array("feild"=>"hincome_dol","label"=>"");
		$this->searchFeilds["partner_mstatus_arr"]=Array("feild"=>"mstatus","label"=>"Marital Status","attr"=>"multiple");
		$this->searchFeilds["partner_hchild_arr"]=Array("feild"=>"haveChildren","label"=>"Have Children","attr"=>"multiple","isDependant"=>"true");
		$this->searchFeilds["HAVEPHOTO"]=Array("feild"=>"photo","label"=>"Photo","toggle"=>"true");
		$this->searchFeilds["partner_manglik_arr"]=Array("feild"=>"manglik","label"=>"Manglik Status","attr"=>"multiple");
		$this->searchFeilds["Horoscope"]=Array("feild"=>"Horoscope","label"=>"Horoscope available","toggle"=>"true");
		$this->searchFeilds["partner_education_arr"]=Array("feild"=>"education","label"=>"Highest Education","attr"=>"multiple");
		$this->searchFeilds["partner_occupation_arr"]=Array("feild"=>"occupation","label"=>"Occupation","attr"=>"multiple");
		$this->searchFeilds["partner_diet_arr"]=Array("feild"=>"diet","label"=>"Diet","attr"=>"multiple");
		$this->searchFeilds["partner_drink_arr"]=Array("feild"=>"drink","label"=>"Drink","attr"=>"multiple");
		$this->searchFeilds["partner_smoke_arr"]=Array("feild"=>"smoke","label"=>"Smoke","attr"=>"multiple");
		$this->searchFeilds["partner_wstatus_arr"]=Array("feild"=>"workAfterMarriage","label"=>"Work after marriage?","attr"=>"multiple","isDependant"=>"true");
		$this->searchFeilds["partner_handicapped_arr"]=Array("feild"=>"handicapped","label"=>"Challenged?","attr"=>"multiple");
		$this->searchFeilds["HIV"]=Array("feild"=>"hiv","label"=>"HIV+?","attr"=>"multiple");
		$this->searchFeilds["partner_settle_abroad_arr"]=Array("feild"=>"settleAbroad","label"=>"Ready to settle abroad?","attr"=>"multiple","isDependant"=>"true");
		return $this->searchFeilds;
	}
	
	public function populateSelectedValues()
	{
		$cities=Array();
		if($this->selectedParams["GENDER"])
				$output["gender"]=$this->selectedParams["GENDER"];
			else
				$output["gender"] = TopSearchBandConfig::$femaleGenderValue;
			
		 foreach($this->fieldSearchQueryMapping as $key=>$value)
		 {
				switch($key)
				{
					case 'LAGE':
					case 'HAGE':
							$output[$value]=json_encode(array("VALUE"=>$this->selectedParams[$key],"LABEL"=>$this->selectedParams[$key]));
							break;
					case 'LHEIGHT':
					case 'HHEIGHT':
							$output[$value]=json_encode(array("VALUE"=>$this->selectedParams[$key],"LABEL"=>FieldMap::getFieldLabel("height_json",$this->selectedParams[$key])));
							break;
					case 'LINCOME':
							$val = ($this->selectedParams[$key]==0)?"Rs. 0":(FieldMap::getFieldLabel("lincome",$this->selectedParams[$key]));
							$output[$value]=json_encode(array("VALUE"=>$this->selectedParams[$key],"LABEL"=>$val));
							break;
					case 'HINCOME':
							$output[$value]=json_encode(array("VALUE"=>$this->selectedParams[$key],"LABEL"=>FieldMap::getFieldLabel("hincome",$this->selectedParams[$key])));
							break;
					case 'LINCOME_DOL':
							$val = ($this->selectedParams[$key]==0)?"$0":(FieldMap::getFieldLabel("lincome_dol",$this->selectedParams[$key]));
							$output[$value]=json_encode(array("VALUE"=>$this->selectedParams[$key],"LABEL"=>$val));
							break;
					case 'HINCOME_DOL':
							$output[$value]=json_encode(array("VALUE"=>$this->selectedParams[$key],"LABEL"=>FieldMap::getFieldLabel("hincome_dol",$this->selectedParams[$key])));
							break;
					case "CITY_INDIA":
								if($this->selectedParams[$key])
									$cities = array_unique(array_merge($cities,explode(",",$this->selectedParams[$key])));
								break;
					case "CITY_RES":
								if($this->selectedParams[$key])
									$cities = array_unique(array_merge($cities,explode(",",$this->selectedParams[$key])));
								break;
					case "STATE":
							if($this->selectedParams[$key])
									$states=$this->selectedParams[$key];
									break;	
					case "ONLINE":
								
					case "LAST_LOGIN_DT":
								if($this->selectedParams[$key])
								{
									$output[$value]= "1";
									//$output["openOption"]["MORE"]="1";
								}
								break;
					case "KEYWORD_TYPE":
								if($this->selectedParams[$key] && $this->selectedParams[$key]=="AND"){
									$output[$value]= $this->selectedParams[$key];
									$output["openOption"]["MORE"]="1";
								}
								break;
					case "KEYWORD":
								if($this->selectedParams[$key]){
									$output[$value]= $this->selectedParams[$key];
									$output["openOption"]["MORE"]="1";
								}
								break;
					case "HOROSCOPE":
								if($this->selectedParams[$key]){
									$output[$value]= $this->selectedParams[$key];
									$output["openOption"]["ASTRO"]="1";
								}
								break;
					default: 
						$output[$value]= $this->selectedParams[$key];
															
				}
			}
			
			if($states)
			{
				$statesArr = explode(",",$states);
				if(sizeof($cities)>0)
				{
					
					foreach($statesArr as $k=>$state)
					{
						$cityList = FieldMap::getFieldLabel("state_CITY", $state);
	          $cityList=explode(",",$cityList);
	          if(count(array_intersect($cityList, $cities)) == count($cityList))
	          {
							$cities = array_diff($cities,$cityList);
							$cities[]=$state;
							
						}else{
                                                        $cities[]=$state;    
                                                }
	          
					}
				}
				else
					$cities = array_merge($cities,$statesArr);
				
			}
			
			$output['partner_city_arr']=implode(",",$cities);
			
			$output['hideAstro']= $this->selectedParams["hideAstro"];
			foreach($this->getSearchSection() as $section=>$feilds)
			{
				
				if($section=="BASIC" || (is_array($output["openOption"][$section]) && in_array($section,$output["openOption"][$section])) )
					continue;
				foreach($feilds as $key=>$feild)
				{
						if($output[$feild])
						{
							$output["openOption"][$section]="1";
							break;
						}
				}
				
			}
			//print_r($output); die;
			return $output;
		
	}
}
?>

