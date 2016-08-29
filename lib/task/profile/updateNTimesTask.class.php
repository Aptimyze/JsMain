<?php

/* this class creates a file with data in it to be displayed on the critical 
 * action layers by fetching it from the table
 */
ini_set('memory_limit','256M');
class updateNTimesTask extends sfBaseTask
{
  protected function configure() {
    $this->namespace = 'profile';
    $this->name = 'updateNTimes';
    $this->briefDescription = 'pops profileids from redis list and counts them and then updates the table ntimes';
    $this->detailedDescription = <<<EOF
Call it with:
[php symfony profile:updateNTimes]
EOF;
    $this->addOptions(array(
        new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
    ));       
  }
  protected function execute($arguments = array(), $options = array()) {
    if(!sfContext::hasInstance()) {
      sfContext::createInstance($this->configuration);
    }
    $date = date("Y-m-d h:i:s");
    $file = fopen(sfConfig::get("sf_upload_dir")."/SearchLogs/nTimesView_".$date.".txt","a");
    $queueArr  = array(0=>'nTimesMaleQueue',1=>'nTimesFemaleQueue');
    foreach($queueArr as $key=>$val){
        
        $memcacheObj = JsMemcache::getInstance();
        $lengthOfQueue = $memcacheObj->getLengthOfQueue($val);
        $pipeline = $memcacheObj->pipeline();
        /*for($i=0;$i<100000;$i++)
            $pipeline->LPUSH($val,rand(10000,30000));
        $usersList = $pipeline->execute();
        die;*/
        for($i=0;$i<$lengthOfQueue;$i++)
            $pipeline->RPOP($val);
        $usersList = $pipeline->execute();
        $countArr = $this->countViewsForProfiles($usersList);
        $jpNtimesObj = new NEWJS_JP_NTIMES();
        $greaterThanOne = 0;
        $count = 0;
        foreach($countArr as $key1=>$val1){
          $stringToWrite = "$key1  $val1";  
          fwrite($file,$stringToWrite."\n");
          $jpNtimesObj->updateProfileViews($key1,$val1);
        }
        $prevQueries = $lengthOfQueue;
        $currQueries = sizeof($countArr);
        $stringToWrite = "$val -: \n previousQueries  $prevQueries \n currentQueries  $currQueries";  
        fwrite($file,$stringToWrite."\n");
    }
    fclose($file);
  }           
  
  protected function countViewsForProfiles($usersList){
      foreach($usersList as $key=>$val){
          if($val){
            if($countArr[$val] != null)
              $countArr[$val] = $countArr[$val]+1;
            else
              $countArr[$val] = 1;
          }
      }
      return $countArr;
  }
}
