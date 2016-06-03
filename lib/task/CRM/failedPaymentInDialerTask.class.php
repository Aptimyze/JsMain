<?php

class failedPaymentInDialerTask extends sfBaseTask
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
    $this->name             = 'failedPaymentInDialer';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [failedPaymentInDialer|INFO] task does things.
Call it with:

  [php symfony failedPaymentInDialer|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
        sfContext::createInstance($this->configuration);
        $processObj	=new PROCESS();
	$csvHandler	=new csvGenerationHandler();

        $processObj->setProcessName("failedPaymentInDialer");
        $processObj->setMethod("NEW_FAILED_PAYMENT");
        $processObj->setSubMethod("NEW_FAILED_PAYMENT");

	$processId =15;
	$processObj->setIdAllot($processId);
	$lastHandledDtObj =new incentive_LAST_HANDLED_DATE();
	$csvStartDt =$lastHandledDtObj->getHandledDate($processId);

        $startDt =date("Y-m-d H:i:s", time()-10*60);
        $endDt 	 =date("Y-m-d H:i:s", time()-05*60);
	$processObj->setStartDate($startDt);
	$processObj->setEndDate($endDt);
	//$csvStartDt =$startDt;

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
				$eligibleProfiles[0]['SERVICE_SELECTED'] 	=$data['SERVICES'];
				$eligibleProfiles[0]['FP_ENTRY_DT'] 		=$data['FP_ENTRY_DT'];
				$eligibleProfiles[0]['DISCOUNT'] 		=$data['DISCOUNT'];
				$eligibleProfiles[0]['LAST_AMOUNT_TRIED'] 	=$data['NET_AMOUNT'];
				$eligibleProfiles[0]['SOURCE']			=$data['SOURCE'];
				$eligibleProfiles[0]['WEB_LEAD']                =$data['WEB_LEAD'];
		                $csvHandler->saveProfileSet($processObj,$eligibleProfiles);
			}	
		}
	}
	// Generate CSV
	$processName =$processObj->getProcessName();	
	$dateSet =$csvStartDt."#".$endDt;		
        $csvHandler->generateCSV($processName,$dateSet);

        //Code added to copy FP Dialer csv file to Dialer
        usleep(3000000);
        $sourceDir = JsConstants::$docRoot.'/uploads/csv_files/failedPaymentInDialer.dat';
        $destDir = JsConstants::$docRoot.'/uploads/csv_files/fpdialer/';
        passthru("cp $sourceDir $destDir", $return_var);
	if($return_var){
		// error in copy command
		mail("manoj.rana@naukri.com,dheeraj.negi@naukri.com","ERROR: FP Dialer csv not copied on Dialer","","From:JeevansathiCrm@jeevansathi.com");
	}
	else
		$lastHandledDtObj->setHandledDate($processId,$endDt);

  }
}
