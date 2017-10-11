<?php
class IncompleteTracking extends TrackingClass{
	
	public function track($request){
		$loginData=$request->getAttribute("loginData");
		if($request->getParameter('channel')=='INCOM_SMS')
		{
			$MIS_INCOMPLETE_SMS_OBJ=new MIS_INCOMPLETE_SMS();
			$MIS_INCOMPLETE_SMS_OBJ->insertLanding($loginData[PROFILEID]);
		}
	}
	
	//Forward the user to destination after doing its stuff
	public function forward($request){
		$loc=sfConfig::get('app_site_url')."/profile/viewprofile.php?ownview=1&channel=INCOM_SMS&EditWhatNew=incompletProfile&echecksum=03a313ff3f4750a433ad4230b117b2bd|i|108c2160ec4432c1ece46c4b9d87aadei280277753______1424851907______&checksum=108c2160ec4432c1ece46c4b9d87aadei280277753";
		header("Location:$loc");
	}
}
