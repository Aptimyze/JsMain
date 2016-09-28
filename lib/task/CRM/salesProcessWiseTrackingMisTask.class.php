<?php
/***************************************************************************************************************

* DESCRIPTION   : Cron script, sceduled on the 1st of every month, to get Process wise tracking (JSC-1030)
*****************************************************************************************************************/
class salesProcessWiseTrackingMisTask extends sfBaseTask{
    
    protected  function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
        ));
        
        $this->namespace = "tracking";
        $this->name = "salesProcessWiseTrackingMisTask";
        $this->briefDescription = "";
        $this->detailedDescription = <<<EOF
            The [salesProcessWiseTrackingMisTask|INFO] task does things.
            Call it with:[php symfony tracking:salesProcessWiseTrackingMisTask|INFO]
EOF;
    }
    
    protected function execute($arguments = array(), $options = array())
    {
        if(!sfContext::hasInstance())
            sfContext::createInstance($this->configuration);
        
        $todaysDate = date('Y-m-d');
        $deleteBeforeDate = date('Y-m-d', strtotime('-7 day',  strtotime($todaysDate)));
        $misGenerationHandlerObj = new misGenerationhandler();
        
        $misGenerationHandlerObj->removeEntriesFromAgentsLoginLog($deleteBeforeDate);
        
        $misGenerationHandlerObj->salesProcessWiseTracking();
        unset($misGenerationHandlerObj);
    }
}
