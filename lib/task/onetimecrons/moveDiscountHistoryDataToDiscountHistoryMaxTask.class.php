<?php
/* This class runs a cron to fetch the eligible pool of lightning deal and store it for further use.
   Eligible profiles for this offer follow 3 conditions:
	1. Pick all currently free users who have logged-in in the last 30 days (pool 1). 
	2. Remove profiles who have received a lightning offer in the last 30 days (eligible users who did not login and did not view the offer will not be removed) (pool 2).
	3. Pick n number of users from pool2 where n is 10% of the number of users in pool 1.
*/

class moveDiscountHistoryDataToDiscountHistoryMaxTask extends sfBaseTask
{
	private $debug = 1;
	private $logFilePath = "";

	protected function configure()
	{
		$this->addOptions(array(
			new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
		));

		$this->namespace        = 'oneTimeCron';
		$this->name             = 'moveDiscountHistoryDataToDiscountHistoryMax';
		$this->briefDescription = 'fetch the eligible pool of lightning deal and store it for further use';
		$this->detailedDescription = <<<EOF
		The [moveDiscountHistoryDataToDiscountHistoryMax|INFO] task does things.
		Call it with:
		[php symfony oneTimeCron:moveDiscountHistoryDataToDiscountHistoryMax|INFO]
EOF;
	}

	protected function execute($arguments = array(), $options = array())
	{
		ini_set('max_execution_time',0);
    	ini_set('memory_limit',-1);
		if (!sfContext::hasInstance()) {
            sfContext::createInstance($this->configuration);
        }
        $this->logFilePath = JsConstants::$docRoot.'/uploads/lightningDealOneTime.txt';
        shell_exec("echo '' > ".$this->logFilePath."");

        $currentTime = date("Y-m-d H:i:s");
        error_log("Getting Distinct Profileids\nTime:$currentTime"."\n",3,$this->logFilePath);
        $lessThanDate = "2017-06-18";
        $discHistObj = new billing_DISCOUNT_HISTORY("newjs_slave");
        $distinctProfileidArr = $discHistObj->getDistinctProfileIds($lessThanDate); //order by profileid for logging?
        error_log("Distinct Profileids fetched\nTime:$currentTime"."\n",3,$this->logFilePath);
        
        $count = count($distinctProfileidArr);
        $currentTime = date("Y-m-d H:i:s");
        error_log("Total Unique Profiles:$count\nTime:$currentTime"."\n",3,$this->logFilePath);
        $discHistMaxObj = new billing_DISCOUNT_HISTORY_MAX();
        $updatedCount = 0;
        //print_r($distinctProfileidArr);
        foreach($distinctProfileidArr as $profileid){
            unset($paramsArr);
            $data = $discHistObj->getDetailsForProfileid($profileid);
            $maxDiscount = -1;
            $maxDiscountDate = NULL;
            $lastLoginDate = NULL;
            foreach($data as $key => $val){
                $currentMaxDiscount = max($val["P"],$val["C"],$val["NCP"],$val["X"]);
                if($currentMaxDiscount >= $maxDiscount){
                    $maxDiscount = $currentMaxDiscount;
                    $maxDiscountDate = $val["DATE"];
                }
                if(strtotime($val["DATE"]) >= strtotime($lastLoginDate)){
                    $lastLoginDate = $val["DATE"];
                }
            }
            $paramsArr["PROFILEID"] = $profileid;
            $paramsArr["MAX_DISCOUNT"] = $maxDiscount;
            $paramsArr["MAX_DISCOUNT_DATE"] = $maxDiscountDate;
            $paramsArr["LAST_LOGIN_DATE"] = $lastLoginDate;
            $discHistMaxObj->updateDiscountHistoryMax($paramsArr);
            $updatedCount++;
        }
        $currentTime = date("Y-m-d H:i:s");
        error_log("Total Updated:$updatedCount\nTime:$currentTime"."\n",3,$this->logFilePath);
	}
}
?>