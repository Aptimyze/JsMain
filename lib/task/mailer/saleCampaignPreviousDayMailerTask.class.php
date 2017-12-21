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
        
       $date="2017-10-03 00:00:00";
       $prev_date = date('Y-m-d', strtotime($date .' -1 day'));
       $prev_date = $prev_date." 00:00:00";
       echo $prev_date;
       
       $incentiveSaleCampaignDetaiObj = new incentive_SALES_CAMPAIGN_PROFILE_DETAILS();
       $freeServiceCount=$incentiveSaleCampaignDetaiObj -> getCountSentFreeMailPreviousDate($date)['count'];
       $paidServiceCount=$incentiveSaleCampaignDetaiObj -> getCountSentPaidMailPreviousDate($date)['count'];
       
       echo $freeServiceCount;
       echo $paidServiceCount;
       die;
      // $to ="anant.gupta@naukri.com,anurag.tripathi@jeevansathi.com";
      $to = "rbnsingh19@gmail.com,ayush.chauhan@jeevansathi.com";
      $from = "JeevansathiCrm@jeevansathi.com";
      $subject = "CSAT survey mails sent for paid and free service < $date >";
      $message = "Free survey mails sent : $freeServiceCount <br> Paid survey mails sent : $paidServiceCount";

      var_dump(sendMail::send_email($to,$message,$subject,$from));
       
    }

}

