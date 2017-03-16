<?php

/**
 * photoScreening actions.
 *
 * @package    jeevansathi
 * @subpackage photoScreening
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class photoScreeningActions extends sfActions {

        const DEFAULT_AVOID_REFRESH_TIME = 2;
        /**
         * Executes index action
         * *
         * @param sfRequest $request A request object
         */
        public function executeIndex(sfWebRequest $request) {
                
        }

        /**
         * This function is used to display the album of a user.
         * */
        public function executeAlbum(sfWebRequest $request) {
                if (!$profilechecksum)
                        $profilechecksum = $_GET['profilechecksum'];

                $authenticationJsObj = new JsAuthentication();
                $requestedProfileid = $authenticationJsObj->jsDecryptProfilechecksum($profilechecksum);

                //$requestedProfileid=238;
                /*
                  $profileObj = Profile::getInstance('newjs_master',$requestedProfileid);
                  $profileObj->getDetail("","","HAVEPHOTO,PHOTO_DISPLAY,USERNAME");
                 */
                $profileObj = Operator::getInstance();
                $profileObj->getDetail($requestedProfileid, "PROFILEID", "HAVEPHOTO,PHOTO_DISPLAY,USERNAME");

                $photodisplay = $profileObj->getPHOTO_DISPLAY();
                $this->USERNAME = $profileObj->getUSERNAME();
                $pictureServiceObj = new PictureService($profileObj);
                $album = $pictureServiceObj->getAlbum($contact_status);
                if ($album) {
                        $this->noOfPics = sizeof($album);
                        foreach ((array) $album as $index => $photo) {
                                $mainPicUrls[] = $photo->getMainPicUrl();
                                $title[] = $photo->getTITLE();
                                $keywords[] = $photo->getKEYWORD();
                        }
                        $keywordArr = sfConfig::get("app_social_keywords");
                        foreach ((array) $keywords as $value) {
                                $keystr = explode(",", $value);
                                $k = '';
                                if ($keystr) {
                                        foreach ((array) $keystr as $val) {
                                                if ($k == '')
                                                        $k.=$keywordArr[$val - 1];
                                                else {
                                                        $k.=", ";
                                                        $k.=$keywordArr[$val - 1];
                                                }
                                        }
                                        $keywordsStr[] = $k;
                                } else
                                        $keywordsStr[] = '';
                        }
                }
                $this->mainPicUrls = $mainPicUrls;
                $this->titleArr = $title;
                $this->keywords = $keywordsStr;
                if (!$album) {
                        echo "ERROR";
                        die;
                }
        }

        public function executeLogin(sfWebRequest $request) {
                $name = $request->getAttribute('name');
                include($_SERVER['DOCUMENT_ROOT'] . "/jsadmin/connect.inc"); //for login()
                $arr = $request->getParameterHolder()->getAll();
                $id = $arr['username'];
                $pass = $arr['password'];
                $cid = login($id, $pass);
                $domain = sfConfig::get("app_site_url");
                $this->redirect("$domain/jsadmin/mainpage.php?name=$name&cid=$cid");

//      if($f and $f!=-1)
//              echo "logged in";
                die;
        }

        /**
         * This function is used to display the profile to be screened by the screening user
         * */
        public function executeScreen(sfWebRequest $request) {
                $this->cid = $request->getParameter("cid");
                $name = $request->getAttribute('name');
                $this->source = $request->getParameter('source');
                $this->execName = $name;
                
                //Initialize Enum Array
                $interfaceArr=ProfilePicturesTypeEnum::$INTERFACE;
                 
                // Memcache for Refresh
                //photoScreeningService::avoidRefresh($name,$interfaceArr["1"],$_GET['skipMemcache'],self::DEFAULT_AVOID_REFRESH_TIME);
                
                $arr = $request->getParameterHolder()->getAll();
                
                //Allotment
                $obj = new PhotoScreenProfileAllotmentFactory();
                $allotParam=array("SOURCE"=>$this->source,"INTERFACE"=>$interfaceArr["1"],"NAME"=>$name);
		if($request->getParameter('profileId'))
                        $allotParam["PROFILEID"] = $request->getParameter('profileId');
                
                $profileAllotedObj = $obj->getAllot($allotParam);
                
                //switch Interface in case of switching
                $profileDetails = $profileAllotedObj->getProfileToScreen($allotParam,$request->getParameter('switchProfile'));
                if($profileDetails=="alreadyAlloted"){//If no Profiles is already alloted for screening
                       $this->alreadyAlloted = 1;
                       $this->marked = 1;
                       $this->setTemplate('markPhotosForScreening');
                }
                elseif(!is_array($profileDetails)){//If no Profiles found to be screened
                       $this->noProfileFound = 1;
                       $this->setTemplate('showPhotosToScreen');
                }
                else{
                        //Get Pictures To Screen
                        $this->mailid = $profileDetails["profileData"]["PROFILEID"]; //CHANGE
                        $photoDataObj = new photoScreeningService($profileDetails["profileObj"]);
                        $paramArr["interface"] = $interfaceArr["1"];
                        $photoData = $photoDataObj->getPicturesToScreen($paramArr);
                        //Show in Template 
                        $this->photoArr = $photoData;
                        $this->profileData = $profileDetails["profileData"];
                        if (($this->source!=PictureStaticVariablesEnum::$SOURCE["MASTER"] && !$photoData["nonScreened"] && !$photoData["profilePic"]) || ($this->source==PictureStaticVariablesEnum::$SOURCE["MASTER"] && !$photoData["screened"] && !$photoData["nonScreened"] && !$photoData["profilePic"])) { //if no profiles are under screening, show the message that no profiles found
                                $this->noPhotosFound = 1;
                                $arrDevelopersEmail = PictureStaticVariablesEnum::$arrPHOTO_SCREEN_DEVELOPERS;
                                JsTrackingHelper::sendDeveloperTrackMail($arrDevelopersEmail,"No Photo Found for ".$profileDetails["profileData"]["PROFILEID"]." USERNAME ".$profileDetails["profileData"]["USERNAME"]);
                               //$profileAllotedObj->reNewProfileForPreprocess($profileDetails["profileData"]["PROFILEID"]);
                                $photoDataObj->skipProfile($profileDetails["profileData"]["PROFILEID"],"","Skipped for refresh issue",0,1);
                                $this->redirect(JsConstants::$siteUrl."/operations.php/photoScreening/screen?name=".$name."&cid=".$this->cid."&source=".$this->source);

                        }
                        
                         if(PictureFunctions::IfUsePhotoDistributed($profileDetails["profileData"]["PROFILEID"]))
                        {
                                $matchToBeArr = JsConstants::$photoServerShardingEnums;

                                $arr = $photoData["profilePic"]["profileType"];
                                $mainPic = $photoData["profilePic"]["mainPicUrl"]["url"];

                                /***/
                                foreach($matchToBeArr as $k=>$v)
                                {
                                        if($mainPic)
                                        {
                                                if(strstr($mainPic,$v))
                                                {
                                                        $finalArr[] = $v;
                                                        $l1 = $v;
                                                }
                                        }
                                }

                                if($photoData["nonScreened"])
                                {
                                        foreach($photoData["nonScreened"] as $kk=>$vv)
                                        {
                                                foreach($matchToBeArr as $k=>$v)
                                                {
                                                        if(strstr($vv["url"],$v))
                                                        {
                                                                if(is_array($finalArr) && in_array($v,$finalArr))
                                                                        ;
                                                                else
                                                                        $finalArr[] = $v;
                                                        }
                                                }
                                        }
                                }
                                if(count($finalArr)==1)
                                        $this->imageCopyServer = "/".$finalArr[0];
                                else
                                {
                                        $this->imageCopyServer = "/".$l1;
                                }


											}
												$this->setTemplate('showPhotosToScreen');
                }
                
                
        }
	
	public function executeScreenPhotosFromMail(sfWebRequest $request)
        {
                $this->cid = $request->getParameter("cid");
                $name = $request->getAttribute('name');
                $this->source = $request->getParameter('source');
                $this->execName = $name;
                $obj = new PhotoScreenProfileAllotmentFactory();
                $allotParam=array("SOURCE"=>$this->source,"NAME"=>$name);
                $mailAllotedObj = $obj->getAllot($allotParam);
                $profileDetails = $mailAllotedObj->getProfileToScreen($allotParam);
                if(!is_array($profileDetails))
                {
                        $this->noProfileFound = 1;
			$this->setTemplate('showPhotosToScreen');
                }
                else
                {
			if($profileDetails[0]=='assigned')
			{
				$this->profileDataKeys = implode("**",array_keys($profileDetails[1])); //done to pass data again to the page if username is entered wrongly
                		$this->profileData = implode("**",$profileDetails[1]); //done to pass data again to the page if username is entered wrongly
			}
			$this->SENDER = $profileDetails[1]['SENDER'];
                	$this->SUBJECT = $profileDetails[1]['SUBJECT'];
                	$this->MESSAGE = $profileDetails[1]['MESSAGE'];
			$this->setTemplate('markPhotosForScreening');
                }
        }


        /**
         * This function is used to check whether the username entered is valid.
         * If its valid, then the user is redirected to the page where album of the 'username' entered is ahown for screening.
         * If its not valid, then the user is retained on the same page and is asked to enter the username again.
         * */
        public function executeSendMailPhotosToScreen(sfWebRequest $request) {
		$name = $request->getAttribute('name');
		$this->source = $request->getParameter("source");

		$this->cid = $request->getAttribute("cid");
		$username = $request->getParameter("username");

		$formArr = $request->getParameterHolder()->getAll();
		
		if ($this->source == 'mail') {
			$this->profileDataKeys = $formArr['profileDataKeys'];
			$this->profileData = $formArr['profileData'];
			$this->profileDataKeys = explode("**", $this->profileDataKeys);
			$this->profileData = explode("**", $this->profileData);
			$this->SENDER = $this->profileData[0];
			$this->SUBJECT = $this->profileData[1];
			$this->MESSAGE = $this->profileData[2];
			foreach ($this->profileDataKeys as $k => $v) {
				if ($v == 'ID') {
				$ID = $this->profileData[$k];
				$this->mailid = $this->profileData[$k];
			}
		}
		$this->profileDataKeys = implode("**", $this->profileDataKeys);
		$this->profileData = implode("**", $this->profileData);
		}
		if($formArr['Delete'])
		{
			$photoScreeningServiceObj = new photoScreeningService;
			$photoScreeningServiceObj->skipProfile("",$ID ,"Deleted from Mail",1,0);
			$this->messageFlag = 1;
                        $this->setTemplate('outputTemplate');

		}
		else
		{
			$this->profile = Operator::getInstance();
			$this->profile->getDetail($username, 'USERNAME', 'PROFILEID');
			if ($this->profile->getPROFILEID() == NULL || $this->profile->getPROFILEID() == '') { //if invalid username
				$this->incorrectUser = 1;
				$this->setTemplate('markPhotosForScreening');
			}
			else
			{ //if valid username was entered and profileid is obtained
				$profileid = $this->profile->getPROFILEID();
				$photoScreenObj = new photoScreeningService($this->profile);
				$photoScreenObj->movePhotosFromMailToNonScreened($name,$ID);
				$this->messageFlag = 1;
				$this->setTemplate('outputTemplate');
			}
		}

        }

        /**
         * This function is used to display the page for 'master photo edit'.
         * It asks the user to enter the username for which he wants to edit the album.
         * */
        public function executeMasterPhotoEdit(sfWebRequest $request) {
                $this->cid = $request->getAttribute("cid");
                $this->marked = 1;
                $this->source = 'master';
                $this->setTemplate('markPhotosForScreening');
        }

        /**
         * This function is used to display the page for screening skipped photos.
         * */
        public function executeSkippedProfileScreen(sfWebRequest $request) {
                $this->cid = $request->getAttribute("cid");
                $this->profileid = $_GET['pid'];
		$this->val = $_GET['val'];
		$this->from = $_GET['from'];
                if ($_GET['val'] == 'new') {
                        $this->profile = Operator::getInstance();
                        $this->profile->getDetail($this->profileid, "PROFILEID", "HAVEPHOTO");
                        $havephoto = $this->profile->getHAVEPHOTO();
                        if ($havephoto == 'U')
                                $source = 'new';
                        elseif ($havephoto == 'Y')
                                $source = 'edit';
//		elseif($havephoto == 'N' || $havephoto == '')
                }
                elseif ($_GET['val'] == 'mail') {
                        $source = 'mail';
                        $mailid = $_GET['mailid'];
                }
		$photoScreeningServiceObj = new photoScreeningService();
		$interface = $photoScreeningServiceObj->photoScreeningProfileStatus($this->profileid);
		if(PictureStaticVariablesEnum::$PICTURE_STATUS[$interface]=="PROCESS_QUEUE")
		{
			if(PictureFunctions::IfUsePhotoDistributed($this->profileid))
			{
				if($this->profileid)
					$this->imageCopyServer = IMAGE_SERVER_ENUM::getImageServerEnum($this->profileid,'withSlash');
			}
			$this->redirect(sfConfig::get("app_site_url").$this->imageCopyServer."/operations.php/photoScreening/processInterface?cid=$this->cid&name=$name&source=master&actualSource=skipped&profileId=$this->profileid&skipMemcache=1");
		}
		elseif(PictureStaticVariablesEnum::$PICTURE_STATUS[$interface]=="FACE_CRON_COMPLETED")
	                $this->redirect(sfConfig::get("app_site_url") . "/operations.php/photoScreening/screen?cid=$this->cid&name=$name&source=master&actualSource=skipped&profileId=$this->profileid&skipMemcache=1");
		else
		{
			$this->preprocessing = 1;
		}
        }

        public function executeSkipProfile(sfWebRequest $request) {
                $this->cid = $request->getAttribute("cid");
                $photoScreeningServiceObj = new photoScreeningService;
                $formArr = $request->getParameterHolder()->getAll();
                $this->source = $formArr['source'];
		$this->interface = $formArr['interface'];
                $photoScreeningServiceObj->skipProfile($formArr['profileid'], $formArr['mailid'], $formArr['comments'], $formArr['mail'], $formArr['comp']);
        }

        public function executeGetAlbum(sfWebRequest $request) {
                $this->cid = $request->getAttribute("cid");
                $this->profileid = $request->getParameter("profileid");
                $photoScreeningServiceObj = new photoScreeningService;
                $userData = $photoScreeningServiceObj->getAlbum($this->profileid);
                $this->username = $userData['USERNAME'];
                $this->gender = $userData['GENDER'];
                $album = $userData['ALBUM'];

                $userData['ALBUM'] = NULL;
                $this->profileData = $userData;
		if(PictureFunctions::IfUsePhotoDistributed($this->profileid))
		{
			if($this->profileid)
				$this->imageCopyServer = IMAGE_SERVER_ENUM::getImageServerEnum($this->profileid,'withSlash');
		}

                if ($album) {
                        foreach ($album as $photo) {
                                if ($photo->getPictureType() == 'S') {
                                        $screenedPhotoUrl[] = $photo->getMainPicUrl();
                                        $screenedTitle[] = $photo->getTITLE();
                                        $screenedPictureId[] = $photo->getPICTUREID();

                                        if ($photo->getOrdering() == 0) {
                                                $this->screenedProfilePicture = $photo->getProfilePicUrl();
                                                $this->screenedThumbnail = $photo->getThumbailUrl();
                                                $this->screenedProfilePicId = $photo->getPICTUREID();
                                        }
                                } elseif ($photo->getPictureType() == 'N') {
                                        $nonscreenedPhotoUrl[] = $photo->getMainPicUrl();
                                        $nonscreenedTitle[] = $photo->getTITLE();
                                        $nonscreenedPictureId[] = $photo->getPICTUREID();
                                        if ($photo->getOrdering() == 0) {
                                                $this->nonscreenedProfilePicture = $photo->getProfilePicUrl();
                                                $this->nonscreenedThumbnail = $photo->getThumbailUrl();
                                                $this->nonscreenedProfilePicId = $photo->getPICTUREID();
                                        }
                                }
                        }

                        $this->screenedTitle = $screenedTitle;
                        $this->nonscreenedTitle = $nonscreenedTitle;
                        $this->screenedPicUrl = $screenedPhotoUrl;
                        $this->nonscreenedPicUrl = $nonscreenedPhotoUrl;
                        $this->screenedPicId = $screenedPictureId;
                        $this->nonscreenedPicId = $nonscreenedPictureId;

                        $this->search = 1;
                } else {
                        $this->noPhotosFound = 1;
                }

                $this->setTemplate('showPhotosToScreen2');
        }

        /**
         * This function is used to perform the upload action when the screening user presses the Upload button.
         **/
        public function executeMasterPhotoEditSubmit(sfWebRequest $request) {
               $formArr = $request->getParameterHolder()->getAll();
                $this->cid = $request->getAttribute("cid");
                $this->source = $formArr['source'];
                $this->marked = 1;
                $username = $formArr['username'];
                $name = $request->getAttribute("name");
                
                //Getting Profile
                $this->profile = Operator::getInstance();
                $profileInfo = $this->profile->getDetail($username, 'USERNAME', 'PROFILEID');
                
                if(!$profileInfo["PROFILEID"])
                        $this->incorrectUser = 1;
                else { 
                        //Getting Album Info
                        $pictureServiceObj = new PictureService($this->profile);
                        $album = $pictureServiceObj->getAlbum("album");

                        if (!$album)
                                $this->noPhotoExist = 1;
                        else
                        {
                                $photoScreeningServiceObj = new photoScreeningService();
                                $status=$photoScreeningServiceObj->pictureScreenStatus($profileInfo["PROFILEID"]);
                                $edit = 0; 
                                
                                if(!$status)
                                        $this->redirect(JsConstants::$siteUrl."/operations.php/photoScreening/screen?name=".$name."&cid=".$this->cid."&source=".$this->source."&profileId=".$profileInfo["PROFILEID"]);
                                else{
                                        foreach($status as $picturId=>$status){
                                                if($status["STATUS"]==array_flip(PictureStaticVariablesEnum::$PICTURE_STATUS)["UPLOAD_COMPLETED"] || $status["STATUS"]==array_flip(PictureStaticVariablesEnum::$PICTURE_STATUS)["RESIZE_CRON_COMPLETED"]){
                                                       $this->preprocessing = 1;
                                                       break;
                                                       $edit = 0; 
                                                }
                                                elseif($status["STATUS"]==array_flip(PictureStaticVariablesEnum::$PICTURE_STATUS)["PROCESS_QUEUE"]){
                                                       $edit = 1; 
                                                }
                                                elseif($status["STATUS"]!=array_flip(PictureStaticVariablesEnum::$PICTURE_STATUS)["DECISION_DONE"]){
                                                       $this->redirect(JsConstants::$siteUrl."/operations.php/photoScreening/screen?name=".$name."&cid=".$this->cid."&source=".$this->source."&profileId=".$profileInfo["PROFILEID"]); 
                                                }
                                                
                                        }
                                        if($edit == 1 && $this->preprocessing!=1){
							if(PictureFunctions::IfUsePhotoDistributed($profileInfo["PROFILEID"]))
							{
								if($profileInfo["PROFILEID"])
									$this->imageCopyServer = IMAGE_SERVER_ENUM::getImageServerEnum($profileInfo["PROFILEID"],'withSlash');
							}
                                                        $this->redirect(JsConstants::$siteUrl.$this->imageCopyServer."/operations.php/photoScreening/processInterface?name=".$name."&cid=".$this->cid."&source=".$this->source."&profileId=".$profileInfo["PROFILEID"]); 
                                                }
					elseif($this->preprocessing!=1){
 						$this->redirect(JsConstants::$siteUrl."/operations.php/photoScreening/screen?name=".$name."&cid=".$this->cid."&source=".$this->source."&profileId=".$profileInfo["PROFILEID"]);
                                        }
                                        
                                }
                        }
                }
                
                $this->setTemplate('markPhotosForScreening');
                
                
        }

        /**
         * This function is used to perform the upload action when the screening user presses the Upload button.
         **/
        public function executeUploadScreeningAction(sfWebRequest $request) {
                $formArr = $request->getParameterHolder()->getAll();
                $this->cid = $request->getAttribute("cid");
                $this->profileid = $formArr['profileid'];
                $this->source = $formArr['source'];
                $this->username = $formArr['username'];
                $this->emailAdd = $formArr['emailAdd'];
                $name = $request->getAttribute("name");
                $this->master = 0;

		/*may no needed now **/
		/*
		if($formArr["copyImages"])
		{
			$copyImagesArr = explode(",",$formArr["copyImages"]);
			foreach($copyImagesArr as $k=>$v)
			{
				$ttt = explode("uploads",$v);
				if($ttt[1])
				{
					$ttt0 = explode("?",$ttt[1]);
					$ttt1 = JsConstants::$docRoot."/uploads".$ttt0[0];
					copy($v,$ttt1);
				}
			}
		}
		*/
                if ($formArr['Skip']) {   //If User presses skip
                        $this->mailid = $formArr['mailid'];
                        $this->setTemplate('skipComments');
                        $this->comp = 1;
                } elseif ($formArr['Submit']) {  //If user presses Upload
                        $profileObj = Operator::getInstance("", $formArr["profileid"]);
                        $profileObj->getDetail($formArr["profileid"], 'PROFILEID', 'USERNAME,HAVEPHOTO,GENDER,AGE');    
                        if ($this->source == PictureStaticVariablesEnum::$SOURCE["MASTER"]) {
                                $this->master = 1;
                        }
                        
                        $photoScreeningServiceObj = new photoScreeningService($profileObj);
                        $picture = $photoScreeningServiceObj->getFinalScreenedArray($formArr);
                        if(is_array($picture["rotate"]) && count($picture["rotate"])>0)
                                $photoScreeningServiceObj->rotationOfImage($picture["rotate"]);
                        if(is_array($picture["watermark"]) && count($picture["watermark"])>0)
                                $photoScreeningServiceObj->saveWatermarkDecision($picture["watermark"]);
                        
                        if (is_array($picture)) {//if Final Array of approval is returned
                                $paramArr = $photoScreeningServiceObj->prepareParameter("UPDATE", $name, $formArr, $picture); // Data Required for Update,tracking & notification Functions
                                //Pic Data for tracking 
                                $picDataForTracking = $photoScreeningServiceObj->pictureScreenStatus($formArr["profileid"]);
                                if ($photoScreeningServiceObj->checkMaxPhotoCountError($paramArr) == 1) // 1 means process- NO ERROR-dont worry
                                { 
                                        if($picture["approvedCount"]+count($picture["DELETE"])+$picture["EDIT"]>0)
                                        {
                                                $isProfileScreened=1;
                                                $paramArr=$photoScreeningServiceObj->saveDecisionStatus($paramArr);
                                        }
                                        else{
                                                $picture["EDIT"]=1;
                                                $isProfileScreened=0;
                                        }
                                }
                                else
                                        $response = "Max Photo Count error- delete Some photos";
                                // update editing count in case of setting another pic as profile pic
                                if ($photoScreeningServiceObj->isProfileScreened() == 1 && $isProfileScreened==1) {//Check Status of All pics of Profile
                                        // Current Approved & deleted Array
                                        $nonScreenedObj = new NonScreenedPicture();
                                        $statusArr = $nonScreenedObj->profilePictureStatusArr($this->profileid);
                                        
                                        // Moving
                                        $moveArr = array("DELETE" => $statusArr["DELETED"], "APPROVED" => $statusArr["APPROVED"], "ProfilePicId" => $statusArr["ProfilePic"], "TYPE" => "N", "DELETE_REASON" => $paramArr["DELETE_REASON"]);
                                        $photoScreeningServiceObj->moveImageAfterScreened($moveArr);
                                        $success = "Screening Done";
                                        
                                        //Deletion Reason
                                        if($statusArr["DELETED"]){
                                                $DeleteNonScreenedObj = new PICTURE_DELETE_NEW;
                                                $statusArr["REJECT_REASON"] = $DeleteNonScreenedObj->getDeletionReason($statusArr["DELETED"]["0"]);
                                        }
                                        else
                                                $statusArr["REJECT_REASON"]="";
                                        
                                        //Notification
                                        $notifyParamArr = $photoScreeningServiceObj->prepareParameter("NOTIFY", $statusArr); // Data Required for Update,tracking & notification Functions
                                        $notifyObj = new JsPhotoScreen_Notify($notifyParamArr);
                                        $notifyObj->notifyUser();
                        
                                } else { 
                                        if (count($paramArr["DELETE"]) > 0) {
                                                $DeleteArr = array("TYPE" => "N", "PROFILEID" => $this->profileid, "PICTUREID" => $paramArr["DELETE"], "DELETE_REASON" => $paramArr["DELETE_REASON"]);
                                                $photoUpdateObj = new DeletedPictures();
                                                $pictureUpdate = $photoUpdateObj->insertDeletedPhotoDetails($DeleteArr);
                                                $response = "Approval Done & waiting for Editing";
                                        }
                                        if($this->master==1 && ($picture["EDIT"]>0 || $photoScreeningServiceObj->isProfileScreened() == 0) && ($formArr["havePhotoValue"]=="Y" && $formArr["set_profile_pic"]))
                                                $this->master=2;                                                
                                } 
                                if (count($picture["screenedPicToDelete"]) > 0) {
                                        $DeleteArr = array("PROFILEID" => $this->profileid, "PICTUREID" => $picture["screenedPicToDelete"]);
                                        //$photoUpdateObj = new DeletedPictures();
                                        //$pictureUpdate = $photoUpdateObj->trackDeletedPhotoDetails($DeleteArr);
                                        
                                        $pictureServiceObj = new PictureService($profileObj);
                                        $profileObj->getDetail($this->profileid, "PROFILEID", "PROFILEID");
                                        foreach($picture["screenedPicToDelete"] as $key=>$pictureId){
                                                $pictureServiceObj->deletePhoto($pictureId,$this->profileid);
                                        }
                                        
                                }
	                        //TRACKING
        	                if($picDataForTracking){
                	            $trackParamArr = $photoScreeningServiceObj->prepareParameter("TRACK", $name, $formArr, $picture, $picDataForTracking); // Data Required for Update,tracking & notification Functions
                        	    $trackingObj = new JsPhotoScreen_TrackingManager($trackParamArr);
	                        }
                        
        	                if(count($picture["screenedPicToDelete"])==0 && !$picDataForTracking && $isProfileScreened==0 && count($paramArr["DELETE"])==0)
                	        {
                        	    $response = "Error - Please perform some action";
                        	}
	                        if($this->master==2)
				{
					if(PictureFunctions::IfUsePhotoDistributed($profileInfo["PROFILEID"]))
					{
						if($profileInfo["PROFILEID"])
							$this->imageCopyServer = IMAGE_SERVER_ENUM::getImageServerEnum($profileInfo["PROFILEID"],'withSlash');
					}
					$this->redirect(JsConstants::$siteUrl.$this->imageCopyServer."/operations.php/photoScreening/processInterface?name=".$name."&cid=".$this->cid."&source=master&profileId=".$this->profileid); 
				}
                        } elseif(!$response)
                                $response = "Error - Deleted photo is selected as Profile Pic";

                        
                } else
                        $response = "Error- Submission Error";

                // Display Error or Success Message
                if ($response) {
                        $this->messageFlag = 0;
                        $this->errMessage = $response;
                } else
                        $this->messageFlag = 1;
                if (!$formArr['Skip'])
                        $this->setTemplate('outputTemplate');
        }

        /**
         * This function is called when the backend user presses the Submit button on the page where he selects the reason for deletion.
         * */
        public function executeSendMail(sfWebRequest $request) {
                $formArr = $request->getParameterHolder()->getAll();
                $this->cid = $request->getAttribute("cid");
                $this->profileid = $formArr['profileid'];
                $this->source = $formArr['source'];
                $this->username = $formArr['username'];
                $this->deletedPhotos = $formArr['deletedPhotos'];
                $this->approvedPhotos = $formArr['approvedPhotos'];
                $this->totalPhotos = $formArr['totalPhotos'];
                $this->actualCountOfPics = $formArr['actualCountOfPics'];
                $this->userType = $formArr["havePhotoValue"];
                $this->emailAdd = $formArr['emailAdd'];

                if (!$formArr["deleteReason"]) {
                        $this->errMessage = "true";
                        $this->setTemplate('deleteComments');
                } else {
                        $deleteReasonStr = implode(" or ", $formArr["deleteReason"]);
                        if ($this->totalPhotos) {
                                if ($this->totalPhotos == $this->deletedPhotos) {
                                        $params_arr = array($deleteReasonStr);
                                        $this->executeMailers("case3", $params_arr, $formArr["profileid"]);
                                        $this->executeSMS($formArr["profileid"], "rejected", $this->userType);
                                } else {
                                        $params_arr = array($this->totalPhotos, $this->approvedPhotos, $this->deletedPhotos, $deleteReasonStr, $this->actualCountOfPics);
                                        $this->executeMailers("case2", $params_arr, $formArr["profileid"]);
                                        $this->executeSMS($formArr["profileid"], "accepted", $this->userType);
                                }
                        }
                        $this->messageFlag = 1;
                        $this->setTemplate('outputTemplate');
                }
        }

        /**
         * This function is used to send mails.
         * */
        public function executeMailers($case, $params_arr, $profileid) {
                $profileObj = Operator::getInstance();
                $profileObj->getDetail($profileid, "PROFILEID", "PROFILEID");
                $photoScreeningServiceObj = new photoScreeningService;
                $photoScreeningServiceObj->sendMailers($case, $params_arr, $profileObj);
        }

        /**
         * This function is used to send SMS.
         * */
        public function executeSMS($profileid, $msgType, $userType) {
                $sendSmsObj = new SendSms;
                $sendSmsObj->send_sms($profileid, $msgType, $userType);
        }

        /**
          This function is read the image sourceImage and send headers with new image newName
         */
        public function executeChangeImageName(sfWebRequest $request) {
                $order = $request->getParameter("order");
                $username = $request->getParameter("username");
                $Imagefile = $request->getParameter("Imagefile");

                $profileid = $request->getParameter("profileid");
                $photo = $request->getParameter("photo");

                if ($profileid && $photo)
                        $Imagefile.="&profileid=" . $profileid . "&photo=" . $photo;
                $fileName = $username . "-" . $order . ".jpg";
                $photoScreeningServiceObj = new photoScreeningService;
                $photoScreeningServiceObj->renameImageName($Imagefile, $fileName);
                die;
        }

        /**
         * This function is used to perform the upload action when the screening user presses the submit button on app photo screening interface.
         * */
        public function executeUploadAppPhoto(sfWebRequest $request) {
                $formArr = $request->getParameterHolder()->getAll();
                $this->cid = $request->getAttribute("cid");
                $this->profileid = $formArr['profileid'];
                $this->source = $formArr['source'];
                $this->username = $formArr['username'];
                $this->emailAdd = $formArr['emailAdd'];


                if ($formArr['Submit']) {              //If user presses Submit
                        $photoScreeningServiceObj = new photoScreeningService;
                        $output = $photoScreeningServiceObj->fileValidate($formArr);
                        if ($output == "Success") {
                                $output = $photoScreeningServiceObj->checkFileConstraint();     //Check size and format errors
                                if ($output == "Success") {
                                        $output = $photoScreeningServiceObj->uploadAppPhoto($formArr);
                                        if ($output == "Success") {
                                                $photoScreeningTracking = new photoScreeningTracking();
                                                if ($formArr["whichCase"] == 1 && $formArr["photoActionRadio"] == "Approve") {
                                                        $appPicStatus = "APP-APPROVED";
                                                        $approvePic = 1;
                                                        $editPic = 0;
                                                } else {
                                                        $appPicStatus = "APP-EDITED";
                                                        $approvePic = 0;
                                                        $editPic = 1;
                                                }
                                                $rec_time = $photoScreeningTracking->updateJsadmin($formArr['source'], $formArr['profileid'], '', '', '', $appPicStatus);
                                                $photoScreeningTracking->updateMis($formArr['source'], "", $formArr['profileid'], "", $formArr['username'], 0, $approvePic, 0, $editPic, $rec_time);
                                                unset($photoScreeningTracking);
                                        }
                                }
                        }
                }

                if ($output == "Success") {
                        $this->messageFlag = 1;

                        $profileObj = Operator::getInstance();
                        $profileObj->getDetail($formArr['profileid'], "PROFILEID", "PROFILEID");
                        $photoScreenedURLobj = new PictureService($profileObj);
                        $photoScreenedURL = $photoScreenedURLobj->getProfilePic();
                        if ($photoScreenedURLobj && $photoScreenedURL && $photoScreenedURL->getmobileAppPicUrl()) {
                                $this->approvedPicture = $photoScreenedURL->getmobileAppPicUrl();
                        }
                } else {
                        $this->messageFlag = 0;
                        $this->errMessage = $output;
                }
                        $this->messageFlag = 1;
                $this->setTemplate('outputTemplate');
        }

    /**
     * Executes GetDeletedPhoto action - to get username for which deleted and original photos are to be shown
     * *
     * @param sfRequest $request A request object
     */
    public function executeGetUserDeletedPlusOriginalPhotos(sfWebRequest $request) 
    {  
        $this->cid = $request->getParameter('cid');
        $this->name = $request->getParameter('name');
        $this->source = $request->getParameter('source');
        $this->execName = $this->name;
        if($request->getParameter('error')==1)
        {
            $this->error=1;
        }
        $this->setTemplate('getUsername');           
    }

    /**
     * Executes ShowDeletedPhotos action -to show deleted plus original photos of user with passed 'profileid'
     * *
     * @param sfRequest $request A request object
     */
    public function executeShowDeletedPlusOriginalPhotos(sfWebRequest $request)
    {
        $this->name = $request->getAttribute("name");
        $this->source = $request->getAttribute("source");
        $this->cid = $request->getAttribute("cid");
        $this->username = trim($request->getParameter("username"));
        $profile = Operator::getInstance();
        $profile->getDetail($this->username, 'USERNAME', 'PROFILEID');
        $this->profileId = $profile->getPROFILEID();
        if(!$this->profileId)
        {
            $error=1;
            $this->redirect(JsConstants::$siteUrl."/operations.php/photoScreening/getUserDeletedPlusOriginalPhotos?name=".$this->name."&cid=".$this->cid."&source=".$this->source."&error=".$error);
        }

        //To display deleted photos
        $pictureServiceObj = new PictureService($profile);
        $this->deletedphotoArr = $pictureServiceObj->getDeletedPhotos();
        
        //To display existing photos
        $profile->getDetail("","","HAVEPHOTO,GENDER","RAW");
        $pictureServiceObj = new PictureService($profile);
        $this->originalphotoArr = $pictureServiceObj->getAlbum();
        
        $this->setTemplate('showDeletedPlusOriginalPhotos');                 
    }
}
?>
