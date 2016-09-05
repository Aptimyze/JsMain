<?php
include_once(JsConstants::$docRoot."/profile/connect.inc");
// including for logging purpose
include_once(JsConstants::$docRoot."/classes/LoggingWrapper.class.php");
$db = connect_db();
if($_GET)
{
	$gender = $_GET['gender'];
	$name = $_GET['name'];
	$checkbox1 = $_GET['checkbox1'];
	$checkbox2 = $_GET['checkbox2'];
	if(!$name){
		$name = "Not Specified";
	}
	if(!$checkbox1){
		$checkbox1 = "Not Specified";
	}
	if(!$checkbox2){
		$checkbox2 = "Not Specified";
	}
	$sql ="INSERT INTO billing.ARIEL_CAMPAIGN VALUES ('','$gender', '$name', '$checkbox1', '$checkbox2',now())";
	$resReg = mysql_query_decide($sql,$db) or LoggingWrapper::getInstance()->sendLogAndDie(LoggingEnums::LOG_ERROR, new Exception($sql.mysql_error($db)));
	if($gender == "Male"){
		header("Location: http://bs.serving-sys.com/BurstingPipe/adServer.bs?cn=tf&c=20&mc=click&pli=13252654&PluID=0&ord=%5btimestamp");
	} else {
		header("Location: http://bs.serving-sys.com/BurstingPipe/adServer.bs?cn=tf&c=20&mc=click&pli=13432972&PluID=0&ord=[timestamp]");
	}
}
