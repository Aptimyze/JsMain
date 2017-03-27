<?php

class variableDiscountMailerTask extends sfBaseTask
{
	protected function configure()
	{
		$this->addOptions(array(
			new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
		));

		$this->namespace        = 'mailer';
		$this->name             = 'variableDiscountMailer';
		$this->briefDescription = 'VD Mailer task to send mail to VD Users';
		$this->detailedDescription = <<<EOF
		The [membershipPromotionMailer|INFO] task does things.
		Call it with:
		[php symfony mailer:variableDiscountMailer|INFO]
EOF;
	}

	protected function execute($arguments = array(), $options = array())
	{
	    	// SET BASIC CONFIGURATION
		if(!sfContext::hasInstance()){
			sfContext::createInstance($this->configuration);
		}
	        ini_set('max_execution_time',0);
        	ini_set('memory_limit',-1);

		/**/
		$vdMailerObj = new billing_VARIABLE_DISCOUNT_MAILER_LOG('newjs_slave');
		$today = date("Y-m-d");
		if($vdMailerObj->sendMailerToday($today))
		{
			/** code for daily count monitoring**/
			$cronDocRoot = JsConstants::$cronDocRoot;
			$php5 = JsConstants::$php5path;
			passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring VD_MAILER#INSERT");
			/**code ends*/
			$countObj = new jeevansathi_mailer_DAILY_MAILER_COUNT_LOG();
			$instanceID = $countObj->getID('VD_MAILER');

			$mailId ='1804';
			$mmObj = new MembershipMailer();
			$vdObj =new VariableDiscount();
			$profilesArr =$vdObj->getVdProfilesForMailer();

			if(count($profilesArr)>0){
				foreach($profilesArr as $profileid=>$details){
					$dataArr['discount'] 	=$details['DISCOUNT'];
					$dataArr['eDate'] 	=$details['EDATE'];
					$dataArr['vdDisplayText']=$vdObj->getVdDisplayText($profileid,'small');
					$dataArr['instanceID']	=$instanceID;
					$dataArr['profileid']	=$profileid;
					$deliveryStatus	=$mmObj->sendMembershipMailer($mailId, $profileid,$dataArr);
					//$deliveryStatus =$mmObj->sendVdMailer($mailId, $profileid, $discount, $eDate, $vdDisplayText, $instanceID);
					$vdObj->updateVdMailerStatus($profileid,$deliveryStatus);
					unset($dataArr);
				}
				/** code for daily count monitoring**/
				passthru("$php5 $cronDocRoot/symfony mailer:dailyMailerMonitoring VD_MAILER");
				/**code ends*/
			}
			unset($mmObj);
			unset($vdObj);
			unset($profilesArr);
		}
	}
}
