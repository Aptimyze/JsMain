<?php

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

	[php symfony kibanAlerts|INFO]
EOF;
	}

	protected function execute($arguments = array(), $options = array())
	{
		$currdate = date('Y.m.d');
		$elkServer = '10.10.18.66';
		$elkPort = '9200';
		$kibanaPort = '5601';
		$indexName = 'filebeat-'.$currdate;
		$query = '_search';
		// in hours
		$interval = 1;
		$intervalString = '-'.$interval.' hour';
		$threshold = 50;
		$timeout = 5000;
		$dashboard = 'Common-Dash';
		$msg = '';
		$from = "jsissues@jeevansathi.com";
		$subject = "Kibana Module Alert";
		$urlToHit = $elkServer.':'.$elkPort.'/'.$indexName.'/'.$query;
		$params = [
			"query"=> [
				"range" => [
					"@timestamp" => [
						"gt" => "now-".$interval."h",
						"lt" => "now"
					]
				]
			],
			'aggs' => [
				'modules' => [
					'terms' => [
						'field' => 'moduleName',
						'size' => 1000
					]
				]
			]
		];
		$response =  CommonUtility::sendCurlPostRequest($urlToHit, json_encode($params), $timeout);
		// timeout checks needs to be done
		if($response)
		{
			date_default_timezone_set('UTC');
			$arrResponse = json_decode($response, true);
			$arrModules = array();
			foreach($arrResponse['aggregations']['modules']['buckets'] as $module) 
			{
			    $arrModules[$module['key']] = $module['doc_count']; 
			}
			$to = "jsissues@jeevansathi.com";

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
			$to = "nikhil.mittal@jeevansathi.com";
			$msg = 'ELK stack Unreachable.Plese look into the matter.';
		}
		SendMail::send_email($to,$msg,$subject,$from,'','','','','','','','nikhil.mittal@jeevansathi.com');
	}
}