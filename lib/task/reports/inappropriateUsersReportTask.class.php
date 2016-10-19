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

      $this->addArguments(array(
    new sfCommandArgument('chunks', sfCommandArgument::REQUIRED, 'My argument'),
));

    $this->namespace        = 'mailer';
    $this->name             = 'inappropriateUsersReport';
    $this->briefDescription = 'regular report inappropriate behaviour of Users';
    $this->detailedDescription = <<<EOF
      The task filters out the users who have sent interests to people who are out of their DPP stored. Also sends CSV through a mail.
      Call it with:

      [php symfony mailer:inappropriateUsersReport] 
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

                    $chunk=$arguments["chunks"];
	            if(!$chunk)$chunk=1;
	            $resultArray=array();
	            $logTable=new MIS_INAPPROPRIATE_USERS_LOG('newjs_slave');
                    $todayDate=date('Y-m-d',strtotime("-0 day"));
                    $resultArr=$logTable->getDataForADate($todayDate);
                    $i=0;
                    foreach ($resultArr as $key => $value) 
                    {
                        $resultArr2[$i]['USERNAME']=$key;
                        foreach ($value as $key2=>$value2)
                        {

                        $resultArr2[$i]['R'] += ($value2['RELIGION_COUNT']);
                        $resultArr2[$i]['A'] += ($value2['AGE_COUNT']);
                        $resultArr2[$i]['M'] += ($value2['MSTATUS_COUNT']);
                        $resultArr2[$i]['T'] += ($value2['TOTAL_SCORE']);

                        }
                        unset($resultArr[$key]);
                    }

                    foreach ($resultArr2 as $key => $value) {
                        $Tarray[]=$value['T'];
                    }
                    array_multisort($Tarray, SORT_DESC, SORT_NUMERIC, $resultArr2);
                    $data="Username,Outside Religion Contact,Outside Marital Status Contact,Outside Age Bracket Contact,Overall negative score\r\n";

                    if(is_array($resultArr2))
                    {
                      foreach ($resultArr2 as $key => $value) 
                      {
                      $data.="\r\n".$value['USERNAME'].','.$value['R'].','.$value['M'].','.$value['A'].','.$value['T'];
                      }
                    }
                      SendMail::send_email('anant.gupta@naukri.com,mithun.s@jeevansathi.com',"Please find the attached CSV file.","Inappropriate Users Summary for $todayDate","noreply@jeevansathi.com",'','',$data,'','inappropriateUsers_'.$todayDate.".csv");


  }

}