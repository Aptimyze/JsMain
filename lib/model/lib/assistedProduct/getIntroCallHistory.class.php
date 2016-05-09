<?php

class getIntroCallHistory
{
	public function __construct($dbname='')
	{
		$this->dbname = $dbname;
	}
        public function offCallHistory($ViewerProfile,$ViewedProfile,$membershipObj,$ProfilesObj){
            $offlineCallCountArr = $membershipObj->getAllCount($ViewerProfile);
            $introCall = $this->getHistoryOfIntroCalls($ViewerProfile,$offlineCallCountArr["EXPIRED"],'','',$ProfilesObj);
            $introCallDetail["PURCHASED"] = $offlineCallCountArr["TOTAL"];
            $introCallDetail["TO_BE_CALLED"] = $introCall["TOTAL"]+1;
            $introCallDetail["CALLED"] = $introCall["calledCount"];
            $introCallDetail["TO_BE_ADDED"] = $offlineCallCountArr["TOTAL"]-($introCall["TOTAL"]+1);
            $introCallDetail["TO_BE_ADDED_CNT"] = ($offlineCallCountArr["TOTAL"]+$offlineCallCountArr["EXPIRED"]) - $introCall["TOTAL_COUNT"];
            if(in_array($ViewedProfile,$introCall['profile']))
            {
                    //If call is in progress and not completed use this code
                    //if($introCall[CS][$ViewedProfile]=="N" && $this->TeleCommentsInProgress($ViewerProfile, $ViewedProfile))
                        //$returnArr["OFFLINE_CALL_PROGRESS"] = 1;
                    $returnArr["OFFLINE_ASSISTANT_REM"] = 1;
            }
            else
            {
                    if($introCallDetail["TO_BE_ADDED_CNT"]>0)
                    {
                            $returnArr["OFFLINE_ASSISTANT_ADD"] = 1;
                    }
            }
            
            $returnArr["introCallDetail"] = $introCallDetail;
            return $returnArr;
        }
        public function getHistoryOfIntroCalls($profileId,$condition='', $skipArray='',$call_status='',$ProfilesObj)
	{
	/*
        if($condition || $skipArray)
		    $introCall = $ProfilesObj->getCallHistoryConditionBased($condition,$skipArray,$call_status);
        else
	*/
        {
            $output = $ProfilesObj->getCallHistory($profileId);
                $calledY = 0;
                $calledC = 0;
                $notCalled = 0;
                $introCall["profile"] = array();
                $i=0;
		if(is_array($output))
                foreach($output as $key)
                {
                                $introCall["profile"][] = $key["MATCH_ID"];
                                if($key["CALL_STATUS"] == "Y")
                                        $calledY++;
                                if($key["CALL_STATUS"] == "C")
                                        $calledC++;
                                if($key["CALL_STATUS"] == "N")
                                        $notCalled++;
                                $introCall[CS][$key["MATCH_ID"]]=$key["CALL_STATUS"];
                }
                $introCall["calledCount"] = $calledY;
                $introCall["notCalledCount"] = $notCalled;
                $introCall["TOTAL"] = $calledY+$notCalled;
                $introCall["TOTAL_COUNT"] = $calledY+$notCalled+$calledC;
        }
                return $introCall;
	}

    public function getHistoryOfIntroCallsPending($profileId,$condition='',$skipArray='')
    {
        $ProfilesObj = new ASSISTED_PRODUCT_AP_CALL_HISTORY($this->dbname);
        $introCall = $ProfilesObj->getHistoryOfIntroCallsPending($condition,$skipArray);
        return $introCall;
    }

    public function getHistoryOfIntroCallsComplete($profileId,$condition='', $skipArray='')
    {
        $ProfilesObj = new ASSISTED_PRODUCT_AP_CALL_HISTORY($this->dbname);
        $introCall = $ProfilesObj->getHistoryOfIntroCallsComplete($condition,$skipArray);
        return $introCall;
    }
   //wrapper function for getIntroCallsCount of ASSISTED_PRODUCT_AP_CALL_HISTORY
    public function getIntroCallsCount($profileid,$call_status="",$skipArray="")
    {
        $ProfilesObj = new ASSISTED_PRODUCT_AP_CALL_HISTORY($this->dbname);
        $output = $ProfilesObj->getIntroCallsCount($profileid,$call_status,$skipArray);
        return $output["COUNT"];
    }

    public function getIntroCallsCompleteCount($profileId,$skipArray='')
    {
        $ProfilesObj = new ASSISTED_PRODUCT_AP_CALL_HISTORY($this->dbname);
        $introCall = $ProfilesObj->getIntroCallsCompleteCount($profileId,$skipArray);
        return $introCall["COUNT"];
    }

     //wrapper function for getIntroCallsCount of ASSISTED_PRODUCT_AP_CALL_HISTORY
    public function getIntroCallsPendingCount($profileid,$skipArray="")
    {
        $ProfilesObj = new ASSISTED_PRODUCT_AP_CALL_HISTORY($this->dbname);
        $output = $ProfilesObj->getIntroCallsPendingCount($profileid,$skipArray);
        return $output["COUNT"];
    }
        public function TeleCommentsInProgress($first,$second){
            if($first && $second)
            {
                $ProfilesObj = new ASSISTED_PRODUCT_AP_MATCH_COMMENTS($this->dbname);
                $output = $ProfilesObj->getProfilesMatching($first,$second);
                return $output;
            }    
        }

    /*function to check whether User has intro calls option
    * @param : $profileid
    * @return : true/false
    */
    function isProfileApMember($subscription)
    {
        $offline = false;
        if (strstr($subscription, "I"))
            $offline = true;
        return $offline;
    }

    //wrapper function for removeFromprofileICList of ASSISTED_PRODUCT_AP_CALL_HISTORY.class.php
    function removeFromprofileICList($param)
    {
        $introObj = new ASSISTED_PRODUCT_AP_CALL_HISTORY();
        $introObj->removeFromprofileICList($param);
    }

}
