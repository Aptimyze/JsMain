<?php

class IndexDeletionTask extends sfBaseTask
{
  protected function configure()
  {
    $this->namespace        = 'kibana';
    $this->name             = 'IndexDeletionTask';
    $this->briefDescription = 'Delete indices from elk.';
    $this->detailedDescription = <<<EOF
The [kibana:IndexDeletionTask|INFO] task does things.
Call it with:
  [php symfony kibana:IndexDeletionTask|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
    // Server at which ElasticSearch and kibana is running
    $elkServer = JsConstants::$kibana['ELK_SERVER'];
    $elkPort = JsConstants::$kibana['ELASTIC_PORT'];
    // print_r(KibanaEnums::$COOLMETRIC_INDEX_DELETION_LIMIT );
    $indicesToDelete = array(
                        KibanaEnums::$COOLMETRIC_INDEX.date('Y.m.d', strtotime( KibanaEnums::$COOLMETRIC_INDEX_DELETION_LIMIT )),
                        KibanaEnums::$FILEBEAT_INDEX.date('Y.m.d', strtotime( KibanaEnums::$FILEBEAT_INDEX_DELETION_LIMIT )),
                        KibanaEnums::$ANDROIDCHAT_INDEX.date('Y.m.d', strtotime( KibanaEnums::$ANDROIDCHAT_INDEX_DELETION_LIMIT )),
                        KibanaEnums::$OPENFIRE_INDEX.date('Y.m.d', strtotime( KibanaEnums::$OPENFIRE_INDEX_DELETION_LIMIT )),
                        KibanaEnums::$APACHE_INDEX.date('Y.m.d', strtotime( KibanaEnums::$APACHE_INDEX_DELETION_LIMIT )),
                        KibanaEnums::$SERVER_INDEX.date('Y.m.d', strtotime( KibanaEnums::$SERVER_INDEX_DELETION_LIMIT )),
                        KibanaEnums::$RABBITTIME_INDEX.date('Y.m.d', strtotime( KibanaEnums::$RABBITTIME_INDEX_DELETION_LIMIT )),
                        );

    foreach ($indicesToDelete as $indexName) {
      passthru("curl -XDELETE 'http://$elkServer:$elkPort/$indexName/'");
    }
  }
}
