<?php

class EvalueTracking{
	
	public function updateTracking($viewed,$viewer,$flag,$type,$sub)
	{
		if($flag == "Y")
		{
			$page = $_SERVER["HTTP_REFERER"];
			if(strpos($page,"contacts_made_received") || strpos($_SERVER['REDIRECT_URL'],"contacts_made_received"))
				$contactPage = "Contact Center";
			else if(strpos($page,"perform"))
				$contactPage = "Search";
			else if(strpos($page,"partner"))
				$contactPage = "My Matches";
			else if(strpos($page,"simprofile"))
				$contactPage = "View Similer Profile";
			else if(strpos($page,"viewprofile"))
				$contactPage = "Profile Page";			
		}
		else 
			$contactPage = "Profile Page";
		if(MobileCommon::isMobile())
			$device = "M";
		else
			$device = "D";
		$evalueTrackingObj = new MIS_EVALUE_TRACKING();
		$id = $evalueTrackingObj->insertTracking($viewed,$viewer,$flag,$contactPage,$type,$sub,$device);
		return $id;
	}
	
	public function updateId($id)
	{
		$evalueTrackingObj = new MIS_EVALUE_TRACKING();
		$evalueTrackingObj->updateId($id);
	}	
}
