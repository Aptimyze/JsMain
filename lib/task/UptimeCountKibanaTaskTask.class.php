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
The [UptimeCountKibanaTask|INFO] task calculates the uptime ratio and logs it.
Call it with:

	[php symfony Kibana:UptimeCountKibanaTask|INFO]
EOF;
	}

	protected function execute($arguments = array(), $options = array())
	{
		$elkServer = 'aura.infoedge.com';
		// server at which data will be pushed
		$indexElkServer = '10.10.18.66';
		$elkPort = '9203';
		$indexName = 'jeevansathiactivity';
		$query = '_search';
		$timeout = 5000;
		$currDate = date('Y.m.d');
		$dirPath = '/data/applogs';
		$filePath = $dirPath."/UptimeCounts.log";
		$interval = 24;
		$date = date('Y-m-d', strtotime('-1 day'));
		$urlToHit = $elkServer.':'.$elkPort.'/'.$indexName.'/'.$query;

		if (false === is_dir($dirPath)) {
			mkdir($dirPath,0777,true);
		}
		$rcode200 = "200";
		$rcode500 = "500";
		// Calculates the aggregated sum of counts of Rcodes
		$params = [
			"query" => [
				"filtered" => ["query" => ["query_string" => [
					"query" => "*",
					"analyze_wildcard" => true ]],
					"filter" => ["bool"=>["must"=>[["range"=> [
						"ACTIVITY_DATE"=> [ "gte"=> "now-".$interval."h", "lte"=> "now"]]]],
				]]]],
				"aggs"=> [
					"2"=> [
						"terms"=> ["field"=> "RCODE",],
					"aggs"=> [
						"1"=> ["sum"=> ["field"=> "COUNT"]]]
					]
				]
			];

		// send curl request
		$response =  CommonUtility::sendCurlPostRequest($urlToHit, json_encode($params), $timeout);
		if($response)
		{
			$arrResponse = json_decode($response, true);
			$arrModules = array();
			foreach($arrResponse['aggregations']['2']['buckets'] as $result)
			{
				// get the aggregated value of sum of counts
				$arrModules[$result['key']] = $result['1']['value'];
			}
			$ratio = $arrModules[$rcode200]/$arrModules[$rcode500];
			$count = array(
					'Date' => $date,
					$rcode200 => $arrModules[$rcode200],
					$rcode500 => $arrModules[$rcode500],
					'ratio' => intval($ratio),
					);
			$count = json_encode($count);
			$ObjectId = time();
			passthru("curl -XPOST '$indexElkServer:$elkPort/uptime/json/$ObjectId' -d'$count'");
		}
	}
}
