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
include_once(JsConstants::$cronDocRoot.'/plugins/predis-1.0/autoload.php');

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
	    if(JsConstants::$memoryCachingSystem=='redis')
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
			$cluster = JsConstants::$redisCluster;
			$options = ['cluster' => 'redis'];
			$this->client = new Predis\Client($cluster, $options);
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
}
?>
