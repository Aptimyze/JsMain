<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

class NonScreenedPicture extends Picture
{
	private $ONLY_TITLE_SCREENED;
	function __construct()
	{
		$this->pictureType='N';
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
	public function getPictureType()
	{
		return $this->pictureType;
	}
	public function getONLY_TITLE_SCREENED()
	{
		return $this->ONLY_TITLE_SCREENED;
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
	public function setONLY_TITLE_SCREENED($ONLY_TITLE_SCREENED)
	{
		$this->ONLY_TITLE_SCREENED=$ONLY_TITLE_SCREENED;
	}
	//Getter Setter Methods
	
        public function setDetail($keyValueArray)
        {
                foreach($keyValueArray as $k=>$v)
                {
			$setArrayAllowed=array("TITLE","KEYWORD","ONLY_TITLE_SCREENED","PICTUREID","ORDERING","UPDATED_TIMESTAMP","PROFILEID","MainPicUrl","ProfilePicUrl","ThumbailUrl","Thumbail96Url","PICFORMAT");
                       	if(in_array($k,$setArrayAllowed))
			{
				eval('$this->set'.$k.'($v);');
			}
                }
		/*
                $photoUrlId=$this->photoEncyption($keyValueArray["PICTUREID"],$keyValueArray["PROFILEID"]);
                $this->setProfilePicUrl($photoUrlId);
                $this->setMainPicUrl($photoUrlId);
                $this->setThumbailUrl($photoUrlId);
                $this->setThumbail96Url($photoUrlId);*/
        }

	public function getSaveUrl($picType,$picId,$profileId,$type="",$objectType='',$directory)
        {
		return parent::getSaveUrl($picType,$picId,$profileId,$type,'nonScreened',$directory);
        }

	public function getDisplayPicUrl($picType,$picId,$profileId,$type="",$objectType='',$path)
	{
		return parent::getDisplayPicUrl($picType,$picId,$profileId,$type,'nonScreened',$path);
	}
}
?>
