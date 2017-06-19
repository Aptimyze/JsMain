<?php

/* This task is used to track new MatchAlertLogTempTracking
 * @author : Akash Kumar
 * created on : 27 Feb 2015 
 */

class MatchAlertLogTempTrackingTask extends sfBaseTask {

        protected function configure() {
                $this->namespace = 'mailer';
                $this->name = 'MatchAlertLogTempTracking';
                $this->briefDescription = 'new matchalert mailer Tracking';
                $this->detailedDescription = <<<EOF
      The task send new matchalert mailer .
      Call it with:

      [php symfony mailer:MatchAlertLogTempTracking] 
EOF;
        }

        protected function execute($arguments = array(), $options = array()) {
                $newMatchAlertTrackingObject = new matchalerts_LOG_TEMP();
                $count = $newMatchAlertTrackingObject->getMatchAlertCount();
                $datesRows = $newMatchAlertTrackingObject->getDatesRowsForTracking();
                $smsSend = 0;
                if (count($datesRows) > 1) {
                        $this->sendNotification("Match Alert cron took 24 plus hours", "Time reached ");
                        $smsSend = 1;
                }
                if (date("H") >= "2" && $count == 0 && $smsSend == 0) {
                        $this->sendNotification("ALERT-New Match Alert Task failed<br>Present Count=>0", "Count Zero");
                        $smsSend = 1;
                }
                if ($count != 0 && $smsSend == 0) {
                        $smsSend = $this->CompareVal("LOGTEMPCNT", $count, 700000);
                        if ($smsSend == 1) {
                                $this->sendNotification("SAME COUNT LOG_TEMP", "cron halted");
                        }
                }
                if ($count != 0 && $smsSend == 0) {
                        $matchalertSentObject = new matchalerts_MATCHALERTS_TO_BE_SENT();
                        $mailerText = 0;
                        $mailerTextArr = array();
                        for ($i = 0; $i < 9; $i++) {
                                $count = $matchalertSentObject->getTotalCountWithScript(9, $i);
                                if ($count != 0 && $count != '') {
                                        $smsSend = $this->CompareVal("MA_SENT_CNT" . $i, $count);
                                        if ($smsSend == 1) {
                                                $mailerText = 1;
                                                $mailerTextArr[] = $i;
                                        }
                                }
                        }
                        unset($matchalertSentObject);
                        if ($mailerText == 1) {
                                exec("ps ax | grep php", $output);
                                $outputStr = implode("<br/><br/>", $output);
                                $str = implode(" ", $mailerTextArr);
                                $this->sendNotification("SAME COUNT LOG_TEMP for 9 :: $str <br/><br/>" . $outputStr, "cron halted at 9 :: $str");
                        }
                }
                $matchalertSentObject = new matchalerts_MATCHALERTS_TO_BE_SENT();
                $count = $matchalertSentObject->getTotalCountWithScript(1, 0);
                if ($count != 0 && $count != "") {
                        $matchalertMailertObject = new matchalerts_MAILER();
                        $MailersCount = $matchalertMailertObject->getMailerProfiles("COUNT(*) as CNT");
                        $smsSend = $this->CompareVal("MA_MAILER_CNT", $MailersCount[0]["CNT"]);
                        if ($smsSend == 1) {
                                $this->sendNotification("Match Alert Mailer Not working", "Match Alert Mailer Not working");
                        }
                }
        }

        function CompareVal($key, $count, $thresholdVal = 0) {
                $memObj = JsMemcache::getInstance();
                $cntval = $memObj->get($key);
                $cnt = 0;
                $send = 0;
                if ($cntval === false) {
                        $cnt = $count;
                } else {
                        $cnt = $cntval;
                }
                $memObj->set($key, $count, 3600);
                if ($cntval !== false && $count == $cnt && ($thresholdVal == 0 || $count < $thresholdVal)) {
                        $send = 1;
                }
                unset($memObj);
                return $send;
        }

        function sendNotification($mailContent, $subject) {
                SendMail::send_email("lavesh.rawat@gmail.com,reshu.rajput@gmail.com,bhavana.kadwal@gmail.com,eshajain88@gmail.com", $mailContent, "ALERT:: Match Alert " . $subject . " " . date('y-m-d h:i:s'));
                $this->sendSMS();
        }

        private function sendSMS() {
                $date = date("Y-m-d h");
                $from = "JSSRVR";
                $profileid = "144111";
                $smsMessage = "Mysql Error Count have reached logTempMatchalert $date within 5 minutes";
                $mobileArr = array("9818424749", "9873639543", "9650350387", "9953457479");
                foreach ($mobileArr as $mobPhone) {
                        $xml_head = "%3C?xml%20version=%221.0%22%20encoding=%22ISO-8859-1%22?%3E%3C!DOCTYPE%20MESSAGE%20SYSTEM%20%22http://127.0.0.1/psms/dtd/message.dtd%22%3E%3CMESSAGE%3E%3CUSER%20USERNAME=%22naukari%22%20PASSWORD=%22na21s8api%22/%3E";
                        $xml_content = "%3CSMS%20UDH=%220%22%20CODING=%221%22%20TEXT=%22" . urlencode($smsMessage) . "%22%20PROPERTY=%220%22%20ID=%22" . $profileid . "%22%3E%3CADDRESS%20FROM=%22" . $from . "%22%20TO=%22" . $mobPhone . "e%22%20SEQ=%22" . $profileid . "%22%20TAG=%22%22/%3E%3C/SMS%3E";
                        $xml_end = "%3C/MESSAGE%3E";
                        $xml_code = $xml_head . $xml_content . $xml_end;
                        $fd = @fopen("http://api.myvaluefirst.com/psms/servlet/psms.Eservice2?data=$xml_code&action=send", "rb");
                        if ($fd) {
                                $response = '';
                                while (!feof($fd)) {
                                        $response.= fread($fd, 4096);
                                }
                                fclose($fd);
                        }
                }
        }

}
