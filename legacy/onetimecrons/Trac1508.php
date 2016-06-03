<?php

if(!$nonSymfony)
{
	if($_SERVER['DOCUMENT_ROOT'])
		$symfonyFilePath=$_SERVER['DOCUMENT_ROOT']."/../";
	else
		$symfonyFilePath = "/var/www/html/";

	include_once("$symfonyFilePath/config/ProjectConfiguration.class.php");
	//getting config path
	$sfProjectConfiguration=new sfProjectConfiguration;
	$symfonyConfigPath=$sfProjectConfiguration->getRootDir()."/config";
	//getting config path

  include_once("$symfonyFilePath/lib/model/db/jsDatabaseException.php");
	include_once("$symfonyFilePath/lib/model/db/jsDatabaseManager.php");
	include_once("$symfonyFilePath/lib/model/db/jsDatabaseFactory.php");
	include_once("$symfonyFilePath/lib/model/db/jsDatabase.php");
	include_once("$symfonyFilePath/lib/model/db/jsPdoDatabaseFactory.php");
	include_once("$symfonyFilePath/lib/model/db/jsDnsResolver.php");
	include_once("$symfonyFilePath/lib/model/db/jsPdoDatabase.php");

	include_once("$symfonyFilePath/lib/model/store/TABLE.class.php");
  include_once("$symfonyFilePath/lib/model/lib/JsDbSharding.class.php");

	//start: including app.yml
	include_once("$symfonyFilePath/lib/vendor/symfony/lib/yaml/sfYaml.php");
	$appDotYml = sfYaml::load ("$symfonyFilePath/apps/operations/config/app.yml");
	$jeevansathiAppDotYml = sfYaml::load ("$symfonyFilePath/apps/jeevansathi/config/app.yml");
	//end: including app.yml
}

  //Common SQL query for all three shards
  $sql = "SELECT PROFILEID, PARTNER_MSTATUS FROM JPARTNER WHERE PARTNER_MSTATUS LIKE \"%\\\\\\%\"";
  
  //Perform update operation shard wise.
  for ($i = 0; $i <= 2; ++$i) {
    $slave_db = null;
    $master_db = null;
    $res = null;
    $row = null;
    $slave_db = jsDatabaseManager::getInstance()->getDatabase(JsDbSharding::getShardNo($i, 1))->getConnection();
    $res = $slave_db->prepare($sql);
    $res->execute();
    while ($row = $res->fetch(PDO::FETCH_ASSOC)) {
      $profileid = $row[PROFILEID];
      $pmstatus = $row[PARTNER_MSTATUS];

      $new_pmstatus = str_replace("\\", "", $pmstatus);
      $update_sql = "UPDATE JPARTNER SET PARTNER_MSTATUS = :PMSTATUS WHERE PROFILEID = :PROFILEID";
      $master_db = jsDatabaseManager::getInstance()->getDatabase(JsDbSharding::getShardNo($i))->getConnection();
      try {
        $res1 = $master_db->prepare($update_sql);
        $res1->bindValue(":PMSTATUS", $new_pmstatus, PDO::PARAM_STR);
        $res1->bindValue(":PROFILEID", $profileid, PDO::PARAM_INT);
        $res1->execute();
      }
      catch (PDOException $e) {
        throw new jsException($e);
      }
    }
    unset($master_db);
    unset($slave_db);
  }
  
