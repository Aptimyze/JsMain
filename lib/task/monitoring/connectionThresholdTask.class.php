<?php
/**
* This will trigger mail for server reaching a defined threshold value.
* @author : Bhavana Kadwal
* @package Monitoring
* @since 2016-02-02
*/
class connectionThresholdTask extends sfBaseTask
{
  /**
    * @var const EMAIL_TO comma separated email ids
  */
  //const EMAIL_TO = "meow1991leo@gmail.com,lavesh.rawat@gmail.com,reshu.rajput@gmail.com,niteshsethi1987@gmail.com,vibhor.garg@jeevansathi.com,pankaj139@gmail.com,ankitshukla125@gmail.com,eshajain88@gmail.com,manojrana975@gmail.com,kunal.test02@gmail.com";
  const EMAIL_TO = "bhavana.kadwal@jeevansathi.com,lavesh.rawat@jeevansathi.com,reshu.rajput@jeevansathi.com,nitesh.s@gmail.com,vibhor.garg@jeevansathi.com,pankaj.khandelwal@Jeevansathi.com,ankit.shukla@jeevansathi.com,esha.jain@jeevansathi.com,manoj.rana@naukri.com,kunal.verma@jeevansathi.com";
  private $SMS_TO = array('9650350387','9818424749','9711304800','9953178503','9810300513','9711818214','9953457479','9873639543','9999216910','9868673707','8826380350');
  const FROM_ID = "JSSRVR";
  const PROFILE_ID = "144111";
  /**
    * @var string $smsMessage sms body text
  */
  private $smsMessage = "";
  private $ifSmsSend = 0;
  private $errorServer = array();
  private $servers = array();
  /**
    * @var string $mailMessage email body text
  */
  private $mailMessage = "";
  private $slackMessage = array();
  private $sleepQuery = array();
  private $ifSend = 0;
  /*
   * @var array $thresholdValue having threshold value for each server   
   */
  static $thresholdValue = array("master"=>680,"masterRO"=>680,"shard1"=>350,"shard2"=>350,"shard3"=>350,"viewSimilar"=>300,"bmsSlave"=>350,"alertsSlave"=>300,"masterRep"=>600,"shard1Rep"=>300,"shard2Rep"=>300,"shard3Rep"=>300,"shard1Slave"=>300,"shard2Slave"=>300,"shard3Slave"=>300);
  
  protected function configure()
  {
    $this->namespace        = 'monitoring';
    $this->name             = 'connectionThreshold';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [connectionThreshold|INFO] runs periodically to check for server reaching a threshold connection value.
Call it with:
  [php symfony monitoring:connectionThreshold|INFO]
EOF;
  }

    protected function execute($arguments = array(), $options = array())
    {      
// all read access credentials for limited users only
                $user = "js_db_monitor";
                $password = "jsdbm0n1t0R";
      // server configuration array with threshold value is indexed at 'threshold'
	$SERVER_ARR[]=array("master",MysqlDbConstants::$masterDDL["HOST"],$user,$password,MysqlDbConstants::$masterDDL["PORT"],'threshold'=>self::$thresholdValue['master']);
       $SERVER_ARR[]=array("shard1",MysqlDbConstants::$shard1DDL["HOST"],$user,$password,MysqlDbConstants::$shard1DDL["PORT"],'threshold'=>self::$thresholdValue['shard1']);
       $SERVER_ARR[]=array("shard2",MysqlDbConstants::$shard2DDL["HOST"],$user,$password,MysqlDbConstants::$shard2DDL["PORT"],'threshold'=>self::$thresholdValue['shard2']);
       $SERVER_ARR[]=array("shard3",MysqlDbConstants::$shard3DDL["HOST"],$user,$password,MysqlDbConstants::$shard3DDL["PORT"],'threshold'=>self::$thresholdValue['shard3']);
       $SERVER_ARR[]=array("shard1Slave",MysqlDbConstants::$shard1SlaveDDL["HOST"],$user,$password,MysqlDbConstants::$shard1SlaveDDL["PORT"],'threshold'=>self::$thresholdValue['shard1Slave']);
       $SERVER_ARR[]=array("shard2Slave",MysqlDbConstants::$shard2SlaveDDL["HOST"],$user,$password,MysqlDbConstants::$shard2SlaveDDL["PORT"],'threshold'=>self::$thresholdValue['shard2Slave']);
       $SERVER_ARR[]=array("shard3Slave",MysqlDbConstants::$shard3SlaveDDL["HOST"],$user,$password,MysqlDbConstants::$shard3SlaveDDL["PORT"],'threshold'=>self::$thresholdValue['shard3Slave']);
//       $SERVER_ARR[]=array("alertsSlave",MysqlDbConstants::$alertsDDL["HOST"],MysqlDbConstants::$alertsDDL["USER"],MysqlDbConstants::$alertsDDL["PASS"],MysqlDbConstants::$alertsDDL["PORT"],'threshold'=>self::$thresholdValue['alertsSlave']);
       
      //$SERVER_ARR[]=array("master",MysqlDbConstants::$master["HOST"],MysqlDbConstants::$master["USER"],MysqlDbConstants::$master["PASS"],MysqlDbConstants::$master["PORT"],'threshold'=>self::$thresholdValue['master']);
      $SERVER_ARR[]=array("masterRep",MysqlDbConstants::$masterRep["HOST"],$user,$password,MysqlDbConstants::$masterRep["PORT"],'threshold'=>self::$thresholdValue['masterRep']);
      //$SERVER_ARR[]=array("shard1",MysqlDbConstants::$shard1["HOST"],MysqlDbConstants::$shard1["USER"],MysqlDbConstants::$shard1["PASS"],MysqlDbConstants::$shard1["PORT"],'threshold'=>self::$thresholdValue['shard1']);
      //$SERVER_ARR[]=array("shard2",MysqlDbConstants::$shard2["HOST"],MysqlDbConstants::$shard2["USER"],MysqlDbConstants::$shard2["PASS"],MysqlDbConstants::$shard2["PORT"],'threshold'=>self::$thresholdValue['shard2']);
      //$SERVER_ARR[]=array("shard3",MysqlDbConstants::$shard3["HOST"],MysqlDbConstants::$shard3["USER"],MysqlDbConstants::$shard3["PASS"],MysqlDbConstants::$shard3["PORT"],'threshold'=>self::$thresholdValue['shard3']);
     $SERVER_ARR[]=array("shard1Rep",MysqlDbConstants::$shard1Rep["HOST"],$user,$password,MysqlDbConstants::$shard1Rep["PORT"],'threshold'=>self::$thresholdValue['shard1Rep']);
     $SERVER_ARR[]=array("shard2Rep",MysqlDbConstants::$shard2Rep["HOST"],$user,$password,MysqlDbConstants::$shard2Rep["PORT"],'threshold'=>self::$thresholdValue['shard2Rep']);
     $SERVER_ARR[]=array("shard3Rep",MysqlDbConstants::$shard3Rep["HOST"],$user,$password,MysqlDbConstants::$shard3Rep["PORT"],'threshold'=>self::$thresholdValue['shard3Rep']);
     
     //$SERVER_ARR[]=array("shard1Slave",MysqlDbConstants::$shard1Slave["HOST"],MysqlDbConstants::$shard1Slave["USER"],MysqlDbConstants::$shard1Slave["PASS"],MysqlDbConstants::$shard1Slave["PORT"],'threshold'=>self::$thresholdValue['shard1Slave']);
     //$SERVER_ARR[]=array("shard2Slave",MysqlDbConstants::$shard2Slave["HOST"],MysqlDbConstants::$shard2Slave["USER"],MysqlDbConstants::$shard2Slave["PASS"],MysqlDbConstants::$shard2Slave["PORT"],'threshold'=>self::$thresholdValue['shard2Slave']);
     //$SERVER_ARR[]=array("shard3Slave",MysqlDbConstants::$shard3Slave["HOST"],MysqlDbConstants::$shard3Slave["USER"],MysqlDbConstants::$shard3Slave["PASS"],MysqlDbConstants::$shard3Slave["PORT"],'threshold'=>self::$thresholdValue['shard3Slave']);
     
//      $SERVER_ARR[]=array("viewSimilar",MysqlDbConstants::$viewSimilar["HOST"],$user,$password,MysqlDbConstants::$viewSimilar["PORT"],'threshold'=>self::$thresholdValue['viewSimilar']);
      $SERVER_ARR[]=array("bmsSlave",MysqlDbConstants::$bmsSlave["HOST"],$user,$password,MysqlDbConstants::$bmsSlave["PORT"],'threshold'=>self::$thresholdValue['bmsSlave']);
      $SERVER_ARR[]=array("alertsSlave",MysqlDbConstants::$alertsSlave["HOST"],$user,$password,MysqlDbConstants::$alertsSlave["PORT"],'threshold'=>self::$thresholdValue['alertsSlave']);
      
      $serverArrayCount = count($SERVER_ARR);
      for ($i = 0; $i < $serverArrayCount; $i++) {    
        $serverName = $SERVER_ARR[$i][0];
        $this->servers[] = $serverName;
        $db = @mysql_connect($SERVER_ARR[$i][1] . ":" . $SERVER_ARR[$i][4],$SERVER_ARR[$i][2],$SERVER_ARR[$i][3]);
         $res=mysql_query("SELECT * FROM INFORMATION_SCHEMA.PROCESSLIST WHERE COMMAND != 'Binlog Dump' AND COMMAND != 'Connect' ORDER BY TIME DESC",$db);
        if(!$res){
	    $this->ifSend = 1;
	    $this->ifSmsSend = 1;
            $this->mailMessage .= "<br/><br/>".$serverName."- Cannot Connect to server";
            $this->slackMessage[$serverName] = $serverName."- Cannot Connect to server";
            $this->errorServer[] = $i;
        }else{
            $this->checkThreshold($res,$serverName,$SERVER_ARR[$i]['threshold']);
        }

	if($serverName == 'master'){
		$sql_l = "SELECT * FROM information_schema.processlist WHERE user NOT IN ('system user') AND user not like '%repl%' AND time >= '2700' AND COMMAND!='Sleep'";
		$res_l =mysql_query($sql_l,$db);
		$row_l = mysql_fetch_array($res_l);
		if($row_l){
			file_put_contents(sfConfig::get("sf_upload_dir")."/SearchLogs/jaago.txt","\n\n".date("Y-m-d h:i:s")."---".var_export($row_l,true));
			$this->sendSMS('','new');
		}
	}
      }
      if($this->ifSend== "1"){ // trigger mail if mail body is not empty
          $this->notify();
      }
      $this->memCacheCheck($this->ifSmsSend);
    }
    /*
     * This function check the threshold for each server
     * @param object $conn database connection
     * @param string $serverName database name
     * @param int $thresholdValue threshold value for the database
     */
    private function checkThreshold($conn,$serverName,$thresholdValue){
        $connectionCount = mysql_num_rows($conn);
        if($thresholdValue <= $connectionCount){
	    $this->ifSend = 1;
	}
        $this->mailMessage .= "<br/><br/>".$serverName." is having ".$connectionCount." connections <br/>";
        $this->slackMessage[$serverName] = $serverName." is having ".$connectionCount." connections";
        $this->formatMsg($conn,$serverName);
    }
    /*
     * This function formats the process list query and append it to mailmessage variable
     * @param object $res database connection
     */
    private function formatMsg($res,$serverName)
    {
        $sleepcount = 0;
        $this->mailMessage .= "Connection Details <br/>";
        while($row=@mysql_fetch_assoc($res))
        {
                if($row["COMMAND"] != "Sleep"){
                        $this->mailMessage .= implode("|\t",$row)."<br/>";
                }else{
                        $sleepcount ++;
                        $server = explode(":",$row["HOST"]);
                        if(!isset($this->sleepQuery[$server[0]])){
                                $this->sleepQuery[$server[0]] = array();
                        }
                        if(!isset($this->sleepQuery[$server[0]][$serverName])){
                                $this->sleepQuery[$server[0]][$serverName] = 0;
                        }
                        $this->sleepQuery[$server[0]][$serverName]++;
                }
        }
        $this->slackMessage[$serverName] .= " :: Sleep count is ".$sleepcount;
    }
    /*
     * This function trigger email
     */
    private function notify(){
	$dt = date("Y-m-d H:i:s");
        $serverMessage = "Hi,<br/><br/>"."Please find below the server details exceeding threshold. <br/><br/>Sleep Queries::<br/>";
        $tableBody = "<table style='border:1px solid black;border-collapse:collapse;'>";
        sort($this->servers);
        $tableBody .= "<tr style='border:1px solid ;'>"."<th style='border:1px solid ;'>Server</th><th style='border:1px solid ;'>".implode("</th><th style='border:1px solid ;'>",$this->servers)."</th>"."</tr>";
        foreach($this->sleepQuery as $key=>$v){
                $aDiff = array_diff($this->servers,array_keys($v));
                foreach($aDiff as $server){
                        $this->sleepQuery[$key][$server] = 0;
                }
                ksort($this->sleepQuery[$key]);
                $tableBody .= "<tr style='border:1px solid ;'><td style='border:1px solid ;'>$key</td><td style='border:1px solid ;'>".implode("</td><td style='border:1px solid ;'>",$this->sleepQuery[$key])."</td></tr>";
        }
        $tableBody .= "</table>";
        $serverMessage .= $tableBody;
        $serverMessage .= "</br></br>".$this->mailMessage;
        $fileName = sfConfig::get("sf_upload_dir")."/SearchLogs/connectionThreshold".date('Ymd').".txt";
        $breaks = array("<br />","<br>","<br/>");
        $msgWithSlashN = str_ireplace($breaks, "\n", $serverMessage);
        file_put_contents($fileName, date("Y m d H:i:s", strtotime("now"))."\n".$msgWithSlashN."\n\n", FILE_APPEND);
        SendMail::send_email(self::EMAIL_TO, $serverMessage,"Servers exceeding threshold - $dt"); 
        CommonUtility::sendSlackmessage(implode(" \n ",$this->slackMessage),"mysql");
    }
/*
     * @param int $memCacheValue memcache threshold value
     */
    private function sendSMS($memCacheValue,$flag="") {
     if($flag=="new"){
	      $this->smsMessage = "Mysql Error Count have reached jaago on master within 5 minutes";
	}
      else{
	      $servers = implode(',',$this->errorServer);
	      $this->smsMessage = "Mysql Error Count have reached Threshold on ".$servers." ".$memCacheValue." within 5 minutes";
       }
      foreach ($this->SMS_TO as $mobPhone) {
        $xml_head = "%3C?xml%20version=%221.0%22%20encoding=%22ISO-8859-1%22?%3E%3C!DOCTYPE%20MESSAGE%20SYSTEM%20%22http://127.0.0.1/psms/dtd/message.dtd%22%3E%3CMESSAGE%3E%3CUSER%20USERNAME=%22naukari%22%20PASSWORD=%22na21s8api%22/%3E";
        $xml_content="%3CSMS%20UDH=%220%22%20CODING=%221%22%20TEXT=%22".urlencode($this->smsMessage)."%22%20PROPERTY=%220%22%20ID=%22".self::PROFILE_ID."%22%3E%3CADDRESS%20FROM=%22".self::FROM_ID."%22%20TO=%22".$mobPhone."e%22%20SEQ=%22".self::PROFILE_ID."%22%20TAG=%22%22/%3E%3C/SMS%3E";
        $xml_end = "%3C/MESSAGE%3E";
        $xml_code = $xml_head . $xml_content . $xml_end;
        $fd = @fopen("http://api.myvaluefirst.com/psms/servlet/psms.Eservice2?data=$xml_code&action=send", "rb");
        if ($fd) {
          $response = '';
          while (!feof($fd)) {
            $response.= fread($fd, 4096);
          }
          fclose($fd);
        }
      }
  }

  private function memCacheCheck($sendSms = 0){
      if($sendSms == 0){
       JsMemcache::getInstance()->set("memcacheThresholdValue",0); 
      }else{
        if (!JsMemcache::getInstance()->get("memcacheThresholdValue") || JsMemcache::getInstance()->get("memcacheThresholdValue") == 0) {
          JsMemcache::getInstance()->set("memcacheThresholdValue",1);
        }else{
          $memCacheValue = JsMemcache::getInstance()->get("memcacheThresholdValue");
          $memCacheValue++; 
          if($memCacheValue>1){
            JsMemcache::getInstance()->set("memcacheThresholdValue",$memCacheValue);
            $this->sendSMS($memCacheValue);
          }
        }
      }
    }
}
