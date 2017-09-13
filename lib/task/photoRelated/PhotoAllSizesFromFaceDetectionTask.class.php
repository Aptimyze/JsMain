<?php

/*
 * Author: Reshu Rajput
 * Created: Sep 18, 2013
 * This cron is used to get images which are from table PICTURE_FROM_SCREEN_NEW and execute face detection saving them in 
 * web/uploads/NonScreenedImages/ 
*/

class PhotoAllSizesFromFaceDetectionTask extends sfBaseTask
{
	private $limit = 100;
 	protected function configure()
  	{
		$this->addArguments(array(
        		new sfCommandArgument('totalScripts', sfCommandArgument::REQUIRED, 'My argument'),
        		new sfCommandArgument('currentScript', sfCommandArgument::REQUIRED, 'My argument'),
        ));

		$this->addOptions(array(
		new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
	     ));

	    $this->namespace        = 'cron';
	    $this->name             = 'PhotoAllSizesFromFaceDetection';
	    $this->briefDescription = 'detect face from main pic of a profile and get images of all required sizes';
	    $this->detailedDescription = <<<EOF
	This cron runs every half an hour to get non screened images and detect face for them and create new required size image which will be verified during screening .
	Call it with:

	  [php symfony cron:PhotoAllSizesFromFaceDetection totalScripts currentScript] 
EOF;
  	}

        /**
         * Image getting corrupted due to image jeevansathi.com in url
         * This cron replaces jeevansathi.com with JS
         * @access private
         */
        private function UpdateForCorruptPrevent()
        {
                //Prevent Image corruption
                $pictureForScreenNewObj = new PICTURE_FOR_SCREEN_NEW();
                $pictureForScreenNewObj->UpdatePresentImageCorrupt();
        }

	protected function execute($arguments = array(), $options = array())
  	{
		ini_set('memory_limit','1024M');
		 if(!sfContext::hasInstance())
                        sfContext::createInstance($this->configuration);
		$totalScripts = $arguments["totalScripts"]; // total no of scripts
	        $currentScript = $arguments["currentScript"]; // current script number
	        
	    if(CommonUtility::hideFeaturesForUptime())
			successfullDie();


/*
		if($currentScript!=11)		
			successfullDie("");
*/
		
		PictureFunctions::setHeaders();
		$this->UpdateForCorruptPrevent();
		$pictureObj = new NonScreenedPicture();
		$faceDetectionObj = new PhotoFaceDetection();
		$picturesForLogic = $pictureObj->getPicturesForFaceDetection($totalScripts,$currentScript,$this->limit);
		if(is_array($picturesForLogic))
		{
			foreach($picturesForLogic as $index=>$value)
			{
				try{
					$pid= $value["PICTUREID"];
//echo $pid."---";
					if(strstr($value["MobileAppPicUrl"],"mainPic"))
						$value["MobileAppPicUrl"] = '';

					if(strstr($value["OriginalPicUrl"],"mediacdn.jeevansathi.com"))
                                                $value["OriginalPicUrl"] = str_replace("mediacdn.jeevansathi.com/","jeevansathi.s3.amazonaws.com/",$value["OriginalPicUrl"]);
					$profileObj = Operator::getInstance("", $value["PROFILEID"]);
					$profileObj->getDetail("","","HAVEPHOTO");
					$value['PROFILE_TYPE'] = $this->getProfileType($profileObj->getHAVEPHOTO());
					$pictureServiceObj =new PictureService($profileObj);
					unset($profilesUpdate);
					$copy= false;
					//Getting absolute path if getting form live not required
					if(strpos($value["OriginalPicUrl"],JsConstants::$docRoot)==FALSE)
                                                $copy= true;
					$faceDetected= false;
					$imageT = PictureFunctions::getImageFormatType($value["OriginalPicUrl"]);	
					if($copy)
					{
						
						$origPic =$pictureObj->getSaveUrlPicture(ProfilePicturesTypeEnum::$PICTURE_UPLOAD_DIR["OriginalPicUrl"],$pid,$value["PROFILEID"],$imageT,'nonScreened');
						copy($value["OriginalPicUrl"],$origPic);
					}
					$outputGot = $faceDetectionObj->getPictureCoordinates($origPic);
					$coordRegex ="/^(\d)+x(\d)+\+(\d)+\+(\d)+/";
					if(preg_match($coordRegex,$outputGot))
					{
						$command = JsConstants::$php5path ." -q ". JsConstants::$cronDocRoot."/symfony cron:ApiFaceDetectionTask ".$value["PROFILEID"]." ".$pid." ".$origPic;
							exec($command);
						foreach(ProfilePicturesTypeEnum::$PICTURE_SIZES as $k =>$v)
						{
							if($value[$k]=="" && $k!="MainPicUrl")
							{
								$picUrl = $pictureObj->getSaveUrlPicture(ProfilePicturesTypeEnum::$PICTURE_UPLOAD_DIR[$k],$pid,$value["PROFILEID"],$imageT,'nonScreened');
							
								$output=$faceDetectionObj->cropPicture($origPic,$outputGot,$k,$picUrl,$imageT);
								if($output)
									$profilesUpdate[$pid][$k]= $pictureObj->getDisplayPicUrlPicture(ProfilePicturesTypeEnum::$PICTURE_UPLOAD_DIR[$k],$pid,$value["PROFILEID"],$imageT,'nonScreened');
									
							}
							else 
								$profilesUpdate[$pid][$k]= $value[$k];
						}

					}
					$profilesUpdate[$pid]['MainPicUrl']=$value['MainPicUrl'];
										
					if(is_array($profilesUpdate))
						$pictureServiceObj->setPicProgressBit("FACE",$profilesUpdate);
					unset($profileObj);
					unset($pictureServiceObj);
					//Track This in Master Log
	                                $this->trackPhotoScreenMasterLog($pid,$value);
				}
				catch(exception $e)
				{
					echo $e;
				}
			}
		}
  	}

	/**
         * Track Photo Screen Master Log
         * Function for updating photo screen master log
         * @access private
         * @params iPicId                       : Picture Id
         * @params arrData                      : Array of data(or info) for give picture id
         */
        private function trackPhotoScreenMasterLog($iPicId,$arrData)
        {
                $arrPicData = array($iPicId=>array(
                                'STATUS'=>PictureStaticVariablesEnum::PIC_STATUS_RESIZE_CRON_COMPLETED,//1 As Resize Cron is already executed on this pictire object
                                'UPDATED_TIMESTAMP'=>$arrData['UPDATED_TIMESTAMP'],
                                'PROFILEID'=>$arrData['PROFILEID'])
                                );
                $objMasterTrack = new JsPhotoScreen_Track_MasterLogs($arrData['PROFILEID'],PictureStaticVariablesEnum::PHOTO_SCREEN_OPERATION_FACEDETECTION_CRON,$arrData['PROFILE_TYPE'],$arrData['UPDATED_TIMESTAMP']);
                $objMasterTrack->trackThis();
        }
        
      /**
	 * getProfileType
	 * Function for deciding the profile type as per the have photo value
	 * @access private
	 * @params cHavePhoto		: Char Value represting have photo status 
	 */	
	private function getProfileType($cHavePhoto)
	{
		$cPhotoType = '';
		if($cHavePhoto == PictureStaticVariablesEnum::$HAVE_PHOTO_STATUS['YES'])
		{
			$cPhotoType = 'E';//Photo Type = EDIT
		}
		else // In case of Underscreening, or blank value or No value
		{
			$cPhotoType = 'N';//Photo Type = NEW
		}
		return $cPhotoType;
	}
}
