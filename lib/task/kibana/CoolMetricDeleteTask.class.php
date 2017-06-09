<?php

class CoolMetricDeleteTask extends sfBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'kibana';
    $this->name             = 'CoolMetricDeleteTask';
    $this->briefDescription = 'Delete data from the cool metric index';
    $this->detailedDescription = <<<EOF
The [kibana:CoolMetricDeleteTask|INFO] task does things.
Call it with:

  [php symfony kibana:CoolMetricDeleteTask|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    $currdate = date('Y.m.d', strtotime( '-16 days' ));
    // Server at which ElasticSearch and kibana is running
    $elkServer = JsConstants::$kibana['ELK_SERVER'];
    $elkPort = JsConstants::$kibana['ELASTIC_PORT'];
    $indexName = KibanaEnums::$COOLMETRIC_INDEX.$currdate;
    passthru("curl -XDELETE 'http://$elkServer:$elkPort/$indexName/'");
  }
}
