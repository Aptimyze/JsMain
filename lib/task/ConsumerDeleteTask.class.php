<?php

class ConsumerDeleteTask extends sfBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'kibana';
    $this->name             = 'ConsumerIndexDelete';
    $this->briefDescription = 'Delete data from the consumer index';
    $this->detailedDescription = <<<EOF
The [ConsumerDelete|INFO] task does things.
Call it with:

  [php symfony kibana:ConsumerIndexDelete|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $currdate = date('Y-m-d', strtotime( '-8 days' ));
    // Server at which ElasticSearch and kibana is running
    $elkServer = '10.10.18.66';
    $elkPort = '9200';
    $indexName = "consumer-$currdate";
    passthru("curl -XDELETE 'http://$elkServer:$elkPort/$indexName/'");
  }
}
