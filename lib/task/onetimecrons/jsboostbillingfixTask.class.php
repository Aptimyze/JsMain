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
        $purchaseDetails = $purchasesObj->fetchJsBoostBillingPool("2017-02-01 00:00:00");
        //print_r($purchaseDetails);die;
        unset($purchasesObj);
        if(is_array($purchaseDetails)){
	        $billIdArr = array_keys($purchaseDetails);
	        if(is_array($billIdArr)){
		        echo "billid string to be processed."."\n";
		        var_dump(implode(",", $billIdArr));
		        echo "count -".count($billIdArr)."\n";
		        $paymentDetObj = new billing_PAYMENT_DETAILS();
		        unset($paymentDetObj);
		        $netBillingAmountArr = $paymentDetObj->getAllDetailsForBillidArr($billIdArr);
		        foreach ($purchaseDetails as $billid => $billDetails) {
		        	if(!empty($netBillingAmountArr[$billid])){
		        		echo "\n"."updating for billid-".$billid;
		        		$addonNetPrice = 0;
		        		if(!empty($billDetails["A"])){
		        			$addonNetPrice = $billDetails["A"]["NET_AMOUNT"];
		        		}
		        		$newCNetPrice = $netBillingAmountArr[$billid]-$addonNetPrice;
		        		$newCActualPrice = $newCNetPrice+$netBillingAmountArr["C"]["DISCOUNT"];
		        		echo "\n"."orig price-".$newCActualPrice." net price-".$newCNetPrice;
		        		$purchasesObj->updateDetails($billDetails["SID"],$newCNetPrice,$newCActualPrice);
		        		++$counter;

		        	}
		        	else{
		        		echo "\n"."billid-".$billid." ignored as no entry in billing.PAYMENT_DETAILS";
		        	}
		        }
		    }
	    }
	    echo "\n"."updated count-".$counter;
	    unset($purchasesObj);
	}
}
