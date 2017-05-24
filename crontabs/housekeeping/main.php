<?php
passthru(JsConstants::$php5path." -q main_createTablesInactive_and_deleted.php");

passthru(JsConstants::$php5path." -q main_shardslave.php 0");
passthru(JsConstants::$php5path." -q main_shardmaster.php 0");
passthru(JsConstants::$php5path." -q main_masterdb.php");

passthru(JsConstants::$php5path." -q main_shardslave.php 1");
passthru(JsConstants::$php5path." -q main_shardslave.php 2");
passthru(JsConstants::$php5path." -q main_shardmaster.php 1");
passthru(JsConstants::$php5path." -q main_shardmaster.php 2");

passthru(JsConstants::$php5path." -q mainshard_createInactiveTables.php 0");
passthru(JsConstants::$php5path." -q mainshard_createInactiveTables.php 1");
passthru(JsConstants::$php5path." -q mainshard_createInactiveTables.php 2");

?>
