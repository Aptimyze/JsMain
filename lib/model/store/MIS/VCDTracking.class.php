<?php

class VCDTracking{
	
	public static function insertTracking($contactHandlerObj)
	{
		$channel = MobileCommon::getChannel();
		$viewed = $contactHandlerObj->getViewed()->getPROFILEID();
		$viewer = $contactHandlerObj->getViewer()->getPROFILEID();
		$type   = $contactHandlerObj->getContactObj()->getTYPE();
		$viewed_sub    = $contactHandlerObj->getViewed()->getPROFILE_STATE()->getPaymentStates()->getPaymentStatus();
		$viewer_sub    = $contactHandlerObj->getViewer()->getPROFILE_STATE()->getPaymentStates()->getPaymentStatus();
		$VcdTrackingDbObj = new MIS_VCD_TRACKING();
		$id = $VcdTrackingDbObj->insertTracking($viewer,$viewed,$channel,$type,$viewed_sub,$viewer_sub);
		return $id;
	}	



	public static function insertYesNoTracking($contactHandlerObj,$contactShown)
	{
		$channel = MobileCommon::getChannel();
		$viewed = $contactHandlerObj->getViewed()->getPROFILEID();
		$viewer = $contactHandlerObj->getViewer()->getPROFILEID();
		$type   = $contactHandlerObj->getContactObj()->getTYPE();
		$viewed_sub    = $contactHandlerObj->getViewed()->getPROFILE_STATE()->getPaymentStates()->getPaymentStatus();
		$viewer_sub    = $contactHandlerObj->getViewer()->getPROFILE_STATE()->getPaymentStates()->getPaymentStatus();
		$VcdTrackingDbObj = new MIS_VCD_YES_NO_TRACKING();
		$id = $VcdTrackingDbObj->insertTracking($viewer,$viewed,$channel,$type,$viewed_sub,$viewer_sub,$contactShown);
		return $id;
	}	
}
