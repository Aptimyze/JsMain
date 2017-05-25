<?php
/**
 * ImageUploadFactory helps in return objects of imageupload/cloudimageupload 
 * 
 * @package    jeevansathi
 * @subpackage successStory
 * @author     Nikhil Dhiman
 */

 
class ImageUploadFactory
	{
	const ImageUpload=1;
	
/**
	 * return imageUpload/CloudImage Obj
	 * @param ProfileState $profileState
	 * @return Priviledge
	 */
		public static function getImageUploadObj($path,$name,$content)
		{
			if(ImageUploadFactory::ImageUpload)
				return (new ImageUpload($path,$name,$content));
			else
				return (new CloudImageUpload($path,$name,$content));
		}
	}
?>
