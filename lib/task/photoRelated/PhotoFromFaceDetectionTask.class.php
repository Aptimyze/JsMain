<?php

/*
 * Author: Reshu Rajput
 * Created: Dec 31, 2013
 * This cron is used to get images which are not screened for App image from table and execute face detection saving them in 
 * web/uploads/NonScreenedImages/mobileAppPic 
*/

class PhotoFromFaceDetectionTask extends sfBaseTask
{
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
	    $this->name             = 'PhotoFromFaceDetection';
	    $this->briefDescription = 'detect face from main pic of a profile and get app image';
	    $this->detailedDescription = <<<EOF
	This cron runs every half an hour to get non screened images and detect face for them and create new app required size image which will be verified during screening .
	Call it with:

	  [php symfony cron:PhotoFromFaceDetection totalScripts currentScript] 
EOF;
  	}

	protected function execute($arguments = array(), $options = array())
  	{
		ini_set('memory_limit','512M');
		$totalScripts = $arguments["totalScripts"]; // total no of scripts
	        $currentScript = $arguments["currentScript"]; // current script number

		if(CommonUtility::hideFeaturesForUptime())
		        successfullDie();

                /* locking */
                $LockingService = new LockingService;
                $file = "faceDetection_$totalScripts_$currentScript.txt";
                $lock = $LockingService->getFileLock($file,1);
                if(!$lock)
			successfullDie();
                /* locking */

		$pictureObj = new NonScreenedPicture();
		$faceDetectionObj = new PhotoFaceDetection();
		$limit = 100;
		$picturesForLogic = $pictureObj->getDataForAlgo($totalScripts,$currentScript,$limit);
		if(is_array($picturesForLogic))
		{
			foreach($picturesForLogic as $pid=>$value)
			{
				try{
					$copy= false;
					if(strpos($value["MainPicUrl"],JsConstants::$applicationPhotoUrl)!=FALSE)
					{
						$value["MainPicUrl"]=str_replace(JsConstants::$applicationPhotoUrl,JsConstants::$docRoot,$value["MainPicUrl"]);
						$mainPic = $value["MainPicUrl"];
					}
					else
						$copy= true;
					PictureFunctions::setHeaders();
					$mainImageInfo = getimagesize($value["MainPicUrl"]);
					$faceDetected= false;
					switch($imageInfo["mime"])
					{
						case 'image/jpg':
							$imageT ="jpeg";
							break;
						case 'image/jpeg':
							$imageT ="jpeg";
							break;
						case 'image/gif':
							$imageT = "gif";
                                			break;
					}
					if($copy)
					{
						$mainPic =$pictureObj->getSaveUrlPicture("mainPic",$pid,$value["PROFILEID"],$imageT,'nonScreened');
						copy($value["MainPicUrl"],$mainPic);
					}
					$picUrl = $pictureObj->getSaveUrlPicture("mobileAppPic",$pid,$value["PROFILEID"],$imageT,'nonScreened');
					if($imageT=="gif")
						$image = imagecreatefromgif($mainPic);
					else
						$image = imagecreatefromjpeg($mainPic);
						
					
					$outputGot = $faceDetectionObj->getPictureCoordinates($mainPic);
					$coordRegex ="/^(\d)+x(\d)+\+(\d)+\+(\d)+/";
					unset($profilesUpdate);
					if(preg_match($coordRegex,$outputGot))
					{
						$coordSplit = explode("+",$outputGot);
				                $coordSplit2 = explode("x",$coordSplit[0]);
						$face_w = $coordSplit2[0];
				                $face_h = $coordSplit2[1];
						$statObj = new MIS_FACEDETECTION_SIZE();
                				$statObj->saveImageSize($pid,$face_w,$face_h);
						$output=$faceDetectionObj->createAppImage($mainPic,$outputGot);
						// Resample
                				$image_p = imagecreatetruecolor($output["APP"]["width"],$output["APP"]["height"]);
                				$imageCreated =imagecopyresampled($image_p, $image, 0,0,$output["MAIN"]["x"],$output["MAIN"]["y"],$output["APP"]["width"],$output["APP"]["height"], $output["MAIN"]["width"],$output["MAIN"]["height"]);
						
						if($imageT=="gif")
                	                                imagegif($image_p, $picUrl);
        	                                else
							imagejpeg($image_p, $picUrl,90);
						if($imageCreated)
						{
							$profilesUpdate[$pid]= $pictureObj->getDisplayPicUrlPicture("mobileAppPic",$pid,$value["PROFILEID"],$imageT,'nonScreened');
							$faceDetected = true;
						}
						else
							$profilesUpdate[$pid]="0";
					}
					else
						$profilesUpdate[$pid]="0";
					if(is_array($profilesUpdate))
						$pictureObj->updateAppTable($profilesUpdate);
					$faceDetectionObj->trackPhotoFaceDetection(true,$faceDetected);
				}
				catch(exception $e)
				{
					echo $e;
				}
			}
		}
  	}
}
