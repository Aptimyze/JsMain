<?php
include_once("CrawlerClassesCommon.php");

class CrawlerConnection
{
	static public $LAN='UP';
	static public $dataCard='DOWN';

	static public function switchConnection($connection,$connect=1)
	{
		global $errorReporting;
		switch($connection)
		{
			case 'LAN' :
				if($connect)
	                                $command="sh $_SERVER[DOCUMENT_ROOT]/crontabs/crawler/CrawlerEth0Connect.sh &";
				else
					$command="sh $_SERVER[DOCUMENT_ROOT]/crontabs/crawler/CrawlerEth0Disconnect.sh &";
                                $statusVariable="LAN";
				$maxSwitchWait='5';
                                break;

                        case 'DATA_CARD' :
				if($connect)
	                                $command="sh $_SERVER[DOCUMENT_ROOT]/crontabs/crawler/CrawlerWvdialConnect.sh > CrawlerWvdialConnect.txt &";
				else
				{
					unset($commandOutput);
					exec('ps ax | grep wvdial',$commandOutput);
					$processIdPattern="/\d{1,2}:\d{1,2}\s{0,}wvdial/";
					foreach($commandOutput as $outputLine)
					{
						preg_match($processIdPattern,$outputLine,$regs);
						if($regs[0])
						{
							$processArr=explode(" ",trim($outputLine));
							if(is_numeric($processArr[0]))
							{
								$command="kill $processArr[0]";
								echo "\n$killProcessCommand";
							}
						}
					}
				}
                                $statusVariable="dataCard";
				$maxSwitchWait='10';
                                break;

		}
		echo "\nLan is ".self::$LAN;
		echo "\ndata card is ".self::$dataCard;
		if($command)
		{
			if($connect)
				echo "\nconnecting to $connection";
			else
				echo "\ndisconnecting $connection";
			passthru($command);
		}
		sleep($maxSwitchWait);
		$switched=self::checkSwitch($connection,$connect);
		if(!$switched)
			$errorReporting["FAILED_SWITCHES"][$connection][$connect]++;
		return $switched;
	}

	static public function checkSwitch($connection,$connect=1)
	{
		switch($connection)
		{
			case 'LAN' : 
				$connectionString="eth0";
				$statusVariable="LAN";
				break;

			case 'DATA_CARD' : 
				$connectionString="ppp0";
				$statusVariable="dataCard";
				break;
		}
		$connected=0;
		unset($commandOutput);
		exec('ifconfig',$commandOutput);
		if(is_array($commandOutput) && count($commandOutput))
		{
			foreach($commandOutput as $outputLine)
			{
				unset($position);
				$position=substr($outputLine,0,4);
				if($position==$connectionString)
				{
					$connected=1;
					if($connect)
					{
						
						self::$$statusVariable='UP';
						echo "\n connected";
						return 1;
					}
				}
			}
			if(!$connect && !$connected)
			{
				$disconnected=1;
				echo "\ndisconnected";
				self::$$statusVariable='DOWN';
				return 1;
			}
			return 0;
		}
	}
}
?>
