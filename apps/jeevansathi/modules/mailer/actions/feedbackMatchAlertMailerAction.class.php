<?php

/**
 * feedback for match alert mailer
 *
 * @package    jeevansathi
 * @subpackage mailer
 * @author     sanyam
 */

class feedbackMatchAlertMailerAction extends sfActions
{
	public function execute($request)
	{	
		$loggedInProfileObj = LoggedInProfile::getInstance('');
		$this->profileid = $loggedInProfileObj->getPROFILEID();
		$this->mailSentDate = $request->getParameter('mailSentDate'); //date on which match alert mail was sent
		$this->stype = $request->getParameter('stype'); //stype
		$this->feedbackValue = $request->getParameter('feedbackValue'); //feedback value given by user
		$this->checksum = $request->getParameter('chksum');
		$this->echecksum = JsAuthentication::jsEncrypt($this->profileid,"");
		$this->feedbackTime = date("Y-m-d H:i:s"); //time when feedback given
		$this->matchAlertLink = $request->getParameter('matchAlertLink'); //redirection link
		$this->redirectLink = $this->matchAlertLink."/".$this->echecksum."/".$this->checksum."?From_Mail=Y&stype=".$this->stype."&clicksource=matchalert1";
		$matchAlertFeedbackObj = new matchAlertFeedback();
		$matchAlertFeedbackObj->insertMatchAlertFeedback($this->profileid,$this->mailSentDate,$this->stype,$this->feedbackValue,$this->feedbackTime);
	}
}
