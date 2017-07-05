<?php
/*
 * This Class will provide all services/requests related to searching of profiles based on search criteria.
 * @author Lavesh Rawat
 * @created 2012-05-30
 */
class SearchService
{
	private $responseType;
	private $engineType;

	/**
	* constructor function
	* @param engineType : sphinx/solr/mysql : we have implemented only solr. 
	* @param responseType response of search results.
	*/
        public function __construct($engineType='solr',$responseType='array',$showAllClustersOptions=0)
        {
		$this->responseType = $responseType;
		$this->engineType = $engineType;
		$this->showAllClustersOptions = $showAllClustersOptions;

		//contains values of other fields.
		$this->othersArray = array('Others','Other','other','others','Other Occupations','Security Professional');
		//occupation values which has the same name as occupation grouping
		$this->sameOccupationNameArray = array('Others','Defence','Pilot','Businessperson','Not working','Doctor','Farming','Sportsperson','Merchant Navy','Air Hostess','Govt. Services');

                if($showAllClustersOptions)
		{
                        //$this->clusterDisplaylimit = 10000;  //random choosen value
			$this->showAllClusters=1;
		}
                else
		{
                        //$this->clusterDisplaylimit = SearchConfig::$limitClustersOptions;
			$this->checkedClusterLimit = 6;
			$this->uncheckedClusterLimit = 6;
		}

        }


	/**
	* This function is used to perform search and return object containing information of (A).search-count (B).clusters (C).results(profile-ids) (D).Results with all the information
	* @param SearchParamtersObj search paramters object.
	* @param results_cluster string options are onlyClusters(calculate clusters only) /onlyResult(calculate results only) / onlyCount(calculate Count of Results only)
	* @param clustersToShow array containing list of clusters to show in order of display.
	* @param cachedSearch array implies search is cached and contains necessary details.
	* @param loggedInProfileObj
	*/
	
	public function performSearch($SearchParamtersObj='',$results_cluster='',$clustersToShow='',$currentPage='',$cachedSearch='',$loggedInProfileObj='')
	{
		$SearchResponseObj = ResponseHandleFactory::getResponseEngine($this->responseType,$this->engineType,$this->showAllClustersOptions);
		if($SearchParamtersObj->getSHOW_RESULT_FOR_SELF() =='N')
		{
			return $SearchResponseObj;
		}
		$SearchRequestObj = RequestHandleFactory::getRequestEngine($SearchResponseObj,$SearchParamtersObj);
		$SearchRequestObj->getResults($results_cluster,$clustersToShow,$currentPage,$cachedSearch,$loggedInProfileObj);
		
		if($SearchParamtersObj->getSHOW_RESULT_FOR_SELF()=='ISKUNDLIMATCHES')
		{
			$SearchResponseObj = $SearchParamtersObj->getGunaMatches($SearchResponseObj);
		}
		
		return $SearchResponseObj;
	}

        /*
        * This function is used to delete profile or array of profiles from search engine(as per current implementation : solr)
        * @param pid array/integer
        */
	public function deleteIdsFromSearch($pid)		
	{
		$SearchResponseObj = ResponseHandleFactory::getResponseEngine($this->responseType,$this->engineType,'');
		$SearchRequestObj  = RequestHandleFactory::getRequestEngine($SearchResponseObj,'');	
		$SearchRequestObj->deleteIdFromSearch($pid);
	}
	
	/**
	* This function is used to format search results clusters
	* @param res array contains search cluster results information.
	* @param clustersToShow array containing list of clusters to show in order of display.
	* @param SearchParamtersObj search paramters object.
	* @param moreClusterSoring int if set implies cluster sorting need to be done for case when more link is clicked
	*/
	public function getFormatedClusterResults($res,$clustersToShow,$SearchParamtersObj,$moreClusterSoring='')
	{
		/* map column of results array to field map array*/
		$fieldMapArrayLabelMapping = searchConfig::fieldMapArrayLabelMapping();
		$doesntMatterLabel = searchConfig::$doesntMatterLabel;
		$allLabel = searchConfig::$allLabel;
                
		/* 
		* loop through all cluster to display 
		* clusters with 'n' options/counts will be set here.
		* Some cluster have option as show , which indicates that we need to show this cluster without any count.
		*/
		foreach($clustersToShow as $k1=>$v1)
		{
			$other_cluster_option = 0;

			$clusterName = $v1;
                        if(in_array($v1,SearchConfig::$sliderClusters))
                        {
                                $this->clusterArr[$clusterName]['Slider']='Show';
				if($v1=="INCOME")
				{
					eval('$this->clusterArr['.$clusterName.'][0] = $SearchParamtersObj->getL'.$clusterName.'();');
                                        eval('$this->clusterArr['.$clusterName.'][1] = $SearchParamtersObj->getH'.$clusterName.'();');
					eval('$this->clusterArr['.$clusterName.'][2] = $SearchParamtersObj->getL'.$clusterName.'_DOL();');
                                        eval('$this->clusterArr['.$clusterName.'][3] = $SearchParamtersObj->getH'.$clusterName.'_DOL();');
					$this->generateIncomeArrayForCluster($clusterName);
					if($this->clusterArr[$clusterName][0] || $this->clusterArr[$clusterName][0]=='0')
						$this->clusterArr[$clusterName][0] = array_search($this->clusterArr[$clusterName][0],$this->clusterArr[$clusterName]["income_arr_rupee_mapping_html"]);
					if($this->clusterArr[$clusterName][1] || $this->clusterArr[$clusterName][1]=='0')
						$this->clusterArr[$clusterName][1] = array_search($this->clusterArr[$clusterName][1],$this->clusterArr[$clusterName]["income_arr_rupee_mapping_html"]);
					if($this->clusterArr[$clusterName][2] || $this->clusterArr[$clusterName][2]=='0')
						$this->clusterArr[$clusterName][2] = array_search($this->clusterArr[$clusterName][2],$this->clusterArr[$clusterName]["income_arr_dollar_mapping_html"]);
					if($this->clusterArr[$clusterName][3] || $this->clusterArr[$clusterName][3]=='0')
						$this->clusterArr[$clusterName][3] = array_search($this->clusterArr[$clusterName][3],$this->clusterArr[$clusterName]["income_arr_dollar_mapping_html"]);
					if($this->clusterArr[$clusterName][0] && $this->clusterArr[$clusterName][1])
						$this->clusterArr[$clusterName]["Rcheckbox"] = 1;
					else
						$this->clusterArr[$clusterName]["Rcheckbox"] = 0;
					
					if($this->clusterArr[$clusterName][2] && $this->clusterArr[$clusterName][3])
						$this->clusterArr[$clusterName]["Dcheckbox"] = 1;
					else
						$this->clusterArr[$clusterName]["Dcheckbox"] = 0;
				}
				else
				{	
					eval('$this->clusterArr['.$clusterName.'][0] = $SearchParamtersObj->getL'.$clusterName.'();');
					eval('$this->clusterArr['.$clusterName.'][1] = $SearchParamtersObj->getH'.$clusterName.'();');
				}
                        }
                        else
               		{   
                            	/** 
				* At the top of each cluster , we need to show All / Doesn't matter.
				*/

				$clusterTempArr = explode(",",SearchConfig::$possibleSearchParamters);
				if(!in_array($v1,$clusterTempArr))
				{
					ValidationHandler::getValidationHandler("","CLUSTER NAME ERR (".$v1.") - getFormatedClusterResults() in SearchService.class.php");
                                	throw new jsException("","CLUSTER NAME ERR (".$v1.") - getFormatedClusterResults() in SearchService.class.php");

				}
                        	unset($clusterTempArr);

				if(in_array($v1,SearchConfig::$clustersWithDoesntMatter))
					$topClusterOptionLabel = $doesntMatterLabel;
				else
					$topClusterOptionLabel = $allLabel;
				$this->clusterArr[$clusterName][$topClusterOptionLabel][0]='Show';
				$this->clusterArr[$clusterName][$topClusterOptionLabel][1]='ALL';

				if($SearchParamtersObj->{"get".$v1}()=='' || $SearchParamtersObj->{"get".$v1}()=='All')
				{
					$isAll_Y = 1;
					if($v1=='OCCUPATION_GROUPING' && $SearchParamtersObj->getOCCUPATION()!='')
						$isAll_Y = 0;
					if($v1=='EDUCATION_GROUPING' && $SearchParamtersObj->getEDU_LEVEL_NEW()!='')
						$isAll_Y = 0;
					if($isAll_Y)	
						$this->clusterArr[$clusterName][$topClusterOptionLabel][2]='Y';
				}
				elseif($v1=='MSTATUS' && $SearchParamtersObj->{"get".$v1}()=='DONT_MATTER')
					$this->clusterArr[$clusterName][$topClusterOptionLabel][2]='Y';

				/** 
				*'Viewed' cluster is a special case where links need to be shown without any count
				*/
				if($v1=='VIEWED')
				{
                                	$clusterNameForFieldLabel = $fieldMapArrayLabelMapping[$v1];
					$tempArr = FieldMap::getFieldLabel($clusterNameForFieldLabel,'',1);
					foreach($tempArr as $k2=>$v2)
					{
						$this->clusterArr[$clusterName][$v2][0]='Show';
						$this->clusterArr[$clusterName][$v2][1]=$k2;
						if(strstr($SearchParamtersObj->{"get".$v1}(),$k2))
							$this->clusterArr[$clusterName][$v2][2]='Y';
					}
				}
                                /** 
                                *'MATCHALERTS_DATE_CLUSTER' cluster is a special case where links need to be shown without any count
                                */
                                if($v1=='MATCHALERTS_DATE_CLUSTER')
                                {
                                        $clusterNameForFieldLabel = $fieldMapArrayLabelMapping[$v1];
                                        $tempArr = FieldMap::getFieldLabel($clusterNameForFieldLabel,'',1);
					$matDC = $SearchParamtersObj->getMATCHALERTS_DATE_CLUSTER();
					if($matDC)
					{
						$tempMatArr = explode(",",$matDC);
						rsort($tempMatArr);
						$matDC = $tempMatArr[0];
					}
                                        foreach($tempArr as $k2=>$v2)
                                        {
                                                $this->clusterArr[$clusterName][$v2][0]='Show';
                                                $this->clusterArr[$clusterName][$v2][1]=$k2;
                                                if($matDC && $matDC>=$k2)
                                                        $this->clusterArr[$clusterName][$v2][2]='Y';	
					}
				}
				elseif($v1=='LAST_ACTIVITY')
				/*
				* Online Should be the 1st cluster without any count
				* sortimg of clusters is not based on count , but is predefined
				*/
				{
					$this->clusterArr[$clusterName]['Online'][0]='Show';
					$this->clusterArr[$clusterName]['Online'][1]='O';
					if($SearchParamtersObj->getONLINE()=='O')
					{
						$this->clusterArr[$clusterName]['Online'][2]='Y';
						$this->clusterArr[$clusterName][$allLabel][2]='';
					}
					$tempArr = SearchConfig::clustersOptionsOfSpecialClusters('LAST_ACTIVITY');
					foreach($tempArr as $kk=> $vv)
						$this->clusterArr[$clusterName][$vv][0]=0;
					unset($tempArr);
				}
                                elseif($v1=='PROFILE_ADDED')
				/**
				* Special case sortimg of clusters is not based on count , but is predefined.
				*/
                                {
                                        $tempArr = FieldMap::getFieldLabel('profileAddedClusters','',1);
                                        foreach($tempArr as $kk=> $vv)
                                                $this->clusterArr[$clusterName][$vv][0]=0;
                                        unset($tempArr);
                                }
				elseif($v1=='STATE')
				/**
				* In This case we need to replace delhi by DELHI NCR.
				*/
				{
					$ncr = FieldMap::getFieldLabel('delhiNcrCities','',1);
					if(is_array($res["CITY_RES"]))
					foreach($res["CITY_RES"] as $k3=>$v3)
					{
						if(in_array($k3,$ncr))
							$res["STATE"]["NCR"]+=$v3; 
					}
					if($res["STATE"]["NCR"])
						unset($res["STATE"]["DE00"]);
					arsort($res["STATE"]);
				}
				elseif($v1=='CITY_RES')
				/**
				* In This case we need to show "All Metros" option as well.
				*/
				{
					if($SearchParamtersObj->getSTATE()=='' && !$moreClusterSoring)
					{
						$delmetro = FieldMap::getFieldLabel('allMetros','',1);
						if(is_array($res["CITY_RES"]))
						foreach($res["CITY_RES"] as $k3=>$v3)
						{
							if(in_array($k3,$delmetro))
								$res["CITY_RES"]["METRO"]+=$v3; 
						}
						arsort($res["CITY_RES"]);
					}
				}


                                $checkedCounter = 0;
				$uncheckedCounter = 0;
				$showMore = 0;
                                $clusterNameForFieldLabel = $fieldMapArrayLabelMapping[$v1];
                                //$clusterNameForFieldLabel = $v1;

				if(is_array($res[$v1]))
				{
                                foreach($res[$v1] as $k=>$v)
                                {
                                        if($res[$v1]=='')
                                                break;

                                        $labelVal=$k;
                                        $cnt=$v;

					/* 
					* Last Activity is a Special Case Where values of previous clusters 
					* need to be added into the next one in the list
					*/
					if(in_array($v1,array('LAST_ACTIVITY','PROFILE_ADDED')))
					{
                                        	//$reset++;
						$tmp_cnt = count(FieldMap::getFieldLabel($clusterNameForFieldLabel,'',1))+1;
						while($tmp_cnt > $labelVal)
						{
							$label=FieldMap::getFieldLabel($clusterNameForFieldLabel,$labelVal);
							$this->clusterArr[$clusterName][$label][0]+=$cnt;
							$this->clusterArr[$clusterName][$label][1]=$labelVal;
							if(strstr($SearchParamtersObj->{"get".$v1}(),"$labelVal"))
								$this->clusterArr[$clusterName][$label][2]='Y';
							$labelVal++;
						}
					}
					elseif($v1=='RELATION')
					/*
					* In Relation clsuters we will show 3 options and 'other'
					* self,parent,sibling,other(All other options are mapped to it)  
					*/
					{
                                        	//$reset++;
						if(in_array($labelVal,searchConfig::$clusterOptionsForRelation))
						{
							$label=FieldMap::getFieldLabel($clusterNameForFieldLabel,$labelVal);
							$this->clusterArr[$clusterName][$label][0]+=$cnt;
							$this->clusterArr[$clusterName][$label][1]=$labelVal;
						
							if(strstr($SearchParamtersObj->{"get".$v1}(),"$labelVal"))
								$this->clusterArr[$clusterName][$label][2]='Y';
						}
						else
						{
						    $relation_other = FieldMap::getFieldLabel('relation_other_search','Other');
						    if(@strstr($relation_other,"$labelVal"))
						    {
						    	$this->clusterArr[$clusterName]['Other'][0]+=$cnt;
						    	$this->clusterArr[$clusterName]['Other'][1] = $relation_other;
						    	if(strstr($SearchParamtersObj->{"get".$v1}(),$relation_other))
								$this->clusterArr[$clusterName]['Other'][2]='Y';
                                                    }
						}
							
					}
					elseif($v1=='HANDICAPPED')
					/**
					* In this cluster,we need to show addition option immediately after All i.e "Any".
					* 'Any' link will be sum of all the options
					*/
					{
						if(in_array($labelVal,searchConfig::$clusterOptionsForHandicapped)) 
						{
                                        		//$reset++;
							$label=FieldMap::getFieldLabel($clusterNameForFieldLabel,$labelVal);

							/* Any Should be 1st option (after All) */
							$this->clusterArr[$clusterName]['Any'][0]+=$cnt;
							$this->clusterArr[$clusterName]['Any'][1]=implode(",",searchConfig::$clusterOptionsForHandicapped);
							$this->clusterArr[$clusterName][$label][0]=$cnt;
							$this->clusterArr[$clusterName][$label][1]=$labelVal;
							if(strstr($SearchParamtersObj->{"get".$v1}(),"$labelVal"))
								$this->clusterArr[$clusterName][$label][2]='Y';
							else
								$noAny=1;

							/* If Any Link Will be ticked */
							if($noAny)
								$this->clusterArr[$clusterName]['Any'][2]='';
							else
								$this->clusterArr[$clusterName]['Any'][2]='Y';

						}
					}
					else
					/**
					* Handle Most of cluster cases.
					*/
					{
						if($labelVal)
						{
                                                        $labelVal= strtoupper($labelVal);
                                        		//$reset++;
							$isSelected = 0;
							if($this->isClusterValueSelected($labelVal,$SearchParamtersObj->{"get".$v1}()))
								$isSelected = 1;
							if($this->moreClusterOptions($isSelected,$checkedCounter,$uncheckedCounter))
							{

	 							if($labelVal=='NCR')
									$label='Delhi NCR';	
							 	elseif($labelVal=='METRO')
									$label='All Metros';	
								else
									$label=FieldMap::getFieldLabel($clusterNameForFieldLabel,$labelVal);
							 	if($label)
							 	{
									if($isSelected)
										$checkedCounter++;	
									else
										$uncheckedCounter++;
									$this->clusterArr[$clusterName][$label][0]+=$cnt;	
									$this->clusterArr[$clusterName][$label][1]=$labelVal;
									if($isSelected)
										$this->clusterArr[$clusterName][$label][2]='Y';
							 	}

                					        if($label=='Others' || $label=='Other')
							 	{
									if($isSelected)
									{
										$s_val_other= $labelVal;
										$other_cluster_option = 'C';
		                            					$checkedCounter--;	
									}
									else
									{
										$other_cluster_option = 'U';
										$uncheckedCounter--;
									}
								}
							}
							else
							{
								if($isSelected)
									$s_val[]= $labelVal;
								if(!$showMore)
								{
									$label = FieldMap::getFieldLabel($clusterNameForFieldLabel,$labelVal);
									if($label)
										$showMore = 1;
								}
							}
						}
					}
                                }
				/* If Option other is present , it shoul move on the left */
				if($other_cluster_option && !$moreClusterSoring)
				{
					$temp = $this->clusterArr[$clusterName]['Others'];
					if($temp)
					{
						unset($this->clusterArr[$clusterName]['Others']);
					}
					else
					{
						$temp = $this->clusterArr[$clusterName]['Other'];
						if($temp)
						unset($this->clusterArr[$clusterName]['Other']);
					}

					if($other_cluster_option=='U')
					{
					if($uncheckedCounter<=searchConfig::$limitUncheckedClustersOptions)
						$this->clusterArr[$clusterName]['Others'] = $temp;
					}

					if($other_cluster_option=='C')
					{
						if($checkedCounter<=searchConfig::$limitCheckedClustersOptions)
							$this->clusterArr[$clusterName]['Others'] = $temp;
						else
						{
							if($s_val_other)
								$s_val[]= $s_val_other;
							unset($s_val_other);
						}
					}
				}
				/* If Option other is present , it shoul move on the left */

				/* show more link on clusters*/
				/* will handle in api
				if($showMore || in_array($clusterName,SearchConfig::$moreClusters_alwaysShow))
					$this->clusterArr[$clusterName]['More'] = 1;
				*/

				/* when selected clusters are more than six than we can't unselect option not                                                shown on the left and hence need to added*/
				if(is_array($s_val))
				{
					$this->clusterArr[$clusterName]['s_val'] = implode(",",$s_val);
					unset($s_val);
				}

				/* No Need to show Cluster if only one value(all/doesn'tmatter) is present */
				if(count($this->clusterArr[$clusterName])==1)
					unset($this->clusterArr[$clusterName]);
				}
				else /* unsetting clusters with no values */
				{
					//if($v1!='VIEWED')	
					if(!in_array($v1,array('VIEWED','MATCHALERTS_DATE_CLUSTER')))
					unset($this->clusterArr[$v1]);
				}

				if($clusterName=='INDIA_NRI')
				{
					if($SearchParamtersObj->getCOUNTRY_RES()!='')
						if($this->clusterArr['INDIA_NRI']['All'][2]=='Y')
							unset($this->clusterArr['INDIA_NRI']['All'][2]);
				}
			}
		}
		//print_r($this->clusterArr[$clusterName]);
                
                if($moreClusterSoring==1)
                {
			if($clusterName=='EDU_LEVEL_NEW' || $clusterName=='OCCUPATION')
			{
				if($clusterName=='EDU_LEVEL_NEW')
				{
	        	                $grpArr = FieldMap::getFieldLabel("education_grouping",1,1);
        	        	        $eduArr = FieldMap::getFieldLabel("education",1,1);
                	        	$grpMappArr = FieldMap::getFieldLabel("education_grouping_mapping_to_edu_level_new",1,1);
				}
				if($clusterName=='OCCUPATION')
				{
	        	                $grpArr = FieldMap::getFieldLabel("occupation_grouping",1,1);
        	        	        $eduArr = FieldMap::getFieldLabel("occupation",1,1);
                	        	$grpMappArr = FieldMap::getFieldLabel("occupation_grouping_mapping_to_occupation",1,1);
				}
	                        asort($grpArr);

				//Move Others option to the end
				unset($grpArr_1);
				foreach($grpArr as $k=>$v)
				{
					if(in_array($v,$this->othersArray))
					{
						$grpArr_1[$k]=$v;
						unset($grpArr[$k]);
					}
				}
				if($grpArr_1)
					foreach($grpArr_1 as $k=>$v)	
						$grpArr[$k] = $v;
				//Move Others option to the end

        	                foreach($grpArr as $k=>$v)
                	        {
					if($v=='Others')
						$v = 'Others ';//differtiate between value 'other' and group Other
					if($v=='Defence')
						$v = 'Defence ';//differtiate between value 'Defence' and group Defence
                        	        $temp = $grpMappArr[$k];
                                	$tempArr = explode(",",$temp);
	                                $finalArr[$v][0] = 'Heading';
	                                $finalArr[$v][1] =  $k;
	                                $finalArr[$v][2] = 'Y';
					$counter=0;

					$unset_me = 1;
                	                foreach($tempArr as $kk=>$vv)
                        	        {
                                	        $key = $eduArr[$vv];
                                        	$value = $this->clusterArr[$clusterName][$key];
						if(key && $value[1])
						{
							$unset_me = 0;			
							$counter++;
                        	                	$finalArr[$key][0] = $value[0];
	                        	                $finalArr[$key][1] = $value[1];
	                        	                $finalArr[$key][2] = $value[2];
							if(!$value[2])
		                                		$finalArr[$v][2] = '';
						}
        	                        }
					/* If there is no option in heading remove it */
					if($unset_me)
						unset($finalArr[$v]);		
				
                	        }
				$this->clusterArr[$clusterName] = $finalArr;
	                }
			else
			{
				ksort($this->clusterArr[$clusterName]);
				//Move Others option to the end
				unset($grpArr_1);
				foreach($this->clusterArr[$clusterName] as $k=>$v)
				{
					if(strstr($k,'Other') || strstr($k,'Others') || strstr($k,'others') || strstr($k,'other'))
					{
						$grpArr_1[$k]=$v;
						unset($this->clusterArr[$clusterName][$k]);
					}
				}
				if($grpArr_1)
					foreach($grpArr_1 as $k=>$v)	
						$this->clusterArr[$clusterName][$k] = $v;
				//Move Others option to the end
			}
		}
		//Unsetting options that add no value for special case cluster 'LAST_ACTIVITY'	and 'PROFILE_ADDED';
		if(is_array($this->clusterArr['LAST_ACTIVITY']))
		foreach($this->clusterArr['LAST_ACTIVITY'] as $k=>$v)
			if($v[0]=='0')
				unset($this->clusterArr['LAST_ACTIVITY'][$k]);
		if(count($this->clusterArr['LAST_ACTIVITY'])==1)
			unset($this->clusterArr['LAST_ACTIVITY']);


		if(is_array($this->clusterArr['PROFILE_ADDED']))
		foreach($this->clusterArr['PROFILE_ADDED'] as $k=>$v)
			if($v[0]=='0')
				unset($this->clusterArr['PROFILE_ADDED'][$k]);
		if(count($this->clusterArr['PROFILE_ADDED'])==1)
			unset($this->clusterArr['PROFILE_ADDED']);
		//Unsetting options that add no value for special case cluster 'LAST_ACTIVITY'	and 'PROFILE_ADDED';
               return $this->clusterArr;
	}



	/**
	* This function is used to format search results clusters
	* @param res array contains search cluster results information.
	* @param clustersToShow array containing list of clusters to show in order of display.
	* @param SearchParamtersObj search paramters object.
	* @param moreClusterSoring int if set implies cluster sorting need to be done for case when more link is clicked
	*/
	public function getFormatedClusterResultsApi($res,$clustersToShow,$SearchParamtersObj,$moreClusterSoring='')
	{
		/* map column of results array to field map array*/
		$fieldMapArrayLabelMapping = searchConfig::fieldMapArrayLabelMapping();
		$doesntMatterLabel = searchConfig::$doesntMatterLabel;
		$allLabel = searchConfig::$allLabel;

		/* 
		* loop through all cluster to display 
		* clusters with 'n' options/counts will be set here.
		* Some cluster have option as show , which indicates that we need to show this cluster without any count.
		*/
		foreach($clustersToShow as $k1=>$v1)
		{
			$other_cluster_option = 0;

			$clusterName = $v1;

                        if(in_array($v1,SearchConfig::$sliderClusters))
                        {
                                $this->clusterArr[$clusterName]['Slider']='Show';
				if($v1=="INCOME")
				{
					eval('$this->clusterArr['.$clusterName.'][0] = $SearchParamtersObj->getL'.$clusterName.'();');
                                        eval('$this->clusterArr['.$clusterName.'][1] = $SearchParamtersObj->getH'.$clusterName.'();');
					eval('$this->clusterArr['.$clusterName.'][2] = $SearchParamtersObj->getL'.$clusterName.'_DOL();');
                                        eval('$this->clusterArr['.$clusterName.'][3] = $SearchParamtersObj->getH'.$clusterName.'_DOL();');
					$this->generateIncomeArrayForCluster($clusterName);
					if($this->clusterArr[$clusterName][0] || $this->clusterArr[$clusterName][0]=='0')
						$this->clusterArr[$clusterName][0] = array_search($this->clusterArr[$clusterName][0],$this->clusterArr[$clusterName]["income_arr_rupee_mapping_html"]);
					if($this->clusterArr[$clusterName][1] || $this->clusterArr[$clusterName][1]=='0')
						$this->clusterArr[$clusterName][1] = array_search($this->clusterArr[$clusterName][1],$this->clusterArr[$clusterName]["income_arr_rupee_mapping_html"]);
					if($this->clusterArr[$clusterName][2] || $this->clusterArr[$clusterName][2]=='0')
						$this->clusterArr[$clusterName][2] = array_search($this->clusterArr[$clusterName][2],$this->clusterArr[$clusterName]["income_arr_dollar_mapping_html"]);
					if($this->clusterArr[$clusterName][3] || $this->clusterArr[$clusterName][3]=='0')
						$this->clusterArr[$clusterName][3] = array_search($this->clusterArr[$clusterName][3],$this->clusterArr[$clusterName]["income_arr_dollar_mapping_html"]);
					if($this->clusterArr[$clusterName][0] && $this->clusterArr[$clusterName][1])
						$this->clusterArr[$clusterName]["Rcheckbox"] = 1;
					else
						$this->clusterArr[$clusterName]["Rcheckbox"] = 0;
					
					if($this->clusterArr[$clusterName][2] && $this->clusterArr[$clusterName][3])
						$this->clusterArr[$clusterName]["Dcheckbox"] = 1;
					else
						$this->clusterArr[$clusterName]["Dcheckbox"] = 0;
				}
				else
				{	
					eval('$this->clusterArr['.$clusterName.'][0] = $SearchParamtersObj->getL'.$clusterName.'();');
					eval('$this->clusterArr['.$clusterName.'][1] = $SearchParamtersObj->getH'.$clusterName.'();');
				}
                        }
                        else
               		{
				/** 
				* At the top of each cluster , we need to show All / Doesn't matter.
				*/

				$clusterTempArr = explode(",",SearchConfig::$possibleSearchParamters);
                        	unset($clusterTempArr);

				if(in_array($v1,SearchConfig::$clustersWithDoesntMatter))
					$topClusterOptionLabel = $doesntMatterLabel;
				else
					$topClusterOptionLabel = $allLabel;
				$this->clusterArr[$clusterName][$topClusterOptionLabel][0]='Show';
				$this->clusterArr[$clusterName][$topClusterOptionLabel][1]='ALL';
                                
                                $ifAny = "";
                                if($v1 == 'KNOWN_COLLEGE'){
                                        $ifAny = $SearchParamtersObj->{"getKNOWN_COLLEGE_IGNORE"}();
                                     $this->clusterArr[$clusterName][searchConfig::$anyWellKnownClg][0]='Show';
                                     $this->clusterArr[$clusterName][searchConfig::$anyWellKnownClg][1]='Any';
                                     if($ifAny=='000')
                                        $this->clusterArr[$clusterName][searchConfig::$anyWellKnownClg][2]='Y';
                                     else
                                        $this->clusterArr[$clusterName][searchConfig::$anyWellKnownClg][2]=''; 
                                }
				if($SearchParamtersObj->{"get".$v1}()=='')
				{
					$isAll_Y = 1;
					if($v1=='OCCUPATION_GROUPING' && $SearchParamtersObj->getOCCUPATION()!='')
						$isAll_Y = 0;
					if($v1=='EDUCATION_GROUPING' && $SearchParamtersObj->getEDU_LEVEL_NEW()!='')
						$isAll_Y = 0;
					if($isAll_Y && $ifAny =="")	
						$this->clusterArr[$clusterName][$topClusterOptionLabel][2]='Y';
				}
				elseif($v1=='MSTATUS' && $SearchParamtersObj->{"get".$v1}()=='DONT_MATTER')
					$this->clusterArr[$clusterName][$topClusterOptionLabel][2]='Y';

				/** 
				*'Viewed' cluster is a special case where links need to be shown without any count
				*/
				if($v1=='VIEWED')
				{
                                	$clusterNameForFieldLabel = $fieldMapArrayLabelMapping[$v1];
					$tempArr = FieldMap::getFieldLabel($clusterNameForFieldLabel,'',1);
					foreach($tempArr as $k2=>$v2)
					{
						$this->clusterArr[$clusterName][$v2][0]='Show';
						$this->clusterArr[$clusterName][$v2][1]=$k2;
						if(strstr($SearchParamtersObj->{"get".$v1}(),$k2))
							$this->clusterArr[$clusterName][$v2][2]='Y';
					}
				}
 
				/**
                                *'MATCHALERTS_DATE_CLUSTER' cluster is a special case where links need to be shown without any count
                                */
                                if($v1=='MATCHALERTS_DATE_CLUSTER')
                                {
                                        $clusterNameForFieldLabel = $fieldMapArrayLabelMapping[$v1];
                                        $tempArr = FieldMap::getFieldLabel($clusterNameForFieldLabel,'',1);
                                        $matDC = $SearchParamtersObj->getMATCHALERTS_DATE_CLUSTER();
                                        if($matDC)
                                        {
                                                $tempMatArr = explode(",",$matDC);
                                                rsort($tempMatArr);
                                                $matDC = $tempMatArr[0];
                                        }
                                        foreach($tempArr as $k2=>$v2)
                                        {
                                                $this->clusterArr[$clusterName][$v2][0]='Show';
                                                $this->clusterArr[$clusterName][$v2][1]=$k2;
                                                if($matDC && $matDC>=$k2)
						{
                                                        $this->clusterArr[$clusterName][$v2][2]='Y';
							$atleastOnce=1;
						}
                                        }
					if($atleastOnce!=1)
						$this->clusterArr[$clusterName]['All'][2] = 'Y';
                                }
				elseif($v1=='LAST_ACTIVITY')
				/*
				* Online Should be the 1st cluster without any count
				* sortimg of clusters is not based on count , but is predefined
				*/
				{
					$this->clusterArr[$clusterName]['Online'][0]='Show';
					$this->clusterArr[$clusterName]['Online'][1]='O';
					if($SearchParamtersObj->getONLINE()=='O')
					{
						$this->clusterArr[$clusterName]['Online'][2]='Y';
						$this->clusterArr[$clusterName][$allLabel][2]='';
					}
					$tempArr = SearchConfig::clustersOptionsOfSpecialClusters('LAST_ACTIVITY');
					foreach($tempArr as $kk=> $vv)
						$this->clusterArr[$clusterName][$vv][0]=0;
					unset($tempArr);
				}
                                elseif($v1=='PROFILE_ADDED')
				/**
				* Special case sortimg of clusters is not based on count , but is predefined.
				*/
                                {
                                        $tempArr = FieldMap::getFieldLabel('profileAddedClusters','',1);
                                        foreach($tempArr as $kk=> $vv)
                                                $this->clusterArr[$clusterName][$vv][0]=0;
                                        unset($tempArr);
                                }
				elseif($v1=='STATE')
				/**
				* In This case we need to replace delhi by DELHI NCR.
				*/
				{
					$ncr = FieldMap::getFieldLabel('delhiNcrCities','',1);
					if(is_array($res["CITY_RES"]))
					foreach($res["CITY_RES"] as $k3=>$v3)
					{
						if(in_array($k3,$ncr))
							$res["STATE"]["NCR"]+=$v3; 
					}
					if($res["STATE"]["NCR"])
						unset($res["STATE"]["DE00"]);
					arsort($res["STATE"]);
				}
				elseif($v1=='CITY_RES')
				/**
				* In This case we need to show "All Metros" option as well.
				*/
				{
					if($SearchParamtersObj->getSTATE()=='' && !$moreClusterSoring)
					{
						$delmetro = FieldMap::getFieldLabel('allMetros','',1);
						if(is_array($res["CITY_RES"]))
						foreach($res["CITY_RES"] as $k3=>$v3)
						{
							if(in_array($k3,$delmetro))
								$res["CITY_RES"]["METRO"]+=$v3; 
						}
						arsort($res["CITY_RES"]);
					}
				}


                                $checkedCounter = 0;
				$uncheckedCounter = 0;
				$showMore = 0;
                                $clusterNameForFieldLabel = $fieldMapArrayLabelMapping[$v1];
                                //$clusterNameForFieldLabel = $v1;

				if(is_array($res[$v1]))
				{
                                foreach($res[$v1] as $k=>$v)
                                {
                                        if($res[$v1]=='')
                                                break;

                                        $labelVal=$k;
                                        $cnt=$v;

					/* 
					* Last Activity is a Special Case Where values of previous clusters 
					* need to be added into the next one in the list
					*/
					if(in_array($v1,array('LAST_ACTIVITY','PROFILE_ADDED')))
					{
                                        	//$reset++;
						$tmp_cnt = count(FieldMap::getFieldLabel($clusterNameForFieldLabel,'',1))+1;
						while($tmp_cnt > $labelVal)
						{
							$label=FieldMap::getFieldLabel($clusterNameForFieldLabel,$labelVal);
							$this->clusterArr[$clusterName][$label][0]+=$cnt;
							$this->clusterArr[$clusterName][$label][1]=$labelVal;
							if(strstr($SearchParamtersObj->{"get".$v1}(),"$labelVal"))
								$this->clusterArr[$clusterName][$label][2]='Y';
							$labelVal++;
						}
					}
					elseif($v1=='RELATION')
					/*
					* In Relation clsuters we will show 3 options and 'other'
					* self,parent,sibling,other(All other options are mapped to it)  
					*/
					{
                                        	//$reset++;
						if(in_array($labelVal,searchConfig::$clusterOptionsForRelation))
						{
							$label=FieldMap::getFieldLabel($clusterNameForFieldLabel,$labelVal);
							$this->clusterArr[$clusterName][$label][0]+=$cnt;
							$this->clusterArr[$clusterName][$label][1]=$labelVal;
						
							if(strstr($SearchParamtersObj->{"get".$v1}(),"$labelVal"))
								$this->clusterArr[$clusterName][$label][2]='Y';
						}
						else
						{
						    $relation_other = FieldMap::getFieldLabel('relation_other_search','Other');
						    if(@strstr($relation_other,"$labelVal"))
						    {
						    	$this->clusterArr[$clusterName]['Other'][0]+=$cnt;
						    	$this->clusterArr[$clusterName]['Other'][1] = $relation_other;
						    	if(strstr($SearchParamtersObj->{"get".$v1}(),$relation_other))
								$this->clusterArr[$clusterName]['Other'][2]='Y';
                                                    }
						}
							
					}
					elseif($v1=='HANDICAPPED')
					/**
					* In this cluster,we need to show addition option immediately after All i.e "Any".
					* 'Any' link will be sum of all the options
					*/
					{
						if(in_array($labelVal,searchConfig::$clusterOptionsForHandicapped)) 
						{
                                        		//$reset++;
							$label=FieldMap::getFieldLabel($clusterNameForFieldLabel,$labelVal);

							/* Any Should be 1st option (after All) */
							$this->clusterArr[$clusterName]['Any'][0]+=$cnt;
							$this->clusterArr[$clusterName]['Any'][1]=implode(",",searchConfig::$clusterOptionsForHandicapped);
							$this->clusterArr[$clusterName][$label][0]=$cnt;
							$this->clusterArr[$clusterName][$label][1]=$labelVal;
							if(strstr($SearchParamtersObj->{"get".$v1}(),"$labelVal"))
								$this->clusterArr[$clusterName][$label][2]='Y';
							else
								$noAny=1;

							/* If Any Link Will be ticked */
							if($noAny)
								$this->clusterArr[$clusterName]['Any'][2]='';
							else
								$this->clusterArr[$clusterName]['Any'][2]='Y';

						}
					}
					else
					/**
					* Handle Most of cluster cases.
					*/
					{
						if($labelVal)
						{
                                                        $labelVal= strtoupper($labelVal);
                                        		//$reset++;
							$isSelected = 0;
							if($this->isClusterValueSelected($labelVal,$SearchParamtersObj->{"get".$v1}()))
								$isSelected = 1;
							if($this->moreClusterOptions($isSelected,$checkedCounter,$uncheckedCounter))
							{

	 							if($labelVal=='NCR')
									$label='';//Delhi NCR';	
							 	elseif($labelVal=='METRO')
									$label='All Metros';	
								else
									$label=FieldMap::getFieldLabel($clusterNameForFieldLabel,$labelVal);
							 	if($label)
							 	{
								if($isSelected)
	                            					$checkedCounter++;	
								else
									$uncheckedCounter++;
							    	$this->clusterArr[$clusterName][$label][0]+=$cnt;	
								$this->clusterArr[$clusterName][$label][1]=$labelVal;
							        if($isSelected)
						                	$this->clusterArr[$clusterName][$label][2]='Y';
							 	}
                					        if($label=='Others' || $label=='Other')
							 	{
									if($isSelected)
									{
										$s_val_other= $labelVal;
										$other_cluster_option = 'C';
		                            					$checkedCounter--;	
									}
									else
									{
										$other_cluster_option = 'U';
										$uncheckedCounter--;
									}
								}
							}
							else
							{
								if($isSelected)
									$s_val[]= $labelVal;
								if(!$showMore)
								{
									$label = FieldMap::getFieldLabel($clusterNameForFieldLabel,$labelVal);
									if($label)
										$showMore = 1;
								}
							}
						}
					}
                                }
				/* If Option other is present , it shoul move on the left */
				/*
				if($other_cluster_option && !$moreClusterSoring)
				{
					$temp = $this->clusterArr[$clusterName]['Others'];
					if($temp)
					{
						unset($this->clusterArr[$clusterName]['Others']);
					}
					else
					{
						$temp = $this->clusterArr[$clusterName]['Other'];
						if($temp)
						unset($this->clusterArr[$clusterName]['Other']);
					}

					if($other_cluster_option=='U')
					{
					if($uncheckedCounter<=searchConfig::$limitUncheckedClustersOptions)
						$this->clusterArr[$clusterName]['Others'] = $temp;
					}

					if($other_cluster_option=='C')
					{
						if($checkedCounter<=searchConfig::$limitCheckedClustersOptions)
							$this->clusterArr[$clusterName]['Others'] = $temp;
						else
						{
							if($s_val_other)
								$s_val[]= $s_val_other;
							unset($s_val_other);
						}
					}
				}
				*/
				/* If Option other is present , it shoul move on the left */

				/* show more link on clusters*/
				/*
				if($showMore || in_array($clusterName,SearchConfig::$moreClusters_alwaysShow))
					$this->clusterArr[$clusterName]['More'] = 1;
				*/

				/* when selected clusters are more than six than we can't unselect option not                                                shown on the left and hence need to added*/
				if(is_array($s_val))
				{
					$this->clusterArr[$clusterName]['s_val'] = implode(",",$s_val);
					unset($s_val);
				}

				/* No Need to show Cluster if only one value(all/doesn'tmatter) is present */
				if(count($this->clusterArr[$clusterName])==1)
					unset($this->clusterArr[$clusterName]);
				}
				else /* unsetting clusters with no values */
				{
					if(!in_array($v1,array('VIEWED','MATCHALERTS_DATE_CLUSTER')))
						unset($this->clusterArr[$v1]);
				}

				if($clusterName=='INDIA_NRI')
				{
					if($SearchParamtersObj->getCOUNTRY_RES()!='')
						if($this->clusterArr['INDIA_NRI']['All'][2]=='Y')
							unset($this->clusterArr['INDIA_NRI']['All'][2]);
				}
			}
                }
                if(1)
                {
			foreach($clustersToShow as $k1=>$v1)
			{	
			if(!in_array($v1,SearchConfig::$sliderClusters))
			{
                        	$clusterName = $v1;
				if($clusterName=='EDU_LEVEL_NEW' || $clusterName=='OCCUPATION')
				{
					if($clusterName=='EDU_LEVEL_NEW')
					{
						$grpArr = FieldMap::getFieldLabel("education_grouping",1,1);
						$eduArr = FieldMap::getFieldLabel("education",1,1);
						$grpMappArr = FieldMap::getFieldLabel("education_grouping_mapping_to_edu_level_new",1,1);
					}
					if($clusterName=='OCCUPATION')
					{
						$grpArr = FieldMap::getFieldLabel("occupation_grouping",1,1);
						$eduArr = FieldMap::getFieldLabel("occupation",1,1);
						$grpMappArr = FieldMap::getFieldLabel("occupation_grouping_mapping_to_occupation",1,1);
					}
					asort($grpArr);

					//Move Others option to the end
					unset($grpArr_1);
					foreach($grpArr as $k=>$v)
					{
						if(in_array($v,$this->othersArray))
						{
							$grpArr_1[$k]=$v;
							unset($grpArr[$k]);
						}
					}
					if($grpArr_1)
						foreach($grpArr_1 as $k=>$v)	
							$grpArr[$k] = $v;
					//Move Others option to the end
					
					foreach($grpArr as $k=>$v)
					{
						if (in_array($v,$this->sameOccupationNameArray))
						{
							$v = $v." ";
						}
						$temp = $grpMappArr[$k];
						$tempArr = explode(",",$temp);
						$finalArr[$v][0] = 'Heading';
						$finalArr[$v][1] =  $k;
						$finalArr[$v][2] = 'Y';
						$counter=0;

						$unset_me = 1;
						foreach($tempArr as $kk=>$vv)
						{
							$key = $eduArr[$vv];
							$value = $this->clusterArr[$clusterName][$key];

							if(key && $value[1])
							{
								$unset_me = 0;			
								$counter++;
								$finalArr[$key][0] = $value[0];
								$finalArr[$key][1] = $value[1];
								$finalArr[$key][2] = $value[2];
								if(!$value[2])
									$finalArr[$v][2] = '';
							}
						}
						/* If there is no option in heading remove it */
						if($unset_me)
							unset($finalArr[$v]);		
					
					}
					//print_r($finalArr);die;
					$this->clusterArr[$clusterName] = $finalArr;
					unset($finalArr);
				}
				else
				{
					/** Move Others at end for all clusters **/
					foreach($this->clusterArr as $clusterName=>$clusterValueArr)
					{
						if(!in_array($clusterName,SearchConfig::$sliderClusters))
						{
						unset($removeOthersArr);
						foreach($clusterValueArr as $clusterOpt=>$clusterOptVal)
						{
							if(in_array($clusterOpt,$this->othersArray))
							{
								$removeOthersArr[$clusterOpt]=$clusterOptVal;
								unset($this->clusterArr[$clusterName][$clusterOpt]);
							}
						}
						if($removeOthersArr)
							$this->clusterArr[$clusterName] = array_merge($this->clusterArr[$clusterName],$removeOthersArr);
						}
					}
					/** Move Others at end for all clusters **/
				}
			}
			}
		}
                
		/**
		* Unsetting options that add no value for special case cluster 'LAST_ACTIVITY'	and 'PROFILE_ADDED';
		*/
		if(is_array($this->clusterArr['LAST_ACTIVITY']))
		foreach($this->clusterArr['LAST_ACTIVITY'] as $k=>$v)
			if($v[0]=='0')
				unset($this->clusterArr['LAST_ACTIVITY'][$k]);
		if(count($this->clusterArr['LAST_ACTIVITY'])==1)
			unset($this->clusterArr['LAST_ACTIVITY']);


		if(is_array($this->clusterArr['PROFILE_ADDED']))
		foreach($this->clusterArr['PROFILE_ADDED'] as $k=>$v)
			if($v[0]=='0')
				unset($this->clusterArr['PROFILE_ADDED'][$k]);
		if(count($this->clusterArr['PROFILE_ADDED'])==1)
			unset($this->clusterArr['PROFILE_ADDED']);

		return $this->clusterArr;
	}

	/**
	* This function calculate the count of search results if caste mapping is applied
	*/	
	public function getCasteMappingCount($SearchParamtersObj,$loggedInProfileObj='')
	{
		$SearchUtilityObj = new SearchUtility;
		$caste = $SearchParamtersObj->getCASTE();

		if($SearchUtilityObj->addCasteMapping($SearchParamtersObj))
		{
			$results_orAnd_cluster = 'onlyCount';
			$obj = $this->performSearch($SearchParamtersObj,$results_orAnd_cluster,'','','',$loggedInProfileObj);
			$SearchParamtersObj->setCASTE($caste,1);
			return $obj;
		}
		return NULL;
	}


	public function generateIncomeArrayForCluster($clusterName)
	{
		//Income Array
                $income_arr_rupee = FieldMap::getFieldLabel("hincome",1,1);
                foreach($income_arr_rupee as $k=>$v)
                {
                        if(!$v)
                                $v = "0";
                        elseif($v=="and above")
                                $v = "& above";
                        if(strpos($v,"Rs.")!==false)
                                $v = substr($v,3);
                        if(strpos($v," Lakh")!==false)
                                $v = str_replace(" Lakh","Lac",$v);
                        $income_arr_rupee[$k]=$v;
                }
		$this->clusterArr[$clusterName]["income_arr_rupee_html"] = $income_arr_rupee;
                $i=1;
                foreach($income_arr_rupee as $k=>$v)
                {
                        $income_arr_rupee_mapping_html[$i] = $k;
                        $i++;
                }
		$this->clusterArr[$clusterName]["income_arr_rupee_mapping_html"] = $income_arr_rupee_mapping_html;
		
		$income_arr_dollar[0]="0";
                $hincome_dol = FieldMap::getFieldLabel("hincome_dol",1,1);
                $income_arr_dollar = $income_arr_dollar + $hincome_dol;
                foreach($income_arr_dollar as $k=>$v)
                {
                        if($v=="and above")
                                $v = "& above";
                        if(strpos($v,"$")!==false)
                                $v = substr($v,1);
                        if(strpos($v,","))
                                $v = str_replace(",","",$v);
                        if($v!="& above" && $v!=0)
                                $v = ($v/1000)."K";
                        $income_arr_dollar[$k]=$v;
                }
		$this->clusterArr[$clusterName]["income_arr_dollar_html"] = $income_arr_dollar;
                $i=1;
                foreach($income_arr_dollar as $k=>$v)
                {
                        $income_arr_dollar_mapping_html[$i] = $k;
                        $i++;
                }
		$this->clusterArr[$clusterName]["income_arr_dollar_mapping_html"] = $income_arr_dollar_mapping_html;
	}

	/*
	* check if cluster value is selected
	* @param matched string need to be matched against @str
	* @return boolean true if found
	*/
	function isClusterValueSelected($matched,$str)
	{
		if($str)
		{
			$str=",".$str.",";
			if(strpos($str,",$matched,")!==false)
			return true;
		}
		return false;
	}

	/*
	* This Function append the sorting criteria to SearchParamtersObj 
	*/
	public function callSortEngine($SearchParamtersObj,$sortlogic='',$loggedInProfileObj='')
	{
                $SortStrategyFactoryObj = SortStrategyFactory::getSorterStrategy($SearchParamtersObj,$loggedInProfileObj,$sortlogic);
                if($SortStrategyFactoryObj)
                                $SortStrategyFactoryObj->getSortString($SearchParamtersObj);
	}

	/**
	* This Function will check if more clusters options need be shown.
	*/
	private function moreClusterOptions($isSelected,$checkedCounter,$uncheckedCounter)
	{
		if($this->showAllClusters || ($isSelected && $checkedCounter<=SearchConfig::$limitCheckedClustersOptions) || (!$isSelected && $uncheckedCounter<=SearchConfig::$limitUncheckedClustersOptions))
			return true;

	}


        /**
        * This function is used to perform grouping operation.
        * This is required for seo pages where we need to get profileid of column(ex:citiy,occ) by count(*) desc
        * @param SearchParamtersObj search paramters object.
        * @return $SearchResponseObj response object containing grouping results.
        */
        public function performGrouping($SearchParamtersObj,$grpField,$grpLimit='',$grpSort='',$grpRows='',$loggedInProfileObj='')
        {
                $SearchResponseObj = ResponseHandleFactory::getResponseEngine($this->responseType,$this->engineType,$this->showAllClustersOptions);
                $SearchRequestObj = RequestHandleFactory::getRequestEngine($SearchResponseObj,$SearchParamtersObj);
                $SearchRequestObj->getGroupingResults($grpField,$grpLimit,$grpSort,$grpRows,$loggedInProfileObj);
                return $SearchResponseObj;
        }

	/*
	This function is used for tracking purpose
	@param 1)pageNo being viewed 2) searchid
	*/
	public static function trackingMis($pageNo,$sid)
	{
		$misObj = new MIS_NEWSEARCH_PAGEVIEW;
		$misObj->insertRecord($pageNo,$sid);
		unset($misObj);
	}

	/**
	* Sorting Logic is specific to this action only.
	*/
	public function setSearchSortLogic($SearchParamtersObj,$loggedInProfileObj='',$sort_type='',$sort_logic="")
	{
		global $_COOKIE;
		if(!$sort_type)
		{
			if(!$sort_logic)
	        	        $sort_logic = $SearchParamtersObj->getSORT_LOGIC();

	                if($sort_logic == '')	
			{
				if($loggedInProfileObj && $loggedInProfileObj->getPROFILEID())
				{
					$profileObj = $loggedInProfileObj;
					$loggedIn=1;
				}
				else
				{

					$isearch_pid=$_COOKIE["ISEARCH"];
					if($isearch_pid)
					{
						$loggedIn=1;
						$profileObj = Profile::getInstance('newjs_master',$isearch_pid);
						$profileObj->getDetail("","","ENTRY_DT");
					}
					else
		                        	$sort_logic = SearchSortTypesEnums::popularSortFlag;
				}

				if($loggedIn)
				{
					$sort_logic = SearchSortTypesEnums::dateSortFlag;
					/*
					if(MobileCommon::isApp()=='A')
						$sort_logic = SearchSortTypesEnums::dateSortFlag;
					else
					{
						$entry_dt = substr($profileObj->getENTRY_DT(),0,10);
						if(CommonUtility::DayDiff($entry_dt,date("Y-m-d")) >= 30)
							$sort_logic = SearchSortTypesEnums::dateSortFlag;
						else
							$sort_logic = SearchSortTypesEnums::relevanceSortFlag;
					}
					*/
				}
			}
                        elseif($sort_logic =='T')
                        {
                                $profileObj = $loggedInProfileObj;
                                if((!$profileObj || $profileObj->getPROFILEID()=='') && !$_COOKIE["ISEARCH"])
                                        $sort_logic = 'P';
                        }
	                $SearchParamtersObj->setSORT_LOGIC($sort_logic);
		}
		else
		{
			$SearchParamtersObj->setSORTING_CRITERIA('');
			$SearchParamtersObj->setSORTING_CRITERIA_ASC_OR_DESC('');
		}
		$this->callSortEngine($SearchParamtersObj,$sort_type,$loggedInProfileObj);
	}
        /*
         * Function to provide Search Summary
         */
        function searchSummary($searchId){
                
                $searchidObj = new SearchLogger();
                $searchidObj->getSearchCriteria($searchId,'nonCritical');
                $searchEngine = "solr";
                $breadCrumbObj = new BreadCrumb;
                $searchSummary = $breadCrumbObj->getSearchParametersLabels($searchidObj,$searchEngine);
                
                $this->searchSummaryFormatted = implode("  |  ",$searchSummary);
                $this->searchId=$searchId;
                
                return array("searchSummaryFormatted"=>$this->searchSummaryFormatted,"searchId"=>$searchId);
                
        }
}
