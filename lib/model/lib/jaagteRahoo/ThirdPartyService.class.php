<?php
/*
 * 
 */
class ThirdPartyService
{
	
	public function __construct()
	{
		
	}
	
	public static function checkSolr()
	{
		$today    = date("Y-m-d");
		$hh = date("h:m:s");
		$yesterday = date("Y-m-d",strtotime("-3 day",strtotime($today)));
		$daily = "&fq=ENTRY_DT:[".$yesterday."T00:00:00Z%20".$today."T".$hh."Z]";
		$ips = ThirdPartyConfig::getValue("SOLR","IP");
		$url= ThirdPartyConfig::getValue("SOLR","URL");
		foreach($ips as $k=>$v){
			$hitUrl=$v.$url.$daily;
			$res = CommonUtility::sendCurlPostRequest($hitUrl,'nouse=1',"100");
			$res = json_decode($res,true);
			$status = $res["responseHeader"]["status"];
		        $solr[$k]["status"]= ($status===0)?"Success":"Fail";
		        $solr[$k]["responseTime"] = $res["responseHeader"]["QTime"];
		}
		return $solr;
	}
	
	public static function checkGuna()
	{
		$startTime = microtime();
		$url= ThirdPartyConfig::getValue("GUNA","URL");
		$timeout = ThirdPartyConfig::getValue("THRESHHOLDS","TIMEOUT","GUNA");
		$res =CommonUtility::sendCurlGetRequest($url,$timeout);
		$timeConsumed = microtime()-$startTime;
		$guna["status"] = ($res!="")?"Success":"Fail";
		$guna["responseTime"] = $timeConsumed;
		return $guna;
	}
	
	public static function checkRedis()
	{
		$startTime = microtime();
		$cachObj = new JsMemcache();
		$cachObj->set("JaagteRaho",array("25315231","dsdhdfjshf","yerwyrrw"));
		$value = $cachObj->get("JaagteRaho");
		$timeConsumed = microtime()-$startTime;
		$redis["status"] = (is_array($value))?"Success":"Fail";
		$redis["responseTime"] = $timeConsumed;
		return $redis;
	}
	
	public static function checkRabbitMq()
	{
		$startTime = microtime();
		$producerObj=new Producer();
		$rabbitmq["status"] = ($producerObj->getRabbitMQServerConnected())?"Success":"Fail";
		$timeConsumed = microtime()-$startTime;
		$rabbitmq["responseTime"] = $timeConsumed;
		return $rabbitmq;
	}
	
	public static function javaService($url,$pid="")
	{
		if($pid)
		{
	        $WebAuthentication = new WebAuthentication;
	        $x = $WebAuthentication->setPaymentGatewayAuthchecksum($pid);
	        $auth = $x["AUTHCHECKSUM"];
	        $header = array("JB-Profile-Identifier:".$auth);
	    }
        $start_tm=microtime(true);
        $response = CommonUtility::sendCurlPostRequest($url,"","",$header);
        $timeConsumed=microtime(true)-$start_tm;
        $data = (Array)json_decode($response);
        $service["status"] = ($data["header"]->status==200)?"Success":"Fail";
        $service["responseTime"] = $timeConsumed;
        return $service;
	}
	
	public static function callJavaServices()
	{
		$NeedAuth = Array("Listings","Auth");
		$urls["JsPresence_67"]["url"]="http://10.10.18.67:8290/profile/v1/presence";
		$urls[]="";
		$urls[]="";
		$urls[]="";
		foreach($urls as $k=>$v)
		{
			if(in_array($NeedAuth,$k))
				$pid = 9061321;
			else
				$pid ="";
			$Services[$k] = self::javaService($v["url"],$pid);
		}
		return $Services;
	}
	
}
