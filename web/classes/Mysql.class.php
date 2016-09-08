<?php
/**
* All the database related actions are peformed through this class.
* This class acts as a layer between application and database.
* @author : Sriram Viswanathan & Shiv Narayan Gautam.
* @copyright : Copyright 2008 Infoedge India Ltd.
*/

//require_once("mysqlConnections.php");
// include wrapper for logging
include_once(JsConstants::$docRoot."/classes/LoggingWrapper.class.php");

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
	* This variable is used to store the previous connection.
	*/
	private $previousDb;

	/**
	* This is the consturctor for Mysql class.
	* This functions intializes certain variables to its default values.
	*/
	public function __construct()
	{
		$this->dbName = MysqlDbConstants::$master[DEFAULT_DB];
		$this->dbHost = MysqlDbConstants::$master[HOST].":".MysqlDbConstants::$master[PORT];
		$this->dbUser = MysqlDbConstants::$master[USER];
		$this->dbPasswd = MysqlDbConstants::$master[PASS];
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
		if($conName)
		{
			switch($conName)
			{
				case 'master' : 
					$dbHost = MysqlDbConstants::$master[HOST].":".MysqlDbConstants::$master[PORT];
					$dbUser = MysqlDbConstants::$master[USER];
					$dbPasswd = MysqlDbConstants::$master[PASS];
					$dbName = MysqlDbConstants::$master[DEFAULT_DB];
					break;
				case 'db_ro' : 
					$dbHost = MysqlDbConstants::$masterRO[HOST].":".MysqlDbConstants::$masterRO[PORT];
					$dbUser = MysqlDbConstants::$masterRO[USER];
					$dbPasswd = MysqlDbConstants::$masterRO[PASS];
					$dbName = MysqlDbConstants::$masterRO[DEFAULT_DB];
					break;
				case 'slave' : 
					$dbHost = MysqlDbConstants::$misSlave[HOST].":".MysqlDbConstants::$misSlave[PORT];
					$dbUser = MysqlDbConstants::$misSlave[USER];
					$dbPasswd = MysqlDbConstants::$misSlave[PASS];
					$dbName = MysqlDbConstants::$misSlave[DEFAULT_DB];
					break;
				case 'slave_ro' : 
					$dbHost = MysqlDbConstants::$misSlave[HOST].":".MysqlDbConstants::$misSlave[PORT];
					$dbUser = MysqlDbConstants::$misSlave[USER];
					$dbPasswd = MysqlDbConstants::$misSlave[PASS];
					$dbName = MysqlDbConstants::$misSlave[DEFAULT_DB];
					break;
				
				case '737' : 
					$dbHost = MysqlDbConstants::$bms[HOST].":".MysqlDbConstants::$bms[PORT];
					$dbUser = MysqlDbConstants::$bms[USER];
					$dbPasswd = MysqlDbConstants::$bms[PASS];
					$dbName = MysqlDbConstants::$bms[DEFAULT_DB];
					break;
				case '737_ro' : 
					$dbHost = MysqlDbConstants::$bmsSlave[HOST].":".MysqlDbConstants::$bmsSlave[PORT];
					$dbUser = MysqlDbConstants::$bmsSlave[USER];
					$dbPasswd = MysqlDbConstants::$bmsSlave[PASS];
					$dbName = MysqlDbConstants::$bmsSlave[DEFAULT_DB];
					break;
				case '211' : 
					$dbHost = MysqlDbConstants::$shard2[HOST].":".MysqlDbConstants::$shard2[PORT];
					$dbUser = MysqlDbConstants::$shard2[USER];
					$dbPasswd = MysqlDbConstants::$shard2[PASS];
					$dbName = MysqlDbConstants::$shard2[DEFAULT_DB];
					break;
				case '211Slave' :
					$dbHost = MysqlDbConstants::$shard2Slave[HOST].":".MysqlDbConstants::$shard2Slave[PORT];
					$dbUser = MysqlDbConstants::$shard2Slave[USER];
					$dbPasswd = MysqlDbConstants::$shard2Slave[PASS];
					$dbName = MysqlDbConstants::$shard2Slave[DEFAULT_DB];
					break;
				
				case '303Master' : 
					$dbHost = MysqlDbConstants::$shard3[HOST].":".MysqlDbConstants::$shard3[PORT];
					$dbUser = MysqlDbConstants::$shard3[USER];
					$dbPasswd = MysqlDbConstants::$shard3[PASS];
					$dbName = MysqlDbConstants::$shard3[DEFAULT_DB];
					break;
				case '303Slave' :
						$dbHost = MysqlDbConstants::$shard3Slave[HOST].":".MysqlDbConstants::$shard3Slave[PORT];
						$dbUser = MysqlDbConstants::$shard3Slave[USER];
						$dbPasswd = MysqlDbConstants::$shard3Slave[PASS];
						$dbName = MysqlDbConstants::$shard3Slave[DEFAULT_DB];
						break;
				case '11Master' :
						$dbHost = MysqlDbConstants::$shard1[HOST].":".MysqlDbConstants::$shard1[PORT];
						$dbUser = MysqlDbConstants::$shard1[USER];
						$dbPasswd = MysqlDbConstants::$shard1[PASS];
						$dbName = MysqlDbConstants::$shard1[DEFAULT_DB];
						break;
				case '11Slave' :
						$dbHost = MysqlDbConstants::$shard1Slave[HOST].":".MysqlDbConstants::$shard1Slave[PORT];
						$dbUser = MysqlDbConstants::$shard1Slave[USER];
						$dbPasswd = MysqlDbConstants::$shard1Slave[PASS];
						$dbName = MysqlDbConstants::$shard1Slave[DEFAULT_DB];
						break;
				case 'alerts' :
						$dbHost = MysqlDbConstants::$alerts[HOST].":".MysqlDbConstants::$alerts[PORT];
						$dbUser = MysqlDbConstants::$alerts[USER];
						$dbPasswd = MysqlDbConstants::$alerts[PASS];
						$dbName = MysqlDbConstants::$alerts[DEFAULT_DB];
						break;
				case 'viewLogSlave' :
						$dbHost = MysqlDbConstants::$viewLogSlave[HOST].":".MysqlDbConstants::$viewLogSlave[PORT];
						$dbUser = MysqlDbConstants::$viewLogSlave[USER];
						$dbPasswd = MysqlDbConstants::$viewLogSlave[PASS];
						$dbName = MysqlDbConstants::$viewLogSlave[DEFAULT_DB];
						break;
				case 'viewLogRep' : 
					$dbHost = MysqlDbConstants::$viewLogRep[HOST].":".MysqlDbConstants::$viewLogRep[PORT];
					$dbUser = MysqlDbConstants::$viewLogRep[USER];
					$dbPasswd = MysqlDbConstants::$viewLogRep[PASS];
					$dbName = MysqlDbConstants::$viewLogRep[DEFAULT_DB];
					break;
                                case '112Slave_shard1' :
                                        $dbHost = MysqlDbConstants::$shard1Slave112[HOST].":".MysqlDbConstants::$shard1Slave112[PORT];
                                        $dbUser = MysqlDbConstants::$shard1Slave112[USER];
                                        $dbPasswd = MysqlDbConstants::$shard1Slave112[PASS];
                                        $dbName = MysqlDbConstants::$shard1Slave112[DEFAULT_DB];
                                        break;
                                case '112Slave_shard2' :
                                        $dbHost = MysqlDbConstants::$shard2Slave112[HOST].":".MysqlDbConstants::$shard2Slave112[PORT];
                                        $dbUser = MysqlDbConstants::$shard2Slave112[USER];
                                        $dbPasswd = MysqlDbConstants::$shard2Slave112[PASS];
                                        $dbName = MysqlDbConstants::$shard2Slave112[DEFAULT_DB];
                                        break;
                                case '112Slave_shard3' :
                                        $dbHost = MysqlDbConstants::$shard3Slave112[HOST].":".MysqlDbConstants::$shard3Slave112[PORT];
                                        $dbUser = MysqlDbConstants::$shard3Slave112[USER];
                                        $dbPasswd = MysqlDbConstants::$shard3Slave112[PASS];
                                        $dbName = MysqlDbConstants::$shard3Slave112[DEFAULT_DB];
					break;
				case 'masterDDL' :
                                        $dbHost = MysqlDbConstants::$masterDDL[HOST].":".MysqlDbConstants::$masterDDL[PORT];
                                        $dbUser = MysqlDbConstants::$masterDDL[USER];
                                        $dbPasswd = MysqlDbConstants::$masterDDL[PASS];
                                        $dbName = MysqlDbConstants::$masterDDL[DEFAULT_DB];
                                        break;
                                case 'shard1DDL' :
                                        $dbHost = MysqlDbConstants::$shard1DDL[HOST].":".MysqlDbConstants::$shard1DDL[PORT];
                                        $dbUser = MysqlDbConstants::$shard1DDL[USER];
                                        $dbPasswd = MysqlDbConstants::$shard1DDL[PASS];
                                        $dbName = MysqlDbConstants::$shard1DDL[DEFAULT_DB];
                                        break;
				case 'shard1SlaveDDL' :
                                                $dbHost = MysqlDbConstants::$shard1SlaveDDL[HOST].":".MysqlDbConstants::$shard1SlaveDDL[PORT];
                                                $dbUser = MysqlDbConstants::$shard1SlaveDDL[USER];
                                                $dbPasswd = MysqlDbConstants::$shard1SlaveDDL[PASS];
                                                $dbName = MysqlDbConstants::$shard1SlaveDDL[DEFAULT_DB];
                                                break;
                                case 'shard2DDL' :
                                        $dbHost = MysqlDbConstants::$shard2DDL[HOST].":".MysqlDbConstants::$shard2DDL[PORT];
                                        $dbUser = MysqlDbConstants::$shard2DDL[USER];
                                        $dbPasswd = MysqlDbConstants::$shard2DDL[PASS];
                                        $dbName = MysqlDbConstants::$shard2DDL[DEFAULT_DB];
                                        break;
				case 'shard2SlaveDDL' :
                                                $dbHost = MysqlDbConstants::$shard2SlaveDDL[HOST].":".MysqlDbConstants::$shard2SlaveDDL[PORT];
                                                $dbUser = MysqlDbConstants::$shard2SlaveDDL[USER];
                                                $dbPasswd = MysqlDbConstants::$shard2SlaveDDL[PASS];
                                                $dbName = MysqlDbConstants::$shard2SlaveDDL[DEFAULT_DB];
                                                break;
                                case 'shard3DDL' :
                                        $dbHost = MysqlDbConstants::$shard3DDL[HOST].":".MysqlDbConstants::$shard3DDL[PORT];
                                        $dbUser = MysqlDbConstants::$shard3DDL[USER];
                                        $dbPasswd = MysqlDbConstants::$shard3DDL[PASS];
                                        $dbName = MysqlDbConstants::$shard3DDL[DEFAULT_DB];
                                        break;
				case 'shard3SlaveDDL' :
                                                $dbHost = MysqlDbConstants::$shard3SlaveDDL[HOST].":".MysqlDbConstants::$shard3SlaveDDL[PORT];
                                                $dbUser = MysqlDbConstants::$shard3SlaveDDL[USER];
                                                $dbPasswd = MysqlDbConstants::$shard3SlaveDDL[PASS];
                                                $dbName = MysqlDbConstants::$shard3SlaveDDL[DEFAULT_DB];
                                                break;
				case 'alertsDDL' :
                                        $dbHost = MysqlDbConstants::$alertsDDL[HOST].":".MysqlDbConstants::$alerts[PORT];
                                        $dbUser = MysqlDbConstants::$alertsDDL[USER];
                                        $dbPasswd = MysqlDbConstants::$alertsDDL[PASS];
                                        $dbName = MysqlDbConstants::$alertsDDL[DEFAULT_DB];
				case 'viewLogDDL' :
                                        $dbHost = MysqlDbConstants::$viewLogDDL[HOST].":".MysqlDbConstants::$viewLogDDL[PORT];
                                        $dbUser = MysqlDbConstants::$viewLogDDL[USER];
                                        $dbPasswd = MysqlDbConstants::$viewLogDDL[PASS];
                                        $dbName = MysqlDbConstants::$viewLogDDL[DEFAULT_DB];
                                        break;
				default : 
					$dbHost = $this->dbHost;
					$dbUser = $this->dbUser;
					$dbPasswd = $this->dbPasswd;
					$dbName = $this->dbName;
					break;
			}
		}
		else
		{
			$conName = $this->conName;
			$dbHost = $this->dbHost;
			$dbUser = $this->dbUser;
			$dbPasswd = $this->dbPasswd;
			$dbName = $this->dbName;
		}

		$db=$this->setActiveDb($conName, $dbHost, $dbUser, $dbPasswd);

		$this->selectDb($dbName,$db);

		return $db;
	}

	/**
	* This function is used to select the requested database.
	* If $db parameter is specified, then database is selected on that connection,
	* otherwise database is selected in the active connection.
	* @param string $dbName Database to select.
	* @param mysqllink $db link to the mysql server.
	*/
        function setActiveDb($conName, $dbHost,$dbUser,$dbPassword)
        {
                if (!isset($this->dbConns[$conName]))
                {
                        $this->dbConns[$conName] = @mysql_connect($dbHost,$dbUser,$dbPassword) or $this->logError("no conn");//die(mysql_error());
			if(php_sapi_name()=="cli")
                                mysql_query("set session wait_timeout=50000,interactive_timeout=50000,net_read_timeout=50000",$this->dbConns[$conName]);
                }
                $this->previousDb = $this->activeDb;

                //Set the active connection.
                $this->activeDb = $this->dbConns[$conName];

                return $this->activeDb;
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
        public function executeQuery($sql,$db,$divert="",$backend="")
        {
                /*if($divert)
                {
                        if($db211 == $this->activeDb)
                                $db211 = $this->activeDb;
                        else
                                $db211 = $this->connect("211");

                        $this->activeDb = $this->previousDb;
                        $result = mysql_query($sql,$db211);
                }
                else
                {
                        if($db != "")
                                $result = mysql_query($sql,$db);
                        else
                                $result = mysql_query($sql,$active_db);
                }*/
		$this->activeDb = $db;
		$result = mysql_query($sql,$db);
		if($result)
                        return $result;
                else
		{
			// check for mysql server has gone away error
			if(mysql_errno($db)==2006 && mysql_ping($db))
			{
				$result = mysql_query($sql,$db);
		                if($result)
                		        return $result;
				else
		                        return $this->logError($sql,$backend);
			}
			else
				return $this->logError($sql,$backend);
		}
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
	* This function is used to fetch the result as an array from the specified resource id.
	* @param string $result Resource Id.
	* @return array.
	*/
	public function fetchRow($result)
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
	public function errNo()
	{
		return mysql_errno($this->activeDb);
	}

	/**
	* This function pings and checks whether the connection is active.
	* @return int
	*/
	public function ping()
	{
		return mysql_ping($this->activeDb);
	}

	/**
	* This function used to log the errors occuring during execution of a query.
	* @param string $sql The query for which error has to be logged.
	* @param bool $hideBlankBanner show/hide blank banner.
	* @param bool $backend to print error (if any) in the html source.
	*/
	public function logError($sql,$backend="")
	{
		LoggingWrapper::getInstance()->sendLog(LoggingEnums::LOG_ERROR, new Exception($sql));
		global $smarty,$ajax_error;

		$errorString = "\n".date("Y-m-d G:i:s",time() + 37800)."\nMysql Error: ".addslashes($this->error())."\nMysql Error Number:".$this->errNo()."\nSQL: $sql\nUser Agent : ".$_SERVER['HTTP_USER_AGENT']."\nReferer : ".$_SERVER['HTTP_REFERER']."\nSelf : ".$_SERVER['PHP_SELF']."\nUri : ".$_SERVER['REQUEST_URI']."\nMethod : ".$_SERVER['REQUEST_METHOD']."\n";
		if($backend)
			echo "<!-- ".$sql.$this->error()." -->";
		else
		{
			error_log($errorString,3,JsConstants::$docRoot . "/profile/logerror.txt");
	                error_log($errorString,3,JsConstants::$docRoot . "/profile/logerror_temp.txt");
			
			global $autoContact,$skip,$new_matches_email_table_population_cron;
			if($autoContact)
			{
				$skip=1;
				return 0;
			}
			if($new_matches_email_table_population_cron)
			{
				return 0;
			}
			if($_SERVER['ajax_error'] OR $ajax_error)
			{
				if($_SERVER['ajax_error']==2 OR $ajax_error==2)
				{
					echo 'A_E';
					exit;
				}
				$smarty->assign("ajax_error",1);
				$smarty->display("search_cluser_layer2.htm");
				die;
			}

                        if($_SERVER['REQUEST_METHOD']=="POST")
                        {
                                $j=0;
                                foreach($_POST as $key => $value)
                                {
                                        if($value != "")
                                        {
                                                $data[$j]["NAME"]=htmlspecialchars($key, ENT_QUOTES, false);
                                                if(is_array($value))
                                                {
							$data[$j]["VALUE"]="ARRAY";
                                                        $i=0;
							foreach($value as $val)
							if($val != "")
                                                        {
								$data[$j][$i++]=htmlspecialchars($val, ENT_QUOTES, false);
                                                        }
                                                }
                                                else
                                                        $data[$j]["VALUE"]=htmlspecialchars($value, ENT_QUOTES, false);
                                                $j++;
                                        }//if
                                }//foreach
                        }
                        else
                        {
                                $j=0;
                                foreach($_GET as $key => $value)
                                {
                                        if($value != "")
                                        {
                                                $data[$j]["NAME"]=htmlspecialchars($key, ENT_QUOTES, false);
                                                if(is_array($value))
                                                {
							$data[$j]["VALUE"]="ARRAY";
                                                        $i=0;
							foreach($value as $val)
							if($val != "")
							{
								$data[$j][$i++]=htmlspecialchars($val, ENT_QUOTES, false);
							}
                                                }
                                                else
                                                        $data[$j]["VALUE"]=htmlspecialchars($value, ENT_QUOTES, false);
                                                $j++;
                                        }//if
                                }//foreach
                        }

                        $smarty->assign("DATA",$data);
                        $smarty->assign("ACTION",$_SERVER['PHP_SELF']);

                        $smarty->assign("CHECKSUM",$checksum);

                        $smarty->assign("FOOT",$smarty->fetch("footer.htm"));
                        $smarty->assign("REVAMP_HEAD",$smarty->fetch("revamp_head.htm"));
			//$smarty->assign("SUBHEADER",$smarty->fetch("subheader.htm"));

                        //$smarty->assign("msg_error", $message);
                        $smarty->display("error_template.htm");
                        //$smarty->display("site_down.htm");
                        //exit;
		}
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
		$messageString = "\n".date("Y-m-d G:i:s",time() + 37800)."\nUser Agent : ".$_SERVER['HTTP_USER_AGENT']."\nReferer : ".$_SERVER['HTTP_REFERER']."\nSelf : ".$_SERVER['PHP_SELF']."\nUri : ".$_SERVER['REQUEST_URI']."\nMethod : ".$_SERVER['REQUEST_METHOD']."\n";

		if($sql)
			$messageString .= "SQL :".$sql."\n";

		error_log($messageString,3,JsConstants::$docRoot . "/bmsjs/bms_logMessages.txt");

		if($exitStatus)
			exit;
	}
}
?>
