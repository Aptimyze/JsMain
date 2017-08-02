<?php

/*
 * Author: Esha Jain
 * This task takes all the profiles in FTO.FTO_CURRENT_STATE and change the FTO state of the all the profiles(which has completed the FTO eligible/active period) to FTO expire
*/

class cronFTOStateUpdateTask extends sfBaseTask
{

  protected function configure()
  {
$this->addArguments(array(
        new sfCommandArgument('profileid', sfCommandArgument::REQUIRED, 'My argument'),
        new sfCommandArgument('action', sfCommandArgument::REQUIRED, 'My argument')
        ));

    $this->namespace        = 'cron';
    $this->name             = 'cronFTOStateUpdate';
    $this->briefDescription = 'updates fto state executed via non symfony code';
    $this->detailedDescription = <<<EOF
The [cronDuplication|INFO] task gets  profileid and action as parameter and it updates the fto state of the profile accordingly.
Call it with:

  [php symfony cron:cronFTOStateUpdate] 
EOF;
$this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
     ));

  }

  protected function execute($arguments = array(), $options = array())
  {
	if(!sfContext::hasInstance())
	  sfContext::createInstance($this->configuration);

        $profileid = $arguments["profileid"]; // NEW / EDIT
        $action = $arguments["action"]; // NEW / EDIT
try
{
	$profile = new Profile('',$profileid);
	$profile->getDetail('', '',"*");
	if($action)
        	$profile->getPROFILE_STATE()->updateFTOState($profile, $action);
}
catch(Exception $e)
{
                        $to='esha.jain@jeevansathi.com,nitesh.s@jeevansathi.com';
                        $msg='';
                        $subject="error: ".__FILE__;
                        $msg='error while updating state: profileid: '.$profileid.' and action: '.$action.'<br/>'.$e->getMessage().'<br/><br/>Warm Regards';
                        SendMail::send_email($to, $msg, $subject);
}
  }
}
