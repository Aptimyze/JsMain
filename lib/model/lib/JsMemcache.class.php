<?php
/**
 * JsMemcache
 * 
 * This class handles Jeevansathi Memcache
 * Find more information in http://devjs.infoedge.com/mediawiki/index.php/Social_Project#Memcache
 * 
 * @package    jeevansathi
 * @author     Tanu Gupta
 * @created    30-06-2011
 */

class JsMemcache extends sfMemcacheCache{

	private static $instance;
	const SUCCESS = "SUCCESSPOOL";
	// Loads memcache.yml settings
	// We have to avoid using create instance from controller
	public function __construct(){
	    $config = sfYaml::load ( sfConfig::get ( 'sf_config_dir' ) . DIRECTORY_SEPARATOR . 'memcache.yml' );
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

        public function getLock($key) {
          
          $act_key = intval($key);
          if ($act_key) {
            $rem = $act_key % 100;
            $file_name = "temp_$rem.txt";
            $fp = fopen("/tmp/$file_name", "a+");
            @chmod("/tmp/$file_name",0777);
            if ($fp) {
              return flock($fp, LOCK_EX) ? $fp : null; // check whether lock acquisition is successful
            }
            else {
              return null; // Error in opening temporary file
            }
          }
        }
        public function releaseLock($fp) {
         
          if ($fp) {
            flock($fp, LOCK_UN);
            fclose($fp);
            return true;
          }
          else {
            return false;
          }
        }
	public function delete($key)
	{
		parent::remove($key);
	}
}
?>
