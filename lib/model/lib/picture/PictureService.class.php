<?php
/**
** This Class will provide all services/requests related to picture(s) info of a profile.
*/
class PictureService
{
	private $pictureObj;
        private $noPhoto='N';
        private $photoUnderScreening='U';
        private $neverAddedPhoto='';
        private $photoPresent='Y';

        private $viewedProfile_InitiatesOrAccpeted='C';

	private $nonScreenedPhotos='N';
	private $screenedPhotos='S';

	private $photosToBeScreenedFlag=0;
	private $photosScreenedFlag=1;

	private $thumbnail96X = 96;
	private $thumbnail96Y = 96;
	private $profilePicX = 150;
	private $profilePicY = 200;
	private $thumbnailX = 60;
	private $thumbnailY = 60;
	private $canvasPicX = 340;
	private $canvasPicY = 310;
	private $searchPicX = 100;
	private $searchPicY = 133;

	public static $isAlbum = 'A';
	public static $largePhoto = 'L';
	public static $noAlbumOrLargePhoto = 'N';
  
  protected $MAILTO = "lavesh.rawat@gmail.com,lavesh.rawat@jeevansathi.com,reshu.rajput@jeevansathi.com,esha.jain@jeevansathi.com"; // mail to emails
        public function __construct($profileObj,$source='')
        { 
		$this->profileObj=$profileObj;
                $this->source=$source;
        }

        public function getPhotosScreenedFlag()
        {
		return $this->photosScreenedFlag;
        }
	public function getNoPhoto()
	{
		return $this->noPhoto;
	}
	public function getPhotoUnderScreening()
	{
		return $this->photoUnderScreening;
	}
	public function getNeverAddedPhoto()
	{
		return $this->neverAddedPhoto;
	}
	public function getPhotoPresent()
	{
		return $this->photoPresent;
	}
	public function getViewedProfile_InitiatesOrAccpeted()
	{
		return $this->viewedProfile_InitiatesOrAccpeted;
	}
	public function getPictureObj()
	{
		return $this->pictureObj;
	}
	public function setPictureObj($pictureObj)
	{
		$this->pictureObj = $pictureObj;
	}

        public function getPhotosToBeScreenedFlag()
        {
		return $this->photosToBeScreenedFlag;
        }

	/**
	  * This function returns the url of dummy photo that is shown when a user does not have a photo
	  * @param - $photoName - set it as 'requestPhoto' or 'noPhoto' depending on the photo you want to show.
	  * @param - $photoSize - set it as 'Profile' for profile photo, 'Thumbnail' for thumbnail photo (60X60px) and 'Search' for search photo (100X133 px)
	  * @param - $gender - gender of the person for whom the photo is to be shown (set as 'M' or 'F')
	  * @param - $isMobile - set the value as 'Y' if mobile version, else leave it blank.
	  * @return - the required url
	**/

	static public function getRequestOrNoPhotoUrl($photoName, $photoSize, $gender, $isMobile='')
	{
		if($isMobile == 'Y')
			$mobile = 'Mobile';
		else
			$mobile = '';

		if($gender == 'M')
			$genderVal = "Male";
		else if($gender == 'F')
			$genderVal = "Female";
		if(!in_array($photoSize,ProfilePicturesTypeEnum::$PICTURE_SIZES_STOCK))
			$photoSize = "ProfilePicUrl";
		return sfConfig::get("app_img_url").constant('StaticPhotoUrls::'.$photoName.$genderVal.$mobile.$photoSize);
	}


        /**
        This function is used to retrieve profile pic of the user.
        If Loggedin user retrives its profile pic then non-screened picture will be given preference over screened.
        If user is viewing profile pic of other user then only screened picture will be shown.
        * @param contactTypeWithLoggedinProfile need contact type b/w sender and receiver for handling case for Privacy:Visible to those you have accepted or expressed interest in 
        * @return picture object-array  list of profile pic info. Returns NULL if no profile pic found.
        **/
	public function getProfilePic($contactTypeWithLoggedinProfile='')
	{
		if(!$this->profileObj->getPROFILEID())
                        throw new jsException("","PROFILEID IS BLANK IN getProfilePic() of PictureService.class.php");

		if($this->profileObj->getHAVEPHOTO()==$this->noPhoto || $this->profileObj->getHAVEPHOTO()==$this->neverAddedPhoto)
			return NULL;

		$profileObjArr[0] = $this->profileObj;
		$pictureArrayObj = new PictureArray($profileObjArr);
		$class=get_class($this->profileObj);
		if($class == 'LoggedInProfile' || $class == 'Operator')
			$photoObj = $pictureArrayObj->getProfilePhoto("N","","",$this->profileObj,"",$contactTypeWithLoggedinProfile,"");
		else if ($class=='Profile')
			$photoObj = $pictureArrayObj->getProfilePhoto("N","","","","",$contactTypeWithLoggedinProfile,"");
		return $photoObj[$this->profileObj->getPROFILEID()];

		/*
		$class=get_class($this->profileObj);

		if($class=='Profile')
		{
			$this->pictureObj=$this->getScreenedPhotos('profilePic');

			if(!$this->pictureObj)
				return NULL;

			if(!$this->profileObj->getGENDER())
				throw new jsException("","PROFILE GENDER IS BLANK IN getProfilePic() of PictureService.class.php");
	
			// for underscreening and Privacy:Visible to those you have accepted or expressed interest in
			if($this->profileObj->getHAVEPHOTO()==$this->photoUnderScreening || ($this->profileObj->getHAVEPHOTO()==$this->photoPresent && $this->profileObj->getPHOTO_DISPLAY()==$this->viewedProfile_InitiatesOrAccpeted))
				return $this->updatePictureUrlsForHiddenPhotos($contactTypeWithLoggedinProfile);
			else
				return $this->pictureObj;
		}
		else
		{
			$this->pictureObj=$this->getNonScreenedPhotos('profilePic');
			if($this->pictureObj)
				return $this->pictureObj;

			$this->pictureObj=$this->getScreenedPhotos('profilePic');
			if($this->pictureObj)
				return $this->pictureObj;
		}
		return NULL;
		*/
	}


        /**
        This function is used to retrieve album of the user.
        If Loggedin user retrives its album then non-screened picture will be given preference over screened.
        If user is viewing profile pic of other user then only screened picture will be shown.
        * @param contactTypeWithLoggedinProfile need contact type b/w sender and receiver for handling case for Privacy:Visible to those you have accepted or expressed interest in 
        * @return picture array(of object-array)  list of album info. Returns '' if no pics found.
        **/
	public function getAlbum($contactTypeWithLoggedinProfile='')
	{
		$class=get_class($this->profileObj);
		if(!$this->profileObj->getPROFILEID())
                        throw new jsException("","PROFILEID IS BLANK IN getAlbum() of PictureService.class.php");

		if($this->profileObj->getHAVEPHOTO()==$this->noPhoto || $this->profileObj->getHAVEPHOTO()==$this->neverAddedPhoto)
			return NULL;

		if($class=='Profile') //only screened images required.
		{
			if($this->profileObj->getHAVEPHOTO()==$this->photoUnderScreening)
				return NULL;
			if(($this->profileObj->getHAVEPHOTO()==$this->photoPresent && $this->profileObj->getPHOTO_DISPLAY()==$this->viewedProfile_InitiatesOrAccpeted) && !in_array($contactTypeWithLoggedinProfile,array('I','A','RA','showPhoto')))
				return NULL;
		}
		else
			$NonScreenedPicture=$this->getNonScreenedPhotos('album');

		//Create array of objects of 2 classes
		$ScreenedPicture=$this->getScreenedPhotos('album');
		
		//Create array of objects of 2 classes
		/*
		Imp Points.
		1. For own album    order : profile-pic ---> recency
		2. For Other album  order : profile-pic ---> Self keyword photo---> recency
		*/

		//Non Screened Section
		if(is_array($NonScreenedPicture))
		{
			foreach($NonScreenedPicture as $k=>$v)
			{
				if($v->getORDERING()==0)
				{
					$finalArr[]=$v;
					$tempKeyArr[]=$v->getPICTUREID();
					$nonScreenProfilePicIsSet=1; // profile have non screened profile pic
				}
				else
				{
					$entry_dt=$NonScreenedPicture[$k]->getUPDATED_TIMESTAMP();
				        list($yy,$mm,$dd) = explode("-",substr($entry_dt,0,10));
				        list($gg,$ii,$ss) = explode(":",substr($entry_dt,11));

					$entry_timestamp = mktime($gg,$ii,$ss,$mm,$dd,$yy)*1000; // multiplied by 1000 as non-screened pic have more preference 
					$NonScreenedPictureNew[$v->getPICTUREID()]=$v;
					$NonScreenedPictureNewTimeStamp[$v->getPICTUREID()]=$entry_timestamp;
				}
			}
			//Sorting By Time 
			if(is_array($NonScreenedPictureNewTimeStamp))
				arsort($NonScreenedPictureNewTimeStamp);
			//Sorting By Time 
		}
		//Non Screened Section


		//Screened Section
		if(is_array($ScreenedPicture))
		{
			foreach($ScreenedPicture as $k=>$v)
			{
				//Screened Profile Pic will be given maximum preference only if non-screened profile pic is not set
				if($v->getORDERING()==0 && !$nonScreenProfilePicIsSet)
				{
					$finalArr[]=$v;
					$tempKeyArr[]=$v->getPICTUREID();
				}
				else
				{
					$entry_dt=$ScreenedPicture[$k]->getUPDATED_TIMESTAMP();
					list($yy,$mm,$dd) = explode("-",substr($entry_dt,0,10));
					list($gg,$ii,$ss) = explode(":",substr($entry_dt,11));

					if($class=='Profile' && strstr($v->getKEYWORD(),'1')) //Self keyword photo more preferece
						$entry_timestamp = mktime($gg,$ii,$ss,$mm,$dd,$yy)*1000;
					else
						$entry_timestamp = mktime($gg,$ii,$ss,$mm,$dd,$yy);

					//Avoid duplication as screened pic can be in non screeened as well
					if(!in_array($v->getPICTUREID(),$tempKeyArr))
					{
						$ScreenedPictureNew[$v->getPICTUREID()]=$v;
						$ScreenedPictureNewTimeStamp[$v->getPICTUREID()]=$entry_timestamp;
					}
					//Avoid duplication as screened pic can be in non screeened as well
				}
			}
			if(is_array($ScreenedPictureNewTimeStamp))
				arsort($ScreenedPictureNewTimeStamp);
		}
		//Screened Section


		unset($ScreenedPicture);
		unset($NonScreenedPicture);
		//creating array on basis of orderid.			

		//Return Merged Array of Objects
		if(is_array($ScreenedPictureNew) && is_array($NonScreenedPictureNew) )
		{
			foreach($NonScreenedPictureNewTimeStamp as $k=>$v)
			{
				$finalArr[]=$NonScreenedPictureNew[$k];
				$tempKeyArr[]=$k;	
			}
			foreach($ScreenedPictureNewTimeStamp as $k=>$v)
			{
				if(!in_array($k,$tempKeyArr))
					$finalArr[]=$ScreenedPictureNew[$k];
			}
		}
		elseif(is_array($ScreenedPictureNew))
		{
			foreach($ScreenedPictureNewTimeStamp as $k=>$v)
				$finalArr[]=$ScreenedPictureNew[$k];
		}
		elseif(is_array($NonScreenedPictureNew))
		{
			foreach($NonScreenedPictureNewTimeStamp as $k=>$v)
				$finalArr[]=$NonScreenedPictureNew[$k];
		}
		if($finalArr)
			return $finalArr;
		else
			return '';
		//Return Merged Array of Objects
	}


	/** 
	This function is used to update the picture information.
  * @param picObjArray array of Object-Array
	* @return status int return 1 if successfully updated else 0 (any error)
	*/
	public function editAlbumInfo($v)
	{
		$status = 1;
    $outputArray['tempStatus']=1;
    if($v->getPictureType()==$this->nonScreenedPhotos){
      $outputArray = $this->editNonScreenedPicture($v);
    }else{
      $outputArray = $this->editScreenedPicture($v);
    }
    $status=$status && $outputArray['tempStatus'];
    return $status;
	}
  public function editNonScreenedPicture($picObj) {
    $updation_in_PICTURE_NEW = 1;
    $tempStatus = 1;
    
    $selectCondition["PICTUREID"] = $picObj->getPICTUREID();
    $selectCondition["PROFILEID"] = $picObj->getPROFILEID();
    $currentPicObject = $this->getNonScreenedPhotos('selectCondition', $selectCondition);
    
    $fieldArray = array("TITLE", "KEYWORD", "ORDERING", "ProfilePicUrl", "ThumbailUrl");
    $updateArray = $this->checkFieldUpdated($fieldArray, $currentPicObject[0], $picObj);
    
    if (array_key_exists("TITLE", $updateArray)) {
      $updation_in_PICTURE_NEW = 2;
    }
    if (array_key_exists("KEYWORD", $updateArray)) {
      $updation_in_PICTURE_NEW*=3;
    }
    if ($picObj->getORDERING() == '0' || $picObj->getORDERING()) {
    }else{
      unset($updateArray["ORDERING"]);
    }
    if ($updateArray) {
      $tempStatus = $this->updatePictureForScreenNewData($updateArray, $picObj->getPICTUREID(), $picObj->getPROFILEID());
    }
    /** Ensures Updatation is done only if TITLE/KEYWORD IS UPDATED. * */
    //Updating screened picture assuming this picId can exist in this table as well.
    if ($updation_in_PICTURE_NEW > 1) {
      /* Cases Handled Here 
        1. If title is updated with a non blank value => updated UNSCREENED_TITLE , do not touch TITLE values
        2. If title is updated with a blank value => updated UNSCREENED_TITLE,TITLE with blank values.
       */
      if ($updation_in_PICTURE_NEW % 2 == 0) {
        $updateArray["UNSCREENED_TITLE"] = $updateArray["TITLE"];
        if ($updateArray["TITLE"] != '')
          unset($updateArray["TITLE"]);
      }
      $tempStatus = $this->updatePictureNewData($updateArray,$picObj->getPICTUREID(),$picObj->getPROFILEID());
    }
    //Updating non-screened picture assuming this picId can exist in this table as well.
    return array('tempStatus'=>$tempStatus);
  }
  public function editScreenedPicture($picObj) {
    $tempStatus = 1;
    $selectCondition["PICTUREID"] = $picObj->getPICTUREID();
    $selectCondition["PROFILEID"] = $picObj->getPROFILEID();
    $currentPicObject = $this->getScreenedPhotos('selectCondition', $selectCondition);
    $fieldArray = array("KEYWORD");
    $updateArray = $this->checkFieldUpdated($fieldArray, $currentPicObject[0], $picObj);
    /* Cases Handled Here 
      1. If title is updated with a non blank value => updated UNSCREENED_TITLE , do not touch TITLE values
      2. If title is updated with a blank value => updated UNSCREENED_TITLE,TITLE with blank values.
     */
    if ($currentPicObject[0]->getTITLE() != $picObj->getTITLE()) {
      if ($v->getTITLE() != '') {
        $updateArray["UNSCREENED_TITLE"] = $picObj->getTITLE();
        $screenedTitleisUpdated = 1;
      } else {
        $updateArray["TITLE"] = '';
        $updateArray["UNSCREENED_TITLE"] = '';
        $checkPhotoScreenFlag = 1;
      }
    }
    if($updateArray){
      $tempStatus = $this->updatePictureNewData($updateArray,$picObj->getPICTUREID(),$picObj->getPROFILEID());
    }
    $this->updateHavePhotoData($screenedTitleisUpdated,$checkPhotoScreenFlag,$picObj->getPROFILEID());
    return array('tempStatus'=>$tempStatus);
  }
  public function updateHavePhotoData($screenedTitleisUpdated,$checkPhotoScreenFlag,$profileId){
    if($screenedTitleisUpdated)
      $this->updateHavePhoto('edit');
    if($checkPhotoScreenFlag)
    {
      $checkPhotoScreenFlag1 = $this->checkForPhotoScreen($profileId);
      if ($checkPhotoScreenFlag1)
        $this->updateHavePhoto('edit',1);
    }
  }
  public function checkFieldUpdated($fieldArray,$currentPicObj,$picObj){
    if(!is_array($fieldArray) || empty($fieldArray))
      return '';
    $updateArray = '';
    foreach($fieldArray as $field){
      eval('$currentPicObj->get'.$field.' != $picObj->get'.$field.'();');
      eval('$updateArray['.'"'.$field.'"'.']=$picObj->get'.$field.'();');
    }
    return $updateArray;
  }
  public function updatePictureNewData($updateArray,$pictureId,$profileId){
    $PICTURE_NEW=new ScreenedPicture;
		$tempStatus=$PICTURE_NEW->edit($updateArray,$pictureId,$profileId);
    unset($PICTURE_NEW);
    return $tempStatus;
  }
  public function updatePictureForScreenNewData($updateArray,$pictureId,$profileId){
    $PICTURE_FOR_SCREEN_NEW=new NonScreenedPicture;
		$tempStatus = $PICTURE_FOR_SCREEN_NEW->edit($updateArray, $pictureId, $profileId);
    unset($PICTURE_FOR_SCREEN_NEW);
    return $tempStatus;
  }
  /** 
	This function is used to add the picture information.
	The latest pic will be the 1st pic to display after profile photo.
        * @param pictureObj array of Object-Array
	* @return status int return 1 if successfully updated else 0 (any error)
	*/
  public function addPhotos($pictureObj){
    $status=1;
    $photoData = $this->getPhotoObjectNColumns($pictureObj);
    foreach($photoData['photoColumns'] as $kk=>$vv)
    {
      eval('$updateArray['.'"'.$vv.'"'.']=$pictureObj->get'.$vv.'();');
    }
    $tempStatus = $photoData['photoObj']->ins($updateArray,$pictureObj->getPROFILEID());
    if(get_class($photoData['photoObj']) == "ScreenedPicture")
    {
      mail($this->MAILTO,"PictureService insImageSeverLog called for Screened Images");
      $tempstat= $photoData['photoObj']->insImageServerLog($updateArray,$pictureObj->getPROFILEID());
      $tempStatus=$tempStatus && $tempstat;
    } 
    $status=$status && $tempStatus;
    $this->updateHavePhoto('add');
		return $status;
  }
  /**
   * 
   * @param type $pictureObj araay of picture object
   * @return type array of photo columns and photo object for picture (screened or non screened)
   */
  public function getPhotoObjectNColumns($pictureObj){
    if($pictureObj->getPictureType()==PictureStaticVariablesEnum::nonScreenedPhotos){
      $photoObj=new NonScreenedPicture;
      $columnUpdationAllowed= array_merge(ProfilePicturesTypeEnum::$PICTURE_NONSCREENED_SIZES_FIELDS, PictureStaticVariablesEnum::$photoColumnArray);
    }else{
      $photoObj=new ScreenedPicture;
      $columnUpdationAllowed= array_merge(ProfilePicturesTypeEnum::$PICTURE_SIZES_FIELDS, PictureStaticVariablesEnum::$photoColumnArray);
    }
    return array('photoObj'=>$photoObj,'photoColumns'=>$columnUpdationAllowed);
  }


        /**
        This function is used to delete a photo from PICTURE_FOR_SCREEN_NEW(N) OR PICTURE_NEW(S) table based on PictureType
	A special condition: If I try to delete from non screened table (PICTURE_FOR_SCREEN_NEW) and no records gets deleted ....then i try to delete corresponding row from screened table (PICTURE_NEW) as only possibilty is that the pic got screened.
        * @param Picture Object $picObj  NonScreenedPicture/ScreenedPicture info (of one picture info)
        * @return status int  if rows gets deleted it will return 1;
        **/
	//public function deletePhoto($picObj)
	public function deletePhoto($pictureId,$profileId,$userType="other")
	{
		$deletePhotoObj = new deletePhoto($pictureId,$profileId,$this->profileObj,$userType,$this->source);
		return $status = $deletePhotoObj->deletePhotoId();
	}

        /**
        This function is used to get autoincrement id for storing picture.
        @return auto increment id which will be used as pictureId for PICTURE_NEW & PICTURE_FOR_SCREEN_NEW
        */
	public function getPictureAutoIncrementId()
	{
		$PICTURE_AUTOINCREMENT = new PICTURE_AUTOINCREMENT;
		return $PICTURE_AUTOINCREMENT->getAutoIncrementPictureId();
	}

	/** 
	This function is used to set a pic as profile pic
        * @param picObjArray array of Object-Array
	* @return status int return 1 if successfully updated else 0 (any error)
	*/
	public function setProfilePic($picObj)
	{
		$setProfilePicObj = new SetProfilePic($picObj,$this->profileObj->getPROFILEID(),$this->profileObj,$this->source);
    $profileSetData = $setProfilePicObj->setProfilePicControl();
		return isset($profileSetData[0])?$profileSetData[0]:true;
	}
  /*
   * fucntion get Screened photo data
   */
  public function getScreenedPictureData($PICTURE_NEW,$whereCondition,$noCompleteUrl,$returnFirstValue){
    $class = get_class($this->profileObj);
    $getFromMasterR = '0';
    //when logged-in user views his album , un-screened title is given preference 
    if ($class != 'Profile'){
      $showUnScreenedTitle = 1;
    }else{
      $getFromMasterR = '1';
    } 
    $pics=$PICTURE_NEW->get($whereCondition,$getFromMasterR);
    if(is_array($pics) && $pics[0]!='')
    {
      foreach ($pics as $k => $v) {
        $ScreenedPicture[$k] = new ScreenedPicture;
        $ScreenedPicture[$k]->setDetail($v, $showUnScreenedTitle);
        if (!$noCompleteUrl){
          $ScreenedPicture[$k]->setCompletePictureUrl();
        }
      }
      if($returnFirstValue === 1){
        return $ScreenedPicture[$k];
      }
      return $ScreenedPicture;
    }
    return NULL;
  }
  public function getNonScreenedPictureData($PICTURE_FOR_SCREEN_NEW,$whereCondition,$noCompleteUrl,$returnFirstValue){
    $pics=$PICTURE_FOR_SCREEN_NEW->get($whereCondition);
    if(is_array($pics) && $pics[0]!='')
    {
      foreach ($pics as $k => $v) {
        $ScreenedPicture[$k] = new NonScreenedPicture($this->source);
        $ScreenedPicture[$k]->setDetail($v);
        if (!$noCompleteUrl){
          $ScreenedPicture[$k]->setCompletePictureUrl();
        }
      }
      if($returnFirstValue === 1){
        return $ScreenedPicture[$k];
      }
      return $ScreenedPicture;
    }
    return NULL;
  }
        /**
        This function is used to retrieve screened profile pic / album pics of the user.
	@param infoType(profilePic/album/selectconditions) string options for getting Screened Pics
        @param selectCondition array if information is fetched based on where conditions
        * @return picture object-array  list of profile pic info or object array of album pics
	Returns NULL if no profile pic found.
        **/
	public function getScreenedPhotos($infoType = '', $selectCondition = '', $noCompleteUrl = '') {
    $PICTURE_NEW = new ScreenedPicture;
    
    $whereCondition["PROFILEID"] = $this->profileObj->getPROFILEID();
    $returnFirstValue = 0;
    if ($infoType == 'profilePic') {
      $whereCondition["ORDERING"] = 0;
      $returnFirstValue = 1;
    } elseif ($infoType == 'album') {
    } elseif ($infoType == 'selectCondition') {
      unset($whereCondition);
      foreach ($selectCondition as $k => $v){
        $whereCondition[$k] = $v;
      }
    } elseif ($infoType == 'exists') {
      return $PICTURE_NEW->pictureidExist($selectCondition, $this->profileObj->getPROFILEID());
    }
    return $this->getScreenedPictureData($PICTURE_NEW, $whereCondition, $noCompleteUrl, $returnFirstValue);
  }

  /**
        This function is used to retrieve non-screened profile pic / album pics of the user.
        * @return picture object-array  list of profile pic info or object array of album pics
	Returns NULL if no profile pic found.
        **/
  public function getNonScreenedPhotos($infoType = '', $selectCondition = '', $noCompleteUrl = '') {
    $PICTURE_FOR_SCREEN_NEW = new NonScreenedPicture($this->source);
    $whereCondition["PROFILEID"] = $this->profileObj->getPROFILEID();
    $returnFirstValue = 0;
    if ($infoType == 'profilePic') {
      $whereCondition["ORDERING"] = 0;
      $returnFirstValue = 1;
    }elseif ($infoType == 'album') {
    }elseif ($infoType == 'selectCondition') {
      unset($whereCondition);
      foreach ($selectCondition as $k => $v)
        $whereCondition[$k] = $v;
    }
    return $this->getNonScreenedPictureData($PICTURE_FOR_SCREEN_NEW, $whereCondition, $noCompleteUrl, $returnFirstValue);
  }

  /**
	* This function is used to save a photo.
	* The photo is imported if parameter $source='import'.
	* The photo is uploaded is parameter $source=computer or computer_noFlash.
	**/
	public function saveAlbum($fileData='',$source, $profileid='',$importSite='')
	{
		$uploadPhotoObj = new UploadPhoto($this->profileObj,$source,$this->source);
		$return = $uploadPhotoObj->uploadPhoto($fileData,$importSite);
		return $return;
	}

	public function saveAlbumFromMail($mailId)
	{
                $havePhotoBeforeUpload = $this->profileObj->getHAVEPHOTO();
		$photoAttachmentObj = new PHOTO_ATTACHMENTS();
		$images = $photoAttachmentObj->getImageAttachments($mailId);	
		if(is_array($images))
		{
			$pictureFunctionObj = new PictureFunctions();
			foreach($images as $key=>$value)
			{
				$imagePath = JsConstants::$docRoot."/uploads/MailImages/".$value;
				$imageSize = filesize($imagePath);
				$imageType = PictureFunctions::getImageFormatType($imagePath);
				if($imageSize >0 && $imageSize <= PictureStaticVariablesEnum::MAX_PICTURE_SIZE && in_array("image/".$imageType,PictureStaticVariablesEnum::$PICTURE_ALLOWED_FORMATS))
				{
					$nonScreenedPicObj = new NonScreenedPicture;
					$picId = $this->getPictureAutoIncrementId();
					$picUrl = $nonScreenedPicObj->getSaveUrl(ProfilePicturesTypeEnum::$PICTURE_UPLOAD_DIR["MainPicUrl"],$picId,$this->profileObj->getPROFILEID(),$imageType);
					chmod($imagePath,0777);					
					$upload_output = $pictureFunctionObj->moveImage($imagePath, $picUrl);
					chmod($picUrl,0777);
					if($upload_output)
					{
						$picArray["PICTUREID"] = $picId;
                                                $picArray["PROFILEID"] = $this->profileObj->getPROFILEID();
                                                $picArray["PICTURETYPE"] = "N";
                                                $picArray["ORDERING"] = $this->getOrderingForInsertion();
                                                $picArray["MainPicUrl"] = $nonScreenedPicObj->getDisplayPicUrl(ProfilePicturesTypeEnum::$PICTURE_UPLOAD_DIR["MainPicUrl"],$picId,$this->profileObj->getPROFILEID(),$imageType);
						$picArray["PICFORMAT"]= $imageType;
					}
					$nonScreenedPicObj->setDetail($picArray);
					$insStatus=$this->addPhotos($nonScreenedPicObj);
					if ($insStatus)
					{

						$this->saveImageDeatils($picId,$picUrl,$this->profileObj->getPROFILEID());
						$this->track1stUnscreenedPhoto($havePhotoBeforeUpload='',"mail");
						//SUCCESS WITH UPLOAD AND DATABASE ENTRY
					}
					else
					{
						$return[] = "Error in database entry";
					}

				}
				else
				{
						$return[] = "Pic size or format issue";
				}
			}
		}
		if(is_array($return))
			return $return;

		return "Success";
	}

        /**
        This function is used to get total uploaded pictures by a user.
        * @return count int total uploaded photos by a user. 
        **/
	public function getUserUploadedPictureCount()
	{
		$countS=0;
                $profileId=$this->profileObj->getPROFILEID();
		$this->profileObj->getDetail("","","HAVEPHOTO,GENDER","RAW");
                $albumList=$this->getAlbum();
		if($albumList)
	                $countS=count($albumList);
		return $countS;
	}

        /**
        This function is used to retrieve picture details based on where conditions.
        If Loggedin user retrives its profile pic then non-screened picture will be given preference over screened.
        If user is viewing profile pic of other user then only screened picture will be shown.
        * @return picture object   
        **/
	public function getPicDetails($whereArr,$noCompleteUrl='')
	{
		$pictureId=@$whereArr["PICTUREID"];
		$profileId=$whereArr["PROFILEID"];

                //if(!$this->profileObj->getPROFILEID())
                        //throw new jsException("","PROFILEID IS BLANK IN getPicDetails() of PictureService.class.php");
		if(!$pictureId)
                        throw new jsException("","PICTUREID IS BLANK IN getPicDetails() of PictureService.class.php");
                $class=get_class($this->profileObj);

		
		$selectCondition["PICTUREID"]=$pictureId;
		$selectCondition["PROFILEID"]=$profileId;

                if($class!='Profile')
		{
			$currentPicObject=$this->getNonScreenedPhotos('selectCondition',$selectCondition,$noCompleteUrl);
		}
		if(!$currentPicObject)
		{
			$currentPicObject=$this->getScreenedPhotos('selectCondition',$selectCondition,$noCompleteUrl);
		}

		return $currentPicObject;
	}

        /**
        This function is used to get ordering values for insertion
        * @return count int total uploaded photos by a user. 
        **/
	public function getOrderingForInsertion()
	{
		$currentOrdering = $this->getUserUploadedPictureCount();	
		/*
		if ($currentOrdering>0)					//If photos present then return 1 
			return 1;
		else
		*/
			return $currentOrdering;			//If no pics present then return 0
	}
	public function updateHavePhoto($method,$value='')
	{
		if($method=='add')
		{
	        	$havePhoto=$this->profileObj->getDetail($this->profileObj->getPROFILEID(),'PROFILEID','HAVEPHOTO');
			if($havePhoto['HAVEPHOTO']==$this->neverAddedPhoto ||$havePhoto['HAVEPHOTO']==$this->noPhoto)
			{
				$this->profileObj->edit(array("HAVEPHOTO"=>"U","PHOTODATE"=>date("Y-m-d H:i:s"),"PHOTOSCREEN"=>$this->photosToBeScreenedFlag));
			}
			else
				$this->profileObj->edit(array("PHOTODATE"=>date("Y-m-d H:i:s"),"PHOTOSCREEN"=>$this->photosToBeScreenedFlag));
		}
		elseif($method=='del')
		{
			if($value==$this->photoUnderScreening)
			{
				$this->profileObj->edit(array("HAVEPHOTO"=>"U","PHOTODATE"=>date("Y-m-d H:i:s"),"PHOTOSCREEN"=>$this->photosToBeScreenedFlag));

			}
			elseif($value==$this->noPhoto)
			{
				$this->profileObj->edit(array("HAVEPHOTO"=>"N","PHOTODATE"=>date("Y-m-d H:i:s"),"PHOTOSCREEN"=>$this->photosScreenedFlag));
			}
		}
		else
		{
			if ($value)
				$this->profileObj->edit(array("PHOTOSCREEN"=>$this->photosScreenedFlag));
			else
				$this->profileObj->edit(array("PHOTOSCREEN"=>$this->photosToBeScreenedFlag));
		}
	}

	public function updatePictureUrlsForHiddenPhotos($contactTypeWithLoggedinProfile="",$mobileView="")
	{
		if($this->profileObj->getHAVEPHOTO()==$this->photoUnderScreening)
		//UnderScreening
		{
			foreach(ProfilePicturesTypeEnum::$PICTURE_NONSCREENED_SIZES_FIELDS as $i=>$key)
			{
				eval('$this->pictureObj->set'.$key.'("",$this->profileObj,$this->photoUnderScreening,$mobileView);');
			}
		}
		elseif($this->profileObj->getHAVEPHOTO()==$this->photoPresent && $this->profileObj->getPHOTO_DISPLAY()==$this->viewedProfile_InitiatesOrAccpeted)
		//Privacy:Visible to those you have accepted or expressed interest in.
		{
			if(strstr($_SERVER['PHP_SELF'],'symfony_index.php')){
				if(sfContext::getInstance()->getRequest()->getAttribute('login')=='') $userNotLoggedIn=1;
			}else{
				global $data;
				if($data['PROFILEID']=='') $userNotLoggedIn=1;
			}
			if($userNotLoggedIn)//Non-Logged In Case
			{
				foreach(ProfilePicturesTypeEnum::$PICTURE_SIZES_FIELDS as $i=>$key)
				{
					eval('$this->pictureObj->set'.$key.'("",$this->profileObj,"NL",$mobileView);');
				}
				
			}
			elseif(!in_array($contactTypeWithLoggedinProfile,array('I','A','RA','showPhoto'))) //Nikhil will finalize these values
			{
				foreach(ProfilePicturesTypeEnum::$PICTURE_SIZES_FIELDS as $i=>$key)
				{
					eval('$this->pictureObj->set'.$key.'("",$this->profileObj,"",$mobileView);');
				}
				
			}
		}
		return $this->pictureObj;
	}

	/**
	* This function is used to check if PHOTOSCREEN can be made 1 or not
	**/
	private function checkForPhotoScreen($profileId)
	{
		$pictureScrObj = new ScreenedPicture;
		$pictureNonScrObj = new NonScreenedPicture;
		$output = $pictureNonScrObj->getMaxOrdering($profileId);
		if ($output!=null)
			$output = true;
		else
			$output = false;
	
		if (!$output)
		{	
			$output1 = $pictureScrObj->hasUnscreenedTitle($profileId);
			if ($output1)
				return false;					//Title are there to be screened
			else
				return true;					//Nothing left to be screened
		}
		else
			return false;			//Photos are there to be screened
	}

	/**
	* This function is used to convert a picture array into picture object array
	**/
	public function arrayToObj($picArray)
	{ 
		if ($picArray["PICTURETYPE"] == "S")
        	{
                	$pictureObj = new ScreenedPicture;
        	}
        	else if ($picArray["PICTURETYPE"] == "N")
        	{
                	$pictureObj = new NonScreenedPicture;
        	}

		if ($pictureObj)                                                              
        	{
                	$pictureObj->setDetail($picArray);
                	$pictureObjArray = array($pictureObj);
			return $pictureObjArray;
        	}
		return null;
	}

	public function generateImages($picType,$src_pic_name,$dest_pic_name,$type,$params_array="")
	{ 
		$pictureFunctionObj = new PictureFunctions;
		if ($picType == "thumbnail96")
		{
			$pictureFunctionObj->maintain_ratio_canvas($src_pic_name,$dest_pic_name,0,0,0,0,$this->thumbnail96X,$this->thumbnail96Y,$type);
                        $pictureFunctionObj->generate_image_for_canvas($dest_pic_name,$this->thumbnail96Y,$this->thumbnail96X,$type);
		}
		else if ($picType == "profilePic")
		{
			$pictureFunctionObj->maintain_ratio_profile_thumb($src_pic_name,$dest_pic_name,$params_array[1],$params_array[2],0,0,$params_array[5],$params_array[6],$this->profilePicX,$this->profilePicY,$type);                                                          //Generate the profile pic of 150*200 dimensions
		}
		else if ($picType == "thumbnail")
		{
			$pictureFunctionObj->maintain_ratio_profile_thumb($src_pic_name,$dest_pic_name,$params_array[1],$params_array[2],0,0,$params_array[5],$params_array[6],$this->thumbnailX,$this->thumbnailY,$type);
		}
		else if ($picType == "canvasPic")
		{
			$pictureFunctionObj->maintain_ratio_canvas($src_pic_name,$dest_pic_name,0,0,0,0,$this->canvasPicX,$this->canvasPicY,$type,0); //Resize the image maintaining aspect ratio
                        $pictureFunctionObj->generate_image_for_canvas($dest_pic_name,$this->canvasPicY,$this->canvasPicX,$type,0);                   //Get canvas of 340*310 dimension
		}
		else if ($picType == "searchPic")
		{
			$pictureFunctionObj->maintain_ratio_canvas($src_pic_name,$dest_pic_name,0,0,0,0,$this->searchPicX,$this->searchPicY,$type);
		}
		else if ($picType == "watermark")
		{
			$pictureFunctionObj->createWatermark($src_pic_name,$dest_pic_name,$type);
		}
		unset($pictureFunctionObj);
	}

	public function getIfPhotoRequested($senders,$receivers)
	{
		$viewerObj = LoggedInProfile::getInstance("newjs_master",'');
		$viewer = $viewerObj->getPROFILEID();
                $dbName = JsDbSharding::getShardNo($viewer);
                $photoRequestObj = new NEWJS_PHOTO_REQUEST($dbName);
                $photoResults = $photoRequestObj->getIfPhotoRequested($senders,$receivers);
		if(is_array($photoResults))
		{
			foreach($photoResults as $res1)
			{
				if($res1['PROFILEID'] == $viewer)
				{
					$photoRequests['sentByViewer'][$res1['PROFILEID_REQ_BY']]=1;
				}
				elseif($res1['PROFILEID_REQ_BY'] == $viewer)
				{
					$photoRequests['receivedByViewer'][$res1['PROFILEID']]=1;
				}
			}
			return $photoRequests;
		}
	}

	/*
	* Save image details of $file into our database.
	*/
	public function saveImageDeatils($picId,$file,$profileId)
	{
		$imageDetails = exif_read_data($file);
		$NEWJS_PICTURE_DETAILS = new NEWJS_PICTURE_DETAILS;
		$NEWJS_PICTURE_DETAILS->ins($picId,$profileId,$imageDetails);
	}
	
	/*
	* Associate a jevansathi user-id with facebook/picasa/flicr unique id.
	*/
	public function associateJsUser_with_importUniqueId($profileId,$uniqueId,$source)
	{
		$imageDetails["UNIQUE_ID"] = $uniqueId;
		$imageDetails["SOURCE"] = $source;
		$NEWJS_PICTURE_DETAILS = new NEWJS_PHOTO_IMPORT_USERDATA;
		$NEWJS_PICTURE_DETAILS->ins($imageDetails,$profileId);
	}

        /**
        This function is used to merge deleted pics as well as album pics of the user.
        * @return picture object-array  list of profile pic info or object array of album pics
        Returns NULL if no pic found.
	* @author reshu
        **/
        public function getAlbumWithDeletedPhotos()
        {
		$class=get_class($this->profileObj);
                if($class != 'Operator')
			die("You are not allowed to call this function : getAlbumWithDeletedPhotos");

		// Get array of album  pictures for a profile 
       		$albumPhotoObjArr=$this->getAlbum();

		// Get array of deleted pictures for a profile 
		$deletedPhotoObjArr=$this->getDeletedPhotos();

		//Return Merged Array of Objects
                if(is_array($deletedPhotoObjArr) && is_array($albumPhotoObjArr) )
                {
			$tempKeyArr= array_merge($deletedPhotoObjArr, $albumPhotoObjArr);
                }
                elseif(is_array($deletedPhotoObjArr))
                {
			$tempKeyArr=$deletedPhotoObjArr;
                }
                elseif(is_array($albumPhotoObjArr))
                {
			$tempKeyArr=$albumPhotoObjArr;
                }
		else
			return NULL;
                return $tempKeyArr;
        }


	/**
        This function is used to retrieve deleted pics of the user.
        * @return picture object-array  list of profile pic info or object array of album pics
        Returns NULL if no pic found.
        **/
        public function getDeletedPhotos()
        {
		$deletedPhoto = new DeletedPictures;
		$pics=$deletedPhoto->getDeletedPhotos($this->profileObj->getPROFILEID());
		
		if(is_array($pics))
		{
			foreach($pics as $k=>$v)
			{
				$deletedPhotos[$k]=new DeletedPictures;
				$deletedPhotos[$k]->setDetail($v);
				$deletedPhotos[$k]->setCompletePictureUrl();
			}
			return $deletedPhotos;
		}
		return NULL;
	}

	/**
	* This function will fire code(RocketFuel) for desktop 1st photo upload
	* @param havePhotoBeforeUpload havephoto of JPROFILE before upload.
	* @param source source of upload of photo
	*/
	public function track1stUnscreenedPhoto($havePhotoBeforeUpload='',$source)
	{
		if($havePhotoBeforeUpload==$this->neverAddedPhoto)
		{
			if($source=="mail")
				$obj = Operator::getInstance("",$this->profileObj->getPROFILEID());
			else
				$obj = LoggedInProfile::getInstance('newjs_master');
			$obj->getDetail("","","SOURCE");

			$pid = $this->profileObj->getPROFILEID();
                        if($source=="mail")
                                RegChannelTrack::insertPageChannel($pid,PageTypeTrack::_PHOTOUPLOAD,ChannelUsed::_OFFLINE);
                        else
                                RegChannelTrack::insertPageChannel($pid,PageTypeTrack::_PHOTOUPLOAD);
			$PHOTO_FIRST = new PHOTO_FIRST;
			$paramArray["PROFILEID"] = $pid;
			$isExists = $PHOTO_FIRST->isExists($paramArray);
			if($isExists==0)
			{
				$PHOTO_FIRST_UNSCREENED = new PHOTO_FIRST_UNSCREENED;
				$PHOTO_FIRST_UNSCREENED->add($pid,$source);
			}
		}
	}

	/**
	* This function will return rocket fuel code for photo
	* @return code for rocket fuel.
	*/
	public function getRocketFuelCodeForPhoto($profileid,$incomplete='')
	{
		$sourceArr = array(PhotoUploadSource::importDesktop,PhotoUploadSource::noflashDesktop,PhotoUploadSource::flashDesktop);
		$flag = 0 ;//default
		$PHOTO_FIRST_UNSCREENED = new PHOTO_FIRST_UNSCREENED;
		$arr = $PHOTO_FIRST_UNSCREENED->get('count(*) AS CNT',$profileid,$sourceArr,$flag);
		$cnt = $arr["CNT"];
		if($cnt)
		{
			if($incomplete=='Y')
			{
				$PHOTO_FIRST_UNSCREENED = new PHOTO_FIRST_UNSCREENED;
				$PHOTO_FIRST_UNSCREENED->updateFlag($profileid,2); //2=>no pixel code as its incomplete
			}
			else
			{
				$PHOTO_FIRST_UNSCREENED->updateFlag($profileid,1); //1=>pixel code fired.
				return PixelCode::fetchRocketFuelCode("upload");
			}
		}
		return NULL;
	}	

	/**
	* This function will check if profile photo of user is underscreening. 
	* @return Y : return variable if photo present else return null
	*/ 
	public function isProfilePhotoUnderScreening()
	{
		if($this->profileObj->getHAVEPHOTO() == $this->photoUnderScreening)
			return 'Y';
		return null;
	}

	/* This function is added by Reshu Rajput
	** This function will return if photo is present for the provided user
	* @param considerScreenPhoto set if want to consider only screened photo.
	* @return Y : return variable if photo present else return null
	*/
	public function isProfilePhotoPresent($considerScreenPhoto='')
	{
		if($this->profileObj->getHAVEPHOTO() != $this->noPhoto && $this->profileObj->getHAVEPHOTO() != $this->neverAddedPhoto)
		{
			if($considerScreenPhoto)
			{
				if ($this->profileObj->getHAVEPHOTO() != $this->photoUnderScreening)
					return 'Y';
				return null;
			}	
			return 'Y';
		}
		return null;
	}

	/** 
	* This function will return the label/text corresponding to album count
	* @param count album count
	* @param if set it will show text like "View Album" else enum liks "A"...
	* @return text/anum 
	*/
	public static function mapAlbumCountToLabel($count='',$text='')
	{
		if($count==1)
			$albumEnum = self::$largePhoto;
		elseif($count>1)
			$albumEnum = self::$isAlbum;
		else
			$albumEnum = self::$noAlbumOrLargePhoto;
		if($text)		
			$albumEnum = ($albumEnum==self::$isAlbum)?'View Album':(($albumEnum==self::$largePhoto)?'Larger Photo':'');
		return $albumEnum;
	}

	/*
	This function acts as the library to perform the photo request
	@return - error message or success
	*/
	public function performPhotoRequest()
	{
		$senderProfileObj=LoggedInProfile::getInstance('newjs_master');
		if($senderProfileObj && $senderProfileObj->getPROFILEID())
		{
			if(!$senderProfileObj->getACTIVATED() || !$senderProfileObj->getGENDER())
				$senderProfileObj->getDetail("","","ACTIVATED,GENDER");

			if($senderProfileObj->getACTIVATED()!="Y")
			{
				$error = "SenderNotActivated";
			}
			elseif($senderProfileObj->getGENDER()==$this->profileObj->getGENDER())
			{
				$error = "SameGender";
			}
			else
			{
				if($this->profileObj->getPRIVACY() == PhotoProfilePrivacy::filteredPrivacy)
				{
					$contactsObj = new Contacts($senderProfileObj,$this->profileObj);
					$contactType = $contactsObj->getTYPE();
					$contactSenderProfileId = $contactsObj->getSenderObj()->getPROFILEID();
					$contactReceiverProfileId = $contactsObj->getReceiverObj()->getPROFILEID();
					unset($contactsObj);
			
					if($contactType=="" || $contactType==ContactHandler::NOCONTACT || ($contactType==ContactHandler::INITIATED && $contactSenderProfileId==$senderProfileObj->getPROFILEID()) || ($contactType==ContactHandler::DECLINE && $contactSenderProfileId==$senderProfileObj->getPROFILEID()) || ($contactType==ContactHandler::CANCEL_CONTACT))
					{
						$viewedFilterParameters = MultipleUserFilter::getFilterParameters(array($this->profileObj->getPROFILEID()));
						$viewerParameters = $senderProfileObj->getFilterParameters();
						$ppObj = new PartnerProfile($this->profileObj);
						$ppObj->getDppCriteria();
						$viewedPartnerProfilesRequiredDetails = "LAGE,HAGE,RELIGION,CASTE,MTONGUE,COUNTRY_RES,CITY_RES,MSTATUS,INCOME";
						foreach(explode(",",$viewedPartnerProfilesRequiredDetails) as $k=>$v)
						{
							$viewedDppArr[$this->profileObj->getPROFILEID()][$v] = explode(",",$ppObj->{"get".$v}()); 
						}
						unset($ppObj);
						$filterObj = new MultipleUserFilter($viewerParameters, $viewedFilterParameters, $viewedDppArr, $senderProfileObj->getPROFILEID(), array($this->profileObj->getPROFILEID()));
						$profilesPassingFilters = $filterObj->checkIfProfileMatchesDpp();
						unset($filterObj);
						if(!$profilesPassingFilters || !is_array($profilesPassingFilters) || $profilesPassingFilters[$this->profileObj->getPROFILEID()]!=1)
							$error = "FilteredProfile";	
					}
				}
			}

			if($error)
			{
				return $error;
			}
			else
			{
				$prObj = new PhotoRequest;
				$output = $prObj->insertOrUpdate($senderProfileObj->getPROFILEID(),$this->profileObj->getPROFILEID());
				unset($prObj);
				
				if($output == 1)
				{
					$pmsObj = new ProfileMemcacheService($this->profileObj);
					$pmsObj->update("PHOTO_REQUEST",1);
					$pmsObj->update("PHOTO_REQUEST_NEW",1);
					$pmsObj->updateMemcache();
					$pmsSenderObj = new ProfileMemcacheService($senderProfileObj);
					$pmsSenderObj->update("PHOTO_REQUEST_BY_ME",1);
					$pmsSenderObj->updateMemcache();
					unset($pmsSenderObj);
					unset($pmsObj);
					try
					{
					    $instantNotificationObj = new InstantAppNotification("PHOTO_REQUEST");
					    $instantNotificationObj->sendNotification($this->profileObj->getPROFILEID(), $senderProfileObj->getPROFILEID());
					}
					catch(Exception $e)
					{
						throw new jsException($e);
					}

				}
				elseif($output == 0)
					$error = "ExceededLimit";
			}
		}
		else
			$error = "NotLogin";

		if($error)
			return $error;
		else
			return "Success";
	}
        /**
         * This function Sets SCREENED Bit of profile picture
         *@param - pictureID
         *@param - Array of different pictures with provided data  
         *@return - 1 on success or gives error message
	*/
	public function setPicProgressBit($task,$pictureArr)
        {
                foreach ($pictureArr as $picId => $valueArr) {
                        $pictureObj = new PICTURE_FOR_SCREEN_NEW();
                        $currentBit = $pictureObj->getScreenBit($picId);
                        $ordering = $currentBit["ORDERING"];
                        $currentBit = str_split($currentBit["SCREEN_BIT"]);
                        $photoTypes = array_flip(array_keys(ProfilePicturesTypeEnum::$PICTURE_SIZES));
                        $photoTypes["MainPicUrl"] = "-1";

                        if ($ordering == 0) {
                                if ($task == "FACE")
                                        $currentBit["0"] = ProfilePicturesTypeEnum::$SCREEN_BITS[$task];
                                if ($task == "PROFILEPIC_CHANGE")
                                        $currentBit["1"] = ProfilePicturesTypeEnum::$SCREEN_BITS["APPROVE"];

                                foreach ($valueArr as $key => $value) {
                                        if ($key!="OriginalPicUrl"){
                                                $currentBit[($photoTypes[$key]) + 2] = ProfilePicturesTypeEnum::$SCREEN_BITS[$task];
                                        }
                                        $pictureUrl[$key] = $value;
                                }
                        } else {
                                foreach ($valueArr as $key => $value) {
                                        if ($key!="OriginalPicUrl"){
                                                $currentBit = ProfilePicturesTypeEnum::$SCREEN_BITS[$task];
                                        }
                                        $pictureUrl[$key] = $value;
                                        
                                }
                        }
                        
                        

                        $finalUpdateArr["task"] = $task;
                        $finalUpdateArr["profileId"] = $this->profileObj->getPROFILEID();
                        $finalUpdateArr["pictureId"] = $picId;
                        
                        if($ordering == 0 && $task=="FACE")
                               $finalUpdateArr["bit"] = str_replace(ProfilePicturesTypeEnum::$SCREEN_BITS["DEFAULT"], ProfilePicturesTypeEnum::$SCREEN_BITS["EDIT"], implode("", $currentBit));
                        elseif($ordering==0)
                               $finalUpdateArr["bit"] = implode("", $currentBit);
                        else
                               $finalUpdateArr["bit"] = $currentBit;
                        
                        if ($pictureUrl && is_array($pictureUrl))
                                $finalUpdateArr["urls"] = $pictureUrl;
                        
                        $pictureObj->updateScreenBit($finalUpdateArr);
                        unset($finalUpdateArr);
                        unset($pictureUrl);
                        unset($currentBit);
                }
                return 1;
        }
	public function flushPicMemcache()
	{
		$memCacheObject = JsMemcache::getInstance();
		$memCacheObject->remove($this->profileObj->getPROFILEID() . "_THUMBNAIL_PHOTO");
	}
  protected function updateProfileCompletionScore($profileId){
    $cScoreObj = ProfileCompletionFactory::getInstance(null,null,$profileId);
    $cScoreObj->updateProfileCompletionScore();
    unset($cScoreObj);
    return true;
 }
}
?>
