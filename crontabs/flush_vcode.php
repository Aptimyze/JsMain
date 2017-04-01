<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

/* File executes to truncate table PHONE_VERIFY_CODE 
 * set to execute after every 7 days.
 * Contains the 4 digit verification code in the table  
*/

include "connect.inc";
$db=connect_ddl();

$sql="TRUNCATE newjs.PHONE_VERIFY_CODE";
mysql_query($sql) or logError("Error while truncate table newjs.PHONE_VERIFY_CODE:-",$sql);
?>
