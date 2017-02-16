<?php

/**
 * Alert Manager Library
 * Avneet Singh Bindra
 * 1 Dec 2015
 */

include_once ($_SERVER['DOCUMENT_ROOT'] . "/profile/functions_edit_profile.php");
include_once ($_SERVER['DOCUMENT_ROOT'] . "/profile/track_matchalert.php");

class AlertManager
{
    
    public function __construct($request) {
        $this->loginData = $request->getAttribute("loginData");
        $this->profileid = $this->loginData['PROFILEID'];
        if (!empty($this->profileid)) {
            $this->parseRequestParams($request);
        }
        $this->updateParam = $request->getParameter('update');
    }
    
    public function parseRequestParams($request) {
        $this->objVars['source'] = $request->getParameter('source');
        $this->objVars['matchalertTrack'] = $request->getParameter('matchalertTrack');
        $this->objVars['logic_used'] = $request->getParameter('logic_used');
        $this->objVars['kundliTracking'] = $request->getParameter('kundliTracking');
        $this->objVars['newMatchesMailTrack'] = $request->getParameter('newMatchesMailTrack');
        $this->objVars['logic_used'] = $request->getParameter('logic_used');
        $this->objVars['matchalertTracksubmit'] = $request->getParameter('matchalertTracksubmit');
        $this->objVars['emailadd'] = $request->getParameter('emailadd');
        $this->objVars['promo_sms'] = $request->getParameter('promo_sms');
        $this->objVars['promo_mails'] = $request->getParameter('promo_mails');
        $this->objVars['match_alert'] = $request->getParameter('match_alert');
        $this->objVars['mem_call'] = $request->getParameter('mem_call');
        $this->objVars['offer_call'] = $request->getParameter('offer_call');
        $this->objVars['service1'] = $request->getParameter('service1');
        $this->objVars['service2'] = $request->getParameter('service2');
        $this->objVars['mem_mail'] = $request->getParameter('mem_mail');
        $this->objVars['contact_alert'] = $request->getParameter('contact_alert');
        $this->objVars['kundli_alert'] = $request->getParameter('kundli_alert');
        $this->objVars['photo_req'] = $request->getParameter('photo_req');
        $this->objVars['new_matches_mail'] = $request->getParameter('new_matches_mail');
        $this->objVars['serv_mail'] = $request->getParameter('serv_mail');
        $this->objVars['serv_sms'] = $request->getParameter('serv_sms');
        $this->objVars['serv_mms'] = $request->getParameter('serv_mms');
        $this->objVars['serv_ussd'] = $request->getParameter('serv_ussd');
        $this->objVars['promo_ussd'] = $request->getParameter('promo_ussd');
        $this->objVars['promo_mms'] = $request->getParameter('promo_mms');
        $this->objVars['vis_alert'] = $request->getParameter('vis_alert');
        $this->objVars['email'] = $request->getParameter('email');
        $this->objVars['push_notify'] = $request->getParameter('push_notify');
        $this->objVars[''] = $request->getParameter('');
        if ($this->objVars['source'] == 'ofl_prof') {
            $this->objVars['allow_js'] = 1;
        }
        $this->objVars['settingArray'] = AlertManagerEnums::$settingsArray;
    }
    
    public function generateResponse() {
        if (!empty($this->profileid)) {
            if (empty($this->updateParam)) {
                if ($this->objVars['matchalertTrack']) {
                    TrackEditUnsubscribe($data["PROFILEID"], 'V', $logic_used);
                }
                if ($this->objVars['kundliTracking']) {
                    $kundMailerTrackObj = new MIS_KUNDLI_MAILER_TRACKING();
                    $kundMailerTrackObj->insertDateAndSub(date("Y-m-d"));
                }
                if ($this->objVars['newMatchesMailTrack']) {
                    if ($sent_date && !is_numeric($sent_date)) {
                        $sent_date = "";
                    }
                    if ($sent_date) {
                        $dateString = MailerConfigVariables::decodeLogicalDate($sent_date);
                    } 
                    else {
                        $dateString = date("Y-m-d");
                    }
                    $matTrackObj = new MATCHALERT_TRACKING_NEW_MATCHES_EMAILS_TRACKING();
                    $matTrackObj->insertDateAndSub($dateString);
                    link_track("unsubscribe.php");
                }
                if ($this->loginData) {
                    $dbObj = new newjs_SMS_SUBSCRIPTION_DEACTIVATED();
                    $this->objVars['sms_unsubscribe'] = $dbObj->getCount($this->profileid);
                    
                    $jprofileObj = new JPROFILE();
                    $row = $jprofileObj->get($this->profileid, 'PROFILEID', 'SOURCE,PROMO_MAILS,SERVICE_MESSAGES,PERSONAL_MATCHES,EMAIL,GET_SMS');
                    $this->objVars['email'] = $row['EMAIL'];
                    $this->objVars['service'] = $row['SERVICE_MESSAGES'];
                    $this->objVars['source'] = $row['SOURCE'];
                    if ($row['GET_SMS'] == "Y") {
                    	$this->objVars['settingArray']["PS"][2] = "S";
                    } else {
                    	$this->objVars['settingArray']["PS"][2] = "U";
                    }
                   	$this->objVars['settingArray']["PM"][2] = $row['PROMO_MAILS'];
                    $this->objVars['settingArray']["MA"][2] = $row['PERSONAL_MATCHES'];
                    
                    $jprofileAlertObj = new JprofileAlertsCache();
                    $row2 = $jprofileAlertObj->getAllSubscriptions($this->profileid);
                    if(empty($row2)){
                    	$jprofileAlertObj->insertNewRow($this->profileid);
                    	$row2 = $jprofileAlertObj->getAllSubscriptions($this->profileid);
                    }
                    if (is_array($row2) && !empty($row2)) {
                        $this->objVars['settingArray']["MC"][2] = $row2['MEMB_CALLS'];
                        $this->objVars['settingArray']["OC"][2] = $row2['OFFER_CALLS'];
                        $this->objVars['settingArray']["SC1"][2] = $row2['SERV_CALLS_SITE'];
                        $this->objVars['settingArray']["SC2"][2] = $row2['SERV_CALLS_PROF'];
                        $this->objVars['settingArray']["MM"][2] = $row2['MEMB_MAILS'];
                        $this->objVars['settingArray']["CA"][2] = $row2['CONTACT_ALERT_MAILS'];
                        $this->objVars['settingArray']["KA"][2] = $row2['KUNDLI_ALERT_MAILS'];
                        $this->objVars['settingArray']["PR"][2] = $row2['PHOTO_REQUEST_MAILS'];
                        $this->objVars['settingArray']["NMM"][2] = $row2['NEW_MATCHES_MAILS'];
                        $this->objVars['settingArray']["SS"][2] = $row2['SERVICE_SMS'];
                        $this->objVars['settingArray']["STM"][2] = $row2['SERVICE_MMS'];
                        $this->objVars['settingArray']["SU"][2] = $row2['SERVICE_USSD'];
                        $this->objVars['settingArray']["PU"][2] = $row2['PROMO_USSD'];
                        $this->objVars['settingArray']["SM"][2] = $row2['SERVICE_MAILS'];
                        $this->objVars['settingArray']["PMM"][2] = $row2['PROMO_MMS'];
                    }
                    
                    $visitAlertOptObj = new visitoralert_VISITOR_ALERT_OPTION('shard1_master');
                    $this->objVars['settingArray']["VA"][2] = $visitAlertOptObj->fetchAlertOption($this->profileid);

                    $notificationAlertsObj = new NotificationConfigurationFunc();
                    $channel = MobileCommon::isMobile()?"M":"D";
                    $this->objVars['settingArray']["PN"][2] = $notificationAlertsObj->checkProfileIfSubscribed($this->profileid,$channel);
                    unset($notificationAlertsObj);
                }
                
                return array('currentSettings' => $this->resortSettings($this->objVars['settingArray']),
                	'userEmail' => $this->objVars['email']);
            } 
            else {
                if ($this->objVars['matchalertTracksubmit']) {
                    TrackEditUnsubscribe($data["PROFILEID"], 'E');
                }
                $jprofileObj = new JPROFILE();
                $jprofileAlertObj = new JprofileAlertsCache();
                $jprofileAlertLogObj = new newjs_JPROFILE_ALERTS_LOG();
                $visitAlertOptObj = new visitoralert_VISITOR_ALERT_OPTION('shard1_master');
                $today = date("Y-m-d");
                
                if ($this->updateParam == 'promo_sms') {
                    if ($this->objVars['promo_sms'] == 'S') {
                        $new_sms_value = "Y";
                    } 
                    else {
                        $new_sms_value = "N";
                    }
                    $jprofileObj->edit(array(
                        "UDATE" => $today,
                        "GET_SMS" => $new_sms_value
                    ) , $this->profileid, "PROFILEID");
                    return array(
                        'error' => NULL
                    );
                }

                if ($this->updateParam == 'promo_mails') {
                    $jprofileObj->edit(array(
                        "UDATE" => $today,
                        "PROMO_MAILS" => $this->objVars['promo_mails']
                    ) , $this->profileid, "PROFILEID");
                    return array(
                        'error' => NULL
                    );
                }
                
                if ($this->updateParam == 'match_alert') {
                    $jprofileObj->edit(array(
                        "UDATE" => $today,
                        "PERSONAL_MATCHES" => $this->objVars['match_alert']
                    ) , $this->profileid, "PROFILEID");
                    return array(
                        'error' => NULL
                    );
                }
                
                if ($this->updateParam == 'mem_call') {
                    $jprofileAlertObj->update($this->profileid, 'MEMB_CALLS', $this->objVars['mem_call']);
                    $jprofileAlertLogObj->update($this->profileid, 'MEMB_CALLS', $this->objVars['mem_call'], $today);
                    return array(
                        'error' => NULL
                    );
                }
                
                if ($this->updateParam == 'offer_call') {
                    $jprofileAlertObj->update($this->profileid, 'OFFER_CALLS', $this->objVars['offer_call']);
                    $jprofileAlertLogObj->update($this->profileid, 'OFFER_CALLS', $this->objVars['offer_call'], $today);
                    return array(
                        'error' => NULL
                    );
                }
                
                if ($this->updateParam == 'service1') {
                    $jprofileAlertObj->update($this->profileid, 'SERV_CALLS_SITE', $this->objVars['service1']);
                    $jprofileAlertLogObj->update($this->profileid, 'SERV_CALLS_SITE', $this->objVars['service1'], $today);
                    return array(
                        'error' => NULL
                    );
                }
                
                if ($this->updateParam == 'service2') {
                    $jprofileAlertObj->update($this->profileid, 'SERV_CALLS_PROF', $this->objVars['service2']);
                    $jprofileAlertLogObj->update($this->profileid, 'SERV_CALLS_PROF', $this->objVars['service2'], $today);
                    return array(
                        'error' => NULL
                    );
                }
                
                if ($this->updateParam == 'mem_mail') {
                    $jprofileAlertObj->update($this->profileid, 'MEMB_MAILS', $this->objVars['mem_mail']);
                    $jprofileAlertLogObj->update($this->profileid, 'MEMB_MAILS', $this->objVars['mem_mail'], $today);
                    return array(
                        'error' => NULL
                    );
                }
                
                if ($this->updateParam == 'contact_alert') {
                    $jprofileAlertObj->update($this->profileid, 'CONTACT_ALERT_MAILS', $this->objVars['contact_alert']);
                    $jprofileAlertLogObj->update($this->profileid, 'CONTACT_ALERT_MAILS', $this->objVars['contact_alert'], $today);
                    return array(
                        'error' => NULL
                    );
                }
                
                if ($this->updateParam == 'kundli_alert') {
                    $jprofileAlertObj->update($this->profileid, 'KUNDLI_ALERT_MAILS', $this->objVars['kundli_alert']);
                    $jprofileAlertLogObj->update($this->profileid, 'KUNDLI_ALERT_MAILS', $this->objVars['kundli_alert'], $today);
                    return array(
                        'error' => NULL
                    );
                }
                
                if ($this->updateParam == 'photo_req') {
                    $jprofileAlertObj->update($this->profileid, 'PHOTO_REQUEST_MAILS', $this->objVars['photo_req']);
                    $jprofileAlertLogObj->update($this->profileid, 'PHOTO_REQUEST_MAILS', $this->objVars['photo_req'], $today);
                    return array(
                        'error' => NULL
                    );
                }
                
                if ($this->updateParam == 'new_matches_mail') {
                    $jprofileAlertObj->update($this->profileid, 'NEW_MATCHES_MAILS', $this->objVars['new_matches_mail']);
                    return array(
                        'error' => NULL
                    );
                }
                
                if ($this->updateParam == 'serv_mail') {
                    $jprofileAlertObj->update($this->profileid, 'SERVICE_MAILS', $this->objVars['serv_mail']);
                    $jprofileAlertLogObj->update($this->profileid, 'SERVICE_MAILS', $this->objVars['serv_mail'], $today);
                    return array(
                        'error' => NULL
                    );
                }
                
                if ($this->updateParam == 'serv_sms') {
                    $jprofileAlertObj->update($this->profileid, 'SERVICE_SMS', $this->objVars['serv_sms']);
                    $jprofileAlertLogObj->update($this->profileid, 'SERVICE_SMS', $this->objVars['serv_sms'], $today);
                    return array(
                        'error' => NULL
                    );
                }
                
                if ($this->updateParam == 'serv_mms') {
                    $jprofileAlertObj->update($this->profileid, 'SERVICE_MMS', $this->objVars['serv_mms']);
                    $jprofileAlertLogObj->update($this->profileid, 'SERVICE_MMS', $this->objVars['serv_mms'], $today);
                    return array(
                        'error' => NULL
                    );
                }
                
                if ($this->updateParam == 'serv_ussd') {
                    $jprofileAlertObj->update($this->profileid, 'SERVICE_USSD', $this->objVars['serv_ussd']);
                    $jprofileAlertLogObj->update($this->profileid, 'SERVICE_USSD', $this->objVars['serv_ussd'], $today);
                    return array(
                        'error' => NULL
                    );
                }
                
                if ($this->updateParam == 'promo_ussd') {
                    $jprofileAlertObj->update($this->profileid, 'PROMO_USSD', $this->objVars['promo_ussd']);
                    $jprofileAlertLogObj->update($this->profileid, 'PROMO_USSD', $this->objVars['promo_ussd'], $today);
                    return array(
                        'error' => NULL
                    );
                }
                
                if ($this->updateParam == 'promo_mms') {
                    $jprofileAlertObj->update($this->profileid, 'PROMO_MMS', $this->objVars['promo_mms']);
                    $jprofileAlertLogObj->update($this->profileid, 'PROMO_MMS', $this->objVars['promo_mms'], $today);
                    return array(
                        'error' => NULL
                    );
                }
                
                if ($this->updateParam == 'vis_alert') {
                    $visitAlertOptObj->updateAlertOption($this->profileid, $this->objVars['vis_alert']);
                    return array(
                        'error' => NULL
                    );
                }
                
                if ($this->updateParam == 'emailadd') {
                    if (trim($this->objVars['emailadd']) == "") {
                        return array(
                            'error' => 'E-mail id cannot be empty, please enter a new e-mail id to update your preference'
                        );
                    } 
                    else {
                        if (trim($this->objVars['emailadd']) != trim($this->objVars['email'])) {
                            if (!$this->objVars['allow_js']) {
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
                            return array(
                                'error' => $MSG
                            );
                        } 
                        else {
                            $newjsOldEmailObj = new newjs_OLDEMAIL();
                            $newjsOldEmailObj->update($this->profileid, trim($this->objVars['email']));
                            $date_now = date("Y-m-d H:i:s");
                            $memHandlerObj = new MembershipHandler();
                            list($ip, $country) = $memHandlerObj->getUserIPandCurrency();
                            if (trim($this->objVars['emailadd']) != trim($this->objVars['email'])) {
                                $dup_fields[] = "email";
                                duplication_fields_insertion($dup_fields, $this->profileid);
                                $expireDt = date("Y-m-d H:i:s");
                                $jsadminAutoExpObj = new ProfileAUTO_EXPIRY();
                                $jsadminAutoExpObj->replace($this->profileid, 'E', $expireDt);
                                $newjsContactArchiveObj = new NEWJS_CONTACT_ARCHIVE();
                                $newjsContactArchiveInfoObj = new CONTACT_ARCHIVE_INFO();
                                $data = $newjsContactArchiveObj->fetchData($this->profileid);
                                if (!empty($data)) {
                                    $changedId = $data['CHANGEID'];
                                    $newjsContactArchiveInfoObj->insert($changedId, $ip, trim($this->objVars['emailadd']) , trim($this->objVars['email']));
                                } 
                                else {
                                    $newjsContactArchiveObj->insert($this->profileid, 'EMAIL');
                                    $newjsContactArchiveInfoObj->insert($changedId, $ip, trim($this->objVars['emailadd']) , trim($this->objVars['email']));
                                }
                                if (strstr($this->objVars['emailadd'], "@gmail.com")) {
                                    
                                    // Chat Code here
                                    $userData = $jprofileObj->get($this->profileid, 'PROFILEID', "USERNAME");
                                    //send_chat_request_email($this->profileid, $this->objVars['emailadd'], $userData['USERNAME']);
                                    $botUserInfoObj = new bot_jeevansathi_user_info();
                                    $botUserOnlineObj = new bot_jeevansathi_user_online();
                                    $botGmailObj = new bot_jeevansathi_gmail_invites();
                                    $botSendObj = new bot_jeevansathi_invite_send();
                                    $botUserInfoObj->delete($this->profileid, trim($this->objVars['emailadd']));
                                    $botUserOnlineObj->delete($this->profileid);
                                    $botGmailObj->insert($userData['USERNAME'], trim($this->objVars['emailadd']));
                                    $botSendObj->insert($this->profileid, trim($this->objVars['emailadd']));
                                    $botUserInfoObj->insert($this->profileid, trim($this->objVars['emailadd']) , $userData['USERNAME']);
                                }
                                $invalid_email = bounced_emailID($this->profileid);
                                $jprofileObj->edit(array(
                                    "EMAIL" => trim($this->objVars['emailadd']),
                                    "VERIFY_EMAIL" => 'N'
                                ) , $this->profileid, "PROFILEID");
                                $cookie_value = $_COOKIE['INVALID_EMAIL'];
                                settype($cookie_value, "integer");
                                if ($cookie_value != 2) {
                                    if ($invalid_email) {
                                        $newjsInvMailObj = new newjs_INVALID_EMAIL_MAILER();
                                        $newjsInvMailObj->delete($this->profileid);
                                        $misEmailDetObj = new MIS_EMAILDETAILS();
                                        $misEmailDetObj->updateInsert($today);
                                    }
                                }
                                setcookie("INVALID_EMAIL", "2", 0, "/", $domain);
                            }
                        }
                    }
                    return array(
                        'error' => NULL
                    );
                }

                if($this->updateParam == 'push_notify')
                {
                    if($this->objVars['push_notify']=="S")
                        $status = "Y";
                    else
                        $status="N";
                    $channel = MobileCommon::isMobile()?"M":"D";
                    $notificationUpdateObj = new MOBILE_API_BROWSER_NOTIFICATION_REGISTRATION();
                    $notificationUpdateObj->updateActivationStatus($this->profileid, $status,$channel);
                    unset($notificationUpdateObj);
                    return array(
                        'error' => NULL
                    );
                }
            }
        }
    }

    public function resortSettings($settingsArr) {
        
        $order = AlertManagerEnums::$sortArr;
        $sortedArr = array();
        if (is_array($order)) {
            foreach ($order as $key => $val) {
                $sortedArr[$val] = $settingsArr[$val];
            }
        }
        $reArrangedArr = array();
        $mailAlertsCheck = array("MA",
        "VA",
        "MM",
        "NMM",
        "CA",
        "PR",
        "KA",
        "SM",
        "PM",);
        $smsAlertsCheck = array("PS",
        "SS",
        "STM",
        "SU",
        "PU",
        "PMM",);
        $callAlertsCheck = array("MC",
        "OC",
        "SC1",
        "SC2",);
        $notificationAlertsCheck = array(
        "PN");
        foreach($sortedArr as $key=>$val){
        	if(in_array($key, $mailAlertsCheck)){
        		$reArrangedArr['mail_alert_section'][$key] = $sortedArr[$key];
        	}
        	if(in_array($key, $smsAlertsCheck)){
        		$reArrangedArr['sms_alert_section'][$key] = $sortedArr[$key];
        	}
        	if(in_array($key, $callAlertsCheck)){
        		$reArrangedArr['call_alert_section'][$key] = $sortedArr[$key];
        	}
            if(in_array($key, $notificationAlertsCheck)){
                $reArrangedArr['notification_alert_section'][$key] = $sortedArr[$key];
            }
        }
        return $reArrangedArr;
    }
}
?>