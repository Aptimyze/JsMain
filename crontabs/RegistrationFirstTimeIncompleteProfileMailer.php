<?php

$curFilePath = dirname(__FILE__) . "/";
include_once('/usr/local/scripts/DocRoot.php');
$fromCrontab = 1;
include_once($_SERVER['DOCUMENT_ROOT']."/profile/connect.inc");
include_once(JsConstants::$docRoot."/commonFiles/comfunc.inc");
include_once($_SERVER['DOCUMENT_ROOT']."/classes/authentication.class.php");

$db = connect_slave();

$to = "ankit.garg@jeevansathi.com,jaiswal.amit@jeevansathi.com";

function onError($errorCodeVal = '', $errorString = '', $profileid = '') {
  switch ($errorCodeVal) {
    case 1:
      send_email($to, $errorString, 'ERROR: Could not fetch profiles registered in last 24 hrs.');
      break;

    case 2:
      send_email($to, $errorString, 'ERROR: Could not fetch details. PROFILEID = ' . $profileid);
      break;

    case 3:
      send_email($to, $errorString, 'ERROR: Could not fetch Near Branch for profile. PROFILEID = ' . $profileid);
      break;

    case 4:
      send_email($to, $errorString, 'ERROR: Could not fetch Agent Details for profile. PROFILEID = ' . $profileid);
      break;

    default:
      send_mail($to, "Cron ran successfully. Have a donut :-)", "Success: Sending First time register mails");
      break;
  }
}

global $smarty;

$numberOfMailsSent = 0;
$numberOfProfilesFound = 0;

$currentTime = time();
$fiveMinutesBefore = $currentTime - (5 * 60); // 5 minutes prior to current time.
$last24hrs = date('Y-m-d H:i:s', $currentTime - (24 * 60 * 60));
$currentDateTime = date('Y-m-d H:i:s', $fiveMinutesBefore);

$getProfileIdsSql = "SELECT PROFILEID FROM newjs.JPROFILE WHERE ACTIVATED <> 'D' AND INCOMPLETE = 'Y' AND (ENTRY_DT BETWEEN '$last24hrs' AND '$currentDateTime') AND SEC_SOURCE <> 'C'";

$errorString = null;
$profileid = null;
$errorCodeVal = 1;

$getProfileIdsSqlResult = mysql_query($getProfileIdsSql, $db) or onError($errorCodeVal, mysql_error());

$numberOfProfilesFound = mysql_num_rows($getProfileIdsSqlResult);

if ($numberOfProfilesFound > 0) {
  while ($row = mysql_fetch_assoc($getProfileIdsSqlResult)) {

    $profileid = $row['PROFILEID'];
    $sql="select EMAIL,USERNAME,PASSWORD,CITY_RES,SEC_SOURCE from JPROFILE where  activatedKey=1 and PROFILEID=$profileid";

    $errorCodeVal = 2;
    $result = mysql_query($sql, $db) or onError($errorCodeVal, mysql_error(), $profileid);

    $myrow1 = mysql_fetch_array($result);

    $my_city = $myrow1['CITY_RES'];

    $smarty->assign("EMAIL",$myrow1["EMAIL"]);

    $smarty->assign("USERNAME",$myrow1["USERNAME"]);

    $smarty->assign("PASSWORD",$myrow1["PASSWORD"]);

    $sql = "SELECT SQL_CACHE NEAR_BRANCH FROM incentive.BRANCH_CITY WHERE VALUE='$my_city'";

    $errorCodeVal = 3;
    $result = mysql_query($sql, $db) or onError($errorCodeVal, mysql_error(), $profileid);

    if(mysql_num_rows($result) > 0)

    {

      $myrow = mysql_fetch_array($result);
      $sql = "SELECT SQL_CACHE NAME,ADDRESS,CONTACT_PERSON,PHONE,ID FROM BRANCHES WHERE VALUE='$myrow[NEAR_BRANCH]'";

    }
    else {

      $sql = "SELECT SQL_CACHE NAME,ADDRESS,CONTACT_PERSON,PHONE,ID FROM BRANCHES WHERE VALUE='UP25'";

    }

    $errorCodeVal = 4;
    $result = mysql_query($sql, $db) or onError($errorCodeVal, mysql_error(), $profileid);

    $myrow = mysql_fetch_array($result);

    $PROFILE_CHECKSUM = createChecksumForSearch($profileid); 

    $jsProtect = new protect;
    $ECHECKSUM = $jsProtect ? $jsProtect->js_encrypt($PROFILE_CHECKSUM, $myrow1['EMAIL']) : '';

    $smarty->assign("BRANCH_NAME", substr_replace(strtolower($myrow['NAME']), strtoupper(substr( strtolower($myrow['NAME']), 0, 1)), 0, 1));

    $smarty->assign("PROFILE_CHECKSUM",$PROFILE_CHECKSUM);

    $smarty->assign("ECHECKSUM", $ECHECKSUM);

    $smarty->assign("BRANCH_PHONE",$myrow['PHONE']);

    $smarty->assign("BRANCH_ADDRESS",$myrow['ADDRESS']);

    $smarty->assign("MAILTO","mailto:anamika.singh@jeevansathi.com");

    mysql_free_result($result);

    $msg = $smarty->fetch("registration_mailer.htm");

    if (true === send_email(/*/'ankit.garg@jeevansathi.com'/*/$myrow1["EMAIL"]/**/, $msg, "Thank you for registering with jeevansathi.com ","register@jeevansathi.com","","","","","","Y")) {
      ++$numberOfMailsSent;
    }

  }
}

$successMsg = "Number Of Profiles: $numberOfProfilesFound\nNumber Of Mails Sent: $numberOfMailsSent";
send_email($to, $successMsg, "Cron Report: " . __FILE__ . " metrics");
