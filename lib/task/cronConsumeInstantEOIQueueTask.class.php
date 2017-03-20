<?php

class cronConsumeInstantEOIQueueTask extends sfBaseTask
{
  protected function configure()
  {
    // // add your own arguments here
    // $this->addArguments(array(
    //   new sfCommandArgument('my_arg', sfCommandArgument::REQUIRED, 'My argument'),
    // ));

    // // add your own options here
    // $this->addOptions(array(
    //   new sfCommandOption('my_option', null, sfCommandOption::PARAMETER_REQUIRED, 'My option'),
    // ));

    $this->namespace        = '';
    $this->name             = 'cronConsumeInstantEOIQueue';
    $this->briefDescription    = 'reads no. of instances of rabbitmq JsNotificationsConsume from MessageQueues.enum.class.php to be run and executes that many instances of cronConsumeNotificationsQueueMessage.';
    $this->detailedDescription = <<<EOF
The [cronConsumeInstantEOIQueue|INFO] task does things.
Call it with:

  [php symfony cronConsumeInstantEOIQueue|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    if (!sfContext::hasInstance())
      sfContext::createInstance($this->configuration);

    $consumerObj = new InstantEoiNotifyConsumer('FIRST_SERVER',0);  //If $serverid='FIRST_SERVER', then 2nd param in Consumer constructor is not taken into account.
    $consumerObj->receiveMessage(); 
  }
}
