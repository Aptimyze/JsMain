<?php

/***************************************************************************************************************
* FILE NAME     : AcceptStories.class.php
* DESCRIPTION   : Libray class for accepting a story.
* CREATION DATE : 15-May-2013
* CREATED BY    : Rohit Khandelwal
* Copyright  2005, InfoEdge India Pvt. Ltd.
*****************************************************************************************************************/
/**
 * class AcceptStories
 * 
 */
class AcceptStories
{

	private $paramsArr;
	private $filesArr;
	
	function __construct($request)
	{
		$this->request = $request;
		$this->paramsArr = $request->getParameterHolder()->getAll();
		$this->filesArr = $request->getFiles();
	}
	/**
	 * 
	 * method to accept story.
	 * @param Object $actionObj
	 */
	public function acceptStory($actionObj)
	{
		
		$noExecute = 0;
  	
		if(!$this->paramsArr['delete'] && $this->paramsArr['photo'])
		{		
			if(!$this->filesArr['frame']['name'] || !$this->filesArr['fullphoto']['name']||!$this->filesArr['homephoto']['name'] || !$this->filesArr['squarephoto']['name'])
			{
				$actionObj->NOPIC = 1;
				$noExecute=1;
				if($this->paramsArr['skip'])
				$screenskip=1;
			}
		}
		elseif($this->paramsArr['delete'] && $this->paramsArr['photo'])
		{		
			if($this->filesArr['frame']['name'] || $this->filesArr['fullphoto']['name']||$this->filesArr['homephoto']['name'] || $this->filesArr['squarephoto']['name'])
			{
				$actionObj->NOPIC = 1;
				$noExecute=1;
				if($this->paramsArr['skip'])
				$screenskip=1;
			}
		}
		$story=trim($this->paramsArr['story']);
		if(!$story)
		{
			$actionObj->NOSTORY=1;
			$noExecute=1;
			if($this->paramsArr['skip'])
				$screenskip=1;
			if($this->paramsArr['delete'])
				$actionObj->delete = 1;
		}
		if(!$noExecute)
		{
			if($this->paramsArr['name_h'] && $this->paramsArr['name_w'])
					$heading=$this->paramsArr['name_h']." weds ".$this->paramsArr['name_w'];
			else
				$heading=$this->paramsArr['user_h']?$this->paramsArr['user_h']:$this->paramsArr['user_w'];	
			if($this->paramsArr['user_h'] ||$this->paramsArr['user_w'])
			{
				$dbObj = new JPROFILE();
				$fields = "PROFILEID,PHONE_RES,PHONE_MOB,CITY_RES,GENDER,COUNTRY_RES,OCCUPATION,RELIGION,CASTE,MTONGUE";
				$profileArr = $dbObj->get($this->paramsArr['email'],"EMAIL",$fields);
				if(!$profileArr && $this->paramsArr['user_h'])
				{
					$profileArr = $dbObj->get($this->paramsArr['user_h'],"USERNAME",$fields);
					if(!$profileArr && $this->paramsArr['user_w'])
					{
						$profileArr = $dbObj->get($this->paramsArr['user_w'],"USERNAME",$fields);
					}
				}		
				if($profileArr)
				{
					$field_v = $profileArr['GENDER'];
					if($profileArr['GENDER']=='M')
						$NAME=$this->paramsArr['name_h'];
					else 	
						$NAME=$this->paramsArr['name_w'];
						
					$field_v["CITY_RES"]=$profileArr["CITY_RES"];
					$field_is["CITY"]=$profileArr["CITY_RES"];
					$field_v['PROFILEID']=$profileArr['PROFILEID'];
					
					$field_v['PHONE_RES']=$profileArr['PHONE_RES'];
					$field_v['PHONE_MOB']=$profileArr['PHONE_MOB'];
					
					$field_is['COUNTRY']=$profileArr['COUNTRY_RES'];
					$field_is['OCCUPATION']=$profileArr['OCCUPATION'];
					$field_is['CASTE']=$profileArr['CASTE'];
					$field_is['RELIGION']=$profileArr['RELIGION'];
					$field_is['MTONGUE']=$profileArr['MTONGUE'];
				}
			}
			
			$field_is['STORYID']=$this->paramsArr['id'];
			$field_v['STORYID']=$this->paramsArr['id'];
			$field_v['USER_H']=$this->paramsArr['user_h'];
			$field_v['USER_W']=$this->paramsArr['user_w'];
			$field_is['NAME1']=$this->paramsArr['name_h'];
			$field_is['NAME2']=$this->paramsArr['name_w'];
			$field_is['HEADING']=$heading;
			$field_is['STORY']= $story;
				
			
			$field_is['STATUS']='A';
			$field_is['YEAR']=$this->paramsArr['year'];
			
			$field_is["MAIN_PIC"]="fullphoto";
			$field_is["FRAME_PIC"]="frame";
			$field_is["HOME_PIC"]="homephoto";
			$field_is["SQUARE_PIC"]="squarephoto";
			AddStory::AddIndividualStory($field_is);		
			
			$successStoryObj = new SuccessStories('', $this->paramsArr['id']);
			$successStoryObj->setUPLOADED('A');
			$successStoryObj->UpdateRecord();

			$field_v['EMAIL'] = $successStoryObj->getSEND_EMAIL();
			
			if($field_v['EMAIL']=="")
				$field_v['EMAIL'] = $successStoryObj->getEMAIL();
				
			$field_v['CONTACT'] = $successStoryObj->getCONTACT_DETAILS();
			
			
			$voucherSuccesStoryDbObj = new billing_VOUCHER_SUCCESSSTORY();
			$voucherSuccesStoryDbObj->insertSuccessStory($field_v);
	}
}
}
?>
