<?php
/**
 * JsPhotoScreen_Tracking Base Class for Other Tracking Class
 * Implementing all common methods or utility methods
 * @package Operations
 * @subpackage PhotoScreen
 * @author Kunal Verma
 * @created 19th Sept 2014
 */
/**
 * Abstract class for JsPhotoScreen_Tracking
 * 
 * @package Tracking
 * @author  Kunal Verma
 */
abstract class JsPhotoScreen_Tracking
{
	/**
	 * Declaration of Member Variables
	 */ 
	/**
	 * Profile Id of User for which screen process is running
	 * @access public
	 * @var Integer 
	 */
	public $m_iProfileId ;
	/**
	 * Profile object of User for which screen process is running
	 * @access public
	 * @var Object of Profile Class 
	 */
	 
	public $m_objProfile=null;
	/**
	 * Old have photo status of profile
	 * @access public
	 * @var String 
	 */
	public $m_szOld_HavePhoto_Status='';
	
	/**
	 * New have photo status of profile
	 * @access public
	 * @var String 
	 */
	public $m_szNew_HavePhoto_Status='N';
	/**
	 * Count of Non Screened Pics
	 * @access public
	 * @var Integer 
	 */
	public $m_iCount_NonScreenedPics=0;

	/**
	 * Count of Screened Pics
	 * @access public
	 * @var Integer 
	 */
	public $m_iCount_ScreenedPics=0;

	/**
	 * Profile Pic is Screened or not
	 * @access public
	 * @var Boolean 
	 */
	public $m_bIsProfilePicScreened='false';

	/**
	 * Photo Screened : Final status of screening 
	 * possible value 1 or 0
	 * @access public
	 * @var Integer 
	 */
	public $m_iPhotoScreened = 0;

	/**
	 * Album exist for profile which screening process is running
	 * @access public
	 * @var Boolean 
	 */
	public $m_bAlbumExist = false;
	
	/**
	 * Number of Approved Pic
	 * @access public
	 * @var Integer
	 */
	public $m_iNum_ApprovedPic;
	
	/**
	 * Number of Deleted Pic
	 * @access public
	 * @var Integer
	 */
	public $m_iNum_DeletePic;
	
	/**
	 * Number Marked For Editing Interface
	 * @access public
	 * @var Integer
	 */
	public $m_iNumPics_MarkedForEditing;
	
	/**
	 * Status used to store in Main_ADMIN_LOG : such as Approved-1, Deleted -8
	 * @access public
	 * @var String
	 */
	public $m_szFinalStatus_Screening;

	/**
	 * Name of exective user
	 * @access public
	 * @var String
	 */	
	public $m_szExecutiveUser ='';
		
	/**
	 * Interface , which denotes the calling operation or interface
	 * @access public
	 * @var Enum
	 */ 
	public $m_enInterface;
	
        /**
	 * RunMasterLogs : If provided in arrParams[MASTER_TRACK_NEEDED], then use that value(true/false)
         * else set true as default
	 * @access public
	 * @var Boolean
	 */ 
	public $m_bRunMasterLogs;
	
	/**
	* Time of PhotoUploadDate
	*/
	public $m_timePhotoUploadDate;
	/**
	* Declaring and Defining Member Function
	*/

	/**
	 * Constructor
	 * @access public
	 * @param $iPid : Profile Id 
	 */
	public function __construct($arrParams)
	{
		$this->m_iProfileId 				= $arrParams['PROFILEID'];
		$this->m_iNum_ApprovedPic 			= $arrParams['NUM_APPROVED_PIC'];
		$this->m_iNum_DeletePic 			= $arrParams['NUM_DELETED_PIC'];
		$this->m_iNumPics_MarkedForEditing	= $arrParams['NUM_EDIT_PIC'];
		$this->m_szExecutiveUser			= $arrParams['EXECUTIVE_NAME'];
		$this->m_szFinalStatus_Screening 	= $arrParams['STATUS_MSG'];
		$this->m_enInterface				= $arrParams['INTERFACE'];
        $this->m_bRunMasterLogs             = (strlen($arrParams['MASTER_TRACK_NEEDED']) && isset($arrParams['MASTER_TRACK_NEEDED']))?$arrParams['MASTER_TRACK_NEEDED']:true;
        
        $this->m_timePhotoUploadDate 		= $arrParams['PHOTO_UPLOAD_TIME'];
		//InitVariables
		$this->initVariables();
		//Check Duplication
		$this->checkDuplication();
		//Bake Screening Status
		$this->bakeScreeningStatus();
	}
	
	/**
	 * Abstract method : Need to be implemented by child class
	 * getReceiveTime : Used to calculate receive time of profile as per source
	 * Used in JsAdmin Stores for further tracking
	 * @access abstract public
	 */
	abstract public function getReceiveTime();

	/**
	 * trackThis :Main Method For Running all the tracking logic
	 * Common Tracking can be included which will called by default in every source, Like Master Tracking
	 * @access public
	 * @return void
	 */	
	public function trackThis()
	{
		//Master Tracking 
		$this->doMasterTracking();
	}
	
	
	/**
	 * initVariables
	 * Initalize all member varaibles
	 * @access public
	 * @return void
	 */	
	public function initVariables()
	{
		$this->m_objProfile = Operator::getInstance('newjs_master',$this->m_iProfileId);
		$arrDetail = $this->m_objProfile->getDetail("","","HAVEPHOTO,ACTIVATED,PHOTODATE,CASTE,MTONGUE");
		$this->m_szOld_HavePhoto_Status = $arrDetail['HAVEPHOTO'];
		
		$pictureServiceObj=new PictureService($this->m_objProfile);
		$arrAlbum = $pictureServiceObj->getAlbum();
	
		$this->m_bAlbumExist = false;
		if($arrAlbum)
			$this->m_bAlbumExist = true;
		if($this->m_bAlbumExist)
		{
			foreach((array)$arrAlbum as $objPic)
			{
				if(JsPhotoScreen_Enum::$enSCREENED_PIC == $objPic->getPictureType())
				{
					if($this->isProfilePic($objPic))
						$this->m_bIsProfilePicScreened = true;

					++$this->m_iCount_ScreenedPics;
				}
				elseif(JsPhotoScreen_Enum::$enUNSCREENED_PIC == $objPic->getPictureType())
				{
					if($this->isProfilePic($objPic))
						$this->m_bIsProfilePicScreened = false;
						
					++$this->m_iCount_NonScreenedPics;
				}
			}
		}
	}
	
	/**
	 * isProfilePic
	 * Helper function to check given Picture object is Main Profile Pic or not
	 * @access public
	 * @param Picture Object
	 * @return Boolean 
	 */	
	public function isProfilePic($objPicture)
	{
		return ($objPicture->getOrdering() === 0);
	}
	
	/**
	 * isAlbumExist()
	 * Album Exist for profile object m_objProlfile
	 * @access public
	 * @param void
	 * @return Boolean 
	 */		
	public function isAlbumExist()
	{
		return $this->m_bAlbumExist;
	}

	/**
	 * checkDuplication
	 * Check Duplication on 'photos' on For New User
	 * @access public
	 * @param void
	 * @return void 
	 */	
	public function checkDuplication()
	{
		if($this->m_objProfile->getACTIVATED()!='U'/* NOT UNDER SCREENING*/)
		{
			$duplicates_DUPLICATE_CHECKS_FIELDS =  new duplicates_DUPLICATE_CHECKS_FIELDS;
			$res=$duplicates_DUPLICATE_CHECKS_FIELDS->get_from_duplication_check_fields($this->m_iProfileId);
			if($res[TYPE]!='NEW')
			{
				if($res)
					$val=$res[FIELDS_TO_BE_CHECKED];
				else
					$val=0;
				$field = "photos";
				$val=Flag::setFlag($field,$val,'duplicationFieldsVal');
				$duplicates_DUPLICATE_CHECKS_FIELDS->insertRetrievedEntry($this->m_objProfile,'edit',$val);
			}
		}
	}
	
	/**
	 * updateProfileInfo
	 * Function for Updating Profile info
	 * @access public
	 * @param Array of fields 
	 * @return Boolean 
	 */	
	public function updateProfileInfo($arrUpdateValue)
	{
		if($this->m_objProfile == null && !is_array($arrUpdateValue))
		{
			return false;
		}
		if(($this->m_objProfile->getACTIVATED() == "U" || $this->m_objProfile->getACTIVATED() == "N") && $this->m_szOld_HavePhoto_Status == "U" && $arrUpdateValue["HAVEPHOTO"] == "Y")
		{
                        $pid = $this->m_objProfile->getPROFILEID();
                        $city_res = $this->m_objProfile->getCITY_RES();
                        $subscription = $this->m_objProfile->getSUBSCRIPTION();
                        include_once (JsConstants::$docRoot."/jsadmin/ap_common.php");
                        include_once (JsConstants::$docRoot."/profile/connect_db.php");
                        include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
                        $db = connect_db();
                        makeProfileLive($pid, $city_res, $subscription, 1);
                        include_once(JsConstants::$docRoot."/profile/InstantSMS.php");
                        $sms=new InstantSMS("PROFILE_APPROVE",$pid);
                        $sms->send();
                        $sms=new InstantSMS("DETAIL_CONFIRM",$pid);
                        $sms->send();
                        $sms=new InstantSMS("MTONGUE_CONFIRM",$pid);
                        $sms->send();
                        try
                        {
                                $producerObj=new Producer();
                                if($producerObj->getRabbitMQServerConnected())
                                {
                                        $sendMailData = array('process' => MessageQueues::SCREENING_Q_EOI, 'data' => array('type' => 'SCREENING','body' => array('profileId' => $pid)), 'redeliveryCount' => 0);
                                        $producerObj->sendMessage($sendMailData);
                                }
                        }
                        catch(Exception $e) {
                        }
                        if($this->m_objProfile->getSCREENING() < 1099511627775){
                            $jsadminObj = new JSADMIN_ACTIVATED_WITHOUT_YOURINFO();
                            $jsadminObj->insert($this->m_objProfile->getPROFILEID());
                        }
			unset($jsadminObj);
			$arrUpdateValue["ACTIVATED"] = "Y";
			$arrUpdateValue["INCOMPLETE"] = "N";
                        $arrUpdateValue["VERIFY_ACTIVATED_DT"]=date("Y-m-d H:i:s");
			CommonFunction::sendWelcomeMailer($this->m_objProfile->getPROFILEID(),0);
		}
		$objPhotoScreeningService = new photoScreeningService($this->m_objProfile);
		if($objPhotoScreeningService->isProfileScreened() == 0)
		{
			return false;
		}
		$this->m_objProfile->edit($arrUpdateValue);
		return true;	
	}
	
	/**
	 * updateFirstPhotEntry
	 * Function for Updating First Photo Entry
	 * @access public
	 * @param void
	 * @return void
	 * @throws jsException, If Null ProfileId is provided
	 */	
	public function updateFirstPhotEntry()
	{
		if(!$this->m_iProfileId)
			throw new jsException("","Profile ID null exist in update first photo entry");
			
		$objPhotoFirst = new PHOTO_FIRST();
		$objPhotoFirst->newPhotoEntry($this->m_iProfileId);
		unset($objPhotoFirst);
	}
	
	/**
	 * updatePhotoRequest
	 * Function for Updating Photo Request Entry in shard db
	 * @access public
	 * @param void
	 * @return void
	 */	
	public function updatePhotoRequest()
	{
		$objPhotoRequest=new NEWJS_PHOTO_REQUEST('shard1_master');
		$objPhotoRequest->updateUploadSeen($this->m_iProfileId);
		$objPhotoRequest=new NEWJS_PHOTO_REQUEST('shard2_master');
		$objPhotoRequest->updateUploadSeen($this->m_iProfileId);
		$objPhotoRequest=new NEWJS_PHOTO_REQUEST('shard3_master');
		$objPhotoRequest->updateUploadSeen($this->m_iProfileId);
		unset($objPhotoRequest);
	}
	
	/**
	 * getter For m_szNew_HavePhoto_Status
	 * @access public
	 * @param void
	 * @return String
	 */	
	public function getNew_HavePhoto_Status()
	{
		return $this->m_szNew_HavePhoto_Status;
	}
	
	/**
	 * getter For m_szOld_HavePhoto_Status
	 * @access public
	 * @param void
	 * @return String
	 */	
	public function getOld_HavePhoto_Status()
	{
		return $this->m_szOld_HavePhoto_Status;
	}
	
	/**
	 * getter For m_iPhotoScreened
	 * @access public
	 * @param void
	 * @return Integer
	 */	
	public function getPhotoScreen_Status()
	{
		return $this->m_iPhotoScreened;
	}
	
	public function setPhotoScreen_Status($enPhotoScreenStatus)
	{
		$this->m_iPhotoScreened = $enPhotoScreenStatus;
	}
	/**
	 * setter For m_szNew_HavePhoto_Status
	 * @access public
	 * @param enStatus : Status as per declared enum in JsPhotoScreen_Enum
	 * @return void
	 */	
	public function setNew_HavePhoto_Status($enStatus)
	{
		$this->m_szNew_HavePhoto_Status = $enStatus;
	}
	
	/**
	 * setter For m_szOld_HavePhoto_Status
	 * @access public
	 * @param enStatus : Status as per declared enum in JsPhotoScreen_Enum
	 * @return void
	 */	
	public function setOld_HavePhoto_Status($enStatus)
	{
		$this->m_szOld_HavePhoto_Status = $enStatus;
	}
	
	/**
	 * updateMain_Admin_Log : Common Logic for updating MAIN_ADMIN_LOG Store
	 * @access public
	 * @param $szStatus : String representation of Status provided by callee function
	 * @param $enSource : String representation of source as per JsPhotoScreen_Enum
	 * @return Time : Receive Time
	 * @throws jsException 
	 */	
	public function updateMain_Admin_Log($szStatus,$enSource)
	{
		$rec_time = $this->getReceiveTime();
		//Call only if count of ApprovedPIC or DeletePIC exist
		if($rec_time && $szStatus && $enSource)
		{
			$date_time=(int)explode(" ",$rec_time);
			
			$date_y_m_d=(int)explode("-",$date_time[0]);
			$time_h_m_s=(int)explode(":",$date_time[1]);
			
			$timestamp=mktime($time_h_m_s[0],$time_h_m_s[1],$time_h_m_s[2],$date_y_m_d[1],$date_y_m_d[2],$date_y_m_d[0]);
			
			$timezone=date("T",$timestamp);
			if($timezone=="EDT")
				$timezone="EST5EDT";
			
			$objMain_Admin_Log = new MAIN_ADMIN_LOG();
			$objMain_Admin_Log->logPhotoScreeningAction($this->m_iProfileId,$szStatus,$timezone,$enSource);
			return $rec_time;
		}
		else
		{
			throw new jsException("","Something missing in updateMain_Admin_Log() of JsPhotoScreen_Tracking.class.php");
		}
	}
	
	/**
	 * updateScreen_Efficiency : Screen_Efficiency
	 * @access public
	 * @param $szStatus : String representation of Status provided by callee function
	 * @param $enSource : String representation of source as per JsPhotoScreen_Enum
	 * @param $enSource : String representation of source2 as provided in case of szTRACK_SOURCE_MAIL
	 * @param $szRec_time : Receive Time 
	 * @return void
	 */		
	public function updateScreen_Efficiency($enSource,$szRec_time,$enSource2='')
	{
		//If Screening is not complete then do not update screen efficency
		if(!$this->isPhotoScreeningComplete())
			return;
			
		$objScreenEfficiency = new SCREEN_EFFICIENCY();
		$objScreenEfficiency->updateScreenedProfilesCount($enSource,$enSource2,$szRec_time);
	}
	
	public function isScoreUpdateRequired()
	{
		return ($this->getNew_HavePhoto_Status() != $this->getNew_HavePhoto_Status());
	}
	
	public function updateIncentive()
	{
		if($this->isScoreUpdateRequired())
		{
			$scoreObj = new UpdateScore();
			$score = $scoreObj->update_score($this->m_iProfileId);
			$main_admin_pool = new incentive_MAIN_ADMIN_POOL();
			$main_admin_pool->updateScore($this->m_iProfileId,$score);
		}
	}
	
	/**
	 * isPhotoExist : Return True if Status change from 
	 * enHAVE_PHOTO_UNDER_SCREEN to enHAVE_PHOTO_YES otherwise reture false
	 * @access public
	 * @return Boolean
	 */	
	public function isPhotoExist()
	{
		return ($this->getOld_HavePhoto_Status() == JsPhotoScreen_Enum::enHAVE_PHOTO_UNDER_SCREEN && $this->getNew_HavePhoto_Status() == JsPhotoScreen_Enum::enHAVE_PHOTO_YES);
	}
	
	/**
	 * isPhotoScreeningComplete
	 *Return true only if new Have Photo Status is not under screening 
	 */
	public function isPhotoScreeningComplete()
	{
		$objPhotoScreeningService = new photoScreeningService($this->m_objProfile);
			
		$arrAllowedHavePhotoStatus = array(JsPhotoScreen_Enum::enHAVE_PHOTO_YES,JsPhotoScreen_Enum::enHAVE_PHOTO_NO);
		return (in_array($this->getNew_HavePhoto_Status(),$arrAllowedHavePhotoStatus) && $this->getPhotoScreen_Status() == JsPhotoScreen_Enum::PHOTO_SCREEN_STATUS_COMPLETE && $objPhotoScreeningService->isProfileScreened());
	}
	/**
	 * trackWrongScreeningEntries : If Something went wrong in screening enteries then call this method
	 * @access public
	 * @return void
	 */	
	public function trackWrongScreeningEntries()
	{
		$obj = new INVALID_SCREENING_ENTRIES_TRACKING();
		$obj->trackInvalidScreeningEntries($this->m_iProfileId);
	}
	
	/**
	 * bakeScreeningStatus : Create Screening status, as per given count of approved, edit, & delete pics
	 * @access public
	 * @return Screen Status
	 */	
	public function bakeScreeningStatus()
	{
		if($this->m_iNum_ApprovedPic)
		{
			$this->m_szFinalStatus_Screening .= " APPROVED - $this->m_iNum_ApprovedPic ";
		}
		
		if($this->m_iNumPics_MarkedForEditing)
		{
			$this->m_szFinalStatus_Screening .= " EDITING - $this->m_iNumPics_MarkedForEditing ";
		}
		
		if($this->m_iNum_DeletePic)
		{
			$this->m_szFinalStatus_Screening .= " DELETED - $this->m_iNum_DeletePic ";
		}
		return $this->m_szFinalStatus_Screening;
	}
	
	/**
	 * doMasterTracking 
	 * This function tracks the queue completion time 
	 * @access public
	 * @return Screen Status
	 */	
	public function doMasterTracking()
	{
                if(!$this->m_bRunMasterLogs)
                    return ;    
                
		$cProfileType = strtoupper(substr($this->m_enCurrentSource,0,1));
		$objMasterTrack = new JsPhotoScreen_Track_MasterLogs($this->m_iProfileId,$this->m_enInterface,$cProfileType,$this->m_timePhotoUploadDate);
		if(!$this->m_iNumPics_MarkedForEditing)
		{
			$objMasterTrack->setProcessComplete(true);
		}
		else//As edit pic exist then mark it as incomplete
		{
			$objMasterTrack->setProcessComplete(false);
		}
		$objMasterTrack->trackThis();
	}
}
?>
