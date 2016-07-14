<?php 
$curFilePath = dirname(__FILE__)."/"; 
include_once("/usr/local/scripts/DocRoot.php");

chdir(dirname(__FILE__));
include("../connect.inc");
$db = connect_db();

$sql="CREATE TABLE MOBILE_API.SCHEDULED_APP_NOTIFICATIONS_1 (
  ID int(11) NOT NULL AUTO_INCREMENT,
  PROFILEID int(12) NOT NULL,
  NOTIFICATION_KEY varchar(20) NOT NULL,
  MESSAGE varchar(200) NOT NULL,
  LANDING_SCREEN int(5) DEFAULT NULL,
  OS_TYPE varchar(3) NOT NULL,
  PRIORITY float NOT NULL,
  COLLAPSE_STATUS varchar(1) NOT NULL,
  TTL int(10) NOT NULL,
  SCHEDULED_DATE date NOT NULL,
  SENT varchar(1) NOT NULL,
  TITLE varchar(60) DEFAULT NULL,
  COUNT int(11) NOT NULL,
  MSG_ID bigint(20) NOT NULL,
  PHOTO_URL varchar(100) DEFAULT NULL,
  PRIMARY KEY (ID),
  UNIQUE KEY PROFILEID (PROFILEID,NOTIFICATION_KEY,SCHEDULED_DATE),
  KEY MSG_ID (MSG_ID),
  KEY NOTIFICATION_KEY (NOTIFICATION_KEY)
) ENGINE=InnoDB AUTO_INCREMENT=242043";
mysql_query($sql,$db);

$sql_rename1="RENAME TABLE MOBILE_API.SCHEDULED_APP_NOTIFICATIONS TO MOBILE_API.SCHEDULED_APP_NOTIFICATIONS_21_JUN_2016";
mysql_query($sql_rename1,$db);

$sql_rename2="RENAME TABLE MOBILE_API.SCHEDULED_APP_NOTIFICATIONS_1 TO MOBILE_API.SCHEDULED_APP_NOTIFICATIONS";
mysql_query($sql_rename2,$db);

$sql_move = "SELECT * FROM MOBILE_API.SCHEDULED_APP_NOTIFICATIONS_21_JUN_2016 WHERE ORDER BY ID ASC";
$resj = mysql_query($sql_move,$db) or die(mysql_error($db)); 
while($rowj = mysql_fetch_array($resj)){
  $sql_insert = "INSERT INTO MOBILE_API.SCHEDULED_APP_NOTIFICATIONS VALUES (".$rowj['ID'].",'".$rowj['PROFILEID']."','".htmlentities($rowj['NOTIFICATION_KEY'], ENT_QUOTES)."','".htmlentities($rowj['MESSAGE'], ENT_QUOTES)."','".$rowj['LANDING_SCREEN']."','".$rowj['OS_TYPE']."','".$rowj['PRIORITY']."','".$rowj['COLLAPSE_STATUS']."','".$rowj['TTL']."','".$rowj['SCHEDULED_DATE']."','".$rowj['SENT']."','".$rowj['TITLE']."','".$rowj['COUNT']."','".$rowj['MSG_ID']."',NULL)";
  mysql_query($sql_insert,$db) or die(mysql_error($db)); 
}

$sql_change = "UPDATE  MOBILE_API.APP_NOTIFICATIONS SET PHOTO_URL =  'P' WHERE ID IN  ('1', '6', '7', '19', '20', '5', '8', '9', '30', '31', '32')";
$res_change = mysql_query($sql_change,$db) or die(mysql_error($db)); 

?>
