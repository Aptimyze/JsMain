<?php
/*
Author: @Esha
*/
include_once "connect.inc";
connect_db();
$sql = "SELECT * FROM SMS_TYPE WHERE STATUS='Y'";
$res=mysql_query_decide($sql) or die($sql);
while($row=mysql_fetch_array($res))
{
	$messageArray[]=$row;
}
$profileSender="144111";
$profileReceiver= "336";
$sql1 = "SELECT EMAIL, jp.PROFILEID, INCOMPLETE, GENDER, USERNAME, SUBSCRIPTION, PHONE_MOB, PASSWORD, CASTE, DTOFBIRTH, MSTATUS, MTONGUE, CITY_RES, WEIGHT, AGE, HEIGHT, EDU_LEVEL_NEW, INCOME, ENTRY_DT,MOB_STATUS, LANDL_STATUS, DATE(LAST_LOGIN_DT) LAST_LOGIN_DT, HAVEPHOTO, OCCUPATION, COUNTRY_RES, GET_SMS, SOURCE, ACTIVATED, COMPANY_NAME, FAMILY_INCOME, OWN_HOUSE,jt.NTIMES,fs.SUBSTATE AS FTO_SUB_STATE,fto.FTO_ENTRY_DATE,fto.FTO_EXPIRY_DATE FROM newjs.JPROFILE jp LEFT JOIN newjs.JP_NTIMES jt ON (jt.PROFILEID = jp.PROFILEID) LEFT JOIN FTO.FTO_CURRENT_STATE fto ON (fto.PROFILEID = jp.PROFILEID) LEFT JOIN FTO.FTO_STATES fs ON (fto.STATE_ID = fs.STATE_ID) WHERE jp.PROFILEID IN ('".$profileSender."','".$profileReceiver."');";
$res1 = mysql_query_decide($sql1) or die($sql1);
while($row1 = mysql_fetch_array($res1))
{
	if($row1['PROFILEID']==$profileSender)
		$details['DATA'] = $row1;
	else
		$details['RECEIVER'] = $row1;
}
//print_r($senderDetails);print_r($receiverDetails);
include_once($_SERVER['DOCUMENT_ROOT']."/classes/SMSLib.class.php");

$smsLib = new SMSLib;
foreach($messageArray as $k=> $mess)
{
	$actualMessage=addslashes(getActualMessage($mess["MESSAGE"],$details,$smsLib));
	$sqlIns = "INSERT INTO newjs.TEMP_SMS_DETAIL(PROFILEID, SMS_TYPE, SMS_KEY, MESSAGE, PHONE_MOB, PRIORITY, ADD_DATE, MUL_SMS) VALUES ('".$profileSender."','".$mess['SMS_TYPE']."','".$k."','".$actualMessage."','9953457479','".$mess['PRIORITY']."',now(),'N')";
	$res = mysql_query_decide($sqlIns) or die($sqlIns);
}


        function getActualMessage ($message,$details,$smsLib) {

                $mLength = strlen($message);
                $messageToken = "";
                $startToken = 0;
                $actualMessage = "";

                for ($i = 0; $i < $mLength; $i++) {

                        if ($message[$i] != '{' && $message[$i] != '}' && !$startToken) $actualMessage .= $message[$i];

                        else if ($message[$i] == '{') {
                                $startToken = 1;
                                continue;
                        }
                        else if ($message[$i] == '}') {
                                $actualMessage .= $smsLib->getTokenValue($messageToken,$details['RECEIVER']);
                                $messageToken = "";
                                $startToken = 0;
                        }
                        if ($startToken) $messageToken .= $message[$i];
                }
                return $actualMessage;
        }
?>			
