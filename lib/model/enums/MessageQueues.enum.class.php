<?
//This class defines constants' values used in rabbitmq files.

class MessageQueues
{
  CONST CONSUMERCOUNT = 4;  //Number of instances of Consumer class run at a time.
  CONST NOTIFICATIONCONSUMERCOUNT = 2;  //Number of instances of JsNotificationsConsume class run at a time.
  CONST MAILQUEUE = "MailQueue";  //Queue for storing mails
  CONST SMSQUEUE = "SmsGcmQueue"; //Queue for storing sms
  CONST CONTACTCACHEINITIATE = "ContactCacheInitiate"; //Queue for sending data to webservice to create cache
  CONST GCMQUEUE = "SmsGcmQueue"; //Queue for storing gcm. Currenly same queue is used for both SMS and GCM.

  //per queue msg limit mapping
  public static $upperMessageLimitPerQueue = array("default"=>1000,"INSTANT_NOTIFICATION_QUEUE"=>10000);
  public static $queuesWithoutMsgCountLimit = array("SCHEDULED_NOTIFICATION_QUEUE1","SCHEDULED_NOTIFICATION_QUEUE2", "SCHEDULED_NOTIFICATION_QUEUE3"); //queues not to be considered for msg upper limit alert
  CONST SAFE_LIMIT = 200000000;     //Limit in MB's for the difference between memory allowed and memory used by rabbitmq.
  CONST MSGBODYLIMIT = NULL;  //to prevent truncation of message. NULL specify that a message of any length can be sent over the queue.
  CONST DELIVERYMODE = 2;     //for persistent messages. 2 is the default value to make messages persistent and the other allowed value is 1 which corresponds to non-persistent messages.
  CONST PASSIVE = false;      //If set, the server will reply with Declare-Ok if the queue already exists with the same name, and raise an error if queue with the given name doesnt exist.
  CONST DURABLE = true;       //the queue will survive server restarts. If set to false, the data in the queue would get lost.
  CONST EXCLUSIVE = false;    // the queue can be accessed in other channels
  CONST AUTO_DELETE = false;  //the queue won't be deleted once the channel is closed.
  CONST EXCHANGE = "";        //for default(nameless) exchange.
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
  CONST CRONNOTIFICATION_CONSUMER_STARTCOMMAND = "symfony cron:cronConsumeNotificationsQueueMessage"; //Command to start cron:cronConsumeNotificationsQueueMessageTask
  CONST FALLBACK_STATUS= true;   //If true, second server is used to handle fallback otherwise only one server is in use.
  CONST REDELIVERY_LIMIT = 3; //This limit is used to set the redelivery limit of messages at the consumer end.
  CONST AGENT_NOTIFICATIONSQUEUE = "AgentsNotificationsQueue"; //Queue for storing agent notifications(notify for FP online users to agents)
  CONST BUFFER_INSTANT_NOTIFICATION_QUEUE = "BufferInstantNotificationsQueue"; //Queue for storing buffer instant notifications(JSPC/JSMS/FSO)
  CONST DELETE_RETRIEVE_QUEUE = "DeleteRetrieveQueue"; //Queue that contains profileId's for those profiles that are deleted.

  /*----------------JS notification(scheduled/instant) queues configuration details--------------------------*/

  public static $SCHEDULED_NOTIFICATION_QUEUE1 = "SCHEDULED_NOTIFICATION_QUEUE1"; //Queue for sending scheduled notification data from notification queue 1 to GCM
  public static $SCHEDULED_NOTIFICATION_QUEUE2 = "SCHEDULED_NOTIFICATION_QUEUE2"; //Queue for sending scheduled notification data from notification queue 2 to GCM
  public static $SCHEDULED_NOTIFICATION_QUEUE3 = "SCHEDULED_NOTIFICATION_QUEUE3"; //Queue for sending scheduled notification data from notification queue 3 to GCM
  public static $DELAYED_NOTIFICATION_EXCHANGE = array("NAME"=>"DelayedNotificationExchange","TYPE"=>"direct","DURABLE"=>true);
  public static $INSTANT_NOTIFICATION_EXCHANGE = array("NAME"=>"InstantNotificationExchange","TYPE"=>"fanout","DURABLE"=>true);
  public static $scheduledNotificationBindingKeyArr=array("SCHEDULED_NOTIFICATION_QUEUE1" => "JS_NOTIFICATION1",
                                                 "SCHEDULED_NOTIFICATION_QUEUE2" => "JS_NOTIFICATION2",
                                                 "SCHEDULED_NOTIFICATION_QUEUE3" => "JS_NOTIFICATION3"
                                                ); //queue name to exchange binding key mapping
  public static $scheduledNotificationDelayMappingArr =  array("SCHEDULED_NOTIFICATION_QUEUE1" => 6.5,
                                                              "SCHEDULED_NOTIFICATION_QUEUE2" => 6,
                                                              "SCHEDULED_NOTIFICATION_QUEUE3" => 6
                                                          );  //queue name to delay time(unit) mapping(configurable after queue deletion using x-expire field in queue declaration)
  public static $notificationDelayMultiplier = 3600; //1 hr multiple delay
  public static $notificationQueueExpiryTime = 7; //queue will expire if unused for 7 hrs,not used currently
  public static $INSTANT_NOTIFICATION_QUEUE = "INSTANT_NOTIFICATION_QUEUE"; //Queue for sending instant notification data from notification queue to GCM
  public static $notificationArr = array("JUST_JOIN" => "JS_NOTIFICATION1", "PENDING_EOI" => "JS_NOTIFICATION2", "MEM_EXPIRE_A5" => "JS_NOTIFICATION3", "MEM_EXPIRE_A10" => "JS_NOTIFICATION3", "MEM_EXPIRE_A15" => "JS_NOTIFICATION3", "MEM_EXPIRE_B1" => "JS_NOTIFICATION3", "MEM_EXPIRE_B5" => "JS_NOTIFICATION3",  "AGENT_ONLINE_PROFILE"=>"JS_INSTANT_NOTIFICATION","AGENT_FP_PROFILE"=>"JS_INSTANT_NOTIFICATION", "PROFILE_VISITOR" => "JS_INSTANT_NOTIFICATION","EOI"=>"JS_INSTANT_NOTIFICATION","MESSAGE_RECEIVED"=>"JS_INSTANT_NOTIFICATION","EOI_REMINDER"=>"JS_INSTANT_NOTIFICATION");

  /*----------------JS notification(scheduled/instant) queues configuration details-------------------------*/
}

?>
