<?php
/*
 *	A Symfony task which sends an SMS at an interval whenever the count of
 *  errors for servers crosses the specified threshold
 */
class SMSkibanaAlertsTask extends sfBaseTask
{
	protected function configure()
	{
		$this->name             = 'SMSkibanaAlerts';
		$this->briefDescription = 'Sends SMS if no. of errors cross a threshold';
		$this->detailedDescription = <<<EOF
The [SMSkibanaAlerts|INFO] task sends SMS if a threshold value is crossed.
Call it with:

	[php symfony SMSkibanaAlerts|INFO]
EOF;
		$this->mobileNumberArr = array("9818424749","9953457479","9873639543","8826380350","9999216910","9868673709","9953178503","9650350387","9755158977","8010619996");
		$this->thresholdSMS = 500;
	}

	protected function execute($arguments = array(), $options = array())
	{
		include(JsConstants::$docRoot."/commonFiles/sms_inc.php");


		$currdate = date('Y.m.d');
		// Server at which ElasticSearch and kibana is running
		$elkServer = '10.10.18.66';
		$elkPort = '9200';
		$kibanaPort = '5601';
		$indexName = 'filebeat-*';
		$query = '_search';
		$timeout = 5000;
		// in minutes
		$interval = 5;
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
		        ]
		      ]
		    ]
		  ],
		  "aggs"=> [
		    "modules"=>
		    [
		        "terms"=>
		        [ "field" => "beat.name" ,  "size" => 10000 ],
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
			   		$this->sms($serverName,$countError,$fromInt);
				}
			}
		}
		else
		{
			die;
		}
	}

	function sms($serverName,$errorCount,$time)
	{
        $message        = "Mysql Error Count have reached server $serverName within 5 minutes $time";
        $from           = "JSSRVR";
        $profileid      = "144111";

        foreach ($this->mobileNumberArr as $key => $value) {
        	$smsState = send_sms($message,$from,$value,$profileid,'','Y');
        }
	}

}