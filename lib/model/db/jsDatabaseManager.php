<?php

/**
 * jsDatabaseManager allows you to setup your database connectivity before the
 * request is handled. This eliminates the need for a filter to manage database
 * connections.
 * @author Tanu Gupta
 */
class jsDatabaseManager {
  protected $databases = array();

  private static $instance;

  /**
   * Retrieve the singleton instance of this class.
   */
  public static function getInstance() {
    if (!isset(self::$instance)) {
      $class = __CLASS__;
      self::$instance = new $class();
    }

    return self::$instance;
  }

  /**
   * Initializes this jsDatabaseManager object
   */
  private function __construct() {
    $config = array();
     if(sfConfig::get("sf_config_dir"))
      $configFile = sfConfig::get("sf_config_dir").'/js_databases.yml';
     else
     {
	global $symfonyConfigPath;
	$configFile = $symfonyConfigPath.'/js_databases.yml';
     }

      $config = array_merge($config, sfYaml::load($configFile));
    if(!$config || count($config) == 0) {
      throw new jsDatabaseException('Database configuration not found: ' . $configFile);
    }
    foreach($config as $dbName => $dbParams) {
      $factoryName = $dbParams['factory'] . 'Factory';
      $factory = new $factoryName();
      $params = $dbParams['param'];
      $database = $factory->createDatabase($params);
      $database->initialize($params);
      $this->databases[$dbName] = $database;
    }
  }

  /**
   * Retrieves the database connection associated with this jsDatabase implementation.
   *
   * @param string A database name
   *
   * @return mixed A Database instance
   *
   * @throws <b>jsDatabaseException</b> If the requested database name does not exist
   */
  public function getDatabase($name) {
    if (isset($this->databases[$name])) {
      return $this->databases[$name];
    }

    // nonexistent database name
    $error = 'Database "%s" does not exist';
    $error = sprintf($error, $name);

    throw new jsDatabaseException($error);
  }

  /**
   * Executes the shutdown procedure
   *
   * @return void
   *
   * @throws <b>jsDatabaseException</b> If an error occurs while shutting down this DatabaseManager
   */
  public function shutdown() {
    // loop through databases and shutdown connections
    foreach ($this->databases as $database) {
      $database->shutdown();
    }
  }
}
?>
