<?php
/**
 * Description of MysqlEligibleToKillTask
 * Task to find all those process which are eligible to kill as per given parameters and list all such process at the end
 * <code>
 * ./symfony help  Mysql:EligibleToKill
 * To execute : $php symfony Mysql:EligibleToKill [--db[="..."]] [--dml[="..."]] [--state[="..."]] serverName
 * </code>
 * @author Kunal Verma
 * @created 17th May 2016
 */
class MysqlEligibleToKillTask extends sfBaseTask
{
  /**
   * Flag to show/hide debug info
   * @var Boolean
   */
  private $m_bDebugInfo = false;
  
  /**
   * Array to store server config
   * @var Array 
   */
  private $m_arrServerConfig = array();
  
  /**
   * Variable to store path of mysql 
   * @var String
   */
  private $m_szSqlPath = null;
  
  /**
   * Arguement 
   */
  const ARG_SERVER_NAME = 'serverName';
  
  /**
   * Optional filter database
   */
  const OPT_DATABASE = 'db';
  
  /**
   * Optional filter dml
   */
  const OPT_DML = 'dml';
  
  /**
   * Optional filter state
   */
  const OPT_QUERY_STATE = 'state';
  
  /**
   * Const path of Mysql Config
   */
  const MYSQL_CONFIG_PATH = '/usr/local/scripts/config/MysqlDbConstants.class.php';
  
  protected function configure()
  {
     // add your own arguments here
     $this->addArguments(array(
       new sfCommandArgument(self::ARG_SERVER_NAME, sfCommandArgument::REQUIRED, 'Server name as specified in config file'),
     ));

     // add your own options here
     $this->addOptions(array(
       new sfCommandOption(self::OPT_DATABASE, null, sfCommandOption::PARAMETER_OPTIONAL, 'Database Filter, by default newjs'),
     ));
     
     $this->addOptions(array(
       new sfCommandOption(self::OPT_DML, null, sfCommandOption::PARAMETER_OPTIONAL, 'DML Filter,like select, insert, update, delete etc, by default select'),
     ));
     
     $this->addOptions(array(
       new sfCommandOption(self::OPT_QUERY_STATE, null, sfCommandOption::PARAMETER_OPTIONAL, 'Filter on the basis of state of queries, like watiting, executing, sending data etc'),
     ));
     
    $this->namespace        = 'Mysql';
    $this->name             = 'EligibleToKill';
    $this->briefDescription = 'Task to find all those process which are eligible to kill as per given parameters and list all such process at the end';
    $this->detailedDescription = <<<EOF
The [Mysql:EligibleToKill|INFO] task does things.
      Task to find all those process which are eligible to kill as per given parameters and list all such process at the end
Call it with:

  [php symfony Mysql:EligibleToKill [--db[="..."]] [--dml[="..."]] [--state[="..."]] serverName]
      --db = Data Base Name
      --dml = DML Queries (Select,Insert,Update,Delete etc)
      --State = State of Queries (Like Waiting, Executing, Sending Data etc)
EOF;
  }
  
  /**
   * execute
   * @param type $arguments
   * @param type $options
   */
  protected function execute($arguments = array(), $options = array())
  {
    $st_Time = microtime(TRUE);

    try {
      $this->loadServerConfig($arguments[self::ARG_SERVER_NAME]);
      $arrQueries = $this->getProcessList($options);
      $this->render($arrQueries);
    } catch (Exception $ex) {
      $this->logSection('Exception : ', $ex->getMessage());
    }
    //end script
    $this->endScript($st_Time);
  }
  
  /**
   * loadServerConfig
   * @param type $szServerName
   */
  private function loadServerConfig($szServerName)
  {
    if($this->m_bDebugInfo) {
      $this->logSection('Given server name : ', $szServerName);
    }
    
    //Config file path
    $szConfigFilePath = self::MYSQL_CONFIG_PATH;
    
    if (false === file_exists($szConfigFilePath)) {
      throw new Exception('Mysql Config File does not exist');
    }
    
    //Load Config File
    require_once($szConfigFilePath);
    if(false === property_exists(MysqlDbConstants,$szServerName))
    {
      throw new Exception('Server config is not present in mysql config file');
    }
    
    $this->m_arrServerConfig = MysqlDbConstants::$$szServerName;
    $this->m_szSqlPath = MysqlDbConstants::$mySqlPath;
    
    if (false === file_exists($this->m_szSqlPath)) {
      throw new Exception('Mysql path is not valid.');
    }
  }
  
  /**
   * 
   * @param type $options
   */
  private function getProcessList($options)
  {
    $szCmd = $this->m_szSqlPath." -u".$this->m_arrServerConfig['USER']." -h".$this->m_arrServerConfig['HOST']." -P".$this->m_arrServerConfig['PORT']." -p".$this->m_arrServerConfig['PASS'];
    
    $szSqlQuery = "SELECT ID,INFO,TIME FROM INFORMATION_SCHEMA.PROCESSLIST WHERE COMMAND='Query'";
    
    //DB Filter
    $defaultDB = "";
    if(isset($options[self::OPT_DATABASE])) {
      $defaultDB = $options[self::OPT_DATABASE];
    }
    if(strlen($defaultDB)) {
      $szSqlQuery .= " AND DB='".$defaultDB."'";
    }
    //DML Filter
    $defaultDml = "select";
    if(isset($options[self::OPT_DML])) {
      $defaultDml = $options[self::OPT_DML];
    } 
    $szSqlQuery .= " AND INFO like '%".$defaultDml."%'";
    
    //State Filter
    if(isset($options[self::OPT_QUERY_STATE])) {
      $szSqlQuery .= " AND STATE like '%".$options[self::OPT_QUERY_STATE]."%'";
    } 
    
    $szSqlQuery .= " ORDER BY TIME DESC";
    
    $shellCmd = $szCmd." -e \"".$szSqlQuery."\"";
    //Execute shell
    
    $result = shell_exec($shellCmd);
    $arrResult = explode("\n",$result);
    $arrOut = array();
    for ($itr = 1; $itr < count($arrResult); $itr++) {
      //sscanf($line, "%d\t%s",$pid,$info);
      list($pid,$info,$time) = explode("\t",$arrResult[$itr]);
      if (is_numeric($pid)) {
        $arrOut[$pid]['query'] = $info;
        $arrOut[$pid]['time'] = $time;
        
      }
    }
    return $arrOut;    
  }
  
  /**
   * render
   * @param type $arrQueries
   */
  private function render($arrQueries)
  {
    if(false === is_array($arrQueries) || 0 === count($arrQueries)) {
      $this->logSection("No eligible query exist.");
      return;
    }
    
    $arrInfo = array();
    foreach ($arrQueries as $iProcessID => $szQueryInfo) {
      $this->log("Kill ".$iProcessID.";");
      $arrInfo[] = $iProcessID." => ".trim($szQueryInfo['query']).", TIME : ".trim($szQueryInfo['time']);
    }
    $this->logSection("Queries Information is as follows : ", "");
    $this->log(implode("\n",$arrInfo));
    
  }
  
  /**
   * End script 
   * To note statistic of memory and time usages
   * @param : $st_Time [Start Time]
   * @return void
   */
  private function endScript($st_Time = '') {
    $end_time = microtime(TRUE);
    $var = memory_get_usage(true);

    if ($var < 1024)
      $mem = $var . " bytes";
    elseif ($var < 1048576)
      $mem = round($var / 1024, 2) . " kilobytes";
    else
      $mem = round($var / 1048576, 2) . " megabytes";

    if ($this->m_bDebugInfo) {
      $this->logSection('Memory usages : ', $mem);
      $this->logSection('Time taken : ', $end_time - $st_Time);
    }
  }
}
