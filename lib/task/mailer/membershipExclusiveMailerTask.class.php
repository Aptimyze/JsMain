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
    //Start:JSC-2649: Remove dummy profiles from mailer
    public function returnFilteredProfilesAfterDummyExclusion($profileArr) {
        // Exclude Dummy Marked profiles from the list of incoming profiles
        // Bulk Function, makes single call to jsadmin_PremiumUsers Table
        // Gets array of dummy marked profiles out of original profiles array
        $premiumUsersObj = new jsadmin_PremiumUsers('newjs_slave');
        $profileDummyArr = $premiumUsersObj->filterDummyProfiles($profileArr);
        $profileArr= array_diff($profileArr, $profileDummyArr);
        // Sanitization of final array after removal of dummy profiles
        $profileArrFinal = array_values(array_filter(array_unique($profileArr)));
        // Clear Memory
        unset($profileArr, $profileDummyArr);
        return $profileArrFinal;
    }
    //End:JSC-2649: Remove dummy profiles from mailer

       protected function execute($arguments = array(), $options = array())
	{
	    	// SET BASIC CONFIGURATION
		if(!sfContext::hasInstance()){
			sfContext::createInstance($this->configuration);
		}
		$mailId ='1797';
		$mmObj = new MembershipMailer();
		$profilesArr =$mmObj->getJsExclusiveProfiles();
                //JSC-2649:Going to remove dummy profiles from complete profiles array
                $profilesArr = $this->returnFilteredProfilesAfterDummyExclusion($profilesArr);
		if(count($profilesArr)>0){
			$jprofileObj = new JPROFILE('newjs_slave');
			$subsArr = $jprofileObj->getAllSubscriptionsArr($profilesArr);
			foreach($profilesArr as $key=>$profileid){
				if(strpos($subsArr[$profileid]['ISD'], "91") !== false){
					$currency = "RS";
				} else {
					$currency = "DOL";
				}
                                $dataArr["username"] = $subsArr[$profileid]['USERNAME'];
				$dataArr['currency'] =$currency;
				$mmObj->sendMembershipMailer($mailId, $profileid, $dataArr);
				unset($dataArr);
				$count++;
			}
		}
		unset($mmObj);
                $to             ="rohan.mathur@jeevansathi.com,manoj.rana@naukri.com,vibhor.garg@jeevansathi.com,anurag.tripathi@jeevansathi.com";
		$latest_date	=date("Y-m-d");
                $subject        ="Exclusive Feerback Monthly Mailer for ".date("jS F Y", strtotime($latest_date));
                $fromEmail      ="From:JeevansathiCrm@jeevansathi.com";
                $msg            ="Total mails sent : $count";
                mail($to,$subject,$msg,$fromEmail);
	}
}
