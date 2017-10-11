<?php
/**
* @brief : This file will be handle match alerts search requirements.
* @author Lavesh Rawat
* @created 2014-09-01
*/
class MatchAlertsSearch extends SearchParamters
{
	CONST femaleProfile  = 'F';
	CONST maleProfile = 'M';
	CONST showAllResults = 'All';
	CONST lastSeenCacheTime = 7200;
  
  /**
   * This Variable will hold last visted time of match alert listing
   * @access Private
   * @var String
   */
  private $lastVistedTime = null;
  
  /**
   * This Variable will hold last call for storing last vist datetime
   * @access Private
   * @var boolean
   */
  private $bStoreLastVistedDate =false;
  
	/**
	* Constructor.
	*/
        public function __construct($loggedInProfileObj)
        {
		parent::__construct();
		$this->loggedInProfileObj = $loggedInProfileObj;
               	$this->pid =  $this->loggedInProfileObj->getPROFILEID();
		if(!$this->pid)
		{
			$context = sfContext::getInstance();
			$context->getController()->forward("static", "logoutPage"); //Logout page
			throw new sfStopException();
		}
		if($this->loggedInProfileObj->getGENDER()== self::femaleProfile)
			$this->setGENDER(self::maleProfile);
		else
			$this->setGENDER(self::femaleProfile);
		$channel =  SearchChannelFactory::getChannel();
		$this->stype =  $channel::getSearchTypeMatchalerts();    
	}

	/*
	* This function will set the criteria for search.
	*/
	public function getSearchCriteria()
	{
		$this->setSEARCH_TYPE($this->stype);
		$MatchAlerts = new MatchAlerts();
		$arr = $MatchAlerts->getProfilesWithOutSorting($this->pid);
		$this->alertsDateConditionArr = $arr;
		if($arr)
			$str = implode(" ",array_keys($arr));
		$this->setProfilesToShow($str);
		$this->setSORT_LOGIC(SearchSortTypesEnums::matchAlertFlag);
		if(!$this->getMATCHALERTS_DATE_CLUSTER())
			$this->setMATCHALERTS_DATE_CLUSTER(self::showAllResults);
    
    if($this->bStoreLastVistedDate) {
      $this->storeLastVistedDate();
      $this->bStoreLastVistedDate = false;
    }
	}
  
  /*
   * Function to Store Last Visted Date
   * @access private  
   * @param  :void
   * @return :void
   */
  private function storeLastVistedDate() 
  {
    if (null !== $this->lastVistedTime) {
      return ;
    }
    $lastSeen = date("Y-m-d h:i:s");
        $producerObj = new Producer();
        if($producerObj->getRabbitMQServerConnected())
        {
                $updateSeenProfileData = array("process"=>"MATCHALERTS_LAST_SEEN",'data'=>array('body'=>array('seen_date'=>$lastSeen,'profileid'=>$this->pid)));
                $producerObj->sendMessage($updateSeenProfileData);
        }else{
                $obj = new seach_MATCH_ALERT_LAST_VISIT(SearchConfig::getSearchDb());
                $obj->ins($this->pid);
        }
        $this->lastVistedTime = $lastSeen;
    JsMemcache::getInstance()->set($this->pid."_MATCHALERTS_LAST_VISITED",$this->lastVistedTime);
    JsMemcache::getInstance()->incrCount("INSERT_COUNTER_MATCHALERTS_LAST_VISITED");
    unset($obj);
  }
  
   /*
   * Function to Get Last Visted Date
    * @access private  
   * @param  :void
   * @return :String (Date Time)
   */
  private function getLastVistedTime()
  {
    if (null !== $this->lastVistedTime) {
      return $this->lastVistedTime;
    }
    
    $lastVisited = JsMemcache::getInstance()->get($this->pid."_MATCHALERTS_LAST_VISITED");
    if(!$lastVisited){
        JsMemcache::getInstance()->incrCount("SELECT_COUNTER_MATCHALERTS_LAST_VISITED_BYDB");
        $obj = new seach_MATCH_ALERT_LAST_VISIT;
        $this->lastVistedTime = $obj->getDt($this->pid);
    }else{
        JsMemcache::getInstance()->incrCount("SELECT_COUNTER_MATCHALERTS_LAST_VISITED_BYCACHE");
        $this->lastVistedTime = $lastVisited;   
    }
    unset($obj);
    
    //by default return current date
    if( null === $this->lastVistedTime) {
      $this->lastVistedTime = "2015-03-07 00:00:01";
    }
    
    return $this->lastVistedTime;
  }
  
  /**
   * storeLastVistTime, function to store last vist time
   * 
   */
  public function storeLastVistTime()
  {
    $this->bStoreLastVistedDate = true;
  }
  
  /*
   * getLastestProfiles
   * As per date diff between max Date, and stored last visited date, return all profiles between those two dates
   * @param $arrProfile : Array of profiles
   * @access public 
   * @return Array of profiles
   */    
  public function getLastestProfiles($arrProfile)
  {
    if (!is_array($arrProfile)) {
      return ;
    }
    
    $lastVistedTime = $this->getLastVistedTime();

    if(null === $lastVistedTime) {
     $dateDiff = 0;
    } else {
     $lastDate = new DateTime($lastVistedTime);
     $currDate = new DateTime(date("Y-m-d h:i:s"));

     $dateDiff = $lastDate->diff($currDate)->days;
    }
    $dateArr = $this->getAlertsDateConditionArr();
    $lastVistedDate = $dateArr[$arrProfile[0]] - $dateDiff;
    
    $arrOut =array();
    foreach($arrProfile as $key=>$value)
		{
			if( $dateArr[$value] > $lastVistedDate )
			{
				$arrOut[] = $value;
			}
		}
    
    return $arrOut;
  }
  
  /*
   * getLastSentProfiles
   * As per max Date from dateArr, return all profiles between those two dates
   * @param $arrProfile : Array of profiles
   * @access public 
   * @return Array of profiles
   */  
  public function getLastSentProfiles($arrProfile)
  {
    if (!is_array($arrProfile)) {
      return ;
    }
    
    $dateArr = $this->getAlertsDateConditionArr();
    $lastSentDate = $dateArr[$arrProfile[0]];
    
    $arrOut =array();
    foreach($arrProfile as $key=>$value)
		{
			if( $dateArr[$value] == $lastSentDate )
			{
				$arrOut[] = $value;
			}
		}
    
    return $arrOut;
  }
  
}
?>
