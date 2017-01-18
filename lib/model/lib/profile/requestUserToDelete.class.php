<?php
/**
 * Library For Sending request to delete profile.
 * 
 */
 
/**
 *
 * @package    jeevansathi
 * @subpackage profile
 * @version    16-01-2017
 */
class requestUserToDelete 
{


		public function deleteRequestedBySelf(sfWebRequest $request)
	{	

		 $profileid = $request->getParameter('pfID');

		 //$profileid = $request->getParameter();
		 include_once(JsConstants::$docRoot."/profile/InstantSMS.php");
		 $sms = new InstantSMS("REQ_CRM_DEL_SELF", $profileid); 
         $sms->send();
         $request->setParameter('selfDEL','1');
         $this->sendMailForDeletion($request);

	}


		public function deleteRequestedByOther(sfWebRequest $request)
	{	
		$profileid = $request->getParameter('pfID');
		$sms = new InstantSMS("REQ_CRM_DEL_SELF", $profileid);
        $sms->send();
        $request->setParameter('selfDEL','0');
        $this->sendMailForDeletion($request);
	}


		public function sendMailForDeletion(sfWebRequest $request,$sendTo='')
	{

		$profileId = $request->getParameter('pfID');
		if(!$profileId)return false;
		$emailSender = new EmailSender(MailerGroup::TOP8, '1846');
        $tpl = $emailSender->setProfileId($profileId);
		$memObject=JsMemcache::getInstance();
		$comingFrom = $request->getParameter('selfDEL');
//		$instanceID=$memObject->get('emailVerInstanceId');	
/*
		if(!$instanceID)
		{
			$countObj=new jeevansathi_mailer_DAILY_MAILER_COUNT_LOG();
			$instanceID = $countObj->getID('EMAIL_VER_MAILER');
			$memObject->remove('emailVerInstanceId');
			$memObject->set('emailVerInstanceId',$instanceID,3600*24);
		}
*/
//        die($emailSender->getProfile()->getEMAIL());
        $tpl->getSmarty()->assign("profileid", $profileId);
        $tpl->getSmarty()->assign("instanceID", $instanceID);

        if($comingFrom == '1'){  
        $subject = "Delete your profile on Jeevansathi";
        $tpl->getSmarty()->assign("emailType", 1);
    	}
    	else{
        $subject = "Are you already married/engaged? Please delete your profile on Jeevansathi";
        $tpl->getSmarty()->assign("emailType", 2);
    	}
        

		$partialObj = new PartialList();
        $partialObj->addPartial("jeevansathi_contact_address", "jeevansathi_contact_address");
        $tpl->setPartials($partialObj);
        //$EmailTemplateObj= new EmailTemplate(1796);
        $date= strtotime(date("Y-m-d"));
    	$date = date('d M ', $date);
		$tpl->setSubject($subject);
		$sendTo = 'ayushsethi.js@gmail.com';
        $emailSender->send($sendTo);
        $status = $emailSender->getEmailDeliveryStatus();
       	(new MAIL_EMAIL_VER_MAILER())->makeEntry($profileId,$status);
        return true;

	}
/*
	public function logThis($crmUser,$userPFID,$requestedBy) 
	{  
		
		try
		{
			if(!$crmUser || !$userPFID || !$requestedBy)
				return;
			$timeNow=(new DateTime)->format('Y-m-j H:i:s');
			$sql="INSERT INTO MIS.REQUEST_DELETIONS_LOG(DATE,CRM_USER,REPORTEE,REQUESTED_BY) VALUES(:DATE,:CRM_USER,:REPORTEE,:REQUESTED_BY)";
			
			$pdoStatement = $this->db->prepare($sql);
			
			
			$pdoStatement->bindValue(":REPORTEE",$userPFID,PDO::PARAM_INT);
			$pdoStatement->bindValue(":REQUESTED_BY",$requestedBy,PDO::PARAM_STR);
			$pdoStatement->bindValue(":DATE",$timeNow,PDO::PARAM_STR);
			$pdoStatement->bindValue(":CRM_USER",$crmUser,PDO::PARAM_STR);
			$pdoStatement->execute();
			
			return ;
		}
		catch(Exception $e)
		{
			throw new jsException($e);
		}
	}
*/

}	