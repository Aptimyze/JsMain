<?php
include(JsConstants::$docRoot."/profile/connect.inc");
include_once(JsConstants::$docRoot."/profile/SymfonySearchFunctions.class.php");
include(JsConstants::$docRoot."/commonFiles/sms_inc.php");
ini_set('max_execution_time','0');

$dbM = connect_db();
mysql_query('set session wait_timeout=10000,interactive_timeout=10000,net_read_timeout=10000',$dbM);

$sql1="ALTER TABLE  newjs.SEARCH_MALE ADD  `NATIVE_STATE` VARCHAR( 10 ) AFTER  `VERIFICATION_SEAL`" ;

$sql2="ALTER TABLE  newjs.SEARCH_FEMALE ADD  `NATIVE_STATE` VARCHAR( 10 ) AFTER  `VERIFICATION_SEAL`" ;

$sql3="ALTER TABLE newjs.SEARCHQUERY ADD `NATIVE_STATE` TEXT";

$sql4="ALTER TABLE  newjs.SWAP ADD  `NATIVE_STATE` VARCHAR( 10 ) AFTER  `VERIFICATION_SEAL`" ;

$sql5="ALTER TABLE  newjs.SEARCH_AGENT ADD  `NATIVE_STATE` TEXT";

$sql6="ALTER TABLE search.LATEST_SEARCHQUERY ADD `NATIVE_STATE` TEXT AFTER `KUNDLI_DATE_CLUSTER`" ;

$sql7="ALTER TABLE  MIS.SEARCHQUERY ADD  `NATIVE_STATE` TEXT";

$sql8="ALTER TABLE  MIS.SEARCHQUERY_TEMP ADD  `NATIVE_STATE` TEXT";

$sql9="UPDATE newjs.NATIVE_PLACE AS NP JOIN newjs.SEARCH_MALE AS SM ON SM.PROFILEID=NP.PROFILEID SET SM.NATIVE_STATE = NP.NATIVE_STATE";

$sql10="UPDATE newjs.NATIVE_PLACE AS NP JOIN newjs.SEARCH_FEMALE AS SF ON SF.PROFILEID=NP.PROFILEID SET SF.NATIVE_STATE = NP.NATIVE_STATE";

$sql11="INSERT INTO `SEARCH_CLUSTERS` ( `DISPLAY_LABEL` , `SOLR_LABEL` , `VALUE` , `ACTIVE` ) 
VALUES (
'Family based out of', 'NATIVE_STATE', '31', 'Y'
)";

updateQuery($sql11);
updateQuery($sql1);
updateQuery($sql2);
updateQuery($sql3);
updateQuery($sql4);
updateQuery($sql5);
updateQuery($sql6);
//updateQuery($sql8);
updateQuery($sql9);
updateQuery($sql10);
//updateQuery($sql7);

function updateQuery($sql)
{
	global $dbM;
	$result=mysql_query($sql,$dbM) or mysql_error1(mysql_error($dbM).$sql);
}
function mysql_error1($msg)
{
        mail("lavesh.rawat@gmail.com,ankitshukla125@gmail.com,ankit.shukla@jeevansathi.com,prashant.pal@jeevansathi.com,pal.prashant28@jeevansathi.com","JSM-665Queries",$msg);
        $mobile1         = "9711818214";
        $mobile2         = "8375938414";
        $mobile3         = "9818424749";
	$date = date("Y-m-d h");
	$message        = "Mysql Error Count have reached solr $date within 5 minutes";
	$from           = "JSSRVR";
	$profileid      = "144111";
	$smsState1 = send_sms($message,$from,$mobile1,$profileid,'','Y');
        $smsState2 = send_sms($message,$from,$mobile2,$profileid,'','Y');
        $smsState3 = send_sms($message,$from,$mobile3,$profileid,'','Y');
}
