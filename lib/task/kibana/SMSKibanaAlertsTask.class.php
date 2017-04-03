<?php
/*
 *	A Symfony task which sends an SMS at an interval whenever the count of
 *  errors for servers crosses the specified threshold
 */
class SMSkibanaAlertsTask extends sfBaseTask
{
	private $SMS_TO = array("9818424749","9953457479","9873639543","8826380350","9999216910","9868673709","9953178503","9650350387","9755158977","8010619996");
  	const FROM_ID = "JSSRVR";
  	const PROFILE_ID = "144111";

	protected function configure()
	{
		$this->name             = 'SMSkibanaAlerts';
		$this->briefDescription = 'Sends SMS if no. of errors cross a threshold';
		$this->detailedDescription = <<<EOF
The [SMSkibanaAlerts|INFO] task sends SMS if a threshold value is crossed.
Call it with:

	[php symfony SMSkibanaAlerts|INFO]
EOF;
	}

	protected function execute($arguments = array(), $options = array())
	{
		date_default_timezone_set('Asia/Calcutta');

		$this->thresholdSMS = KibanaEnums::$SMS_ERROR_THRESHOLD;
		// include(JsConstants::$docRoot."/commonFiles/sms_inc.php");


		$currdate = date('Y.m.d');
		// Server at which ElasticSearch and kibana is running
		$elkServer = JsConstants::$kibana['ELK_SERVER'];
		$elkPort = JsConstants::$kibana['ELASTIC_PORT'];
		$kibanaPort = JsConstants::$kibana['KIBANA_PORT'];
		$indexName = KibanaEnums::$FILEBEAT_INDEX.'*';
		$query = KibanaEnums::$KIBANA_SEARCH_QUERY;
		$timeout = KibanaEnums::$SMS_ALERT_TIMEOUT;
		// in minutes
		$interval = KibanaEnums::$SMS_ALERT_THRESHOLD;
		$urlToHit = $elkServer.':'.$elkPort.'/'.$indexName.'/'.$query;

	
	 

		$intervalStringEnd = '-'.$interval.' minutes';

		$fromInt = date('Y:m:d H:i:s',strtotime($intervalStringEnd));
		$toInt = date('Y:m:d H:i:s');

		$params = [
		"query"=> [
		    "match" => ["logType"=>"Error"]
		],
		"aggs"=> [
		"filtered"=> [
		  "filter"=> [
		    "bool"=> [
		      "must"=> [
		        [
		          "range"=> [
		            "@timestamp"=> [
		              "gt"=> "now-". $interval."m",
		              "lt"=> "now"
		            ]
		          ]
		        ],
		        [
		        	"exists" => 
		        	[
                    	"field" => "LogMessage"
                	]
		        ]
		      ],
		      "must_not" => [
		      	"regexp" =>[
		      		"LogMessage" => ".*no conn.*|.*Too many connections.*|^.{0,0}$"
		      	]
		      ]
		    ]
		  ],
		  "aggs"=> [
		    "modules"=>
		    [
		        "terms"=>
		        [ "field" => "beat.name" ],
		    ]
		  			]
				]
			]
		];
		// send curl request
		$response =  CommonUtility::sendCurlPostRequest($urlToHit, json_encode($params), $timeout);

		if($response)
		{
			$arrResponse = json_decode($response, true);
			$arrServers = array();

			$arrServers['startTime'] = $fromInt;
			$arrServers['endTime'] = $toInt;

			// Get module count for each module
			foreach($arrResponse['aggregations']['filtered']['modules']['buckets'] as $server)
			{
				$countError = $server['doc_count'];
				$serverName = $server['key'];
				if ( $countError >= $this->thresholdSMS )
				{
			   		$this->sendSMS($serverName,$countError,$fromInt);
				}
			}
		}
		else
		{
			die;
		}
	}
	/**
	 * send sms
	 * @param  int $serverName server, on which error has occured
	 * @param  int $errorCount count count on servers
	 * @param  string $time       
	 */
    private function sendSMS($serverName,$errorCount,$time) {
      $this->smsMessage = "Mysql Error Count have reached Threshold on $serverName($errorCount) within 5 minutes $time";
      foreach ($this->SMS_TO as $mobPhone) {
        $xml_head = "%3C?xml%20version=%221.0%22%20encoding=%22ISO-8859-1%22?%3E%3C!DOCTYPE%20MESSAGE%20SYSTEM%20%22http://127.0.0.1/psms/dtd/message.dtd%22%3E%3CMESSAGE%3E%3CUSER%20USERNAME=%22naukari%22%20PASSWORD=%22na21s8api%22/%3E";
        $xml_content="%3CSMS%20UDH=%220%22%20CODING=%221%22%20TEXT=%22".urlencode($this->smsMessage)."%22%20PROPERTY=%220%22%20ID=%22".self::PROFILE_ID."%22%3E%3CADDRESS%20FROM=%22".self::FROM_ID."%22%20TO=%22".$mobPhone."e%22%20SEQ=%22".self::PROFILE_ID."%22%20TAG=%22%22/%3E%3C/SMS%3E";
        $xml_end = "%3C/MESSAGE%3E";
        $xml_code = $xml_head . $xml_content . $xml_end;
        $fd = @fopen("http://api.myvaluefirst.com/psms/servlet/psms.Eservice2?data=$xml_code&action=send", "rb");
        if ($fd) {
          $response = '';
          while (!feof($fd)) {
            $response.= fread($fd, 4096);
          }
          fclose($fd);
        }
      }
  }


}