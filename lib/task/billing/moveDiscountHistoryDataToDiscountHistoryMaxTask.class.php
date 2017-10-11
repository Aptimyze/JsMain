<?php
/* This class runs a cron to fetch the eligible pool of lightning deal and store it for further use.
   Eligible profiles for this offer follow 3 conditions:
	1. Pick all currently free users who have logged-in in the last 15 days (pool 1). 
	2. Remove profiles who have received a lightning offer in the last 15 days (eligible users who did not login and did not view the offer will not be removed) (pool 2).
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

		$this->namespace        = 'billing';
		$this->name             = 'moveDiscountHistoryDataToDiscountHistoryMaxTask';
		$this->briefDescription = 'fetch the eligible pool of lightning deal and store it for further use';
		$this->detailedDescription = <<<EOF
		The [moveDiscountHistoryDataToDiscountHistoryMaxTask|INFO] task does things.
		Call it with:
		[php symfony billing:moveDiscountHistoryDataToDiscountHistoryMaxTask|INFO]
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
        
        //Move older than 30 days data from LIGHTNING_DEAL_DISCOUNT to LIGHTNING_DEAL_DISCOUNT_BACKUP.
        //--------------------------------------------------------------------------------------------
        $lessThanDate = date('Y-m-d',strtotime('-30 day'));
        
        $lightningDealBackup = new billing_LIGHTNING_DEAL_DISCOUNT_BACKUP();
        $lightningDealBackup->backupData($lessThanDate);
        unset($lightningDealBackup);
        
        //Delete data that has been backed up.
        $lightningDealObj = new billing_LIGHTNING_DEAL_DISCOUNT();
        $lightningDealObj->deleteOldData($lessThanDate);
        unset($lightningDealObj);
        
        //--------------------------------------------------------------------------------------------
        
        $discHistMaxObj = new billing_DISCOUNT_HISTORY_MAX();
        $discHistMaxObj->truncateTable();
        
        //$this->sendAlertMail("nitish.sharma@jeevansathi.com,ankita.g@jeevansathi.com", "Discount History Max table truncated", "Discount History Max table truncated");
        if($this->debug == 1){
            error_log("Discount History Max Table truncated.\nGetting Distinct Profileids\nTime:$currentTime"."\n",3,$this->logFilePath);
        }
        
        $todayDate = date("Y-m-d");
        $lessThanDate = date("Y-m-d",strtotime("$todayDate -1 days"));
        
        $discHistObj = new billing_DISCOUNT_HISTORY("newjs_slave");
        $distinctProfileidArr = $discHistObj->getDistinctProfileIds($lessThanDate);
        
        if($this->debug == 1){
            error_log("Distinct Profileids fetched\nTime:$currentTime"."\n",3,$this->logFilePath);
        }
        
        $count = count($distinctProfileidArr);
        $currentTime = date("Y-m-d H:i:s");
        if($this->debug == 1){
            error_log("Total Unique Profiles:$count\nTime:$currentTime"."\n",3,$this->logFilePath);
        }
        
        $updatedCount = 0;
        //print_r($distinctProfileidArr);
        foreach($distinctProfileidArr as $profileid){
            unset($paramsArr,$data);
            $data = $discHistObj->getDetailsForProfileid($profileid);
            $maxDiscount = -1;
            $maxDiscountDate = NULL;
            $lastLoginDate = NULL;
            if($data){
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
            }
            $updatedCount++;
        }
        $currentTime = date("Y-m-d H:i:s");
        if($this->debug == 1){
            error_log("Total Updated:$updatedCount\nTime:$currentTime"."\n",3,$this->logFilePath);
        }
        //$this->sendAlertMail("nitish.sharma@jeevansathi.com,ankita.g@jeevansathi.com", "moveDiscountHistoryDataToDiscountHistoryMaxTask.class.php executed", "moveDiscountHistoryDataToDiscountHistoryMaxTask.class.php executed");
	}
    
    public function sendAlertMail($to,$msgBody,$subject){
        $from = "info@jeevansathi.com";
        $from_name = "Jeevansathi Info";
        SendMail::send_email($to,$msgBody, $subject, $from,"","","","","","","1","",$from_name);
    }
}
?>