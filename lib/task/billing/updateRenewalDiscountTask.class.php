<?php

class updateRenewalDiscountTask extends sfBaseTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'jeevansathi'),
        ));

        $this->namespace           = 'billing';
        $this->name                = 'updateRenewalDiscount';
        $this->briefDescription    = '';
        $this->detailedDescription = <<<EOF
The [updateRenewalDiscount|INFO] task does things.
Call it with:

  [php symfony updateRenewalDiscount|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        sfContext::createInstance($this->configuration);
        $expiryDt         = date("Y-m-d", time() + 29 * 24 * 60 * 60);
        $startDt          = date("Y-m-d");
        $discountExpiryDt = date("Y-m-d", strtotime("$expiryDt +10 days"));

        $ssObj = new BILLING_SERVICE_STATUS('newjs_slave');
        $res   = $ssObj->getRenewalProfiles($expiryDt);
        $memHandlerObj = new MembershipHandler();
        $rdObj         = new billing_RENEWAL_DISCOUNT();
        $rdLogObj      = new billing_RENEWAL_DISCOUNT_LOG();
        $rdObj->removedExpiredProfiles();
        $billPurObj     = new BILLING_PURCHASES('newjs_slave');

        // Get bulk details of profiles last main membership transactions
        if (count($res) > 0) {
            foreach ($res as $key => $profileid) {
		$details =$billPurObj->getLastPurchaseDetails($profileid);
		$detailsArr =$details[$profileid];
		if(is_array($detailsArr))
			$purDet[$profileid] =$detailsArr;
                //$proArr[] = $profileid;
            }
            //$purDet = $billPurObj->getLastPurchaseDetails($proArr);
        }
        if (count($res) > 0) {
            foreach ($res as $key => $profileid) {
                $discount = $memHandlerObj->calculateVariableRenewalDiscount($profileid);
                $discount = $memHandlerObj->calculateNewRenewalDiscountBasedOnPreviousTransaction($profileid, $discount, $purDet[$profileid]);
                $rdObj->insert($profileid, $discount, $expiryDt);
                $rdLogObj->insert($profileid, $discount, $startDt, $discountExpiryDt);
		$countArr[] =$profileid;
            }
        }
	$totRenewal =count($countArr);
	if($totRenewal<100){	
                $to             ="rohan.mathur@jeevansathi.com,manoj.rana@naukri.com";
                $latest_date    =date("Y-m-d");
                $subject        ="Renewal Discount Calculated For: ".date("jS F Y", strtotime($latest_date));
                $fromEmail      ="From:JeevansathiCrm@jeevansathi.com";
                $msg            ="Total Renewal calculated: $totRenewal having Expiry on: $expiryDt";
                mail($to,$subject,$msg,$fromEmail);
	}
    }
}
