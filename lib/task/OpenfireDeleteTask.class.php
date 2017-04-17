<?php

class OpenfireDeleteTask extends sfBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'kibana';
    $this->name             = 'OpenfireIndexDelete';
    $this->briefDescription = '';
    $this->detailedDescription = <<<EOF
The [OpenfireDelete|INFO] task does things.
Call it with:

  [php symfony kibana:OpenfireIndexDelete|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $currdate = date('Y.m.d', strtotime( '-8 days' ));
    // Server at which ElasticSearch and kibana is running
    $elkServer = JsConstants::$kibana['ELK_SERVER'];
    $elkPort = JsConstants::$kibana['ELASTIC_PORT'];
    $indexName = KibanaEnums::$OPENFIRE_INDEX.$currdate;
    passthru("curl -XDELETE 'http://$elkServer:$elkPort/$indexName/'");
  }
}
