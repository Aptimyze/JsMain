<?php
/*
This class includes functions for sending mail, sms and notifications.
*/
include_once(JsConstants::$cronDocRoot."/crontabs/connect.inc");
class ProcessHandler
{
  /**
   * 
   * Function for sending e-mail
   * 
   * @access public
   * @param $type,$body
   */
	public function sendMail($type,$body)
	{
    $senderid=$body['senderid'];
    $receiverid=$body['receiverid'];
    $message = $body['message'];
    $senderObj = new Profile('',$senderid);   
    $senderObj->getDetail("","","*");
    $receiverObj = new Profile('',$receiverid);
    $receiverObj->getDetail("","","*");
    switch($type)
    {
      case 'CANCELCONTACT' :  ContactMailer::sendCancelledMailer($receiverObj,$senderObj);
                              break;
      case 'ACCEPTCONTACT' :  ContactMailer::sendAcceptanceMailer($receiverObj,$senderObj);  
                              break;
      case 'DECLINECONTACT':  ContactMailer::sendDeclineMail($receiverObj,$senderObj); 
                              break;
      case 'MESSAGE'       :  ContactMailer::sendMessageMailer($receiverObj, $senderObj,$message);
                              break;
    }
	}

  /**
   * 
   * Function for sending SMS.
   * 
   * @access public
   * @param $type,$body
   */
  public function sendSMS($type,$body)
  {
    
    include_once(JsConstants::$docRoot."/profile/InstantSMS.php");
    $senderid=$body['senderid']; 
    $receiverid=$body['receiverid'];
    switch($type)
    {
      case 'ACCEPTANCE_VIEWER' : $smsViewer = new InstantSMS($type,$senderid,'',$receiverid);
                                 $smsViewer->send();  
                                 break;
      case 'ACCEPTANCE_VIEWED' : $smsViewer = new InstantSMS($type,$receiverid,'',$senderid);
                                 $smsViewer->send();  
                                 break; 
    }
  }

  /**
   * 
   * Function for sending notifications.
   * 
   * @access public
   * @param $type,$body
   */
  public function sendGCM($type,$body)
  {
    $senderid=$body['senderid'];   
    $receiverid=$body['receiverid'];
    $message = $body['message'];
    switch($type)
    {
      case 'ACCEPTANCE' :  $instantNotificationObj = new InstantAppNotification("ACCEPTANCE");
                           $instantNotificationObj->sendNotification($receiverid, $senderid);
                           break;
      case 'MESSAGE'    :  $instantNotificationObj = new InstantAppNotification("MESSAGE_RECEIVED");
                           $instantNotificationObj->sendNotification($receiverid, $senderid, $message);  
                           break;
    }
  } 

  /**
   * 
   * Function for sending gcm notifications(fso app/browser).
   * 
   * @access public
   * @param $type,$body
   */
  public function sendGcmNotification($type,$body)
  {
    if(in_array($type, BrowserNotificationEnums::$notificationChannelType))
    {
      switch($type)
      {
        case "BROWSER_NOTIFICATION" : GcmNotificationsSender::handleNotification($type,$body,false);
                                      break;
        case "FSOAPP_NOTIFICATION"  : GcmNotificationsSender::handleNotification($type,$body,true);
                                      break;
      }
    }    
  }
}
?>