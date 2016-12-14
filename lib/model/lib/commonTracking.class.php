<?php

class commonTracking {
    
    
    
    
    	/** Update recent users entry, required to trap in online users
	*/
	public static function logRecentUserEntry($profileId,$isMobile,$dateTime1,$dateTime2)
	{
		$allow=1;
		$pid=intval($profileId);
		if($allow && $pid && !$isMobile)
		{
                        $dbObj=new userplane_recentusers("newjs_master");
			$dbObj->replacedata($pid);

		}

		// Add Online-User
		$dateTime =date("H");
		$redisOnline =true;
		if(($dateTime>=$dateTime1) && ($dateTime<$dateTime2))
			$redisOnline =false;
		if($pid && $allow && $redisOnline)
		{
			$jsCommonObj =new JsCommon();
			$jsCommonObj->setOnlineUser($pid);
		}

	}

        public static function insert_into_login_history($profileID,$ip){
            		$dbName = JsDbSharding::getShardNo($profileID);
		//Insert Into LOG_LOGIN_HISTORY
		$dbLogLoginHistory=new NEWJS_LOG_LOGIN_HISTORY($dbName);
		$dbLogLoginHistory->insertIntoLogLoginHistory($profileID,$ip);
		
		//Insert Ignore Into LOGIN_HISTORY 
		$dbLoginHistory= new NEWJS_LOGIN_HISTORY($dbName);
		$insert=$dbLoginHistory->insertIntoLoginHistory($profileID);
		//if exist then update
		if(!$insert)
		{
			//if exist then update  newjs.LOGIN_HISTORY_COUNT
			$dbLoginHistoryCount= new NEWJS_LOGIN_HISTORY_COUNT($dbName);
			$update=$dbLoginHistoryCount->updateLoginHistoryCount($profileID);
            
            //If No Update then replace
            if(!$update)
			$dbLoginHistoryCount->replaceLoginHistoryCount($profileID);
		}
		//update Jprofile LAST_LOGIN_DT
		$dbJprofile=new JPROFILE("newjs_master");
		$dbJprofile->updateLoginSortDate($profileID);
        }

	public static function loginTracking($profileId,$channel,$websiteVersion,$location="",$reqUri)
	{
		include_once(sfConfig::get("sf_web_dir")."/classes/LoginTracking.class.php");
		$loginTracking= LoginTracking::getInstance($profileId);
		$loginTracking->setChannel($channel);
		$loginTracking->setWebisteVersion($websiteVersion);
		
		if(!$location)
		{
			if(sfContext::getInstance()->getRequest()->getParameter('link_id') && strpos($_SERVER[REQUEST_URI],"/e/")!==false){
				$link=LinkFactory::getLink(sfContext::getInstance()->getRequest()->getParameter('link_id'));
				$request_uri=$link->getLinkAddress();
			}
			else
				$request_uri=$reqUri;
			$page=explode('?',$request_uri);
			$page=$page[0];
			$page=explode('/',$page);
			$no=count($page);
			$page=$page[$no-1];
		}
		else
		{
			if($location)
				$request_uri=$location;			
			$request_uri=str_replace("CMGFRMMMMJS=","pass=",$request_uri);
			$request_uri=str_replace("&echecksum=","&autologin=",$request_uri);
			$request_uri=str_replace("?echecksum=","?autologin=",$request_uri);
			$request_uri=str_replace("&checksum=","&chksum=",$request_uri);
			$request_uri=str_replace("?checksum=","?ckhsum=",$request_uri);
			$request_uri=str_replace(urlencode($echecksum),"",$request_uri);
			$request_uri=str_replace($echecksum,"",$request_uri);
			$request_uri=ltrim($request_uri,"/");
			$page=$request_uri;
		}
			$loginTracking->setRequestURI($page);
		$loginTracking->loginTracking();
	}

    
    
    
    
    
    
    
    
    
    
    
}