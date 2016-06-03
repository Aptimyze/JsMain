<?php

function isJenkinJob()
{
	$jenkinHomePath = '/var/lib/jenkins/jobs';
	return stristr(dirname(__FILE__),$jenkinHomePath);
}

$arrToken= explode('/',dirname(__FILE__));
if(isJenkinJob())
	$branchName = 'jenkinsJobs/'.$arrToken[count($arrToken) - 4];
else
	$branchName = $arrToken[count($arrToken) - 3];

$configPath = '/usr/local/scripts/config/';
require_once $configPath.$branchName.'/JsConstants.class.php';
require_once $configPath.'/MysqlDbConstants.class.php';
require_once $configPath.'/MongoDbConstants.class.php';
require_once $configPath.'/environmentFunctions.php';

$_test_dir = realpath(dirname(__FILE__).'/..');

// configuration
require_once dirname(__FILE__).'/../../config/ProjectConfiguration.class.php';
$configuration = ProjectConfiguration::getApplicationConfiguration('jeevansathi', 'dev', true);
//create instance 
if(!sfContext::hasInstance())
	sfContext::createInstance($configuration);
// autoloader
$autoload = sfSimpleAutoload::getInstance(sfConfig::get('sf_cache_dir').'/project_autoload.cache');
$autoload->loadConfiguration(sfFinder::type('file')->name('autoload.yml')->in(array(
  sfConfig::get('sf_symfony_lib_dir').'/config/config',
  sfConfig::get('sf_config_dir'),
)));
$autoload->register();
?>
