<?php

/**
 *CLASS MoreSuccessStory  This class will get stories to show on success story page
 *
 * PHP versions 4 and 5
 * @package   jeevansathi
 * @subpackage   successStory
 * @author    hemant agarwal
 * */
class MoreSuccessStory
{
	const STORY_LIMIT = 24;
	/**
	 *those who deleted profile mentioneing that they found match on jeevansathi 
	 *and have not submitted the reason
	 *@access public
	 *@param string $fromSeo int $year array $seoParam
	 *@return array
	 *@uses NEWJS_SUCCESS_STORIES,
	 *
	 */
	
	function getMoreSuccessStory()
	{
		$successStoryObj = new NEWJS_SUCCESS_STORIES("newjs_slave");
		$noStoryArr = $successStoryObj->getMoreStory();
		return $noStoryArr;
		
	}
	
}
?>
