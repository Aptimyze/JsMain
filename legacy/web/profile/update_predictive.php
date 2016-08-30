<?
include("connect.inc");
$db=connect_slave();
mysql_select_db_js();
$sql1="ALTER TABLE `JS_PREDICTIVE`  DROP `HAVEPHOTO`";
//mysql_query_decide($sql1) or die(mysql_error_js());

$sql2="ALTER TABLE `JS_PREDICTIVE` CHANGE `SOURCE` `SOURCE` VARCHAR( 70 ),CHANGE `DRINK` `DRINK` VARCHAR( 20 ) ,CHANGE `SMOKE` `SMOKE` VARCHAR( 20 )  ";
mysql_query_decide($sql2)  or die(mysql_error_js());


$sql4="ALTER TABLE `JS_PREDICTIVE` ADD `PROFILE_LENGTH` MEDIUMINT( 11 ) NOT NULL DEFAULT '0',ADD `SCORE` MEDIUMINT( 11 ) NOT NULL DEFAULT '0'";
mysql_query_decide($sql4)  or die(mysql_error_js());

?>
