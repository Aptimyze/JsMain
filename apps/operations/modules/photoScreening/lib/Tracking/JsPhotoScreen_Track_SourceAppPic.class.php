<?php
class JsPhotoScreen_Track_SourceAppPic extends JsPhotoScreen_Tracking
{
	private $m_enCurrentSource = JsPhotoScreen_Enum::szTRACK_SOURCE_APP_PIC;
	private $m_szFinalStatus_Screening;//Status used to store in Main_ADMIN_LOG : such as APP_APPROVED or APP_EDITED
	private $m_szExecutiveUser;
	
	public function __construct($arrParams)
	{
		//Ctor
		$this->m_szExecutiveUser			= $arrParams['EXECUTIVE_NAME'];
		$this->m_szFinalStatus_Screening 	= $arrParams['STATUS_MSG'];
	}
	//Override parent::trackThis() method
	public function trackThis()
	{
		//Do tracking for JsPhotoScreen_Enum::enTRACK_SOURCE_APP_PIC
		//Common Tracking
		parent::trackThis();
		
		//JS Admin Tracking
		$timeReceive = $this->trackJsAdmin();
		
		//trackMis
		$this->trackMIS($timeReceive);
	}
	
	public function getReceiveTime()
	{
		$this->m_objJsAdmin_ScreenPhotoApp = new JSADMIN_SCREEN_PHOTOS_FOR_APP;
        return $this->m_objJsAdmin_ScreenPhotoApp->getReceiveTime($this->m_iProfileId);
	}
	
	public function trackJsAdmin()
	{//TODO:: Pass parameter in updateMain_Admin_log function
		//TODO Send App Pic Status
		$szRec_time = $this->updateMain_Admin_Log($this->m_szFinalStatus_Screening,$this->m_enCurrentSource);
		$this->deleteEntryAfterScreening();
		return $szRec_time;
	}
	
	public function deleteEntryAfterScreening()
	{
		if($this->m_objJsAdmin_ScreenPhotoApp)
			$this->m_objJsAdmin_ScreenPhotoApp->deleteEntryAfterScreening($this->m_iProfileId);
	}
	
	public function updateMIS($szUser,$iNum_ApprovedPhoto,$iNum_DelPhoto)
	{
		$objPhotoScreen_Stats = new PHOTO_SCREEN_STATS();
		$source = "app_pic";
		$objPhotoScreen_Stats->updateScreenedPhotoCountMobileAppPic($szUser,$source,$iNum_Mail_ApprovedPhoto,$delPhotos);
		
		$objPhotoScreen_Stats->updateScreenedPhotoCount($szUser,$this->m_enCurrentSource,$iNum_ApprovedPhoto,$iNum_DelPhoto);		
	}
	//TODO:: Complete Function Call
	public function trackMIS($timeReceive)
	{
		//TODO : Call UpdateMIS with parameters 
		$this->updateMIS($this->m_szExecutiveUser);

		$this->updateScreen_Efficiency($this->m_enCurrentSource,$timeReceive);//TODO Pass parameters
	}
}
?>
