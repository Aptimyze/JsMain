<?php
/**
 * JsRedis
 * 
 * This class handles Jeevansathi Redis
 * Find more information in http://devjs.infoedge.com/mediawiki/index.php/Social_Project#Redis
 * 
 * @package    jeevansathi
 * @author     Pankaj Khandelwal
 * @created    28-10-2015
 */

class JsRedis extends sfRedis{

	private static $instance;
	// Loads Redis.yml settings
	// We have to avoid using create instance from controller
	public function __construct(){
	    $config = sfYaml::load ( sfConfig::get ( 'sf_config_dir' ) . DIRECTORY_SEPARATOR . 'redis.yml' );
      parent::initialize($config["all"]);
	}

	// We have to create instance using this method only.
  public static function getInstance()
  {
    if(!isset(self::$instance))
    {
      $class = __CLASS__;
      self::$instance = new $class();
    }
    return self::$instance;
  }

        
	public function delete($key)
	{
		parent::remove($key);
	}
}
?>
