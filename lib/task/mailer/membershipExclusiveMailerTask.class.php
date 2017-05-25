<?php

class membershipExclusiveMailerTask extends sfBaseTask
{
	protected function configure()
	{
		$this->addOptions(array(
			new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
		));

		$this->namespace        = 'mailer';
		$this->name             = 'membershipExclusiveMailer';
		$this->briefDescription = 'Paid Exclusive membership mailers on certain dates after purchase';
		$this->detailedDescription = <<<EOF
		The [membershipPromotionMailer|INFO] task does things.
		Call it with:
		[php symfony mailer:membershipExclusiveMailer|INFO]
EOF;
	}

	protected function execute($arguments = array(), $options = array())
	{
	    	// SET BASIC CONFIGURATION
		if(!sfContext::hasInstance()){
			sfContext::createInstance($this->configuration);
		}
		$mailId ='1797';
		$mmObj = new MembershipMailer();
		$profilesArr =$mmObj->getJsExclusiveProfiles();
		if(count($profilesArr)>0){
			$jprofileObj = new JPROFILE('newjs_slave');
			$subsArr = $jprofileObj->getAllSubscriptionsArr($profilesArr);
			foreach($profilesArr as $key=>$profileid){
				if(strpos($subsArr[$profileid]['ISD'], "91") !== false){
					$currency = "RS";
				} else {
					$currency = "DOL";
				}
				$dataArr['currency'] =$currency;
				$mmObj->sendMembershipMailer($mailId, $profileid, $dataArr);
				unset($dataArr);
				$count++;
			}
		}
		unset($mmObj);
                $to             ="rohan.mathur@jeevansathi.com,manoj.rana@naukri.com,vibhor.garg@jeevansathi.com";
		$latest_date	=date("Y-m-d");
                $subject        ="Exclusive Feerback Monthly Mailer for ".date("jS F Y", strtotime($latest_date));
                $fromEmail      ="From:JeevansathiCrm@jeevansathi.com";
                $msg            ="Total mails sent : $count";
                mail($to,$subject,$msg,$fromEmail);
	}
}
