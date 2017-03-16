<?php
 
 class FilebeatDeleteTaskTask extends sfBaseTask
 {
  protected function configure()
  {
    $this->namespace        = 'kibana';
    $this->name             = 'FilebeatIndexDelete';
    $this->briefDescription = 'Delete data from the filebeat index';
    $this->detailedDescription = <<<EOF
    The [filebeatDelete|INFO] task does things.
    Call it with:
 
    [php symfony kibana:FilebeatIndexDelete|INFO]
EOF;
   }
 
  protected function execute($arguments = array(), $options = array())
  {
    // Server at which ElasticSearch and kibana is running
    $elkServer = JsConstants::$kibana['ELK_SERVER'];
    $elkPort = JsConstants::$kibana['ELASTIC_PORT'];
    $indexDate = date('Y.m.d', strtotime( '-32 days' ));
    $indexName = KibanaEnums::$FILEBEAT_INDEX."$indexDate";
    passthru("curl -XDELETE 'http://$elkServer:$elkPort/$indexName/'");
  }
}