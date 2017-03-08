<?php 
	/**
	* Enums related to kibana crons are stored here
	*/
	class KibanaEnums
	{
		const ELK_SERVER = 'elkjs.js.jsb9.net';
		const ELASTIC_PORT = '9200';
		const KIBANA_PORT = '5601';
		const FILEBEAT_INDEX = 'filebeat-';
		const CONSUMER_INDEX = 'consumer-';


		const ERROR_TREND_INDEX = 'errortrends';
		const STARTING_DAYS_BEFORE = 1;
		const STARTING_DAYS_END = 1;

		const KIBANA_SEARCH_QUERY = '_search';
		const KIBANA_ALERT_EMAIL_INTERVAL = 1;
		const KIBANA_ALERT_EMAIL_THRESHOLD = 50;
		const KIBANA_REQUEST_THRESHOLD = 5000;


		const CONSUMER_ALERT_EMAIL_INTERVAL = 1;
		const CONSUMER_ALERT_EMAIL_THRESHOLD = 100;
		const CONSUMER_REQUEST_THRESHOLD = 5000;

		const SMS_ALERT_TIMEOUT = 5000;
		const SMS_ALERT_THRESHOLD = 5;
		const SMS_ERROR_THRESHOLD = 200;

		const AURA_SERVER = 'es.aura.resdex.com';
		const AURA_PORT = '9203';
		const AURA_INDEX = 'jeevansathiactivity';
		const UPTIME_INDEX = 'uptime';

		const UPTIME_DAY = 1;
		const UPTIME_HOUR = 24;

	}

 ?>