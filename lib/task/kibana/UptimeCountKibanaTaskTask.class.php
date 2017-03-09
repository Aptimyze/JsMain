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
		$elkServer = JsConstants::$kibana['AURA_SERVER'];
		$elkPort = JsConstants::$kibana['AURA_PORT'];
		$indexName = KibanaEnums::$AURA_INDEX;
		$query = KibanaEnums::$KIBANA_SEARCH_QUERY;
		$timeout = KibanaEnums::$KIBANA_REQUEST_THRESHOLD;
		$interval = KibanaEnums::$UPTIME_HOUR;
		$day = KibanaEnums::$UPTIME_DAY;
		// server at which data will be pushed
		$indexElkServer = JsConstants::$kibana['ELK_SERVER'];
		$indexElkPort = JsConstants::$kibana['ELASTIC_PORT'];
		$pushIndexName = KibanaEnums::$UPTIME_INDEX;
		$appIndexName = KibanaEnums::$FILEBEAT_INDEX.'*';
		$date = date('Y-m-d', strtotime("-$day day"));
		$auraUrl = $elkServer.':'.$elkPort.'/'.$indexName.'/'.$query;
		$elkAppUrl = $indexElkServer.':'.$indexElkPort.'/'.$appIndexName.'/'.$query;
		$rcode200 = "200";
		$rcode500 = "500";
		// Calculates the aggregated sum of counts of Rcodes
		$params = [
			"query" => [
				"filtered" => ["query" => ["query_string" => [
					"query" => "*",
					"analyze_wildcard" => true ]],
					"filter" => ["bool"=>["must"=>[["range"=> [
						"ACTIVITY_DATE"=> [ "gte"=> "now-".($day*$interval)."h", "lte"=> "now-".(($day-1)*$interval)."h"]]]],
				]]]],
				"aggs"=> [
					"2"=> [
						"terms"=> ["field"=> "RCODE",],
					"aggs"=> [
						"1"=> ["sum"=> ["field"=> "COUNT"]]]
					]
				]
			];

		// Calculates 500 counts from APP logs
		$params2 = [
			"query"=> ["match" => ["logType"=>"Error"]],
			"aggs"=> ["filtered"=> [ "filter"=> [ "bool"=> ["must"=> [[
			          "range"=> [
			            "@timestamp"=> [
			              "gt"=> "now-".($day*$interval)."h",
			              "lt"=> "now-".(($day-1)*$interval)."h",]
			          ]]]]
			],
			"aggs"=> ["modules"=>["terms"=> [ 
				"field" => "moduleName" ,  "size" => 1000 ]]]]]
		];

		// send curl request
		$AuraResponse =  CommonUtility::sendCurlPostRequest($auraUrl, json_encode($params), $timeout);
		$ElkResponse =  CommonUtility::sendCurlPostRequest($elkAppUrl, json_encode($params2), $timeout);

		if($AuraResponse && $ElkResponse)
		{
			$arrResponse = json_decode($AuraResponse, true);
			$arrModules = array();
			foreach($arrResponse['aggregations']['2']['buckets'] as $result)
			{
				// get the aggregated value of sum of counts
				$arrModules[$result['key']] = $result['1']['value'];
			}

			$arrResponse = json_decode($ElkResponse, true);
			$arrRcode = array();
			foreach($arrResponse['aggregations']['filtered']['modules']['buckets'] as $result)
			{
				// get the aggregated value of counts
				$arrRcode[$result['key']] = $result['doc_count'];
			}
			$arrModules[$rcode200] -= $arrRcode[$rcode500];
			$arrModules[$rcode500] += $arrRcode[$rcode500];
			$ratio = ($arrModules[$rcode500]*100)/($arrModules[$rcode500]+$arrModules[$rcode200]);
			$count = array(
					'Date' => $date,
					$rcode200 => $arrModules[$rcode200],
					$rcode500 => $arrModules[$rcode500],
					'ratio' => $ratio,
					'total' => $arrModules[$rcode200] + $arrModules[$rcode500],
					);
			$count = json_encode($count);
			$ObjectId = time();
			exec("curl -XPOST '$indexElkServer:$indexElkPort/$pushIndexName/json/$ObjectId' -d'$count'".' 2>&1');
		}
		else
		{
			// Uptime count not pushed
			die;
		}
	}
}
