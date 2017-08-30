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
        ini_set('memory_limit', '-1');
        if (!sfContext::hasInstance()) {
            sfContext::createInstance($this->configuration);
        }
        $useScoreLogTable = false;
        $modelMapping = array("E"=>"EVER_PAID","R"=>"RENEWAL","N"=>"NEVER_PAID");
        if($useScoreLogTable == true){
            $modelArr = array("EVER_PAID","NEVER_PAID","RENEWAL");
            $scoreDBObj = new incentive_SCORE_UPDATE_LOG_NEW_MODEL("newjs_slave");
            $startDt = date("Y-m-d 00:00:00",(time()-86400));
            $endDt = date("Y-m-d 23:59:59",(time()-86400));
        }
        else{
            $modelArr = array("E","N","R");
            $scoreDBObj = new test_ANALYTICS_SCORE_POOL("newjs_local111");
            $startDt = "";
            $endDt = "";
        }

        //get data of score distribution
        $start = 0; $end = 100; $increment=10;
        foreach ($modelArr as $key => $value) {
            for($i=$start; $i<$end; $i+=$increment){
                $data[$value]["NO_SCORE"] = 0;
                if($i!=$start)
                  $data[$value][($i+1)."-".($i+$increment)]=0;
                else  
                $data[$value][$i."-".($i+$increment)]=0;
                
            }
            $scoringData = $scoreDBObj->getScoreDistribution($value,$startDt,$endDt);
            $totalCountWithoutNull = 0;
            foreach($scoringData as $index => $details){
                if(is_null($details['SCORE'])==false && $details['SCORE'] >= $start){
                    $scoreDiv = floor($details['SCORE']/$increment);
                    if($details['SCORE']>=$start && $details['SCORE']<=$increment){
                        $data[$value][$start."-".$increment]++;
                    }
                    else if($details['SCORE'] % $increment == 0){
                        $data[$value][((($scoreDiv-1)*$increment)+1)."-".($scoreDiv*$increment)]++;
                    }
                    else{
                        $data[$value][(($scoreDiv*$increment)+1)."-".(($scoreDiv+1)*$increment)]++; 
                    }
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
            $fileName = "ScoreDistribution.csv";
            $file_path = JsConstants::$docRoot."/uploads/".$fileName;
            $fp = fopen($file_path, "w") or print_r("Cannot Open");
            //fputcsv($fp, array('MODEL','SCORE','PROFILE COUNT','PROFILES %'));

            //print_r($data);die;
            foreach($data as $key=>$model) {
                if(($key == "EVER_PAID"|| $key == "E")){
                    fputcsv($fp, array('SCORE','PROFILE COUNT'));
                }
                if($useScoreLogTable == false){
                    $modelName = $modelMapping[$key];
                }
                else{
                    $modelName = $key;
                }
                fputcsv($fp, array('-----',$modelName,'-------'));
                foreach ($model as $range => $val) {
                    $csvData = array();
                    if($range != "TOTAL COUNT"){
                        //$csvData['MODEL'] = $key;
                        $csvData['SCORE'] = $range;
                        $csvData['PROFILE COUNT'] = $val;
                        /*if($data[$key]['TOTAL COUNT'] == 0 || $val == 0){
                          $csvData['PROFILES %'] = "0 %";  
                        }
                        else{
                            $csvData['PROFILES %'] = round((($val / $data[$key]['TOTAL COUNT']) * 100),2)." %";
                        }*/
                    }
                  
                    //print_r($csvData);

                    
                   fputcsv($fp, $csvData);
                } 
            }
            $csvAttachment = file_get_contents($file_path);
            //print_r($csvAttachment);die;

            //send csv as mail
            $to = "rohan.mathur@jeevansathi.com";
            $cc = "vibhor.garg@jeevansathi.com,ankita.g@jeevansathi.com,manoj.rana@naukri.com";
            
            $message = "Please find attached excel sheet containing requested data";
            $subject = "Analysis score distribution report";
            SendMail::send_email($to, $message, $subject, 'js-sums@jeevansathi.com', $cc, '', $csvAttachment, "application/vnd.ms-excel", "ScoreDistribution_".date('d-M-Y').".csv", '', '', '', '');
            unset($csvAttachment);
        
        }
        else{
            CRMAlertManager::sendMailAlert("Sql query to retrieve score distribution returned null,please check in cronSendAnalyseScoreDistributionReport","AgentNotifications");
        }
    }
}
?>
