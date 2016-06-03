<?php
/****************************************************************************************************/
  /*
        *       Created By              :       Shobha Kumari
	*	Dated 			:	29.09.2005
        *       Description             :       This file is a configuration file responsible for
						defining variables for smarty , database connection for
						different servers etc.(used for bms display)
*******************************************************************************************************/
global $_SVNENABLE , $_SVN;
global $_SVN,$_LOGPATHS,$_SMARTYPATHS;
global $_LOGPATH,$_HITSFILE,$_ADDLOGPATH ,$dbbms;
global $_HOST_NAME , $_USER , $_PASSWORD , $_SITEURL;

$_SVNENABLE="false";//value set to "true", will work only after authentication of user
$_HOST_NAME = MysqlDbConstants::$bms["HOST"];
$_TPLPATH= JsConstants::$bmsDocRoot."/bmsjs/templates/bmsjs";
$_COMPILEPATH= JsConstants::$bmsDocRoot."/bmsjs/templates_c";
$_ADDLOGPATH="/bmsjs/log";
$_LOGPATHS = JsConstants::$docRoot;
$_SITEURL = JsConstants::$bmsUrl;
$_USER = MysqlDbConstants::$bms["USER"];
$_PASSWORD = MysqlDbConstants::$bms["PASS"];
$_SMARTYPATHS =JsConstants::$smartyDir;
$_HITSFILE = "bms_hits.php";
?>
