<?php

class membershipExpiryMailerForContactsTask extends sfBaseTask
{
    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name', 'operations'),
        ));

        $this->namespace           = 'mailer';
        $this->name                = 'membershipExpiryMailerForContacts';
        $this->briefDescription    = 'VD Mailer task to send mail to VD Users';
        $this->detailedDescription = <<<EOF
		The [membershipPromotionMailer|INFO] task does things.
		Call it with:
		[php symfony mailer:membershipExpiryMailerForContacts|INFO]
EOF;
    }

    protected function execute($arguments = array(), $options = array())
    {
        // SET BASIC CONFIGURATION
        if (!sfContext::hasInstance()) {
            sfContext::createInstance($this->configuration);
        }
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', -1);

        // Get profiles
        $todayDate        = date("Y-m-d");
        $expiryDate1      = date("Y-m-d", strtotime("$todayDate -3 days"));
        $expiryDate2      = date("Y-m-d", strtotime("$todayDate -1 days"));
        $endDate          = $expiryDate2 . " 23:59:59";
        $serviceStatusObj = new BILLING_SERVICE_STATUS('newjs_local111');
        $profilesArr      = $serviceStatusObj->getExpiredProfilesForDate($expiryDate1, $expiryDate2);

        // logic to handle profiles lost dur to slave lag
        if (is_array($profilesArr)) {
            $profilesArr1   = array_keys($profilesArr);
            $memExpObj      = new billing_MEM_EXPIRY_CONTACTS_LOG('newjs_local111');
            $getProfileList = $memExpObj->getProfileList($expiryDate1);
            if (!is_array($getProfileList)) {
                $getProfileList = array();
            }

            $profilesArr2 = array_diff($profilesArr1, $getProfileList);
            if (is_array($profilesArr2)) {
                foreach ($profilesArr2 as $key => $profileid) {
                    $profilesArrNew[] = $profilesArr[$profileid];
                }
            }
        }
        unset($profilesArr);
        unset($profilesArr1);
        unset($profilesArr2);
        unset($getProfileList);

        $mailId         = '1836';
        $attachmentName = 'Jeevansathi-Contacts.csv';
        $mmObj          = new MembershipMailer();
        $memExpInsObj   = new billing_MEM_EXPIRY_CONTACTS_LOG();
        $header         = array("USERNAME" => "Profile ID", "VIEWED_DATE" => "Contact viewed on", "MOBILE" => "Mobile Number", "LANDLINE" => "Landline Number", "ALT" => "Alternate Number", "EMAIL" => "Email ID");

        if (count($profilesArrNew) > 0) {
            foreach ($profilesArrNew as $key => $data) {
                $profileid            = $data['PROFILEID'];
                $dataArr['profileid'] = $profileid;
                $startDate            = $data['ACTIVATED_ON'];
                $dataSet              = $mmObj->getContactsViewedList($profileid, $startDate, $endDate);
                if ($dataSet) {
                    $attachment     = $mmObj->getCsvData($dataSet, $header);
                    $deliveryStatus = $mmObj->sendMembershipMailer($mailId, $profileid, $dataArr, $attachment, $attachmentName);
                    $memExpInsObj->add($profileid);
                    //print_r($attachment);
                }
                unset($dataArr);
            }
            unset($mmObj);
        }
    }
}
