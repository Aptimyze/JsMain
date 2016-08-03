<?php

class membershipExpiryMailerForContactsTask extends sfBaseTask
{
	protected function configure()
	{
		$this->addOptions(array(
			new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
		));

		$this->namespace        = 'mailer';
		$this->name             = 'membershipExpiryMailerForContacts';
		$this->briefDescription = 'VD Mailer task to send mail to VD Users';
		$this->detailedDescription = <<<EOF
		The [membershipPromotionMailer|INFO] task does things.
		Call it with:
		[php symfony mailer:membershipExpiryMailerForContacts|INFO]
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

		// Get profiles
		$todayDate =date("Y-m-d");
		$expiryDate =date("Y-m-d",strtotime("$todayDate -1 days")); 
		$endDate =$expiryDate." 23:59:59";
		$serviceStatusObj = new BILLING_SERVICE_STATUS('newjs_local111');
		$profilesArr =$serviceStatusObj->getExpiredProfilesForDate($expiryDate);
		//print_r($profilesArr);

		$mailId 	='1836';
		$attachmentName ='Jeevansathi-Contacts.xls';
		$mmObj 		=new MembershipMailer();
		$header 	=array("USERNAME"=>"Profile ID","VIEWED_DATE"=>"Contact viewed on","MOBILE"=>"Mobile Number","LANDLINE"=>"Landline Number","ALT"=>"Alternate Number","EMAIL"=>"Email ID");

		if(count($profilesArr)>0){
			foreach($profilesArr as $key=>$data){
				$profileid		=$data['PROFILEID'];
				$dataArr['profileid']	=$profileid;
				$startDate		=$data['ACTIVATED_ON'];
				$dataSet 		=$mmObj->getContactsViewedList($profileid,$startDate,$endDate);
				$attachment		=$mmObj->getExcelData($dataSet,$header);	
				print_r($attachment);
				$deliveryStatus		=$mmObj->sendMembershipMailer($mailId, $profileid,$dataArr,$attachment,$attachmentName);
				unset($dataArr);
			}
			unset($mmObj);
			unset($vdObj);
			unset($profilesArr);
		}
	}
}
