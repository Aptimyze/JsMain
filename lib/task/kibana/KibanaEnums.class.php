<?php 
	/**
	* Enums related to kibana crons are stored here
	*/
	class KibanaEnums
	{
		public static $FILEBEAT_INDEX = 'filebeat-';
		public static $CONSUMER_INDEX = 'consumer-';
		public static $OPENFIRE_INDEX = 'openfire-';


		public static $ERROR_TREND_INDEX = 'errortrends';
		public static $STARTING_DAYS_BEFORE = 1;
		public static $STARTING_DAYS_END = 1;

		public static $KIBANA_SEARCH_QUERY = '_search';
		public static $KIBANA_ALERT_EMAIL_INTERVAL = 1;
		public static $KIBANA_ALERT_EMAIL_THRESHOLD = 50;
		public static $KIBANA_REQUEST_THRESHOLD = 5000;


		public static $CONSUMER_ALERT_EMAIL_INTERVAL = 1;
		public static $CONSUMER_ALERT_EMAIL_THRESHOLD = 100;
		public static $CONSUMER_REQUEST_THRESHOLD = 5000;

		public static $SMS_ALERT_TIMEOUT = 5000;
		public static $SMS_ALERT_THRESHOLD = 5;
		public static $SMS_ERROR_THRESHOLD = 200;

		public static $AURA_INDEX = 'jeevansathiactivity';
		public static $UPTIME_INDEX = 'uptime';

		public static $UPTIME_DAY = 1;
		public static $UPTIME_HOUR = 24;
	}
 ?>