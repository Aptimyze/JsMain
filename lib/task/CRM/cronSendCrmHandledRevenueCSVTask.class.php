<?php
/* cron to send crm handled revenue mis csv sheet */

class cronSendCrmHandledRevenueCSVTask extends sfBaseTask
{
    /**
     *
     * Configuration details for CRM:cronSendCrmHandledRevenueCSV
     *
     * @access protected
     * @param none
     */
    protected function configure()
    {
        $this->addArguments(array(new sfCommandArgument('fortnight', sfCommandArgument::OPTIONAL, 'My argument')));
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name', 'operations')
        ));

        $this->namespace = 'CRM';
        $this->name = 'cronSendCrmHandledRevenueCSV';
        $this->briefDescription = 'send crm handled revenue mis csv sheet';
        $this->detailedDescription = <<<EOF
		The [cronSendCrmHandledRevenueCSV|INFO] task does things.
		Call it with:
		[php symfony CRM:cronSendCrmHandledRevenueCSV|INFO]
EOF;
    }

    /**
     *
     * Function for executing cron.
     *
     * @access protected
     * @param $arguments,$options
     */
    protected function execute($arguments = array(), $options = array())
    {
        if (!sfContext::hasInstance()) {
            sfContext::createInstance($this->configuration);
        }

        $fortnight = $arguments["fortnight"];

        /**
         * generate csv if valid fortnight passed
         */
        if ($fortnight == "H1" || $fortnight == "H2") {
            $fortnightValue = substr($fortnight, 1);
            $file_path = "/var/www/html/web/uploads/crmRevenue.csv";
            $fp = fopen($file_path, "w");

            if ($fortnight == "H1") {
                $month = date('M');
                $year = date('Y');
            } else {
                $month = date('M', strtotime("- 1 month"));

                if ($month == "Dec") {
                    $year = date('Y', strtotime("-1 year"));
                } else {
                    $year = date('Y');
                }
            }

            /**
             * send curl request to mis action to generate csv
             */
            // $jsadminConnectObj = new jsadmin_CONNECT('newjs_master');
            // $params = array('USER'=>'jstech','IPADDR'=>'127.0.0.1');
            // $last_inserted_id = $jsadminConnectObj->createLoginSessionForAgent($params);
            // $cid = Encrypt_Decrypt::encryptIDUsingMD5($last_inserted_id);
            $tuCurl = curl_init();
            $curlURL = "http://staging.jeevansathi.com/operations.php/crmMis/crmHandledRevenueCsvGenerate?fromMisCron=1&monthValue=" . $month . "&yearValue=" . $year . "&fortnightValue=" . $fortnightValue . "&report_type=TEAM&report_content=REVENUE&report_format=XLS&dialer_check=1&cid=eba0dc302bcd9a273f8bbb72be3a687bi484";
           // print_r($curlURL);
            curl_setopt($tuCurl, CURLOPT_URL, $curlURL);
            /**
             * curl_setopt($tuCurl, CURLOPT_RETURNTRANSFER, 1);
             */
            curl_setopt($tuCurl, CURLOPT_FILE, $fp);
            curl_setopt($tuCurl, CURLOPT_TIMEOUT, 120);
            curl_setopt($tuCurl, CURLOPT_CONNECTTIMEOUT, 0);
            $tuData = curl_exec($tuCurl);
            curl_close($tuCurl);
			/**
			 * send mail alert in case of problem in fetching mis data
			 */
            if ($tuData !== true) {
                CRMAlertManager::sendMailAlert("Issue in cronSendCrmHandledRevenueCSVTask while fetching mis data", "AgentNotifications");
            } else {
                /**
                 * send csv as mail
                 */
                $to = "shyam@naukri.com,jitesh.bhugra@naukri.com,rohan.mathur@jeevansathi.com,anamika.singh@jeevansathi.com,rajeev.joshi@jeevansathi.com,Amit.Malhotra@jeevansathi.com,anoop.singhal@naukri.com,pawan.tripathi@naukri.com";
                $cc = "vibhor.garg@jeevansathi.com,ankita.g@jeevansathi.com";
                // $to = "avneet.bindra@jeevansathi.com";
                $message = "Please find attached excel sheet containing requested data";
                $subject = "Crm Handled Revenue MIS Report";
                $csvAttachment = file_get_contents($file_path);
                /**
                 * print_r($csvAttachment);
                 */
                $fileName = "crmHandledRevenue_" . $month . "_" . $year . "_" . $fortnight . ".csv";
                SendMail::send_email($to, $message, $subject, 'js-sums@jeevansathi.com', $cc, '', $csvAttachment, "application/vnd.ms-excel", $fileName, '', '', '', '');
                /**
                 * SendMail::send_email($to,$message,$subject,"","","",$csvAttachment,"",$fileName);
                 */
                unset($csvAttachment);
            }
        } else {
            echo "Invalid fortnight value provided";
            die;
        }
    }
}
