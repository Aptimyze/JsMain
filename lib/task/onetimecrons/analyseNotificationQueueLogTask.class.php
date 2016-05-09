<?php
/* This class runs a cron to read count of notifications pushed and transferred from log files*/


class analyseNotificationQueueLogTask extends sfBaseTask
{
	protected function configure()
	{
		$this->addOptions(array(
			new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','operations'),
		));

		$this->namespace        = 'CRM';
		$this->name             = 'analyseNotificationQueueLog';
		$this->briefDescription = 'read count of notifications pushed and transferred from log files';
		$this->detailedDescription = <<<EOF
		The [analyseNotificationQueueLog|INFO] task does things.
		Call it with:
		[php symfony CRM:analyseNotificationQueueLog|INFO]
EOF;
	}

	protected function execute($arguments = array(), $options = array())
	{
		if(!sfContext::hasInstance())
		{
			sfContext::createInstance($this->configuration);
		}
    
    $pushedTxtPath = JsConstants::$cronDocRoot.BrowserNotificationEnums::$publishedNotificationLog;
    $transferredTxtPath = JsConstants::$cronDocRoot.BrowserNotificationEnums::$transferredNotificationlog;
    $pushedCount = array();
    $transferredCount = array();
    foreach (MessageQueues::$notificationArr as $notificationKey => $bindingValue) {
      $pushedCount[$notificationKey] = substr_count(file_get_contents($pushedTxtPath), $notificationKey);
      $transferredCount[$notificationKey] = substr_count(file_get_contents($transferredTxtPath), $notificationKey);
    }
    echo "----pushed notifications count-----";
    print_r($pushedCount);
    echo "----transferred notifications count----";
    print_r($transferredCount);
   	die;
		
	}
}
