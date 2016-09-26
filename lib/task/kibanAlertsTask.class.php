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
		var_dump($currdate);
		$urlToHit = "10.10.18.66:9200/filebeat-".$currdate."/_search";
		$params = [
			"query"=> [
				"range" => [
					"@timestamp" => [
						"gt" => "now-1h",
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
		// var_dump($arrModules['500']);
		foreach ($arrModules as $key => $value) {
			echo $key.$value;
			if($value > 50)
			{
				// fire alert
			}
		}
	}
}
