<?php
/**
 * @brief This class is used to handle a image parameter detection using Google Vision API
 * User: Pankaj Khandelwal
 * Date: 20/09/17
 * Time: 2:37 PM
 */
include_once(JsConstants::$cronDocRoot . '/amq/vendor/autoload.php');
use Vision as vs;

class GoogleVisionApi
{

	/**
	 * @brief This function call Google Vision API to detect face
	 * @param $picturePath
	 * @param $imageFormatType
	 * @return Face co-ordinates
	 */
	public function getPictureCoordinates($picturePath, $imageFormatType,$pictureid,$profileid){
		PictureFunctions::setHeaders();

		//COPY into temp to avoid original image corruption

		if(!file_exists($picturePath))
		{
			SendMail::send_email("lavesh.rawat@gmail.com,pankaj139@gmail.com,reshu.rajput@jeevansathi.com",$picturePath,"Face detection error");
			//die;
		}
		$newImgArr=explode(".",$picturePath);
		if(count($newImgArr)==2){
			$newImgArr[0]=$newImgArr[0]."tempORIG";
			$newPicturePath=$newImgArr[0].".".$newImgArr[1];
			copy($picturePath, $newPicturePath);
			$picturePath=$newPicturePath;
			$this->rotateImageFile($picturePath,$imageFormatType);
		}
		else{
			SendMail::send_email("lavesh.rawat@gmail.com,pankaj139@gmail.com,reshu.rajput@jeevansathi.com",$picturePath,"Face detection error2");
			//die;
		}

		$img = file_get_contents($picturePath);
		$imgData = base64_encode($img);

		$data = '{
				    "requests": [
				    {
				      "image": {
				        "content": "' . $imgData . '"
				      },
				      "features": [
				        {
				          "type": "FACE_DETECTION"
				        }
				      ]
				    }
				  ]
				}';
		//$url = "https://vision.googleapis.com/v1/images:annotate?key=AIzaSyAY-YyNRX7_SqF8e88wIMz7RKySLpfX2Eg";
		$url = "https://vision.googleapis.com/v1/images:annotate?key=AIzaSyCSQBtJte7cqF6WdKJfkt5QysEw4s-aW9E";

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
		$faceDetected = false;
		if (!empty($response["faceAnnotations"])) {
			$otherData = $response["faceAnnotations"][0];
			$cordinates = $otherData["boundingPoly"]["vertices"];
			$x = $cordinates[0]["x"]?$cordinates[0]["x"]:0;
			$y = $cordinates[0]["y"]?$cordinates[0]["y"]:0;
			$h = $cordinates[2]["y"] - $y;
			$w = $cordinates[1]["x"] - $x;
			$cord = $w . "x" . $h . "+" . $x . "+" . $y;
			$faceDetected = true;
		}
		$photoBenchmarkObj = new test_PHOTO_BENCHMARK();
		$photoBenchmarkObj->insert($faceDetected, $pictureid, $picturePath, $imageFormatType, $profileid);
		return $cord;
	}


	/**
	 * @brief This function detect the image orientation and rotate them if needed.
	 * @param $filename
	 * @param $imageFormatType
	 */
	public function rotateImageFile($filename, $imageFormatType)
	{
		$exif = exif_read_data($filename);
		$img = imagecreatefromstring(file_get_contents($filename));
		if ($img && $exif && isset($exif['Orientation']))
		{
			$ort = $exif['Orientation'];

			if ($ort == 6 || $ort == 5)
				$img = imagerotate($img, 270, null);
			if ($ort == 3 || $ort == 4)
				$img = imagerotate($img, 180, null);
			if ($ort == 8 || $ort == 7)
				$img = imagerotate($img, 90, null);

			if ($ort == 5 || $ort == 4 || $ort == 7)
				imageflip($img, IMG_FLIP_HORIZONTAL);
		}
		if ($imageFormatType == "gif")
			imagegif($img, $filename);
		else
			imagejpeg($img, $filename);
	}

	public function getPictureDetails($picturePath, $iPicId, $iProfileId,$imageFormatType,$ordering){
		PictureFunctions::setHeaders();

		//COPY into temp to avoid original image corruption

		if(!file_exists($picturePath))
		{
			SendMail::send_email("lavesh.rawat@gmail.com,pankaj139@gmail.com",$picturePath,"Face detection error");
			//die;
		}
		$newImgArr=explode(".",$picturePath);
		if(count($newImgArr)==2){
			$newImgArr[0]=$newImgArr[0]."tempORIG";
			$newPicturePath=$newImgArr[0].".".$newImgArr[1];
			copy($picturePath, $newPicturePath);
			$picturePath=$newPicturePath;
			$this->rotateImageFile($picturePath,$imageFormatType);
		}
		$img = file_get_contents($picturePath);
		$imgData = base64_encode($img);

		$data = '{
				    "requests": [
				    {
				      "image": {
				        "content": "' . $imgData . '"
				      },
				      "features": [
				        {
				          "type": "FACE_DETECTION"
				        },
				        {
				            "type": "LABEL_DETECTION"
				        },
				        {
				            "type": "SAFE_SEARCH_DETECTION"
				        }
				      ]
				    }
				  ]
				}';
		//$url = "https://vision.googleapis.com/v1/images:annotate?key=AIzaSyAY-YyNRX7_SqF8e88wIMz7RKySLpfX2Eg";
		$url = "https://vision.googleapis.com/v1/images:annotate?key=AIzaSyCSQBtJte7cqF6WdKJfkt5QysEw4s-aW9E";

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);

		$response = json_decode($result, true);
		$response = $response["responses"][0];
		$safes = $response["safeSearchAnnotation"];
		$labels = $response["labelAnnotations"];

		foreach ($labels as $label)
		{
			$desc[] = $label["description"];
		}
		$faces = $response["faceAnnotations"];
		$arrPicData['LABEL'] = implode(",",$desc);
		$arrPicData['ADULT'] = $safes["adult"];
		$arrPicData['SPOOF'] = $safes["spoof"];
		$arrPicData['VIOLENCE'] = $safes["violence"];
        $arrPicData['FACE_COUNT'] = is_array($faces) ? count($faces) : 0;
        
        $arrPicData['PICTUREID'] = $iPicId;
        $arrPicData['PROFILEID'] = $iProfileId;
		$arrPicData["MAINPIC"] = $ordering == 0?1:0;

        $storeObjApiResp = new PICTURE_PICTURE_API_RESPONSE();
        //TODO : $arrPicData
        $iPicId = $storeObjApiResp->insertRecord($arrPicData);
        
		$postParams["api_secret"] = "IFJC5E4Da1_N-h53VW_YGGhBpi-mGRto";
		$postParams["api_key"] = "S8ihRmykTeEf4gH8x2wysOcLYWp_D348";
		$postParams["return_attributes"] = "gender,age,headpose,facequality,blur,ethnicity";
		$postParams["image_base64"] = $imgData;
		$url = "https://api-us.faceplusplus.com/facepp/v3/detect";

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postParams);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);
		$result = json_decode($result, true);
		$facePlusFaces = $result["faces"];
		if($iPicId && is_array($faces))
		{
		    $storeObjFaceResp = new PICTURE_FACE_RESPONSE();
            foreach($faces as $index=>$face)
			{
				$cordinates = null;
				$cord = null;
				$cordinates = $face["boundingPoly"]["vertices"];
				$x = $cordinates[0]["x"]?$cordinates[0]["x"]:0;
				$y = $cordinates[0]["y"]?$cordinates[0]["y"]:0;
				$h = $cordinates[2]["y"] - $y;
				$w = $cordinates[1]["x"] - $x;
				$cord = $w . "x" . $h . "+" . $x . "+" . $y;

                $arrData['CORD'] = $cord; 
                $arrData['BLUR'] = $face["blurredLikelihood"];
                $arrData['PAN_ANGLE'] = $face["panAngle"];
                $arrData['ROLL_ANGLE'] = $face["rollAngle"];
                $arrData['TILT_ANGLE'] = $face["tiltAngle"];
				$arrData['UNDEREXPOSED'] = $face["underExposedLikelihood"];
				$arrData["facequality"] = $facePlusFaces[$index]["attributes"]["facequality"]["value"];
				$arrData["gaussianblur"] = $facePlusFaces[$index]["attributes"]["blur"]["gaussianblur"]["value"];
				$arrData["motionblur"] = $facePlusFaces[$index]["attributes"]["blur"]["motionblur"]["value"];
				$arrData["blurness"] = $facePlusFaces[$index]["attributes"]["blur"]["blurness"]["value"];
				$arrData["gender"] = $facePlusFaces[$index]["attributes"]["gender"]["value"];
				$arrData["age"] = $facePlusFaces[$index]["attributes"]["age"]["value"];
				$arrData["ethnicity"] = $facePlusFaces[$index]["attributes"]["ethnicity"]["value"];
                
                $arrData['PICTUREID'] = $iPicId;

                $storeObjFaceResp->insertRecord($arrData);
			}
		}
	}

}
