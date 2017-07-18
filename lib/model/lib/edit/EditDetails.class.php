<?php
/**
 * @class EditDetails
 * Returns All detials for a particular user
 * Is used in case of Edit module(frontend) and showStat page(Backend) to fetch all relevant data corresponding to a particular profile
 * */
class EditDetails{
	
	/**
	 * This function fetches the profile detail values
	 * @param Object $actionObj
	 * @param Object $apiProfileSectionObj
	 * @param sectionFlag $sectionFlag
	 * @param myProfileArr &$myProfileArr (passed by reference)
	 * @return ResponseHandlerConfig's Response
	 */
	public function getEditDetailsValues($actionObj,$apiProfileSectionObj,$sectionFlag,&$myProfileArr,$fromShowStats='')
	{
    $request = sfContext::getInstance()->getRequest();
		$religion = $actionObj->loginProfile->getReligion();
		if($sectionFlag=="all")
		{	
			if(MobileCommon::isApp()==null)
			{
				$myProfileArr["Album"]["ProfilePicUrl"]="";
				$myProfileArr["Album"]["tempSelectVar"]="";

			}
			if(MobileCommon::isApp())
				$myProfileArr["MyBasicInfo"]=ProfileCommon::removeBlank($apiProfileSectionObj->getApiBasicInfo());
			else
				$myProfileArr["Details"]=ProfileCommon::removeBlank($apiProfileSectionObj->getApiBasicInfo());

			if(MobileCommon::isApp())
				$myProfileArr["Astro"]=ProfileCommon::removeBlank($apiProfileSectionObj->getApiAstroKundali());
			else if(($religion==RELIGION::HINDU || $religion==Religion::JAIN || $religion==Religion::SIKH || $religion== Religion::BUDDHIST))
				$myProfileArr["Kundli"]=ProfileCommon::removeBlank($apiProfileSectionObj->getApiAstroKundali());

			$myProfileArr["Education"]=ProfileCommon::removeBlank($apiProfileSectionObj->getApiEducation());
			if(MobileCommon::isApp())
				$myProfileArr["Occupation"]=ProfileCommon::removeBlank($apiProfileSectionObj->getApiOccupation());
			else
				$myProfileArr["Career"]=ProfileCommon::removeBlank($apiProfileSectionObj->getApiOccupation());
			$myProfileArr["Family"]=ProfileCommon::removeBlank($apiProfileSectionObj->getApiFamilyDetails());
			$myProfileArr["Lifestyle"]=ProfileCommon::removeBlank($apiProfileSectionObj->getApiLifeAttr());
			$myProfileArr["Contact"]=ProfileCommon::removeBlank($apiProfileSectionObj->getApiContactInfo());
			$myProfileArr["Critical"]=ProfileCommon::removeBlank($apiProfileSectionObj->getApiCriticalInfo());




				//album ny anand
			if(MobileCommon::isApp()){
				
				$myProfileArr["album"]["privacy"] = $actionObj->loginProfile->getPHOTO_DISPLAY();
                                if($myProfileArr["album"]["privacy"]=='')
                                        $myProfileArr["album"]["privacy"]="A";
				$picServiceObj = new PictureService($actionObj->loginProfile);
				$album = $picServiceObj->getAlbum($request->getParameter("contactType"));
				if($album && is_array($album))
				{ 
					foreach($album as $k=>$v)
					{
						$albumArr[$k]["pictureid"] = $v->getPICTUREID();
						$albumArr[$k]["url"] = $v->getMainPicUrl();
						
						if(MobileCommon::isApp()=="I" && $v->getOrdering()=="0" && $k==0){
							if($v->getProfilePic235Url())
								$myProfileArr["album"]["profilePicUrl"]=$v->getProfilePic235Url();
							else if($v->getProfilePic120Url())
								$myProfileArr["album"]["profilePicUrl"]=$v->getProfilePic120Url();
							else if($v->getProfilePicUrl())
								$myProfileArr["album"]["profilePicUrl"]=$v->getProfilePicUrl();
							else if($v->getMainPicUrl())
								$myProfileArr["album"]["profilePicUrl"]=$v->getMainPicUrl();
							else
								$myProfileArr["album"]["profilePicUrl"]="";
						}
					}		
				}
				if($albumArr && is_array($albumArr))
				{
					$myProfileArr["album"]["albumUrls"]=$albumArr;
					$myProfileArr["album"]["max_no_of_photos"]=sfConfig::get("app_max_no_of_photos");

				}
				else
				{
					$myProfileArr["album"]["albumUrls"]=NULL;
					$myProfileArr["album"]["max_no_of_photos"]=sfConfig::get("app_max_no_of_photos");
				}

					//unsetting the picture service object
				unset($picServiceObj);
					//Screening Array
					//$myProfileArr["ScreeningFields"]=$apiProfileSectionObj->getApiScreeningFields();
			}	
			$casteGrouping = 0;
                        if(MobileCommon::isApp() && $request->getParameter("newdpplist") && $request->getParameter("newdpplist") == 1){
                                $casteGrouping = 1;
                        }
			$myProfileArr["Dpp"] = $this->getDppValuesArr($apiProfileSectionObj,$casteGrouping);
                        
			if(MobileCommon::isApp()){
				$myProfileArr["ScreeningMessage"]="under screening";
				$myProfileArr["c_care"]="18004196299";
                                $newjsMatchLogicObj = new newjs_MATCH_LOGIC();
                                $cnt_logic = $newjsMatchLogicObj->getPresentLogic($actionObj->loginProfile->getPROFILEID(),MailerConfigVariables::$oldMatchAlertLogic);
                                if($cnt_logic>0)
                                        $myProfileArr["toggleMatchalert"] = "dpp";
                                else
                                        $myProfileArr["toggleMatchalert"] = "new";
			}

                //To get complete your profile Links on page save
			if(MobileCommon::isDesktop() || $fromShowStats==1)
			{
				$cScoreObject = ProfileCompletionFactory::getInstance(null,$actionObj->loginProfile,null);
				$myProfileArr["profileCompletion"]["PCS"] = $cScoreObject->getProfileCompletionScore();
				$noOfMsg = 4;
				$myProfileArr["profileCompletion"]["msgDetails"] = $cScoreObject->GetIncompleteDetails($noOfMsg);
				$myProfileArr["profileCompletion"]["linkDetails"] = $cScoreObject->GetLink();
			}
			return(ResponseHandlerConfig::$SUCCESS);
			
		}
		elseif($sectionFlag=="incomplete")
		{
			//incomeplete testing
			$myProfileArr["Incomplete"]=ProfileCommon::removeBlank($apiProfileSectionObj->getApiIncompleteInfo());
			if(!MobileCommon::isApp())
			{
				$pictureServiceObj = new PictureService($actionObj->loginProfile);
				$profilePicObject = $pictureServiceObj->getProfilePic();
				$noOfPhotos=$pictureServiceObj->getUserUploadedPictureCount();
				if ($profilePicObject != NULL && $noOfPhotos && $profilePicObject->getProfilePic120Url())
					$myProfileArr["ProfilePicUrl"]=$profilePicObject->getProfilePic120Url();
				elseif ($profilePicObject != NULL && $noOfPhotos && $profilePicObject->getProfilePicUrl)
					$myProfileArr["ProfilePicUrl"]=$profilePicObject->getProfilePicUrl();
				elseif ($profilePicObject != NULL && $noOfPhotos && $profilePicObject->getMainPicUrl)
					$myProfileArr["ProfilePicUrl"]=$profilePicObject->getMainPicUrl();
				else
				{
					if($actionObj->loginProfile->getGENDER()=="F")
						$myProfileArr["ProfilePicUrl"]="/images/jsms/commonImg/3_4_NoFemalePhoto.jpg";
					else
						$myProfileArr["ProfilePicUrl"]="/images/jsms/commonImg/3_4_NoMalePhoto.jpg";
				}
				$completionObj=  ProfileCompletionFactory::getInstance("API",$actionObj->loginProfile,null);
				$myProfileArr["ProfileCompletionScore"]=$completionObj->getProfileCompletionScore();

			}
			return(ResponseHandlerConfig::$INCOMPLETE_USER);
		}
	}

	//This function returns the Dpp values for a particular user
	public function getDppValuesArr($apiProfileSectionObj,$hasCasteGrouping='')
	{	
		$arrTemp = array();
		$arrOut = ProfileCommon::removeBlank($apiProfileSectionObj->getApiDppLifeAttr());
		$arrTemp = array_merge($arrOut,$arrTemp);
		$arrOut = ProfileCommon::removeBlank($apiProfileSectionObj->getApiDppEducationAndOcc());
		$arrTemp = array_merge($arrOut,$arrTemp);

		$arrOut = ProfileCommon::removeBlank($apiProfileSectionObj->getApiDppReligionAndEth());
		if($hasCasteGrouping=='1')
		{
			$arrOut=$this->changeCasteLabelForGrouping($arrOut);
		}
		$arrTemp = array_merge($arrOut,$arrTemp);
		$arrOut = ProfileCommon::removeBlank($apiProfileSectionObj->getApiDppBasicInfo());
		$arrTemp = array_merge($arrOut,$arrTemp);
		return $arrTemp;
	}


    /**
	 * This function is used to change label format of caste for the user 
	 * 
	 */
    public function changeCasteLabelForGrouping($casteArr)
    {
    	foreach($casteArr as $key=>$val)
    	{
    		if($val["key"]=="P_CASTE")
    		{
    			$casteGroupArr=FieldMap::getFieldLabel("caste_group_array",'',1);

    			foreach(explode(",",$val["value"]) as $k=>$v)
    			{

    				$label[] = (FieldMap::getFieldLabel("caste_without_religion",$v)).(array_key_exists($v,$casteGroupArr)?"- All":"");
    			}
    			$casteArr[$key]["label_val"]=implode(", ",$label);

    			break;
    		}

    	}

    	return $casteArr;

    }
    
    //This is used to return the JPartnerObj
    public function getJpartnerObj($actionObj)
    {
   		include_once(sfConfig::get("sf_web_dir")."/classes/shardingRelated.php");
    	return DetailActionLib::getJPartnerEdit($actionObj);
    }

    //This function is used to fetch the profile pic url
    public function getProfilePicUrl($actionObj)
    {
    	$picDetailArr = array();
    	$pictureServiceObj = new PictureService($actionObj->loginProfile);
    	$profilePicObj = $pictureServiceObj->getProfilePic();
    	$picDetailArr["album_count"] = $pictureServiceObj->getUserUploadedPictureCount();
    	if($profilePicObj != '')
    	{
    		$picDetailArr["profilePicUrl"] = ($profilePicObj->getProfilePicUrl()!='')?$profilePicObj->getProfilePicUrl():$profilePicObj->getMainPicUrl();
    	}
    	else
    	{
    		$picDetailArr["profilePicUrl"] = "";
    	}
    	return $picDetailArr;
    }

    //This function is used to fetch other Basic details
    public function getOtherDetails($actionObj,$cid)
    {
    	$otherDetailsArr = array();
    	$otherDetailsArr["username"]=$actionObj->loginProfile->getUSERNAME();
    	$otherDetailsArr["age"]=$actionObj->loginProfile->getAGE();
    	$otherDetailsArr["checksum"]=$cid;
    	$otherDetailsArr["profilechecksum"]=JsAuthentication::jsEncryptProfilechecksum($actionObj->loginProfile->getPROFILEID());
    	$otherDetailsArr["entryDate"]=date("Y-m-d",strtotime($actionObj->loginProfile->getENTRY_DT()));
    	$otherDetailsArr["photoDisplay"]=$actionObj->loginProfile->getPHOTO_DISPLAY();
    	$otherDetailsArr["modifiedDate"]=date("Y-m-d",strtotime($actionObj->loginProfile->getMOD_DT()));
    	$otherDetailsArr["lastLogin"]=date("Y-m-d",strtotime($actionObj->loginProfile->getLAST_LOGIN_DT()));
    	$otherDetailsArr["photoModifiedDate"]=date("Y-m-d",strtotime($actionObj->loginProfile->getPHOTODATE()));
    	if(strstr($actionObj->loginProfile->getSUBSCRIPTION(),'D'))
    	{
    		$otherDetailsArr["subscription"] = 'Y';
    	}
    	else
    	{
    		$otherDetailsArr["subscription"] = 'N';
    	}
    	return $otherDetailsArr;
    }

    //This function returns data required for profile completion score section
    public function getProfCompScoreDetails($actionObj)
    {
    	$profCompScoreArr = array();
    	$profCompScoreArr["havePhoto"]=$actionObj->loginProfile->getHAVEPHOTO();
    	$profCompScoreArr["incomplete"]=$actionObj->loginProfile->getINCOMPLETE();
    	$profCompScoreArr["photoDisplay"]=$actionObj->loginProfile->getPHOTO_DISPLAY();
    	return $profCompScoreArr;
    }

}
?>
