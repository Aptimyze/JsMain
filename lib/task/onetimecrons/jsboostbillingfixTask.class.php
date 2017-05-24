<?php

class jsboostbillingfixTask extends sfBaseTask
{
	protected function configure()
	{
		$this->addOptions(array(
			new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
		));

		$this->namespace        = 'CRM';
		$this->name             = 'jsboostbillingfix';
		$this->briefDescription = 'fix mem upgrade issue';
		$this->detailedDescription = <<<EOF
		The [jsboostbillingfix|INFO] task does things.
		Call it with:
		[php symfony CRM:jsboostbillingfix|INFO]
EOF;
	}

	protected function execute($arguments = array(), $options = array())
	{
        if (!sfContext::hasInstance()) {
            sfContext::createInstance($this->configuration);
        }
        $counter = 0;
        //confirm slave lag
        $purchasesObj = new billing_PURCHASES();
        $billIdArr = $purchasesObj->fetchJsBoostBillingPool("2017-05-24 00:00:00");
        unset($purchasesObj);
        echo "billid string to be processed."."\n";
        $billIdStr = implode(",", $billIdArr);
        var_dump($billIdStr);
        echo "count- ".count($billIdArr)."\n";
        
       	$purchaseDetObj = new billing_PURCHASE_DETAIL();
       	$purchaseDetails = $purchaseDetObj->getBillingDetails($billIdStr,true);
       	//print_r($purchaseDetails);
        if(is_array($purchaseDetails)){ 
	        $paymentDetObj = new BILLING_PAYMENT_DETAIL();
	        $netBillingAmountArr = $paymentDetObj->getAllDetailsForBillidArr($billIdArr);
	        unset($paymentDetObj);
	        //print_r($purchaseDetails);
	        //print_r($netBillingAmountArr);
	        foreach ($purchaseDetails as $billid => $billDetails) {
	        	if(!empty($netBillingAmountArr[$billid])){
	        		echo "\n"."updating for billid-".$billid."--sid-".$billDetails["C"]["SID"];
	        		$addonNetPrice = 0;

	        		if(!empty($billDetails["A"])){
	        			$addonNetPrice = $billDetails["A"]["NET_AMOUNT"];
	        		}
	        		echo "\n"."old orig price-".$billDetails["C"]["PRICE"]." old net price-".$billDetails["C"]["NET_AMOUNT"];
	        		$newCNetPrice = $netBillingAmountArr[$billid]['AMOUNT']-$addonNetPrice;
	        		$newCActualPrice = $newCNetPrice+$billDetails["C"]["DISCOUNT"];
	        		echo "\n"."orig price-".$newCActualPrice." net price-".$newCNetPrice;
	        		echo "\n";
	        		$purchaseDetObj->updateDetails($billDetails["C"]["SID"],$newCNetPrice,$newCActualPrice);
	        		++$counter;

	        	}
	        	else{
	        		echo "\n"."billid-".$billid." ignored as no entry in billing.PAYMENT_DETAILS";
	        	}
	        }
	    }
	    echo "\n"."updated count-".$counter;
	    unset($purchaseDetObj);
	}
}
