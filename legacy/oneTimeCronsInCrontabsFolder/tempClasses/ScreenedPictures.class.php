<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");


class ScreenedPicture extends Picture
{
	private $searchPicUrl;
        function __construct()
        {
                $this->pictureType='S';
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
        public function getSearchPicUrl()
        {
                return $this->searchPicUrl;
        }
        public function getPictureType()
        {
                return $this->pictureType;
        }
	public function getIsProfilePhotoVisible()
	{
		return $this->isProfilePhotoVisible;
	}
        public function getPhotoUrlForSpecCase($profileObj='',$nonLoggedInCase="")
        {
		$filtered=$profileObj->getGENDER();
                if($filtered=='M')
                {
			if($nonLoggedInCase)
			{
	                        if($nonLoggedInCase=='U')
        	                        return sfConfig::get('app_underscreeningmalephoto');
                	        else
                        	        return sfConfig::get('app_nonloggedinmalephoto');
			}
			else
				return sfConfig::get('app_filteredmalephoto');
                }
                elseif($filtered=='F')
                {
			if($nonLoggedInCase)
			{
                        	if($nonLoggedInCase=='U')
                                	return sfConfig::get('app_underscreeningfemalephoto');
	                        else
        	                        return sfConfig::get('app_nonloggedinfemalephoto');
			}
			else
				return sfConfig::get('app_filteredfemalephoto');
                }
        }

        public function setThumbailUrl($id,$profileObj='',$nonLoggedInCase='')
        {
                if($profileObj || $nonLoggedInCase)
		{
                        $this->thumbnailUrl=$this->getPhotoUrlForSpecCase($profileObj,$nonLoggedInCase);
		}
                else
                        $this->thumbnailUrl = $id;
        }
        public function setThumbail96Url($id,$profileObj='',$nonLoggedInCase='')
        {
                if($profileObj || $nonLoggedInCase)
                        $this->thumbnail96Url=$this->getPhotoUrlForSpecCase($profileObj,$nonLoggedInCase);
                else
                       	$this->thumbnail96Url = $id;
        }
        public function setProfilePicUrl($id,$profileObj='',$nonLoggedInCase='')
        {
                if($profileObj || $nonLoggedInCase)
			$this->profilePicUrl=$this->getPhotoUrlForSpecCase($profileObj,$nonLoggedInCase);	
		else
        	        $this->profilePicUrl = $id;
        }
        public function setMainPicUrl($id,$profileObj='',$nonLoggedInCase='')
        {
                if($profileObj || $nonLoggedInCase)
		{
			$this->isProfilePhotoVisible='N';
			$this->mainPicUrl=$this->getPhotoUrlForSpecCase($profileObj,$nonLoggedInCase);	
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
	//Getter Setter Methods	


        public function setDetail($keyValueArray)
        {
                foreach($keyValueArray as $k=>$v)
                {
			$setArrayAllowed=array("TITLE","KEYWORD","PICTUREID","ORDERING","UPDATED_TIMESTAMP","PROFILEID","MainPicUrl","ProfilePicUrl","ThumbailUrl","Thumbail96Url","PICFORMAT","SearchPicUrl");
			if(in_array($k,$setArrayAllowed))
			{
                           	eval('$this->set'.$k.'($v);');
			}
                }
        /*        $photoUrlId=$this->photoEncyption($keyValueArray["PICTUREID"],$keyValueArray["PROFILEID"]);
                $this->setProfilePicUrl($photoUrlId);
                $this->setMainPicUrl($photoUrlId);
                $this->setThumbailUrl($photoUrlId);
                $this->setThumbail96Url($photoUrlId);*/
        }

	public function getSaveUrl($picType,$picId,$profileId,$type="",$objectType='',$directory)
	{
		return parent::getSaveUrl($picType,$picId,$profileId,$type,'screened',$directory);
	}

        public function getDisplayPicUrl($picType,$picId,$profileId,$type="",$objectType='',$path)
        {
		return parent::getDisplayPicUrl($picType,$picId,$profileId,$type,'screened',$path);
        }
}
?>
