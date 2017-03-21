<?php

class reconsileBillingsTask extends sfBaseTask
{
    protected function configure()
    {
        $this->addOptions(array(new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name', 'jeevansathi')
        ));

        $this->namespace = 'billing';
        $this->name = 'reconsileBillings';
        $this->briefDescription = '';
        $this->detailedDescription = <<<EOF
        The [reconsileBillings|INFO] task does things.
        Call it with:
        [php symfony billing:reconsileBillings|INFO]
EOF;
    }

    /**
     * @param array $arguments
     * @param array $options
     */
    protected function execute($arguments = array(), $options = array())
    {
        sfContext::createInstance($this->configuration);
        
        include_once (JsConstants::$docRoot . "/jsadmin/connect.inc");
        include_once (JsConstants::$docRoot . "/classes/Membership.class.php");

        $start_time = date("Y-m-d H:i:s", (time() - 2*3600));
        
        $billPurObj = new BILLING_PURCHASES('newjs_slave');
        $billPayDetObj = new BILLING_PAYMENT_DETAIL('newjs_slave');
        $billingOrderObjSlave = new BILLING_ORDERS('newjs_slave');
        $billingOrderObj = new BILLING_ORDERS();
        $billOrdDev = new billing_ORDERS_DEVICE('newjs_slave');
        $jprofileObjSlave = new JPROFILE('newjs_slave');
        $billServStatObj = new BILLING_SERVICE_STATUS('newjs_slave');
        $jprofileObj = new JPROFILE();   

        /**
         * Fetch Profile Data for cron
         * @var [type]
         */
        $newPurBillings = $billPurObj->getProfilesForReconsiliationAfter($start_time,"");
        print_r($newPurBillings);
        if (!empty($newPurBillings)) {
            $newPayDetBillings = $billPayDetObj->getAllDetailsForBillidArr(array_keys($newPurBillings));
            foreach ($newPurBillings as $key => $val) {
                if (!empty($val['ORDERID']) && is_numeric($val['ORDERID']) && $val['ORDERID'] != 0) {
                    /**
                     * Consider only order which have a valid value i.e. front-end transactions
                     * Fetching Details based on order generated on the front-end and 
                     * assigning necessary variables in the Membership Object
                     */
                    $membershipObj = new Membership();
                    $orderDet[$val['BILLID']] = $billingOrderObjSlave->getOrderDetailsForId($val['ORDERID']);
                    $orderid = $orderDet[$val['BILLID']]['ORDERID']."-".$orderDet[$val['BILLID']]['ID'];
                    $currentSubscription = $jprofileObjSlave->getSubscriptions($orderDet[$val['BILLID']]['PROFILEID'], 'SUBSCRIPTION');

                    /**
                     * Checking if details exist in PAYMENT_DETAILS Table for the current transaction
                     */
                    if (!in_array($val['BILLID'], array_keys($newPayDetBillings))){
                        /**
                         * If details do not exist then we will run the makePaid function after bill generation to complete tables
                         * Also, pre-setting params required to populate rest of the tables
                         */
                        echo "\n"."PAYMENT_DETAILS entry absent-".$val['BILLID'];
                        $membershipObj->billid = $val['BILLID'];
                        $membershipObj->device = $billOrdDev->getOrderDeviceFromBillid($val['BILLID']);
                        $billingOrderObj->updateOrderForReconsiliation($orderDet[$val['BILLID']]['ID']);
                        if (empty($membershipObj->device) || $membershipObj->device == '') {
                            $membershipObj->device = 'desktop';
                        }
                        $membershipObj->startServiceOrder($orderid, true);
                    } else if (empty($currentSubscription) || $currentSubscription == ''){
                        /**
                         * Fetching latest servefor from billing.SERVICE_STATUS and updating in JPROFILE
                         */
                        echo "\n"."JPROFILE SUBSCRIPTION entry absent-".$val['BILLID'];
                        $serveFor =  $billServStatObj->getActiveSuscriptionString($orderDet[$val['BILLID']]['PROFILEID']);
                        if(!empty($serveFor) && $serveFor != '') {
                            $jprofileObj->updateSubscriptionStatus($serveFor, $orderDet[$val['BILLID']]['PROFILEID']);
                        }
                        $memCacheObject = JsMemcache::getInstance(); 
                        $checkForMemUpgrade = $memCacheObject->get($orderDet[$val['BILLID']]['PROFILEID'].'_MEM_UPGRADE_'.$orderid);
                        unset($memCacheObject);

                        var_dump("memUpgarde- ".$checkForMemUpgrade);
                        if($checkForMemUpgrade == null || $checkForMemUpgrade == false || $checkForMemUpgrade != "NA"){
                            echo "\n"."upgrade entry not updated-".$key;
                            $memHandlerObj = new MembershipHandler(false);
                            $memHandlerObj->updateMemUpgradeStatus($orderid,$orderDet[$val['BILLID']]['PROFILEID'],array("UPGRADE_STATUS"=>"DONE","BILLID"=>$val['BILLID']));
                            unset($memHandlerObj);
                        }

                    } else{
                        //check for upgrade order entry update done or not
                        $memCacheObject = JsMemcache::getInstance(); 
                        $checkForMemUpgrade = $memCacheObject->get($orderDet[$val['BILLID']]['PROFILEID'].'_MEM_UPGRADE_'.$orderid);
                        unset($memCacheObject);
                        if($checkForMemUpgrade == null || $checkForMemUpgrade == false || in_array($checkForMemUpgrade, VariableParams::$memUpgradeConfig["allowedUpgradeMembershipAllowed"])){
                            $upgradeOrderObj = new billing_UPGRADE_ORDERS();
                            $isUpgradeCaseEntry = $upgradeOrderObj->isUpgradeEntryExists($orderid,$orderDet[$val['BILLID']]['PROFILEID']);
                            if(is_array($isUpgradeCaseEntry) && $isUpgradeCaseEntry["UPGRADE_STATUS"] != "DONE"){
                                $memHandlerObj = new MembershipHandler(false);
                                $memHandlerObj->updateMemUpgradeStatus($orderid,$orderDet[$val['BILLID']]['PROFILEID'],array("UPGRADE_STATUS"=>"DONE","BILLID"=>$val['BILLID']));
                                unset($memHandlerObj);
                            }
                            unset($upgradeOrderObj);
                        }
                    }
                } else {
                    /**
                     * Transactions which were done through back-end are not handled
                     */
                }
                /**
                 * Unset all the variables used in the loop before exiting
                 */
                unset($membershipObj);
                unset($orderDet);
                unset($orderid);
                unset($serveFor);
            }
        }
    }
}
