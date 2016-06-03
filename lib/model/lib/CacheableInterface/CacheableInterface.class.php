<?php

interface CacheableInterface {
  
  public function set($key);
  
  public function get($key);

  public function removePoolEntry($key);

  public function exists($key);
}
