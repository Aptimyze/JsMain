<?php

class membershipPromotionMailerTask extends sfBaseTask
{
	protected function configure()
	{
		$this->addOptions(array(
			new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
		));

		$this->namespace        = 'mailer';
		$this->name             = 'membershipPromotionMailer';
		$this->briefDescription = 'Paid membership mailers on certain dates after purchase';
		$this->detailedDescription = <<<EOF
		The [membershipPromotionMailer|INFO] task does things.
		Call it with:
		[php symfony mailer:membershipPromotionMailer|INFO]
EOF;
	}

	protected function execute($arguments = array(), $options = array())
	{
	    // SET BASIC CONFIGURATION
		if(!sfContext::hasInstance()){
			sfContext::createInstance($this->configuration);
		}
		
		if (!$_SERVER['DOCUMENT_ROOT']) {
			$_SERVER['DOCUMENT_ROOT'] =sfConfig::get("sf_web_dir");
		}
		include_once($_SERVER['DOCUMENT_ROOT']."/classes/Membership.class.php");
		include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect_db.php");

		//connect_db();

		$mmObj = new MembershipMailer();

		$billingPurchasesObj = new BILLING_PURCHASES('newjs_slave');
		$profileArr = $billingPurchasesObj->getFPRBPromotionalMailerProfiles();
		$ssObj = new BILLING_SERVICE_STATUS('newjs_slave');

		if(!empty($profileArr)) {
			$serviceArr = $ssObj->getActiveServicesListForProfileArr($profileArr);
			foreach($serviceArr as $key=>$val) {
				if(!strstr($val,'T') && !strstr($val,'R')) {
					//$mmObj->sendEmailForFPRBPromotion(1795, $key);
					$mmObj->sendMembershipMailer(1795, $key);
				} 
			}			
		}

		unset($mmObj);
		unset($billingPurchasesObj);
		unset($ssObj);
	}
}
