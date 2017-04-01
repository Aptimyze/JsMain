<?php
/*
* @package jeevansathi
* @subpackage deletePhoto
* @author Esha Jain
* @created 01st March 2016
*/
/**
* Class For deleting photo 
*/

class deletePhoto extends PictureService
{
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
	 *
	 * This is array having profileid and pictureid to be passed to various objects for updation
	 *@access private
	 *@vartype array
	 */
	private $whereCondition;
	/**
	* Class For deleting photo 
	*/
        /**
         * 
         * Constructor for initializing object of delete photo
         * @param $pictureid: pictureid of photo to be deleted
         * @param $profileid: profileid of photo
	 * @param whereCondition array having profileid and picture id set
         */
        public function __construct($pictureid,$profileid,$profileObj,$userType,$source)
	{
		$this->pictureid = $pictureid;
		$this->profileid = $profileid;
		parent::__construct($profileObj,$source);
		$this->whereCondition = $this->getWhereCondition();
	}

	/**
	 *
	 * The function is used to generate array with profileid and pictureid for updations
	 */
	public function getWhereCondition()
	{
		return $whereCondition = array("PICTUREID"=>$this->pictureid,"PROFILEID"=>$this->profileid);
	}
	/**
	 *
	 *The function takes whereCondition with pictureid and profileid as input and returns pic object
	 */
	public function getPictureObject()
	{
		$pic = $this->getPicDetails($this->whereCondition);
		return $pic[0];
	}
	/**
	 *
	 * The function is used to check whether the pic can be delete or not
	 * @param $picObj object of picture to be delete
	 * @param $photoCount number of pics present for the profile
	 * @param $profilePicObj object of profilePic of the profile for which the picture is deleted
	 * @returns array with 3 flags: 1)CAN_DELETE : states whether pic can be deleted or not
					2)PROFILE_PIC_DELETE : non screened profile pic getting deleted
					3)SCREENED_PROFILE_PIC_DELETE : screened profile pic getting deleted 
	 */
	public function canDelete($picObj,$photoCount,$profilePicObj)
	{
		$canDelete = $profilePicDelete = $screenedProfilePicDelete = false;
		if($photoCount!=0)
		{
			//checking if profile photo is getting deleted, non profile photo can be deleted without any conditions
			if($picObj->getORDERING()==PictureStaticVariablesEnum::profilePicOrdering)
			{
				//if only one photo is present that is profile pic, it can be deleted
				if($photoCount == 1)
				{
					$canDelete = true;
					$profilePicDelete = true;
				}
				/**else if 1) there are more than one photos AND
					2) the pic is a screened pic AND
					3) its not profile pic AND
					4) a profile pic is present 
				then it can be deleted
				**/
				elseif($picObj->getPictureType()==PictureStaticVariablesEnum::screenedPhotos)
				{
					if(!$profilePicObj)
						throw new jsException("","profilePicObj is blank in deletePhoto.class.php");
					if($profilePicObj->getPictureId()!=$this->pictureid && $profilePicObj->getPictureId())
					{
						$canDelete= true;
						$screenedProfilePicDelete= true;
					}
				}
				elseif($userType=="newMobile")
				{
					$canDelete=true;
				}
			}
			else
				$canDelete = true;
		}
		$canDeleteArr = array("CAN_DELETE"=>$canDelete,"PROFILE_PIC_DELETE"=>$profilePicDelete,"SCREENED_PROFILE_PIC_DELETE"=>$screenedProfilePicDelete);
		return $canDeleteArr;
	}

	/**
	 *
	 * This function returns photo objects on the basis of picture type of the pic to be deleted
	 */
	public function getPhotoObjects($picObj)
	{
		if($picObj->getPictureType()==PictureStaticVariablesEnum::nonScreenedPhotos)
		{
			$photoObj=new NonScreenedPicture;
			$otherPhotoObj=new ScreenedPicture;
		}
		if($picObj->getPictureType()==PictureStaticVariablesEnum::screenedPhotos)
		{
			$photoObj=new ScreenedPicture;
			$otherPhotoObj=new NonScreenedPicture;
		}
		return array("photoObj"=>$photoObj,"otherPhotoObj"=>$otherPhotoObj);
	}

	public function trackDeletePictureDetails($photoObj)
	{
		$p = $photoObj->get($this->whereCondition);
		if(is_array($p))
		{
			$trackDeletedPhoto = new DeletedPictures();
			$trackDeletedPhoto->trackDeletedPhotoDetails($this->whereCondition);
		}
		return true;
	}

	public function trackDeletePhoto($from)
	{
		$trackDelPhoto = new PHOTO_DELETE_TRACKING();
		$trackDelPhoto->trackPhotoDelete($this->pictureid,$this->profileid,'frontend',$from);
		return true;
	}
	/**
	 *
	 * this function deletes the photo from PICTURE_FOR_SCREEN_NEW AND PICTURE_NEW
	 */
	public function delete($photoObj,$otherPhotoObj)
	{
		$status=$photoObj->del($this->whereCondition);
		$statusOther=$otherPhotoObj->del($this->whereCondition);
		if($status==1||$statusOther==1)
			return 1;
		return 0;
	}


	public function getDeletedFrom($photoObj)
	{
		$class=get_class($photoObj);
		if($class=='ScreenedPicture')
			$from = 'screened';
		elseif($class=='NonScreenedPicture')
			$from = 'unscreened';
		return $from;
	} 
	/**
	 *
	 * This function updates the ordering of the pic on the basis of flags PROFILE_PIC_DELETE
	 */
	public function updateOrdering($picObj,$photoObj,$otherPhotoObj,$canDelete)
	{
		if($canDelete['PROFILE_PIC_DELETE']!=1)
		{
			$screenedProfilePicDelete = $canDelete['SCREENED_PROFILE_PIC_DELETE'];
			$orderCondition = array("PROFILEID"=>$picObj->getPROFILEID(),"ORDERING"=>$picObj->getORDERING());

			/***if pic is not screened profile pic then update photoObj ordering **/
			if($canDelete['SCREENED_PROFILE_PIC_DELETE']!=1)
				$photoObj->updateOrdering($orderCondition);

			/* If We are deleting Screen photos then ordering need to be updated in non-screen table as well **/
			if($picObj->getPictureType()==PictureStaticVariablesEnum::screenedPhotos)
				$otherPhotoObj->updateOrdering($orderCondition);
		}
		return true;
	}

	/**
	 *
	 * this function updates havephoto N or U
	 */
	public function changeHavePhoto($canDelete,$status)
	{
		if($status)
		{
			if($canDelete['PROFILE_PIC_DELETE']==1)
				$update = "N";
			elseif($canDelete['SCREENED_PROFILE_PIC_DELETE']==1)
				$update = "U";
			if($update)
				$this->updateHavePhoto('del',$update);
		}
	}
	/**
	 *
	 * this is the control function for the complete steps of deleting photo of a profile
	 */
	public function deletePhotoId()
	{
                if(!$this->pictureid)
                        throw new jsException("","PICTUREID IS BLANK IN deletePhoto() of PictureService.class.php");

		$picObj = $this->getPictureObject();

		if($picObj)
		{
			$profilePicObj = $this->getProfilePic();

			$photoCount = $this->getUserUploadedPictureCount();

			$canDelete = $this->canDelete($picObj,$photoCount,$profilePicObj);

			if($canDelete['CAN_DELETE'])
			{
				$photoObjs  = $this->getPhotoObjects($picObj);

				$this->trackDeletePictureDetails($photoObjs['photoObj']);

				$status = $this->delete($photoObjs['photoObj'],$photoObjs['otherPhotoObj']);

				$this->changeHavePhoto($canDelete,$status);

				$this->updateOrdering($picObj,$photoObjs['photoObj'],$photoObjs['otherPhotoObj'],$canDelete);

				$from = $this->getDeletedFrom($photoObjs['photoObj']);

				$this->trackDeletePhoto($from);
        
        $this->updateProfileCompletionScore($this->profileid);
			}
		}
		return true;
	}
}
