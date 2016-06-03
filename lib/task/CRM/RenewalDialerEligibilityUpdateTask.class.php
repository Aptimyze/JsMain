<?php
/**
 * Description of inRenewalDialerEligibilityTask
 * This file is used to fetch profiles based on a set of condition and make a pool of profiles ready to be sent to Dialler.
 * @author nitish
 */
class RenewalDialerEligibilityUpdateTask extends sfBaseTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
        ));
        
        $this->namespace = "renewalDialer";
        $this->name = "RenewalDialerEligibilityUpdateTask";
        $this->briefDescription = "";
        $this->detailedDescription = <<<EOF
            The [RenewalDialerEligibilityUpdateTask|INFO] task does things.
            Call it with:[php symfony RenewalDialerEligibilityUpdateTask|INFO]
EOF;
    }
    
    protected function execute($arguments = array(), $options = array()) {
        ini_set('max_execution_time',0);
        ini_set('memory_limit',-1);
        if(!sfContext::hasInstance())
            sfContext::createInstance($this->configuration);
        $processObj = new Process();
        $processObj->setProcessName("inRenewalDialer");
        $processObj->setMethod("IN_RENEWAL_DIALER_ELIGIBILITY");
        
        $inRenewalDialerObj = new RenewalDialer();
        $inRenewalDialerObj->preFilter($processObj);
        $profiles = $inRenewalDialerObj->fetchProfiles($processObj);
        $inRenewalDialerObj->filterProfiles($profiles);
        unset($profiles);
    }
}
