<?php
class PictureFunctions
{
	
	/*This function is used to get image format of image
	@param imageUrl: image url
	@return imageType: image format type
	*/
	public static function getImageFormatType($imageUrl)
	{
		$imageInfo = getimagesize($imageUrl);
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
		return $imageT;

	}

	/*This function is used to get docroot url instead of application url of pictures
	@param imageUrl : imageUrl
	@return imageUrl : image url with docroot
	*/
	public static function getPictureDocUrl($imageUrl)
	{ 
		if(strpos($imageUrl,JsConstants::$applicationPhotoUrl)!=1)
		{
			$imageUrl=str_replace(JsConstants::$applicationPhotoUrl,JsConstants::$docRoot,$imageUrl);
		}
		if(PictureFunctions::IfUsePhotoDistributed('X'))
		{
			$imageUrl = str_replace(JsConstants::$photoServerName.'/','',$imageUrl);
		}

		return $imageUrl;
	}
	
	
	/*This function is used to get image server enum url instead of application url of pictures
	@param imageUrl : imageUrl
	@return imageUrl : image url with image server
	*/
	public static function getPictureServerUrl($imageUrl)
	{ 
		if(PictureFunctions::IfUsePhotoDistributed('X') && strpos($imageUrl,JsConstants::$applicationPhotoUrl)!=1)
		{
			$imageUrl=str_replace(JsConstants::$applicationPhotoUrl,IMAGE_SERVER_ENUM::$appPicUrl,$imageUrl);
		}
		
		return $imageUrl;
	}
	
	public function maintain_ratio_canvas($pic_name,$final_pic_name,$x1,$y1,$x2,$y2,$width,$height,$type_of_image,$click='1')
	{
		$filename = $pic_name;
		$new_filename = $final_pic_name;
		if ($type_of_image == "image/gif" || $type_of_image == "image/GIF" || $type_of_image == ".gif" || $type_of_image == ".GIF")
			$image = imagecreatefromgif($filename);
		else
			$image = imagecreatefromjpeg($filename);
			

		$width_orig = imagesx($image);
		$height_orig = imagesy($image);

		$ratio_orig = ($width_orig/$height_orig);
                 if ($click == "1") {
                        if ($width_orig < $width && $height_orig < $height) {
                                $width = $width_orig;
                                $height = $height_orig;
                        } else {
                                if ($width / $height > $ratio_orig) {
                                        $width = $height * $ratio_orig;
                                } else {
                                        $height = $width / $ratio_orig;
                                }
                        }
                } else {
                        if ($width_orig > "340") {
                                $width = "340";
                                $height = (($width / $width_orig) * $height_orig);
                                if ($height > "310") {
                                        $height = "310";
                                        $width = (($height / $height_orig) * $width_orig);
                                }
                        } elseif ($height_orig > "310") {
                                $height = "310";
                                $width = (($height / $height_orig) * $width_orig);
                                if ($width > "340") {
                                        $width = "340";
                                        $height = (($width / $width_orig) * $height_orig);
                                }
                        } else {
                                $height = $height_orig;
                                $width = $width_orig;
                        }
                        if ($height < 100)
                                $height = "100";
                        if ($width < 75)
                                $width = "75";
                }
                // Resample
		$image_p = imagecreatetruecolor($width, $height);
		imagecopyresampled($image_p, $image, $x2, $y2, $x1, $y1, $width, $height, $width_orig, $height_orig);

		// Output
		if ($type_of_image == "image/gif" || $type_of_image == "image/GIF" || $type_of_image == ".gif" || $type_of_image == ".GIF")
			imagegif($image_p, $new_filename);
		else
			imagejpeg($image_p, $new_filename);
		//$command = "chmod -R 777 ".$new_filename;
		//shell_exec($command);
		chmod($new_filename, 0777);
	}

	public function maintain_ratio_profile_thumb($pic_name,$final_pic_name,$x1,$y1,$x2,$y2,$width,$height,$final_width,$final_height,$type_of_image)
	{
		$filename = $pic_name;
		$new_filename = $final_pic_name;

		if ($type_of_image == "image/gif" || $type_of_image == "image/GIF" || $type_of_image == ".gif" || $type_of_image == ".GIF")
			$image = imagecreatefromgif($filename);
		else
			$image = imagecreatefromjpeg($filename);
			
		// Resample
		$image_p = imagecreatetruecolor($width, $height);
		imagecopyresampled($image_p, $image, $x2, $y2, $x1, $y1, $width, $height, $width, $height);

		$width_orig = $width;
		$height_orig = $height;

		$image_p1 = imagecreatetruecolor($final_width, $final_height);
		imagecopyresampled($image_p1, $image_p, 0, 0, 0, 0, $final_width, $final_height, $width_orig, $height_orig);

		// Output
		if ($type_of_image == "image/gif" || $type_of_image == "image/GIF" || $type_of_image == ".gif" || $type_of_image == ".GIF")
			imagegif($image_p1, $new_filename);
		else
			imagejpeg($image_p1, $new_filename);

		//$command = "chmod -R 777 ".$new_filename;
		//shell_exec($command);
		chmod($new_filename, 0777);
	}

	public function generate_image_for_canvas($new_filename,$max_height,$max_width,$type_of_image,$click="1")
	{
		$filename1 = $new_filename;
		
		$hmargin=0;
	        $wmargin=0;
	        
	      	if($type_of_image == "image/gif" || $type_of_image == "image/GIF" || $type_of_image == ".gif" || $type_of_image == ".GIF")
	          	$src_img = imagecreatefromgif($filename1);
	    	else
	            	$src_img = imagecreatefromjpeg($filename1);
	
	        $w=imagesx($src_img);
	        $h=imagesy($src_img);
                if ($click == "1") {
                        if ($h < $max_height)
                                $hmargin = ($max_height - $h) / 2;
                        if ($w < $max_width)
                                $wmargin = ($max_width - $w) / 2;
                        if ($hmargin && $wmargin) {
                                $x = $wmargin;
                                $y = $hmargin;
                        } elseif ($wmargin) {
                                $x = $wmargin;
                                $y = 0;
                        } elseif ($hmargin) {
                                $x = 0;
                                $y = $hmargin;
                        } else {
                                $x = 0;
                                $y = 0;
                        }

                        if ($type_of_image == "image/gif" || $type_of_image == "image/GIF" || $type_of_image == ".gif" || $type_of_image == ".GIF") {
                                if ($max_width == 340)
                                        $filename = sfConfig::get('sf_web_dir') . "/images/white340.gif";
                                else
                                        $filename = sfConfig::get('sf_web_dir') . "/images/white96.gif";
                                $des_img = imagecreatefromgif($filename);
                        }
                        else {
                                if ($max_width == 340)
                                        $filename = sfConfig::get('sf_web_dir') . "/images/white340.jpg";
                                else
                                        $filename = sfConfig::get('sf_web_dir') . "/images/white96.jpg";
                                $des_img = imagecreatefromjpeg($filename);
                        }

                        imagecopymerge($des_img, $src_img, $x, $y, 0, 0, $w, $h, 100);
                        unset($src_img);
                        if ($type_of_image == "image/gif" || $type_of_image == "image/GIF" || $type_of_image == ".gif" || $type_of_image == ".GIF") {
                                imagegif($des_img, $filename1);
                        } else {
                                imagejpeg($des_img, $filename1);
                        }
                } else {
                        if ($type_of_image == "image/gif" || $type_of_image == "image/GIF" || $type_of_image == ".gif" || $type_of_image == ".GIF") {
                                imagegif($src_img, $filename1);
                        } else {
                                imagejpeg($src_img, $filename1);
                        }
                }
                //$command = "chmod -R 777 ".$filename1;
		//shell_exec($command);
		chmod($filename1, 0777);
	
	        unset($des_img);
		unset($filename1);
	}

	public function photo_resize($width, $height, $req_width, $req_height)
	{
		if( $width > $height)
		{
			$hei=round($req_width*$height/$width);
			$wid=$req_width;
			if($hei>$req_height)
			{
				$hei=$req_height;
				$wid=round($req_height*$width/$height);
			}
			$height=$hei;
			$width=$wid;
		}
		elseif( $height > $width)
		{
			$wid=round($req_height*$width/$height);
			$hei=$req_height;
			if($wid>$req_width)
			{
				$wid=$req_width;
				$hei=round($req_width*$height/$width);
			}
			$height=$hei;
			$width=$wid;
		}
		else
		{
			if($req_height < $req_width)
				$x=$req_height;
			else
				$x=$req_width;
			$height=$x;
			$width=$x;
		}
		$hh=0;
		$ww=0;

		if($height<$req_height)
		{
			$hh=($req_height-$height)/2;
			$hh.="px";
		}
		if($width<$req_width)
		{
			$ww=($req_width-$width)/2;
			$ww.="px";
		}
		$size[0]=$ww; //left right margin
		$size[1]=$hh; //top bottom margin
		$size[2]=$width; //final width
		$size[3]=$height; //final height

		return $size;
	}

	public function createWatermark($filename_path,$type_of_pic,$format)
	{
		if ($type_of_pic == "main")
			$watermark_path = sfConfig::get('sf_web_dir')."/images/watermark_big_1.gif";
		else
			$watermark_path = sfConfig::get('sf_web_dir')."/images/watermark_small.gif";
			
		$destination_path = $filename_path;

		if($format == "image/gif" || $format == "image/GIF")
                        $src_handle = imagecreatefromgif($filename_path);
                else
                        $src_handle = imagecreatefromjpeg($filename_path);

		$width = imagesx($src_handle);
		$height = imagesy($src_handle);

		$watermark_handle = imagecreatefromgif($watermark_path);
		$w = imagesx($watermark_handle);
		$h = imagesy($watermark_handle);

		$x = $width-$w;
		$y = ($height-$h)/2;

		imagecopymerge($src_handle,$watermark_handle,$x,$y,0,0,$w,$h,30);

		if ($format == "image/gif" || $format == "image/GIF")
        		imagegif($src_handle,$destination_path);
		else
        		imagejpeg($src_handle,$destination_path,90);
		

		chmod($destination_path,0777);
		unset($src_handle);
		unset($watermark_handle);
	}
        /**
        This function is read the image sourceImage and send headers with new image newName
        @param sourceImage file to be read
        @param newName new name of the file
        */
	public function renameImageName($sourceImage,$newName)
	{
		/*
		$type=strrpos($sourceImage,'.');
		$type=substr($sourceImage, $type, strlen($sourceImage));
		if($type=='jpg')
			header('Content-Type: image/jpg');
		elseif($type=='gif')
			header('Content-Type: image/gif');
		*/
		header('Content-type: application/pdf');
        	header('Content-Disposition: attachment; filename="' . $newName .'"');
	        readfile($sourceImage);
		die;
	}

	/** Funstion added by Reshu for Image Server Implementation
	* @param source file to be transfered
	* @param dest file destination to be saved
	*/
	public function moveImage($source,$dest)
	{
		//$success = move_uploaded_file($source, $dest);		
		$success = copy($source, $dest);		
		return $success;
	}

	/*
	This function is used to write images at a remote location to local disk
	@param - source url, destination path
	*/
	public function moveImageFromRemoteLocationToLocalDisk($src,$dest)
	{
		$picture = CommonUtility::sendCurlPostRequest($src,0);
		$fh = fopen($dest, 'w+');
		fwrite($fh, $picture);
		fclose($fh);
	}
	
	/*This function is used to get complete Picture url with Cloud Implementation
	*@param:value Picture url need to be modified
	*@return:setServer Complete url after modification
	*/
	public static function getCloudOrApplicationCompleteUrl($value,$getAbsoluteUrl=false)
	{
		$flag=substr($value,0,2);
		$remaining=substr($value,2);
		switch($flag)
		{
			case IMAGE_SERVER_ENUM::$appPicUrl : 
							if(MobileCommon::getHttpsUrl()==true)
								$setServer=$getAbsoluteUrl?JsConstants::$docRoot:JsConstants::$httpsApplicationPhotoUrl;
							else
								$setServer=$getAbsoluteUrl?JsConstants::$docRoot:JsConstants::$applicationPhotoUrl;
							     break;
			case IMAGE_SERVER_ENUM::$cloudUrl : 
							if(MobileCommon::getHttpsUrl()==true)
								$setServer=JsConstants::$httpsCloudUrl;
							else
								$setServer=JsConstants::$cloudUrl;
							    break;
			 case IMAGE_SERVER_ENUM::$cloudArchiveUrl : $setServer=JsConstants::$cloudArchiveUrl;
                                                            break;
		}
		
		if($setServer)
			$setServer = $setServer.$remaining;
		else
			$setServer=$value;
		if(PictureFunctions::IfUsePhotoDistributed('X') && $getAbsoluteUrl)
		{
			$matchToBeArr = JsConstants::$photoServerShardingEnums;
			foreach($matchToBeArr as $k=>$v)
				$setServer = str_replace($v.'/','',$setServer);
			//$setServer = str_replace(JsConstants::$photoServerName.'/','',$setServer);
			
		}
		return $setServer;
	}
	/*
	This function is used to rotate a image and generate the rotated image at the same path
	@param - file path, angle, format
	*/
	public static function rotateImage($filename_path,$angle,$format)
	{
		$destination_path = $filename_path;

                if($format == "image/gif" || $format == "image/GIF")
                        $src_handle = imagecreatefromgif($filename_path);
                else
                        $src_handle = imagecreatefromjpeg($filename_path);

		$rotate = imagerotate($src_handle,$angle,0);

		if($format == "image/gif" || $format == "image/GIF")
                        imagegif($rotate,$destination_path);
                else
                        imagejpeg($rotate,$destination_path,90);

		imagedestroy($src_handle);
		imagedestroy($rotate);
	}


	/**
	* This function will map the photo-url to message.
	* @param url : url of the photo to be displayed.
	* @return arr aray containg photo message and action on message;
	*/
	public static function mapUrlToMessageInfoArr($url,$photoType='Profile',$isPhotoRequested='',$gender="", $noStaticImage=false)
	{
		$clickAction = null;
		$msg = null;
		if(!in_array($photoType,ProfilePicturesTypeEnum::$PICTURE_SIZES_STOCK))
			$photoType = "ProfilePicUrl";
		switch(true)
		{
			case strstr($url,constant("StaticPhotoUrls::contactAcceptedPhotoFemale$photoType")):
			case strstr($url,constant("StaticPhotoUrls::contactAcceptedPhotoMale$photoType")):
				$msg = PhotoMessagesEnum::VISIBLE_ON_ACCEPT;
				break;
			case strstr($url,constant("StaticPhotoUrls::nonLoggedInPhotoFemale$photoType")):
			case strstr($url,constant("StaticPhotoUrls::nonLoggedInPhotoMale$photoType")):
			{
				$clickAction = ApiPictureActionEnum::LOGIN;
				$msg = PhotoMessagesEnum::LOGIN_TO_VIEW;
				break;
			}
			case strstr($url,constant("StaticPhotoUrls::underScreeningPhotoMale$photoType")):
			case strstr($url,constant("StaticPhotoUrls::underScreeningPhotoFemale$photoType")):
				$msg = PhotoMessagesEnum::COMING_SOON;
				break;
			case strstr($url,constant("StaticPhotoUrls::filteredPhotoFemale$photoType")):
			case strstr($url,constant("StaticPhotoUrls::filteredPhotoMale$photoType")):
				$msg =PhotoMessagesEnum::PROFILE_FILTERED;
				break;
			case (constant("StaticPhotoUrls::noPhotoFemale$photoType")!="" && strstr($url,constant("StaticPhotoUrls::noPhotoFemale$photoType"))):
                        case (constant("StaticPhotoUrls::noPhotoMale$photoType")!="" && strstr($url,constant("StaticPhotoUrls::noPhotoMale$photoType"))):
                                $msg =PhotoMessagesEnum::NO_PHOTO;
                                break;
			case strstr($url,constant("StaticPhotoUrls::requestPhotoFemale$photoType")):
                        case strstr($url,constant("StaticPhotoUrls::requestPhotoMale$photoType")):
			case empty($url):
			{
				if($isPhotoRequested=='Y')
				{
					$msg = PhotoMessagesEnum::PHOTO_REQUESTED;
				}
				else
				{
					$msg = PhotoMessagesEnum::PHOTO_REQ;
					$clickAction = ApiPictureActionEnum::REQUEST;
				}
				break;
			}
			default:
				break;
		}
		$arr["label"] = $msg;
		if($msg!=null)
		{
			if(MobileCommon::isApp() || $noStaticImage)
				$arr["url"] = null;
			else
				$arr["url"] =self::getNoPhotoJSMS($gender,$photoType);
		}
		else
			$arr["url"] = $url;
		$arr["action"] = $clickAction;
		return $arr;
	}

	/*This function is used to get default image for JSMS 
	*@param gender : gender to find default image
	*@return url : url of no photo image
	*/
	public static function getNoPhotoJSMS($gender,$photoType="JSMS")
	{
		if($gender=='F')
                	return JsConstants::$imgUrl.constant("StaticPhotoUrls::noPhotoFemale$photoType");
                elseif($gender=='M')
                        return JsConstants::$imgUrl.constant("StaticPhotoUrls::noPhotoMale$photoType");
		else
			return null;
	}
        /**
	 * Create Image
	 * Function for creating image from given path
	 * @access private
	 * @params Path	  	: Path of image
	 */	
	public function createImage($Path)
	{
		$szType = $this->getImageFormatType($Path);
		if($szType == "gif")
		{
			$image = imagecreatefromgif($Path);
		}
		else if($szType == "jpeg")
		{
			$image = imagecreatefromjpeg($Path);
		}
		
		return $image;
	}

	/**
	 * Store Resized Image
	 * Function for creating image from given path
	 * @access private
	 * @params new_image		: Raw image returned by imagecreatetruecolor()
	 * @params StoragePath	: Path of image
	 * @params Type			: Pic Format
	 */	
	public function storeResizedImage($new_image,$StoragePath,$type)
	{
		if($type == "gif")
		{
			imagegif($new_image, $StoragePath);
		}
		else if($type == "jpeg" || $type == "jpg")
		{
			imagejpeg($new_image, $StoragePath,90);
		}
	}

	public static function getHeaderThumbnailPicUrl(){
		$loginProfile = LoggedInProfile::getInstance();
		if($loginProfile->getPROFILEID()!='')
		{
			$memCacheObject = JsMemcache::getInstance();
			if($memCacheObject->get($loginProfile->getPROFILEID() . "_THUMBNAIL_PHOTO")){
				$thumbnailUrl = unserialize($memCacheObject->get($loginProfile->getPROFILEID() . "_THUMBNAIL_PHOTO"));
			} else {
				$gender = $loginProfile->getGENDER();
				$profilePic = $loginProfile->getHAVEPHOTO();
				if (empty($profilePic)) {
					$profilePic = "N";
				}
				if (isset($profilePic) && !empty($profilePic) && $profilePic != "N") {
					$pictureServiceObj = new PictureService($loginProfile);
					$profilePicObj = $pictureServiceObj->getProfilePic();
				   	if($profilePicObj){
				   		if($profilePic=='U')	
							$picUrl = $profilePicObj->getThumbail96Url();
						else
							$picUrl = $profilePicObj->getProfilePic120Url();

					   	$photoArray = self::mapUrlToMessageInfoArr($picUrl,'ThumbailUrl','',$gender);
					   	if($photoArray[label] != ''){
	                    	$thumbnailUrl = self::getNoPhotoJSMS($gender,'ProfilePic120Url');
					   	} else {
			                $thumbnailUrl = $photoArray['url'];
			            }
				   	}
				} else {
					$thumbnailUrl = self::getNoPhotoJSMS($gender,'ProfilePic120Url');
				}
				$memCacheObject->set($loginProfile->getPROFILEID() . "_THUMBNAIL_PHOTO", serialize($thumbnailUrl) , 600);
			}
		} else {
			// default show male photo URL
			$thumbnailUrl = JsConstants::$imgUrl.constant("StaticPhotoUrls::noPhotoMale$photoType");
		}
		
		return $thumbnailUrl;
	}

	public static function mapPictureFormatType($type)
        {
        	$result;
        	switch($type)
        	{
        		case "image/jpeg": $result = "jpeg"; break;
        		case "image/jpg" : $result = "jpg"; break;
        		case "image/gif" : $result = "gif";break;
        	}
        	return $result;
        }

	/**
	* pid = 'X' fro  face detection,preproces
	*/
	public static function IfUsePhotoDistributed($pid)
	{
		if(in_array($pid,array('9061321','2114','1572'))) //add 'X' for facedet/copy to orig on live server
			return 1;

		if(JsConstants::$usePhotoDistributed)
			return 1;
		return 0;
	}

	public static function getNameIfUsePhotoDistributed($mainPic) 
	{
		$matchToBeArr = JsConstants::$photoServerShardingEnums;
		foreach($matchToBeArr as $k=>$v)
		{
			if($mainPic)
			{
				if(strstr($mainPic,$v))
				{
					return $v;
				}
			}
		}
		return NULL;
	}
        /**
         * 
         * @param type $viewedProfileId
         * @param type $photoDisplayArray
         * @param type $photoType
         * @param type $loggedInprofileId
         * @param type $perform
         * @param type $value
         */
        public static function photoUrlCachingForChat($viewedProfileId,$photoDisplayArray = array(),$photoType,$loggedInprofileId = '',$perform,$value = ""){
                
                if( ($value!='' || $perform=='remove') && $loggedInprofileId != '' && array_key_exists($viewedProfileId, $photoDisplayArray)){
                        $key = $photoType."_".$loggedInprofileId."_".$viewedProfileId;
                }else{
                        $key = $photoType."_".$viewedProfileId;
                }
		//echo $key."<br>\n"  ;
                if($perform == 'set'){
                        JsMemcache::getInstance()->set($key,$value,3600);
                }elseif($perform == 'remove'){
                        JsMemcache::getInstance()->remove($key);
                }
        }
}
?>
