<?php
/* cron to send analysis score distribution report*/

class cronSendAnalyseScoreDistributionReportTask extends sfBaseTask
{
    /**
     *
     * Configuration details for CRM:cronSendAnalyseScoreDistributionReport
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
        $this->name = 'cronSendAnalyseScoreDistributionReport';
        $this->briefDescription = 'send analysis score distribution report';
        $this->detailedDescription = <<<EOF
		The [cronSendAnalyseScoreDistributionReport|INFO] task does things.
		Call it with:
		[php symfony CRM:cronSendAnalyseScoreDistributionReport|INFO]
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
        //ini_set('memory_limit', '-1');
        if (!sfContext::hasInstance()) {
            sfContext::createInstance($this->configuration);
        }
        $modelArr = array("E","N");
        //get data of score distribution
        $start = 0; $end = 100; $increment=10;
        $scoreDBObj = new test_ANALYTICS_SCORE_POOL("newjs_slave");
        foreach ($modelArr as $key => $value) {
            for($i=$start; $i<=$end; $i+=$increment){
                $data[$value][$i."-".($i+$increment)]=0;
            }
            $scoringData = $scoreDBObj->getScoreDistribution($value);
            foreach($scoringData as $index => $details){
                if($details['SCORE']){
                    $scoreDiv = $details['SCORE']/10;
                    $data[$value][($scoreDiv*10)."-".(($scoreDiv+1)*10)]++;
                }
                else{
                    $data[$value]["NO_SCORE"]++;
                }
            }
        }
        unset($scoreDBObj);
        print_r($data);

        if($data && is_array($data)){
            //convert data into csv format
            $fileName = "ScoreDistribution_".date('d-M-Y').".csv";
            $file_path = JsConstants::$docRoot."/uploads/".$fileName;
            $fp = fopen($file_path, "w") or print_r("Cannot Open");
            fputcsv($fp, array('MODEL','SCORE','PROFILE COUNT','PROFILES %'));
            $profileCountWithoutNullScore = array();
            foreach($data as $key=>$model) {
                $profileCountWithoutNullScore[$model] = 0;
                foreach ($model as $key => $val) {
                    $profileCountWithoutNullScore[$model] += $val['PROFILE COUNT'];
                } 
            }
            if(is_array($profileCountWithoutNullScore)){
                foreach($data as $key=>$model) {
                    foreach ($model as $key => $val) {
                        $val['PROFILES %'] = ($val['PROFILE COUNT'] / $profileCountWithoutNullScore[$model]) * 100;
                        fputcsv($fp, $val);
                    } 
                }
                $csvAttachment = file_get_contents($file_path);
                print_r($csvAttachment);die;

                //send csv as mail
                //$to = "rohan.mathur@jeevansathi.com";
                $cc = "vibhor.garg@jeevansathi.com,ankita.g@jeevansathi.com";
                $to = "nsitankita@gmail.com";
                $message = "Please find attached excel sheet containing requested data";
                $subject = "Analysis score distribution report";
                SendMail::send_email($to, $message, $subject, 'js-sums@jeevansathi.com', $cc, '', $csvAttachment, "application/vnd.ms-excel", $fileName, '', '', '', '');
                unset($csvAttachment);
            }
            else{
                CRMAlertManager::sendMailAlert("Total profile count for score distribution calculated is 0,please check in cronSendAnalyseScoreDistributionReport","AgentNotifications");
            }
        }
        else{
            CRMAlertManager::sendMailAlert("Sql query to retrieve score distribution returned null,please check in cronSendAnalyseScoreDistributionReport","AgentNotifications");
        }
    }
}
?>