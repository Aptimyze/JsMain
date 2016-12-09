<?php

function create_city_drop_and_isd($Country_code) {
    $ret = "";
    $sql = "select VALUE,LABEL from CITY_NEW WHERE COUNTRY_VALUE='$Country_code' AND TYPE!='STATE' order by SORTBY";
    $res = mysql_query_optimizer($sql) or logError("error", $sql);
    $ret.= "<select style=\"width:185px;\" name=\"City_Res\" id=\"City_arr\" onchange=\"show_code();\">";
    while ($myrow = mysql_fetch_array($res)) $ret.= "<option value=\"$myrow[VALUE]\">$myrow[LABEL]</option>\n";
    $ret.= "<option value=\"0\">Others</option>\n";
    
    $ret.= "</select>";
    $sql_isd = "select ISD_CODE from COUNTRY_NEW WHERE VALUE='$Country_code'";
    $res_isd = mysql_query_optimizer($sql_isd) or logError("error", $sql_isd);
    $myrow_isd = mysql_fetch_array($res_isd);
    $ret.= "isd";
    $ret.= $myrow_isd["ISD_CODE"];
    return $ret;
}

function get_stdcode_of_city($City_code) {
    $ret = "";
    $sql = "select STD_CODE from CITY_NEW WHERE VALUE='$City_code'";
    $res = mysql_query_optimizer($sql) or logError("error", $sql);
    $myrow = mysql_fetch_array($res);
    $ret = $myrow["STD_CODE"];
    return "std" . $ret;
}

function verify_email($email_id, $pid) {
    if ($email_id) {
        $sql = "Select PROFILEID from newjs.JPROFILE WHERE EMAIL='$email_id'";
        $res = mysql_query_optimizer($sql) or logError("error", $sql);
        $myrow = mysql_fetch_array($res);
        $ret = $myrow["PROFILEID"];
        if ($ret) {
            if ($ret != $pid) return "exist";
            else return "not";
        } 
        else return "not";
    } 
    else return "empty";
}

function get_annulled_details($profileid) {
    $sql_ms = "select * from newjs.ANNULLED where PROFILEID='$profileid'";
    $result_ms = mysql_query_decide($sql_ms) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes", $sql_ms, "ShowErrTemplate");
    $myrow_ms = mysql_fetch_assoc($result_ms);
    return $myrow_ms;
}

function get_code($tablename, $value) {
    if ($tablename == "CITY_INDIA") {
        $tablename = "CITY_NEW";
        $attribute = "STD_CODE";
    } 
    else {
        $attribute = "CODE";
    }
    $sql = "select " . $attribute . " from newjs.$tablename where VALUE='$value'";
    $res = mysql_query_decide($sql) or logError("Error in getting code value", $sql);
    $myrow = mysql_fetch_array($res);
    $code = $myrow[$attribute];
    return $code;
}

function log_edit($paramArray, $table_name = "") {
    foreach (array(
        "HAVE_JCONTACT",
        "HAVE_JEDUCATION",
        "PHONE_WITH_STD",
        "SHOWBLACKBERRY",
        "SHOWLINKEDIN",
        "SHOWFACEBOOK",
        "CALL_ANONYMOUS",
        "SEC_SOURCE",
        'LANDL_STATUS',
        'MOB_STATUS',
        'ALT_MOB_STATUS'
    ) as $notToSet) unset($paramArray[$notToSet]);
    if (!$table_name) $table_name = "newjs.EDIT_LOG";
    $paramArray[IPADD] = FetchClientIP();
    foreach ($paramArray as $field => $value) {
        if($field != "ALT_EMAIL" && $field != "ALT_EMAIL_STATUS")
        {
            $value = addslashes(stripslashes($value));
            $fields.= $field . ",";
            $values.= "'$value',";
        }        
    }
    $fields = substr($fields, 0, -1);
    $values = substr($values, 0, -1);
    $fieldsArr = explode(",",$fields);    
    $diffArr = array_diff($fieldsArr,array("PROFILEID","IPADD","MOD_DT"));    
    if(count($diffArr) >0)
    {
        $sql_el = "INSERT INTO $table_name ($fields) VALUES ($values)";
        if (is_new_entry($paramArray[PROFILEID])) {
            $sql_bckup = "SELECT PROFILEID,USERNAME,GENDER,RELIGION,CASTE,SECT,MANGLIK,MTONGUE,MSTATUS,DTOFBIRTH,OCCUPATION,COUNTRY_RES,CITY_RES,HEIGHT, EDU_LEVEL,EMAIL,RELATION,COUNTRY_BIRTH,DRINK,SMOKE,HAVECHILD,RES_STATUS,BTYPE,COMPLEXION,DIET,HEARD,INCOME, HANDICAPPED,GOTHRA,GOTHRA_MATERNAL,NAKSHATRA,MESSENGER_ID,MESSENGER_CHANNEL,PHONE_RES,PHONE_MOB,FAMILY_BACK,SCREENING,CONTACT,SUBCASTE,YOURINFO,FAMILYINFO,SPOUSE,EDUCATION,PINCODE,PARENT_PINCODE,PRIVACY,EDU_LEVEL_NEW,FATHER_INFO,SIBLING_INFO,WIFE_WORKING,JOB_INFO,MARRIED_WORKING,PARENT_CITY_SAME,PARENTS_CONTACT,FAMILY_VALUES,STD,ISD,MOTHER_OCC,T_BROTHER,T_SISTER,M_BROTHER,M_SISTER,FAMILY_TYPE,FAMILY_STATUS,FAMILY_INCOME,CITIZENSHIP,BLOOD_GROUP,HIV,THALASSEMIA,WEIGHT,NATURE_HANDICAP,WORK_STATUS,ANCESTRAL_ORIGIN,HOROSCOPE_MATCH,SPEAK_URDU,PHONE_NUMBER_OWNER,PHONE_OWNER_NAME,MOBILE_NUMBER_OWNER,MOBILE_OWNER_NAME,RASHI,TIME_TO_CALL_START,TIME_TO_CALL_END,PROFILE_HANDLER_NAME,GOING_ABROAD,OPEN_TO_PET,HAVE_CAR,OWN_HOUSE,COMPANY_NAME,MOD_DT from newjs.JPROFILE where PROFILEID=" . $paramArray[PROFILEID];
            $res_bckup = mysql_query_decide($sql_bckup) or logError("Due to some temporary problem your request could not be processed. Please try after some time.", $sql_bckup, "ShowErrTemplate");
            $row_bckup = mysql_fetch_assoc($res_bckup);
            foreach ($row_bckup as $field => $value) {
                $value = addslashes(stripslashes($value));
                $fields_backup.= $field . ",";
                $values_backup.= "'$value',";
            }
            $fields_backup = substr($fields_backup, 0, -1);
            $values_backup = substr($values_backup, 0, -1);        
            $sql_backup = "INSERT INTO $table_name ($fields_backup) VALUES ($values_backup)";
            $result = mysql_query_decide($sql_backup) or logError("Due to some temporary problem your request could not be processed. Please try after some time.", $sql_backup, "ShowErrTemplate");
        }
    $result_el = mysql_query_decide($sql_el) or logError("Due to some temporary problem your request could not be processed. Please try after some time.", $sql_el, "ShowErrTemplate");
    }

    if(array_key_exists("ALT_EMAIL", $paramArray))
    {
        $emailUID=(new NEWJS_ALTERNATE_EMAIL_LOG())->insertEmailChange($paramArray["PROFILEID"],$paramArray['ALT_EMAIL']);
        if($paramArray["ALT_EMAIL"]!="")
        {
            $result = (new emailVerification())->sendAlternateVerificationMail($paramArray["PROFILEID"], $emailUID,$paramArray['ALT_EMAIL']);
        }
    }
}

function is_new_entry($profileid) {
    $sql = "SELECT count(*) cnt from newjs.EDIT_LOG where PROFILEID=$profileid";
    $res = mysql_query_decide($sql) or logError("Error in getting code value", $sql);
    $row = mysql_fetch_row($res);
    if (!$row[0]) return true;
}

function edit_nonHindu_religion($paramArray, $religion_table_name) {
    foreach ($paramArray as $field => $value) {
        $fields.= $field . ",";
        $values.= "'$value',";
    }
    $fields = substr($fields, 0, -1);
    $values = substr($values, 0, -1);
    $religion_log_table = array(
        'newjs.JP_CHRISTIAN' => 'newjs.EDIT_LOG_JPC',
        'newjs.JP_PARSI' => 'newjs.EDIT_LOG_JPP',
        'newjs.JP_MUSLIM' => 'newjs.EDIT_LOG_JPM',
        'newjs.JP_SIKH' => 'EDIT_LOG_JPS',
        'newjs.JP_JAIN' => 'newjs.EDIT_LOG_JPJ',
    );
    $sql = "REPLACE INTO $religion_table_name ($fields) VALUES ($values)";
    $sql_el = "INSERT INTO " . $religion_log_table[$religion_table_name] . " ($fields) VALUES ($values)";
    $result = mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.", $sql, "ShowErrTemplate");
    $result_el = mysql_query_decide($sql_el) or logError("Due to some temporary problem your request could not be processed. Please try after some time.", $sql_el, "ShowErrTemplate");
}

function update_annulled_reason($profileid, $COURT, $date_an, $REASON, $mstatus) {
    if ($COURT != '' && $date_an != '') {
        $sql_ms = "REPLACE INTO newjs.ANNULLED (ID,PROFILEID,COURT,DATE,REASON,ENTRY_DT,SCREENED,UPDATE_DT,MSTATUS) VALUES ('','$profileid','$COURT','$date_an','$REASON',now(),'N',now(),'$mstatus')";
        mysql_query_decide($sql_ms) or logError("Due to a temporary problem your request could not be processed. Please try after a couple of minutes", $sql_ms, "ShowErrTemplate");
    }
}

function unset_diocese($profileid) {
    $sql_di = "update JP_CHRISTIAN set DIOCESE='' where PROFILEID='$profileid'";
/*    mysql_query_decide($sql_di) or logError("Due to some temporary problem your request could not be processed. Please try after some time.", $sql_el1, "ShowErrTemplate");*/

    include_once(JsConstants::$docRoot."/classes/JProfileUpdateLib.php");
    $objUpdate = JProfileUpdateLib::getInstance();
    $result = $objUpdate->updateJP_CHRISTIAN($profileid,array('DIOCESE'=>''));
    if(false === $result) {
        logError("Due to some temporary problem your request could not be processed. Please try after some time.", $sql_di, "ShowErrTemplate");
    }
}

function edit_religion_mis_updates($profileid, $Mtongue) {
    $sql_clicks = "UPDATE MIS.ASTRO_CLICK_COUNT SET MTONGUE='$Mtongue' WHERE PROFILEID='$profileid'";
    mysql_query_decide($sql_clicks) or logError("1 Due to a temporary problem your request could not be processed. Please try after a couple of minutes", $sql_clicks, "ShowErrTemplate");
    
    $sql_generated = "UPDATE MIS.ASTRO_DATA_COUNT SET MTONGUE='$Mtongue' WHERE PROFILEID='$profileid'";
    mysql_query_decide($sql_generated) or logError("1 Due to a temporary problem your request could not be processed. Please try after a couple of minutes", $sql_generated, "ShowErrTemplate");
}

function update_cookie($value, $type) {
    if ($type == "INCOME") {
        setcookie('JS_INCOME', $value, 0, "/", $domain);
    }
}

function archive_contacts($paramArr, $profileid) {
    $date_now = date("Y-m-d H:i:s");
    $ip = FetchClientIP();
     //Gets ipaddress of user
    if (strstr($ip, ",")) {
        $ip_new = explode(",", $ip);
        $ip = $ip_new[1];
    }
    $sql_sel = "SELECT EMAIL, PARENTS_CONTACT,STD,ISD, CONTACT, PHONE_RES, PHONE_MOB, MESSENGER_ID, MESSENGER_CHANNEL FROM newjs.JPROFILE WHERE PROFILEID='$profileid'";
    $res_sel = mysql_query_decide($sql_sel) or die(mysql_error_js());
    $row_sel = mysql_fetch_assoc($res_sel);
    $arr_fields = array(
        0 => "EMAIL," . $paramArr[EMAIL],
        1 => "PARENTS_CONTACT," . $paramArr[PARENTS_CONTACT],
        2 => "CONTACT," . $paramArr[CONTACT],
        3 => "PHONE_RES," . $paramArr[ISD] . "-" . $paramArr[STD] . "-" . $paramArr[PHONE_RES],
        4 => "PHONE_MOB," . $paramArr[ISD] . "-" . $paramArr[PHONE_MOB]
    );
    
    foreach ($arr_fields as $key => $value) {
        $archive = 0;
        $val1 = explode(',', $value);
        $field = array_shift($val1);
        $val = implode(',', $val1);
        if ($field == "PHONE_RES") {
            if ($row_sel['PHONE_RES'] == $paramArr[PHONE_RES] && $paramArr[PHONE_RES] == '');
            else {
                $ph_row = $row_sel['ISD'] . "-" . $row_sel['STD'] . "-" . $row_sel['PHONE_RES'];
                if ($ph_row != $val) {
                    $archive = 1;
                    $ph_arr = explode("-", $val);
                    if ($ph_arr[2] == '') $val = '';
                }
            }
        } 
        elseif ($field == "PHONE_MOB") {
            if (($row_sel['PHONE_MOB'] == $Mobile) && ($Mobile == ''));
            else {
                $mob_row = $row_sel['ISD'] . "-" . $row_sel['PHONE_MOB'];
                if ($mob_row != $val) {
                    $archive = 1;
                    $mob_arr = explode("-", $val);
                    if ($mob_arr[1] == '') $val = '';
                }
            }
        } 
        else {
            if ($row_sel[$field] != $val) $archive = 1;
        }
        if ($archive) {
            if ($field == "PHONE_RES") {
                if ($row_sel['PHONE_RES'] != '') $old_val = $row_sel['ISD'] . "-" . $row_sel['STD'] . "-" . $row_sel['PHONE_RES'];
                else $old_val = '';
            } 
            elseif ($field == "PHONE_MOB") {
                if ($row_sel['PHONE_MOB'] != '') $old_val = $row_sel['ISD'] . "-" . $row_sel['PHONE_MOB'];
                else $old_val = '';
            } 
            else $old_val = $row_sel[$field];
            $sql_search = "SELECT CHANGEID FROM newjs.CONTACT_ARCHIVE WHERE PROFILEID='$profileid' AND FIELD='$field'";
            $res_search = mysql_query_decide($sql_search) or die(mysql_error_js());
            if ($field == "EMAIL") $smartyVar[EMAIL_F] = 1;
            if ($field == "PARENTS_CONTACT") $smartyVar[PARENTS_F] = 1;
            if ($field == "CONTACT") $smartyVar[CONTACT_F] = 1;
            if ($field == "PHONE_RES") $smartyVar[RES_F] = 1;
            if ($field == "PHONE_MOB") $smartyVar[MOB_F] = 1;
            if (mysql_num_rows($res_search) > 0) {
                $old_val = addslashes(stripslashes($old_val));
                $val = addslashes(stripslashes($val));
                $row_search = mysql_fetch_assoc($res_search);
                $changeid = $row_search['CHANGEID'];
                $sql_add = "INSERT INTO CONTACT_ARCHIVE_INFO (CHANGEID,DATE,IPADD,OLD_VAL,NEW_VAL) VALUES($changeid,'$date_now','$ip','$old_val','$val') ";
                $res_add = mysql_query_decide($sql_add) or die(mysql_error_js());
            } 
            else {
                $sql_insert = "INSERT INTO CONTACT_ARCHIVE(PROFILEID,FIELD) VALUES($profileid,'$field')";
                $res_insert = mysql_query_decide($sql_insert) or die(mysql_error_js());
                
                $sql_search = "SELECT CHANGEID FROM newjs.CONTACT_ARCHIVE WHERE PROFILEID='$profileid' AND FIELD='$field'";
                $res_search = mysql_query_decide($sql_search) or die(mysql_error_js());
                $row_search = mysql_fetch_assoc($res_search);
                $changeid = $row_search['CHANGEID'];
                
                $sql_add = "INSERT INTO CONTACT_ARCHIVE_INFO (CHANGEID,DATE,IPADD,OLD_VAL,NEW_VAL) VALUES($changeid,'$date_now','$ip','$old_val','$val') ";
                $res_add = mysql_query_decide($sql_add) or die(mysql_error_js());
            }
        }
    }
    $val = $paramArr[MESSENGER_ID] . "@" . $paramArr[MESSENGER_CHANNEL];
    if ($row_sel['MESSENGER_ID']) $old_val = $row_sel['MESSENGER_ID'] . "@" . $row_sel['MESSENGER_CHANNEL'];
    else $old_val = '';
    $field = 'MESSENGER';
    if ($val == '@') $val = '';
    if ($old_val != $val) {
        $sql_search = "SELECT CHANGEID FROM newjs.CONTACT_ARCHIVE WHERE PROFILEID='$profileid' AND FIELD='$field'";
        $res_search = mysql_query_decide($sql_search) or die(mysql_error_js());
        $smartyVar[MESS_F] = 1;
        if (mysql_num_rows($res_search) > 0) {
            $row_search = mysql_fetch_assoc($res_search);
            $changeid = $row_search['CHANGEID'];
            $sql_add = "INSERT INTO CONTACT_ARCHIVE_INFO (CHANGEID,DATE,IPADD,OLD_VAL,NEW_VAL) VALUES($changeid,'$date_now','$ip','$old_val','$val') ";
            $res_add = mysql_query_decide($sql_add) or die(mysql_error_js());
        } 
        else {
            $sql_insert = "INSERT INTO CONTACT_ARCHIVE(PROFILEID,FIELD) VALUES($profileid,'$field')";
            $res_insert = mysql_query_decide($sql_insert) or die(mysql_error_js());
            $sql_search = "SELECT CHANGEID FROM newjs.CONTACT_ARCHIVE WHERE PROFILEID='$profileid' AND FIELD='$field'";
            $res_search = mysql_query_decide($sql_search) or die(mysql_error_js());
            $row_search = mysql_fetch_assoc($res_search);
            $changeid = $row_search['CHANGEID'];
            $sql_add = "INSERT INTO CONTACT_ARCHIVE_INFO (CHANGEID,DATE,IPADD,OLD_VAL,NEW_VAL) VALUES($changeid,'$date_now','$ip','$old_val','$val') ";
            $res_add = mysql_query_decide($sql_add) or die(mysql_error_js());
        }
    }
    return $smartyVar;
}

function remove_from_invalid_email_mailer($profileid) {
    $sql = "DELETE FROM newjs.INVALID_EMAIL_MAILER WHERE PROFILEID='$profileid'";
    $result = mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.", $sql, "ShowErrTemplate");
}

function ivr_call($profileid, $phone_changed, $phone_updated, $mob_updated, $Mobile, $Phone, $State_Code, $off_source) {
    $today = date("Y-m-d");
    if ($mob_updated && $off_source != 'ofl_prof') {
        phoneUpdateProcess($profileid, '', 'M', 'E');
        $sql = "UPDATE jsadmin.OFFLINE_MATCHES SET CATEGORY='' WHERE MATCH_ID='$profileid' AND CATEGORY='6'";
        mysql_query_decide($sql) or logError("1 Due to a temporary problem, your request could not be processed. Please try after a couple of minutes", $sql, "ShowErrTemplate");
    }
    if ($profileid) {
        if ($phone_updated) {
            $invalid_contact_no = bounced_phone($profileid);
            
            //===========  IVR-  Phone Verification Code (IVR-Call)  ================
            include_once ($_SERVER['DOCUMENT_ROOT'] . "/ivr/jsPhoneVerify.php");
            if ($phone_changed == 1) {
                phoneUpdateProcess($profileid, '', 'L', 'E');
            }
            
            //============== End IVR-Check==================================
            
            if ($invalid_contact_no) {
                $sql = " DELETE FROM newjs.INVALID_PHONE_MAILER where PROFILEID='$profileid'";
                $result = mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.", $sql, "ShowErrTemplate");
                
                $sql = "UPDATE MIS.USERDETAILS set PHONE_MOB_UPDATE=PHONE_MOB_UPDATE+1";
                $sql.= " where ENTRY_DATE='$today'";
                mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.", $sql, "ShowErrTemplate");
            }
        }
    }
}

function bot_email_entry($profileid, $Email) {
    global $data;
    $username = $data['USERNAME'];
    if (strstr($Email, "@gmail")) {
        $sql = "delete from bot_jeevansathi.user_info where  profileID='$profileid' OR gmail_ID='$Email'";
        mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.", $sql, "ShowErrTemplate");
        
        $sql = "delete from bot_jeevansathi.user_online where  USER='$profileid'";
        mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.", $sql, "ShowErrTemplate");
        
        $sql_invite_entry = "insert into bot_jeevansathi.gmail_invites(profileid,gmailid) values('$username','$Email')";
        mysql_query_decide($sql_invite_entry) or logError("Due to some temporary problem your request could not be processed. Please try after some time.", $sql, "ShowErrTemplate");
        
        $sql_invite_entry = "insert into bot_jeevansathi.invite_send(PROFILEID,EMAIL) values('$profileid','$Email')";
        mysql_query_decide($sql_invite_entry) or logError("Due to some temporary problem your request could not be processed. Please try after some time.", $sql, "ShowErrTemplate");
        
        $sql_bot_entry = "insert ignore into bot_jeevansathi.user_info(`gmail_ID`,`on_off_flag`,`show_in_search`,`profileID`,`jeevansathi_ID`) values('$Email',0,1,'$profileid','$username')";
        mysql_query_decide($sql_bot_entry) or logError("Due to some temporary problem your request could not be processed. Please try after some time.", $sql, "ShowErrTemplate");
        //send_chat_request_email($profileid, $Email, $username);
    }
}

function update_astro_layer_mis($profileid, $type, $user_mtongue) {
    if (!$type) $type = "E";
    
    $sql = "INSERT INTO newjs.ASTRO_PULLING_REQUEST (PROFILEID,ENTRY_DT,PENDING,TYPE) VALUES('$profileid',NOW(),'Y','$type')";
    mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.", $sql, "ShowErrTemplate");
    $sql = "INSERT INTO MIS.ASTRO_CLICK_COUNT(PROFILEID,TYPE,ENTRY_DT,MTONGUE) VALUES('$profileid','$type',NOW(),'$user_mtongue')";
    mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.", $sql, "ShowErrTemplate");
}

function get_country_birth_value($label) {
    if ($label) {
        $sql_ctry = "SELECT VALUE FROM newjs.COUNTRY WHERE LABEL='$label'";
        $res_ctry = mysql_query_decide($sql_ctry) or logError($sql_ctry, "ShowErrTemplate");
        $row_ctry = mysql_fetch_array($res_ctry);
        
        //here Value = 136 is for "Others".
        $value = $row_ctry['VALUE'] ? $row_ctry['VALUE'] : 136;
    } 
    else $value = 136;
    return $value;
}

function track_completeness($SOURCE, $profileid, $USERNAME, $city_res, $age, $gender) {
    global $smarty;
    $sql_gp = "SELECT GROUPNAME FROM MIS.SOURCE WHERE SourceID='$SOURCE'";
    $ressource_gp = mysql_query_decide($sql_gp) or logError("Due to some temporary problem your request could not be processed. Please try after some time.", $sql, "ShowErrTemplate");
    if (mysql_num_rows($ressource_gp)) {
        $mysource_gp = mysql_fetch_array($ressource_gp);
        $groupname = $mysource_gp["GROUPNAME"];
        if ($groupname == "google") $smarty->assign("reg_comp_frm_ggl", "1");
        elseif ($groupname == "Google_NRI") $smarty->assign("reg_comp_frm_ggl_nri", "1");
        if ($groupname) $VAR = $groupname;
        elseif ($GROUPNAME) $VAR = $GROUPNAME;
        elseif ($SOURCE) $VAR = $SOURCE;
        $pixelcode = pixelcode($VAR);
        $pixelcode = str_replace('~$USERNAME`', $USERNAME, $pixelcode);
        $pixelcode = str_replace('~$PROFILEID`', $profileid, $pixelcode);
        $pixelcode = str_replace('~$CITY`', $city_res, $pixelcode);
        $pixelcode = str_replace('~$AGE`', $age, $pixelcode);
        $pixelcode = str_replace('~$GENDER`', $gender, $pixelcode);
        $smarty->assign("pixelcode", $pixelcode);
        $smarty->assign("GROUPNAME", $groupname);
        $smarty->assign("SOURCE", $SOURCE);
        $smarty->assign("groupname", $groupname);
        $smarty->assign("fromeditpage", 1);
        $smarty->assign("REGISTRATION", $smarty->fetch("registration_tracking.htm"));
    }
}

function pixelcode($VAR) {
    if ($VAR) {
        $sql = "SELECT PIXELCODE FROM MIS.PIXELCODE WHERE GROUPNAME='$VAR'";
        $res = mysql_query_decide($sql) or logError("Due to some temporary problem your request could not be processed. Please try after some time.", $sql, "ShowErrTemplate");
        $row = mysql_fetch_array($res);
        return $row["PIXELCODE"];
    }
}

function get_photo_request_count($mypid) {
    $mysqlObj = new Mysql;
    
    $myDbName = getProfileDatabaseConnectionName($mypid, '', $mysqlObj);
    $myDb = $mysqlObj->connect("$myDbName");
    $sql = "SELECT PROFILEID FROM PHOTO_REQUEST WHERE PROFILEID_REQ_BY='$mypid'";
    $result = $mysqlObj->executeQuery($sql, $myDb);
    while ($row = mysql_fetch_array($result)) {
        $sender_pid = $row['PROFILEID'];
        $DECLINED_PROFILES = getCancelDeclinedContacts($mypid, "", $mysqlObj, $myDb);
        if (!in_array($sender_pid, $DECLINED_PROFILES)) {
            $photo_req = $photo_req + 1;
        }
    }
    return $photo_req;
}

function getCancelDeclinedContacts($self_profileid, $profilesArr = "", $mysqlObj, $db) {
    $DECLINED_PROFILES = array();
    
    //if($self_profileid && $profilesArr)
    if ($self_profileid) {
        
        //$profileIds = implode(',',$profilesArr);
        //$sql_msg = "SELECT SENDER FROM newjs.CONTACTS WHERE newjs.CONTACTS.SENDER IN($profileIds) AND newjs.CONTACTS.RECEIVER = '$self_profileid' AND newjs.CONTACTS.TYPE IN ('D','C')";
        $sql_msg = "SELECT SENDER FROM newjs.CONTACTS WHERE newjs.CONTACTS.RECEIVER = '$self_profileid' AND newjs.CONTACTS.TYPE IN ('D','C')";
        $res_msg = $mysqlObj->executeQuery($sql_msg, $db);
        while ($row_msg = $mysqlObj->fetchAssoc($res_msg)) {
            $DECLINED_PROFILES[] = $row_msg['SENDER'];
        }
        
        //$sql_msg = "SELECT RECEIVER FROM newjs.CONTACTS WHERE newjs.CONTACTS.RECEIVER IN($profileIds) AND newjs.CONTACTS.SENDER = '$self_profileid' AND newjs.CONTACTS.TYPE IN('C','D')";
        $sql_msg = "SELECT RECEIVER FROM newjs.CONTACTS WHERE newjs.CONTACTS.SENDER = '$self_profileid' AND newjs.CONTACTS.TYPE IN('C','D')";
        $res_msg = $mysqlObj->executeQuery($sql_msg, $db);
        while ($row_msg = $mysqlObj->fetchAssoc($res_msg)) {
            $DECLINED_PROFILES[] = $row_msg['RECEIVER'];
        }
    }
    return $DECLINED_PROFILES;
}

function insert_in_old_email($profileid, $email) {
    $sql = "INSERT IGNORE INTO OLDEMAIL VALUES('$profileid','$email')";
    mysql_query_decide($sql) or logError($errorMsg, "$sql", "ShowErrTemplate");
}

/**
 * @fn mapAutoSugSubcasteData
 * @brief This function maps $value to auto sug SUBCASTE list.
 * @param $value The field to be mapped
 * @param $profile_id The profile ID of the logged in User
 *
 */
function mapAutoSugSubcasteData($profile_id, $value) {
    $value = trim($value, " \n\t\r");
    $sug_subcaste_id = 0;
    $sql1 = "select SQL_CACHE DISTINCT(SUBCASTE_ID) from newjs.SUBCASTE_SPELLINGS_MAP where SPELLING like '%" . $value . "%'";
    $result = mysql_query_decide($sql1) or logError($errorMsg, $sql1, "ShowErrTemplate");
    if (count($row = mysql_fetch_assoc($result)) > 0) {
        $sug_subcaste_id = $row['SUBCASTE_ID'];
    }
    
    if ($value) {
        if ($sug_subcaste_id) {
            $sql2 = "INSERT IGNORE INTO newjs.USER_SUBCASTE_MAP(PROFILEID, SUBCASTE, SUBCASTE_ID) VALUES('" . $profile_id . "', '" . $value . "', '" . $sug_subcaste_id . "')";
            mysql_query_decide($sql2) or logError($errorMsg, $sql2, "ShowErrTemplate");
        } 
        else {
            $sql2 = "INSERT IGNORE INTO newjs.USER_SUBCASTE_MAP(PROFILEID, SUBCASTE) VALUES('" . $profile_id . "', '" . $value . "')";
            mysql_query_decide($sql2) or logError($errorMsg, $sql2, "ShowErrTemplate");
        }
    }
}

function insert_in_duplication_check_fields($profileid, $type, $value) {
    if ($value) {
        if ($profileid) {
            $sqlJprofile = "SELECT CASTE,MTONGUE FROM newjs.JPROFILE WHERE PROFILEID='" . $profileid . "'";
            $resJprofile = mysql_query_decide($sqlJprofile) or logError($errorMsg, $sqlJprofile, "ShowErrTemplate");
            if ($rowJprofile = mysql_fetch_assoc($resJprofile)) {
                $CASTE = $rowJprofile['CASTE'];
                $MTONGUE = $rowJprofile['MTONGUE'];
            }
        }
        $sql = "REPLACE INTO duplicates.DUPLICATE_CHECKS_FIELDS SET PROFILEID='$profileid',TYPE='$type',FIELDS_TO_BE_CHECKED='$value',CASTE='$CASTE',MTONGUE='$MTONGUE'";
        mysql_query_decide($sql) or logError($errorMsg, "$sql", "ShowErrTemplate");
    }
}

function get_from_duplication_check_fields($profileid) {
    $sql = "SELECT FIELDS_TO_BE_CHECKED,TYPE from duplicates.DUPLICATE_CHECKS_FIELDS where PROFILEID='$profileid'";
    $res = mysql_query_decide($sql) or logError($errorMsg, "$sql", "ShowErrTemplate");
    if ($res) {
        if ($row = mysql_fetch_assoc($res));
        return $row;
    }
    return 0;
}

function my_checkoldemail($email, $profileid)
// returns 1 if email id not valid
{
    $flag = 0;
    if (trim($email) != "") {
        $sql = "SELECT COUNT(*) as cnt FROM newjs.OLDEMAIL where OLD_EMAIL='$email'";
        $result = mysql_query_decide($sql) or logError("error", $sql);
        $myrow = mysql_fetch_array($result);
        if ($myrow['cnt'] > 0) {
            $sql = "SELECT COUNT(*) as CNT FROM OLDEMAIL where OLD_EMAIL='$email' AND PROFILEID='$profileid'";
            $result = mysql_query_decide($sql) or logError("error", $sql);
            $myrow = mysql_fetch_array($result);
            if ($myrow['CNT'] == 0) $flag = 2;
        }
    }
    return $flag;
}
