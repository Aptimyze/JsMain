<?php
$curFilePath = dirname(__FILE__)."/";
include_once("/usr/local/scripts/DocRoot.php");
chdir(dirname(__FILE__));
include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect.inc");
include_once("housekeepingConfig.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
$db=connect_ddl();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db);
$dbSlave=connect_slave();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbSlave);
$mysqlObj=new Mysql;

$timestamp=mktime(0, 0, 0, date("m")  , date("d"), date("Y")-2);
$archiveDate=date("Y-m-d",$timestamp);
//for Dev Purpose
$devTesting=0;

for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{       $myDbName=getActiveServerName($activeServerId);       $myDbArr[$myDbName]=$mysqlObj->connect("$myDbName");       mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$myDbArr[$myDbName]);
}

for($activeServerId=0;$activeServerId<$noOfActiveServers;$activeServerId++)
{
	
	$dbNameS=getActiveServerName($activeServerId,"masterDDL");
	$dbM=$mysqlObj->connect($dbNameS);
	mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbS);
	
	$dbNameS=getActiveServerName($activeServerId,"slave");
	$dbS=$mysqlObj->connect($dbNameS);
	mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbS);
	mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbS);


	//PHOTO REQUEST
	if($devTesting){
		$to = "nitesh.s@jeevansathi.com";
		$from = "info@jeevansathi.com";
		$subject = "Drop table";
		$msgBody = "Drop table in crontabs/housekeeping/oneTimeCreateArchiveTables.php";
		send_email($to,$msgBody,$subject,$from);
	
		$sql_drop_photoArchive="DROP TABLE IF EXISTS newjs.PHOTO_REQUEST_ARCHIVE";
		$res=mysql_query($sql_drop_photoArchive,$dbM) or die(mysql_error($dbM).$sql_drop_photoArchive);
	}
	$sql_create_photoArchive="CREATE TABLE `PHOTO_REQUEST_ARCHIVE` (`PROFILEID` int(11) NOT NULL DEFAULT '0',`PROFILEID_REQ_BY` int(11) NOT NULL DEFAULT '0',`DATE` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',`CNT` smallint(6) NOT NULL DEFAULT '0',`SEND_MAIL` char(1) NOT NULL DEFAULT '',`SEEN` char(1) NOT NULL,`UPLOAD_SEEN` char(1) NOT NULL,`UPLOAD_DATE` date NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=latin1";
	$res=mysql_query($sql_create_photoArchive,$dbM) or die(mysql_error($dbM).$sql_create_photoArchive);
	
	//HOROSCOPE REQUEST	
	if($devTesting){
		$sql_drop_horoArchive="DROP TABLE IF EXISTS newjs.HOROSCOPE_REQUEST_ARCHIVE";
		$res=mysql_query($sql_drop_horoArchive,$dbM) or die(mysql_error($dbM).$sql_drop_horoArchive);
	}
	
	 $sql_create_horoscope="CREATE TABLE `HOROSCOPE_REQUEST_ARCHIVE` (`PROFILEID` int(11) NOT NULL DEFAULT '0',`PROFILEID_REQUEST_BY` int(11) NOT NULL DEFAULT '0',`DATE` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',`SEND_MAIL` varchar(1) NOT NULL,`CNT` smallint(6) NOT NULL,`SEEN` char(1) NOT NULL,`UPLOAD_SEEN` char(1) NOT NULL,`UPLOAD_DATE` date NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=latin1";
	$res=mysql_query($sql_create_horoscope,$dbM) or die(mysql_error($dbM).$sql_create_horoscope);
	
	//CONTACTS -- EOI -- MESSAGE_LOG -- MESSAGES
	//CONTACTS 
	if($devTesting){
		$sql_drop_profileContactsArchive="DROP TABLE IF EXISTS newjs.PROFILE_CONTACTS_ARCHIVE";
		$res=mysql_query($sql_drop_profileContactsArchive,$dbM) or die(mysql_error($dbM).$sql_drop_profileContactsArchive);
	}
	
	 $sql_create_profileContactsArchive="CREATE TABLE `PROFILE_CONTACTS_ARCHIVE` (`CONTACTID` int(11) NOT NULL DEFAULT '0',`SENDER` mediumint(11) unsigned NOT NULL DEFAULT '0',`RECEIVER` mediumint(11) unsigned NOT NULL DEFAULT '0',`TYPE` char(1) NOT NULL DEFAULT '',`TIME` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',`COUNT` smallint(11) unsigned NOT NULL DEFAULT '0',`MSG_DEL` char(1) NOT NULL DEFAULT '',`SEEN` char(1) NOT NULL,`FILTERED` char(1) NOT NULL,`FOLDER` char(3) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=latin1";
	$res=mysql_query($sql_create_profileContactsArchive,$dbM) or die(mysql_error($dbM).$sql_create_profileContactsArchive);

	//EOI
	if($devTesting){
		$sql_drop_eoiViewedLogArchive="DROP TABLE IF EXISTS newjs.EOI_VIEWED_LOG_ARCHIVE";
		$res=mysql_query($sql_drop_eoiViewedLogArchive,$dbM) or die(mysql_error($dbM).$sql_drop_eoiViewedLogArchive);
	}
	
	 $sql_create_eoiViewedLogArchive="CREATE TABLE `EOI_VIEWED_LOG_ARCHIVE` (`VIEWER` mediumint(8) NOT NULL DEFAULT '0',`VIEWED` mediumint(8) NOT NULL DEFAULT '0',`DATE` datetime NOT NULL DEFAULT '0000-00-00 00:00:00') ENGINE=MyISAM DEFAULT CHARSET=latin1";
	$res=mysql_query($sql_create_eoiViewedLogArchive,$dbM) or die(mysql_error($dbM).$sql_create_eoiViewedLogArchive);


	//MESSAGES
	if($devTesting){
		$sql_drop_messagesArchive="DROP TABLE IF EXISTS newjs.MESSAGES_ARCHIVE";
		$res=mysql_query($sql_drop_messagesArchive,$dbM) or die(mysql_error($dbM).$sql_drop_messagesArchive);
	}
	
	 $sql_create_messagesArchive="CREATE TABLE `MESSAGES_ARCHIVE` ( `ID` int(11) NOT NULL, `MESSAGE` text NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=latin1";
	$res=mysql_query($sql_create_messagesArchive,$dbM) or die(mysql_error($dbM).$sql_create_messagesArchive);


	//MESSAGE_LOG
	 if($devTesting){
		$sql_drop_messageLogArchive="DROP TABLE IF EXISTS newjs.MESSAGE_LOG_ARCHIVE";
		$res=mysql_query($sql_drop_messageLogArchive,$dbM) or die(mysql_error($dbM).$sql_drop_messageLogArchive);
	}
	
	 $sql_create_messageLogArchive="CREATE TABLE `MESSAGE_LOG_ARCHIVE`  (`SENDER` mediumint(8) unsigned NOT NULL DEFAULT '0',`RECEIVER` mediumint(8) unsigned NOT NULL DEFAULT '0',`DATE` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',`IP` int(10) unsigned DEFAULT NULL,`RECEIVER_STATUS` char(1) NOT NULL DEFAULT 'U',`FOLDERID` mediumint(9) NOT NULL DEFAULT '0',`MSG_OBS_ID` int(11) NOT NULL DEFAULT '0',`SENDER_STATUS` char(1) NOT NULL DEFAULT 'U',`TYPE` char(1) NOT NULL DEFAULT 'R',`ID` int(11) NOT NULL,`OBSCENE` char(1) NOT NULL DEFAULT 'N',`IS_MSG` char(1) NOT NULL DEFAULT 'N',`SEEN` char(1) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=latin1";
	$res=mysql_query($sql_create_messageLogArchive,$dbM) or die(mysql_error($dbM).$sql_create_messageLogArchive);
	
}

//VIEW_LOG

$db_211=connect_viewLogDDL();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db_211);


	if($devTesting){
		$sql_drop_viewLogArchive="DROP TABLE IF EXISTS newjs.VIEW_LOG_ARCHIVE";
		$res=mysql_query($sql_drop_viewLogArchive,$db_211) or die(mysql_error($db_211).$sql_drop_viewLogArchive);
	}
	 $sql_create_viewLogArchive="CREATE TABLE `VIEW_LOG_ARCHIVE`(`VIEWER` int(8) unsigned NOT NULL DEFAULT '0',`VIEWED` int(8) unsigned NOT NULL DEFAULT '0',`DATE` date NOT NULL DEFAULT '0000-00-00',`VIEWED_MMM` char(1) NOT NULL DEFAULT 'N') ENGINE=MyISAM DEFAULT CHARSET=latin1";
	$res=mysql_query($sql_create_viewLogArchive,$db_211) or die(mysql_error($db_211).$sql_create_viewLogArchive);


//Bookmarks
	if($devTesting){
		$sql_drop_bookmarksArchive="DROP TABLE IF EXISTS newjs.BOOKMARKS_ARCHIVE";
		$res=mysql_query($sql_drop_bookmarksArchive,$db) or die(mysql_error($db).$sql_drop_bookmarksArchive);
	}
	 $sql_create_bookmarksArchive="CREATE TABLE `BOOKMARKS_ARCHIVE`(`BOOKMARKER` int(11) unsigned NOT NULL DEFAULT '0',`BOOKMARKEE` int(11) unsigned NOT NULL DEFAULT '0',`BKDATE` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',`BKNOTE` varchar(255) NOT NULL,`SEEN` char(1) NOT NULL) ENGINE=MyISAM DEFAULT CHARSET=latin1";
	$res=mysql_query($sql_create_bookmarksArchive,$db) or die(mysql_error($db).$sql_create_bookmarksArchive);
