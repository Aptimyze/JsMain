<?php
class AllocatedProfiles
{
	/**
     * @fn __construct
     * @brief Constructor function
     * @param none
     */

    public function __construct()
    {
    }

    /**
     * @fn __getOnlineProfilesForAllocatedAgent
     * @brief wrapper for func getOnlineProfilesForAllocatedAgent of incentive_MAIN_ADMIN.class.php
     * @param $agentsArray
     */

    public function getOnlineProfilesForAllocatedAgent($agentsArray,$lastTimeOnlineDate="")
    {
    	$mainAdminObj = new incentive_MAIN_ADMIN();
    	$profilesArr = $mainAdminObj->getOnlineProfilesForAllocatedAgent($agentsArray,$lastTimeOnlineDate);

	$jsCommonObj =new JsCommon();
	if($lastTimeOnlineDate){
		$score1 =strtotime($lastTimeOnlineDate);
		$score2=time();
	}
	$onlineProfiles =$jsCommonObj->getOnlineUsetList($score1,$score2);	
	foreach($profilesArr as $key=>$val){
		$profileid =$key;
		if(!in_array($profileid, $onlineProfiles))
			unset($profilesArr[$profileid]);
	}
    	unset($mainAdminObj);
    	return $profilesArr;
    }

    /**
     * @fn __getAgentsForProfiles
     * @brief wrapper for func getAgentsForProfiles of incentive_MAIN_ADMIN.class.php
     * @param $agentsArray
     */

    public function getAgentsForProfile($profileArr,$db='')
    {
        $mainAdminObj = new incentive_MAIN_ADMIN($db);
        $profilesArr = $mainAdminObj->getAgentsForProfile($profileArr);
        unset($mainAdminObj);
        return $profilesArr;
    }
			
}
?>
