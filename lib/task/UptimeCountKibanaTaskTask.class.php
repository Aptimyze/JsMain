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

	[php symfony Kibana:UptimeCountKibanaTask|INFO]
EOF;
	}

	protected function execute($arguments = array(), $options = array())
	{
		$elkServer = 'aura.infoedge.com';
		$elkPort = '9203';
		$indexName = 'jeevansathiactivity';
		$query = '_search';
		$timeout = 5000;
		$dirPath = '/home/nickedes/Desktop/logs';
		$filePath = $dirPath."/Counts.log";
		$date = date('Y-m-d', strtotime('-2 day'));
		$urlToHit = $elkServer.':'.$elkPort.'/'.$indexName.'/'.$query;

		if (false === is_dir($dirPath)) {
			mkdir($dirPath,0777,true);
		}

		// parameters required, get all request code counts in the specified interval
		$params = [
			"aggs" => [
					"rcodes" => [
						"terms" => [ "field" => "RCODE" ,  "size" => 100 ],
						"aggs" => [
							"histo" => [
								"date_histogram" => [
									"field" => "ACTIVITY_DATE",
									"interval" => "day",
									"format" => "yyyy-MM-dd"
								]
							]
						]
					]
				]
		];

		// send curl request
		$response =  CommonUtility::sendCurlPostRequest($urlToHit, json_encode($params), $timeout);
		if($response)
		{
			$arrResponse = json_decode($response, true);
			$arrModules = array();
			foreach($arrResponse['aggregations']['rcodes']['buckets'] as $rcode)
			{
					foreach ($rcode['histo']['buckets'] as $dateCounts) {
						print_r($dateCounts);
						if($dateCounts['key_as_string'] == $date)
						{
							$arrModules[$rcode['key']] = $dateCounts['doc_count'];
						}
					}
			}
			$ratio = $arrModules['200']/$arrModules['500'];
			$count = array('200' => $arrModules['200'], '500' => $arrModules['500'], 'ratio' => $ratio);
			$fileResource = fopen($filePath,"a");
			fwrite($fileResource,json_encode($count)."\n");
			fclose($fileResource);
		}
	}
}
