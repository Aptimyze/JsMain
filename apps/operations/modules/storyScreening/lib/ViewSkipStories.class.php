<?php

/***************************************************************************************************************
* FILE NAME     :  ViewSuccessStories.class.php
* DESCRIPTION   :  Fetches skipped stories
* CREATION DATE :  15-May-2013
* CREATED BY    :  Rohit Khandelwal
* Copyright  2005, InfoEdge India Pvt. Ltd.
*****************************************************************************************************************/
/**
 * class ViewSkipStories
 * 
 */
class ViewSkipStories
{

	private $paramsArr;
	function __construct($paramsArr)
	{
		$this->paramsArr = $paramsArr;
	}
	/**
	 * 
	 * returns an array of skipped profiles.
	 */
	public function performAction()
	{
		$SuccessStoryDbObj = new NEWJS_SUCCESS_STORIES();
		$detailArr = $SuccessStoryDbObj->fetchSkippedProfiles();  	
		return $detailArr;
	}
}
?>
