<?php
include_once ($_SERVER['DOCUMENT_ROOT'] . "/classes/Membership.class.php");
include_once ($_SERVER['DOCUMENT_ROOT'] . "/classes/Memcache.class.php");
include_once ($_SERVER['DOCUMENT_ROOT'] . "/profile/connect_functions.inc");
include_once ($_SERVER['DOCUMENT_ROOT'] . "/classes/globalVariables.Class.php");

class PaymentHandler
{
    function __construct() {
        $this->memObj = new JMembership();
        $this->serviceObj = new JServices();
        $this->payObj = new JPayment();
    }
    
    public function getNearByBranches($profileid) {
        $nearByCities = $this->payObj->get_nearest_branches($profileid);
        return $nearByCities;
    }
    
    public function getStates() {
        $states = $this->payObj->getStates();
        return $states;
    }
    
    public function getBranchesInCity($city_name) {
        $branches = $this->payObj->getChangeBranches($city_name);
        return $branches;
    }

    public function getBranchesInCityArr($cityArr) {
        $branches = $this->payObj->getChangeBranchesArr($cityArr);
        return $branches;
    }
    
    public function getBanks() {
        $banks = $this->payObj->getBanks();
        return $banks;
    }
    
    public function getCityRes($profileid) {
        $city_res = $this->payObj->getCityRes($profileid);
        return $city_res;
    }
    
    public function getPickup($profileid) {
        $city_res = $this->getCityRes($profileid);
        $nearByCities = $this->getNearByCities();
        if (array_key_exists($city_res, $nearByCities)) return $courier = 'Y';
    }
    
    public function getNearByCities($chequePickup = NULL) {
        $nearByCities = $this->payObj->getNearBycities($chequePickup);
        return $nearByCities;
    }
    
    public function getNetBanks() {
        $netBankObj = new billing_NET_BANK();
        $netBanks = $netBankObj->getNetBanks();
        return $netBanks;
    }
    
    public function getPaymentCollectDetails($profileid) {
        $paymentCollectObj = new incentive_PAYMENT_COLLECT();
        $profileDetails = $paymentCollectObj->getLastOfflineOrderDetails($profileid);
        
        $detailsArr['NAME'] = $profileDetails['NAME'];
        $detailsArr['PHONE'] = "+91 - " . $profileDetails['PHONE_MOB'] . " / " . $profileDetails['PHONE_RES'];
        $detailsArr['PHONE_MOB'] = $profileDetails['PHONE_MOB'];
        $detailsArr['PHONE_RES'] = $profileDetails['PHONE_RES'];
        $detailsArr['ADDRESS'] = $profileDetails['ADDRESS'];
        $detailsArr['AMOUNT'] = $profileDetails['AMOUNT'];
        $detailsArr['CITY'] = $profileDetails['CITY'];
        $detailsArr['REQ_ID'] = $profileDetails['ID'];
        $detailsArr['DATE'] = date("d M Y", strtotime($profileDetails['PREF_TIME']));
        return $detailsArr;
    }
    
    public function submitPickupRequest($dataArr) {
        
        $dataArr['BYUSER'] = "Y";
        $dataArr['CONFIRM'] = "";
        $dataArr['ENTRY_DT'] = date("Y-m-d H:i:s");
        $dataArr['COURIER_TYPE'] = "GHARPAY";
        $dataArr['PICKUP_TYPE'] = "CHEQ_REQ_USER";
        $dataArr['REQ_DT'] = date("Y-m-d H:i:s");

        $jprofileObj = new JPROFILE();
        $city = $jprofileObj->getCity(array($dataArr['PROFILEID']));
        $dataArr['CITY'] = $city[$dataArr['PROFILEID']];
        
        $paymentCollectObj = new incentive_PAYMENT_COLLECT();
        $paymentCollectObj->addProfile($dataArr);
    }
}
?>
