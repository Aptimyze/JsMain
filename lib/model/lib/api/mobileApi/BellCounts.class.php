<?php
class BellCounts
{
	public static function getDetails($profileid)
        {
		if($profileid)
		{
			$profileObj=LoggedInProfile::getInstance('newjs_master');
			$profileMemcacheObj = new ProfileMemcacheService($profileObj);
			$bellCounts['AWAITING_RESPONSE_NEW']=JsCommon::convert99($profileMemcacheObj->get("AWAITING_RESPONSE_NEW"));
			$bellCounts['ACC_ME_NEW']=JsCommon::convert99($profileMemcacheObj->get("ACC_ME_NEW"));
	                $bellCounts['MESSAGE_NEW']=0;
	                //JsCommon::convert99($profileMemcacheObj->get("MESSAGE_NEW"));
	                $bellCounts['PHOTO_REQUEST_NEW']=JsCommon::convert99($profileMemcacheObj->get("PHOTO_REQUEST_NEW"));
	        $justJoinedMemcacheCount=$profileMemcacheObj->get('JUST_JOINED_MATCHES_NEW');
			$bellCounts['NEW_MATCHES']=JsCommon::convert99($justJoinedMemcacheCount);
            //            $justJoinMatchArr = SearchCommonFunctions::getJustJoinedMatches($profileObj); 
                        //$bellCounts['NEW_MATCHES']=JsCommon::convert99($justJoinMatchArr['CNT']);
			$bellCounts['TOTAL_NEW']=JsCommon::convert99($profileMemcacheObj->get("AWAITING_RESPONSE_NEW") + $profileMemcacheObj->get("ACC_ME_NEW") + $bellCounts['MESSAGE_NEW'] + $profileMemcacheObj->get("PHOTO_REQUEST_NEW") + $justJoinedMemcacheCount);
			return $bellCounts;
		}
        }

        public static function getFTUCountDetails($profileid)
        {
        	if($profileid)
        	{
        		$profileObj=LoggedInProfile::getInstance('newjs_master');
				$profileMemcacheObj = new ProfileMemcacheService($profileObj);
				$countDetails["INTEREST_RECEIVED"] = $profileMemcacheObj->get("AWAITING_RESPONSE");
				$countDetails["ACCEPTED"] = $profileMemcacheObj->get("ACC_ME");
				$countDetails["MESSAGE"] = $profileMemcacheObj->get("MESSAGE");
				$countDetails["PHOTO_REQUEST"] = $profileMemcacheObj->get("PHOTO_REQUEST");
				$countDetails["TOTAL"] = 0;
				foreach ($countDetails as $key => $value) {
					$countDetails["TOTAL"]  = $countDetails["TOTAL"]+$value;
				}
				$justJoinedMemcacheCount=$profileMemcacheObj->get('JUST_JOINED_MATCHES_NEW');
				if($justJoinedMemcacheCount)
					$countDetails['NEW_MATCHES']=JsCommon::convert99($justJoinedMemcacheCount);
				else
					$countDetails['NEW_MATCHES']=0;
				$countDetails['JUST_JOINED_MATCHES']=$profileMemcacheObj->get('JUST_JOINED_MATCHES');
				if(!$countDetails['JUST_JOINED_MATCHES'])
					$countDetails['JUST_JOINED_MATCHES']=0;
				$dailyMatchesMemcacheCount=$profileMemcacheObj->get('MATCHALERT');
				$countDetails['DAILY_MATCHES_NEW']=JsCommon::convert99($dailyMatchesMemcacheCount);
				if(!$countDetails['DAILY_MATCHES_NEW'])
					$countDetails['DAILY_MATCHES_NEW']=0;
				$countDetails['DAILY_MATCHES']=$profileMemcacheObj->get('MATCHALERT_TOTAL');
				if(!$countDetails['DAILY_MATCHES'])
					$countDetails['DAILY_MATCHES']=0;
				
				return $countDetails;

        	}
        }
        public static function getEngagementBarCounts($proflieid)
        {
        	if($proflieid)
        	{
        		$profileObj=LoggedInProfile::getInstance('newjs_master');
				$profileMemcacheObj = new ProfileMemcacheService($profileObj);
				$countDetails["AWAITING_RESPONSE_NEW"] = $profileMemcacheObj->get("AWAITING_RESPONSE_NEW");
				$countDetails["ACC_ME_NEW"] = $profileMemcacheObj->get("ACC_ME_NEW");
				$countDetails["MESSAGE_NEW"] = $profileMemcacheObj->get("MESSAGE_NEW");
				$countDetails["PHOTO_REQUEST_NEW"] = $profileMemcacheObj->get("PHOTO_REQUEST_NEW");
				$countDetails["AWAITING_RESPONSE"] = $profileMemcacheObj->get("AWAITING_RESPONSE");
				$countDetails["ACC_ME"] = $profileMemcacheObj->get("ACC_ME");
				$countDetails["MESSAGE"] = $profileMemcacheObj->get("MESSAGE");
				$countDetails["PHOTO_REQUEST"] = $profileMemcacheObj->get("PHOTO_REQUEST");
				return $countDetails;
        	}
        }
        
        public static function getNewCountsMyjsPc($proflieid)
        {
			if($proflieid)
			{
				$profileObj=LoggedInProfile::getInstance('newjs_master');
				$profileMemcacheObj = new ProfileMemcacheService($profileObj);
				$justJoinedMemcacheCount=$profileMemcacheObj->get('JUST_JOINED_MATCHES_NEW');
				if($justJoinedMemcacheCount)
					$countDetails['NEW_MATCHES']=JsCommon::convert99($justJoinedMemcacheCount);
				else
					$countDetails['NEW_MATCHES']=0;
				$dailyMatchesMemcacheCount=$profileMemcacheObj->get('MATCHALERT');
				$countDetails['DAILY_MATCHES_NEW']=JsCommon::convert99($dailyMatchesMemcacheCount);
				if(!$countDetails['DAILY_MATCHES_NEW'])
					$countDetails['DAILY_MATCHES_NEW']=0;
				return $countDetails;
			}
		}

		public static function getJSPCBellCounts($proflieid)
        {
			if($proflieid)
			{
				$profileObj = LoggedInProfile::getInstance('newjs_master');
				$profileMemcacheObj = new ProfileMemcacheService($profileObj);
				$justJoinedMemcacheCount = $profileMemcacheObj->get('JUST_JOINED_MATCHES_NEW');
				if($justJoinedMemcacheCount){
					$countDetails['NEW_MATCHES']=JsCommon::convert99($justJoinedMemcacheCount);
				}
				else{
					$countDetails['NEW_MATCHES']=0;
				}
				$dailyMatchesMemcacheCount=$profileMemcacheObj->get('MATCHALERT');
				$countDetails['DAILY_MATCHES_NEW']=JsCommon::convert99($dailyMatchesMemcacheCount);
				if(!$countDetails['DAILY_MATCHES_NEW']){
					$countDetails['DAILY_MATCHES_NEW']=0;
				}
				$countDetails["AWAITING_RESPONSE_NEW"] = $profileMemcacheObj->get("AWAITING_RESPONSE_NEW");
				if(!$countDetails["AWAITING_RESPONSE_NEW"]){
					$countDetails["AWAITING_RESPONSE_NEW"] = 0;
				}
				$countDetails["ACC_ME_NEW"] = $profileMemcacheObj->get("ACC_ME_NEW");
				if(!$countDetails["ACC_ME_NEW"]){
					$countDetails["ACC_ME_NEW"] = 0;
				}
				$countDetails["MESSAGE_NEW"] = $profileMemcacheObj->get("MESSAGE_NEW");
				if(!$countDetails["MESSAGE_NEW"]){
					$countDetails["MESSAGE_NEW"] = 0;
				}
				$countDetails["PHOTO_REQUEST_NEW"] = $profileMemcacheObj->get("PHOTO_REQUEST_NEW");
				if(!$countDetails["PHOTO_REQUEST_NEW"]){
					$countDetails["PHOTO_REQUEST_NEW"] = 0;
				}
				foreach($countDetails as $key=>$val){
					$countDetails['TOTAL_NEW'] += $val;
				}
				return $countDetails;
			}
		}
}
?>
