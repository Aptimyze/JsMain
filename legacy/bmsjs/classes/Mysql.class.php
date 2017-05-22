<?php
/**
* All the database related actions are peformed through this class.
* This class acts as a layer between application and database.
* @author : Sriram Viswanathan.
* @copyright : Copyright 2008 Infoedge India Ltd.
*/
class Mysql
{
	private $conName;
	private $dbName;
	private $dbHost;
	private $dbUser;
	private $dbPasswd;
	private $sql;
	private $SITE_URL;
	/**
	* This is an array used to store all the open connections.
	*/
	private $dbConns = array();
	/**
	* This variable is used to store the active connection.
	*/
	private $activeDb;

	/**
	* This is the consturctor for Mysql class.
	* This functions intializes certain variables to its default values.
	*/
	public function __construct()
	{
                $this->conName = "bms_master";
                $this->dbName = "bms2";
                $this->dbHost = MysqlDbConstants::$bms[HOST];
                $this->dbUser = MysqlDbConstants::$bms[USER];
                $this->dbPasswd = MysqlDbConstants::$bms[PASS];
                $this->IMG_URL = JsConstants::$bmsUrl;
	}

	/**
	* This function is used to establish connection to the database server.
	* A new connection is established and made active only when the requested connection is not in the $dbConns array.
	* If the requested connection is present in $dbConns array, then it is made active from the array.
	* @param string $conName Name of the connection.
	* @return mysqllink
	*/
	public function connect($conName="")
	{
		if($conName=='temp')
		{
			$http_msg=print_r($_SERVER,true);
			mail("lavesh.rawat@gmail.com","web/bmsjs/classes/Mysql.class.php","$http_msg");
		}
		else
		{
			$conName = $this->conName;
			$dbHost = $this->dbHost;
			$dbUser = $this->dbUser;
			$dbPasswd = $this->dbPasswd;
		}

		if(!isset($this->dbConns[$conName]))
			$this->dbConns[$conName] = @mysql_connect($dbHost,$dbUser,$dbPasswd) or $this->blankBanner();

		$this->activeDb = $this->dbConns[$conName];

		return $this->activeDb;
	}

        public function blankBanner()
        {
                echo("<img src=\"".$this->IMG_URL."/P/IN/zero.gif\">");
                exit;
        }

	/**
	* This function is used to select the requested database.
	* If $db parameter is specified, then database is selected on that connection,
	* otherwise database is selected in the active connection.
	* @param string $dbName Database to select.
	* @param mysqllink $db link to the mysql server.
	*/
	public function selectDb($dbName,$db="")
	{
		if(!$dbName)
			$dbName = $this->dbName;

		if(!$db)
			$db = $this->activeDb;

		return mysql_select_db($dbName,$db);
	}

	/**
	* This function runs the specified query on the speicified connection or on the active connection(if $db parameter is not specified).
	* @param string $sql The query to execute.
	* @param mysqllink $db link to the mysql server.
	* @param bool $hideBlankBanner show/hide blank banner.
	* @param bool $backend to print error (if any) in the html source.
	* @return resourceId
	*/
	public function query($sql,$db="",$hideBlankBanner="",$backend="")
	{
		if(!$db)
			$db = $this->activeDb;

		$result =  mysql_query($sql,$db);

		if($result)
			return $result;
		else
			return $this->logError($sql,$hideBlankBanner,$backend);
	}

	/**
	* This function is used to fetch the result as an array from the specified resource id.
	* @param string $result Resource Id.
	* @return array.
	*/
	public function fetchArray($result)
	{
		return mysql_fetch_array($result);
	}

	/**
	* This function is used to fetch the result as an associative array from the specified resource id.
	* @param string $result Resource Id.
	* @return array.
	*/
	public function fetchAssoc($result)
	{
		return mysql_fetch_assoc($result);
	}

	/**
	* This function is used to move to the specified position in the specified resource id.
	* @param string $result Resource Id.
	* @param int $position Posidtion to seek.
	*/
	public function dataSeek($result,$position)
	{
		return mysql_data_seek($result,$position);
	}

	/**
	* This function is used to count the number of rows in a result set.
	* @param string $result Resource Id.
	* @return int
	*/
	public function numRows($result)
	{
		return mysql_num_rows($result);
	}

	/**
	* This function returns the auto increment id value returned by the last insert query.
	* @return int.
	*/
	public function insertId()
	{
		return mysql_insert_id($this->activeDb);
	}

	/**
	* This function returns count of number of rows modified by the last update/delete/replace query.
	* @return int
	*/
	public function affectedRows()
	{
		return mysql_affected_rows($this->activeDb);
	}

	/**
	* This function returns error discription(if any) associated with a query.
	* @return string
	*/
	public function error()
	{
		return mysql_error($this->activeDb);
	}

	/**
	* This function returns error number(if any) associated with a query.
	* @return int
	*/
	public function errno()
	{
		return mysql_errno($this->activeDb);
	}

	/**
	* This function used to log the errors occuring during execution of a query.
	* @param string $sql The query for which error has to be logged.
	* @param bool $hideBlankBanner show/hide blank banner.
	* @param bool $backend to print error (if any) in the html source.
	*/
	public function logError($sql,$hideBlankBanner="",$backend="")
	{
		$errorString = "echo \"".date("Y-m-d G:i:s",time() + 37800)."\nMysql Error: ".addslashes($this->error())."\nMysql Error Number:".$this->errno()."\nSQL: $sql\nUser Agent : ".$_SERVER['HTTP_USER_AGENT']."\nReferer : ".$_SERVER['HTTP_REFERER']."\nSelf : ".$_SERVER['PHP_SELF']."\nUri : ".$_SERVER['REQUEST_URI']."\nMethod : ".$_SERVER['REQUEST_METHOD']."\n";

		$errorString .= "\" >> ".$_SERVER['DOCUMENT_ROOT']."/bmsjs/bms_logError.txt";

		passthru($errorString);

		if(!$hideBlankBanner)
			echo("<img src=\"".$this->IMG_URL."/P/IN/zero.gif\">");

		if($backend)
			echo "<!-- ".$sql.$this->error()." -->";

		exit;
	}

	/**
	* This function used to log the messages.
	* @param string $message The message to be logged.
	* @param string $sql The sql query.
	* @param bool $exitStatus exit or not.
	*/
	public function logMessages($message,$sql="",$exitStatus="")
	{
		$messageString = "echo \"".date("Y-m-d G:i:s",time() + 37800)."\nUser Agent : ".$_SERVER['HTTP_USER_AGENT']."\nReferer : ".$_SERVER['HTTP_REFERER']."\nSelf : ".$_SERVER['PHP_SELF']."\nUri : ".$_SERVER['REQUEST_URI']."\nMethod : ".$_SERVER['REQUEST_METHOD']."\n";

		if($sql)
			$messageString .= "SQL :".$sql."\n";

		$messageString .= "\" >> ".$_SERVER['DOCUMENT_ROOT']."/bmsjs/bms_logMessages.txt";

		passthru($messageString);

		if($exitStatus)
			exit;
	}
}
?>
