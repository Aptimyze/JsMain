<?php

class cronCampaignsRegistrationMisTask extends sfBaseTask
{
	protected function configure()
  {
    $this->namespace        = 'cron';
    $this->name             = 'cronCampaignsRegistrationMis';
    $this->briefDescription = 'This cron fetches data to be displayed for LocationAgeRegistration Mis';
    $this->detailedDescription = <<<EOF
The [cronCampaignsRegistrationMis|INFO] ADD DESCRIPTION.
Call it with:

  [php symfony cron:cronCampaignsRegistrationMis] 
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
   $params = $memObject->get("MIS_CAMPAIGN_PARAMS_KEY");
   $memObject->delete("MIS_CAMPAIGN_PARAMS_KEY");
   $registrationMisObj = new CampaignsRegistrationMis();
   $groupData = $registrationMisObj->getRegistrationMisData($params);
   $memObject->set($params['memKeySet'],$groupData,'43200');
 }
}