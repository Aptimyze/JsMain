<?php

/* This task is used to track new Visitor Alert Monitoring
 * @author : Ankit Shukla
 * created on : 11 Dec 2017
 */

class VisitorAlertMonitoringTask extends sfBaseTask {

        protected function configure() {
            $this->addOptions(array(
                new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
            ));
                $this->namespace = 'monitoring';
                $this->name = 'VisitorAlertMonitoringTask';
                $this->briefDescription = 'Visitor Alert Tracking';
                $this->detailedDescription = <<<EOF
      The task monitors Visitor Alert Tracking .
      Call it with:

      [php symfony monitoring:VisitorAlertMonitoring] 
EOF;
        }

        protected function execute($arguments = array(), $options = array()) {
            
                $thresholdVal = 100000;
                if(!sfContext::hasInstance())
                    sfContext::createInstance ($this->configuration);
                $visitoralertMailerVisitors = new visitorAlert_MAILER('shard1_master');
                $mailerTableInfo = $visitoralertMailerVisitors->getMailerTableInfo();
                
                $smsSend = 0;
                if($mailerTableInfo['N'] == 0){
                    
                    if($mailerTableInfo['U'] == 0){
                        
                        $memObj = JsMemcache::getInstance();
                        $populateKey = "VA_POPULATE_CHECK";
                        $cntval = $memObj->get($populateKey);
                        
                        if($cntval === false){
                            $visitorAlertRecord = new visitorAlert_RECORD('shard1_master');
                            $prevDate = date('Y-m-d',  strtotime('-1 day'));
                            $prevDateRecord = $visitorAlertRecord->getVisitorAlertRecord($prevDate);
                            
                            if(!$prevDateRecord){
                                $this->sendNotification("Visitor Alert Populate failed", "Visitor Alert Populate failed");
                                $smsSend = 1;
                            }
                            else
                                $memObj->set($populateKey, $prevDateRecord, 86000);
                            
                        }
                        elseif(($mailerTableInfo['Y']+$mailerTableInfo['B']+$mailerTableInfo['F']+$mailerTableInfo['I']) < $thresholdVal){
                            $this->sendNotification("Visitor Alert Cron failed", "Visitor Alert Cron failed");
                        }
                    }
                }
                else{
                    $smsSend = $this->CompareVal("VA_CALC_CNT", $mailerTableInfo['N']);
                    if($smsSend == 1 && $mailerTableInfo['U']!=0)
                        $this->sendNotification("Visitor Alert Calculate failed", "Visitor Alert Calculate failed");
                        
                    $smsSend = $this->CompareVal("VA_NOT_SENT_CNT", $mailerTableInfo['N']);
                    if ($smsSend == 1) {
                            $this->sendNotification("Visitor Alert Mailer failed", "Visitor Alert Mailer failed");
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
                $memObj->set($key, $count, 3700);
                if ($cntval !== false && $count == $cnt && ($thresholdVal == 0 || $count < $thresholdVal)) {
                        $send = 1;
                }
                unset($memObj);
                return $send;
        }

        function sendNotification($mailContent, $subject) {
                SendMail::send_email(/*"lavesh.rawat@gmail.com,reshu.rajput@gmail.com,*/"ankitshukla125@gmail.com"/*,eshajain88@gmail.com"*/, $mailContent, "ALERT:: Visitor Alert " . $subject . " " . date('y-m-d h:i:s'));
                $this->sendSMS();
        }

        private function sendSMS() {
                $date = date("Y-m-d h");
                $from = "JSSRVR";
                $profileid = "144111";
                $smsMessage = "Mysql Error Count have reached visitorAlertCron $date within 5 minutes";
                $mobileArr = array(/*"9818424749", "9873639543",*/ "9711818214"/*, "9953457479"*/);
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
                                CommonUtility::logTechAlertSms($smsMessage, $mobPhone);
                        }
                }
        }

}
