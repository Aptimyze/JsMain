<?php

class vdDiscountOfferTask extends sfBaseTask
{
	protected function configure()
	{

		$this->addOptions(array(
			new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
			));

		$this->namespace        = 'SMSAlert';
		$this->name             = 'vdDiscountOffer';
		$this->briefDescription = '';
		$this->detailedDescription = <<<EOF
		The [SMSAlert|INFO] task does things.
		Call it with:
		[php symfony SMSAlert:vdDiscountOffer|INFO]
EOF;

	}

	protected function execute($arguments = array(), $options = array())
	{	
		// SET BASIC CONFIGURATION
		if(!sfContext::hasInstance()){
			sfContext::createInstance($this->configuration);
		}
		$entry_dt = date("Y-m-d");
		include_once(JsConstants::$docRoot."/profile/connect_db.php");
		include_once(JsConstants::$docRoot."/classes/ScheduleSms.class.php");
		include_once(JsConstants::$docRoot."/classes/Membership.class.php");
		$vdDiscountSmsLog = new billing_VARIABLE_DISCOUNT_SMS_LOG('newjs_slave');
		$status = $vdDiscountSmsLog->checkVDStatus($entry_dt);
		if($status){
			$sms = new ScheduleSms;
			//$sms->processData("VD1",$entry_dt);
			//$sms->processData("VD2",$entry_dt);
			$sms->processData("VD1",$entry_dt);
		}
	}
}
