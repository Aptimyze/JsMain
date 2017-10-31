<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class ExclusivePendingInterestUtility {
    
    private $numberOfShards=3;
    
    public function profileIdShardSeperation($profileIdArray) {
        
        if (empty($profileIdArray)) {
			return NULL;
		}
                
                foreach ($profileIdArray as $profileId) {
                    $dbName=JsDbSharding::getShardNo($profileId);
                        
                    if($dbName[5] == '1') {
                        $result["SHARD1"][] = $profileId;
                    }
                    else if($dbName[5] == '2') {
                        $result["SHARD2"][] = $profileId;
                    }
                    else {
                        $result["SHARD3"][] = $profileId;
                    }
                }
        return $result;
    }
    
    
    /**
     * This is a common method that calls the respective shard to fetch the 
     * data from their corresponding profile ID.
     * 
     * @param Array $profileIdArray 
     */
    public function getProfileInterestDetails($profileIdArray) {
        $shardBasedProfileIds = $this->profileIdShardSeperation($profileIdArray);
        $result = array();
        for($i = 1; $i <= $this->numberOfShards; $i++ ) {
            if(!empty($shardBasedProfileIds["SHARD".$i])) {
                $result1 = $this->getShardBasedProfileInterestDetails("shard".$i."_slave", $shardBasedProfileIds["SHARD".$i]);
                $result = $result + $result1;
            }
        }
        return $result;
    }
    
    /** 
     * This method is used to make a specific connection to a particular
     * shard of newjs_Contacts table.
     * 
     * @param type $dbName - Name of the database. eg - shard1, shard2
     * @param type $type - type of 
     * @param type $profilesId - a 1D array of profile ids
     */
    
    public function getShardBasedProfileInterestDetails($dbName, $profileIdArray, $type = 'I') {
        $contactsObj = new newjs_CONTACTS($dbName);
        $lastWeekMailDate = date('Y-m-d h:m:s',strtotime(" -7 days"));
        $result = $contactsObj->getReceivedDetailsForMatchMailer( implode(",", $profileIdArray), $lastWeekMailDate, $type);
        return $result;
    }
        
    
    public function populateValueParameter($receiver, $interestedProfiles) {
        $result['RECEIVER'] = $receiver;
        
        $count=1;
        
        foreach($interestedProfiles as $key => $value) {
            if($count <= crmParams::$maxProfileIdRetrievalLimit) {
                $result['USER'.$count] = $value;
                ++$count;
                continue;
            }
            break;
        }
        return $result;
    }
    
    /**
     * This method is used to get the agent details for a specific profile ID.
     * This method hits the Exclusive_Servicing Table to fetch the
     * mapping from the client to username of the agent.
     * Further it fetches agent contact details from PSWRDS table.
     * 
     * Currently, it fetches Agent's Name, Phone number, email id. If anything
     * further needs to be added, add the parameter to the $agentDetails array.
     * 
     * @param int $profileId Takes profile id as input to get the agent details
     */
    
    public function getAgentDetails($profileId) {
        $agentDetails = array();
        
        $pendingInterestDataStore = new billing_EXCLUSIVE_SERVICING();
        $agentUsername = $pendingInterestDataStore->getAgentUserName($profileId);
        
        $agentContactDetailsClass = new jsadmin_PSWRDS();
        $agentContactDetails = $agentContactDetailsClass->getAgentContactDetails($agentUsername);
        $agentDetails["AGENT_NAME"] = $agentContactDetails["FIRST_NAME"] ." ". $agentContactDetails["LAST_NAME"];
        $agentDetails["AGENT_PHONE"] = $agentContactDetails["PHONE"];
        $agentDetails["EMAIL"] = $agentContactDetails["EMAIL"];
        
        return $agentDetails;
    }
    
    public function getSubjectAndBody() {
        $content = array();
        
        $tomorrow = date("Y-m-d", time() + 86400);
        $content["subject"] = "JS Exclusive Matchmail (Awaiting Response) ".$tomorrow;
        $content["body"] = "We initiated contact with the following profiles "
                . "and are awaiting their response. Please go through "
                . "these profiles and let us know which of them you would "
                . "like us to pursue on your behalf. We can discuss these "
                . "profiles on our weekly scheduled call or mean while you "
                . "can share the desired Profile IDs with us on mail "
                . "for further follow up.";
        return $content;
    }
    
    /**
     * This method sorts the provided data on the bases of epoch time
     * and returns the result in ascending order,i.e, latest data is at the 
     * top.
     * 
     * @param Associative Array $data Contains the information in a Map
     * @return Associative Array $data Sorting done in the existing array
     */
    public function sortOnTime($data) {
        foreach($data as $receiver => $sender ) {
            
            foreach ($sender as $senderID => $time ) {
                $timestamp = strtotime($time);
                $sender[$senderID] = $timestamp;
            }
            
            asort($sender);
            $data[$receiver] = $sender;
        }
        
        return $data;
    }
    
    public function castToInputObject($data) {
        
        foreach ($data as $receiver => $sender) {
                
                $count = 0;
                foreach ($sender as $senderID => $time) {
                    $temp[$count] = $senderID;
                    $count++;
                }
                $data[$receiver] = $temp;
        }
        return $data;
    }
    
    /**
     * This method separates the profiles with the photos and arranges the
     * profiles with photos at the top.
     * 
     * @param Associative Array $userDetails
     * @return Associative Array
     */
    public function bumpUpPhotoListing($userDetails) {

        $result1;
        $result2;
        
        foreach ($userDetails as $key => $value) {
            
            if($value->HAVEPHOTO == 'Y') {
                $result1[] = $value;
            }
            
            else {
                $result2[] = $value;
            }
        }
        
        if(is_array($result1) && is_array($result2)) {
            return array_merge($result1, $result2);
        }
        else if(is_array($result1) && !is_array($result2)) {
            return $result1;
        } 
        else {
            return $result2;
        }
        
    }
        
    
}

