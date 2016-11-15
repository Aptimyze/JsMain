<?php
/*
 *	A Symfony task which gets the counts of 200 and 500 requests over a period of time
 *  and calculates the uptime ratio and log this information.
 */
class UptimeCountKibanaTaskTask extends sfBaseTask
{
	protected function configure()
	{
		$this->namespace        = 'Kibana';
		$this->name             = 'UptimeCountKibanaTask';
		$this->briefDescription = '';
		$this->detailedDescription = <<<EOF
The [UptimeCountKibanaTask|INFO] task calculates the uptime and logs it.
Call it with:

	[php symfony Kibana:UptimeCountKibanaTask|INFO]
EOF;
	}

	protected function execute($arguments = array(), $options = array())
	{
		$elkServer = 'aura.infoedge.com';
		$elkPort = '9203';
		$indexName = 'jeevansathiactivity';
		$query = '_count';
		$timeout = 5000;
		$dirPath = '/home/nickedes/Desktop/logs';
		$filePath = $dirPath."/Counts.log";
		$interval = 24;
		$date = date('Y-m-d', strtotime('-1 day'));
		$urlToHit = $elkServer.':'.$elkPort.'/'.$indexName.'/'.$query;

		if (false === is_dir($dirPath)) {
			mkdir($dirPath,0777,true);
		}
		$rcode200 = "200";
		$rcode500 = "500";
		// parameters required, get all request code counts in the specified interval
		$params = [

			"query"=> [
				"filtered"=> [
				  "query"=> [
					"match"=> [ "RCODE" => $rcode200 ]
				  ],
				  "filter"=> [
					"range"=> [ "ACTIVITY_DATE"=>
							[
								"gte" => "now-".$interval."h",
								"lt" => "now",
							]
				  ]
				]
			  ]
		]];

		// send curl request
		$response200 =  CommonUtility::sendCurlPostRequest($urlToHit, json_encode($params), $timeout);
		$params = [

			"query"=> [
				"filtered"=> [
				  "query"=> [
					"match"=> [ "RCODE" => $rcode500 ]
				  ],
				  "filter"=> [
					"range"=> [ "ACTIVITY_DATE"=> 
							[
								"gte" => "now-".$interval."h",
								"lt" => "now",
							]
				  ]
				]
			  ]
		]];
		// send curl request
		$response500 =  CommonUtility::sendCurlPostRequest($urlToHit, json_encode($params), $timeout);
		if($response200 && $response500)
		{
			$arrModules = array();
			$arrModules[$rcode200] = json_decode($response200, true)['count'];
			$arrModules[$rcode500] = json_decode($response500, true)['count'];
			$ratio = $arrModules[$rcode200]/$arrModules[$rcode500];
			$count = array($rcode200 => $arrModules[$rcode200], $rcode500 => $arrModules[$rcode500], 'ratio' => $ratio);
			$fileResource = fopen($filePath,"a");
			fwrite($fileResource,json_encode($count)."\n");
			fclose($fileResource);
		}
	}
}
