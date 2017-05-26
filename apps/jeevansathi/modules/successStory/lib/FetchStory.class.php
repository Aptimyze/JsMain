<?php

/**
*CLASS FetchStory  This class will get stories to show on success story page
*
* PHP versions 4 and 5
* 
* @package   jeevansathi
* @subpackage   successStory
* @author    hemant agarwal<hemant.a@jeevansathi.com>
* 
* */

class FetchStory
{
	const STORY_LIMIT = 9;
	/**
	 *
	 * This holds the type of level 1 seo
	 *
	 * @var string
	 */
	private $_parentType;
	
	/**
	 *
	 * This holds the type of level 2 seo
	 *
	 * @var string
	 */
	private $_mappedType;
	
	/**
	 *
	 * This holds the value of level 1 seo
	 *
	 * @var string
	 */
	private $_parentValue;
	
	/**
	 *
	 * This holds the value of level 1 seo
	 *
	 * @var string
	 */
	private $_mappedValue;
	
	/**
	 *
	 * This holds the year for which story needs to show
	 *
	 * @var string
	 */
	private $_year;
	
	/**
	 *
	 * This holds if user has landed from seo pages
	 * 
	 * @var string
	 */
	private $_fromSeo;
	
	
	function __construct()
	{
		$this->individualStoryObj = new newjs_INDIVIDUAL_STORIES();
	}
	
	/**
	 * sets parent type value
	 * @param integer $_parentType
	 * @access public
	 *
	 */
	public function setParentType($_parentType)
	{
		$this->_parentType = $_parentType;
	}
	
	/**
	 * gets parent type value
	 *
	 * @access public
	 * @return string
	 */
	public function getParentType()
	{
		return $this->_parentType;
	}
	/**
	 * sets parent value
	 * @param integer $_parentType
	 * @access public
	 *
	 */
	public function setParentValue($_parentValue)
	{
		$this->_parentValue = $_parentValue;
	}
	
	/**
	 * gets parent value
	 *
	 * @access public
	 * @return string
	 */
	public function getParentValue()
	{
		return $this->_parentValue;
	}
	
	/**
	 * sets mapped type value
	 * @param integer $_mappedType
	 * @access public
	 *
	 */
	public function setMappedType($_mappedType)
	{
		$this->_mappedType = $_mappedType;
	}
	
	/**
	 * gets mapped type value
	 *
	 * @access public
	 * @return string
	 */
	public function getmappedType()
	{
		return $this->_mappedType;
	}
	
	/**
	 * sets mapped value
	 * @param integer $_mappedValue
	 * @access public
	 *
	 */
	public function setMappedValue($_mappedValue)
	{
		$this->_mappedValue = $_mappedValue;
	}
	
	/**
	 * gets mapped value
	 *
	 * @access public
	 * @return string
	 */
	public function getMappedValue()
	{
		return $this->_mappedValue;
	}
	
	/**
	 * sets variable if landed from seo page 
	 * @param integer $_fromSeo
	 * @access public
	 *
	 */
	public function setFromSEO($_fromSeo)
	{
		$this->_fromSeo = $_fromSeo;
	}
	
	/**
	 * gets seo page variable
	 *
	 * @access public
	 * @return string
	 */
	public function getFromSEO()
	{
		return $this->_fromSeo;
	}
	
	/**
	 * sets year
	 * @param integer $_parentType
	 * @access public
	 *
	 */
	public function setYear($_year)
	{
		$this->_year = $_year;
	}
	
	/**
	 * gets year value
	 *
	 * @access public
	 * @return string
	 */
	public function getYear()
	{
		return $this->_year;
	}
	
	
	
	/**
	 *get success stories 
	 *@param $searchParam Object of FetchStory class
	 *@return array stories array with and without photo.
	 *@uses newjs_INDIVIDUAL_STORIES,
	 *
	 */
	public function getSuccessStories($searchParam)
	{
		
		$parentType = $searchParam->getParentType();
		$parentValue = $searchParam->getParentValue();
		$mappedType = $searchParam->getMappedType();
		$mappedValue = $searchParam->getMappedValue();
		$year = $searchParam->getYear();
		$fromSeo = $searchParam->getFromSEO();
		
		if($fromSeo == 'N')
			$searchParam->setParentType('');
		
			
		$story = $this->individualStoryObj->getStories($searchParam);
		$totalCount = count($story);
		
		//getting success stories if count is 0 from above
		
		if($totalCount == 0 && $fromSeo == 'Y' )
		{
			$searchParam->setParentType('');
			$story = $this->individualStoryObj->getStories($searchParam);
			$totalCountYear = count($story);
		}
		

		if($totalCountYear == 0 && isset($totalCountYear))
		{
			$year = $year-1;
			$searchParam->setYear($year);
			$story = $this->individualStoryObj->getStories($searchParam);
		}
	
		foreach($story as $key=>&$val)
		{
			$val['combinedName'] = $val['NAME1']." ~ ".$val['NAME2'];
			if(empty($val['SQUARE_PIC_URL'])){
				if(empty($val['MAIN_PIC_URL'])){
					$val['MAIN_PIC_URL'] = '/images/jspc/success_story/successCouple.png';
				}
			} else {
				$val['MAIN_PIC_URL'] = $val['SQUARE_PIC_URL'];
			}
			$storyArr['withphoto'][] = $val;
			// if($val['FRAME_PIC_URL'] != '')
			// 	$storyArr['withphoto'][] = $val;
			// else
			// 	$storyArr['withoutphoto'][] = $val;
		}
		
		if($totalCount == 0 && $fromSeo == 'Y' )
		{
			$storyArr['year'] = $year;
			$storyArr['seo'] = 1;
		}
		else if($fromSeo == 'Y')
		{
			$storyArr['year'] = '';
			$storyArr['seo'] = 1;
		}
		else
		{
			$storyArr['year'] = $year;
			$storyArr['seo'] = 0;
		}
		
		return $storyArr;
		
	}
	
}
?>
