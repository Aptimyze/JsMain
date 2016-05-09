<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");


include('connect.inc');
$db=connect_db();

$sql="SET @DONT_UPDATE_TRIGGER=1";
mysql_query($sql,$db);

$sql_update="UPDATE newjs.JPROFILE SET SHOW_HOROSCOPE = 'Y' WHERE SHOW_HOROSCOPE=''";
mysql_query($sql_update,$db);

?>
