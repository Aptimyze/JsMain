<?php 
  $curFilePath = dirname(__FILE__)."/"; 
 include_once("/usr/local/scripts/DocRoot.php");

ini_set('max_execution_time','0');
ini_set('memory_limit',-1);
chdir(dirname(__FILE__));
include("../config.php");
include("../connect.inc");

include_once($_SERVER['DOCUMENT_ROOT']."/classes/globalVariables.Class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Mysql.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/Memcache.class.php");
include_once(JsConstants::$docRoot."/commonFiles/RevampJsDbFunctions.php");
include_once(JsConstants::$docRoot."/commonFiles/SymfonyPictureFunctions.class.php");
include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect_functions.inc");


$mysqlObj=new Mysql;

$db=connect_db();
mysql_query("set session wait_timeout=1000",$db);

for($i=0;$i<5;$i++)
{
	$sql="SELECT PROFILEID,EMAIL FROM MAIL.INACTIVE_PROFILES_TEMP WHERE STATUS!='Y' and (`PROFILEID` % 5 = $i)";
	$res=mysql_query($sql,$db) or die(mysql_error($db));
	if(mysql_num_rows($res))
	{
        $emailVerObj=new emailVerification();
        $emailDbObj=new NEWJS_EMAIL_CHANGE_LOG();

		while($row=mysql_fetch_assoc($res))
        {   

            $sql2="UPDATE MAIL.INACTIVE_PROFILES_TEMP SET STATUS='Y' WHERE PROFILEID=".$row['PROFILEID'];
            mysql_query($sql2,$db) or die(mysql_error($db));

            $result=$emailDbObj->getLastEntry($row['PROFILEID']);
            if($result['ID']) continue;
            $emailUID=$emailDbObj->insertEmailChange($row['PROFILEID'],$row['EMAIL']);
            $emailVerObj->sendVerificationMail($row['PROFILEID'],$emailUID);
            
        
	}

    unset($res);
}
}
?>