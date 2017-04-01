<?php
/**
 * Description of CalProfilesPoolStatusTask
 * 
 * This task will get the profileid which were requiest in NEWJS_JPROFILE::getArray(), increment count of available profileid in cache and total requested
 * To execute : $ symfony ProfileCache:CalProfileCachePoolStatus [--interval[="..."]] 
 * </code>
 * @author Kunal Verma
 * @created 25th August 2016
 */
ini_set('memory_limit','128M');
class CalProfilesPoolStatusTask extends sfBaseTask
{
    /**
     * 
     */
    const GET_INTERVAL = 60; 
    
    const OPT_INTERVAL = 'interval';
    
    /**
     *
     * @var type 
     */
    private $timeInterval = null;
    
    /*
     * Configure function 
     */
    protected function configure()
    { 
      $this->addOptions(array(
		new sfCommandOption('application', null, sfCommandOption::PARAMETER_OPTIONAL, 'The application name', 'jeevansathi'),
	     ));
      
      // add your own options here
     $this->addOptions(array(
       new sfCommandOption(self::OPT_INTERVAL, null, sfCommandOption::PARAMETER_OPTIONAL, 'INTERVAL OF DATA TO GET FROM CACHE'),
     ));
      
      $this->namespace        = 'ProfileCache';
      $this->name             = 'CalProfileCachePoolStatus';
      $this->briefDescription = 'Task will get the profileids which were requiest in NEWJS_JPROFILE::getArray(), increment count of available profileid in cache and total requested profileid count also';
      $this->detailedDescription = <<<EOF
      The [CalProfileCachePoolStatus|INFO] task does things.
        Task will get the profileids which were requiest in NEWJS_JPROFILE::getArray(), increment count of available profileid in cache and total requested profileid count also
      Call it with:
        
      [php symfony ProfileCache:CalProfileCachePoolStatus [--interval[="..."]]]
        
EOF;
    }
   
    /*
     * Main execute function of task
     */
    protected function execute($arguments = array(), $options = array())
    { 
      if(!sfContext::hasInstance())
	      sfContext::createInstance($this->configuration);
      
      $this->timeInterval = self::GET_INTERVAL;
      
      if(isset($options[self::OPT_INTERVAL])) {
        $this->timeInterval = intval($options[self::OPT_INTERVAL]);
      }
      
      //Start Time
      $st_Time = microtime(TRUE);
     
      $now =time();  
      $arrProfiles = $this->getProfiles($now);
      
      //Calculate Pool Size
      $this->calculateProfilePool($arrProfiles);
      //Remove from cache also
      $this->removeProfiles($now);
      //Get Script Statistics
      $this->endScript($st_Time);
    }
    
    /**
     * 
     * @param type $now
     * @return type
     */
    private function getProfiles($now)
    {
      $arrProfiles = JsMemcache::getInstance()->zRangeByScore('JPROFILE_GET_ARRAY',$now-$this->timeInterval,$now);
      return $arrProfiles;
    }
    
    /**
     * 
     * @param type $now
     */
    private function removeProfiles($now)
    {
      $arrProfiles = JsMemcache::getInstance()->zRemRangeByScore('JPROFILE_GET_ARRAY',$now-$this->timeInterval,$now);
    }
    
    /**
     * 
     * @param type $arrProfiles
     */
    private function calculateProfilePool($arrProfiles)
    {
      foreach ($arrProfiles as $key=>$value) {
        $arrValue = explode(',', $value);
        array_walk($arrValue, array("CalProfilesPoolStatusTask", "profileCacheKey"));
        $arrResponse = JsMemcache::getInstance()->getMultiHashByPipleline($arrValue);
        $this->processCacheResponse($arrValue, $arrResponse);
      }
    }
    
    /**
     * 
     * @param string $item1
     * @param type $key
     */
    private function profileCacheKey(&$item1, $key)
    {
      $item1 = ProfileCacheConstants::PROFILE_CACHE_PREFIX.$item1;
    }
    
    /**
     * 
     * @param type $arrRequest
     * @param type $arrResponse
     */
    private function processCacheResponse($arrRequest, $arrResponse)
    {
      $countProfilesRequest = count($arrRequest);
      $countCacheExist =0;
      
      foreach($arrResponse as $key => $value) {
        if(is_array($value) && count($value)) {
          ++$countCacheExist;
        }
      }
      
      $this->logPoolCount('TOTAL_PROFILE', $countProfilesRequest);
      $this->logPoolCount('IN_CACHE', $countCacheExist);
    }
    
    private function logPoolCount($name, $count)
    {
      if(0 === $count) {
        return ;
      }
        
      $key = 'POOL_COUNT'.'_'.date('Y-m-d');
      JsMemcache::getInstance()->hIncrBy($key, $name, $count);
      
      JsMemcache::getInstance()->hIncrBy($key, $name.'::'.date('H'), $count);
    }
    
    /*
     * End script 
     * To note statistic of memory and time usages
     * @param : $st_Time [Start Time]
     * @return void
     */
    private function endScript($st_Time='')
    {
        $end_time = microtime(TRUE);
        $var = memory_get_usage(true);

        if ($var < 1024)
            $mem =  $var." bytes";
        elseif ($var < 1048576)
            $mem =  round($var/1024,2)." kilobytes";
        else
            $mem = round($var/1048576,2)." megabytes";
        
        if($this->m_bDebugInfo)
        {
            $this->logSection('Memory usages : ', $mem);
            $this->logSection('Time taken : ', $end_time - $st_Time);
        }
    }
}
?>