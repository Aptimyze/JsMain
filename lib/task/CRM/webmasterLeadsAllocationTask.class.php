<?php

class webmasterLeadsAllocationTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    // // add your own options here
    // $this->addOptions(array(
    //   new sfCommandOption('my_option', null, sfCommandOption::PARAMETER_REQUIRED, 'My option'),
    // ));
     $this->addOptions(array(
       new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','operations'),
     ));

    $this->namespace        = 'Allocation';
    $this->name             = 'webmasterLeadsAllocation';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [webmasterLeadsAllocation|INFO] task does things.
Call it with:

  [php symfony webmasterLeadsAllocation|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
        sfContext::createInstance($this->configuration);
        $processObj             =new PROCESS();
        $agentBucketHandlerObj  =new AgentBucketHandler();
        $processObj->setProcessName("Allocation");
        $processObj->setMethod("WEBMASTER_LEADS");
        $processObj->setSubMethod("WEBMASTER_LEADS");
        $agentBucketHandlerObj->allocate($processObj);
        unset($processObj);
        unset($agentBucketHandlerObj);
        $processObj = new PROCESS();
        $agentBucketHandlerObj = new AgentBucketHandler();
        $processObj->setProcessName("Allocation");
        $processObj->setMethod("WEBMASTER_LEADS");
        $processObj->setSubMethod("WEBMASTER_LEADS_EXCLUSIVE");
        $agentBucketHandlerObj->allocate($processObj);
  }
}
