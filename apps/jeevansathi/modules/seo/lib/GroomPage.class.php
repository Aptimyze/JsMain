<?php

/***************************************************************************************************************
* FILE NAME     : GroomPage.class.php
* DESCRIPTION   : This is the child class of Page_Type and wraps the BridePage class object
* CREATION DATE : 25th July 2012
* CREATED BY    : Hemant Agrawal
* Copyright  2005, InfoEdge India Pvt. Ltd.
*****************************************************************************************************************/
/**
 * class GroomPage
 * 
 */
class GroomPage extends PageType
{

   private $levelObj;
   private $times;
  
   function __construct($levelObj,$times=1)
   {
   	$this->levelObj=$levelObj;
   	
   	$this->times=$times;
		
   }
	function getProfiles()
	{
		$seoSearchProfileObj=$this->levelObj->getProfiles();
		$seoSearchProfileObj->setTableName("SEARCH_MALE");
		
		
		// Update limits in case of groom page
		$seoSearchProfileObj->updateLimits($this->times);
		
		
		$fetchProfileObj=new FetchProfiles();
		$result=$fetchProfileObj->SearchSeoProfiles($seoSearchProfileObj);
		
		if($seoSearchProfileObj->getGroomProfiles())
		{
			$result=array_merge(@array_diff($seoSearchProfileObj->getGroomProfiles(),$result));
		}
		$seoSearchProfileObj->setGroomProfiles($result);
		
		// Rollback limits in case of groom page
		$seoSearchProfileObj->rollbackLimits();

		return $seoSearchProfileObj;
	} // end of member function getProfiles






} // end of GroomPage
?>
