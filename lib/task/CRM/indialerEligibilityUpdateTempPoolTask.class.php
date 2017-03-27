<?php

class indialerEligibilityUpdateTempPoolTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    // // add your own options here
     $this->addOptions(array(
       new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
     ));

    $this->namespace        = 'inDialer';
    $this->name             = 'indialerEligibilityUpdateTempPoolTask';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [indialerEligibilityUpdateTempPoolTask|INFO] task does things.
Call it with:

  [php symfony indialerEligibilityUpdateTempPoolTask|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
        /* Code section for updating indialer profiles eligibility */
        ini_set('max_execution_time',0);
        ini_set('memory_limit',-1);
	if(!sfContext::hasInstance())
                sfContext::createInstance($this->configuration);

        $processObj   	=new PROCESS();

	//Renewal In dialer
        $processObj->setProcessName("inRenewalDialer");
        $processObj->setMethod("IN_RENEWAL_DIALER_ELIGIBILITY");
        $inRenewalDialerObj = new RenewalDialer();
        $inRenewalDialerObj->createTempPoolForRenewalDialer();

	//In dialer
        $processObj->setProcessName("inDialer");
        $processObj->setMethod("IN_DIALER_ELIGIBILITY");
	$indialerObj    =new Dialer();
        $indialerObj->createTempPoolForDialer();
  }
}
