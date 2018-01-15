<?php

/**************************************************************************************************************************************

* DESCRIPTION   : Cron script, scheduled daily to send a CSV with username, latest analytics score, last login date and city (JSC-2048)
***************************************************************************************************************************************/

class preAllocatedProfilesMailerTask extends sfBaseTask{
    protected  function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
        ));
        
        $this->namespace = "csvGeneration";
        $this->name = "preAllocatedProfilesMailerTask";
        $this->briefDescription = "";
        $this->detailedDescription = <<<EOF
            The [preAllocatedProfilesMailerTask|INFO] task does things.
            Call it with:[php symfony csvGeneration:preAllocatedProfilesMailerTask|INFO]
EOF;
    }
    
    protected function execute($arguments = array(), $options = array())
    {
        if(!sfContext::hasInstance())
            sfContext::createInstance($this->configuration);
        
        //$profileAllocationTechObj = new incentive_PROFILE_ALLOCATION_TECH("newjs_slave");
        //$profileidArr = $profileAllocationTechObj->fetchAllProfileIds();
        
        $dt = date('Y-m-d');
        //$dt = "2015-08-05";
        $preAllocationLogObj = new incentive_PRE_ALLOCATION_LOG("newjs_slave");
        $preAllocationData = $preAllocationLogObj->getProfileIdsScoreForDate($dt); 
        if($preAllocationData){
            $profileidArr = array_keys($preAllocationData);
            $jprofileObj = new JPROFILE("newjs_slave");
            $profileDetails = $jprofileObj->getProfileSelectedDetails($profileidArr,"PROFILEID, USERNAME,LAST_LOGIN_DT,CITY_RES,COUNTRY_RES");
            
            //$mainAdminPoolObj = new incentive_MAIN_ADMIN_POOL("newjs_slave");
            //$analyticScore = $mainAdminPoolObj->getArray(array("PROFILEID"=>  implode(",", $profileidArr)), '', '', 'PROFILEID, ANALYTIC_SCORE');
            $purchasesObj = new billing_PURCHASES("newjs_slave");
            $profilesStr = implode(",", $profileidArr);
            $purchaseDetails = $purchasesObj->isPaidEver($profilesStr);
            unset($purchasesObj);
            foreach($preAllocationData as $profileid => $details){
                unset($tempArr);
                $tempArr["USERNAME"] = $profileDetails[$profileid]["USERNAME"];
                $tempArr["ALLOTED_TO"] = $details["ALLOTED_TO"];
                $tempArr["ANALYTIC_SCORE"] = $details["SCORE"];
                $tempArr["LAST_LOGIN_DT"] = $profileDetails[$profileid]["LAST_LOGIN_DT"];
                $countryCode = $profileDetails[$profileid]["COUNTRY_RES"];
                if($countryCode  != "51"){
                    $tempArr["CITY_RES"] = FieldMap::getFieldLabel("country", $countryCode);
                }
                else{
                    $cityCode = $profileDetails[$details["PROFILEID"]]["CITY_RES"];
                    $tempArr["CITY_RES"] = FieldMap::getFieldLabel("city_india", $cityCode);
                }
                $tempArr["EVER_PAID"] = 'N';
                if(is_array($purchaseDetails) && $profileid && array_key_exists($profileid, $purchaseDetails)){
                    $tempArr["EVER_PAID"] = 'Y';
                }
                $finalArr[]=$tempArr;
            }
            unset($purchaseDetails);
            $filepath = "/var/www/html/web/uploads/csv_files/";
            //$filepath = "/var/www/html/branches/membership/web/uploads/";
            $filename = $filepath."preAllocatedProfileMailer.csv";
            unlink($filename);
            $csvData = fopen("$filename", "w") or print_r("Cannot Open");

            fputcsv($csvData, array('Username','Alloted To','Latest analytics score','Last login date','City','EVER_PAID'));
            foreach($finalArr as $key=>&$val) {
                fputcsv($csvData, $val);
            }
            $file_size = filesize($filename);
            fclose($csvData);

            $csvAttachment = file_get_contents($filename);
            //print_r($csvAttachment);die;
            $to = "isha.mehra@jeevansathi.com,bharat.vaswani@jeevansathi.com,shashank.ghanekar@jeevansathi.com,anamika.singh@jeevasathi.com,rajeev.joshi@jeevansathi.com,rohan.mathur@jeevansathi.com";
            //$to = "nitish.sharma@jeevansathi.com,ankita.g@jeevansathi.com";
            $cc = "nitish.sharma@jeevansathi.com,vibhor.garg@jeevansathi.com,manoj.rana@naukri.com";
            //$cc = "nitish.sharma@jeevansathi.com,vibhor.garg@jeevansathi.com";
            $from = "js-sums@jeevansathi.com";
            $subject = "Pre Allocated Profiles Mailer for allot date ".$dt;
            $msgBody = "PFA CSV report containing pre allocated profiles.";

            SendMail::send_email($to, $msgBody, $subject, $from, $cc, '', $csvAttachment, '', 'preAllocatedProfileMailer.csv');

        }
    }
}
