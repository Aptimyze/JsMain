<?php
class UploadPhoto extends PictureService
{
 /*
 * @package jeevansathi
 * @subpackage deletePhoto
 * @author Esha Jain
 * @created 01st March 2016
 */
 /**
 * Class For deleting photo 
 */

	public static $allowedImageType = array(IMAGETYPE_GIF,IMAGETYPE_JPEG,IMAGETYPE_PNG); 

	public static $channelUpload = array("computer_noFlash","appPicsCamera","iOSPicsCamera","iOSPicsGallery","mobPicsGallery","appPicsGallery","appPicsUpload","mobileUpload");

        public function __construct($profileObj,$uploadSource,$source)
	{
		$this->profileid = $profileObj->getPROFILEID();

		$this->uploadSource = $uploadSource;

		parent::__construct($profileObj,$source);
		
	}

	public function uploadPhoto($fileData='',$importSite='')
	{
		$files = $_FILES;

                $nonScreenedPicObj = new NonScreenedPicture;

                $havePhotoBeforeUpload = $this->profileObj->getHAVEPHOTO();

		$uploadType = $this->checkSourceValue();
		
		$return  = call_user_func_array(array($this, $uploadType), array($files,$havePhotoBeforeUpload, $fileData,$importSite,$nonScreenedPicObj));

		return $return;
		
	}

	public function checkSourceValue()
	{

		if(in_array($this->uploadSource,self::$channelUpload))
			return "channelUpload";

		elseif($this->uploadSource == "userCroppedProfilePic")
			return "userCroppedProfilePic";

		else
			return "picLinkUpload";
	}


	public function channelUpload($files,$havePhotoBeforeUpload='',$fileData='',$importSite='',$nonScreenedPicObj)
	{
		if($this->uploadSource=="mobileUpload")
			$files = $this->oldMobileDataConversion($files);

		$uploadCounts = array("ErrorCounter"=>0,"SizeErrorCounter"=>0,"FormatErrorCounter"=>0,"ActualFiles"=>0,"MaxCountError"=>false);
		$successfullFiles=0;

		if(!$files)
			return false;
		foreach($files as $k=>$v)
		{
			if(!$v["name"])
				continue;

			$picsInDb = $this->getUserUploadedPictureCount();
			$photosInLimit = self::numberOfPhotosInLimit($picsInDb);

			if(!$photosInLimit)
			{
				$uploadCounts['MaxCountError']=true;
				return $uploadCounts;
			}
			$uploadCounts['ActualFiles']++;

			$canUpload = $this->canUpload($v,$nonScreenedPicObj);
			
			if($canUpload['ErrorCounter']==1)
			{
				$uploadCounts  = $this->updateErrorCounts($canUpload,$uploadCounts);
			}
			else
			{
				if($this->uploadSource=="mobileUpload")
					$this->mobileUploadOrientation($v);

				$picInfo = $this->copyPic($v,$nonScreenedPicObj);
				
				if($picInfo['ErrorCounter']==1)
				{
					$uploadCounts  = $this->updateErrorCounts($picInfo,$uploadCounts);
				}
				else
				{
					chmod($picInfo['SRC'], 0777);
					
					$this->generateImages("thumbnail96",$picInfo['SRC'],$picInfo['DEST'],$v['type']);
					
					$pictureArray = $this->generatePictureArray($nonScreenedPicObj,$picInfo);
					
					$nonScreenedPicObj->setDetail($pictureArray);

					$insertStatus=$this->addPhotos($nonScreenedPicObj);

					if($insertStatus)
					{
						$successfullFiles++;
						$this->saveImageDeatils($picInfo['PIC_ID'],$picInfo['SRC'],$this->profileid);
						$this->track1stUnscreenedPhoto($havePhotoBeforeUpload,$this->uploadSource);
						$uploadCounts['PIC_ID']=$picInfo['PIC_ID'];
						//SUCCESS WITH UPLOAD AND DATABASE ENTRY
					}
					else
						$uploadCounts['ErrorCounter']++;
				}
			}
			
		}

		if($successfullFiles){
			$this->importUploadTracking($this->uploadSource,$successfullFiles);
      $this->updateProfileCompletionScore($this->profileid);
    }
		return $uploadCounts;
	}
	public function mobileUploadOrientation($image)
	{
		$imageHeaderInfo = exif_read_data($image["tmp_name"]);

		if(array_key_exists($imageHeaderInfo["Orientation"],PictureStaticVariablesEnum::$orientationToAngle))
		{
			$angle = PictureStaticVariablesEnum::$orientationToAngle[$imageHeaderInfo["Orientation"]];

			PictureFunctions::rotateImage($image["tmp_name"],$angle,$image["type"]);
		}
		return true;
	}
	public function oldMobileDataConversion($files)
	{
		foreach($files['photoInput'] as $k=>$v)
		{
			foreach($v as $kk=>$vv)
			{
				$convertedFiles[$kk][$k]=$vv;
			}
		}
		return $convertedFiles;
	}
        public function userCroppedProfilePic($files,$havePhotoBeforeUpload='',$fileData='',$importSite='',$nonScreenedPicObj)
        {
		$http_msg=print_r($_SERVER,true);
		mail("eshajain88@gmail.com","data","prfilid:$this->profileObj->getPROFILEID(),source:$source,\n\ndata:$http_msg");
                $errorCounts = array("ErrorCounter"=>0,"SizeErrorCounter"=>0,"FormatErrorCounter"=>0,"ActualFiles"=>0);

		$image = $files["croppedPic"];

                $successfullFiles=0;

                if(!$files)
                        return;

		$profilePicObj = $this->getNonScreenedPhotos('profilePic');

		if (!$profilePicObj) 
		{
			$errorCounts['ErrorCounter']++;
		}
		elseif($image["name"])
		{
			$picId = $profilePicObj->getPICTUREID();

                        $canUpload = $this->canUpload($v,$nonScreenedPicObj);

                        if($canUpload['ErrorCounter']==1)
                                $errorCounts  = $this->updateErrorCounts($canUpload,$errorCounts);

                        else
                        {
                                $picInfo = $this->copyCroppedPic($v,$nonScreenedPicObj,$picId);
                                if($picInfo['ErrorCounter']==1)
                                        $errorCounts  = $this->updateErrorCounts($canUpload,$errorCounts);
                                else
                                {
                                        chmod($picInfo['DEST'], 0777);
					$picArray["ProfilePicUrl"] = $nonScreenedPicObj->getDisplayPicUrl("profilePic", $picId, $this->profileid, $picInfo['ACTUAL_TYPE']);
					$nonScreenedPicObj->edit($picArray,$picId,$this->profileid);
					$croppedPic++;
				}
			}
			unset($picArray);
		}
		return ($errCounts['ErrorCounter']."**-**".$croppedPic."**-**".$picId);
        }
	public function picLinkUpload($files='',$havePhotoBeforeUpload='',$fileData='',$importSite='',$nonScreenedPicObj)
	{
		$actualType = $this->getFacebookActualType($fileData);
		if(in_array($actualType,PictureStaticVariablesEnum::$photoFormats))
		{
			$noOfPhotos = $this->getUserUploadedPictureCount();
			if($noOfPhotos<PictureStaticVariablesEnum::maxNumberOfPhotos)
			{
				$picInfo = $this->getPicCopyData($nonScreenedPicObj,$actualType);
				$content=$this->getPicContent($fileData);
				$fileCoppied = $this->copypicLinkImage($picInfo['SRC'],$content);
				$this->generateImages("thumbnail96",$picInfo['SRC'],$picInfo['DEST'],$actualType);
			}
		}
		if($fileCoppied)
		{
			$lockingObj = $this->getLockObj();
			$picArray = $this->generatePictureArray($nonScreenedPicObj,$picInfo);
			if($picArray["ORDERING"]<PictureStaticVariablesEnum::maxNumberOfPhotos)
			{
				$nonScreenedPicObj->setDetail($picArray);
				$this->addPhotos($nonScreenedPicObj);
				$this->saveImageDeatils($picInfo['PIC_ID'],$picInfo['SRC'],$this->profileid);
				$this->track1stUnscreenedPhoto($havePhotoBeforeUpload,$this->uploadSource);
				$this->importUploadTracking($importSite,$successfullFiles = 1);
        $this->updateProfileCompletionScore($this->profileid);
				$return = array("PIC_ID"=>$picInfo['PIC_ID']);
			}
			$this->releaseLockObj($lockingObj);
		}
		return $return;
	}
	public function getPicContent($picLink)
	{
		return CommonUtility::sendCurlGetRequest($picLink);
	}
	public function getFacebookActualType($picLink)
	{
                $type=strrpos($picLink,'.');
                $type=substr($picLink, $type, strlen($picLink));
                $typeStr=strtolower($type);
                $typeArr = explode("?",$typeStr);
                $type = $typeArr[0];
                $actualType = str_replace('.','',$type);
		return $actualType;
	}
	public function getLockObj()
	{
		$lockingObj = new LockingService;
		$lockingObj->getFileLock("picture_".$this->profileid);
		return $lockingObj;
	}
	public function releaseLockObj($lockingObj)
	{
		$lockingObj->releaseFileLock();
	}
	public function copypicLinkImage($src,$content)
	{
		return file_put_contents($src,$content);
	}
	public function importUploadTracking($uploadSource,$successfullFiles='')
	{
		$trackingObj = new importUploadTracking();
		$trackingObj->photoSaveEntry($this->profileid,$uploadSource,$successfullFiles);
		return true;
	}

	public function generatePictureArray($nonScreenedPicObj,$picInfo)
	{
		$picArray["PICTUREID"] = $picInfo['PIC_ID'];
		$picArray["PROFILEID"] = $this->profileid;
		$picArray["PICTURETYPE"] = "N";
		$picArray["ORDERING"] = $this->getOrderingForInsertion();
		$picArray["MainPicUrl"] = $nonScreenedPicObj->getDisplayPicUrl("mainPic",$picInfo['PIC_ID'],$this->profileid,$picInfo['ACTUAL_TYPE']);
		$picArray["ProfilePicUrl"] = null;
		$picArray["ThumbailUrl"] = null;
		$picArray["Thumbail96Url"] = $nonScreenedPicObj->getDisplayPicUrl("thumbnail96",$picInfo['PIC_ID'],$this->profileid,$picInfo['ACTUAL_TYPE']);
		$picArray["PICFORMAT"] = $picInfo['ACTUAL_TYPE'];
		return $picArray;
	}

	public function getActualType($imageType)
	{
		if ($imageType == "image/jpeg" || $imageType == "image/pjpeg")
			$actualType = "jpeg";
		elseif ($imageType == "image/jpg")
			$actualType = "jpg";
		elseif ($imageType == "image/gif")
			$actualType = "gif";
		elseif ($imageType == "image/png")
			$actualType = "png";
		return $actualType;
	}

	public function updateErrorCounts($canUpload,$errorCounts)
	{
		foreach($errorCounts as $k=>$v)
		{
			if(is_int($v))
				$sum[$k] = $v+$canUpload[$k];
			elseif(array_key_exists($k,$canUpload))
				$sum[$k]=$canUpload[$k];
			else
				$sum[$k]=$v;
		}
		return $sum;
	}
	public function canUpload($image,$nonScreenedPicObj)
	{
		$sizeLimitExceeded = $this->sizeLimitExceeded($image);
		if($sizeLimitExceeded)
		{
			$error['ErrorCounter']=1;
			$error['SizeErrorCounter']=1;
			return $error;
		}
		$imageType = $this->getImageType($image["tmp_name"]);
		$unexpectedFormat = $this->unexpectedFormat($imageType);
		if($unexpectedFormat)
		{
			$error['ErrorCounter']=1;
			$error['FormatErrorCounter']=1;
			return $error;
		}
		$picError = self::picError($image);
		if($picError)
		{
			$error['ErrorCounter']=1;
			return $error;
		}
		return true;
	}
	public static function picError($image)
	{
		if ($image["error"]!=0)
			return true;
		else
			return false;
	}
	public function copyPic($image,$nonScreenedPicObj)
	{
		$actualType = $this->getActualType($image['type']);		
                $picInfoArr = $this->getPicCopyData($nonScreenedPicObj,$actualType);                                
                if(!move_uploaded_file($image['tmp_name'], $picInfoArr['SRC']))
                {
                        $error['ErrorCounter']=1;
                        return $error;
                }
                elseif($actualType == "png")
                {
                	$srcPath = $this->convertPngToJpeg($picInfoArr['SRC']);
                	if($srcPath != "0")
                	{
                		$picInfoArr["SRC"] = $srcPath;
                		$picInfoArr["ACTUAL_TYPE"] = "jpeg";
                		$todayDate = date("Y-m-d");
                		$pngTrackingObj = new PICTURE_PNG_PHOTO_TRACKING("newjs_masterRep");
                		$pngTrackingObj->insertPngTracking($todayDate,$picInfoArr["PIC_ID"]);
                		unset($pngTrackingObj);
                	}
                	else
                	{
                		$error['ErrorCounter']=1;
                		//SendMail::send_email("sanyam1204@gmail.com,reshu.rajput@jeevansathi.com","error in converting pic with path \n\n".$picInfoArr['SRC']."\n and pic id:".$picInfoArr["PIC_ID"]);
                        return $error;
                	}
                	
                }

		return $picInfoArr;
	}

	public function getPicCopyData($nonScreenedPicObj,$actualType)
	{
		$picIdGenerated = $this->getPictureAutoIncrementId();
		$srcName = $nonScreenedPicObj->getSaveUrl("mainPic",$picIdGenerated,$this->profileid,$actualType);
		$destName= $nonScreenedPicObj->getSaveUrl("thumbnail96",$picIdGenerated,$this->profileid,$actualType);
		$arr = array("ACTUAL_TYPE"=>$actualType,"PIC_ID"=>$picIdGenerated,"SRC"=>$srcName,"DEST"=>$destName);
		return $arr; 
	}

	public function copyCroppedPic($image,$nonScreenedPicObj,$picId)
	{
		$picInfoArr = $this->getCroppedPicCopyData($image,$nonScreenedPicObj,$picId);
		if(!move_uploaded_file($image['tmp_name'], $picInfoArr['DEST']))
                {
                        $error['ErrorCounter']=1;
                        return $error;
                }
                return $picInfoArr;

	}

	public function getCroppedPicCopyData($image,$nonScreenedPicObj,$picId)
	{
                $actualType = $this->getActualType($image['type']);
		$destName = $nonScreenedPicObj->getSaveUrl("profilePic", $picId, $this->profileObj->getPROFILEID(), $actualType);
                $arr = array("ACTUAL_TYPE"=>$actualType,"PIC_ID"=>$picId,"DEST"=>$destName);
                return $arr;
	}

	public static function getImageType($fileName)
	{
		$imageType = exif_imagetype($fileName);  //php function to return image size
		return $imageType;

	}
	public static function numberOfPhotosInLimit($picsInDb)
	{
		if ($picsInDb>=PictureStaticVariablesEnum::maxNumberOfPhotos)
			return false;
		return true;
	}
	public static function sizeLimitExceeded($image)
	{
		if ($image["size"]>PictureStaticVariablesEnum::maxPhotoSize)
		{
			return true;
		}
		return false;
	}
	public static function unexpectedFormat($typeOfImage)
	{
		if (in_array($typeOfImage, self::$allowedImageType))
		{
			return false;
		}
		return true;
	}

	public static function convertPngToJpeg($filename)
	{		
		$destFilename = str_replace(".png",".jpeg",$filename);
		$image = imagecreatefrompng($filename);		
    	imagejpeg($image,$destFilename);
    	imagedestroy($image);
    	if($image == "")
    		return 0;
    	else
    		return $destFilename;
	}
}
