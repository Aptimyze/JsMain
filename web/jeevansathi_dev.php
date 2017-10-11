<?php

// this check prevents access to debug front controllers that are deployed by accident to production servers.
// feel free to remove this, extend it or make something more sophisticated.
if (!in_array(FetchClientIP(), array('127.0.0.1', '::1', '121.243.22.130', '115.249.243.194', '122.160.211.2', '110.234.12.82')))
{
  die('You are not allowed to access this file.');
}

$jsb9_track_stime=microtime(true);
require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');

$configuration = ProjectConfiguration::getApplicationConfiguration('jeevansathi', 'dev', true);
sfContext::createInstance($configuration)->dispatch();
