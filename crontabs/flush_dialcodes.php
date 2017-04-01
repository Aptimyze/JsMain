<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");


/* File executes to truncate table DIALCODE_GENERATE 
 * set to execute after every 10 minutes.
 * Maintains the 5 digit DIALCODE in the table  
*/

include "connect.inc";
$db=connect_ddl();

$sql="SELECT MAX(DIALCODE) AS DIALCODE FROM newjs.DIALCODE_GENERATE";
$res=mysql_query($sql) or logError("Could not retrieve the dialcode from the table",$sql);
$row =mysql_fetch_array($res);
$dialcode = $row['DIALCODE'];

if($dialcode >99200)
{
	$sql="TRUNCATE newjs.DIALCODE_GENERATE";
	mysql_query($sql) or logError("Error while truncate table DIALCODE_GENERATE",$sql);
	die;
}

?>
