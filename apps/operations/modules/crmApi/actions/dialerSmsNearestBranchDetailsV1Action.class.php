<?php

/*
 * Get sms of nearest branch details
 * @input: profileid
 * @output: success/failure
 */

/**
 * Description of dialerSmsNearestBranchDetailsV1Action
 *
 * @author nitish
 */
class dialerSmsNearestBranchDetailsV1Action extends sfActions {
    
    function execute($request){
        $profileid = $request->getParameter('profileid');
        if($profileid){
            $jprofileObj = new JPROFILE('newjs_slave');
            $profileDetails = $jprofileObj->get($profileid, "PROFILEID","USERNAME,CITY_RES");
            $username = $profileDetails['USERNAME'];
            if($username){
                $newjsConObj = new NEWJS_CONTACT_US('newjs_slave');
                $arrResult = array();
                $newjsConObj->fetch_All_Contact($arrResult);
                foreach($arrResult as $key=>$val) {
                    if (empty($branchArr[$val['STATE_VAL']][$val['CITY_ID']])) {
                        $branchArr[$val['STATE_VAL']][$val['CITY_ID']] = $val['ADDRESS'];
                    }
                }
                $cityRes       = $profileDetails["CITY_RES"];
                $state = ucwords(preg_replace("/[^A-Z]+/", "", $cityRes));
                if (in_array($state, array_keys($branchArr))) {
                    if (in_array($cityRes, array_keys($branchArr[$state]))) {
                        $branchAddress = $branchArr[$state][$cityRes]; 
                    } else {
                        $branchAddress = array_values($branchArr[$state])[0]; 
                    }
                } 
                else {
                    $branchAddress = $branchAddress = array_values($branchArr['DE'])[0]; 
                }
                $branchAddress = wordwrap($branchAddress, 120);
                $tokenArr      = array("BRANCH_ADDRESS" => $branchAddress);
                CommonUtility::sendPlusTrackInstantSMS('CRM_SMS_BRANCH', $profileid, $tokenArr);
                $result["response"] = "Success";
            }
            else{
                $result["response"] = "Error";
            }
        }
        else{
            $result["response"] = "Prameter Missing";
        }
        echo json_encode($result);
        die();
        //$respObj->setResponseBody($output);
        //$respObj->generateResponse();
        //die();
    }
}
