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
		$this->briefDescription = '';
		$this->detailedDescription = <<<EOF
		The [memUpgradeFix|INFO] task does things.
		Call it with:
		[php symfony CRM:memUpgradeFix|INFO]
EOF;
	}

	protected function execute($arguments = array(), $options = array())
	{
        $updateCount = 0;
	    $upgradeObj = new billing_UPGRADE_ORDERS("newjs_slave");
        $upgradeEntries = $upgradeObj->getAllEntries();
        $ordersObj = new billing_ORDERS("newjs_slave");
        $purchaseDetailObj = new billing_PURCHASE_DETAIL();
        unset($upgradeObj);
        print_r($upgradeEntries);
        foreach ($upgradeEntries as $profileid => $details) {
            if(!empty($details["ORDERID"]) && !empty($details["BILLID"])){
                
                $ordersplitArr = explode("-",$details["ORDERID"]);
                if(is_array($ordersplitArr) && $ordersplitArr[0]){
                    $orderid = $ordersplitArr[0];
                    $orderDetails = $ordersObj->getOrderDetailsForOrderIdOnly($orderid);
                    print_r($orderDetails);
                    if($orderDetails["AMOUNT"] > 0){
                        $purchaseDetailObj->updateEntriesForBillid($details['BILLID'],$orderDetails["AMOUNT"],$orderDetails["DISCOUNT"],$orderDetails["AMOUNT"]+$orderDetails["DISCOUNT"]);
                        ++$updateCount;
                        echo "done";
                    }
                }
            }
        }
        unset($ordersObj);
        unset($purchaseDetailObj);
        var_dump($updateCount);
	}
}
