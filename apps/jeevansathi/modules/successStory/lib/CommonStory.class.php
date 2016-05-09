<?php
/**
 *CLASS CommonStory
 * This class have common static methods used for success story page
 * 
 * 
 * PHP versions 4 and 5
 * @package   jeevansathi
 * @subpackage   successStory
 * @author    hemant agarwal
 * */

class CommonStory
{
	
	
	/**
	 *get count of total success stories
	 *@param
	 *@return integer
	 *@uses NEWJS_PROFILE_DEL_REASON
	 *
	 */
	
	public static function getTotalStoriesCount()
	{
		$dbObj = new NEWJS_PROFILE_DEL_REASON();
		$totalStory = $dbObj->getSuccessStoriesCount();
		return $totalStory;
	}
	
	/**
	 *get photchecksum
	 *@param profileid
	 *@return string
	 *@uses 
	 *
	 */
	public static function getPhotoChecksum($profileid)
	{
		$photochecksum = md5($profileid+5)."i".($profileid+5);
		$photochecksum_new = intval(intval($profileid)/1000) . "/" . md5($profileid+5);
		return $photochecksum_new;
	}
}

?>