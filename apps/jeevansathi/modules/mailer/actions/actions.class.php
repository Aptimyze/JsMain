<?php

/**
 * mailer actions updates email tracking data.
 *
 * @package    jeevansathi
 * @subpackage mailer
 * @author     akash
 */
class mailerActions extends sfActions
{
 /**
  * Executes Update action and shows image
  * @param Array of checksum, logic used,sent date,frequency
  */
  public function executeOpenRate($request)
  {
	$checksum = $request->getParameter('checksum');  // contains profileID
	$logic_used = $request->getParameter('logic_used');
	$sent_date = $request->getParameter('sent_date'); // No of days from 1 Jan 2006
	$freq = $request->getParameter('freq');
	$email = $request->getParameter('email');
	$stype = $request->getParameter('stype');
	
	
	$getprofileid = new JsAuthentication();
	$profileId = $getprofileid->jsDecryptProfilechecksum($checksum); // Get profile ID from check Sum
	
	if(!$profileId)
	{$profileId=0;}
	if($logic_used && is_numeric($logic_used))
	{
		if($sent_date && !is_numeric($sent_date))
			$sent_date = "";
		if($freq && !is_numeric($freq))
			$freq = "";		
		$emailView = new EmailViewCount();
		$gap = $emailView->getLogicalDate(); // No of days from 1 Jan 2006 to date when email opened
		$dated=$emailView->getDateFromLogicalDate($sent_date); // Sent date from logical Date
		$openEmailData=array('csa'=>$profileId,'date'=>$gap,'logic'=>$logic_used,'sent'=>$sent_date,'freq'=>$freq,'email'=>strtoupper($email));
		// Added stype and respective condition to track new matches email
		if($stype == SearchTypesEnums::NewMatchesEmail)
			$res = $emailView->openNewMatchesEmailTracking($openEmailData);
		else	
			$res = $emailView->increment($openEmailData,$dated);
	
	}
	header('Content-type: image/gif');
	$path = JsConstants::$docRoot."/images/symfonyMailer/spacer.gif";
	readfile($path);
	die;

  }
}
