<?php
class BrowserNotificationEnums
{
	//headers sent in curl request to GCM for browser notifications
	public static $gcmHeaders = array("BROWSER_NOTIFICATION"=>array(
                                        'Authorization: key=AIzaSyDhN2_SvMkXPBnrl4Hr6IlmmC9tbWnPYuQ',
                            	    	'Content-Type: application/json'),
                                    "FSOAPP_NOTIFICATION"=>array(
                                        'Authorization: key=AIzaSyAEtHE2FAhvmfm--BOlXygFgI3mtvRTt-Q',
                                        'Content-Type: application/json')
	);

	CONST GCM_SUCCESS = 'S';  //gcm success status
	CONST GCM_FAILURE = 'F';  //gcm failure status
	CONST GCM_INVALID = 'I';  //gcm invalid status
    CONST GCM_REGID_EXPIRED = 'E';  //gcm invalid status
	CONST GCM_REQUEST_URL = "https://android.googleapis.com/gcm/send";   //url of gcm api
    public static $messageDelimiters = array("{","}");
    public static $variablesMaxlength = array(
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
    public static $landingIdToUrl = array(
            "1" => "/search/perform?justJoinedMatches=1",
            "2"=>"2",
            "3" => "/profile/contacts_made_received.php?page=eoi&filter=R",
            "4" => "/profile/contacts_made_received.php?page=visitors&filter=R",
            "5" => "/profile/mem_comparison.php"
    );
    public static $staticContentNotification = array();  
      
    public static $publishedNotificationLog = "/web/uploads/NotificationLogs/PublishedNotifications.txt";
    public static $transferredNotificationlog = "/web/uploads/NotificationLogs/TransferredNotifications.txt";
    public static $addNotificationLog = true;  //add published and transferred msg keys in logs
    public static $notificationChannelType = array("BROWSER_NOTIFICATION","FSOAPP_NOTIFICATION");
    public static $instantNotifications = array("AGENT_ONLINE_PROFILE","AGENT_FP_PROFILE", "PROFILE_VISITOR");
    public static $renewalReminderNotification = array("MEM_EXPIRE_A5", "MEM_EXPIRE_A10", "MEM_EXPIRE_A15", "MEM_EXPIRE_B1", "MEM_EXPIRE_B5");
    //count of notifications picked from BROWSER_NOTIFICATION and backed up at a time
    public static $backupNotificationsCountLimit = 1000;
    //login history channel wise criteria for notifications(instant/scheduled)
    public static $loginBasedNotificationProfileFilter = array(
                                                                "D"=>"'D'",
                                                                "M"=>"'A','I','M','N'"
                                                             );
    //notifications for which channel wise login filter is not applicable
    public static $notificationWithoutLoginFilter = array("AGENT_ONLINE_PROFILE","AGENT_FP_PROFILE");

    public static $minChromeVersion = 44;
}
?>
