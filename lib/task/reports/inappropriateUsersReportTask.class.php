<?php

/**
 This task is used to generate the csv file for report abuse data of the previos date and email the same to the backend operators 
 *@author : Palash Chordia
 *created on : 13 July 2016 
 */
class inappropriateUsersReportTask extends sfBaseTask
{
  public static $cronLIVEDate='2016-11-17';
  public static $dayDiff;
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

                    $this->setData();
                    $data="Username,Outside Religion Contact,Outside Marital Status Contact,Outside Age Bracket Contact,Overall negative score\r\n";
                    foreach ($this->finalResultsArray as $key => $value) 
                      {
                      $data.="\r\n".$value['USERNAME'].','.$value['RCOUNT'].','.$value['MCOUNT'].','.$value['ACOUNT'].','.$value['TCOUNT'];
                      }
                      SendMail::send_email('anant.gupta@naukri.com,mithun.s@jeevansathi.com',"Please find the attached CSV file.","Inappropriate Users Summary for $todayDate","noreply@jeevansathi.com",'','',$data,'','inappropriateUsers_'.$todayDate.".csv");


  }

  
  public function getLastSevenDaysData($value,$uname)
    {
      for($i=1;$i<8;$i++) 
      {
      
      $date1 = date('Y-m-d',strtotime("-".($i)." day"));
      $return['RCOUNT'] += $value[$date1]['RCOUNT'];
      $return['TCOUNT'] += $value[$date1]['TCOUNT'];
      $return['ACOUNT'] += $value[$date1]['ACOUNT'];

      }  
      
      $return['USERNAME'] = $uname;
      return $return;
    }

    // this function calculates whether data of the last 7 days is max as compared to the data sent alreaddy in these last 7 days 
  public function isLast7Max($value)
    {
      $tempMax=0;
      if(self::$dayDiff > 6)
      {
          $i=6;
          $diffArray[7] = 0;

      }
      else 
        $i = self::$dayDiff;
      
      if($i==0)return true;
      
      for(;$i>=0;$i--)
      {
      $date1 = date('Y-m-d',strtotime("-".($i+1)." day"));
      $date2 = date('Y-m-d',strtotime("-".($i+8)." day"));
      $previous = $diffArray[$i+1] ?  $diffArray[$i+1] : 0 ;
      if($i==0)$tempMax=max($diffArray);
      $diffArray[$i] = $previous  + $value[$date1]['TCOUNT'] -  $value[$date2]['TCOUNT'];
      }
      
      
      if($tempMax < $diffArray[0])
          return true;
      return false;
    }
    
  
    public function setData(){
                    self::$dayDiff = floor((time()-strtotime(self::$cronLIVEDate))/(60 * 60 * 24));
                    $yesterDate=date('Y-m-d',strtotime("-1 day"));
                    $startDate=date('Y-m-d',strtotime("-14 day"));
                    $chunks=10;
                    for($i=0;$i<$chunks;$i++)
                    {
                    empty($groupedByUsername);
                    $resultArr=(new MIS_INAPPROPRIATE_USERS_LOG('newjs_slave'))->getDataForADate($startDate,$yesterDate,$chunks,$i);
                    foreach ($resultArr as $key => $value) 
                    {
                        $groupedByUsername[$value['USERNAME']][$value['DATE']] = $value;
                        unset($resultArr[$key]);
                    }
                    empty($resultArr);
                    foreach ($groupedByUsername as $key => $value) 
                    {
                            if($this->isLast7Max($value))
                            {
                            $tempVal = $this->getLastSevenDaysData($value,$key);
                            $this->finalResultsArray[] = $tempVal;
                            $Tarray[]=$tempVal['TCOUNT'];
                            }
                            unset($groupedByUsername[$key]);
                    }
                    }
                    
                    array_multisort($Tarray, SORT_DESC, SORT_NUMERIC, $this->finalResultsArray);
                    return $this->finalResultsArray;
    }
    
}