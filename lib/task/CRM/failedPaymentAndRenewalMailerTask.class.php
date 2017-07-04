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
        	$incSRLObj 	=new incentive_FP_REGULAR_LOG();
	        $latest_date 	=$incSRLObj->getLatestDate();
                $process        ='renewalProcessInDialer';
	        $data 		=$incSRLObj->getAllDataForGivenDate($latest_date, $process);
	        $to 		="rohan.mathur@jeevansathi.com,anamika.singh@jeevansathi.com,rajeev.joshi@jeevansathi.com";
	        $from 		="JeevansathiCrm@jeevansathi.com";
	        $subject 	="Renewal Process in Dialer LOG for ".date("jS F Y", strtotime($latest_date));
	        $csvObj 	=new csvGenerationHandler();
	       	$csvObj->sendEmailAlert($data, $to, $from, $subject);
                
                $process        ='failedPaymentInDialer';
	        $data 		=$incSRLObj->getAllDataForGivenDate($latest_date, $process);
	        $to 		="rohan.mathur@jeevansathi.com,anamika.singh@jeevansathi.com,rajeev.joshi@jeevansathi.com";
	        $from 		="JeevansathiCrm@jeevansathi.com";
	        $subject 	="Failed Payment in Dialer LOG for ".date("jS F Y", strtotime($latest_date));
	        $csvObj 	=new csvGenerationHandler();
	       	$csvObj->sendEmailAlert($data, $to, $from, $subject);

  }
}
