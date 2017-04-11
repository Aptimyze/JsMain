<?php

/**
 * Membership User
 *
 *
 * @package    jeevansathi
 * @author     Lakshay
 * @created    14-02-2013
 */

class memUser
{
    public $userType;
    public $memStatus;
    public $ipAddress;
    public $currency;
    public $profileid;
    public $expiryDate;
    public $contactsRemaining;
    public $memObj;
    public $festFlag;
    public $memUpgradeEligible = false;
    public $mtongue;

    function __construct($profileid) {
        if ($profileid != '') {
            $this->setProfileid($profileid);
            $this->ipAddress = $this->getIpAddress();
            $this->currency = $this->getCurrency();
            $this->setMtongue($profileid);
            $this->memObj = new JMembership();
        } 
        else {
            $this->setProfileid('');
            $this->userType = "1";
            $this->currency = $this->getCurrency();
            $this->ipAddress = $this->getIpAddress();
            $this->mtongue = "-1";
        }
    }

    public function setMtongue($profileid=""){
        
        if($profileid != ""){
            $profileObj = LoggedInProfile::getInstance('newjs_master');

            if($profileObj != null){
                $this->mtongue = $profileObj->getMTONGUE();
            }
            if(!empty($this->mtongue)){

                $serviceObj = new billing_SERVICES("newjs_slave");
                $activeOnlineServices = $serviceObj->getOnlineActiveDurations($this->mtongue);
                unset($serviceObj);
                if(!is_array($activeOnlineServices) || count($activeOnlineServices)==0){
                    $this->mtongue = "-1";
                }
            }
        }
    }

    public function setMemStatus($fromBackend="") {
        list($userType, $expiryDate, $memStatus) = $this->memObj->getMemUserType($this->profileid,$fromBackend);
        if ($userType == 2) {
            $this->userType = memUserType::FREE;
        } 
        elseif ($userType == 3) {
            $this->userType = memUserType::EXPIRED_BEYOND_LIMIT;
            $this->memStatus = $memStatus;
        } 
        elseif ($userType == 4) {
            $this->expiryDate = $expiryDate;
            $this->userType = memUserType::EXPIRED_WITHIN_LIMIT;
            $this->memStatus = $memStatus;
        } 
        elseif ($userType == 5) {
            $this->userType = memUserType::PAID_BEYOND_RENEW;
            $this->memStatus = $memStatus;
        } 
        elseif ($userType == 6) {
            $this->expiryDate = $expiryDate;
            $this->userType = memUserType::PAID_WITHIN_RENEW;
            $this->memStatus = $memStatus;
        } 
        elseif ($userType == 7) {
            $this->userType = memUserType::ONLY_VAS;
        }
        elseif ($userType == memUserType::UPGRADE_ELIGIBLE) {
            $this->userType = memUserType::UPGRADE_ELIGIBLE;
            $this->memStatus = $memStatus;
        }
        else echo "blank";
        $this->expiryDate = date("jS F", strtotime($expiryDate));
        $this->memUpgradeEligible = $memUpgradeEligible;
    }

    public function getIpAddress() {
        return $this->ipAddress;
    }

    public function setIpAddress($ipAddressSet) {
        return $this->ipAddress = $ipAddressSet;
    }

    public function getCurrency() {
        return $this->currency;
    }

    public function setCurrency($currencyType) {
        return $this->currency = $currencyType;
    }

    public function setProfileid($profileid) {
        $this->profileid = $profileid;
    }

    public function getProfileid() {
        return $this->profileid;
    }
	
	public function setFestInfo($festFlag) {
        $this->festFlag = $festFlag;
    }

    public function getFestInfo() {
        return $this->festFlag;
    }

    public function getRemainingContacts($profileid,$extraFields="") {
        $contacts = $this->memObj->getRemainingContactsForUser($this->profileid,$extraFields);
        return $contacts;
    }

    public function getMemStatus() {
        return $this->memStatus;
    }
    
    public function getUserType() {
        return $this->userType;
    }
}
