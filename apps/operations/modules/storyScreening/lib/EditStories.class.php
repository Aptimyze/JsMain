<?php

/***************************************************************************************************************
* FILE NAME     : EditStories.class.php
* DESCRIPTION   : Library class to implement functionalities of Edit/Unhold story.
* CREATION DATE : 15-May-2013 
* CREATED BY    : Rohit Khandelwal
* Copyright  2005, InfoEdge India Pvt. Ltd.
*****************************************************************************************************************/
/**
 * class EditStories
 * 
 */
class EditStories
{

	private $paramsArr;
	private $filesArr;
	private $SuccessStoryDbObj;
	private $IndividualStoryDbObj;
	private $voucherSuccesStoryDbObj;
	
	function __construct($request)
	{
		$this->paramsArr = $request->getParameterHolder()->getAll();
		$this->filesArr = $request->getFiles();
		$this->SuccessStoryDbObj = new NEWJS_SUCCESS_STORIES();
		$this->IndividualStoryDbObj = new newjs_INDIVIDUAL_STORIES();
		$this->voucherSuccesStoryDbObj = new billing_VOUCHER_SUCCESSSTORY();
	}
	/**
	 * 
	 * method to search a story either by story id or username.
	 * @param Object $actionObj
	 */
	public function searchStories($actionObj)
	{
		if($this->paramsArr['search_user_h']|| $this->paramsArr['search_user_w'] || $this->paramsArr['search_name_h'] ||$this->paramsArr['search_name_w'] )
		{
	  		$this->searchByIdName($actionObj);
		}
		elseif($this->paramsArr['STORY_ID'])
		{
			$this->searchByStoryId($actionObj);
		}
		else
			$actionObj->NODATA=1;
	}
	/**
	 * 
	 * method to accept searched story.
	 * @param Object $actionObj
	 */
	public function acceptStories($actionObj)
	{
			$noExecute=0;
			if($this->paramsArr['STORY_ID'])
				$story_id=trim($this->paramsArr['STORY_ID']);
			if($this->paramsArr['user_h'])
				$user_h=trim($this->paramsArr['user_h']);
			if($this->paramsArr['user_w'])
				$user_w=trim($this->paramsArr['user_w']);
			if($this->paramsArr['contact'])
				$contact=trim($this->paramsArr['contact']);
			if($this->paramsArr['textstory'])
				$textstory=trim($this->paramsArr['textstory']);
			if($this->paramsArr['name_h'])
				$name_h=trim($this->paramsArr['name_h']);
			if($this->paramsArr['name_w'])
				$name_w=trim($this->paramsArr['name_w']);
			if($this->paramsArr['email'])
				$email=trim($this->paramsArr['email']);
			if(($name_h || $name_w) && $textstory)
				$noExecute = 0;
			else
			{
				if(!$name_h && !$name_w)
				$actionObj->NONAME = 1;
				if(!$textstory)
					$actionObj->NOSTORY=1;
				$actionObj->screenid=$this->paramsArr['id'];
				$actionObj->paramsArr['unsearch']=1;
				$noExecute = 1;
				
			}
			if(!$this->paramsArr['photo'] && ($this->filesArr['frame']['name'] || $this->filesArr['fullphoto']['name']) || !$this->paramsArr['sid'] && $this->filesArr['photo']['name'] && !$this->paramsArr['delete'])
			{
				if(!$this->filesArr['frame']['name']||!$this->filesArr['fullphoto']['name'] || !$this->filesArr['homephoto']['name'] || !$this->filesArr['squarephoto']['name'])
				{	
					$noExecute = 1;
					$actionObj->NOPIC = 1;
					$actionObj->screenid=$this->paramsArr['id'];
					$actionObj->paramsArr['unsearch']=1;
				}
			}
			if(!$noExecute)
			{
				$field_is["NAME1"]=$name_h;
				$field_ss["NAME_H"]=$name_h;
				$field_v["NAME_H"]=$name_h;
				$field_is["NAME2"]=$name_w;
				$field_ss["NAME_W"]=$name_w;
				$field_v["NAME_W"]=$name_w;
    			$field_ss["USERNAME_H"]=$user_h;
				$field_v["USERNAME_H"]=$user_h;
				$field_ss["USERNAME_W"]=$user_w;
				$field_v["USERNAME_W"]=$user_w;
				$field_ss["CONTACT_DETAILS"]=$contact;
				$field_v["CONTACT"]=$contact;
				$field_ss["EMAIL"]=$email;
				$field_v["EMAIL"]=$email;
				if($this->paramsArr['heading'])
				{
					$field_is["HEADING"]=$this->paramsArr['heading'];
				}
                elseif($name_w && $name_h)
				{
					$this->paramsArr['heading'] = $name_h." weds ".$name_w;
					$field_is["HEADING"]=$this->paramsArr['heading'];
				}
				$field_ss["COMMENTS"]=$textstory;
				$field_is["STORY"]=$textstory;
				if($user_h || $user_w)
	            {
					if(!$this->paramsArr['email'])
						$fieldsStr .= 'EMAIL,';
					if(!$this->paramsArr['contact'])
					 $fieldsStr .= 'CONTACT,';
					if(!$this->paramsArr['city'])
					 $fieldsStr .= 'CITY_RES,';					 
					$fieldsStr.= "PHONE_RES,PHONE_MOB,PROFILEID,OCCUPATION,COUNTRY_RES,RELIGION,CASTE,MTONGUE";
					$criteria = "USERNAME";
					if($this->paramsArr['user_h'])
						$value = $this->paramsArr['user_h'];
					else
						$value = $this->paramsArr['user_w'];
					$field_ss['USERNAME'] = $value;
				 	$dbObj = new JPROFILE();
				 	$profileDetail = $dbObj->get($value,$criteria,$fieldsStr);
				 	if(!$profileDetail && $this->paramsArr['user_h'] && $this->paramsArr['user_w'])
					{
						$value = $this->paramsArr['user_w'];
						$field_ss['USERNAME'] = $value;
						$profileDetail = $dbObj->get($value,$criteria,$fieldsStr);                      		
					}
				 	if($profileDetail)
					{						
	                    if(array_key_exists('CONTACT',$profileDetail))
	                    {
							$field_ss["CONTACT_DETAILS"]=$profileDetail["CONTACT"];
							$field_v["CONTACT"]=$profileDetail["CONTACT"];
	                    }
	                    if(array_key_exists('EMAIL',$profileDetail))
	                    {
					    	$field_ss["EMAIL"]=$profileDetail["EMAIL"];
							$field_v["EMAIL"]=$profileDetail["EMAIL"];
	                    }
	                    if(array_key_exists('CITY_RES',$profileDetail))
	                    {
							$field_v["CITY_RES"]=$profileDetail["CITY_RES"];
							$field_is["CITY"]=$profileDetail["CITY_RES"];
	                    }
					    $field_v["PHONE_RES"]=$profileDetail["PHONE_RES"];
	        	        $field_v["PHONE_MOB"]=$profileDetail["PHONE_MOB"];
	                	$field_v["PROFILEID"]=$profileDetail["PROFILEID"];
	                	$field_is["OCCUPATION"]=$profileDetail["OCCUPATION"];
						$field_is["RELIGION"]=$profileDetail["RELIGION"];
						$field_is["CASTE"]=$profileDetail["CASTE"];
						$field_is["MTONGUE"]=$profileDetail["MTONGUE"];
						$field_is["COUNTRY"]=$profileDetail["COUNTRY_RES"];
					}
				}
				if($this->paramsArr['delete'])
				{
					if($this->paramsArr['sid'])
					{
						$individualStoryObj = new IndividualStories('', $this->paramsArr['sid']);
						$individualStoryObj->setMAIN_PIC_URL('');
						$individualStoryObj->setFRAME_PIC_URL('');
						$individualStoryObj->setHOME_PIC_URL('');
						$individualStoryObj->setSQUARE_PIC_URL('');
						$individualStoryObj->UpdateRecord();
						$cmd1 = "rm ".JsConstants::$docRoot."/uploads/ScreenedImages/".$this->paramsArr['sid']."F.jpg";
						$cmd2 = "rm ".JsConstants::$docRoot."/uploads/ScreenedImages/".$this->paramsArr['sid']."M.jpg";
						$cmd3 = "rm ".JsConstants::$docRoot."/uploads/ScreenedImages/".$this->paramsArr['sid']."H.jpg";
						$cmd4 = "rm ".JsConstants::$docRoot."/uploads/ScreenedImages/".$this->paramsArr['sid']."S.jpg";
						$cmd1 = preg_replace('/[^A-Za-z0-9\. -_]/', '', $cmd1);
						$cmd2 = preg_replace('/[^A-Za-z0-9\. -_]/', '', $cmd2);
						$cmd3 = preg_replace('/[^A-Za-z0-9\. -_]/', '', $cmd3);
						$cmd4 = preg_replace('/[^A-Za-z0-9\. -_]/', '', $cmd4);
						passthru($cmd1);
						passthru($cmd2);
						passthru($cmd3);
						passthru($cmd4);
					}
				}
				/*else
				{
					if($this->paramsArr['frame'])
					{
						if(!$this->paramsArr['sid'])
						{
							$individualStoryObj = new IndividualStories();
						}
						$individualStoryObj = new IndividualStories('', $this->paramsArr['sid']);
					}
				}*/
			$field_ss["WEDDING_DATE"]=$this->paramsArr['year']."-".$this->paramsArr['month']."-".$this->paramsArr['day'];
			$field_ss["UPLOADED"]="A";
			$field_is["STATUS"]="A";
			$field_ss['ID'] = $this->paramsArr['id'];			
			$field_is['YEAR']=$this->paramsArr['year'];
			$field_ss["DATETIME"]=$this->paramsArr['datetime'];
			$field_ss["PHOTO"]="fullphoto";
			$field_is["MAIN_PIC"] = "fullphoto";
			$field_is["FRAME_PIC"] = "frame";
			$field_is["HOME_PIC"]  = "homephoto";
			$field_is["SQUARE_PIC"]  = "squarephoto";
			$noExecute=0;
			if($this->paramsArr['sid'])
				$field_is['SID'] = $this->paramsArr['sid'];
			/*$successStoryObj = new SuccessStories('',$this->paramsArr['id']);
			$successStoryObj->updateGetVar($field_ss);
			SuccessCommon::UpdatePicUrl($successStoryObj);
			$successStoryObj->UpdateRecord();*/
			$lastId = AddStory::AddSuccessStory($field_ss);
			$field_is["STORYID"]=$lastId;
			$field_v["STORYID"]=$lastId;
			
			/*$individualStoryObj->updateGetVar($field_is);
			SuccessCommon::UpdateIndividualPicUrl($individualStoryObj);*/
			if(!$noExecute)
			{					
				/*if($this->paramsArr['sid'])
				{
					$individualStoryObj->UpdateRecord();
				}
				else
					$individualStoryObj->InsertRecord();*/
					AddStory::AddIndividualStory($field_is);
			}
			if($this->voucherSuccesStoryDbObj->getID($this->paramsArr['id'])==0)
			{
				if($field_v["EMAIL"] && $field_v["CONTACT"] && $field_v['PHONE_RES'])
				{					
					$this->voucherSuccesStoryDbObj->insertSuccessStory($field_v);
				}			
			}
		}
		$actionObj->noExecute = $noExecute;
	}
	/**
	 * 
	 * Method to search a story by username or name.
	 * @param unknown_type $actionObj
	 */
	private function searchByIdName($actionObj)
	{
		if($this->paramsArr['search_user_h'])
	        	$whereClause['USERNAME_H'] = $this->paramsArr['search_user_h'];
		if($this->paramsArr['search_user_w'])
			$whereClause['USERNAME_W'] = $this->paramsArr['search_user_w'];
		if($this->paramsArr['search_name_h'])
			$whereClause['NAME_H'] = $this->paramsArr['search_name_h'];
		if($this->paramsArr['search_name_w'])
			$whereClause['NAME_W'] = $this->paramsArr['search_name_w'];
		if($whereClause)
		{
			$detailArr = $this->SuccessStoryDbObj->fetchStoryDetail($whereClause);
			if($detailArr)
			{
				foreach($detailArr as $val)
				{
					$this->status = $this->editStatus($val["UPLOADED"]);
					$this->explodeDate($val['WEDDING_DATE']);
					if($val["UPLOADED"]=="A" || $val["UPLOADED"]=="R")
					{
						$arr = $this->IndividualStoryDbObj->getPictureInfoByStoryId($val['ID']);
						$story[]=$this->createStoryArr($val, $arr);
					}
					else
					{
						$story[]=$this->createStoryArr($val);
					}//end of if
				}//end of for
				$actionObj->showformunhold = 1;
				$actionObj->story=$story;
				if($this->paramsArr['search_user_h'])
					$actionObj->search_user_h = $this->paramsArr['search_user_h'];
				if($this->paramsArr['search_user_w'])
					$actionObj->search_user_w = $this->paramsArr['search_user_w'];
				if($this->paramsArr['search_name_h'])
					$actionObj->search_name_h = $this->paramsArr['search_name_h'];
				if($this->paramsArr['search_name_w'])
					$actionObj->search_name_w = $this->paramsArr['search_name_w'];
			}//end of if
			else
				$actionObj->NOSTORY = 1;
		}
	}
	/**
	 * 
	 * Method to search a story by SID.
	 * @param Object $actionObj
	 */
	private function searchByStoryId($actionObj)
	{
		$arr = $this->IndividualStoryDbObj->getPictureInfoBySID($this->paramsArr['STORY_ID']);
  		//$datetime = $this->SuccessStoryDbObj->getDateTime($this->paramsArr['STORY_ID']);
  		if($arr)
		{
			$whereClause['ID'] = $arr['STORYID'];
			$result = $this->SuccessStoryDbObj->fetchStoryDetail($whereClause);
			$detailArr = $result[0];
			if(!$detailArr)
			{
				$detailArr['NAME_H']=$arr['NAME1'];
				$detailArr['NAME_W']=$arr['NAME2'];
				$detailArr['ID']=$arr['STORYID'];
				$detailArr['YEAR']=$arr['YEAR'];
				$detailArr['UPLOADED']=$arr['STATUS'];
				$detailArr['WEDDING_DATE']=$arr['YEAR']."0101";
			}
			$this->explodeDate($detailArr['WEDDING_DATE']);
			if($arr['YEAR']!='')
				$this->year=$arr['YEAR'];
			$this->status = $this->editStatus($detailArr["UPLOADED"]);
			$story[]=$this->createStoryArr($detailArr, $arr);
			$actionObj->showformunhold = 1;
			$actionObj->story=$story;
		}
		else
			$actionObj->NODATA=1;
	}
	/**
	 * 
	 * Return the corresponding status of uploaded value.
	 * @param string $uploaded
	 */
	private function editStatus($uploaded)
	{
		if($uploaded=="A")
			$status="UPLOADED";
		elseif($uploaded=="H")
			$status="HELD BACK";
		elseif($uploaded=="S")
			$status="SKIPPED";
		elseif($uploaded=="D")
			$status="REJECTED";
		elseif($uploaded=="R")
			$status="REMOVED";
		else
			$status="TO BE SCREENED";
		return $status;	
	}
 
	public function explodeDate($date)
	{
		list($this->year,$this->month,$this->day)=explode("-",$date);
		if($this->year=="0000")
		{
			$this->year="2007";
			$this->day="15";
			$this->month="03";
		}
	 }
 
    /**
     * 
     * returns the story array to use in template.
     * @param array $detailArr array of success story values.
     * @param array $arr array of individual story values.
     */ 
	public function createStoryArr($detailArr,$arr="")
 	{
	 	if($arr)
	 	{
			if($arr["MAIN_PIC_URL"])
			{
				$photo=1;
				$photo_m= $arr["MAIN_PIC_URL"];
				if($arr["FRAME_PIC_URL"])
					$photo_f = $arr["FRAME_PIC_URL"];
				if($arr["HOME_PIC_URL"])
					$photo_h=$arr["HOME_PIC_URL"];
				if($arr["SQUARE_PIC_URL"])
					$photo_sq=$arr["SQUARE_PIC_URL"];	
			}
			else
				$photo=0;
				
			if($detailArr["PIC_URL"])
			{
				$photo_ss=1;
				$photo_s = $detailArr["PIC_URL"];
			}
			else
			{
				$photo_ss=0;
				$photo_s = 0;
			}
		
			$storyArr=array("user_h" => $detailArr["USERNAME_H"],
							"user_w"=> $detailArr["USERNAME_W"],
							"name_h" => $detailArr["NAME_H"],
							"name_w" => $detailArr["NAME_W"],
							"heading" => $arr["HEADING"],
							"story"=>$arr["STORY"],
							"id" => $detailArr["ID"],
							"sid" => $arr["SID"],
							"status"=>$this->status,
							"email"=>$detailArr["EMAIL"],
							"datetime"=>$detailArr['DATETIME'],
							"contact"=>$detailArr["CONTACT_DETAILS"],
							"photo"=>$photo,
							"photo_m" => $photo_m,
							"photo_f"=>$photo_f,
							"photo_h"=>$photo_h,
							"photo_sq"=>$photo_sq,
							"photo_ss"=>$photo_ss,
							"year" => $this->year,
							"month" => $this->month,
							"day" => $this->day);
	 	}
	 	else
	 	{
	 		if($detailArr["PIC_URL"])
	 		{
				$photo=1;
				$photo_s = $detailArr['PIC_URL'];
	 		}
			else
			{
				$photo=0;
				$photo_s = 0;
			}
			$storyArr = array("user_h"=>$detailArr["USERNAME_H"],
								"user_w"=>$detailArr["USERNAME_W"],
								"name_h"=>$detailArr["NAME_H"],
								"name_w"=>$detailArr["NAME_W"],
								"story"=>$detailArr["COMMENTS"],
								"id"=>$detailArr["ID"],
								"status"=>$this->status,
								"email"=>$detailArr["EMAIL"],
								"contact"=>$detailArr["CONTACT_DETAILS"],							
								"photo"=>$photo,
								"photo_s"=>$photo_s,							
								"datetime"=>$detailArr["DATETIME"],
								"year" => $this->year,
		                        "month" => $this->month,      
		                        "day" => $this->day);
	 	}
		return $storyArr;
 }
}
?>
