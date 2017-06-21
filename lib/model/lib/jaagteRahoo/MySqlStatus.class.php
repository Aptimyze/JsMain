<?php
class MysqlStatus
{
	private $serverConfig;
	static $thresholdValue = array("master"=>300,"masterRO"=>680,"shard1"=>350,"shard2"=>350,"shard3"=>350,"viewSimilar"=>300,"bmsSlave"=>350,"alertsSlave"=>300,"masterRep"=>600,"shard1Rep"=>300,"shard2Rep"=>300,"shard3Rep"=>300,"shard1Slave"=>100,"shard2Slave"=>100,"shard3Slave"=>100);
        public function __construct()
        {
		$user = "js_db_monitor";
		$password = "jsdbm0n1t0R";
		$this->serverConfig[]=array("master",MysqlDbConstants::$master["HOST"],$user,$password,MysqlDbConstants::$master["PORT"],"threshold"=>self::$thresholdValue['master']);
		$this->serverConfig[]=array("shard1",MysqlDbConstants::$shard1["HOST"],$user,$password,MysqlDbConstants::$shard1["PORT"],"threshold"=>self::$thresholdValue['shard1']);
		$this->serverConfig[]=array("shard2",MysqlDbConstants::$shard2["HOST"],$user,$password,MysqlDbConstants::$shard2["PORT"],"threshold"=>self::$thresholdValue['shard2']);
		$this->serverConfig[]=array("shard3",MysqlDbConstants::$shard3["HOST"],$user,$password,MysqlDbConstants::$shard3["PORT"],"threshold"=>self::$thresholdValue['shard3']);
		$this->serverConfig[]=array("shard1Slave",MysqlDbConstants::$shard1Slave["HOST"],$user,$password,MysqlDbConstants::$shard1Slave["PORT"],"threshold"=>self::$thresholdValue['shard1Slave']);
		$this->serverConfig[]=array("shard2Slave",MysqlDbConstants::$shard2Slave["HOST"],$user,$password,MysqlDbConstants::$shard2Slave["PORT"],"threshold"=>self::$thresholdValue['shard2Slave']);
		$this->serverConfig[]=array("shard3Slave",MysqlDbConstants::$shard3Slave["HOST"],$user,$password,MysqlDbConstants::$shard3Slave["PORT"],"threshold"=>self::$thresholdValue['shard3Slave']);
		$this->serverConfig[]=array("alertsSlave",MysqlDbConstants::$alertsSlave["HOST"],$user,$password,MysqlDbConstants::$alertsSlave["PORT"],"threshold"=>self::$thresholdValue['alertsSlave']);
	}
	public function getStatus()
	{
		foreach($this->serverConfig as $i=>$serverDetails)
		{
                        $serverName = $serverDetails[0];
			$db = @mysql_connect($serverDetails[1] . ":" . $serverDetails[4],$serverDetails[2],$serverDetails[3]);
                        $res=mysql_query("SELECT * FROM information_schema.processlist ORDER BY TIME DESC",$db);
			if(!$res)
                        {
				$serverData[$serverName]['TOTAL_COUNT']= "fail gaya" ;
				$serverData[$serverName]['FLAG']=0;
                        }
                        else
                        {
                                $info = $this->getQueryInfo($res);
                                $serverData[$serverName]["SLEEP_COUNT"] = $info['SLEEP_COUNT'];
                                unset($info['SLEEP_COUNT']);
                                $serverData[$serverName]["QUERIES"] = $info;
                                $connectionCount = $serverData[$serverName]['SLEEP_COUNT'] + count($info);
                                $flag = $this->checkThreshold($connectionCount,$serverName,$serverDetails['threshold']);
				$serverData[$serverName]['TOTAL_COUNT']=$connectionCount ;
				$serverData[$serverName]['FLAG']=$flag;
                        }

		}
		return $serverData;
	}
        private function getQueryInfo($res)
        {
                $sleepCount=0;
                while($row=@mysql_fetch_row($res))
                {
                        if($row[4]=="Sleep")
                                $sleepCount++;
                        else
                                $queryInfo[]=implode("|\t",$row);
                }
                $queryInfo['SLEEP_COUNT']=$sleepCount;
                return $queryInfo;
        }
	private function checkThreshold($connectionCount,$serverName,$thresholdValue)
	{
		if($thresholdValue <= $connectionCount)
		{
			return false;
		}
		return true;
	}
}
