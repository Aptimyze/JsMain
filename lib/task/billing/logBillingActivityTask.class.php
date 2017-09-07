<?php

class logBillingActivityTask extends sfBaseTask
{
    protected function configure()
    {
        $this->addOptions(array(new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','jeevansathi'),
        ));

        $this->namespace        = 'billing';
        $this->name             = 'logBillingActivity';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
        The [logBillingActivity|INFO] task does things.
        Call it with:
        [php symfony logBillingActivity|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        sfContext::createInstance($this->configuration);
        // Temporarily setting date time zone to IST for this cron
        $orgTZ = date_default_timezone_get();

        $start_time = date("Y-m-d H:i:s", (time()-2*60*60));
        $end_time = date("Y-m-d H:i:s", time());

        date_default_timezone_set("Asia/Calcutta");
        $ind_start_time = date("Y-m-d H:i:s", (time()-2*60*60));
        $ind_end_time = date("Y-m-d H:i:s", time());

        // 10 Transactions per 2 hours (8AM:8PM) else 2 Transactions per 2 hours (8PM:8AM)
        if(strtotime($ind_start_time) >= strtotime(date("Y-m-d 08:00:00", time())) && strtotime($ind_end_time) <= strtotime(date("Y-m-d 20:00:00", time()))){
        	$countCheck = 10;
        } else {
        	$countCheck = 2;
        }
        $billPaymentDet = new BILLING_PAYMENT_DETAIL();
        $billOrdDev = new billing_ORDERS_DEVICE();
        $billOrd = new BILLING_ORDERS();
        
        // Fetching All payments within last 2 hours
        $paidProfilesArr = $billPaymentDet->getProfilesWithinDateRangeNew($start_time, $end_time);
        
        //print_r(array($start_time, $end_time, $ind_start_time, $ind_end_time));
        
        $billidStr = ',';
        if(is_array($paidProfilesArr)){
	        foreach($paidProfilesArr as $key=>$val){
	            $billidStr .= $val['BILLID'].',';
        	}
        	$billidStr = ltrim(rtrim($billidStr,','),',');

        	//print $billidStr."                     \n";

        	// Fetching Payment Source(Channels)
	        $orderDetArr = $billOrdDev->getPaymentSourceFromBillidStr($billidStr);
	        $orderIdStr = ',';

	        foreach($orderDetArr as $key=>$val){
	            $orderIdStr .= $val['ID'].',';
	        }
	        $orderIdStr = ltrim(rtrim($orderIdStr,','),',');

	        //print $orderIdStr."                     \n";

	        // Fetching Payment Gateway
	        $orderGatewayArr = $billOrd->getOrderDetailsForIdStr($orderIdStr);

	        //print_r($orderGatewayArr)."                     \n";

	        // Setting Gateway for All Payments
	        foreach($orderDetArr as $key=>&$val){
	            if(in_array($val['ID'], array_keys($orderGatewayArr))){
	                $val['GATEWAY'] = $orderGatewayArr[$val['ID']]['GATEWAY'];
	            }
	        }
	        
	        // Setting Sources(Channels) for All Payments
	        foreach($paidProfilesArr as $key=>&$val){
	            // Setting proper Channel, if not found i.e. backend billind default Desktop
	            if(in_array($val['BILLID'], array_keys($orderDetArr))){
	            	$val['SOURCE'] = $orderDetArr[$val['BILLID']]['SOURCE'];
	                $val['GATEWAY'] = $orderDetArr[$val['BILLID']]['GATEWAY'];
	            } else {
	                $val['SOURCE'] = 'desktop';
	                $val['GATEWAY'] = 'BACKEND';
	            }
	        }
        }

        $countArr['INR_TRANSACTION'] = 0;
        $countArr['DOLLAR_TRANSACTION'] = 0;
        $countArr['DESKTOP'] = 0;
        $countArr['MOBILE_WEBSITE'] = 0;
        $countArr['ANDROID'] = 0;
        $countArr['IOS'] = 0;
        $countArr['CCAVENUE'] = 0;
        $countArr['PAYU'] = 0;
        $countArr['PAYTM'] = 0;
        $countArr['BACKEND'] = 0;
        if(is_array($paidProfilesArr)){
	        foreach($paidProfilesArr as $key=>$val){
	        	if($val['TYPE'] == 'DOL'){
	        		$countArr['DOLLAR_TRANSACTION']++;
	        	}
	        	if($val['TYPE'] == 'RS'){
	        		$countArr['INR_TRANSACTION']++;
	        	}
	        	if($val['SOURCE'] == 'desktop'){
	        		$countArr['DESKTOP']++;
	        	}
	        	if($val['SOURCE'] == 'mobile_website' || $val['SOURCE'] == 'old_mobile_website'){
	        		$countArr['MOBILE_WEBSITE']++;
	        	}
	        	if($val['SOURCE'] == 'Android_app' || $val['SOURCE'] == 'JSAA_mobile_website'){
	        		$countArr['ANDROID']++;
	        	}
	        	if($val['SOURCE'] == 'iOS_app'){
	        		$countArr['IOS']++;
	        	}
	        	if($val['GATEWAY'] == 'PAYU'){
	        		$countArr['PAYU']++;
	        	}
	        	if($val['GATEWAY'] == 'CCAVENUE'){
	        		$countArr['CCAVENUE']++;	
	        	}
	        	if($val['GATEWAY'] == 'PAYTM'){
	        		$countArr['PAYTM']++;
	        	}
	        	if($val['GATEWAY'] == 'BACKEND'){
	        		$countArr['BACKEND']++;
	        	}
	        }
	    }

	    //print_r($paidProfilesArr)."                     \n";

        $to = "rohan.mathur@jeevansathi.com,vidushi@naukri.com,vijay.bhaskar@jeevansathi.com";
        $from = "js-sums@jeevansathi.com";
        $subject = "Membership Stats Last 2 Hours (IST) : {$ind_start_time} to {$ind_end_time}";
        $SMS_MESSAGE = '';
        foreach($countArr as $key=>$val){
        	$msgBody .= "<br><strong>{$key}</strong> :: {$val}";
        	$SMS_MESSAGE .= $key." - ".$val.", ";
        }

        $SMS_MESSAGE = rtrim($SMS_MESSAGE,", ");
                
        $alertArr = array('AVNEET'=>9711458230,'MANOJ'=>9999216910,'NITISH'=>8989931104,'VIBHOR'=>9868673709,'TUSHAR'=>9013609387,'AYUSHI'=>9711540936,'ANIKET'=>7503366474,'VIDUSHI'=>9711304800);

        SendMail::send_email($to, $msgBody, $subject, $from);

        if(($countArr['INR_TRANSACTION'] + $countArr['DOL_TRANSACTION']) < $countCheck) {
        	include_once(JsConstants::$docRoot."/classes/SmsVendorFactory.class.php");
        	$smsVendorObj = SmsVendorFactory::getSmsVendor("air2web");
        	$profileid = "144111";
			foreach($alertArr as $key=>$val){
				$xmlData1 = $xmlData1 . $smsVendorObj->generateXml($profileid,$val,$SMS_MESSAGE);
			}
			if($xmlData1){
				$smsVendorObj->send($xmlData1,"transaction");
			}
			unset($xmlData1);
        }

		date_default_timezone_set($orgTZ);

    }
}
