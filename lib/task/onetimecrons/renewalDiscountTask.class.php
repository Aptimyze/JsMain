<?php

class renewalDiscountTask extends sfBaseTask
{
	protected function configure()
	{

	$this->addOptions(array(
		new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','jeevansathi'),
		));

	$this->namespace        = 'oneTimeCron';
	$this->name             = 'renewalDiscount';
	$this->briefDescription = 'Updates the Renewal discount percentage for profiles from today till next x days specified in code';
	$this->detailedDescription = <<<EOF
	The [renewalDiscount|INFO] task does things.
	Call it with:

	[php symfony oneTimeCron:renewalDiscount|INFO]
EOF;
	}

	protected function execute($arguments = array(), $options = array())
	{
		sfContext::createInstance($this->configuration);

		$rdObj = new billing_RENEWAL_DISCOUNT();
		$rdLogObj =new billing_RENEWAL_DISCOUNT_LOG();

		// Remove data generated since past 100 days
		$removeDate  = date("Y-m-d", time()-30*24*60*60);
		$rdObj->removeProfilesAfterDate($removeDate);
		$rdLogObj->removeProfilesAfterDate($removeDate);

		for($i=0;$i<=30;$i++){
			// Run loop to re-update data of past 100 days till today
			$startDt  = date("Y-m-d", time()-(30-$i)*24*60*60);
			$expiryDt = date("Y-m-d", strtotime("$startDt +30 days"));
			$discountExpiryDt = date("Y-m-d", strtotime("$expiryDt +10 days"));

			$ssObj = new BILLING_SERVICE_STATUS();
			$res = $ssObj->getRenewalProfiles($expiryDt);
			$memHandlerObj = new MembershipHandler();
			$rdObj         = new billing_RENEWAL_DISCOUNT();
	        $rdLogObj      = new billing_RENEWAL_DISCOUNT_LOG();
	        $rdObj->removedExpiredProfiles();
	        $billPurObj     = new BILLING_PURCHASES('newjs_slave');
	        if (count($res) > 0) {
	            foreach ($res as $key => $profileid) {
				$details =$billPurObj->getLastPurchaseDetails($profileid);
				$detailsArr =$details[$profileid];
				if(is_array($detailsArr))
					$purDet[$profileid] =$detailsArr;
	            }
        	}
            if (count($res) > 0) {
	            foreach ($res as $key => $profileid) {
	                $discount = $memHandlerObj->calculateVariableRenewalDiscount($profileid);
	                $discount = $memHandlerObj->calculateNewRenewalDiscountBasedOnPreviousTransaction($profileid, $discount, $purDet[$profileid]);
	                $rdObj->insert($profileid, $discount, $expiryDt);
	                $rdLogObj->insert($profileid, $discount, $startDt, $discountExpiryDt);
	            }
        	}
        	unset($res,$details,$detailsArr,$purDet,$discount);
		}
	}
}
