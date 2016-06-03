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
class FetchProfilesOld 
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
   
   
  function SearchSeoProfiles( $sparamObj )
  {
		//$show_cluster=array('20','25','82','109','116','134','146','71','18','78');
		
		
		$type1=$sparamObj->getLevel1Type();
		$type2=$sparamObj->getLevel2Type();
		$value1In=$sparamObj->getLevel1Value();
		$value2In=$sparamObj->getLevel2Value();
		$tableName=$sparamObj->getTableName();
		$limitArr=$sparamObj->getLimits();
		if($type1 == 'SPECIAL_CASES' && $tableName == 'SEARCH_MALE')
		{
			if(in_array($value1In, $this->static_arr))
			{
				$this->spObj = new NEWJS_SPECIAL_CASES_PROFILES();
				$splArray = $this->spObj->getProfiles($value1In,'M');				
			}
			else 
			{ 
				$this->dbObj= new NEWJS_SEARCHMALE_TEXT();
				$type1 = $this->spl_cases_type_arr[$value1In];
		   		$value1In = $this->spl_cases_val_arr[$value1In];
		   		$splArray = $this->dbObj->getProfiles($type1,$value1In);				
			}
			$type1 = 'PROFILEID';
			$value1In = implode(",", $splArray);
			$this->dbObj= new NEWJS_SEARCHMALE_SEO();
		    
		}
		else if($type1 == 'SPECIAL_CASES' && $tableName == 'SEARCH_FEMALE')
		{
			if(in_array($value1In, $this->static_arr))
			{
				$this->spObj = new NEWJS_SPECIAL_CASES_PROFILES();
				$splArray = $this->spObj->getProfiles($value1In,'F');
			}
			else
			{
				$this->dbObj= new NEWJS_SEARCHFEMALE_TEXT();				
		    	$type1 = $this->spl_cases_type_arr[$value1In];
		    	$value1In = $this->spl_cases_val_arr[$value1In];
		    	$splArray = $this->dbObj->getProfiles($type1,$value1In);		    	
			}
			$type1 = 'PROFILEID';
			$value1In = implode(",", $splArray);
			$this->dbObj= new NEWJS_SEARCHFEMALE_SEO();
		}
		else if($tableName == 'SEARCH_MALE')
			$this->dbObj= new NEWJS_SEARCHMALE_SEO();
		else if($tableName == 'SEARCH_FEMALE')
			$this->dbObj= new NEWJS_SEARCHFEMALE_SEO();
			 	
		//print_r($this->dbObj);
    	$whereCon1=$this->UpdateTypeValues($type1,$value1In);
		
		
		$whereCon2=$this->UpdateTypeValues($type2,$value2In);
		
		if($type1==$type2)
		{
			unset($whereCon1);
		}
		if($whereCon1 && $whereCon2)
			$fieldQuery=" $whereCon1 and $whereCon2 ";
		elseif($whereCon1)
			$fieldQuery=" $whereCon1 ";
		else if($whereCon2)
			$fieldQuery=" $whereCon2 ";    
		if(!$fieldQuery)
		   return;
		$pidArray = $this->dbObj->getTopProfiles($fieldQuery,$limitArr[0]);
		
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
		$notInProfileStr=implode("','",$pidArray);

 		if($type1 == FetchProfiles::CITY_TYPE || $type2 == FetchProfiles::CITY_TYPE)
 			$pidTempArr = $this->dbObj->getCityProfileSpec( $fieldQuery,$limitArr[1], $notInProfileStr);
 		else
 			$pidTempArr = $this->dbObj->getTopCityProfile($fieldQuery,$limitArr[1], $notInProfileStr);

 		$count= count($pidTempArr);
 		if($count < $limitArr[1])
			$topCityProfiles = 0;
		else
			$topCityProfiles = 1;
				
		$limitArr[1] = $limitArr[1]-$count;
		$pidArray=$this->ArrayMerge($pidArray,$pidTempArr);
		if($pidArray)
			$notInProfileStr = implode("','",$pidArray);
			
		if($type1 == FetchProfiles::OCC_TYPE || $type2 == FetchProfiles::OCC_TYPE)
			$pidTempArr = $this->dbObj->getOccProfileSpec($fieldQuery,$limitArr[2], $notInProfileStr);
 		else
 			$pidTempArr = $this->dbObj->getTopOccProfile($fieldQuery,$limitArr[2], $notInProfileStr);
 			
 		
 		$count = count($pidTempArr);
 		if($count < $limitArr[2])
			$topOccProfiles = 0;
		else
			$topOccProfiles = 1;
		
		$limitArr[2] = $limitArr[2]-$count;
		$pidArray=$this->ArrayMerge($pidArray,$pidTempArr);
		if($pidArray)
			$notInProfileStr = implode("','",$pidArray);
		

	
		unset($pidTempArr);
		
		if($topNtimesProfiles == 0 || $topCityProfiles==0 || $topOccProfiles == 0)
		{
			
			if($topNtimesProfiles == 0)
			{
				$pidTempArr = $this->dbObj->getNoCountProfiles($fieldQuery, $limitArr[0], $notInProfileStr);
				$pidArray=$this->ArrayMerge($pidArray,$pidTempArr);
				$notInProfileStr = implode("','",$pidArray);
			}
			if($topCityProfiles == 0)
			{
				$pidTempArr = $this->dbObj->getNoCityProfiles($fieldQuery, $limitArr[1],$notInProfileStr);
				$pidArray=$this->ArrayMerge($pidArray,$pidTempArr);
				$notInProfileStr = implode("','",$pidArray);
			}
			if($topOccProfiles == 0)
			{
				$pidTempArr = $this->dbObj->getNoOccProfiles($fieldQuery,$limitArr[2], $notInProfileStr);
				$pidArray=$this->ArrayMerge($pidArray,$pidTempArr);
				
			}
		
		}
		
		$key = array_search(-1, $pidArray);
		
		if(!($key===false) && $pidArray[$key] == -1)
			unset($pidArray[$key]);
			
		return $pidArray;
		
	
	}	
	
	// end of searchSeoProfiles function
	
	function UpdateTypeValues($type,$value)
	{
		$casteObj = new RevampCasteFunctions(); 
		if(!isset($type) || !isset($value))
			return null;
		//Change function calling to symfony library.. 
		if($type==FetchProfiles::CASTE_TYPE)
		{
			if($type==FetchProfiles::CASTE_TYPE)
				$whereCon="S.$type in('".$casteObj->getAllcastes($value)."')";
		}
		else if($type==FetchProfiles::COUNTRY_TYPE && $value==FetchProfiles::COUNTRY_NRI)
        {
				//$value[0]=$value;
				$whereCon="S.$type not in (".FetchProfiles::COUNTRY_INDIA.")";
        }
        else
        {
			if($type==FetchProfiles::CITY_TYPE && $value==FetchProfiles::COUNTRY_INDIA)
			{
				if(is_numeric($value))
				{
					$whereCon="S.$type in($value)";
						//$value[0]=$value;
				 }
				else
				{
						$whereCon="S.$type like '$value%'";
						//$value[2]=$value;
				}
			}
			else if($type == FetchProfiles::CITY_TYPE)
			{
				if(is_numeric($value))
				{
					$whereCon="S.$type in($value)";
					//$value[0]=$value;
				}
			
				else
				{
				   $whereCon="S.$type like '$value%'";
				}
			}
			else if($type == FetchProfiles::MSTATUS_TYPE)
			{
				$whereCon = "S.$type in ('$value')";
			}
			else
			{
				$whereCon="S.$type in($value)";
			}
		}
	
		return $whereCon;
		
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
