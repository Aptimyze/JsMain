<?php
/*
* Author: Lavesh Rawat
* This cron is used to kill the cron.
*/
class KillMailTask extends sfBaseTask
{

 	protected function configure()
  	{
		$this->addOptions(array(
		new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'masscomm'),
	     ));

	    $this->namespace        = 'cron';
	    $this->name             = 'KillMail';
	    $this->briefDescription = 'kill the cron';
	    $this->detailedDescription = <<<EOF
	    This cron will fire
	  [php symfony cron:KillMail] 
EOF;
  	}

	protected function execute($arguments = array(), $options = array())
  	{
                if(!sfContext::hasInstance())
                         sfContext::createInstance($this->configuration);

		$whereParamArray["STATUS"] = 'N';
		$mmmjs_MAILER_STOPED = new mmmjs_MAILER_STOPED;
		$arr = $mmmjs_MAILER_STOPED->get($whereParamArray,'MAILER_ID');
		if(is_array($arr))
		foreach($arr as $k=>$v)
		{
			$msg = "pkill -f 'symfony cron:FireMail ACTUAL $v[MAILER_ID]'";
			passthru($msg);
			$mmmjs_MAILER_STOPED->updateStatus($v["MAILER_ID"]);
		}
	}
}
