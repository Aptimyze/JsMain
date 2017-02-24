<?php
class ServerHealthEnums
{
	public static $config = array(
				"matchalertsSlave"=>array(
							"host"=>"http://ser2.jeevansathi.com/load.php",
							"loadThreshold"=>14,
							"memoryThreshold"=>10),
				"staging"=>array(
							"host"=>"http://staging.jeevansathi.com/load.php",
							"loadThreshold"=>12,
							"memoryThreshold"=>7),
					);
} 
