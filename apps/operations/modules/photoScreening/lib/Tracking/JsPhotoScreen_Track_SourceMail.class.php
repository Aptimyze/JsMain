<?php
/**
 * JsPhotoScreen_Track_SourceMail Class
 * Tracks source mail for PhotoScreen Module
 * @package Operations
 * @subpackage PhotoScreen
 * @author Kunal Verma
 * @created 23th Sept 2014
 */
/**
 * JsPhotoScreen_Track_SourceNew
 * This class inherits "JsPhotoScreen_Tracking" class and implements its abstrack methods
 * 
 */
class JsPhotoScreen_Track_SourceMail extends JsPhotoScreen_Tracking
{
	/**
	 * Declaration of Member Variables
	 */ 
	/**
	 * Current Source : Value must be from JsPhotoScreen_Enum::szTRACK_SOURCE_
	 * @access private
	 * @var Enum 
	 */
	private $m_enCurrentSource = JsPhotoScreen_Enum::szTRACK_SOURCE_MAIL;
	
	/**
	 * Number of Approved Pic from Mail
	 * @access private
	 * @var Integer
	 */
	private $m_iNum_Mail_ApprovedPic;
	
	/**
	 * Number of Deleted Pic from Mail
	 * @access private
	 * @var Integer
	 */
	private $m_iNum_Mail_DeletePic;
	
	/**
	 * Mail Source which could be new/edit/null
	 * @access private
	 * @var String
	 */
	private $m_szMailSource;
	
	/**
	 * Email Id : From which mail request has been sent
	 * @access private
	 * @var String
	 */
	private $m_szEmailId;
		
	/**
	 * Constructor
	 * @access public
	 * @param $arrParams : Array of paramters 
	 * which can have keys only as specified in JsPhotoScreen_Enum::$arrTRACKING_PARAMS
	 */	
	public function __construct($arrParams)
	{
		//Calling Parent constructor
		parent::__construct($arrParams);
			
		$this->m_iNum_Mail_ApprovedPic 		= $arrParams['NUM_MAIL_APPROVED_PIC'];
		$this->m_iNum_Mail_DeletePic 		= $arrParams['NUM_MAIL_DELETE_PIC'];
		
		$this->m_szEmailId					= $arrParams['EMAIL_ID'];
		$this->m_szMailSource 				= $arrParams['SECOND_SOURCE'];
	}
		/**
	 * initVariables
	 * Initalize all member varaibles
	 * @access public
	 * @return void
	 */	
	public function initVariables()
	{//TODO : Need to compare with old code
		//Call parent initVariables
		parent::initVariables();
		//Init Some variable as per New Source
		$this->m_szNew_HavePhoto_Status = 'N';
		$this->m_iPhotoScreened = 0;
		if($this->m_bAlbumExist)
		{
			if($this->m_iCount_NonScreenedPics && $this->m_iCount_ScreenedPics)
			{
				$this->m_szNew_HavePhoto_Status = 'U';
				if($this->m_bIsProfilePicScreened)
					$this->m_szNew_HavePhoto_Status = 'Y';

				$this->m_iPhotoScreened = 0;
			}
			else
			if($this->m_iCount_ScreenedPics)
			{
				$this->m_szNew_HavePhoto_Status = 'Y';
				$this->m_iPhotoScreened = 1;
			}
			elseif($this->m_iCount_ScreenedPics == 0 && $this->m_iCount_NonScreenedPics == 0)
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
		//Do tracking for JsPhotoScreen_Enum::enTRACK_SOURCE_MAIL
		parent::trackThis();
		
		//Profile Information tracking
		$this->trackProfileInformation();
		
		//JS Admin Tracking
		$timeReceive = $this->trackJsAdmin();
		
		//Track MIS
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
									"PHOTOSCREEN"=>$this->getPhotoScreen_Status(),
									"PHOTODATE"=>date("Y-m-d H:i:s"),
									);
			
			if($this->m_iNum_ApprovedPic)
			{
				$arrUpdateParam["SORT_DT"] = date('Y-m-d H:i:s',time());
			}
			
			$this->updateProfileInfo($arrUpdateParam);	
			//Update First Photo Entry
			if($this->isPhotoExist())
			{
				$this->updateFirstPhotEntry();
			}							
		}
		else//Album does not exist
		{
			if($this->m_iNum_ApprovedPic && in_array($this->getOld_HavePhoto_Status(),JsPhotoScreen_Enum::$enHAVE_PHOTO_NO))
			{
				$this->setNew_HavePhoto_Status(JsPhotoScreen_Enum::enHAVE_PHOTO_YES);
				$arrUpdateParam = array(
									"HAVEPHOTO"=>$this->getNew_HavePhoto_Status(),
									"PHOTOSCREEN"=>1,
									"SORT_DT"=>date('Y-m-d H:i:s',time()),
									"PHOTODATE"=>date("Y-m-d H:i:s"),
									);
				$this->updateProfileInfo($arrUpdateParam);	
				$this->updateFirstPhotEntry();				
			}
			else//if Old_HavePhotoStatus is Under Screening or Yes
			{//TODO Check New_HavePhoto_Status
				$this->updateProfileInfo(array("HAVEPHOTO"=>"N","PHOTOSCREEN"=>1));
			}
		}
		
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
		$objScreen_Photos_FromMail = new SCREEN_PHOTOS_FROM_MAIL();
		$objScreen_Photos_FromMail->logScreeningAction( $this->m_iProfileId,
														$this->m_szEmailId,
														$this->m_iNum_ApprovedPic,
														$this->m_iNum_DeletePic);
		$szRecTime= $objScreen_Photos_FromMail->getReceiveTime($this->m_iProfileId,$this->m_szEmailId);
		return $szRecTime;
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
		return $szRec_time;
	}
	
	/**
	 * updateScreenStats
	 * Method for updating screen stats
	 * @param  $secondSource come in case of mail source, whose value can be edit/new/null
	 * @access private
	 * @return void
	 */
	private function updateScreenStats($iNum_ApprovedPhoto,$iNum_DelPhoto,$iNum_Mail_ApprovedPhoto,$iNum_Mail_DelPhoto,$secondSource="")
	{
		$objPhotoScreen_Stats = new PHOTO_SCREEN_STATS();
		$objPhotoScreen_Stats->updateScreenedPhotoCountMail($this->m_szExecutiveUser,$secondSource,$iNum_Mail_ApprovedPhoto,$iNum_ApprovedPhoto,$iNum_Mail_DelPhoto,$iNum_DelPhoto);
		unset($objPhotoScreen_Stats);
	}
	
	/**
	 * trackMIS
	 * Main method for tracking, and updating all store related to MIS 
	 * @access private
	 * @return void
	 */
	private function trackMIS($timeReceive)
	{
		$this->updateScreenStats($this->m_iNum_ApprovedPic,$this->m_iNum_DeletePic,$this->m_iNum_Mail_ApprovedPic,$this->m_iNum_Mail_DeletePic,$this->m_szMailSource);

		$this->updateScreen_Efficiency($this->m_enCurrentSource,$timeReceive,$this->m_szMailSource);
	}
}
?>
