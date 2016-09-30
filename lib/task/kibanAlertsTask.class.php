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

	public function sendCurlPostRequest($urlToHit,$postParams,$timeout='',$headerArr="")
    {
        if(!$timeout)
	        $timeout = 10000;
        $ch = curl_init($urlToHit);
		if($headerArr)
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArr);
		else
            curl_setopt($ch, CURLOPT_HEADER, 0);
		if($postParams)
	                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		if($postParams)
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postParams);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, $timeout);
		curl_setopt($ch,CURLOPT_NOSIGNAL,1);
		curl_setopt($ch, CURLOPT_TIMEOUT_MS, $timeout*10);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $output = curl_exec($ch);
        if(!$output)
        {
        	var_dump(curl_error($ch));
        	var_dump(curl_errno($ch));
        	die;
        	return curl_errno($ch);
        }
	    return $output;
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
		var_dump($response);
		if($response)
		{
			$arrResponse = json_decode($response, true);
			$arrModules = array();
			foreach($arrResponse['aggregations']['modules']['buckets'] as $module) 
			{
			    $arrModules[$module['key']] = $module['doc_count']; 
			}
			$to = "jsissues@jeevansathi.com";
			$from = "jsissues@jeevansathi.com";

			$msg = '';
			$kibanaUrl = $elkServer.":".$kibanaPort."/app/kibana#/dashboard/".$dashboard."?_g=(refreshInterval:(display:Off,pause:!f,value:0),time:(from:'".date('Y-m-d')."T".date('H:i:s', strtotime($intervalString)).".000Z',mode:absolute,to:'".date('Y-m-d')."T".date('H:i:s').".000Z'))";

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
			// SendMail::send_email($to,$msg,$subject,$from,'','','','','','','','nikhil.mittal@jeevansathi.com');
			fromSendMail::send_email($to,$msg,$subject,$from,'','','','','','','','nikhil.mittal@jeevansathi.com');
		}
	}
}