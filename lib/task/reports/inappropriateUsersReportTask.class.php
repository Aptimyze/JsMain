<?php

/**
 This task is used to generate the csv file for report abuse data of the previos date and email the same to the backend operators 
 *@author : Palash Chordia
 *created on : 13 July 2016 
 */
class inappropriateUsersReportTask extends sfBaseTask
{
  public static $cronLIVEDate='2016-11-21';
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
                $startDate=date('Y-m-d',strtotime("-7 day"));
                $todayDate=date('Y-m-d');
                $masterDbObj=new MIS_INAPPROPRIATE_USERS_LOG();
                $masterDbObj->truncateTable($startDate);
                $masterDbReportObj=new MIS_INAPPROPRIATE_USERS_REPORT();
                $masterDbReportObj->truncateTable($startDate);
                $this->getDataForToday();
                $data="Username,Outside Religion Contact,Outside Marital Status Contact,Outside Age Bracket Contact,Overall negative score,Report Abuse Count,Report Invalid Count";
              
                foreach ($this->finalResultsArray as $key => $value) 
                { 
                $data.="\r\n".$value['USERNAME'].','.$value['RCOUNT'].','.$value['MCOUNT'].','.$value['ACOUNT'].','.$value['TCOUNT'].','.$value['ABUSE_COUNT'].','.$value['INVALID_COUNT'];
                }
                SendMail::send_email('anant.gupta@naukri.com,mithun.s@jeevansathi.com',"Please find the attached CSV file.","Inappropriate Users Summary for $todayDate","noreply@jeevansathi.com",'','',$data,'','inappropriateUsers_'.$todayDate.".csv");

  
  }


  public function isLast7Max($value,$reportObj,$uname)
    { 
        
        $dateStart = date('Y-m-d',strtotime("-7 day"));
        $dateEnd = date('Y-m-d',strtotime("-1 day"));
        $MAX=$reportObj->getMaxForUser($value['PROFILEID'],$dateStart,$dateEnd);
      for($i=1;$i<8;$i++)
      {
      
      $date1 = date('Y-m-d',strtotime("-".($i)." day"));
      $return['RCOUNT'] += $value[$date1]['RCOUNT'];
      $return['TCOUNT'] += $value[$date1]['TCOUNT'];
      $return['ACOUNT'] += $value[$date1]['ACOUNT'];
      $return['MCOUNT'] += $value[$date1]['MCOUNT'];

      }  

      if(!$MAX['MAX'] || $return['TCOUNT']>$MAX['MAX']){
          $return['USERNAME']=$uname;
          return $return;
      } 
      return false;
    }

 
    public function getDataForToday(){ 
                    $reportObjSlave = new MIS_INAPPROPRIATE_USERS_REPORT('newjs_slave');
                    $reportObj = new MIS_INAPPROPRIATE_USERS_REPORT();
                    $yesterDate=date('Y-m-d',strtotime("-1 day"));
                    $startDate=date('Y-m-d',strtotime("-7 day"));
                    $chunks=10;
                    for($i=0;$i<$chunks;$i++)
                    {
                        empty($groupedByUsername);
                        if(date('Y-m-d')==self::$cronLIVEDate)
                            $resultArr=(new MIS_INAPPROPRIATE_USERS_LOG('newjs_slave'))->getDataForLast7Days($startDate,$yesterDate,$chunks,$i);
                        else
                            $resultArr=(new MIS_INAPPROPRIATE_USERS_LOG('newjs_slave'))->getDataForADate($startDate,$yesterDate,$chunks,$i);

                        foreach ($resultArr as $key => $value) 
                        {
                            $groupedByUsername[$value['USERNAME']][$value['DATE']] = $value;
                            $groupedByUsername[$value['USERNAME']]['PROFILEID'] = $value[PROFILEID];
                            unset($resultArr[$key]);
                        } 
                        empty($resultArr);

                        foreach ($groupedByUsername as $key => $value) 
                        {
                          
                                if($tempVal=$this->isLast7Max($value,$reportObjSlave,$key))
                                {


                                 $startingDate = date('Y-m-d H:i:s');
                                 $date = new DateTime($startingDate);
                                 $date->sub(new DateInterval('P30D')); //get the date which was 30 days ago
                                 $endDate = $date->format('Y-m-d H:i:s');

                              
                                $reportInvalidCount=(new JSADMIN_REPORT_INVALID_PHONE())->getReportInvalidCountMIS($value['PROFILEID'],$startingDate,$endDate);
                                $reportAbuseCount = (new REPORT_ABUSE_LOG())->getReportAbuseCountMIS($value['PROFILEID'],$startingDate,$endDate);

                                $tempVal['ABUSE_COUNT'] = $reportAbuseCount==NULL ? 0:$reportAbuseCount ;
                                $tempVal['INVALID_COUNT'] = $reportInvalidCount == NULL ? 0 : $reportInvalidCount;
                              
                              
                                $this->finalResultsArray[] = $tempVal;
                                $Tarray[]=$tempVal['TCOUNT'];
                                $reportObj->insert($value['PROFILEID'],$tempVal['TCOUNT'],$tempVal['RCOUNT'],$tempVal['ACOUNT'],$tempVal['MCOUNT'],$key,$tempVal['ABUSE_COUNT'],$tempVal['INVALID_COUNT']);
                                }
                                unset($groupedByUsername[$key]);
                        }
                    }
                  
                    array_multisort($Tarray, SORT_DESC, SORT_NUMERIC, $this->finalResultsArray);
                   
    }

  
    
}