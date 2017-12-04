<?php
include_once('DialerLog.class.php');
class DialerApplication {

    public function checkProfileInProcess($profileID,$inFP=true,$inRCB=true,$inRR=true){
        if($inFP){
            $failedPaymentObj = new billing_TRACKING_FAILED_PAYMENT();
            if($failedPaymentObj->searchProfileInCSV($profileID,date("Y-m-d H:i:s",time()-11.5*60*60)))
                return true;
        }

        if($inRCB){
            $rcbObj =new incentive_SALES_CSV_DATA_RCB();
            if($rcbObj->searchProfileInCSV($profileID))
                return true;
        }

        if($inRR){
            $rrPaymentObj =new incentive_LOGGING_CLIENT_INFO();
            if($rrPaymentObj->searchProfileInCSV($profileID,date("Y-m-d H:i:s",time()-23.5*60*60)))
                return true;
        }
        return false;
    }
}
?>
