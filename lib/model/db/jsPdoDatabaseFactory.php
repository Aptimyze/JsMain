<?php
class jsPdoDatabaseFactory implements jsDatabaseFactory {
  public function createDatabase($params) {
    $jsDnsResolver = new jsDnsResolver();
    return new jsPdoDatabase($jsDnsResolver->resolveDNS($params['dsn']), $params['username'], $params['password'], array_key_exists('reconnect', $params) && $params['reconnect'] === true, array_key_exists('debug', $params) && $params['debug'] === true);
  }
}
?>
