<?php
/**
 * @brief This class is used to handle a image parameter detection using Google Vision API
 * User: Pankaj Khandelwal
 * Date: 20/09/17
 * Time: 2:37 PM
 */

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

}
