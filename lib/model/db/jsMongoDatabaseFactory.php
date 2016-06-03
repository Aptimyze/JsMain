<?php
class jsMongoDatabaseFactory implements jsDatabaseFactory {
  public function createDatabase($params) {
    $serverOne = null;
    $serverTwo = null;
    
    $jsDnsResolver = new jsDnsResolver();
    
    if(isset($params['dsn'])) {
      $dsn = $jsDnsResolver->resolveDNS($params['dsn']);
    }
    
    return new jsMongoDatabase($dsn, $params['username'], $params['password'], $params['replicaSet'],array_key_exists('reconnect', $params) && $params['reconnect'] === true, array_key_exists('debug', $params) && $params['debug'] === true, $params['default_db']);
    
  }
}
?>
