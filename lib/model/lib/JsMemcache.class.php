<?php
/**
 * JsMemcache
 * 
 * This class handles Jeevansathi Memcache
 * Find more information in http://devjs.infoedge.com/mediawiki/index.php/Social_Project#Memcache
 * 
 * @package    jeevansathi
 * @author     Tanu Gupta / Lavesh Rawat 
 * @created    30-06-2011 / 18 May 2016
 */

/** 
* auto load of predis(plugin to connect redis from php.
*/
include_once(JsConstants::$cronDocRoot.'/plugins/predis-1.1/autoload.php');

class JsMemcache extends sfMemcacheCache{
		
	private static $instance;
	const SUCCESS = "SUCCESSPOOL";

	/**
	* When we implement Api 
	private static $defaultHeaderArr = array("responseType"=>"json","debug"=>"0");
	CONST urlApiMap = array("get"=>"GET","set"=>"SET","del"=>"DEL");
	*/

	/**
	* This function check if we are using redis server
	*/
	private static function isRedis()
	{
	    if(in_array(JsConstants::$memoryCachingSystem,array('redis','redisCluster','redisSentinel')))
		return true;
	}
		

	/**
	* This function will hit the caching web service and return the json decoded output
	* @param postParams array
        * @lifetime int
	*/
	private function getOutput($type,$postParams,$lifetime='')
	{	
		$out = CommonUtility::sendCurlPostRequest(JsConstants::$redisCachingUrl,$postParams,$lifetime,self::$defaultHeaderArr);
		$arr = json_decode($out,true);
		return $arr;
	}


	/**
	* if memcache : Loads memcache.yml settings
	* if redis : Do nothing.
	*/
	public function __construct(){
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
			jsException::log("C-redisClusters".$e->getMessage());
		}
            }
            else
	    {
		    $config = sfYaml::load ( sfConfig::get ( 'sf_config_dir' ) . DIRECTORY_SEPARATOR . 'memcache.yml' );
		    parent::initialize($config["all"]);
	    }
	}

	/**
	* If memcache/redis :  We have to create instance using this method only.
	*/
        public static function getInstance()
        {
		if(!isset(self::$instance))
		{
			$class = __CLASS__;
			self::$instance = new $class();
		}
		return self::$instance;
        }

        public function getLock($key) {

		/* removed the function defination as file locking does not make any sense here */
        }
        public function releaseLock($fp) {
		/* removed the function defination as file locking does not make any sense here */
        }
	public function set($key,$value,$lifetime = NULL)
	{
	    	if(self::isRedis())
		{
			if($this->client)
			{
				try
				{
					/**
					* When we implement Api 
					$postParams = array("del"=>1,"key"=>$key,"value"=>$value);
					$arr = self::getOutput('set',$postParams,$lifetime);
					if($arr["status"]["code"]=='200')
					{
						return true;
					}
					return false;
					*/

					/**
					* default setting is lifetime.
					*/
					if(!$lifetime)
						$lifetime= 3600;
					$key = (string)$key;
					$value = serialize($value);
					$this->client->setEx($key,$lifetime,$value);
				}
				catch (Exception $e)
				{  
					jsException::log("S-redisClusters".$e->getMessage());
				}
			}
		}
		else
		{
			parent::set($key,$value,$lifetime);
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
					/*
					* When we implement Api 
					$postParams = array("get"=>1,"key"=>$key);
					$arr = self::getOutput('get',$postParams);
					if($arr["status"]["code"]=='200' && $arr["item"]["count"]>0)
					{
						return $arr["item"]["data"][$key];
					}
					return false;
					*/
					$key = (string)$key;
					$value = $this->client->get($key);
					$value = unserialize($value);
					return $value;
				}
				catch (Exception $e)
				{ 
					jsException::log("G-redisClusters".$e->getMessage());
					return false;
				}
			}
		}
		else
		{
			return parent::get($key);
		}
	}


	/**
	* Remove $key from redis/memcache.
	* A duplicate function need to be added as in existing code people are using both remove/delete.
	*/
	public function remove($key)
	{
		$this->delete($key);
	}

	/**
	* Remove $key from redis/memcache
	*/
	public function delete($key)
	{
	    	if(self::isRedis())
		{
			if($this->client)
			{
				try
				{
					/**
					* When we implement Api 
					$postParams = array("delete"=>1,"key"=>$key);
					$arr = self::getOutput('del',$postParams);
					if($arr["status"]["code"]=='200')
					{
						return true;
					}
					return false;
					*/
					$this->client->del($key);
				}
				catch (Exception $e)
				{ 
					jsException::log("D-redisClusters".$e->getMessage());
				}
			}
		}
		else
		{
			parent::remove($key);
		}
	}

	/**
	 * @param $key
	 * @param $arrValue
	 */
	public function setHashObject($key,$arrValue)
	{
		if(self::isRedis())
		{
			if($this->client)
			{
				try
				{
					$this->client->hmset($key, $arrValue);
				}
				catch (Exception $e)
				{
					jsException::log("HS-redisClusters".$e->getMessage());
				}
			}
		}
	}

	/**
	 * @param $key
	 * @param $subKey
	 * @return mixed
	 */
	public function getHashOneValue($key,$subKey)
	{
		if(self::isRedis())
		{
			if($this->client)
			{
				try
				{
					return $this->client->hget($key, $subKey);
				}
				catch (Exception $e)
				{
					jsException::log("HG-redisClusters".$e->getMessage());
				}
			}
		}
	}

	/**
	 * @param $key
	 * @param $arrSubKey
	 * @return mixed
	 */
	public function getHashManyValue($key,$arrSubKey)
	{
		if(self::isRedis())
		{
			if($this->client)
			{
				try
				{
					return $this->client->hmget($key, $arrSubKey);
				}
				catch (Exception $e)
				{
					jsException::log("HGM-redisClusters".$e->getMessage());
				}
			}
		}
	}

	/**
	 * @param $key
	 * @return mixed
	 */
	public function getHashAllValue($key)
	{
		if(self::isRedis())
		{
			if($this->client)
			{
				try
				{
					return $this->client->hgetall($key);
				}
				catch (Exception $e)
				{
					jsException::log("HG-redisClusters".$e->getMessage());
				}
			}
		}
	}

        public function zAdd($key,$test1,$test2)
        {
                if(self::isRedis())
                {
                        if($this->client)
                        {
                                try
                                {
                                        $this->client->zAdd($key,$test1,$test2);
                                }
                                catch (Exception $e)
                                {
                                        jsException::log("D-redisClusters".$e->getMessage());
                                }
                        }
                }
        }
        public function zRange($key,$test1,$test2)
        {
                if(self::isRedis())
                {
                        if($this->client)
                        {
                                try
                                {
                                        $dataSet =$this->client->zRange($key,$test1,$test2);
					return $dataSet;
                                }
                                catch (Exception $e)
                                {
                                        jsException::log("D-redisClusters".$e->getMessage());
					return false;
                                }
                        }
                }
        }
        public function zRem($key,$value)
        {
                if(self::isRedis())
                {
                        if($this->client)
                        {
                                try
                                {
                                        $this->client->zRem($key,$value);
                                }
                                catch (Exception $e)
                                {
                                        jsException::log("D-redisClusters".$e->getMessage());
                                }
                        }
                }
        }
        public function zRangeByScore($key,$test1,$test2)
        {
                if(self::isRedis())
                {
                        if($this->client)
                        {
                                try
                                {
                                        $dataSet =$this->client->zRangeByScore($key,$test1,$test2);
					return $dataSet;
                                }
                                catch (Exception $e)
                                {
                                        jsException::log("D-redisClusters".$e->getMessage());
                                }
                        }
                }
        }
        public function zRemRangeByScore($key,$test1,$test2)
        {
                if(self::isRedis())
                {
                        if($this->client)
                        {
                                try
                                {
                                        $dataSet =$this->client->zRemRangeByScore($key,$test1,$test2);
                                        return $dataSet;
                                }
                                catch (Exception $e)
                                {
                                        jsException::log("D-redisClusters".$e->getMessage());
                                }
                        }
                }
        }


}
?>
