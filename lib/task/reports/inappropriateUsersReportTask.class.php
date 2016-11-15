<?php

/**
 This task is used to generate the csv file for report abuse data of the previos date and email the same to the backend operators 
 *@author : Palash Chordia
 *created on : 13 July 2016 
 */
class inappropriateUsersReportTask extends sfBaseTask
{
  protected function configure()
  {


    $this->namespace        = 'report';
    $this->name             = 'inappropriateUsersReport';
    $this->briefDescription = 'regular report inappropriate behaviour of Users';
    $this->detailedDescription = <<<EOF
      The task filters out the users who have sent interests to people who are out of their DPP stored. Also sends CSV through a mail.
      Call it with:

      [php symfony report:inappropriateUsersReport] 
EOF;
    $this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
     ));
  }

  protected function execute($arguments = array(), $options = array())
  {
		ini_set('memory_limit','512M');
                ini_set('max_execution_time', 0);
		if(!sfContext::hasInstance())
	         sfContext::createInstance($this->configuration);
                $startDate=date('Y-m-d',strtotime("-14 day"));
                $masterDbObj=new MIS_INAPPROPRIATE_USERS_LOG();
                $masterDbObj->truncateTable($startDate);

                    $finalResultsArray = (new inappropriateUsers())->getDataForADate();
                    $data="Username,Outside Religion Contact,Outside Marital Status Contact,Outside Age Bracket Contact,Overall negative score\r\n";
                    foreach ($finalResultsArray as $key => $value) 
                      {
                      $data.="\r\n".$value['USERNAME'].','.$value['RCOUNT'].','.$value['MCOUNT'].','.$value['ACOUNT'].','.$value['TCOUNT'];
                      }
                      SendMail::send_email('anant.gupta@naukri.com,mithun.s@jeevansathi.com',"Please find the attached CSV file.","Inappropriate Users Summary for $todayDate","noreply@jeevansathi.com",'','',$data,'','inappropriateUsers_'.$todayDate.".csv");


  }

    
  
    
}