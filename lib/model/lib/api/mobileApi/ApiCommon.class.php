<?php
//This is a common library for API module where common/general functions can be defined

class ApiCommon
{
	private $noTime = "0000-00-00 00:00:00";
	private $apiCacheTime = 86400;
	private $religionNotNeededInRegistration = 8;

	public function __construct()
	{
	}

	/*
	This function generates the table data for the tables passed as params
	@param - array with index as the table name and value as the last updated time present in the app
	@return - array with all the table data, last updated time value and result as yes/no
	*/
	public function getStaticTablesData($param)
	{
		$tableMapping =array("religion"=>"RELIGION","caste"=>"CASTE","country"=>"COUNTRY_NEW","city"=>"CITY_NEW","mtongue"=>"MTONGUE","education"=>"EDUCATION_LEVEL_NEW","education_grouping"=>"EDUCATION_GROUPING","occupation"=>"OCCUPATION","occupation_grouping"=>"OCCUPATION_GROUPING","height"=>"HEIGHT","income"=>"INCOME","hobby"=>"HOBBIES","sect"=>"SECT","state"=>"STATE_NEW","topCityIndia"=>"TOP_CITY_INDIA_NEW","jamaat"=>"JAMAAT");
                $memObject=JsMemcache::getInstance();
                $cachedTime = $memObject->getHashAllValue('STATIC_TABLES_CACHED_ON');
                $cachedData = $memObject->getHashAllValue('STATIC_TABLES_CACHED_DATA');
                if(empty($cachedTime)){
                        $gsObj = new GeneralStore;
                        $tableInfo = $gsObj->getTablesInformation("newjs",$tableMapping,1);
                        $memObject->setHashObject("STATIC_TABLES_CACHED_ON",$tableInfo,$this->apiCacheTime);
                }else{
                        $tableInfo = $cachedTime;
                }
		unset($gsObj);
		foreach($param as $k=>$v)
		{
                        $fromCache=0;
                        if($v==$this->noTime || strtotime($v)<strtotime($tableInfo[$tableMapping[$k]]))
                        {
                                if(isset($cachedData[$k]) && $cachedData[$k] != ""){
                                        $fromCache = 1;
                                        $output[$k]["result"] = "yes";
                                        $output[$k]["uptime"] = $tableInfo[$tableMapping[$k]];
                                        $output[$k]["data"] = unserialize($cachedData[$k]);
                                        continue;
                                }
                        }
			if($k=="religion")
			{
				if($v==$this->noTime || strtotime($v)<strtotime($tableInfo[$tableMapping[$k]]))
				{
					$output[$k]["result"] = "yes";
					$output[$k]["uptime"] = $tableInfo[$tableMapping[$k]];
					$nrObj = new NEWJS_RELIGION;
                                        $religionArr = $nrObj->getDATA();
                                        unset($nrObj);
					$i=0;
					foreach($religionArr as $kk=>$vv)
					{
						if($vv["VALUE"]!=$this->religionNotNeededInRegistration)
						{
							$tempArr[$i]["label"] = trim($vv["LABEL"]);
							$tempArr[$i]["value"] = $vv["VALUE"];
							$i++;
						}
					}
					$output[$k]["data"] = $tempArr;
					unset($tempArr);
					unset($religionArr);
				}
				else
				{
					$output[$k]["result"] = "no";
					$output[$k]["uptime"] = $v;
					$output[$k]["data"] = null;
				}
			}
                elseif($k=="jamaat")
                {
                                if($v==$this->noTime || strtotime($v)<strtotime($tableInfo[$tableMapping[$k]]))
                                {
                                        $output[$k]["result"] = "yes";
                                        $output[$k]["uptime"] = $tableInfo[$tableMapping[$k]];
					$jamaatArr = FieldMap::getFieldLabel("jamaat",'',1);
					$i=0;
					foreach($jamaatArr as $kk=>$vv)
					{
						$tempArr[$i]['label']=$vv;
						$tempArr[$i]['value']=$kk;
						$i++;
					}
					$output[$k]['data']=$tempArr;
					unset($tempArr);
                                }
				else
				{
                                        $output[$k]["result"] = "no";
                                        $output[$k]["uptime"] = $v;
                                        $output[$k]["data"] = null;
				}
                }

			elseif($k=="caste")
			{
				if($v==$this->noTime || strtotime($v)<strtotime($tableInfo[$tableMapping[$k]]))
				{
                                       $output[$k]["result"] = "yes";
                                       $output[$k]["uptime"] = $tableInfo[$tableMapping[$k]];
                                       $ncObj = new NEWJS_CASTE;
                                       $casteArr = $ncObj->getFullTableForRegistration();
                                       unset($ncObj);
                                       $i=0;
                                       foreach($casteArr as $kk=>$vv)
                                       {
                                               $tempArr[$i]["id"] = $vv["ID"];
                                               if(strstr($vv["LABEL"],":"))
                                               {
                                                       $tempArr[$i]["label"] = trim(ltrim(strstr($vv["LABEL"],":"),":"));
                                               }
                                               else
                                                       $tempArr[$i]["label"] = $vv["LABEL"];
                                               $tempArr[$i]["value"] = $vv["VALUE"];
                                               $tempArr[$i]["parent"] = $vv["PARENT"];
                                               $i++;
                                       }
                                       $output[$k]["data"] = $tempArr;
                                       unset($casteArr);
                                       unset($tempArr);
				}
				else
				{
					$output[$k]["result"] = "no";
					$output[$k]["uptime"] = $v;
					$output[$k]["data"] = null;
				}
			}
			elseif($k=="subcaste")			//Not Needed at present
			{
				/*
				if($v==$this->noTime || strtotime($v)<strtotime($tableInfo[$tableMapping[$k]]))
				{
					$output[$k]["result"] = "yes";
					$output[$k]["uptime"] = $tableInfo[$tableMapping[$k]];
					$nsObj = new NEWJS_SUBCASTE;
                                        $subcasteArr = $nsObj->getFullTable();
                                        unset($nsObj);
                                        $i=0;
                                        foreach($subcasteArr as $kk=>$vv)
                                        {
                                                $tempArr[$i]["id"] = $vv["ID"];
                                                $tempArr[$i]["label"] = $vv["LABEL"];
                                                $tempArr[$i]["related_caste_ids"] = $vv["RELATED_CASTE_IDS"];
                                                $i++;
                                        }
                                        $output[$k]["data"] = $tempArr;
                                        unset($subcasteArr);
                                        unset($tempArr);
				}
				else
				{
					$output[$k]["result"] = "no";
					$output[$k]["uptime"] = $v;
					$output[$k]["data"] = null;
				}
				*/
			}
			elseif($k=="country")
			{
				if($v==$this->noTime || strtotime($v)<strtotime($tableInfo[$tableMapping[$k]]))
				{
					$output[$k]["result"] = "yes";
					$output[$k]["uptime"] = $tableInfo[$tableMapping[$k]];
                    
                    //important country
                    $arrImpCountry=FieldMap::getFieldLabel("impcountry",'',1);
                    
					$ncObj = new NEWJS_COUNTRY_NEW;
                                        $countryArr = $ncObj->getFullTable();
                                        unset($ncObj);
                                        $i=0;
                                        foreach($countryArr as $kk=>$vv)
                                        {
                                                $tempArr[$i]["id"] = $vv["ID"];
                                                $tempArr[$i]["label"] = trim($vv["LABEL"]);
                                                $tempArr[$i]["value"] = $vv["VALUE"];
                                                $tempArr[$i]["isd_code"] = $vv["ISD_CODE"];
                                                $tempArr[$i]['is_imp'] = "false";
                                                if(array_key_exists($vv["VALUE"],$arrImpCountry))
                                                {
                                                    $tempArr[$i]['is_imp'] = "true";
                                                }
                                                $i++;
                                        }
                                        $output[$k]["data"] = $tempArr;
                                        unset($countryArr);
                                        unset($tempArr);
				}
				else
				{
					$output[$k]["result"] = "no";
					$output[$k]["uptime"] = $v;
					$output[$k]["data"] = null;
				}
			}
			elseif($k=="state")		//Not needed at present
			{
				if($v==$this->noTime || strtotime($v)<strtotime($tableInfo[$tableMapping[$k]]))
				{
					$output[$k]["result"] = "yes";
					$output[$k]["uptime"] = $tableInfo[$tableMapping[$k]];
					$newjsStateObj = new newjs_STATE_NEW;
					$output[$k]["data"] = $newjsStateObj->getStatesIndia();


				}
				else
				{
					$output[$k]["result"] = "no";
					$output[$k]["uptime"] = $v;
					$output[$k]["data"] = null;
				}
				
			}
			elseif($k=="city")
			{
				if($v==$this->noTime || strtotime($v)<strtotime($tableInfo[$tableMapping[$k]]))
				{
					$output[$k]["result"] = "yes";
					$output[$k]["uptime"] = $tableInfo[$tableMapping[$k]];
                    
                    //imp city
                    $arrImpCity=FieldMap::getFieldLabel("topindia_city",'',1);
                    $arrImp_IndianCity = array_flip(explode(",",$arrImpCity["51"]));
                    unset($arrImpCity);
                    
					$ncObj = new newjs_CITY_NEW;
					$countryCodes = Array("51","128","0");
                                        $cityArr = $ncObj->getCities($countryCodes);
                                        unset($ncObj);
                                        $i=0;
                                        foreach($cityArr as $kk=>$vv)
                                        {
                                                $tempArr[$i]["id"] = $vv["ID"];
                                                $tempArr[$i]["label"] = trim($vv["LABEL"]);
                                                $tempArr[$i]["value"] = $vv["VALUE"];
                                                $tempArr[$i]["state"] = $vv["STATE"];
                                                $tempArr[$i]["country_code"] = $vv["COUNTRY_VALUE"];
                                                $tempArr[$i]["is_imp"] = "false";
						$tempArr[$i]["imp_order"]  = "-1";
                                                if(array_key_exists($vv["VALUE"],$arrImp_IndianCity))
                                                {
                                                    $tempArr[$i]["is_imp"] = "true";
						    $tempArr[$i]["imp_order"] = $arrImp_IndianCity[$vv["VALUE"]]; //Order as mentioned in FieldMapLib
                                                }
                                                $i++;
                                        }
                                        $output[$k]["data"] = $tempArr;
                                        unset($cityArr);
                                        unset($tempArr);
				}
				else
				{
					$output[$k]["result"] = "no";
					$output[$k]["uptime"] = $v;
					$output[$k]["data"] = null;
				}
			}
			elseif($k=="mtongue")
			{
				if($v==$this->noTime || strtotime($v)<strtotime($tableInfo[$tableMapping[$k]]))
				{
					$output[$k]["result"] = "yes";
					$output[$k]["uptime"] = $tableInfo[$tableMapping[$k]];
					$nmObj = new NEWJS_MTONGUE;
                                        $mtongueArr = $nmObj->getFullTableForRegistration();
                                        unset($nmObj);
                                        $i=0;
                                        foreach($mtongueArr as $kk=>$vv)
                                        {
                                                $tempArr[$i]["id"] = $vv["ID"];
                                                $tempArr[$i]["label"] = $vv["SMALL_LABEL"];
                                                $tempArr[$i]["value"] = $vv["VALUE"];
						if($vv["REGION"] == 5)
                                                	$tempArr[$i]["region"] = TopSearchBandConfig::$allHindiLabel;
						else
                                                	$tempArr[$i]["region"] = FieldMap::getFieldLabel("mtongue_region_label",$vv["REGION"]);
                                                $i++;
                                        }
                                        $output[$k]["data"] = $tempArr;
                                        unset($mtongueArr);
                                        unset($tempArr);
				}
				else
				{
					$output[$k]["result"] = "no";
					$output[$k]["uptime"] = $v;
					$output[$k]["data"] = null;
				}
			}
			elseif($k=="education")
			{
				if($v==$this->noTime || strtotime($v)<strtotime($tableInfo[$tableMapping[$k]]) || strtotime($v)<strtotime($tableInfo[$tableMapping["education_grouping"]]))
                                {
					$output[$k]["result"] = "yes";
					if(strtotime($tableInfo[$tableMapping[$k]]) < strtotime($tableInfo[$tableMapping["education_grouping"]]))
                                        	$output[$k]["uptime"] = $tableInfo[$tableMapping["education_grouping"]];
					else
                                        	$output[$k]["uptime"] = $tableInfo[$tableMapping[$k]];
                                        $nelnObj = new NEWJS_EDUCATION_LEVEL_NEW;
                                        $eduArr = $nelnObj->getFullTable();
                                        unset($nelnObj);
                                        $i=0;
                                        foreach($eduArr as $kk=>$vv)
                                        {
                                                $tempArr[$i]["id"] = $vv["ID"];
                                                $tempArr[$i]["label"] = trim($vv["LABEL"]);
                                                $tempArr[$i]["value"] = $vv["VALUE"];
                                                $tempArr[$i]["group_name"] = $vv["GROUP_NAME"];
						if($vv["EDU_TYPE"])
							$tempArr[$i]["education_type"] = $vv["EDU_TYPE"];
						else
							$tempArr[$i]["education_type"] = null;
                                                $i++;
                                        }
                                        $output[$k]["data"] = $tempArr;
                                        unset($eduArr);
                                        unset($tempArr);
				}
				else
                                {
                                        $output[$k]["result"] = "no";
					$output[$k]["uptime"] = $v;
					$output[$k]["data"] = null;
                                }
			}
			elseif($k=="occupation")
			{
				if($v==$this->noTime || strtotime($v)<strtotime($tableInfo[$tableMapping[$k]]) || strtotime($v)<strtotime($tableInfo[$tableMapping["occupation_grouping"]]))
                                {
                                        $output[$k]["result"] = "yes";
                                        if(strtotime($tableInfo[$tableMapping[$k]]) < strtotime($tableInfo[$tableMapping["occupation_grouping"]]))
                                                $output[$k]["uptime"] = $tableInfo[$tableMapping["occupation_grouping"]];
                                        else
                                                $output[$k]["uptime"] = $tableInfo[$tableMapping[$k]];
                                        $noObj = new NEWJS_OCCUPATION;
                                        $occArr = $noObj->getFullTable();
                                        unset($noObj);
                                        $i=0;
                                        foreach($occArr as $kk=>$vv)
                                        {
                                                $tempArr[$i]["id"] = $vv["ID"];
                                                $tempArr[$i]["label"] = trim($vv["LABEL"]);
                                                $tempArr[$i]["value"] = $vv["VALUE"];
                                                $tempArr[$i]["group_name"] = $vv["GROUP_NAME"];
                                                $i++;
                                        }
                                        $output[$k]["data"] = $tempArr;
                                        unset($occArr);
                                        unset($tempArr);
                                }
                                else
                                {
                                        $output[$k]["result"] = "no";
					$output[$k]["uptime"] = $v;
					$output[$k]["data"] = null;
                                }
			}
			//occupation grouping being added 
			elseif($k=="occupation_grouping")
			{
				if($v==$this->noTime || strtotime($v)<strtotime($tableInfo[$tableMapping[$k]]) || strtotime($v)<strtotime($tableInfo[$tableMapping["occupation_grouping"]]))
                                {
                                        $output[$k]["result"] = "yes";
                                        if(strtotime($tableInfo[$tableMapping[$k]]) < strtotime($tableInfo[$tableMapping["occupation_grouping"]]))
                                                $output[$k]["uptime"] = $tableInfo[$tableMapping["occupation_grouping"]];
                                        else
                                                $output[$k]["uptime"] = $tableInfo[$tableMapping[$k]];
                                        $noObj = new NEWJS_OCCUPATION_GROUPING;
                                        $occArr = $noObj->getFullTable();
                                        
                                        unset($noObj);
                                        $i=0;
                                        foreach($occArr as $kk=>$vv)
                                        {                                                
                                                $tempArr[$i]["label"] = trim($vv["LABEL"]);
                                                $tempArr[$i]["value"] = $vv["VALUE"];                                                
                                                $i++;
                                        }
                                        $output[$k]["data"] = $tempArr;                                        
                                        unset($occArr);
                                        unset($tempArr);
                                }
                                else
                                {
                                	$output[$k]["result"] = "no";
                                	$output[$k]["uptime"] = $v;
                                	$output[$k]["data"] = null;
                                }
			}
			//occupation grouping code ends here
			elseif($k=="height")
			{
				if($v==$this->noTime || strtotime($v)<strtotime($tableInfo[$tableMapping[$k]]))
                                {
                                        $output[$k]["result"] = "yes";
                                        $output[$k]["uptime"] = $tableInfo[$tableMapping[$k]];
                                        $nhObj = new NEWJS_HEIGHT;
                                        $heightArr = $nhObj->getFullTable();
                                        unset($nhObj);
                                        $i=0;
                                        foreach($heightArr as $kk=>$vv)
                                        {
                                                $tempArr[$i]["id"] = $vv["ID"];
						//$tempArr1 = explode(" ",str_replace("&quot;","\"",$vv["LABEL"]));
                                                //$tempArr[$i]["label"] = $tempArr1[0].$tempArr1[1];
						//unset($tempArr1);
                                                $tempArr[$i]["label"] = str_replace("&quot;","\"",$vv["LABEL"]);
                                                $tempArr[$i]["value"] = $vv["VALUE"];
                                                $i++;
                                        }
                                        $output[$k]["data"] = $tempArr;
                                        unset($heightArr);
                                        unset($tempArr);
                                }
                                else
                                {
                                        $output[$k]["result"] = "no";
					$output[$k]["uptime"] = $v;
					$output[$k]["data"] = null;
                                }
			}
			elseif($k=="income")
			{
				if($v==$this->noTime || strtotime($v)<strtotime($tableInfo[$tableMapping[$k]]))
                                {
                                        $output[$k]["result"] = "yes";
                                        $output[$k]["uptime"] = $tableInfo[$tableMapping[$k]];
                                        $niObj = new NEWJS_INCOME;
                                        $incomeArr = $niObj->getFullTable();
                                        unset($niObj);
                                        $i=0;
                                        foreach($incomeArr as $kk=>$vv)
                                        {
                                                $tempArr[$i]["id"] = $vv["ID"];
                                                $tempArr[$i]["label"] = trim($vv["LABEL"]);
                                                $tempArr[$i]["value"] = $vv["VALUE"];
                                                $tempArr[$i]["type"] = $vv["TYPE"];
						if(!$vv["MIN_LABEL"] || $vv["MIN_LABEL"] == "No Income")
							$tempArr[$i]["min_label"] = "0";
						else
                                                	$tempArr[$i]["min_label"] = $vv["MIN_LABEL"];
                                                $tempArr[$i]["min_value"] = $vv["MIN_VALUE"];
						if(!$vv["MAX_LABEL"] || $vv["MAX_LABEL"] == "No Income")
							$tempArr[$i]["max_label"] = "0";
						else
                                                	$tempArr[$i]["max_label"] = $vv["MAX_LABEL"];
                                                $tempArr[$i]["max_value"] = $vv["MAX_VALUE"];
                                                $i++;
                                        }
                                        $output[$k]["data"] = $tempArr;
                                        unset($incomeArr);
                                        unset($tempArr);
                                }
                                else
                                {
                                        $output[$k]["result"] = "no";
					$output[$k]["uptime"] = $v;
					$output[$k]["data"] = null;
                                }
			}
			elseif($k=="hobby")
                        {
				if($v==$this->noTime || strtotime($v)<strtotime($tableInfo[$tableMapping[$k]]))
                                {
					$output[$k]["result"] = "yes";
                                        $output[$k]["uptime"] = $tableInfo[$tableMapping[$k]];
                                        $nhObj = new JHOBBYCacheLib;
                                        $hobbyArr = $nhObj->getHobbiesAndInterestAndSpokenLanguage();
                                        unset($nhObj);
                                        $i=0;
                                        foreach($hobbyArr as $kk=>$vv)
                                        {
                                                $tempArr[$i]["label"] = $vv["LABEL"];
                                                $tempArr[$i]["value"] = $vv["VALUE"];
                                                $tempArr[$i]["type"] = $vv["TYPE"];
                                                $i++;
                                        }
                                        $output[$k]["data"] = $tempArr;
                                        unset($hobbyArr);
                                        unset($tempArr);
				}
                                else
                                {
                                        $output[$k]["result"] = "no";
                                        $output[$k]["uptime"] = $v;
                                        $output[$k]["data"] = null;
                                }
			}
			elseif($k=="sect")
                        {
                                if($v==$this->noTime || strtotime($v)<strtotime($tableInfo[$tableMapping[$k]]))
                                {
					$output[$k]["result"] = "yes";
                                        $output[$k]["uptime"] = $tableInfo[$tableMapping[$k]];
                                        $nsObj = new NEWJS_SECT;
                                        $sectArr = $nsObj->getAllSects();
                                        unset($nsObj);
                                        $i=0;
                                        foreach($sectArr as $kk=>$vv)
                                        {
						if(strstr($vv["LABEL"],":"))
                                                        $tempArr[$i]["label"] = trim(ltrim(strstr($vv["LABEL"],":"),":"));
                                                else
                                                        $tempArr[$i]["label"] = $vv["LABEL"];
                                                $tempArr[$i]["value"] = $vv["VALUE"];
                                                $tempArr[$i]["parent"] = $vv["PARENT_RELIGION"];
                                                $i++;
                                        }
                                        $output[$k]["data"] = $tempArr;
                                        unset($sectArr);
                                        unset($tempArr);
				}
                                else
                                {
                                        $output[$k]["result"] = "no";
                                        $output[$k]["uptime"] = $v;
                                        $output[$k]["data"] = null;
                                }
                        }

            elseif($k="topCityIndia")
            {
            	if($v==$this->noTime || strtotime($v)<strtotime($tableInfo[$tableMapping[$k]]))
            	{
            		$output[$k]["result"] = "yes";
					$output[$k]["uptime"] = $tableInfo[$tableMapping[$k]];
					$topCityIndiaObj = new newjs_TOP_CITY_INDIA_NEW;
					$output[$k]["data"] = $topCityIndiaObj->getTopCitiesIndia();
				}
            	else
            	{
            		$output[$k]["result"] = "no";
					$output[$k]["uptime"] = $v;
					$output[$k]["data"] = null;
            	}
            }
			else
			{
				return null;
			}

                        if($output[$k]["data"]!=null && $fromCache !=1){
                                $memObject->setHashObject("STATIC_TABLES_CACHED_DATA",array($k=>serialize($output[$k]['data'])),$this->apiCacheTime);
                        }
		}

		foreach($output as $k=>$v)
		{
			
			foreach($v as $kk=>$vv)
			{
				if($kk=="data")
				{
					$i=1;
					foreach($vv as $kkk=>$vvv)
					{
						$output[$k][$kk][$kkk]["SORTBY"] = $i;
						$i++;
					}
				}
			}
		}
		return $output;
	}

	/*
        This function generates the search form data
        @param - array with index as the "searchForm" and value as the last updated time present in the app
        @return - array with all the table data, last updated time value and result as yes/no
        */
	public function getSearchFormData($param,$needMobileFormat=false,$forClusters="")
	{
		if(!$forClusters)
		{
			$tableMapping =array("RELIGION","CASTE","COUNTRY_NEW","CITY_NEW","MTONGUE");
			$gsObj = new GeneralStore;
			$tableInfo = $gsObj->getTablesInformation("newjs",$tableMapping,1);
			unset($gsObj);
                        if($needMobileFormat){
                          $tableInfo['MANGLIK'] = strtotime('now');
                          $tableInfo['EDUCATION'] = strtotime('now');
                          $tableInfo['OCCUPATION'] = strtotime('now');
                        }
		}

		foreach($param as $k=>$v)
                {
                        if($k=="searchForm")
                        {
				if($v==$this->noTime || strtotime($v)<strtotime($tableInfo["RELIGION"]) || strtotime($v)<strtotime($tableInfo["CASTE"]) || strtotime($v)<strtotime($tableInfo["COUNTRY_NEW"]) || strtotime($v)<strtotime($tableInfo["CITY_NEW"]) || strtotime($v)<strtotime($tableInfo["MTONGUE"]) || strtotime($v)<strtotime(TopSearchBandConfig::$searchFormDataLogicalChangeLatest))
				{
					$sortArr = array("RELIGION"=>strtotime($tableInfo["RELIGION"]),"CASTE"=>strtotime($tableInfo["CASTE"]),"COUNTRY_NEW"=>strtotime($tableInfo["COUNTRY_NEW"]),"CITY_NEW"=>strtotime($tableInfo["CITY_NEW"]),"MTONGUE"=>strtotime($tableInfo["MTONGUE"]),"SEARCH_LOGIC"=>strtotime(TopSearchBandConfig::$searchFormDataLogicalChangeLatest));
					arsort($sortArr);
					foreach($sortArr as $yoyo=>$xoxo)
					{
						$maxTimeField = $yoyo;
						break;
					}
					unset($sortArr);

					$output[$k]["result"] = "yes";
					if($maxTimeField == "SEARCH_LOGIC")
                                        	$output[$k]["uptime"] = TopSearchBandConfig::$searchFormDataLogicalChangeLatest;
					else
                                        	$output[$k]["uptime"] = $tableInfo[$maxTimeField];
					$parameters["forClusters"] = $forClusters;
					$topSearchBandObj = new TopSearchBandPopulate($parameters);
					if($needMobileFormat){
                                                if(MobileCommon::isDesktop()){
                                                        $dataArray = $topSearchBandObj->generateDataArrayPC();
                                                }else{
                                                        $dataArray = $topSearchBandObj->generateDataArrayMobile();
                                                }
                                        }else
						$dataArray = $topSearchBandObj->generateDataArrayApp();
					unset($topSearchBandObj);
					$output[$k]["data"] = $dataArray;
					unset($dataArray);
				}
				else
				{
					$output[$k]["result"] = "no";
                                        $output[$k]["uptime"] = $v;
					$output[$k]["data"] = null;
				}
			}
                        else
                        {
                                return null;
                        }
		}
		return $output;
	}

	    /**
    	* Function to Generate the Common URL
    	* @param sfRequest $request A request object
    	* @return string url
    	* @access public
    	* Note 
    	*/
	public static function getApiUrl($request,$moduleName="",$actionName="",$paramArr="",$nonSymfony="")
        {
                $url=JsConstants::$siteUrl."/api/".$request->getParameter("version");
                if($moduleName && $actionName)
                        $url=$url."/".$moduleName."/".$actionName;
                else if($nonSymfony)
                        $url=JsConstants::$siteUrl."/".$nonSymfony;
                if(is_array($paramArr))
                {
                        $url=$url."?";
                        foreach($paramArr as $key=>$val)
                        {
                                $url=$url.$key."=".$val.",";
                        }
                        trim($url,",");
                }
                return $url;
        }

	/**
    	* Function to return whether login trakcing is to be done or not on a URL
    	* @param sfRequest $module $action $version 
    	* @return bool 
    	* @access public
    	* Note 
    	*/
	public static function getTrackLoginFlag($moduleName='',$actionName='',$version='')
	{
		$trackingArray=RequestHandlerConfig::$trackLoginArray;
		if($moduleName && array_key_exists($moduleName,$trackingArray))
		{
			if($actionName && array_key_exists($actionName,$trackingArray[$moduleName]))
			{
				if($version && array_key_exists($version,$trackingArray[$moduleName][$actionName]))
				{
					if($trackingArray[$moduleName][$actionName][$version]=="N")
						return false;
					else
						return true;
				}
				else
					return true;
			}
			else
				return true;
		}
		else
			return true;
			
			
	}

}
?>
