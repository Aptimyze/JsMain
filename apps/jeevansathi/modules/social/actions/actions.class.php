<?php

/**
 * social actions.
 *
 * @package    jeevansathi
 * @subpackage social
 * @author     Lavesh Rawat
 * @version    
 */
class socialActions extends sfActions
{
	
 public $albumCoverImageArr=array();

 /**
 * Automatically calls before the action to execute.
 */
 public function preExecute()
 {
	$this->request->setAttribute('bms_topright',647);
	$this->request->setAttribute('bms_bottom',648);
 }

/**
        This layer is shown to users who
        1. have JPROFILE.PHOTO_DISPLAY=C
        2. have JPROFILE.ENTRY_DT older than 1 month
        This layer is not shown to
        1. incomplete users
        2. un-activated users.
        3. users who have seen this layer 3 times
        4. users who have selected option 3 (to remove photos) from this layer before
        5. paid members
	This function opens up a layer in which user is asked to either become a paid member, change his photo_display value to "A" ot remove his photos.
	A corresponding entry is madde in the table MIS.PHOTO_PRIVACY_LAYER_LOG
**/
  public function executePhotoPrivacyLayer(sfWebRequest $request)
  {
	$profileObj=LoggedInProfile::getInstance('newjs_master');
	$profileid = $profileObj->getPROFILEID();
	$photoPrivacyLayerLog = new MIS_PHOTO_PRIVACY_LAYER_LOG();
	if($profileid)
		$this->insertedId = $photoPrivacyLayerLog->insertRecord($profileid,'myJeevansathi');
  }

  public function executeMobPhotoPrivacy(sfWebRequest $request)
  {
	  //echo("abc");die;
  	$profileObj=LoggedInProfile::getInstance('newjs_master');
  	$profileObj->getDetail("","","HAVEPHOTO"); 
    $pictureServiceObj=new PictureService($profileObj);
  	$ProfilePicUrlObj = $pictureServiceObj->getProfilePic();
  	$this->ProfilePicUrl = $ProfilePicUrlObj->getMainPicUrl();
  }
  	public function executeMobPhotoUpload(sfWebRequest $request)
	{
		$loginProfile=LoggedInProfile::getInstance('newjs_master');
		$loginProfile->getDetail("","","USERNAME"); 
		$this->username=$loginProfile->getUSERNAME();	
	}


/**
	This function updates JPROFILE.PHOTO_DISPLAY value of the logged in user to 'A'
**/
  public function executeUpdatePhotoDisplay(sfWebRequest $request)
  {
	$profileObj=LoggedInProfile::getInstance('newjs_master');
	$profileid = $profileObj->getPROFILEID();
	$photoDisplay = PhotoProfilePrivacy::photoVisibleToAll;
	$profileObj->edit(array("PHOTO_DISPLAY"=>"$photoDisplay"));
	die;
  }

/**
	This function updates the table MIS.PHOTO_PRIVACY_LAYER_LOG with the option that user has selected from the photo privacy layer shown on my jeevansathi page.
**/
  public function executeUpdatePhotoPrivacySelection(sfWebRequest $request)
  {
	$profileObj=LoggedInProfile::getInstance('newjs_master');
	$profileid = $profileObj->getPROFILEID();
	$id = $request->getParameter("id");
	$option = $request->getParameter("option");
	if($profileid)
	{
		$photoPrivacyLayerLog = new MIS_PHOTO_PRIVACY_LAYER_LOG();
		$photoPrivacyLayerLog->updateRecord($id,$option,$profileid);
	}
	die;
  }

  /**
  This function is used to view All the Photos of the User
  @param picture ID(id000<picId>) or "none". If no parameter is passed then the script dies.
  */
	
  public function executeViewAllPhotos(sfWebRequest $request)
  {
	if(MobileCommon::isDesktop())
	{
		$this->redirect("/profile/viewprofile.php?ownview=1");
	}
	if ($request->getParameter('mainImageId'))              			//If a parameter passed is picture Id or "none"
	{
        	$profileObj=LoggedInProfile::getInstance('newjs_master');
		$viewAllPhotosObj = new ViewAllPhotos($profileObj);
		$outputArr = $viewAllPhotosObj->setCommonVariables($request->getParameter('mainImageId'));

		$this->keywords = 		$outputArr->keywords;
		$this->userPics = 		$outputArr->userPics;
		$this->countOfPics =		$outputArr->countOfPics;      				//Count no of pics
		$this->allThumbnailPhotos = 	$outputArr->allThumbnailPhotos;
		$this->tempCount = 		$outputArr->tempCount;
		$this->allPicIds = 		$outputArr->allPicIds;
        	$this->titleArr = 		$outputArr->titleArr;
        	$this->keywordArrStr = 		$outputArr->keywordArrStr;
        	$this->picIdArr = 		$outputArr->picIdArr;
        	$this->picType = 		$outputArr->picType;
		$this->sliderNo = 		$outputArr->sliderNo;
		$this->currentPicIndex = 	$outputArr->currentPicIndex;                   	//Set Picture Number for the Template
		$this->frontPicUrl = 		$outputArr->frontPicUrl;           		//Set Main Pic Url to be displayed
		$this->currentPicId = 		$outputArr->currentPicId;			//Set Current Pic Id to be stored in the hidden input in template
		$this->currentPic_Type = 	$outputArr->currentPic_Type;			//Set Current Pic Id to be stored in the hidden input in template
		$this->currentPicKeywords = 	$outputArr->currentPicKeywords;			//Current Pic Keywords
		$this->dropdownKeywordsLabel = 	$outputArr->dropdownKeywordsLabel;		//Keywords list display in the disabled dropdown
		$this->widthOfMainPic = 	$outputArr->widthOfMainPic;
                $this->heightOfMainPic = 	$outputArr->heightOfMainPic;
		$this->randomNo = rand(1,9);
  	}
	else									// No parameter is passed
	{
		die;								//Display nothing and the script dies
	}
  }					

  /**
  This function is used to fetch the details of the pic that is clicked on viewAllPhotos template slider and the pic that arrives at the next/prev action
  @param Picture Id
  @return MainPicURL,Title,Keywords(names),PictureID,Keywords(Indexes),Pics Count and Profile Pic Id separated by **-**
  */

  public function executeImageDetails(sfWebRequest $request)
  {
	$this->keywords=sfConfig::get("app_social_keywords");
	$profileObj=LoggedInProfile::getInstance('newjs_master');				
	$pid=$profileObj->getPROFILEID();
	if(!$pid)
		die("userTimedOut");
	$profileObj->getDetail("","","HAVEPHOTO"); 
        $pictureServiceObj=new PictureService($profileObj);		

	$picId = $request->getParameter('picId');					//Get picture ID as parameter
	$whereArr["PICTUREID"] = $picId;
	$whereArr["PROFILEID"] = $pid;
      	$currentPicObj = $pictureServiceObj->getPicDetails($whereArr);			//Get picture object corresponding the picture ID
	$mainPicUrl = $currentPicObj[0]->getMainPicUrl();				//Get MainPicURL
	$title = $currentPicObj[0]->getTITLE();						//Get Picture Title
	$pic_keywords = $currentPicObj[0]->getKEYWORD();				//Get Keywords (indexes separated by ,)
	$picId = $currentPicObj[0]->getPICTUREID();
	$picType = $currentPicObj[0]->getPictureType();
	$pics_count=$pictureServiceObj->getUserUploadedPictureCount();
	$profile_pic_object = $pictureServiceObj->getProfilePic();
	$profile_pic_id = $profile_pic_object->getPICTUREID();

		if (trim($pic_keywords) == "")						//If no keywords
		{
			$currentPicKeywords = "";					//Set keyword names as ""
		}
		else									//Else picture has keywords
		{
			$currentKeywordArr = explode(",",$pic_keywords);        	 //pic_keywords has indexes separated by ,

			for ($i=0;$i<count($currentKeywordArr);$i++)			//Fetch keyword names from the indexes
			{
				$current_Pic_Keywords[$i] = $this->keywords[$currentKeywordArr[$i]-1];
			}
			$currentPicKeywords = implode(", ",$current_Pic_Keywords);	//Join keyword names separated by ,
		}
	PictureFunctions::setHeaders();	
	$size = getimagesize($mainPicUrl);
	echo $mainPicUrl."**-**".$title."**-**".$currentPicKeywords."**-**".$picId."**-**".$pic_keywords."**-**".$pics_count."**-**".$profile_pic_id."**-**".$picType."**-**".$size[0]."**-**".$size[1];
	die;										//Kill the script as it is an AJAX Request
  }

  /**
  This function is called when a user updates his title/keywords from the viewAllPhotos template
  @params Picture ID, Type = title/keywords, title/keywds value
  @return	: 1) If title updated	 - "updated_title" and new title separated by **-**
  		: 2) If keywords updated - "updated_keywords", new keywords name and new keywords index separated by **-**
  */

  public function executeUpdatePictureDetails(sfWebRequest $request)
  {
	$this->keywords=sfConfig::get("app_social_keywords");
	
	$profileObj=LoggedInProfile::getInstance('newjs_master');				
	$pid=$profileObj->getPROFILEID();
	if(!$pid)
		die("userTimedOut");
        $pictureServiceObj=new PictureService($profileObj);				

        $formArr = $request->getParameterHolder()->getAll();					//Fetch all the parameters
	
	if (trim($formArr["title"])=="")
		$formArr["title"] = null;
	if (trim($formArr["keywds"])=="")
		$formArr["keywds"] = null;
	
	$whereArr["PICTUREID"] = $formArr["picId"];
	$whereArr["PROFILEID"] = $profileObj->getPROFILEID();
	$currentPicObj = $pictureServiceObj->getPicDetails($whereArr);
	
	$currentPicObj[0]->setTITLE($formArr["title"]);
	$currentPicObj[0]->setKEYWORD($formArr["keywds"]);

       	$updateStatus=$pictureServiceObj->editAlbumInfo($currentPicObj[0]);	//UPDATION ENDS
	
	if ($updateStatus) 
	{
		if ($formArr["type"]=="title")							//If title updated
		{
			echo "updated_title**-**".$formArr["title"];				//Output the content related with title updation
		}
		else if ($formArr["type"]=="keywords")						//Else If keywords updated
		{
			if ($formArr["keywds"])							//If new keywords exist or not set to blank
			{
				$currentKeywordArr = explode(",",$formArr["keywds"]);		//formArr["keywds"] contains keywords indexes separated by ,

                		for ($i=0;$i<count($currentKeywordArr);$i++)			//Fetch keyowrds name corresponding to index
                		{
                		        $current_Pic_Keywords[$i] = $this->keywords[$currentKeywordArr[$i]-1];
                		}
                		$currentPicKeywords = implode(", ",$current_Pic_Keywords);	//Set names into a string separated by ,
			}
			else									//If new keywords set to blank
			{
				$currentPicKeywords = "";					//Set keywords names to ""
			}

			echo "updated_keywords**-**".$currentPicKeywords."**-**".$formArr["keywds"];   //Output the content
		}
		else {}										//Else do nothing
	}
	else
	{
		throw new jsException("","Title/Keyword not updated.");
	}
	die;										//The script dies because it is an AJAX Request
  }

  /**
  This function is called from the saveImage template when the user presses the save button. It save all the details of the pics imported/uploaded
  @params	: 1) Titles of all the images in the form of a string where each title is separated by **-**
  		: 2) Picture ID's of all the images in the form of a string where each picture ID is separated by **-**
  		: 3) Picture Type(N/S) of all the images in the form of a string where each type is separated by **-**
  		: 4) Keywords(indexes) of all the images in the form of a string where indexes are separated by , and different image keywords separated by **-**
  @return updated_album
  */

  public function executeSaveAlbumInfo(sfWebRequest $request)
  {
	$profileObj=LoggedInProfile::getInstance('newjs_master');				
	$pid=$profileObj->getPROFILEID();
	if(!$pid)
		die("userTimedOut");
        $pictureServiceObj=new PictureService($profileObj);			

	$formArr = $request->getParameterHolder()->getAll(); 			//Fetch all the parameters passed
	
	$title_values = explode("**-**",$formArr["title_array"]);		//Break the title string into array
       	$picId_values = explode("**-**",$formArr["picId_array"]);		//Break the picture ID string into array
       	$picType_values = explode("**-**",$formArr["picType_array"]);		//Break the picture type string into array
       	$keyword_values = explode("**-**",$formArr["keyword_array"]);		//Break the keywords string into array

	foreach ($picId_values as $k=>$v)					//Loop to save all image details one by one
	{
		$picArray["PICTUREID"] = $picId_values[$k];			//Create an array with all the image details STARTS here
               	$picArray["TITLE"] = $title_values[$k];
              	$picArray["KEYWORD"] = $keyword_values[$k];
              	$picArray["PICTURETYPE"] = $picType_values[$k];
		$picArray["PROFILEID"] = $profileObj->getPROFILEID();		//Array creation ENDS

		$pictureObjArray = $pictureServiceObj->arrayToObj($picArray);
		if ($pictureObjArray)
			$updateStatus=$pictureServiceObj->editAlbumInfo($pictureObjArray[0]);	//Save/Update ENDS
	}									//FOREACH loop ends
	echo "saved_album";							//Output
	die;									//Kill the script as it is an AJAX Request
  }

  /**
  This function is used to open the layer for cropping of profile picture and to set the checked pic as Profile Pic
  @params "none" or save000<picId> or view000<picId>
  */

  public function executeProfileLayer(sfWebRequest $request)
  {
	$profileObj=LoggedInProfile::getInstance('newjs_master');					
	$pid=$profileObj->getPROFILEID();
	if(!$pid)
	{
		$this->userTimedOutError = true;
	}
	else
	{
		$this->userTimedOutError = false;
	ini_set('memory_limit',"256M");			//Memory limit to be 128M and not -1

	if($request->getParameter('displayImage')=='none')					//If parameter = "none"
	{
		$this->layerContent = 0;							//0=> template shows Please select one pic data 
	}
	else											//Parameter = save000<picId> or view000<picId>
	{
                $pictureServiceObj=new PictureService($profileObj);			

                $param_picId = $request->getParameter('displayImage');				//Fetch the parameter
                $picId = substr($param_picId,7);						//Get the picId from parameter
		$srcPage = substr($param_picId,0,4);						//Get the page from which the function is called
		if ($srcPage == "save")								//Function is called from saveImage template
		{
			$this->outputDisplayContent = 1;					//1=>Title,keywordsand profile picture saved
		}
		else if ($srcPage == "view")							//Function is called from viewAllPhotos template
		{
			$this->outputDisplayContent = 2;					//2=>Profile picture saved
		}
		else
		{}	
			
                $whereArr["PICTUREID"] = $picId;
                $whereArr["PROFILEID"] = $pid;
                $currentPicObj = $pictureServiceObj->getPicDetails($whereArr,1);			//Get picture object correspondingto Picture ID

                $status=$pictureServiceObj->setProfilePic($currentPicObj[0]);			//Set the picture as Profile Picture
		$currentPicObj[0]->setCompletePictureUrl();

             	$this->layerContent = 1;										//1=>Template shows cropping data
		
		$picFormat = $currentPicObj[0]->getPICFORMAT();
            	$dest_pic_name = $currentPicObj[0]->getSaveUrl("canvasPic",$currentPicObj[0]->getPICTUREID(),$pid,$picFormat);	//Path where 340*310 canvas to be saved
        	$this->picId = $currentPicObj[0]->getPICTUREID();							//Picture ID of the picture	
		$this->canvasPicUrl = $currentPicObj[0]->getDisplayPicUrl("canvasPic",$currentPicObj[0]->getPICTUREID(),$pid,$picFormat);		
				
		if ($picFormat == "gif")
                  	$type = "image/gif";
             	else if ($picFormat == "jpeg")
                   	$type = "image/jpeg";
              	else if ($picFormat == "jpg")
			$type == "image/jpg";

		if ($currentPicObj[0]->getPictureType() == "N")
		{
         		$src_pic_name = $currentPicObj[0]->getSaveUrl("mainPic",$currentPicObj[0]->getPICTUREID(),$pid,$picFormat);
			$pictureServiceObj->generateImages("canvasPic",$src_pic_name,$dest_pic_name,$type);
		}
		else
		{
         		$src_pic_name = $currentPicObj[0]->getMainPicUrl();
			/*
			$migrate = new MoveFiles('get');
         		$src_pic_name = $currentPicObj[0]->getSaveUrl("mainPic",$currentPicObj[0]->getPICTUREID(),$pid,$picFormat,'',$migrate);
			$migrate->makeFTPConnection(sfConfig::get("app_ftp_username"),sfConfig::get("app_ftp_password"),sfConfig::get("app_ftp_host"));
			$migrate->getFiles($dest_pic_name,$src_pic_name);
			*/
			$pfObj = new PictureFunctions;
			$pfObj->moveImageFromRemoteLocationToLocalDisk($src_pic_name,$dest_pic_name);
			unset($pfObj);
			$pictureServiceObj->generateImages("canvasPic",$dest_pic_name,$dest_pic_name,$type);
		}
	}	//Else ends
	}
  }

  /**
  This function is used to save the cropped profile pic (150*200) and thumnail (60*60) of the Profile Pic. AJAX Request and GET method used
  @params "none" or array containing the values related to cropping co-ordinates,profilepic(150*200)/thumbnail(60*60), etc.
  @return The required cropped profile pic and thumnails is generated and the page is redirected by javascript to addPhotos template
  */ 

  public function executeSaveProfilePic(sfWebRequest $request)
  {
	$params = $request->getParameter('dimensionParams');			//Fetch the parameter
        $profileObj=LoggedInProfile::getInstance('newjs_master');				
	$pid=$profileObj->getPROFILEID();
	if(!$pid)
		die("userTimedOutIframe");
        $pictureServiceObj=new PictureService($profileObj);	

        if ($params)				//User clicks the save button on first layer (where profile pic can be cropped) or second layer (thumbnail cropping)
        {
		$params_array = explode(",",$params);				//Break the parameters into an array
                $whereArr["PICTUREID"] = $params_array[0];
                $whereArr["PROFILEID"] = $pid;
                $currentPicObj = $pictureServiceObj->getPicDetails($whereArr);		//Get picture object corresponding the profile picture

                if ($params_array[7] == "profile")				//If saving the cropped profile pic (150*200)
                {
			$picFormat = $currentPicObj[0]->getPICFORMAT();
                        $dest_pic_name = $currentPicObj[0]->getSaveUrl("profilePic",$params_array[0],$pid,$picFormat);		//Path to save the 150*200 profile pic
                        $src_pic_name = $currentPicObj[0]->getSaveUrl("canvasPic",$params_array[0],$pid,$picFormat);		//Path of canvas profile pic

			if ($picFormat == "gif")
                	        $type = "image/gif";
                	else if ($picFormat == "jpeg")
                	        $type = "image/jpeg";
                	else if ($picFormat == "jpg")
                	        $type == "image/jpg";

			$pictureServiceObj->generateImages("profilePic",$src_pic_name,$dest_pic_name,$type,$params_array);

			$currentPicObj[0]->setProfilePicUrl($currentPicObj[0]->getDisplayPicUrl("profilePic",$params_array[0],$pid,$picFormat));

                        $updateStatus=$pictureServiceObj->editAlbumInfo($currentPicObj[0]);

                        echo "thumbnail_layer";					//Output in order to display the next layer	
                        die;							//Kill the script
                }
                else if ($params_array[7] == "thumbnail")			//Else if saving the cropped thumbnail (60*60)
                {
			$picFormat = $currentPicObj[0]->getPICFORMAT();
                        $dest_pic_name = $currentPicObj[0]->getSaveUrl("thumbnail",$params_array[0],$pid,$picFormat);		//Path to save the 60*60 thumbnail
                        $src_pic_name = $currentPicObj[0]->getSaveUrl("canvasPic",$params_array[0],$pid,$picFormat);		//Path to canvas profile pic

			if ($picFormat == "gif")
                                $type = "image/gif";
                        else if ($picFormat == "jpeg")
                                $type = "image/jpeg";
                        else if ($picFormat == "jpg")
                                $type == "image/jpg";

			$pictureServiceObj->generateImages("thumbnail",$src_pic_name,$dest_pic_name,$type,$params_array);

			$currentPicObj[0]->setThumbailUrl($currentPicObj[0]->getDisplayPicUrl("thumbnail",$params_array[0],$pid,$picFormat));

                        $updateStatus=$pictureServiceObj->editAlbumInfo($currentPicObj[0]);

			unlink($src_pic_name);					//Delete the canvas pic
			if ($params_array[8] == "save")
			{
                        	echo "layer_done**-**save";					//Output in order to redirect to viewAllPhotos template
			}
			else if ($params_array[8] == "view")
			{
				echo "layer_done**-**view";
			}
			else
			{}
                        die;							//Kill the script
                }
		else
		{}
        }
	else
	{
		throw new jsException("","Error in creating profile pic/thumbnail.");
	}
  }

  /**
  This function is used to open the compUpload template which enables uploading from computer
  */

  public function executeCompUpload(sfWebRequest $request)
  {
    $this->forward("social", "addPhotos");
    die;
	$profileObj=LoggedInProfile::getInstance('newjs_master');					
  }

  /**
  This function opens the compUploadFrame template which enables uploading from computer. The template opens in an iframe inside compUpload template.
  */

  public function executeCompUploadFrame(sfWebRequest $request)
  {
	$profileObj=LoggedInProfile::getInstance('newjs_master');			

        $pictureServiceObj=new PictureService($profileObj);				
	$pics=$pictureServiceObj->getUserUploadedPictureCount();				//Get number of pics already present for this PROFILEID
	if ($pics>=sfConfig::get("app_max_no_of_photos"))
	{
		$this->errorMsg = true;
	}
	else
	{
		$this->errorMsg = false;
		$profileid = $request->getAttribute('profileid');
		$authenticationJsObj = new JsAuthentication();					
		$profilechecksum = md5($profileid)."i".$profileid;				
		$echecksum=$authenticationJsObj->js_encrypt($profilechecksum);
		$this->echecksum = $echecksum;
		$this->currentPicCount = $pics;							//Set the already present pic count for the template
		$this->uploadPicCount = sfConfig::get("app_max_no_of_photos")-$pics;		//Set maximum no of pics the user can upload count
	}
  }

  /**
  This function opens the compUploadNoFlash template which enables uploading from computer in case flash is not installed or a lower version is found.
  */

  public function executeCompUploadNoFlash(sfWebRequest $request)
  {
	$profileObj=LoggedInProfile::getInstance('newjs_master');                                 

        $pictureServiceObj=new PictureService($profileObj);                             
        $pics=$pictureServiceObj->getUserUploadedPictureCount();                        //Get number of pics already present for this PROFILEID
        if ($pics>=sfConfig::get("app_max_no_of_photos"))                       	//If pics already present is greater than or equal to 20
        {
		$this->redirect("social/addPhotos");					//Redirect to addPhotos template
        }
        else                                                                            //Else the pics already present is less than 20
        {
		$profileid = $request->getAttribute('profileid');
                $authenticationJsObj = new JsAuthentication();
                $profilechecksum = md5($profileid)."i".$profileid;
                $echecksum=$authenticationJsObj->js_encrypt($profilechecksum);
                $this->echecksum = $echecksum;
                $this->currentPicCount = $pics;                                         //Set the already present pic count for the template
                $this->uploadPicCount = sfConfig::get("app_max_no_of_photos")-$pics;   	//Set maximum no of pics the user can upload count
		
		$browserCheckObj = new BrowserCheck;
		$output = $browserCheckObj->getBrowser();				//Special case for Firefox browsers on windows and linux
		if ($output["platform"] == "linux")
		{
			$output = "Small";
		}
		else
		{
			$output = "Big";
		}

		if ($output == "Small")
			$this->fileTagSize = 1;
		else
			$this->fileTagSize = 0;
	}
  }
  
  /**
  This function is called when the user presses the upload button on compUpload template and this function is called recursively as many times as the no 		: of pics to be uploaded in case of flash and once in case of nonFlash
  @params flash or nonFlash
  */

  public function executeCompUploadAction(sfWebRequest $request)
  {
	$flash_available = $request->getParameter('ifFlash');					//Fetch the parameter
	ini_set('memory_limit',"256M");				//Set memory limit to 128M and not -1
	
	if ($flash_available == "flash")							//Decrypt profileid
	{
		$echecksum = $request->getParameter('echecksum');
		$authenticationJsObj = new JsAuthentication();
		$profilechecksum=$authenticationJsObj->js_decrypt($echecksum);
		$index = strrpos($profilechecksum,"i");
		$profileid = substr($profilechecksum,$index+1);
        	$profileObj=LoggedInProfile::getInstance('newjs_master',$profileid);	
	}
	else if ($flash_available == "nonFlash")
	{
        	$profileObj=LoggedInProfile::getInstance('newjs_master');			//Profile class object with temporary PROFILEID
	}
	else
	{}

	$pictureServiceObj=new PictureService($profileObj);					//PictureService class object
	$pics=$pictureServiceObj->getUserUploadedPictureCount();				//Get count of pictures already present

	if ($pics>=sfConfig::get("app_max_no_of_photos"))					//If saved pictures is greater than or equal to 20
	{
		$this->redirect("social/saveImage?err=excessError");				//Redirect to saveImage template with error message
	}
	else											//Else continue saving action
	{
		if ($flash_available == "flash")						//If parameter equals to "flash" (Flash Upload)
		{	
        	        $pictureServiceObj->saveAlbum('',"computer_flash");		//Save picture
			die;									//Kill the script
  		}
		else if ($flash_available == "nonFlash")					//Else if parameter equals to "nonFlash" (Simple Upload)
		{
        	        $output = $pictureServiceObj->saveAlbum('',"computer_noFlash");	//Save picture
			$outputArr = explode("**-**",$output);
			$uploaded_success = $outputArr[1]-$outputArr[0];
			if ($outputArr[1] == 0)
			{
				$url = "social/saveImage?err=".$uploaded_success."&size=".$outputArr[2]."&format=".$outputArr[3]."&total=".$outputArr[1]."&successCount=".$uploaded_success;
			}
			else
			{
				if ($outputArr[0] == 0)
				{
					$url = "social/saveImage?successCount=".$uploaded_success;
				}
				else
				{
					$url = "social/saveImage?err=".$uploaded_success."&size=".$outputArr[2]."&format=".$outputArr[3]."&total=".$outputArr[1]."&successCount=".$uploaded_success;
				}
			}
			$this->redirect($url);				
		}
		else										//Else
		{
			die;									//Error so kill the script
		}
	}
  }

  /**
  * This function fetches a list of urls of the photos that the user has already uploaded/imported
  * and directs to a page from where user can add more photos to his/her profile.
  */

  public function executeAddPhotos(sfWebRequest $request)
  {
	if(MobileCommon::isMobile("JS_MOBILE"))
	{
		header('Location: '.$SITE_URL."/profile/viewprofile.php?ownview=1");
		exit;
	}
	if($request->getParameter('fromCALphoto')==1)
	{
		$this->fromCALphoto = 1;
	}

	$this->keywords=sfConfig::get("app_social_keywords");//array("My photo", "My family", "My friends", "My office", "My home");
	$this->request->setAttribute('bms_sideBanner',711);

	$profileObj=LoggedInProfile::getInstance('newjs_master');
	$profileObj->getDetail("","","HAVEPHOTO,PHOTO_DISPLAY,GENDER,ENTRY_DT");
	$this->PHOTODISPLAY=$profileObj->getPHOTO_DISPLAY();
	$this->havePhoto = $profileObj->getHAVEPHOTO();
	$this->showMyjs=0;

	//this was added to add tracking for upload click from mailer
	if($request->getParameter("fromAddPhotoMailer")==1)
	{
		$photoUploadTrackingObj = new PICTURE_UPLOAD_PHOTO_FROM_MAILER_TRACKING("newjs_masterRep");
		$photoUploadTrackingObj->insertTrackingRecord($profileObj->getPROFILEID(),$request->getParameter('mailType'),date("Y-m-d"));
		unset($photoUploadTrackingObj);
	}
	$currentTime=time();
	$registrationTime = strtotime($profileObj->getENTRY_DT());
	if(($currentTime - $registrationTime)/(3600)<24)
	{
		$this->showMyjs = 1;
	}

	$this->entryDate = $profileObj->getENTRY_DT();
        $pictureServiceObj=new PictureService($profileObj);
        $ProfilePicUrlObj = $pictureServiceObj->getProfilePic();
	$this->ProfilePicUrl='';
	if (is_subclass_of($ProfilePicUrlObj, 'Picture'))
	{
		$this->profilePicPictureId = $ProfilePicUrlObj->getPICTUREID();
		$this->ProfilePicUrl = $ProfilePicUrlObj->getProfilePic235Url();
		$this->mainPicUrl = $ProfilePicUrlObj->getMainPicUrl();
		if(!$this->ProfilePicUrl)
			$this->ProfilePicUrl = $this->mainPicUrl;
	}
	else
	{
		$this->ProfilePicUrl = PictureService::getRequestOrNoPhotoUrl('noPhoto', "ProfilePic235Url", $profileObj->getGENDER());
	}

        $pics=$pictureServiceObj->getAlbum();
	if($profileObj)    
		$this->loggedInProfileId = $profileObj->getPROFILEID();

	if($pics)
	{
		$i=0;
		foreach((array)$pics as $v)
		{
			$imgUrl = '';
			$imgUrl = $v->getMainPicUrl();
			$pictureids[$i] = $v->getPICTUREID();
			$pictureUrls[$i] = $imgUrl;
			$i++;
		}

		$this->urls=$urls;
		$this->picID=$pictureID;

		$this->totalPics=sizeof($pictureUrls);
		$this->totalImages=sizeof($pictureUrls);

		if((sizeof($pics)) >= sfConfig::get("app_max_no_of_photos"))
			$this->errorMsg=1;

	}
		$this->urlsJson = json_encode($pictureUrls);	
		$this->pictureidsJson = json_encode($pictureids);	
		$this->fromReg = $request->getParameter('fromReg');
		$this->uploadType = $request->getParameter('uploadType');
		$this->cropper = $request->getParameter('cropper');
		if($this->cropper==1)
		{
			ini_set('memory_limit',"256M");
			/** if mediacdn cross domain policy comes **/
                        if(strstr($this->mainPicUrl,JsConstants::$cloudUrl))
                        {
				$timeMainPic = time();
                                $pictureObj = new NonScreenedPicture();
                                $origPic = JsConstants::$docRoot."/uploads/canvasPic/$this->profilePicPictureId"."-".$timeMainPic.".jpg";
                                PictureFunctions::setHeaders();
                                copy($this->mainPicUrl,$origPic);
                                $this->mainPicUrl = JsConstants::$siteUrl."/uploads/canvasPic/$this->profilePicPictureId"."-".$timeMainPic.".jpg";
                        }
                        /** if mediacdn cross domain policy comes **/
		}
		$this->showConf = $request->getParameter('showConf');
		$this->importPhotosBarHeightPerShift = PictureStaticVariablesEnum::$importPhotosBarHeightPerShift;
		$this->importPhotosBarCountPerShift = PictureStaticVariablesEnum::$importPhotosBarCountPerShift;
		if(PictureFunctions::IfUsePhotoDistributed($profileObj->getPROFILEID()))
			$this->imageCopyServer = IMAGE_SERVER_ENUM::getImageServerEnum($profileObj->getPROFILEID());
  }

  /**
  * This function is used to display the album of a user.
  **/
  public function executeMobilePhotoAlbum(sfWebRequest $request)
  {   
	$profilechecksum = $request->getParameter('profilechecksum');
	$linkarr = $request->getpathInfoArray();
	
	/* This cookie part is to handle a undetectable bug in UC Browser*/
	$cookieName  = "cookieForRefresh_".$profilechecksum;
	$cookieName2 = "cookieForRefresh1";
	$timeForCookie=time()+36000; // 100s for cookie
	
	foreach($_COOKIE as $cookieK=>$cookieV){
		if(strstr($cookieK,"cookieForRefresh") && $cookieK!=$cookieName && $cookieK!=$cookieName2){
			setcookie($cookieK,null,-1,"/");
		}
	}
	
	if($_COOKIE[$cookieName]){
		$referVal = $_COOKIE[$cookieName];
		setcookie ($cookieName, null,-1,'/');
		setcookie($cookieName2, $referVal,$timeForCookie,'/');
		$this->toReload=0;
	}
	else{
		
		if(strstr($linkarr["HTTP_REFERER"],"/social/MobilePhotoAlbum") && $_COOKIE[$cookieName2])
		{
			$referVal = $_COOKIE[$cookieName2];
		}
		else
		{
			$cookieValue = $linkarr["HTTP_REFERER"];
			setcookie($cookieName, $cookieValue,$timeForCookie,'/');
			$this->toReload=1;
		}
	}
	/* This cookie part is to handle a undetectable bug in UC Browser*/


	$loggedInProfileid = $request->getAttribute('profileid');
	if(!$loggedInProfileid) //this was added to ensure that POG album cannot be viewed in case of Logout.
	{
		$this->forward('static','LogoutPage');
	}	
	$requestedProfileid = NULL;

	if($profilechecksum)
	{
		$authenticationJsObj = new JsAuthentication();
		$requestedProfileid =$authenticationJsObj->jsDecryptProfilechecksum($profilechecksum);	
	}
	if($requestedProfileid==NULL || $requestedProfileid=='')
	{  
		$loggedInProfile = LoggedInProfile::getInstance('newjs_master');
		if(!$loggedInProfile || $loggedInProfile->getPROFILEID()=='')
	        {
			$this->forward('static','LogoutPage');
		}
        	$loggedInProfile->getDetail("","","HAVEPHOTO,PRIVACY,PHOTO_DISPLAY");
        	$requestedProfileid=$loggedInProfile->getPROFILEID();
	        $ProfileObj=$loggedInProfile;
	}
	else
	{

		if(PictureFunctions::conditionalPhotoAccess()) //if conditional layer is to be shown
		{
			$this->showLayer = 1;
			$this->setTemplate("mobile/mobilePhotoAlbum");
		}
		$Profile = Profile::getInstance('newjs_master',$requestedProfileid);
		$Profile->getDetail("","","HAVEPHOTO,PRIVACY,PHOTO_DISPLAY");
		if($Profile->getPHOTO_DISPLAY()=='C')
		{
                        include_once(sfConfig::get("sf_web_dir")."/profile/connect_functions.inc");
                        include_once(sfConfig::get("sf_web_dir")."/profile/contacts_functions.php");
                        $contact_status_new = get_contact_status_dp($requestedProfileid,$loggedInProfileid);
                        if($contact_status_new["R_TYPE"])
                                $contact_status = $contact_status_new["R_TYPE"];
                        else
                                $contact_status = $contact_status_new["TYPE"];
		}
		$ProfileObj=$Profile;		

	}
	$picServiceObj = new PictureService($ProfileObj);
	$album = $picServiceObj->getAlbum($contact_status);
	if(is_array($album))
	{
		$this->countPics = count($album);
		//Code for album view logging
		if($requestedProfileid != $loggedInProfileid)
		{
                        $producerObj = new Producer();
                        $loggedInProfileForLogging = LoggedInProfile::getInstance('newjs_master');
                        if($loggedInProfileid && $loggedInProfileid%PictureStaticVariablesEnum::photoLoggingMod<PictureStaticVariablesEnum::photoLoggingRem && $loggedInProfileForLogging->getGENDER()!= $ProfileObj->getGENDER()){
                            if($producerObj->getRabbitMQServerConnected()){
                                $triggerOrNot = "inTrigger";
                                $queueData = array('process' =>MessageQueues::VIEW_LOG,'data'=>array('type' => $triggerOrNot,'body'=>array('VIEWER'=>$loggedInProfileid,VIEWED=>$requestedProfileid)), 'redeliveryCount'=>0 );
                                $producerObj->sendMessage($queueData);
                            }
                            else{    
                                $vlt=new VIEW_LOG_TRIGGER();
                                $vlt->updateViewTrigger($loggedInProfileid,$requestedProfileid);
                            }
                        }
//			$channel = MobileCommon::getChannel();
//			$date = date("Y-m-d H:i:s");
//			$albumViewLoggingObj = new albumViewLogging();
//			$albumViewLoggingObj->logProfileAlbumView($loggedInProfileid,$requestedProfileid,$date,$channel);
		}		
	}
	else if($requestedProfileid)
		$this->redirect(sfConfig::get("app_site_url")."/profile/viewprofile.php?profilechecksum=".$profilechecksum);
	else
		$this->redirect(sfConfig::get("app_site_url")."/social/MobilePhotoUpload");
		
	foreach($album AS $photoKey=>$photoData)
	{
		$mob_img_url[] =  $photoData->getMainPicUrl();
		$pictureId[] =	 $photoData->getPICTUREID();
	}
	$this->mob_img_url=$mob_img_url;
	$this->pictureId=$pictureId;
	if(!$requestedProfileid)
	{
		$this->goBackLink=sfConfig::get('app_site_url')."/profile/viewprofile.php?ownview=1";
	}
	else
	{
		 $linkarr = $request->getpathInfoArray();
		 if($referVal)
			$this->goBackLink = $referVal; 
		 elseif($linkarr["HTTP_REFERER"])
			$this->goBackLink = $linkarr["HTTP_REFERER"]; 
		 else
			$this->goBackLink = sfConfig::get('app_site_url')."/profile/viewprofile.php?profilechecksum=".$profilechecksum;
			
		if($request->getParameter('bg'))
			$this->goBackLink = $this->goBackLink."#".$request->getParameter('bg');
	}
	
		//print_r($this->goBackLink);die;
	if($request->getParameter('setProfilePic')==1 && $loggedInProfileid==$requestedProfileid)
		$this->purposeOfAlbumView = "setProfilePic";
	elseif($loggedInProfileid==$requestedProfileid)
		$this->purposeOfAlbumView = "OwnAlbum";
	$this->setTemplate("mobile/mobilePhotoAlbum");	  
  }
  /**
  * This function is used to display the album of a user.
  **/
  public function executeAlbum(sfWebRequest $request)
  { 
	 MobileCommon::forwardmobilesite($this);
	 
	if(!$profilechecksum)
		$profilechecksum = $request->getParameter('profilechecksum');
		
	if(MobileCommon::isDesktop())
	{
		$this->redirect("/profile/viewprofile.php?profilechecksum=".$profilechecksum);
	}

	$loggedInProfileid = $request->getAttribute('profileid');

	$authenticationJsObj = new JsAuthentication();
	$requestedProfileid=$authenticationJsObj->jsDecryptProfilechecksum($profilechecksum);

	if($requestedProfileid == '')
	{
		$this->profileDoesntExist = 1;
	}
	else
	{
		if($requestedProfileid == $loggedInProfileid)
		{
			$this->selfProfile=1;
			$profileObj=LoggedInProfile::getInstance('newjs_master',$loggedInProfileid);
		}
		else
			$profileObj = Profile::getInstance('newjs_master',$requestedProfileid);

		$profileObj->getDetail("","","HAVEPHOTO,PHOTO_DISPLAY,USERNAME");
		$photodisplay=$profileObj->getPHOTO_DISPLAY();
		$this->USERNAME = $profileObj->getUSERNAME();	

		if($photodisplay == 'C')
		{
			include_once(sfConfig::get("sf_web_dir")."/profile/connect_functions.inc");
			include_once(sfConfig::get("sf_web_dir")."/profile/contacts_functions.php");
			
			$contact_status_new = get_contact_status_dp($requestedProfileid,$loggedInProfileid);
	//		$contact_status_new = get_contact_status_dp($loggedInProfileid,$requestedProfileid);
			if($contact_status_new["R_TYPE"])
				$contact_status = $contact_status_new["R_TYPE"];
			else
				$contact_status = $contact_status_new["TYPE"];
		}
		$pictureServiceObj=new PictureService($profileObj);
		$album=$pictureServiceObj->getAlbum($contact_status);

		if($album)
		{
			$this->noOfPics = sizeof($album);

			// MIS logging by Reshu for Album view start
 			$MISviewAlbum= new AlbumViewLog();
                	$MISviewAlbum->misViewAlbumInsert($requestedProfileid,$_SERVER[HTTP_REFERER],$this->noOfPics);
			//MIS logging end			
	
			foreach((array)$album as $index=>$photo)
			{
				$mainPicUrls[]=$photo->getMainPicUrl();
				$title[]=$photo->getTITLE();
				$keywords[]=$photo->getKEYWORD();
			}
		
			$keywordArr = sfConfig::get("app_social_keywords");
			foreach((array)$keywords as $value)
			{
				$keystr = explode(",",$value);
				$k = '';
				if($keystr)
				{
					foreach((array)$keystr as $val)
					{
						if($k=='')
							$k.=$keywordArr[$val-1];
						else
						{
							$k.=", ";
							$k.=$keywordArr[$val-1];
						}
					}
					$keywordsStr[] = $k;
				}
				else
					$keywordsStr[] = '';
			}
		}
		$this->mainPicUrls = $mainPicUrls;
		$this->titleArr = $title;
		$this->keywords = $keywordsStr;
		if(!$album)
		{
			$this->noAlbumToDisplay=1;
		}
	}
    if ($loggedInProfileid)
        $this->LOGGEDIN = 1;

	if(MobileCommon::isMobile("JS_MOBILE"))
	{
		$pg1=$request->getParameter("pg1");
		if($pg1)
			$this->prev=$pg1-1;
		else
		{
			$this->prev=$this->noOfPics-1;
			$pg1=0;
		}
		if($mainPicUrls[$pg1+1])
			$this->next=$pg1+1;
		else
			$this->next=0;
                $this->httpRef = $_SERVER["HTTP_REFERER"];
                if(!strstr($_SERVER["HTTP_REFERER"],'searchId'))
                {
                        if(strstr($_SERVER["HTTP_REFERER"],'?'))
                                $this->httpRef.="&searchId=".$request->getParameter('searchId');
                        else
                                $this->httpRef.="?searchId=".$request->getParameter('searchId');
                }
                if($request->getParameter('searchId')=="")
                {
                        $this->httpRef=sfConfig::get('app_site_url')."/profile/viewprofile.php?profilechecksum=".$profilechecksum;
                }
                
                $naviObj=new Navigator();
                $naviObj->navigation("MVS","","");
                $this->BREADCRUMB=$naviObj->onlyBackBreadCrumb;
                $this->NAVIGATOR=$naviObj->NAVIGATOR;
                $this->nav_type="MVS";
		$SITE_URL=sfConfig::get('app_site_url');
		$this->titleMob=$title[$pg1];
		$this->countPics=count($mainPicUrls);
		$this->currentPicNumber=$pg1+1;
		$this->keywordMob=$keywordsStr[$pg1];
		//$this->mob_img_url=$SITE_URL."/jsmb/image_resize.php?url=".urlencode($mainPicUrls[$pg1])."&w=360&h=480";
		$this->mob_img_url=$mainPicUrls[$pg1];
		$this->NAV_TYPE=$request->getParameter('nav_type');
		$this->PROFILECHECKSUM=$request->getParameter('profilechecksum');
		//$this->setTemplate("jsmb_view_album");
		$this->setTemplate("mobile/mobileAlbum");
	}
	else
	{
		if($request->getParameter('searchPage') == 1)
		{
			$xmlObj = new CreateXml;
                        $domtree = $xmlObj->createDoc();

                        $xmlRoot = $xmlObj->addChildWithoutValue($domtree,$domtree,"userData",1);

                        $xmlObj->addChildWithValue($domtree,$xmlRoot,"username",$this->USERNAME);
                        $xmlObj->addChildWithValue($domtree,$xmlRoot,"profileDoesntExist",$this->profileDoesntExist);
                        $xmlObj->addChildWithValue($domtree,$xmlRoot,"noOfPics",$this->noOfPics);
                        $xmlObj->addChildWithValue($domtree,$xmlRoot,"noAlbumToDisplay",$this->noAlbumToDisplay);
                        $xmlObj->addChildWithValue($domtree,$xmlRoot,"loggedin",$this->LOGGEDIN);
                        $xmlObj->addChildWithValue($domtree,$xmlRoot,"imageUrl",sfConfig::get("app_img_url"));

                        if($this->noOfPics)
                        {
                                foreach($this->mainPicUrls as $k=>$v)
                                {
                                        $currentTrack = $xmlObj->addChildWithoutValue($domtree,$xmlRoot,"albumInfo",1);
                                        $xmlObj->addChildWithValue($domtree,$currentTrack,"url",str_replace("&","&#038;",$this->mainPicUrls[$k]));
                                        $xmlObj->addChildWithValue($domtree,$currentTrack,"title",$this->titleArr[$k]);
                                        $xmlObj->addChildWithValue($domtree,$currentTrack,"keywords",$this->keywords[$k]);
                                }
                        }

                        $output = $xmlObj->saveDoc($domtree);
			header('content-type: text/xml');
			echo $output;
			die;
		}
	}
  }

  /**
  * This function unsets all cookies which will be used later for the photo import procedure 
  * and directs to a page where user is asked for permission to import from facebook/flickr/picasa.
  */
  public function executeImportPermission(sfWebRequest $request)
  {
	$profileObj=LoggedInProfile::getInstance('newjs_master');
	$profileid = $request->getAttribute('profileid');
	$this->importSite=$request->getParameter('importSite');
	$PHOTO_URL=new PHOTO_URL;
	$PHOTO_URL->emptyTable($profileid,$this->importSite);
	$albumObj = new FacebookAlbumsData();
	$albumObj->deleteAlbumData();

	setcookie ("import_aid_$this->importSite","",time()-3600,"/");
	setcookie ("IMPORT_SELPICS_$this->importSite","",time()-3600,"/");
	setcookie ("IMPORT_PREVPAGE_$this->importSite","",time()-3600,"/");
	setcookie ("IMPORT_NEXTPAGE_$this->importSite","",time()-3600,"/");
	setcookie ("IMPORT_PIC_$this->importSite","",time()-3600,"/");

	unset($_COOKIE["import_aid_$this->importSite"]);
	unset($_COOKIE["IMPORT_SELPICS_$this->importSite"]);
	unset($_COOKIE["IMPORT_PREVPAGE_$this->importSite"]);
	unset($_COOKIE["IMPORT_NEXTPAGE_$this->importSite"]);
	unset($_COOKIE["IMPORT_PIC_$this->importSite"]);
	//print_r($_COOKIE);


	$trackingObj=new importUploadTracking;
	$trackingObj->pageVisitCounterUpdate($profileid,'PERMISSION_PAGE',$this->importSite);

  }


  /**
  * Executes the full import action i.e. selecting the albums whose photos are to be displayed,
  * and then selecting the photos to be imported.
  * For easy understanding, the flow for this function is documented at the following link:
  * http://devjs.infoedge.com/mediawiki/wikiImages/photoManagemnet/import_process_flow.flw
  */
  public function executeImport(sfWebRequest $request)
  {
        //print_r($request->getParameterHolder()->getAll());
	$profileObj=LoggedInProfile::getInstance('newjs_master');
	$profileObj->getDetail("","","HAVEPHOTO,PHOTO_DISPLAY");

	$this->profileid = $request->getAttribute('profileid');
	$pictureServiceObj=new PictureService($profileObj);
	$albumList=$pictureServiceObj->getAlbum();

	$this->importSite=$request->getParameter('importSite');
	$this->importLimit=0;
	if($albumList)
		$this->importLimit=sizeof($albumList);

	$this->limit=sfConfig::get("app_max_no_of_photos") - $this->importLimit;  //no of photos that can be uploaded/imported by the user
	$picObj=ImportPhotoFactory::getPhotoAgent($request->getParameter('importSite'));
	if($request->getParameter('listAlbum'))
	{
		$albumIdArr=array();
		$albumNameArr=array();
		$albumCoverImageArr=array();
		$authVariable='';

		$picObj->getAlbumList();
		$pictureServiceObj->associateJsUser_with_importUniqueId($this->profileid,$picObj->getUserIdentity(),$this->importSite);
		$this->final = $picObj->final;
		$resultArr["data"] = $this->final;
	}
	if($listPhotos || $request->getParameter('listPhotos'))
	{
		if(!$listPhotos)
		{
			$listPhotos = $request->getParameter('listPhotos');
			$listPhotos = ltrim($listPhotos,'album');
		}
		$picObj=ImportPhotoFactory::getPhotoAgent($request->getParameter('importSite'));
		$resultArr["photos"] = $picObj->getAllAlbumPhotos($listPhotos);
		$resultArr["active"] = 'album'.$listPhotos;
	}
	$respObj = ApiResponseHandler::getInstance();
	$respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
	$respObj->setResponseBody($resultArr);
	$respObj->generateResponse();
	return sfView::NONE;
  }




  /**
  * Executes the full import action i.e. selecting the albums whose photos are to be displayed,
  * and then selecting the photos to be imported.
  * For easy understanding, the flow for this function is documented at the following link:
  * http://devjs.infoedge.com/mediawiki/wikiImages/photoManagemnet/import_process_flow.flw
  */
  public function executeImport1(sfWebRequest $request)
  {
	$profileObj=LoggedInProfile::getInstance('newjs_master');
	$profileObj->getDetail("","","HAVEPHOTO,PHOTO_DISPLAY");
	$this->profileid = $request->getAttribute('profileid');
	$pictureServiceObj=new PictureService($profileObj);
	$albumList=$pictureServiceObj->getAlbum();

	$this->importSite=$request->getParameter('importSite');
	$this->importLimit=0;
	if($albumList)
		$this->importLimit=sizeof($albumList);

	$this->limit=sfConfig::get("app_max_no_of_photos") - $this->importLimit;  //no of photos that can be uploaded/imported by the user
	$picObj=ImportPhotoFactory::getPhotoAgent($request->getParameter('importSite'));
	$picObj->getAlbumList();
	$this->picData =json_encode($picObj->final);
	$this->importPhotosBarHeightPerShift = PictureStaticVariablesEnum::$importPhotosBarHeightPerShift;
$this->importPhotosBarCountPerShift = PictureStaticVariablesEnum::$importPhotosBarCountPerShift;
  }





  /**
  This function is used to display the saveImage template which shows all the pics saved for a PROFILEID along with their title and keywords
  @param err,successCount,total
  */
  public function executeSaveImage(sfWebRequest $request)
  {
    $this->forward("social", "addPhotos");
    die;
	$this->profileid = $request->getAttribute('profileid');

	$this->importSite=$request->getParameter('importSite');
	$urlObj= new PHOTO_URL();
	$urlObj->emptyTable($this->profileid,$this->importSite);
	$albumObj = new FacebookAlbumsData();
	$albumObj->deleteAlbumData();

        setcookie ("import_aid_$this->importSite","",time()-3600,"/");
        setcookie ("IMPORT_SELPICS_$this->importSite","",time()-3600,"/");
        setcookie ("IMPORT_PREVPAGE_$this->importSite","",time()-3600,"/");
        setcookie ("IMPORT_NEXTPAGE_$this->importSite","",time()-3600,"/");
        setcookie ("IMPORT_PIC_$this->importSite","",time()-3600,"/");
	unset($_COOKIE["import_aid_$this->importSite"]);
	unset($_COOKIE["IMPORT_SELPICS_$this->importSite"]);
	unset($_COOKIE["IMPORT_PREVPAGE_$this->importSite"]);
	unset($_COOKIE["IMPORT_NEXTPAGE_$this->importSite"]);
	unset($_COOKIE["IMPORT_PIC_$this->importSite"]);

	$profileObj=LoggedInProfile::getInstance('newjs_master');
	$profileObj->getDetail("","","GENDER,MTONGUE,RELIGION,AGE,HAVEPHOTO,PHOTO_DISPLAY,SOURCE,INCOMPLETE"); 
	/* pixel code tracking*/
	$grpName = $profileObj->getSOURCE();
	$dbSource = new MIS_SOURCE();
	$resource = $dbSource->getSourceFields("GROUPNAME",$grpName);
        $pictureServiceObj=new PictureService($profileObj);
	
	//Rocket fuel pixel for upload photo page
	if(PixelCode::RocketFuelValidation($resource["GROUPNAME"],$grpName,$profileObj->getAGE(),$profileObj->getGENDER(),$profileObj->getMTONGUE(),$profileObj->getRELIGION()))
		$this->pixelcode = $pictureServiceObj->getRocketFuelCodeForPhoto($profileObj->getPROFILEID(),$profileObj->getINCOMPLETE());
	/* pixel code tracking*/

	$pics=$pictureServiceObj->getUserUploadedPictureCount();		//Get count of pictures already present
        $err=$request->getParameter('err');					//Fetch the parameter
        $successCount=$request->getParameter('successCount');			//Fetch the parameter

	if ($pics!=$successCount)
	{
		$this->firstTime = false;
	}
	else
	{
		$this->firstTime = true;
	}
	
	if($err == "excessError")						//If error due to more than 20 pics
	{										
		$this->errorMsg="true";						//Set error message as "true"
	}
	elseif($err!="")							//in case when few of the selected photos are saved
	{
        	$total=$request->getParameter('total');
		if ($request->getParameter('size'))
		{
			$this->sizeErrCount = $request->getParameter('size');
		}
		if ($request->getParameter('format'))
        	{
			$this->formatErrCount = $request->getParameter('format');
			$this->displayPicFormat = "";
			$separatorIndex = strrpos(sfConfig::get("app_photo_formats"),",");
			if ($separatorIndex)
			{
				$this->displayPicFormat = substr_replace(strtoupper(sfConfig::get("app_photo_formats"))," and",$separatorIndex,1);
			}
			else
			{
				$this->displayPicFormat = sfConfig::get("app_photo_formats");
			}
		}

		if ($pics>=sfConfig::get("app_max_no_of_photos"))
			$this->thresholdLimit = true;
		else
			$this->thresholdLimit = false;

		$this->errorMsg2 = true;
		$this->actualPhotosUploaded = $err;
		$this->totalPhotosToUpload = $total;
	}

	$this->keywords=sfConfig::get("app_social_keywords");
	
        
	$savedPics=$pictureServiceObj->getAlbum();				//Get all picture objects in an array

	//Fetch the details of all the picture objects in separate arrays
	if($savedPics)
	{
		foreach((array)$savedPics as $k=>$v)
		{
			$savedurls[]=$v->getThumbail96Url();
			$pictureID[]=$v->getPICTUREID();
			$title[]=$v->getTITLE();
			$keyword1[]=$v->getKEYWORD();
			$picType[]=$v->getPictureType();
			$order=$v->getOrdering();
        	      	if($order==0)
        	      		$this->profilePicture=$k;
		}
	//Fetching of details ENDS here

		//Set the details to be displayed for the template
		$this->array10=$savedurls;
		$this->titleArr=$title;
		$this->keywordArrStr=$keyword1;
		$this->picIdArr=$pictureID;
		$this->picType=$picType;
		$this->allPhotoIdsString = implode(",",$pictureID);
		//Setting of details for the template ENDS here

		//The loop below checks for the keywords for each picture. keyword1[] array has the keyword indexes. From the indexes we fetch the keyword names. If more than 1 keyword then we keep 1st keyword followed by 3 dots. If 1 keyword then just the name of the keyword. If no keyword then ""

		for ($j=0;$j<count($keyword1);$j++)
		{
			if (trim($keyword1[$j]) == "")
			{
				$dropdownKeywordsLabelArr[$j] = "";
			}
			else
			{
				$currentKeywordArr = explode(",",$keyword1[$j]);
	
			if (count($currentKeywordArr)>2)
			{
				$dropdownKeywordsLabelArr[$j] = $this->keywords[$currentKeywordArr[0]-1].", ".$this->keywords[$currentKeywordArr[1]-1].", ...";	
			}
			else if (count($currentKeywordArr)==2)
			{
				$dropdownKeywordsLabelArr[$j] = $this->keywords[$currentKeywordArr[0]-1].", ".$this->keywords[$currentKeywordArr[1]-1];
			}
			else
			{
				$dropdownKeywordsLabelArr[$j] = $this->keywords[$currentKeywordArr[0]-1];
			}
		}
	} //The LOOP ENDS here
	
	$this->dropdownKeywordsLabel = $dropdownKeywordsLabelArr;			//Set the keyword names array for the disabled dropdown in the template

	}
	else
		$this->noPhotosError=1;
  }

 /**
 * This function is used to delete a saved photo.
 **/
  public function executeDeletePic(sfWebRequest $request)
  {
	$profileObj=LoggedInProfile::getInstance('newjs_master');
	$pid=$profileObj->getPROFILEID();
	if(!$pid)
		die("userTimedOutDelete");
	$profileObj->getDetail("","","HAVEPHOTO,PHOTO_DISPLAY"); 
	$pictureServiceObj=new PictureService($profileObj);
        $picId=$request->getParameter('picId'); //pic to b deleted
        $pic_id=$request->getParameter('pic_id'); //currently selected profile pic
	if($pic_id)
	{
		$whereArr["PICTUREID"] = $pic_id;
		$whereArr["PROFILEID"] = $pid;
		$pictureObj=$pictureServiceObj->getPicDetails($whereArr);
		$status=$pictureServiceObj->setProfilePic($pictureObj[0]);
	}

	$delStatus=$pictureServiceObj->deletePhoto($picId,$profileObj->getPROFILEID());
	if($delStatus)
		echo '<img src="'.sfConfig::get("app_img_url").'/images/del.png">';
	else
		echo '<img src="'.sfConfig::get("app_img_url").'/images/del.png">';
//sleep(5);
        die;
  }

 /**
 * When a user tried to delete a photo, this function is called. It is used to display a layer which asks the user for permission to delete a particular photo
 **/
  public function executeDeleteLayer(sfWebRequest $request)
  {
	$profileObj=LoggedInProfile::getInstance('newjs_master');
	$pid=$profileObj->getPROFILEID();
	if(!$pid)
	{
		$this->userTimedOutError = true;
	}
	else
	{
		$this->userTimedOutError = false;
        	$this->picId=$request->getParameter('picId');
        	$this->delId=$request->getParameter('delId');
        	$this->ifProf=$request->getParameter('ifProf');
        	$this->origProf=$request->getParameter('origProf');
	}
  }

 /**
 * When a user tries to deletes the profile picture, this function is called. 
 * It displays a layer which tells the user to select another photo as profile photo before deleting the current profile picture.
 **/
  public function executeDeleteProfilePicLayer(sfWebRequest $request)
  {
	$profileObj=LoggedInProfile::getInstance('newjs_master');
	$pid=$profileObj->getPROFILEID();
	if(!$pid)
	{
		$this->userTimedOutError = true;
	}
	else
	{
		$this->userTimedOutError = false;
	}
  }

  public function executeViewPage(sfWebRequest $request)
  {
        $obj = new Profile();
        $this->show = $obj->profileObj->getName($request->getParameter('profileid'));
  }

 /**
 * This function is called whenever the transition takes place
 * 1) from albums page to photos page
 * 2) on clicking previous/next buttons on photos page
 * It is used to display a layer which shows the page is loading
 **/
  public function executeLoadingLayer(sfWebRequest $request)
  {
	$this->importSite=$request->getParameter('importSite');
	$this->profileid=$request->getAttribute('profileid');
	$fromAlbumPage=$request->getParameter('fromAlbumPage');

//	if(strstr($_SERVER['HTTP_REFERER'],"session_key") || strstr($_SERVER['HTTP_REFERER'],"token"))
//	if(!$_COOKIE["IMPORT_NEXTPAGE_$this->importSite"] && !$_COOKIE["IMPORT_PREVPAGE_$this->importSite"])
	if($fromAlbumPage == 1)
	{
		$PHOTO_URL=new PHOTO_URL;
		$PHOTO_URL->emptyTable($this->profileid,$this->importSite);
		setcookie ("IMPORT_SELPICS_$this->importSite","",time()-3600,"/");
		unset($_COOKIE["IMPORT_SELPICS_$this->importSite"]);
	}

  }

 /**
 * This function is called while images are being imported.
 * It is used to check the number of photos that have been imported.
 **/
  public function executeCheckImageStatus(sfWebRequest $request)
  {
	$pid = $request->getAttribute('profileid');
        $formArr = $request->getParameterHolder()->getAll();
        $imageId=$formArr['saveImage'];
        $arr=explode("|",$imageId);
        $idList="('".implode("','",$arr)."')";

        $PHOTO_URL=new PHOTO_URL;
        $arr=$PHOTO_URL->mapURL($pid,$idList);
        $count=$formArr['total']-count($arr);
        echo $count;
        die;
  }

 /**
 * This function is used to save several photos simultaneously.
 **/
  public function executeSaveImportImages(sfWebRequest $request)
  {
//VA whitelisting
	SendMail::send_email("eshajain88@gmail.com,lavesh.rawat@gmail.com","executeSaveImportImages loop which was assumed not to be in use in social/actions","executeSaveImportImages called");
        $profileObj=LoggedInProfile::getInstance('newjs_master'); 
//throw new jsException("","PROFILEID & PICTUREID IS BLANK IN get() of PICTURE_NEW.class.php");
	$pid=$profileObj->getPROFILEID();
	if(!$pid)
		die("userTimedOut");
        $formArr = $request->getParameterHolder()->getAll();
	$importSite=$formArr['importSite'];
        $imageId=$formArr['saveImage'];
        $arr=explode("|",$imageId);
        $path=sfConfig::get('sf_app_module_dir')."/social/actions/photoSaveMultiTheading.php";
        foreach($arr as $id)
        {
                if($id)
                        passthru(sfConfig::get('app_php5path')." -q $path $id $pid $importSite >> /dev/null &");
        }
		
        echo "Done";
        die;
  }

  /**
  *This function display the page "Import Failed" when the connection is timedout
  **/
  public function executeImportFailed(sfWebRequest $request)
  {
        $profileObj=LoggedInProfile::getInstance('newjs_master'); 
	$profileid=$profileObj->getPROFILEID();
	$this->importSite=$request->getParameter('importSite');
	$trackingObj=new importUploadTracking;
	$trackingObj->pageVisitCounterUpdate($profileid,'IMPORT_FAILED',$this->importSite);
  }

  /**
  This function is used to update MIS entry for flash upload.
  @param successCount as the pics successfully uploaded.
  */

  public function executeFlashMisEntry(sfWebRequest $request)
  {
        $profileObj=LoggedInProfile::getInstance('newjs_master'); 
	$profileid=$profileObj->getPROFILEID();
	$successCount = $request->getParameter('successCount');
	$trackingObj = new importUploadTracking();
      	$trackingObj->photoSaveEntry($profileid,'computer_flash',$successCount);
	die;
  }


	/*
	*This function is called when save button in cropper is clicked to crop photo and save resized images
	* takes cropbox dimensions, new dimensions array and image src as inputs 
	*/
	public function executeProcessCropper(sfWebRequest $request)
	{
		ini_set('memory_limit',"512M");
		$profileObj=LoggedInProfile::getInstance('newjs_master');
		$profileid = $profileObj->getPROFILEID();
		$profileObj->getDetail("","","HAVEPHOTO");

		$cropImageSource = $request->getParameter('imageSource');
		$cropBoxDimensionsArr = $request->getParameter("cropBoxDimensionsArr");		
	        $imgPreviewTypeArr = $request->getParameter('imgPreviewTypeArr');

                $cropperProcessObj = new CropperProcess($profileObj);
                $profilesUpdate = $cropperProcessObj->process($cropImageSource,$cropBoxDimensionsArr,$imgPreviewTypeArr);
                $pictureServiceObj =new PictureService($profileObj);
                if(is_array($profilesUpdate))
                        $output = $pictureServiceObj->setPicProgressBit("FACE",$profilesUpdate);
                else
                        $output = -1;
                unset($pictureServiceObj);
            // Flush memcache for header picture
                $memCacheObject = JsMemcache::getInstance();
                $memCacheObject->remove($profileid . "_THUMBNAIL_PHOTO");

		$respObj = ApiResponseHandler::getInstance();
		if($output == 1)
			$respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
		else
			$respObj->setHttpArray(ResponseHandlerConfig::$FAILURE);
		$respObj->setResponseBody($profilesUpdate);  //response to be decided and failure case:LATER
		$respObj->generateResponse();
		unset($profilesUpdate);
		die;
	}

	/*
	This function is called when the upload button for photo is pressed on mobile 
	*/
	public function executeMobileUploadAction(sfWebRequest $request)
	{
		$profileObj=LoggedInProfile::getInstance('newjs_master');
		$this->pageName = $request->getParameter("page");
		$profileObj->getDetail("","","USERNAME");
		if($profileObj && $profileObj->getPROFILEID())
		{
			if($request->getParameter("uploadType")=="photo")
			{
				$pictureServiceObj=new PictureService($profileObj);					//PictureService class object
				$pics=$pictureServiceObj->getUserUploadedPictureCount();

				if ($pics>=sfConfig::get("app_max_no_of_photos"))         		//If saved pictures is greater than or equal to 20
        			{
                			$this->err="excessError";      
        			}
        			else                                                                                    //Else continue saving action
        			{
					$outputArr = $pictureServiceObj->saveAlbum('',"mobileUpload");	//Save picture
					$uploaded_success = $outputArr['ActualFiles']-$outputArr['ErrorCounter'];
					if ($outputArr['ActualFiles'] == 0)
					{
						$this->actualPhotosUploaded = $uploaded_success;
						$this->sizeErrCount = $outputArr['SizeErrorCounter'];
						$this->formatErrCount = $outputArr['FormatErrorCounter'];
						$this->totalPhotosToUpload = $outputArr['ActualFiles'];
						$this->err = "error";
						$this->extraPhotosNotUploaded = '';
					}
					else
					{
						if ($outputArr['ErrorCounter'] == 0)
						{
							$this->actualPhotosUploaded = $uploaded_success;
							$this->totalPhotosToUpload = $outputArr['ActualFiles'];
							$this->extraPhotosNotUploaded = '';
							if($uploaded_success != $outputArr['ActualFiles'])
								$this->err = "error";
						}
						else
						{
							$this->actualPhotosUploaded = $uploaded_success;
							$this->sizeErrCount = $outputArr['SizeErrorCounter'];
							$this->formatErrCount = $outputArr['FormatErrorCounter'];
							$this->totalPhotosToUpload = $outputArr['ActualFiles'];
							$this->err = "error";
							$this->extraPhotosNotUploaded = '';
						}
					}

				}

				$pics=$pictureServiceObj->getUserUploadedPictureCount();
				$this->picsInAlbum = $pics;

				if(isset($this->err))                     //in case when few of the selected photos are saved
				{
					if ($this->formatErrCount)
					{
						$this->displayPicFormat = "";
						$separatorIndex = strrpos(sfConfig::get("app_photo_formats"),",");
						if ($separatorIndex)
						{
							$this->displayPicFormat = substr_replace(strtoupper(sfConfig::get("app_photo_formats"))," and",$separatorIndex,1);
						}
						else
						{
							$this->displayPicFormat = sfConfig::get("app_photo_formats");
						}
					}
				}	

				if ($this->picsInAlbum>=sfConfig::get("app_max_no_of_photos"))
					$this->thresholdLimit = true;
				else
					$this->thresholdLimit = false;

				$this->uploadCount = count($_FILES["photoInput"]["name"]);
			}
			elseif($request->getParameter("uploadType")=="mail")
			{
				$this->username = $profileObj->getUSERNAME();
			}
			$this->uploadType = $request->getParameter("uploadType");
		}
	}

	//This function is called to perform photo request to a profile
	public function executePhotoRequest(sfWebRequest $request)
  	{	
		$profileObj=LoggedInProfile::getInstance('newjs_master');
		if($profileObj && $profileObj->getPROFILEID())
		{	
			$profileObj->getDetail("","","ACTIVATED,GENDER");
			
			if($request->getParameter("showtemp"))		//This parameter is sent when only photo request layer needs to be opened
			{
                                $this->profilechecksum = $request->getParameter("profilechecksum");
			}
			else		//This is the case when actual photo request takes place
			{
				$profileId = $profileObj->getPROFILEID();
				$receiverProfileId = JsAuthentication::jsDecryptProfilechecksum($request->getParameter("profilechecksum"));
				//$receiverProfileId = 3809824;
			
				if($profileObj->getHAVEPHOTO()=="Y" || $profileObj->getHAVEPHOTO()=="U")
					$this->photohave = "Y";
				if($profileObj->getSUBSCRIPTION()=="" || $profileObj->getSUBSCRIPTION()=="D")
					$this->photosubs = "Y";
				if($receiverProfileId)
				{
				$receiverObj = Profile::getInstance("newjs_master",$receiverProfileId);
				$receiverObj->getDetail("","","USERNAME,GENDER,PRIVACY");

				$this->USERNAME = $receiverObj->getUSERNAME();
				$this->PROFILECHECKSUM = $request->getParameter("profilechecksum");
                                
				$psObj = new PictureService($receiverObj);
				$output = $psObj->performPhotoRequest();
				}
				else
				{
					$output = "InvalidReceiver";
				}
				if($output == "Success")
				{	$output = "true";
                                        if($receiverObj->getGENDER()=="F")
                                                $heSheCall = "she";
                                        else
                                                $heSheCall = "he";
					$successMessage = "Your photo request has been sent to ".$this->USERNAME.". We will inform you when $heSheCall uploads photo.";
				}
				if($output == "InvalidReceiver")
				{
					$output = "I";
					$successMessage = "Receiver provided is invalid";
				}
				elseif($output == "SameGender")
				{
						$output = "G";
					$successMessage = "You cannot request photo to a person of the same gender.";
				}
				elseif($output == "FilteredProfile")
				{
						$output = "F";
					$successMessage = "You cannot request photo as this person has filtered you.";
				}
				elseif($output == "ExceededLimit")
				{
						$output = "E";
					$successMessage = "You have already requested this user for photo.";
				}
				elseif($output == "SenderNotActivated")
				{
						$output = "U";
					$successMessage = "You cannot request photo as your profile is still being screened.";
				}
				elseif($output == "NotLogin")
				{
						$output = "LOGIN";
						$successMessage = "Please login first to request photo";
				}
				$this->isMobile=MobileCommon::isMobile("JS_MOBILE");
				if($this->isMobile)
				{	
                                        $naviObj=new Navigator();
                                        $naviObj->navigation("MVS","","");
                                        $this->BREADCRUMB=$naviObj->onlyBackBreadCrumb;
                                        $this->NAVIGATOR=$naviObj->NAVIGATOR;
                                        $this->nav_type="MVS";
                      
					$httpRefObj = new CommonUtility();
					$this->httpRef=$httpRefObj->jsmsHttpRef($request);
					$this->output=$output;
					$this->executionMessage=$successMessage;
					$this->setTemplate("mobile/mobilePhotoRequest");
				}
				else
				{
				echo $output;
				die;
				}	
			}
		}
		else
		{
			$output = "LOGIN";
			$successMessage = "Please login first to request photo";
						
			if($request->getParameter("newPR"))
			{
				$this->isMobile=MobileCommon::isMobile("JS_MOBILE");
				if($this->isMobile)
				{		
					$this->output=$output;
					$this->executionMessage=$successMessage;
					$this->setTemplate("mobile/mobilePhotoRequest");
				}
				else
				{
				echo $output;
				die;
				}
			}
			else
				$this->forward("static","loginLayer");
		}	
	}
  public function executeImportFbV1(sfWebRequest $request)
  {
        $profileObj=LoggedInProfile::getInstance('newjs_master');
        $profileObj->getDetail("","","HAVEPHOTO,PHOTO_DISPLAY");
        $photoUrl = $request->getParameter("urlToSave");
	$importSite = $request->getParameter("importSite");
	if(!$importSite)
		$importSite = "facebook";
        $pictureServiceObj=new PictureService($profileObj);
        $pictureidArr=$pictureServiceObj->saveAlbum($photoUrl,"import",$profileObj->getPROFILEID(),$importSite);

	if(is_array($pictureidArr))
		$uploaded = true;
	else
		$uploaded=false;
	$pictureid = $pictureidArr['PIC_ID'];
	if(($setProfilePic=$request->getParameter("setProfilePhoto"))=="Y")
	{
                $whereArr["PICTUREID"] = $pictureid;
                $whereArr["PROFILEID"] = $profileObj->getPROFILEID();
                $currentPicObj = $pictureServiceObj->getPicDetails($whereArr,1);                        //Get picture object correspondingto Picture ID
                $status=$pictureServiceObj->setProfilePic($currentPicObj[0]); 
	}
	$respObj = ApiResponseHandler::getInstance();
	$respObj->setHttpArray(ResponseHandlerConfig::$SUCCESS);
	$respObj->setResponseBody(array("uploaded"=>$uploaded,"label"=>"success upload","PICTUREID"=>$pictureid));
	$respObj->generateResponse();

	die;
  }
}
