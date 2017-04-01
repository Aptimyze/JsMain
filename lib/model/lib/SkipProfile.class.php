<?php
/**
 * Skip Profile Array class
 *
 */
class SkipProfile
{
    /**
     * Call this method to get singleton
     *
     * @return skipProfile
     */
     
    private $_profileid;
    
    private $_profileArray;
    
    private static $_instance;
    
    // This is to insure that page doesnot break for too many ignored profiles
    private static $MAX_IGNORE_LIMIT = 5000; 
    
    public static function getInstance($profileid)
    {
		if (self::$_instance[$profileid] === null) {
            self::$_instance[$profileid] = new skipProfile($profileid);
        }
        return self::$_instance[$profileid];
    }

    /**
     * Private construct so nobody else can instance it
     *
     */
    public function __construct($profile)
    {
	    $this->_profileid =$profile;
    }
    
    public function getContactedSkipProfile()
    {
		if(!is_array($this->_profileArray["CONTACTED_BY_ME"]) && !is_array($this->_profileArray["CONTACTED_ME"]))
		{
			$contactsObj = new ContactsRecords();
			$profileArray = $contactsObj->getSkipContactedProfile($this->_profileid,$skipContactType=array('A','D','C','I','E'));
			//print_r($profileArray); //die;
			$this->setContactedProfile($profileArray);
			
		}
		
	}
	
	
	public function getIgnoredSkipProfile()
	{
		if(!is_array($this->_profileArray["IGNORED"]))
		{
			$ignoreProfileObj = new IgnoredProfiles();
			$profileArray = $ignoreProfileObj->listIgnoredProfile($this->_profileid);
			/** 
			 * Condition added by Reshu to avoid ignored profiles more than specified limit which cause db issue
			 */
			if(count($profileArray) > self::$MAX_IGNORE_LIMIT)
			{
					$profileArray = array_slice($profileArray,0,self::$MAX_IGNORE_LIMIT);
			}
			
			self::setIgnoredProfile($profileArray);
		}
		return $this->_profileArray;
	}
	
	
	
	private function setIgnoredProfile($profileArray)
	{
		$this->_profileArray["IGNORED"]=$profileArray;
	}
	private function setContactedProfile($profileArray)
	{
		$this->_profileArray["CONTACTED_BY_ME"] = $profileArray["CONTACTED_BY_ME"];
		$this->_profileArray["CONTACTED_ME"] = $profileArray["CONTACTED_ME"];
	}
	
	public function getSkipProfiles($skipArray,$withoutMerge='')
	{
		
		$memcacheServiceObj = new ProfileMemcacheService($this->_profileid);
		
		if(is_array($skipArray))
		{
			foreach($skipArray as $key=>$value)
			{
				
				if($key === "CONTACT")
				{
				 if($memcacheServiceObj->memcache->getCONTACTED_BY_ME()!='' || $memcacheServiceObj->memcache->getCONTACTED_ME() !='')
					{
						$this->_profileArray["CONTACTED_BY_ME"] = unserialize($memcacheServiceObj->memcache->getCONTACTED_BY_ME());
						$this->_profileArray["CONTACTED_ME"] = unserialize($memcacheServiceObj->memcache->getCONTACTED_ME());
					}
					else
					{
							$this->getContactedSkipProfile();
					}
							if(is_array($value))
							foreach($value as $key1=>$value1)
							{
								if(isset($this->_profileArray["CONTACTED_BY_ME"][$value1]))
								{
									$profileid[] = $this->_profileArray["CONTACTED_BY_ME"][$value1];
									
								}
								if(isset($this->_profileArray["CONTACTED_ME"][$value1]))
								{
									$profileid[] = $this->_profileArray["CONTACTED_ME"][$value1];
									
								}
							}
					
					
				}
				if($value == "IGNORE")
				{
					
					
					if($memcacheServiceObj->memcache->getIGNORED()!='')
					{
						
						$this->_profileArray["IGNORED"]= unserialize($memcacheServiceObj->memcache->getIGNORED());
						
					}
					else
					{
						
						$this->getIgnoredSkipProfile();
						
					}
						$profileidIgnore = $this->_profileArray["IGNORED"];
					
						if(is_array($profileidIgnore))
						{
							$profileid[] = $profileidIgnore;
							
						}
					
					
				}
			}
			if(is_array($profileid))
				$profileid = call_user_func_array('array_merge', $profileid);
			if($withoutMerge=='1')
				return $this->_profileArray;
		}
		
		//print_r($profileid); die;
		if(count($profileid)<1)
			return null;
		return $profileid;
	}

	public static function unsetInstance($profileid)
	{
		unset(self::$_instance[$profileid]);
	}
}

