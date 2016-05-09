<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

$flag_using_php5=1;
include("connect.inc");
$db=connect_slave();


$sql="RENAME TABLE CONTACTS_SEARCH_NEW_TEMP TO CONTACTS_SEARCH_NEW1, CONTACTS_SEARCH_NEW TO CONTACTS_SEARCH_NEW_TEMP, CONTACTS_SEARCH_NEW1 TO CONTACTS_SEARCH_NEW";
mysql_query($sql,$db) or die(mysql_error() . "<BR>" . $sql);

$sql="TRUNCATE TABLE CONTACTS_SEARCH_NEW_TEMP";
mysql_query($sql,$db) or die(mysql_error() . "<BR>" . $sql);

$sql="TRUNCATE TABLE CONTACTS_SEARCH_NEW_1";
mysql_query($sql,$db) or die(mysql_error() . "<BR>" . $sql);

//TEMP ADDED
$sql="INSERT INTO TEMPRECEIVER SELECT RECEIVER,COUNT(*) AS CNT FROM CONTACTS_SEARCH_NEW GROUP BY RECEIVER";
mysql_query($sql,$db) or die(mysql_error($db) . "<BR>" . $sql);

$sql="INSERT INTO TEMPSENDER SELECT SENDER,COUNT(*) AS CNT FROM CONTACTS_SEARCH_NEW GROUP BY SENDER";
mysql_query($sql,$db) or die(mysql_error($db) . "<BR>" . $sql);
//TEMP ADDED


//Ends Here.
$sql="TRUNCATE TABLE LAST_LOGIN_PROFILES";
mysql_query($sql,$db) or die(mysql_error() . "<BR>" . $sql);

?>
