<?php

class cronLocationAgeRegistrationMisTask extends sfBaseTask
{
	protected function configure()
  {
    $this->namespace        = 'cron';
    $this->name             = 'cronLocationAgeRegistrationMis';
    $this->briefDescription = 'This cron fetches data to be displayed for LocationAgeRegistration Mis';
    $this->detailedDescription = <<<EOF
The [cronLocationAgeRegistrationMis|INFO] ADD DESCRIPTION.
Call it with:

  [php symfony cron:cronLocationAgeRegistrationMis] 
EOF;
$this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
     ));

  }

  protected function execute($arguments = array(), $options = array())
  {
   if(!sfContext::hasInstance())
     sfContext::createInstance($this->configuration);
   $memObject = JsMemcache::getInstance();
   $params = $memObject->get("MIS_PARAMS_KEY");
   $memObject->delete("MIS_PARAMS_KEY");
   $registrationMisObj = new cityAgeRegistrationMis();
   $groupData = $registrationMisObj->getRegistrationMisData($params);
   $memObject->set($params['memKeySet'],$groupData,'43200');
 }
}