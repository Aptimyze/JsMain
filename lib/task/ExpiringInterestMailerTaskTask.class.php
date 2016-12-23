<?php

class ExpiringInterestMailerTaskTask extends sfBaseTask
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
    $this->name             = 'ExpiringInterestMailerTask';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [ExpiringInterestMailerTask|INFO] task does things.
Call it with:

  [php symfony ExpiringInterestMailerTask|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // add your code here
  }
}
