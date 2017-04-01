<?php

/* 
 * this class will log those profiled which have zero Dpp- trends intersection matches
 * and also check of a profile is there in the table
 */

class logAndFetchProfilesForZeroMatches {
    /*
     * this function will make an entry for a profileid
     * @param - profileid
     */
    public static function insertEntry($profileId){
        $date = Date("Y-m-d H:m:s");
        $tableObj = new matchalerts_ZERO_TvDPP_MATCHES();
        $tableObj->insertEntryOfProfile($profileId, $date);
    }
    
    /*
     * this function is used to check for an entry and return true or false
     * it will also remove an entry if time period has passed
     * @param - profileid
     */
    public static function checkIfProfileIsEligible($profileId){
        $noOfDaysSeconds = 10*24*60*60;
        $todaysDate = time();
        $tableObj = new matchalerts_ZERO_TvDPP_MATCHES();
        $tableMasterObj = new matchalerts_ZERO_TvDPP_MATCHES();
        $entryDate = $tableObj->getEntryDateForProfile($profileId);
        $diff = $todaysDate-strtotime($entryDate);
        if($diff > $noOfDaysSeconds){
            $tableObj->deleteEntryOfProfile($profileId);
            return true;
        }
        else
            return false;
    }
}
