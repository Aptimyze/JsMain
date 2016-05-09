<?php
/*
 * Author: Vipin Dalal
 * This cron is used to choose the actual mailer based on mailerid.
*/
class ChooseMailTask extends sfBaseTask
{
	protected function configure()
  	{
		$this->addArguments(array(
			new sfCommandArgument('TYPE', sfCommandArgument::REQUIRED, 'My argument'),
                ));

		$this->addOptions(array(
		new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'masscomm'),
	     ));

	    $this->namespace        = 'cron';
	    $this->name             = 'ChooseMail';
	    $this->briefDescription = 'choose actutal mailer for mmm interface which have completed their test.';
	    $this->detailedDescription = <<<EOF
	    This cron will choose
            1. Mails who have successfully completed their tests run.
	  [php symfony cron:ChooseMail TYPE ] 
EOF;
  	}

	protected function execute($arguments = array(), $options = array())
  	{
                if(!sfContext::hasInstance())
                         sfContext::createInstance($this->configuration);
		$type = $arguments["TYPE"];
		$smarty = MmmUtility::createSmartyObject();
		$newMailer = new MmmMailerBasicInfo;

		if($type=='ACTUAL')
		{
			$statusArr[] = MmmConfig::$status['RUNNING'];
			$statusArr[] = MmmConfig::$status['FIRED'];
			$arr = $newMailer->retrieveMailersByStatus($statusArr);
		}
		elseif($type=='TEST')
			$arr = $newMailer->retrieveMailersByStatus(MmmConfig::$status['MARKED_FOR_TESTING']);	
		else
			;//throw excpetion.

		foreach($arr as $key => $value)
		{
			if($type=='ACTUAL')
			{
				shell_exec(JsConstants::$php5path." symfony cron:FireMail $type $key &");
				//$mailerId = $key;
				//$newMailer->updateStatus($mailerId, MmmConfig::$status['FIRED']);
			}
			elseif($type=='TEST')
			{
				shell_exec(JsConstants::$php5path." symfony cron:FireMail $type $key");
				//$mailerId = $key;
				//$newMailer->updateStatus($mailerId, MmmConfig::$status['TEST_COMPLETED']);
			}
		}
		//print_r($arr);
		//die;		
	}
}
