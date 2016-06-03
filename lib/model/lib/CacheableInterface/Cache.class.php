<?php

include_once("CacheableInterface.class.php");

class Cache {
  protected $__cache_backend;

  public function __construct(CacheableInterface $cache_backend) {
    $this->__cache_backend = $cache_backend;
  }

  public function set($key, $data) {
    if (isset($key) && isset($data)) {
      $set_value = $this->__cache_backend->set($key, $data);
      return $set_value;
    }
    else {
      throw new ObjectNotFoundException('Key and Data fields not set');
    }
  }

  public function get($key,$loggedin=false) {
    $key = trim($key);
    if ($key !== '') {
      return $this->__cache_backend->get($key,$loggedin);
    }
    else {
      throw new ProfileIdNotProvidedException('No profile ID provided');
    }
  }

  public function remove($key) {
    return $this->__cache_backend->removePoolEntry($key);
  }

  public function getCacheStats() {
    return $this->__cache_backend->getCacheStats();
  }

  public function getContents() {
    $this->__cache_backend->getContents();
  }

  public function flushCache() {
    $this->__cache_backend->flushCache();
  }
}
