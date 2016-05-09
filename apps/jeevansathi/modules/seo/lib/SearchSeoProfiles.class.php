<?php

/***************************************************************************************************************
* FILE NAME     : SearchSeoProfiles.class.php
* DESCRIPTION   : This contains the searchParametes and their setters and getters method
* CREATION DATE : 27th July 2012
* CREATED BY    : Hemant Agrawal
* Copyright  2005, InfoEdge India Pvt. Ltd.
*****************************************************************************************************************/

class SearchSeoProfiles
{
	private $groomProfiles;
	private $brideProfiles;
	private $level1Type;
	private $level2Type;
	private $level1Value;
	private $level2Value;

	private $tableName;
	private $typeArr;
	private $limitArr;
	function __construct()
	{
		$this->limitArr=SearchSeoProfiles::getDefaultLimit();
		$this->typeArr=array('CITY'=>'CITY_RES','COUNTRY'=>'COUNTRY_RES','STATE'=>'CITY_RES');
	}
	
	public static function getDefaultLimit()
	{
		return array(6,3,3);
	}
	
	public function getGroomProfiles()
	{
		return $this->groomProfiles;
	}
	
	public function getBrideProfiles()
	{
		return $this->brideProfiles;
	}
	
		
	function getlevel1Type()
	{
			return $this->level1Type;
	}
	
	function getlevel2Type()
	{
		return $this->level2Type;
	}
	
	function getlevel1Value()
	{
		return $this->level1Value;
	}
	
	function getlevel2Value()
	{
		return $this->level2Value;
	}

	function getTableName()
	{
		return $this->tableName;
	}
	
	function getLimits()
	{
		return $this->limitArr;
	}
	
	public function setGroomProfiles($profileArr)
	{
		$this->groomProfiles=$profileArr;
	}
	
	public function setBrideProfiles($profileArr)
	{
		$this->brideProfiles=$profileArr;
	} 	
	
	function  setlevel1Type($value)
	{
		$this->level1Type=$value;
		if($this->typeArr[$value])
			$this->level1Type=$this->typeArr[$value];	
	}
	
	function  setlevel2Type($value)
	{
		$this->level2Type=$value;
		if($this->typeArr[$value])
			$this->level2Type=$this->typeArr[$value];
	}
	function setlevel1Value($value)
	{
		$this->level1Value=$value;
	}
	
	function setlevel2Value($value)
	{
		$this->level2Value=$value;
	}

	function setTableName($value)
	{
		$this->tableName=$value;
	}
	
	function setLimits($valueArr)
	{
		if(count($this->limitArr)==count($valueArr))
		{
			$this->limitArr=$valueArr;
		}
	}	
	
	function updateLimits($times)
	{
		$this->limitArr=$this->getLimits();
		foreach($this->limitArr as $key=>$val)
			$temp[$key]=$val*$times;
		$this->setLimits($temp);
	}
  
	function rollbackLimits()
	{
	  $this->limitArr = SearchSeoProfiles::getDefaultLimit();
	  $this->setLimits($this->limitArr);
	}
	
}
