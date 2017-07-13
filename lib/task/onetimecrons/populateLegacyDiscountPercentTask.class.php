<?php

class populateLegacyDiscountPercentTask extends sfBaseTask
{
	protected function configure()
	{
		$this->addOptions(array(
			new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
		));

		$this->namespace        = 'CRM';
		$this->name             = 'populateLegacyDiscountPercent';
		$this->briefDescription = 'fix mem upgrade issue';
		$this->detailedDescription = <<<EOF
		The [populateLegacyDiscountPercent|INFO] task does things.
		Call it with:
		[php symfony CRM:populateLegacyDiscountPercent|INFO]
EOF;
	}

	protected function execute($arguments = array(), $options = array())
	{
        if (!sfContext::hasInstance()) {
            sfContext::createInstance($this->configuration);
        }
        $counter = 0;
        $paymentDetObj = new BILLING_PAYMENT_DETAIL("newjs_slave");
        $billIdArr = $paymentDetObj->getZeroAmountBillings("2016-01-01 00:00:00","2017-07-01 00:00:00");
        if(is_array($billIdArr)){
        	$billIdStr = implode(",", $billIdArr);
        	echo "bill ids with 0 amount :"."\n";
        	var_dump($billIdStr);
        	$purchasesObj = new BILLING_PURCHASES();
        	$purchasesObj->updateDiscountPercent($billIdStr,100);
        	unset($purchasesObj);
        	echo "update done"."\n";
        }
        unset($paymentDetObj);
    }
}
