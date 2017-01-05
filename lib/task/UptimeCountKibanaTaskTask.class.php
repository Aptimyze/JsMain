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
		// $elkServer = 'es.aura.resdex.com';
		$elkServer = 'aura.infoedge.com';
		$elkPort = '9203';
		$indexName = 'jeevansathiactivity';
		$query = '_search';
		$timeout = 5000;
		$interval = 24;
		$day = 1;
		// server at which data will be pushed
		$indexElkServer = '10.10.18.66';
		$indexElkPort = '9200';
		$pushIndexName = 'uptime';
		$appIndexName = 'filebeat-*';
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
		// $params2 = [
		// 	"query"=> [
		// 		"filtered"=> [ "query"=> [ "query_string"=> [
		// 			"analyze_wildcard"=> true,
		// 			"query"=> "*" ]],
		// 			"filter"=> ["bool"=> ["must"=> [[
		// 				"query"=> [
		// 					"match"=> [ "moduleName"=> [ "query"=> "500", "type"=> "phrase"] ]],
		// 			  "$state"=> ["store"=> "appState"]],
		// 	["range"=> [
		// 		"@timestamp"=> [
		// 		  "gte"=> "now-".($day*$interval)."h",
		// 		  "lte"=> "now-".(($day-1)*$interval)."h",
		// 		]]]],]]]],
		// 	"aggs"=> ["2"=> ["terms"=> [
		// 		"field"=> "moduleName","size"=> 100,
		// 		"order"=> ["_count"=> "desc"]]]]
		// ];
		$params2 = [
			"query"=> [
			    "match" => ["logType"=>"Error"]
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
			        [ "field" => "moduleName" ,  "size" => 1000 ]
			    ]
			  ]
			]
			]
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
			print_r($arrResponse);die;
			$arrRcode = array();
			foreach($arrResponse['aggregations']['2']['buckets'] as $result)
			{
				print_r($result);
				// get the aggregated value of counts
				$arrRcode[$result['key']] = $result['doc_count'];
			}			
			print_r($arrRcode);
			$arrModules[$rcode200] -= $arrRcode[$rcode500];
			$arrModules[$rcode500] += $arrRcode[$rcode500];
			print_r($arrModules);
			die;
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
			// exec("curl -XPOST '$indexElkServer:$indexElkPort/$pushIndexName/json/$ObjectId' -d'$count'".' 2>&1');
		}
	}
}
