<?php
/* This class runs a cron to fetch the eligible pool of lightning deal and store it for further use.
   Eligible profiles for this offer follow 3 conditions:
	1. Pick all currently free users who have logged-in in the last 30 days (pool 1). 
	2. Remove profiles who have received a lightning offer in the last 30 days (eligible users who did not login and did not view the offer will not be removed) (pool 2).
	3. Pick n number of users from pool2 where n is 10% of the number of users in pool 1.
* @author : Ankita
*/

class setLightningDealEligiblePoolTask extends sfBaseTask
{
	private $debug = 0;
	protected function configure()
	{
		$this->addOptions(array(
			new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
		));

		$this->namespace        = 'billing';
		$this->name             = 'setLightningDealEligiblePool';
		$this->briefDescription = 'fetch the eligible pool of lightning deal and store it for further use';
		$this->detailedDescription = <<<EOF
		The [setLightningDealEligiblePool|INFO] task does things.
		Call it with:
		[php symfony billing:setLightningDealEligiblePool|INFO]
EOF;
	}

	protected function execute($arguments = array(), $options = array())
	{
		//ini_set('max_execution_time',0);
    	//ini_set('memory_limit',-1);
		if (!sfContext::hasInstance()) {
            sfContext::createInstance($this->configuration);
        }

        $dealObj = new LightningDeal($this->debug);
        //generate eligible pool
        $eligiblePool = $dealObj->generateDealEligiblePool();

        //store eligible pool
        $dealObj->storeDealEligiblePool($eligiblePool);
        unset($dealObj);

        //truncate billing.DISCOUNT_HISTORY table
        $todayDate = date("Y-m-d");
        $today1Date = date("Y-m-d",strtotime("$todayDate -1 days"));
		$offsetDate = date("Y-m-d",strtotime("$todayDate -".VariableParams::$lightningDealOfferConfig["lastLoggedInOffset"]." days"));
        $discHistObj = new billing_DISCOUNT_HISTORY();
        //backup daily data to billing.DISCOUNT_HISTORY_BACKUP
        $discHistObj->backupDailyData($today1Date);

        //uncomment ankita later
        $discHistObj->truncateTable($offsetDate);
        unset($discHistObj);
	}
}
?>