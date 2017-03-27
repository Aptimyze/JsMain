<?php
/**
* @brief : This file will be handle contact view search requirements.
* @author Bhavana Kadwal
* @created 2016-05-13
*/
class ContactViewAttempts extends PartnerProfile
{
	CONST femaleProfile  = 'F';
	CONST maleProfile = 'M';
	CONST showAllResults = 'All';
	/**
	* Constructor.
	*/
        public function __construct($loggedInProfileObj)
        {
		$this->loggedInProfileObj = $loggedInProfileObj;
               	$this->pid =  $this->loggedInProfileObj->getPROFILEID();
                parent::__construct($loggedInProfileObj); 
	}

	/*
	* This function will set the criteria for search.
	*/
	public function getSearchCriteria()
	{
                $VCDTrackingObj = new VCDTracking;
		$arr = $VCDTrackingObj->getContactAttemptProfiles($this->pid);
                
                //set Dpp Criteria
                if(!empty($arr)){
                        $this->getDppCriteria();
                         if($this->loggedInProfileObj->getGENDER()== self::femaleProfile)
                                $this->setGENDER(self::maleProfile);
                        else
                                $this->setGENDER(self::femaleProfile); 
                }
                
                //Set search Type
                $channel =  SearchChannelFactory::getChannel();
		$this->stype =  $channel::getSearchTypeContactViewAttempt(); 
		$this->setSEARCH_TYPE($this->stype);
                
               
                
                // get profiles to show from contact attempts
		
                
                $this->setAttemptConditionArr($arr);
		if($arr){
			$str = implode(" ",array_keys($arr));
                }else{
                        $str = '0 0';
                }
		$this->setProfilesToShow($str);
                
		$this->setSORT_LOGIC(SearchSortTypesEnums::viewAttemptFlag);
	}
}
?>
