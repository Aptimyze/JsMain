<?php
/* This library is used to have photo file specific functions for photo screening module
* @author : Reshu Rajput
* @created : 1 Oct 2014
*/
class photoFileService
{
	private $NO_BROWSED_ERROR = 4;
	
	/*This function is used to validate files uploaded from photo screening
	*@param formArr: form array including uploadPhotoNonScr array in files
	*@return error message/ Success string
	*/ 
	public function fileValidate($formArr)
        {
		if ($_FILES["uploadPhotoNonScr"])                       //If non screened photos exist
		{
			foreach ($_FILES["uploadPhotoNonScr"]["name"] as $k=>$v)
			{
				
				if ($_FILES["uploadPhotoNonScr"]["error"][$k] == $this->NO_BROWSED_ERROR)     //If no file browsed.
				{
					return "Err...Some photo/photos are not browsed";
				}
				elseif (!in_array($_FILES["uploadPhotoNonScr"]["type"][$k],PictureStaticVariablesEnum::$PICTURE_ALLOWED_FORMATS) || $_FILES["uploadPhotoNonScr"]["size"][$k]>PictureStaticVariablesEnum::MAX_PICTURE_SIZE)
				{
					return "Some photo/photos have size/format error. Please try uploading all again.";             
				}
				elseif(array_key_exists($k,ProfilePicturesTypeEnum::$PICTURE_SIZES))
				{
					$min_size = ProfilePicturesTypeEnum::$PICTURE_SIZES_MIN_WIDTH[$k];
					$size = ProfilePicturesTypeEnum::$PICTURE_SIZES[$k];
					$aspect_ratio = $size['w']/$size['h'];
					$width = $PICTURE_SIZES[$k]["w"];
					$img = ImageCreateFromJpeg($_FILES["uploadPhotoNonScr"]["tmp_name"][$k]);
					$min_width = $min_size['w'];
					$min_height = $min_size['h'];
					$imagesX=imagesx($img);
					$imagesY=imagesy($img);
					if(floor($imagesX)>$size["w"] || floor($imagesY)>$size["h"])
						return "Err.. Profile Pic ".$size['w']." x ".$size['h']." Dimensions are incorrect";
					if($imagesX<$min_width)
					{
						return "Err.. Profile Pic ".$size['w']." x ".$size['h']." Dimensions are incorrect";
					}
					$tol=ProfilePicturesTypeEnum::$tolerance;
					if(abs($imagesX/$imagesY-$aspect_ratio)>$tol)
					{
						return "Err.. Profile Pic ".$size['w']." x ".$size['h']." Aspect Ratio of dimensions are not maintained";
					}
					
				}
			}
		}
		 return "Success";
	}
}
?>
