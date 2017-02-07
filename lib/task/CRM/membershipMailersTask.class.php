<?php

class membershipMailersTask extends sfBaseTask
{
	protected function configure()
	{
		$this->addOptions(array(
			new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
		));

		$this->namespace        = 'mailer';
		$this->name             = 'membershipMailers';
		$this->briefDescription = 'Paid membership mailers on certain dates after registration';
		$this->detailedDescription = <<<EOF
		The [membershipMailers|INFO] task does things.
		Call it with:
		[php symfony mailer:membershipMailers|INFO]
EOF;
	}

	protected function execute($arguments = array(), $options = array())
	{
	    	// SET BASIC CONFIGURATION
	        ini_set('memory_limit',-1);
		if(!sfContext::hasInstance()){
			sfContext::createInstance($this->configuration);
		}
		if(!$_SERVER['DOCUMENT_ROOT'])
		        $_SERVER['DOCUMENT_ROOT'] =sfConfig::get("sf_web_dir");
		include_once($_SERVER['DOCUMENT_ROOT']."/classes/Membership.class.php");
		include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect_db.php");

		//connect_db();

		$sObj = new billing_SERVICES('newjs_slave');
		$price = $sObj->fetchServicePrice("P12", 'desktop');
		$price_rs = round($price['PRICE_RS_TAX']/365);   // service price in rupees
		$price_cent = round($price['PRICE_DOL']/365*100);   // service price in cents
		unset($sObj);

		$jObj = new JPROFILE('newjs_slave');
		$mmObj = new MembershipMailer();

		// dayAfterRegistration array maps "DAY" => "MAILID"
		$dayAfterRegistration = array(6=>1785,9=>1784,13=>1786,20=>1786,29=>1784,59=>1784,89=>1784,119=>1784,149=>1784,179=>1784,239=>1784,299=>1784,359=>1784);  
		$checkDay =JSstrToTime(date("Y-m-d",time()-14*24*60*60));
		$registerCheckArr =array(29,59,89,119,149,179,239,299,359);

		foreach($dayAfterRegistration as $dd => $mailid) {
			$d = date('Y-m-d', strtotime('-'.$dd.' day'));
			$condition = "ENTRY_DT >= '$d 00:00:00' AND ENTRY_DT <= '$d 23:59:59'";
			$infoArr = $jObj->getMembershipMailerProfiles($condition);

			if(count($infoArr)>0){
				foreach($infoArr as $k => $v) {
					$profileid = $v['PROFILEID'];

					if((strstr($v['SUBSCRIPTION'],"F")!="") || (strstr($v['SUBSCRIPTION'],"D")!="")){
						continue;
					}
					if(in_array($dd, $registerCheckArr)){
						$lastLoginDt =$v['LAST_LOGIN_DT'];
						if(JSstrToTime($lastLoginDt)<$checkDay)
							continue;
					}
					if($dd == 9 || in_array($dd, $registerCheckArr)) {
						$membershipMsg = $mmObj->getJsExclusiveMessage($profileid);
					}
					else if($v['ISD'] == 91) {
						$priceArr = $mmObj->getMembershipDurationsAndPrices($profileid, 'RS');
						$priceArr['CUR'] = 'Rs.';
						$membershipMsg = "Rs. ".$price_rs;
					}
					else {
						$priceArr = $mmObj->getMembershipDurationsAndPrices($profileid, 'DOL');
						$priceArr['CUR'] = '$';
						$membershipMsg = $price_cent."¢";
					}
					//$mmObj->sendEmailAfterRegistration($mailid, $profileid, $membershipMsg, $priceArr);      
					$dataArr =array("membershipMsg"=>"$membershipMsg","priceArr"=>"$priceArr");
					$mmObj->sendMembershipMailer($mailid, $profileid, $dataArr);
					unset($dataArr);
				}
			}
		}

		// Paid condition to send JS-Exclusive mailer
		$profileArr30 =array();
		$profileArr60 =array();
        
        $profileArrPlus30 = array();
        $profileArrMin9 = array();
        $profileArrMin30 = array();
        $profileArrMin60 = array();
        $profileArrMin120 = array();

		$purchase_dt30 = date('Y-m-d', strtotime('-29 day'));
		$profileArr30 = $mmObj->getAllPaidProfiles($purchase_dt30);
		$purchase_dt60 = date('Y-m-d', strtotime('-59 day'));
		$profileArr60 =$mmObj->getAllPaidProfiles($purchase_dt60);
		$profileArr =array_merge($profileArr30,$profileArr60);	
        
        $ssObj = new BILLING_SERVICE_STATUS("newjs_masterRep");
        $purchase_dtPlus30 = date('Y-m-d', strtotime('+25 day'));
        $profileArrPlus30 = $ssObj->getMaxExpiryProfilesForDates($purchase_dtPlus30,$purchase_dtPlus30);
        $purchase_dtMin9 = date('Y-m-d', strtotime('-8 day'));
        $profileArrMin9 = $ssObj->getMaxExpiryProfilesForDates($purchase_dtMin9,$purchase_dtMin9);
        $purchase_dtMin30 = date('Y-m-d', strtotime('-29 day'));
        $profileArrMin30 = $ssObj->getMaxExpiryProfilesForDates($purchase_dtMin30,$purchase_dtMin30);
        $purchase_dtMin60 = date('Y-m-d', strtotime('-59 day'));
        $profileArrMin60 = $ssObj->getMaxExpiryProfilesForDates($purchase_dtMin60,$purchase_dtMin60);
        $purchase_dtMin120 = date('Y-m-d', strtotime('-119 day'));
        $profileArrMin120 = $ssObj->getMaxExpiryProfilesForDates($purchase_dtMin120,$purchase_dtMin120);
        $profileArr =array_merge($profileArr,$profileArrPlus30,$profileArrMin9,$profileArrMin30,$profileArrMin60,$profileArrMin120);	
        //$c = count($profileArr);
        //mail("nitish.sharma@jeevansathi.com,manoj.rana@naukri.com","Membership mailer","Count of JSExclusive Mailer:$c");
		if(count($profileArr)>0) {
			foreach($profileArr as $profileid) {
				if($mmObj->isExclusive($profileid)) 
					continue;
				$dataArr['membershipMsg'] = $mmObj->getJsExclusiveMessage($profileid);
				//$mmObj->sendEmailAfterRegistration(1784, $profileid, $membershipMsg);
				$mmObj->sendMembershipMailer(1784, $profileid, $dataArr);
				unset($dataArr);
			}			
		}
		unset($jObj);
		unset($mmObj);
	}
}
