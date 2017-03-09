<?php

class ScreenedPicture extends Picture
{
	private $searchPicUrl;

     
        function __construct()
        {
                $this->pictureType='S';
        }

	//Getter Setter Methods	
        public function getUNSCREENED_TITLE()
        {
                return $this->UNSCREENED_TITLE;
        }

        public function setUNSCREENED_TITLE($x)
        {
                $this->UNSCREENED_TITLE = $x;
        }

        public function getProfilePicUrl($nofallback='')
        {
                return $this->profilePicUrl;
        }
        public function getThumbailUrl($nofallback='')
        {
                return $this->thumbnailUrl;
        }
        public function getThumbail96Url($nofallback='')
        {
                return $this->thumbnail96Url;
        }
        public function getMainPicUrl($nofallback='')
        {
                return $this->mainPicUrl;
        }
        public function getSearchPicUrl($nofallback='')
        {
                return $this->searchPicUrl;
        }
        public function getMobileAppPicUrl($nofallback='')
        {
						if($this->MobileAppPicUrl  || $nofallback==1)
                return $this->MobileAppPicUrl;
						else
								return $this->mainPicUrl;
        }
        public function getOriginalPicUrl($nofallback='')
        {
                return $this->OriginalPicUrl;
        }
        public function getPictureType()
        {
                return $this->pictureType;
        }
	public function getIsProfilePhotoVisible()
	{
		return $this->isProfilePhotoVisible;
	}
	public function getIsPhotoShown()
	{
		return $this->isPhotoShown;
	}
  public function getProfilePic120Url($nofallback='')
	{
		
		if($this->ProfilePic120Url  || $nofallback==1)
         return $this->ProfilePic120Url;
		else
				return $this->profilePicUrl;
	}
  public function getProfilePic235Url($nofallback='')
	{
		if($this->ProfilePic235Url  || $nofallback==1)
         return $this->ProfilePic235Url;
		else
				return $this->profilePicUrl;
		
	}
  public function getProfilePic450Url($nofallback='')
	{
		if($this->ProfilePic450Url || $nofallback==1)
         return $this->ProfilePic450Url;
		elseif($this->MobileAppPicUrl)
        return $this->MobileAppPicUrl;
		else
			  return $this->profilePicUrl;
  }
	public function setIsPhotoShown($isProfilePhotoShown)
	{
		$this->isPhotoShown = $isProfilePhotoShown;
	}
        
	/**
	This function is used to return photo url according to special coditions like filtered , profile pic under screening.
	*/
	 /* Changed by Reshu for common configuration, use of StaticPhotoUrls */

        public function getPhotoUrlForSpecCase($profileObj='',$nonLoggedInCase="",$mobileView="",$picType="")
        {
		global  $jeevansathiAppDotYml;
		$filtered=$profileObj->getGENDER();
		if($mobileView=="mobile")
			$mobile="Mobile";
		else
			$mobile="";
		if($filtered=='M')
                        $gender="Male";
                elseif($filtered=='F')
                        $gender="Female";
                if($picType=="" || !in_array($picType,ProfilePicturesTypeEnum::$PICTURE_SIZES_STOCK))
			$picType="ProfilePicUrl";
                if($gender)
                {
			
                        if($nonLoggedInCase)
                        {
                                if($nonLoggedInCase=='U')
                                        $photoUrl=sfConfig::get("app_img_url").constant('StaticPhotoUrls::underScreeningPhoto'.$gender.$mobile.$picType);
                                else
                                        $photoUrl=sfConfig::get("app_img_url").constant('StaticPhotoUrls::nonLoggedInPhoto'.$gender.$mobile.$picType);
                        }
                        else
                        {
                                if($jeevansathiAppDotYml)
                                        $photoUrl=sfConfig::get("app_img_url").constant('StaticPhotoUrls::filteredPhoto'.$gender.$mobile.$picType);
                                else
                                        $photoUrl=sfConfig::get("app_img_url").constant('StaticPhotoUrls::contactAcceptedPhoto'.$gender.$mobile.$picType);
                        } 
			
                        return $photoUrl;
                }

        }

        public function setThumbailUrl($id,$profileObj='',$nonLoggedInCase='',$mobileView="")
        {
                if($profileObj || $nonLoggedInCase)
		{
                        $this->thumbnailUrl=$this->getPhotoUrlForSpecCase($profileObj,$nonLoggedInCase,$mobileView,"ThumbnailUrl");
		}
                else
                        $this->thumbnailUrl = $id;
        }
        public function setThumbail96Url($id,$profileObj='',$nonLoggedInCase='',$mobileView="")
        {
                if($profileObj || $nonLoggedInCase)
                        $this->thumbnail96Url=$this->getPhotoUrlForSpecCase($profileObj,$nonLoggedInCase,$mobileView);
                else
                       	$this->thumbnail96Url = $id;
        }
        public function setProfilePicUrl($id,$profileObj='',$nonLoggedInCase='',$mobileView="")
        {
                if($profileObj || $nonLoggedInCase)
			$this->profilePicUrl=$this->getPhotoUrlForSpecCase($profileObj,$nonLoggedInCase,$mobileView);	
		else
        	        $this->profilePicUrl = $id;
        }
        public function setMainPicUrl($id,$profileObj='',$nonLoggedInCase='',$mobileView="")
        {
                if($profileObj || $nonLoggedInCase)
		{
			$this->isProfilePhotoVisible='N';
			$this->mainPicUrl=$this->getPhotoUrlForSpecCase($profileObj,$nonLoggedInCase,$mobileView);	
		}
		else
		{
			$this->isProfilePhotoVisible='Y';
	          //      $this->mainPicUrl=sfConfig::get('app_photo_url')."/uploads/ScreenedImages/mainPic/".$id.".jpg";
			$this->mainPicUrl = $id;
		}
        }
        public function setSearchPicUrl($id,$profileObj='',$nonLoggedInCase='')
        {
                if($profileObj || $nonLoggedInCase)
			$this->searchPicUrl=$this->getPhotoUrlForSpecCase($profileObj,$nonLoggedInCase);	
		else
        	        $this->searchPicUrl = $id;
        }
	public function setMobileAppPicUrl($id,$profileObj='',$nonLoggedInCase='')
        {
                if($profileObj || $nonLoggedInCase)
                        $this->MobileAppPicUrl=$this->getPhotoUrlForSpecCase($profileObj,$nonLoggedInCase,'',"MobileAppPicUrl");
                else
                        $this->MobileAppPicUrl = $id;
        }
        public function setProfilePic120Url($id,$profileObj='',$nonLoggedInCase='')
	{
		if($profileObj || $nonLoggedInCase)
                        $this->ProfilePic120Url=$this->getPhotoUrlForSpecCase($profileObj,$nonLoggedInCase,'',"ProfilePic120Url");
                else
                        $this->ProfilePic120Url = $id;
	}
         public function setProfilePic235Url($id,$profileObj='',$nonLoggedInCase='')
	{
		if($profileObj || $nonLoggedInCase)
                        $this->ProfilePic235Url=$this->getPhotoUrlForSpecCase($profileObj,$nonLoggedInCase,'',"ProfilePic235Url");
                else
                        $this->ProfilePic235Url = $id;
	}
         public function setProfilePic450Url($id,$profileObj='',$nonLoggedInCase='')
	{
		if($profileObj || $nonLoggedInCase)
                        $this->ProfilePic450Url=$this->getPhotoUrlForSpecCase($profileObj,$nonLoggedInCase,'',"ProfilePic450Url");
                else
                        $this->ProfilePic450Url= $id;
	}
        public function setOriginalPicUrl($id,$profileObj='',$nonLoggedInCase='')
	{
		if($profileObj || $nonLoggedInCase)
                        $this->OriginalPicUrl=$this->getPhotoUrlForSpecCase($profileObj,$nonLoggedInCase);
                else
                        $this->OriginalPicUrl = $id;
	}
	//Getter Setter Methods	


        public function setDetail($keyValueArray,$showUnScreenedTitle="")
        {
		foreach($keyValueArray as $k=>$v)
                {
			$setArrayAllowed=array_merge(array("UNSCREENED_TITLE","TITLE","KEYWORD","PICTUREID","ORDERING","UPDATED_TIMESTAMP","PROFILEID","PICFORMAT"), ProfilePicturesTypeEnum::$PICTURE_SIZES_FIELDS);
			if(in_array($k,$setArrayAllowed))
			{
				if($k=="TITLE" && $showUnScreenedTitle && $keyValueArray["UNSCREENED_TITLE"])
					$v=$keyValueArray["UNSCREENED_TITLE"];
                           	eval('$this->set'.$k.'($v);');
			}
		}
        }
	/**Added by Reshu 
        This function is used to set all the urls complete of the picture provided.
        */

        public function setCompletePictureUrl()
        {
		$completeUrlArrayAllowed= ProfilePicturesTypeEnum::$PICTURE_SIZES_FIELDS;
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
		return parent::getSaveUrlPicture($picType,$picId,$profileId,$type,'screened',$migrate);
	}

        public function getDisplayPicUrl($picType,$picId,$profileId,$type="")
        {
		return parent::getDisplayPicUrlPicture($picType,$picId,$profileId,$type,'screened');
        }
	
	// wrapper functions created by Reshu for PICTURE_NEW store 
	
	/* Wrapper for PICTURE_NEW->ins()	
	 * @param paramArr array contains key value pair for insertion
        * @returns true on success
	*/
	public function ins($paramArr=array())
	{
		$photoObj=new PICTURE_NEW;
                $status=$photoObj->ins($paramArr);
		if(array_key_exist("PROFILEID",$paramArr))
			   PictureNewCacheLib::getInstance()->removeCache($paramArr['PROFILEID']);
		return $status;
	}
	/**
          Wrapper for PICTURE_NEW->get()
        * @param  paramArr array contains where condition on basis of which entry will be fetched.
        * @return detailArr array picture(s) info. Return null in case of no matching rows found.
        **/
        public function get($paramArr=array(),$getFromMasterR='')
        {
		if (PictureNewCacheLib::getInstance()->isCacheable($paramArr, __CLASS__)) 
		{
			if(PictureNewCacheLib::getInstance()->isCached($paramArr,__CLASS__))
			{
				$result = PictureNewCacheLib::getInstance()->get($paramArr, __CLASS__);
				if (false !== $result) 
				{
					$result = FormatResponse::getInstance()->generate(FormatResponseEnums::REDIS_TO_MYSQL, $result);
//					$this->logCacheConsumption();
				}
			}
			else
			{
				$photoObj=new PICTURE_NEW;
				$result=$photoObj->get(array("PROFILEID"=>$paramArr['PROFILEID']));
				foreach($result  as $k=>$v)
					$encodedData[$v['ORDERING']] = json_encode($v);
				PictureNewCacheLib::getInstance()->cacheThis($paramArr['PROFILEID'],$encodedData);
			}
			$result = PictureNewCacheLib::getInstance()->processWhere($result,$paramArr);
		}
		else
		{
			if($getFromMasterR=='1')
				 $photoObj=new PICTURE_NEW("newjs_masterRep");
			else
				$photoObj=new PICTURE_NEW;
			$result=$photoObj->get($paramArr);
		}
		return $result;
	}
	 /**
         Wrapper for PICTURE_NEW->edit()
        * @param paramArr array contains where condition on basis of which entry will be fetched.
        * @param pictureId int update is only only by pictureId.
        * @returns true on sucess
        **/
        public function edit($paramArr=array(),$pictureId,$profileId)
        {
		$photoObj=new PICTURE_NEW;
                $status=$photoObj->edit($paramArr,$pictureId,$profileId);
		PictureNewCacheLib::getInstance()->removeCache($profileid);
                return $status;
	}
	
	public function del($paramArr=array())
        {
    		$photoObj=new PICTURE_NEW;
                $count=$photoObj->del($paramArr);
		if(array_key_exist("PROFILEID",$paramArr))
			   PictureNewCacheLib::getInstance()->removeCache($paramArr['PROFILEID']);
                return $count;

        }
	
	public function updateOrdering($paramArr=array())
        {
		$photoObj=new PICTURE_NEW;
                $photoObj->updateOrdering($paramArr);
		if(array_key_exist("PROFILEID",$paramArr))
			   PictureNewCacheLib::getInstance()->removeCache($paramArr['PROFILEID']);
	}
	
	public function getMaxOrdering($profileId)
        {
		$photoObj=new PICTURE_NEW;
                $max=$photoObj->getMaxOrdering($profileId);
                return $max;
        }  
	
	public function pictureidExist($picId,$pid)
        {
		$photoObj=new PICTURE_NEW;
                $count=$photoObj->pictureidExist($picId,$pid);
                return $count;
	}
	
	public function deleteRowsBasedOnPicId($picIdArr)
        {
		$photoObj=new PICTURE_NEW;
                $photoObj->deleteRowsBasedOnPicId($picIdArr);
	}
	
	public function updateScreenedPhotosOrdering($profileid)
        {
		$photoObj=new PICTURE_NEW;
                $photoObj->updateScreenedPhotosOrdering($profileid);
		if(array_key_exist("PROFILEID",$paramArr))
			   PictureNewCacheLib::getInstance()->removeCache($paramArr['PROFILEID']);
	}

	public function insertBulkScreen($profileId,$picId,$title,$keywords,$ins_ordering,$MainPicUrl,$ProfilePicUrl,$ThumbailUrl,$Thumbail96Url,$MobileAppPicUrl='',$ProfilePic120Url='',$ProfilePic235Url='',$ProfilePic450Url='',$OriginalPicUrl='',$SearchPicUrl,$PicFormat)
        {
		$photoObj=new PICTURE_NEW;
                $status=$photoObj->insertBulkScreen($profileId,$picId,$title,$keywords,$ins_ordering,$MainPicUrl,$ProfilePicUrl,$ThumbailUrl,$Thumbail96Url,$MobileAppPicUrl,$ProfilePic120Url,$ProfilePic235Url,$ProfilePic450Url,$OriginalPicUrl,$SearchPicUrl,$PicFormat);
                return $status;
	}

	public function updateScreenTitles($picId,$screenTitleArr)
        {
		$photoObj=new PICTURE_NEW;
                $status=$photoObj->updateScreenTitles($picId,$screenTitleArr);
                return $status;
	}
	
	public function hasScreenedMainPhoto($profileId)
        {
		$photoObj=new PICTURE_NEW;
                $status=$photoObj->hasScreenedMainPhoto($profileId);
                return $status;
	}

	public function hasUnscreenedTitle($profileId)
        {
		$photoObj=new PICTURE_NEW;
                $status=$photoObj->hasUnscreenedTitle($profileId);
                return $status;
	}
	
	public function getMultipleUserProfilePics($whereCondition)
        {
		if(array_key_exists("PROFILEID",$whereCondition))
		{
			$paramArr = $whereCondition;
			if(is_array($whereCondition['PROFILEID']))
			{
				unset($paramArr['PROFILEID']);
				$paramArr['PROFILEID']=current($whereCondition['PROFILEID']);
			
				if ( PictureNewCacheLib::getInstance()->isCacheable($paramArr, __CLASS__))
				{
					foreach($whereCondition['PROFILEID'] as $k=>$pid)
					{
						$paramArr['PROFILEID']= $pid;
						if(PictureNewCacheLib::getInstance()->isCached($paramArr,__CLASS__))
						{
							$result = PictureNewCacheLib::getInstance()->get($paramArr, __CLASS__);
							if (false !== $result)
							{
								$result = FormatResponse::getInstance()->generate(FormatResponseEnums::REDIS_TO_MYSQL, $result);
								$result = PictureNewCacheLib::getInstance()->processWhere($result,$paramArr);
							}							
						}
						else
						{
							$queryProfiles[]=$pid;
/*
							$photoObj=new PICTURE_NEW;
							$result=$photoObj->get(array("PROFILEID"=>$pid));
							foreach($result  as $k=>$v)
								$encodedData[$v['ORDERING']] = json_encode($v);

							PictureNewCacheLib::getInstance()->cacheThis($paramArr['PROFILEID'],$encodedData);
*/
						}
						if(is_array($result))
							$final[$pid]=$result[0];
						unset($result);
					}
					if(is_array($queryProfiles))
					{
						$photoObj=new PICTURE_NEW();
						$data  = $photoObj->getMultiProfilesData($queryProfiles);
					}
					return $final;
				}
				else
				{
					$photoObj=new PICTURE_NEW("newjs_masterRep");
					$profilePicsArr=$photoObj->getMultipleUserProfilePics($whereCondition);
					return $profilePicsArr;
				}
			}
			else
			{
				return $this->get($whereCondition);
			}
		}
	}
	public function getMultipleUserPicsCount($whereCondition)
        {
                $photoObj=new PICTURE_NEW("newjs_masterRep");
                $profilePicsCountArr=$photoObj->getMultipleUserPicsCount($whereCondition);
                return $profilePicsCountArr;
        }

	public function startTransaction()
        {
               $photoObj=new PICTURE_NEW;
               $photoObj->startTransaction();

        }
        public function commitTransaction()
        {
                $photoObj=new PICTURE_NEW;
                $photoObj->commitTransaction();

        }

        public function rollbackTransaction()
        {
                $photoObj=new PICTURE_NEW;
                $photoObj->rollbackTransaction();

        }

	/* Functions added for inserting in Image Server Log table*/
	/*This function is to insert into Image server Log table when a picture with different types is send
	*@ param : paramArray Info of a picture
	*@return : result as true or false 
	*/	
	public function insImageServerLog($paramArr=array())
	{
                $imageServer=new ImageServerLog;
		$picArray=array();
		$picArray[0]=$paramArr["PICTUREID"]; 
		$result=$this->insertBulkImageServerLog($picArray,$paramArr["MainPicUrl"],$paramArr["ProfilePicUrl"],$paramArr["ThumbailUrl"],$paramArr["Thumbail96Url"],$paramArr["SearchPicUrl"],$paramArr["MobileAppPicUrl"],$paramArr["ProfilePic120Url"],$paramArr["ProfilePic235Url"],$paramArr["ProfilePic450Url"],$paramArr["OriginalPicUrl"]);
		return $result;
	}	
	

	/*
        This function will insert bulk picture information in LOG, it will take array of all the possible image types we need to enter in the LOG and will generate new arrays for module name( PICTURE here), image type, moduleId and status(N as default). This is done as we need different enteries for different types.
        *@param : picId  It will contain array of pic Ids to be mapped with moduleId array
	*@param : MainPicUrl Array for image type MainPicUrl
	*@param : ProfilePicUrl Array for image type ProfilePicUrl
	*@param : ThumbailUrl Array for image type ThumbailUrl
	*@param : Thumbail96Url Array for image type Thumbail96Url
	*@param : SearchPicUrl Array for image type SearchPicUrl
        */

        public function insertBulkImageServerLog($picId,$MainPicUrl,$ProfilePicUrl,$ThumbailUrl,$Thumbail96Url,$SearchPicUrl,$MobileAppPicUrl='',$ProfilePic120Url='',$ProfilePic235Url='',$ProfilePic450Url='',$OriginalPicUrl='')
        {
		$moduleName= array();
		$moduleId=array();
		$imageType=array();
		$status=array();
		for ($i=0,$j=0;$i<count($picId);$i++)
                { 
			if(!empty($MainPicUrl[$i]))
			{
				$moduleName[$j]="PICTURE" ;
                		$moduleId[$j]=$picId[$i];
                		$imageType[$j]="MainPicUrl";
                		$status[$j]="N";
				$j++;
			}
			if(!empty($ProfilePicUrl[$i]))
                        {
                                $moduleName[$j]="PICTURE" ;
                                $moduleId[$j]=$picId[$i];
                                $imageType[$j]="ProfilePicUrl";
                                $status[$j]="N";
                                $j++;
                        }
			if(!empty($ThumbailUrl[$i]))
                        {
                                $moduleName[$j]="PICTURE" ;
                                $moduleId[$j]=$picId[$i];
                                $imageType[$j]="ThumbailUrl";
                                $status[$j]="N";
                                $j++;
                        }
			if(!empty($Thumbail96Url[$i]))
                        {
                                $moduleName[$j]="PICTURE" ;
                                $moduleId[$j]=$picId[$i];
                                $imageType[$j]="Thumbail96Url";
                                $status[$j]="N";
                                $j++;
                        }
                        if(!empty($SearchPicUrl[$i]))
                        {
                                $moduleName[$j]="PICTURE" ;
                                $moduleId[$j]=$picId[$i];
                                $imageType[$j]="SearchPicUrl";
                                $status[$j]="N";
                                $j++;
                        }
			if(!empty($MobileAppPicUrl[$i]))
                        {
                                $moduleName[$j]="PICTURE" ;
                                $moduleId[$j]=$picId[$i];
                                $imageType[$j]="MobileAppPicUrl";
                                $status[$j]="N";
                                $j++;
                        }
                        if(!empty($ProfilePic120Url[$i]))
                        {
                                $moduleName[$j]="PICTURE" ;
                                $moduleId[$j]=$picId[$i];
                                $imageType[$j]="ProfilePic120Url";
                                $status[$j]="N";
                                $j++;
                        }
                        if(!empty($ProfilePic235Url[$i]))
                        {
                                $moduleName[$j]="PICTURE" ;
                                $moduleId[$j]=$picId[$i];
                                $imageType[$j]="ProfilePic235Url";
                                $status[$j]="N";
                                $j++;
                        }
                        if(!empty($ProfilePic450Url[$i]))
                        {
                                $moduleName[$j]="PICTURE" ;
                                $moduleId[$j]=$picId[$i];
                                $imageType[$j]="ProfilePic450Url";
                                $status[$j]="N";
                                $j++;
                        }
                        if(!empty($OriginalPicUrl[$i]))
                        {
                                $moduleName[$j]="PICTURE" ;
                                $moduleId[$j]=$picId[$i];
                                $imageType[$j]="OriginalPicUrl";
                                $status[$j]="N";
                                $j++;
                        }

                }
		$imageServer=new ImageServerLog;
                $result=$imageServer->insertBulk($moduleName,$moduleId,$imageType,$status);
		return $result;
        }
}
?>
