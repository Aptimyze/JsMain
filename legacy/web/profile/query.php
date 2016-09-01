<?
include("connect.inc");
$db=connect_db();
mysql_select_db_js("newjs");

//Sql for creating table that will maintain record of user login on day basis
$login_history_count="CREATE TABLE `LOGIN_HISTORY_COUNT` ( `PROFILEID` mediumint(11) unsigned NOT NULL default '0', `TOTAL_COUNT` varchar(100) NOT NULL default '0', PRIMARY KEY  (`PROFILEID`))";
mysql_query_decide($login_history_count);

//SQl for createing table PAGE_VIEWS that will maintain how many page viewed by user
$page_views=" CREATE TABLE `PAGE_VIEWS` ( `PROFILEID` mediumint(11) unsigned NOT NULL default '0', `TOTAL_COUNT` varchar(100) NOT NULL default '0', PRIMARY KEY  (`PROFILEID`)) ";
mysql_query_decide($page_views);

//SQL for updating table USERSTARTPAYING
$userstart="ALTER TABLE `USER_STARTS_PAYING` ADD `SEARCHES` MEDIUMINT( 11 ) NOT NULL DEFAULT '0',ADD `PAGE_VEIWS` MEDIUMINT( 11 ) NOT NULL DEFAULT '0'";
mysql_query_decide($userstart);
?>
