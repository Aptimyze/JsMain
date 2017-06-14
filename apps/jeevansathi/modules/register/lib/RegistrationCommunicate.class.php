<?php
/** This class will send mails and sms to users after registration page 1 and page 2
 * */
class RegistrationCommunicate
{
	/** Send email after page 1 completion, takes profileid as parameter
	 * */
	public static function sendEmailAfterRegistrationIncomplete($profile){
			$email_sender = new EmailSender(MailerGroup::REGISTRATION_PAGE1, 1771);
			$emailTpl = $email_sender->setProfile($profile);
			$email_sender->send();
	}
	/** Send email to user on page 2
	 *
	 * */
   // public static function sendEmailAfterRegCompletion($profileid,$cityRes) {
	    public static function sendEmailAfterRegCompletion($profileid) {
       /* $orgTZ = date_default_timezone_get();
        date_default_timezone_set("Asia/Calcutta");
        $indianTimeStamp = date("Y:m:d:H:i:s");
        date_default_timezone_set($orgTZ);
		$indianTime = explode(':', $indianTimeStamp );
		$weekdayToday = date('w', strtotime($indianTime[0] . '-' . $indianTime[1] . '-' . $indianTime[2] . ' ' . $indianTime[3] . ':' . $indianTime[4] . ':' . $indianTime[5]));		
		//KYC MAILER condition for selected cities
		//if($cityRes=="DE00" ||$cityRes=="HA03" ||$cityRes=="UP12" ||$cityRes=="HA02" ||$cityRes=="UP47" ||$cityRes=="UP25" ||$cityRes=="UK05" ||$cityRes=="UP21" ||$cityRes=="UP06" ||$cityRes=="UP01" || $cityRes=="RA07")
			//$KYC=true;
		//else
			//$KYC=false;
		
		
		// Trac #1625 Starts
		//If it is sunday or profile is registered after 6:30PM or before 7:30AM
		if (($weekdayToday == 0) || ($indianTime[3] > 18) || (($indianTime[3] == 18) && $indianTime[4] >= 30) || ($indianTime[3] < 7) || (($indianTime[3] == 7) && $indianTime[4] <= 30)) {
			/*if($KYC)
			{
				// Send new KYC Screening Mail
				$emailSender = new EmailSender(MailerGroup::SCREENING_KYC,1777);
				$emailSender->setProfileId($profileid);
				$emailSender->send();
			}			
			else
			{*/
				// Send new Screening Mail
				/*$emailSender = new EmailSender(MailerGroup::SCREENING);
				$emailSender->setProfileId($profileid);
				$emailSender->send();
			/*}
		} else {
			if($KYC)
			{
				// Send new KYC Screening Mail
				$emailSender = new EmailSender(MailerGroup::SCREENING_KYC,1776);
				$emailSender->setProfileId($profileid);
				$emailSender->send();
			}			
			else
			{*/
                                
				RegChannelTrack::insertPageChannel($profileid,PageTypeTrack::_PAGE2);
				
				$emailSender = new EmailSender(MailerGroup::SCREENING, 1779);
				$emailSender->setProfileId($profileid);
 				$emailSender->send();
    }
	/** Initiate IVR call for phone verification
	 * */
    public static function initiatePhoneVerification($loginProfile) {
			return true;
			$phone_mob=$loginProfile->getPHONE_MOB();
			$phone_res=$loginProfile->getPHONE_RES();
			$profileid=$loginProfile->getPROFILEID();
			$isdArr=explode('+',$loginProfile->getISD());
			if($isdArr[0]=="+")
				$isd=$isdArr[1];
			else
				$isd=$isdArr[0];
			$std=$loginProfile->getSTD();
			//added by nitesh in registration revamp
			/* As Requirment we have shifted IVR-  Phone No. Verification Code after profile completation in second page
			* Scenarios checked for IVR call: 1. junk number exist (no ivr call)
			2. Duplicate Exist (no ivr call)
			3. ivr call (if neither junk nor duplicate)
			*/
			include_once (sfConfig::get("sf_web_dir")."/ivr/jsPhoneVerify.php");
			include_once (sfConfig::get("sf_web_dir")."/ivr/jsivrFunctions.php");
			if($phone_mob){
				$ivr_phone = $phone_mob;
				$phoneType = 'M';
				$ivr_std = '';
				$ivr_isd=$isd;
			}
			else if($phone_res){
				$ivr_phone 	=$phone_res;
				$phoneType	='L';
				$ivr_isd=$isd;
				$ivr_std =trim($std);
				$ivr_phone	=$phone_res;				
			}
			$chk_junk = chkJunkNumberList($ivr_phone, $phoneType);
			if ($chk_junk) phoneUpdateProcess($profileid, '', $phoneType, 'J');
			/* IVR - code ends */
	}
    public static function sendSms($profileid) {
			/* SMS Code for sending sms to users added by nitesh in registration revamp*/
			include_once(sfConfig::get("sf_web_dir")."/profile/InstantSMS.php");
			
                        //$sms1 = new InstantSMS("REGISTER_CONFIRM", $profileid); Uncommented by Palash To stop these messages JSI-2106
			//$sms1->send();
			$sms2 = new InstantSMS("REGISTER_KYC", $profileid);
			$sms2->send();
			/* Ends Here of SMS code */
	}
}
