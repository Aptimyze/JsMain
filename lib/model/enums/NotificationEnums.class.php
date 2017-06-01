<?php
class NotificationEnums
{
	public static $appVariablesMaxlength = array(
			"USERNAME"  => 8,
			"USERNAME1" => 8,
			"USERNAME2" => 8,
            "PASSWORD"  => 16,
            "MSTATUS"   => 13,
            "MTONGUE"   => 12,
            "DTOFBIRTH" => 10,
            "HEIGHT"    => 6,
            "NAME"      => 12,
            "BACK_MATCH_URL" => 23,
            "PAYMENT" => 5,
            "NOSLIKEME" => 3,
            "ULOGIN" => 23,
            "URL_ACCEPT" => 23,
            "UDESPID" => 23,
            "CASTE" => 12,
            "MSTATUS" => 13,
            "EDU_LEVEL" => 10,
            "OCCUPATION" => 10,
            "CITY_RES" => 10,
            "ANAME" => 11,
            "ACITY" => 15,
            "COMPANY_NAME" => 12
			);
	public static $dppMatchFreshness = 7;
	public static $PENDING = "P";
	public static $DELIVERED = "Y";
	public static $LOCAL = "L";
	public static $alarmMinTime = "18:00:00";
	public static $alarmMaxTime = "20:00:00";
	public static $scheduledNotificationCap = 5;
	public static $appMessageDelimiters = array("{","}");
	const AppNotificationSettingClass = "MOBILE_API_APP_NOTIFICATIONS";
	public static $GcmAppHeaders = array(
            'Authorization: key=AIzaSyBtGcgP-t9J1iFSs3TKxxc6kMljNAQthZ0',
	    'Content-Type: application/json'
	);
	public static $iosResponseCodeArr =array("0"=>"0-No errors encountered","1"=>"1-Processing error","2"=>"2-Missing device token","3"=>"3-Missing topic","4"=>"4-Missing payload","5"=>"5-Invalid token size","6"=>"6-Invalid topic size","7"=>"7-Invalid payload size","8"=>"8-Invalid token","255"=>"255-None(unknown)");
	public static $scheduledNotificationKey = array('JUST_JOIN','PENDING_EOI','VISITOR','ATN','ETN','VD','MATCHALERT','PROFILE_VISITOR','MEM_EXPIRE','FILTERED_EOI','CONTACTS_VIEWED_BY','CONTACT_VIEWS','PHOTO_UPLOAD','MEM_DISCOUNT','MATCH_OF_DAY','UPGRADE_APP','LOGIN_REGISTER');
      public static $keyBasedScheduleDaysConfig = array('PENDING_EOI'=>array('SAT','WED'));
      public static $CANCELLED = "C";
      public static $scheduledNotificationsLimit = 3;
      public static $scheduledNotificationPriorityArr = array('CONTACT_VIEWS','PENDING_EOI','CONTACTS_VIEWED_BY','PROFILE_VISITOR','JUST_JOIN','MATCHALERT','FILTERED_EOI');
      public static $staticContentNotification = array("FILTERED_EOI", "INCOMPLETE_SCREENING","MATCHALERT","UPGRADE_APP","LOGIN_REGISTER");  //notifications with static content
      public static $appVersionCheck = array("DEFAULT"=>array('AND'=>23,'IOS'=>1),
                                          "FILTERED_EOI"=>array('AND'=>32,'IOS'=>'2.2'),
                                          "CONTACTS_VIEWED_BY"=>array('AND'=>32,'IOS'=>'2.2'),
                                          "CONTACT_VIEWS"=>array('AND'=>32,'IOS'=>'2.2'),
                                          "CHAT_MSG"=>array('AND'=>90),
                                          "CHAT_EOI_MSG"=>array('AND'=>90),
                                          "LOGIN_REGISTER"=>array('AND'=>97),
                                          "UPGRADE_APP"=>array('AND'=>95)
                                        ); //app version mapping for notifications

      //profile registration offset for notification schedule
      public static $registrationOffsetForNotification = array("CONTACT_VIEWS"=>35);

      public static $digestNotificationKeys = array("EOI"=>"EOI_DIGEST");
      public static $channelArr = array("A_I"=>"Android - Ios","D"=>"Desktop","M"=>"Mobile");	
      public static $enableNotificationLogging = false;

      public static $mailScheduleComplete = array("UPGRADE_APP");
      
      public static $jscDevMail = "nitish.sharma@jeevansathi.com,vibhor.garg@jeevansathi.com,manoj.rana@naukri.com,ankita.g@jeevansathi.com,smarth.katyal@jeevansathi.com";

      //config for sending multiple curl requests for GCM notification in parallel
      public static $monitorInstantKeyArr =array('ACCEPTANCE','EOI','MESSAGE_RECEIVED','PHOTO_REQUEST','EOI_REMINDER','BUY_MEMB','PROFILE_VISITOR','PHOTO_UPLOAD','INCOMPLETE_SCREENING','CHAT_MSG','CHAT_EOI_MSG');	

      public static $monitorScheduledKeyArr =array('JUST_JOIN'=>'13-18','PENDING_EOI'=>'13-18','MATCH_OF_DAY'=>'13-18','MATCHALERT'=>'4-22');
      			
      public static $multiCurlReqConfig = array("threshold"=>50,"sendMultipleParallelNotification"=>true,"notificationKey"=>array("MATCH_OF_DAY","JUST_JOIN","PENDING_EOI","MEM_DISCOUNT","FILTERED_EOI"));

      //config for logged out notifications
      public static $loggedOutNotifications = array("LOGIN_REGISTER");

      //config for notifications, not eligible for local polling
      public static $notEligibleForPolling = array("LOGIN_REGISTER");

      // time criteria notification array	
      public static $timeCriteriaNotification = array('EOI','EOI_REMINDER','PHOTO_REQUEST');

      public static $notificationTempLogArr =array('JUST_JOIN');	
}
