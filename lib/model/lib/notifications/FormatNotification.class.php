<?php

/**
 * Description of GCM
 *
 */

class FormatNotification
{
    public static function formater($details, $localNotification='') 
    {
	$dataArray = array("MESSAGE"=>$details['MESSAGE'],'LANDING_SCREEN'=>$details['LANDING_SCREEN'],'PROFILE_CHECKSUM'=>$details['PROFILE_CHECKSUM'],"COLLAPSE_STATUS"=>$details['COLLAPSE_STATUS'],"TITLE"=>$details['TITLE'],"PHOTO_URL"=>$details['PHOTO_URL'],"USERNAME"=>$details['USERNAME'],'NOTIFICATION_KEY'=>$details['NOTIFICATION_KEY'],'MSG_ID'=>$details['MSG_ID']);
	if($localNotification) 
		$dataArray['SENT']=$details['SENT'];
	if($details['IMG_URL'])
		$dataArray['IMG_URL']=$details['IMG_URL'];
	if($details['COUNT'])
		$dataArray['COUNT']=$details['COUNT'];
	if($details['NEW_REGID'])
		$dataArray['NEW_REGID']=$details['NEW_REGID'];
    if($details['NOTIFICATION_KEY']=='UPGRADE_APP'){
        $dataArray['CURRENT_ANDROID_MAX_VERSION']=$details['CURRENT_ANDROID_MAX_VERSION'];
        unset($dataArray['USERNAME']);
        unset($dataArray['PROFILE_CHECKSUM']);
    }
	if($details['NOTIFICATION_KEY']=="EOI")
		$dataArray['RESPONSE_TRACKING']="responseTracking=".JSTrackingPageType::GCM_PROFILE_PAGE;
	if($details['NOTIFICATION_KEY']=='PHOTO_REQUEST')
		$dataArray['STYPE'] =SearchTypesEnums::PHOTO_REQUEST_ANDROID;
	if($details['NOTIFICATION_KEY']=='PHOTO_UPLOAD')
		$dataArray['STYPE'] =SearchTypesEnums::PHOTO_UPLOAD_ANDROID;
    	if($details['NOTIFICATION_KEY']=='MATCH_OF_DAY')
		$dataArray['STYPE'] =SearchTypesEnums::AndroidMatchOfDay;
    if($details['NOTIFICATION_KEY']=='CHAT_MSG' || $details['NOTIFICATION_KEY'] == "CHAT_EOI_MSG" || $details['NOTIFICATION_KEY'] == "MESSAGE_RECEIVED"){
		$dataArray['OTHER_PROFILEID'] =$details['OTHER_PROFILEID'];
        $dataArray['CHAT_ID'] =$details['CHAT_ID'];
        $dataArray['OTHER_USERNAME'] =$details['OTHER_USERNAME'];
    }
    

    if($details['NOTIFICATION_KEY']=='MATCHALERT')
            $dataArray['STYPE'] =SearchTypesEnums::MATCHALERT_ANDROID;
    if($details['NOTIFICATION_KEY']=='JUST_JOIN')
            $dataArray['STYPE'] =SearchTypesEnums::JUST_JOIN_ANDROID;
    if($details['NOTIFICATION_KEY']=='PENDING_EOI')
            $dataArray['RTYPE'] =JSTrackingPageType::PENDING_EOI_ANDROID;
    if($details['NOTIFICATION_KEY']=='FILTERED_EOI')
            $dataArray['RTYPE'] =JSTrackingPageType::FILTERED_EOI_ANDROID;
        
    if(in_array($details['NOTIFICATION_KEY'], NotificationEnums::$loggedOutNotifications)){
        unset($dataArray['USERNAME']);
        unset($dataArray['PROFILE_CHECKSUM']);
    }
	return $dataArray;
    }
    public static function formaterForIos($details)
    {
        $dataArray = array('PROFILE_CHECKSUM'=>$details['PROFILE_CHECKSUM'],'LANDING_SCREEN'=>$details['LANDING_SCREEN'],'NOTIFICATION_KEY'=>$details['NOTIFICATION_KEY'],'MSG_ID'=>$details['MSG_ID'],'PHOTO_URL'=>$details['PHOTO_URL']);
        if($details['NOTIFICATION_KEY']=='PHOTO_REQUEST')
                $dataArray['STYPE'] =SearchTypesEnums::PHOTO_REQUEST_IOS;
        if($details['NOTIFICATION_KEY']=='PHOTO_UPLOAD')
                $dataArray['STYPE'] =SearchTypesEnums::PHOTO_UPLOAD_IOS;
        if($details['NOTIFICATION_KEY']=='MATCH_OF_DAY')
                $dataArray['STYPE'] =SearchTypesEnums::IOSMatchOfDay;

	/*
        if($details['NOTIFICATION_KEY']=='MATCHALERT')
                $dataArray['STYPE'] =SearchTypesEnums::MATCHALERT_IOS;
        if($details['NOTIFICATION_KEY']=='JUST_JOIN')
                $dataArray['STYPE'] =SearchTypesEnums::JUST_JOIN_IOS;
        if($details['NOTIFICATION_KEY']=='PENDING_EOI')
                $dataArray['RTYPE'] =JSTrackingPageType::PENDING_EOI_IOS;
        if($details['NOTIFICATION_KEY']=='FILTERED_EOI')
                $dataArray['RTYPE'] =JSTrackingPageType::FILTERED_EOI_IOS;
	*/

        return $dataArray;
    }

    /*notification formater for push notifications for new architecture */
    public static function formatPushNotification($details,$channel)
    {
	if($channel == 'ALL' || $channel == 'AND')
	{
	    $dataArray = array("PROFILEID"=>$details["PROFILEID"],"MESSAGE"=>$details['MESSAGE'],'LANDING_SCREEN'=>$details['LANDING_SCREEN'],'PRIORITY'=>$details['PRIORITY'],"COLLAPSE_STATUS"=>$details['COLLAPSE_STATUS'],"TITLE"=>$details['TITLE'],"COUNT"=>$details['COUNT'],"SENT"=>$details['SENT'],"PHOTO_URL"=>$details['PHOTO_URL'],'NOTIFICATION_KEY'=>$details['NOTIFICATION_KEY'],'MSG_ID'=>$details['MSG_ID'],"TTL"=>$details["TTL"],"OS_TYPE"=>$details['OS_TYPE']);
            $type = "APP_NOTIFICATION";
	}
    	elseif(in_array("CRM_AND", $channel)==false)
    	{
            $dataArray = array("REG_ID"=>array($details["REG_ID"]),"NOTIFICATION_KEY"=>$details["NOTIFICATION_KEY"],"MSG_ID"=>$details["MSG_ID"]);
            $type = "BROWSER_NOTIFICATION";
        }
    	else
        {
    		$dataArray = array("REG_ID"=>array($details["REG_ID"]),"MESSAGE"=>$details['MESSAGE'],'LANDING_ID'=>$details['LANDING_ID'],'PROFILE_CHECKSUM'=>$details['PROFILE_CHECKSUM'],"COLLAPSE_STATUS"=>$details['TAG'],"TITLE"=>$details['TITLE'],"ICON"=>$details['ICON'],"USERNAME"=>$details['USERNAME'],'NOTIFICATION_KEY'=>$details['NOTIFICATION_KEY'],'MSG_ID'=>$details['MSG_ID'],"TTL"=>$details["TTL"]);
            $type = "FSOAPP_NOTIFICATION";
        }
	$queueName = MessageQueues::$notificationArr[$details['NOTIFICATION_KEY']];
	if($queueName == '')
		$queueName = 'JS_NOTIFICATION6';
    	$msgdata = array('process' => $queueName, 'data' => array('type' => $type, 'body' => $dataArray), 'redeliveryCount' => 0);
		return $msgdata;
    }
    public static function formatLogData($dataArray,$table='',$process='')
    {
        if($table =='REGISTRATION_ID'){
            $type = $table;
        }
        elseif($table=='LOCAL_NOTIFICATION_LOG'){
            $type = $table;
        }
        elseif($process=='DELIVERY_TRACKING_API'){
            $type = $process;
        }
        elseif($process=='UPDATE_NOTIFICATION_STATUS_API'){
            $type = $process;
        }
        elseif($process=='REGISTRATION_API'){
            $type = $process;
        }
        else if($process == 'NOTIFICATION_OPENED_TRACKING_API'){
            $type = $process;
        }
        $queueName ='JS_NOTIFICATION_LOG';
        $msgdata = array('process' => $queueName, 'data' => array('type' => $type, 'body' => $dataArray), 'redeliveryCount' => 0);
        return $msgdata;
    }
    

}
?>
