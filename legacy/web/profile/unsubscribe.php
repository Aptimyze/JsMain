<?php
//to zip the file before sending it
$zipIt = 0;
if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) $zipIt = 1;
if ($zipIt) ob_start("ob_gzhandler");

//end of it
include ("connect.inc");
include ("alert_page.php");
include_once ($_SERVER['DOCUMENT_ROOT'] . "/profile/functions_edit_profile.php");
$symfonyFilePath = realpath($_SERVER['DOCUMENT_ROOT'] . "/../");
include_once ($symfonyFilePath . "/lib/model/lib/FieldMapLib.class.php");
include_once ($symfonyFilePath . "/lib/model/lib/Flag.class.php");
include_once (JsConstants::$docRoot . "/commonFiles/SymfonyPictureFunctions.class.php");
$db = connect_db();
$data = authenticated($checksum);

//Added By lavesh.
if ($data) login_relogin_auth($data);
include_once ("track_matchalert.php");
if ($matchalertTrack && $data["PROFILEID"]) {
    TrackEditUnsubscribe($data["PROFILEID"], 'V', $logic_used);
    $smarty->assign("matchalertTracksubmit", 1);
}

if ($kundliTracking && $data["PROFILEID"]) {
    $sql = "INSERT INTO MIS.KUNDLI_MAILER_TRACKING (DATE,UNSUBSCRIPTION) VALUES ('" . date("Y-m-d") . "',1) ON DUPLICATE KEY UPDATE UNSUBSCRIPTION = UNSUBSCRIPTION+1";
    mysql_query_decide($sql) or logError($errorMsg, "$sql", "ShowErrTemplate");
}

$errorMsg = "Due to a temporary problem your request could not be processed. Please try after a couple of minutes";

if ($newMatchesMailTrack && $data["PROFILEID"]) {
    if ($sent_date && !is_numeric($sent_date)) $sent_date = "";
    if ($sent_date) $dateString = MailerConfigVariables::decodeLogicalDate($sent_date);
    else $dateString = date("Y-m-d");

    $sql = "INSERT INTO MIS.NEW_MATCHES_EMAILS_TRACKING (DATE,UNSUBSCRIPTION) VALUES ('" . $dateString . "',1) ON DUPLICATE KEY UPDATE UNSUBSCRIPTION = UNSUBSCRIPTION+1";
    mysql_query_decide($sql) or logError($errorMsg, "$sql", "ShowErrTemplate");
}

//ends Here.

/*************************************Portion of Code added for display of Banners*******************************/
$smarty->assign("data", $data["PROFILEID"]);
$smarty->assign("bms_topright", 18);

//$smarty->assign("bms_right",28);
$smarty->assign("bms_bottom", 19);
$smarty->assign("bms_left", 24);
$smarty->assign("bms_new_win", 32);

/*$regionstr=8;
include("../bmsjs/bms_display.php");
/************************************************End of Portion of Code*****************************************/

// ADDITION FOR BMS
$smarty->assign("data", $data["PROFILEID"]);
$smarty->assign("regionstr", 8);

$today = date("Y-m-d");

/**************************Added By Shakti for link tracking**********************/
link_track("unsubscribe.php");

/*********************************************************************************/

/*********************************************************************************************************************
Changed By	: Shakti Srivastava
Change Date	: 22 September, 2005
Reason		: Due to a bug, people were not able to view their latest subscription status. The default values were
		: always selected irrespective of members subscription status
		*********************************************************************************************************************/
if ($crmback == 'admin') {
    $profileid = $pid;
    $smarty->assign("crmback", 'admin');
    $smarty->assign("pid", $pid);
    $smarty->assign("cid", $cid);
}
else $profileid = $data['PROFILEID'];

if ($source == "ofl_prof") $allow_js = 1;

if ($data || $crmback == 'admin') {

    //added by lavesh.
    $sql_s = "SELECT COUNT(*) as cnt FROM SMS_SUBSCRIPTION_DEACTIVATED WHERE PROFILEID='$profileid'";
    $res_s = mysql_query_decide($sql_s) or logError($errorMsg, "$sql_s", "ShowErrTemplate");
    $row_s = mysql_fetch_array($res_s);
    $sms_unsubscribe = $row_s['cnt'];

    //ends here

    $sql_s = "SELECT SOURCE,PROMO_MAILS,SERVICE_MESSAGES,PERSONAL_MATCHES,EMAIL,GET_SMS FROM JPROFILE WHERE PROFILEID='$profileid'";
    $res_s = mysql_query_decide($sql_s) or logError($errorMsg, "$sql_s", "ShowErrTemplate");
    $row_s = mysql_fetch_array($res_s);
    $email = $row_s['EMAIL'];
    $service = $row_s['SERVICE_MESSAGES'];
    $source = $row_s['SOURCE'];

    /*$promo=$row_s['PROMO_MAILS'];
    $personal=$row_s['PERSONAL_MATCHES'];
    */

    //added by prinka
    $settingArray["PS"][2] = $row_s['GET_SMS'];
    $settingArray["PM"][2] = $row_s['PROMO_MAILS'];
    $settingArray["MA"][2] = $row_s['PERSONAL_MATCHES'];

    $sql2 = "SELECT MEMB_CALLS,OFFER_CALLS,SERV_CALLS_SITE,SERV_CALLS_PROF,MEMB_MAILS,CONTACT_ALERT_MAILS,KUNDLI_ALERT_MAILS,PHOTO_REQUEST_MAILS,NEW_MATCHES_MAILS,SERVICE_SMS,SERVICE_MMS,SERVICE_USSD,PROMO_USSD,SERVICE_MAILS,PROMO_MMS FROM JPROFILE_ALERTS WHERE PROFILEID=$profileid";
    $res2 = mysql_query_decide($sql2) or logError($errorMsg, "$sql2", "ShowErrTemplate");

    if ($row2 = mysql_fetch_array($res2)) {
        $settingArray["MC"][2] = $row2['MEMB_CALLS'];
        $settingArray["OC"][2] = $row2['OFFER_CALLS'];
        $settingArray["SC1"][2] = $row2['SERV_CALLS_SITE'];
        $settingArray["SC2"][2] = $row2['SERV_CALLS_PROF'];
        $settingArray["MM"][2] = $row2['MEMB_MAILS'];
        $settingArray["CA"][2] = $row2['CONTACT_ALERT_MAILS'];
        $settingArray["KA"][2] = $row2['KUNDLI_ALERT_MAILS'];
        $settingArray["PR"][2] = $row2['PHOTO_REQUEST_MAILS'];
        $settingArray["NMM"][2] = $row2['NEW_MATCHES_MAILS'];
        $settingArray["SS"][2] = $row2['SERVICE_SMS'];
        $settingArray["STM"][2] = $row2['SERVICE_MMS'];
        $settingArray["SU"][2] = $row2['SERVICE_USSD'];
        $settingArray["PU"][2] = $row2['PROMO_USSD'];
        $settingArray["SM"][2] = $row2['SERVICE_MAILS'];
        $settingArray["PMM"][2] = $row2['PROMO_MMS'];
    }

    //added by prinka

    $mysqlObj = new Mysql;
    $myDb1 = $mysqlObj->connect("11Master");
    $sql = "SELECT ALERT_OPTION FROM visitoralert.VISITOR_ALERT_OPTION WHERE PROFILEID='$profileid'";
    $res = mysql_query_decide($sql, $myDb1) or logError($errorMsg, "$sql", "ShowErrTemplate");
    $row = mysql_fetch_array($res);
    $visitor = $row['ALERT_OPTION'];
    $settingArray["VA"][2] = $visitor;

    // Smarty Assign for the Revamp

    $smarty->assign("alert_manager", 1);
    $smarty->assign("REVAMP_LEFT_PANEL", $smarty->fetch("leftpanel_settings.htm"));
    $smarty->assign("FOOT", $smarty->fetch("footer.htm"));
     //Added for revamp
    $smarty->assign("SUB_HEAD", $smarty->fetch("sub_head.htm"));
    $smarty->assign("head_tab", 'my jeevansathi');
    $smarty->assign("REVAMP_HEAD", $smarty->fetch("revamp_head.htm"));

    //$smarty->assign("REVAMP_SEARCH_PANEL",$smarty->fetch("revamp_top_search_band.htm"));
    rightpanel($data);

    //$smarty->assign("REVAMP_TOP_SEARCH",$smarty->fetch("revamp_top_search_band.htm"));
    $smarty->assign("REVAMP_RIGHT_PANEL", $smarty->fetch("revamp_rightpanel.htm"));
    $smarty->assign("PROFILECHECKSUM", $profilechecksum);
    $smarty->assign("CHECKSUM", $checksum);

    // End

    if ($CMDUpdate) {
        if ($matchalertTracksubmit && $data["PROFILEID"]) {
            include_once ("track_matchalert.php");
            TrackEditUnsubscribe($data["PROFILEID"], 'E');
        }

        if (trim($emailadd) == "") {
            $MSG = "email id cannot be empty, please enter a new email id to update your preference.";
            die($MSG);
            $error = 1;
        }
        else {
            if ($promo_sms == 'S') $new_sms_value = "Y";
            else $new_sms_value = "N";

            $sql = "UPDATE JPROFILE SET UDATE='$today', PROMO_MAILS='$promo_mails', PERSONAL_MATCHES='$match_alert', GET_SMS='$new_sms_value'";
            if (!$crmback) $sql.= ", MOD_DT=NOW() ";
            $sql.= " WHERE PROFILEID='$profileid'";
            mysql_query_decide($sql) or logError($errorMsg, "$sql", "ShowErrTemplate");

            //added by prinka
            //			$sql1="REPLACE INTO JPROFILE_ALERTS(PROFILEID,MEMB_CALLS,OFFER_CALLS,SERV_CALLS_SITE,SERV_CALLS_PROF) VALUES('$profileid','$memb_calls','$offer_calls','$serv_calls1','$serv_calls2')";
            $sql1 = "REPLACE INTO JPROFILE_ALERTS(PROFILEID,MEMB_CALLS,OFFER_CALLS,SERV_CALLS_SITE,SERV_CALLS_PROF,MEMB_MAILS,CONTACT_ALERT_MAILS,KUNDLI_ALERT_MAILS,PHOTO_REQUEST_MAILS,NEW_MATCHES_MAILS,SERVICE_MAILS,SERVICE_SMS,SERVICE_MMS,SERVICE_USSD,PROMO_USSD,PROMO_MMS) VALUES('$profileid','$mem_call','$offer_call','$service1','$service2','$mem_mail','$contact_alert','$kundli_alert','$photo_req','$new_matches_mail','$serv_mail','$serv_sms','$serv_mms','$serv_ussd','$promo_ussd','$promo_mms')";
            mysql_query_decide($sql1) or logError($errorMsg, "$sql1", "ShowErrTemplate");

            //prinka
            //For EDIT_LOG_JPROFILE_ALERTS
            $now = date("Y-m-d H-i-s");
            $sql2 = "INSERT IGNORE INTO JPROFILE_ALERTS_LOG(PROFILEID,MEMB_CALLS,OFFER_CALLS,SERV_CALLS_SITE,SERV_CALLS_PROF,MEMB_MAILS,CONTACT_ALERT_MAILS,KUNDLI_ALERT_MAILS,PHOTO_REQUEST_MAILS,SERVICE_MAILS,SERVICE_SMS,SERVICE_MMS,SERVICE_USSD,PROMO_USSD,PROMO_MMS,FROM_PAGE,MOD_DT) VALUES('$profileid','$mem_call','$offer_call','$service1','$service2','$mem_mail','$contact_alert','$kundli_alert','$photo_req','$serv_mail','$serv_sms','$serv_mms','$serv_ussd','$promo_ussd','$promo_mms','U','$now')";
            mysql_query_decide($sql2) or logError("errorMsg", $sql2, "ShowErrTemplate");

            //added by vibhor
            $sql = "REPLACE INTO visitoralert.VISITOR_ALERT_OPTION VALUES ('$profileid','$vis_alert')";
            mysql_query_decide($sql, $myDb1) or logError($errorMsg, "$sql", "ShowErrTemplate");

            //ends

            //if(!checkemail($emailadd))
            $error = 0;
            if ($email != $emailadd) {
                if (!$allow_js) {
                    $flag = checkemail($emailadd);
                    if ($flag) {
                        if ($flag == 1) $MSG = "Entered email id is invalid, please enter a new email id to update your preference";
                        else $MSG = "Email id already exists, please enter a new email id to update your preference.";
                        $error = 1;
                    }
                    else {
                        $flag1 = my_checkoldemail($emailadd, $profileid);
                        if ($flag1) {
                            $MSG = "Email id already exists, please enter a new email id to update your preference.";
                            $error = 1;
                        }
                        else {
                            $flag2 = checkemail_af($emailadd);
                            if ($flag2) {
                                if ($flag2 == 1) $MSG = "Entered email id is invalid, please enter a new email id to update your preference";
                                else $MSG = "Email id already exists, please enter a new email id to update your preference.";
                                $error = 1;
                            }
                        }
                    }
                }
            }

            if ($error) {
                die($MSG);
            }
            else {
                $sql = "INSERT IGNORE INTO OLDEMAIL VALUES('$profileid','$email')";
                mysql_query_decide($sql) or logError($errorMsg, "$sql", "ShowErrTemplate");

                // code added by neha for archiving contact information
                $date_now = date("Y-m-d H:i:s");
                $ip = FetchClientIP();
                 //Gets ipaddress of user
                if (strstr($ip, ",")) {
                    $ip_new = explode(",", $ip);
                    $ip = $ip_new[1];
                }
                if ($email != $emailadd) {

                    //Insert into autoexpiry table, to expire all autologin url coming before date
                    $expireDt = date("Y-m-d H:i:s");
                    $sqlExpire = "replace into jsadmin.AUTO_EXPIRY set PROFILEID='$profileid',TYPE='E',DATE='$expireDt'";
                    mysql_query_decide($sqlExpire) or logError($errorMsg, "$sqlExpire", "ShowErrTemplate");

                    //end

                    $sql_search = "SELECT CHANGEID FROM newjs.CONTACT_ARCHIVE WHERE PROFILEID='$profileid' AND FIELD='EMAIL'";
                    $res_search = mysql_query_decide($sql_search) or logError($errorMsg, "$sql_search", "ShowErrTemplate");
                    if (mysql_num_rows($res_search) > 0) {
                        $row_search = mysql_fetch_assoc($res_search);
                        $changeid = $row_search['CHANGEID'];
                        $sql_add = "INSERT INTO CONTACT_ARCHIVE_INFO (CHANGEID,DATE,IPADD,OLD_VAL,NEW_VAL) VALUES($changeid,'$date_now','$ip','$email','$emailadd') ";
                        $res_add = mysql_query_decide($sql_add) or logError($errorMsg, "$sql_add", "ShowErrTemplate");
                    }
                    else {
                        $sql_insert = "INSERT INTO CONTACT_ARCHIVE(PROFILEID,FIELD) VALUES($profileid,'EMAIL')";
                        $res_insert = mysql_query_decide($sql_insert) or logError($errorMsg, "$sql_insert", "ShowErrTemplate");
                        $sql_search = "SELECT CHANGEID FROM newjs.CONTACT_ARCHIVE WHERE PROFILEID='$profileid' AND FIELD='EMAIL'";
                        $res_search = mysql_query_decide($sql_search) or logError($errorMsg, "$sql_search", "ShowErrTemplate");
                        $row_search = mysql_fetch_assoc($res_search);
                        $changeid = $row_search['CHANGEID'];
                        $sql_add = "INSERT INTO CONTACT_ARCHIVE_INFO (CHANGEID,DATE,IPADD,OLD_VAL,NEW_VAL) VALUES($changeid,'$date_now','$ip','$email','$emailadd') ";
                        $res_add = mysql_query_decide($sql_add) or logError($errorMsg, "$sql_add", "ShowErrTemplate");
                    }
                }

                //end of code added by neha.

                $sql_u = "UPDATE JPROFILE SET EMAIL='$emailadd' WHERE PROFILEID='$profileid'";
                mysql_query_decide($sql_u) or logError($errorMsg, "$sql_u", "ShowErrTemplate");

                if (trim($email) != trim($emailadd)) {

                    ///Duplication fields update on edit///////
                    ///////////////////////////////////////////
                    $dup_fields[] = "email";
                    duplication_fields_insertion($dup_fields, $profileid);

                    ///////////////////////////////////////////
                    ///////////////////////////////////////////

                }
                if (trim($email) != trim($emailadd))
                 //VERIFY_EMAIL='Y' implies invalid email-id.
                {

                    //if gmail id provided then make entry into invite table bot_jeevansathi
                    if (strstr($emailadd, "@gmail.com")) {
                        $Email = $emailadd;

                        //$profileid=$data['PROFILEID'];
                        $username = $data["USERNAME"];
                        $sql = "delete from bot_jeevansathi.user_info where  profileID='$profileid' OR gmail_ID='$Email'";
                        mysql_query_decide($sql) or logError($sql);

                        $sql = "delete from bot_jeevansathi.user_online where  USER='$profileid'";
                        mysql_query_decide($sql) or logError($sql);

                        $sql_invite_entry = "insert into bot_jeevansathi.gmail_invites(profileid,gmailid) values('$username','$Email')";
                        mysql_query_decide($sql_invite_entry);

                        $sql_invite_entry = "insert into bot_jeevansathi.invite_send(PROFILEID,EMAIL) values('$profileid','$Email')";
                        mysql_query_decide($sql_invite_entry);

                        $sql_bot_entry = "insert ignore into bot_jeevansathi.user_info(`gmail_ID`,`on_off_flag`,`show_in_search`,`profileID`,`jeevansathi_ID`) values('$Email',0,1,'$profileid','$username')";
                        mysql_query_decide($sql_bot_entry) or logError($sql);

                        send_chat_request_email($profileid, $Email, $username);
                    }

                    $invalid_email = bounced_emailID($profileid);
                    $sql = "UPDATE newjs.JPROFILE SET VERIFY_EMAIL = 'N' WHERE PROFILEID = '$profileid'";
                    $res = mysql_query_decide($sql) or logError("Error in updating email", $sql);

                    $cookie_value = $_COOKIE['INVALID_EMAIL'];
                    settype($cookie_value, "integer");
                    if ($cookie_value != 2) {
                        if ($invalid_email)

                        //earlier email is invalid.
                        {
                            $sql = "DELETE FROM newjs.INVALID_EMAIL_MAILER WHERE PROFILEID='$profileid'";
                            $result = mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.", $sql, "ShowErrTemplate");

                            //No. of times email is updated.
                            $sql = "UPDATE MIS.EMAILDETAILS set EMAIL_UPDATED=EMAIL_UPDATED+1 where ENTRY_DATE='$today'";
                            mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.", $sql, "ShowErrTemplate");
                            if (!mysql_affected_rows_js()) {
                                $sql = "INSERT INTO MIS.EMAILDETAILS VALUES('','','','1','$today')";
                                mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.", $sql, "ShowErrTemplate");
                            }
                        }
                        setcookie("INVALID_EMAIL", "2", 0, "/", $domain);
                    }
                }
                if ($crmback == 'admin') $lnk = "<a href=\"/jsadmin/searchpage.php?user=&cid=$cid\">BACK TO JSADMIN</a>";
                else $lnk = "<a href=\"mainmenu.php?checksum=$checksum\">My Jeevansathi</a>";
            }

            //$smarty->display("email_manager_confirm.htm");
            //Ends Here.

        }
    }
    else {
        $smarty->assign("email", $email);
        $smarty->assign("promo", $promo);
        $smarty->assign("service", $service);
        $smarty->assign("personal", $personal);
        $smarty->assign("visitor", $visitor);
        $smarty->assign("crmback", $crmback);
        $smarty->assign("sms_unsubscribe", $sms_unsubscribe);
        $smarty->assign("yes", $yes);

        ///added by prinka
        /*
        $smarty->assign("memb_calls",$memb);
        $smarty->assign("offer_calls",$offer);
        $smarty->assign("serv1",$serv1);
        $smarty->assign("serv2",$serv2);
        */

        ////
        ////added by prinka
        $smarty->assign('sortArr', $sortArr);
        $smarty->assign('settingArray', $settingArray);

        ////

        $smarty->display("email_manager.htm");
    }
}
else {
    if ($CMDUpdate) {
        die("You have logged out or Your Session has expired");
    }

    TimedOut();
}



// flush the buffer
if ($zipIt) ob_end_flush();
?>
