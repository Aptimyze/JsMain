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

	[php symfony ConsumerKibanaAlert|INFO]
EOF;
	}

	protected function execute($arguments = array(), $options = array())
	{

		$currdate = date('Y.m.d');
		// Server at which ElasticSearch and kibana is running
		$elkServer = '10.10.18.66';
		$elkPort = '9200';
		$kibanaPort = '5601';
		$indexName = 'consumer-'.$currdate;
		$query = '_search';
		// in hours
		$interval = 1;
		$intervalString = '-'.$interval.' hour';
		$threshold = 100;
		$timeout = 5000;
		$dashboard = 'Consumer_new_dashboard';
		$msg = '';
		$from = "jsissues@jeevansathi.com";
		$subject = "Kibana Module Alert";
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
			// $to = "jsissues@jeevansathi.com";
			$to = "kumar.ashok@jeevansathi.com";
			$to = "nikhil.mittal@jeevansathi.com";
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
				$msg = "In the interval of ".$interval." hour with threshold of ".$threshold."\n\n".$msg."\n\n Kibana Url: ".$kibanaUrl;
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
