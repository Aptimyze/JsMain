<?php
ini_set("max_execution_time",1200);

class fieldSalesAllocationProcessTask extends sfBaseTask
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
    $this->name             = 'fieldSalesAllocationProcess';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [fieldSalesAllocationProcess|INFO] task does things.
Call it with:

  [php symfony fieldSalesAllocationProcess|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
	ini_set('memory_limit',-1);
	if(!sfContext::hasInstance())
		sfContext::createInstance($this->configuration);

	$processObj		=new PROCESS();
        $agentBucketObj		=new AgentBucketHandler();
        $agentAllocDetailsObj	=new AgentAllocationDetails();

	$processObj->setIdAllot('10');
        $processObj->setProcessName("Allocation");
        $processObj->setMethod("FIELD_SALES");
	//$agentAllocDetailsObj->createPhoneVerifiedPool($processObj);

	// Sub-method 1 : FIELD_SALES
        $processObj->setSubMethod("FIELD_SALES");
        $agentBucketObj->allocate($processObj);

        // Sub-method 2 : FIELD_SALES_SCHEDULED
        $processObj->setIdAllot('11');
        $processObj->setSubMethod("FIELD_SALES_VISIT");
        $agentBucketObj->allocate($processObj);

	echo "Field Sales Process Done !!!";

  }
}
