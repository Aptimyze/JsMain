<?php
/**
* This taks will decide correct Strategy for the Paid members users. 
*/
class PaidMembersCalculationTask extends sfBaseTask
{
	private $limit = 5000;
	private $limitRec = 16;
	protected function configure()
  	{
		$this->addArguments(array(
                        new sfCommandArgument('totalScripts', sfCommandArgument::REQUIRED, 'My argument'),
                        new sfCommandArgument('currentScript', sfCommandArgument::REQUIRED, 'My argument'),
        	));

		$this->addOptions(array(
		new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
	     ));
	     
	    $this->namespace        = 'PaidMailer';
	    $this->name             = 'PaidMembersCalculation';
	    $this->briefDescription = '';
	    $this->detailedDescription = <<<EOF
	Call it with:
	  [php symfony PaidMailer:PaidMembersCalculation] 
EOF;
  	}

	protected function execute($arguments = array(), $options = array())
	{
		if(!sfContext::hasInstance())
			sfContext::createInstance($this->configuration);

                ini_set('memory_limit','512M');
                $totalScripts = $arguments["totalScripts"]; // total no of scripts
                $currentScript = $arguments["currentScript"]; // current script number
		$flag=0;

		do{
			$search_PAIDMEMBERS_TO_BE_SENT = new search_PAIDMEMBERS_TO_BE_SENT();
			$arr = $search_PAIDMEMBERS_TO_BE_SENT->fetch($totalScripts,$currentScript,$this->limit);
                        //$arr[0]["PROFILEID"] = 144111;
			if(is_array($arr))
			{
				foreach($arr as $v)
				{
                                        $profileid = $v["PROFILEID"];
					$search_PAIDMEMBERS_TO_BE_SENT->update($profileid);
					$loggedInProfileObj = LoggedInProfile::getInstance("newjs_slave",$profileid);
					$loggedInProfileObj->getDetail($profileid,"PROFILEID","*");
					if($loggedInProfileObj->getPROFILEID())
					{
                                               $StrategyReceiversNT = new PaidMembersStrategy($loggedInProfileObj,$this->limitRec);
                                               $totalResults = $StrategyReceiversNT->getMatches();

					}
				}
			
			}
			else
				$flag=0;
		}while($flag);
	}
}
