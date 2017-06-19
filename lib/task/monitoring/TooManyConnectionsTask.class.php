<?php

ini_set("max_execution_time",0);
ini_set("memory_limit","512M");
ini_set("mysql.connect_timeout",-1);
ini_set("default_socket_timeout",259200); // 3 days
ini_set("log_errors_max_len",0);

/**
* This will mail too many connection reason.
* @author : Lavesh Rawat
* @package Monitoring
* @since 2015-04-30
*/
class TooManyConnectionsTask extends sfBaseTask
{
        /**
        * @access private
        * @var int $m_sleep timeduration after which we will check again for too many connections
        */
	private $m_sleep = 1;


	protected function configure()
        {
                $this->addOptions(array(
                new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
             ));
          
            $this->namespace        = 'monitoring';
            $this->name             = 'TooManyConnections';
            $this->briefDescription = 'This cron runs periodically and check if there are too many connections and if yes, send mail of screenshot of connections';
            $this->detailedDescription = <<<EOF
          [php symfony monitoring:TooManyConnections]
EOF;
        }

        protected function execute($arguments = array(), $options = array())
        {
		if(!sfContext::hasInstance())
                        sfContext::createInstance($this->configuration);

		$master = @mysql_connect(MysqlDbConstants::$master["HOST"].":".MysqlDbConstants::$master["PORT"],MysqlDbConstants::$master["USER"],MysqlDbConstants::$master["PASS"]);
		mysql_query('set session wait_timeout=86400,interactive_timeout=86400,net_read_timeout=86400',$master);

		while(1)
		{
			if($masterRO)	
				mysql_close($masterRO);
			if(!$masterRO = @mysql_connect(MysqlDbConstants::$masterRO["HOST"].":".MysqlDbConstants::$masterRO["PORT"],MysqlDbConstants::$masterRO["USER"],MysqlDbConstants::$masterRO["PASS"]))
			{
				$SERVER_ARR[]=array("master",MysqlDbConstants::$masterRO["HOST"],MysqlDbConstants::$masterRO["USER"],MysqlDbConstants::$masterRO["PASS"],MysqlDbConstants::$masterRO["PORT"]);
				$SERVER_ARR[]=array("shard1",MysqlDbConstants::$shard1["HOST"],MysqlDbConstants::$shard1["USER"],MysqlDbConstants::$shard1["PASS"],MysqlDbConstants::$shard1["PORT"]);
				$SERVER_ARR[]=array("shard2",MysqlDbConstants::$shard2["HOST"],MysqlDbConstants::$shard2["USER"],MysqlDbConstants::$shard2["PASS"],MysqlDbConstants::$shard2["PORT"]);
				$SERVER_ARR[]=array("shard3",MysqlDbConstants::$shard3["HOST"],MysqlDbConstants::$shard3["USER"],MysqlDbConstants::$shard3["PASS"],MysqlDbConstants::$shard3["PORT"]);
				/*
				$SERVER_ARR[]=array("shard1Slave",MysqlDbConstants::$shard1Slave["HOST"],MysqlDbConstants::$shard1Slave["USER"],MysqlDbConstants::$shard1Slave["PASS"],MysqlDbConstants::$shard1Slave["PORT"]);
				$SERVER_ARR[]=array("shard2Slave",MysqlDbConstants::$shard2Slave["HOST"],MysqlDbConstants::$shard2Slave["USER"],MysqlDbConstants::$shard2Slave["PASS"],MysqlDbConstants::$shard2Slave["PORT"]);
				$SERVER_ARR[]=array("shard3Slave",MysqlDbConstants::$shard3Slave["HOST"],MysqlDbConstants::$shard3Slave["USER"],MysqlDbConstants::$shard3Slave["PASS"],MysqlDbConstants::$shard3Slave["PORT"]);
				*/
				$SERVER_ARR[]=array("viewSimilar",MysqlDbConstants::$viewSimilar["HOST"],MysqlDbConstants::$viewSimilar["USER"],MysqlDbConstants::$viewSimilar["PASS"],MysqlDbConstants::$viewSimilar["PORT"]);
			$SERVER_ARR[]=array("bmsSlave",MysqlDbConstants::$bmsSlave["HOST"],MysqlDbConstants::$bmsSlave["USER"],MysqlDbConstants::$bmsSlave["PASS"],MysqlDbConstants::$bmsSlave["PORT"]);
				$SERVER_ARR[]=array("alertsSlave",MysqlDbConstants::$alertsSlave["HOST"],MysqlDbConstants::$alertsSlave["USER"],MysqlDbConstants::$alertsSlave["PASS"],MysqlDbConstants::$alertsSlave["PORT"]);

				for($i=0;$i<count($SERVER_ARR);$i++)
				{
					/*
					* always master will have too many connections. So we we will use already established  connection.
					*/
					if($i==0)
						$db = $master;
					else
						$db = @mysql_connect($SERVER_ARR[$i][1] . ":" . $SERVER_ARR[$i][4],$SERVER_ARR[$i][2],$SERVER_ARR[$i][3]);
					$k = $SERVER_ARR[$i][0];

					if($i==0)
						$res=mysql_query("SHOW FULL PROCESSLIST",$master);
					else
						$res=mysql_query("SHOW FULL PROCESSLIST",$db);
					if(!$res)
					{
						$msg[$k]  = "Cant Connect";
					}
					else
					{
						$arr = self::formatMsg($res);
						$msg[$k] = $arr["message"];
						$msg1[$k] = $arr["cnt"];
						
					}
					if($i==0)	
					{
						$arr = self::formatMsg($res1);
						$k = "masterLastworked";
						$msg[$k] = $arr["message"];
						$msg1[$k] = $arr["cnt"];
					}
				}
				self::write($msg,$msg1);
				unset($msg);		
				sleep($this->m_sleep);
			}
			else
			{
				$res1 = mysql_query("SHOW FULL PROCESSLIST",$master);
			}
			//sleep($this->m_sleep);
		}
	}

	static private function write($arr,$arr1)
	{
                $orgTZ = date_default_timezone_get();
                date_default_timezone_set("Asia/Calcutta");
                
		$date = date("Y-m-d");
		$myfile = fopen(sfConfig::get("sf_upload_dir")."/errors/toomany/".$date.".txt","a+");
		$txt = date("Y-m-d h:m:s")."\n\n";
		fwrite($myfile, $txt);
		$txt = $results = print_r($arr, true);
		fwrite($myfile, $txt);
		fclose($myfile);
		
		$myfile = fopen(sfConfig::get("sf_upload_dir")."/errors/toomany/".$date."_count.txt","a+");
                $txt = date("Y-m-d h:m:s")."\n";
                fwrite($myfile, $txt);
                $txt = $results = print_r($arr1, true);
                fwrite($myfile, $txt);
                fclose($myfile);
                date_default_timezone_set($orgTZ);
	}

	static private function formatMsg($res)
	{
		$message = null;
		$count=0;
		while($row=@mysql_fetch_row($res))
		{
			$message.=implode(",\t",$row)."\n";
			$count++;
		}
		$arr["cnt"] = $count;
		$arr["message"] = $message;
		return $arr;
		//return $message;
    	}
}
