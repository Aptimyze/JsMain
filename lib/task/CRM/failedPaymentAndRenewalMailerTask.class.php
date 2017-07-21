<?php

class failedPaymentAndRenewalMailerTask extends sfBaseTask
{
  protected function configure()
  {
     $this->addOptions(array(
       new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'The application name','operations'),
     ));

    $this->namespace        = 'csvGeneration';
    $this->name             = 'failedPaymentAndRenewalMailer';
    $this->briefDescription = 'this sends mails';
    $this->detailedDescription = <<<EOF
The [failedPaymentAndRenewalMailer|INFO] task does things.
Call it with:

  [php symfony csvGeneration:failedPaymentAndRenewalMailer|INFO]
EOF;
  }

  protected function execute($arguments = array(), $options = array())
  {
        sfContext::createInstance($this->configuration);
        	$incSRLObj 	=new incentive_PROCESS_REGULAR_LOG();
                $process        ='failedPaymentInDialer';
	        $latest_date 	=$incSRLObj->getLatestDate($process);
                $prev_date = date('Y-m-d',(strtotime ( '-1 day' , strtotime ( $latest_date) ) ));
               //for failed payment mailer, get previous day complete data
	        $data 		=$incSRLObj->getAllDataForGivenDate($prev_date, $process);
	        $to 		="rohan.mathur@jeevansathi.com,anamika.singh@jeevansathi.com,rajeev.joshi@jeevansathi.com,smarth.katyal@jeevansathi.com";
	        $from 		="JeevansathiCrm@jeevansathi.com";
	        $subject 	="Failed Payment in Dialer LOG for ".date("jS F Y", strtotime($prev_date));
	        $csvObj 	=new csvGenerationHandler();
                
                $overallCount = $this->formatData($data);
	       	$csvObj->sendEmailForFailedPaymentCSVLogging($overallCount, $to, $from, $subject);
                $process        ='renewalProcessInDialer';
                $latest_date 	=$incSRLObj->getLatestDate($process);
	        $data 		=$incSRLObj->getAllDataForGivenDate($latest_date, $process);
	        $to 		="rohan.mathur@jeevansathi.com,anamika.singh@jeevansathi.com,rajeev.joshi@jeevansathi.com,smarth.katyal@jeevansathi.com";
	        $from 		="JeevansathiCrm@jeevansathi.com";
	        $subject 	="Renewal Process in Dialer LOG for ".date("jS F Y", strtotime($latest_date));
	        $csvObj 	=new csvGenerationHandler();
	       	$csvObj->sendEmailForRenewalProcessCSVLogging($data, $to, $from, $subject);

  }
  public function formatData($data) {
/*
 * 2D Array Index Key::
 *               ||0(FILTERED_PROFILES)||1(COUNT)||2(FilterName) 
 * 0  TOTAL_PROFILES  
 * 1  DO_NOT_CALL
 * 2  ALLOCATED
 * 3  NEGATIVE_TREATMENT
 * 4  NOT_ACTIVATED
 * 5  INVALID_PHONE
 * 6  MALE_AGE
 * 7  NRI
 * 8  NON_OPTIN
 * 9  NO_PHONE_EXISTS
 * 10 NO_PHONE
 */

        $count = count($data);
        $overallCounter = array(array());
        for ($i = 0; $i < $count; $i++) {
            if ($data[$i]['FILTER'] == 'TOTAL_PROFILES') {
                $overallCounter[0][0] += $data[$i]['FILTERED_PROFILES'];
                $overallCounter[0][1] += $data[$i]['COUNT'];
                $overallCounter[0][2] = 'TOTAL_PROFILES';
            } else if ($data[$i]['FILTER'] == 'DO_NOT_CALL') {
                $overallCounter[1][0] += $data[$i]['FILTERED_PROFILES'];
                $overallCounter[1][1] += $data[$i]['COUNT'];
                $overallCounter[1][2] = 'DO_NOT_CALL';
            } else if ($data[$i]['FILTER'] == 'ALLOCATED') {
                $overallCounter[2][0] += $data[$i]['FILTERED_PROFILES'];
                $overallCounter[2][1] += $data[$i]['COUNT'];
                $overallCounter[2][2] = 'ALLOCATED';
            } else if ($data[$i]['FILTER'] == 'NEGATIVE_TREATMENT') {
                $overallCounter[3][0] += $data[$i]['FILTERED_PROFILES'];
                $overallCounter[3][1] += $data[$i]['COUNT'];
                $overallCounter[3][2] = 'NEGATIVE_TREATMENT';
            } else if ($data[$i]['FILTER'] == 'NOT_ACTIVATED') {
                $overallCounter[4][0] += $data[$i]['FILTERED_PROFILES'];
                $overallCounter[4][1] += $data[$i]['COUNT'];
                $overallCounter[4][2] = 'NOT_ACTIVATED';
            } else if ($data[$i]['FILTER'] == 'INVALID_PHONE') {
                $overallCounter[5][0] += $data[$i]['FILTERED_PROFILES'];
                $overallCounter[5][1] += $data[$i]['COUNT'];
                $overallCounter[5][2] = 'INVALID_PHONE';
            } else if ($data[$i]['FILTER'] == 'MALE_AGE') {
                $overallCounter[6][0] += $data[$i]['FILTERED_PROFILES'];
                $overallCounter[6][1] += $data[$i]['COUNT'];
                $overallCounter[6][2] = 'MALE_AGE';
            } else if ($data[$i]['FILTER'] == 'NRI') {
                $overallCounter[7][0] += $data[$i]['FILTERED_PROFILES'];
                $overallCounter[7][1] += $data[$i]['COUNT'];
                $overallCounter[7][2] = 'NRI';
            } else if ($data[$i]['FILTER'] == 'NON_OPTIN') {
                $overallCounter[8][0] += $data[$i]['FILTERED_PROFILES'];
                $overallCounter[8][1] += $data[$i]['COUNT'];
                $overallCounter[8][2] = 'NON_OPTIN';
            } else if ($data[$i]['FILTER'] == 'NO_PHONE_EXISTS') {
                $overallCounter[9][0] += $data[$i]['FILTERED_PROFILES'];
                $overallCounter[9][1] += $data[$i]['COUNT'];
                $overallCounter[9][2] = 'NO_PHONE_EXISTS';
            } else if ($data[$i]['FILTER'] == 'NO_PHONE') {
                $overallCounter[10][0] += $data[$i]['FILTERED_PROFILES'];
                $overallCounter[10][1] += $data[$i]['COUNT'];
                $overallCounter[10][2] = 'NO_PHONE';
            }
        }
        return $overallCounter;
    }

}
