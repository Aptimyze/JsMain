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
	public function getPictureCoordinates($picturePath, $imageFormatType){
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
		else{
			SendMail::send_email("lavesh.rawat@gmail.com,pankaj139@gmail.com",$picturePath,"Face detection error2");
			//die;
		}

		$vision = new \Vision\Vision(
			"AIzaSyAY-YyNRX7_SqF8e88wIMz7RKySLpfX2Eg",
			[
				// See a list of all features in the table below
				// Feature, Limit
				new \Vision\Feature(\Vision\Feature::FACE_DETECTION, 100),
			]
		);
		$response = $vision->request(
			new \Vision\Image($picturePath)
		);
		$faces = $response->getFaceAnnotations();
		if(is_array($faces)) {
			$face = $faces[0];
			$cordinates = $face->getBoundingPoly()->getVertices();
			$x = $cordinates[0]->getX() ? $cordinates[0]->getX() : 0;
			$y = $cordinates[0]->getY() ? $cordinates[0]->getY() : 0;
			$h = $cordinates[2]->getY() - $y;
			$w = $cordinates[1]->getX() - $x;
			$cord = $w . "x" . $h . "+" . $x . "+" . $y;
		}
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

	public function getPictureDetails($picturePath){
		PictureFunctions::setHeaders();

		//COPY into temp to avoid original image corruption

		if(!file_exists($picturePath))
		{
			SendMail::send_email("lavesh.rawat@gmail.com,pankaj139@gmail.com",$picturePath,"Face detection error");
			//die;
		}

		$vision = new \Vision\Vision(
			"AIzaSyAY-YyNRX7_SqF8e88wIMz7RKySLpfX2Eg",
			[
				// See a list of all features in the table below
				// Feature, Limit
				new \Vision\Feature(\Vision\Feature::FACE_DETECTION, 100),
				new \Vision\Feature(\Vision\Feature::SAFE_SEARCH_DETECTION,10),
				new \Vision\Feature(\Vision\Feature::LABEL_DETECTION),100,
			]
		);
		$response = $vision->request(
			new \Vision\Image($picturePath)
		);
		$labels = $response->getLabelAnnotations();
		foreach ($labels as $label)
		{
			$desc[] = $label->getDescription();
		}
		$labelText = implode(",",$desc);
		$safes = $response->getSafeSearchAnnotation();
		$adult = $safes->getAdult();
		$spoof = $safes->getSpoof();
		$violence = $safes->getViolence();
		$faces = $response->getFaceAnnotations();
		if(is_array($faces))
		{
			foreach($faces as $face)
			{
				$cordinates = null;
				$cord = null;
				$cordinates = $face->getBoundingPoly()->getVertices();
				$x = $cordinates[0]->getX() ? $cordinates[0]->getX() : 0;
				$y = $cordinates[0]->getY() ? $cordinates[0]->getY() : 0;
				$h = $cordinates[2]->getY() - $y;
				$w = $cordinates[1]->getX() - $x;
				$cord = $w . "x" . $h . "+" . $x . "+" . $y;
				echo "Blurred:  ".$face->getBlurredLikelihood()."\n";
				echo "Tilt Angle:  ".$face->getTiltAngle()."\n";
				echo "Roll Angle:  ".$face->getRollAngle()."\n";
				echo "Pan Angle:  ".$face->getPanAngle()."\n";
				echo "UnderExposed:  ".$face->getUnderExposedLikelihood()."\n";
			}
		}
		else{
			$noFace = true;
		}

		die;
	}

}