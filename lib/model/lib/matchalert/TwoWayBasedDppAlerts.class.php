<?php

/* 
 * this class will log profiled's count for Mutual matches corresponding to DPP
 * and also check of a profile is there in the table
 */

class TwoWayBasedDppAlerts {
    /*
     * this function will make an entry for a profileid
     * @param - profileid
     */
    public static function insertEntry($profileId,$cnt){
        $date = Date("Y-m-d H:m:s");
        $tableObj = new matchalerts_TwoWayVsDppCount();
        $tableObj->insertEntryOfProfile($profileId, $date,$cnt);
    }
    /*
     * this function will delete an entry for a profileid
     * @param - profileid
     */
    public static function deleteEntry($profileId){
        $tableObj = new matchalerts_TwoWayVsDppCount();
        $tableObj->deleteEntryOfProfile($profileId);
    }
    
    /*
     * this function is used to check for an entry and return true or false
     * @param - profileid
     */
    public static function checkForDppProfile($profileId){
        $tableObj = new matchalerts_TwoWayVsDppCount();
        $entryData = $tableObj->getEntryDateForProfile($profileId);
        return $entryData;
    }
}
