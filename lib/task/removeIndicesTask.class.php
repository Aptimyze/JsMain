<?php

class removeIndicesTask extends sfBaseTask
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

    $this->namespace        = 'Removing Indices';
    $this->name             = 'removeIndices';
    $this->briefDescription = 'This cron is used to remove the indices which are created by kibana ';
    $this->detailedDescription = <<<EOF
The [removeIndices|INFO] task does things.
Call it with:

  [php symfony removeIndices|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // add your code here
    $path = '/data/applogs/';
    $date = strtotime(date("Y.m.d", strtotime("-2 day")));
    $indexName = 'filebeat-'.$date;
    $commandToRun = 'rm '.$path.$indexName;
    exec($commandToRun);
  }
}
