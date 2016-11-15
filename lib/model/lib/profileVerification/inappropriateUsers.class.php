<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of inappropriateUsers
 *
 * @author palash
 */
class inappropriateUsers {
    //put your code here
  public static $cronLIVEDate='2016-11-17';
  public static $dayDiff;

  public function getLastSevenDaysData($value,$uname)
    {
      for($i=1;$i<8;$i++) 
      {
      
      $date1 = date('Y-m-d',strtotime("-".($i)." day"));
      $return['RCOUNT'] += $value[$date1]['RCOUNT'];
      $return['TCOUNT'] += $value[$date1]['TCOUNT'];
      $return['ACOUNT'] += $value[$date1]['ACOUNT'];
      $return['MCOUNT'] += $value[$date1]['MCOUNT'];

      }  
      
      $return['USERNAME'] = $uname;
      return $return;
    }

    // this function calculates whether data of the last 7 days is max as compared to the data sent alreaddy in these last 7 days 
  public function isLast7Max($value)
    {
        $tempMax=0;
        if((self::$dayDiff > 6) || !$this->fromCron)
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
      
      
      if($tempMax==NULL || $tempMax < $diffArray[0])
          return true;
      return false;
    }
 
    public function getDataForADate($endDate){
                    self::$dayDiff = floor((time()-strtotime(self::$cronLIVEDate))/(60 * 60 * 24));
                    $yesterDate=$endDate ? $endDate : date('Y-m-d',strtotime("-1 day"));
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
