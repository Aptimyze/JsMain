<?php

class photoScreeningService
{
        private $thumbnailX = 60;
        private $thumbnailY = 60;
        private $profilePicX = 150;
        private $profilePicY = 200;
	private $appPicX = 450;
	private $appPicY = 600;
	private $associateUploadedPhotoWithUnScreenedPictureId;
	private $associateUploadedPhotoWithScreenedPictureId;
	const SCREENING_TYPE= "P";
	const PROCESS_INTERFACE_STATUS = "E";	
        const AUTO_REMINDER_MAIL_MAX_COUNT = 2;
        public function __construct($profileObj='')
        {
                if($profileObj)
                        $this->profileObj=$profileObj;	
        }
	/**
	 * This function takes source ($source: new/edit/mail) and allotts a profile to the photo screening user($name).
	 * First it checks for the photo profiles allotted to the user, 
	 * if no profiles found then it checks for the profiles assigned to some other user for more than 1 hour 
	 * and then for the unallotted profiles. 
	 * It then assigns the profile found to the user returns the profile data and album.
	 * @return: an array containing the data to be displayed on the photo screening page (data includes profileDetails, album, attachments sent in mail)
	**/
	public function allottPhotos($source,$name)
	{
		if($source=='new' || $source=='edit')
			$admin_jprofile = new MAIN_ADMIN();
		elseif($source=='mail')
			$admin_jprofile = new SCREEN_PHOTOS_FROM_MAIL();
		elseif($source=="appPic")
			$admin_jprofile = new JSADMIN_SCREEN_PHOTOS_FOR_APP;

		if($source=='new')
			$flag='U';
		elseif($source == 'edit')
			$flag='Y';
		elseif($source == 'mail')
			$flag=''; //for enabling same function call
		elseif($source == "appPic")
			$flag = 'A';

		$userAllottedProfiles = $admin_jprofile->userAllottedProfiles($flag,$name); //gets new/edit/mail photo profile that is allotted to the user but whose screening hasnt been done
		if($userAllottedProfiles && $userAllottedProfiles['PROFILEID']) //profile is assigned to a screening user but screening is not done.
		{
			$this->id = $userAllottedProfiles['MAILID'];
			$details=$this->showAllottedProfile($userAllottedProfiles['PROFILEID'],$source);
		}
		elseif($userAllottedProfiles) //called when $source=mail: profile is assigned to a screening user but profileid is not associated with this assignment.
		{
			$details[0]='assigned';
			$mailDetails = new PHOTOS_FROM_MAIL();
			$details[1] = $mailDetails->getMailDetails($userAllottedProfiles['MAILID']);
			$this->id = $userAllottedProfiles['MAILID'];
		}
		else
		{
			//Get A Lock
			$lockingObj = new LockingService;
			$key = $lockingObj->semgetLock(1234);
			//Get A Lock

			$allottedProfiles = $admin_jprofile->allottedProfiles($flag);//gets a profile which was allotted to some user atleast 30 min back and hasn't been screened yet.
			if($allottedProfiles)
			{
				if($source == 'new' || $source == 'edit' || $source == 'appPic')
				{
					$this->id=$allottedProfiles['PROFILEID'];
				}
				elseif($source == 'mail')
				{
					$this->id=$allottedProfiles['MAILID'];
				}
				$admin_jprofile->reallotProfile($name,$this->id);//assign profile to a screening user by updating alloted date and username.

				//Release Lock                  
				$lockingObj->semreleaseLock($key);
				//Release Lock   

				if($allottedProfiles['PROFILEID'])
					$details=$this->showAllottedProfile($allottedProfiles['PROFILEID'],$source);
				elseif($allottedProfiles)
				{
					$details[0]='assigned';
					$mailDetails = new PHOTOS_FROM_MAIL();
					$details[1] = $mailDetails->getMailDetails($this->id);
				}
			}
			else
			{
				//Release Lock                  
				$lockingObj->semreleaseLock($key);
				//Release Lock   

				//Get A Lock
				$lockingObj = new LockingService;
				$key2 = $lockingObj->semgetLock(5678);
				//Get A Lock

				$newProfile = $admin_jprofile->unallottedProfiles($flag);
				$screen_time = sfConfig::get("app_screentime");

				if($newProfile && ($source == 'new' || $source == 'edit' || $source == 'appPic'))
				{
					$receivetime=$newProfile['PHOTODATE'];
					$this->id=$newProfile['PROFILEID'];
					$username=$newProfile['USERNAME'];
					$details=$this->showAllottedProfile($this->id,$source);
				}
				elseif($newProfile && $source == 'mail')
				{
					$this->id = $newProfile['ID'];
					$receivetime=$newProfile['DATE'];
					$details[0]='assigned';
					$mailDetails = new PHOTOS_FROM_MAIL();
					$details[1] = $mailDetails->getMailDetails($this->id);
				}
				else
				{
					$details[0] = "noProfileFound";
					return $details;
				}
				$submittime=timeFunctions::newtime($receivetime,0,$screen_time,0);
				$admin_jprofile->allotProfile($this->id,$username,$receivetime,$submittime,$name);

				//Release Lock                  
				$lockingObj->semreleaseLock($key2);
				//Release Lock   

			}
		}
		$details[5]=$profileid;
		$details[6]=$this->id;
		return $details;
	}

	/**
	 * This function is used to return the username, gender, havephoto and album of a user (including both screened and non screened photos).
	 * @param $profileid - the profile id whose album is to be returned.
	 * @return $userData - the USERNAME, GENDER, HAVEPHOTO and album of the user.
	**/	
	public function getAlbum($profileid)
	{
		$profileObj = Operator::getInstance('newjs_master',$profileid);
		$profileObj->getDetail("","","USERNAME,GENDER,HAVEPHOTO");
		$userData['USERNAME']=$profileObj->getUSERNAME();
		$userData['GENDER']=FieldMap::getFieldLabel('gender',$profileObj->getGENDER());
		$pictureServiceObj=new PictureService($profileObj);
		$userData['ALBUM']= $pictureServiceObj->getAlbum();
		return $userData;
	}

	/**
	 * This function is used to get a profile's album and details that are to be shown on the photo screening page.
	 * @param: $source - source from which screening is being done: new/edit/mail.
	 * @param: $profileid - id of the profile for which screenign is to be done.
	 * @return: an array containing the data to be displayed on the photo screening page (data includes profileDetails, album)
	**/
	public function showAllottedProfile($paramArr)
	{       
		$profileObj = Operator::getInstance('newjs_master',$paramArr["PROFILEID"]);
		$profileObj->getDetail("","","USERNAME,GENDER,AGE,COUNTRY_RES,CITY_RES,MSTATUS,RELIGION,CASTE,COUNTRY_BIRTH,CITY_BIRTH,HAVEPHOTO,PHOTO_DISPLAY,MTONGUE,EMAIL,SUBSCRIPTION");

		$profileData['PROFILEID']=$paramArr["PROFILEID"];
		$profileData['USERNAME']=$profileObj->getUSERNAME();
		$profileData['GENDER']=FieldMap::getFieldLabel('gender',$profileObj->getGENDER());
		$profileData['MTONGUE']=FieldMap::getFieldLabel('community',$profileObj->getMTONGUE());
		$profileData['AGE']=$profileObj->getAGE();
		$profileData['COUNTRY_RES']=FieldMap::getFieldLabel('country',$profileObj->getCOUNTRY_RES());
		$profileData['CITY_RES']=FieldMap::getFieldLabel('city',$profileObj->getCITY_RES());
		$profileData['MSTATUS']=FieldMap::getFieldLabel('mstatus',$profileObj->getMSTATUS());
		$profileData['RELIGION']=FieldMap::getFieldLabel('religion',$profileObj->getRELIGION());
		$profileData['CASTE']=FieldMap::getFieldLabel('caste',$profileObj->getCASTE());
		$profileData['COUNTRY_BIRTH']=FieldMap::getFieldLabel('country',$profileObj->getCOUNTRY_RES());
		$profileData['CITY_BIRTH']=FieldMap::getFieldLabel('city',$profileObj->getCITY_BIRTH());
		$profileData['EMAIL']=$profileObj->getEMAIL();
		$profileData['HAVEPHOTO']=$profileObj->getHAVEPHOTO();

		if($profileObj->getSUBSCRIPTION()!='')
			$profileData['USERPAID']=1;
		else
			$profileData['USERPAID']=0;
		
                $details["profileData"]=$profileData;
                $details["profileObj"]=$profileObj;
                
                return $details;
	}

	/**
	 * This function is used to delete a profile's entries from newjs.PICTURE_FOR_SCREEN_NEW.
	 * @param: $picIdArr - array of picture ids whose entries need to be removed.
	**/
	public function deleteEntriesFromScreening($picIdArr)
	{
		$picIds = implode(',',$picIdArr);
		$picture_for_screen_new=new NonScreenedPicture();
		$picture_for_screen_new->deleteRowsBasedOnPicId($picIds);
	}

	/**
	 * This function is used to delete a profile's entries from newjs.PICTURE_NEW.
	 * @param: $picIdArr - array of picture ids whose entries need to be removed.
	**/
	public function deleteScreenedPhotoEntries($picIdArr)
	{
		$picIds = implode(',',$picIdArr);
		$picture_new=new ScreenedPicture;
		$picture_new->deleteRowsBasedOnPicId($picIds);
	}

	/**
	 * This function is used to update the column 'ordering' for a profile's entries in newjs.PICTURE_NEW.
	 * @param: $profileid - profile id whose ordering needs to be updated.
	**/
	public function updateScreenedPhotosOrdering($profileid)
	{
		$picture_new=new ScreenedPicture();
		$picture_new->updateScreenedPhotosOrdering($profileid);
	}

	/**
	 * This function is used to allott a profile to a screening user in case of "master photo edit".
	 * @param: $profileid - profile id of the profile whose screening is to be done.
	 * @param: $username - username corresponding to the profileid.
	 * @param: $name - name of the screening user.
	**/
	public function allotProfile($profileid,$username,$name)
	{
		$screen_time = sfConfig::get("app_screentime");

		$admin_jprofile = new MAIN_ADMIN();
		$flag = $admin_jprofile->reallotProfile($name,$profileid);
		if($flag == 'noRowsUpdated')
		{
			$time=time();
			$now=date('Y-m-d H:i:s',$time);
			$receivetime=$now;
			$submittime=timeFunctions::newtime($receivetime,0,$screen_time,0);
			$admin_jprofile->allotProfile($profileid,$username,$receivetime,$submittime,$name);
		}

	}
	
	/**
	 * This function is used to get the column ALLOT_TIME of a profile from jsadmin.MAIN_ADMIN
	 * @param: $profileid - profileid for which the allot time is to be found.
	 * @return: $allotTime - allot time corresponding to the given profileid.
	**/
	public function getAllotTime($profileid)
	{
		$main_admin = new MAIN_ADMIN();
		$allotTime = $main_admin->getAllotTime($profileid);
		return $allotTime;
	}

	/**
	 * This function is used to skip photo screening of a profile.
	 * @param: $profileid - profileid corresponding to a profile whose screening needs to be skipped.
	 * @param: $mailid - mail id corresponding to a mail whose screening is to be skipped.
	 * @param: $comments - comments entered by a screening user giving the reason for akipping the profile.
	 * @param: $mail - flag used to identify whether the screening is done from mail or not (1 for yes, 0 for no).
	 * @param: $comp - flag used to identify whether screening is done for photos uploaded by the user (1 for yes, 0 for no).
	**/
	public function skipProfile($profileid,$mailid,$comments,$mail,$comp)
	{
		if($mail == 1)
		{
			$skipObj = new SCREEN_PHOTOS_FROM_MAIL();
			$skipObj->skipProfile($profileid,$mailid,$comments);
		}
		if($comp == 1)
		{
			$skipObj = new MAIN_ADMIN();
			$skipObj->skipProfile($profileid,$mailid,$comments);
		}
	}

	/**
	This function is used to check if the required files are selected, profile pic/thumbanil is selected, profile pic radio button is clicked, etc.
  	@param $_FILES array and all POST variables passsed in $formArr
  	@return Success if all checks are appropriates else the Error
  	*/
	public function fileValidate($formArr)
	{
		if($formArr["action"] == "uploadAppPhoto")
		{
			if($formArr["whichCase"]==1)
			{
				if($formArr["photoActionRadio"] == "Approve")
				{
					return "Success";
				}
				elseif($formArr["photoActionRadio"] == "Edit")
				{
					if($_FILES["appPhoto"]["error"] == 4)
					{
						return "Err1 - APP Photo not browsed";
					}
					elseif($_FILES["appPhoto"]["error"] == 0)
					{
						$imageDetails = getimagesize($_FILES["appPhoto"]["tmp_name"]);
						if(round($imageDetails[1]/$imageDetails[0])>($this->appPicY/$this->appPicX))
							return "Err4 - Dimensions not correct";
						else
							return "Success";
					}
					else
						return "Err3 - APP Photo defective";
				}
				else
					return "No radio button selected";
			}
			elseif($formArr["whichCase"]==2)
			{
				if($_FILES["appPhoto"]["error"] == 4)
				{
					return "Err2 - APP Photo not browsed";
				}
				elseif($_FILES["appPhoto"]["error"] == 0)
				{
					$imageDetails = getimagesize($_FILES["appPhoto"]["tmp_name"]);
                                      	if(round($imageDetails[1]/$imageDetails[0])>($this->appPicY/$this->appPicX))
						return "Err6 - Dimensions not correct";
					else
						return "Success";
				}
				else
					return "Err5 - APP Photo defective";
			}
			else
				return "Invalid Access";
		}
		else
		{
			$nonScreenPics = 0;
			$nonScreenMail = 0;
			$nonScreenMore = 0;

			if ($_FILES["uploadPhotoNonScr"])			//If non screened photos exist
			{
				foreach ($_FILES["uploadPhotoNonScr"]["name"] as $k=>$v)
				{
					if ($_FILES["uploadPhotoNonScr"]["error"][$k] == 4)	//If no file browsed.
					{
						if (strpos($formArr["picIdNonScr"][$k],"ttach")==false && strpos($formArr["picIdNonScr"][$k],"ddmore")==false) //File is not an attachment.
						{
							if (!in_array($formArr["picIdNonScr"][$k],$formArr["deletePhotoNonScr"]))
								return "Err1...Some photo/photos under the <b>NonScreened Category</b> are not browsed or deleted.";
						}
						else if (strpos($formArr["picIdNonScr"][$k],"ttach"))	//File is an attachment.
						{
							if (!in_array($formArr["picIdNonScr"][$k],$formArr["deletePhotoNonScr"]))
								return "Err2...Some photo/photos under the <b>Image Attachments Category</b> are not browsed or deleted.";
						}
					}
				}
			}

			if ($formArr["picIdNonScr"])
			{
				foreach($formArr["picIdNonScr"] as $k=>$v)	//Loop to get count of diff types of non screen pics.
				{
					if (strpos($v,"ttach"))
						$nonScreenMail = $nonScreenMail + 1;
					else if (strpos($v,"ddmore"))
						$nonScreenMore = $nonScreenMore + 1;
					else
						$nonScreenPics = $nonScreenPics + 1;
				}
			}

			if (!$formArr["set_profile_pic"])		//If profile pic is not set
			{
				if (in_array(0,$_FILES["uploadPhotoNonScr"]["error"]))			//If any non screen pic is browsed.
					return "Err12...Select atleast one pic as profile pic.";
			}
			else						//If profile pic is set.
			{
				if ($formArr["deletePhotoScr"])		//If some screened photo is deleted
				{
					if (in_array($formArr["set_profile_pic"],$formArr["deletePhotoScr"]))	//If profile pic set is also marked to delete
					{
						if (count($formArr["deletePhotoScr"]) == count($formArr["picIdScr"]) && count($formArr["deletePhotoNonScr"]) >= ($nonScreenPics + $nonScreenMail) && !in_array(0,$_FILES["uploadPhotoNonScr"]["error"]))	//If no photos are broswed and all are marked to delete
						{
							return "Success";
						}
						else				//Profile pic cannot be deleted
						{
							return "Err3...Selected profile pic cannot be deleted. Select another pic as profile pic.";
						}
					}
				}

				if ($formArr["deletePhotoNonScr"])	//If some non screened photo is deleted
				{
					if (in_array($formArr["set_profile_pic"],$formArr["deletePhotoNonScr"]))	//If profile pic is also marked to delete
					{
						if ($formArr["picIdScr"])	//If screened pics exist
						{
							if (count($formArr["deletePhotoScr"]) == count($formArr["picIdScr"]) && count($formArr["deletePhotoNonScr"]) >= ($nonScreenPics + $nonScreenMail) && !in_array(0,$_FILES["uploadPhotoNonScr"]["error"]))	//If no photos are browsed and all are marked for delete
							{
								return "Success";
							}
							else		//Profile pic cannot be deleted
							{
								return "Err4-1...Selected profile pic cannot be deleted. Select another pic as profile pic.";
							}
						}
						else		// If screened pics does not exist
						{
							if (count($formArr["deletePhotoNonScr"]) >= ($nonScreenPics + $nonScreenMail) && !in_array(0,$_FILES["uploadPhotoNonScr"]["error"]))								//If no phots are browsed and all are marked to delete.
							{
								return "Success";
							}
							else		//Profile pic cannot be deleted
							{
								return "Err4-2...Selected profile pic cannot be deleted. Select another pic as profile pic.";
							}
						}
					}
				}

				if ($formArr["source"] == "mail" || $formArr["source"] == "master")	//If its a mail or master scenario
				{
					if ($_FILES["uploadPhotoScr"])			//If screened photo exist
					{
						if ($formArr["set_profile_pic"] == $formArr["screenedProfilePicId"])		//If profile pic is not changed
						{
							if($_FILES["uploadPhotoScr"]["error"][0] == 4)		//If profile pic is not browsed
							{
								if ($_FILES["profilePic"]["error"]!=4 || $_FILES["thumbnail"]["error"]!=4)	//No cropped thumbnail or profile pic can be browsed
								{
									return "Err5...Invalid cropped profile pic/thumbnail.";
								}
							}
							else				//If profile pic is browsed
							{
								if ($_FILES["profilePic"]["error"] == 4 || $_FILES["thumbnail"]["error"] == 4)	//Cropped thumbnail and profile pic need to be browsed
								{
									return "Err6...Either cropped profile pic or thumbnail is not browsed.";
								}	
							}	
						}
						else		//If profile pic is changed
						{
							if (in_array($formArr["set_profile_pic"],$formArr["picIdScr"]))	//If new profile pic is in screened section
							{
								$profilePicIndex = array_search($formArr["set_profile_pic"],$formArr["picIdScr"]);
								$profilePicFlag = "Screened";
								if ($_FILES["uploadPhotoScr"]["error"][$profilePicIndex] == 4)	//Browsing not necessary
								{
									//return "Err7...Selected profile pic is not browsed.";
								}
							}	
							else		//If new profile pic is in non screened section
							{
								$profilePicIndex = array_search($formArr["set_profile_pic"],$formArr["picIdNonScr"]);
								$profilePicFlag = "NonScreened";
								if ($_FILES["uploadPhotoNonScr"]["error"][$profilePicIndex] == 4)	//Browsing required
								{
									return "Err8...Selected profile pic is not browsed.";
								}
							}	
							if ($_FILES["profilePic"]["error"] == 4 || $_FILES["thumbnail"]["error"] == 4)	//Cropped profile pic and thumbnail need to be browsed
							{
								return "Err9...Either cropped profile pic or thumbnail is not browsed.";
							}
						}
					}
					else			//If screened photo does not exist
					{
						$profilePicIndex = array_search($formArr["set_profile_pic"],$formArr["picIdNonScr"]);
						$profilePicFlag = "NonScreened";
						if ($_FILES["uploadPhotoNonScr"]["error"][$profilePicIndex] == 4)	//Selected profile pic need to be browsed
						{
							return "Err10...Selected profile pic is not browsed.";
						}
		
						if ($_FILES["profilePic"]["error"] == 4 || $_FILES["thumbnail"]["error"] == 4)	//Cropped thumbnail and profile pic need to be browsed
						{
							return "Err11...Either cropped profile pic or thumbnail is not browsed.";
						}
					}
				}	
				else if ($formArr["source"] == "new" || $formArr["source"] == "edit")		//If its a new or edit scenario
				{
					if ($formArr["picIdScr"])	//If screened photo exists
					{
						if ($formArr["set_profile_pic"] == $formArr["screenedProfilePicId"])	//If profile pic is not changed
						{
							if ($_FILES["profilePic"]["error"]!=4 || $_FILES["thumbnail"]["error"]!=4)	//Cropped thumbnail or profile pic cannot be browsed
							{
								return "Err13...Invalid cropped profile pic/thumbnail.";
							}
						}
						else		//If profile pic is changed
						{
							if (in_array($formArr["set_profile_pic"],$formArr["picIdScr"]))	//If new profile pic is screened
							{
							}
							else			//If new profile pic is not screened
							{
								$profilePicIndex = array_search($formArr["set_profile_pic"],$formArr["picIdNonScr"]);
								if ($_FILES["uploadPhotoNonScr"]["error"][$profilePicIndex] == 4)	//Browse the pic
								{
									return "Err14...Selected profile pic is not browsed.";
								}
		
							}
							if ($_FILES["profilePic"]["error"] == 4 || $_FILES["thumbnail"]["error"] == 4)	//Cropped profile pic and thumbanil need to be browsed
							{
								return "Err15...Either cropped profile pic or thumbnail is not browsed.";
							}
						}
					}
					else		//If screened photo does not exists
					{
						$profilePicIndex = array_search($formArr["set_profile_pic"],$formArr["picIdNonScr"]);
						if ($_FILES["uploadPhotoNonScr"]["error"][$profilePicIndex] == 4)	//Browse selected profile pic
						{
							return "Err16...Selected profile pic is not browsed.";
						}
		
						if ($_FILES["profilePic"]["error"] == 4 || $_FILES["thumbnail"]["error"] == 4)	//Cropped thumbnail and profile pic need to be browsed
						{
							return "Err17...Either cropped profile pic or thumbnail is not browsed.";
						}
		
					}
				}
			}

			if($_FILES["profilePic"]["error"]==0 && $_FILES["thumbnail"]["error"]==0)      //profilePic or thumbnail dimensions are incorrect
			{
				$img = ImageCreateFromJpeg($_FILES["profilePic"]["tmp_name"]);
				$imagesX=imagesx($img);
				$imagesY=imagesy($img);
				if($imagesX!=$this->profilePicX && $imagesY!=$this->profilePicY)
					return "Err18 profilePic Dimensions are incorect";

				$img = ImageCreateFromJpeg($_FILES["thumbnail"]["tmp_name"]);
				$imagesX=imagesx($img);
				$imagesY=imagesy($img);
				if($imagesX!=$this->thumbnailX && $imagesY!=$this->thumbnailY)
					return "Err18 thumbnail Dimensions are incorect";
			}
			return "Success";	// If no errors then return Success
		}
	}	

	/**
	This function is used to check if the number of files that are going to be uploaded dint crossed the maximum limit of 20.
  	@param all POST variables passsed in $formArr
  	@return Success if all checks are appropriates else the Error
  	*/
	public function checkMaxCount($formArr)
	{
		$picture_new = new ScreenedPicture;
                $count1 = $picture_new->getMaxOrdering($formArr["profileid"]);		//Get count of already existing screened pics
                if ($count1 != null)
                        $count1 = $count1+1;
                else
                        $count1 = 0;		

		if ($_FILES["uploadPhotoNonScr"])			//Get count of non screened pics browsed
		{
			$count4 = 0;
			foreach($_FILES["uploadPhotoNonScr"]["name"] as $k=>$v)
			{
				if ($_FILES["uploadPhotoNonScr"]["error"][$k]==0)
				{
					$count4++;
				}
			}
		}
		else
		{
			$count4 = 0;
		}

		if ($_FILES["uploadPhotoScr"])			//Get count of screened pics browsed
		{
			$count3 = 0;
			foreach($_FILES["uploadPhotoScr"]["name"] as $k=>$v)
			{
				if ($_FILES["uploadPhotoScr"]["error"][$k]==0)
				{
					$count3++;
				}
			}
		}
		else
		{
			$count3 = 0;
		}
		$actualCount = $count1 + $count3 + $count4;		//Get the actual count as the summation of the above three

		if ($actualCount>sfConfig::get("app_max_no_of_photos"))
		{
			return "Error - More than ".sfConfig::get("app_max_no_of_photos")." photos. Click on back button of browser and try again.";
		}
		else
		{
			return "Success";
		}
	}

	/**
	This function is used to check the size and type constraint on the uploaded files.
  	@return Success if all checks are appropriates else the Error
  	*/
	public function checkFileConstraint()
	{
		$errCount = 0;
		$totalCount = 0;
		$noFile = 0;

		$file_type_array = sfConfig::get("app_photo_formats_to_check");
		foreach ($_FILES as $k=>$v)
		{
			if($k == "profilePic" || $k == "thumbnail" || $k == "appPhoto")
			{
				if ($v["error"]!=4)
				{
					if (!in_array($v["type"],$file_type_array) || $v["size"]>sfConfig::get("app_max_photo_size_screen"))
					{
						return "Some photo/photos have size/format error. Please try uploading all again.";			
					}
				}
			}
			else
			{
				foreach($v["name"] as $kk=>$vv)
				{
					if ($v["error"][$kk]!=4)
					{
						if (!in_array($v["type"][$kk],$file_type_array) || $v["size"][$kk]>sfConfig::get("app_max_photo_size_screen"))
						{
							return "Some photo/photos have size/format error. Please try uploading all again.";
						}
					}
				}
			}
		}
		
		return "Success";
	}

	/**
	This function is used to upload the files, make database entry and do ordering in PICTURE_NEW. These actions are performed by separate functions.
  	@param all POST variables passsed in $formArr
  	@return Success if all checks are appropriates else the Error
  	*/
	public function saveAlbum($formArr,$picIdNonScr='',$picIdScr='')
	{
		$profilePicId == null;
		$newUpload = false;
	
		/* for updating PICTURE_DETAILS */
		if(is_array($_FILES["uploadPhotoNonScr"]["tmp_name"]))
		foreach($_FILES["uploadPhotoNonScr"]["tmp_name"] as $k=>$v)
		{
			if($v)
				$this->associateUploadedPhotoWithUnScreenedPictureId[$v] = $picIdNonScr[$k];
		}
		if(is_array($_FILES["uploadPhotoScr"]["tmp_name"]))
		foreach($_FILES["uploadPhotoScr"]["tmp_name"] as $k=>$v)
		{
			if($v)
				$this->associateUploadedPhotoWithScreenedPictureId[$v] = $picIdScr[$k];
		}
		/* for updating PICTURE_DETAILS */

		$profileObj = Operator::getInstance('newjs_master',$formArr["profileid"]);
		$pictureServiceObj=new PictureService($profileObj);

		if (is_array($formArr["picIdScr"]) && in_array($formArr["set_profile_pic"],$formArr["picIdScr"]))		//If selected pic is a screened pic
		{
			$profilePicIndex = array_search($formArr["set_profile_pic"],$formArr["picIdScr"]);
			$profilePicFlag = "Screened";
		}
		else		//If selected pic is a non screened pic
		{
			$profilePicIndex = array_search($formArr["set_profile_pic"],$formArr["picIdNonScr"]);
			$profilePicFlag = "NonScreened";
		}
		
		foreach ($_FILES as $k=>$v)
		{
			if (($k == "profilePic" || $k == "thumbnail"))
			{
			}
			else
			{
				if ($k == "uploadPhotoScr")		//If screened pics are uploaded
				{
					foreach($v["name"] as $kk=>$vv)
					{
						if ($v["error"][$kk]!=4)
						{
							$picId = $pictureServiceObj->getPictureAutoIncrementId();	//Get the new PICTURE ID
							if ($kk == $profilePicIndex && $profilePicFlag == "Screened")	//If the pic being uploaded is marked as profile pic
							{
								$profilePicId = $picId;		//Store the PICTURE ID for cropped thumbnail and profile pic
							}

							if ($v["type"][$kk] == "image/gif")		//Get the format of pic being uploaded
								$format_of_pic = "gif";
							else if ($v["type"][$kk] == "image/jpeg")
								$format_of_pic = "jpeg";
							else if ($v["type"][$kk] == "image/jpg")
								$format_of_pic = "jpg";

							$outputResponse = $this->performUpload($picId,$v["tmp_name"][$kk],"mainPic",$v["type"][$kk],$formArr["profileid"]);		//Perform file transfer
							$outputResponseArr = explode("**-**",$outputResponse);	//Get the output array

							$uploadActionOutput[] = $outputResponseArr[0];		//File Transfer PASS or FAIL
							/* for updating PICTURE_DETAILS */
							if($outputResponseArr[0]=='PASS')

							{
								$filePath = $v["tmp_name"][$kk];
								$oldPicId = $this->associateUploadedPhotoWithScreenedPictureId[$filePath];
								$photoScreeningTracking = new photoScreeningTracking;
								$photoScreeningTracking->updateImageDetails($oldPicId,$picId,$formArr["profileid"]);
							}
							/* for updating PICTURE_DETAILS */

							$MainPicUrl[] = $outputResponseArr[1];			//Main Pic Url
							$ThumbailUrl[] = null;		
							$ProfilePicUrl[] = null;
							$SearchPicUrl[] = null;
							$Thumbail96Url[] = $outputResponseArr[2];		//Thumbnail96 Url
							$PicFormat[] = $format_of_pic;		
							$dbEntryPicId[] = $picId;				//Picture Id of pic uploaded
							$dbEntryTitle[] = $formArr["titleScr"][$kk];		//Title of pic uploaded
							$dbEntryKeywords[] = null;				
							$newUpload = true;					//A new pic is uploaded
						}
						else if ($v["error"][$kk]==4 && ($formArr["set_profile_pic"] == $formArr["picIdScr"][$kk]) && $formArr["set_profile_pic"]!=$formArr["screenedProfilePicId"])	//If profile pic is changed and the new profile pic belongs to the screened category and its not browsed.
						{
							$profilePicId = $formArr["set_profile_pic"];		//Store the Picture Id for cropped profile pic and thumbnail
						}
					}
				}
				else if ($k == "uploadPhotoNonScr")		//If non screened pics are uploaded
				{
					foreach($v["name"] as $kk=>$vv)
					{
						if ($v["error"][$kk]!=4)
						{
							$picId = $pictureServiceObj->getPictureAutoIncrementId();	//Get PICTURE ID
							if ($kk == $profilePicIndex && $profilePicFlag == "NonScreened")	//If the pic being uploaded is marked as profile pic
							{
								$profilePicId = $picId;		//Store the PICTURE Id for cropped profile pic and thumbnail
							}

							if ($v["type"][$kk] == "image/gif")	//Get the image format
								$format_of_pic = "gif";
							else if ($v["type"][$kk] == "image/jpeg")
								$format_of_pic = "jpeg";
							else if ($v["type"][$kk] == "image/jpg")
								$format_of_pic = "jpg";

							$outputResponse = $this->performUpload($picId,$v["tmp_name"][$kk],"mainPic",$v["type"][$kk],$formArr["profileid"]);	//Perform file transfer
							$outputResponseArr = explode("**-**",$outputResponse);	//Get output array
							$uploadActionOutput[] = $outputResponseArr[0];		//File Transfer output as PASS or FAIL
		
							/* for updating PICTURE_DETAILS */
							if($outputResponseArr[0]=='PASS')

							{
								$filePath = $v["tmp_name"][$kk];
								$oldPicId = $this->associateUploadedPhotoWithUnScreenedPictureId[$filePath];
								$photoScreeningTracking = new photoScreeningTracking;
								$photoScreeningTracking->updateImageDetails($oldPicId,$picId,$formArr["profileid"]);
							}
							/* for updating PICTURE_DETAILS */

							$MainPicUrl[] = $outputResponseArr[1];			//Main Pic Url
							$ThumbailUrl[] = null;
							$ProfilePicUrl[] = null;
							$SearchPicUrl[] = null;
							$Thumbail96Url[] = $outputResponseArr[2];		//Thumbnail96 Url
							$PicFormat[] = $format_of_pic;
							$dbEntryPicId[] = $picId;				//Picture Id of uploaded pic
                                                        $dbEntryTitle[] = $formArr["titleNonScr"][$kk];		//title of uploaded pic
							if ($formArr["keywordNonScr"][$kk])			//keywords of uploaded pic
                                                        	$dbEntryKeywords[] = $formArr["keywordNonScr"][$kk];
							else
								$dbEntryKeywords[] = null;
							$newUpload= true;					//A new file is uploaded
						}
					}
				}
			}
		}

		if ($formArr["source"] == "new" || $formArr["source"] == "edit")	//If its a new or edit scenario
		{
			if ($profilePicFlag == "Screened" && $formArr["set_profile_pic"]!=$formArr["screenedProfilePicId"])	//If profile pic is changed and the new is screened
			{
				$profilePicId = $formArr["picIdScr"][$profilePicIndex];		//Store Picture ID for cropped profile pic and thumbnail
				if (is_array($formArr["deletePhotoScr"]) && in_array($profilePicId,$formArr["deletePhotoScr"]))		//If selected profile pis is marked for delete
				{
					$profilePicId = null;					//Keep the profilePicId as null
				}
			}
		}

		if ($profilePicId)			//If profilePicId exist i.e. when a new profile pic is selected
		{
			$outputResponse1 = $this->performUpload($profilePicId,$_FILES["profilePic"]["tmp_name"],"profilePic",$_FILES["profilePic"]["type"],$formArr["profileid"]);	//Perform file transfer of cropped profile pic
			$outputResponseArr1 = explode("**-**",$outputResponse1);	//Get the output array
                 	$uploadActionOutput[] = $outputResponseArr1[0];			//File transfer output as PASS or FAIL

			$outputResponse = $this->performUpload($profilePicId,$_FILES["thumbnail"]["tmp_name"],"thumbnail",$_FILES["thumbnail"]["type"],$formArr["profileid"]);		//Perform file transfer of cropped thumbnail
			$outputResponseArr = explode("**-**",$outputResponse);		//Get the output array
                        $uploadActionOutput[] = $outputResponseArr[0];			//File transfer output as PASS or FAIL

			if (is_array($formArr["picIdScr"]) && in_array($profilePicId,$formArr["picIdScr"]))		//If the new profile pic is not browsed then perform update query
			{
				$updateParamArr["ProfilePicUrl"] = $outputResponseArr1[1];	//Cropped Profile Pic Url
				$updateParamArr["SearchPicUrl"] = $outputResponseArr1[2];	//Cropped Search Pic Url
				$updateParamArr["ThumbailUrl"] = $outputResponseArr[1]; 	//Cropped Thumbnail Url
				//Added by Reshu for Image server log entry
				$updateParamArr["PICTUREID"]=$profilePicId;
			}
			else		//If the new profile pic is browsed
			{
				$profilePicIndexTemp = array_search($profilePicId,$dbEntryPicId);	//Get index of profilePicId in the dbEntryPicId array 
				$ProfilePicUrl[$profilePicIndexTemp] = $outputResponseArr1[1];		//Store cropped porifle pic url at desired index
				$SearchPicUrl[$profilePicIndexTemp] = $outputResponseArr1[2];		//Store cropped porifle pic url at desired index
                        	$ThumbailUrl[$profilePicIndexTemp] = $outputResponseArr[1];		//Store cropped thumbnail url at desired index
			}

			if($profilePicFlag == "NonScreened")	
			{
				$picture_for_screen_new=new NonScreenedPicture;
				if(strpos($formArr["set_profile_pic"],"ttach")==false && strpos($formArr["set_profile_pic"],"ddmore")==false)
				{
					$selectedNonScreenProfilePicData = $picture_for_screen_new->get(array("PICTUREID"=>$formArr["set_profile_pic"],"PROFILEID"=>$formArr["profileid"]));
					$selectedNonScreenProfilePic = $selectedNonScreenProfilePicData[0]["MainPicUrl"];
					unset($selectedNonScreenProfilePicData);
				}
				elseif(strpos($formArr["set_profile_pic"],"ttach"))
				{
					if($formArr["mailid"])
					{
						$selectedNonScreenProfilePic = $picture_for_screen_new->getDisplayPicUrl("MailImages","-",$formArr["profileid"],"",$formArr["mailImagesNamesMapping"][$formArr["set_profile_pic"]]);
					}	
				}
				unset($picture_for_screen_new);
			}
		}

		if ($formArr["picIdNonScr"])
		{
			foreach($formArr["picIdNonScr"] as $id)		//Loop to get the picture id's in PICTUERE_FOR_SCREEN_NEW table
           		{
                		if(strpos($id,"ttach")==false && strpos($id,"ddmore")==false)
                        	{
                         		$picIdArr[]=$id;
                   		}
			}
           	}

		if (is_array($uploadActionOutput) && in_array("FAIL",$uploadActionOutput))	//If any file transfer is not successfule
		{
			return "Some photo/photos could not be uploaded. Please try uploading all again.";	
		}
		else if (count($uploadActionOutput)>0 && $newUpload)	//All file transfer are successful and new photos are also uploaded
		{
			//Transaction
			$picture_new = new ScreenedPicture;
			$picture_new->startTransaction();
			if ($dbEntryPicId)
			{
				$dbActionOutput = $this->performDbAction($formArr["profileid"],$dbEntryPicId,$dbEntryTitle,$dbEntryKeywords,$picture_new,$MainPicUrl,$ProfilePicUrl,$ThumbailUrl,$Thumbail96Url,$PicFormat,$SearchPicUrl);		//Perform insert queries on PICTURE_NEW
			}
			if ($updateParamArr)
			{
				$picture_new->edit($updateParamArr,$profilePicId,$formArr["profileid"]);		//Perform update
				//Added by Reshu for Image server Log table entry
                                $picture_new->insImageServerLog($updateParamArr);
			}
			if ($formArr["actualTitleScreen"])	//update titles in case only titles need to be screened
			{
				$this->updateScreenTitles($formArr["actualTitleScreen"],$formArr["titleScr"],$formArr["picIdScr"]);	
			}
			if ($picIdArr)
			{
                        	$this->deleteEntriesFromScreening($picIdArr);		//Delete entries from PICTURE_FOR_SCREEN_NEW
			}
			$picture_new->commitTransaction();
			//End Transaction

			if ($dbActionOutput == "Success")	//Insert queries are successful
			{
				if ($profilePicId)		//Profile pic is changed then perform swapping
				{
					$currentProfilePicObj = $pictureServiceObj->getScreenedPhotos("profilePic"); 
					$whereArr["PICTUREID"] = $profilePicId;
                			$currentPicObj = $pictureServiceObj->getPicDetails($whereArr);
					
					if ($currentPicObj[0]->getORDERING() != $currentProfilePicObj->getORDERING())
					{
						$status = $this->updateFinalOrdering($currentProfilePicObj,$currentPicObj,$formArr["profileid"]);	
						if (!$status)
							return "Error in changing the ordering.";
					}

					if($selectedNonScreenProfilePic)	//make entry in PICTURE_FOR_SCREEN_APP table
					{
						$this->performDbActionForApp($formArr["profileid"],$selectedNonScreenProfilePic,$profilePicId);
					}
				}
				return "Success";
			}	
			else
			{
				return $dbActionOutput;		//return Error obtained
			}
		}
		else if (count($uploadActionOutput)>0 && !$newUpload)	//No new photo was uploaded but cropped profile pic and thumbnail was uploaded successfully
		{
			//Transaction
			$picture_new = new ScreenedPicture;
			$picture_new->startTransaction();
			
			if ($updateParamArr)
			{
				$picture_new->edit($updateParamArr,$profilePicId,$formArr["profileid"]);		//Perform update
				//Added by Reshu for Image server Log table entry
				$picture_new->insImageServerLog($updateParamArr);
			}
			if ($formArr["actualTitleScreen"])	//update titles in case only titles need to be screened
                        {
                                $this->updateScreenTitles($formArr["actualTitleScreen"],$formArr["titleScr"],$formArr["picIdScr"]);
                        }
			if ($picIdArr)
                        {
                                $this->deleteEntriesFromScreening($picIdArr);
                        }
			$picture_new->commitTransaction();
			//End Transaction

				if ($profilePicId)		//Profile pic is changed then perform swapping
                                {
                                        $currentProfilePicObj = $pictureServiceObj->getScreenedPhotos("profilePic");
                                        $whereArr["PICTUREID"] = $profilePicId;
                                        $currentPicObj = $pictureServiceObj->getPicDetails($whereArr);

                                        if ($currentPicObj[0]->getORDERING() != $currentProfilePicObj->getORDERING())
                                        {
                                                $status = $this->updateFinalOrdering($currentProfilePicObj,$currentPicObj,$formArr["profileid"]);
                                                if (!$status)
                                                        return "Error in changing the ordering.";
                                        }

					if($selectedNonScreenProfilePic)	//make entry in PICTURE_FOR_SCREEN_APP table
					{
						$this->performDbActionForApp($formArr["profileid"],$selectedNonScreenProfilePic,$profilePicId);
					}
                                }
			
			return "Success";
		}
		else	//Neither new photo was uploaded nor any cropped pic was uploaded or all non screened photos were deleted
		{
			//Transaction
			$picture_new = new ScreenedPicture;
			$picture_new->startTransaction();
			
			if ($formArr["actualTitleScreen"])	//update titles in case only titles need to be screened
			{
				$this->updateScreenTitles($formArr["actualTitleScreen"],$formArr["titleScr"],$formArr["picIdScr"]);	
			}
			if ($picIdArr)		
			{	
		   		$this->deleteEntriesFromScreening($picIdArr);	//Delete entries form PICTURE_FOR_SCREEN_NEW
			}
			$picture_new->commitTransaction();
			//End Transaction
			return "Success";
		}
	}

	/**
	This function is used to swap the ordering of currently selected profile pic and the profile pic that already exists.
  	@param Objects of currently selected profile pic and the profile pic that already exists.
  	@return 1 if ordering is done successfully else 0.
  	*/
	public function updateFinalOrdering($currentProfilePicObj,$currentPicObj,$profileId)
	{
		$PICTURE_NEW=new ScreenedPicture;
		$PICTURE_NEW->startTransaction();

		$first_free_ordering=$currentPicObj[0]->getORDERING();
                $currentPicObj[0]->setORDERING(-1);
		$updateArray["ORDERING"]=$currentPicObj[0]->getORDERING();
            	$tempStatus1=$PICTURE_NEW->edit($updateArray,$currentPicObj[0]->getPICTUREID(),$profileId);
		unset($updateArray);
		
           	$currentProfilePicObj->setORDERING($first_free_ordering);
		$updateArray["ORDERING"]=$currentProfilePicObj->getORDERING();
               	$tempStatus2=$PICTURE_NEW->edit($updateArray,$currentProfilePicObj->getPICTUREID(),$profileId);
		unset($updateArray);

            	$currentPicObj[0]->setORDERING(0);
		$updateArray["ORDERING"]=$currentPicObj[0]->getORDERING();
		
              	$tempStatus3=$PICTURE_NEW->edit($updateArray,$currentPicObj[0]->getPICTUREID(),$profileId);
		unset($updateArray);

		$PICTURE_NEW->commitTransaction();
		
		if ($tempStatus1 && $tempStatus2 && $tempStatus3)
			return 1;
		else 
			return 0;
	}	

	/**
	This function is used to perform file transfer part of upload process.
  	@param PICTUREID,path to temp folder,type of pic,format(jpeg/jpg/gif) and PROFILEID
  	@return PASS if uploaded successfully else FAIL
  	*/
	public function performUpload($picId,$temp,$type,$format,$profileId)
	{
		$profileObj = Operator::getInstance('newjs_master',$profileId);
		$pictureServiceObj=new PictureService($profileObj);
                $screenedPicObj = new ScreenedPicture;
		$extraPicUrl = null;

		if ($format == "image/gif")
           		$format_of_pic = "gif";
             	else if ($format == "image/jpeg")
                   	$format_of_pic = "jpeg";
           	else if ($format == "image/jpg")
                     	$format_of_pic = "jpg";

		$src_pic_name = $screenedPicObj->getSaveUrl($type,$picId,$profileId,$format_of_pic);

		if ($type == "mainPic")
		{
			$dest_pic_name = $screenedPicObj->getSaveUrl("thumbnail96",$picId,$profileId,$format_of_pic);
			$extraPicUrl = $screenedPicObj->getDisplayPicUrl("thumbnail96",$picId,$profileId,$format_of_pic);
		}
		if ($type == "profilePic")
		{
			$dest_pic_name = $screenedPicObj->getSaveUrl("searchPic",$picId,$profileId,$format_of_pic);
			$extraPicUrl = $screenedPicObj->getDisplayPicUrl("searchPic",$picId,$profileId,$format_of_pic);
		}

		$buffer_path = $screenedPicObj->getSaveUrl("canvasPic",$picId,$profileId,$format_of_pic);

			if ($type == "mainPic")
			{
				$pictureServiceObj->generateImages("thumbnail96",$temp,$buffer_path,$format);
				$pictureServiceObj->generateImages("watermark",$temp,"main",$format);
				$copyOutput1 = $this->moveImageToScreened($temp,$src_pic_name);
				$copyOutput2 = $this->moveImageToScreened($buffer_path,$dest_pic_name);
				if ($copyOutput1 && $copyOutput2)
					$response = "PASS";
				else
					$response = "FAIL";
				unlink($buffer_path);
			}
			else if ($type == "profilePic")
			{
				$pictureServiceObj->generateImages("watermark",$temp,"profile",$format);
				$copyOutput1 = $this->moveImageToScreened($temp,$src_pic_name);
				$pictureServiceObj->generateImages("searchPic",$temp,$temp,$format);
				$copyOutput2 = $this->moveImageToScreened($temp,$dest_pic_name);
				if ($copyOutput1 && $copyOutput2)
					$response = "PASS";
				else
					$response = "FAIL";
			}
			else if ($type == "thumbnail")
			{
				$copyOutput1 = $this->moveImageToScreened($temp,$src_pic_name);
				if ($copyOutput1)
					$response = "PASS";
				else
					$response = "FAIL";
			}
			else
			{
				$response = "FAIL";
			}
			unlink($temp);  

		$displayMainPicUrl = $screenedPicObj->getDisplayPicUrl($type,$picId,$profileId,$format_of_pic);
		$response = $response."**-**".$displayMainPicUrl."**-**".$extraPicUrl;
		return $response;
	}
	
	/**
	This function is used to make the entry in PICTURE_NEW table
  	@param PROFILEID,PICTUREID array, TITLE array, KEYWORDS array
  	@return Success if uploaded successfully else Error message
  	*/
	public function performDbAction($profileId,$dbEntryPicId,$dbEntryTitle,$dbEntryKeywords,$picture_new,$MainPicUrl,$ProfilePicUrl,$ThumbailUrl,$Thumbail96Url,$MobileAppPicUrl='',$ProfilePic120Url='',$ProfilePic235Url='',$ProfilePic450Url='',$OriginalPicUrl='',$PicFormat,$SearchPicUrl)
	{
                
		if(!$picture_new)
			$picture_new = new ScreenedPicture;
              	$ordering = $picture_new->getMaxOrdering($profileId);

		if ($ordering != null)
			$ordering = $ordering + 1;
		else
			$ordering =0;

		$response = $picture_new->insertBulkScreen($profileId,$dbEntryPicId,$dbEntryTitle,$dbEntryKeywords,$ordering,$MainPicUrl,$ProfilePicUrl,$ThumbailUrl,$Thumbail96Url,$MobileAppPicUrl,$ProfilePic120Url,$ProfilePic235Url,$ProfilePic450Url,$OriginalPicUrl,$PicFormat,$SearchPicUrl);
		//Added by Reshu for Image Server Implementation
		$responseImage=$picture_new->insertBulkImageServerLog($dbEntryPicId,$MainPicUrl,$ProfilePicUrl,$ThumbailUrl,$Thumbail96Url,$SearchPicUrl,$MobileAppPicUrl,$ProfilePic120Url,$ProfilePic235Url,$ProfilePic450Url,$OriginalPicUrl);
		$response = $response && $responseImage;
		return $response;
	}

	/*
	This function is used to make entry in PICTURE_FOR_SCREEN_APP table
	@param - PROFILEID, non screened image url, screened profile pic id
	*/
	public function performDbActionForApp($profileId,$selectedNonScreenProfilePic,$profilePicId)
	{
		$param["PROFILEID"] = $profileId;
		$param["MainPicUrl"] = $selectedNonScreenProfilePic;
		$param["SCREENED_PICTUREID"] = $profilePicId;
		$picture_for_screen_new=new NonScreenedPicture;
		$picture_for_screen_new->insOnAppTable($param);
	}

	/**
	This function is used to update titles if only title are to be screened.
  	@param PICTUREID string of pics whose title is to be screened, TITLE array, screen pic ID array
  	@return Success if updated successfully else Error message
  	*/
	public function updateScreenTitles($picIdStr,$screenTitleArr,$screenArr)
	{
		$picIdArr = explode(",",$picIdStr);
		foreach ($picIdArr as $k=>$v)
		{
			$index = array_search($v,$screenArr);
			$requiredTitleArr[] = $screenTitleArr[$index];
		}
	
                $picture_new = new ScreenedPicture;

		$response = $picture_new->updateScreenTitles($picIdArr,$requiredTitleArr);
		return $response;
	}


	/**
	This function is used to send mails for different case of photo uploads.
  	@param mailer type,parameters,profileObj(having only profileid)
  	*/
	public function sendMailers($mailType,$params_arr,$profileObj)
	{
		$mailGroup = MailerGroup::PHOTO_UPLOAD;
		if ($mailType == "case2")	//This is the case when some photos are uploaded and (some are deleted or none are deleted)
		{
			$caseFlag = 0;
			{
				$fto_sub_state = JsCommon::getProfileState($profileObj);
				if($fto_sub_state==FTOSubStateTypes::FTO_ELIGIBLE_NO_PHONE_HAVE_PHOTO)		//C3
				{
					$suggested_matches_array=SearchCommonFunctions::getDppMatches($profileObj->getPROFILEID(),'fto_offer',SearchSortTypesEnums::popularSortFlag);
					$email_sender=new EmailSender($mailGroup,1725);
					$tpl = $email_sender->setProfileId($profileObj->getPROFILEID());
					$p_list = new PartialList;
                                        $p_list->addPartial('suggested_profiles','suggested_profiles',$suggested_matches_array["SEARCH_RESULTS"],false);
                                        $p_list->addPartial('jeevansathi_contact_address','jeevansathi_contact_address');
                                        $tpl->setPartials($p_list);
					$fto_exp_date = $profileObj->getPROFILE_STATE()->getFTOStates()->getExpiryDate();
					$smartyObj = $tpl->getSmarty();
					$smartyObj->assign("PHOTOS_UPLOADED",$params_arr[0]);
					$smartyObj->assign("PHOTOS_SCREENED",$params_arr[1]);
					$smartyObj->assign("FTO_END_MONTH_UPPERCASE",strtoupper(date("M",JSstrToTime($fto_exp_date))));
					$smartyObj->assign("FTO_END_MONTH",date("M",JSstrToTime($fto_exp_date)));
					$smartyObj->assign("FTO_END_YEAR",date("Y",JSstrToTime($fto_exp_date)));
					$smartyObj->assign("FTO_END_DAY",date("d",JSstrToTime($fto_exp_date)));
					$smartyObj->assign("FTO_END_DAY_SUFFIX",date("S",JSstrToTime($fto_exp_date)));
					$smartyObj->assign("FTO_END_DAY_SINGLE_DOUBLE_DIGIT",date("j",JSstrToTime($fto_exp_date)));
				}
				elseif($fto_sub_state==FTOSubStateTypes::FTO_ACTIVE_LEAST_THRESHOLD || $fto_sub_state==FTOSubStateTypes::FTO_ACTIVE_BELOW_LOW_THRESHOLD || $fto_sub_state==FTOSubStateTypes::FTO_ACTIVE_BETWEEN_LOW_HIGH_THRESHOLD || $fto_sub_state==FTOSubStateTypes::FTO_ACTIVE_ABOVE_HIGH_THRESHOLD)	//D1,D2,D3,D4
				{
					if($fto_sub_state==FTOSubStateTypes::FTO_ACTIVE_LEAST_THRESHOLD)
						$suggested_matches_array=SearchCommonFunctions::getDppMatches($profileObj->getPROFILEID(),'fto_offer',SearchSortTypesEnums::popularSortFlag);
					else
						$suggested_matches_array=SearchCommonFunctions::getDppMatches($profileObj->getPROFILEID(),'fto_offer');
                                        $email_sender=new EmailSender($mailGroup,1740);
                                        $tpl = $email_sender->setProfileId($profileObj->getPROFILEID());
					$p_list = new PartialList;
					$p_list->addPartial('suggested_profiles','suggested_profiles',$suggested_matches_array["SEARCH_RESULTS"],false);
                                        $p_list->addPartial('jeevansathi_contact_address','jeevansathi_contact_address');
					$tpl->setPartials($p_list);
                                        $fto_exp_date = $profileObj->getPROFILE_STATE()->getFTOStates()->getExpiryDate();
                                        $smartyObj = $tpl->getSmarty();
                                        $smartyObj->assign("PHOTOS_UPLOADED",$params_arr[0]);
                                        $smartyObj->assign("PHOTOS_SCREENED",$params_arr[1]);
                                        $smartyObj->assign("FTO_END_MONTH_UPPERCASE",strtoupper(date("M",JSstrToTime($fto_exp_date))));
                                        $smartyObj->assign("FTO_END_MONTH",date("M",JSstrToTime($fto_exp_date)));
                                        $smartyObj->assign("FTO_END_YEAR",date("Y",JSstrToTime($fto_exp_date)));
                                        $smartyObj->assign("FTO_END_DAY",date("d",JSstrToTime($fto_exp_date)));
                                        $smartyObj->assign("FTO_END_DAY_SUFFIX",date("S",JSstrToTime($fto_exp_date)));
                                        $smartyObj->assign("FTO_END_DAY_SINGLE_DOUBLE_DIGIT",date("j",JSstrToTime($fto_exp_date)));
                                        $smartyObj->assign("FTO_REMAIN_DAYS",$profileObj->getPROFILE_STATE()->getFTOStates()->getRemainingDays());
				}
				elseif($fto_sub_state==FTOSubStateTypes::FTO_EXPIRED_BEFORE_ACTIVATED || $fto_sub_state==FTOSubStateTypes::FTO_EXPIRED_AFTER_ACTIVATED || $fto_sub_state==FTOSubStateTypes::FTO_EXPIRED_INBOUND_ACCEPT_LIMIT || $fto_sub_state==FTOSubStateTypes::FTO_EXPIRED_OUTBOUND_ACCEPT_LIMIT || $fto_sub_state==FTOSubStateTypes::FTO_EXPIRED_TOTAL_ACCEPT_LIMIT || $fto_sub_state==FTOSubStateTypes::NEVER_EXPOSED || $fto_sub_state==FTOSubStateTypes::DUPLICATE)	//E1-E5,F,G
				{
					$caseFlag = 1;
				}
				else
				{
					$caseFlag = 1;
				}
			}

			/*
			else
			{
				$caseFlag = 1;
			}
			*/

			if($caseFlag==1)
			{
				$search_array1=SearchCommonFunctions::getDppMatches($profileObj->getPROFILEID(),'mailer_photo_upload');	//5 DPP matches with photo and no popular sorting
				$search_array2=SearchCommonFunctions::getDppMatches($profileObj->getPROFILEID(),'mailer_photo_upload','','');	//Dpp matches count with no condition of have photo

				$email_sender=new EmailSender($mailGroup,1741);
				$tpl = $email_sender->setProfileId($profileObj->getPROFILEID());
				$p_list = new PartialList;
				$p_list->addPartial('dpp_matches','dpp_matches',$search_array1["SEARCH_RESULTS"]);
				$p_list->addPartial('self_tuple','dpp_matches',array($profileObj->getPROFILEID()));
				$tpl->setPartials($p_list);
				$smartyObj = $tpl->getSmarty();
				$smartyObj->assign("PHOTOS_UPLOADED",$params_arr[0]);
				$smartyObj->assign("PHOTOS_SCREENED",$params_arr[1]);
				$smartyObj->assign("PHOTOS_REJECTED",$params_arr[2]);
				$smartyObj->assign("REJECT_REASON",$params_arr[3]);
				$smartyObj->assign("TOTAL_PHOTOS_NOW",$params_arr[4]);
				$smartyObj->assign("SEARCH_COUNT",$search_array2["TOTAL_SEARCH_RESULTS"]);
			}
		}
		else if ($mailType == "case3")		//This is the case when none are uploaded. Only deletion takes place
		{
			$email_sender=new EmailSender($mailGroup,1743);
                        $tpl = $email_sender->setProfileId($profileObj->getPROFILEID());
			$smartyObj = $tpl->getSmarty();
                       	$smartyObj->assign("REJECT_REASON",$params_arr[0]);
		}
		else if ($mailType == "case4")		//This is the case when more than 20 are uploaded from backend but only 20 gets uploaded.
		{
			$email_sender=new EmailSender($mailGroup,1744);
			$tpl = $email_sender->setProfileId($profileObj->getPROFILEID());
		}
		$email_sender->send();
	}

	/**
	This function is read the image sourceImage and send headers with new image newName
  	@param sourceImage file to be read
  	@param newName new name of the file
  	*/
        public function renameImageName($sourceImage,$newName)
        {
	        $PictureFunctionsObj=new PictureFunctions;
        	$PictureFunctionsObj->renameImageName($sourceImage,$newName);
        }
	
	/** Added  by Reshu for Image Server Implementation
	* This function is used to copy images from non screened folder to screened folder 
	* @param source non screened image
	*/
	public function moveImageToScreened($source,$dest)
        {
								$source = PictureFunctions::getCloudOrApplicationCompleteUrl($source,true);
								$dest = PictureFunctions::getCloudOrApplicationCompleteUrl($dest,true);
                chmod($source,0777);
                
                if($source==$dest)
                        return true;
                
                $tempS = explode("uploads",$source);
                $destS = explode("uploads",$dest);
                if($tempS[1] == $destS[1])
                        return true;

                $PictureFunctionsObj=new PictureFunctions;
                return $PictureFunctionsObj->moveImage($source,$dest);
        }

	/*
	This function is used to uplaod the app photo and make corresponding database entries
	@param - form array
	*/
	public function uploadAppPhoto($formArr)
	{
		$nonScreenedPicObj = new NonScreenedPicture;
		$screenedPicObj = new ScreenedPicture;

		if($formArr["whichCase"]==1)		//Case When Both MainPicUrl and AlgoPicUrl are present
		{
			if($formArr["photoActionRadio"] == "Approve")
			{
				$src_pic_name = $nonScreenedPicObj->getSaveUrl("mobileAppPic",$formArr["pictureid"],$formArr["profileid"]);
				$dest_pic_name = $screenedPicObj->getSaveUrl("mobileAppPic",$formArr["pictureid"],$formArr["profileid"]);
				$output = $this->moveImageToScreened($src_pic_name,$dest_pic_name);
			}
			elseif($formArr["photoActionRadio"] == "Edit")
			{
				$dest_pic_name = $screenedPicObj->getSaveUrl("mobileAppPic",$formArr["pictureid"],$formArr["profileid"]);
				$output = move_uploaded_file($_FILES['appPhoto']['tmp_name'], $dest_pic_name);
			}
		}
		elseif($formArr["whichCase"]==2)	//Case When only MainPicUrl is present
		{
			$dest_pic_name = $screenedPicObj->getSaveUrl("mobileAppPic",$formArr["pictureid"],$formArr["profileid"]);
			$output = move_uploaded_file($_FILES['appPhoto']['tmp_name'], $dest_pic_name);
		}

		if($output)
		{
			$status = $this->checkAppPhotoStatus($nonScreenedPicObj,$formArr["profileid"],$formArr["pictureid"]);
			if($status)
			{
				$app_pic_url = $screenedPicObj->getDisplayPicUrl("mobileAppPic",$formArr["pictureid"],$formArr["profileid"]);
				$screenedPicObj->startTransaction();
				$screenedPicObj->edit(array("MobileAppPicUrl"=>$app_pic_url),$formArr["screened_pictureid"],$formArr["profileid"]);
				$nonScreenedPicObj->delFromAppTable($formArr["profileid"],$formArr["pictureid"]);
				$imsObj = new ImageServerLog;
				$imsObj->insertBulk("PICTURE",$formArr["screened_pictureid"],"MobileAppPicUrl","N");
				$screenedPicObj->commitTransaction();
				unset($imsObj);
			}
			else
			{
				$nonScreenedPicObj->delFromAppTable($formArr["profileid"],$formArr["pictureid"]);
			}
			return "Success";
		}
		else
			return "File cannot be uploaded";
	}

	/*
	This function is used to check if the current picture id is the latest picture id or not for a given profile
	@param - non screened picture object, profileid, current picture id
	@return - 1 if true else 0
	*/
	public function checkAppPhotoStatus($nonScreenedPicObj,$profileid,$currentPictureid)
	{
		$pictureid = $nonScreenedPicObj->getLatestPictureIdForProfile($profileid);
		if($pictureid && $pictureid == $currentPictureid)
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}
       /**
         * This function gives the list of photos for a profile to be screened in proper form
         *@param - profileObj - Object of profile to be screened
         *@param - Source-newEdit or Mail or else ||||| Interface - AR-Accept/Reject or PROCESS  
         *@return - error message or success
	*/
	public function getPicturesToScreen($paramArr) {
                
                //Enums for pictures status and interface
                $photoTypes = ProfilePicturesTypeEnum::$PICTURE_SIZES;
                $bitStatus = ProfilePicturesTypeEnum::$SCREEN_BITS;
                $interfaceArr = ProfilePicturesTypeEnum::$INTERFACE;

                //Getting Album and separating in 
                $pictureServiceObj = new PictureService($this->profileObj,"SCREENING");
                $album = $pictureServiceObj->getAlbum("album");
                
                //Preparing array for different interfaces 
                foreach ($album as $key => $picObj) {
                        if (get_class($picObj) == "NonScreenedPicture") {
                                $nonScreened[] = $picObj;
                                if ($picObj->getORDERING() == 0) { 
                                        $screenBit = $picObj->getSCREEN_BIT(); 
                                        $screenFlipBit = array_flip(array_keys($photoTypes));
                                        foreach ($photoTypes as $type => $size) {
                                                $profilePicTypeStatus = $screenBit[($screenFlipBit[$type] + 2)];
                                                if (($paramArr["interface"] == $interfaceArr["1"] && !$this->isPhotoDefault($type, $screenBit)) || ($paramArr["interface"] == $interfaceArr["2"] && ($this->isPhotoProcessing($type, $screenBit) || $this->isPhotoDefault($type, $screenBit)))) {
                                                        eval('$photoTypes[$type]["url"] = $picObj->get' . $type . '();');
                                                        $photoTypes[$type]["bit"] = $profilePicTypeStatus;
                                                } else
                                                        unset($photoTypes[$type]);
                                        }
                                        $pictureToBeScreenedArr["profilePic"]["profileType"] = $photoTypes;
                                        $pictureToBeScreenedArr["profilePic"]["pictureId"] = $picObj->getPICTUREID();

                                        if ($paramArr["interface"] == $interfaceArr["1"] && !$this->isPhotoDeleted("MainPicUrl", $screenBit))
                                                $pictureToBeScreenedArr["profilePic"]["mainPicUrl"] = array("url" => $picObj->getMainPicUrl()."?time=".time(), "localpath" => $picObj->getLocalMainPicUrl(),"bit" => $screenBit[1], "title" => $picObj->getTITLE());
                                        elseif ($paramArr["interface"] == $interfaceArr["2"] && $this->isPhotoProcessing("MainPicUrl", $screenBit))
                                                $pictureToBeScreenedArr["profilePic"]["mainPicUrl"] = array("url" => $picObj->getOriginalPicUrl(), "bit" => $screenBit[1], "title" => $picObj->getTITLE());

                                        if ($paramArr["interface"] == $interfaceArr["2"])
                                        {
                                                if(strstr($picObj->getOriginalPicUrl(),"uploads"))
                                                {
                                                       $pictureToBeScreenedArr["profilePic"]["OriginalProfilePicUrl"] = $picObj->getOriginalPicUrl();
                                                       $pictureToBeScreenedArr["profilePic"]["MainPicUrl"] = $picObj->getMainPicUrl()."?time=".time();
                                                }
                                                else
                                                {
                                                        if($picObj->getOriginalPicUrl()==$picObj->getMainPicUrl())
                                                            $pictureToBeScreenedArr["profilePic"]["OriginalProfilePicUrl"] = $picObj->getMainPicUrl();
                                                        else
															$pictureToBeScreenedArr["profilePic"]["OriginalProfilePicUrl"] = $picObj->getOriginalPicUrl();
														 $pictureToBeScreenedArr["profilePic"]["MainPicUrl"] = $picObj->getMainPicUrl()."?time=".time();
                                               
                                                      // $pictureToBeScreenedArr["profilePic"]["OriginalProfilePicUrl"] = str_replace("mediacdn.jeevansathi.com","jeevansathi.s3.amazonaws.com",$picObj->getOriginalPicUrl()); commented because image not displaying
                                                }
                                                /*
                                                else
                                                        $pictureToBeScreenedArr["profilePic"]["OriginalProfilePicUrl"] = $picObj->getMainPicUrl();
                                                */
                                        }
                                }
                                else { 
                                        if ($paramArr["interface"] == $interfaceArr["1"] && !$this->isPhotoDeleted("nonProfilePic", $picObj->getSCREEN_BIT())) {
                                                $pictureToBeScreenedArr["nonScreened"][$picObj->getPICTUREID()] = array("url" => $picObj->getMainPicUrl()."?time=".time(),
                                                    "title" => $picObj->getTITLE(),
                                                    "bit" => $picObj->getSCREEN_BIT(),
                                                    "localpath" => $picObj->getLocalMainPicUrl());
                                        } elseif ($paramArr["interface"] == $interfaceArr["2"] && $this->isPhotoProcessing("nonProfilePic", $picObj->getSCREEN_BIT())) {
                                                $pictureToBeScreenedArr["nonScreened"][$picObj->getPICTUREID()] = array("url" => $picObj->getOriginalPicUrl(),
                                                    "title" => $picObj->getTITLE(),
                                                    "bit" => $picObj->getSCREEN_BIT());
                                        }
                                }
                                $pictureID[] = $picObj->getPICTUREID();
                        }
                        
                        elseif (get_class($picObj) == "ScreenedPicture") {
                                $screened[] = $picObj;
                                $pictureToBeScreenedArr["screened"][$picObj->getPICTUREID()]["url"] = $picObj->getMainPicUrl();
                                $screenedPictureIDs[] = $picObj->getPICTUREID();
                        }
                }
                $pictureToBeScreenedArr["pictureIDs"] = is_array($pictureID) ? implode(",", $pictureID) : "";
                $pictureToBeScreenedArr["screenedPictureIDs"] = is_array($screenedPictureIDs) ? implode(",", $screenedPictureIDs) : "";

                return $pictureToBeScreenedArr;
        }

        /**
         * This function gives the Final arranged list of screened photos in proper form
         *@param - Form input data
         *@return - Final Screened Array
	*/
	public function getFinalScreenedArray($formArr)
	{
          
           $albumList = explode(",", $formArr['pictureIDs']);
          
           
                if(stristr($formArr["set_profile_pic"],"screened")){
                        $profilePic = str_replace("screened", "", $formArr["set_profile_pic"]);
                        if(is_array($formArr["screenedPicDelete"]) && in_array($profilePic,$formArr["screenedPicDelete"])){
                               return "error0"; 
                        }
                        $this->screenedPhotoAsProfilePic($profilePic);
                }
                else{
                        $profilePic = $formArr["set_profile_pic"];
                }
                if(array_key_exists("profilePic_".$formArr["set_profile_pic"],$formArr) && $formArr["profilePic_".$formArr["set_profile_pic"]]=="DELETE")
                {
									if(sizeof(explode(",",$formArr["pictureIDs"]))==1 && sizeof(explode(",",$formArr["screenedPictureIDs"]))==0)
										return "error0";
								}
                $approvCount = 0;
                $edit = 0;
                $profileEdit=0;
                $rotate=array();
                foreach ($albumList as $albumKey => $albumVal) {  
                        if ($albumVal != $profilePic && !$formArr["profilePic_" . $albumVal]) {
                                $picture[$albumVal]["bit"] = ProfilePicturesTypeEnum::$SCREEN_BITS[$formArr["albumPic_" . $albumVal]];
                                if ($formArr["titleNonScr_" . $albumVal])
                                        $picture[$albumVal]["title"] = $formArr["titleNonScr_" . $albumVal];
                                if ($formArr["albumPic_" . $albumVal] == "APPROVE") {
                                        $approved[] = $albumVal;
                                        $approvCount++;
                                } elseif ($formArr["albumPic_" . $albumVal] == "DELETE")
                                        $deleted[] = $albumVal;
                                elseif ($formArr["albumPic_" . $albumVal] == "EDIT")
                                        $edit++;
                        }
                        else { 
                                $picture[$albumVal]["bit"] = ProfilePicturesTypeEnum::$SCREEN_BITS[$formArr["profilePic_" . $albumVal]];
                                if ($formArr["profilePic_" . $albumVal] == "APPROVE" || $formArr["albumPic_" . $albumVal] == "APPROVE"){
                                        $approved[] = $albumVal;
                                        $approvCount++;
                                }
                                elseif ($formArr["profilePic_" . $albumVal] == "DELETE" || $formArr["albumPic_" . $albumVal] == "DELETE"){
																				
                                        $deleted[] = $albumVal;
                                        $deleted[] = $albumVal;
																			
                                }
                                elseif ($formArr["profilePic_" . $albumVal] == "EDIT" || $formArr["albumPic_" . $albumVal] == "EDIT"){
                                        $edit++;
                                }
                        }
                        if ($formArr["rotate_" . $albumVal] && $formArr["rotate_" . $albumVal]!=0){
                                $rotate[$albumVal]=$formArr["rotate_" . $albumVal];
                        }
                        
                        if(in_array($albumVal, $formArr["watermark"]))
                        {
                                $watermark[$albumVal]=ProfilePicturesTypeEnum::$PICTURE_WATERMARK[array_flip(ProfilePicturesTypeEnum::$WATERMARK)["MainPicUrl"]];
                        }
                } 
                if ($formArr["profilePic_" . $profilePic] && $formArr["profilePic_" . $profilePic] != "DELETE") { 
                        $photoTypes = ProfilePicturesTypeEnum::$PICTURE_SIZES;
                        $picture[$profilePic]["bit"] = array_fill(0, (count($photoTypes) + 2), 0);
                        $picture[$profilePic]["bit"]["0"] = 1;
                        $picture[$profilePic]["bit"]["1"] = ProfilePicturesTypeEnum::$SCREEN_BITS[$formArr["profilePic_" . $profilePic]];
                        foreach (array_keys($photoTypes) as $typeKey => $typeVal) {
                                if ($formArr[$typeVal] == "APPROVE") {
                                        $picture[$profilePic]["bit"][($typeKey + 2)] = ProfilePicturesTypeEnum::$SCREEN_BITS[$formArr[$typeVal]];
                                        $approvCount++;
                                } else {
                                        $picture[$profilePic]["bit"][($typeKey + 2)] = ProfilePicturesTypeEnum::$SCREEN_BITS["EDIT"];
                                        $edit++;
                                        $profileEdit = 1;
                                }
                                if($formArr["watermark"]["watermarkOnType"][$typeVal]){
                                        if(!$watermark[$profilePic])
                                                $watermark[$profilePic]=1;
                                        if(in_array($typeVal,ProfilePicturesTypeEnum::$WATERMARK))
                                                $watermark[$profilePic]=$watermark[$profilePic]*ProfilePicturesTypeEnum::$PICTURE_WATERMARK[array_flip(ProfilePicturesTypeEnum::$WATERMARK)[$typeVal]];
                                }
                                
                        }
                        if ($profileEdit==0) {
                                $approved[] = $profilePic;
                        }

                        $picture[$profilePic]["bit"] = implode("", $picture[$profilePic]["bit"]);
                } elseif ($approvCount == 0 && $formArr["profilePic_" . $profilePic] && $formArr["profilePic_" . $profilePic] == "DELETE") {
                        $picture[$profilePic]["bit"] = ProfilePicturesTypeEnum::$SCREEN_BITS["DELETE"];
                } elseif ($approvCount > 0 && $formArr["profilePic_" . $profilePic] && $formArr["profilePic_" . $profilePic] == "DELETE") {
                        return "error0";
                } else {
                        if ($approvCount > 0 && $formArr["albumPic_" . $profilePic] == "DELETE") {
                                return "error0";
                        } else {
                                $picture[$profilePic]["bit"] = ProfilePicturesTypeEnum::$SCREEN_BITS[$formArr["albumPic_" . $profilePic]];
                                if ($formArr["ProfilePic_" . $albumList["0"]]) {
                                        $picture[$albumList["0"]]["bit"] = ProfilePicturesTypeEnum::$SCREEN_BITS[$formArr["ProfilePic_" . $albumList["0"]]];
                                }
                        }
                }
                $finalPictureArr["rotate"]=$rotate;
                $finalPictureArr["watermark"]=$watermark;
                $finalPictureArr["profile"] = $profilePic;
                $finalPictureArr["all"] = $picture;
                if(is_array($deleted))
                        $finalPictureArr["DELETE"] = array_unique($deleted);
                else
                        $finalPictureArr["DELETE"] = $deleted;
              
                if(is_array($approved))
                        $finalPictureArr["APPROVE"] = array_unique($approved);
                else
                        $finalPictureArr["APPROVE"] = $approved;
                
                $finalPictureArr["approvedCount"]=$approvCount;
                $finalPictureArr["EDIT"] = $edit;
                $finalPictureArr["screenedPicToDelete"] = $formArr["screenedPicDelete"];
                if (!$formArr["deleteReason"])
                        $finalPictureArr["DELETE_REASON"] = array();
                else
                        $finalPictureArr["DELETE_REASON"] = implode(",", $formArr["deleteReason"]);
				
                return $finalPictureArr;
        }
        
       /**
         * This function check if all pictures can be moved to PICTURE_NEW & PICTURE_DELETE tables
         *@return - 1 for Yes, 0 for Waiting for Editing
	*/
	public function isProfileScreened()
        {
              $pictureObj = new PICTURE_FOR_SCREEN_NEW();
              $currentBit=$pictureObj->isProfileScreened($this->profileObj->getPROFILEID());
              return $currentBit;
             
        }
        /**
         * This function check if user uploaded new photo while submiting
         *@return - 1 for Yes, 0 for Waiting for Editing
	*/
	public function isSuitableForSubmit()
        {
              $pictureObj = new PICTURE_FOR_SCREEN_NEW();
              $status=$pictureObj->isSuitableForSubmit($this->profileObj->getPROFILEID());
              
              return $status;
             
        }
        
        
        /**
         * This function check if all pictures can be moved to PICTURE_NEW & PICTURE_DELETE tables
         *@return - 1 for Yes, 0 for Waiting for Editing
	*/
	public function screenedPhotoAsProfilePic($pictureId)
        {
              $pictureServiceObj = new PictureService($this->profileObj);
              $whereArr=array("PROFILEID"=>$this->profileObj->getPROFILEID(),"PICTUREID"=>$pictureId);
              $picObj = $pictureServiceObj->getPicDetails($whereArr);
              $currentBit=$pictureServiceObj->setProfilePic($picObj[0]);
	      $main = $picObj[0]->getMainPicUrl();
              $org = $picObj[0]->getOriginalPicUrl();
              if(!$org)
              		$org = $main;
              $arrUpdate[$picObj[0]->getPICTUREID()] = array("OriginalPicUrl"=>$org,"MainPicUrl"=>$main);
              $pictureServiceObj->setPicProgressBit("RESIZE",$arrUpdate);
	      $arrUpdate[$picObj[0]->getPICTUREID()] = array();
              $pictureServiceObj->setPicProgressBit("FACE",$arrUpdate);
              $pictureServiceObj->setPicProgressBit("PROFILEPIC_CHANGE",$arrUpdate);
        }
        
        /**
         * This function prepares parameter for Tracking and other functions
         *@return - array of patamenters
	*/
	public function prepareParameter($parameterFor='',$name='',$formArr='',$picture='',$picDataForTracking='')
        {       
                if($parameterFor=="TRACK")
                {
                        if ($formArr["source"] == PictureStaticVariablesEnum::$SOURCE["MASTER"]) {
                                if ($this->profileObj->getHAVEPHOTO() == PictureStaticVariablesEnum::$HAVE_PHOTO_STATUS["YES"])
                                        $source = PictureStaticVariablesEnum::$SOURCE["EDIT"];
                                else
                                        $source = PictureStaticVariablesEnum::$SOURCE["NEW"];
                        }
                        else
                                $source = $formArr["source"];
                        
                        $masterTrackNotNeeded="";
                        
                        if((count($picture["DELETE"])+$picture["approvedCount"]+$picture["EDIT"])<1 && $formArr["source"]== PictureStaticVariablesEnum::$SOURCE["MASTER"])
                        {
                                $masterTrackNotNeeded = false;
                                $picture["EDIT"]=1;
                        }
                        
                        if($picture["EDIT"]==0){
                                if($this->isProfileScreened() != 1){
                                       $picture["EDIT"]=1; 
                                       $subject = "Count Error - ".$this->profileObj->getPROFILEID();
                                       $emailBody=print_r($formArr,true);
                                       $emailBody=$emailBody.print_r($picture,true);
                                       //SendMail::send_email("lavesh.rawat@gmail.com,akashkumardtu@gmail.com",$emailBody,$subject);
                                }
                        }
                        
                        //param array
                        $Arr=JsPhotoScreen_Enum::$arrTRACKING_PARAMS;
                        $paramArr = array(
                            $Arr["EXECUTIVE_NAME"] => $name,
                            $Arr["PROFILEID"] => $this->profileObj->getPROFILEID(),
                            $Arr["NUM_APPROVED_PIC"] => $picture["approvedCount"],
                            $Arr["NUM_DELETED_PIC"] => count($picture["DELETE"]),
                            $Arr["NUM_EDIT_PIC"] => $picture["EDIT"],
                            $Arr["SOURCE"] => $source,
                            $Arr["INTERFACE"]=> ProfilePicturesTypeEnum::$INTERFACE["1"],
                            $Arr["MASTER_TRACK_NEEDED"]=>$masterTrackNotNeeded,
                            $Arr["PHOTO_UPLOAD_TIME"]=>end($picDataForTracking)["UPDATED_TIMESTAMP"]
                               );
                        
                }
                elseif($parameterFor=="NOTIFY"){       // Param array for notification
                        
                        $paramArr = array(
                            "NOTIFY_CHANNEL" => JsPhotoScreen_Enum::enNOTIFY_CHANNEL_MAIL_SMS,
                            "PROFILEID" => $this->profileObj->getPROFILEID(),
                            "NUM_APPROVED_PIC" => count($name["APPROVED"]),
                            "NUM_DELETED_PIC" => count($name["DELETED"]),
                            "NUM_UPLOADED_PIC" => (count($name["APPROVED"])+count($name["DELETED"])),
                            "REJECT_REASON" => $name["REJECT_REASON"]);
                }
                else
                {       

                        if((count($picture["DELETE"])+$picture["approvedCount"]+$picture["EDIT"])<1 && $formArr["source"]== PictureStaticVariablesEnum::$SOURCE["MASTER"])
                        {
                                $masterTrackNotNeeded = false;
                                $picture["EDIT"]=1;
                        }
                        // ARRAY in CAPITAL and string or int in camel notation
                        $paramArr = array(
                            "profileId" => $this->profileObj->getPROFILEID(),
                            "profilePic" => $picture["profile"],
                            "profileObj" => $this->profileObj,
                            "APPROVE" => $picture["APPROVE"],
                            "DELETE" => $picture["DELETE"],
                            "EDIT" => $picture["EDIT"],
                            "SCREENED_DELETED" => count($picture["screenedPicToDelete"]),
                            "DELETE_REASON" => $picture["DELETE_REASON"],
                            "FINAL" => $picture["all"]);
                }
                return $paramArr;
             
        }
        
        
        /**
         * This function check and returns the status of Profile Screening Status
         *@return - 1 for Yes, 0 for Waiting for Editing
	*/
	public function pictureScreenStatus($profileId,$require='')
        {
              $pictureObj = new PICTURE_FOR_SCREEN_NEW();
              
              $nonScreenedPictureObj = new NonScreenedPicture();
              $paramArr["noOperationPerformed"]=$nonScreenedPictureObj->screenBitCheck("NoOperation");
              $paramArr["require"]=$require;
              $currentStatus=$pictureObj->pictureScreenStatus($profileId,$paramArr);
              return $currentStatus;
             
        }
        /**
         * This function updates SCREEN_BIT & title in PICTURE_FOR_SCREEN_NEW tables
         *@param - Array of picture Ids with bit and title
	*/
	public function saveDecisionStatus($paramArr)
        {
                if ($this->isSuitableForSubmit() == 1) {
									
                        // UPDATE ORDERING for profile pic
                        if ($paramArr["profilePic"]) {
                                $picProfileDetail = array("PICTUREID" => $paramArr["profilePic"], "PROFILEID" => $paramArr["profileId"]);
                                $pictureServiceObj = new PictureService($paramArr["profileObj"],"SCREENING");
                                $picObj = $pictureServiceObj->getPicDetails($picProfileDetail);
                               
                                if($picObj[0] && $picObj[0]->getORDERING()!=0)
                                {
                                        $previousOrdering = $picObj[0]->getORDERING();
                                        $paramArr["FINAL"][$paramArr["profilePic"]]["bit"]=  ProfilePicturesTypeEnum::$SCREEN_BITS["DEFAULT"].ProfilePicturesTypeEnum::$SCREEN_BITS["RESIZE"].implode("",array_fill(0,(count(array_merge(ProfilePicturesTypeEnum::$SCREEN_BIT_POSITION,  array_keys(ProfilePicturesTypeEnum::$PICTURE_SIZES)))-2),ProfilePicturesTypeEnum::$SCREEN_BITS["DEFAULT"]));
                                        $paramArr["EDIT"]++;
                                }
				
                                $result = $pictureServiceObj->setProfilePic($picObj[0]);
                        }
                        
                        // UPDATE table entries with title
                        $photoUpdateObj = new PICTURE_FOR_SCREEN_NEW();
                        $pictureUpdate = $photoUpdateObj->updateScreeningDecision($paramArr);
			
                        return $paramArr;
                }
        }
        
        
        /*
         * Memcache functionality implemented to avoid user refreshing the page
         */
        public static function avoidRefresh($name,$interface,$skipMemcache,$time='5')
        { 
                if ($skipMemcache != 1) {
                        $key = "PHOTO_SCREEN_" . $name . $interface;

                        if (JsMemcache::getInstance()->get($key)) {
                                JsMemcache::getInstance()->set($key, $name, $time);
                                exit("Please refresh after 5 seconds.");
                        } else
                                JsMemcache::getInstance()->set($key, $name, $time);
                }
        }
        
        
       /**
         * This function Moves entries to PICTURE_NEW & PICTURE_DELETE_NEW tables
         *@param - Array of picture Ids to be moved
	*/
	public function moveImageAfterScreened($paramArr)
        {
                $deleted=$paramArr["DELETE"];
                $approved=$paramArr["APPROVED"];
               
                if(count($deleted)>0 and $deleted[0]){
                        $DeleteArr=array("TYPE"=>$paramArr["TYPE"],"PROFILEID"=>$this->profileObj->getPROFILEID(),"PICTUREID"=>$deleted,"DELETE_REASON"=>$paramArr["DELETE_REASON"]);
                        $photoUpdateObj = new DeletedPictures();
                        $pictureUpdate=$photoUpdateObj->insertDeletedPhotoDetails($DeleteArr);
                }
                if(count($approved)>0){
                        $ApprovedArr=array("TYPE"=>$paramArr["TYPE"],"PROFILEID"=>$this->profileObj->getPROFILEID(),"PICTUREID"=>$approved,"ProfilePicId"=>$paramArr["ProfilePicId"]);
                        $pictureUpdate=$this->insertApprovedPhotoDetails($ApprovedArr);
                }
               // print_r($pictureUpdate);die;
                if(count($deleted)>0 || count($approved)>0)// TO clear PICTURE_SCREEN_NEW
                {
                    $clearPictureScreened["profileId"]=$this->profileObj->getPROFILEID();
                    if(!$deleted)
                            $deleted=array();
                    if(!$approved)
                            $approved=array();
                    $clearPictureScreened["picId"]=  array_merge($deleted,$approved);
                    $photoClearObj = new PICTURE_FOR_SCREEN_NEW();
                    $pictureUpdate=$photoClearObj->deleteRowsBasedOnPicId($clearPictureScreened);
                }
                //Compute And Store Profile Completion Score
                $cScoreObject = ProfileCompletionFactory::getInstance(null,null,$this->profileObj->getPROFILEID());
                $cScoreObject->updateProfileCompletionScore();

                // Flush memcache for header picture
                $memCacheObject = JsMemcache::getInstance();
				$memCacheObject->remove($this->profileObj->getPROFILEID() . "_THUMBNAIL_PHOTO");
           
        }
        /**
	This function is used to check if the number of files screened dont cross the maximum limit of 20.
  	@param $paramArr containing ProfileId & Number of approved photos
  	@return 1 for No error else 0 for Error
  	*/
	public function checkMaxPhotoCountError($paramArr)
	{
		$picture_new = new ScreenedPicture;
                $countScreened = $picture_new->getMaxOrdering($paramArr["profileId"]);		//Get count of already existing screened pics
                $this->screenedCount = $countScreened;
                $countApproved = count($paramArr["APPROVE"]);
                if(($countScreened-$paramArr["SCREENED_DELETED"])+$countApproved>(PictureStaticVariablesEnum::MAX_PICTURE_COUNT+1))
                        return 0;
                else
                        return 1;
	}
        /**
          This function is used move entries from PICTURE_FOR_SCREEN_NEW to PICTURE_NEW
          @param $paramArr containing ProfileId & Number of approved photos
          @return 1 for No error else 0 for Error
         */
        public function insertApprovedPhotoDetails($paramArr) {

                $screenedPicObj = new ScreenedPicture();
                $nonScreenedPicObj = new NonScreenedPicture();
                
                $pictureServiceObj = new PictureService($this->profileObj,'SCREENING');
                $photoDetails = $pictureServiceObj->getNonScreenedPhotos('album');
                if ($photoDetails) {
                        foreach ($photoDetails AS $key => $PICTURE) {
                                if (in_array($PICTURE->getPICTUREID(), $paramArr["PICTUREID"], TRUE)) {
                                        $newPicId = $pictureServiceObj->getPictureAutoIncrementId(); //Get new PICTURE ID for PICTURE_NEW
                                        $photoScreeningTrackingObj = new photoScreeningTracking();
                                        $photoScreeningTrackingObj->updateImageDetails($PICTURE->getPICTUREID(), $newPicId, $this->profileObj->getPROFILEID());

                                        //Dependent NonScreened Urls
                                        $thumbnail96NonScreenedUrl = $PICTURE->getMainPicUrl();
                                        $thumbnailNonScreenedUrl = $PICTURE->getProfilePic120Url();
                                        $searchPicNonScreenedUrl = $PICTURE->getProfilePicUrl();
                                        
                                        //Photo Dimensions
                                        if($PICTURE->getORDERING()==0){
                                                if($PICTURE->getMobileAppPicUrl())
                                                        $this->updateImageDimensions($newPicId,$PICTURE->getMobileAppPicUrl());
                                                elseif($PICTURE->getMainPicUrl())
                                                        $this->updateImageDimensions($newPicId,$PICTURE->getMainPicUrl());
                                        }
                                        $paramForDetails = array("PROFILEID" => $this->profileObj->getPROFILEID(), "PICTUREID" => $PICTUREID);
                                        foreach (ProfilePicturesTypeEnum::$PICTURE_UPLOAD_DIR AS $picName => $dirName) {
                                                if ($picName == "Thumbail96") {  // For saving thumbnail96 in Screened directory and adding watermark
                                                        if ($thumbnail96NonScreenedUrl) {

                                                                $linkArr = $this->getImageSavingDetails($thumbnail96NonScreenedUrl, $dirName, $newPicId, $PICTURE); // Getting Urls for saving
                                                                $pictureServiceObj->generateImages($dirName, $linkArr["url"], $linkArr["SavePicLink"], $PICTURE->getPICFORMAT()); //Save image
                                                                $this->watermarkOnImage($picName, $linkArr["SavePicLink"], $type,$PICTURE->getWATERMARK()); //Perform Watermarking

                                                                $PICTURE->setThumbail96Url($linkArr["NewPicLink"]);
                                                        }
                                                } elseif ($picName == "Thumbail") { // For saving thumbnail in Screened directory and adding watermark
                                                        if ($PICTURE->getORDERING() == 0 && $thumbnailNonScreenedUrl) {
                                                                $pictureFunctionObj = new PictureFunctions();
                                                                $linkArr = $this->getImageSavingDetails($thumbnailNonScreenedUrl, $dirName, $newPicId, $PICTURE); // Getting Urls for saving
                                                                //Creating Image
                                                                $type = $pictureFunctionObj->getImageFormatType($linkArr["url"]);
                                                                $image = $pictureFunctionObj->createImage($linkArr["url"]);
                                                                $image_p = imagecreatetruecolor($this->thumbnailX, $this->thumbnailY);
                                                                $ImageCreated = imagecopyresampled($image_p, $image, 0, 0, 0, 0, $this->thumbnailX, $this->thumbnailY, ProfilePicturesTypeEnum::$PICTURE_SIZES["ProfilePic120Url"]["w"], ProfilePicturesTypeEnum::$PICTURE_SIZES["ProfilePic120Url"]["h"]);

                                                                if ($ImageCreated) {

                                                                        $pictureFunctionObj->storeResizedImage($image_p, $linkArr["SavePicLink"], $type); //Storing Image
                                                                        $this->watermarkOnImage($picName, $linkArr["SavePicLink"], $type,$PICTURE->getWATERMARK()); //Watermarking
                                                                        $PICTURE->setThumbailUrl($linkArr["NewPicLink"]);
                                                                } else {
                                                                        $PICTURE->setThumbailUrl("");
                                                                }
                                                        } else {
                                                                $PICTURE->setThumbailUrl("");
                                                        }
                                                } elseif ($picName == "SearchPicUrl") {// For saving SearchPicUrl in Screened directory and adding watermark
                                                        if ($PICTURE->getORDERING() == 0 && $searchPicNonScreenedUrl) {
                                                                $pictureFunctionObj = new PictureFunctions();
                                                                
                                                                $linkArr = $this->getImageSavingDetails($searchPicNonScreenedUrl, $dirName, $newPicId, $PICTURE); // Getting Urls for saving
                                                                
                                                                $this->moveImageToScreened($linkArr["url"], $linkArr["SavePicLink"]); //Moving Image
                                                                $this->watermarkOnImage($picName, $linkArr["SavePicLink"], $PICTURE->getPICFORMAT(),$PICTURE->getWATERMARK()); //Watermarking
                                                                $pictureServiceObj->generateImages($dirName, $linkArr["SavePicLink"], $linkArr["SavePicLink"], $format); //Saving Image

                                                                $PICTURE->setSearchPicUrl($linkArr["NewPicLink"]);
                                                        } else {
                                                                $PICTURE->setSearchPicUrl("");
                                                        }
                                                } else { // For all image types whose own link exists
                                                        $getterURL = "get" . $picName;
                                                        $url = "";
                                                        $url = $PICTURE->$getterURL();
                                                        $url = PictureFunctions::getPictureDocUrl($url);
                                                        
                                                        //Moving Images from Picture_screen_new to PICTURE_NEW
                                                        if ($url) {

                                                                $linkArr = $this->getImageSavingDetails($url, $dirName, $newPicId, $PICTURE); // Getting Urls for saving
                                                                $copyOutput = $this->moveImageToScreened($linkArr["url"], $linkArr["SavePicLink"]); //Moving Image
                                                                $setterURL = "set" . $picName;
                                                                if ($copyOutput) {

                                                                        $this->watermarkOnImage($picName, $linkArr["SavePicLink"], $PICTURE->getPICFORMAT(),$PICTURE->getWATERMARK()); //Watermarking
                                                                        $PICTURE->$setterURL($linkArr["NewPicLink"]);
                                                                } else
                                                                        $PICTURE->$setterURL("");
                                                        }
                                                }
                                        } 
                                        if ($PICTURE->getPICTUREID() == $paramArr["ProfilePicId"] || (!$paramArr["ProfilePicId"] && $PICTURE->getORDERING()==0 && $PICTURE->getProfilePicUrl()))
                                                $paramArr["ProfilePicId"] = $newPicId;

                                        $dbEntryPicId[] = $newPicId;
                                        $dbEntryTitle[] = $PICTURE->getTITLE();
                                        $dbEntryKeywords[] = $PICTURE->getKEYWORD();
                                        $MainPicUrl[] = $PICTURE->getMainPicUrl();
                                        $ProfilePicUrl[] = $PICTURE->getProfilePicUrl();
                                        $ThumbailUrl[] = $PICTURE->getThumbailUrl();
                                        $Thumbail96Url[] = $PICTURE->getThumbail96Url();
                                        $MobileAppPicUrl[] = $PICTURE->getMobileAppPicUrl();
                                        $ProfilePic120Url[] = $PICTURE->getProfilePic120Url();
                                        $ProfilePic235Url[] = $PICTURE->getProfilePic235Url();
                                        $ProfilePic450Url[] = $PICTURE->getProfilePic450Url();
                                        $OriginalPicUrl[] = $PICTURE->getOriginalPicUrl();
                                        $PicFormat[] = $PICTURE->getPICFORMAT();
                                        $SearchPicUrl[] = $PICTURE->getSearchPicUrl();
                                }
                        }
                }
                //Transaction   
                if ($dbEntryPicId) {
                        if(!isset($this->screenedCount))
                        {
                                $picture_new = new ScreenedPicture;
                                $countScreened = $picture_new->getMaxOrdering($paramArr["PROFILEID"]);		//Get count of already existing screened pics
                                $this->screenedCount = $countScreened;
                        }
                        $this->countBeforeScreening =  $this->screenedCount + 1;

                        $pictureNew = new ScreenedPicture;
                        $pictureNew->startTransaction();
                        $dbActionOutput = $this->performDbAction($this->profileObj->getPROFILEID(), $dbEntryPicId, $dbEntryTitle, $dbEntryKeywords, $pictureNew, $MainPicUrl, $ProfilePicUrl, $ThumbailUrl, $Thumbail96Url, $MobileAppPicUrl, $ProfilePic120Url, $ProfilePic235Url, $ProfilePic450Url, $OriginalPicUrl, $PicFormat, $SearchPicUrl);  //Perform insert queries on PICTURE_NEW
                        $pictureNew->commitTransaction();
                }
                //End Transaction
                $ProfilePicId=$paramArr["ProfilePicId"];
                if ($ProfilePicId) {  //Profile pic is changed then perform swapping
                        $currentProfilePicObj = $pictureServiceObj->getScreenedPhotos("profilePic");

                        if(!$currentProfilePicObj)
                        {
                                $this->updateScreenedPhotosOrdering($paramArr["PROFILEID"]);
                                $currentProfilePicObj = $pictureServiceObj->getScreenedPhotos("profilePic");
                        }

                        $whereArr["PICTUREID"] = $ProfilePicId;
                        $currentPicObj = $pictureServiceObj->getPicDetails($whereArr);

                        if ($currentPicObj[0]->getORDERING() != $currentProfilePicObj->getORDERING()) {
                                $status = $this->updateFinalOrdering($currentProfilePicObj, $currentPicObj, $this->profileObj->getPROFILEID());
                                if (!$status)
                                        return "Error in changing the ordering.";
                        }
                }
                //Deleting Pics from PICTURE_NEW
                $this->deleteScreenedPhotoEntries($paramArr['PICTUREID']);
                $this->updateScreenedPhotosOrdering($paramArr["PROFILEID"]);
                if($dbEntryPicId)
                    $this->triggerAutoReminderMail($paramArr);
                
                // Flush memcache for header picture
                $memCacheObject = JsMemcache::getInstance();
				$memCacheObject->remove($this->profileObj->getPROFILEID() . "_THUMBNAIL_PHOTO");                  
        }

        
        public function triggerAutoReminderMail($paramArr){
        if($this->countBeforeScreening > self::AUTO_REMINDER_MAIL_MAX_COUNT) return false;
        $picture_new = new ScreenedPicture;
        $countScreened = $picture_new->getMaxOrdering($paramArr["PROFILEID"]) + 1 ;		//Get count of already existing screened pics

        if($countScreened <= $this->countBeforeScreening)return false;    
        $producerObj=new Producer();
        if($producerObj->getRabbitMQServerConnected())
        {
            $sender = $paramArr["PROFILEID"];
            $sendMailData = array('process' =>'MAIL','data'=>array('type' => 'PHOTO_SCREENED','body'=>array('senderid'=>$sender ), 'redeliveryCount'=>0 ));
            $producerObj->sendMessage($sendMailData);
            return true;
        }
            
        }
        /*This function is used upload pictures from Process Interface
	*@param formArr : form array
	*@return output : either error message or array of count and success message
	*/	
	public function processUpload($formArr,$ops=false,$filesGlobArr='')
        {
                $photoFileServiceObj = new photoFileService();
		if(!$ops)
			$output = $photoFileServiceObj->fileValidate($formArr);
		elseif(is_array($filesGlobArr))
			$output="Success";
		else
			$output = "Err..issue with cropping photos";
		$count =0;
                if($output=="Success")
                {
			
                        if ($_FILES["uploadPhotoNonScr"] || $filesGlobArr)
                        {
                                $pictureServiceObj =new PictureService($this->profileObj,'SCREENING');
                                $pictureObj = new NonScreenedPicture('SCREENING');
                                $pictureFunctionsObj = new PictureFunctions();
                                foreach ($_FILES["uploadPhotoNonScr"]["name"] as $k=>$v)
                                {
						$split = explode("/",$_FILES["uploadPhotoNonScr"]["type"][$k]);
                                                $imageT = $split[1];
                                        if(array_key_exists($k,ProfilePicturesTypeEnum::$PICTURE_SIZES))
                                        {
                                                $pid = $formArr["picIdNonScr"][1];
                                                $picSaveUrl = $pictureObj->getSaveUrlPicture(ProfilePicturesTypeEnum::$PICTURE_UPLOAD_DIR[$k],$pid,$this->profileObj->getPROFILEID(),$imageT,'nonScreened');
						$picUrl =  $pictureObj->getDisplayPicUrl(ProfilePicturesTypeEnum::$PICTURE_UPLOAD_DIR[$k],$pid,$this->profileObj->getPROFILEID(),$imageT,'nonScreened');
						$result = $pictureFunctionsObj->moveImage($_FILES["uploadPhotoNonScr"]["tmp_name"][$k],$picSaveUrl);
						if($result)
						{
                                                	$picturesToUpdate[$pid][$k]=$picUrl;
							$count++;
						}
                                        }
					else
					{
						$pid = $formArr["picIdNonScr"][$k];
						$picSaveUrl = $pictureObj->getSaveUrlPicture(ProfilePicturesTypeEnum::$PICTURE_UPLOAD_DIR["MainPicUrl"],$pid,$this->profileObj->getPROFILEID(),$imageT,'nonScreened');
						$picUrl = $pictureObj->getDisplayPicUrl(ProfilePicturesTypeEnum::$PICTURE_UPLOAD_DIR["MainPicUrl"],$pid,$this->profileObj->getPROFILEID(),$imageT,'nonScreened');
						chmod($picSaveUrl,0777);
						$result = $pictureFunctionsObj->moveImage($_FILES["uploadPhotoNonScr"]["tmp_name"][$k],$picSaveUrl);
						if($result)
						{
							$picturesToUpdate[$pid]["MainPicUrl"]=$picUrl;
							$count++;
						}
					}
                                }
                                if(array_key_exists("Save",$formArr)&& $formArr['Save']=="Save")
                                {
                                foreach ($filesGlobArr["uploadPhotoNonScr"]['name'] as $k=>$v)
                                {
					$imageT = $filesGlobArr["uploadPhotoNonScr"]["type"][$k];
                                        if(array_key_exists($k,ProfilePicturesTypeEnum::$PICTURE_SIZES))
                                        {
                                                $pid = $formArr["picIdNonScr"][1];
                                                $picSaveUrl = $pictureObj->getSaveUrlPicture(ProfilePicturesTypeEnum::$PICTURE_UPLOAD_DIR[$k],$pid,$this->profileObj->getPROFILEID(),$imageT,'nonScreened');
                                                $picUrl =  $pictureObj->getDisplayPicUrl(ProfilePicturesTypeEnum::$PICTURE_UPLOAD_DIR[$k],$pid,$this->profileObj->getPROFILEID(),$imageT,'nonScreened');
                                        //        $result = $pictureFunctionsObj->moveImage($_FILES["uploadPhotoNonScr"]["tmp_name"][$k],$picSaveUrl);
						$manipulator = new ImageManipulator();
						$result = $manipulator->save($v,$picSaveUrl,$imageT);
						$picturesToUpdate[$pid][$k]=$picUrl;
						$count++;
                                        }
                                        else
                                        {
/*
                                                $pid = $formArr["picIdNonScr"][1];
                                                $picSaveUrl = $pictureObj->getSaveUrlPicture(ProfilePicturesTypeEnum::$PICTURE_UPLOAD_DIR["MainPicUrl"],$pid,$this->profileObj->getPROFILEID(),$imageT,'nonScreened');
                                                $picUrl = $pictureObj->getDisplayPicUrl(ProfilePicturesTypeEnum::$PICTURE_UPLOAD_DIR["MainPicUrl"],$pid,$this->profileObj->getPROFILEID(),$imageT,'nonScreened');
						$manipulator = new ImageManipulator();
						$result = $manipulator->save($v,$picSaveUrl,$imageT);
						$picturesToUpdate[$pid]["MainPicUrl"]=$picUrl;
						$count++;
*/
                                        }
				}
				}
				$pictureServiceObj->setPicProgressBit(ProfilePicturesTypeEnum::$INTERFACE["2"],$picturesToUpdate);
				$profileScreened = "0";
				$statusArr =$pictureObj->profilePictureStatusArr($this->profileObj->getPROFILEID());
				if($this->isProfileScreened() == 1) //
				{

					$moveArr = array("DELETE" => $statusArr["DELETED"], "APPROVED" => $statusArr["APPROVED"], "ProfilePicId" => $statusArr["profilePic"], "TYPE" => "N");
					$this->moveImageAfterScreened($moveArr);	
					$profileScreened = "1";
					if($statusArr["DELETED"])
                                       	{
                                	        $deleteNonScreenedObj = new PICTURE_DELETE_NEW;
                                        	$statusArr["REJECT_REASON"] = $deleteNonScreenedObj->getDeletionReason($statusArr["DELETED"]["0"]);
                                	}
                                	else
                                        	$statusArr["REJECT_REASON"]="";
				}
                        }
			$returnArray = array();
			$returnArray["message"]= "Success";
			$returnArray["count"] = $count;
			$returnArray["notify"] = $profileScreened;
			$returnArray["statusArr"] = $statusArr;
			return $returnArray;
			
                }
		return $output;
        }

	/*This function is used to track and notify after process interface
	*@param paramArray : array of attributes required for tracking and notifying for process interface
	*/
	public function trackProcessInterface($paramArray)
	{
		$Arr=JsPhotoScreen_Enum::$arrTRACKING_PARAMS;
		$masterTrackNeeded = true;
		if($paramArray["source"] == PictureStaticVariablesEnum::$SOURCE["MASTER"])
			$masterTrackNeeded = false;
		if ($paramArray["source"] == PictureStaticVariablesEnum::$SOURCE["MAIL"] || $paramArray["source"] == PictureStaticVariablesEnum::$SOURCE["MASTER"])
		{
                	if ($this->profileObj->getHAVEPHOTO() == PictureStaticVariablesEnum::$HAVE_PHOTO_STATUS["YES"])
                        	$source = PictureStaticVariablesEnum::$SOURCE["EDIT"];
                        else
                        	$source = PictureStaticVariablesEnum::$SOURCE["NEW"];
                }
		else
			$source = $paramArray["source"];
		$trackParamArray = array(
			$Arr["EXECUTIVE_NAME"] => $paramArray["name"],
			$Arr["PROFILEID"] => $this->profileObj->getPROFILEID(),
			$Arr["NUM_APPROVED_PIC"] => $paramArray["count"],
			$Arr["SOURCE"] => $source,
			$Arr["PIC_DATA"]=> $paramArray["PIC_DATA"],
            $Arr["INTERFACE"]=> ProfilePicturesTypeEnum::$INTERFACE["2"],
			$Arr["MASTER_TRACK_NEEDED"]=>$masterTrackNeeded,
            $Arr["PHOTO_UPLOAD_TIME"]=>end($paramArray["picDataForTracking"])["UPDATED_TIMESTAMP"]
		);
		$trackingObj = new JsPhotoScreen_TrackingManager($trackParamArray);
		if($paramArray["notify"] =="1")
		{
			//Notification is done only if profile is screened completely
                	$notifyParamArr = $this->prepareParameter("NOTIFY", $paramArray["statusArr"]); // Data Required for Update,tracking & notification Functions
                	$notifyObj = new JsPhotoScreen_Notify($notifyParamArr);
                	$notifyObj->notifyUser();
		}
	}
        public function rotationOfImage($pictureRotation)
	{ 
                $pictureServiceObj = new PictureService($this->profileObj,'SCREENING');
                $photoDetails = $pictureServiceObj->getNonScreenedPhotos('album');
                foreach($photoDetails as $pic=>$picObj ){
                        if(in_array($picObj->getPICTUREID(),array_keys($pictureRotation))){
                                $imageUrl = $picObj->getLocalMainPicUrl();
                                $this->rotateImage($imageUrl,$pictureRotation[$picObj->getPICTUREID()],$picObj->getPICFORMAT());
                        }
                }
	}
        public function saveWatermarkDecision($watermark){
               $paramArr["watermark"]=$watermark;
               $paramArr["profileId"]=$this->profileObj->getPROFILEID();
               $nonScreenedPictureObj = new PICTURE_FOR_SCREEN_NEW();
               $picture = $nonScreenedPictureObj->setWatermark($paramArr);
        }

      /** 
	* This function will Check if photo approved
	* @param pictureName, ScreenBit
	* @return 1 for True 
	*/
	public static function isPhotoApproved($pictureType,$screenbit)
	{ 
		if(($pictureType=="nonProfilePic" && $screenbit==ProfilePicturesTypeEnum::$SCREEN_BITS["APPROVE"]) || ($pictureType!="nonProfilePic" && $screenbit[(array_flip(array_merge(ProfilePicturesTypeEnum::$SCREEN_BIT_POSITION,array_keys(ProfilePicturesTypeEnum::$PICTURE_SIZES)))[$pictureType])]==ProfilePicturesTypeEnum::$SCREEN_BITS["APPROVE"])){
                        return 1;        
                }
                return 0;
	}
        /** 
	* This function will Check if Photo Got resized
	* @param pictureName, ScreenBit
	* @return 1 for True 
	*/
	public static function isPhotoResized($pictureType,$screenbit)
	{
		if(($pictureType=="nonProfilePic" && $screenbit==ProfilePicturesTypeEnum::$SCREEN_BITS["RESIZE"]) || ($pictureType!="nonProfilePic" && $screenbit[(array_flip(array_keys(ProfilePicturesTypeEnum::$SCREEN_BIT_POSITION))["MainPicUrl"])]==ProfilePicturesTypeEnum::$SCREEN_BITS["APPROVE"])){
                        return 1;        
                }
                return 0;
	}
        /** 
	* This function will Check if photo delete
	* @param pictureName, ScreenBit
	* @return 1 for True 
	*/
	public static function isPhotoDeleted($pictureType,$screenbit)
	{
		if(($pictureType=="nonProfilePic" && $screenbit==ProfilePicturesTypeEnum::$SCREEN_BITS["DELETE"]) || ($pictureType!="nonProfilePic" && $screenbit[(array_flip(array_merge(ProfilePicturesTypeEnum::$SCREEN_BIT_POSITION,array_keys(ProfilePicturesTypeEnum::$PICTURE_SIZES)))[$pictureType])]==ProfilePicturesTypeEnum::$SCREEN_BITS["DELETE"])){
                        return 1;        
                }
                return 0;
	}
        /** 
	* This function will Check if photo processing
	* @param pictureName, ScreenBit
	* @return 1 for True 
	*/
	public static function isPhotoProcessing($pictureType,$screenbit)
	{
		
                if(($pictureType=="nonProfilePic" && $screenbit==ProfilePicturesTypeEnum::$SCREEN_BITS["EDIT"]) || ($pictureType!="nonProfilePic" && $screenbit[(array_flip(array_merge(ProfilePicturesTypeEnum::$SCREEN_BIT_POSITION,array_keys(ProfilePicturesTypeEnum::$PICTURE_SIZES)))[$pictureType])]==ProfilePicturesTypeEnum::$SCREEN_BITS["EDIT"])){
                        return 1;        
                }
                return 0;
	}
        /** 
	* This function will Check if photo processing
	* @param pictureName, ScreenBit
	* @return 1 for True 
	*/
	public static function isPhotoDefault($pictureType,$screenbit)
	{
		
                if(($pictureType=="nonProfilePic" && $screenbit==ProfilePicturesTypeEnum::$SCREEN_BITS["DEFAULT"]) || ($pictureType!="nonProfilePic" && $screenbit[(array_flip(array_merge(ProfilePicturesTypeEnum::$SCREEN_BIT_POSITION,array_keys(ProfilePicturesTypeEnum::$PICTURE_SIZES)))[$pictureType])]==ProfilePicturesTypeEnum::$SCREEN_BITS["DEFAULT"])){
                        return 1;        
                }
                return 0;
	}
        public function rotateImage($filename,$degrees, $format) {

		if(!$format)
			$format = PictureFunctions::getImageFormatType($filename);
                if($degrees>0)
                        $degrees=360-$degrees;
                if($degrees<0)
                        $degrees=0-$degrees;
                if ($format=="jpg" || $format=="jpeg")
                        $source=imagecreatefromjpeg($filename);
                elseif ($format=="gif")
                        $source = imagecreatefromgif($filename);
		//echo $source."--".$filename."::".$format."::".$degrees;
                $rotate = imagerotate($source, $degrees, 0);
                
                if ($format=="jpg" || $format=="jpeg")
                        imagejpeg($rotate,$filename);
                elseif ($format=="gif")
                        imagegif($rotate,$filename);
                
                imagedestroy($source);
                imagedestroy($rotate);
        }

        public function watermarkOnImage($picName,$SaveLink,$format,$watermarkOrNot)
        { 
                if(in_array($picName,ProfilePicturesTypeEnum::$WATERMARK)){
                        if ($watermarkOrNot%ProfilePicturesTypeEnum::$PICTURE_WATERMARK[array_flip(ProfilePicturesTypeEnum::$WATERMARK)[$picName]] == 0) {
                                $pictureServiceObj = new PictureService($this->profileObj);

                                $size = ((str_replace('"', "", explode("height=", getimagesize($SaveLink)["3"])[1])) > 250) ? "main" : "profile";

                                if (in_array($picName, ProfilePicturesTypeEnum::$WATERMARK))
                                        $pictureServiceObj->generateImages("watermark", $SaveLink, $size, $format);
                        }
                }
        }
        public function updateImageDimensions($newPicId,$url){
                $size = getimagesize($url);
                $mobAppPicSizeObj = new PICTURE_MobAppPicSize();
                $mobAppPicSizeObj->updateImageSize($newPicId,$size);
        }
        public function getImageSavingDetails($url,$dirName,$newPicId,$PICTURE){
                $nonScreenedPicObj = new NonScreenedPicture();
                $Arr["url"] = PictureFunctions::getPictureDocUrl($url);
                $Arr["SavePicLink"] = $nonScreenedPicObj->getSaveUrlPicture($dirName, $newPicId, $this->profileObj->getPROFILEID(), $PICTURE->getPICFORMAT(), 'screened');
                $Arr["NewPicLink"] = $nonScreenedPicObj->getDisplayPicUrlPicture($dirName, $newPicId, $this->profileObj->getPROFILEID(), $PICTURE->getPICFORMAT(), 'screened');
                return $Arr;
        }
        

	/*This function is used to move images from mail to non screened state
	*@param $name :name of operator
	*@mailId : mail id assigned
	*/
	public function movePhotosFromMailToNonScreened($name,$mailId)
	{
		$updateScreeningStatus = new SCREEN_PHOTOS_FROM_MAIL();
		$profileId = $this->profileObj->getPROFILEID();
                $updateScreeningStatus->updateScreeningStatus($name, $mailId, $profileId);		
		$pictureServiceObj = new PictureService($this->profileObj);	
		$output = $pictureServiceObj->saveAlbumFromMail($mailId);
		$updateScreeningStatus->logScreeningAction($profileId, $mailId,"NA","NA","1");
	}

	/*This function is used to give final status of profile for photo screening
	*@param profile id : profile id
	*@return interface : interface it should open for screening
	*/
	public function photoScreeningProfileStatus($profileId)
	{
		$status=$this->pictureScreenStatus($profileId);
		$edit = 0;
                $preprocessing = 0;

		if(!$status)
			$interface = 2;
		else{
			foreach($status as $picturId=>$status){
				if($status["STATUS"]==array_flip(PictureStaticVariablesEnum::$PICTURE_STATUS)["UPLOAD_COMPLETED"] || $status["STATUS"]==array_flip(PictureStaticVariablesEnum::$PICTURE_STATUS)["RESIZE_CRON_COMPLETED"]){
				       $preprocessing = 1;
				}
				elseif($status["STATUS"]==array_flip(PictureStaticVariablesEnum::$PICTURE_STATUS)["PROCESS_QUEUE"]){
				       $edit = 1;
				}


			}
			if($preprocessing ==1)
				$interface = 0;
			elseif($edit == 1)
				$interface = 3;
			else
				$interface = 2;
		}
		return $interface;
	}	
        
}
?>
