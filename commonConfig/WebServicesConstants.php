<?php

/**
 * Created by PhpStorm.
 * User: Pankaj1
 * Date: 24/02/16
 * Time: 11:52 AM
 */
class WebServicesConstants
{
	public static $whiteListedIp = array("172.10.18.61","172.10.18.62","172.10.18.63","172.10.18.65","127.0.0.1");
	public static $contactUrl    = "http://ce.jeevansathi.com";
	public static $redisConf     = array("HOST"=>"127.0.0.1","PORT"=>"6379");
	public static $rabbit		 = array("HOST"=>"127.0.0.1","PORT"=>"5672","USERNAME"=>"guest","PASSWORD"=>"guest");
	public static $sentinel		 = array("HOST"=>"127.0.0.1","PORT"=>26379,"CLUSTERNAME"=>"mymaster","DOWNCLUSTER"=>"masterdown");
	public static $cacheActive   = 1; 

}
