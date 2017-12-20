<?php

/**************************************************************************************************************************************

* DESCRIPTION   : Script to send an Email, 2 days after purchasing membership and Notification 3 days after purchasing membership (if not already upgraded) to upgrade plan to next level.
***************************************************************************************************************************************/

class upgradeMembershipNotificationMailerTask extends sfBaseTask{
    protected  function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'Application Name','jeevansathi'),
        ));
        
        $this->namespace = "notification";
        $this->name = "upgradeMembershipNotificationMailerTask";
        $this->briefDescription = "";
        $this->detailedDescription = <<<EOF
            The [upgradeMembershipNotificationMailerTask|INFO] task does things.
            Call it with:[php symfony notification:upgradeMembershipNotificationMailerTask|INFO]
EOF;
    }
    
    protected function execute($arguments = array(), $options = array())
    {
        ini_set('max_execution_time',0);
        ini_set('memory_limit',-1);
        if(!sfContext::hasInstance())
            sfContext::createInstance($this->configuration);
        
        $mailerOffsetInDays = "2";
        $notificationOffsetInDays = "3";
        
        $greaterOffset = max($mailerOffsetInDays,$notificationOffsetInDays);
        $greaterThanDate = date('Y-m-d',strtotime("-$greaterOffset days",  strtotime(date('Y-m-d'))))." 00:00:00";
        
        $purchasesObj = new BILLING_PURCHASES("newjs_slave");
        $data = $purchasesObj->getPaidProfiledWithinRange($greaterThanDate);
        
        $segregatedData = $this->segerateDataForNotificationMail($data,$mailerOffsetInDays,$notificationOffsetInDays);
        
        $this->sendMemUpgradeNotification(array_keys($segregatedData["NOTIFICATION"]));
        $this->sendMemUpgradeMailer(array_keys($segregatedData["MAILER"]));
        
    }
    
    public function segerateDataForNotificationMail($data,$mailerOffsetInDays,$notificationOffsetInDays){
        if($data && is_array($data)){
            $mailerStartDate       = date('Y-m-d',strtotime("-$mailerOffsetInDays days",  strtotime(date('Y-m-d'))))." 00:00:00";
            $mailerEndDate         = date('Y-m-d',strtotime("-$mailerOffsetInDays days",  strtotime(date('Y-m-d'))))." 23:59:59";
            $notificationStartDate = date('Y-m-d',strtotime("-$notificationOffsetInDays days",  strtotime(date('Y-m-d'))))." 00:00:00";
            $notificationEndDate   = date('Y-m-d',strtotime("-$notificationOffsetInDays days",  strtotime(date('Y-m-d'))))." 23:59:59";
            foreach($data as $profileid => $billDetails){
                $flag = 0;
                foreach($billDetails as $key=>$val){
                    if($val["MEM_UPGRADE"] == "MAIN")
                        $flag = 1;
                    if(strtotime($mailerStartDate) <= strtotime($val["ENTRY_DT"]) && strtotime($val["ENTRY_DT"]) <= strtotime($mailerEndDate))
                        $result["MAILER"][$profileid] = 1;
                    else if (strtotime($notificationStartDate) <= strtotime($val["ENTRY_DT"]) && strtotime($val["ENTRY_DT"]) <= strtotime($notificationEndDate))
                        $result["NOTIFICATION"][$profileid] = 1;
                }
                if($flag == 1)
                    unset($data[$profileid],$result["MAILER"][$profileid],$result["NOTIFICATION"][$profileid]);
            }
            return $result;
        }
    }
    
    public function sendMemUpgradeNotification($notificationProfilesArr){
        //$notificationProfilesArr[] = 5616315;
        if($notificationProfilesArr && is_array($notificationProfilesArr)){
            $instantNotificationObj = new InstantAppNotification("UPGRADE_MEMBERSHIP");
            foreach($notificationProfilesArr as $profileid){
                $instantNotificationObj->sendNotification($profileid);
            }
            unset($instantNotificationObj,$notificationProfilesArr);
        }
    }
    public function sendMemUpgradeMailer($mailerProfilesArr){
        //$mailerProfilesArr[] = 9204277;
        if($mailerProfilesArr && is_array($mailerProfilesArr)){
            $notificationDataPoolObj = new NotificationDataPool();
            foreach($mailerProfilesArr as $profileid){
                unset($upgradeData);
                $upgradeData = $notificationDataPoolObj->getUpgradedMembershipDetails($profileid);
                if($upgradeData){
                    unset($top8Mailer);
                    $group = $this->getUpgradeMailerGroup($upgradeData["upgradeMainMem"]);
                    if($group){
                        $memPurchasedDate = date('jS M Y',  strtotime($upgradeData["memPurchasedDate"]));
                        $upgradePrice = $upgradeData["upgradeExtraPay"];
                        $memHandlerObj = new MembershipHandler();
                        $membershipPageLink = $memHandlerObj->getMembershipAutoLoginLink($profileid,"MEM_UPGRADE_MAILER");
                        $top8Mailer = new EmailSender(MailerGroup::TOP8, $group);
                        $tpl = $top8Mailer->setProfileId($profileid);
                        $subject = $this->getUpgradeMailerSubject($upgradeData["upgradeMainMem"], $upgradeData["upgradeExtraPay"]);
                        $tpl->setSubject($subject);
                        $tpl->getSmarty()->assign("memPurchasedDate",$memPurchasedDate);
                        $tpl->getSmarty()->assign("upgradePrice",$upgradePrice);
                        $tpl->getSmarty()->assign("membershipPageLink",$membershipPageLink);
                        $top8Mailer->send();
                    }
                }
            }
        }
    }
    
    public function getUpgradeMailerGroup($memName){
        if(isset($memName)){
            switch($memName){
                case "C":
                    $group = 1854;
                    break;
                case "NCP":
                    $group = 1855;
                    break;
                case "X":
                    $group = 1856;
                    break;
            }
            return $group;
        }
    }
    
    public function getUpgradeMailerSubject($memName,$upgradedPrice){
        if(isset($memName)){
            switch($memName){
                case "C":
                    $subject = "Let even free members see your contact details. Upgrade to eValue for just Rs.".$upgradedPrice;
                    break;
                case "NCP":
                    $subject = "Get highlighted in Searches, Match of day section, Daily recommendations and in notifications. Upgrade to eAdvantage for just Rs.".$upgradedPrice;
                    break;
                case "X":
                    $subject = "Let a dedicated Relationship Manager help you find a match. Upgrade to JS Exclusive for just Rs.".$upgradedPrice;
                    break;
            }
            return $subject;
        }
    }
    
    
}
