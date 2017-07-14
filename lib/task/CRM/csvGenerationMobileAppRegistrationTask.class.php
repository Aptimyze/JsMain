<?php

class csvGenerationMobileAppRegistrationTask extends sfBaseTask
{
	protected function configure()
	{

		$this->addOptions(array(
			new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
			));

		$this->namespace        = 'csvGeneration';
		$this->name             = 'mobileAppRegistration';
		$this->briefDescription = '';
		$this->detailedDescription = <<<EOF
		The [csvGeneration:mobileAppRegistration|INFO] task does things.
		Call it with:
		[php symfony csvGeneration:mobileAppRegistration|INFO]
EOF;
	}

	protected function execute($arguments = array(), $options = array())
	{
		// SET BASIC CONFIGURATION
		if(!sfContext::hasInstance()){
			sfContext::createInstance($this->configuration);
		}
		$processObj = new PROCESS();
		$processObj->setProcessName("MOBILE_APP_REGISTRATIONS");
		$csvHandler = new csvGenerationHandler();
		$profiles = $csvHandler->fetchProfiles($processObj);
		$csvHandler->saveProfileSet($processObj,$profiles);	
		echo "Mobile App Registrations CSV Data added to table";	
	}
}
