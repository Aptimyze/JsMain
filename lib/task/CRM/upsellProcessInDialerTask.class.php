<?php

class upsellProcessInDialerTask extends sfBaseTask
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
    $this->name             = 'upsellProcessInDialer';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [failedPaymentInDialer|INFO] task does things.
Call it with:

  [php symfony upsellProcessInDialer|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
        sfContext::createInstance($this->configuration);
        $processObj	=new PROCESS();
	$csvHandler	=new csvGenerationHandler();

        $processObj->setIdAllot('12');
        $processObj->setProcessName("upsellProcessInDialer");
        $processObj->setMethod("UPSELL");
        $processObj->setSubMethod("UPSELL");

       	$largeFileData  =$csvHandler->fetchLargeFileData();
       	$processObj->setLeadIdSuffix($largeFileData['LEAD_ID_SUFFIX']);
	$profiles =$csvHandler->fetchProfiles($processObj);
        // pre-filter logic
	if(count($profiles)>0)
	        $profiles =$csvHandler->preFilter($processObj, $profiles);
	if(count($profiles)>0){
		foreach($profiles as $key=>$data){
			$profileid =$data['PROFILEID'];
			if($profileid){
				$profilesDetail =$csvHandler->fetchProfilesDetail($processObj,array($profileid),'',$data['BILLID']);
				$eligibleProfiles =$csvHandler->filterProfiles($processObj,$profilesDetail,$filter);
				$eligibleProfiles[0]['SERVICE_SELECTED'] =$data['SERVICEID'];
				$eligibleProfiles[0]['PAYMENT_ENTRY_DT'] =$data['ENTRY_DT'];
				$eligibleProfiles[0]['DISCOUNT']         =$data['DISCOUNT'];
		                $csvHandler->saveProfileSet($processObj,$eligibleProfiles);
			}	
		}
	}
	$lastHandledDtObj =new incentive_LAST_HANDLED_DATE();
	$processId =$processObj->getIdAllot();
	$endDate =$processObj->getEndDate();
	$lastHandledDtObj->setHandledDate($processId,$endDate);

	// Generate CSV
	$processName =$processObj->getProcessName();	
        $startDt     =$processObj->getStartDate();
        $endDt       =$processObj->getEndDate();
	$dateSet     =$startDt."#".$endDt;		
        $csvHandler->generateCSV($processName,$dateSet);

        //Code added to copy FP Dialer csv file to Dialer
        usleep(3000000);
        $sourceDir = JsConstants::$docRoot.'/uploads/csv_files/upsellDataInDialer.dat';
        $destDir = JsConstants::$docRoot.'/uploads/csv_files/fpdialer/';
        passthru("cp $sourceDir $destDir", $return_var);
	if($return_var){
		// error in copy command
		mail("manoj.rana@naukri.com,vibhor.garg@jeevansathi.com,dheeraj.negi@naukri.com","ERROR: Upsell Dialer csv not copied on Dialer","","From:JeevansathiCrm@jeevansathi.com");
	}

  }
}
