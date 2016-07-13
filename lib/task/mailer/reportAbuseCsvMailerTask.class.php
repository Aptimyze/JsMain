<?php

/**
 This task is used to generate the csv file for report abuse data of the previos date and email the same to the backend operators 
 *@author : Palash Chordia
 *created on : 13 July 2016 
 */
class reportAbuseCsvMailerTask extends sfBaseTask
{
    private $smarty;
    private $mailerName = "MATCHALERT";
    private $limit = 1000;
  
  protected function configure()
  {
    $this->namespace        = 'mailer';
    $this->name             = 'reportAbuseCsvMailer';
    $this->briefDescription = 'regular report abuse csv mailer';
    $this->detailedDescription = <<<EOF
      The task sends the csv of report abuse as mailer.
      Call it with:

      [php symfony mailer:reportAbuseCsvMailer] 
EOF;
    $this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
     ));
  }

  protected function execute($arguments = array(), $options = array())
  {
    $yesterdayDate=date('Y-m-d',strtotime("-1 day"));
    $reportArray=(new REPORT_ABUSE_LOG)->getReportAbuseLog($yesterdayDate,$yesterdayDate);
    $data="REPORTEE,REPORTER,REPORTEE_EMAIL,REPORTER_EMAIL,REASON,OTHER_REASON,DATE\r\n";
   foreach ($reportArray as $key => $value) 
      {
         $profileArray[]=$value['REPORTEE'];
         $profileArray[]=$value['REPORTER'];
      # code...
      }

     if(is_array($profileArray))
    {
      $profileDetails=(new JPROFILE())->getProfileSelectedDetails($profileArray,"PROFILEID,EMAIL,USERNAME");
      foreach ($reportArray as $key => $value) 
      {

      $data.="\r\n".$profileDetails[$value['REPORTEE']]['USERNAME'].",".$profileDetails[$value['REPORTER']]['USERNAME'].','.$profileDetails[$value['REPORTEE']]['EMAIL'].','.$profileDetails[$value['REPORTER']]['EMAIL'].','.$value['REASON'].','.str_replace('"','""',$value['OTHER_REASON']).','.$value['DATE'];
        
      
      }
    }

  SendMail::send_email('anant.gupta@naukri.com,mithun.s@jeevansathi.com',"Please find the attached CSV file.","Report Abuse Summary for $yesterdayDate","noreply@jeevansathi.com",'','',$data,'reportAbuse_'.$yesterdayDate);
  
//    SendMail::send_email('palash.chordia@jeevansathi.com',"Please find the attached CSV file.","Report Abuse Summary for $yesterdayDate","noreply@jeevansathi.com",'','',$data,'','reportAbuse_'.$yesterdayDate.".csv");
           
  }
 

}
