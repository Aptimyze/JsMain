<?php
//This class defines constants' values used in rabbitmq files.

class MessageQueues
{
  CONST CONSUMERCOUNT = 3;  //Number of instances of Consumer class run at a time.
  CONST NOTIFICATIONCONSUMERCOUNT = 3;  //Number of instances of JsNotificationsConsume class run at a time.
  CONST SCREENINGCONSUMERCOUNT = 1;  //Number of instances of ScreeningConsumer class run at a time.
  CONST WRITEMESSAGECONSUMERCOUNT = 1;  //Number of instances of Write message queue consumers run at a time.
  CONST MAILQUEUE = "MailQueue";  //Queue for storing mails
  CONST SMSQUEUE = "SmsGcmQueue"; //Queue for storing sms
  CONST CONTACTCACHEINITIATE = "ContactCacheInitiate"; //Queue for sending data to webservice to create cache
  CONST GCMQUEUE = "SmsGcmQueue"; //Queue for storing gcm. Currenly same queue is used for both SMS and GCM.
  CONST CHATROSTERS = "chatRosters"; //Queue for users rosters for chat
  CONST USERCREATION = "USER_CREATION"; //Queue for chat user creation
  CONST CONSUMER_COUNT_SINGLE = 1; //This is to ensure that only 1 consumer instance runs at a time.
  CONST UPDATE_SEEN_CONSUMER_COUNT = 1; //variable to store cosumers to be executed for update seen
  CONST FEATURED_PROFILE_CONSUMER_COUNT = 1; //variable to store cosumers to be executed for update seen
  CONST PROFILE_CACHE_CONSUMER_COUNT = 1; //variable to store cosumers to be executed for update seen
  CONST CHAT_CONSUMER_COUNT = 1; //variable to store cosumers to be executed for chat messages
  CONST UPDATE_VIEW_LOG_CONSUMER_COUNT = 1;
  CONST NOTIFICATION_LOG_CONSUMER_COUNT = 1; //count of notification log consumer instances
  CONST INVALIDATECACHE = "invalidateCache";
  CONST CHAT_MESSAGE = "chatMessage";
  CONST VIEW_LOG = "ViewLogQueue";
  CONST FALLBACK_SERVER_MSGPICK_COUNT = 10;
  
  //per queue msg limit mapping
  public static $upperMessageLimitPerQueue = array("default"=>1000,"INSTANT_NOTIFICATION_QUEUE"=>10000);
  public static $queuesWithoutMsgCountLimit = array("SCHEDULED_NOTIFICATION_QUEUE1","SCHEDULED_NOTIFICATION_QUEUE2", "SCHEDULED_NOTIFICATION_QUEUE3", "SCHEDULED_NOTIFICATION_QUEUE4","SCHEDULED_NOTIFICATION_QUEUE5","SCHEDULED_NOTIFICATION_QUEUE6","profile-created-queue","profile-deleted-queue","roster-created-acceptance","roster-created-acceptance_sent","roster-created-intrec","roster-created-intsent","roster-created-shortlist","roster-updated-queue","roster-created-dpp","chat","delayed_profile_delete_queue"); //queues not to be considered for msg upper limit alert
  CONST SAFE_LIMIT = 200000000;     //Limit in MB's for the difference between memory allowed and memory used by rabbitmq.
  CONST MSGBODYLIMIT = NULL;  //to prevent truncation of message. NULL specify that a message of any length can be sent over the queue.
  CONST DELIVERYMODE = 2;     //for persistent messages. 2 is the default value to make messages persistent and the other allowed value is 1 which corresponds to non-persistent messages.
  CONST PASSIVE = false;      //If set, the server will reply with Declare-Ok if the queue already exists with the same name, and raise an error if queue with the given name doesnt exist.
  CONST DURABLE = true;       //the queue will survive server restarts. If set to false, the data in the queue would get lost.
  CONST EXCLUSIVE = false;    // the queue can be accessed in other channels
  CONST AUTO_DELETE = false;  //the queue won't be deleted once the channel is closed.
  CONST EXCHANGE = "";        //for default(nameless) exchange.
  CONST CHATEXCHANGE = "Profile"; //Chat Exchange
  CONST MANDATORY = true;     //If set,server will return an unroutable message with a Return method. If false, the server silently drops the message.
  CONST IMMEDIATE = false;    // If this set, the server will return an undeliverable message with a Return method. If false, the server will queue the message, but with no guarantee that it will ever be consumed.
  CONST MULTIPLE_TAG = true;  //If set,multiple messages can be rejected with a single method. If false, the delivery tag refers to a single message.
  CONST REQUEUE = true;       //If true, the server will attempt to requeue the message. If false or the requeue attempt fails the messages are discarded or dead-lettered.
  CONST CONSUMER = "";        //Consumer tag - identifier for consumer.
  CONST NO_LOCAL = false;     //If set the server will not send messages to the connection that published them.
  CONST NO_ACK = false;       //If set the server does not expect acknowledgements for messages from consumer.
  CONST CONSUMER_EXCLUSIVE = false; //Request exclusive consumer access, meaning only this consumer can access the queue.
  CONST NO_WAIT = false;      //If set, the server will not respond to the method and client should not wait for a reply method
  CONST CRONCONSUMER_STARTCOMMAND = "symfony cron:cronConsumeQueueMessage"; //Command to start cron:cronConsumeQueueMessageTask
  CONST CRONCHAT_CONSUMER_STARTCOMMAND = "symfony cron:cronConsumeChatMessage"; //Command to start cron:cronConsumeChatMessage
  CONST CRONNOTIFICATION_CONSUMER_STARTCOMMAND = "symfony cron:cronConsumeNotificationsQueueMessage"; //Command to start cron:cronConsumeNotificationsQueueMessageTask
  CONST CRONSCREENINGQUEUE_CONSUMER_STARTCOMMAND = "symfony cron:cronConsumeScreeningQueueTask"; //Command to start cron:cronConsumeScreeningQueueTask
  CONST CRONWRITEMESSAGEQUEUE_CONSUMER_STARTCOMMAND = "symfony cron:cronConsumeWriteMessageQUEUE"; //Command to start cron:cronConsumeWriteMessageQUEUE write message queue
  CONST CRONNOTIFICATION_LOG_CONSUMER_STARTCOMMAND = "symfony cron:cronConsumeNotificationsLogQueueMessage";
  CONST FALLBACK_STATUS= true;   //If true, second server is used to handle fallback otherwise only one server is in use.
  CONST REDELIVERY_LIMIT = 3; //This limit is used to set the redelivery limit of messages at the consumer end.
  CONST AGENT_NOTIFICATIONSQUEUE = "AgentsNotificationsQueue"; //Queue for storing agent notifications(notify for FP online users to agents)
  CONST BUFFER_INSTANT_NOTIFICATION_QUEUE = "BufferInstantNotificationsQueue"; //Queue for storing buffer instant notifications(JSPC/JSMS/FSO)
  CONST DUPLICATE_LOG_QUEUE = "DuplicateLogQueue"; //Queue for logging duplicate profiles
  CONST DELETE_RETRIEVE_QUEUE = "DeleteRetrieveQueue"; //Queue that contains profileId's for those profiles that are deleted.
  CONST SCREENING_QUEUE = "ScreeningQueue"; //Queue that contains profileId's for those profiles that are screened.
  CONST UPDATE_SEEN_QUEUE = "updateSeenQueue";
  CONST UPDATE_FEATURED_PROFILE_QUEUE = "updateFeaturedProfileQueue";
  CONST CRONDELETERETRIEVE_STARTCOMMAND = "symfony cron:cronConsumeDeleteRetrieveQueueMessage"; //Command to start cron:cronConsumeDeleteRetrieveQueueMessage
  CONST UPDATESEEN_STARTCOMMAND = "symfony cron:cronConsumeUpdateSeenQueueMessage"; //Command to start cron:cronConsumeDeleteRetrieveQueueMessage
  CONST UPDATE_FEATURED_PROFILE_STARTCOMMAND = "symfony cron:cronConsumeUpdateFeaturedProfileQueue"; //Command to start cron:cronConsumeDeleteRetrieveQueueMessage
  CONST PROFILE_CACHE_STARTCOMMAND = "symfony ProfileCache:ConsumeQueue"; //Command to start profile cache queue consuming cron
  CONST UPDATE_VIEW_LOG_STARTCOMMAND = "symfony cron:cronConsumeUpdateViewLogQueue"; //Command to start VIEW LOG consuming cron
  /*----------------JS notification(scheduled/instant) queues configuration details--------------------------*/

  public static $SCHEDULED_NOTIFICATION_QUEUE1 = "SCHEDULED_NOTIFICATION_QUEUE1"; //Queue for sending scheduled notification data from notification queue 1 to GCM
  public static $SCHEDULED_NOTIFICATION_QUEUE2 = "SCHEDULED_NOTIFICATION_QUEUE2"; //Queue for sending scheduled notification data from notification queue 2 to GCM
  public static $SCHEDULED_NOTIFICATION_QUEUE3 = "SCHEDULED_NOTIFICATION_QUEUE3"; //Queue for sending scheduled notification data from notification queue 3 to GCM
  public static $SCHEDULED_NOTIFICATION_QUEUE4 = "SCHEDULED_NOTIFICATION_QUEUE4"; //Queue for sending scheduled notification data from notification queue 4 to GCM
  public static $SCHEDULED_NOTIFICATION_QUEUE5 = "SCHEDULED_NOTIFICATION_QUEUE5"; //Queue for sending scheduled notification data from notification queue 5 to GCM
  public static $SCHEDULED_NOTIFICATION_QUEUE6 = "SCHEDULED_NOTIFICATION_QUEUE6"; //Queue for sending scheduled notification data from notification queue 6 to GCM
  public static $DELAYED_NOTIFICATION_EXCHANGE = array("NAME"=>"DelayedNotificationExchange","TYPE"=>"direct","DURABLE"=>true);
  public static $INSTANT_NOTIFICATION_EXCHANGE = array("NAME"=>"InstantNotificationExchange","TYPE"=>"fanout","DURABLE"=>true);
  public static $NOTIFICATION_LOG_EXCHANGE     = array("NAME"=>"NotificationLogExchange","TYPE"=>"direct","DURABLE"=>true);
  public static $scheduledNotificationBindingKeyArr=array("SCHEDULED_NOTIFICATION_QUEUE1" => "JS_NOTIFICATION1",
      "SCHEDULED_NOTIFICATION_QUEUE2" => "JS_NOTIFICATION2",
      "SCHEDULED_NOTIFICATION_QUEUE3" => "JS_NOTIFICATION3",
      "SCHEDULED_NOTIFICATION_QUEUE4" => "JS_NOTIFICATION4",
      "SCHEDULED_NOTIFICATION_QUEUE5" => "JS_NOTIFICATION5",
      "SCHEDULED_NOTIFICATION_QUEUE6" => "JS_NOTIFICATION6"	
  ); //queue name to exchange binding key mapping
  public static $scheduledNotificationDelayMappingArr =  array("SCHEDULED_NOTIFICATION_QUEUE1" => 8,
      "SCHEDULED_NOTIFICATION_QUEUE2" => 8,
      "SCHEDULED_NOTIFICATION_QUEUE3" => 2,
      "SCHEDULED_NOTIFICATION_QUEUE4" => 0.5,
      "SCHEDULED_NOTIFICATION_QUEUE5" => 10,
      "SCHEDULED_NOTIFICATION_QUEUE6" => 1
  );  //queue name to delay time(unit) mapping(configurable after queue deletion using x-expire field in queue declaration)
  public static $notificationDelayMultiplier = 3600; //1 hr multiple delay
  public static $notificationQueueExpiryTime = 7; //queue will expire if unused for 7 hrs,not used currently
  public static $INSTANT_NOTIFICATION_QUEUE = "INSTANT_NOTIFICATION_QUEUE"; //Queue for sending instant notification data from notification queue to GCM
  public static $NOTIFICATION_LOG_QUEUE = "NOTIFICATION_LOG_QUEUE";	
  
  public static $notificationArr = array("JUST_JOIN" => "JS_NOTIFICATION1", "PENDING_EOI" => "JS_NOTIFICATION2", "MEM_EXPIRE_A5" => "JS_NOTIFICATION3", "MEM_EXPIRE_A10" => "JS_NOTIFICATION3", "MEM_EXPIRE_A15" => "JS_NOTIFICATION3", "MEM_EXPIRE_B1" => "JS_NOTIFICATION3", "MEM_EXPIRE_B5" => "JS_NOTIFICATION3",  "AGENT_ONLINE_PROFILE"=>"JS_INSTANT_NOTIFICATION","AGENT_FP_PROFILE"=>"JS_INSTANT_NOTIFICATION", "PROFILE_VISITOR" => "JS_INSTANT_NOTIFICATION","EOI"=>"JS_INSTANT_NOTIFICATION","MESSAGE_RECEIVED"=>"JS_INSTANT_NOTIFICATION","EOI_REMINDER"=>"JS_INSTANT_NOTIFICATION","MATCHALERT"=>"JS_NOTIFICATION4","MEM_DISCOUNT"=>"JS_NOTIFICATION4","FILTERED_EOI"=>"JS_NOTIFICATION5","ATN"=>"JS_NOTIFICATION3","ETN"=>"JS_NOTIFICATION3","CONTACT_VIEWS"=>"JS_NOTIFICATION3","CONTACTS_VIEWED_BY"=>"JS_NOTIFICATION2","VD"=>"JS_NOTIFICATION2","MEM_DISCOUNT"=>"JS_NOTIFICATION6");

  /*----------------JS notification(scheduled/instant) queues configuration details-------------------------*/
  const PROFILE_CACHE_Q_DELETE = "ProfileCacheDeleteQueue";
  const PROCESS_PROFILE_CACHE_DELETE = "PROFILE_CACHE_DELETE";
  const SCREENING_Q_EOI = "SCREENING_SEND_EOI";
  const DELAY_MINUTE = 15;
  const DELAY_WRITEMSG = self::DELAY_MINUTE*60;

  // queue/exchange names
  const WRITE_MSG_queueRightNow = 'right.now.queue';
  const WRITE_MSG_exchangeRightNow = 'right.now.exchange';
  const WRITE_MSG_queueDelayed5min = 'delayed.five.minutes.queue';
  const WRITE_MSG_exchangeDelayed5min = 'delayed.five.minutes.exchange';
  const WRITE_MSG_Q = "WRITE_MSG_Queue";
}

?>
