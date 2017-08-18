<?php
/********************************************************************************
 * Task to generate alert based on records from database table SPAM_CONTROL
 *
 * @Purpose     This TASK is used to generate ALERT for drop in email open rates for spam control
 * @execution   Terminal - php symfony mailer:spamAlert
 * @author		Akash Kumar
 ********************************************************************************/
class spamAlertTask extends sfBaseTask
{
	private $gmailThreshold_a=200;  // Absolute threshold for email domains
	private $yahooThreshold_a=400;
	private $rediffThreshold_a=300;
	private $hotmailThreshold_a=100;
	private $gmailThreshold_r=0.1;		 // Relative threshold for email domains
	private $yahooThreshold_r=0.1;
	private $rediffThreshold_r=0.1;
	private $hotmailThreshold_r=0.1;
	private	$argumentForAbsolute="A";
	private	$argumentForRelative="R";
	private $emailId='lavesh.rawat@gmail.com';	//ALert receiver
	private $message='';	//Alert Message
	private $subject='JS Email Open Rate Alert';	// Email Subject
	private $senderId='akash.k@jeevansathi.com';	// Sender email ID
	protected function configure()
	{
		$this->addArgument('alert', sfCommandArgument::OPTIONAL, 'alert is parameter for Absolute(A)/Relative(R) based alert', 'both');
		$this->namespace        = 'mailer';
		$this->name             = 'spamAlert';
		$this->briefDescription = 'generate ALERT for drop in email open rates for spam control';
		$this->detailedDescription = <<<EOF
The [spamAlert] task generate ALERT for drop in email open rates for spam control.
Call it with:

  [php symfony mailer:spamAlert]
EOF;
	}

	protected function execute($arguments = array(), $options = array())
	{
		$fileName =  $_SERVER["SCRIPT_FILENAME"];
		$http_msg=print_r($_SERVER,true);
		mail("reshu.rajput@gmail.com","For DLL Movement - $fileName",$http_msg);
		$emailObject=new SpamControl();           // Object of library class SpamAlert to analyse and alert based drop in openrate of email 
		$openrate=$emailObject->alert();
		if($arguments['alert']==$argumentForAbsolute || $arguments['alert']=="both")
			{
			if($openrate["GMAIL_OPEN"]<$gmailThreshold_a){$message=$message."Absolute CRITICAL Gmail Open Rate-".$openrate["GMAIL_OPEN"]."<br>";}
			if($openrate["YAHOO_OPEN"]<$yahooThreshold_a){$message=$message."Absolute CRITICAL Yahoo Open Rate-".$openrate["YAHOO_OPEN"]."<br>";}
			if($openrate["REDIFF_OPEN"]<$rediffThreshold_a){$message=$message."Absolute CRITICAL Rediff Open Rate-".$openrate["REDIFF_OPEN"]."<br>";}
			if($openrate["HOTMAIL_OPEN"]<$hotmailThreshold_a){$message=$message."Absolute CRITICAL Gmail Open Rate-".$openrate["HOTMAIL_OPEN"]."<br>";}
			}
		if($arguments['alert']==$argumentForRelative || $arguments['alert']=="both")
			{
				$gmailROpenRate=($openrate["GMAIL_OPEN"]/$openrate["GMAIL"]);
			if($gmailROpenRate<$gmailThreshold_r){$message=$message."RELATIVE CRITICAL Gmail Open Rate-".$gmailROpenRate."<br>";}
				$yahooROpenRate=($openrate["YAHOO_OPEN"]/$openrate["YAHOO"]);
			if($yahooROpenRate<$yahooThreshold_r){$message=$message."RELATIVE CRITICAL Yahoo Open Rate-".$yahooROpenRate."<br>";}
				$rediffROpenRate=($openrate["REDIFF_OPEN"]/$openrate["REDIFF"]);
			if($rediffROpenRate<$rediffThreshold_r){$message=$message."RELATIVE CRITICAL Rediff Open Rate-".$rediffROpenRate."<br>";}
				$hotmailROpenRate=($openrate["HOTMAIL_OPEN"]/$openrate["HOTMAIL"]);
			if($hotmailROpenRate<$hotmailThreshold_r){$message=$message."RELATIVE CRITICAL Hotmail Open Rate-".$hotmailROpenRate."<br>";}
			}
			
		$mailSent = SendMail::send_email($emailId,$message,$subject,$senderId);
		if(!$mailSent)
			{
				SendMail::send_email("reshu.rajput@gmail.com,lavesh.rawat@gmail.com","Email Open Rate Alert Sending Failed",$senderId);
				die;
			}
	}
}
