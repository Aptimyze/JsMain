<?php

class cronConsumeWriteMessageQUEUETask extends sfBaseTask
{
	protected function configure()
	{
		$this->namespace        = 'cron';
		$this->name             = 'cronConsumeWriteMessageQUEUE';
		$this->briefDescription = 'Initialises instance of rabbitmq consumer class to retrieve stored write messages on first server';
	$this->detailedDescription = <<<EOF
The [cronConsumeWriteMessageQUEUE|INFO] task does things.
Call it with:

  [php symfony cron:cronConsumeWriteMessageQUEUE|INFO]
EOF;
		$this->addOptions(array(
			new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi')
		));
  	}

	protected function execute($arguments = array(), $options = array())
	{
		if (!sfContext::hasInstance())
		sfContext::createInstance($this->configuration);

		$consumerObj = new WriteMessageConsumer('FIRST_SERVER',0);  //If $serverid='FIRST_SERVER', then 2nd param in Consumer constructor is not taken into account.
		$consumerObj->receiveMessage();
	}
}
