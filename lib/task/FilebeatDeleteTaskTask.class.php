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
    $currdate = date('Y-m-d', strtotime( '-32 days' ));
    $start = '2016-09-01';
    $end = $currdate;
    $dates = array($start);
    // Server at which ElasticSearch and kibana is running
    $elkServer = '10.10.18.66';
    $elkPort = '9200';
    while(end($dates) < $end)
    {
      $dates[] = date('Y-m-d', strtotime(end($dates).' +1 day'));
    }
    foreach ($dates as $key => $value)
    {
      $indexDate = date('Y.m.d',strtotime($value));
      $indexName = "filebeat-$indexDate";
      // passthru("curl -XDELETE 'http://$elkServer:$elkPort/$indexName/'");
    }
  }
}