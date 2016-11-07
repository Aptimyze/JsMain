<?php

class UptimeCountKibanaTaskTask extends sfBaseTask
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

		$this->namespace        = 'Kibana';
		$this->name             = 'UptimeCountKibanaTask';
		$this->briefDescription = '';
		$this->detailedDescription = <<<EOF
The [UptimeCountKibanaTask|INFO] task does things.
Call it with:

	[php symfony UptimeCountKibanaTask|INFO]
EOF;
	}

	protected function execute($arguments = array(), $options = array())
	{
		$elkServer = 'aura.infoedge.com';
		$elkPort = '9203';
		$indexName = 'jeevansathiactivity';
		$query = '_search';
		$timeout = 5000;
		$date = date('Y-m-d', strtotime('-1 day'));

		var_dump($date);
		$urlToHit = $elkServer.':'.$elkPort.'/'.$indexName.'/'.$query;
		// parameters required, log type of Error and get all module counts in the specified interval
		$params = [
			// "query"  => [
			// 	"range" => [
			// 		"ACTIVITY_DATE" => 
			// 		[
			// 			"gte" => "$date"
			// 		]
			// 	]
			// ],
			"aggs" => [
        		"modules" => [
            		"terms" => [ "field" => "RCODE" ,  "size" => 100 ] 
            	]
			]
		];

		// send curl request
		$response =  CommonUtility::sendCurlPostRequest($urlToHit, json_encode($params), $timeout);
		var_dump($response);
		if($response)
		{
			$arrResponse = json_decode($response, true);
			$arrModules = array();
			print_r($arrResponse);
			foreach($arrResponse['aggregations']['modules']['buckets'] as $rcode)
			{
					$arrModules[$rcode['key']] = $rcode['doc_count']; 
			}
			$count = ['200' => $arrModules[200], '500' => $arrModules[500]];
			var_dump($count);
			die;
		}
	}
}
