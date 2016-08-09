<?php
/**
* This will populate/truncate the data used for featured Profile
*/
class featuredProfilePopulateTask extends sfBaseTask
{
	protected function configure()
    {
        $this->addArguments(array(
                        new sfCommandArgument('totalScripts', sfCommandArgument::REQUIRED, 'My argument'),
                        new sfCommandArgument('currentScript', sfCommandArgument::REQUIRED, 'My argument'),
        	));

		$this->addOptions(array(
		new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
	     ));
        
        $this->namespace = 'featuredProfiles';
        $this->name = 'featuredProfilePopulate';
        $this->briefDescription='';
        $this->detailedDescription = <<<EOF
          Call it with:
          [php symfony featuredProfiles:featuredProfilePopulate totalScripts currentScript]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        if(!sfContext::hasInstance())
            sfContext::createInstance ($this->configuration);
        ini_set('memory_limit','512M');

        $totalScripts = $arguments["totalScripts"]; // total no of scripts
        $currentScript = $arguments["currentScript"]; // current script number
    
        $featuredProfileObj = new FEATURED_PROFILE_MAILER();

        //Truncate table Data       
        $featuredProfileObj->truncateFeaturedProfileData();

        $lastLoginDate = date('Y-m-d', strtotime(featuredProfileMailerEnum::$lastLoginDateCriteria));
        $verifyActivationInCriteria = "( ";
        for($i=0;$i<featuredProfileMailerEnum::$iterationLimit;$i++)
        {
            $noOfDays = featuredProfileMailerEnum::$initialDayCount + $i*(featuredProfileMailerEnum::$constantDayInterval);
            $verifyActivationInCriteria .= "'".date('Y-m-d',strtotime('-'.$noOfDays.' day'))."', "; 
        }
        $verifyActivationInCriteria = rtrim($verifyActivationInCriteria ,", ").")";
        
        
        $valueArray = array("SUBSCRIPTION"=>"''","activatedKey"=>1,'ACTIVATED'=>"Y","MOB_STATUS"=>"Y");
        $greaterThanArray = array("LAST_LOGIN_DT"=>$lastLoginDate);
        $addWhereText = "DATE(VERIFY_ACTIVATED_DT) IN ".$verifyActivationInCriteria." AND EMAIL LIKE 'featuredProfile@js.com%'";

       
        //select from slave
        $jprofileObj = new JPROFILE('newjs_slave');
        $detailArr = $jprofileObj->getArray($valueArray,'',$greaterThanArray,'PROFILEID','','','','','','','',$addWhereText);

        foreach($detailArr as $key=>$value)
        {
          $profileIdArr[] = $value['PROFILEID'];
        }
		    unset($jprofileObj);

        $featuredProfileObj->insertFeaturedProfileData($profileIdArr);
        unset($featuredProfileObj);
    }
}