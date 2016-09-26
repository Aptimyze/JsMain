<?php

/**
 * jsPdoDatabase provides connectivity for the PDO database abstraction layer.
 */
class jsPdoDatabase extends jsDatabase {

	private $dsn;
	private $username;
	private $password;
	private $debug;
	private $reconnect;

	public function __construct($dsn, $username, $password, $reconnect = false, $debug=false) {
		$this->dsn = $dsn;
		$this->username = $username;
		$this->password = $password;
		$this->debug = $debug;
		$this->reconnect = $reconnect;
	}

	/**
   * Connects to the database.
   *
   * @throws <b>jsDatabaseException</b> If a connection could not be created
   */
	protected function connect() {
		try {
			$this->connection = new PDO($this->dsn, $this->username, $this->password);
			if($this->reconnect){
				$this->connection = new jsPdoPersistent($this->connection, $this->dsn, $this->username, $this->password, $this->reconnect, $this->debug);
			}
			if($this->debug) {
				$this->connection = new jsPdoDebug($this->connection);
			}
		} catch (PDOException $e) {
			if($this->dsn)
			{
				$dsnArr=explode(';',$this->dsn);
				$dsnHost=explode('=',$dsnArr[0]);
				if($dsnHost[1]==MysqlDbConstants::$alertsSlave['HOST']){
					jsException::log("getconnect in PDO database-->".$this->dsn.$e);
					return;	
				}
			}
			throw new jsDatabaseException($e->getMessage() . '[dsn: ' . $this->dsn . ']',$e->getCode());
		}

		// lets generate exceptions instead of silent failures
		if (defined('PDO::ATTR_ERRMODE')) {
			$this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		} else {
			$this->connection->setAttribute(PDO_ATTR_ERRMODE, PDO_ERRMODE_EXCEPTION);
		}
                //Increase the session time out for all cli's
                if(php_sapi_name() == 'cli'){
                        $query = $this->connection->prepare("set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000");
                        $query->execute();
                }
	}

	/**
   * Executes the shutdown procedure.
   *
   * @return void
   *
   * @throws <b>jsDatabaseException</b> If an error occurs while shutting down this database
   */
	public function shutdown() {
		$this->connection = null;
	}
}


class jsPdoDebug {
	private $delegate;

	public function __construct($pdo) {
		$this->delegate = $pdo;
	}

	public function prepare($statement, array $driver_options=array()) {
		return new jsPdoStatementDebug($this->delegate->prepare($statement, $driver_options));
	}

	public function __call($method, $args) {
		if($method === 'exec' || $method === 'query') {
			try {
				return call_user_func_array(array($this->delegate , $method), $args);
			} catch (PDOException $e) {
				//Logging manager added for pdo as well
				LoggingManager::getInstance()->logThis(LoggingEnums::LOG_DEBUG, $e,array(LoggingEnums::MESSAGE=>'jsDatabase PDO error in ' . $method . '(\'' . $args[0] . '\', ...) [Error message: ' . $e->getMessage() . ']'));
			
				throw $e;
			}
		}
		return call_user_func_array(array($this->delegate , $method), $args);
	}
}

class jsPdoStatementDebug {
	private $delegate;
	private $boundValues = array();

	public function __construct($pdoStatement) {
		$this->delegate = $pdoStatement;
	}

	public function execute(array $input_parameters=null) {
		try {
			return $this->delegate->execute($input_parameters);
		} catch(PDOException $e) {
			LoggingManager::getInstance()->logThis(LoggingEnums::LOG_DEBUG, $e,array(LoggingEnums::MESSAGE=>'jsDatabase PDOStatement execute error: [Query: ' . $this->delegate->queryString . '] [Bound values: ' . $this->boundValues($input_parameters) . '] [Error message: ' . $e->getMessage() . ']'));		
			throw $e;
		}
	}

	private function boundValues($input_parameters) {
		$boundValues = count($input_parameters) ? $input_parameters : $this->boundValues;
		$result = '';
		foreach($boundValues as $key => $value) {
			$result .= "{{$key}: $value}";
		}
		return $result;
	}

	public function __call($method, $args) {
		if($method === 'bindParam' || $method === 'bindValue') {
			$this->boundValues[$args[0]] = $args[1];
		}
		return call_user_func_array(array($this->delegate , $method), $args);
	}
}

class jsPdoPersistent {
	private $delegate;
	private $dsn;
	private $username;
	private $password;
	private $debug;
	private $reconnect;
	private $conAttributes;

	public function __construct($pdo, $dsn, $username, $password, $reconnect, $debug) {
		$this->dsn = $dsn;
		$this->username = $username;
		$this->password = $password;
		$this->debug = $debug;
		$this->reconnect = $reconnect;
		$this->delegate = $pdo;
		$this->conAttributes = array();
	}

	public function prepare($statement, array $driver_options=array()) {
		return new jsPdoStatementPersistent($this->delegate->prepare($statement, $driver_options), $this->dsn, $this->username, $this->password, $this->reconnect, $this->debug, $statement, $driver_options);
	}

	public function __call($method, $args) {
		if($method === 'exec' || $method === 'query') {
			try {
				return call_user_func_array(array($this->delegate , $method), $args);
			} catch (PDOException $e) {
				LoggingManager::getInstance()->logThis(LoggingEnums::LOG_ERROR, $e);
				$error = $this->delegate->errorInfo();
				if($error[1] == 2006 || $error[1] == 2013)
				{
					try{
						LoggingManager::getInstance()->logThis(LoggingEnums::LOG_DEBUG, $e,array(LoggingEnums::MESSAGE=>'jsDatabase PDOPersistent  error: [dsn: ' . $this->dsn . '] [Error message: ' . $error[2] . ']'));	
						$pdoDatabase = new jsPdoDatabase($this->dsn, $this->username, $this->password, $this->reconnect, $this->debug);
						$this->delegate = $pdoDatabase->getConnection();
						$this->setAttributes();
						return call_user_func_array(array($this->delegate , $method), $args);
					}
					catch(PDOException $pe)
					{  LoggingManager::getInstance()->logThis(LoggingEnums::LOG_ERROR, $pe);
						throw $pe;
					}
				}
				else
				{
					throw $e;
				}
			}
		}
		else if ($method === 'setAttribute')
		{
			$this->conAttributes[$args[0]] = $args[1];
		}
		return call_user_func_array(array($this->delegate , $method), $args);
	}

	private function setAttributes()
	{
		foreach($this->conAttributes as $key=>$val)
		{
			$this->delegate->setAttribute($key, $val);
		}
	}

}

class jsPdoStatementPersistent {
	private $delegate;
	private $dsn;
	private $username;
	private $password;
	private $debug;
	private $reconnect;
	private $statement;
	private $driver_options;

	private $bindedValues = array();
	private $bindedParams = array();
	private $bindedColumns = array();
	private $stmtAttributes = array();
	private $fetchmode = '';

	public function __construct($pdoStatement, $dsn, $username, $password, $reconnect, $debug, $statement, $driver_options) {
		$this->delegate = $pdoStatement;
		$this->dsn = $dsn;
		$this->username = $username;
		$this->password = $password;
		$this->debug = $debug;
		$this->reconnect = $reconnect;
		$this->statement = $statement;
		$this->driver_options = $driver_options;
	}

	public function execute(array $input_parameters=null) {
		try {
			return $this->delegate->execute($input_parameters);
		} catch(PDOException $e) {
			$error = $this->delegate->errorInfo();
			if($error[1] == 2006 || $error[1] == 2013)
			{
				try{
					LoggingManager::getInstance()->logThis(LoggingEnums::LOG_DEBUG, $e,array(LoggingEnums::MESSAGE=>'jsDatabase PDOStatementPersistent  error: [dsn: ' . $this->dsn . '] [Error message: ' . $error[2] . '] [Query: '.$this->statement . ']'));
					$pdoPersistent = new jsPdoDatabase($this->dsn, $this->username, $this->password, $this->reconnect, $this->debug);
					$this->delegate = $pdoPersistent->getConnection()->prepare($this->statement, $this->driver_options);
					$this->setAttributes();
					$this->setFetchMode();
					$this->boundValues();
					$this->boundParams();
					$this->boundColumns();
					return $this->delegate->execute($input_parameters);
				}
				catch(PDOException $pe)
				{
					LoggingManager::getInstance()->logThis(LoggingEnums::LOG_ERROR, $pe);
					throw $pe;
				}
			}
			else
			{
				LoggingManager::getInstance()->logThis(LoggingEnums::LOG_ERROR, $e);
				throw $e;
			}
		}
	}

	public function __call($method, $args) {
		if($method === 'bindParam')
		{
			$this->bindedParams[$args[0]] = array($args[1], $args[2], $args[3], $args[4]);
		}
		elseif($method === 'bindColumn')
		{
			$this->bindedColumns[$args[0]] = array($args[1], $args[2], $args[3], $args[4]);
		}
		elseif($method === 'bindValue')
		{
			if(isset($args[2]))
				$this->bindedValues[$args[0]] = array($args[1], $args[2]);
			else
				$this->bindedValues[$args[0]] = array($args[1]);
		}
		elseif($method === 'setAttribute')
		{
			$this->stmtAttributes[$args[0]] = $args[1];
		}
		elseif($method === 'setFetchMode')
		{
			$this->fetchmode = $args[0];
		}
		return call_user_func_array(array($this->delegate , $method), $args);
	}

	private function boundParams()
	{
		foreach($this->bindedParams as $k => $v)
		{
			if($v[3])
			$this->delegate->bindParam($k, $v[0], $v[1], $v[2], $v[3]);
			elseif($v[2])
			$this->delegate->bindParam($k, $v[0], $v[1], $v[2]);
			elseif($v[1])
			$this->delegate->bindParam($k, $v[0], $v[1]);
			else
			$this->delegate->bindParam($k, $v[0]);
		}
	}
	private function boundColumns()
	{
		foreach($this->bindedColumns as $k => $v)
		{
			if($v[3])
			$this->delegate->bindColumn($k, $v[0], $v[1], $v[2], $v[3]);
			elseif($v[2])
			$this->delegate->bindColumn($k, $v[0], $v[1], $v[2]);
			elseif($v[1])
			$this->delegate->bindColumn($k, $v[0], $v[1]);
			else
			$this->delegate->bindColumn($k, $v[0]);
		}
	}
	private function boundValues()
	{
		foreach($this->bindedValues as $k => $v)
		{
			if($v[1])
			$this->delegate->bindValue($k, $v[0], $v[1]);
			else
			$this->delegate->bindValue($k, $v[0]);
		}
	}
	private function setAttributes()
	{
		foreach($this->stmtAttributes as $k => $v)
		{
			$this->delegate->setAttribute($k, $v);
		}
	}
	private function setFetchMode()
	{
		if($this->fetchmode)
		$this->delegate->setFetchMode($this->fetchmode);
	}
}
?>
