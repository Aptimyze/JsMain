<?php

class paidCampaignProcessTask extends sfBaseTask
{
  protected function configure()
  {
     $this->addOptions(array(
       new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','operations'),
     ));

    $this->namespace        = 'csvGeneration';
    $this->name             = 'paidCampaignProcess';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [paidCampaignProcess|INFO] task does things.
Call it with:

  [php symfony paidCampaignProcess|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
	ini_set('memory_limit',-1);
        sfContext::createInstance($this->configuration);
        $processObj	=new PROCESS();
	$csvHandler	=new csvGenerationHandler();

        $processObj->setProcessName("paidCampaignProcess");
        $processObj->setMethod("PAID_CAMPAIGN");
        $processObj->setSubMethod("PAID_CAMPAIGN");

        $largeFileData  =$csvHandler->fetchLargeFileData();
        $processObj->setLeadIdSuffix($largeFileData['LEAD_ID_SUFFIX']);
	$csvHandler->removeOldProfiles($processObj);
        $profiles =$csvHandler->fetchProfiles($processObj);

        // pre-filter logic
        if(count($profiles)>0)
                $profiles =$csvHandler->preFilter($processObj, $profiles);

        if(count($profiles)>0){
                foreach($profiles as $key=>$data){
                        $profileid =$data['PROFILEID'];
                        if($profileid){
                                $profilesDetail =$csvHandler->fetchProfilesDetail($processObj,array($profileid));
                                $eligibleProfiles =$csvHandler->filterProfiles($processObj,$profilesDetail,$filter);
                                $eligibleProfiles[0]['SERVICEID'] =$data['SERVICEID'];
                                $eligibleProfiles[0]['PAYMENT_ENTRY_DT'] =$data['ENTRY_DT'];
                                $csvHandler->saveProfileSet($processObj,$eligibleProfiles);
                        }
                }
        }
  }

}
