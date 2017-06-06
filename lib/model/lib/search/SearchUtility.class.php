<?php
/*
 * @brief This class is used to set search paramters based on user input from top search band / Quick Seach Band.
 * @author Lavesh Rawat
 * @created 2012-07-25
*/
class SearchUtility
{	
	private $stypeCluster = SearchTypesEnums::Clusters;
	private $directStypeCluster = SearchTypesEnums::Quick;
	private $viewed = 'V';
	private $notViewed = 'N';
        private $genderMale = 'M';
        private $genderFemale = 'F';
	/*
	* This File is used to remove profiles(ignored/contacted....) from search results
	* @param SearchParamtersObj
	* @param seperator result profiles should be seperated by a identier( like comma(,) space( )....)
	* @param loggedInProfileObj profile object
	* @param profile to ignore is passed in the url (optional)
	* @param noAwaitingContacts exclude awaiting contacts.
	*/
	function removeProfileFromSearch($SearchParamtersObj,$seperator,$loggedInProfileObj,$profileFromUrl="",$noAwaitingContacts='',$removeMatchAlerts="",$notInArray = '',$showOnlineArr='',$getFromCache = 0,$tempContacts = "")
	{
		//print_r($SearchParamtersObj);die;
		if($profileFromUrl)
		{
			if($SearchParamtersObj->getIgnoreProfiles())
                             	$SearchParamtersObj->setIgnoreProfiles($SearchParamtersObj->getIgnoreProfiles()." ".$profileFromUrl);
                   	else
                              	$SearchParamtersObj->setIgnoreProfiles($profileFromUrl);
		}
		else
		{
			$pid = $loggedInProfileObj->getPROFILEID();
			if(get_class($SearchParamtersObj) == "FeaturedProfile")
			{
				$fplObj = new NEWJS_FEATURED_PROFILE_LOG(SearchConfig::getSearchDb());
				$profiles = $fplObj->getProfilesToIgnore($pid,$seperator);
				if($profiles)
				{
					if($SearchParamtersObj->getIgnoreProfiles())
						$SearchParamtersObj->setIgnoreProfiles($SearchParamtersObj->getIgnoreProfiles()." ".$profiles);
					else
						$SearchParamtersObj->setIgnoreProfiles($profiles);	
				}
				unset($fplObj);
			}
			else
			{
				if($pid)
				{
                                        if($getFromCache == 1){
                                                $memObject=JsMemcache::getInstance();
                                                $hideArr = $memObject->get('SEARCH_MA_IGNOREPROFILE_'.$pid);
                                        }
                                        if(!$hideArr){
                                                /* ignored profiles two way */
                                                $IgnoredProfilesObj = new IgnoredProfiles();
                                                $hideArr = $IgnoredProfilesObj->listIgnoredProfile($pid,$seperator);

                                                /* contacted profiles */
                                                $Obj = new ContactsRecords;
                                                $hideArr.= $Obj->getContactsList($pid,$seperator,$noAwaitingContacts);
                                                
                                                if($getFromCache == 1){
                                                       $memObject->set('SEARCH_MA_IGNOREPROFILE_'.$pid,$hideArr,SearchConfig::$matchAlertCacheLifetime);
                                                }
                                        }
					/** matchAlerts Profile **/
					
					if($removeMatchAlerts)
					{
						$matchalerts_LOG = new matchalerts_LOG();
						$hideArr.= $matchalerts_LOG->getProfilesSentInMatchAlerts($pid,$seperator);
					}


					
					$request = sfContext::getInstance()->getRequest();	
					if($request->getParameter('hitFromMyjs') == 1 && $request->getParameter('caching') == 0)
					{	
					$stype = $request->getParameter('stype');	
					$listnameForMyjs = $request->getParameter('listingName');
					$cacheCriteria = MyjsSearchTupplesEnums::getListNameForCaching($listnameForMyjs);


	                                        $forNextPrev = JsMemcache::getInstance()->get("cached".$cacheCriteria."Myjs".$pid);
						$forNextPrev = unserialize($forNextPrev);
						if(is_array($forNextPrev))
						{	
							$forNextPrevTemp = $forNextPrev;
							$forNextPrev = implode(" ",$forNextPrevTemp);
							$hideArr.= $forNextPrev;
						
						}
					}
					
				}
				// adding code to remove temporary contacts sent by the user while the user is unscreened.
				if($tempContacts)
				{		
					$contactsTempObj =  new NEWJS_CONTACTS_TEMP(SearchConfig::getSearchDb());
					$hideArr.= $contactsTempObj->getTempContactProfilesForUser($pid,$seperator);
				}
				if($SearchParamtersObj->getONLINE()==SearchConfig::$onlineSearchFlag)
				/* For Online search  */
				{
					$ChatLibraryObj = new ChatLibrary(SearchConfig::getSearchDb());
					$tempArr = $ChatLibraryObj->findOnlineProfiles($seperator,$SearchParamtersObj);
					$SearchParamtersObj->setOnlineProfiles($tempArr);
					unset($tempArr);
				}
				if($SearchParamtersObj->getVIEWED() && $pid)
				/*Viewed - Not Viewed Clusters */
				{
					$ViewedLogObj = new ViewedLog;
					if($SearchParamtersObj->getVIEWED()==$this->viewed)
					{
						$showArrCluster=1;
						$showArr.= $ViewedLogObj->findViewedProfiles($pid,$seperator);
					}
					elseif($SearchParamtersObj->getVIEWED()==$this->notViewed)
						$hideArr.= $ViewedLogObj->findViewedProfiles($pid,$seperator);
				}
				if( ($SearchParamtersObj->getMATCHALERTS_DATE_CLUSTER() || $SearchParamtersObj->getKUNDLI_DATE_CLUSTER())&& $pid)
				{
					$alreadyInShowStr = $SearchParamtersObj->getProfilesToShow();
					$SearchParamtersObj->setProfilesToShow('');
					$alreadyInShowArr = explode(" ",$alreadyInShowStr);
					if($showArrCluster==1 && $showArr)
					{
						unset($alreadyInShowArr1);
						$alreadyInShowArr1 = explode(" ",$showArr);
						if($alreadyInShowArr && $alreadyInShowArr[0]!='')
						{
							$alreadyInShowArr = array_intersect($alreadyInShowArr1,$alreadyInShowArr);
						}
						else			
							$alreadyInShowArr = $alreadyInShowArr1;
					}
					if($SearchParamtersObj->getMATCHALERTS_DATE_CLUSTER())
					{
						
						$week= $SearchParamtersObj->getMATCHALERTS_DATE_CLUSTER();
						if($week=='All')
							$week='';
						else
						{
							$weekArr = explode(",",$week);
							rsort($weekArr);
							$week = $weekArr[0];
						}
						//if($week || $SearchParamtersObj->getNEWSEARCH_CLUSTERING() || ($_GET["moreLinkCluster"] && in_array($_GET["moreLinkCluster"],array('OCCUPATION','EDU_LEVEL_NEW'))))
						//{
							//$MatchAlerts = new MatchAlerts();
							//$matArr1 = $MatchAlerts->getProfilesWithOutSortishowOnlineArrng($pid,$week);
						//}
						//else
							//$matArr1 = $SearchParamtersObj->getAlertsDateConditionArr();
							
						//if($week=='')
						//	$matArr1 = $SearchParamtersObj->getAlertsDateConditionArr();
						//else{
								$MatchAlerts = new MatchAlerts();
							$matArr1 = $MatchAlerts->getProfilesWithOutSorting($pid,$week);
						//}
							
							
					}
					else
						$matArr1 = KundliAlerts::getProfilesWithOutSorting($pid);
					if(is_array($matArr1))
						$matArr = array_keys($matArr1);
			                $SearchParamtersObj->setAlertsDateConditionArr($matArr1);
					unset($matArr1);
					if($matArr)
					{
						if($alreadyInShowArr && $alreadyInShowArr[0]!='')
						{
							$intersectArr = array_intersect($matArr,$alreadyInShowArr);
							if($intersectArr)
								$showArr= implode(" ",$intersectArr);
							else
								$showArr = '0 0';
						}
						else
						{
							if($showArrCluster==1)
								$showArr = '0 0';
							else
								$showArr= implode(" ",$matArr);
						}
					}
					else
						$showArr = '0 0';
				}
                                //remove profiles for AP cron
                                if($notInArray)
                                {
                                        $hideArr.= $notInArray;
                                }
				if($hideArr)
				{
					if($SearchParamtersObj->getIgnoreProfiles())
						$SearchParamtersObj->setIgnoreProfiles($SearchParamtersObj->getIgnoreProfiles()." ".$hideArr);
					else
						$SearchParamtersObj->setIgnoreProfiles($hideArr);
				}
				if($showOnlineArr)
				{
					$showArr.= " ".$showOnlineArr;
				}
				if($showArr)
				{
					if($SearchParamtersObj->getProfilesToShow())
						$SearchParamtersObj->setProfilesToShow($SearchParamtersObj->getProfilesToShow()." ".$showArr);
					else
						$SearchParamtersObj->setProfilesToShow($showArr);
				}
			}
		}
	} 

	/*
        * Sets SearchParamtersObj corresponding to the user action on search clusters
        * @param $request request array
        * @param $addRemoveCluster cluster is added or removed
        * @param $SearchParamtersObj object array of search parameters.
        */
	public function getSearchCriteriaAfterClusterApplication($request,$addRemoveCluster,$SearchParamtersObj)
	{
		
		$searchParamsSetter['SEARCH_TYPE']= $this->stypeCluster;

		if($request->getParameter("appCluster"))
		{
			$solr_labels = FieldMap::getFieldLabel("solr_clusters",1,1);
			$cluster = $solr_labels[$request->getParameter("appCluster")];
			if($request->getParameter("dollar")==1)
				$cluster=$cluster."_DOL";
			$clusterVal = $request->getParameter("appClusterVal");
			if($cluster == "MANGLIK" && $clusterVal != 'ALL'){ // check for cluster only search for not adding dont know to 'not manglik'
                            if($clusterVal!='')
					$clusterVal .= ','.SearchTypesEnums::APPLY_ONLY_CLUSTER;
			}
			if($cluster == "DIET" && $clusterVal != 'ALL'){ // check for cluster only search for not adding dont know to 'not manglik'
                            if($clusterVal!='')
					$clusterVal .= ','.SearchTypesEnums::APPLY_ONLY_CLUSTER;
			}
      if($cluster=='MATCHALERTS_DATE_CLUSTER' && $clusterVal==NULL)
				$clusterVal = 'ALL';
			if(MobileCommon::isApp()=='A')
				$searchParamsSetter['SEARCH_TYPE']= SearchTypesEnums::AppClusters;
			elseif(MobileCommon::isApp()=='I')
				$searchParamsSetter['SEARCH_TYPE']= SearchTypesEnums::IosClusters;
			else
			{
				$fromPc = 1;
				$searchParamsSetter['SEARCH_TYPE']= SearchTypesEnums::Clusters;
			}
		}
		else
		{
			$cluster = $request->getParameter("NEWSEARCH_CLUSTERING");
			$clusterValArr = $request->getParameter("selectedClusterArr");
			if($cluster=='MATCHALERTS_DATE_CLUSTER' && $clusterValArr=='[]')
				$clusterValArr = '["ALL"]';
		}

		$fromMoreLayerCluster = $request->getParameter("fromMoreLayerCluster");		

		if($addRemoveCluster=='json')
			$clusterValArr = json_decode($clusterValArr,true);
		if(is_array($clusterValArr))
			$clusterVal = implode(",",$clusterValArr);
		if($SearchParamtersObj->getNEWSEARCH_CLUSTERING())
			$list_of_clusters = explode(",",$SearchParamtersObj->getNEWSEARCH_CLUSTERING());
		$clusterGetter = "get".$cluster;
                
		if($clusterVal == 'ALL')
		{
			if($cluster != 'MATCHALERTS_DATE_CLUSTER' && $cluster != 'KUNDLI_DATE_CLUSTER')
				$searchParamsSetter[$cluster]='';
			/*-untested- */
			if(!$list_of_clusters && $cluster)
				$list_of_clusters[] = $cluster;
			
			if(is_array($list_of_clusters) && count($list_of_clusters)>0)
			{
				$list_of_clusters = array_diff($list_of_clusters,array($cluster));

				if(is_array($list_of_clusters) && count($list_of_clusters)>0)
					$searchParamsSetter['NEWSEARCH_CLUSTERING'] = implode(",",$list_of_clusters);
				else
					$searchParamsSetter['NEWSEARCH_CLUSTERING'] = '';

				if($cluster == 'LAST_ACTIVITY')
					$searchParamsSetter['Online']='';
				elseif($cluster == 'INDIA_NRI')
				{
					$searchParamsSetter['COUNTRY_RES']='';
					$searchParamsSetter['STATE']='';
					$searchParamsSetter['CITY_RES']='';
				}
                                elseif($cluster == 'COUNTRY_RES'){
					$searchParamsSetter['STATE']='';
					$searchParamsSetter['CITY_RES']='';
                                }elseif($cluster == 'STATE'){
					$searchParamsSetter['CITY_RES']='';
                                }elseif($cluster=='OCCUPATION_GROUPING')
					$searchParamsSetter['OCCUPATION']='';
				elseif($cluster=='EDUCATION_GROUPING')
					$searchParamsSetter['EDU_LEVEL_NEW']='';
				elseif($cluster == 'RELIGION')
				{
					$searchParamsSetter['CASTE']='';
					$searchParamsSetter['CASTE_GROUP']='';
				}
				elseif($cluster == 'CASTE_GROUP')
					$searchParamsSetter['CASTE']='';
				elseif($cluster == 'MSTATUS')
					$searchParamsSetter['HAVECHILD']='';
				elseif($cluster=='MATCHALERTS_DATE_CLUSTER')
				{
					$searchParamsSetter['NEWSEARCH_CLUSTERING'] = 'MATCHALERTS_DATE_CLUSTER';
					$searchParamsSetter['MATCHALERTS_DATE_CLUSTER']='All';		
				}
			}
		}
		else
		{
			if(!is_array($list_of_clusters) || (is_array($list_of_clusters) && !in_array($cluster,$list_of_clusters)) )
				$list_of_clusters[] = $cluster;
			$searchParamsSetter['NEWSEARCH_CLUSTERING'] = implode(",",$list_of_clusters);

			if(strstr($clusterVal,'$'))
			{
				$temp = explode("$",$clusterVal);
				if($temp[0]=="-" && $temp[1]=="-")
				{
					$searchParamsSetter["L".$cluster]="";
					$searchParamsSetter["H".$cluster]="";
				}
				else
				{
					$searchParamsSetter["L".$cluster]=$temp[0];
					$searchParamsSetter["H".$cluster]=$temp[1];
				}
				if($cluster == "INCOME")
				{
					if($temp[0]=="-" && $temp[1]=="-")	//If Rupee checkbox is unclicked i.e. dont use Rupee parameter
					{
						if(($SearchParamtersObj->getLINCOME_DOL() || $SearchParamtersObj->getLINCOME_DOL() == '0') && ($SearchParamtersObj->getHINCOME_DOL() || $SearchParamtersObj->getHINCOME_DOL()=='0'))		//If dollar values exist then perform mapping
						{
							$dArr["minID"] = $SearchParamtersObj->getLINCOME_DOL();
							$dArr["maxID"] = $SearchParamtersObj->getHINCOME_DOL();
							$incomeType = "D";
							$incomeMappingObj = new IncomeMapping("",$dArr);
							$incomeValues = $incomeMappingObj->getAllIncomes();
							unset($incomeMappingObj);
							$searchParamsSetter[$cluster]=implode(",",$incomeValues);
						}
						else		//If dollar values does'nt exist then remove Income parameter
						{
							$searchParamsSetter[$cluster]="";
						}
					}
					else		//If Rupee checkbox is clicked
					{
						if(($SearchParamtersObj->getLINCOME_DOL() || $SearchParamtersObj->getLINCOME_DOL() == '0') && ($SearchParamtersObj->getHINCOME_DOL() || $SearchParamtersObj->getHINCOME_DOL()=='0'))	//If dollar values exist then perform independent search
						{
							$rArr["minIR"] = $searchParamsSetter["L".$cluster];
							$rArr["maxIR"] = $searchParamsSetter["H".$cluster];
							$dArr["minID"] = $SearchParamtersObj->getLINCOME_DOL();
							$dArr["maxID"] = $SearchParamtersObj->getHINCOME_DOL();
							$incomeType = "B";
							$incomeMappingObj = new IncomeMapping($rArr,$dArr);
							$incomeValues = $incomeMappingObj->getAllIncomes(1);
							unset($incomeMappingObj);
							$searchParamsSetter[$cluster]=implode(",",$incomeValues);
						}
						else	//If dollar values does not exist then perform mapping
						{
							$rArr["minIR"] = $searchParamsSetter["L".$cluster];
							$rArr["maxIR"] = $searchParamsSetter["H".$cluster];
							$incomeType = "R";
							$incomeMappingObj = new IncomeMapping($rArr,"");
							$incomeValues = $incomeMappingObj->getAllIncomes();
							unset($incomeMappingObj);
							$searchParamsSetter[$cluster]=implode(",",$incomeValues);
						}
					}
				}
				elseif($cluster == "INCOME_DOL")
				{
					if($temp[0]=="-" && $temp[1]=="-")	//If dollar checkbox is unclicked
					{
						if(($SearchParamtersObj->getLINCOME() || $SearchParamtersObj->getLINCOME() == '0') && ($SearchParamtersObj->getHINCOME() || $SearchParamtersObj->getHINCOME()=='0'))		//If rupee values exist then perform mapping
						{
							$rArr["minIR"] = $SearchParamtersObj->getLINCOME();
							$rArr["maxIR"] = $SearchParamtersObj->getHINCOME();
							$incomeType = "R";
							$incomeMappingObj = new IncomeMapping($rArr,"");
							$incomeValues = $incomeMappingObj->getAllIncomes();
							unset($incomeMappingObj);
							$searchParamsSetter["INCOME"]=implode(",",$incomeValues);
						}
						else	//If rupee values does not exist then remove INCOME parameter
						{
							$searchParamsSetter["INCOME"]="";
						}
					}
					else		//If dollar checkbox is clicked
					{
						if(($SearchParamtersObj->getLINCOME() || $SearchParamtersObj->getLINCOME() == '0') && ($SearchParamtersObj->getHINCOME() || $SearchParamtersObj->getHINCOME()=='0'))		//If rupee value exists then perform independent search
						{
							$rArr["minIR"] = $SearchParamtersObj->getLINCOME();
							$rArr["maxIR"] = $SearchParamtersObj->getHINCOME();
							$dArr["minID"] = $searchParamsSetter["L".$cluster];
							$dArr["maxID"] = $searchParamsSetter["H".$cluster];
							$incomeType = "B";
							$incomeMappingObj = new IncomeMapping($rArr,$dArr);
							$incomeValues = $incomeMappingObj->getAllIncomes(1);
							unset($incomeMappingObj);
							$searchParamsSetter["INCOME"]=implode(",",$incomeValues);
						}
						else	//If rupee value does not exist then perform mapping
						{
							$dArr["minID"] = $searchParamsSetter["L".$cluster];
							$dArr["maxID"] = $searchParamsSetter["H".$cluster];
							$incomeType = "D";
							$incomeMappingObj = new IncomeMapping("",$dArr);
							$incomeValues = $incomeMappingObj->getAllIncomes();
							unset($incomeMappingObj);
							$searchParamsSetter["INCOME"]=implode(",",$incomeValues);
						}
					}
				}
			}
			else
			{
				/**
				* If NCR is choosen in state then we need to map all state and city correspoinding to it
				*/
				if(strstr($clusterVal,'NCR') && $cluster=='STATE')
				{
					$ncrC = FieldMap::getFieldLabel('delhiNcrCities','',1);
					$ncrS = FieldMap::getFieldLabel('delhiNcrStates','',1);
					$temp = implode(",",$ncrS);
					$clusterVal = str_replace("NCR","NCR,".$temp,$clusterVal);
					$city = $SearchParamtersObj->getCITY_RES();
					if($city && $city!='DONT_MATTER')
						$city = $city.",".implode(",",$ncrC);
					else
						$city = implode(",",$ncrC);

					$city = $this->str_to_array_unique($city);
					$searchParamsSetter['CITY_RES']=$city;
				}
				if($cluster=='HANDICAPPED')
				{
					$clusterVal = $this->str_to_array_unique($clusterVal);
				}
				/**
				* If METRO is choosen in state then we need to map all city correspoinding to it
				*/
				if(strstr($clusterVal,'METRO') && $cluster=='CITY_RES')
				{
					$delmetro = FieldMap::getFieldLabel('allMetros','',1);									     
					$temp = implode(",",$delmetro);	
					/**/
					$forCityCluster = $request->getParameter("forCityCluster");
					if($forCityCluster)
					{
						$tempForCityCluster = explode(",",$forCityCluster);
						foreach($tempForCityCluster as $k=>$v)
							$tempForCityCluster_2[$v]+=1;

						unset($tempForCityCluster);
						foreach($tempForCityCluster_2 as $k=>$v)
							if($v%2!=0)
								$tempForCityCluster[]=$k;
						if($tempForCityCluster)
						foreach($tempForCityCluster as $k=>$v)
						{
							if(in_array($v,$delmetro))
								$clusterToCheck[] = $v;
						}
						if(is_array($clusterToCheck))
						{
							$tempForCityCluster2 = explode(",",$clusterVal);
							foreach($clusterToCheck as $k=>$v)
								if(!in_array($v,$tempForCityCluster2))
								{
									$flagRemoveAllMtero = 1	;
								}
						}
						if($flagRemoveAllMtero)
						{
							 $clusterVal = str_replace(",METRO,","",$clusterVal);
							 $clusterVal = str_replace("METRO,","",$clusterVal);
							 $clusterVal = str_replace(",METRO","",$clusterVal);
							 $clusterVal = str_replace("METRO","",$clusterVal);
						}
					}
					/**/
					if(strstr($clusterVal,'METRO'))
					{
						$clusterVal = str_replace("METRO","METRO,".$temp,$clusterVal);
						if($clusterVal)
							$clusterVal=$clusterVal.",".implode(",",$delmetro);
						else
							$clusterVal.=@implode(",",$delmetro);
					}
					$clusterVal = $this->str_to_array_unique($clusterVal);
				}
				if($cluster=='OCCUPATION_GROUPING' || $cluster=='EDUCATION_GROUPING')
				{
					$fieldMapV = ($cluster=='OCCUPATION_GROUPING')?"occupation_grouping_mapping_to_occupation":"education_grouping_mapping_to_edu_level_new";
					$mappedArr = FieldMap::getFieldLabel($fieldMapV,1,1);
					if(strstr($clusterVal,'@'))
					{
						$tempArr = explode(",",$clusterVal);
						foreach($tempArr as $v)
						{
							if(strstr($v,'@'))
							{
								$temp1[] = rtrim($v,'@');
								$groupVals[] = $mappedArr[rtrim($v,'@')];
							}
							else
								$temp2[] = $v;
						}
						$groupVals = explode(",",implode(",",$groupVals));
						$temp1 = implode(",",$temp1);
						$temp2 = implode(",",array_diff($temp2,$groupVals));
						if($temp1)
							$clusterVal = $temp1;

						if($temp1)
						{
							if($cluster=='OCCUPATION_GROUPING')
									$searchParamsSetter['OCCUPATION'] = $temp2;
							if($cluster=='EDUCATION_GROUPING')
									$searchParamsSetter['EDU_LEVEL_NEW'] = $temp2;		
							
						}
						unset($temp1);unset($temp2);unset($tempArr);
					}
					elseif($fromMoreLayerCluster || ($request->getParameter("appCluster") && !$fromPc) )
					{
						if($cluster=='OCCUPATION_GROUPING')
							$searchParamsSetter['OCCUPATION'] = $clusterVal;
						if($cluster=='EDUCATION_GROUPING')
							$searchParamsSetter['EDU_LEVEL_NEW'] = $clusterVal;	
						$clusterVal='';
					}
				}
                                if($cluster=='COUNTRY_RES'){
                                        $selectedVAl = explode(",",$clusterVal);
                                        if(!in_array(51, $selectedVAl)){
                                                $searchParamsSetter['STATE']='';
                                                $searchParamsSetter['CITY_RES']='';
                                        }
                                }
                                if($cluster=='STATE'){
                                        $searchParamsSetter['CITY_RES']='';
                                }
				$searchParamsSetter[$cluster]=$clusterVal;
			}
		}
		//print_r($searchParamsSetter); die;
//die;
                if($cluster == "CITY_RES"){
                        $SearchParamtersObj->setter($searchParamsSetter,2);
                }else{
                        $SearchParamtersObj->setter($searchParamsSetter);
                }
		//return $SearchParamtersObj;
	}
	
	/* Perform search when searchurl is called directly*/
	public function directSeachUrl($loggedInProfileObj="")
	{
		$SearchParamtersObj =  new SearchParamters;
		$searchParamsSetter['SEARCH_TYPE'] = $this->directStypeCluster;

                if($loggedInProfileObj && $loggedInProfileObj->getPROFILEID() && $loggedInProfileObj->getGENDER()==$this->genderFemale)
			$searchParamsSetter['GENDER'] = $this->genderMale;
		else
			$searchParamsSetter['GENDER'] = $this->genderFemale;

		$SearchParamtersObj->setter($searchParamsSetter);
		return $SearchParamtersObj;
	}

	/*This function is called to set the parameters passed in the url*/
	public function setParametersPassedThroughUrl($request,$SearchParamtersObj)
	{
		if($request)
		{
			if($request->getParameter("lage") && !$SearchParamtersObj->getLAGE())
				$SearchParamtersObj->setLAGE($request->getParameter("lage"));
			if($request->getParameter("hage") && !$SearchParamtersObj->getHAGE())
				$SearchParamtersObj->setHAGE($request->getParameter("hage"));
			if($request->getParameter("gender"))
				$SearchParamtersObj->setGENDER($request->getParameter("gender"));
			if($request->getParameter("havePhoto") && !$SearchParamtersObj->getHAVEPHOTO())
				$SearchParamtersObj->setHAVEPHOTO($request->getParameter("havePhoto"));
			if($request->getParameter("onlineArr") && !$SearchParamtersObj->getONLINE())
                        	$SearchParamtersObj->setONLINE(SearchConfig::$onlineSearchFlag);
                	if($request->getParameter("STYPE"))
                        	$SearchParamtersObj->setSEARCH_TYPE($request->getParameter("STYPE"));
                	if($request->getParameter("caste") && !$SearchParamtersObj->getCASTE())
                        	$SearchParamtersObj->setCASTE($request->getParameter("caste"));
                	if($request->getParameter("mtongue") && !$SearchParamtersObj->getMTONGUE())
                        	$SearchParamtersObj->setMTONGUE($request->getParameter("mtongue"));
                	if($request->getParameter("privacy") && !$SearchParamtersObj->getPRIVACY())
                        	$SearchParamtersObj->setPRIVACY($request->getParameter("privacy"));
                	if($request->getParameter("photo_display") && !$SearchParamtersObj->getPHOTO_DISPLAY())
                        	$SearchParamtersObj->setPHOTO_DISPLAY($request->getParameter("photo_display"));
                	if($request->getParameter("ignoreProfile"))
                        	$this->removeProfileFromSearch($SearchParamtersObj,"","",$request->getParameter("ignoreProfile"));
			 if($request->getParameter("lincome") && !$SearchParamtersObj->getLINCOME())
                                $SearchParamtersObj->setLINCOME($request->getParameter("lincome"));
                        if($request->getParameter("hincome") && !$SearchParamtersObj->getHINCOME())
                                $SearchParamtersObj->setHINCOME($request->getParameter("hincome"));
			 if($request->getParameter("lheight") && !$SearchParamtersObj->getLHEIGHT())
                                $SearchParamtersObj->setLHEIGHT($request->getParameter("lheight"));
                        if($request->getParameter("hheight") && !$SearchParamtersObj->getHHEIGHT())
                                $SearchParamtersObj->setHHEIGHT($request->getParameter("hheight"));
		}
	}

	/*
	* This function add caste mapping results to search paramter object.
	* @param SearchParamtersObj
	* @retuen 1 on success
	*/
	public function addCasteMapping($SearchParamtersObj)
	{
                $caste =  $SearchParamtersObj->getCASTE();
                if($caste)
		{
	                $CasteSuggest = new CasteSuggest;
        	        $mappedCaste = $CasteSuggest->getSuggestedCastes($caste,2);
                	if(is_array($mappedCaste))
	                {
        	                $mappedCaste[] = $caste;
                	        $mappedCaste = array_unique($mappedCaste);
                        	$casteStr = implode(",",$mappedCaste);
	                        $SearchParamtersObj->setCASTE($casteStr,1);
				return 1;
        	        }
		}
		return NULL;
	}

	/* 
	* This function Relax last cluster applied to increase zero results.
	* @param relaxRefinementCluster last cluser/refinement applied.
	*/
	public function relaxLastClusterOptionsToAvoidZeroResults($SearchParamtersObj,$relaxRefinementCluster)
	{
                if($relaxRefinementCluster)
                {
                        $label = $relaxRefinementCluster;
			if($label=='LAST_ACTIVITY')
			{
				$SearchParamtersObj->setOnline('');
			}
			elseif($label=='VIEWED')
			{
				$SearchParamtersObj->setViewed('');
			}
                }
	}

	/**
	* Special handling of cluster as on left hand cluster education_grp is used and on more link edu_level_new is used.
	* same applies for occupation
	* @param moreLinkCluster which cluster is used.
	*/
	public function specialClusterOnMore($SearchParamtersObj,$moreLinkCluster)
	{
		
		if($moreLinkCluster=='EDU_LEVEL_NEW')
		{
			$mappedArr = FieldMap::getFieldLabel("education_grouping_mapping_to_edu_level_new",1,1);
			$grp = $SearchParamtersObj->getEDUCATION_GROUPING();
			if($grp)
			{
				$tempArr = explode(",",$grp);
				foreach($tempArr as $k=>$v)
					$tempArr2[] = $mappedArr[$v];
				$val = implode(",",$tempArr2);
			
				if($SearchParamtersObj->getEDU_LEVEL_NEW())
					$val=$SearchParamtersObj->getEDU_LEVEL_NEW().",".$val;
				$SearchParamtersObj->setEDU_LEVEL_NEW($val);
				//$SearchParamtersObj->setEDUCATION_GROUPING('');
			}
		}
		elseif($moreLinkCluster=='OCCUPATION')
		{
			$mappedArr = FieldMap::getFieldLabel("occupation_grouping_mapping_to_occupation",1,1);
			$grp = $SearchParamtersObj->getOCCUPATION_GROUPING();
			if($grp)
			{
				$tempArr = explode(",",$grp);
				foreach($tempArr as $k=>$v)
					$tempArr2[] = $mappedArr[$v];
				$val = implode(",",$tempArr2);
				if($SearchParamtersObj->getOCCUPATION())
					$val=$SearchParamtersObj->getOCCUPATION().",".$val;
				$SearchParamtersObj->setOCCUPATION($val);
				//$SearchParamtersObj->setOCCUPATION_GROUPING('');
			}
		}
	}

	/**
	* This function list the number of clusters for which cluster section will be open.
	* @param SearchParamtersObj 
	* @param clustersToShow list of clusters to be displayed.
	*/
	public function getListOfOpenClusters($SearchParamtersObj,$clustersToShow='',$clusterOptions='')
	{
		$usedClusters = $SearchParamtersObj->getNEWSEARCH_CLUSTERING();
		$usedClusters = "";
		
		if(!$usedClusters && $clusterOptions)
		{
			foreach($clusterOptions as $k=>$v)	
			{
				$usedClusters = $usedClusters.$k.",";
				
			}
		}
		$usedClusters = rtrim($usedClusters,",");
		if($usedClusters)
		{
			$usedClustersArr = explode(",",$usedClusters);
			foreach($usedClustersArr as $v)
			{
				$openCluster[$v] = 'Y';
				if($v=='INCOME_DOL')
				$openCluster['INCOME'] = 'Y';
					
			}
			
		}
		return $openCluster;
	}

	function clusterWithMappedMoreOptions($SearchParamtersObj,$clusterVal,$globalArr,$mappedOfCluster,$checkIfEven='')
	{
		if($checkIfEven)
			if(strstr($checkIfEven,'ALL'))
				$allClicked=1;

		$mappedArr = FieldMap::getFieldLabel($globalArr,1,1);
		$grp = $clusterVal;
		$tempArr = explode(",",$grp);
		$tempArr_1 = $tempArr;
		foreach($tempArr as $k=>$v)
			$tempArr2[] = $mappedArr[$v];
		unset($tempArr);

		if($allClicked)
			$grp = '';
		else
		{
			$clusterTempArr = explode(",",SearchConfig::$possibleSearchParamters);
			if(in_array($mappedOfCluster,$clusterTempArr))
				$grp = $SearchParamtersObj->{"get".$mappedOfCluster}();
			else
				throw new jsException("","CLUSTER NAME ERR (".$mappedOfCluster.") - clusterWithMappedMoreOptions() in SearchUtility.class.php");
			unset($clusterTempArr);
		}
		if($grp)	
		{
			$tempArr = explode(",",$grp);
			foreach($tempArr as $k=>$v)
				$tempArr2[] = $v;
			unset($tempArr);
		}

		if($tempArr2)
		{
			$temp = implode(",",$tempArr2);
			$tempArr2 = explode(",",$temp);
			$tempArr2 = array_unique($tempArr2);
			$val = implode(",",$tempArr2);
		}

		if($checkIfEven)
		{
			$arr1 = explode(",",$checkIfEven);
			foreach($arr1 as $v)
				$arr2[$v]+=1;
			foreach($arr2 as $k=>$v)
			{
				if($v%2==1)
				{
					if(!in_array($k,$tempArr_1))
						$arr3[] = $mappedArr[$k];
				}
			}

			if($arr3)
			{
				$temp = implode(",",$arr3);
				$arr3 = explode(",",$temp);
				if($tempArr2)
				{
					$arr3 = array_diff($tempArr2,$arr3);
					$val = implode(",",$arr3);
				}
				else
					$val = '';

			}
		}
		return $val;
	} 
	
	function str_to_array_unique($cityStr='')
	{
		if($cityStr)
		{
			$city_arr = explode(",",$cityStr);
			return implode(",",array_unique($city_arr));
		}
		return '';
	}
	
	/**
	* This function will convert solr time to mysql/php time so that we can use in our script.
	* solr time has T and Z whiche need to be removed.
	*/
	static public function convertSolrTimeToMysqlTime($time='')
	{	
		if(!$time)
			return NULL;
                $time = str_replace("T"," ",$time);
                $time = str_replace("Z"," ",$time);
		return $time;
	}
	
        public function restoreSearchResultsCacheBySearchId($searchId)
        {
		$noAwaitingContacts = 1;
                $loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');
                $request = sfContext::getInstance()->getRequest();
                $request->setParameter('searchId',$searchId);
                $SearchServiceObj = new SearchService();
                $SearchParamtersObj = SearchParamtersLayer::setSearchParamters($request,$loggedInProfileObj);
		$this->removeProfileFromSearch($SearchParamtersObj,'spaceSeperator',$loggedInProfileObj,'',$noAwaitingContacts);
		if($SearchParamtersObj->getGENDER()=='')
			return 'I';
                $SearchServiceObj->setSearchSortLogic($SearchParamtersObj,$loggedInProfileObj);
                $responseObj = $SearchServiceObj->performSearch($SearchParamtersObj,'','','','',$loggedInProfileObj);
                return $responseObj;
        }
	public function isMatchAlertsPage($SearchParamtersObj)
	{
		if(!$SearchParamtersObj)
			return NULL;
                if($SearchParamtersObj->getSEARCH_TYPE() == SearchTypesEnums::MatchAlerts || $SearchParamtersObj->getMATCHALERTS_DATE_CLUSTER()!='')
			return true;
		return NULL;
	}
	public function isKundliAlertsPage($SearchParamtersObj)
	{
		if(!$SearchParamtersObj)
			return NULL;
                if($SearchParamtersObj->getSEARCH_TYPE() == SearchTypesEnums::KundliAlerts || $SearchParamtersObj->getKUNDLI_DATE_CLUSTER()!='')
			return true;
		return NULL;
	}

	public static function cachedSearchApi($type,$request="",$pid="",$statusArr="",$resultArr="")
        {  
                $caching = $request->getParameter("caching");
                if($caching || $type=="del")
                {       
			if(!$pid)
			{
				$loggedInProfileObj = LoggedInProfile::getInstance('newjs_master');   
	                        $pid = $loggedInProfileObj->getPROFILEID();
			}
			if(!$pid)
				return 0;
                        if($request->getParameter("searchBasedParam")=='justJoinedMatches')
                        {       
                                if($type=='set')
                                {      
                                        JsMemcache::getInstance()->set("cachedJJS$pid",serialize($statusArr));
                                        JsMemcache::getInstance()->set("cachedJJR$pid",serialize($resultArr));
                                        $profileIdPoolArray = array();
                                        if(is_array($resultArr)&&array_key_exists('profiles',$resultArr)) {  
				foreach ($resultArr['profiles'] as $key => $value) {
		 			array_push($profileIdPoolArray,$value['profileid']);
			}
		}
		JsMemcache::getInstance()->set("cachedJJRMyjs$pid",serialize($profileIdPoolArray));

					return 1;
                                }       
                                elseif($type=='get')
                                {       
                                        $statusArr = JsMemcache::getInstance()->get("cachedJJS$pid");
                                        $resultArr = JsMemcache::getInstance()->get("cachedJJR$pid");
                                        if($statusArr && $resultArr)
                                        {       
                                                $cachedArr["statusArr"] = unserialize($statusArr);
                                                $cachedArr["resultArr"] = unserialize($resultArr);
                                                return $cachedArr;
                                        }
                                }
                        }
			elseif($request->getParameter("partnermatches")=='1')
                        {	
                                if($type=='set')
                                {	
                                        JsMemcache::getInstance()->set("cachedPMS$pid",serialize($statusArr));
                                        JsMemcache::getInstance()->set("cachedPMR$pid",serialize($resultArr)); 
                                        $profileIdPoolArray = array();
                                        if(is_array($resultArr) &&array_key_exists('profiles',$resultArr)) {  
				foreach ($resultArr['profiles'] as $key => $value) {
		 			array_push($profileIdPoolArray,$value['profileid']);
			}
		}
		JsMemcache::getInstance()->set("cachedPMRMyjs$pid",serialize($profileIdPoolArray));


                                        return 1;
                                }
                                elseif($type=='get')
                                {	
                                        $statusArr = JsMemcache::getInstance()->get("cachedPMS$pid");
                                        $resultArr = JsMemcache::getInstance()->get("cachedPMR$pid");
                                        if($statusArr && $resultArr)
                                        {	
                                                $cachedArr["statusArr"] = unserialize($statusArr);
                                                $cachedArr["resultArr"] = unserialize($resultArr);
                                                return $cachedArr;
                                        }
                                }
                        }
			elseif($request->getParameter("verifiedMatches")=='1')
                        {	
                                if($type=='set')
                                {	
                                        JsMemcache::getInstance()->set("cachedVMS$pid",serialize($statusArr));
                                        JsMemcache::getInstance()->set("cachedVMR$pid",serialize($resultArr));
                                        $profileIdPoolArray = array();
                                        if(is_array($resultArr) &&array_key_exists('profiles',$resultArr)) {  
				foreach ($resultArr['profiles'] as $key => $value) {
		 			array_push($profileIdPoolArray,$value['profileid']);
			}
		}
		JsMemcache::getInstance()->set("cachedVMRMyjs$pid",serialize($profileIdPoolArray));
                                        return 1;
                                }
                                elseif($type=='get')
                                {	
                                        $statusArr = JsMemcache::getInstance()->get("cachedVMS$pid");
                                        $resultArr = JsMemcache::getInstance()->get("cachedVMR$pid");
                                        
                                        if($statusArr && $resultArr)
                                        {	
                                                $cachedArr["statusArr"] = unserialize($statusArr);
                                                $cachedArr["resultArr"] = unserialize($resultArr);
                                                return $cachedArr;
                                        }
                                }
                        }

               elseif($request->getParameter("searchBasedParam")=='matchalerts')
                        {	
                                if($type=='set')
                                {
					if($request->getParameter("androidMyjsNew"))
					{
						JsMemcache::getInstance()->set("cachedDMAS$pid",serialize($statusArr));
                                                JsMemcache::getInstance()->set("cachedDMAR$pid",serialize($resultArr));
					}
					else
					{	
	                                        JsMemcache::getInstance()->set("cachedDMS$pid",serialize($statusArr));
        	                                JsMemcache::getInstance()->set("cachedDMR$pid",serialize($resultArr)); 
					}
                                        $profileIdPoolArray = array();
                                        if(is_array($resultArr) &&array_key_exists('profiles',$resultArr)) {  
				foreach ($resultArr['profiles'] as $key => $value) {
		 			array_push($profileIdPoolArray,$value['profileid']);
			}
		}
		JsMemcache::getInstance()->set("cachedDMRMyjs$pid",serialize($profileIdPoolArray));


                                        return 1;
                                }
                                elseif($type=='get')
                                {	
					if($request->getParameter("androidMyjsNew"))
                                        {
	                                        $statusArr = JsMemcache::getInstance()->get("cachedDMAS$pid");
	                                        $resultArr = JsMemcache::getInstance()->get("cachedDMAR$pid");
                                        }       
                                        else
                                        {
	                                        $statusArr = JsMemcache::getInstance()->get("cachedDMS$pid");
	                                        $resultArr = JsMemcache::getInstance()->get("cachedDMR$pid");
					}
                                        if($statusArr && $resultArr)
                                        {	
                                                $cachedArr["statusArr"] = unserialize($statusArr);
                                                $cachedArr["resultArr"] = unserialize($resultArr);
                                                return $cachedArr;
                                        }
                                }
                        }


                        elseif($request->getParameter("lastsearch")=='1')
                        {	
                                if($type=='set')
                                {	
                                        JsMemcache::getInstance()->set("cachedLSMS$pid",serialize($statusArr));
                                        JsMemcache::getInstance()->set("cachedLSMR$pid",serialize($resultArr)); 
                                        $profileIdPoolArray = array();
                                        if(is_array($resultArr) &&array_key_exists('profiles',$resultArr)) {  
				foreach ($resultArr['profiles'] as $key => $value) {
		 			array_push($profileIdPoolArray,$value['profileid']);
			}
		}
		JsMemcache::getInstance()->set("cachedLSMRMyjs$pid",serialize($profileIdPoolArray));


                                        return 1;
                                }
                                elseif($type=='get')
                                {	
                                        $statusArr = JsMemcache::getInstance()->get("cachedLSMS$pid");
                                        $resultArr = JsMemcache::getInstance()->get("cachedLSMR$pid");
                                        if($statusArr && $resultArr)
                                        {	
                                                $cachedArr["statusArr"] = unserialize($statusArr);
                                                $cachedArr["resultArr"] = unserialize($resultArr);
                                                return $cachedArr;
                                        }
                                }
                        }




			if($type=='del')
			{
				JsMemcache::getInstance()->set("cachedJJS$pid","");
				JsMemcache::getInstance()->set("cachedJJR$pid","");
				JsMemcache::getInstance()->set("cachedVMS$pid","");
                JsMemcache::getInstance()->set("cachedVMR$pid","");
				JsMemcache::getInstance()->set("cachedPMS$pid","");
                JsMemcache::getInstance()->set("cachedPMR$pid","");
                JsMemcache::getInstance()->set("cachedDMS$pid","");
                JsMemcache::getInstance()->set("cachedDMAS$pid","");
                JsMemcache::getInstance()->set("cachedLSMS$pid","");
                JsMemcache::getInstance()->set("cachedDMR$pid","");
                JsMemcache::getInstance()->set("cachedDMAR$pid","");
                JsMemcache::getInstance()->set("cachedLSMR$pid","");
                // delete data Match of the day
                JsMemcache::getInstance()->set("cachedMM24$pid","");
			}	
                }
                return 0;
        }

}
?>
