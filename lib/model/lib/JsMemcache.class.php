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
	public function set($key,$value,$lifetime = NULL,$retryCount=0,$jsonEncode='')
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
					if($jsonEncode=='1')
						$value = json_encode($value);
					else
						$value = serialize($value);
					$this->client->setEx($key,$lifetime,$value);
					if($retryCount == 1)
						jsException::log("S-redisClusters  ->".$key." -- ".$this->get($key));
				}
				catch (Exception $e)
				{
					jsException::log("S-redisClusters  ->".$key." -- ".$e->getMessage()."  ".$retryCount);
					self::$instance == null;
					self::getInstance();
					if($retryCount==0)
						$this->set($key,$value,$lifetime,1);
				}
			}
		}
		else
		{
			parent::set($key,$value,$lifetime);
		}
	}

	public function get($key,$default = NULL,$retryCount=0)
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
					self::$instance == null;
					self::getInstance();
					if($retryCount==0)
						$this->get($key,$default,1);
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
	public function remove($key, $throwException=false)
	{
		$this->delete($key,$throwException);
	}

	/**
	 * Remove $key from redis/memcache
	 */
	public function delete($key,$throwException=false)
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
					if($throwException) {
						throw $e;
					}
					jsException::log("D-redisClusters".$e->getMessage());
				}
			}
		}
		else
		{
			parent::remove($key);
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

	/**
	 * @param $key
	 * @param $arrValue
	 * @param int $expiryTime
	 * @param bool $throwException
	 * @return mixed
	 * @throws Exception
	 */
    public function setHashObject($key,$arrValue,$expiryTime=3600,$throwException = false)
    {
        if(self::isRedis())
        {
            if($this->client)
            {
                try
                {
                    $result = $this->client->hmset($key, $arrValue);
                    $this->client->expire($key, $expiryTime);
					return $result->__toString();
                }
                catch (Exception $e)
                {
					if ($throwException) {
						throw $e;
					}
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

	public function incrCount($key)
	{
		if(self::isRedis())
		{
			if($this->client)
			{
				try
				{
					return $this->client->incr($key);
				}
				catch (Exception $e)
				{
					jsException::log("HG-redisClusters incr".$e->getMessage());
				}
			}
		}
	}
        
        //this function is a wrapper for lpush function for redis
        public function lpush($key,$value)
	{
		if(self::isRedis())
		{
			if($this->client)
			{
				try
				{
					$this->client->lpush($key,$value);
				}
				catch (Exception $e)
				{
					jsException::log("Ntimes-redis push".$e->getMessage());
				}
			}
		}
	}
        
        public function pipeline()
	{
		if(self::isRedis())
		{
			if($this->client)
			{
				try
				{
					return $this->client->pipeline();
				}
				catch (Exception $e)
				{
					jsException::log("redis pipeline".$e->getMessage());
				}
			}
		}
	}
        
        public function getLengthOfQueue($key)
	{
		if(self::isRedis())
		{
			if($this->client)
			{
				try
				{
					return $this->client->llen($key);
				}
				catch (Exception $e)
				{
					jsException::log("redis getLength".$e->getMessage());
				}
			}
		}
	}
        public function hIncrBy($key, $field, $incrBy=1)
        {
                if(self::isRedis())
                {
                        if($this->client)
			{
                                try
				{
					return $this->client->hIncrBy($key, $field, $incrBy);
				}
				catch (Exception $e)
				{
					jsException::log("HG-redisClusters hincrBy".$e->getMessage());
				}
			}
		}
  }
  
  /**
   * getMultiHashByPipleline
   * @param type $arrKey
   * @return type
   */
  public function getMultiHashByPipleline($arrKey)
  {
    if(self::isRedis())
		{
			if($this->client)
			{
				try{
                                        $pipe = $this->client->pipeline();

                                        foreach($arrKey as $key) {
                                          $pipe->hgetall($key);
                                        }
                                        $arrResponse = $pipe->execute();
                                        return $arrResponse;
				}
				catch (Exception $e)
				{
					jsException::log("HG-redisClusters getMultiHashByPipleline".$e->getMessage());
				}
			}
		}
  }
  
  /**
   * getMultiHashFieldsByPipleline
   * @param type $arrKey
   * @param type $arrFields
   * @return type
   */
  public function getMultipleHashFieldsByPipleline($arrKey, $arrFields)
  {
    if(self::isRedis())
		{
			if($this->client)
			{
				try{
				          $pipe = $this->client->pipeline();
				          foreach($arrKey as $key) {
				            $pipe->hmget($key, $arrFields);
				          }
				          $arrResponse = $pipe->execute();
				          //Decorating Response same as a Mysql Response
				          $count = 0;
				          $arrOut = array();
		
				          foreach($arrKey as $key){
	
				            $arrOut[$key] = $arrResponse[$count];
				            unset($arrResponse[$count++]);
				            $iItr = 0;
				            foreach($arrFields as $k=>$v){
				              $arrOut[$key][$v] = $arrOut[$key][$iItr];
				              unset($arrOut[$key][$iItr++]);
				            }
				          }
          
				          return $arrOut;
				}
				catch (Exception $e)
				{
					jsException::log("HG-redisClusters getMultiHashFieldsByPipleline".$e->getMessage());
				}
			}
		}
  }
  
  /**
   * 
   * @param type $arrHashes
   * @param type $expiryTime
   * @param type $throwException
   * @return type
   */
  public function setMultipleHashByPipleline($arrHashes, $expiryTime=3600,$throwException = false)
  {
    if(self::isRedis())
		{
			if($this->client)
			{
				try{
				          $pipe = $this->client->pipeline();
				          foreach($arrHashes as $key=>$value) {
				        	  $pipe->hmset($key, $value);
					          $pipe->expire($key, $expiryTime);
				          }
				          $arrResponse = $pipe->execute();
          
				          return $arrResponse;
				}
				catch (Exception $e)
				{
				        if($throwException){
				            throw $e;
					}
					jsException::log("HG-redisClusters setMultipleHashByPipleline".$e->getMessage());
				}
			}
		}
  }
    
  /**
   * 
   * @param type $key
   * @param type $fields
   * @param type $throwException
   * @return type
   * @throws Exception
   */
    public function hdel($key, $fields, $throwException = false)
    {
        if (self::isRedis()) {
            if ($this->client) {
                try {
                    $response = $this->client->hdel($key, $fields);
                    return $response;
                }
                catch (Exception $e) {
                    if ($throwException) {
                        throw $e;
                    }
                    jsException::log("HG-redisClusters hdel" . $e->getMessage());
                }
            }
        }
    }

  public function getSetsAllValue($key)
  {
  	if(self::isRedis())
  	{
  		if($this->client)
  		{
  			try
  			{
  				return $this->client->smembers($key); //Gets all members (values) for the given key
  			}
  			catch (Exception $e)
  			{
  				jsException::log("HG-redisClusters".$e->getMessage());
  			}
  		}
  	}
  }

  //This function uses pipeline to save all values in arr corresponding to the given key in the redis
  //Pipleline was removed since we could add data in an array directly using a single sadd
  public function storeDataInCacheByPipeline($key,$arr,$expiryTime=3600)
  {
  	if(self::isRedis())
  	{
  		if($this->client)
  		{
  			try
  			{ 	$returnVal = $this->client->sadd($key,$arr);  				
  				$this->client->expire($key, $expiryTime);
  				return $returnVal;
  			}
  			catch (Exception $e)
  			{
  				jsException::log("HG-redisClusters".$e->getMessage());
  			}
  		}
  	}
  }

  public function deleteSpecificDataFromCache($key,$value,$expiryTime=3600)
  {
  	if(self::isRedis())
  	{
  		if($this->client)
  		{
  			try
  			{
  				$returnVal = $this->client->srem($key,$value);
  				$this->client->expire($key, $expiryTime);
  				return $returnVal;		
  			}
  			catch (Exception $e)
  			{
  				jsException::log("HG-redisClusters".$e->getMessage());
  			}
  		}
  	}
  }

  public function addDataToCache($key,$value,$expiryTime=3600)
  {
  	if(self::isRedis())
  	{
  		if($this->client)
  		{
  			try
  			{
  				$returnVal = $this->client->sadd($key,$value);
  				$this->client->expire($key, $expiryTime);
  				return $returnVal;
  			}
  			catch (Exception $e)
  			{
  				jsException::log("HG-redisClusters".$e->getMessage());
  			}
  		}
  	}
  }

  public function checkDataInCache($key,$value)
  {
  	if(self::isRedis())
  	{
  		if($this->client)
  		{
  			try
  			{
  				return $this->client->sismember($key,$value);
  			}
  			catch (Exception $e)
  			{
  				jsException::log("HG-redisClusters".$e->getMessage());
  			}
  		}
  	}
  }

  public function getCountFromCache($pidKey)
  {
  	if(self::isRedis())
  	{
  		if($this->client)
  		{
  			try
  			{
  				return $this->client->scard($pidKey);
  			}
  			catch (Exception $e)
  			{
  				jsException::log("HG-redisClusters".$e->getMessage());
  			}
  		}
  	}
  }

  public function keyExist($key)
  {
  	if(self::isRedis())
  	{
  		if($this->client)
  		{
  			try
  			{
  				return $this->client->exists($key);
  			}
  			catch (Exception $e)
  			{
  				jsException::log("HG-redisClusters".$e->getMessage());
  			}
  		}
  	}
  }

  public function getSpecificValuesFromCache($viewerKey,$profileIdArr)
  {
  	if(self::isRedis())
  	{
  		if($this->client)
  		{
  			try
  			{
  				$pipe = $this->client->pipeline();

  				foreach($profileIdArr as $k=>$value) {
  					$pipe->sismember($viewerKey,$value);
  				}
  				$resultArr = $pipe->execute();	
  				foreach($resultArr as $key=>$val)
  				{
  					if($val == 1)
  					{
  						$finalArr[$profileIdArr[$key]] = 1;
  					}
  				}
  				return $finalArr;
  			}
  			catch (Exception $e)
  			{
  				jsException::log("HG-redisClusters".$e->getMessage());
  			}
  		}
  	}
  }
  public function addKeyToSet($setName,$key)
  {
  	if(self::isRedis())
  	{
  		if($this->client)
  		{
  			try
  			{
  				$pipe = $this->client->pipeline();
                                $pipe->sAdd($setName,$key);
                                $pipe->execute();	
  			}
  			catch (Exception $e)
  			{
  				jsException::log("HG-redisClusters".$e->getMessage());
  			}
  		}
  	}
  }
  
}
?>
