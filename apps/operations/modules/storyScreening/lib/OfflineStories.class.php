<?php

/***************************************************************************************************************
* FILE NAME     : OfflineStories.class.php
* DESCRIPTION   : Library class for addding an offline success story.
* CREATION DATE : 15-May-2013
* CREATED BY    : Rohit Khandelwal
* Copyright  2005, InfoEdge India Pvt. Ltd.
*****************************************************************************************************************/
/**
 * class OfflineStories
 * 
 */
class OfflineStories
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
	 * uploads a success story and make entries in success_stories and individual_stories tables.
	 * @param Object $actionObj
	 */
	public function uploadStory($actionObj)
	{
		$noExecute=0;
		if(!$this->paramsArr['name_h'] && !$this->paramsArr['name_w'])
		{
				$actionObj->noname=1;
				$noExecute = 1;
		}
		$this->paramsArr['story'] = trim($this->paramsArr['story']);
		if(!$this->paramsArr['story'])
		{
			$actionObj->nostory=1;
			$noExecute=1;
		}
		else
			$this->story=$this->paramsArr['story'];
			
		if($this->filesArr['frame']['name']||$this->filesArr['fullphoto']['name'] || $this->filesArr['homephoto']['name'] || $this->filesArr['squarephoto']['name'])
		{	
			if(!$this->filesArr['frame']['name']||!$this->filesArr['fullphoto']['name'] || !$this->filesArr['homephoto']['name'] || !$this->filesArr['squarephoto']['name'])
			{
				$actionObj->nopic=1;
				$noExecute=1;
			}
		}
		if(!$noExecute)
		{
				if(array_key_exists('user_h',$this->paramsArr))
				{
					$field_ss["USERNAME_H"]= $this->paramsArr['user_h'];
					$field_v["USERNAME_H"]= $this->paramsArr['user_h'];
				}
				if(array_key_exists('user_w',$this->paramsArr))
				{
					$field_ss["USERNAME_W"]= $this->paramsArr['user_w'];
					$field_v["USERNAME_W"] = $this->paramsArr['user_w'];
				}
				if($this->paramsArr['name_h'])
				{
					$field_is["NAME1"]= $this->paramsArr['name_h'];
					$field_ss["NAME_H"]= $this->paramsArr['name_h'];
					$field_v["NAME_H"]= $this->paramsArr['name_h'];
				}
				if($this->paramsArr['name_w'])
				{
				    $field_is["NAME2"]= $this->paramsArr['name_w'];
				    $field_ss["NAME_W"]= $this->paramsArr['name_w'];
					$field_v["NAME_W"]= $this->paramsArr['name_w'];
				}
				if($this->paramsArr['heading'])
					$field_is["HEADING"]=$this->paramsArr['heading'];
				elseif($this->paramsArr['name_h'] && $this->paramsArr['name_w'])
				{
					$field_is["HEADING"]= $this->paramsArr['name_h']." weds ".$this->paramsArr['name_w'];
				}
				if($this->paramsArr['contact'])
				{
					$field_ss["CONTACT_DETAILS"]= $this->paramsArr['contact'];
					$field_v["CONTACT"]= $this->paramsArr['contact'];
				}
				if($this->paramsArr['email_h'])
				{
					$field_ss["EMAIL"]= $this->paramsArr['email_h'];
					$field_v["EMAIL"]= $this->paramsArr['email_h'];
					$field_ss['SEND_EMAIL']=$this->paramsArr['email_h'];
				}
				if($this->paramsArr['email_w'])
				{
					$field_ss["EMAIL_W"]= $this->paramsArr['email_w'];
					$field_v["EMAIL"]= $this->paramsArr['email_w'];
					$field_ss['SEND_EMAIL']=$this->paramsArr['email_w'];
				}
				if($this->paramsArr['user_h'] || $this->paramsArr['user_w'])
				{
					
					if(!$this->paramsArr['email_h'])
						$fieldsStr='EMAIL,';
					if(!$this->paramsArr['contact'])
					 $fieldsStr .= 'CONTACT,';
					$fieldsStr .= " CITY_RES,PHONE_RES,PHONE_MOB,PROFILEID,OCCUPATION,COUNTRY_RES,RELIGION,CASTE,MTONGUE,USERNAME,SUBSCRIPTION,ACTIVATED";
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
						$field_v["CITY_RES"]=$profileDetail["CITY_RES"];
						$field_is["CITY"]=$profileDetail["CITY_RES"];
						
					    $field_v["PHONE_RES"]=$profileDetail["PHONE_RES"];        	        
                	   	$field_v["PHONE_MOB"]=$profileDetail["PHONE_MOB"];
	                	   	
	                    $field_v["PROFILEID"]=$profileDetail["PROFILEID"];	
						$field_is["OCCUPATION"]=$profileDetail["OCCUPATION"];
						$field_is["RELIGION"]=$profileDetail["RELIGION"];
						$field_is["CASTE"]=$profileDetail["CASTE"];
						$field_is["MTONGUE"]=$profileDetail["MTONGUE"];
						$field_is["COUNTRY"]=$profileDetail["COUNTRY_RES"];
						
						$field_ss["COMMENTS"]=$this->paramsArr['story'];
						$field_is["STORY"]= $this->paramsArr['story'];
						$field_ss["UPLOADED"]="A";
						$field_is["STATUS"]="A";
						$field_ss["WEDDING_DATE"]=$this->paramsArr['year']."-".$this->paramsArr['month']."-".$this->paramsArr['day'];
						$field_is['YEAR']=$this->paramsArr['year'];
						$field_ss["DATETIME"]=date("Y-m-d H:i:s");
						
						$field_ss["PHOTO"]="fullphoto";
						$lastid=AddStory::AddSuccessStory($field_ss);
		
					    $field_is["STORYID"] = $lastid;
						$field_v["STORYID"] = $lastid;
						
						$field_is["MAIN_PIC"]="fullphoto";
						$field_is["FRAME_PIC"]="frame";
						$field_is["HOME_PIC"]="homephoto";	
						$field_is["SQUARE_PIC"]="squarephoto";	
						AddStory::AddIndividualStory($field_is);
						
						if($field_v["CONTACT"] && $field_v["EMAIL"])
						{
							$this->voucherSuccesStoryDbObj->insertSuccessStory($field_v);
						}
					}
					else
					{
						$actionObj->novalidusername = 1;
						$noExecute = 1;
					}
		}
		else
		{
		 $actionObj->nousername = 1;
		 $noExecute = 1;
		}
		}
		$actionObj->noExecute = $noExecute;
	}
}
?>
