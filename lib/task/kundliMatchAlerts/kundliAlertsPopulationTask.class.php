<?php
/**
* This will populate/truncate the data used for Kundli Match Alert
*/
class kundliAlertsPopulateTask extends sfBaseTask
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
        
        $this->namespace = 'kundliMatchAlerts';
        $this->name = 'kundliAlertsPopulate';
        $this->briefDescription='';
        $this->detailedDescription = <<<EOF
          Call it with:
          [php symfony kundliMatchAlerts:kundliAlertsPopulate totalScripts currentScript]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        if(!sfContext::hasInstance())
            sfContext::createInstance ($this->configuration);
        ini_set('memory_limit','1024M');

        $totalScripts = $arguments["totalScripts"]; // total no of scripts
        $currentScript = $arguments["currentScript"]; // current script number
        
        $kundliMailerObj = new KUNDLI_ALERT_KUNDLI_MATCHES_MAILER();
         		
		//Truncate table Data        
		$kundliMailerObj->truncateKundliMailerData();       

        //Getting profiles using JPROFILE object
        $jprofileObj = JPROFILE::getInstance('newjs_slave');
        $lastLoginDate = date('Y-m-d', strtotime(kundliMatchAlertMailerEnums::$lastLoginDateCriteria));
        $valueArray = array("activatedKey"=>1,'ACTIVATED'=>"Y,H","MOB_STATUS"=>"Y","INCOMPLETE"=>"N");
        $greaterThanArray = array("LAST_LOGIN_DT"=>$lastLoginDate);
        $excludeArray  = array("BTIME"=>"''","COUNTRY_BIRTH"=>"''","CITY_BIRTH"=>"''");
       
        $detailArr = $jprofileObj->getArray($valueArray,$excludeArray,$greaterThanArray,'PROFILEID','','','','','','','','');

        foreach($detailArr as $key=>$value)
        {
          $profileIdArr[] = $value['PROFILEID'];

          // insert profileId's to Kundli Alerts table
          $kundliMailerObj->insertKundliMailerData($profileIdArr);
          unset($profileIdArr);
        }
            unset($jprofileObj);

        unset($kundliMailerObj);
   }
}