<?php

/***************************************************************************************************************
* FILE NAME     : BridePage.class.php
* DESCRIPTION   : This is the child class of Page_Type and wraps the Community record object
* CREATION DATE : 25th July 2012
* CREATED BY    : Hemant Agrawal
* Copyright  2005, InfoEdge India Pvt. Ltd.
*****************************************************************************************************************/
/**
 * class BridePage
 * 
 */
class BridePage extends PageType
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
		$seoSearchProfileObj->setTableName("SEARCH_FEMALE");
		
		// Update limits in case of Bride page
		$seoSearchProfileObj->updateLimits($this->times);

		
		$FetchProfileObj = new FetchProfiles();
		$result=$FetchProfileObj->SearchSeoProfiles($seoSearchProfileObj);
		if($seoSearchProfileObj->getBrideProfiles())
		{
			$result=array_merge(@array_diff($seoSearchProfileObj->getBrideProfiles(),$result));
		}
		
		$seoSearchProfileObj->setBrideProfiles($result);

		$seoSearchProfileObj->rollbackLimits();

		return $seoSearchProfileObj;
	} // end of member function getProfiles

  

} // end of BridePage
?>
