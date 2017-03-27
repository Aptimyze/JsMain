<?php

/**
 This task is used to generate the csv file for report abuse data of the previos date and email the same to the backend operators 
 *@author : Palash Chordia
 *created on : 13 July 2016 
 */
class reportAbuseCsvMailerTask extends sfBaseTask
{
  
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

    $this->executeCSVforReportInvalid();
    $this->executeCSVforReportAbuse();

           
  }

private function executeCSVforReportAbuse()
  {

   //This is the function which is executed when csv for report abuse is required.
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
  
    SendMail::send_email('anant.gupta@naukri.com,mithun.s@jeevansathi.com',"Please find the attached CSV file.","Report Abuse Summary for $yesterdayDate","noreply@jeevansathi.com",'','',$data,'','reportAbuse_'.$yesterdayDate.".csv");
 }
private function executeCSVforReportInvalid()
  {
    //This is the function which is executed when csv for Mark Invalid is required.
    $yesterdayDate=date('Y-m-d',strtotime("-1 day"));
    $reportArray=(new JSADMIN_REPORT_INVALID_PHONE())->getReportInvalidLog($yesterdayDate,$yesterdayDate);
    $data="SUBMITEE,SUBMITER,COMMENTS,DATE,CONTACT_NUMBER_MARKED,COUNT_IN_LAST_90_DAYS\r\n";

     $startDate=$yesterdayDate;
     $endDate=$yesterdayDate;
      $reportArray= (new feedbackReports())->getReportInvalidLog($startDate,$endDate);
     

      foreach ($reportArray as $key => $value) 
      { 
      $data.="\r\n".$value['submitee_id'].",".$value['submiter_id'].','.$value['comments'].','.$value['timestamp'].','.$value['phone_number'].','.$value['count'];
      # code...
      }
    SendMail::send_email('anant.gupta@naukri.com,mithun.s@jeevansathi.com',"Please find the attached CSV file.","Report Invalid Summary for $yesterdayDate","noreply@jeevansathi.com",'','',$data,'','MarkPhoneInvalid_'.$yesterdayDate.".csv");
          
  }
 




}
