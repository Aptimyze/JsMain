<?php
/*This class is used to handle the Caste Relaxation logic in matchalerts*/
class CommunityModelMatchAlertsStrategy extends MatchAlertsStrategy
{
	public function __construct($loggedInProfileObj,$limit,$logicLevel)
	{
                $this->profileId=$loggedInProfileObj->getPROFILEID();
                $this->logicLevel = $logicLevel;
                $this->logProfile = 1;
                $this->limit = $limit;
	}

	/*
	This function is used to get all the profiles from analytics server
	@param - profileid
	@return - array of profiles
	*/
	public function getMatches($matchesSetting='')    
        {
            $profilesArray = array();
            if($this->profileId){
                $communityModelTable = new test_Top10_CommunityModelRecommendation();
                $profileIdString = $communityModelTable->fetchProfiles($this->profileId);
                $profilesArray = explode(',', $profileIdString);
                if($profilesArray[0]!='' || count($profilesArray)>1){
                    $profilesArray = array_slice($profilesArray,0,$this->limit);
                    $this->logRecords($this->profileId, $profilesArray, $this->logicLevel, $this->limit,0,$matchesSetting);
                }            
            }
            return $profilesArray;
	}
}