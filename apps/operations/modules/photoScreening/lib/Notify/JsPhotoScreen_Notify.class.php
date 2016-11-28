<?php
/**
 * JsPhotoScreen_Notify Class 
 * Implementing logic for calling notification as per given parameters
 * Possible list of paramter as mentioned in JsPhotoScreen_Enum::$arrNOTIFY_PARAMS
 * @package Operations
 * @subpackage PhotoScreen
 * @author Kunal Verma
 * @created 26th Sept 2014
 */
/**
 * JsPhotoScreen_Notify
 * 
 * @module Notify
 * @author  Kunal Verma
 */

class JsPhotoScreen_Notify
{
	/**
	 * Declaration of Member Variables
	 */ 
	/**
	 * m_enCurrentOperation : Specifies Channel Name on which we have to notify
	 * @access private
	 * @var Enum 
	 */
	private $m_enNotify_Channel; 
	
	/**
	 * Profile Id of User for which screen process is running
	 * @access private
	 * @var Integer 
	 */
	private $m_iProfileID;
	
	/**
	 * Sms Msg Type 
	 * @access private
	 * @var String 
	 */
	private $m_szSmsMsgType;
	
	/**
	 * Array of mail param
	 * @access private
	 * @var Array 
	 */
	private $m_arrMailParams;
	
	/**
	 * Declaring and Defining Member Function
	 */
	 
	/**
	 * Constructor
	 * @access public
	 * @param $arrParams : Array of Notify params
	 * If paramter is not specifed in constructor then call respective set methods
	 * @return void
	 */
	public function __construct($arrParams='')
	{
		$this->m_enNotify_Channel	= $arrParams['NOTIFY_CHANNEL'];
		$this->m_iProfileID			= $arrParams['PROFILEID'];
		$this->m_iNum_ApprovedPic	= $arrParams['NUM_APPROVED_PIC'];
		$this->m_iNum_DeletePic 	= $arrParams['NUM_DELETED_PIC'];
		$this->m_iNum_UploadPic		= $arrParams['NUM_UPLOADED_PIC'];
		$this->m_szRejectReason		= $this->bakeRejectReason($arrParams['REJECT_REASON']);						
	}

	/**
	 * setNotifyChannel
	 * @access public
	 * @param $enNotifyChannel : Enum Value as specified in JsPhotoScreen_Enum::enNOTIFY_CHANNEL_*
	 * @return void
	 */	
	public function setNotifyChannel($enNotifyChannel)
	{
		$this->m_enNotify_Channel 	= $enNotifyChannel;
	}

	/**
	 * setProfileId
	 * @access public
	 * @param $iProfileID : Profile Id
	 * @return void
	 */	
	public function setProfileId($iProfileID)
	{
		$this->m_iProfileID			= $iProfileID;
	}

	/**
	 * setSmsMsgType
	 * @access public
	 * @param $iProfileID : SMS Msg Type
	 * @return void
	 */		
	public function setSmsMsgType($enSMS_MSG_TYPE/*As specified in enum*/)
	{
		$this->m_szSmsMsgType 		= $enSMS_MSG_TYPE;
	}

	/**
	 * setMailParams 
	 * @access public
	 * @param $arrParams : Array of mail Param as specified in JsPhotoScreen_Enum::arrMAIL_PARAMS
	 * @return void
	 */		
	public function setMailParams($arrParams)
	{
		$this->m_arrMailParams		= $arrParams;
	}
	
	/**
	 * notifyUser : Main call for notifying user on specifed channels
	 * @access public
	 * @param  void 
	 * @return void
	 */	
	public function notifyUser()
	{
		$this->Process();
		switch($this->m_enNotify_Channel)
		{
			case JsPhotoScreen_Enum::enNOTIFY_CHANNEL_MAIL:
			{
				new JsPhotoScreen_NotifyEmail($this->m_arrMailParams);
			}
			break;
			case JsPhotoScreen_Enum::enNOTIFY_CHANNEL_SMS:
			{
				new JsPhotoScreen_NotifySms($this->m_iProfileID,$this->m_szSmsMsgType);
			}
			break;
			case JsPhotoScreen_Enum::enNOTIFY_CHANNEL_MAIL_SMS:
			{				
				$this->photoRejectionReason["PHOTO_REJECTION_REASON"] = $this->m_arrMailParams["REJECT_REASON"];
				$objNotifySMS 	= new JsPhotoScreen_NotifySms($this->m_iProfileID,$this->m_szSmsMsgType,$this->photoRejectionReason);
				$objNotifyEmail = new JsPhotoScreen_NotifyEmail($this->m_arrMailParams);
			}
			break;
		}
	}
	
	/**
	 * Process : Process the request
	 * Decide which mailer and sms msg type to send as per given params
	 * @access public
	 * @param  void 
	 * @return void
	 */	
	private function Process()
	{
		$objProfile 	= Operator::getInstance('newjs_master',$this->m_iProfileID);
		
		$objPicService 	= new PictureService($objProfile);
		$this->m_arrMailParams['TOTAL_PHOTOS_NOW']	= $objPicService->getUserUploadedPictureCount();

		$this->m_arrMailParams['PHOTOS_UPLOADED'] 	= $this->m_iNum_UploadPic;
		$this->m_arrMailParams['PHOTOS_SCREENED'] 	= $this->m_iNum_ApprovedPic;
		$this->m_arrMailParams['PHOTOS_REJECTED'] 	= $this->m_iNum_DeletePic;
		$this->m_arrMailParams['REJECT_REASON']		= $this->m_szRejectReason;
		$this->m_arrMailParams['PROFILEID']			= $this->m_iProfileID;
		
		if($this->m_iNum_ApprovedPic)
		{
			$this->m_arrMailParams['MALER_TYPE']	= JsPhotoScreen_Enum::PHOTO_UPLOADED_MAILER;
			$this->m_arrMailParams['REJECT_REASON'] = null;
			$this->m_szSmsMsgType					= JsPhotoScreen_Enum::szSMS_MSG_ACCEPT;
		}
		else if($this->m_iNum_DeletePic)
		{
			$this->m_arrMailParams['MALER_TYPE']	= JsPhotoScreen_Enum::PHOTO_REJECT_MAILER;
			$this->m_szSmsMsgType					= JsPhotoScreen_Enum::szSMS_MSG_REJECT;			
		} 
	}
	/**
	 * bakeRejectReason
	 * @access private
	 * @param $szRejectReason : String of reasons, as string is comma sperated key of DELETE_REASONS array
	 * @return String : Reason, sperated by 'or' keyword
	 */
	private function bakeRejectReason($szRejectReason)
	{
		$arrActualReason = array();
		$szReason = null;
		if(strlen($szRejectReason))
		{
			$arrRejectEnum = explode(",",$szRejectReason);
			foreach($arrRejectEnum as $szKey=>$iVal)
			{
				if(array_key_exists(intval($iVal),PictureStaticVariablesEnum::$DELETE_REASONS))
				{
					$arrActualReason[] = PictureStaticVariablesEnum::$DELETE_REASONS[(intval($iVal))];
				}
			}
			if(count($arrActualReason))
			{
				$szReason = implode(" or ",$arrActualReason); 
			}
		}
		return $szReason;
	}
}
?>
