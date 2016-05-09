<?php
/**
 * JsPhotoScreen_Track_MasterLogs Class
 * Tracks all operation and its time of completion in PhoyoScreen Moduel
 * @package Operations
 * @subpackage PhotoScreen
 * @author Kunal Verma
 * @created 26th Sept 2014
 */
/**
 * JsPhotoScreen_Track_MasterLogs
 */
class JsPhotoScreen_Track_MasterLogs
{
	/**
	 * Declaration of Member Variables
	 */ 
	/**
	 * Current Operation Type : Value must be from JsPhotoScreen_Enum::PHOTO_SCREEN_MASTER_
	 * @access private
	 * @var Enum 
	 */
	private $m_enOperationType = -1; 
	
	/**
	 * PhotoScreenOperations, which call this tracking 
	 * Enum specified in PictureStaticVariablesEnum::PHOTO_SCREEN_OPERATION_*
	 * @access private
	 * @var Enum 
	 */
	private $m_enPhotoScreenOperations;
	
	/**
	 * Profile Id of User for which screen process is running
	 * @access private
	 * @var Integer 
	 */
	private $m_iProfileId;
	
	/**
	 * Photo Upload Time
	 * @access private
	 * @var String 
	 */
	private $m_timePhotoUploadDate;
	
	/**
	 * Photo preprocess completion time 
	 * Considering RESIZE_CRONE AND FACEDETECTION_CRON as Preprocess Opertions
	 * @access private
	 * @var String 
	 */
	private $m_timePreprocessCompletion;
	
	/**
	 * Accept Reject Queue Completion
	 * @access private
	 * @var String 
	 */
	private $m_time_AcceptRejectCompletion;
	
	/**
	 * Editing Process Queue Completion
	 * @access private
	 * @var String 
	 */	
	private $m_time_EditProcessCompletion;
	
	/**
	 * object of Master Tracking Store
	 * @access private
	 * @var Object
	 */		
	private $m_objStore_MasterTracking;
	
	/**
	 * SCREENING_COMPLETION_QUEUE_NAME
	 * Name of interface from which screening got completed
	 * Enum as specified in PictureStaticVariablesEnum::PIC_STATUS_*
	 * @access private
	 * @var Enum
	 */
	private $m_enScreeningCompletionQueueName;
	
	/**
	 * Status of record exist or not in store
	 * @access private
	 * @var Boolean
	 */
	private $m_bRecordExist = false;
	
	/**
	 * Profile Type as per old have photo status
	 * Its a enum value 'E' for Edit and 'N' for New
	 * @access private
	 * @var Boolean
	 */
	private $m_cProfileType;
	/**
	 * Constructor
	 * @access public
	 * @param $iPid : Profile Id 
	 * @param $timePicUpload  : Upload Time
	 * @param $enPhotoScreenOperations : Calling operation type
	 * @return void
	 */	
	public function __construct($iProfileId,$enPhotoScreenOperations,$cProfileType,$timePicUpload='')
	{
		if(!$iProfileId)
		{
			throw new jsException("","Please provide profileid for photo screen master tracking");
		}	

		if(!strlen($cProfileType))
		{
			throw new jsException("","Please provide profile type for photo screen master tracking");
		}
		
		$this->m_iProfileId 					= $iProfileId;
		$this->m_enPhotoScreenOperations 		= $enPhotoScreenOperations;
		$this->m_cProfileType					= $cProfileType;
		
		if(in_array($enPhotoScreenOperations,ProfilePicturesTypeEnum::$INTERFACE))
		{
			$this->m_enScreeningCompletionQueueName = $enPhotoScreenOperations;
		}
				
		$this->m_timePhotoUploadDate 		= $timePicUpload;
		$this->m_objStore_MasterTracking 	= new PICTURE_PHOTOSCREEN_MASTER_TRACKING;	
	
	}
	
	/**
	 * trackAcceptReject_QueueCompletion:
	 * Track accept reject queue completion time , 
	 * return true if profile id is eligible for update operation  else return false
	 * @access private
	 * @return Boolean
	 */	
	private function trackAcceptReject_QueueCompletion()
	{
		if($this->isEligibleForAcceptQueueCompletion())
		{
			$this->m_objStore_MasterTracking->updateAcceptRejectQueueCompletionTime($this->m_iProfileId,$this->m_cProfileType,$this->m_enScreeningCompletionQueueName);
			return true;
		}
		return false;
	}
	
	/**
	 * trackEditingProcess_QueueCompletion:
	 * Track editing process queue completion time , 
	 * return true if profile id is eligible for update operation else return false
	 * @access private
	 * @return Boolean
	 */
	private function trackEditingProcess_QueueCompletion()
	{
		if($this->isEligibleForEditingProcessCompletion())
		{
			$this->m_objStore_MasterTracking->updateProcessQueueCompletionTime($this->m_iProfileId,$this->m_cProfileType,$this->m_enScreeningCompletionQueueName);
			return true;
		}
		return false;
	}
	
	/**
	 * isEligibleForEditingProcessCompletion
	 * return true if profile id is eligible for update operation else return false
	 * @access private
	 * @return Boolean
	 */
	private function isEligibleForEditingProcessCompletion()
	{
		return ($this->m_enPhotoScreenOperations === ProfilePicturesTypeEnum::$INTERFACE["2"]);
	}
	
	/**
	 * isEligibleForAcceptQueueCompletion
	 * return true if profile id is eligible for update operation else return false
	 * @access private
	 * @return Boolean
	 */
	private function isEligibleForAcceptQueueCompletion()
	{
		return ($this->m_enPhotoScreenOperations === ProfilePicturesTypeEnum::$INTERFACE["1"]);
	}

	/**
	 * decideOperationType
	 * this method decides which operation either insert or update
	 * to execute on store.
	 * @access private
	 * @return void
	 */
	private function decideOperationType()
	{
		/**
		 * As per calling PhotoScreenOperation decide operation type
		 */
		$arrAllowedPreprocess = array(PictureStaticVariablesEnum::PHOTO_SCREEN_OPERATION_PREPROCESS_CRON,PictureStaticVariablesEnum::PHOTO_SCREEN_OPERATION_FACEDETECTION_CRON,ProfilePicturesTypeEnum::$INTERFACE['1'],ProfilePicturesTypeEnum::$INTERFACE['2']);
		
		if(in_array($this->m_enPhotoScreenOperations,$arrAllowedPreprocess))
		{
			$this->m_enOperationType = $this->isRecordExist()?(PictureStaticVariablesEnum::PHOTO_SCREEN_MASTER_UPDATE):(PictureStaticVariablesEnum::PHOTO_SCREEN_MASTER_INSERT);
		}
		else if($this->isRecordExist() && $this->m_enPhotoScreenOperations == ProfilePicturesTypeEnum::$INTERFACE['1']/*AR*/)
		{
			$this->m_enOperationType = PictureStaticVariablesEnum::PHOTO_SCREEN_MASTER_UPDATE;
		} 
		else if($this->isRecordExist(false) && $this->m_enPhotoScreenOperations == ProfilePicturesTypeEnum::$INTERFACE['2']/*PROCESS*/)//Dont Consider Accept Reject Time
		{
			$this->m_enOperationType = PictureStaticVariablesEnum::PHOTO_SCREEN_MASTER_UPDATE;
		}
		else
		{
			$this->m_enOperationType = -1;//Go to default case
		}
	}

	/**
	 * isRecordExist
	 * Record Exist, if record exist then fill the all value in member varibles
	 * @access private
	 * @return Boolean
	 */
	private function isRecordExist($bConsiderAcceptReject=true)
	{
		$arrData = array();
		//Check Record exist or not
		if(!$this->m_bRecordExist)
		{
			$this->m_bRecordExist = $this->m_objStore_MasterTracking->isRecordExist($this->m_iProfileId,$this->m_cProfileType,$arrData,$bConsiderAcceptReject);
			if($arrData)
			{
				$this->m_timePhotoUploadDate 			= $arrData['UPLOADDATE'];
				$this->m_timePreprocessCompletion		= $arrData['PREPROCESS_COMPLETION_TIME'];
				$this->m_time_AcceptRejectCompletion	= $arrData['ACCEPT_REJECT_Q_COMPLETION_TIME'];
				$this->m_time_EditProcessCompletion		= $arrData['PROCESS_Q_COMPLETION_TIME'];			
			}
		}		
		return $this->m_bRecordExist ;
	}
	
	/**
	 * getRecentPhotoUploadTime
	 * Get Recent PhotoUpload Time
	 * Either given in class constructor or will be calculate by existing data
	 * @access private
	 * @return time(in String)
	 */
	private function getRecentPhotoUploadTime()
	{
		if(!strlen($this->m_timePhotoUploadDate) || $this->m_timePhotoUploadDate=="0000-00-00 00:00:00" )
		{
			throw new jsException("","Photo Screen Master Log Error : Please provide photo data time");
		}
		return $this->m_timePhotoUploadDate;
	}
	
	/**
	 * getRecentPreprocessCompletionTime
	 * Get Recent Preprocess Completion 
	 * @access private
	 * @return time(in String)
	 */
	private function getRecentPreprocessCompletionTime()
	{
		//We are considering Resize_Cron_Completion_Time and Face_Detection_Completion_Time status 
		// Whichever is latest will be consider as preprocess completion time
		
		$arrUpdatePreprocessTime = array(PictureStaticVariablesEnum::PHOTO_SCREEN_OPERATION_PREPROCESS_CRON,PictureStaticVariablesEnum::PHOTO_SCREEN_OPERATION_FACEDETECTION_CRON);
		
		$arrInterface = array(ProfilePicturesTypeEnum::$INTERFACE['1'],ProfilePicturesTypeEnum::$INTERFACE['2']);
		if(in_array($this->m_enPhotoScreenOperations,$arrUpdatePreprocessTime))
		{
			$this->m_timePreprocessCompletion = date("Y-m-d H:i:s");
		}
		else if(in_array($this->m_enPhotoScreenOperations,$arrInterface) && $this->m_enOperationType === PictureStaticVariablesEnum::PHOTO_SCREEN_MASTER_INSERT)/*PATCH*/
		{
			$this->m_timePreprocessCompletion = $this->m_timePhotoUploadDate;
			if($this->m_enPhotoScreenOperations == ProfilePicturesTypeEnum::$INTERFACE['2'])
				$this->m_time_AcceptRejectCompletion = $this->m_timePhotoUploadDate;
			
			$this->m_time_AcceptRejectCompletion =  date("Y-m-d H:i:s");
			if($this->m_enPhotoScreenOperations == ProfilePicturesTypeEnum::$INTERFACE['2'])
			{
				$this->m_time_EditProcessCompletion =  date("Y-m-d H:i:s");
			}	
		}
		if(!strlen($this->m_timePreprocessCompletion) || $this->m_timePreprocessCompletion=="0000-00-00 00:00:00" )
		{
			throw new jsException("","Photo Screen Master Log Error : Preprocess time is null");
		}
		return $this->m_timePreprocessCompletion;
	}
	
	/**
	 * getColumns
	 * All value in well formated array which will be passed in store object function calls
	 * @access public
	 * @return Array
	 */
	public function getColumns()
	{
		$this->m_arrColumns = array(	
				'PROFILEID'							=>$this->m_iProfileId,
				'PROFILE_TYPE'						=>$this->m_cProfileType,
				'UPLOADDATE'						=>$this->m_timePhotoUploadDate,
				'PREPROCESS_COMPLETION_TIME'		=>$this->m_timePreprocessCompletion,
				'ACCEPT_REJECT_Q_COMPLETION_TIME'	=>$this->m_time_AcceptRejectCompletion,
				'PROCESS_Q_COMPLETION_TIME'			=>$this->m_time_EditProcessCompletion,
				'SCREENING_COMPLETION_QUEUE_NAME'	=>$this->m_enScreeningCompletionQueueName,
				);
		return $this->m_arrColumns;
	}

	/**
	 * Process : Main Function to run the entire logic
	 * As per the decide operation type, we will update store
	 * and if we are not able to decide operation then 
	 * we send a developer mail regarding to scenario
	 * @access private
	 * @return void
	 */	
	private function Process()
	{
		$this->decideOperationType();

		$this->m_timePhotoUploadDate		= $this->getRecentPhotoUploadTime();		
		$this->m_timePreprocessCompletion 	= $this->getRecentPreprocessCompletionTime();

		switch($this->m_enOperationType)
		{
			case PictureStaticVariablesEnum::PHOTO_SCREEN_MASTER_INSERT :
			{	
				$this->m_objStore_MasterTracking->insertRecord($this->getColumns());
			}
			break;
			case PictureStaticVariablesEnum::PHOTO_SCREEN_MASTER_UPDATE :
			{
				if($this->isOperationValidForUpdation() && $this->trackEditingProcess_QueueCompletion())
				{
					break;
				}
				if($this->isOperationValidForUpdation() && $this->trackAcceptReject_QueueCompletion())
				{	
					break;
				}
				$this->m_objStore_MasterTracking->updateRecord($this->getColumns());
			}
			break;
			default:
			{
				//Handle Cant Determine Case or any other case
				//JsTrackingHelper::sendDeveloperTrackMail(PictureStaticVariablesEnum::$arrPHOTO_SCREEN_DEVELOPERS,"'Cant determine' status of PhotoScreen for $this->m_iProfileId");
				return false;
			}
			break;
		}
	}
	
	/**
	 * trackThis : Public function to track the master process
	 * @access Public
	 * @return void
	 */	
	public function trackThis()
	{
		$this->Process();
	}
	
	
	/**
	 * isOperationValidForUpdation : 
	 * In case of resize cron, and face detection cron,
	 * we are not calling accept recject accept update and edit process update
	 * @access private
	 * @return Boolean
	 */
	private function isOperationValidForUpdation()
	{
		$arrSkipOperation = array(PictureStaticVariablesEnum::PHOTO_SCREEN_OPERATION_PREPROCESS_CRON,PictureStaticVariablesEnum::PHOTO_SCREEN_OPERATION_FACEDETECTION_CRON);

		if(in_array($this->m_enPhotoScreenOperations,$arrSkipOperation))
		{
			return false;
		}
		return true;	
	}
	
	public function setProcessComplete($bStatus=false)
	{
		if(!$bStatus)//If Status is not complete then update Queue Name
		{
			$this->m_enScreeningCompletionQueueName = NULL;
		}
	}
	
	
}
?>
