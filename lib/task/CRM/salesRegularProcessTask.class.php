<?php

class salesRegularProcessTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    // // add your own options here
     $this->addOptions(array(
       new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
     ));

    $this->namespace        = 'csvGeneration';
    $this->name             = 'salesRegularProcess';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [salesRegularProcess|INFO] task does things.
Call it with:

  [php symfony salesRegularProcess|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
        /* Code section for regular Sales process 
           Code section used in case of large data-set          
        */
	ini_set('max_execution_time',0);
	ini_set('memory_limit',-1);
	if(!sfContext::hasInstance())
                sfContext::createInstance($this->configuration);

                $csvHandler=new csvGenerationHandler();
                $processObj=new PROCESS();
                $processObj->setProcessName("SALES_REGULAR");
                $csvHandler->removeOldProfiles($processObj);

		// Section to fetch campaign csv details 
		$largeFileData 	=$csvHandler->fetchLargeFileData();
		$processObj->setLimit($largeFileData['DATA_LIMIT']);
		$processObj->setLeadIdSuffix($largeFileData['LEAD_ID_SUFFIX']);
		$campaignCntArr	=$csvHandler->fetchCampaignCntArr();
		$processObj->setCampaignCntArr($campaignCntArr);
		$max_dt         =date("Y-m-d",time()-2*24*60*60);
		$processObj->setEndDate($max_dt);	

		// Section to use temporary table for filtering large data-set
                $profiles 	=$csvHandler->fetchProfiles($processObj);
		$csvHandler->storeTemporaryProfiles($processObj,$profiles);
		unset($profiles);
                $csvHandler->preFilter($processObj);
		$profiles 	=$csvHandler->fetchTemporaryProfiles($processObj);
		
		// Normal execution of profile in csv generation
		// logging array defined below		
		$filter = array("campaignUndefinedCnt"=>0,"jprofileCnt"=>0, "dncCnt"=>0, "premiumIncomeCnt"=>0, "alertsCnt"=>0, "dispositionValidityCnt"=>0, "scoreValidityCnt"=>0,"dataLimitExceedCnt"=>0, "nonOptinProfileCnt"=>0, "campaignUndefinedCnt_L"=>0, "jprofileCnt_L"=>0, "dncCnt_L"=>0, "premiumIncomeCnt_L"=>0, "alertsCnt_L"=>0, "dispositionValidityCnt_L"=>0, "scoreValidityCnt_L"=>0, "dataLimitExceedCnt_L"=>0, "nonOptinProfileCnt_L"=>0);

		foreach($profiles as $key=>$profileid){
			$profilesDetail	=$csvHandler->fetchProfilesDetail($processObj,array($profileid));
			$eligibleProfiles =$csvHandler->filterProfiles($processObj,$profilesDetail,$filter);
			$csvHandler->saveProfileSet($processObj,$eligibleProfiles);	
		}

		// Used for logging part and sending email alert
		$totalCnt 		=count($profiles);
		$latestProfilesCnt 	=$csvHandler->getTemporaryProfilesCount($max_dt);
		$csvHandler->updateSalesLog2($totalCnt, $latestProfilesCnt, $max_dt, $filter);

		mail("rohan.mathur@jeevansathi.com,anamika.singh@jeevansathi.com,rajeev.joshi@jeevansathi.com,manoj.rana@naukri.com,vibhor.garg@jeevansathi.com","Revamp CSVs for calling executed successfully","","From:JeevansathiCrm@jeevansathi.com");	

	        $incSRLObj 	=new incentive_SALES_REGULAR_LOG();
	        $latest_date 	=$incSRLObj->getLatestDate();
	        $data 		=$incSRLObj->getAllDataForGivenDate($latest_date);
	        $to 		="rohan.mathur@jeevansathi.com,anamika.singh@jeevansathi.com,rajeev.joshi@jeevansathi.com";
	        $from 		="JeevansathiCrm@jeevansathi.com";
	        $subject 	="CSVs FILTERED LOG for ".date("jS F Y", strtotime($latest_date));
	        $csvObj 	=new csvGenerationHandler();
	       	$csvObj->sendEmailAlert($data, $to, $from, $subject);
  }
}
