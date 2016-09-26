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
        $scoreDBObj = new incentive_SCORE_UPDATE_LOG_NEW_MODEL("newjs_slave");
        foreach ($modelArr as $key => $value) {
            for($i=$start; $i<=$end; $i+=$increment){
                $data[$value]["NO_SCORE"] = 0;
                $data[$value][$i."-".($i+$increment)]=0;
                
            }
            $scoringData = $scoreDBObj->getScoreDistribution(date("Y-m-d 00:00:00"),date("Y-m-d 23:59:59"),$value);
            $totalCountWithoutNull = 0;
            foreach($scoringData as $index => $details){
                if($details['SCORE']){
                    $scoreDiv = $details['SCORE']/10;
                    $data[$value][($scoreDiv*10)."-".(($scoreDiv+1)*10)]++;
                    $totalCountWithoutNull++;
                }
                else{
                    $data[$value]["NO_SCORE"]++;
                }
            }
            //echo $totalCountWithoutNull."++++"."\n";
            $data[$value]['TOTAL COUNT'] = $totalCountWithoutNull;
        }
        unset($scoreDBObj);
        ////print_r($data);die;

        if($data && is_array($data)){
            //convert data into csv format
            $fileName = "ScoreDistribution_".date('d-M-Y').".csv";
            $file_path = JsConstants::$docRoot."/uploads/".$fileName;
            $fp = fopen($file_path, "w") or //print_r("Cannot Open");
            fputcsv($fp, array('MODEL','SCORE','PROFILE COUNT','PROFILES %'));

            ////print_r($data);die;
            foreach($data as $key=>$model) {
                foreach ($model as $range => $val) {
                    $csvData = array();
                    if($range != "TOTAL COUNT"){
                        $csvData['MODEL'] = $key;
                        $csvData['SCORE'] = $range;
                        $csvData['PROFILE COUNT'] = $val;
                        if($data[$key]['TOTAL COUNT'] == 0 || $val == 0){
                          $csvData['PROFILES %'] = 0;  
                        }
                        else{
                            $csvData['PROFILES %'] = ($val / $data[$key]['TOTAL COUNT']) * 100;
                        }
                    }
                  
                    //print_r($csvData);

                    fputcsv($fp, $csvData);
                } 
            }
            $csvAttachment = file_get_contents($file_path);
            ////print_r($csvAttachment);die;

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
            CRMAlertManager::sendMailAlert("Sql query to retrieve score distribution returned null,please check in cronSendAnalyseScoreDistributionReport","AgentNotifications");
        }
    }
}
?>