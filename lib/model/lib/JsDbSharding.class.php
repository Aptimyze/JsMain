<?php
class JsDbSharding
{
        /**
          * This function returns the shard name (db name) to which a profileid belongs.
          * @param - $profileid - profileid for which the shard name needs to be found
          * @return - $dbName - $shard database which is to be connected.
        **/
	static public function getShardNo($profileid,$connectSlave='')
	{
		if(!$connectSlave)
		{
			$shard = ($profileid%3) + 1;
			$dbName = "shard".$shard."_master";
			return $dbName;
		}
		else
		{
			$shard = ($profileid%3) + 1;
			$dbName = "shard".$shard."_slave";
			return $dbName;
		}
		
	}

        /**
          * This function returns the shard names (db name) to which an array of profileids belong.
          * @param - $profileIdArr - array of profileids for which the shard names need to be found
          * @return - $dbName - an array of profileid as key and the shard name as its value.
        **/
	static public function getShardNumberForMultipleProfiles($profileIdArr,$connectSlave='')
	{
		if(!$connectSlave)
		{
			foreach($profileIdArr as $profileid)
			{
				$shard = ($profileid%3) + 1;
				$shardName = "shard".$shard."_master";
				$dbName[$shardName][$profileid] = "shard".$shard."_master";
			}
			return $dbName;
		}
		else
		{
			foreach($profileIdArr as $profileid)
			{
				$shard = ($profileid%3) + 1;
				$shardName = "shard".$shard."_slave";
				$dbName[$shardName][$profileid] = "shard".$shard."_slave";
			}
			return $dbName;
		}
	}

	/**
          * This function returns the shard name (db name) on the basis of shard no.
          * @param -  1) shard number(0 or 1 or 2) 2) optional, pass if a slave connection is required
          * @return - $dbName - shard database which is to be connected.
        **/
        public static function getShardDbName($shardNo,$connectSlave='')
        {
                $shard = $shardNo + 1;
                if(!$connectSlave)
                        $dbName = "shard".$shard."_master";
                else
                        $dbName = "shard".$shard."_slave";
                return $dbName;
        }
    static public function getShardList()
	{
		for($shard = 0;$shard<=2;$shard++)
			$shardList[] = JsDbSharding::getShardDbName($shard);
		return $shardList;
		
		
	}
}
?>
