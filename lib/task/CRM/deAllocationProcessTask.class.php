<?php

class deAllocationProcessTask extends sfBaseTask
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

    $this->namespace        = 'deAllocation';
    $this->name             = 'deAllocationProcess';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [deAllocationProcess|INFO] task does things.
Call it with:

  [php symfony deAllocationProcess|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
        ini_set('max_execution_time',0);
        ini_set('memory_limit',-1);
	sfContext::createInstance($this->configuration);
	$processObj=new PROCESS();
        $processObj->setProcessName("DeAllocation");
        $agentBucketHandlerObj=new AgentBucketHandler();
        if(isset($subMethod))
        {
                $subMethod=$request->getParameter("submit");
                $processObj->setSubMethod($subMethod);
                $agentBucketHandlerObj->deallocate($processObj);
        }
        else
        {
                //Based on Disposition
                $processObj->setMethod("DISPOSITION");

                $processObj->setSubMethod("NEGATIVE_LIST");
                $msg=$agentBucketHandlerObj->deallocate($processObj);

                $processObj->setSubMethod("LIMIT_EXCEED");
                $msg.=$agentBucketHandlerObj->deallocate($processObj);

		/* Removed
                $processObj->setSubMethod("LIMIT_EXCEED_RENEWAL");
                $msg.=$agentBucketHandlerObj->deallocate($processObj);*/

                //Based on FTA 
                $processObj->setMethod("FTA_FTO");
                $processObj->setSubMethod("FTA");
                $msg.=$agentBucketHandlerObj->deallocate($processObj);
	
		//Based On MAX Days provided for conversion
                $processObj->setMethod("SALESEXPIRY");
                $processObj->setSubMethod("UPSELL");
                $msg.=$agentBucketHandlerObj->deallocate($processObj);

                $processObj->setSubMethod("SALES_OTHERS");
                $msg.=$agentBucketHandlerObj->deallocate($processObj);

		$processObj->setMethod("FOLLOWUP_RELEASE");
                $processObj->setSubMethod("FOLLOWUP_PENDING");
                $msg.=$agentBucketHandlerObj->deallocate($processObj);

		// De-allocate Deleted profiles
                $processObj->setMethod("RELEASE");
                $processObj->setSubMethod("DELETED_PROFILES");
                $msg.=$agentBucketHandlerObj->deallocate($processObj);
                
        //De-allocate disposition based
                $processObj->setSubMethod("DISPOSITION_BASED");
                $processObj->setIdAllot('13');
                $msg.=$agentBucketHandlerObj->deallocate($processObj);
                $lastHandledDateObj = new incentive_LAST_HANDLED_DATE();
                $lastHandledDateObj->setHandledDate($processObj->getIdAllot(), date('Y-m-d H:i:s'));
	
		echo $msg;
        }
        $agentBucketHandlerObj->deleteFromLoggingClientInfo();
  }
}
