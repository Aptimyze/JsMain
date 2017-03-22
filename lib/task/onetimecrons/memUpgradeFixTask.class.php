<?php

class memUpgradeFixTask extends sfBaseTask
{
	protected function configure()
	{
		$this->addOptions(array(
			new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
		));

		$this->namespace        = 'CRM';
		$this->name             = 'memUpgradeFix';
		$this->briefDescription = 'fix mem upgrade issue';
		$this->detailedDescription = <<<EOF
		The [memUpgradeFix|INFO] task does things.
		Call it with:
		[php symfony CRM:memUpgradeFix|INFO]
EOF;
	}

    private function getReverseUpgradeMapping($upgradeMem,$upgradeMemDur){
        $mainMem = "";
        $mainMemDur = $upgradeMemDur;
        switch($upgradeMem){
            case "C":
                $mainMem = "P";
                break;
            case "NCP":
                $mainMem = "C";
                break;
            case "X":
                $mainMem = "NCP";
                break;
        }
        return array($mainMem,$mainMemDur);
    }

	protected function execute($arguments = array(), $options = array())
	{
        if (!sfContext::hasInstance()) {
            sfContext::createInstance($this->configuration);
        }

        $memCacheObject = JsMemcache::getInstance();
        $mainMembershipsRS = unserialize($memCacheObject->get("desktop_MAIN_MEMBERSHIP_"."RS"));
        $mainMembershipsDOL = unserialize($memCacheObject->get("desktop_MAIN_MEMBERSHIP_"."DOL"));
        //print_r($mainMembershipsDOL);
        //print_r($mainMembershipsRS);
        $updateCount = 0;
	    $upgradeObj = new billing_UPGRADE_ORDERS();
        $upgradeEntries = $upgradeObj->getAllEntries();
        var_dump($upgradeEntries);
        unset($upgradeObj);
        $purchaseDetailObj = new billing_PURCHASE_DETAIL();
        $purchaseBillIdArr = $purchaseDetailObj->getBillingDetails($upgradeEntries);
        print_r($purchaseBillIdArr);

        foreach ($purchaseBillIdArr as $billid => $billWisepurchaseDetails) {
            echo "\n"."billid-".$billid."\n";
            print_r($billWisepurchaseDetails);
            $defaultAddonCase = false;
            $serviceidArr = array_keys($billWisepurchaseDetails);
            $serviceStr = implode(",", $serviceidArr);
            if(strpos($serviceStr, "J") != false){
                $defaultAddonCase = true;
            }
            echo "defaultAddonCase--";
            var_dump($defaultAddonCase);
            foreach ($billWisepurchaseDetails as $serviceid => $serviceWiseDetails) {
                if(is_array($serviceWiseDetails) && !empty($serviceid) && strpos($serviceid, "J") == false){
                    $tempMem = @preg_split('/(?<=\d)(?=[a-z])|(?<=[a-z])(?=\d)/i', $serviceid);
                    $upgradeMem = $tempMem[0];
                    if (strpos($upgradeMem, "L")) {
                        $upgradeMemDur = "L";
                        $upgradeMem    = substr($upgradeMem, 0, -1);
                    } else {
                        $upgradeMemDur = $tempMem[1];
                    }
                    if($defaultAddonCase == true && $upgradeMem == "C"){
                        $upgradeMem = "NCP";
                    }
                    $previousMemDetails = $this->getReverseUpgradeMapping($upgradeMem,$upgradeMemDur);
                    var_dump($serviceid);
                    print_r($previousMemDetails);
                    if(is_array($previousMemDetails) && $previousMemDetails[0]!=""){
                        if($serviceWiseDetails["CUR_TYPE"] == "DOL"){
                            $actualAmount = $mainMembershipsDOL[$upgradeMem][$upgradeMem.$upgradeMemDur]['PRICE']-$mainMembershipsDOL[$previousMemDetails[0]][$previousMemDetails[0].$previousMemDetails[1]]['PRICE']; 
                        }
                        else{
                            $actualAmount = $mainMembershipsRS[$upgradeMem][$upgradeMem.$upgradeMemDur]['PRICE']-$mainMembershipsRS[$previousMemDetails[0]][$previousMemDetails[0].$previousMemDetails[1]]['PRICE'];
                        }
                        if($actualAmount > 0 && $serviceWiseDetails["PRICE"] != $actualAmount){
                            $netAmount = $actualAmount - $serviceWiseDetails["DISCOUNT"];
                            echo "\n"."new prices are"."\n";
                            var_dump($actualAmount."-".$netAmount);
                            $purchaseDetailObj->updateEntriesForBillid($serviceWiseDetails["BILLID"],$netAmount,$actualAmount);
                        }

                    }
                }
            }
        }
        unset($purchaseDetailObj);
        echo "\n"."updateCount=";
        var_dump($updateCount);
	}
}
