<?php
/**
 * This class perform all operation related to handling of search paramters.Peform operations like search , clusters. 
 * @author Lavesh Rawat
 * @created 2012-07-10
 */
class SolrRequest implements RequestHandleInterface
{
	private $searchResults;
	private $solrPagination;
        private $solrCurlTimeout = 400;
	/**
	* constructor of solr Request class
	* @param responseObj contains information about output type (array/xml/...) and engine used(solr/sphinx/mysql....)
	* @param searchParamtersObj search paramters object.
        * Note: +3 explaination : we need to display to top $limitClustersOptions cluster options which should exclude options like blank others.
	*/
	public function __construct($responseObj,$searchParamtersObj='')
	{ 
                $this->responseObj = $responseObj;
		if($searchParamtersObj)
		{
	                $this->searchParamtersObj = $searchParamtersObj;

                        //$this->logSearch();
                        JsMemcache::getInstance()->incrCount("TOTAL_SEARCH_COUNT_".date("d"));
                        $profileObj = LoggedInProfile::getInstance('newjs_master');
                        if($profileObj->getPROFILEID())
                	{ 
                        	//if($profileObj->getPROFILEID()%7>2)
				if($profileObj->getPROFILEID()%4==0 || $profileObj->getPROFILEID()%4==1)
	                                $this->solrServerUrl = JsConstants::$solrServerProxyUrl1."/select";
        	                else
                	                $this->solrServerUrl = JsConstants::$solrServerProxyUrl."/select";
	                }
        	        else
                	{ 
				if(JsConstants::$whichMachine=='matchAlert') /* new matches load on one server */
	                        	$this->solrServerUrl = JsConstants::$solrServerProxyUrl1."/select";
				else
	                        	$this->solrServerUrl = JsConstants::$solrServerLoggedOut."/select";
	                }
                        
                        if($this->searchParamtersObj->getIS_VSP() && $this->searchParamtersObj->getIS_VSP() == 1){
                                $this->solrServerUrl = JsConstants::$solrServerForVSP."/select";
                        }
                        if($this->searchParamtersObj->getSHOW_RESULT_FOR_SELF()=='ISKUNDLIMATCHES'){
                                $this->solrServerUrl = JsConstants::$solrServerForKundali."/select"; 
                        }
                        if($this->searchParamtersObj->getSORT_LOGIC()==SearchSortTypesEnums::SortByVisitorsTimestamp){
								$this->solrServerUrl = JsConstants::$solrServerForVisitorAlert."/select"; 
                        }
              		$this->profilesPerPage = SearchCommonFunctions::getProfilesPerPageOnSearch($searchParamtersObj);
			/*
			if($this->responseObj->getShowAllClustersOptions())
				$this->solrClusterlimit = 10000; //some random max value
			else
				$this->solrClusterlimit = SearchConfig::$limitClustersOptions + 3;
			*/
			$this->solrClusterlimit = '10000';
		}
	}

	// http://devjs.infoedge.com:8080/apache-solr-4.0.0-BETA/update?stream.body=<delete><query>id:1857497</query></delete>&commit=true

	/*
	* This function is used to delete profile or array of profiles from solr.
	* @param pid array/comma-seperate string/idividual-id
	*/
	public function deleteIdFromSearch($pid)
	{
		if(is_array($pid))
			$pid = implode(" ",$pid);
		elseif(strstr($pid,","))
		{
			$pid = str_replace(' ','',$pid);
			$pid = str_replace(',',' ',$pid);
		}
                $post = "stream.body=<delete><query>id:(".$pid.")</query></delete>&commit=true";
                foreach(JsConstants::$solrServerUrls as $key=>$solrUrl){
                        $index = array_search($solrUrl, JsConstants::$solrServerUrls);
                        if($index == $key && $solrUrl == JsConstants::$solrServerUrls[$index]){
                                $url = $solrUrl."/update";
                                $this->sendCurlPostRequest($url,$post);
                        }
                }
		//print_r($this->searchResults);die;
		$this->responseObj->getFormatedResults($this->searchResults); // ????????
	}
	
        /**
        * This function is used to get search results.
        * @param results_cluster string options are onlyClusters(calculate clusters only)/onlyResults(calculate results only)
	* @param clustersToShow  containing list of clusters to show in order of display.
	* @param currentPage  page number of search result page.
	* @param cachedSearch implies search is cached and contains necessary details.
	* @param loggedInProfileObj
        * @return responseObj object-array containing info like (ResultsArray / totalResults)
        */
        public function getResults($results_cluster='all',$clustersToShow,$currentPage='',$cachedSearch='',$loggedInProfileObj='')
        {
		$this->clustersToShow = $clustersToShow;
		$this->results_cluster = $results_cluster;

		if($cachedSearch)
			$this->solrPostParams = $cachedSearch["URL"];
		else
		{
	                $this->setWhereCondition('',$loggedInProfileObj);
		}

		$this->formSolrSearchUrl();
		$this->pagination($currentPage);
		$this->sendCurlPostRequest($this->solrServerUrl,$this->solrPostParams.$this->solrPagination);
		$this->responseObj->getFormatedResults($this->searchResults,$this->solrPostParams,$this->searchParamtersObj,$loggedInProfileObj);
		//$this->debugQuery();
        }


        public function getGroupingResults($grpField,$grpLimit='',$grpSort='',$grpRows='',$loggedInProfileObj='')
        {
                $this->setWhereCondition(1,$loggedInProfileObj);
		$this->groupConditions[] = '&group=true';
                if($grpField)
                        $this->groupConditions[] = '&group.field='.$grpField;
                if($grpLimit)
                        $this->groupConditions[] = '&group.limit='.$grpLimit;
                if($grpSort)
                        $this->groupConditions[] = '&group.sort='.$grpSort;
                if($grpRows)
                        $this->groupConditions[] = '&rows='.$grpRows;
	
                $this->formSolrSearchUrl();
                $this->sendCurlPostRequest($this->solrServerUrl,$this->solrPostParams.$this->solrPagination);
                $this->responseObj->getFormatedResults($this->searchResults,$this->solrPostParams);
        }


	/* 
	* sets limits of search results like for page number 1 (0 to 10)   for page number 2 (11 to 20)....
	*/
	public function pagination($pageNumber)
	{
		if($this->results_cluster=='onlyCount')
		{
			$this->start = 0 ;
			$this->profilesPerPage = 0;
		}
		else
		{
			
			
			if(!$pageNumber)
				$pageNumber = 1;
			
			$this->start = ($pageNumber-1)*$this->profilesPerPage;
		}
		$this->solrPagination = "&start=".$this->start."&rows=".$this->profilesPerPage;
	}

	/**
	* This funcion will set the solr post parameters required to fetch the desired results.
	*/
	public function formSolrSearchUrl()
	{
		$urlToHit = $this->solrServerUrl;
		if(is_array($this->filters))
		foreach($this->filters as $k=>$v)
			$this->solrPostParams.= $v;

		if(is_array($this->clusters))
		foreach($this->clusters as $k=>$v)
			$this->solrPostParams.= $v;

		if(is_array($this->groupConditions))
		foreach($this->groupConditions as $k=>$v)
			$this->solrPostParams.= $v;
                //print_r($this->solrPostParams);die;
	}

	/**
	* General Utility function to send post curl request.
	* NEED TO MOVE IN COMMON LIBRARY
	*/	
	public function sendCurlPostRequest($urlToHit,$postParams)
	{
		$start = microtime(TRUE);
                if(php_sapi_name() === 'cli')
                    $this->searchResults = CommonUtility::sendCurlPostRequest($urlToHit,$postParams);
                else
                    $this->searchResults = CommonUtility::sendCurlPostRequest($urlToHit,$postParams,$this->solrCurlTimeout);
                $end= microtime(TRUE);
                $diff = $end - $start;
                if($diff > 2 ){
                        //$fileName = sfConfig::get("sf_upload_dir")."/SearchLogs/search_threshold".date('Y-m-d-h').".txt";
                        //file_put_contents($fileName, $diff." :::: ".$urlToHit."?".$postParams."\n\n", FILE_APPEND);
                }
                
                if(!$this->searchResults){
                        $fileName = sfConfig::get("sf_upload_dir")."/SearchLogs/search_threshold_empty_".date('Y-m-d-h').".txt";
                        file_put_contents($fileName, $diff." :::: ".$urlToHit."?".$postParams."\n\n", FILE_APPEND);
                }
	}

        /**
        * Filtering condition are set here 
        * we have divided filtering conditions in 2 part :
	  (a) filters  (b) special cluster
        */
        public function setWhereCondition($noSorting='',$loggedInProfileObj='')
        {
					
		$this->filters[]="q=*:*";
		$this->filters[]="&wt=phps";
		if($this->solrClusterlimit)
			$this->filters[]="&facet.limit=$this->solrClusterlimit";	
		if($this->results_cluster!='onlyResults' && $this->clustersToShow)
			$this->filters[] = "&facet=true&facet.zeros=false";
		if($this->results_cluster=='onlyClusters')
			$this->filters[] = '&rows=0';

		$rangeWhereArr = explode(",",$this->searchParamtersObj->getRangeParams());
		$valueWhereArr = explode(",",$this->searchParamtersObj->getWhereParams());

		if($this->searchParamtersObj->getGENDER()=='ALL')
			;
		elseif($this->searchParamtersObj->getGENDER()=='M')
			$this->filters[]="&fq=GENDER:M";
		else
			$this->filters[]="&fq=GENDER:F";
		
		if($valueWhereArr["OCCUPATION"] && $valueWhereArr["OCCUPATION_GROUPING"])
			unset($valueWhereArr["OCCUPATION_GROUPING"]);
		if($valueWhereArr["EDU_LEVEL_NEW"] && $valueWhereArr["EDUCATION_GROUPING"])
			unset($valueWhereArr["EDUCATION_GROUPING"]);

		if($this->searchParamtersObj->getINCOME_SORTBY())
			$valueWhereArr[]=  'INCOME_SORTBY';

                $setOrCond = array();
                foreach($valueWhereArr as $field)
                { 
                        eval('$value = $this->searchParamtersObj->get'.$field.'();');
                        
                        if($field=='INDIA_NRI' || ($field=='CITY_RES' && $this->searchParamtersObj->getCITY_RES()!='') || ($field=='CITY_INDIA' && $this->searchParamtersObj->getCITY_INDIA()!='') || ($field=='COUNTRY_RES' && $this->searchParamtersObj->getCOUNTRY_RES()!='') || ($field=='STATE' && $this->searchParamtersObj->getSTATE()!='')) {
                                $setOrCond[$field] = $value;
                                continue;
                        }
                                
												if($field=='OCCUPATION_GROUPING' && $this->searchParamtersObj->getOCCUPATION()!='')
													continue;
												if($field=='EDUCATION_GROUPING' && $this->searchParamtersObj->getEDU_LEVEL_NEW()!='')
													continue;
			                     
                        if($value)
                        {
				if($field=="SUBCASTE")
				{
					unset($tempArr);
					$tempArr = explode(" ",str_replace("/"," ",str_replace(","," ",strtolower($value))));
					foreach($tempArr as $k=>$v)
                                        {
                                                if(!$v)
                                                        unset($tempArr[$k]);
                                                else
                                                        $tempArr[$k] = trim($v);
                                        }
					if($tempArr && is_array($tempArr))
                                        {
						$textQuery[] = $field.":(".implode(" OR ",$tempArr).")";
					}
					unset($tempArr);
					
				}
				elseif($field=="KEYWORD")
				{
					unset($tempArr);
					$tempArr = explode(" ",str_replace("/"," ",str_replace(","," ",strtolower($value))));
					foreach($tempArr as $k=>$v)
					{
						if(!$v)
							unset($tempArr[$k]);
						else
							$tempArr[$k] = trim($v);
					}
				}
				elseif($field=="KEYWORD_TYPE")
				{
					if($tempArr && is_array($tempArr))
					{
						if($value == "OR")
							$textQuery[] = SearchConfig::$textBasedSearchParameters.":(".implode(" OR ",$tempArr).")";
						elseif($value == "AND")
							$textQuery[] = SearchConfig::$textBasedSearchParameters.":(".implode(" AND ",$tempArr).")";
						else
							$textQuery[] = SearchConfig::$textBasedSearchParameters.":(NOT ".implode(" AND NOT ",$tempArr).")";
						unset($tempArr);
					}
				}
				else
				{
					$solrFormatValue = str_replace(","," ",$value);
					$solrFormatValue = str_replace("','"," ",$solrFormatValue);
					$setWhereParams[]=$field;

					if(in_array($field,array('OCCUPATION','OCCUPATION_GROUPING','EDU_LEVEL_NEW','EDUCATION_GROUPING')))
          { 
						$fieldToLower = strtolower($field);
						if(strstr($field,'OCCUPATION'))
						{
							$setWhereParams[]='OCCUPATION_GROUPING';
							$solrFormatValue = str_replace(","," ",$this->searchParamtersObj->getOCCUPATION());
							$solrFormatValue = str_replace("','"," ",$solrFormatValue);
							$valGroup = $this->searchParamtersObj->getOCCUPATION_GROUPING();
							$solrFormatValueGroup = str_replace(","," ",$valGroup);
							$solrFormatValueGroup = str_replace("','"," ",$solrFormatValueGroup);
							$this->specialCases($field,$solrFormatValue,'occupation,occupation_grouping','OCCUPATION','OCCUPATION_GROUPING',$solrFormatValueGroup);
						}
						elseif(strstr($field,'EDU'))
						{
							$setWhereParams[]='EDUCATION_GROUPING';
							$solrFormatValue = str_replace(","," ",$this->searchParamtersObj->getEDU_LEVEL_NEW());
							$solrFormatValue = str_replace("','"," ",$solrFormatValue);
							$valGroup=$this->searchParamtersObj->getEDUCATION_GROUPING();
							$solrFormatValueGroup = str_replace(","," ",$valGroup);
							$solrFormatValueGroup = str_replace("','"," ",$solrFormatValueGroup);
							$this->specialCases($field,$solrFormatValue,'edu_level_new,education_grouping','EDU_LEVEL_NEW','EDUCATION_GROUPING',$solrFormatValueGroup);
						}
					}
					elseif(is_array($this->clustersToShow) && in_array($field,$this->clustersToShow))
					{ 
						
                                                if($field=="CITY_RES" || $field=="STATE" || $field=="NATIVE_STATE")
                                                        $solrFormatValue='"'.implode('","',explode(" ",$solrFormatValue)).'"';
                                                $fieldToLower = strtolower($field);
						if(!in_array($solrFormatValue,searchConfig::$dont_all_labels))
							$this->filters[]="&fq={!tag=$fieldToLower}$field:($solrFormatValue)";
							//$this->filters[]="&fq=$field:($solrFormatValue)";
						$this->clusters[]="&facet.field={!ex=$fieldToLower}$field";
					}
					else
                                        { 
																					
                                                if($field=="CITY_RES" || $field=="STATE" || $field=="NATIVE_STATE")
                                                        $solrFormatValue='"'.implode('","',explode(" ",$solrFormatValue)).'"';
                                                elseif($field=="HIV" && $solrFormatValue=="N")
                                                        $solrFormatValue="N NS";
                                                if(!in_array($solrFormatValue,searchConfig::$dont_all_labels))
							$this->filters[]="&fq=$field:($solrFormatValue)";
                                                //$this->clusters[]="&facet.field=$field";
					}
				}
                        }
                }
                if(!empty($setOrCond)){
                        if((isset($setOrCond["CITY_RES"]) || isset($setOrCond["CITY_INDIA"]) || isset($setOrCond["STATE"])) && isset($setOrCond["COUNTRY_RES"])){ 
                                $this->clusters[]="&facet.field={!ex=country_res,city_res,state}COUNTRY_RES";
                                $this->clusters[]="&facet.field={!ex=city_india}CITY_INDIA";
                                $this->clusters[]="&facet.field={!ex=state}STATE";
                                $setWhereParams[]="COUNTRY_RES";
                                $setWhereParams[]="CITY_RES";
                                $solrFormatValueCity = str_replace(","," ",$setOrCond["CITY_RES"]);
                                $solrFormatValueCity = str_replace("','"," ",$solrFormatValueCity);
                                $solrFormatValueCity='"'.implode('","',explode(" ",$solrFormatValueCity)).'"';
                                $solrFormatValueCityIndia = '';
                                if(isset($setOrCond["CITY_INDIA"])){
                                        $solrFormatValueCityIndia = str_replace(","," ",$setOrCond["CITY_INDIA"]);
                                        $solrFormatValueCityIndia = str_replace("','"," ",$solrFormatValueCityIndia);
                                        $solrFormatValueCityIndia='"'.implode('","',explode(" ",$solrFormatValueCityIndia)).'"';
                                }else{
                                    $solrFormatValueCityIndia = $solrFormatValueCity;
                                }
                                $solrFormatValueStateIndia = '';
                                if(isset($setOrCond["STATE"])){
                                        $solrFormatValueStateIndia = str_replace(","," ",$setOrCond["STATE"]);
                                        $solrFormatValueStateIndia = str_replace("','"," ",$solrFormatValueStateIndia);
                                        $solrFormatValueStateIndia='"'.implode('","',explode(" ",$solrFormatValueStateIndia)).'"';
                                        $setWhereParams[]="STATE";
                                }
                                $country = explode(',',$setOrCond["COUNTRY_RES"]);
                                $country = array_unique($country);
                                $countryCount = count($country);
                                foreach($country as $c){
                                        if($c!=51 || $countryCount == 1)
                                                $countries[] = $c;
                                }
                                $setOrCond["COUNTRY_RES"] = implode(',',$countries);
                                $solrFormatValueCOUNTRY = str_replace(","," ",$setOrCond["COUNTRY_RES"]);
                                $solrFormatValueCOUNTRY = str_replace("','"," ",$solrFormatValueCOUNTRY);
                                
                                $solrFormatValueCOUNTRY_RES = str_replace(","," ",implode(',',$country));
                                $solrFormatValueCOUNTRY_RES = str_replace("','"," ",$solrFormatValueCOUNTRY_RES);
                                //$this->filters[]="&fq={!tag=country_res}COUNTRY_RES:($solrFormatValueCOUNTRY_RES)";
                                //{!tag=country_res,city_res,city_india,state}
                                $searchOperator = "OR";
                                if($countryCount == 1 && $solrFormatValueCOUNTRY == '51'){
                                        $searchOperator = "AND";      
                                }
                                $stateCheck = '';
                                if($solrFormatValueStateIndia){
                                        $stateCheck = "AND STATE :($solrFormatValueStateIndia)";
                                }
                                if($solrFormatValueCityIndia){
                                        if($solrFormatValueCOUNTRY){
                                                $this->filters[]="&fq={!tag=country_res,city_res,city_india,state}(CITY_RES:($solrFormatValueCityIndia) $stateCheck) $searchOperator  COUNTRY_RES:($solrFormatValueCOUNTRY)";
                                                $this->filters[]="&fq={!tag=country_res,city_res,city_india,state}(CITY_INDIA:($solrFormatValueCityIndia) $stateCheck) $searchOperator  COUNTRY_RES:($solrFormatValueCOUNTRY)";
                                        }else{
                                                $this->filters[]="&fq={!tag=country_res,city_res,city_india,state}(CITY_RES:($solrFormatValueCityIndia) $stateCheck)";
                                                $this->filters[]="&fq={!tag=country_res,city_res,city_india,state}(CITY_INDIA:($solrFormatValueCityIndia) $stateCheck)";
                                        }
                                }elseif(isset($setOrCond["COUNTRY_RES"])){                                      
                                        if($stateCheck){
                                         $this->filters[]="&fq={!tag=country_res,city_res,city_india,state}STATE:($solrFormatValueStateIndia) $searchOperator COUNTRY_RES:($solrFormatValueCOUNTRY)";
                                        }else{
                                                $setWhereParams[]="COUNTRY_RES";
                                                $this->clusters[]="&facet.field={!ex=country_res,city_res,city_india,state}COUNTRY_RES";
                                                $solrFormatValueCOUNTRY = str_replace(","," ",$setOrCond["COUNTRY_RES"]);
                                                $solrFormatValueCOUNTRY = str_replace("','"," ",$solrFormatValueCOUNTRY); 
                                                $this->filters[]="&fq={!tag=country_res,city_res,city_india,state}COUNTRY_RES:($solrFormatValueCOUNTRY_RES)";       
                                        }
                                }
                        }elseif(isset($setOrCond["COUNTRY_RES"])){
                                $setWhereParams[]="COUNTRY_RES";
                                $this->clusters[]="&facet.field={!ex=country_res,city_res,city_india,state}COUNTRY_RES";
                                $solrFormatValueCOUNTRY = str_replace(","," ",$setOrCond["COUNTRY_RES"]);
                                $solrFormatValueCOUNTRY = str_replace("','"," ",$solrFormatValueCOUNTRY);
                                $this->filters[]="&fq={!tag=country_res,city_res,city_india,state}COUNTRY_RES:($solrFormatValueCOUNTRY)";
                        }elseif($setOrCond["STATE"]){
                                $solrFormatValueStateIndia = str_replace(","," ",$setOrCond["STATE"]);
                                $solrFormatValueStateIndia = str_replace("','"," ",$solrFormatValueStateIndia);
                                $solrFormatValueStateIndia='"'.implode('","',explode(" ",$solrFormatValueStateIndia)).'"';
                                $setWhereParams[]="STATE";
                                $this->clusters[]="&facet.field={!ex=city_res,city_india,state}STATE";
                                $this->filters[]="&fq={!tag=city_res,city_india,state}STATE:($solrFormatValueStateIndia)";
                        }elseif($setOrCond['CITY_RES'] && is_numeric($setOrCond['CITY_RES'])){
                            //added for seo solr for countries other than india
                                $this->clusters[]="&facet.field={!ex=country_res,city_res,state}COUNTRY_RES";
                                $this->clusters[]="&facet.field={!ex=city_india}CITY_INDIA";
                                $this->clusters[]="&facet.field={!ex=state}STATE";
                                $setWhereParams[]="CITY_RES";
                                $solrFormatValueCity = str_replace(","," ",$setOrCond["CITY_RES"]);
                                $solrFormatValueCity = str_replace("','"," ",$solrFormatValueCity);
                                $solrFormatValueCity='"'.implode('","',explode(" ",$solrFormatValueCity)).'"';
                                $this->filters[]="&fq={!tag=country_res,city_res,city_india,state}CITY_RES:($solrFormatValueCity)";
                        }
                }
		
	
		// value where for ends here
		if($textQuery && is_array($textQuery))
		{
			$this->filters[0] = "q=".implode(" AND ",$textQuery);
		}

		if(SearchConfig::$filteredRemove && $loggedInProfileObj && $loggedInProfileObj->getPROFILEID()!='')
		{
			$filterQuery = '';

			if($loggedInProfileObj->getAGE())
                        {
                                $filterQuery = $filterQuery."if(and(tf(AGE_FILTER,Y),if(and(if(abs(sub(min(PARTNER_LAGE,".$loggedInProfileObj->getAGE()."),PARTNER_LAGE)),0,1),if(abs(sub(max(PARTNER_HAGE,".$loggedInProfileObj->getAGE()."),PARTNER_HAGE)),0,1)),0,1)),1,0),";
                        }
                        if($loggedInProfileObj->getMSTATUS())
                        {
                                $filterQuery = $filterQuery."if(and(tf(MSTATUS_FILTER,Y),if(tf(PARTNER_MSTATUS,".$loggedInProfileObj->getMSTATUS()."),0,1)),1,0),";
                        }
                        if($loggedInProfileObj->getRELIGION())
                        {
                                $filterQuery = $filterQuery."if(and(tf(RELIGION_FILTER,Y),if(tf(PARTNER_RELIGION,".$loggedInProfileObj->getRELIGION()."),0,1)),1,0),";
                        }
                        if($loggedInProfileObj->getCASTE())
                        {
                                $filterQuery = $filterQuery."if(and(tf(CASTE_FILTER,Y),if(tf(PARTNER_CASTE,".$loggedInProfileObj->getCASTE()."),0,1)),1,0),";
                        }
                        if($loggedInProfileObj->getCOUNTRY_RES())
                        {
                                $filterQuery = $filterQuery."if(and(tf(COUNTRY_RES_FILTER,Y),if(tf(PARTNER_COUNTRYRES,".$loggedInProfileObj->getCOUNTRY_RES()."),0,1)),1,0),";
                        }
                        if($loggedInProfileObj->getCITY_RES())
                        {
																$filterQuery = $filterQuery."if(and(tf(CITY_RES_FILTER,Y),if(tf(PARTNER_CITYRES,".$loggedInProfileObj->getCITY_RES()."),0,1)),1,0),";
                        }
												if($loggedInProfileObj->getMTONGUE())
                        {
                                $filterQuery = $filterQuery."if(and(tf(MTONGUE_FILTER,Y),if(tf(PARTNER_MTONGUE,".$loggedInProfileObj->getMTONGUE()."),0,1)),1,0),";
                        }
                        if($loggedInProfileObj->getINCOME())
                        {
                                $filterQuery = $filterQuery."if(and(tf(INCOME_FILTER,Y),if(tf(PARTNER_INCOME_FILTER,".$loggedInProfileObj->getINCOME()."),0,1)),1,0),";
                        }
                        if($filterQuery)
                        {
				$filterQuery = rtrim($filterQuery,",");
                                $filterQuery = "sum(".$filterQuery.")";
				if($this->searchParamtersObj->getShowFilteredProfiles()=='N') //need to remove filtered profiles
					$this->filters[]="&fq={!frange l=0.0 u=0.0}if(and(tf(PRIVACY,F),".$filterQuery."),500,".$filterQuery.")";
				elseif($this->searchParamtersObj->getShowFilteredProfiles()=='X') //need to remove filtered profiles
					$this->filters[]="&fq={!frange l=0.0 u=10.0}if(and(1,".$filterQuery."),500,".$filterQuery.")";
				else // tag filtered profiles
					$this->filters[]="&fq={!frange l=0.0 u=10.0}if(and(tf(PRIVACY,F),".$filterQuery."),500,".$filterQuery.")";
                        }			
		}

		//online+ignore+contacted+viewed
		if($this->searchParamtersObj->getIgnoreProfiles())
			 $this->filters[]="&fq=-id:(".$this->searchParamtersObj->getIgnoreProfiles().")";
		if($this->searchParamtersObj->getProfilesToShow())
			 $this->filters[]="&fq=id:(".$this->searchParamtersObj->getProfilesToShow().")";
		if($this->searchParamtersObj->getOnlineProfiles())
			 $this->filters[]="&fq=id:(".$this->searchParamtersObj->getOnlineProfiles().")";
		//online+ignore+contacted+viewed

		//HIV ignore, MANGLIK ignore, MSTATUS ignore, HANDICAPPED ignore
		if($this->searchParamtersObj->getHIV_IGNORE())
			$this->filters[]="&fq=-HIV:(".str_replace(","," ",$this->searchParamtersObj->getHIV_IGNORE()).")";
		if($this->searchParamtersObj->getMANGLIK_IGNORE())
			$this->filters[]="&fq=-MANGLIK:(".str_replace(","," ",$this->searchParamtersObj->getMANGLIK_IGNORE()).")";
		if($this->searchParamtersObj->getMSTATUS_IGNORE())
			$this->filters[]="&fq=-MSTATUS:(".str_replace(","," ",$this->searchParamtersObj->getMSTATUS_IGNORE()).")";
		if($this->searchParamtersObj->getHANDICAPPED_IGNORE())
			$this->filters[]="&fq=-HANDICAPPED:(".str_replace(","," ",$this->searchParamtersObj->getHANDICAPPED_IGNORE()).")";
                if($this->searchParamtersObj->getOCCUPATION_IGNORE())
			$this->filters[]="&fq=-OCCUPATION:(".str_replace(","," ",$this->searchParamtersObj->getOCCUPATION_IGNORE()).")";
		//HIV ignore, MANGLIK ignore, MSTATUS ignore, HANDICAPPED ignore

                //Fso Verified Dpp Matches
                if($this->searchParamtersObj->getFSO_VERIFIED()){
                $this->filters[]="&fq=VERIFICATION_SEAL:(/".$this->searchParamtersObj->getFSO_VERIFIED().".*/)";}
                //Fso Verified Dpp Matches
         
		if(is_array($this->clustersToShow))
                foreach($this->clustersToShow as $field)
		{
			if(!is_array($setWhereParams) || !in_array($field,$setWhereParams))
				if(in_array($field,$valueWhereArr)) // => if(!in_array($field,array('VIEWED','AGE','HEIGHT','INCOME')))
					$this->clusters[]="&facet.field=$field";
		}
                
	        if(is_array($rangeWhereArr))
                foreach($rangeWhereArr as $field)
                {
                        eval('$lvalue = $this->searchParamtersObj->getL'.$field.'();');
                        eval('$hvalue = $this->searchParamtersObj->getH'.$field.'();');
                        if($lvalue && $hvalue)
                        {
				$this->filters[]="&fq=$field:[$lvalue $hvalue]";
                        }
                }
                
		if($this->results_cluster!='onlyCount')
		{
			if(!$noSorting)
				$this->addSortCriteria();
		}
	}

	/*
	* This function adds sort criteria to filter conditions
	*/
	public function addSortCriteria()
	{
		$sortingArr = $this->searchParamtersObj->getSORTING_CRITERIA();			
		$asc_or_descArr = $this->searchParamtersObj->getSORTING_CRITERIA_ASC_OR_DESC();			
		if(is_array($sortingArr))
		foreach($sortingArr as $k =>$sortArr)
		{
			if(is_array($sortArr))
			{
				foreach($sortArr as $k1=>$v1)
				{
					foreach($v1 as $kk=>$vv)
					{
						$expArr[]="map($k1,$kk,$kk,$vv,0)";
					}
				}
				foreach($expArr as $k1=>$v1)
				{
					if($k1>0)
					{
						$exp = "sum(".$exp.",".$v1.")";
					}
					elseif($k1==0)
					{
						$exp = $v1;
					}
				}
			}
			else
				$exp = $sortArr;

			$sortstringArr[] = $exp." ".$asc_or_descArr[$k];
		}
                $sortstringArr[] = 'id desc';
		if($sortstringArr)
			$this->filters[]="&sort=".implode(",",$sortstringArr);
		if($this->searchParamtersObj->getFL_ATTRIBUTE())
			$this->filters[]="&fl=".$this->searchParamtersObj->getFL_ATTRIBUTE();
		//print_r($this->filters);echo "\n\n\n\n\n";
		//die;
	}

					
	public function specialCases($field,$solrFormatValue,$tag,$label,$label2,$val2='')
	{
		
		$finalStr='';
		if($solrFormatValue!='')
			$finalStr="$label:($solrFormatValue) ";
		if($val2!='')
			$finalStr.="$label2:($val2)";
		$this->filters[]="&fq={!tag=$tag}$finalStr";
		if(is_array($this->clustersToShow))
		{
			if($field==$label && in_array($label2,$this->clustersToShow))
				$field=$label2;
			$this->clusters[]="&facet.field={!ex=$tag}$field";
		}
	}
	/* debug only*/
	public function debugQuery()
	{
		//&fq={!tag=mtongue}MTONGUE:(7 10 13 14 15 27 28 30 33 19)
		foreach($this->filters as $k=>$v)
		{
			unset($y);
			if(strstr($v,':['))
			{
				$y[1]=$v;
				$y[1]=str_replace(" "," AND ",$y[1]);
				$y[1]=str_replace(":["," BETWEEN ",$y[1]);
				$y[1]=str_replace("]","",$y[1]);
				$y[1]=str_replace("&fq=","",$y[1]);
			}
			elseif(strstr($v,'id:('))
			{
				$y = explode("}",$v);
				$y[1]=str_replace(":(","#IN#",$y[0]);
				$y[1]=str_replace(" ","','",$y[1]);
				$y[1]=str_replace("#IN#"," IN (",$y[1]);
				$y[1]=str_replace("(","('",$y[1]);
				$y[1]=str_replace(")","')",$y[1]);
				$y[1]=str_replace("&fq=id","PROFILEID",$y[1]);
				$y[1]=str_replace("&fq=-id","PROFILEID NOT",$y[1]);
				//print_r($y[1]);
			}
			else
			{
				$y = explode("}",$v);

				if(strstr($v,'COUNTRY') || strstr($v,'CITY_RES'))
				{
					if(!$y[1])
						$y[1] = $y[0];
				}
				$y[1]=str_replace(":(","#IN#",$y[1]);
				$y[1]=str_replace(" ","','",$y[1]);
				$y[1]=str_replace("#IN#"," IN (",$y[1]);
				$y[1]=str_replace("(","('",$y[1]);
				$y[1]=str_replace(")","')",$y[1]);
				$y[1]=str_replace("&fq=","",$y[1]);
			}
			if($y[1])
			$z[]=$y[1];
		}
		if($z)
		{
		$zz=implode(" AND ",$z);
		$zzz="SELECT COUNT(*) , RELIGION FROM SEARCH_FEMALE WHERE $zz GROUP BY RELIGION";
		echo $zzz;		echo "<br><br>";
		}
	}

        public function logSearch(){
                $Keytime = 3600000;
                $keyAuto = "COUNTER_SEARCH_TYPE_KEYS";
                $searchKey = "COUNTER_SEARCH_TYPE_";
                $Rurl = explode("/",trim($_SERVER["REQUEST_URI"],"/"));
                $searchKey .= $Rurl[0]."_";
                $app = MobileCommon::isApp();
                if(!$app){
                        if(MobileCommon::isDesktop()){
                                $app = "D";
                        }elseif(MobileCommon::isNewMobileSite()){
                                $app = "J";
                        }else{
                                $app = "O";
                        }
                }
                $searchKey .= $app."_";
                if(php_sapi_name() === 'cli'){
                        $searchKey .= "CLI_";
                }
                $searchKey .= $this->searchParamtersObj->getSEARCH_TYPE();
                JsMemcache::getInstance()->storeDataInCacheByPipeline($keyAuto,array($searchKey),$Keytime);
                JsMemcache::getInstance()->incrCount($searchKey);
        }
}
