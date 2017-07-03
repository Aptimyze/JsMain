<?php

class renewalProcessInDialerTask extends sfBaseTask
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
    $this->name             = 'renewalProcessInDialer';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [renewalProcessInDialer|INFO] task does things.
Call it with:

  [php symfony csvGeneration:renewalProcessInDialer|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
        sfContext::createInstance($this->configuration);
        $processObj	=new PROCESS();
	$csvHandler	=new csvGenerationHandler();

        $processObj->setProcessName("renewalProcessInDialer");
        $processObj->setMethod("RENEWAL");
        $processObj->setSubMethod("RENEWAL");

       	$largeFileData  =$csvHandler->fetchLargeFileData();
       	$processObj->setLeadIdSuffix($largeFileData['LEAD_ID_SUFFIX']);
	$profiles =$csvHandler->fetchProfiles($processObj);
        
	// pre-filter logic
	if(count($profiles)>0){
            $csvHandler->storeTemporaryProfiles($processObj,$profiles);
            $profiles =$csvHandler->preFilter($processObj, $profiles);
        }
	if(count($profiles)>0){
		foreach($profiles as $key=>$data){
			$profileid =$data['PROFILEID'];
			if($profileid){
				$profilesDetail =$csvHandler->fetchProfilesDetail($processObj,array($profileid));
				$eligibleProfiles =$csvHandler->filterProfiles($processObj,$profilesDetail,$filter);
				$eligibleProfiles[0]['EXPIRY_DT'] =$data['EDATE'];
		                $csvHandler->saveProfileSet($processObj,$eligibleProfiles);
			}	
		}
	}
  }
}
