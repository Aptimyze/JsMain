<?php
class ServerHealthEnums
{
	public static $config = array(
				"matchalertsSlave"=>array(
							"host"=>"http://ser2.jeevansathi.com/load.php",
							"loadThreshold"=>100,
							"memoryThreshold"=>10),
				"staging"=>array(
							"host"=>"http://staging.jeevansathi.com/load.php",
							"loadThreshold"=>80,
							"memoryThreshold"=>20),
					);
} 
