<?php
/* This class runs a cron to fetch the eligible pool of lightning deal and store it for further use.
   Eligible profiles for this offer follow 3 conditions:
	1. Pick all currently free users who have logged-in in the last 30 days (pool 1). 
	2. Remove profiles who have received a lightning offer in the last 30 days (eligible users who did not login and did not view the offer will not be removed) (pool 2).
	3. Pick n number of users from pool in point 2 where n is 10% of the number of users in pool 1.
*/

class setLightningDealEligiblePoolTask extends sfBaseTask
{
	private $debug = 1;
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
        //start: Pool 1-all currently free users who have logged-in in the last 30 days
        $lastLoggedInOffset = VariableParams::$lightningDealOfferConfig["lastLoggedInOffset"] - 1;
        $todayDate = date("Y-m-d");
		$offsetDate = date("Y-m-d",strtotime("$todayDate -".$lastLoggedInOffset." days"))." 00:00:00";

		//use MIS.LOGIN_TRACKING to get last logged in pool within offset time
        $loginTrackingObj = new MIS_LOGIN_TRACKING("newjs_local111");
        $lastLoggedInArr = $loginTrackingObj->getLastLoginProfilesForDate("",$offsetDate,VariableParams::$lightningDealOfferConfig["channelsAllowed"]);
        unset($loginTrackingObj);
        if($this->debug == 1){
	        echo "last logged in pool.."."\n";
	        print_r($lastLoggedInArr);
	    }
	    //$lastLoggedInArr = array(1,9061321);
	    
	    //use newjs.JPROFILE to get currently free pool from $lastLoggedInArr
	    $jprofileObj = new JPROFILE("newjs_slave");
	    $lastLoggedInFreePool1 = $jprofileObj->getProfileSelectedDetails($lastLoggedInArr,"PROFILEID",array("activatedKey"=>1,"SUBSCRIPTION"=>''));
	    unset($jprofileObj);
	    if($this->debug == 1){
	        echo "after membership filter,currently free last logged in pool.."."\n";
	        print_r($lastLoggedInFreePool1);
	    }
	    die("done");
	}
}
