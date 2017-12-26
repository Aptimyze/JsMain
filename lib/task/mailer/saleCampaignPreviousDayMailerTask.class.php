<?php

class saleCampaignPreviousDayMailerTask extends sfBaseTask
{

    protected function configure()
    {
        $this->addOptions(array(
            new sfCommandOption('application', null, sfCommandOption::PARAMETER_REQUIRED, 'Application Name','jeevansathi'),
        ));
        
        $this->namespace        = 'mailer';
        $this->name             = 'saleCampaignPreviousDayMailer';
        $this->briefDescription = 'Mailer task to send count of paid and free mail sent on previous days survey';
        $this->detailedDescription = <<<EOF
		The [saleCampaignPreviousDayMailer|INFO] task does things.
		Call it with:
		[php symfony mailer:saleCampaignPreviousDayMailer|INFO]
EOF;
    }
        
    protected function execute($arguments = array(), $options = array())
    {
        // SET BASIC CONFIGURATION
        if(!sfContext::hasInstance()){
            sfContext::createInstance($this->configuration);
        }
        
      $date = date('Y-m-d'); 
      $prev_date = date('Y-m-d', strtotime($date.' -1 day'));
      $start_date = $prev_date." 00:00:00";
      $end_date = $prev_date." 23:59:59";
      
      $incentiveSaleCampaignDetaiObj = new incentive_SALES_CAMPAIGN_PROFILE_DETAILS("crm_slave");
      $serviceCount = $incentiveSaleCampaignDetaiObj -> getCountSentMailPreviousDate($prev_date,$end_date);
       
      $freeServiceCount = $serviceCount['IB_Service'];
      $paidServiceCount = $serviceCount['IB_PaidService'];
      $to ="anant.gupta@naukri.com,anurag.tripathi@jeevansathi.com";
      $from = "JeevansathiCrm@jeevansathi.com";
      $subject = "CSAT survey mails sent for paid and free service < $prev_date >";
      $message = "Free survey mails sent : $freeServiceCount <br> Paid survey mails sent : $paidServiceCount";

      sendMail::send_email($to,$message,$subject,$from);
       
    }

}

