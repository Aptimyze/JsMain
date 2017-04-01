<?php

class cronFieldSalesExecutiveEfficiencyMisTask extends sfBaseTask
{
	protected function configure()
  {
    $this->namespace        = 'cron';
    $this->name             = 'cronFieldSalesExecutiveEfficiencyMis';
    $this->briefDescription = 'This cron fetches data to be displayed for cronFieldSalesExecutiveEfficiencyMis Mis';
    $this->detailedDescription = <<<EOF
The [cronFieldSalesExecutiveEfficiencyMis|INFO] ADD DESCRIPTION.
Call it with:

  [php symfony cron:cronFieldSalesExecutiveEfficiencyMis] 
EOF;
$this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'operations'),
     ));

  }

  protected function execute($arguments = array(), $options = array())
  {
   if(!sfContext::hasInstance())
     sfContext::createInstance($this->configuration);
	   $memObject = JsMemcache::getInstance();
	   $params = $memObject->get("MIS_FS_PARAMS_KEY");
	   $memObject->delete("MIS_FS_PARAMS_KEY");

	   // Data fetch logic
	   $misObj =new misGenerationHandler();	
	   $groupData = $misObj->getComputedFieldSalesExecutiveEfficiencyMis($params);
	   $memObject->set($params['memKeySet'],$groupData,'3600');
 }
}



