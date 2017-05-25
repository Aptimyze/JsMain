<?php
/**
 * JsPhotoScreen_Track_SourceEdit Class
 * Tracks source edit for PhotoScreen Module
 * @package Operations
 * @subpackage PhotoScreen
 * @author Kunal Verma
 * @created 22th Sept 2014
 */
/**
 * JsPhotoScreen_Track_SourceNew
 * This class inherits "JsPhotoScreen_Tracking" class and implements its abstrack methods
 * 
 */
class JsPhotoScreen_Track_SourceEdit extends JsPhotoScreen_Tracking
{
	/**
	 * Declaration of Member Variables
	 */ 
	/**
	 * Current Source : Value must be from JsPhotoScreen_Enum::szTRACK_SOURCE_
	 * @access public
	 * @var Enum 
	 */
	public $m_enCurrentSource = JsPhotoScreen_Enum::szTRACK_SOURCE_EDIT;

	/**
	 * Constructor
	 * @access public
	 * @param $arrParams : Array of paramters 
	 * which can have keys only as specified in JsPhotoScreen_Enum::$arrTRACKING_PARAMS
	 */	
	public function __construct($arrParams)
	{
		//Call Parent Ctor
		parent::__construct($arrParams);
	}
		/**
	 * initVariables
	 * Initalize all member varaibles
	 * @access public
	 * @return void
	 */	
	public function initVariables()
	{
		//Call parent initVariables
		parent::initVariables();
		//Init Some variable as per Edit Source
		$this->m_szNew_HavePhoto_Status = $this->getOld_HavePhoto_Status();
		$this->m_iPhotoScreened = 0;
		if($this->m_bAlbumExist)
		{
			if($this->m_iCount_NonScreenedPics && $this->m_iCount_ScreenedPics)
			{
				if($this->m_bIsProfilePicScreened)
				{
					$this->m_szNew_HavePhoto_Status = 'Y';
				}
				$this->m_iPhotoScreened = 0;
			}
			else if($this->m_iCount_ScreenedPics)
			{
				$this->m_szNew_HavePhoto_Status = 'Y';
				$this->m_iPhotoScreened = 1;
			}
			else if($this->m_iCount_ScreenedPics == 0 && $this->m_iCount_NonScreenedPics == 0)
			{
				$this->m_szNew_HavePhoto_Status = 'N';
				$this->m_iPhotoScreened = 1;
			}
		}
	}
	/**
	 * trackThis :Main Method For Running all the tracking logic
	 * Common Tracking can be included which will called by default in every source 
	 * by calling parent::trackThis()
	 * @access public
	 * @return void
	 */	
	public function trackThis()
	{
	/**
		*Do tracking for JsPhotoScreen_Enum::enTRACK_SOURCE_NEW
		*/

		/**
		 *Common Tracking
		 */ 
		parent::trackThis();
		
		/**
		 * Profile Information tracking and updation, which are as follows 
		 * 1) Have Photo, Screen Status, Sort_Dt, Photo_Date in JPROFILE Store
		 * 2) Update Photo Request Entry in Shards
		 */
		$this->trackProfileInformation();
		
		/**
		 * JS Admin Tracking 
		 * 1) Update Main_Admin_Log Store 
		 * 2) Delete Entry from Main_Admin Store
		 */
		$timeReceive = $this->trackJsAdmin();
		
		/**
		 * MIS Tracking 
		 * 1) Update Screen Status 
		 * 2) Update Screen Efficiency
		 */
		$this->trackMIS($timeReceive);
	}
	
	/**
	 * trackProfileInformation
	 * Main method for tracking, and updating all profile related informations
	 * @access private
	 * @return void
	 */	
	private function trackProfileInformation()
	{
		//If Album Exist then update profile info
		if($this->isAlbumExist())
		{	
			$arrUpdateParam = array("HAVEPHOTO"=>$this->getNew_HavePhoto_Status(),
									"PHOTOSCREEN"=>$this->getPhotoScreen_Status());
			if($this->m_iNum_ApprovedPic>0)
			{
				//Update SORT_DT IF number of approved pic exist 
				$arrUpdateParam["SORT_DT"] = date('Y-m-d H:i:s',time());
			}
			if(!$this->m_iNumPics_MarkedForEditing)//Update only if Number of Edit Pics is zero
			{
				$this->updateProfileInfo($arrUpdateParam);		
			}		
		}
		else//Album does not exist
		{
			if($this->m_iNumPics_MarkedForEditing)
				return ;
				
			if($this->m_iNum_ApprovedPic && in_array($this->getOld_HavePhoto_Status(),JsPhotoScreen_Enum::$enHAVE_PHOTO_NO))
			{
				$this->setNew_HavePhoto_Status(JsPhotoScreen_Enum::enHAVE_PHOTO_YES);
				$this->setPhotoScreen_Status(JsPhotoScreen_Enum::PHOTO_SCREEN_STATUS_COMPLETE);
				$arrUpdateParam = array(
									"HAVEPHOTO"=>$this->getNew_HavePhoto_Status(),
									"PHOTOSCREEN"=>$this->getPhotoScreen_Status(),
									"SORT_DT"=>date('Y-m-d H:i:s',time()),
									"PHOTODATE"=>date("Y-m-d H:i:s"),
									);
		
			}
			else if($this->m_iNum_DeletePic && !$this->m_iNum_ApprovedPic);
			{
				$this->setNew_HavePhoto_Status(JsPhotoScreen_Enum::enHAVE_PHOTO_NO);
				$this->setPhotoScreen_Status(JsPhotoScreen_Enum::PHOTO_SCREEN_STATUS_COMPLETE);
				$arrUpdateParam = array(
										"HAVEPHOTO"=>$this->getNew_HavePhoto_Status(),
										"PHOTOSCREEN"=>$this->getPhotoScreen_Status(),
										);
			}
			if(is_array($arrUpdateParam) && count($arrUpdateParam))
			{	
				$this->updateProfileInfo($arrUpdateParam);	
			}
		}
		//Update Photo Request If Screening is Complete
		if($this->isPhotoExist())
		{
			$this->updatePhotoRequest();
		}
	}
	
	/**
	 * Abstract method : Need to be implemented 
	 * updateMIS function required this function or method
	 * @access public
	 */
	public function getReceiveTime()
	{
		$this->objAdmin_Store = new MAIN_ADMIN();
		return $this->objAdmin_Store->getReceiveTime($this->m_iProfileId);
	}
	
	/**
	 * trackJsAdmin
	 * Main method for tracking, and updating all store in JsAdmin Db
	 * @access private
	 * @return Recieve Time as return by getReceiveTime function
	 */	
	private function trackJsAdmin()
	{
		$szRec_time = $this->updateMain_Admin_Log($this->m_szFinalStatus_Screening,$this->m_enCurrentSource);
		$this->deleteMainAdminLogEntry();
		return $szRec_time;
	}
	
	/**
	 * deleteMainAdminLogScreening
	 * Helper method of deleting entery in Main_AdminLog
	 * @access private
	 * @return void
	 */	
	private function deleteMainAdminLogEntry()
	{
		if($this->objAdmin_Store)
		{
			$this->objAdmin_Store->deleteEntryAfterScreening($this->m_iProfileId);
		}
	}
	
	/**
	 * updateScreenStats
	 * Method for updating screen stats
	 * @access private
	 * @return void
	 */
	private function updateScreenStats($iNum_ApprovedPhoto,$iNum_DelPhoto,$iNumMarked_ForEditing,$iNum_Mail_ApprovedPhoto="",$iNum_Mail_DelPhoto="")
	{
		$objPhotoScreen_Stats = new PHOTO_SCREEN_STATS();
		if($iNum_Mail_ApprovedPhoto)
			$iNum_ApprovedPhoto += $iNum_Mail_ApprovedPhoto;
		if($iNum_Mail_DelPhoto)
			$iNum_DelPhoto 		+= $iNum_Mail_DelPhoto;		
		
		$objPhotoScreen_Stats->updateScreenedPhotoCount($this->m_szExecutiveUser,$this->m_enCurrentSource,$iNum_ApprovedPhoto,$iNum_DelPhoto,$iNumMarked_ForEditing,$this->m_enInterface);
	}
	
	/**
	 * trackMIS
	 * Main method for tracking, and updating all store related to MIS 
	 * @access private
	 * @return void
	 */
	private function trackMIS($timeReceive)
	{
		$this->updateScreenStats($this->m_iNum_ApprovedPic,$this->m_iNum_DeletePic,$this->m_iNumPics_MarkedForEditing);
		$this->updateScreen_Efficiency($this->m_enCurrentSource,$timeReceive);
	}
}
?>
