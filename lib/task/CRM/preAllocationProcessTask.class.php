<?php

class salesPreAllocationTask extends sfBaseTask
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

    $this->namespace        = 'preAllocation';
    $this->name             = 'preAllocationProcess';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [salesPreAllocation|INFO] task does things.
Call it with:

  [php symfony salesPreAllocation|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
	ini_set('max_execution_time',0);
	ini_set('memory_limit',-1);
	sfContext::createInstance($this->configuration);
	$processObj=new PROCESS();
        $agentBucketObj=new AgentBucketHandler();
        $agentAllocDetailsObj=new AgentAllocationDetails();
        $agentPreAllocObj=new AgentPreAllocation();
        $agentPreAllocObj->truncateProfilesTech();
	$agentPreAllocObj->truncatePreAllocTempPool();

        $processObj->setProcessName("PreAllocation");
        $locsArray=$agentAllocDetailsObj->fetchAllCenters();
	$localityLimitArr =$agentAllocDetailsObj->getLocalityLimit('PREALLOCATION');
	$agentPreAllocObj->createPoolForPreAllocation(); 
	$everPaidPool =$agentAllocDetailsObj->getEverPaidPool();
	$processObj->setEverPaidPool($everPaidPool);
	
	// Optimized 
	$agentsArr =$agentAllocDetailsObj->getAgentInfo();	
	$processObj->setAgentDetails($agentsArr);
	$locationObj=new incentive_LOCATION('newjs_slave');
	$citySelArr=$locationObj->fetchSpecialCities();
	$processObj->setSpecialCityList($citySelArr);
	$restIndCities=$agentAllocDetailsObj->fetchRestIndiaStatesCities();
	$processObj->setRestIndCities($restIndCities);

	$limitArr = array();
	foreach($localityLimitArr as $k => $v) {
		$limitArr[strtoupper($k)] = $v; 
	}
        $processObj->setAllCentersArray($locsArray);
        $processObj->setLimitArr($limitArr);

	// VD Offer activation check
	$vdActive =$agentAllocDetailsObj->isVdOfferActive();
	if($vdActive)
		$loopInitValue=1;
	else
		$loopInitValue=0;

	// $j=1 for considering discounted profiles
	// $j=0 for considering non-discounted profiles
	for($j=$loopInitValue;$j>=0;$j--)
	{
		$processObj->setDiscountStatus($j);
        	for($i=-5;$i<=6;$i++)
	        {
			if($i!=1 && $i!=-1 && $i!=-2 && $i!=-3 && $i!=-4)
			{	
				$processObj->setDiscountStatus($j);
	                	$processObj->setLevel($i);
		                $agentBucketObj->preAllocate($processObj);
			}
        	}
	}
  }
}
