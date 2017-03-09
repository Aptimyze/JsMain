<?php

class ConsumerKibanaAlertTask extends sfBaseTask
{
	protected function configure()
	{
		// // add your own arguments here
		// $this->addArguments(array(
		//   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
		// ));

		// // add your own options here
		// $this->addOptions(array(
		//   new sfCommandOption('my_option', null, sfCommandOption::PARAMETER_REQUIRED, 'My option'),
		// ));

		$this->namespace        = 'Alerter';
		$this->name             = 'ConsumerKibanaAlert';
		$this->briefDescription = 'Sends alerts for Consumer services and Roster logs';
		$this->detailedDescription = <<<EOF
The [ConsumerKibanaAlert|INFO] task does things.
Call it with:

	[php symfony Alerter:ConsumerKibanaAlert|INFO]
EOF;
	}

	protected function execute($arguments = array(), $options = array())
	{

		$currdate = date('Y.m.d');
		// Server at which ElasticSearch and kibana is running
		$elkServer = JsConstants::$kibana['ELK_SERVER'];
		$elkPort = JsConstants::$kibana['ELASTIC_PORT'];
		$kibanaPort = JsConstants::$kibana['KIBANA_PORT'];
		$indexName = KibanaEnums::$CONSUMER_INDEX.$currdate;
		$query = KibanaEnums::$KIBANA_SEARCH_QUERY;
		// in hours
		$interval = KibanaEnums::$CONSUMER_ALERT_EMAIL_INTERVAL;
		$intervalString = '-'.$interval.' hour';
		$toInt = date('H:i:s');
		$fromInt = date('H:i:s',strtotime($intervalString));
		$threshold = KibanaEnums::$CONSUMER_ALERT_EMAIL_THRESHOLD;
		$timeout = KibanaEnums::$CONSUMER_REQUEST_THRESHOLD;
		$dashboard = 'ConsumerDashBoard';
		$msg = '';
		$noError = 1;
		$from = "jsissues@jeevansathi.com";
		$subject = "Consumer alert";
		$urlToHit = $elkServer.':'.$elkPort.'/'.$indexName.'/'.$query;

		// parameters required, log type of Error and get all module counts in the specified interval
		$params = [
			"query"=> [
					"match" => ["logLevel"=>"error"]
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
							[ "field" => "source" ,  "size" => 1000 ]
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
			// Get count for each source type
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
				if ($value > 0)
				{
					$noError = 0;
				}
			}
			if($msg != '')
			{
				$msg = "In the interval ".$fromInt." - ".$toInt." with threshold of ".$threshold."\n\n".$msg."\n\n Kibana Url: ".$kibanaUrl;
			}
			else if($noError)
			{
				$msg = "In the interval ".$fromInt." - ".$toInt." , no errors were logged.\n";
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
