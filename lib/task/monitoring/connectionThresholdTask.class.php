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
  const EMAIL_TO = "meow1991leo@gmail.com,lavesh.rawat@gmail.com,reshu.rajput@gmail.com,niteshsethi1987@gmail.com,vibhor.garg@jeevansathi.com,pankaj139@gmail.com,ankitshukla125@gmail.com,eshajain88@gmail.com,manojrana975@gmail.com";
  private $SMS_TO = array('9650350387','9818424749','9711304800','9953178503','9810300513','9711818214','9953457479','9873639543','9999216910','9868673707');
  const FROM_ID = "JSSRVR";
  const PROFILE_ID = "144111";
  /**
    * @var string $smsMessage sms body text
  */
  private $smsMessage = "";
  private $ifSmsSend = 0;
  private $errorServer = array();
  /**
    * @var string $mailMessage email body text
  */
  private $mailMessage = "";
  private $ifSend = 0;
  /*
   * @var array $thresholdValue having threshold value for each server   
   */
  static $thresholdValue = array("master"=>680,"masterRO"=>680,"shard1"=>350,"shard2"=>350,"shard3"=>350,"viewSimilar"=>300,"bmsSlave"=>350,"alertsSlave"=>300,"masterRep"=>600,"shard1Rep"=>300,"shard2Rep"=>300,"shard3Rep"=>300);
  
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
      // server configuration array with threshold value is indexed at 'threshold'
      $SERVER_ARR[]=array("master",MysqlDbConstants::$master["HOST"],MysqlDbConstants::$master["USER"],MysqlDbConstants::$master["PASS"],MysqlDbConstants::$master["PORT"],'threshold'=>self::$thresholdValue['master']);
      $SERVER_ARR[]=array("masterRO",MysqlDbConstants::$masterRO["HOST"],MysqlDbConstants::$masterRO["USER"],MysqlDbConstants::$masterRO["PASS"],MysqlDbConstants::$masterRO["PORT"],'threshold'=>self::$thresholdValue['masterRO']);
      $SERVER_ARR[]=array("masterRep",MysqlDbConstants::$masterRep["HOST"],MysqlDbConstants::$masterRep["USER"],MysqlDbConstants::$masterRep["PASS"],MysqlDbConstants::$masterRep["PORT"],'threshold'=>self::$thresholdValue['masterRep']);
      $SERVER_ARR[]=array("shard1",MysqlDbConstants::$shard1["HOST"],MysqlDbConstants::$shard1["USER"],MysqlDbConstants::$shard1["PASS"],MysqlDbConstants::$shard1["PORT"],'threshold'=>self::$thresholdValue['shard1']);
      $SERVER_ARR[]=array("shard2",MysqlDbConstants::$shard2["HOST"],MysqlDbConstants::$shard2["USER"],MysqlDbConstants::$shard2["PASS"],MysqlDbConstants::$shard2["PORT"],'threshold'=>self::$thresholdValue['shard2']);
      $SERVER_ARR[]=array("shard3",MysqlDbConstants::$shard3["HOST"],MysqlDbConstants::$shard3["USER"],MysqlDbConstants::$shard3["PASS"],MysqlDbConstants::$shard3["PORT"],'threshold'=>self::$thresholdValue['shard3']);
     $SERVER_ARR[]=array("shard1Rep",MysqlDbConstants::$shard1Rep["HOST"],MysqlDbConstants::$shard1Rep["USER"],MysqlDbConstants::$shard1Rep["PASS"],MysqlDbConstants::$shard1Rep["PORT"],'threshold'=>self::$thresholdValue['shard1Rep']);
     $SERVER_ARR[]=array("shard2Rep",MysqlDbConstants::$shard2Rep["HOST"],MysqlDbConstants::$shard2Rep["USER"],MysqlDbConstants::$shard2Rep["PASS"],MysqlDbConstants::$shard2Rep["PORT"],'threshold'=>self::$thresholdValue['shard2Rep']);
     $SERVER_ARR[]=array("shard3Rep",MysqlDbConstants::$shard3Rep["HOST"],MysqlDbConstants::$shard3Rep["USER"],MysqlDbConstants::$shard3Rep["PASS"],MysqlDbConstants::$shard3Rep["PORT"],'threshold'=>self::$thresholdValue['shard3Rep']);
      $SERVER_ARR[]=array("viewSimilar",MysqlDbConstants::$viewSimilar["HOST"],MysqlDbConstants::$viewSimilar["USER"],MysqlDbConstants::$viewSimilar["PASS"],MysqlDbConstants::$viewSimilar["PORT"],'threshold'=>self::$thresholdValue['viewSimilar']);
      $SERVER_ARR[]=array("bmsSlave",MysqlDbConstants::$bmsSlave["HOST"],MysqlDbConstants::$bmsSlave["USER"],MysqlDbConstants::$bmsSlave["PASS"],MysqlDbConstants::$bmsSlave["PORT"],'threshold'=>self::$thresholdValue['bmsSlave']);
      $SERVER_ARR[]=array("alertsSlave",MysqlDbConstants::$alertsSlave["HOST"],MysqlDbConstants::$alertsSlave["USER"],MysqlDbConstants::$alertsSlave["PASS"],MysqlDbConstants::$alertsSlave["PORT"],'threshold'=>self::$thresholdValue['alertsSlave']);
      
      $serverArrayCount = count($SERVER_ARR);
      for ($i = 0; $i < $serverArrayCount; $i++) {    
        $serverName = $SERVER_ARR[$i][0];
        $db = @mysql_connect($SERVER_ARR[$i][1] . ":" . $SERVER_ARR[$i][4],$SERVER_ARR[$i][2],$SERVER_ARR[$i][3]);
        $res=mysql_query("SHOW FULL PROCESSLIST",$db);
        if(!$res){
	    $this->ifSend = 1;
	    $this->ifSmsSend = 1;
            $this->mailMessage .= "<br/><br/>".$serverName."- Cannot Connect to server";
            $this->errorServer[] = $i;
        }else{
            $this->checkThreshold($res,$serverName,$SERVER_ARR[$i]['threshold']);
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
        $this->formatMsg($conn);
    }
    /*
     * This function formats the process list query and append it to mailmessage variable
     * @param object $res database connection
     */
    private function formatMsg($res)
    {
        $this->mailMessage .= "Connection Details <br/>";
        while($row=@mysql_fetch_row($res))
        {
                $this->mailMessage .= implode("|\t",$row)."<br/>";
        }
    }
    /*
     * This function trigger email
     */
    private function notify(){
	$dt = date("Y-m-d H:i:s");
        $serverMessage = "Hi,<br/><br/>"."Please find below the server details exceeding threshold.".$this->mailMessage;
        SendMail::send_email(self::EMAIL_TO, $serverMessage,"Servers exceeding threshold - $dt"); 
    }
/*
     * @param int $memCacheValue memcache threshold value
     */
    private function sendSMS($memCacheValue) {
      $servers = implode(',',$this->errorServer);
      $this->smsMessage = "Mysql Error Count have reached Threshold on ".$servers." ".$memCacheValue." within 5 minutes";
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
