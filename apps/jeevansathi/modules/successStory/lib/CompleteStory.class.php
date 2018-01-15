<?php
class CompleteStory
{
	function __construct()
	{
		$this->dbObj = new newjs_INDIVIDUAL_STORIES();
	}
	
	/**
	 * get full success story details
	 * @access public
	 * @param int $sid
	 * @return array $storyDetail
	 */
	public function getCompleteSuccessStory($sid)
	{
		$storyDetail = $this->dbObj->getCompleteStoryDetail($sid);
		return $storyDetail;
	}
	
	/**
	 * get stories array for the year used for next and previous stories
	 * @access public
	 * @param int $year
	 * @return array
	 */
	public function getNextSuccessStory($year)
	{
		$sidArr = $this->dbObj->getCompleteStory($year);
		return $sidArr;
	}
	
}
?>