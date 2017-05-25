<?php

/***************************************************************************************************************
* FILE NAME     : 
* DESCRIPTION   : 
* CREATION DATE : 
* CREATED BY    : Rohit Khandelwal
* Copyright  2005, InfoEdge India Pvt. Ltd.
*****************************************************************************************************************/
/**
 * class SuccessCommon
 * 
 */
class SuccessCommon
{

	public static function rejectStory($id)
	{	
		$SuccessStoryDbObj = new NEWJS_SUCCESS_STORIES();
		$SuccessStoryDbObj->updateUploaded('D',$id);
	}
	public function removeStory($sid,$id)
	{
		$SuccessStoryDbObj = new NEWJS_SUCCESS_STORIES();
		$IndividualStoryDbObj = new newjs_INDIVIDUAL_STORIES();
		$IndividualStoryDbObj->updateStatus('R',$sid);
		$SuccessStoryDbObj->updateUploaded('R',$id);
	}
	public static function checkUploadedImage($fileName,$where,$id)
	{
		if(CommonUtility::UploadImageCheck("frame"))
		{
			$filepath=$docRoot."/web/uploads/$where/successStory/$id.jpg";
			$file = fopen($filepath,"w");
			if($file)
			{							
				return $filepath;
			}
		}
		return false;
	}
	public static function UpdatePicUrl($sObj)
	{
			$imageId=$sObj->getID()."S".".jpg";
			if($_FILES["fullphoto"]["tmp_name"])
			{
					$fp = fopen($_FILES["fullphoto"]["tmp_name"],"rb") or $flag_error=1;
					$fcontent = fread($fp,filesize($_FILES["fullphoto"]["tmp_name"]));
					fclose($fp);
					$imageObj=ImageUploadFactory::getImageUploadObj("/uploads/ScreenedImages/story/",$imageId,$fcontent);
					$imagepath=$imageObj->UploadImage();
					//$imagepath=CommonUtility::UploadPic($imageId,"NonScreenedImages",$fcontent);
					if($imagepath)
					{
							$sObj->setPIC_URL($imagepath);

							$sObj->UpdateRecord();

					}

			}
	}
    public static function UpdateIndividualPicUrl($iObj)
	{
			$mainPicId=$iObj->getSID()."M".".jpg";
			if($_FILES["fullphoto"]["tmp_name"])
			{
					$fp = fopen($_FILES["fullphoto"]["tmp_name"],"rb") or $flag_error=1;
					$fcontent = fread($fp,filesize($_FILES["fullphoto"]["tmp_name"]));
					fclose($fp);
					$imageObj=ImageUploadFactory::getImageUploadObj("/uploads/ScreenedImages/story/",$mainPicId,$fcontent);
					$imagepath=$imageObj->UploadImage();
					if($imagepath)
					{
							$iObj->setMAIN_PIC_URL($imagepath);
					}

			}
			$framePicId=$iObj->getSID()."F".".jpg";
			if($_FILES["frame"]["tmp_name"])
			{
					$fp = fopen($_FILES["frame"]["tmp_name"],"rb") or $flag_error=1;
					$fcontent = fread($fp,filesize($_FILES["frame"]["tmp_name"]));
					fclose($fp);
					$imageObj=ImageUploadFactory::getImageUploadObj("/uploads/ScreenedImages/story/",$framePicId,$fcontent);
					$imagepath=$imageObj->UploadImage();
					if($imagepath)
					{
							$iObj->setFRAME_PIC_URL($imagepath);

					}

			}
			$homePicId=$iObj->getSID()."H".".jpg";
			if($_FILES["homephoto"]["tmp_name"])
			{
					$fp = fopen($_FILES["homephoto"]["tmp_name"],"rb") or $flag_error=1;
					$fcontent = fread($fp,filesize($_FILES["homephoto"]["tmp_name"]));
					fclose($fp);
					$imageObj=ImageUploadFactory::getImageUploadObj("/uploads/ScreenedImages/story/",$homePicId,$fcontent);
					$imagepath=$imageObj->UploadImage();
					if($imagepath)
					{
							$iObj->setHOME_PIC_URL($imagepath);
					}

			}
			$iObj->UpdateRecord();
	}
}
?>
