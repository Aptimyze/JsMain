<?php
include_once(JsConstants::$docRoot."/profile/connect_functions.inc");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
ini_set('max_execution_time','0');
ini_set(memory_limit,-1);
ini_set(mysql.connect_timeout,-1);
$db_157 = mysql_connect("172.16.3.157","ankit","ankit");
$db_185 = mysql_connect("172.16.3.185","localuser","Km7Iv80l");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db_157);
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db_185);

$rowCount=1000;
for($i=2500;$i>=0;$i++)
{
	$offset = $i*$rowCount;
	$count = 0;
	$str = '';
	$sql = "SELECT PROFILEID,PASSWORD FROM newjs.JPROFILE LIMIT ".$offset.",".$rowCount;
        $res=mysql_query($sql,$db_157) or die(mysql_error() . "<BR>" . $sql);
	if(mysql_num_rows($res)<=0)
		break;
        while($row=mysql_fetch_array($res))
	{
		$epd = addslashes(PasswordHashFunctions::createHash($row['PASSWORD']));
		$str.="('".$row['PROFILEID']."','".addslashes($row['PASSWORD'])."','".$epd."'),";
	}
	if($str)
	{
		$str = substr($str,0,-1);
		$sql1 = "INSERT IGNORE INTO  newjs.PasswordHashTesting (  `PROFILEID` ,  `ORIGINAL_PASSWORD` ,  `ENCRYPTED_PASSWORD` ) VALUES ".$str;
		$res=mysql_query($sql1,$db_185) or die(mysql_error() . "<BR>" . $sql1);
	}
}
