<?php
ini_set("max_execution_time",0);
ini_set("mysql.connect_timeout",-1);
/**
* This will mail too many connection reason.
* @author : Lavesh Rawat
* @package Monitoring
* @since 2016-06-09
*/
class RedisWorkingFineTask extends sfBaseTask
{
        /**
        * @access private
        * @var int $m_sleep timeduration after which we will check again for redis.
        */
	private $m_sleep = 60;
	private $testArr = array('lavesh1'=>1,'lavesh2'=>2,'lavesh3'=>3);
  
	/**
	* @var const EMAIL_TO comma separated email ids
	*/
	const EMAIL_TO = "meow1991leo@gmail.com,lavesh.rawat@gmail.com,reshu.rajput@gmail.com";
	const FROM_ID = "JSSRVR";
	const PROFILE_ID = "144111";
	private $SMS_TO = array('9650350387','9818424749','9810300513','9873639543');
	private $smsMessage = "";
  	private $mailMessage = "";

	protected function configure()
        {
                $this->addOptions(array(
                new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
             ));
          
            $this->namespace        = 'monitoring';
            $this->name             = 'RedisWorkingFine';
            $this->briefDescription = 'This cron runs periodically and check if redis is working fine.';
            $this->detailedDescription = <<<EOF
          [php symfony monitoring:RedisWorkingFine]
EOF;
        }

        protected function execute($arguments = array(), $options = array())
        {
		while(1)
		{
			foreach($this->testArr as $key1=>$val1)
			{
				//$key1 = 'lavesh3';
        	        	//$x = JsMemcache::getInstance()->set($key1,'3');
	                	$val = JsMemcache::getInstance()->get($key1);
				if($val!=$val1)
				{
					$issues[] = $key1;
				}
			}
			if($issues)
			{
				$this->mailMessage = implode(",",$issues);
				$this->emailNotify();
				$this->sendSMS();
				unset($issues);
			}
			//sleep($this->m_sleep);
		}
	}


	/*
	* This function trigger email
	*/
	private function emailNotify(){
		$dt = date("Y-m-d h:m:i");
		$serverMessage = "Hi,<br/><br/>"."Please find below the redis deatils ".$this->mailMessage;
		SendMail::send_email(self::EMAIL_TO, $serverMessage,"Redis serves not working - $dt");
	}


	private function sendSMS() {
		$this->smsMessage = "Mysql Error Count have reached Redis on ".$this->mailMessage."within 5 minutes";
		foreach ($this->SMS_TO as $mobPhone) 
		{
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
                CommonUtility::logTechAlertSms($this->smsMessage, $mobPhone);
			}
		}
	}
}
