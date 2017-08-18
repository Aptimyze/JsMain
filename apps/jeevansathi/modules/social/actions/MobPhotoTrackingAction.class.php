<?php
class MobPhotoTrackingAction extends sfActions
{
	public function execute($request)
	{
		
		$PROFILEID=$request->getParameter("profileId");
		$type=$request->getParameter("trackType");
		$msg=$request->getParameter("trackInfo");
		if($type=="action")
		{
			$objPictureTrack = new PictureUploadTracking;
			$objPictureTrack->InsertPageTrack($PROFILEID,$type,$msg);
		}
		else
		{
			$objPictureTrack = new PictureUploadTracking;
			$objPictureTrack->InsertErrorMsg($PROFILEID,$type,$msg);
		}
		die;
	}
}
?>
		
		
		
