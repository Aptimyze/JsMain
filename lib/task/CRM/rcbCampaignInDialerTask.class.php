<?php

class rcbCampaignInDialerTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    // // add your own options here
    // $this->addOptions(array(
    //   new sfCommandOption('my_option', null, sfCommandOption::PARAMETER_REQUIRED, 'My option'),
    // ));
     $this->addOptions(array(
       new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','operations'),
     ));

    $this->namespace        = 'csvGeneration';
    $this->name             = 'rcbCampaignInDialer';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [rcbCampaignInDialer|INFO] task does things.
Call it with:

  [php symfony rcbCampaignInDialer|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
        sfContext::createInstance($this->configuration);
        $processObj	=new PROCESS();
	$csvHandler	=new csvGenerationHandler();

        $processObj->setProcessName("rcbCampaignInDialer");
        $processObj->setMethod("RCB_WEBMASTER_LEADS");
        $processObj->setSubMethod("RCB_WEBMASTER_LEADS");

	$processId =16;
	$processObj->setIdAllot($processId);
	$lastHandledDtObj =new incentive_LAST_HANDLED_DATE();
	$startDt =$lastHandledDtObj->getHandledDate($processId);
	// get IST	
	//$endDt  =date("Y-m-d H:i:s", time());
	$crmUtilityObj =new crmUtility();
	$endDt =$crmUtilityObj->getIST();
	$processObj->setStartDate($startDt);
	$processObj->setEndDate($endDt);

       	$largeFileData  =$csvHandler->fetchLargeFileData();
       	$processObj->setLeadIdSuffix($largeFileData['LEAD_ID_SUFFIX']);

	$csvHandler->removeOldProfiles($processObj);
	$profiles =$csvHandler->fetchProfiles($processObj);
	if(count($profiles)>0){
		foreach($profiles as $key=>$profileid){
			if($profileid){
				$profilesDetail =$csvHandler->fetchProfilesDetail($processObj,array($profileid));
				$eligibleProfiles =$csvHandler->filterProfiles($processObj,$profilesDetail,$filter);
		                $csvHandler->saveProfileSet($processObj,$eligibleProfiles);
			}	
		}
	}
	$lastHandledDtObj->setHandledDate($processId,$endDt);

  }
}
