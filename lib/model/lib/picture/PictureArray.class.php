<?php
/**
This class is used to fetch pictureObjs of multiple profiles in one single query.
**/
class PictureArray
{
	private $pictureArr;
	private $viewedObjArr;
	private $viewerObj;

	private $registeredPrivacy = PhotoProfilePrivacy::registeredPrivacy;
	private $filteredPrivacy = PhotoProfilePrivacy::filteredPrivacy;
	private $nonRestrictedPrivacy = PhotoProfilePrivacy::nonRestrictedPrivacy;
	private $hiddenPrivacy = PhotoProfilePrivacy::hiddenPrivacy;
	private $photoVisibleIfContactAccepted = PhotoProfilePrivacy::photoVisibleIfContactAccepted;
	private $photoVisibleToAll = PhotoProfilePrivacy::photoVisibleToAll;
	private $viewedProfilesRequiredDetails = array("PROFILEID","PHOTO_DISPLAY","PRIVACY","HAVEPHOTO","GENDER");
	private $viewerProfilesRequiredDetails = "PHOTO_DISPLAY,PRIVACY,HAVEPHOTO,PHOTOSCREEN,AGE,INCOME,MSTATUS,COUNTRY_RES,RELIGION,CASTE,MTONGUE,CITY_RES,GENDER";
	private $viewedPartnerProfilesRequiredDetails = "LAGE,HAGE,PARTNER_RELIGION,PARTNER_CASTE,PARTNER_MTONGUE,PARTNER_COUNTRYRES AS COUNTRY_RES,PARTNER_CITYRES AS CITY_RES,PARTNER_MSTATUS,PARTNER_INCOME,PROFILEID";

	public function __construct($viewedObjArr='')
	{
		$this->viewedObjArr = $viewedObjArr;
//		$this->pictureArr = array();
	}

	/**
	  * This function is to be called to get multiple picture Objects
	  * @param - $this->viewedObjArr - An array of profile object of profiles for which the profile photos are to be fetched (with the attributes PHOTO_DISPLAY,PRIVACY,HAVEPHOTO,GENDER)
	  * @param - $skipProfilePrivacy - If this flag is set as Y, then profile filter condition will not be checked for any of the profiles
	  * @param - $viewedDppArr - An array of DPP values of the profiles whose JPROFILE.PRIVACY=F
	  * @param - $viewerObj - Profile Object of the user logged in or to whom the mailer is to be sent
	  * @param - $skipContacts - If this flag is set as Y, then we would consider an Accepted contact between viewer and viewed profiles
	  * @param - $contactsBetweenViewedAndViewer - An array of contacts between the logged in profile and the profiles for which photo is to be fetched
			Refer ContactsRecord->getContactsSent() to get the format in which the contacts array needs to be given

	  * @param - $isMobile - Set this value as 'Y' for WAP site
	  * @param - $skipPrivacyFilterArr - An array of profiles for which filters are not to be checked
	  * @return - Array of Picture Objects of all profiles having Photos
	**/

	public function getProfilePhoto($photoType = 'N', $skipProfilePrivacy='',$viewedDppArr='',$viewerObj='',$skipContacts='',$contactsBetweenViewedAndViewer='',$isMobile='',$dbname='',$skipPrivacyFilterArr='')
	{
                if($dbname == ''){
                        $dbname = 'newjs_master';
                }
		$this->viewerObj = $viewerObj;
		//checking if atleast one profile is passed for which photo is to be fetched
		if(!is_array($this->viewedObjArr))
		{
			throw new jsException("","NO PROFILEIDS PASSED");
		}

		//checking the login status of the viewer and making the profile object if it doesn't exist : start
		if(!$this->viewerObj)
		{
			if(strstr($_SERVER['PHP_SELF'],'symfony_index.php'))
			{
				$this->viewerObj=LoggedInProfile::getInstance($dbname);
			}
			else
			{
				global $data;
				$this->viewerObj=LoggedInProfile::getInstance($dbname,$data['PROFILEID']);
			}

			if(!$this->viewerObj || $this->viewerObj->getPROFILEID() == '')
			{
				unset($this->viewerObj);
				$login = 'N';
			}
			else
				$login = 'Y';
		}
		else
			$login = 'Y';
		//checking the login status of the viewer and making the profile object if it doesn't exist : end

		//getting viewer fields from JPROFILE
		if($this->viewerObj)
		{
			$arrOfValues = explode(",",$this->viewerProfilesRequiredDetails);
			$blankValues = CommonUtility::getBlankValues($this->viewerObj, $arrOfValues);
			if($blankValues != '')
				$this->viewerObj->getDetail("","","$blankValues");
		}

		//if the dpp of the profiles is not passed, then query the dpp for profiles whose JPROFILE.PRIVACY=F for logged-in and mailer cases
		if($skipProfilePrivacy != 'Y' && !is_array($viewedDppArr) && $login == 'Y')
		{
			foreach($this->viewedObjArr as $viewedObj)
			{
				if($viewedObj->getPRIVACY() == $this->filteredPrivacy)
				{
					if(!$skipPrivacyFilterArr || !in_array($viewedObj->getPROFILEID(), $skipPrivacyFilterArr))
						$profilesWithPrivacySet[]=$viewedObj->getPROFILEID();
				}
			}
			if(is_array($profilesWithPrivacySet))
				$viewedProfilesWithShards = JsDbSharding::getShardNumberForMultipleProfiles($profilesWithPrivacySet);

			//getting dpp of profiles with privacy set as F from JPARTNER
			if(is_array($viewedProfilesWithShards))
			{
				$jpartnerObj = new PartnerProfileArray();
				foreach($viewedProfilesWithShards as $shardDbName => $profileArr)
				{
					if(is_array($profileArr) && sizeof($profileArr)>0)
					{
						$pidArr = array_keys($profileArr);
						$dppArr = $jpartnerObj->getDppForMultipleProfiles($pidArr,$shardDbName,$this->viewedPartnerProfilesRequiredDetails);
						if(is_array($dppArr))
						{
							foreach($dppArr as $profileid => $dpp)
							{
								foreach($dpp as $key=>$dpp2)
								{
									if($key != 'PROFILEID')
										$viewedDppArr[$profileid][str_replace("PARTNER_","",$key)]=explode(",",str_replace("'","",$dpp2));
								}
							}
						}
					}
				}
			}
		}
		else if (is_array($viewedDppArr))
		{
			foreach($this->viewedObjArr as $viewedObj)
			{
				if($viewedObj->getPRIVACY() == $this->filteredPrivacy)
				{
					if(!$skipPrivacyFilterArr || !in_array($viewedObj->getPROFILEID(), $skipPrivacyFilterArr))
						$profilesWithPrivacySet[]=$viewedObj->getPROFILEID();
				}
			}
		}

		//checking if logged in profile passes filters of the profiles in viewedProfiles : start
		if($skipProfilePrivacy != 'Y')
		{
			if($this->viewerObj && $profilesWithPrivacySet && is_array($viewedDppArr))
			{
				$viewedFilterParameters = MultipleUserFilter::getFilterParameters($profilesWithPrivacySet,$dbname);
				$viewerParameters = $this->viewerObj->getFilterParameters();
				$filterObj = new MultipleUserFilter($viewerParameters, $viewedFilterParameters, $viewedDppArr, $this->viewerObj->getPROFILEID(), $profilesWithPrivacySet);
				$profilesPassingFilters = $filterObj->checkIfProfileMatchesDpp();
			}
			else if ($this->viewerObj && $profilesWithPrivacySet)
			{
				foreach($profilesWithPrivacySet as $profile1)
					$profilesPassingFilters[$profile1] = 1;
			}
		}
		//checking if logged in profile passes filters of the profiles in viewedProfiles : end
		//if $skipProfilePrivacy flag is set, then set all the profiles with JPROFILE.PRIVACY AS 'F' to filters passed : start
		else
		{
			foreach($this->viewedObjArr as $viewedObj)
			{
				if($viewedObj->getPRIVACY() == $this->filteredPrivacy)
				{
					$profilesPassingFilters[$viewedObj->getPROFILEID()] = 1;
				}
			}
		}
		//if $skipProfilePrivacy flag is set, then set all the profiles with JPROFILE.PRIVACY AS 'F' to filters passed : end

		//if contacts are to be considered and contact details are not passed, then query them from contacts table
		//contacts would be fetched for profiles with either JPROFILE.PHOTO_DISPLAY as C or JPROFILE.PRIVACY as F
		if($skipContacts != 'Y' && !is_array($contactsBetweenViewedAndViewer))
		{
			foreach($this->viewedObjArr as $viewedObj)
			{
				if($viewedObj->getPRIVACY() == $this->filteredPrivacy || $viewedObj->getPHOTO_DISPLAY() == $this->photoVisibleIfContactAccepted || $viewedObj->getPRIVACY() == $this->hiddenPrivacy)
				{
					$profilesWithRestrictedPhotoSettings[]=$viewedObj->getPROFILEID();
				}
			}
			if($this->viewerObj && is_array($profilesWithRestrictedPhotoSettings))
			{
				$profilesWithRestrictedPhotoSettings[]=$this->viewerObj->getPROFILEID();
				$profileIdStr = implode(",",$profilesWithRestrictedPhotoSettings);
				if($profileIdStr != '')
				{
					$contactsObj = new ContactsRecords();
					$contactsResults = $contactsObj->getContactsSent($profileIdStr, $profileIdStr,$this->viewerObj);
					$contactsBetweenViewedAndViewer = $contactsResults['allContacts'];
				}
			}
		}
		//if $skipContacts flag is set, then set the contact status between viewer and viewed profile to 'showPhoto'.
		//In this case photo would be shown even in cases where JPROFILE.PHOTO_DISPLAY = C
		elseif($skipContacts == 'Y')
		{
			foreach($this->viewedObjArr as $viewedObj)
			{
				if($viewedObj->getPRIVACY() == $this->filteredPrivacy || $viewedObj->getPHOTO_DISPLAY() == $this->photoVisibleIfContactAccepted || $viewedObj->getPRIVACY() == $this->hiddenPrivacy)
				{
					$contactsBetweenViewedAndViewer[$viewedObj->getPROFILEID()]='showPhoto';
				}
			}
		}
		//print_r($contactsBetweenViewedAndViewer);
		//echo "---";

		//Since the table newjs.PICTURE_DISPLAY_LOGIC is being used to decide whether the photo is displayed or not, I am passing 'showPhoto', so that I get all photos and then based on the table it would be decided which all would be shown.
		/*Implementation of this condition is changed by Reshu Rajput
		  If value is empty for a particular viewedObj then the contact is retrieved and contactsBetweenViewedAndViewer is set
		*/
		if(is_array($contactsBetweenViewedAndViewer))
		{
			foreach($contactsBetweenViewedAndViewer as $k=>$v)
			{
				if($v !="")
					$tempContacts[$k] = 'showPhoto';
				else
				{
					foreach($this->viewedObjArr as $viewObj)
		                        {
						if($viewObj->getPROFILEID() == $k)
							$viewedObj = $viewObj;
					}
					if($viewedObj->getPRIVACY() == $this->filteredPrivacy || $viewedObj->getPHOTO_DISPLAY() == $this->photoVisibleIfContactAccepted || $viewedObj->getPRIVACY() == $this->hiddenPrivacy)
	                                {
        			                $profilesWithRestrictedPhotoSettingsTemp[]=$viewedObj->getPROFILEID();
                                	}
					
				}
			}
			if(is_array($profilesWithRestrictedPhotoSettingsTemp))
			{
				$profilesWithRestrictedPhotoSettingsTemp[]=$this->viewerObj->getPROFILEID();
                                $profileIdStr = implode(",",$profilesWithRestrictedPhotoSettingsTemp);
                                $contactsObj = new ContactsRecords();
                                $contactsResultsTemp = $contactsObj->getContactsSent($profileIdStr, $profileIdStr,$this->viewerObj);
				foreach($contactsResultsTemp["allContacts"] as $k=>$v)
				{
                        		$contactsBetweenViewedAndViewer[$k] = $v;
                        		$tempContacts[$k] = $contactsBetweenViewedAndViewer[$k] ;
				}
				unset($contactsResultsTemp);
			}
			unset($profilesWithRestrictedPhotoSettingsTemp);
			
		}
		if($isMobile == 'Y')
			$photoObjArr = $this->getProfilePics($this->viewedObjArr,$tempContacts,'mobile',1,$photoType);
		else
			$photoObjArr = $this->getProfilePics($this->viewedObjArr,$tempContacts,'',1,$photoType);
                
                
		unset($tempContacts); 
		$pictureDisplayLogic = FieldMap::getFieldLabel('photo_display_logic', '', 1);

		/* forming string to decide which photos to serve */
		foreach($this->viewedObjArr as $viewedObj)
		{
			$viewedId = $viewedObj->getPROFILEID();
			$genderArr[$viewedId] = $viewedObj->getGENDER();
			if($photoObjArr[$viewedId])
			{
				$havephoto = $viewedObj->getHAVEPHOTO();
				$privacy = $viewedObj->getPRIVACY();
				$photoDisplay = $viewedObj->getPHOTO_DISPLAY();
				if($privacy == '')
					$privacy = $this->nonRestrictedPrivacy;
				if($privacy == $this->hiddenPrivacy)
				{
					$privacy = $this->nonRestrictedPrivacy;
					$photoDisplay == $this->photoVisibleIfContactAccepted;
				}
				if($photoDisplay == '')
					$photoDisplay = $this->photoVisibleToAll;
				//check login status

				if($login == 'N' || $privacy == $this->nonRestrictedPrivacy || $privacy == $this->registeredPrivacy)
					$filtersPassed = 'D';
				elseif($profilesPassingFilters[$viewedId] == 1)
					$filtersPassed = 'Y';
				else
					$filtersPassed = 'N';

				if($login == 'N' || ($photoDisplay == $this->photoVisibleToAll && ($privacy == $this->registeredPrivacy || $privacy == $this->nonRestrictedPrivacy)))
					$contactStatus = 'DM';
				else
					$contactStatus = $contactsBetweenViewedAndViewer[$viewedId];
				if($contactStatus == 'showPhoto')
					$contactStatus = 'A';
				elseif($contactStatus == '')
					$contactStatus = 'N';
				$str = $havephoto.$photoDisplay.$privacy.$login.$filtersPassed.$contactStatus;
			}
			else
			{
				//no photo present
				if($havephoto == 'N' || $havephoto == '')
					$str = 'N';
			}
/*
			if($havephoto == 'N' || $havephoto == '')
				$str = 'N';
			else
				$str = $havephoto.$photoDisplay.$privacy.$login.$filtersPassed.$contactStatus;
*/

			$finalResult[$viewedId] = $pictureDisplayLogic[$str];

			if($this->viewerObj && $this->viewerObj->getPROFILEID() == $viewedId)
				$loggedInResult[$viewedObj->getPROFILEID()] = 1;
/*
			if(get_class($viewedObj) == 'LoggedInProfile' && $photoType = 'N')
				$loggedInResult[$viewedObj->getPROFILEID()] = 1;
*/
		}
		foreach($finalResult as $viewedId=>$result)
		{
			//if a loggedInObject is passed as a viewedObj, then the photo is shown irrespective of any PRIVACY or PHOTO_DISPLAY checks.
//			if($this->viewerObj && $this->viewerObj->getPROFILEID()==$viewedId)
//				$result = 'yes';
			if($loggedInResult[$viewedId] == 1)
				$result = 'yes';
			if($photoObjArr[$viewedId])
			{
				$photoObjArr[$viewedId]->setIsPhotoShown($result);
				if($result == 'yes')
				{
					$finalPhotoObj[$viewedId] = $photoObjArr[$viewedId];
				}
				else
				{
					$finalPhotoObj[$viewedId] = $this->setPhotoUrlsForNoOrHiddenPhotos($photoObjArr[$viewedId],$result,$genderArr[$viewedId],$isMobile);
					//set photos for various filter, contacts, etc cases
				}
			}
			else if(!$finalPhotoObj[$viewedId])
				$finalPhotoObj[$viewedId] = NULL;
		}
//print_r($finalPhotoObj);
		return $finalPhotoObj;
	}

	/**
	  * This function sets the photo urls in case the profile has restricted the photo view through JPROFILE.PRIVACY or JPROFILE.PHOTO_DISPLAY
	  * @param - $pictureObj - Photo Object of the profile whose photos are hidden
	  * @param - $staticPhotoName - name of the case (photo filtered / photo visible if contact accepted , etc)
	  * @param - $gender - gender of the profile whose photo object is passed
	  * @param - $mobileView - set this to 'Y' if photo is to be shown for mobile
	  * @return - the final photo object
	**/

	private function setPhotoUrlsForNoOrHiddenPhotos($pictureObj,$staticPhotoName,$gender,$mobileView)
	{
		if($pictureObj)
		{
			if($gender == 'M')
				$genderVal = 'Male';
			else if($gender == 'F')
				$genderVal = 'Female';

			if($mobileView == 'Y')
				$mobile = 'Mobile';
			else
				$mobile='';

			foreach(ProfilePicturesTypeEnum::$PICTURE_SIZES_FIELDS as $i=>$key)
			{
				if(!in_array($key,ProfilePicturesTypeEnum::$PICTURE_SIZES_STOCK))
					$statickey = "ProfilePicUrl";
				else
					$statickey = $key;
				$value = sfConfig::get("app_img_url").constant('StaticPhotoUrls::'.$staticPhotoName.$genderVal.$mobile.$statickey);
				eval('$pictureObj->set'.$key.'($value,"","",$mobileView);');
			}
			return $pictureObj;
		}
	}

	/**
        This function is to be called to get multiple picture Objects (only JPROFILE.PHOTO_DISPLAY is considered in this function)
        * @param  1) Profile objects array, 2) array with index as profileid and value as contactTypeWithLoggedinProfile
        * @return array with index as profileid and value picture object.
        **/
	private function getProfilePics($profileObjArr,$contactTypeWithLoggedinProfile='',$mobileView="",$skipPrivacyPhotoDisplayChecks="",$photoType='N')
	{
		foreach($profileObjArr as $k=>$v)
		{
			$picture_service_obj = new PictureService($v);
			if($v->getHAVEPHOTO() == $picture_service_obj->getPhotoPresent())	//HAVEPHOTO = Y
			{
				$profileIdArr[] = $v->getPROFILEID();
			}
			elseif($v->getHAVEPHOTO() == $picture_service_obj->getPhotoUnderScreening())	//HAVEPHOTO = U
			{
				$non_screened_picture_obj = new ScreenedPicture;
				$arr["PROFILEID"] = $v->getPROFILEID();
				$non_screened_picture_obj->setDetail($arr);
				$non_screened_picture_obj->setCompletePictureUrl();
				unset($arr);
				$picture_service_obj->setPictureObj($non_screened_picture_obj);
				$picture_service_obj->updatePictureUrlsForHiddenPhotos($contactTypeWithLoggedinProfile[$v->getPROFILEID()],$mobileView);
				$this->pictureArr[$v->getPROFILEID()] = $picture_service_obj->getPictureObj();
				unset($non_screened_picture_obj);
			}
			else		//HAVEPHOTO = N or ''
			{
				$this->pictureArr[$v->getPROFILEID()] = "";
			}
			unset($picture_service_obj);
			
		}

		if($profileIdArr)		//If atleast 1 profile has HAVEPHOTO = Y
			$pictureArr1 = $this->getScreenedPhotos("profilePic",$profileIdArr);
		
		if($pictureArr1 && is_array($pictureArr1))	//Merge the arrays having picture objects corresponding to different HAVEPHOTO
		{
			if(is_array($this->pictureArr))
				$this->pictureArr = $this->pictureArr + $pictureArr1;
			else
				$this->pictureArr = $pictureArr1;
		}
			

		foreach($profileObjArr as $k=>$v)
		{
			if($this->viewerObj && $this->viewerObj->getPROFILEID() == $v->getPROFILEID() && $photoType == 'N')
			{
				$picSerObj = new PictureService($this->viewerObj);
				$pictureObjNonScr = $picSerObj->getNonScreenedPhotos('profilePic');
				if($pictureObjNonScr)
					$this->pictureArr[$this->viewerObj->getPROFILEID()] = $pictureObjNonScr;
			}
		}
		return $this->pictureArr;
	}

	/**
        This function hits query at the store to get Screened Profile Photos corresponding to multiple profiles.
        * @param  1) type = profilePic, 2) array og profileids
        * @return array with index as profileid and value picture object.
        **/
	private function getScreenedPhotos($type,$profileIdArr)
	{
		if($type == "profilePic")
		{
			$whereCondition["PROFILEID"] = $profileIdArr;
			$whereCondition["ORDERING"] = 0;

			$picture_new_obj = new ScreenedPicture;
			$pictureArr = $picture_new_obj->getMultipleUserProfilePics($whereCondition);
			
			unset($picture_new_obj);
			if($pictureArr)
			{
				foreach($pictureArr as $k=>$v)
				{
					$picture=new ScreenedPicture;
                                	$picture->setDetail($v);
					$picture->setCompletePictureUrl();
                                	$pictureArr[$k] = $picture;
					unset($picture);
				}
			}
			return $pictureArr;
		}
		elseif($type == "albumCount")
		{
			$whereCondition["PROFILEID"] = $profileIdArr;
			$picture_new_obj = new ScreenedPicture;
                        $pictureArr = $picture_new_obj->getMultipleUserPicsCount($whereCondition);
			unset($picture_new_obj);
			return $pictureArr;
		}		
	}

	/**
        This function gives the no of screened photos of a list of profileids.
        * @param  array of profile objects
        * @return array with index as profileid and value = count of screened photos.
        **/
	public function getNoOfPics($profileObjArr)
	{
		$pictureArr = array();
		foreach($profileObjArr as $k=>$v)
		{
			$picture_service_obj = new PictureService($v);
                        if($v->getHAVEPHOTO() == $picture_service_obj->getPhotoPresent())       //HAVEPHOTO = Y
                        {
                                $profileIdArr[] = $v->getPROFILEID();
                        }
                        else            //HAVEPHOTO = N or '' or U
                        {
                                $pictureArr[$v->getPROFILEID()] = 0;
                        }
                        unset($picture_service_obj);
		}

		if($profileIdArr)               //If atleast 1 profile has HAVEPHOTO = Y
                        $pictureArr1 = $this->getScreenedPhotos("albumCount",$profileIdArr);

		if($pictureArr1 && is_array($pictureArr1))
			$pictureArr = $pictureArr + $pictureArr1;

		return $pictureArr;
	}

	//to be removed after trac 1495 legacy changes
	public function getProfilePicsOld($profileObjArr,$contactTypeWithLoggedinProfile='',$mobileView="")
	{
		foreach($profileObjArr as $k=>$v)
		{
			$picture_service_obj = new PictureService($v);
			if($v->getHAVEPHOTO() == $picture_service_obj->getPhotoPresent())	//HAVEPHOTO = Y
			{
				$profileIdArr[] = $v->getPROFILEID();
			}
			elseif($v->getHAVEPHOTO() == $picture_service_obj->getPhotoUnderScreening())	//HAVEPHOTO = U
			{
				$non_screened_picture_obj = new ScreenedPicture;
				$arr["PROFILEID"] = $v->getPROFILEID();
				$non_screened_picture_obj->setDetail($arr);
				$non_screened_picture_obj->setCompletePictureUrl();
				unset($arr);
				$picture_service_obj->setPictureObj($non_screened_picture_obj);
				$picture_service_obj->updatePictureUrlsForHiddenPhotos($contactTypeWithLoggedinProfile[$v->getPROFILEID()],$mobileView);
				$this->pictureArr[$v->getPROFILEID()] = $picture_service_obj->getPictureObj();
				unset($non_screened_picture_obj);
			}
			else		//HAVEPHOTO = N or ''
			{
				$this->pictureArr[$v->getPROFILEID()] = "";
			}
			unset($picture_service_obj);
		}

		if($profileIdArr)		//If atleast 1 profile has HAVEPHOTO = Y
			$pictureArr1 = $this->getScreenedPhotos("profilePic",$profileIdArr);
		
		if($pictureArr1 && is_array($pictureArr1))
		{
			foreach($profileObjArr as $k=>$v)		//Check for privacy
			{

				if(array_key_exists($v->getPROFILEID(),$pictureArr1))
				{
					$picture_service_obj = new PictureService($v);
					$picture_service_obj->setPictureObj($pictureArr1[$v->getPROFILEID()]);
					
					if($v->getHAVEPHOTO()==$picture_service_obj->getPhotoPresent() && $v->getPHOTO_DISPLAY()==$picture_service_obj->getViewedProfile_InitiatesOrAccpeted())
					{
						$picture_service_obj->updatePictureUrlsForHiddenPhotos($contactTypeWithLoggedinProfile[$v->getPROFILEID()],$mobileView);
						$pictureArr1[$v->getPROFILEID()] = $picture_service_obj->getPictureObj();
					}
					unset($picture_service_obj);
				}
			}			//Ends privacy check
		}

		if($pictureArr1 && is_array($pictureArr1))	//Merge the arrays having picture objects corresponding to different HAVEPHOTO
		{
			if(is_array($this->pictureArr))
                                $this->pictureArr = $this->pictureArr + $pictureArr1;
                        else
                                $this->pictureArr = $pictureArr1;
//			$this->pictureArr = $this->pictureArr + $pictureArr1;
		}

		return $this->pictureArr;
	}

        /**
        * This function reutrns the count of screened photos for a given set of profileids passed in constructor
        * @return array with index as profileid and value as count of pics.
        **/
        public function getScreendPictureCountByPid()
        {	
		$arr = NULL;
		foreach($this->viewedObjArr as $viewedObj)
		{
			if($viewedObj->getHAVEPHOTO()== 'Y')
				$profilesWithScreenedPhoto[]=$viewedObj->getPROFILEID();
		}

		if($profilesWithScreenedPhoto)	
		{
			$PICTURE_NEW = new PICTURE_NEW("newjs_masterRep");
			$arr = $PICTURE_NEW->getScreendPictureCountByPid($profilesWithScreenedPhoto);
		}
		return $arr;
	}	
}
?>
