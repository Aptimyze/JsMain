<?php

class cronExecuteInstantEOIQueueTask extends sfBaseTask
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
    $this->name             = 'cronExecuteInstantEOIQueue';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [cronExecuteInstantEOIQueue|INFO] task does things.
Call it with:

  [php symfony cronExecuteInstantEOIQueue|INFO]
EOF;

  $this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi')
    ));
  }

  protected function execute($arguments = array(), $options = array())
  {
    if (!sfContext::hasInstance())
      sfContext::createInstance($this->configuration);

    $instancesNum = MessageQueues::INSTANTEOICONSUMERCOUNT;
    for($i=1;$i<=$instancesNum;$i++)
    {
      passthru(JsConstants::$php5path." ".MessageQueues::CRON_INSTANT_EOI_QUEUE_CONSUMER_STARTCOMMAND." > /dev/null &");
    }
  }
}
