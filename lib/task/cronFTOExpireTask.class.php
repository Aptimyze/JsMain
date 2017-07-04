<?php

/*
 * Author: Esha Jain
 * This task takes all the profiles in FTO.FTO_CURRENT_STATE and change the FTO state of the all the profiles(which has completed the FTO eligible/active period) to FTO expire
 */

class cronFTOExpireTask extends sfBaseTask
{
  protected function configure()
  {

    $this->namespace        = 'cron';
    $this->name             = 'cronFTOExpire';
    $this->briefDescription = 'expires fto state on if Fto period for the profile is over';
    $this->detailedDescription = <<<EOF
      The [cronDuplication|INFO] task gets all the profiles for which duplication check needs to be done and runs that particular duplication check.
      Call it with:

      [php symfony cron:cronFTOExpire] 
EOF;
$this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
     ));
  }

  protected function execute($arguments = array(), $options = array())
  {
	if(!sfContext::hasInstance())
		sfContext::createInstance($this->configuration);

  	$detailArray = FTOStateHandler::getNonCompleteExpiredProfilesWithExpiryNow();//by complete expired, i am refering to profiles in e1 and e2 state, so this function returns all profiles except thos in state e1 and e2
    foreach($detailArray as $k=>$v)
	$profileArray[] = $v['PROFILEID'];

     $jprofileObj = new JPROFILE;
     $incompleteProfiles = array();
     if($profileArray)
     {
     	foreach($profileArray as $k=>$profileid)
	{
        	if($k!=0)
                	$valueArray['PROFILEID'].=", ";
		$valueArray['PROFILEID'].="'".$profileid."'";
	}
     }
     $incompleteProfiles = array();
     if($valueArray && FTOLiveFlags::IS_FTO_LIVE)
     {
     	$returnArr=$jprofileObj->getArray($valueArray,'','','PROFILEID, INCOMPLETE, ACTIVATED');
        if($returnArr)
        {
        	foreach($returnArr as $k=>$result)
                {
                	if($result['INCOMPLETE']=='Y' || $result['ACTIVATED']=='U')
                        	$incompleteProfiles[] = $result['PROFILEID'];
		}
        }
      }

    $email_sender = new EmailSender(MailerGroup::EXPIRY);

    foreach($detailArray as $k=>$profileFtoDetail)
    {
      if(!in_array($profileFtoDetail['PROFILEID'],$incompleteProfiles))
      {
      	if($profileFtoDetail['DATEDIFF']==FTO_PERIOD::BEFORE_ACTIVE|| $profileFtoDetail['DATEDIFF']==FTO_PERIOD::BEFORE_ACTIVE+1)
		$profilesBeforeActiveExpire[]=$profileFtoDetail['PROFILEID'];
      	elseif($profileFtoDetail['DATEDIFF']==FTO_PERIOD::AFTER_ACTIVE||$profileFtoDetail['DATEDIFF']==FTO_PERIOD::AFTER_ACTIVE+1)
        	$profilesAfterActiveExpire[]=$profileFtoDetail['PROFILEID'];
      }
    }

try
{
    if($profilesBeforeActiveExpire)
      FTOStateHandler::updateStateOfAllProfiles($profilesBeforeActiveExpire,FTOStateTypes::FTO_EXPIRED,FTOSubStateTypes::FTO_EXPIRED_BEFORE_ACTIVATED,"ELIGIBLE_TIME_UP");
    if($profilesAfterActiveExpire)
      FTOStateHandler::updateStateOfAllProfiles($profilesAfterActiveExpire, FTOStateTypes::FTO_EXPIRED,FTOSubStateTypes::FTO_EXPIRED_AFTER_ACTIVATED,"ACTIVE_TIME_UP");
}
catch(Exception $e)
{
                        $to='esha.jain@jeevansathi.com,nitesh.s@jeevansathi.com';
                        $msg='';
                        $subject="error: ".__FILE__;
                        $msg='error while marking expired: query execution<br/>'.$e->getMessage().'<br/><br/>Warm Regards';
			SendMail::send_email($to, $msg, $subject);
}
/*
try
{
    // E1 USERS
    if ($profilesBeforeActiveExpire) {
      $email_sender->bulkSend($profilesBeforeActiveExpire, array(array("jeevansathi_contact_address", "jeevansathi_contact_address")));
    }
    // E2 USERS
    if ($profilesAfterActiveExpire) {
      $email_sender->bulkSend($profilesAfterActiveExpire, array(array("jeevansathi_contact_address", "jeevansathi_contact_address")));
    }
}
catch(Exception $e)
{
                        $to='esha.jain@jeevansathi.com,tanu.gupta@jeevansathi.com';
                        $msg='';
                        $subject="error: ".__FILE__;
                        $msg='error while marking expired: mail firing<br/>'.$e->getMessage().'<br/><br/>Warm Regards';
			SendMail::send_email($to, $msg, $subject);
}
*/
  }
}
