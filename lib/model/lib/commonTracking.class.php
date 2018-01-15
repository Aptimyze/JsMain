<?php

class CommonLoginTracking {
    
    
    
    
    	/** Update recent users entry, required to trap in online users
	*/
	public static function completeLoginTracking($trackingData)
	{
		$profileId=$trackingData["profileId"];
		$ip=CommonFunction::getIP();
		$queueArr['ip']=$ip;
		if($trackingData[misLoginTracking])
		{
			include_once(sfConfig::get("sf_web_dir")."/classes/LoginTracking.class.php");
			$loginTracking= LoginTracking::getInstance($profileId);
			$loginTracking->setChannel($trackingData["channel"]);
			$loginTracking->setWebisteVersion($trackingData["websiteVersion"]);
			$loginTracking->setRequestURI($trackingData["page"]);
			$loginTracking->loginTracking();
		}
		if($trackingData[logLoginHistoryTracking])
		{
			$dbName = JsDbSharding::getShardNo($profileId);
			//Insert Into LOG_LOGIN_HISTORY
			$dbLogLoginHistory=new NEWJS_LOG_LOGIN_HISTORY($dbName);
			$dbLogLoginHistory->insertIntoLogLoginHistory($profileId,$ip);
			
			//Insert Ignore Into LOGIN_HISTORY 
			$dbLoginHistory= new NEWJS_LOGIN_HISTORY($dbName);
			$insert=$dbLoginHistory->insertIntoLoginHistory($profileId);
			//if exist then update
			if(!$insert)
			{
				//if exist then update  newjs.LOGIN_HISTORY_COUNT
				$dbLoginHistoryCount= new NEWJS_LOGIN_HISTORY_COUNT($dbName);
				$update=$dbLoginHistoryCount->updateLoginHistoryCount($profileId);
	            if(!$update)
				$dbLoginHistoryCount->replaceLoginHistoryCount($profileId);
			}
			$dbJprofile=new JPROFILE("newjs_master");
			$dbJprofile->updateLoginSortDate($profileId);
		}
		if($trackingData["appLoginProfileTracking"])
		{
			$dbAppLoginProfiles=new MOBILE_API_APP_LOGIN_PROFILES();
			$appProfileId=$dbAppLoginProfiles->insertAppLoginProfile($profileId);
		}
		if($trackingData["logLogoutTracking"])
		{
			$dbObj = new LOG_LOGOUT_HISTORY(JsDbSharding::getShardNo($profileId));
			$dbObj->insert($profileId,$ip);
		}

	}
    
    
    
    
    
    
    
    
    
    
    
}