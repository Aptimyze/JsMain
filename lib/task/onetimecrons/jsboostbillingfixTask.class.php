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
        
        $purchasesObj = new billing_PURCHASES("newjs_slave");
        $purchaseDetails = $purchasesObj->fetchJsBoostBillingPool("2017-02-01 00:00:00");
        unset($purchasesObj);
        //print_r($purchaseDetails);die;
        foreach ($purchaseDetails as $billid => $details) {
        	
        }
	}
}
