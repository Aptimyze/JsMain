<?php
class NonScreenedPicture extends Picture
{
	function __construct($source='')
	{
		$this->pictureType='N';
                $this->source=$source;
	}
	
	//Getter Setter Methods
        public function getProfilePicUrl()
        {
                return $this->profilePicUrl;
        }
        public function getThumbailUrl()
        {
                return $this->thumbnailUrl;
        }
        public function getThumbail96Url()
        {
                return $this->thumbnail96Url;
        }
        public function getMainPicUrl()
        {
                return $this->mainPicUrl;
        }
	public function getMobileAppPicUrl()
        {
                if($this->MobileAppPicUrl || $this->source=="SCREENING")
                        return $this->MobileAppPicUrl;
                else
                        return $this->mainPicUrl;
        }
	public function getPictureType()
	{
		return $this->pictureType;
	}
	public function getIsPhotoShown()
	{
		return $this->isPhotoShown;
	}
        public function getProfilePic120Url()
	{
		return $this->ProfilePic120Url;
	}
         public function getProfilePic235Url()
	{
		return $this->ProfilePic235Url;
	}
         public function getProfilePic450Url()
	{
		return $this->ProfilePic450Url;
	}
         public function getSCREEN_BIT()
	{
		return $this->SCREEN_BIT;
	}
        public function setIsPhotoShown($isProfilePhotoShown)
	{
		$this->isPhotoShown = $isProfilePhotoShown;
	}

        public function setThumbailUrl($id)
        {
        //        $this->thumbnailUrl=sfConfig::get('app_photo_url')."/uploads/NonScreenedImages/thumbnail/".$id.".jpg";
		$this->thumbnailUrl = $id;
        }
        public function setThumbail96Url($id)
        {
        //        $this->thumbnail96Url=sfConfig::get('app_photo_url')."/uploads/NonScreenedImages/thumbnail96/".$id.".jpg";
		$this->thumbnail96Url = $id;
        }
        public function setProfilePicUrl($id)
        {
         //       $this->profilePicUrl=sfConfig::get('app_photo_url')."/uploads/NonScreenedImages/profilePic/".$id.".jpg";
		$this->profilePicUrl = $id;
        }
        public function setMainPicUrl($id)
        {
         //       $this->mainPicUrl=sfConfig::get('app_photo_url')."/uploads/NonScreenedImages/mainPic/".$id.".jpg";
		$this->mainPicUrl = $id;
        }
        public function getSearchPicUrl()
        {
                return $this->searchPicUrl;
        }
        public function getOriginalPicUrl()
        {
                return $this->OriginalPicUrl;
        }
		public function getLocalMainPicUrl()
        {
                return $this->LocalMainPicUrl;
        }
        public function getWATERMARK()
        {
                return $this->WATERMARK;
        }
        public function getORDERING()
        {
                return $this->ORDERING;
        }
        public function setSearchPicUrl($url)
        {
                $this->searchPicUrl = $url;
        }
        public function setProfilePic120Url($url)
	{
		$this->ProfilePic120Url=$url;
	}
         public function setProfilePic235Url($url)
	{
		$this->ProfilePic235Url=$url;
	}
         public function setProfilePic450Url($url)
	{
		$this->ProfilePic450Url=$url;
	}
         public function setSCREEN_BIT($bit)
	{
		$this->SCREEN_BIT=$bit;
	}
        public function setMobileAppPicUrl($url)
	{ 
		$this->MobileAppPicUrl=$url;
	}
        public function setOriginalPicUrl($url)
	{
		$this->OriginalPicUrl=$url;
	}
        public function setWATERMARK($Watermark)
	{
		$this->WATERMARK=$Watermark;
	}
        public function setLocalMainPicUrl($url)
	{
		$pictureobj = new PictureFunctions;
		if($url)
		$url=$pictureobj->getCloudOrApplicationCompleteUrl($url,true);
		$flag=substr($url,0,8);
		if($flag=="/var/www")
		$this->LocalMainPicUrl=$url;
		else
		$this->LocalMainPicUrl="";
	}
	//Getter Setter Methods
	
        public function setDetail($keyValueArray)
        {
		foreach($keyValueArray as $k=>$v)
                {
			$setArrayAllowed= array_merge(array("TITLE","KEYWORD","PICTUREID","ORDERING","UPDATED_TIMESTAMP","PROFILEID","SCREEN_BIT","PICFORMAT","WATERMARK"),ProfilePicturesTypeEnum::$PICTURE_NONSCREENED_SIZES_FIELDS);
                       	if(in_array($k,$setArrayAllowed))
			{
				eval('$this->set'.$k.'($v);');
			}
                        if($k=="MainPicUrl")
                                $this->setLocalMainPicUrl($v);
                }
                
        }
	
	 /**Added by Reshu 
        This function is used to set all the urls complete of the picture provided.
        */

        public function setCompletePictureUrl()
        {
                $completeUrlArrayAllowed= ProfilePicturesTypeEnum::$PICTURE_NONSCREENED_SIZES_FIELDS;
                foreach($completeUrlArrayAllowed as $v=>$k)
                {
			$setServer="";
			eval('$value = $this->get'.$k.'();');
			if($value)
			{
				$setServer = PictureFunctions::getCloudOrApplicationCompleteUrl($value);
                        	if($setServer)
                                       eval('$this->set'.$k.'($setServer);');
                        }
               }
        }


	public function getSaveUrl($picType,$picId,$profileId,$type="")
        {
		return parent::getSaveUrlPicture($picType,$picId,$profileId,$type,'nonScreened');
        }

	public function getDisplayPicUrl($picType,$picId,$profileId,$type="",$imgAttachName="")
	{
		return parent::getDisplayPicUrlPicture($picType,$picId,$profileId,$type,'nonScreened',$imgAttachName);
	}
	public function getLocalPicUrl($picType,$picId,$profileId,$type="")
        {
		return parent::getSaveUrlPicture($picType,$picId,$profileId,$type,'nonScreened');
        }
	 // wrapper functions created by Reshu for PICTURE_FOR_SCREEN_NEW store 

        /* Wrapper for PICTURE_FOR_SCREEN_NEW->ins()       
         * @param paramArr array contains key value pair for insertion
        * @returns true on success
        */
        public function ins($paramArr=array())
        {
                $photoObj=new PICTURE_FOR_SCREEN_NEW;
                $status=$photoObj->ins($paramArr);
                return $status;
        }
        /**
          Wrapper for PICTURE_FOR_SCREEN_NEW->get()
        * @param  paramArr array contains where condition on basis of which entry will be fetched.
        * @return detailArr array picture(s) info. Return null in case of no matching rows found.
        **/
        public function get($paramArr=array())
        {
                $photoObj=new PICTURE_FOR_SCREEN_NEW;
                $detailArr=$photoObj->get($paramArr);
                return $detailArr;
        }
         /**
         Wrapper for PICTURE_FOR_SCREEN_NEW->edit()
        * @param paramArr array contains where condition on basis of which entry will be fetched.
        * @param pictureId int update is only only by pictureId.
        * @returns true on sucess
        **/
        public function edit($paramArr=array(),$pictureId,$profileId)
        {
                $photoObj=new PICTURE_FOR_SCREEN_NEW;
                $status=$photoObj->edit($paramArr,$pictureId,$profileId);
                return $status;
        }
	
	public function del($paramArr=array())
        {
                $photoObj=new PICTURE_FOR_SCREEN_NEW;
                $count=$photoObj->del($paramArr);
                return $count;

        }

        public function updateOrdering($paramArr=array())
        {
                $photoObj=new PICTURE_FOR_SCREEN_NEW;
                $photoObj->updateOrdering($paramArr);
        }

        public function getMaxOrdering($profileId)
        {
                $photoObj=new PICTURE_FOR_SCREEN_NEW;
                $max=$photoObj->getMaxOrdering($profileId);
                return $max;
        }


        public function deleteRowsBasedOnPicId($picIdArr)
        {
                $photoObj=new PICTURE_FOR_SCREEN_NEW;
                $photoObj->deleteRowsBasedOnPicId($picIdArr);
        }

	public function insOnAppTable($param)
	{
		$photoObj=new NEWJS_PICTURE_FOR_SCREEN_APP;
		$photoObj->ins($param);
		unset($photoObj);
	}

	public function getFromAppTable($profileid)
	{
		$photoObj=new NEWJS_PICTURE_FOR_SCREEN_APP;
                $output = $photoObj->get($profileid);
		if($output && is_array($output))
		{
			foreach($output as $k=>$v)
			{
				foreach($v as $kk=>$vv)
				{
					if($kk=="MainPicUrl")
						$output[$k][$kk] = PictureFunctions::getCloudOrApplicationCompleteUrl($vv);
					elseif($kk=="AlgoPicUrl")
						$output[$k][$kk] = PictureFunctions::getCloudOrApplicationCompleteUrl($vv);
				}	
			}
		}
                unset($photoObj);
		return $output;
	}

	public function getLatestPictureIdForProfile($profileid)
	{
		$photoObj=new NEWJS_PICTURE_FOR_SCREEN_APP;
		$output = $photoObj->getLatestPictureIdForProfile($profileid);
		unset($photoObj);
		return $output;
	}
	
	public function delFromAppTable($profileid,$pictureid)
	{
		$photoObj=new NEWJS_PICTURE_FOR_SCREEN_APP;
                $photoObj->del($profileid,$pictureid);
                unset($photoObj);
	}

	public function getCountFromAppTable()
	{
		$photoObj=new NEWJS_PICTURE_FOR_SCREEN_APP;
                $count = $photoObj->getCountFromAppTable();
                unset($photoObj);
		return $count;
	}
		
	/* Added by Reshu Rajput for APP table */
	
	public function updateAppTable($paramArr=array())
	{
		$photoObj=new NEWJS_PICTURE_FOR_SCREEN_APP;
                $photoObj->update($paramArr);
                unset($photoObj);
	}

	/*This function is used to get Pictures for face detection algo
	@param totalScripts : Total number of scripts
	@param currentScript: Current script
	@param limit : limit
	@return result : Array of pictures and details
	*/
	public function getPicturesForFaceDetection($totalScripts,$currentScript,$limit)
        {
		$photoObj=new PICTURE_FOR_SCREEN_NEW;
		$screenBit = $this->screenBitCheck(ProfilePicturesTypeEnum::$SCREEN_BITS["FACE"]);
		$paramArr=Array(
				"CURRENTSCRIPT"=>$currentScript,
				"TOTALSCRIPT"=>$totalScripts,
				"LIMIT"=>$limit	
	
				);
		if(PictureFunctions::IfUsePhotoDistributed('X'))
			$paramArr['MainPicUrl'] ="LIKE '%".JsConstants::$photoServerName."%'";
		else
		{
			$words = preg_replace('/\d/', '', JsConstants::$photoServerName);
			$paramArr['MainPicUrl'] ="NOT LIKE '%".$words."%'";
		}
		$paramArr['SCREEN_BIT'] = explode("#",$screenBit);
		$paramArr['ORDERING'] = '0';
		$paramArr['OriginalPicUrl'] = 1;
		
		$result =$photoObj->get($paramArr);
		unset($photoObj);
		if(is_array($result))
		{
			foreach($result as $key=>$value)
			{
				$setServer = PictureFunctions::getCloudOrApplicationCompleteUrl($value['OriginalPicUrl'],true);
				if($setServer)
                        		$result[$key]['OriginalPicUrl']=$setServer; 

			}
		}
		return $result;
	}

	/** 
        * This function will return bits to check for particular process
        * @param task
        * @return position#bitValue 
        */
        public function screenBitCheck($task)
        { 
                if($task==ProfilePicturesTypeEnum::$SCREEN_BITS["FACE"]){
                        return (array_flip(array_merge(ProfilePicturesTypeEnum::$SCREEN_BIT_POSITION,  array_keys(ProfilePicturesTypeEnum::$PICTURE_SIZES)))["FACE"]+1)."#".ProfilePicturesTypeEnum::$SCREEN_BITS["DEFAULT"];
                }
                elseif($task=="NoOperation"){
                        return implode("",array_fill(0,count(array_merge(ProfilePicturesTypeEnum::$SCREEN_BIT_POSITION,  array_keys(ProfilePicturesTypeEnum::$PICTURE_SIZES))),ProfilePicturesTypeEnum::$SCREEN_BITS["DEFAULT"]));
                }
                
        }

	
	/*This function is used to get freshly uploaded pictures
	@param totalScripts : Total number of scripts
	@param currentScript: Current script
	@param limit : limit
	@return result : Array of pictures and details
	*/
	public function getFreshUploadePictures($totalScripts,$currentScript,$limit="")
	{
		
		$photoObj=new PICTURE_FOR_SCREEN_NEW;
		$paramArr=Array(
		"CURRENTSCRIPT"=>$currentScript,
		"TOTALSCRIPT"=>$totalScripts,
		"OriginalPicUrl"=>2,		
			"LIMIT"=>$limit	
		);
		if(PictureFunctions::IfUsePhotoDistributed('X'))
			$paramArr['MainPicUrl'] ="LIKE '%".JsConstants::$photoServerName."%'";
		else
		{
			$words = preg_replace('/\d/', '', JsConstants::$photoServerName);
			$paramArr['MainPicUrl'] ="NOT LIKE '%".$words."%'";
		}
		$result =$photoObj->get($paramArr);
		unset($photoObj);
		if(is_array($result))
		{
			foreach($result as $key=>$value)
			{
				$setServer = PictureFunctions::getCloudOrApplicationCompleteUrl($value['MainPicUrl'],true);
				if($setServer)
				{
                        		$result[$key]['MainPicUrl']=$setServer; 
                        		$result[$key]['IfUsePhotoDistributed']= PictureFunctions::getNameIfUsePhotoDistributed($value['MainPicUrl']);
				}

			}
		}
		return $result;
	}


	/*This function is used to get edit uploaded pictures
        @param totalScripts : Total number of scripts
        @param currentScript: Current script
        @param limit : limit
        @return result : Array of pictures and details
        */
        public function getEditPictures($totalScripts,$currentScript,$limit="")
        {
                $photoObj=new PICTURE_FOR_SCREEN_NEW;
                $paramArr=Array(
                "CURRENTSCRIPT"=>$currentScript,
                "TOTALSCRIPT"=>$totalScripts,
                "LIMIT"=>$limit,
                "EditPictures"=>"(ORDERING='0' AND SCREEN_BIT='0000000') OR (ORDERING != '0' AND SCREEN_BIT='0')"
                );
                $result =$photoObj->get($paramArr);
                unset($photoObj);
                return $result;
        }

	public function insertBulkForLegacyProfiles($profileArr)
        {
                $photoObj=new NEWJS_PICTURE_FOR_SCREEN_APP;
                $photoObj->insertBulkForLegacyProfiles($profileArr);
                unset($photoObj);
        }
        public function profilePictureStatusArr($profileId)
        {
                $photoObj=new PICTURE_FOR_SCREEN_NEW;
                return $photoObj->profilePictureStatusArr($profileId);
                unset($photoObj);
    
        }

    //wrapper function for getPICTUREIDIFNONSCREENED function of PICTURE_FOR_SCREEN_NEW
    public function getPICTUREIDIFNONSCREENED($profileId) 
    {
    	$photoObj = new PICTURE_FOR_SCREEN_NEW();
    	$output = $photoObj->getPICTUREIDIFNONSCREENED($profileId);
    	unset($photoObj);
    	return $output;
    }   
}
?>
