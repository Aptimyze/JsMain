<?php
/**
 * @class negativeTreatment
 * @brief  this class negative treatment for spam profiles registered on Jeevansathi
 */

class negativeTreatment
{

    /**
     * @fn __construct
     * @brief Constructor function
     */
    public function __construct()
    {
        $this->profileArr            = array();
        $this->profileNegArrForPhone = array();
        $this->phoneNegArr           = array();
        $this->emailNegArr           = array();
        $this->profileNegArrForEmail = array();
        $this->fields                = 'PROFILEID';

        $this->phoneLogObj      = new PHONE_VERIFIED_LOG('newjs_local111');
        $this->oldEmailObj      = new newjs_OLDEMAIL('newjs_local111');
        $this->jprofileEmailObj = new JPROFILE('newjs_local111');
        $this->prmObj           = new jsadmin_PremiumUsers('newjs_slave');
        $this->cnt              = 1;
    }

    public function getProfileId($username)
    {
        $jProfileObj     = $this->jprofileEmailObj;
        $jProfileDetails = $jProfileObj->get($username, "USERNAME", $this->fields);
        $profileid       = $jProfileDetails['PROFILEID'];
        return $profileid;
    }

    public function addProfileToNegative($profileidArr, $type = '')
    {
        // print "Original Profile Arr ---  ";
        // print_r($profileidArr);
        // print "End Original Profile Arr ---  ";
        // print "Modified Profile Arr ---  ";
        $profileidArr = $this->returnFilteredProfilesAfterDummyExclusion($profileidArr);
        // print_r($profileidArr);
        // print "End Modified Profile Arr ---  ";
        $this->cnt++;
        //echo "Main Profile List: ";
        if ($type && is_array($profileidArr)) {
            foreach ($profileidArr as $key => $profileid) {
                $this->profileNegArrForPhone[] = $profileid;
                $this->profileNegArrForEmail[] = $profileid;
            }
        }
        //// print_r($profileidArr);

        // Phone Number handling
        if (count($profileidArr) > 0) {
            unset($phoneArr);
            unset($phoneArrNew);
            $phoneArr = $this->phoneLogObj->getVerifiedPhoneNumbers($profileidArr);
            if (is_array($phoneArr)) {
                $phoneArrNew = array_diff($phoneArr, $this->phoneNegArr);
            }

            if (is_array($phoneArrNew)) {
                $this->addPhoneToNegative($phoneArrNew);
            }
        }

        // Email handling
        if (count($profileidArr) > 0) {
            unset($emailArr);
            unset($emailArrNew);
            $emailArr = $this->oldEmailObj->getEmailList($profileidArr);
            // jprofile condition for email
            $jemailArr1 = $this->jprofileEmailObj->getProfileSelectedDetails($profileidArr, 'PROFILEID,EMAIL');
            if (is_array($jemailArr1)) {
                foreach ($jemailArr1 as $key => $val) {
                    $jemailArr[] = $val['EMAIL'];
                }

            }
            if (is_array($emailArr) && is_array($jemailArr)) {
                $emailArr = array_merge($emailArr, $jemailArr);
            } elseif (is_array($jemailArr)) {
                $emailArr = $jemailArr;
            }

            // jprofile condition for email end

            if (is_array($emailArr)) {
                $emailArr = array_unique($emailArr);
            }
            $emailArrNew = array_diff($emailArr, $this->emailNegArr);
            if (is_array($emailArrNew)) {
                $this->addEmailToNegative($emailArrNew);
            }
        }
    }

    public function addPhoneToNegative($phoneNumberArr)
    {
        // print "Original Phone Arr ---  ";
        // print_r($phoneNumberArr);
        // print "End Original Phone Arr ---  ";

        if (!empty($phoneNumberArr) && is_array($phoneNumberArr)) {
            // get compherensive list of phone numbers for profiles
            $fullDetPhoneNumberArr = $this->phoneLogObj->getVerifiedProfilesAndPhone($phoneNumberArr);
            $profileArr            = array();
            $profileFilteredArr    = array();
            $profileDummyArr       = array();
            $tempPhoneArr          = array();
            $phoneArr              = array();
            foreach ($fullDetPhoneNumberArr as $key => $val) {
                // Make list of all profiles retrieved above
                $profileArr[] = $val['PROFILEID'];
                // Make mapping of profiles corresponding to numbers
                $tempPhoneArr[$val['PHONE_NUM']][] = $val['PROFILEID'];
            }
            unset($key, $val);
            $profileArr = array_filter(array_unique($profileArr));
            // Filter profiles from the above list to exclude dummy profiles
            $profileFilteredArr = $this->returnFilteredProfilesAfterDummyExclusion($profileArr);
            $profileDummyArr    = array_diff($profileArr, $profileFilteredArr);
            $tempPhoneArr2      = $tempPhoneArr;
            // Now remove all the phone numbers corresponding to the dummy marked profiles
            foreach ($profileDummyArr as $key => $val) {
                foreach ($tempPhoneArr as $phone => $proArr) {
                    if (in_array($val, $proArr)) {
                        unset($tempPhoneArr2[$phone]);
                    }
                }
            }
            unset($key, $val);
            // Now we have only phone numbers corresponsing to non-dummy Profiles
            if (is_array($tempPhoneArr2)) {
                foreach ($tempPhoneArr2 as $key => $pArr) {
                    $phoneArr[] = $key;
                }
            }
            // Finally replace the incoming array with this new one !
            $phoneNumberArr = $phoneArr;
            // print "Modified Phone Arr ---  ";
            // print_r($phoneNumberArr);
            // print "End Modified Phone Arr ---  ";
            unset($key, $val, $k, $v, $fullDetPhoneNumberArr, $profileArr, $profileDummyArr, $profileFilteredArr, $tempPhoneArr, $tempPhoneArr2, $phoneArr);
        }

        // Add phone number to negative
        /*echo "Phone Number List: ";
        // print_r($phoneNumberArr);*/
        if (is_array($phoneNumberArr)) {
            foreach ($phoneNumberArr as $key => $phoneValue) {
                $this->phoneNegArr[] = $phoneValue;
            }
        }

        // find profiles for verified phone number
        if (count($phoneNumberArr) > 0) {
            unset($profileArr);
            unset($profileArrNew);
            $profileArr = $this->phoneLogObj->getVerifiedProfiles($phoneNumberArr);
            if (is_array($profileArr)) {
                $profileArr    = $this->returnFilteredProfilesAfterDummyExclusion($profileArr);
                $profileArrNew = array_diff($profileArr, $this->profileNegArrForPhone);
            }

            /*echo "Profiles for Phone: ";
            // print_r($profileArrNew);*/
            if (is_array($profileArrNew)) {
                foreach ($profileArrNew as $key => $pid) {
                    $this->profileNegArrForPhone[] = $pid;
                }
                $this->addProfileToNegative($profileArrNew);
            }
        }
    }
    public function addEmailToNegative($emailArr)
    {
        // print "Original Email Arr ---  ";
        // print_r($emailArr);
        // print "End Original Email Arr ---  ";
        if (!empty($emailArr) && is_array($emailArr)) {
            // get compherensive list of email numbers for profiles
            $fullDetEmailArr    = $this->oldEmailObj->getEmailProfilesAndEmail($emailArr);
            $profileArr         = array();
            $profileFilteredArr = array();
            $tempEmailArr       = array();
            $emailArr2          = array();
            foreach ($fullDetEmailArr as $key => $val) {
                // Make list of all profiles retrieved above
                $profileArr[] = $val['PROFILEID'];
                // Make mapping of profiles corresponding to emails
                $tempEmailArr[$val['OLD_EMAIL']][] = $val['PROFILEID'];
                $eArr[]                            = $val['OLD_EMAIL'];
            }
            unset($key, $val);
            // MERGING
            if (is_array($eArr)) {
                $emailArr = array_merge(array_unique($eArr), $emailArr);
            }
            $valueArray['EMAIL'] = "'" . implode("','", $emailArr) . "'";
            $jprofileArr1        = $this->jprofileEmailObj->getArray($valueArray, '', '', 'PROFILEID,EMAIL');
            foreach ($jprofileArr1 as $key => $val) {
                $tempEmailArr[$val['EMAIL']][] = $val['PROFILEID'];
                $profileArr[]                  = $val['PROFILEID'];
            }
            $profileArr = array_filter(array_unique($profileArr));
            // Filter profiles from the above list to exclude dummy profiles
            $profileFilteredArr = $this->returnFilteredProfilesAfterDummyExclusion($profileArr);
            $profileDummyArr    = array_diff($profileArr, $profileFilteredArr);
            $tempEmailArr2      = $tempEmailArr;
            // Now remove all the emails corresponding to the dummy profiles
            foreach ($profileDummyArr as $key => $val) {
                foreach ($tempEmailArr as $email => $proArr) {
                    if (in_array($val, $proArr)) {
                        unset($tempEmailArr2[$email]);
                    }
                }
            }
            unset($key, $val, $email, $proArr);
            // Now we have only phone numbers corresponsing to non-dummy Profiles
            if (is_array($tempEmailArr2)) {
                foreach ($tempEmailArr2 as $key => $pArr) {
                    $emailArr2[] = $key;
                }
            }
            // Finally replace the incoming array with this new one !
            $emailArr = $emailArr2;
            // print "Modified Email Arr ---  ";
            // print_r($emailArr);
            // print "End Modified Email Arr ---  ";
            unset($key, $val, $k, $v, $fullDetEmailArr, $profileArr, $profileFilteredArr, $profileDummyArr, $tempEmailArr, $tempEmailArr2, $emailArr2);
        }

        // Add email to negative list
        /*echo "Email list: ";
        // print_r($emailArr);*/
        foreach ($emailArr as $key => $emailVal) {
            $this->emailNegArr[] = $emailVal;
        }

        // find profiles for email
        if (count($emailArr) > 0) {
            unset($profileArr);
            unset($profileArrNew);
            $profileArr = $this->oldEmailObj->getEmailProfiles($emailArr);

            // jprofile condition for email
            $valueArray['EMAIL'] = "'" . implode("','", $emailArr) . "'";
            $jprofileArr1        = $this->jprofileEmailObj->getArray($valueArray, '', '', 'PROFILEID');
            if (is_array($jprofileArr1)) {
                foreach ($jprofileArr1 as $key => $val) {
                    $jprofileArr[] = $val['PROFILEID'];
                }

            }
            if (is_array($profileArr) && is_array($jprofileArr)) {
                $profileArr = array_merge($profileArr, $jprofileArr);
            } elseif (is_array($jprofileArr)) {
                $profileArr = $jprofileArr;
            }

            // jprofile condition for email end

            if (is_array($profileArr)) {
                $profileArr    = array_unique($profileArr);
                $profileArr    = $this->returnFilteredProfilesAfterDummyExclusion($profileArr);
                $profileArrNew = array_diff($profileArr, $this->profileNegArrForEmail);
            }
            /*echo "Profiles for Email:";
            // print_r($profileArrNew);*/
            if (is_array($profileArrNew)) {
                foreach ($profileArrNew as $key => $pid) {
                    $this->profileNegArrForEmail[] = $pid;
                }
                $this->addProfileToNegative($profileArrNew);
            }
        }
    }
    public function addToNegative($type, $value, $comments)
    {
        $submitObj      = new incentive_NEGATIVE_SUBMISSION_LIST();
        $this->submitID = $submitObj->insert($type, $value, $comments);

        switch ($type) {
            case 'PHONE_NUM':
                $this->addPhoneToNegative(array($value));
                break;
            case 'EMAIL':
                $this->addEmailToNegative(array($value));
                break;
            case 'PROFILEID':
                $this->addProfileToNegative(array($value), $type);
                break;
            default:
                break;
        }
        $this->profileNegArrForPhone = array_unique($this->profileNegArrForPhone);
        $this->profileNegArrForEmail = array_unique($this->profileNegArrForEmail);
        $this->profileArr            = array_merge($this->profileNegArrForPhone, $this->profileNegArrForEmail);
        $this->profileArr            = array_filter(array_unique($this->profileArr));
        $this->phoneNegArr           = array_filter(array_unique($this->phoneNegArr));
        $this->emailNegArr           = array_filter(array_unique($this->emailNegArr));
        $insertArr                   = array("PROFILEID" => $this->profileArr, "PHONE_NUM" => $this->phoneNegArr, "EMAIL" => $this->emailNegArr);
        $this->insertIntoNegative($insertArr);

        // Delete the profile
        if (is_array($this->profileArr)) {
            $jProfileObj      = $this->jprofileEmailObj;
            $DeleteProfileObj = new DeleteProfile();
            $delete_reason    = 'Other reasons';
            $specify_reason   = 'Negative List';
            foreach ($this->profileArr as $key => $profileid) {
                $jProfile  = $jProfileObj->get($profileid, "PROFILEID", 'USERNAME,PROFILEID,ACTIVATED');
                $profileid = $jProfile['PROFILEID'];
                $activated = $jProfile['ACTIVATED'];
                $username  = $jProfile['USERNAME'];
                if ($profileid && $activated != 'D') {
                    // $DeleteProfileObj->delete_profile($profileid, $delete_reason, $specify_reason);
                    $this->deleteProfilesForNegativeTreatment($profileid, $delete_reason, $specify_reason, $username);
                    $DeleteProfileObj->callDeleteCronBasedOnId($profileid);
                }
            }
        }

    }

    // Add negative values in incentive_NEGATIVE_LIST
    public function insertIntoNegative($insertArr)
    {
        $negativeListObj = new incentive_NEGATIVE_LIST();
        if (is_array($insertArr)) {
            foreach ($insertArr as $type => $value) {
                if (is_array($value)) {
                    foreach ($value as $key1 => $val1) {
                        $negativeListObj->insert($type, $val1, $this->submitID);
                    }}
            }
        }
    }

    public function returnFilteredProfilesAfterDummyExclusion($profileArr)
    {
        // Exclude Dummy Marked profiles from the list of incoming profiles
        // Bulk Function, makes single call to jsadmin_PremiumUsers Table
        // Gets array of dummy marked profiles out of original profiles array
        $profileDummyArr = $this->prmObj->filterDummyProfiles($profileArr);
        foreach ($profileArr as $key => $val) {
            if (in_array($val, $profileDummyArr)) {
                // Unsets profiles which are marked as dummy from original array
                unset($profileArr[$key]);
            }
        }
        // Sanitization of final array after removal of dummy profiles
        $profileArrFinal = array_values(array_filter(array_unique($profileArr)));
        // Clear Memory
        unset($profileArr, $profileDummyArr);
        return $profileArrFinal;
    }

    public function deleteProfilesForNegativeTreatment($profileid, $delete_reason, $specify_reason, $username)
    {
        //Start:JSC-2551:Log before pushing to RabbitMQ for deletion 
        $profileDeleteObj = new PROFILE_DELETE_LOGS();
        $startTime = date('Y-m-d H:i:s');
        $arrDeleteLogs = array(
            'PROFILEID' => $profileid,
            'DELETE_REASON' => $delete_reason,
            'SPECIFY_REASON' => $specify_reason,
            'USERNAME'  => $username,
            'CHANNEL' => CommonFunction::getChannel(),
            'START_TIME' => $startTime,
        );
        $profileDeleteObj->insertRecord($arrDeleteLogs);
        //End:JSC-2551:Log before pushing to RabbitMQ for deletion 
        $jprofileObj         = new JPROFILE;
        $markDelObj          = new JSADMIN_MARK_DELETE;
        $ProfileDelReasonObj = new NEWJS_PROFILE_DEL_REASON;
        $AP_ProfileInfo      = new ASSISTED_PRODUCT_AP_PROFILE_INFO;
        $AP_MissedServiceLog = new ASSISTED_PRODUCT_AP_MISSED_SERVICE_LOG;
        $AP_CallHistory      = new ASSISTED_PRODUCT_AP_CALL_HISTORY;
        
        //$ProfileDelReasonObj->Replace($username, $delete_reason, $specify_reason, $profileid);

        $jprofileObj->updateDeleteData($profileid);
        
        $markDelObj->Update($profileid);
        $AP_ProfileInfo->Delete($profileid);
        $AP_MissedServiceLog->Update($profileid);
        $AP_CallHistory->UpdateDeleteProfile($profileid);
        
        //Start: JSC-2551: Push to RabbitMQ
        $producerObj=new Producer();
	if($producerObj->getRabbitMQServerConnected()){
            $sendMailData = array('process' =>'DELETE_RETRIEVE','data'=>array('type' => 'DELETING','body'=>array('profileId'=>$profileid)), 'redeliveryCount'=>0 );
            $producerObj->sendMessage($sendMailData);
            $sendMailData = array('process' =>'USER_DELETE','data' => ($profileid), 'redeliveryCount'=>0 );
            $producerObj->sendMessage($sendMailData);
        }else{
            $path = $_SERVER['DOCUMENT_ROOT']."/profile/deleteprofile_bg.php $profileid > /dev/null &";
            $cmd = JsConstants::$php5path." -q ".$path;
            passthru($cmd);
        }
        //End:JSC-2551:Push to RabbitMQ
        
        //Start:JSC-2551: Mark Completion in logs
        $arrDeleteLogs = array(
            'END_TIME' => date('Y-m-d H:i:s'),
            'COMPLETE_STATUS' => 'Y',
            'INTERFACE' =>'B',
        );
        $profileDeleteObj->updateRecord($profileid, $startTime, $arrDeleteLogs);
        //End:JSC-2551: Mark Completion in logs
    }
    
    public function checkEmail($email)
    {
        if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/i", $email)) {
            return 1;
        }

        return;
    }
    public function checkPhoneNumber($phoneNumber)
    {
        $phoneNumber = substr(preg_replace("/[a-zA-Z!(\' ')@#$+^&*-]/", "", $phoneNumber), -15);
        $phoneNumber = ltrim($phoneNumber, 0);
        $totLength   = strlen($phoneNumber);
        if ($totLength < 6 || $totLength > 14) {
            return false;
        }

        if (!is_numeric($phoneNumber)) {
            return false;
        }

        return $phoneNumber;
    }
    public function fetchProfileDetailsFromNegative($negType, $negativeVal)
    {
        $negativeListObj        =new incentive_NEGATIVE_LIST('newjs_slave');

        $dataArr =$negativeListObj->getProfileData($negType,$negativeVal);
	if(is_array($dataArr)){
		$id =$dataArr['SUBMISSION_ID'];
		$submissionListObj =new incentive_NEGATIVE_SUBMISSION_LIST('newjs_slave');
		$subDataArr =$submissionListObj->getData($id);
		$dataArr['COMMENTS'] =$subDataArr['COMMENTS'];
	}
        if(is_array($dataArr))
                return $dataArr;
        return;

    }
    public function removeProfileFromNegative($negType, $negativeVal)
    {

        $negativeListObj        =new incentive_NEGATIVE_LIST();
        $negativeProfileListObj =new incentive_NEGATIVE_PROFILE_LIST();

	$status1 =$negativeListObj->removeProfile($negType,$negativeVal);
	if($negType=='PHONE_NUM')
		$status2 =$negativeProfileListObj->removeProfileUsingPhone($negativeVal);
	else
		$status2 =$negativeProfileListObj->removeProfile($negType,$negativeVal);

	if($status2>=1 && $negType=='PROFILEID'){
		$negativeTreatmentObj   =new incentive_NEGATIVE_TREATMENT_LIST();
		$status3 =$negativeTreatmentObj->removeProfile($negType,$negativeVal);
	}
	if($status1>=1 || $status2>=1)
		return true;
	return;
    }	

}
