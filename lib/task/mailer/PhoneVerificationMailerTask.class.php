<?php

/*
 * Author: Esha Jain
 * This task gets all the profiles with last loggin date before the date passed and remove all such profiles from the knwlarityvno table.
 */

class PhoneVerificationMailerTask extends sfBaseTask
{
  protected function configure()
  {

    $this->namespace        = 'mailer';
    $this->name             = 'PhoneVerificationMailer';
    $this->briefDescription = 'send verification email to all the activated and unverified profiles';
    $this->detailedDescription = <<<EOF
      The [PhoneVerificationMailer|INFO] task collects data and send verification email to all the activated and unverified profiles.
      Call it with:

      [php symfony mailer:PhoneVerificationMailer] 
EOF;
$this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
     ));
  }

  protected function execute($arguments = array(), $options = array())
  {
	if(!sfContext::hasInstance())
		sfContext::createInstance($this->configuration);
	$currentDay = date("D");
	if(strcmp($currentDay,"Sat"))//returns flase if the sting are equal
	{
		$date = date('Y-m-d',mktime(0,0,0,date('m'),date("d")-1,date("Y")));
		$this->date1 = "$date 00:00:00";
		$this->date2 = "$date 23:59:59";
	}
	else
	{
		$date = DateConstants::PhoneMandatoryLive;
		$this->date1 ="$date 00:00:00";
	}
	$profiles = $this->getUnverifiedProfiles();
	if(is_array($profiles))
	{
		$emailSenderObj = new EmailSender(22,1775);
		foreach($profiles as $k=>$profileid)
		{
                        $tplObj=$emailSenderObj->setProfileId($profileid);
                        $p_list=new PartialList;
                        $p_list->addPartial('jeevansathi_contact_address','jeevansathi_contact_address');
                        $tplObj->setPartials($p_list);
                        $emailSenderObj->send();
                        $count++;
                }
                 SendMail::send_email("esha.jain@jeevansathi.com","$count Phone Verification Mailer mandatory sent out","Phone Verification Mailer mandatory cron completed");
	}
  }
  private function getUnverifiedProfiles()
  {
	$mainAdminLogObj =new MAIN_ADMIN_LOG("newjs_slave");
	$profileDetails = $mainAdminLogObj->getUnverifiedActivatedProfiles($this->date1,$this->date2);
	if(!is_array($profileDetails))
		return false;
	$jprofileContactObj = new ProfileContact();
	$valueArray['PROFILEID']=implode(",",$profileDetails);
	$valueArray['ALT_MOB_STATUS']="'Y'";
	$profilesList = $jprofileContactObj->getArray($valueArray,"","","PROFILEID");
	if(is_array($profilesList))
	{
		foreach($profilesList as $k=>$v)
			$neglectedProfiles[] = $v['PROFILEID'];
	}
	$profilesList = array();
	$phoneVerifiedLogObj = new PHONE_VERIFIED_LOG;
	$profilesList = $phoneVerifiedLogObj->getProfilesVerified($profileDetails);
	if(is_array($profilesList))
	{
		foreach($profilesList as $k=>$v)
			$neglectedProfiles[] = $v;
	}
	if(!is_array($neglectedProfiles))
		return $profileDetails;
	foreach($profileDetails as $k=>$v)
	{
		if(!in_array($v,$neglectedProfiles))
			$finalProfiles[] = $v;
	}
	return $finalProfiles;
  }
}
