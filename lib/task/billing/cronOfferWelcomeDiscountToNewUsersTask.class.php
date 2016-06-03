<?php
/* This class runs a cron to offer welcome discount to all users who create their profile and upload a photograph , on all plans in the their first 5 days from registrations.
*/

class cronOfferWelcomeDiscountToNewUsersTask extends sfBaseTask
{
	protected function configure()
	{
		$this->addOptions(array(
			new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
		));

		$this->namespace        = 'billing';
		$this->name             = 'cronOfferWelcomeDiscountToNewUsers';
		$this->briefDescription = 'offer welcome discount to all users who create their profile and upload a photograph , on all plans in the their first 5 days from registrations';
		$this->detailedDescription = <<<EOF
		The [cronOfferWelcomeDiscountToNewUsers|INFO] task does things.
		Call it with:
		[php symfony billing:cronOfferWelcomeDiscountToNewUsers|INFO]
EOF;
	}

	protected function execute($arguments = array(), $options = array())
	{
	    // SET BASIC CONFIGURATION
    	//ini_set('max_execution_time',0);
    	//ini_set('memory_limit',-1);
		if(!sfContext::hasInstance())
		{
			sfContext::createInstance($this->configuration);
		}
		//offer conditions
		$params["offset"] = "- 1 hour"; //mob verified  or photo screened within last 1 hour
		$params["registerOffset"] = "- 5 day";    //registered within last 5 days
		$discountPercent = 10;   //10% discount
		$discountDuration = "+ 5 day";  //valid for 5 days after registration
		$sendMailForDiscount = false; //mail not to be sent
        $sendSMSForDiscount = false; //SMS not to be sent (added for removing WD users from VD sms scheduling)
		//get discount eligible profiles
		$WDObj = new VariableDiscount();
		$profilesArr = $WDObj->getDiscountEligibleProfiles($params,"WELCOME_DISCOUNT");
		unset($WDObj);
		
		//activate discount and log entry for resulting profiles if offer not given earlier
        $discountObj = new billing_WELCOME_DISCOUNT();
        if(is_array($profilesArr) && $profilesArr)
        {
        	//discount on all active plans
			$memObj = new MembershipHandler();
			$serviceArr = $memObj->getActiveServices();
			unset($memObj);
			$vdObj = new VariableDiscount();
        		foreach ($profilesArr as $key => $details) {
        		//if(!$discountObj->ifAlreadyOfferedWD($details["PROFILEID"]))
        		{
                	//activate discount
                	$startDate = date('Y-m-d', strtotime($details["ENTRY_DT"]));
                	$endDate = date("Y-m-d",strtotime($discountDuration, strtotime($startDate)));
                	$discountDetails = array("discountPercent"=>$discountPercent,"startDate"=>$startDate,"endDate"=>$endDate,"entryDate"=>date('Y-m-d'),"DISC2"=>$discountPercent,"DISC3"=>$discountPercent,"DISC6"=>$discountPercent,"DISC12"=>$discountPercent,"DISCL"=>$discountPercent);
                	$vdObj->activateVDForProfile($details["PROFILEID"],$discountDetails,$serviceArr,$sendMailForDiscount,$sendSMSForDiscount);
                	unset($discountDetails);
                	$discountObj->addEntry($details["PROFILEID"]); 
                	}
            	}
		unset($vdObj);
        }
        unset($profilesArr);
        unset($discountObj);
	}
}
