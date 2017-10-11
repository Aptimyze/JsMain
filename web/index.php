<?php
	if(JsConstants::$whichMachine !="matchAlert")
	{
		include_once(JsConstants::$docRoot."/profile/bot_attack_prevention.php");
		$jsb9_track_stime=microtime(true);
	}
        require_once(dirname(__FILE__).'/../config/ProjectConfiguration.class.php');
	if(JsConstants::$whichMachine=="local")
	        $configuration = ProjectConfiguration::getApplicationConfiguration('jeevansathi', 'dev', true);
	elseif(JsConstants::$whichMachine=="test")
		$configuration = ProjectConfiguration::getApplicationConfiguration('jeevansathi', 'test', false);
	else
		$configuration = ProjectConfiguration::getApplicationConfiguration('jeevansathi', 'prod', false);

	sfContext::createInstance($configuration)->dispatch();

