<?php
//Including the entire symfony production files and removing the individual included files. Checked memory consumption. 4.5MB in case of complete include and 3.9MB when limited files were included.
	$symfonyFilePath = JsConstants::$cronDocRoot;

	include_once($symfonyFilePath.'/lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php');
	include_once($symfonyFilePath.'/config/ProjectConfiguration.class.php');
	if(JsConstants::$whichMachine=='matchAlert' && is_array($_SERVER["argv"]) && $_SERVER["argv"][1]=='cron:FireMail')
		$app = "masscomm";
	else
	{
		if(strstr($_SERVER["PHP_SELF"],"operations.php") || strstr($_SERVER["PHP_SELF"],"operations_dev.php"))
			$app = "operations";
		else
			$app = "jeevansathi";
	}
	if(JsConstants::$whichMachine=="local")
        	$configuration =ProjectConfiguration::getApplicationConfiguration($app, 'dev',true);
	elseif(JsConstants::$whichMachine=="test")
        	$configuration = ProjectConfiguration::getApplicationConfiguration($app, 'test', false);
	else
        	$configuration =ProjectConfiguration::getApplicationConfiguration($app, 'prod', false);

	if(!sfContext::hasInstance())
	sfContext::createInstance($configuration);

	if(JsConstants::$whichMachine!='matchAlert')
	{
		//THESE VARIABLES ARE USED IN SOME FILES SO KEEPING THEM
		//getting config path
		$sfProjectConfiguration=new sfProjectConfiguration;
		$symfonyConfigPath=$sfProjectConfiguration->getRootDir()."/config";
		//getting config path

		//start: including app.yml
		$appDotYml = sfYaml::load ("$symfonyFilePath/apps/operations/config/app.yml");
		$jeevansathiAppDotYml = sfYaml::load ("$symfonyFilePath/apps/jeevansathi/config/app.yml");
		//end: including app.yml
		//KEEPING VARIBALES ENDS
	}

class SymfonyPictureFunctions
{
	public function createThumbnails($src_pic_name,$dest_pic_name,$format)
	{
		$pictureFunctionObj = new PictureFunctions;
		$pictureFunctionObj->maintain_ratio_canvas($src_pic_name,$dest_pic_name,0,0,0,0,96,96,$format);
               	$pictureFunctionObj->generate_image_for_canvas($dest_pic_name,96,96,$format);
	}

	public function getSaveUrl($picType,$picId,$profileId,$type,$objectType)
	{
		$profileObj=LoggedInProfile::getInstance('newjs_master',$profileId);
                $pictureServiceObj = new PictureService($profileObj);
		if ($objectType == "N")
			$picObj = new NonScreenedPicture;
		else
			$picObj = new ScreenedPicture;

                $output = $picObj->getSaveUrl($picType,$picId,$profileId,$type);
                return $output;
	}

	public function getDisplayPicUrl($picType,$picId,$profileId,$type,$objectType)
	{
		$profileObj=LoggedInProfile::getInstance('newjs_master',$profileId);
                $pictureServiceObj = new PictureService($profileObj);
		if ($objectType == "N")
			$picObj = new NonScreenedPicture;
		else
			$picObj = new ScreenedPicture;

                $output = $picObj->getDisplayPicUrl($picType,$picId,$profileId,$type);
                return $output;
	}

	public function getOrderingForInsertion($profileId)
	{
		$profileObj=LoggedInProfile::getInstance('newjs_master',$profileId);
                $pictureServiceObj = new PictureService($profileObj);
                $output = $pictureServiceObj->getOrderingForInsertion();
                return $output;
	}

	public function getPictureAutoIncrementId($profileId)
	{
		$profileObj=LoggedInProfile::getInstance('newjs_master',$profileId);
                $pictureServiceObj = new PictureService($profileObj);
                $output = $pictureServiceObj->getPictureAutoIncrementId();
                return $output;
	}

	public function getUserUploadedPictureCount($profileId)
	{
		$profileObj=LoggedInProfile::getInstance('newjs_master',$profileId);
		$pictureServiceObj = new PictureService($profileObj);
		$output = $pictureServiceObj->getUserUploadedPictureCount();
		return $output;
	}

	public function getMaxOrdering($profileid)
	{
		$pictureNew = new PICTURE_NEW();
		return $pictureNew->getMaxOrdering($profileid);
	}

	public function haveScreenedMainPhoto($profileid)
	{
		$pictureNew = new PICTURE_NEW();
		return $pictureNew->hasScreenedMainPhoto($profileid);
	}
	
	public function checkMorePhotos($profileid,$nonSymfony="",$db="")
	{
		if($nonSymfony)
		{
			$sql="SELECT COUNT(*) FROM newjs.PICTURE_NEW WHERE PROFILEID='$profileid'";
			$res=mysql_query($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			$row=mysql_fetch_row($res);
			$count=$row[0];
		}
		else
		{
			$pictureNew = new PICTURE_NEW();
			$count=$pictureNew->getMaxOrdering($profileid);
		}
		if($count>0)
			return 1;
		else
			return 0;
	}

	public function checkMorePhotosMultipleIds($profileids,$db="",$getCount=false)
	{
		$rep_values =array(" ", "-", "'","\"");
                $profileids =str_replace($rep_values,'',$profileids);
                if(is_array($profileids))
                        $profileids_str = implode(",",$profileids);
                else
                        $profileids_str = $profileids;
		
		$prof_alb = array();
		$albPhotos = array();
		if ($profileids_str)
		{
			$sql = "SELECT COUNT(*) as CNT,PROFILEID FROM newjs.PICTURE_NEW WHERE PROFILEID IN (".$profileids_str.") GROUP BY PROFILEID";
			if ($db)
                                $result =mysql_query($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
                        else
                                $result =mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			
			 while($row= mysql_fetch_assoc($result))
                        {
                           	$profileid =$row["PROFILEID"];
				$albPhotos[$profileid] = $row["CNT"];
				if ($row["CNT"]>0){
					if($getCount) $prof_alb[$profileid] = $row["CNT"];
					else $prof_alb[$profileid]=1;
				}
				else
					$prof_alb[$profileid]=0;
                        }
		}
		return $prof_alb;
	}

	public function getAlbum($profileId,$showAll='')//returns the urls of first 3 screened photos of a user
	{
		if($showAll)
		{
			//$profile=LoggedInProfile::getInstance('newjs_master',$profileId);
			global $symfonyFilePath;
			include_once("$symfonyFilePath/apps/operations/lib/Operator.class.php");
                        $profile = Operator::getInstance('newjs_master',$profileId);
		}
		else
			$profile = Profile::getInstance();
		$profile->getDetail($profileId,"","PROFILEID,HAVEPHOTO,GENDER,PHOTO_DISPLAY","RAW");
		$pictureServiceObj=new PictureService($profile);
		$album=$pictureServiceObj->getAlbum();
		if($album)
		{
			$i=0;
			foreach((array)$album as $photo)
			{
				if($i>2)
					break;
				$urls[] = $photo->getMainPicUrl();
				if($photo->getOrdering() == 0)
					$urls['profile']=$photo->getProfilePicUrl();
				$i++;
			}
		}
		if($urls)
			return $urls;
		else
			return NULL;
	}

        function getAlbum_nonSymfony($profileids,$db)
        {
                $sql = "SELECT PROFILEID,ORDERING,KEYWORD,UPDATED_TIMESTAMP,MainPicUrl,ProfilePicUrl, IF(ORDERING=0,100, IF (KEYWORD LIKE  '%1%', 80, 0)) AS LOGIC_SORT FROM newjs.PICTURE_NEW WHERE PROFILEID IN ($profileids) ORDER BY PROFILEID,LOGIC_SORT DESC ,UPDATED_TIMESTAMP DESC";
		if ($db)
			$result =mysql_query($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		else
			$result =mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
		while($row= mysql_fetch_assoc($result))
                {
                        $pid=$row['PROFILEID'];
                        if(!$count[$pid])
                        {
                                $count[$pid] = 1;
                                $album[$pid]['PROFILEPHOTO']= PictureFunctions::getCloudOrApplicationCompleteUrl($row['ProfilePicUrl']);
                        }
                        elseif($count[$row['PROFILEID']] < 3)
                        {       
                                $index = "ALBUMPHOTO".$count[$pid];
                                $album[$pid][$index] = PictureFunctions::getCloudOrApplicationCompleteUrl($row['MainPicUrl']);
                                $count[$pid]++;
                        }
                }
                return $album;
        }


	public static function getPhotoUrls_nonSymfony($profileids,$paramters,$db="")
	{
		$rep_values =array(" ", "-", "'","\"");
                $profileids =str_replace($rep_values,'',$profileids);
		if(is_array($profileids))
                        $profileids_str = implode(",",$profileids);
                else
                        $profileids_str = $profileids; 
		$photoUrls_nonSymfony='';
		if($profileids_str)
		{
			$sql ="select PROFILEID,$paramters from newjs.PICTURE_NEW where PROFILEID IN ($profileids_str) AND ORDERING=0 ORDER BY FIELD(PROFILEID,".$profileids_str.")";
			if ($db)
        	        	$result =mysql_query($sql,$db) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			else
                		$result =mysql_query_decide($sql) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes",$sql,"ShowErrTemplate");
			$completeUrlArrayAllowed=array("MainPicUrl","ProfilePicUrl","ThumbailUrl","Thumbail96Url","SearchPicUrl","ProfilePic120Url");
		        while($row= mysql_fetch_assoc($result))
        		{
				foreach($row as $k=>$v)
				{
                			$profileid =$row["PROFILEID"];
				 	if(in_array($k,$completeUrlArrayAllowed))
 					     $photoUrls_nonSymfony[$profileid][$k]= PictureFunctions::getCloudOrApplicationCompleteUrl($v);
 					else
			        	        $photoUrls_nonSymfony[$profileid][$k]=$v;
				}
	        	}
		}
		return $photoUrls_nonSymfony;
	}

	public function getProfilePicObjs($profileId,$contactType="")
	{
		$rep_values =array(" ", "-", "'","\"");
        	$profileId =str_replace($rep_values,'',$profileId);
        	if(is_array($profileId))
                	$profileid_arr =$profileId;
		else
			$profileid_arr =explode(",",$profileId);

		foreach($profileid_arr as $v)
		{
			$profile = Profile::getInstance();
                	$profile->getDetail($v,"","PROFILEID,HAVEPHOTO,GENDER,PHOTO_DISPLAY","RAW");
			$pictureServiceObj=new PictureService($profile);
                	$profilePicObjs[$v] = $pictureServiceObj->getProfilePic($contactType);
			unset($profile);
		}
		return $profilePicObjs;
	}	
}
//$a = SymfonyPictureFunctions::getAlbum(144111);
class SymfonyPhoneFunctions
{
	public function getProbableDuplicateOfProfileByReason($profileid,$reason)
	{
	        $duplicateHandler=new DuplicateHandler();
        	$profiles=$duplicateHandler->getProbableDuplicateOfProfileByReason($profileid,$reason);
		return $profiles;
	}
	public function getProfileDuplicates($profileid)
	{
	        $duplicateHandler=new DuplicateHandler();
                $profiles=$duplicateHandler->getProfileDuplicates($profileid);
		return $profiles;
	}
}
class SymfonyFTOFunctions
{
	public static function getFTOStateArray($profileid)
	{
		$profileArray = FTOStateHandler::getFTOCurrentState($profileid);//$ftoCurrentStateObj->getFTOCurrentStateRow($profileid);
		if($profileArray['FTO_ENTRY_DATE']||$profileArray['FTO_EXPIRY_DATE'])
		{
				$profileArray['FTO_REMAINING_DAYS'] = FTOStateHandler::calculateRemainingDays($profileArray['FTO_EXPIRY_DATE']);
			        $orgTZ = date_default_timezone_get();
				date_default_timezone_set("Asia/Calcutta");
				$entryDate =JSstrToTime($profileArray['FTO_ENTRY_DATE']);
				$profileArray['FTO_ENTRY_DATE'] = date("Y-m-d H:i:s", $entryDate);
				$expiryDate =JSstrToTime($profileArray['FTO_EXPIRY_DATE']);
				$profileArray['FTO_EXPIRY_DATE'] = date("Y-m-d H:i:s", $expiryDate);
				date_default_timezone_set($orgTZ);
		}
		if(!$profileArray['STATE'])
		{
			$profileArray = SymfonyFTOFunctions::setNeverExposed();
		}
		return $profileArray;
	}
	public static function updateFTOState($profileid,$action)
	{
		try
		{
		$currentPath= getcwd();
		chdir($_SERVER['DOCUMENT_ROOT']."/../");
		$command = PHP_BINDIR."/php symfony cron:cronFTOStateUpdate ".$profileid." ".$action;
		exec($command);
		chdir($currentPath);
		}
		catch(Exception $e)
		{
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
			$cc='esha.jain@jeevansathi.com';
			$to='tanu.gupta@jeevansathi.com';
			$msg='';
			$subject="error: updateFTOState in SymfonyPictureFunction.class.php";
			$msg='error with profileid:'.$profileid.' and action:'.$action.' <br/><br/>Warm Regards';
			send_email($to,$msg,$subject,"",$cc);

		}
	}
	public static function getIS_FTO_LIVE()
	{
		return FTOLiveFlags::IS_FTO_LIVE;
	}
	public static function getProfilesInState($state,$subState='')
	{
		return FTOStateHandler::getProfilesInState($state,$subState);
	}

  private static function getOfferPageView($profileid) {
    $ftoCurrentStateObj = new FTO_FTO_CURRENT_STATE;
    $currentStateRow = $ftoCurrentStateObj->getFTOCurrentStateRow($profileid);
    $subState = $currentStateRow['STATE_ID'];
    if (($subState !== FTOSubStateTypes::NEVER_EXPOSED) || ($subState !== FTOSubStateTypes::DUPLICATE)) {
      $storeClass = new FTO_FTO_OFFER_PAGE_VIEWS();
      try {
        list($state, $retVal) = $storeClass->getOfferPageView($profileid, $subState);
      }
      catch (Exception $e) {
        return -1;
      }
      unset($storeClass);
      return $retVal;
    }
  }

  private static function insertOfferPageView($profileid) {
    $ftoCurrentStateObj = new FTO_FTO_CURRENT_STATE;
    $currentStateRow = $ftoCurrentStateObj->getFTOCurrentStateRow($profileid);
    $subState = $currentStateRow['STATE_ID'];
    if (($subState !== FTOSubStateTypes::NEVER_EXPOSED) || ($subState !== FTOSubStateTypes::DUPLICATE)) {
      $storeClass = new FTO_FTO_OFFER_PAGE_VIEWS();
      try {
        $retVal = $storeClass->insertOfferPageView($profileid, $subState);
      }
      catch (Exception $e) { //some Error
        return -1;
      }
      unset($storeClass);
      return $retVal;
    }
  }

  private static function updateOfferPageView($profileid, $stateId) {
    $ftoCurrentStateObj = new FTO_FTO_CURRENT_STATE;
    $currentStateRow = $ftoCurrentStateObj->getFTOCurrentStateRow($profileid);
    $subState = $currentStateRow['STATE_ID'];
    if (($subState !== FTOSubStateTypes::NEVER_EXPOSED) || ($subState !== FTOSubStateTypes::DUPLICATE)) {
      $storeClass = new FTO_FTO_OFFER_PAGE_VIEWS();
      try {
        $storeClass->updateOfferPageView($profileid, $subState);
      }
      catch (Exception $e) {
        return -1;
      }
      unset($storeClass);
    }
  }

  public static function showOfferPage($profileid) {
    // Get times viewed.
    // If times viewed = 0, 
    //  insert entry with fto state
    //  return true;
    // else
    //  return false;

    $ftoCurrentStateObj = new FTO_FTO_CURRENT_STATE;
    $currentStateRow = $ftoCurrentStateObj->getFTOCurrentStateRow($profileid);
    $subState = $currentStateRow['STATE_ID'];
   
    list($stateId, $retVal) = self::getOfferPageView($profileid);
    if (($retVal === 0) || ($subState !== $stateId)) {
      if (0 !== self::insertOfferPageView($profileid, $stateId)) {
        return true;
      }
      else {
        return false;
      }
    }
    else {
      return false;
    }
  }

	public static function setNeverExposed()
	{
			$profileArray['STATE']=FTOStateTypes::NEVER_EXPOSED;
			$profileArray['SUBSTATE']=FTOSubStateTypes::NEVER_EXPOSED;
			return $profileArray;
	}
	public static function editOnFtoContactConfirmation($mypid)
	{
		$editObj= new EditOnFtoContactConfirmation($mypid);
		$output["HREF"]=$editObj->getLinkToShowHref();
                $output["TEXT"]=$editObj->getLinkToShowText();
		return $output;
	}
}
class SymfonyEmailFunctions
{
  public static function sendEmail($mailerGroup, $profileid) {

    switch ($mailerGroup) {

      case 16:
        $emailSender = new EmailSender(MailerGroup::SCREENING);
        $emailSender->setProfileId($profileid);
        $retVal = $emailSender->send();
        break;

      default:
        break;
    }
  }
}
?>
