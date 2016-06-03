<?php
class PictureUploadTracking
{
	public function InsertPageTrack($profileid,$type,$msg)
	{
		$msgWhiteList = array("UPLOAD_PAGE", "ALBUM_PAGE", "RETRY");
		if($profileid && $type=="action" && $msg && in_array($msg,$msgWhiteList))
		{
			$objPicture_error_STORE 	= new PICTURECheck;
			$check=$objPicture_error_STORE->Update($profileid,$type,$msg);
			if($check!=true)
			$check=$objPicture_error_STORE->Insert($profileid,$type,$msg);
		}
	}
	public function InsertErrorMsg($profileid,$type,$msg)
	{
		if($profileid && $type && $msg)
		{
			$objPicture_errormsg_STORE 	= new PICTURE_ErrorForPictureUpload;
			$objPicture_errormsg_STORE->Insert($profileid,$type,$msg);
		}
	}
}
?>	
		


