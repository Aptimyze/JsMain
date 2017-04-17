<?php

/**
 * JsPhotoScreen_NotifyEmail
 * Implementing mailer logic for calling notification as per given parameters
 * Possible list of paramter as mentioned in JsPhotoScreen_Enum::$arrMAIL_PARAMS
 * @package Operations
 * @subpackage PhotoScreen
 * @author Kunal Verma
 * @created 26th Sept 2014
 */
/**
 * JsPhotoScreen_NotifyEmail
 * 
 * @module Notify
 * @author  Kunal Verma
 */

class JsPhotoScreen_NotifyEmail
{
	/**
	 * Declaration of Member Variables
	 */ 
	/**
	 * m_iPhotosUploaded : Number of uploaded photos
	 * @access private
	 * @var Integer
	 */
	private $m_iPhotosUploaded;

	/**
	 * m_iPhotosScreened : Number of Screened Photos;
	 * @access private
	 * @var Integer
	 */
	private $m_iPhotosScreened;

	/**
	 * m_iPhotosRejected : Number of Rejected photos
	 * @access private
	 * @var Integer
	 */
	private $m_iPhotosRejected;

	/**
	 * m_szRejectReason : Reject reason
	 * @access private
	 * @var String
	 */
	private $m_szRejectReason;

	/**
	 * m_iTotalPhotosNow : Total count of photos nows
	 * @access private
	 * @var Integer
	 */
	private $m_iTotalPhotosNow;

	/**
	 * m_enMailerType : Enum specifying Mailer Typer, 
	 * possible value as specifed in JsPhotoScreen_Enum::
	 * 	PHOTO_UPLOADED_MAILER ,
	 * 	PHOTO_REJECT_MAILER ,
	 * 	PHOTO_UPLOAD_MAX_MAILER
	 * @access private
	 * @var Integer
	 */
	private $m_enMailerType;
	
	/**
	 * m_szMailGroup : Mailer Group as used in Mailer Framwork
	 * @access private
	 * @var String
	 */	
	private $m_szMailGroup = MailerGroup::PHOTO_UPLOAD;
	
	
	/**
	 * Declaring and Defining Member Function
	 */
	 
	/**
	 * Constructor
	 * @access public
	 * @param $arrParams : Array of mailer params
	 * @return void
	 */
	public function __construct($arrParams)
	{
		$this->initVariables($arrParams);
		$this->Process();
	}
	
	/**
	 * initVariables
	 * Initalize all member varaibles
	 * @access private
	 * @param $arrMailParams : Array of Mail paramter
	 * @return void
	 */	
	private function initVariables($arrMailParams)
	{
		if(!is_array($arrMailParams) || !$arrMailParams['MALER_TYPE'])
			return;

		$this->m_iPhotosUploaded 	= $arrMailParams['PHOTOS_UPLOADED'];
		$this->m_iPhotosScreened 	= $arrMailParams['PHOTOS_SCREENED'];
		$this->m_iPhotosRejected 	= $arrMailParams['PHOTOS_REJECTED'];
		$this->m_szRejectReason 	= $arrMailParams['REJECT_REASON'];
		$this->m_iTotalPhotosNow 	= $arrMailParams['TOTAL_PHOTOS_NOW'];
		$this->m_enMailerType		= $arrMailParams['MALER_TYPE'];//As specified in enums
		$this->m_iProfileID			= $arrMailParams['PROFILEID'];
	}

	/**
	 * Process : Process the mailer request as per Mailer Type
	 * @access private
	 * @param  void 
	 * @return void
	 */	
	private function Process()
	{
		if(!$this->m_enMailerType)
		{
			//throw new jsException("","Mailer Type is not specified in JsPhotoScreen_NotifyEmail");
		}
		switch($this->m_enMailerType)
		{
			case JsPhotoScreen_Enum::PHOTO_UPLOADED_MAILER:
			{
				$this->sendUploadMailer();
			}
			break;
			case JsPhotoScreen_Enum::PHOTO_REJECT_MAILER:
			{
				$this->sendRejectMailer();
			}
			break;
			case JsPhotoScreen_Enum::PHOTO_UPLOAD_MAX_MAILER:
			{
				$this->sendUpload_MaxMailer();
			}
			break;
		}
	}
	
	/**
	 * sendUploadMailer
	 * This is the mailer which will be send when some(or all) photos
	 * are uploaded( or may be some are deleted or none are deleted)
	 * @access private
	 * @param  void 
	 * @return void
	 */	
	private function sendUploadMailer()
	{
		if(!$this->m_iProfileID)
		{
			throw new jsException("","ProfileID is null in sendUploadMailer method of JsPhotoScreen_NotifyEmail");
		}
		$search_array1=SearchCommonFunctions::getDppMatches($this->m_iProfileID,'mailer_photo_upload');	//5 DPP matches with photo and no popular sorting
		$search_array2=SearchCommonFunctions::getDppMatches($this->m_iProfileID,'mailer_photo_upload','','');	//Dpp matches count with no condition of have photo
		$objEmailSender=new EmailSender($this->m_szMailGroup,JsPhotoScreen_Enum::PHOTO_UPLOADED_MAILER);
		$objTemplate = $objEmailSender->setProfileId($this->m_iProfileID);

		$objPartialList = new PartialList;
		$objPartialList->addPartial('dpp_matches','dpp_matches',$search_array1["SEARCH_RESULTS"]);
		$objPartialList->addPartial('self_tuple','dpp_matches',array($this->m_iProfileID));
		$objTemplate->setPartials($p_list);

		$smartyObj = $objTemplate->getSmarty();
		$smartyObj->assign("PHOTOS_UPLOADED",$this->m_iPhotosUploaded);
		$smartyObj->assign("PHOTOS_SCREENED",$this->m_iPhotosScreened);
		$smartyObj->assign("PHOTOS_REJECTED",$this->m_iPhotosRejected);
		$smartyObj->assign("REJECT_REASON",$this->m_szRejectReason);
		$smartyObj->assign("TOTAL_PHOTOS_NOW",$this->m_iTotalPhotosNow);
		$smartyObj->assign("SEARCH_COUNT",$search_array2["TOTAL_SEARCH_RESULTS"]);
		$objEmailSender->send();
	}

	/**
	 * sendRejectMailer
	 * This is the mailer which will be send when all are rejected
	 * @access private
	 * @param  void
	 * @return void
	 */		
	private function sendRejectMailer()
	{
		if(!$this->m_iProfileID || !strlen($this->m_szRejectReason))
		{
			throw new jsException("","ProfileID or Reject Reason is null in sendRejectMailer method of JsPhotoScreen_NotifyEmail");
		}
		$rejectReason = ":<br><br>* ".str_replace(" or ","<br>* ",$this->m_szRejectReason);		
		$rejectReason = rtrim($rejectReason,".");		
		$objEmailSender=new EmailSender($this->m_szMailGroup,JsPhotoScreen_Enum::PHOTO_REJECT_MAILER);		
        $objTemplate = $objEmailSender->setProfileId($this->m_iProfileID);
		$smartyObj = $objTemplate->getSmarty();
        $smartyObj->assign("REJECT_REASON",$rejectReason);
   		$objEmailSender->send();
	}
	
	/**
	 * sendUpload_MaxMailer
	 * This is the mailer which will be send when more than 20 are uploaded 
	 * from backend or mailer but only PHOTO_UPLOAD_MAX_MAILER gets uploaded.
	 * @access private
	 * @param  void 
	 * @return void
	 */	
	private function sendUpload_MaxMailer()
	{
		if(!$this->m_iProfileID)
		{
			throw new jsException("","ProfileID is null in sendRejectMailer method of JsPhotoScreen_NotifyEmail");
		}
		$objEmailSender=new EmailSender($this->m_szMailGroup,JsPhotoScreen_Enum::PHOTO_UPLOAD_MAX_MAILER);
		$objEmailSender->setProfileId($this->m_iProfileID);
		$objEmailSender->send();
	}
}
?>
