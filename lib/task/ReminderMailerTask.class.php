<?php

class ReminderMailerTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    // // add your own options here
    
	$this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),));
    $this->namespace        = 'cron';
    $this->name             = 'ReminderMailer';
    $this->briefDescription = 'FTO Reminder Mailer';
    $this->detailedDescription = <<<EOF
The [ReminderMailer|INFO] task get Profiles which are in C1,C2,C3 state and send Reminder Mail to those.
Call it with:

  [php symfony ReminderMailer|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
		$profileArr = FTOStateHandler::getProfilesInState(FTOStateTypes::FTO_ELIGIBLE);
		$emailSenderObj1 = new EmailSender(MailerGroup::REMINDER1);
		$emailSenderObj2 = new EmailSender(MailerGroup::REMINDER2);
		$reminder1Count=0;
		$reminder2Count=0;
		
		foreach($profileArr as $key=>$val)
		{
			$now = date("Y-m-d");
			$fto_entry = date("Y-m-d", JSstrToTime($val['FTO_ENTRY_DATE']));
			$fto_expiry = date("Y-m-d", JSstrToTime($val['FTO_EXPIRY_DATE']));
			$day = abs(date("U", JSstrToTime($now)) - date("U", JSstrToTime($fto_entry))) / 86400;
			if(($val['DATEDIFF']==FTO_PERIOD::BEFORE_ACTIVE||$val['DATEDIFF']==FTO_PERIOD::BEFORE_ACTIVE+1) && date("Y-m-d",JSstrToTime($val['FTO_EXPIRY_DATE']))==date("Y-m-d"))
			{
					$reminder2Count++;
					$this->sendMail($val['PROFILEID'],MailerGroup::REMINDER2,$emailSenderObj2);
			}
			else if($day%2 == 0 && $fto_expiry >= $now)
			{
					$reminder1Count++;
					$this->sendMail($val['PROFILEID'],MailerGroup::REMINDER1,$emailSenderObj1);
			}
		}
		
		// if script completes successfully send mail
		SendMail::send_email("ntiesh.s@jeevansathi.com,nikhil.dhimana@jeevansathi.com","$reminder1Count reminder 1 mail sent out and $reminder2Count reminder 2 mail sent out.","Reminder Mail cron completed");
  }
  /**
   * 
   * Send e-mail to perosn with Profileid $profileId 
   * @param int $profileId profileId of the receiver
   * @param int $mailGroup MailGroup of the mail (Reminder1 or Reminder2)
   * @param Object $emailSender object of EmailSender class
   * @return void
   */
  public function sendMail($profileId,$mailGroup,$emailSender)
	{           $detailArr ='PROFILEID,USERNAME,GENDER,RELIGION,CASTE,SECT,MANGLIK,MTONGUE,MSTATUS,DTOFBIRTH,OCCUPATION,COUNTRY_RES,CITY_RES,HEIGHT, EDU_LEVEL,EMAIL,ENTRY_DT,MOD_DT,RELATION,COUNTRY_BIRTH,SOURCE,INCOMPLETE,PROMO,DRINK,SMOKE,HAVECHILD,RES_STATUS,BTYPE,COMPLEXION,DIET,HEARD,INCOME,CITY_BIRTH,BTIME,HANDICAPPED,NTIMES,SUBSCRIPTION,SUBSCRIPTION_EXPIRY_DT,ACTIVATED,ACTIVATE_ON,AGE,GOTHRA,GOTHRA_MATERNAL,NAKSHATRA,PHONE_RES,PHONE_MOB,FAMILY_BACK,SUBCASTE,SHOWPHONE_RES,SHOWPHONE_MOB, HAVEPHOTO,PHOTO_DISPLAY,PHOTOSCREEN,PREACTIVATED,PHOTODATE,PINCODE,EDU_LEVEL_NEW,WIFE_WORKING,MARRIED_WORKING,PARENT_CITY_SAME,PARENTS_CONTACT,SHOW_PARENTS_CONTACT,FAMILY_VALUES,SORT_DT,VERIFY_EMAIL,SHOW_HOROSCOPE,GET_SMS,STD,ISD,MOTHER_OCC,T_BROTHER,T_SISTER,M_BROTHER,M_SISTER,FAMILY_TYPE,FAMILY_STATUS,FAMILY_INCOME,CITIZENSHIP,BLOOD_GROUP,HIV,THALASSEMIA,WEIGHT,NATURE_HANDICAP,WORK_STATUS,ANCESTRAL_ORIGIN,HOROSCOPE_MATCH,RASHI,PHONE_WITH_STD,MOB_STATUS,LANDL_STATUS,PHONE_FLAG,PROFILE_HANDLER_NAME,GOING_ABROAD,OPEN_TO_PET,HAVE_CAR,OWN_HOUSE,COMPANY_NAME,HAVE_JCONTACT,HAVE_JEDUCATION,SUNSIGN';
				$profileObj = new Profile('',$profileId);
				$profileObj->getDetail('', '', $detailArr);
                $ftoState=JsCommon::getProfileState($profileObj);
			//	if($profileObj->getINCOMPLETE()=='Y' || $profileObj->getACTIVATED()!='Y'||JsCommon::getProfileState($profileObj)=='P'){
				if($ftoState=='IU'||$ftoState=='P')
					return;
				$tplObj=$emailSender->setProfile($profileObj);
				$p_list=new PartialList;
				$p_list->addPartial('jeevansathi_contact_address','jeevansathi_contact_address');
				$tplObj->setPartials($p_list);
				
				if($mailGroup==MailerGroup::REMINDER2)
				{
					$MailSmarty = $tplObj->getSmarty();
					if(JsCommon::isPhoneValid($profileObj))
						$MailSmarty->assign('INVALID_PHONE','N');
					else	
						$MailSmarty->assign('INVALID_PHONE','Y');
				}			
				$emailSender->send();
				unset($profileObj);
	}
}
