<?php
require_once dirname(__FILE__).'/../lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php';
sfCoreAutoload::register();

/** 
* Auto Load For MongoDb Client
*/
require_once dirname(__FILE__).'/../lib/vendor/mongo/composer' . '/autoload_real.php';
ComposerAutoloaderInit3a5e22b6d44c5dbef2f12e970538c922::getLoader();

class ProjectConfiguration extends sfProjectConfiguration
{
  public function setup()
  {
    $this->enablePlugins('sfSmarty3Plugin');
    $this->enablePlugins('sfMinifyPlugin');
    //$this->enablePlugins('sfRedisPlugin');
  }
}
