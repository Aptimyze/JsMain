<?php
include_once(JsConstants::$docRoot."/profile/connect_functions.inc");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
ini_set('max_execution_time','0');
ini_set(memory_limit,-1);
ini_set(mysql.connect_timeout,-1);
$db_185 = mysql_connect("172.16.3.185","localuser","Km7Iv80l");
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$db_185);
$rowCount=5000;
for($i=0;$i>=0;$i++)
{
	$offset = $i*$rowCount;
	$count = 0;
	$sql = "SELECT * FROM newjs.PasswordHashTesting LIMIT ".$offset.",".$rowCount;
        $res=mysql_query($sql,$db_185) or die(mysql_error() . "<BR>" . $sql);
	if(mysql_num_rows($res)<=0)
		break;
	$fp = fopen("./encrytionFail.txt","a+");
        while($row=mysql_fetch_array($res))
	{
		if(!PasswordHashFunctions::validatePassword($row['ORIGINAL_PASSWORD'], $row['ENCRYPTED_PASSWORD']))
		{
					fwrite($fp,$row['PROFILEID']."\n");
		}
	}
	fwrite($fp,"Complete:\t\t  offset::".$offset."\trowCount::".$rowCount."\n");
	fclose($fp);
}
