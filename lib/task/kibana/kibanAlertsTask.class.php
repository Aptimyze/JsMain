<?php
/*
 *	A Symfony task which sends a alert at an interval whenever the count of
 *  errors for modules crosses the specified threshold
 */
class kibanAlertsTask extends sfBaseTask
{
	protected function configure()
	{
		$this->namespace        = 'Alerter';
		$this->name             = 'kibanAlerts';
		$this->briefDescription = 'Sends alerts if no. of errors cross a threshold';
		$this->detailedDescription = <<<EOF
The [kibanAlerts|INFO] task does things.
Call it with:

	[php symfony Alerter:kibanAlerts|INFO]
EOF;
	}

	protected function execute($arguments = array(), $options = array())
	{
		$currdate = date('Y.m.d');
		// Server at which ElasticSearch and kibana is running
		$elkServer = JsConstants::$kibana['ELK_SERVER'];
		$elkPort = JsConstants::$kibana['ELASTIC_PORT'];
		$kibanaPort = JsConstants::$kibana['KIBANA_PORT'];
		$indexName = KibanaEnums::$FILEBEAT_INDEX.$currdate;
		$query = KibanaEnums::$KIBANA_SEARCH_QUERY;
		// in hours
		$interval = KibanaEnums::$KIBANA_ALERT_EMAIL_INTERVAL;
		$intervalString = '-'.$interval.' hour';
		$toInt = date('H:i:s');
		$fromInt = date('H:i:s',strtotime($intervalString));
		$threshold = KibanaEnums::$KIBANA_ALERT_EMAIL_THRESHOLD;
		$timeout = KibanaEnums::$KIBANA_REQUEST_THRESHOLD;
		$dashboard = 'Common-Dash';
		$msg = '';
		$from = "jsissues@jeevansathi.com";
		$subject = "Kibana Module Alert";
		$urlToHit = $elkServer.':'.$elkPort.'/'.$indexName.'/'.$query;

		// parameters required, log type of Error and get all module counts in the specified interval
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
			              "gt"=> "now-".$interval."h",
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
			        [ "field" => "moduleName" ,  "size" => 1000 ]
			    ]
			  ]
			]
			]
		];
		// send curl request
		$response =  CommonUtility::sendCurlPostRequest($urlToHit, json_encode($params), $timeout);
		if($response)
		{
			// Default timezone for Elastic is UTC
			date_default_timezone_set('UTC');
			$arrResponse = json_decode($response, true);
			$arrModules = array();
			// Get module count for each module
			foreach($arrResponse['aggregations']['filtered']['modules']['buckets'] as $module)
			{
			    $arrModules[$module['key']] = $module['doc_count']; 
			}
			$to = "jsissues@jeevansathi.com";
			// Kibana Url for the dashboard in the specified interval
			$kibanaUrl = 'http://'.$elkServer.":".$kibanaPort."/app/kibana#/dashboard/".$dashboard."?_g=(refreshInterval:(display:Off,pause:!f,value:0),time:(from:'".date('Y-m-d')."T".date('H:i:s', strtotime($intervalString)).".000Z',mode:absolute,to:'".date('Y-m-d')."T".date('H:i:s').".000Z'))";
			foreach ($arrModules as $key => $value)
			{
				if($value > $threshold)
				{
					$msg .= $key." has encountered ".$value." errors.\n";
				}
			}
			if($msg != '')
			{
				$msg = "In the interval ".$fromInt." - ".$toInt." with threshold of ".$threshold."\n\n".$msg."\n\n Kibana Url: ".$kibanaUrl;
			}
		}
		else
		{
			// ElasticSearch is unreachable
			$to = "nikhil.mittal@jeevansathi.com";
			$msg = 'ELK stack Unreachable.Plese look into the matter.';
		}
		SendMail::send_email($to,$msg,$subject,$from,'','','','','','','','nikhil.mittal@jeevansathi.com');
	}
}