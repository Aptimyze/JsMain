<?php

class ConsumerDeleteTask extends sfBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'kibana';
    $this->name             = 'ConsumerIndexDelete';
    $this->briefDescription = 'Delete data from the consumer index';
    $this->detailedDescription = <<<EOF
The [kibana:ConsumerIndexDelete|INFO] task does things.
Call it with:

  [php symfony kibana:ConsumerIndexDelete|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $currdate = date('Y.m.d', strtotime( '-8 days' ));
    // Server at which ElasticSearch and kibana is running
    $elkServer = JsConstants::$kibana['ELK_SERVER'];
    $elkPort = JsConstants::$kibana['ELASTIC_PORT'];
    $indexName = KibanaEnums::$CONSUMER_INDEX.$currdate;
    passthru("curl -XDELETE 'http://$elkServer:$elkPort/$indexName/'");
  }
}
