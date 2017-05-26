<?php
/**
* This class  provides connectivity for the Mongodatabase abstraction layer
* @author : lavesh
*/
class jsMongoDatabase extends jsDatabase {

	private $dsn;
	private $username;
	private $password;
	private $debug;
	private $reconnect;

	/**
	* @dsn : mongodb server
	* @username: username
        * @password: passowrd
	* @reconnect not using.
        * @debug: not using.
	*/
	public function __construct($dsn, $username, $password,$replicaSet, $reconnect = false, $debug=false, $defaultDB='profile') {
    		$this->dsn = $dsn;
		$this->username = $username;
		$this->password = $password;
		$this->debug = $debug;
		$this->reconnect = $reconnect;
    $this->defaultDB = $defaultDB;
    $this->replicaSet = $replicaSet;
	}


	/**
	* Connection String.
	*/
	protected function connect() {
		try
		{
      if(JsConstants::$whichMachine == "dev" || JsConstants::$whichMachine == "local") {
        $this->defaultDB = "admin";
      }
      
      $this->connection = new MongoDB\Client("mongodb://$this->username:$this->password@$this->dsn,$this->dsn\\$this->defaultDB?replicaSet=$this->replicaSet&authSource=$this->defaultDB");
		} 
		catch (exception $e) 
		{
			jsException::log("can't connect()-->dsnOne::".$this->dsnOne."-->dsnTwo::".$this->dsnTwo.$e);
		}
	}

	/**
	* not in use
	*/
	public function shutdown() {
	}
}
?>
