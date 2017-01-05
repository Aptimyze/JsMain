<?php
/**
 * JsPhotoScreen_NotifySms
 * Implementing Sms logic for calling notification as per given parameters
 * @package Operations
 * @subpackage PhotoScreen
 * @author Kunal Verma
 * @created 26th Sept 2014
 */
/**
 * JsPhotoScreen_NotifySms
 * 
 * @module Notify
 * @author  Kunal Verma
 */

class JsPhotoScreen_NotifySms
{
	/**
	 * Declaration of Member Variables
	 */ 
	/**
	 * m_objSms : Object of SendSms Class
	 * @access private
	 * @var Integer
	 */
	private $m_objSms;
	
	/**
	 * Declaring and Defining Member Function
	 */
	 
	/**
	 * Constructor
	 * @access public
	 * @param $iProfileID : Profile Id of User
	 * @param $szMsgType : Message Type(Either Accepted or Rejected)
	 * @return void
	 */
	public function __construct($iProfileID,$szMsgType,$photoRejectReason="")
	{		
		if(!$iProfileID || !strlen($szMsgType))
		{
			//throw new jsException("","ProfileID is null or msgtype is null in JsPhotoScreen_NotifySms");
		}	
		$this->m_objSms = new SendSms;
		$this->m_objSms->send_sms($iProfileID,$szMsgType,'',$photoRejectReason);
	}
}
?>
