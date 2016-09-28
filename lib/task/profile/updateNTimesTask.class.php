<?php

/* this class creates a file with data in it to be displayed on the critical 
 * action layers by fetching it from the table
 */
ini_set('memory_limit','512M');
class updateNTimesTask extends sfBaseTask
{
  private $measurePerformance = false;
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
    $date = date("Y-m-d");
    if($this->measurePerformance)
        $file = fopen(sfConfig::get("sf_upload_dir")."/SearchLogs/nTimesView_".$date.".txt","a");
    $queueArr  = array(0=>'nTimesMaleQueue',1=>'nTimesFemaleQueue');
    foreach($queueArr as $key=>$val){
        
        $memcacheObj = JsMemcache::getInstance();
        $lengthOfQueue = $memcacheObj->getLengthOfQueue($val);
        
        //use pipeline  for multiple pops
        $pipeline = $memcacheObj->pipeline();
        echo $lengthOfQueue;
        for($i=0;$i<$lengthOfQueue;$i++)
            $pipeline->RPOP($val);
        //execute pipeline
        $usersList = $pipeline->execute();
        
        //get individual count for every profile
        $countArr = $this->countViewsForProfiles($usersList);
        $jpNtimesObj = new NEWJS_JP_NTIMES();
        $greaterThanOne = 0;
        $count = 0;
        if(sizeof($countArr)>0){
            //update table rows for every profile
            foreach($countArr as $key1=>$val1){
              if($this->measurePerformance){
                $stringToWrite = "$key1,$val1";  
                fwrite($file,$stringToWrite."\n");
              }
              $jpNtimesObj->updateProfileViews($key1,$val1);
            }
        }
        $prevQueries = $lengthOfQueue;
        $currQueries = sizeof($countArr);
        
        //log perfirmance in file
        if($this->measurePerformance){
            $stringToWrite = "$val -: \n previousQueries  $prevQueries \n currentQueries  $currQueries";  
            fwrite($file,$stringToWrite."\n");
        }
    }
    if($this->measurePerformance)
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
