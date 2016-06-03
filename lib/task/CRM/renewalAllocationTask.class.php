<?php

class renewalAllocationTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    // // add your own options here
     $this->addOptions(array(
       new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The apllication name','operations'),
     ));

    $this->namespace        = 'Allocation';
    $this->name             = 'renewalAllocation';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [renewalAllocation|INFO] task does things.
Call it with:

  [php symfony renewalAllocation|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
	sfContext::createInstance($this->configuration);
	$processObj             =new PROCESS();
        $agentBucketHandlerObj  =new AgentBucketHandler();
        $processObj->setProcessName("Allocation");
        $processObj->setMethod("RENEWAL");
        $processObj->setSubMethod("RENEWAL");
        $agentBucketHandlerObj->allocate($processObj);
  }
}
