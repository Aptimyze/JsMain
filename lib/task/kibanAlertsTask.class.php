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
		$date = ;
		$urlToHit = "10.10.18.66:9200/filebeat-"."2016.09.22"."/_search";
		$params = [
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
		var_dump($arrModules['500']);
	}
}
