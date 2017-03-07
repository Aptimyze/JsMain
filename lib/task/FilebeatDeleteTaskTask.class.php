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
    $elkServer = '10.10.18.66';
    $elkPort = '9200';
    $indexDate = date('Y.m.d', strtotime( '-32 days' ));
    $indexName = "filebeat-$indexDate";
    passthru("curl -XDELETE 'http://$elkServer:$elkPort/$indexName/'");
  }
}