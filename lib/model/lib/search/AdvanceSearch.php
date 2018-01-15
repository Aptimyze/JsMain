<?php
class AdvanceSearch extends SearchParamters
{	
	private $pid;

	public function __construct($loggedInProfileObj="")
	{
		
		if($loggedInProfileObj && $loggedInProfileObj->getPROFILEID())
			$this->pid = $loggedInProfileObj->getPROFILEID();
		parent::__construct();
	}
	
	public function getSearchCriteria($request)
	{
		

		$json = $request->getParameter('json');
		if($json)
		{
			$this->getnewJSPCSearchCriteria($request);
			return;
		}
		
		$formArr = $request->getParameterHolder()->getAll();
		
		
		if($formArr["Gender"])
			$this->setGENDER($formArr["Gender"]);
		if($formArr["Min_Age"])
                	$this->setLAGE($formArr["Min_Age"]);
		if($formArr["Max_Age"])
                	$this->setHAGE($formArr["Max_Age"]);
		if($formArr["Min_Height"])
                	$this->setLHEIGHT($formArr["Min_Height"]);
		if($formArr["Max_Height"])
                	$this->setHHEIGHT($formArr["Max_Height"]);
		if($formArr["partner_mstatus_arr"] && $formArr["partner_mstatus_arr"][0]!='DM')
			$this->setMSTATUS(implode(",",$formArr["partner_mstatus_arr"]));
		if($formArr["partner_hchild_arr"] && $formArr["partner_hchild_arr"][0]!='DM' && $this->getMSTATUS()!='N')
			$this->setHAVECHILD(implode(",",$formArr["partner_hchild_arr"]));
		if($formArr["Living_with_parents"] && $formArr["Living_with_parents"]!='DM' && $formArr["Gender"]=='M')			//Groom Search
			$this->setLIVE_PARENTS($formArr["Living_with_parents"]);

		if($formArr["partner_religion_arr"] && $formArr["partner_religion_arr"][0]!='DM')
		{
			foreach($formArr["partner_religion_arr"] as $k=>$v)
			{
				$tempArr = explode("|X|",$v);
				$formArr["partner_religion_arr"][$k] = $tempArr[0];
				unset($tempArr);
			}
			$this->setRELIGION(implode(",",$formArr["partner_religion_arr"]));
		}
		if($formArr["partner_mtongue_arr"] && $formArr["partner_mtongue_arr"][0]!='DM')
		{
			$mtongStr = "";
			foreach($formArr["partner_mtongue_arr"] as $k=>$v)
			{
				$mtongStr = $mtongStr.str_replace("|#|",",",$v).",";
			}
			$mtongStr = rtrim($mtongStr,",");
			foreach(SearchConfig::$advanceSearchMtongueHardCodeArray as $k=>$v)
			{
				$mtongStr = str_replace($v,$k,$mtongStr);
			}
			$mtongStrArr = explode(",",$mtongStr);
			$mtongStrArr = array_unique($mtongStrArr);
			$this->setMTONGUE(implode(",",$mtongStrArr));
			unset($mtongStrArr);
			unset($mtongStr);
		}
		if($formArr["partner_caste_arr"] && $formArr["partner_caste_arr"][0]!='DM' && ($this->getRELIGION()=='1' || $this->getRELIGION()=='4' || $this->getRELIGION()=='3' || $this->getRELIGION()=='9' || $this->getRELIGION()=='1,4' || $this->getRELIGION()=='1,9'))
			$this->setCASTE($formArr["partner_caste_arr"]);
                if($formArr["Sub_caste"] && ($this->getRELIGION()=='1' || $this->getRELIGION()=='1,4' || $this->getRELIGION()=='1,9'))
			$this->setSUBCASTE($formArr["Sub_caste"]);
		if($formArr["partner_manglik_arr"] && $formArr["partner_manglik_arr"][0]!='DM' && ($this->getRELIGION()=='1' || $this->getRELIGION()=='1,4' || $this->getRELIGION()=='1,9'))
			$this->setMANGLIK(implode(",",$formArr["partner_manglik_arr"]));
		if($formArr["Horoscope"] && $formArr["Horoscope"]!='DM' && ($this->getRELIGION()=='1' || $this->getRELIGION()=='1,4' || $this->getRELIGION()=='1,9'))
			$this->setHOROSCOPE("Y");
		if($formArr["muslim_deno"] && $formArr["muslim_deno"]!="DM" && $this->getRELIGION()=='2')
			$this->setCASTE($formArr["muslim_deno"]);					//MUSLIM SECT (radio button)
		if($formArr["partner_mathab_arr"] && $formArr["partner_mathab_arr"]!='DM' && $this->getRELIGION()=='2')
			$this->setMATHTHAB(implode(",",$formArr["partner_mathab_arr"]));		//MUSLIM
		if($formArr["speak_urdu"] && $formArr["speak_urdu"]!='DM' && $this->getRELIGION()=='2')
			$this->setSPEAK_URDU($formArr["speak_urdu"]);					//MUSLIM
		if($formArr["hijab"] && $formArr["hijab"]!='DM' && $this->getRELIGION()=='2' && $formArr["Gender"]=='F')
			$this->setHIJAB_MARRIAGE($formArr["hijab"]);					//MUSLIM
		if($formArr["amritdhari"] && $formArr["amritdhari"]!='DM' && ($this->getRELIGION()=='4' || $this->getRELIGION()=='1,4'))
			$this->setAMRITDHARI($formArr["amritdhari"]);					//SIKH RELIGION
		if($formArr["cut_hair"] && $formArr["cut_hair"]!='DM' && ($this->getRELIGION()=='4' || $this->getRELIGION()=='1,4'))
			$this->setCUT_HAIR($formArr["cut_hair"]);					//SIKH RELIGION
		if($formArr["zarathustri"] && $formArr["zarathustri"]!='DM' && $this->getRELIGION()=='5')
			$this->setZARATHUSHTRI($formArr["zarathustri"]);				//PARSI RELIGION
		if($formArr["partner_sampraday_arr"] && $formArr["partner_sampraday_arr"][0]!='DM' && ($this->getRELIGION()=='9' || $this->getRELIGION()=='1,9'))
			$this->setSAMPRADAY(implode(",",$formArr["partner_sampraday_arr"]));		//JAIN RELIGION
		if($formArr["working_wife"] && $formArr["working_wife"]!='DM' && $this->getRELIGION()=='2' && $formArr["Gender"]=='M')
			$this->setWIFE_WORKING($formArr["working_wife"]);				//Groom Search Muslim
		if($formArr["partner_turban_arr"] && $formArr["partner_turban_arr"][0]!='DM' && ($this->getRELIGION()=='4' || $this->getRELIGION()=='1,4') && $formArr["Gender"]=='M')
			$this->setWEAR_TURBAN(implode(",",$formArr["partner_turban_arr"]));		//Groom Search Sikh
		if($formArr["partner_wstatus_arr"] && $formArr["partner_wstatus_arr"][0]!='DM')
			$this->setWORK_STATUS(implode(",",$formArr["partner_wstatus_arr"]));
		if($formArr["partner_occupation_arr"] && $formArr["partner_occupation_arr"][0]!='DM')
			$this->setOCCUPATION(implode(",",$formArr["partner_occupation_arr"]));
		if($formArr["partner_education_arr"] && $formArr["partner_education_arr"][0]!='DM')
		{
			foreach($formArr["partner_education_arr"] as $k=>$v)
			{
				if(strpos($v,"|#|"))
					unset($formArr["partner_education_arr"][$k]);
			}
			$this->setEDU_LEVEL_NEW(implode(",",$formArr["partner_education_arr"]));
		}
		if($formArr["partner_country_arr"] && $formArr["partner_country_arr"][0]!='DM')
		{
			foreach($formArr["partner_country_arr"] as $k=>$v)
			{
				$tempArr = explode("#",$v);
                                $formArr["partner_country_arr"][$k] = $tempArr[0];

				if($tempArr[0]!=51)
					$india=1;
                                unset($tempArr);
			}
			$this->setCOUNTRY_RES(implode(",",$formArr["partner_country_arr"]));
			if($formArr["partner_city_arr"] && $formArr["partner_city_arr"][0]!='DM' && count($formArr["partner_country_arr"])==1 && $formArr["partner_country_arr"][0]==51)
			{ 
                                $state_arr=array();
                                $city_arr=array();
                                foreach($formArr["partner_city_arr"] as $key=>$res_value){
                                        if(strlen($res_value)>2)
                                                $city_arr[] =  $res_value;
                                        else
                                                $state_arr[] = $res_value;
                                }
                                $city_arr_selected = $city_arr;
                                $state_arr_selected = $state_arr;
                                
                                //Advance Search Prefill issue
                                $this->setCITY_RES_SELECTED(implode(",",$city_arr_selected));
				if($india!=1)
					$this->setCITY_INDIA_SELECTED(implode(",",$city_arr_selected));
                                if(count($state_arr_selected)>0)
					$this->setSTATE_SELECTED(implode(",",$state_arr_selected));
                                $city_arr_temp = $city_arr;
                                
                                //Auto State and city selection
                                /*foreach ($state_arr as $k=>$stateVal){
                                        if(FieldMap::getFieldLabel("state_CITY","",1)[$stateVal]){
                                                $city_from_state =  $this->cityStateConversion("",$stateVal); 
                                                $city_arr = array_merge($city_arr,$city_from_state);
                                                $city_arr = array_unique($city_arr);
                                        }
                                        
                                }
                                
                                foreach ($city_arr_temp as $k=>$cityVal){
                                        if(FieldMap::getFieldLabel("city_india","",1)[$cityVal]){
                                                $state_from_city =  $this->cityStateConversion($cityVal);
                                                $state_arr = array_merge($state_arr,$state_from_city);
                                                $state_arr = array_unique($state_arr);
                                        }
                                }*/
                                
                                //Setting Keys
                                $this->setCITY_RES(implode(",",$city_arr));
				if($india!=1)
					$this->setCITY_INDIA(implode(",",$city_arr));
                                if(count($state_arr)>0)
					$this->setSTATE(implode(",",$state_arr));
			}
		}
		if(($formArr["rsLIncome"] || $formArr["rsLIncome"]=='0') && ($formArr["rsHIncome"] || $formArr["rsHIncome"]=='0'))
		{
			$this->setLINCOME($formArr["rsLIncome"]);
			$this->setHINCOME($formArr["rsHIncome"]);
		}
		if(($formArr["doLIncome"] || $formArr["doLIncome"]=='0') && ($formArr["doHIncome"] || $formArr["doHIncome"]=='0'))
		{
			$this->setLINCOME_DOL($formArr["doLIncome"]);
			$this->setHINCOME_DOL($formArr["doHIncome"]);
		}

		if(($this->getLINCOME_DOL() || $this->getLINCOME_DOL() == '0') && ($this->getHINCOME_DOL() || $this->getHINCOME_DOL()=='0'))
		{
			if(($this->getLINCOME() || $this->getLINCOME() == '0') && ($this->getHINCOME() || $this->getHINCOME()=='0'))
			{
				$rArr["minIR"] = $this->getLINCOME();
				$rArr["maxIR"] = $this->getHINCOME();
				$dArr["minID"] = $this->getLINCOME_DOL();
				$dArr["maxID"] = $this->getHINCOME_DOL();
				$incomeType = "B";
				$incomeMappingObj = new IncomeMapping($rArr,$dArr);
				$incomeValues = $incomeMappingObj->getAllIncomes(1);
				unset($incomeMappingObj);
				$this->setINCOME(implode(",",$incomeValues));
			}
			else
			{
				$dArr["minID"] = $this->getLINCOME_DOL();
				$dArr["maxID"] = $this->getHINCOME_DOL();
				$incomeType = "D";
				$incomeMappingObj = new IncomeMapping("",$dArr);
				$incomeValues = $incomeMappingObj->getAllIncomes();
				unset($incomeMappingObj);
				$this->setINCOME(implode(",",$incomeValues));
			}
		}
		else
		{
			if(($this->getLINCOME() || $this->getLINCOME() == '0') && ($this->getHINCOME() || $this->getHINCOME()=='0'))
			{
				$rArr["minIR"] = $this->getLINCOME();
				$rArr["maxIR"] = $this->getHINCOME();
				$incomeType = "R";
				$incomeMappingObj = new IncomeMapping($rArr,"");
				$incomeValues = $incomeMappingObj->getAllIncomes();
				unset($incomeMappingObj);
				$this->setINCOME(implode(",",$incomeValues));
			}
		}
		
		if($formArr["partner_diet_arr"] && $formArr["partner_diet_arr"][0]!='DM')
			$this->setDIET(implode(",",$formArr["partner_diet_arr"]));
		if($formArr["partner_drink_arr"] && $formArr["partner_drink_arr"][0]!='DM')
			$this->setDRINK(implode(",",$formArr["partner_drink_arr"]));
		if($formArr["partner_smoke_arr"] && $formArr["partner_smoke_arr"][0]!='DM')
			$this->setSMOKE(implode(",",$formArr["partner_smoke_arr"]));
		if($formArr["partner_body_arr"] && $formArr["partner_body_arr"][0]!='DM')
			$this->setBTYPE(implode(",",$formArr["partner_body_arr"]));
		if($formArr["partner_complexion_arr"] && $formArr["partner_complexion_arr"][0]!='DM')
			$this->setCOMPLEXION(implode(",",$formArr["partner_complexion_arr"]));
		if($formArr["HIV"] && $formArr["HIV"]!='DM')
			$this->setHIV($formArr["HIV"]);
		if($formArr["partner_handicapped_arr"] && $formArr["partner_handicapped_arr"][0]!='DM')
		{
			$this->setHANDICAPPED(implode(",",$formArr["partner_handicapped_arr"]));
			if($formArr["partner_nhandicapped_arr"] && $formArr["partner_nhandicapped_arr"][0]!='DM' && (strstr($this->getHANDICAPPED(),"1") || strstr($this->getHANDICAPPED(),"2")))
				$this->setNATURE_HANDICAP(implode(",",$formArr["partner_nhandicapped_arr"]));
		}

                if($formArr["keywords"])
		{
			$this->setKEYWORD($formArr["keywords"]);
			if($formArr["kwd_rule"] && $this->getKEYWORD())
				$this->setKEYWORD_TYPE($formArr["kwd_rule"]);
		}
		if($formArr["Photo"])
			$this->setHAVEPHOTO($formArr["Photo"]);
		if($formArr["Login"] && $this->pid)
		{
			$dbName = JsDbSharding::getShardNo($this->pid);
                        $nlhObj = new NEWJS_LOGIN_HISTORY($dbName);
			$last_log_dt = $nlhObj->getLastLoginDate($this->pid);
			unset($nlhObj);
			if($last_log_dt)
				$this->setLAST_LOGIN_DT($last_log_dt);
		}
		if($formArr["Online"])
			$this->setONLINE("O");
                if($formArr["sort_by"])
		{
			if($formArr["sort_by"]=="S")			//Freshness
				$this->setSORT_LOGIC("O");
			else
				$this->setSORT_LOGIC($formArr["sort_by"]);
		}
		$this->setSEARCH_TYPE(SearchTypesEnums::Advance);
	}
        
        function cityStateConversion($city = '', $state = '') {
                if ($city) {
                        $city = explode(",", $city);
                        foreach ($city as $key => $value) {
                                $state[$key] = substr($value, 0, 2);
                        }
                        $state = array_unique($state);
                        return $state;
                } elseif ($state) {
                        $cityList = FieldMap::getFieldLabel("state_CITY", $state);
                        $cityList=explode(",",$cityList);
                        return $cityList;
                }
                return NULL;
        }
        
    private function getnewJSPCSearchCriteria($request)
    {
			
			$formArr = $request->getParameterHolder()->getAll();
		
		
				if($formArr["Gender"])
					$this->setGENDER($formArr["Gender"]);
				if($formArr["Min_Age"])
		                	$this->setLAGE($formArr["Min_Age"]);
				if($formArr["Max_Age"])
		                	$this->setHAGE($formArr["Max_Age"]);
				if($formArr["Min_Height"])
		                	$this->setLHEIGHT($formArr["Min_Height"]);
				if($formArr["Max_Height"])
		                	$this->setHHEIGHT($formArr["Max_Height"]);
				if($formArr["partner_mstatus_arr"])
					$this->setMSTATUS($formArr["partner_mstatus_arr"]);
				if($formArr["partner_hchild_arr"] && $this->getMSTATUS()!='N')
					$this->setHAVECHILD($formArr["partner_hchild_arr"]);
				if($formArr["partner_religion_arr"])
					$this->setRELIGION($formArr["partner_religion_arr"]);
				if($formArr["partner_mtongue_arr"])
				{
					$mtongue = explode(",",$formArr["partner_mtongue_arr"]);
					$mtongue = array_unique($mtongue);
					$this->setMTONGUE(implode(",",$mtongue));
					
				}
				if($formArr["partner_caste_arr"])
					$this->setCASTE($formArr["partner_caste_arr"]);
		    if($formArr["partner_manglik_arr"])
					$this->setMANGLIK($formArr["partner_manglik_arr"]);
				if($formArr["Horoscope"])
					$this->setHOROSCOPE("Y");
				if($formArr["partner_wstatus_arr"])
					$this->setMARRIED_WORKING($formArr["partner_wstatus_arr"]);
				if($formArr["partner_settle_abroad_arr"])
					$this->setGOING_ABROAD($formArr["partner_settle_abroad_arr"]);
				if($formArr["partner_occupation_arr"])
					$this->setOCCUPATION($formArr["partner_occupation_arr"]);
				if($formArr["partner_education_arr"])
				{
					$this->setEDU_LEVEL_NEW($formArr["partner_education_arr"]);
				}
				if($formArr["partner_country_arr"])
				{
					
		        
					$this->setCOUNTRY_RES($formArr["partner_country_arr"]);
					
						$city_resArr = explode(",",$formArr["partner_city_arr"]);
				     foreach($city_resArr as $v)
				     {           	
					                  
								if(ctype_alpha($v))
									$state_arr[] = $v;
								else
									$city_arr[] = $v;
				     	
							}
                                                        /*
							if($state_arr)
							{
								foreach ($state_arr as $k=>$stateVal){
									
									if(FieldMap::getFieldLabel("state_CITY","",1)[$stateVal]){
										
													$city_from_state =  $this->cityStateConversion("",$stateVal); 
													$city_arr = array_merge($city_arr,$city_from_state);
													$city_arr = array_unique($city_arr);
									}
											
								}
							}
							
							if($city_arr)
							{
								foreach ($city_arr as $k=>$cityVal){
									if(FieldMap::getFieldLabel("city_india","",1)[$cityVal]){
													$state_from_city =  $this->cityStateConversion($cityVal);
													$state_arr = array_merge($state_arr,$state_from_city);
													$state_arr = array_unique($state_arr);
									}
								}
								
								
							}
                                                        */
							if(is_array($state_arr))
								$this->setSTATE(implode(",",$state_arr));
							else
								$this->setSTATE($state_arr);
							if(is_array($city_arr))
							{
								$this->setCITY_INDIA(implode(",",array_unique($city_arr)));
								$this->setCITY_RES(implode(",",array_unique($city_arr)));
							}
							else
							{
								$this->setCITY_INDIA($city_arr);
								$this->setCITY_RES($city_arr);
							}
							
				}
				if(($formArr["rsLIncome"] || $formArr["rsLIncome"]=='0') && ($formArr["rsHIncome"] || $formArr["rsHIncome"]=='0'))
				{
					$this->setLINCOME($formArr["rsLIncome"]);
					$this->setHINCOME($formArr["rsHIncome"]);
				}
				if(($formArr["doLIncome"] || $formArr["doLIncome"]=='0') && ($formArr["doHIncome"] || $formArr["doHIncome"]=='0'))
				{
					$this->setLINCOME_DOL($formArr["doLIncome"]);
					$this->setHINCOME_DOL($formArr["doHIncome"]);
				}
		
				if(($this->getLINCOME_DOL() || $this->getLINCOME_DOL() == '0') && ($this->getHINCOME_DOL() || $this->getHINCOME_DOL()=='0'))
				{
					if(($this->getLINCOME() || $this->getLINCOME() == '0') && ($this->getHINCOME() || $this->getHINCOME()=='0'))
					{
						$rArr["minIR"] = $this->getLINCOME();
						$rArr["maxIR"] = $this->getHINCOME();
						$dArr["minID"] = $this->getLINCOME_DOL();
						$dArr["maxID"] = $this->getHINCOME_DOL();
						$incomeType = "B";
						$incomeMappingObj = new IncomeMapping($rArr,$dArr);
						$incomeValues = $incomeMappingObj->getAllIncomes(1);
						unset($incomeMappingObj);
						$this->setINCOME(implode(",",$incomeValues));
					}
					else
					{
						$dArr["minID"] = $this->getLINCOME_DOL();
						$dArr["maxID"] = $this->getHINCOME_DOL();
						$incomeType = "D";
						$incomeMappingObj = new IncomeMapping("",$dArr);
						$incomeValues = $incomeMappingObj->getAllIncomes();
						unset($incomeMappingObj);
						$this->setINCOME(implode(",",$incomeValues));
					}
				}
				else
				{
					if(($this->getLINCOME() || $this->getLINCOME() == '0') && ($this->getHINCOME() || $this->getHINCOME()=='0'))
					{
						$rArr["minIR"] = $this->getLINCOME();
						$rArr["maxIR"] = $this->getHINCOME();
						$incomeType = "R";
						$incomeMappingObj = new IncomeMapping($rArr,"");
						$incomeValues = $incomeMappingObj->getAllIncomes();
						unset($incomeMappingObj);
						$this->setINCOME(implode(",",$incomeValues));
					}
				}
				
				if($formArr["partner_diet_arr"])
					$this->setDIET($formArr["partner_diet_arr"]);
				if($formArr["partner_drink_arr"])
					$this->setDRINK($formArr["partner_drink_arr"]);
				if($formArr["partner_smoke_arr"])
					$this->setSMOKE($formArr["partner_smoke_arr"]);
				if($formArr["HIV"])
					$this->setHIV($formArr["HIV"]);
				if($formArr["partner_handicapped_arr"])
				{
					$this->setHANDICAPPED($formArr["partner_handicapped_arr"]);
					
				}
		    if($formArr["keywords"])
				{
					$this->setKEYWORD($formArr["keywords"]);
					// If Keyword rule is not provided we put OR operator by default
					$this->setKEYWORD_TYPE($formArr["kwd_rule"]?$formArr["kwd_rule"]:"OR");
				}
				if($formArr["HAVEPHOTO"])
					$this->setHAVEPHOTO($formArr["HAVEPHOTO"]);
				if($formArr["Login"] && $this->pid)
				{
					$dbName = JsDbSharding::getShardNo($this->pid);
		                        $nlhObj = new NEWJS_LOGIN_HISTORY($dbName);
					$last_log_dt = $nlhObj->getLastLoginDate($this->pid);
					unset($nlhObj);
					if($last_log_dt)
						$this->setLAST_LOGIN_DT($last_log_dt);
				}
				if($formArr["Online"])
					$this->setONLINE("O");
		    
				$this->setSEARCH_TYPE(SearchTypesEnums::Advance);
					
		}

}
?>
