<?php
$jsb9_track_stime=microtime(true);
require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');
if(JsConstants::$whichMachine=="local")
	$configuration = ProjectConfiguration::getApplicationConfiguration('operations', 'dev', true);
elseif(JsConstants::$whichMachine=="test")
	$configuration = ProjectConfiguration::getApplicationConfiguration('operations', 'test', false);
else
	$configuration = ProjectConfiguration::getApplicationConfiguration('operations', 'prod', false);
sfContext::createInstance($configuration)->dispatch();
?>
