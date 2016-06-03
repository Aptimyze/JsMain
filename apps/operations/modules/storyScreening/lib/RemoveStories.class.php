<?php

/***************************************************************************************************************
* FILE NAME     : RemoveStories.class.php
* DESCRIPTION   : library class to search and remove success story.
* CREATION DATE : 15-May-2013
* CREATED BY    : Rohit Khandelwal
* Copyright  2005, InfoEdge India Pvt. Ltd.
*****************************************************************************************************************/
/**
 * class RemoveStories
 * 
 */
class RemoveStories
{

	private $paramsArr;
	private $SuccessStoryDbObj;
	private $IndividualStoryDbObj;
	
	function __construct($paramsArr)
	{
		$this->paramsArr = $paramsArr;
		$this->SuccessStoryDbObj = new NEWJS_SUCCESS_STORIES();
		$this->IndividualStoryDbObj = new newjs_INDIVIDUAL_STORIES();
	}
	/**
	 * 
	 * Searches a story by username or name.
	 * @param Object $actionObj
	 */
	public function searchStories($actionObj)
	{
		$this->paramsArr['user_h']=trim($this->paramsArr['user_h']);
		$this->paramsArr['user_w']=trim($this->paramsArr['user_w']);
		$this->paramsArr['name_h']=trim($this->paramsArr['name_h']);
		$this->paramsArr['name_w']=trim($this->paramsArr['name_w']);
		if($this->paramsArr['user_h']|| $this->paramsArr['user_w'] || $this->paramsArr['name_h'] ||$this->paramsArr['name_w'] )
		{
			if($this->paramsArr['user_h'])
				$whereClause['USERNAME_H'] = $this->paramsArr['user_h'];
			if($this->paramsArr['user_w'])
				$whereClause['USERNAME_W'] = $this->paramsArr['user_w'];
			if($this->paramsArr['name_h'])
				$whereClause['NAME_H'] = $this->paramsArr['name_h'];
			if($this->paramsArr['name_w'])
				$whereClause['NAME_W'] = $this->paramsArr['name_w'];
			if($whereClause)
				$detailArr = $this->SuccessStoryDbObj->fetchStoryDetail($whereClause);
			if($detailArr)
			{
				$noExecute=1;
				foreach($detailArr as $val)
				{
					if($val["UPLOADED"]!="A")
					{	
						if($this->paramsArr['user_h'])
							$actionObj->user_h = $this->paramsArr['user_h'];
						if($this->paramsArr['user_w'])
							$actionObj->user_w = $this->paramsArr['user_w'];
						if($this->paramsArr['name_h'])
							$actionObj->name_h = $this->paramsArr['name_h'];
						if($this->paramsArr['name_w'])
							$actionObj->name_w = $this->paramsArr['name_w'];		
					}
					else
					{
						$id=$val["ID"];
						$arr = $this->IndividualStoryDbObj->getPictureInfoByStoryId($id);
						$actionObj->showformremove = 1;
						if($arr["MAIN_PIC_URL"])
        	        	{
							$photo=1;
							$photo_m = $arr["MAIN_PIC_URL"];
							$photo_f = $arr["FRAME_PIC_URL"];            	                
						}
						else
							$photo=0;
						$story[]=array("name_h" => $val["NAME_H"],
								       "name_w" => $val["NAME_W"],
									"user_h"=>$val["USERNAME_H"],
									"user_w"=>$val["USERNAME_W"],
						            "story" => $arr["STORY"],
									"id" => $id,
									"sid"=>$arr["SID"],
									"heading"=>$arr["HEADING"],
									"photo"=>$photo,
									"photo_m"=>$photo_m,
									"photo_f"=>$photo_f);
						$noExecute = 0;
						}
					}
					if($noExecute)
						$actionObj->NOTUP = 1;
					else
						$actionObj->story = $story;
				}
				else
				$actionObj->NOSTORY = 1;				
			}
			elseif($this->paramsArr['STORY_ID'])
			{
				$arr = $this->IndividualStoryDbObj->getPictureInfoBySID($this->paramsArr['STORY_ID']);
  
  				if($arr)
				{
					$whereClause['ID'] = $arr['STORYID'];
					$result = $this->SuccessStoryDbObj->fetchStoryDetail($whereClause);
					$detailArr = $result[0];
					if($detailArr)
					{
						$actionObj->showformremove = 1;
						if($arr["MAIN_PIC_URL"])
	        	        {
							$photo=1;
							$photo_m = $arr["MAIN_PIC_URL"];
							$photo_f = $arr["FRAME_PIC_URL"];            	                
						}
						else
							$photo=0;
						$story[]=array("name_h" => $detailArr["NAME_H"],
								       "name_w" => $detailArr["NAME_W"],
										"user_h"=>$detailArr["USERNAME_H"],
										"user_w"=>$detailArr["USERNAME_W"],
							            "story" => $arr["STORY"],
										"id" => $id,
										"sid"=>$arr["SID"],
										"heading"=>$arr["HEADING"],
										"photo"=>$photo,
										"photo_m"=>$photo_m,
										"photo_f"=>$photo_f);
						$noExecute = 0;
					}								
					if($noExecute)
						$actionObj->NOTUP = 1;
					else
						$actionObj->story = $story;
				}
				else
					$actionObj->NOSTORY = 1;
			}
			else
				$actionObj->NODATA = 1;
		}
}
?>
