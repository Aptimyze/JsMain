<?php

// This cron will check whether the profile gets register after 30 days of registration
class cronCheckBillingAfterRegistrationTask extends sfBaseTask
{

    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name', 'operations')
        ));
        
        $this->namespace = 'billing';
        $this->name = 'cronCheckBillingAfterRegistration';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
        The [billing|INFO] task does things.
        Call it with:
        [php symfony billing:cronCheckBillingAfterRegistration|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        // Get the set of profile which are
        $mis_table = new MIS_CAMPAIGN_KEYWORD_TRACKING();
        $jprofile = new JPROFILE("newjs_slave");
        // $clientUsername = new JPROFILE("newjs_slave");
        $paymentsDetailsNew = new billing_PAYMENT_DETAIL_NEW();
        $timePeriod = 30;
        $jprofileArray = array();
        $limit = 200;
        $count = 0;
        $paymentDetailsNewArray = array();
        $profileArray = $mis_table->getActiveProfile(null);
        $length = count($profileArray);
        while ($count <= $length && ! empty($profileArray)) {
            $temp = array_slice($profileArray, $count, $count + $limit - 1);
            $str_temp = implode(",", $temp);
            $resJprofile = $jprofile->getEntryDtJprofile($temp);
            $resPaymentDetailsNew = $paymentsDetailsNew->getFirstEntryDate($temp, "DONE");
            if (! empty($resJprofile) && is_array($jprofileArray) && is_array($resJprofile))
                $jprofileArray = $resJprofile + $jprofileArray;
            if (! empty($resPaymentDetailsNew) && is_array($resPaymentDetailsNew) && is_array($paymentDetailsNewArray))
                $paymentDetailsNewArray = $resPaymentDetailsNew + $paymentDetailsNewArray;
            $count += $limit;
        }
        foreach ($jprofileArray as $key => $value) {
            $profileId = $key;
            $entryDt = $value;
            $explodeArray = explode(" ", $entryDt);
            $dateJprofile = $explodeArray[0];
            $datePayment = $paymentDetailsNewArray[$profileId];
            
            if ($dateJprofile != null && $datePayment != null) {
                $explodeArray = explode(" ", $datePayment);
                $datePayment = $explodeArray[0];
                $diff = $this->dateDiff($dateJprofile, $datePayment);
                if ($diff < 30)
                    $mis_table->updateIsPaidStatus($profileId, 'Y');
                else
                    $mis_table->updateIsPaidStatus($profileId, 'N');
            } else if ($dateJprofile != null) {
                $curDate = date('Y-m-d');
                $diffDt = $this->dateDiff($date1, $curDate);
                if ($diffDt >= 30)
                    $mis_table->updateIsPaidStatus($profileId, 'N');
            }
            unset($explodeArray);
        }
        unset($mis_table);
        unset($jprofile);
        unset($jprofileArray);
        unset($paymentDetailsNewArray);
    }

    public function dateDiff($date1, $date2)
    {
        $date1_ts = strtotime($date1);
        $date2_ts = strtotime($date2);
        $diff = $date2_ts - $date1_ts;
        return round($diff / 86400);
    }
}
