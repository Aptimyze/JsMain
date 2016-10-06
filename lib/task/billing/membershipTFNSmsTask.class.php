<?php

class membershipTFNSmsTask extends sfBaseTask
{
    protected function configure()
    {

        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name', 'operations'),
        ));

        $this->namespace           = 'SMSAlert';
        $this->name                = 'membershipTFNSms';
        $this->briefDescription    = '';
        $this->detailedDescription = <<<EOF
		The [SMSAlert|INFO] task does things.
		Call it with:
		[php symfony SMSAlert:membershipTFNSms|INFO]
EOF;

    }

    protected function execute($arguments = array(), $options = array())
    {
        // SET BASIC CONFIGURATION
        if (!sfContext::hasInstance()) {
            sfContext::createInstance($this->configuration);
        }
        $curDate         = date("Y-m-d", time());
        $billPurObj      = new BILLING_PURCHASES('newjs_slave');
        $billServStatObj = new BILLING_SERVICE_STATUS('newjs_slave');
        $profileArr1     = $billPurObj->fetchTFNSMSProfiles($curDate); // P+15, P+45
        $profileArr2     = $billServStatObj->fetchTFNSMSProfiles($curDate); // E-32
        $billidArr       = array();
        $profileArr      = array();
        if (is_array($profileArr1) && !empty($profileArr1)) {
            foreach ($profileArr1 as $key => $val) {
                if (strstr($val['SERVICEID'], 'W') === false) {
                    $billidArr[] = $val['BILLID'];
                } else {
                    if ((strtotime($curDate) - 30 * 24 * 60 * 60) <= strtotime($val['ENTRY_DT'])) {
                        $billidArr[] = $val['BILLID'];
                    }
                }
            }
            $billIdArr  = @array_unique($billidArr);
            $profileArr = $billServStatObj->filterActiveProfilesFromBillidArr($billIdArr);
            unset($key, $val);
        }
        if (is_array($profileArr2) && !empty($profileArr2)) {
            foreach ($profileArr2 as $key => $val) {
                $profileArr[] = $val;
            }
        }
        foreach ($profileArr as $key=>$val) {
            $profile[] = $val['PROFILEID'];
        }
        $profile = @array_filter(array_unique($profile));
        if (is_array($profile) && !empty($profile)) {
            foreach ($profile as $key => $val) {
                CommonUtility::sendPlusTrackInstantSMS("MEM_TFN_SMS", $val);
            }
        }
    }
}
