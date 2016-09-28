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
    
        //creating featured profile store object
        $featuredProfileObj = new FEATURED_PROFILE_MAILER("newjs_masterRep");
        $featuredProfileDDLObj = new FEATURED_PROFILE_MAILER("newjs_masterDDL");

        //Truncate table Data       
        $featuredProfileDDLObj->truncateFeaturedProfileData();
        unset($featuredProfileDDLObj);

        //last login date should not be more than 1 month back
        $lastLoginDate = date('Y-m-d', strtotime(featuredProfileMailerEnum::$lastLoginDateCriteria));

        //verify activation date should be 15 days from current date, and then 15+ (x*60) days
        $verifyActivationInCriteria = "( ";
        for($i=0;$i<featuredProfileMailerEnum::$iterationLimit;$i++)
        {
            $noOfDays = featuredProfileMailerEnum::$initialDayCount + $i*(featuredProfileMailerEnum::$constantDayInterval);
            $verifyActivationInCriteria .= "'".date('Y-m-d',strtotime('-'.$noOfDays.' day'))."', "; 
        }
        $verifyActivationInCriteria = rtrim($verifyActivationInCriteria ,", ").")";
        
        
        $valueArray = array("SUBSCRIPTION"=>"''","activatedKey"=>1,'ACTIVATED'=>"Y,H","MOB_STATUS"=>"Y","INCOMPLETE"=>"N");
        $greaterThanArray = array("LAST_LOGIN_DT"=>$lastLoginDate);
        $addWhereText = "DATE(VERIFY_ACTIVATED_DT) IN ".$verifyActivationInCriteria;

       
        //select from slave
        $jprofileObj = JPROFILE::getInstance('newjs_slave');
        $detailArr = $jprofileObj->getArray($valueArray,'',$greaterThanArray,'PROFILEID','','','','','','','',$addWhereText);
        foreach($detailArr as $key=>$value)
        {
          $profileIdArr[] = $value['PROFILEID'];

          // insert profileId's to featured Profile table
          $featuredProfileObj->insertFeaturedProfileData($profileIdArr);
          unset($profileIdArr);
        }
		    unset($jprofileObj);

        unset($featuredProfileObj);
    }
}
