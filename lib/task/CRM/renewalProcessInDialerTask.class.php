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
        $max_dt         =date("Y-m-d",time()-2*24*60*60);

        $processObj->setProcessName("renewalProcessInDialer");
        $processObj->setMethod("RENEWAL");
        $processObj->setSubMethod("RENEWAL");
        $processObj->setEndDate($max_dt);

       	$largeFileData  =$csvHandler->fetchLargeFileData();
       	$processObj->setLeadIdSuffix($largeFileData['LEAD_ID_SUFFIX']);
	$profiles =$csvHandler->fetchProfiles($processObj);
        $csvHandler->removeOldProfiles($processObj);
        // logging array defined below		
	$filter = array("notActivatedCnt"=>0,"invalidPhoneCnt"=>0, "maleAgeCnt"=>0, "nriCnt"=>0, "nonOptinProfileCnt"=>0, "noPhoneExistsCnt"=>0,"noPhoneCnt"=>0,"lastLoginCnt"=>0, "notActivatedCnt_L"=>0,"invalidPhoneCnt_L"=>0, "maleAgeCnt_L"=>0, "nriCnt_L"=>0, "nonOptinProfileCnt_L"=>0, "noPhoneExistsCnt_L"=>0,"noPhoneCnt_L"=>0,"lastLoginCnt_L"=>0);
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
        // Used for logging part and sending email alert
	$totalCnt 		=count($profiles);
	$latestProfilesCnt 	=$csvHandler->getTemporaryFPProfilesCount($max_dt,'renewalProcessInDialer');
	$csvHandler->updateFPLogs($totalCnt, $latestProfilesCnt, $max_dt, $filter,'renewalProcessInDialer');
  }
}
