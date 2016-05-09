<?php
require_once JsConstants::$cronDocRoot.'/lib/vendor/symfony/lib/autoload/sfCoreAutoload.class.php';
require_once(JsConstants::$cronDocRoot.'/config/ProjectConfiguration.class.php');
if(JsConstants::$whichMachine=="local")
        $configuration =ProjectConfiguration::getApplicationConfiguration('jeevansathi', 'dev',true);
elseif(JsConstants::$whichMachine=="test")
        $configuration = ProjectConfiguration::getApplicationConfiguration('jeevansathi', 'test', false);
else
        $configuration =ProjectConfiguration::getApplicationConfiguration('jeevansathi', 'prod', false);

