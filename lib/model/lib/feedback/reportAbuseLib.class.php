<?php

class reportAbuseLib
{
// $loginProfile->getPROFILEID()
// $loginProfile->getUSERNAME()
	public static function reportAbuseAction($loginPROFILEID, $loginUSERNAME, $otherProfileId, $categoryNew, $otherReason, $category, $crmUserName, $m_iAbuseAttachmentID){
		(new REPORT_ABUSE_LOG())->insertReport(
			$loginPROFILEID,
			$otherProfileId,
			$categoryNew,
			$otherReason,
			$category,
			$crmUserName,
			// $this->m_iAbuseAttachmentID
			$m_iAbuseAttachmentID
			);
		// block for blocking the reported abuse added by Palash
		$ignore_Store_Obj = new IgnoredProfiles("newjs_master");
		$ignore_Store_Obj->ignoreProfile($loginPROFILEID,$otherProfileId);

		//Entry in Chat Roster
		try {

			$ignoreProfile = new Profile("",$otherProfileId);
			$ignoreProfile->getDetail("","","*");
			$producerObj = new Producer();
			if ($producerObj->getRabbitMQServerConnected()) {
				$chatData = array('process' => 'CHATROSTERS', 'data' => array('type' => 'BLOCK', 'body' => array('sender' => array('profileid'=>$loginPROFILEID,'checksum'=>JsAuthentication::jsEncryptProfilechecksum($loginPROFILEID),'username'=>$loginUSERNAME), 'receiver' => array('profileid'=>$ignoreProfile->getPROFILEID(),'checksum'=>JsAuthentication::jsEncryptProfilechecksum($ignoreProfile->getPROFILEID()),"username"=>$ignoreProfile->getUSERNAME()))), 'redeliveryCount' => 0);
				$producerObj->sendMessage($chatData);
			}
			unset($producerObj);
		} catch (Exception $e) {
			throw new jsException("Something went wrong while sending instant EOI notification-" . $e);
		}

		//End
		ProfileMemcache::clearInstance($loginPROFILEID);
		ProfileMemcache::clearInstance($otherProfileId);
	/*if(stristr($categoryNew, 'Already married/engaged') || stristr($categoryNew,'User is already married / engaged'))
	{	
	$ReportAbuseMailObj = new requestUserToDelete();
	$ReportAbuseMailObj->sendMailForDeletion($otherProfileId,'0');
	}*/
	}
}