<?php

class upsellAllocationTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    // // add your own options here
     $this->addOptions(array(
       new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','operations'),
     ));

    $this->namespace        = 'Allocation';
    $this->name             = 'upsellAllocation';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [upsellAllocation|INFO] task does things.
Call it with:

  [php symfony upsellAllocation|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
	sfContext::createInstance($this->configuration);
	$processObj             =new PROCESS();
        $agentBucketHandlerObj  =new AgentBucketHandler();
        $processObj->setProcessName("Allocation");
        $processObj->setMethod("UPSELL");
        $processObj->setSubMethod("UPSELL");
        $agentBucketHandlerObj->allocate($processObj);
	
	echo "Upsell Allocation Process Completed !!!";
  }
}
