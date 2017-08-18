<?php
/**
 * @brief This class is used to handle a facedetection tracking
 * @author Reshu Rajput
 * @created 2014-01-07
 */

class PhotoFaceDetection
{
	public static $FACE_DETECTION_SIZE_CONST = array(
					    "ProfilePic120Url"=>array("MAX_FACE"=>"110","IDEAL_FACE"=>"90","HEIGHT_INCREASE"=>7),
                                            "ProfilePic235Url"=>array("MAX_FACE"=>"210","IDEAL_FACE"=>"180","HEIGHT_INCREASE"=>15),
                                            "ProfilePic450Url"=>array("MAX_FACE"=>"410","IDEAL_FACE"=>"200","HEIGHT_INCREASE"=>30),
                                            "ProfilePicUrl"=>array("MAX_FACE"=>"135","IDEAL_FACE"=>"110","HEIGHT_INCREASE"=>13),
                                            "MobileAppPicUrl"=>array("MAX_FACE"=>"410","IDEAL_FACE"=>"200","HEIGHT_INCREASE"=>30));

	public function getSizesToCrop($pictureType)
	{
		if(array_key_exists($pictureType,self::$FACE_DETECTION_SIZE_CONST))
		{
			$faceDetectionSizes = ProfilePicturesTypeEnum::$PICTURE_SIZES[$pictureType];
			return array_merge($faceDetectionSizes,self::$FACE_DETECTION_SIZE_CONST[$pictureType]);
		}
		 throw new jsException('',"Invalid picture Type Enum is requested in PhotoFaceDetection.class.php");
			
	}
						
	/**
	  * This function is used to enter tracking in MIS.PHOTO_FACEDETECTION_STATS
	**/
	public function trackPhotoFaceDetection($increaseProcessed,$increaseFaceDetected)
	{
			$statObj = new PHOTO_FACEDETECTION_STATS();
			$statObj->trackPhotoFaceDetection($increaseProcessed,$increaseFaceDetected);
	}
	
	 public function getPhotoFaceDetectionStat($date)
        {
                        $statObj = new PHOTO_FACEDETECTION_STATS();
                        $result=$statObj->getPhotoFaceDetectionStat($date);
			return $result;
        }


	/*This function is used to get crop and resize information from face detected as per product requirement
	@param : $pic url of the image
	@param : $faceCoord face coordinations calcualted from face detection
	@return output : array of COORD and SFACTOR where COORD - string of format widthxheight+startx+starty and SFACTOR is resize factor 
	*/
	
	public function cropPicture($pic,$faceCoord,$pictureType,$savePicUrl,$imageFormatType)
	{
		PictureFunctions::setHeaders();
		// Constants as per product reqirements
		$sizes = $this->getSizesToCrop($pictureType);
		$MAX_FACE = $sizes["MAX_FACE"];
		$IDEAL_FACE = $sizes["IDEAL_FACE"];;
		$MAX_WIDTH = $sizes["w"];
		$MAX_HEIGHT = $sizes["h"];
		$MAX_FROM_CENTER = ceil($sizes["w"]/2);
		$HEIGHT_INCREASE = $sizes["HEIGHT_INCREASE"];
		$RATIO_REQUIRED = $MAX_WIDTH/$MAX_HEIGHT; 
		unset($sizes); 
		if($imageFormatType=="gif")
			$image = imagecreatefromgif($pic);
                else
                        $image = imagecreatefromjpeg($pic);

		$coordSplit = explode("+",$faceCoord);
		$coordSplit2 = explode("x",$coordSplit[0]);
		// Face coordinates and size from face detection algo
		$face_x= $coordSplit[1];
		$face_y= $coordSplit[2];
		$face_w = $coordSplit2[0];
		$face_h = $coordSplit2[1];
		$imageInfo = getimagesize($pic);
                //get original image h/w
                $width = $imageInfo[0];
                $height = $imageInfo[1];
		// If face area more than max_face get shrinked dimensions 
		$sFactor=1;
		if($face_w > $MAX_FACE)
		{
			$fw=round($MAX_WIDTH*($face_w/$width));
			$fh=round($MAX_HEIGHT*($face_h/$height));
			$reqFace = min($MAX_FACE,$face_w,max($IDEAL_FACE,$fw,$fh));
			$sFactor= $face_w/$reqFace;

		}
		$height_margin = min(($sFactor*$HEIGHT_INCREASE),(($height-$face_h)*$sFactor/2),$face_y);
		$face_y2 = $face_y - $height_margin;
		$height = $height - $face_y2;
		if(($height/($sFactor*$MAX_HEIGHT)) > ($width/($sFactor*$MAX_WIDTH)))
		{
			if($width < $sFactor*$MAX_WIDTH)
			{
				$start_x=0;
                        	$end_x=$width;
                        	$final_width = $width;
			}	
			else
			{
				$final_width = $sFactor*$MAX_WIDTH;
                        	$faceMid=$face_x+ ($face_w/2);
                        	if($faceMid < $MAX_FROM_CENTER*$sFactor)
                        	{
                                	$start_x=0;
                                	$end_x = $final_width;
                        	}
                        	else
                        	{
                                	$end_x=$width < ($faceMid +$MAX_FROM_CENTER*$sFactor)?$width:round($faceMid + $MAX_FROM_CENTER*$sFactor);
                                	$start_x=$end_x - $final_width;
                        	}
			}
			$start_y = $face_y2;
			$req_h = max($final_width/$RATIO_REQUIRED,$face_h+$height_margin);
			$end_y =$height<$req_h?($start_y + $height):($start_y+$req_h);
			$final_height = $req_h;
				
		}
		else
		{
			$start_y = $face_y2;
			$final_height = min($height,$sFactor*$MAX_HEIGHT);
			$end_y = $final_height + $start_y;	
			$req_w = max($final_height*$RATIO_REQUIRED,$face_w);
			$final_width = $width < $req_w?$width:$req_w;
			$faceMid=$face_x+ ($face_w/2);
			if($faceMid < $final_width/2)
			{
				$start_x =0;
				$end_x = $final_width;
			}
			else
			{
				$end_x = $width < ($faceMid +($final_width/2))?$width:($faceMid +($final_width/2));
				$start_x = $end_x - $final_width;
			}
		}
		$output["MAIN"]["width"] =round($final_width);
		$output["MAIN"]["height"] =round($final_height);
		$output["MAIN"]["x"] =round($start_x);
		$output["MAIN"]["y"] =round($start_y);
		if($sFactor >1)
		{
			$pos_width = round($final_width/$sFactor)> $MAX_WIDTH?$MAX_WIDTH:round($final_width/$sFactor);
			$pos_height =round($final_height/$sFactor)> $MAX_HEIGHT?$MAX_HEIGHT:round($final_height/$sFactor);
			$output["APP"]["width"] =$pos_width;
	                $output["APP"]["height"] =$pos_height;
		}
		else
		{
			
			$output["APP"]["width"] =$final_width;
                	$output["APP"]["height"] =$final_height;
		}
		$min_size=ProfilePicturesTypeEnum::$PICTURE_SIZES_MIN_WIDTH[$pictureType];
		$min_width=$min_size['w'];
		$min_height=$min_size['h'];
			if($output["APP"]["width"]<$min_width)
			{
				$output["APP"]["width"]=$min_width;
			}
			$output["APP"]["height"]=$output["APP"]["width"]/$RATIO_REQUIRED;
		$image_p = imagecreatetruecolor($output["APP"]["width"],$output["APP"]["height"]);
		if($image)
		{
			$imageCreated =imagecopyresampled($image_p, $image, 0,0,$output["MAIN"]["x"],$output["MAIN"]["y"],$output["APP"]["width"],$output["APP"]["height"], $output["MAIN"]["width"],$output["MAIN"]["height"]);	
		}        
		if($imageFormatType=="gif")
                	imagegif($image_p, $savePicUrl);
                else
                	imagejpeg($image_p, $savePicUrl,90);
		chmod($savePicUrl,0777);
		unset($output);
		return $imageCreated;
}

	/*This function is used to execute the face detection logic for the particular picture and return the coordinates
	@param : picturePath  url of the image
	@return : output  coordinates in string form
	*/
	public function getPictureCoordinates($picturePath)
	{
		PictureFunctions::setHeaders();
		$logicPath = JsConstants::$faceDetectionFile."/facedetect";
		$cascadePath = JsConstants::$faceDetectionCascadePath."/haarcascades/haarcascade_frontalface_alt.xml";

		//COPY into temp to avoid original image corruption
		
		if(!file_exists($picturePath))
		{
			SendMail::send_email("lavesh.rawat@gmail.com,akashkumardce@gmail.com",$picturePath,"Face detection error");
			//die;
		}
                $newImgArr=explode(".",$picturePath);
                if(count($newImgArr)==2){
                        $newImgArr[0]=$newImgArr[0]."tempORIG";
                        $newPicturePath=$newImgArr[0].".".$newImgArr[1];
                        copy($picturePath, $newPicturePath);
                        $picturePath=$newPicturePath;
                }
                else{
			SendMail::send_email("lavesh.rawat@gmail.com,akashkumardce@gmail.com",$picturePath,"Face detection error2");
			//die;
                }

		$cmd= $logicPath." --cascade=\"".$cascadePath."\" ".$picturePath;
		$output = `$cmd`;
		if($output=="no")
		{
			$cascadePath = JsConstants::$faceDetectionCascadePath."/haarcascades/haarcascade_frontalface_alt2.xml";
			$cmd= $logicPath." --cascade=\"".$cascadePath."\" ".$picturePath;
                	$output = `$cmd`;
		}
		if($output=="no")
		{
			$cascadePath = JsConstants::$faceDetectionCascadePath."/haarcascades/haarcascade_frontalface_default.xml";
			$cmd= $logicPath." --cascade=\"".$cascadePath."\" ".$picturePath;
                	$output = `$cmd`;
		}
		if($output=="no")
		{
			$cascadePath = JsConstants::$faceDetectionCascadePath."/haarcascades/haarcascade_frontalface_alt_tree.xml";
			$cmd= $logicPath." --cascade=\"".$cascadePath."\" ".$picturePath;
                	$output = `$cmd`;
		}
		if($output=="no")
		{
			$cascadePath = JsConstants::$faceDetectionCascadePath."/lbpcascades/lbpcascade_frontalface.xml";
			$cmd= $logicPath." --cascade=\"".$cascadePath."\" ".$picturePath;
                	$output = `$cmd`;
		}
		if($output!="no")
			return $output;
		else 
			return NULL;
	}
}
?>
