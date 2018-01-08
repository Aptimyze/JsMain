<?php 
	/**
	* Enums related to kibana crons are stored here
	*/
	class KibanaEnums
	{
		public static $FILEBEAT_INDEX = 'filebeat-';
		public static $FILEBEAT_INDEX_DELETION_LIMIT = '-32 days';

		public static $CONSUMER_INDEX = 'consumer-';
		public static $CONSUMER_INDEX_DELETION_LIMIT = '-3 days';

		public static $OPENFIRE_INDEX = 'openfire-';
		public static $OPENFIRE_INDEX_DELETION_LIMIT = '-7 days';

		public static $COOLMETRIC_INDEX = 'coolmatric-';
		public static $COOLMETRIC_INDEX_DELETION_LIMIT = '-8 days';

		public static $ANDROIDCHAT_INDEX = 'androidchat-';
		public static $ANDROIDCHAT_INDEX_DELETION_LIMIT = '-8 days';

		public static $APACHE_INDEX = 'apache-';
		public static $APACHE_INDEX_DELETION_LIMIT = '-8 days';

		public static $RABBITTIME_INDEX = 'rabbittime-';
		public static $RABBITTIME_INDEX_DELETION_LIMIT = '-3 days';
		
		public static $SERVER_INDEX = 'server-';
		public static $SERVER_INDEX_DELETION_LIMIT = '-3 days';




		public static $COOLMETRIC_TREND_INDEX = 'coolmetrictrends';

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