<?php
/**
 * Created by PhpStorm.
 * User: Pankaj1
 * Date: 29/08/17
 * Time: 11:47 AM
 */

class ApiFaceDetectionTask extends sfBaseTask
{
	public static $FACE_DETECTION_SIZE_CONST = array(
		"ProfilePic120Url" => array("MAX_FACE" => "110", "IDEAL_FACE" => "90", "HEIGHT_INCREASE" => 7, "MIN_FACE" => "75"),
		"ProfilePic235Url" => array("MAX_FACE" => "210", "IDEAL_FACE" => "180", "HEIGHT_INCREASE" => 15, "MIN_FACE" => "75"),
		"ProfilePic450Url" => array("MAX_FACE" => "410", "IDEAL_FACE" => "200", "HEIGHT_INCREASE" => 30, "MIN_FACE" => "75"),
		"ProfilePicUrl" => array("MAX_FACE" => "135", "IDEAL_FACE" => "110", "HEIGHT_INCREASE" => 13, "MIN_FACE" => "75"),
		"MobileAppPicUrl" => array("MAX_FACE" => "410", "IDEAL_FACE" => "200", "HEIGHT_INCREASE" => 30, "MIN_FACE" => "75"));

	protected function configure()
	{
		$this->addArguments(array(
			new sfCommandArgument('profileId', sfCommandArgument::REQUIRED, 'My argument'),
			new sfCommandArgument('pictureId', sfCommandArgument::REQUIRED, 'My argument'),
			new sfCommandArgument('imagePath', sfCommandArgument::REQUIRED, 'My argument')

		));

		$this->addOptions(array(
			new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
		));

		$this->namespace = 'cron';
		$this->name = 'ApiFaceDetectionTask';
		$this->briefDescription = 'detect face from main pic of a profile and get images of all required sizes';
		$this->detailedDescription = <<<EOF
	This cron runs every half an hour to get non screened images and detect face for them and create new required size image which will be verified during screening .
	Call it with:

	  [php symfony cron:ApiFaceDetectionTask profileId pictureId imagePath] 
EOF;
	}

	protected function execute($arguments = array(), $options = array())
	{
		ini_set('memory_limit', '1024M');
		if (!sfContext::hasInstance())
			sfContext::createInstance($this->configuration);
		$origPic = $arguments["imagePath"]; // image path
		$pid = $arguments["pictureId"]; //picture Id
		$profileid = $arguments["profileId"]; //picture Id

		if (CommonUtility::hideFeaturesForUptime())
			successfullDie();
		PictureFunctions::setHeaders();
		$pictureObj = new NonScreenedPicture();
		$profileObj = Operator::getInstance("", $profileid);
		$profileObj->getDetail("", "", "HAVEPHOTO");
		$pictureServiceObj = new PictureService($profileObj);
		unset($profilesUpdate);
		$faceDetected = false;
		$imageT = PictureFunctions::getImageFormatType($origPic);
		$outputGot = $this->getPictureCoordinates($origPic);
		$coordRegex = "/^(\d)+x(\d)+\+(\d)+\+(\d)+/";
		$profilesUpdate = array();
		if (preg_match($coordRegex, $outputGot)) {
			$faceDetected = true;
			foreach (ProfilePicturesTypeEnum::$PICTURE_SIZES as $k => $v) {
				if ($k != "MainPicUrl") {
					$picUrl = $pictureObj->getSaveUrlPicture(ProfilePicturesTypeEnum::$PICTURE_UPLOAD_TEST_DIR[$k], $pid, $profileid, $imageT, 'nonScreened');

					$output = $this->cropPicture($origPic, $outputGot, $k, $picUrl, $imageT);
					if ($output)
						$profilesUpdate[$k] = $pictureObj->getDisplayPicUrlPicture(ProfilePicturesTypeEnum::$PICTURE_UPLOAD_TEST_DIR[$k], $pid, $profileid, $imageT, 'nonScreened');

				}
			}
		}
		$this->track($faceDetected, $pid, $origPic, $profilesUpdate, $imageT, $profileid);

		unset($profileObj);
		unset($pictureServiceObj);
	}

	public function getPictureCoordinates($picturePath)
	{
		PictureFunctions::setHeaders();

		$im = file_get_contents($picturePath);
		$imdata = base64_encode($im);

		$data = '{
    "requests": [
    {
      "image": {
        "content": "' . $imdata . '"
      },
      "features": [
        {
          "type": "FACE_DETECTION"
        }
      ]
    }
  ]
}';
		$url = "https://vision.googleapis.com/v1/images:annotate?key=AIzaSyAY-YyNRX7_SqF8e88wIMz7RKySLpfX2Eg";

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);

		$result = json_decode($result, true);
		$response = $result["responses"][0];
		$cord = null;
		if (!empty($response["faceAnnotations"])) {
			$otherdata = $response["faceAnnotations"][0];
			$cordinates = $otherdata["boundingPoly"]["vertices"];
			$x = $cordinates[0]["x"];
			$y = $cordinates[0]["y"];
			$h = $cordinates[2]["y"] - $cordinates[0]["y"];
			$w = $cordinates[1]["x"] - $cordinates[0]["x"];
			$cord = $w . "x" . $h . "+" . $x . "+" . $y;
		}
		return $cord;

	}


	/*This function is used to get crop and resize information from face detected as per product requirement
	@param : $pic url of the image
	@param : $faceCoord face coordinations calcualted from face detection
	@return output : array of COORD and SFACTOR where COORD - string of format widthxheight+startx+starty and SFACTOR is resize factor
	*/

	public function cropPicture($pic, $faceCoord, $pictureType, $savePicUrl, $imageFormatType)
	{
		PictureFunctions::setHeaders();
		// Constants as per product reqirements
		$sizes = $this->getSizesToCrop($pictureType);
		$MAX_FACE = $sizes["MAX_FACE"];
		$IDEAL_FACE = $sizes["IDEAL_FACE"];
		$MAX_WIDTH = $sizes["w"];
		$MAX_HEIGHT = $sizes["h"];
		$MIN_FACE = $sizes["MIN_FACE"];
		$MAX_FROM_CENTER = ceil($sizes["w"] / 2);
		$HEIGHT_INCREASE = $sizes["HEIGHT_INCREASE"];
		$RATIO_REQUIRED = $MAX_WIDTH / $MAX_HEIGHT;
		if ($imageFormatType == "gif")
			$image = imagecreatefromgif($pic);
		else
			$image = imagecreatefromjpeg($pic);

		$coordSplit = explode("+", $faceCoord);
		$coordSplit2 = explode("x", $coordSplit[0]);
		// Face coordinates and size from face detection algo
		$face_x = $coordSplit[1];
		$face_y = $coordSplit[2];
		$face_w = $coordSplit2[0];
		$face_h = $coordSplit2[1];
		$imageInfo = getimagesize($pic);
		//get original image h/w
		$width = $imageInfo[0];
		$height = $imageInfo[1];
		// If face area more than max_face get shrinked dimensions
		$sFactor = 1;
		if ($face_w > $MAX_FACE) {
			$fw = round($MAX_WIDTH * ($face_w / $width));
			$fh = round($MAX_HEIGHT * ($face_h / $height));
			$reqFace = min($MAX_FACE, $face_w, max($IDEAL_FACE, $fw, $fh));
			$sFactor = $face_w / $reqFace;

		}
		//If face area is less then thrashold area enlarge dimensions
		if ($face_w < $MIN_FACE) {
			$fw = round($MIN_FACE * ($face_w / $width));
			$fh = round($MIN_FACE * ($face_h / $height));
			echo $reqFace = max($MIN_FACE, $face_w, min($IDEAL_FACE, $fw, $fh));
			$sFactor = $face_w / $reqFace;
		}
		$height_margin = min(($sFactor * $HEIGHT_INCREASE), (($height - $face_h) * $sFactor / 2), $face_y);
		$face_y2 = $face_y - $height_margin;
		$height = $height - $face_y2;
		if (($height / ($sFactor * $MAX_HEIGHT)) > ($width / ($sFactor * $MAX_WIDTH))) {
			if ($width < $sFactor * $MAX_WIDTH) {
				$start_x = 0;
				$final_width = $width;
			} else {
				$final_width = $sFactor * $MAX_WIDTH;
				$faceMid = $face_x + ($face_w / 2);
				if ($faceMid < $MAX_FROM_CENTER * $sFactor) {
					$start_x = 0;
				} else {
					$end_x = $width < ($faceMid + $MAX_FROM_CENTER * $sFactor) ? $width : round($faceMid + $MAX_FROM_CENTER * $sFactor);
					$start_x = $end_x - $final_width;
				}
			}
			$start_y = $face_y2;
			$req_h = max($final_width / $RATIO_REQUIRED, $face_h + $height_margin);
			$final_height = $req_h;

		} else {
			$start_y = $face_y2;
			$final_height = min($height, $sFactor * $MAX_HEIGHT);
			$req_w = max($final_height * $RATIO_REQUIRED, $face_w);
			$final_width = $width < $req_w ? $width : $req_w;
			$faceMid = $face_x + ($face_w / 2);
			if ($faceMid < $final_width / 2) {
				$start_x = 0;
			} else {
				$end_x = $width < ($faceMid + ($final_width / 2)) ? $width : ($faceMid + ($final_width / 2));
				$start_x = $end_x - $final_width;
			}
		}
		$output["MAIN"]["width"] = round($final_width);
		$output["MAIN"]["height"] = round($final_height);
		$output["MAIN"]["x"] = round($start_x);
		$output["MAIN"]["y"] = round($start_y);
		if ($sFactor > 1) {
			$pos_width = round($final_width / $sFactor) > $MAX_WIDTH ? $MAX_WIDTH : round($final_width / $sFactor);
			$pos_height = round($final_height / $sFactor) > $MAX_HEIGHT ? $MAX_HEIGHT : round($final_height / $sFactor);
			$output["APP"]["width"] = $pos_width;
			$output["APP"]["height"] = $pos_height;
		} elseif ($sFactor < 1) {
			$pos_width = round($final_width / $sFactor) > $MAX_WIDTH ? $MAX_WIDTH : round($final_width / $sFactor);
			$pos_height = round($final_height / $sFactor) > $MAX_HEIGHT ? $MAX_HEIGHT : round($final_height / $sFactor);
			$output["APP"]["width"] = $pos_width;
			$output["APP"]["height"] = $pos_height;
		} else {

			$output["APP"]["width"] = $final_width;
			$output["APP"]["height"] = $final_height;
		}
		$min_size = array("w" => "200", "h" => "200");
		$min_width = $min_size['w'];
		$min_height = $min_size['h'];
		if ($output["APP"]["width"] < $min_width) {
			$output["APP"]["width"] = $min_width;
		}
		$output["APP"]["height"] = $output["APP"]["width"] / $RATIO_REQUIRED;
		$image_p = imagecreatetruecolor($output["APP"]["width"], $output["APP"]["height"]);
		$imageCreated = imagecopyresampled($image_p, $image, 0, 0, $output["MAIN"]["x"], $output["MAIN"]["y"], $output["APP"]["width"], $output["APP"]["height"], $output["MAIN"]["width"], $output["MAIN"]["height"]);
		if ($imageFormatType == "gif")
			imagegif($image_p, $savePicUrl);
		else
			imagejpeg($image_p, $savePicUrl, 90);
		chmod($savePicUrl, 0777);
		unset($output);
		return $imageCreated;

	}

	/*This function is used to execute the face detection logic for the particular picture and return the coordinates
	@param : picturePath  url of the image
	@return : output  coordinates in string form
	*/

	public function getSizesToCrop($pictureType)
	{
		if (array_key_exists($pictureType, self::$FACE_DETECTION_SIZE_CONST)) {
			$faceDetectionSizes = ProfilePicturesTypeEnum::$PICTURE_SIZES[$pictureType];
			return array_merge($faceDetectionSizes, self::$FACE_DETECTION_SIZE_CONST[$pictureType]);
		}
		throw new jsException('', "Invalid picture Type Enum is requested in PhotoFaceDetection.class.php");

	}


	private function track($faceDetected, $pid, $origPath, $profilesUpdate, $imageT, $profileid)
	{
		$track = new test_PHOTO_BENCHMARK();
		$track->insert($faceDetected, $pid, $origPath, $profilesUpdate, $imageT, $profileid);
	}
}