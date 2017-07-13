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
	        $latest_date 	=$incSRLObj->getLatestDate();
                $process        ='renewalProcessInDialer';
	        $data 		=$incSRLObj->getAllDataForGivenDate($latest_date, $process);
	        $to 		="rohan.mathur@jeevansathi.com,anamika.singh@jeevansathi.com,rajeev.joshi@jeevansathi.com,smarth.katyal@jeevansathi.com";
	        $from 		="JeevansathiCrm@jeevansathi.com";
	        $subject 	="Renewal Process in Dialer LOG for ".date("jS F Y", strtotime($latest_date));
	        $csvObj 	=new csvGenerationHandler();
                
                $overallCount = $this->formatData($data);
	       	$csvObj->sendEmailForFailedPaymentCSVLogging($overallCount, $to, $from, $subject);
                $process        ='failedPaymentInDialer';
	        $data 		=$incSRLObj->getAllDataForGivenDate($latest_date, $process);
	        $to 		="rohan.mathur@jeevansathi.com,anamika.singh@jeevansathi.com,rajeev.joshi@jeevansathi.com,smarth.katyal@jeevansathi.com";
	        $from 		="JeevansathiCrm@jeevansathi.com";
	        $subject 	="Failed Payment in Dialer LOG for ".date("jS F Y", strtotime($latest_date));
	        $csvObj 	=new csvGenerationHandler();
	       	$csvObj->sendEmailAlert($data, $to, $from, $subject);

  }
  public function formatData($data) {
/*
 * Array Index Key::
 *               ||0(FILTERED_PROFILES)||1(COUNT)||2(LATEST_REG_FILTERED_PROFILES)||3(LATEST_REG_COUNT)||4(FilterName)||5(LATEST_REG_DT) 
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
                $overallCounter[0][2] += $data[$i]['LATEST_REG_FILTERED_PROFILES'];
                $overallCounter[0][3] += $data[$i]['LATEST_REG_COUNT'];
                $overallCounter[0][4] = 'TOTAL_PROFILES';
                $overallCounter[0][5] = $data[$i]['LATEST_REG_DT'];
            } else if ($data[$i]['FILTER'] == 'DO_NOT_CALL') {
                $overallCounter[1][0] += $data[$i]['FILTERED_PROFILES'];
                $overallCounter[1][1] += $data[$i]['COUNT'];
                $overallCounter[1][2] += $data[$i]['LATEST_REG_FILTERED_PROFILES'];
                $overallCounter[1][3] += $data[$i]['LATEST_REG_COUNT'];
                $overallCounter[1][4] = 'DO_NOT_CALL';
                $overallCounter[1][5] = $data[$i]['LATEST_REG_DT'];
            } else if ($data[$i]['FILTER'] == 'ALLOCATED') {
                $overallCounter[2][0] += $data[$i]['FILTERED_PROFILES'];
                $overallCounter[2][1] += $data[$i]['COUNT'];
                $overallCounter[2][2] += $data[$i]['LATEST_REG_FILTERED_PROFILES'];
                $overallCounter[2][3] += $data[$i]['LATEST_REG_COUNT'];
                $overallCounter[2][4] = 'ALLOCATED';
                $overallCounter[2][5] = $data[$i]['LATEST_REG_DT'];
            } else if ($data[$i]['FILTER'] == 'NEGATIVE_TREATMENT') {
                $overallCounter[3][0] += $data[$i]['FILTERED_PROFILES'];
                $overallCounter[3][1] += $data[$i]['COUNT'];
                $overallCounter[3][2] += $data[$i]['LATEST_REG_FILTERED_PROFILES'];
                $overallCounter[3][3] += $data[$i]['LATEST_REG_COUNT'];
                $overallCounter[3][4] = 'NEGATIVE_TREATMENT';
                $overallCounter[3][5] = $data[$i]['LATEST_REG_DT'];
            } else if ($data[$i]['FILTER'] == 'NOT_ACTIVATED') {
                $overallCounter[4][0] += $data[$i]['FILTERED_PROFILES'];
                $overallCounter[4][1] += $data[$i]['COUNT'];
                $overallCounter[4][2] += $data[$i]['LATEST_REG_FILTERED_PROFILES'];
                $overallCounter[4][3] += $data[$i]['LATEST_REG_COUNT'];
                $overallCounter[4][4] = 'NOT_ACTIVATED';
                $overallCounter[4][5] = $data[$i]['LATEST_REG_DT'];
            } else if ($data[$i]['FILTER'] == 'INVALID_PHONE') {
                $overallCounter[5][0] += $data[$i]['FILTERED_PROFILES'];
                $overallCounter[5][1] += $data[$i]['COUNT'];
                $overallCounter[5][2] += $data[$i]['LATEST_REG_FILTERED_PROFILES'];
                $overallCounter[5][3] += $data[$i]['LATEST_REG_COUNT'];
                $overallCounter[5][4] = 'INVALID_PHONE';
                $overallCounter[5][5] = $data[$i]['LATEST_REG_DT'];
            } else if ($data[$i]['FILTER'] == 'MALE_AGE') {
                $overallCounter[6][0] += $data[$i]['FILTERED_PROFILES'];
                $overallCounter[6][1] += $data[$i]['COUNT'];
                $overallCounter[6][2] += $data[$i]['LATEST_REG_FILTERED_PROFILES'];
                $overallCounter[6][3] += $data[$i]['LATEST_REG_COUNT'];
                $overallCounter[6][4] = 'MALE_AGE';
                $overallCounter[6][5] = $data[$i]['LATEST_REG_DT'];
            } else if ($data[$i]['FILTER'] == 'NRI') {
                $overallCounter[7][0] += $data[$i]['FILTERED_PROFILES'];
                $overallCounter[7][1] += $data[$i]['COUNT'];
                $overallCounter[7][2] += $data[$i]['LATEST_REG_FILTERED_PROFILES'];
                $overallCounter[7][3] += $data[$i]['LATEST_REG_COUNT'];
                $overallCounter[7][4] = 'NRI';
                $overallCounter[7][5] = $data[$i]['LATEST_REG_DT'];
            } else if ($data[$i]['FILTER'] == 'NON_OPTIN') {
                $overallCounter[8][0] += $data[$i]['FILTERED_PROFILES'];
                $overallCounter[8][1] += $data[$i]['COUNT'];
                $overallCounter[8][2] += $data[$i]['LATEST_REG_FILTERED_PROFILES'];
                $overallCounter[8][3] += $data[$i]['LATEST_REG_COUNT'];
                $overallCounter[8][4] = 'NON_OPTIN';
                $overallCounter[8][5] = $data[$i]['LATEST_REG_DT'];
            } else if ($data[$i]['FILTER'] == 'NO_PHONE_EXISTS') {
                $overallCounter[9][0] += $data[$i]['FILTERED_PROFILES'];
                $overallCounter[9][1] += $data[$i]['COUNT'];
                $overallCounter[9][2] += $data[$i]['LATEST_REG_FILTERED_PROFILES'];
                $overallCounter[9][3] += $data[$i]['LATEST_REG_COUNT'];
                $overallCounter[9][4] = 'NO_PHONE_EXISTS';
                $overallCounter[9][5] = $data[$i]['LATEST_REG_DT'];
            } else if ($data[$i]['FILTER'] == 'NO_PHONE') {
                $overallCounter[10][0] += $data[$i]['FILTERED_PROFILES'];
                $overallCounter[10][1] += $data[$i]['COUNT'];
                $overallCounter[10][2] += $data[$i]['LATEST_REG_FILTERED_PROFILES'];
                $overallCounter[10][3] += $data[$i]['LATEST_REG_COUNT'];
                $overallCounter[10][4] = 'NO_PHONE';
                $overallCounter[10][5] = $data[$i]['LATEST_REG_DT'];
            }
        }
        return $overallCounter;
    }

}
