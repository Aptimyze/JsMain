<?php
/*
 * ContactMailer.class.php
 * 
 * Copyright 2013 Pankaj Khandelwal <pankaj.khandelwal@jeevansathi.com>
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 * 
 * 
 */

include_once(JsConstants::$docRoot. "/classes/Services.class.php");
class ContactMailer
{
    /**
	 * 
	 * method to send mail on Decline of a contact
	 * @param Object $receiver Profile Object of receiver
	 * @param Object $sender   Profile Object of sender
	 */	
	public static function sendDeclineMail($receiver,$sender)
	{
		$email_sender=new EmailSender(2,1748);
		$tpl = $email_sender->setProfile($receiver);
		$FtoState = $receiver->getPROFILE_STATE()->getFTOStates()->getSubState();
		if($FtoState == FTOSubStateTypes::FTO_ELIGIBLE_NO_PHONE_NO_PHOTO || $FtoState == FTOSubStateTypes::FTO_ELIGIBLE_HAVE_PHONE_NO_PHOTO)
		{
			$FTO = 1;
		}
		else
		{
			$FTO = 2;
		}
		$profileComplition = ProfileCompletionFactory::getInstance("null",$receiver,"null");
		$score = $profileComplition->getProfileCompletionScore();
		
		$profileArr[0] = $receiver;
		$picture = new PictureArray;
		$photoCount = $picture->getNoOfPics($profileArr);
		if($photoCount[$receiver->getPROFILEID()]>0)
		{
			$photo = 1;
		}
		else
		{
			$photo = 0;
		}
		$dppMatchesArr = SearchCommonFunctions::getMyDppMatches("",$receiver,4);
		$inputM2 = $dppMatchesArr["PIDS"];
		$partialList=new PartialList;
		$partialList->addPartial('suggested_profiles','suggested_profiles2',$inputM2,false);
		$partialList->addPartial('jeevansathi_contact_address','jeevansathi_contact_address');
		$smartyObj = $tpl->getSmarty();
		if($receiver->getPROFILE_STATE()->getPaymentStates()->isPaid())
		{
			$smartyObj->assign("paid",1);
			if($sender->getPROFILE_STATE()->getPaymentStates()->getPaymentStatus() =='EVALUE')
				$paidStatus = "eValue";
			else if($sender->getPROFILE_STATE()->getPaymentStates()->getPaymentStatus() =='ERISHTA')
				$paidStatus = "eRishta";
			$smartyObj->assign("paidStatus",$paidStatus);
		}
		$havePhoto = $receiver->getHAVEPHOTO();
		$photo = ($havePhoto=="Y"||$havePhoto=="U")?1:0;
		$smartyObj->assign("lowscore",$score);
		$smartyObj->assign("sugcount",count($inputM2));
		$smartyObj->assign("FTO",$FTO);
		$smartyObj->assign("photo",$photo);
		$smartyObj->assign("otherProfile",$sender->getPROFILEID());
		$tpl->setPartials($partialList);
                
                if(CommonConstants::contactMailersCC)
                {    
                $contactNumOb=new ProfileContact();
                $numArray=$contactNumOb->getArray(array('PROFILEID'=>$receiver->getPROFILEID()),'','',"ALT_EMAIL,ALT_EMAIL_STATUS");
                if($numArray['0']['ALT_EMAIL'] && $numArray['0']['ALT_EMAIL_STATUS']=='Y')
                {
                   $ccEmail =  $numArray['0']['ALT_EMAIL'];    
                }
                else $ccEmail = "";
                }
                else 
                    $ccEmail = "";
		$email_sender->send("",$partialList,$ccEmail);
	}
	/**
	 * 
	 * method to send mail on acceptance of contact
	 * @param Object $receiver Profile Object of receiver
	 * @param Object $sender   Profile Object of sender
	 */	
	public static function sendAcceptanceMailer($receiver,$sender)
	{
		$email_sender=new EmailSender(2,1742);
		$tpl = $email_sender->setProfile($receiver);
		$partialList = new PartialList;
		$partialList->addPartial('self_tuple','tuple_profiles',array($sender->getPROFILEID()));
		$variableDiscountObj = new VariableDiscount;
		$variableDiscount = $variableDiscountObj->getDiscDetails($receiver->getPROFILEID());
		$smartyObj = $tpl->getSmarty();
		$FtoState = $receiver->getPROFILE_STATE()->getFTOStates()->getSubState();
		if($receiver->getPROFILE_STATE()->getPaymentStates()->isPaid())
		{
			$contactAllotedObj = new jsadmin_CONTACTS_ALLOTED;
			$contactViewed = $contactAllotedObj->getViewedContacts($receiver->getPROFILEID());
			$smartyObj->assign("Count",$contactViewed);
			$smartyObj->assign("paid",1);
			if($sender->getPROFILE_STATE()->getPaymentStates()->getPaymentStatus() =='EVALUE')
				$paidStatus = "eValue";
			else if($sender->getPROFILE_STATE()->getPaymentStates()->getPaymentStatus() =='ERISHTA')
				$paidStatus = "eRishta";
			$smartyObj->assign("paidStatus",$paidStatus);
		}
		
		//elseif($FtoState= FTOSubStateTypes::NEVER_EXPOSED  && !$receiver->getPROFILE_STATE()->getPaymentStates()->isPaid())
		else
		{
			if(!empty($variableDiscount))
			{
				$vdDisplayText = $variableDiscountObj->getVdDisplayText($receiver->getPROFILEID(),'small');
				$discountMax = $variableDiscount["DISCOUNT"];
				$smartyObj->assign("variableDiscount",$discountMax);
				$smartyObj->assign("vdDisplayText",$vdDisplayText);
				$smartyObj->assign("VD_END_MONTH",date("M",JSstrToTime($variableDiscount["EDATE"])));
                $smartyObj->assign("VD_END_YEAR",date("Y",JSstrToTime($variableDiscount["EDATE"])));
                $smartyObj->assign("VD_END_DAY",date("d",JSstrToTime($variableDiscount["EDATE"])));
                $smartyObj->assign("VD_END_DAY_SUFFIX",date("S",JSstrToTime($variableDiscount["EDATE"])));
                $tpl->getSmarty()->assign("topSource","VDA2".$discountMax);
				$tpl->getSmarty()->assign("BottomSource","VDA3".$discountMax);
			}
			else
			{
				$tpl->getSmarty()->assign("topSource","A2");
				$tpl->getSmarty()->assign("BottomSource","A3");
			}
			
				
			
				
		}
		$himher =  $sender->getGENDER()=="M"?"him":"her";
		$smartyObj->assign("himher",$himher);
		$hisher =  $sender->getGENDER()=="M"?"his":"her";
		$smartyObj->assign("hisher",$hisher);
			
		$partialList->addPartial('jeevansathi_contact_address','jeevansathi_contact_address');
		$smartyObj->assign("otherProfile",$sender->getPROFILEID());
		$smartyObj->assign("acceptanceTemplate",$acceptanceTemplate);
		$smartyObj->assign("acceptance_mailer",1);
		$smartyObj->assign("FTO",$FTO);
		$tpl->setPartials($partialList);
                
                if(CommonConstants::contactMailersCC)
                {
                $contactNumOb=new ProfileContact();
                $numArray=$contactNumOb->getArray(array('PROFILEID'=>$receiver->getPROFILEID()),'','',"ALT_EMAIL,ALT_EMAIL_STATUS");
                if($numArray['0']['ALT_EMAIL'] && $numArray['0']['ALT_EMAIL_STATUS']=='Y')
                {
                   $ccEmail =  $numArray['0']['ALT_EMAIL'];    
                }
                else $ccEmail = "";
                }
                else $ccEmail = "";

		$email_sender->send("",$partialList,$ccEmail);
	}
	/**
	 * 
	 * method to send mail on cancallation of a contact
	 * @param Object $receiver Profile Object of receiver
	 * @param Object $sender   Profile Object of sender
	 */	
	
	public static function sendCancelledMailer ($receiver,$sender)
	{
		$email_sender=new EmailSender(2,1758);
		$tpl = $email_sender->setProfile($receiver);
		$FtoState = $receiver->getPROFILE_STATE()->getFTOStates()->getSubState();
		if($FtoState == FTOSubStateTypes::FTO_ELIGIBLE_NO_PHONE_NO_PHOTO || $FtoState == FTOSubStateTypes::FTO_ELIGIBLE_HAVE_PHONE_NO_PHOTO)
		{
			$FTO = 1;
		}
		else
		{
			$FTO = 2;
		}
		$profileArr[0] = $receiver;
		$picture = new PictureArray;
		$photoCount = $picture->getNoOfPics($profileArr);
		if($photoCount[$receiver->getPROFILEID()]>0)
		{
			// Photo is visible on accept
			if($receiver->getPHOTO_DISPLAY() == 'C')
			{
				$photo = 2;
			}
			else
			{
				$photo = 1;
			}
		}
		else
		{
			$photo = 0;
		}
		$dppMatchesArr = SearchCommonFunctions::getMyDppMatches("",$receiver,4);
		$inputM2 = $dppMatchesArr["PIDS"];
		$partialList=new PartialList;
		$partialList->addPartial('suggested_profiles','suggested_profiles2',$inputM2,false);
		$partialList->addPartial('jeevansathi_contact_address','jeevansathi_contact_address');
		$smartyObj = $tpl->getSmarty();
		$smartyObj->assign("sugcount",count($inputM2));
		$smartyObj->assign("FTO",$FTO);
		$smartyObj->assign("photo",$photo);
		$smartyObj->assign("otherProfile",$sender->getPROFILEID());
		$tpl->setPartials($partialList);
                
                if(CommonConstants::contactMailersCC)
                {                
                $contactNumOb=new ProfileContact();
                $numArray=$contactNumOb->getArray(array('PROFILEID'=>$receiver->getPROFILEID()),'','',"ALT_EMAIL,ALT_EMAIL_STATUS");
                if($numArray['0']['ALT_EMAIL'] && $numArray['0']['ALT_EMAIL_STATUS']=='Y')
                {
                   $ccEmail =  $numArray['0']['ALT_EMAIL'];    
                }
                else $ccEmail = "";
                }
                else $ccEmail = "";

		$email_sender->send("",$partialList,$ccEmail);
		
	}
	/**
	 * 
	 * method to send mail on writing a message
	 * @param Object $receiver Profile Object of receiver
	 * @param Object $sender   Profile Object of sender
	 * @param string $message
	 */
	public static function sendMessageMailer($receiver,$sender,$message)
	{
	$emailSender = new EmailSender(MailerGroup::WRITE_MESSAGE);
    $tpl = $emailSender->setProfileId($receiver->getPROFILEID());
	$variableDiscountObj = new VariableDiscount();
	$variableDiscount = $variableDiscountObj->getDiscDetails($receiver->getPROFILEID());
	$subscriptionStatus=CommonFunction::isPaid($receiver->getSUBSCRIPTION());
	$tpl->getSmarty()->assign("RECEIVER_IS_PAID", $subscriptionStatus);
	$tpl->getSmarty()->assign("profileid", $receiver->getPROFILEID());
	$tpl->getSmarty()->assign("otherProfileId", $sender->getPROFILEID());

	if(!empty($variableDiscount))
	{
		$vdDisplayText = $variableDiscountObj->getVdDisplayText($viewedProfileId,'small');
		$discountMax = $variableDiscount["DISCOUNT"];
		$tpl->getSmarty()->assign("variableDiscount",$discountMax);
		$tpl->getSmarty()->assign("vdDisplayText",$vdDisplayText);
		$tpl->getSmarty()->assign("VD_END_MONTH",date("M",JSstrToTime($variableDiscount["EDATE"])));
        $tpl->getSmarty()->assign("VD_END_YEAR",date("Y",JSstrToTime($variableDiscount["EDATE"])));
        $tpl->getSmarty()->assign("VD_END_DAY",date("d",JSstrToTime($variableDiscount["EDATE"])));
        $tpl->getSmarty()->assign("VD_END_DAY_SUFFIX",date("S",JSstrToTime($variableDiscount["EDATE"])));
		$tpl->getSmarty()->assign("topSource","VDMSG1".$discountMax);
		$tpl->getSmarty()->assign("BottomSource","VDMSG2".$discountMax);
	}
	else
	{
		$tpl->getSmarty()->assign("BottomSource","VDMSG2");
	}

	$partialObj = new PartialList();
	$profileChecksum=JsAuthentication::jsEncryptProfilechecksum($sender->getPROFILEID());
	if(strlen($message)>1000){$showReadMore=1;$message=substr($message,0,1000);}else $showReadMore=0;
        $partialObj->addPartial("messageMailerTuple", "messageMailerTuple",  array('profileArray'=>array($sender->getPROFILEID()=>$message),'showReadMore'=>$showReadMore));
        $partialObj->addPartial("jeevansathi_contact_address", "jeevansathi_contact_address");
        $tpl->setPartials($partialObj);

        if(CommonConstants::contactMailersCC)
        {                

        $contactNumOb=new ProfileContact();
        $numArray=$contactNumOb->getArray(array('PROFILEID'=>$receiver->getPROFILEID()),'','',"ALT_EMAIL,ALT_EMAIL_STATUS");
        if($numArray['0']['ALT_EMAIL'] && $numArray['0']['ALT_EMAIL_STATUS']=='Y')
        {
           $ccEmail =  $numArray['0']['ALT_EMAIL'];    
        }
        else $ccEmail = "";
        }
        else $ccEmail = "";
	$emailSender->send('','',$ccEmail);
	}

  /**
   * Fire instant EOI mailer
   *
   * <p>
   * This function triggers instant EOI mailer. The condition to trigger this mailer is that the viewed profile should have entry date within 30 days.
   * </p>
   * 
   * @access public
   * @param $viewedProfileId The profileid of the viewed to whom the mail will be sent.
   * @param $viewerProfileId The profileid of the viewer, who has done the EOI
   * @param $draft The draft message.
   * @param $subscriptionStatus The subscription status of viewed.
   * @return boolean
   */
  public static function InstantEOIMailer($viewedProfileId, $viewerProfileId, $draft, $subscriptionStatus) {
    $emailSender = new EmailSender(MailerGroup::EOI, 1754);
    $tpl = $emailSender->setProfileId($viewedProfileId);
    $tpl->getSmarty()->assign("otherProfileId", $viewerProfileId);
    $tpl->getSmarty()->assign("RECEIVER_IS_PAID", $subscriptionStatus);
	$profileMemcacheServiceObj = new ProfileMemcacheService($viewedProfileId);
	$totalCount = $profileMemcacheServiceObj->get("AWAITING_RESPONSE");
	$variableDiscountObj = new VariableDiscount();
	$variableDiscount = $variableDiscountObj->getDiscDetails($viewedProfileId);
	if(!empty($variableDiscount))
	{
		$vdDisplayText = $variableDiscountObj->getVdDisplayText($viewedProfileId,'small');
		$discountMax = $variableDiscount["DISCOUNT"];
		$tpl->getSmarty()->assign("variableDiscount",$discountMax);
		$tpl->getSmarty()->assign("vdDisplayText",$vdDisplayText);
		$tpl->getSmarty()->assign("VD_END_MONTH",date("M",JSstrToTime($variableDiscount["EDATE"])));
        $tpl->getSmarty()->assign("VD_END_YEAR",date("Y",JSstrToTime($variableDiscount["EDATE"])));
        $tpl->getSmarty()->assign("VD_END_DAY",date("d",JSstrToTime($variableDiscount["EDATE"])));
        $tpl->getSmarty()->assign("VD_END_DAY_SUFFIX",date("S",JSstrToTime($variableDiscount["EDATE"])));
		$tpl->getSmarty()->assign("topSource","VDEOI1".$discountMax);
		$tpl->getSmarty()->assign("BottomSource","VDEOI2".$discountMax);
	}
	else
	{
		$tpl->getSmarty()->assign("BottomSource","EOI2");
	}
	$tpl->getSmarty()->assign("count", 1);
	$tpl->getSmarty()->assign("totalCount",$totalCount);
	$partialObj = new PartialList();
        $partialObj->addPartial("eoi_profile", "eoi_profile", array($viewerProfileId=>$draft));
        $partialObj->addPartial("jeevansathi_contact_address", "jeevansathi_contact_address");
        $tpl->setPartials($partialObj);
    
        if(CommonConstants::contactMailersCC)
        {                
        $contactNumOb=new ProfileContact();
        $numArray=$contactNumOb->getArray(array('PROFILEID'=>$viewedProfileId),'','',"ALT_EMAIL,ALT_EMAIL_STATUS");
        if($numArray['0']['ALT_EMAIL'] && $numArray['0']['ALT_EMAIL_STATUS']=='Y')
        {
           $ccEmail =  $numArray['0']['ALT_EMAIL'];    
        }
        else $ccEmail = "";
        }
        else $ccEmail = "";

    $emailSender->send("",'',$ccEmail);
  } //end of InstantEOIMailer
  
  /**
   * Fire instant Reminder mailer
   *
   * <p>
   * This function triggers instant Reminder mailer. The condition to trigger this mailer is that the viewed profile should have entry date within 30 days.
   * </p>
   * 
   * @access public
   * @param $viewedProfileId The profileid of the viewed to whom the mail will be sent.
   * @param $viewerProfileId The profileid of the viewer, who has done the EOI
   * @param $draft The draft message.
   * @param $subscriptionStatus The subscription status of viewed.
   * @return boolean
   */
  public static function InstantReminderMailer($viewedProfileId, $viewerProfileId, $draft, $subscriptionStatus) {

$emailSender = new EmailSender(MailerGroup::EOI, 1756);
    $tpl = $emailSender->setProfileId($viewedProfileId);
    $tpl->getSmarty()->assign("otherProfileId", $viewerProfileId);
    $tpl->getSmarty()->assign("RECEIVER_IS_PAID", $subscriptionStatus);
  	$viewerProfileIdObj = new Profile('',$viewerProfileId);
    if($viewerProfileIdObj->getPROFILE_STATE()->getPaymentStates()->getPaymentStatus() =='EVALUE')
		$paidStatus = "eValue";
	else if($viewerProfileIdObj->getPROFILE_STATE()->getPaymentStates()->getPaymentStatus() =='ERISHTA')
		$paidStatus = "eRishta";
	$smartyObj = $tpl->getSmarty();
	$smartyObj->assign("paidStatus",$paidStatus);
	$smartyObj->assign("count", 1);
	$profileMemcacheServiceObj = new ProfileMemcacheService($viewedProfileId);
	$totalCount = $profileMemcacheServiceObj->get("AWAITING_RESPONSE");
	$smartyObj->assign("totalCount",$totalCount);
    $partialObj = new PartialList();
    $partialObj->addPartial("eoi_profile", "eoi_profile", array($viewerProfileId=>$draft));
    $partialObj->addPartial("jeevansathi_contact_address", "jeevansathi_contact_address");
    $tpl->setPartials($partialObj);
    $emailSender->send();
  } //end of InstantReminderMailer
}

