<?php

class MatchalertNotification
{

public static function getCount($profileid)
{
	$skipContactedType = SkipArrayCondition::$MATCHALERT;
	$skipProfileObj    = SkipProfile::getInstance($profileid);
	$skipProfile       = $skipProfileObj->getSkipProfiles($skipContactedType);
	$logDate 	   = ceil(self::getLogDateFromLogicalDate()-1);
	$matchalertLogObj  = new matchalerts_LOG();
	//return $count = $matchalertLogObj->getMatchAlertProfileCount($profileid, $skipProfile,$logDate);
	$matchalertArr 	   = $matchalertLogObj->getMatchAlertProfileForNotification($profileid, $skipProfile,$logDate);
	//print_r($matchalertArr);
	$photoUrl 	   = self::getProfileHavingPhoto($matchalertArr);
	$dataPool['COUNT'] = count($matchalertArr);
	$dataPool['PHOTO'] = $photoUrl;
	return $dataPool;
}
public static function getProfileHavingPhoto($matchalertArr='')
{
	if(is_array){
		$photoIcon ='P';
		foreach($matchalertArr as $key=>$profileid){
			if($profileid){
				$notificationDataPoolObj = new NotificationDataPool();
				$photoUrl =$notificationDataPoolObj->getNotificationImage($photoIcon,$profileid);
				if($photoUrl && $photoUrl!='D')
					return $photoUrl;
			}
		}
		return $photoUrl; 
	}
}
public static function getLogDateFromLogicalDate($inputDate='')
        {
                if(!$inputDate)
                       $inputDate=mktime(0,0,0,date("m"),date("d"),date("Y"));
                $zero=mktime(0,0,0,01,01,2005);
                $gap=($inputDate-$zero)/(24*60*60);
                return $gap;
        }

}
