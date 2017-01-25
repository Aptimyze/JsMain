<?php

/***************************************************************************************************************
* FILE NAME     : FetchProfiles.class.php
* DESCRIPTION   : this contains the searchSEOProfiles result function 
* CREATION DATE : 27th July 2012
* CREATED BY    : Hemant Agrawal
* Copyright  2005, InfoEdge India Pvt. Ltd.
*****************************************************************************************************************/
/**
 * class FetchProfiles
 * 
 */
class FetchProfiles
{
   
	
	//private $page_source ;	
	private $dbObj;
	const CASTE_TYPE='CASTE';
    const COUNTRY_TYPE='COUNTRY_RES';
	const COUNTRY_NRI='NRI';
	const CITY_TYPE='CITY_RES';
	const OCC_TYPE='OCCUPATION';
	const MSTATUS_TYPE = 'MSTATUS';
	const SPL_TYPE = 'SPECIAL_CASES';
	const COUNTRY_INDIA='51';
	
	function __construct(){	
		$this->spl_cases_type_arr = array('HIV'=>'HIV','Deaf'=>'NATURE_HANDICAP','Dumb'=>'NATURE_HANDICAP','Blind'=>'NATURE_HANDICAP','Handicapped'=>'NATURE_HANDICAP');
		$this->spl_cases_val_arr = array('HIV'=>'Y','Deaf'=>'2','Dumb'=>'4','Blind'=>'3','Handicapped'=>'1');
		$this->static_arr = array('Thalassemia','Cancer Survivor','Diabetic','Leucoderma');
		$this->cancer_b_arr = array();			
	}
	
// Search SEO profiles function
   
   
  function SearchSeoProfiles($sparamObj)
  {
		$type1=$sparamObj->getLevel1Type();
		$type2=$sparamObj->getLevel2Type();
		$limitArr=$sparamObj->getLimits();
		// get top profiles by ntimes
		$pidArray = $this->seoSearchSOLR($sparamObj,$HAVEPHOTO='Y',$PRIVACY='A',$PHOTO_DISPLAY='A',$limitArr[0],$sort=1);
		
		if(!isset($pidArray))
		$pidArray = array(0=>-1);
 		$count = count($pidArray);
 		
 		//Required only if profiles fetch are less than expected
 		if($count < $limitArr[0])
 			$topNtimesProfiles = 0;
 		else {
 			$topNtimesProfiles = 1;
 		}
 		
 		//Again reset the limit 
 		$limitArr[0] = $limitArr[0]-$count;
		
 		if($pidArray)
			$notInProfileStr=implode(" ",$pidArray);

		// get city spec profiles
 		if($type1 == FetchProfiles::CITY_TYPE || $type2 == FetchProfiles::CITY_TYPE)
 			$pidTempArr = $this->seoSearchSOLR($sparamObj,$HAVEPHOTO='Y',$PRIVACY='A',$PHOTO_DISPLAY='A',$limitArr[1],$sort=1,$notInProfileStr);
 		else
 			$pidTempArr = $this->seoSearchSOLR($sparamObj,$HAVEPHOTO='Y',$PRIVACY='A',$PHOTO_DISPLAY='A',$limitArr[1],$sort=0,$notInProfileStr,$cluster=FetchProfiles::CITY_TYPE);

 		$count= count($pidTempArr);
 		if($count < $limitArr[1])
			$topCityProfiles = 0;
		else
			$topCityProfiles = 1;
				
		$limitArr[1] = $limitArr[1]-$count;
		$pidArray=$this->ArrayMerge($pidArray,$pidTempArr);
		if($pidArray)
			$notInProfileStr = implode(" ",$pidArray);
			
		//get occ spec profiles
		if($type1 == FetchProfiles::OCC_TYPE || $type2 == FetchProfiles::OCC_TYPE)
			$pidTempArr = $this->seoSearchSOLR($sparamObj,$HAVEPHOTO='Y',$PRIVACY='A',$PHOTO_DISPLAY='A',$limitArr[2],$sort=1,$notInProfileStr);
 		else
 			$pidTempArr = $this->seoSearchSOLR($sparamObj,$HAVEPHOTO='Y',$PRIVACY='A',$PHOTO_DISPLAY='A',$limitArr[2],$sort=0,$notInProfileStr,$cluster=FetchProfiles::OCC_TYPE);
 			
 		
 		$count = count($pidTempArr);
 		if($count < $limitArr[2])
			$topOccProfiles = 0;
		else
			$topOccProfiles = 1;
		
		$limitArr[2] = $limitArr[2]-$count;
		$pidArray=$this->ArrayMerge($pidArray,$pidTempArr);
		if($pidArray)
			$notInProfileStr = implode(" ",$pidArray);

		unset($pidTempArr);
		// get profiles if found no results from any of the above queries
		if($topNtimesProfiles == 0 || $topCityProfiles==0 || $topOccProfiles == 0)
        {
           
            if($topNtimesProfiles == 0)
            {
                $pidTempArr = $this->seoSearchSOLR($sparamObj,$HAVEPHOTO='',$PRIVACY='A',$PHOTO_DISPLAY='',$limitArr[1],$sort=1,$notInProfileStr);
                $pidArray=$this->ArrayMerge($pidArray,$pidTempArr);
                $notInProfileStr = implode(" ",$pidArray);
            }
            if($topCityProfiles == 0)
            {
                if($type1 == FetchProfiles::CITY_TYPE || $type2 == FetchProfiles::CITY_TYPE)
                    $pidTempArr = $this->seoSearchSOLR($sparamObj,$HAVEPHOTO='',$PRIVACY='A',$PHOTO_DISPLAY='',$limitArr[1],$sort=1,$notInProfileStr);
                else
                    $pidTempArr = $this->seoSearchSOLR($sparamObj,$HAVEPHOTO='',$PRIVACY='A',$PHOTO_DISPLAY='',$limitArr[1],$sort=0,$notInProfileStr,$cluster=FetchProfiles::CITY_TYPE);
               
                $pidArray=$this->ArrayMerge($pidArray,$pidTempArr);
                $notInProfileStr = implode(" ",$pidArray);
            }
            if($topOccProfiles == 0)
            {
                if($type1 == FetchProfiles::OCC_TYPE || $type2 == FetchProfiles::OCC_TYPE)
                    $pidTempArr = $this->seoSearchSOLR($sparamObj,$HAVEPHOTO='',$PRIVACY='A',$PHOTO_DISPLAY='',$limitArr[2],$sort=1,$notInProfileStr);
                else
                    $pidTempArr = $this->seoSearchSOLR($sparamObj,$HAVEPHOTO='',$PRIVACY='A',$PHOTO_DISPLAY='',$limitArr[2],$sort=0,$notInProfileStr,$cluster=FetchProfiles::OCC_TYPE);
               
                $pidArray=$this->ArrayMerge($pidArray,$pidTempArr);
               
            }
       
        }
		
		$key = array_search(-1, $pidArray);
		
		if(!($key===false) && $pidArray[$key] == -1)
			unset($pidArray[$key]);
		
		return $pidArray;
		
	
	}	
	

	// end of searchSeoProfiles function


	private function seoSearchSOLR($sparamObj,$HAVEPHOTO='',$PRIVACY='',$PHOTO_DISPLAY='',$noOfResult,$sort='',$notInProfileStr='',$cluster='')
	{

		$type1=$sparamObj->getLevel1Type();
		$type2=$sparamObj->getLevel2Type();
		$value1In=$sparamObj->getLevel1Value();
		$value2In=$sparamObj->getLevel2Value();
		$tableName=$sparamObj->getTableName();
		
		if($type1 == FetchProfiles::SPL_TYPE && $tableName == 'SEARCH_MALE')
		{
			if(in_array($value1In, $this->static_arr))
			{
				$this->spObj = new NEWJS_SPECIAL_CASES_PROFILES();
				$splArray = $this->spObj->getProfiles($value1In,'M');		
				$type1 = 'PROFILEID';
				$value1In = implode(" ", $splArray);		
			}
			else 
			{ 
				$type1 = $this->spl_cases_type_arr[$value1In];
		   		$value1In = $this->spl_cases_val_arr[$value1In];		
			}
			
		    
		}
		else if($type1 == FetchProfiles::SPL_TYPE && $tableName == 'SEARCH_FEMALE')
		{
			if(in_array($value1In, $this->static_arr))
			{
				$this->spObj = new NEWJS_SPECIAL_CASES_PROFILES();
				$splArray = $this->spObj->getProfiles($value1In,'F');
				$type1 = 'PROFILEID';
				$value1In = implode(" ", $splArray);
			}
			else
			{				
		    	$type1 = $this->spl_cases_type_arr[$value1In];
		    	$value1In = $this->spl_cases_val_arr[$value1In];
			}
			
		}
				
		$paramArr["HAVEPHOTO"]=$HAVEPHOTO;
		$paramArr["PHOTO_DISPLAY"]=$PHOTO_DISPLAY;
		$paramArr["PRIVACY"]=$PRIVACY;
		$paramArr["IGNORE_PROFILES"]=$notInProfileStr;
                
		if($tableName == 'SEARCH_FEMALE')
			$paramArr["GENDER"] = 'F';
		elseif($tableName == 'SEARCH_MALE')
			$paramArr["GENDER"] = 'M';
		
		$paramArr[$type1]=$value1In;
			
		if($type2)
			$paramArr[$type2]=$value2In;
		
		// handling of special case static array profiles
		if($type1 == 'PROFILEID')
			$paramArr["SHOW_PROFILES"]=$value1In;
                
                //format solr inputs from seo
                $paramArr = $this->formatSeoSolrInputs($paramArr);
                
		$SearchParametersObj = new SearchBasedOnParameters;
		$SearchParametersObj->getSearchCriteria($paramArr);
		$SearchParametersObj->setNoOfResults($noOfResult);
		if($sort && !$cluster)
			$SearchParametersObj->setSORT_LOGIC('S');
		$SearchServiceObj = new SearchService;
		if($sort && !$cluster)
			$SearchServiceObj->callSortEngine($SearchParametersObj,'S');
                
		if($cluster)
		{	
			//suggested		
			$clustersToShow = array($cluster);
			$respObj = $SearchServiceObj->performSearch($SearchParametersObj,"",$clustersToShow);
			$tempArr = $respObj->getClustersResults();
			if($tempArr)
			{
				$grpRows = $noOfResult;
				foreach($tempArr[$cluster] as $k=>$v)
				{
					if($grpRows<=0)
						break;
					else
						$topclusterOptions[] = $k;
					$grpRows--;
				}
				if(is_array($topclusterOptions))
				{
					$paramArr[$cluster] =  implode(",",$topclusterOptions);
					$SearchParametersObj->getSearchCriteria($paramArr);
					$sequenceOfOutput[$cluster] = $topclusterOptions;
				}
				unset($topclusterOptions);
			}
			//suggested		

			$grpField = $cluster;
			$grpRows = $noOfResult;
			$grpSort = 'NTIMES desc';
			$respObj = $SearchServiceObj->performGrouping($SearchParametersObj,$grpField,$grpLimit,$grpSort,$grpRows);
			$groupArr = $respObj->getGroupingResultsPidArr($sequenceOfOutput);//suggested
			return $groupArr[$cluster];
		}
		else
		{
			$respObj = $SearchServiceObj->performSearch($SearchParametersObj,"onlyResults");
			if($respObj->getSearchResultsPidArr() && is_array($respObj->getSearchResultsPidArr()))
				return $respObj->getSearchResultsPidArr();
			else
				return null;
		}
	}
	
	/*function - formatSeoSolrInputs
         * formats solr inputs from seo
         * @params: $paramArr
         * @return : $paramArr
         */
	function formatSeoSolrInputs($paramArr){
            if($paramArr["CITY_RES"]){
                $mappedCityValue = FieldMap::getFieldLabel('city_india',$paramArr["CITY_RES"]);
                //var_dump($mappedCityValue."--".strlen($paramArr["CITY_RES"]));
                //if city/state is in India
                if($mappedCityValue!=null && strlen($paramArr["CITY_RES"])>0){
                    $paramArr["COUNTRY_RES"] = FetchProfiles::COUNTRY_INDIA;
                    if(strlen($paramArr["CITY_RES"]) > 2){  //City case
                        $paramArr["CITY_INDIA"] = $paramArr["CITY_RES"];
                    }
                    else{
                        $paramArr["STATE"] = $paramArr["CITY_RES"];  //state case
                        unset($paramArr["CITY_RES"]);
                    }
                }
            } 
            //print_r($paramArr);
            return $paramArr;
        }
        
	function ArrayMerge($array1,$array2)
	{
		$returnArray=@array_merge($array1,$array2);
		if(!$returnArray)
			$returnArray=$array1;
		if(!$returnArray)
			$returnArray=$array2;	
		return $returnArray;
	}
	
			
} // end of FetchProfiles
?>
