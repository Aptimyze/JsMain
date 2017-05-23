<?php

/**
 * This task checks for PayU Payments that were not completed in the last 24 hours
 * using th PayU API and updated their statuses
 * It is scheduled to run every 2 minuted and pick the records of last 24 hours
 */

class renewalSurveySmsTask extends sfBaseTask
{
    protected function configure()
    {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
        ));

        $this->namespace        = 'billing';
        $this->name             = 'renewalSurveySms';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
        The [billing|INFO] task does things.
        Call it with:
        [php symfony billing:renewalSurveySms|INFO]
EOF;

    }

    protected function execute($arguments = array(), $options = array())
    {   
        // SET BASIC CONFIGURATION
        if (!sfContext::hasInstance()) {
            sfContext::createInstance($this->configuration);
        }
        
        // Current Date +15 days
        $expDate = date("Y-m-d", time() + 15*24*60*60);

        include_once(JsConstants::$docRoot."/classes/SmsVendorFactory.class.php");
        $smsVendorObj = SmsVendorFactory::getSmsVendor("air2web");
        
        // SMS Message to be sent to users
        $SMS_MESSAGE = "How has your experience been as a paid member on Jeevansathi? Please answer this simple question https://goo.gl/vByKzj";

        $billingServStatObj = new BILLING_SERVICE_STATUS('newjs_slave');
        $jprofileAlertObj = new JprofileAlertsCache('newjs_slave');
        $jprofileObj = new JPROFILE('newjs_slave');

        // Fetch profiles expiring on curDate +15 days
        $eligibleProfileTempArr = $billingServStatObj->getRenewalProfilesForDates($expDate, $expDate);
        
        foreach($eligibleProfileTempArr as $key=>$val){
            $eligibleProfileArr[] = $val['PROFILEID'];
        }

        if(is_array($eligibleProfileArr)){
            $profileSubscriptionsAlertsArr = $jprofileAlertObj->getAllSubscriptionsArr($eligibleProfileArr);
            $profileSubscriptionsJprofileArr = $jprofileObj->getAllSubscriptionsArr($eligibleProfileArr);
            foreach($eligibleProfileArr as $key=>$profileid){
                $servSms = $profileSubscriptionsAlertsArr[$profileid]['SERVICE_SMS'];
                $inrStatus = $profileSubscriptionsJprofileArr[$profileid]['ISD'];
                $mobStat = $profileSubscriptionsJprofileArr[$profileid]['MOB_STATUS'];
                $phoneMob = $profileSubscriptionsJprofileArr[$profileid]['PHONE_MOB'];
                if($servSms == 'S' && strpos($inrStatus, "91") !== false && $mobStat == 'Y'){
                    // Generating SMS String 
                    $xmlData1 = $xmlData1 . $smsVendorObj->generateXml($profileid,$phoneMob,$SMS_MESSAGE);        
                }
                unset($servSms);
                unset($inrStatus);
                unset($mobStat);
                unset($phoneMob);
                unset($$pos);
            }
        }

        // Finally Send SMS
        if($xmlData1){
            $smsVendorObj->send($xmlData1,"transaction");
        }
        unset($xmlData1);
    }
}