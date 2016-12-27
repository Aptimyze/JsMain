<?php

class sugarcrmLtfProcessTask extends sfBaseTask
{
    protected function configure()
    {
        $this->addOptions(array(
           new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
        ));

        $this->namespace        = 'csvGeneration';
        $this->name             = 'sugarcrmLtfProcess';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
The [sugarcrmLtfProcess|INFO] task does things.
Call it with:

  [php symfony sugarcrmLtfProcess|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        if(!sfContext::hasInstance())
            sfContext::createInstance($this->configuration);

        $csvHandler=new csvGenerationHandler();
        $processObj=new PROCESS();
        $processObj->setProcessName("SUGARCRM_LTF");
        $csvHandler->removeOldProfiles($processObj);
	$processObj->setMethod("SUGARCRM_LTF_LEADS");
	$mmScoreArr =$csvHandler->getLeadScore();                                      
	$processObj->setScore($mmScoreArr);

        // for mobile leads profile
        $processObj->setSubMethod("LTF_MOBILE_LEADS");
        $mobile_leads=$csvHandler->fetchProfiles($processObj);
        if(count($mobile_leads)){
            for($i=0; $i<count($mobile_leads); $i++)
            {
                $processObj->setIdAllot($mobile_leads[$i]);
                $profileDetail = $csvHandler->fetchProfilesDetail($processObj);
				if($profileDetail && $csvHandler->filterMalesWhoseAgeGreaterThanTwentyThree($processObj, $profileDetail))
	                $csvHandler->saveProfileSet($processObj,$profileDetail);    
            }            
        }

        // for others leads profile
        $processObj->setSubMethod("LTF_OTHER_LEADS");
        $others_leads=$csvHandler->fetchProfiles($processObj);
        if(count($others_leads)){
            for($i=0; $i<count($others_leads); $i++)
            {
                $processObj->setIdAllot($others_leads[$i]);
                $profileDetail =$csvHandler->fetchProfilesDetail($processObj); 
				if($profileDetail && $csvHandler->filterMalesWhoseAgeGreaterThanTwentyThree($processObj, $profileDetail))
	                $csvHandler->saveProfileSet($processObj,$profileDetail);   
            }
        }
	$start_time=date("Y-m-d H:i:s");
        mail("manoj.rana@naukri.com","NEW LTF CSV Generation Completed At $start_time<EOD>","","From:JeevansathiCrm@jeevansathi.com");
	mail("rohan.mathur@jeevansathi.com,manoj.rana@naukri.com","LTF CSVs executed successfully","","From:JeevansathiCrm@jeevansathi.com");
    }
}
