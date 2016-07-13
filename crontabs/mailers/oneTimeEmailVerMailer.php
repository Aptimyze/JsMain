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
	$sql="SELECT PROFILEID FROM MAIL.INACTIVE_PROFILES_TEMP WHERE STATUS!='Y'";
	$res=mysql_query($sql,$db) or die(mysql_error($db));
	if(mysql_num_rows($res))
	{
		$row=mysql_fetch_assoc($res);
        $emailVerObj=new emailVerification();
        $emailDbObj=new NEWJS_EMAIL_CHANGE_LOG();
        foreach ($row as $key => $value) 
        {
            $result=$emailDbObj->getLastEntry($value['PROFILEID']);
            if($result['ID']) continue;
            $emailUID=$emailDbObj->insertEmailChange($value['PROFILEID'],$value['EMAIL']);
            $emailVerObj->sendVerificationMail($value['PROFILEID'],$emailUID);
            $sql2="UPDATE MAIL.INACTIVE_PROFILES_TEMP SET STATUS='Y' WHERE PROFILEID=".$value['PROFILEID'];
            mysql_query($sql2,$db) or die(mysql_error($db));

        }
	}

?>