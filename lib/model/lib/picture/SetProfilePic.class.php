<?php
 /*
 * @package jeevansathi
 * @subpackage setProfilePic
 * @author Esha Jain
 * @created 01st March 2016
 */
 /**
 * Class For setting profile pic of the user
 */

class SetProfilePic extends PictureService
{
 /*
 * @package jeevansathi
 * @subpackage setProfilePic
 * @author Esha Jain
 * @created 01st March 2016
 */
 /**
 * Class For setting profile pic of the user
 */
        /**
         * 
         * The variable holds the pictureid to be deleted
         * @access private
         * @vartype int
         */
        private $pictureid;

        /**
         *
         * The variable holds the value of profileid of the pic
         */
        private $profileid;
        /**
        * Class For setting profile photo 
        */
        /**
         * 
         * Constructor for initializing object of setting profile photo
         * @param $pictureid: pictureid of photo to be set at profile pic
         * @param $profileid: profileid of photo
         */

        public function __construct($newPicObj,$profileid,$profileObj,$source)
        {
                $this->newPicObj = $newPicObj;
                $this->profileid = $profileid;
		parent::__construct($profileObj,$source);
        }

        /**
         *
         * this is the control function for the complete steps of setting profile photo
         */
        public function setProfilePicControl()
        {
		$PICTURE_FOR_SCREEN_NEW = new NonScreenedPicture($source);

		$currentProfilePicObj = $this->getNonScreenedProfilePicObj($PICTURE_FOR_SCREEN_NEW);

		if(!$this->newPicObj)
			return;
		if($this->checkSamePic($currentProfilePicObj))
			return true;

		$currentProfilePicScreenedStatus = $this->getCurrentProfilePicScreenStatus($currentProfilePicObj->getPICTUREID(),$PICTURE_FOR_SCREEN_NEW);

		$newPicScreenedStatus = $this->getNewPicScreenStatus();

		$case = $currentProfilePicScreenedStatus.$newPicScreenedStatus;
    
		switch($case)
		{
			case 00:
				$this->nonScreenedToNonScreened($currentProfilePicObj,$PICTURE_FOR_SCREEN_NEW);
				break;

			case 01:

				$this->nonScreenedToScreened($currentProfilePicObj,$PICTURE_FOR_SCREEN_NEW);
				break;

			case 10:
				$this->screenedToNonScreened($PICTURE_FOR_SCREEN_NEW);
				break;

			case 11:

				$this->screenedToScreened($PICTURE_FOR_SCREEN_NEW);
				break;

		}
		return array(0=>true,1=>$case);
        }
        /*
         * this function is used to mark profile pic if current profile pic is Non screened and new Pic is non screened
         *
         * NonScreened To NonScreened
         *
         * swapping gordering of the two pics in PICTURE_FOR_SCREEN_NEW table
         *
         */
        public function nonScreenedToNonScreened($currentProfilePicObj,$PICTURE_FOR_SCREEN_NEW)
        {
          if($this->getScreenedPhotos('exists',$currentProfilePicObj->getPICTUREID())=='0'){
            $freeOrder = $this->getNewPicfreedOrdering();
            $this->updateCurrentPicObj($currentProfilePicObj,$freeOrder); 
          }else{
            // if non screend picture id exists in screened table
            $whereCondition["PICTUREID"]=$currentProfilePicObj->getPICTUREID();
            $whereCondition["PROFILEID"]=$currentProfilePicObj->getPROFILEID();
            $this->deleteNonScreenedPicture($whereCondition,$PICTURE_FOR_SCREEN_NEW);
          }
          $this->setNewPicOrderingZero();
        }
        /*
         * this function delete non screened picture entry
         * @param $whereCondition array where condition array for deletion
         */
        public function deleteNonScreenedPicture($whereCondition,$PICTURE_FOR_SCREEN_NEW){
          $PICTURE_FOR_SCREEN_NEW->del($whereCondition);
        }
	/*
	 * this function is used to mark profile pic if current profile pic is Non screened and new Pic is screened
	 *
	 * NonScreened To Screened
	 *
	 * if a screened picture is made profile pic its entry is made in non screened pic because it has to be screened to make changes permenant
	 *
	 * so first we will have increse ordering of all non screened pic by one to make space for screened profile pic entry with orderign zero
	 *
	 */
	public function nonScreenedToScreened($currentProfilePicObj,$PICTURE_FOR_SCREEN_NEW)
	{
    if($this->getScreenedPhotos('exists',$currentProfilePicObj->getPICTUREID())=='0'){
      $whereCondition["PROFILEID"]=$this->profileid;
      $whereCondition["INCREASE_ORDERING"]=1;
      $this->setNonScreenedPicsOrdering($whereCondition,$PICTURE_FOR_SCREEN_NEW);
    }
		$updateArray = $this->getNonScreenedObjectArray();
		$this->setNonScreenedEntry($updateArray,$PICTURE_FOR_SCREEN_NEW);
    $this->flushPicMemcache();
	}
        /*
         * this function is used to mark profile pic if current profile pic is screened and new Pic is non screened
         *
         * Screened To NonScreened
         *
         * if a non screened picture is made profile pic its entry is made in non screened pic
         *
         */
	public function screenedToNonScreened($PICTURE_FOR_SCREEN_NEW)
	{
		$picCurrentOrdering=$this->newPicObj->getORDERING();
		$this->newPicObj->setORDERING(0);
		$this->addPhotos($this->newPicObj);
		unset($whereCondition);
		$whereCondition["PROFILEID"]=$this->profileid;
		$whereCondition["ORDERING"]=$picCurrentOrdering;
		$this->setNonScreenedPicsOrdering($whereCondition,$PICTURE_FOR_SCREEN_NEW);
	}
        /*
         * this function is used to mark profile pic if current profile pic is screened and new Pic is screened
         *
         * Screened To Screened
         *
         * an entry of the same is made in non screened pic for screening
         *
         */
	public function screenedToScreened($PICTURE_FOR_SCREEN_NEW) 
	{
		$updateArray = $this->getNonScreenedObjectArray();
		$this->setNonScreenedEntry($updateArray,$PICTURE_FOR_SCREEN_NEW);
	}

	public function setNonScreenedEntry($updateArray,$PICTURE_FOR_SCREEN_NEW)
	{
		$PICTURE_FOR_SCREEN_NEW->setDetail($updateArray);
		$PICTURE_FOR_SCREEN_NEW->setORDERING(0);
		$this->addPhotos($PICTURE_FOR_SCREEN_NEW);
	}
	public function getNonScreenedObjectArray()
	{
		foreach(ProfilePicturesTypeEnum::$PICTURE_SCREENED_SIZES_FIELDS as $k=>$v)
		{
			if($k=="TITLE" && $this->newPicObj->getUNSCREENED_TITLE()){
				eval('$updateArray['.'"'.$v.'"'.']=$this->newPicObj->getUNSCREENED_TITLE();');
      }else
      {
				eval('$updateArray['.'"'.$v.'"'.']=$this->newPicObj->get'.$v.'(1);');
				$updateArray[$v] = PictureFunctions::getPictureServerUrl($updateArray[$v]);
			}
		}
		return $updateArray;
	}
	/**
	* This function update the ordering for Non screened Picture
	* @param type array $whereCondition pictureid and ordering or increase ordering 
	*/
	public function setNonScreenedPicsOrdering($whereCondition,$PICTURE_FOR_SCREEN_NEW)
	{
		$PICTURE_FOR_SCREEN_NEW->updateOrdering($whereCondition);
		unset($whereCondition);
	}
	/**
	 * @param $freeOrder the ordering to be set for the current profile pic
	 * this function updates the ordering of current pic in PICTURE_FOR_SCREEN_NEW and set it to the input parameter provided	  */
	public function updateCurrentPicObj($currentProfilePicObj,$freeOrder)
	{
		$currentProfilePicObj->setORDERING($freeOrder);
		$currentProfilePicObj->setProfilePicUrl(null);
		$currentProfilePicObj->setThumbailUrl(null);
		$this->editAlbumInfo($currentProfilePicObj);
	}
	/*
	 *
	 *this function set the ordering of new oic to -1 and returns the current ordering of new pic
	 */
	public function getNewPicfreedOrdering()
	{
		$freeOrder=$this->newPicObj->getORDERING();
		$this->newPicObj->setORDERING(-1);
		$this->editAlbumInfo($this->newPicObj);
		return $freeOrder;
	}
	/*
	 *
	 * this function sets the ordering of new pic to zero in PICTURE_FOR_SCREEN_NEW
	 */
	public function setNewPicOrderingZero()
	{
		$this->newPicObj->setORDERING(0);
		$this->editAlbumInfo($this->newPicObj);
	}
	/*
	 *
	 * this function returns whether the current pic is screened or not
	 */
        public function getCurrentProfilePicScreenStatus($currentPictureid,$PICTURE_FOR_SCREEN_NEW)
        {
                $pictureScreened = $this->checkPictureExistInNonScreen($currentPictureid,$this->profileid,$PICTURE_FOR_SCREEN_NEW);

		if($pictureScreened == 0)
			return PictureStaticVariablesEnum::nonScreened;
		else
			return PictureStaticVariablesEnum::Screened;
        }
	/*
	 *
	 * this function checks wether the picture id entered is in PICTURE_NEW of the profileid
	 */
	public function checkPictureExistInNonScreen($pictureid,$profileid,$PICTURE_FOR_SCREEN_NEW)
	{
		$whereCondition["PROFILEID"]=$profileid;
		$whereCondition["ORDERING"]=0;
		$pic=$PICTURE_FOR_SCREEN_NEW->get($whereCondition);

		if(is_array($pic) && $pic[0]!='')
		{
			return 0;
		}
		return 1;
	}
	/*
	 *
	 * this function gets the non screened object of current profile pic
	 */
	public function getNonScreenedProfilePicObj($PICTURE_FOR_SCREEN_NEW)
	{
		$whereCondition = array("ORDERING"=>0,"PROFILEID"=>$this->profileid);
		$pic=$PICTURE_FOR_SCREEN_NEW->get($whereCondition);
		$PICTURE_FOR_SCREEN_NEW->setDetail($pic[0]);
		return $PICTURE_FOR_SCREEN_NEW;
	}
	/*
	 *
	 * this function is used to check the screening status of the new pic to be set as profile pic
	 */
	public function getNewPicScreenStatus()
	{
    
		if($this->newPicObj->getPictureType()==PictureStaticVariablesEnum::nonScreenedPhotos)
			return PictureStaticVariablesEnum::nonScreened;
		else
			return PictureStaticVariablesEnum::Screened;
	}
	/*
	 *
	 * this function is used to check if the new pic and the current profile pic are same or not
	 */
	public function checkSamePic($currentProfilePicObj)
	{
    if($currentProfilePicObj && ($currentProfilePicObj->getPICTUREID()==$this->newPicObj->getPICTUREID()))
			return true;
		return false;
	}
}
