<?php
/*
This php script is run to create object of rabbitmq screening Mailer Consumer class and call 
the receiveMessage function to let the consumer receive profile ids on first server.
*/

class cronConsumeScreeningMailerQueueTask extends sfBaseTask
{
  protected function configure()
  {
    $this->namespace           = 'cron';
    $this->name                = 'cronConsumeScreeningMailerQueueTask';
    $this->briefDescription    = 'Initialises instance of rabbitmq consumer class to retrieve successfully screened profiles on first server';
    $this->detailedDescription = <<<EOF
     The [cronConsumeScreeningMai;erQueueTask|INFO] calls receiveMessage on first server:
     [php symfony cron:cronConsumeScreeningMailerQueueTask] 
EOF;
    $this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi')
    ));
  }

  /**
   * 
   * Function for executing cron- creates JsNotificationsConsume class object and calls receiveMessage func to consume notification messages on FIRST_SERVER.
   * 
   * @access protected
   * @param $arguments,$options
   */
  protected function execute($arguments = array(), $options = array())
  {
    if (!sfContext::hasInstance())
      sfContext::createInstance($this->configuration);
    $consumerObj=new ScreeningMailerConsumer('FIRST_SERVER',0);  //If $serverid='FIRST_SERVER', then 2nd param in Consumer constructor is not taken into account.
    $consumerObj->receiveMessage(); 
  }

}
?>
