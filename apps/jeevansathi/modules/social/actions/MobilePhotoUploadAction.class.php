<?php

/**
 * PhotoUploadProgressAction
 *
 * @package : JSMS   
 * @subpackage : Social
 * @author   : Neha Gupta 
 */
class MobilePhotoUploadAction extends sfActions {

        public function execute($request) { 
			//echo("abc");die;
		$loggedInProfile = LoggedInProfile::getInstance('newjs_master');
                $loggedInProfile->getDetail("","","USERNAME,HAVEPHOTO,PRIVACY,PHOTO_DISPLAY");
		$this->username=$loggedInProfile->getUSERNAME();
		$this->privacy =$loggedInProfile->getPHOTO_DISPLAY();
		$this->upload = true;
		$this->gender=$loggedInProfile->getGENDER();
		if(PictureFunctions::IfUsePhotoDistributed($loggedInProfile->getPROFILEID()))
				$this->imageCopyServer = IMAGE_SERVER_ENUM::getImageServerEnum($loggedInProfile->getPROFILEID(),'1');
		
		
		$picServiceObj = new PictureService($loggedInProfile);
                if($picServiceObj->getProfilePic())
                {
						if($picServiceObj->getProfilePic()->getProfilePic235Url())
                        $this->profilepicurl= $picServiceObj->getProfilePic()->getProfilePic235Url();
                        elseif($picServiceObj->getProfilePic()->getprofilePicUrl())
                        {
							$this->profilepicurl= $picServiceObj->getProfilePic()->getprofilePicUrl();
							$this->picturecheck=1;
						}
						else
							$this->profilepicurl= $picServiceObj->getProfilePic()->getmainPicUrl();
                        $album = $picServiceObj->getAlbum();
                }

		$album = $picServiceObj->getAlbum();
		if($request->getParameter('selectFile')==1)
			$this->selectFileOrNot = $request->getParameter('selectFile');
		else
			$this->selectFileOrNot = 0;
		if(is_array($album))
			$this->alreadyPhotoCount = count($album);
		else
			$this->alreadyPhotoCount = 0;
			$this->selectTemplate = 0;
			
		$profileId = $loggedInProfile->getPROFILEID();
                $this->profileId = $profileId;
		$trackingObj = new PictureUploadTracking();
		$trackingObj->InsertPageTrack($profileId,"action","ALBUM_PAGE");
                       
			//echo($this->sel);die;
		$this->setTemplate("mobile/mobilePhotoUploadProgress");
        }

}

