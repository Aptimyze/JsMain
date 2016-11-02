<?php

class ConsumerDeleteTask extends sfBaseTask
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

    $this->namespace        = 'consumer';
    $this->name             = 'ConsumerDelete';
    $this->briefDescription = 'Delete data from the consumer index';
    $this->detailedDescription = <<<EOF
The [ConsumerDelete|INFO] task does things.
Call it with:

  [php symfony ConsumerDelete|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $currdate = date('Y.m.d', strtotime( '-1 days' ));
    // Server at which ElasticSearch and kibana is running
    $elkServer = '10.10.18.66';
    $elkPort = '9200';
    $indexName = 'consumer-'.$currdate;
    passthru("curl -XDELETE 'http://$elkServer:$elkPort/$indexName/'");
  }
}
