<?php
/** 
* All the Memcache related operations are peformed through this class.
* It will decrease database load as data will be fetched from memory.
* @author Lavesh Rawat
* @copyright Copyright 2008, Infoedge India Ltd.
*/

include_once(JsConstants::$cronDocRoot.'/plugins/predis-1.1/autoload.php');
class UserMemcache extends Memcache 
{
	private $memConns = array();

	public function __construct()
	{
		if(self::isRedis())
		{
			try
			{
				if(JsConstants::$memoryCachingSystem=='redis')
				{
					$this->client = new Predis\Client(JsConstants::$ifSingleRedis);
				}
				elseif(JsConstants::$memoryCachingSystem=='redisSentinel')
				{
					$sentinels = JsConstants::$redisSentinel;
					$options   = ['replication' => 'sentinel', 'service' => 'mymaster'];
					$this->client = new Predis\Client($sentinels, $options);
				}
				else
				{
					$cluster = JsConstants::$redisCluster;
					$options = ['cluster' => 'redis'];
					$this->client = new Predis\Client($cluster, $options);
				}
			}
			catch (Exception $e) {	
				$this->client = NULL;
				jsException::log("CC-redisClusters".$e->getMessage());
	                }
		}
		else
		{
			$this->memdbHost=JsConstants::$memcache[HOST];
			$this->memport=JsConstants::$memcache[PORT];
			$this->memCompression=0;
			$this->memExpiry=3600; 
		}
	}

	/**
        * This function check if we are using redis server
        */
        private static function isRedis()
        {
		if(in_array(JsConstants::$memoryCachingSystem,array('redis','redisCluster','redisSentinel')))
                	return true;
        }


	public function memConnect($memdbHost="",$portName="")
	{
		if(self::isRedis())
		{
		}
		else
		{
			if(!$memdbHost)
				$memdbHost=$this->memdbHost;
			if(!$portName)
				$portName=$this->memport;

			if(!isset($this->memConns[$memdbHost]))
			{
				if($memdbHost==JsConstants::$localHostIp)
					$this->memConns[$memdbHost]=$this->connect('unix:///tmp/memcached.sock',0);
				else
					$this->memConns[$memdbHost]=$this->connect($memdbHost,$portName);
			}
			$this->activeMemcache= $this->memConns[$memdbHost];
			return $this->activeMemcache;
		}
	}

	public function logServerProfileMapping($profileid,$myDbId,$memdbHost="",$portName="")
	{
		if(self::isRedis())
		{
			$profiledDbServer=$profileid."DbServer";
			$this->set($profiledDbServer,$myDbId);
		}
		else
		{
			$memcacheObj=$this->memConnect($memdbHost,$portName);
			if($memcacheObj)
			{
				$profiledDbServer=$profileid."DbServer";
				$this->set($profiledDbServer,$myDbId);
			}
		}
	}

	public function getServerProfileMapping($profileid,$memdbHost="",$portName="")
	{
		if(self::isRedis())
		{
			$profiledDbServer=$profileid."DbServer";
			return $this->get($profiledDbServer);
		}
		else
		{
			$memcacheObj=$this->memConnect($memdbHost,$portName);
			if($memcacheObj)
			{
				$profiledDbServer=$profileid."DbServer";
				return $this->get($profiledDbServer);
			}
		}
		return 0;
	}
	public function getDataFromMem($key)
	{
		if(self::isRedis())
		{
			return $this->get($key);
		}
		else
		{
			$memcacheObj=$this->memConnect($memdbHost,$portName);
			if($memcacheObj)
			{
				return $this->get($key);
			}
		}
		return '';
	}

	public function setDataToMem($value,$key_val=0,$time=3600)
	{
		$total_time=$time;
		if(self::isRedis())
		{
			if($key_val)

				$key=$key_val;
			else
				$key=md5($value);
			if(!$key_val)
				if(!$this->get($key))
					$this->set($key,$value,0,$total_time);
			if($key_val)
				$this->set($key,$value,0,$total_time);
			return $key;
		}
		else
		{
			$memcacheObj=$this->memConnect($memdbHost,$portName);
                	if($memcacheObj)
	                {
				if($key_val)
					$key=$key_val;
				else
					$key=md5($value);

				if(!$key_val)
					if(!$this->get($key))
	        	                	$this->set($key,$value,0,$total_time);
				if($key_val)
					$this->set($key,$value,0,$total_time);
				return $key;
        	        }
		}
		return -1;
	}

	public function setlock($key)
	{
		/* removed the function defination as file locking does not make any sense here */
	}
	public function releaselock($fp)
	{
		/* removed the function defination as file locking does not make any sense here */
	}

	public function set($key,$value,$flag="",$lifetime = NULL)
	{
	    	if(self::isRedis())
		{
			if($this->client)
			{
				try
				{
					$key = (string)$key;
					if(!$lifetime)
						$lifetime= 3600;
					$value = serialize($value);
					$this->client->setEx($key,$lifetime,$value);
				}
				catch (Exception $e)
				{	
					jsException::log("SS-redisClusters".$e->getMessage());
				}
			}
		}
		else
		{
			parent::set($key,$value,$flag,$lifetime);
		}
	}
	public function get($key,$default = NULL)
	{
	    	if(self::isRedis())
		{
			if($this->client)
			{
				try
				{
					$key = (string)$key;
					$value = $this->client->get($key);
					$value = unserialize($value);
					return $value;
				}
				catch (Exception $e)
				{	
					jsException::log("GG-redisClusters".$e->getMessage());
					return false;
				}
			}
		}
		else
			return parent::get($key);
	}
	public function remove($key)
	{
		$this->delete($key);
	}
	public function delete($key)
	{
	    	if(self::isRedis())
		{
			if($this->client)
			{
				try	
				{
					$this->client->del($key);
				}
				catch (Exception $e)
				{	
					jsException::log("DD-redisClusters".$e->getMessage());
				}
			}
		}
		else
			parent::delete($key);
	}
}
?>
