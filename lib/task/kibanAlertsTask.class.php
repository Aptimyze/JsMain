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
		$indexName = 'filebeat-'.$currdate;
		$query = '_search';
		$interval = "1h";
		$threshold = 50;
		$urlToHit = $elkServer.':'.$elkPort.'/'.$indexName.'/'.$query;
		$params = [
			"query"=> [
				"range" => [
					"@timestamp" => [
						"gt" => "now-".$interval,
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
		$response = CommonUtility::sendCurlPostRequest($urlToHit,json_encode($params));
		$arrResponse = json_decode($response, true);
		$arrModules = array();
		foreach($arrResponse['aggregations']['modules']['buckets'] as $module) 
		{
		    $arrModules[$module['key']] = $module['doc_count']; 
		}
		// $to = "jsissues@jeevansathi.com";
		$to = "jsissues@jeevansathi.com";
		$from = "jsissues@jeevansathi.com";

		$msg = '';
		$kibanaUrl = "http://10.10.18.66:5601/app/kibana#/dashboard/Common-Dash";
		$subject = "Kibana Module Alert";
		foreach ($arrModules as $key => $value)
		{
			if($value > $threshold)
			{
				$msg .= $key." has encountered ".$value." errors.\n";
			}
		}
		if($msg != '')
		{
			$msg = "In the interval of ".$interval." with threshold of ".$threshold."\n\n".$msg."\n\n Kibana Url: ".$kibanaUrl;
		}

		SendMail::send_email($to,$msg,$subject,$from,'','','','','','','','nikhil.mittal@jeevansathi.com');
	}
}
