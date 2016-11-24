<?php
class backendActionsLib
{
	private $pswrdsObj;
	private $connectObj;
	private $useCrmMemcache;
	private $memcacheObj;

	/**
	  * Executes Constructor
	  *
	  * @param $tableArr---table to DB mapping array,$useMemcache(true/false)
	**/
	public function __construct($tableArr="",$useMemcache=false)
	{
		if(is_array($tableArr) && count($tableArr)>0)
		{
			foreach ($tableArr as $key => $value) 
			{
				$index = strpos($key, '_');
				if($index != false)
					$objName = strtolower(substr($key,$index+1))."Obj";
				else
					$objName = strtolower($key)."Obj";
				if($key=="jsadmin_CONNECT" && $useMemcache==true)
					continue;
				else
					$this->$objName = new $key($value);
			}
		}
		$this->useCrmMemcache = $useMemcache;
		if($useMemcache==true)
		{
			$this->memcacheObj= JsMemcache::getInstance();
		}
	}

	/**
	  * returns logged in agent details after validating agent otherwise returns null
	  *
	  * @param $loginCredentials,$agentIP
	  * @return $loginData
	**/
	public function getLoginDetails($loginCredentials=array())
	{
		$username = $loginCredentials["USERNAME"];
		$pass = $loginCredentials["PASSWORD"];
		$pass = md5($pass);
		
		$loginData = $this->pswrdsObj->fetchLoggedInAgentDetails($username,$pass);
		return $loginData;
	}

	/**
	  * update details of agent in PSWRDS table
	  *
	  * @param $params,$recid
	  * @return none
	**/
	public function updateAgentDetailsInPSWRDS($params,$recid)
	{	
		if($this->useCrmMemcache==true)
		{
			$CRM_MEMCACHED_DATA = $this->getMemcachedCrmData();
			if($CRM_MEMCACHED_DATA==false)
			{
				$CRM_MEMCACHED_DATA = array();
			}
			foreach ($params as $key => $value) 
			{
				$CRM_MEMCACHED_DATA["AGENTS_PSWRDS"][$recid][$key] = $value;
			}
			$this->setCrmMemcacheData("CRM_MEMCACHED_DATA",$CRM_MEMCACHED_DATA);
		}
		else
			$this->pswrdsObj->edit($params,$recid);	
	}



	/**
	  * checks whether login session has expired or not
	  *
	  * @param $agentLastLoginDt
	  * @return true/false
	**/
	public function checkExpiredLoginForAgent($agentData,$lastLoggedInOffset)
	{
		$lastLoginDt = date('Y-m-d H:i:s',strtotime($lastLoggedInOffset));
		if($agentData['last_login_dt'] < $lastLoginDt || $agentData['active']!='Y')
			return true;
		else
			return false;
	}

	/**
	  * edit profile details in JPROFILE table
	  *
	  * @param $fieldArr,$value,$criteria
	  * @return none
	**/
	public function editProfileDetails($fieldArr,$value,$criteria)
	{
		if(is_array($fieldArr))
			$this->jprofileObj->edit($fieldArr,$value,$criteria);
	}

/**
	  * deletes expired backend login sessions from jsadmin.CONNECT table
	  *
	  * @param $expiryOffset
	  * @return none
	**/
	public function deleteExpiredLoginSession($expiryOffset)
	{
		/*if($this->useCrmMemcache==true)--confirm LATER
		{
			$diff = time()-$expiryOffset;
			$CRM_MEMCACHED_DATA = JsMemcache::getInstance()->get("CRM_MEMCACHED_DATA");
			foreach ($CRM_MEMCACHED_DATA["AGENTS_CONNECT"] as $agentid => $details) 
			{
				if($details["TIME"]<$diff)
					unset($CRM_MEMCACHED_DATA["AGENTS_CONNECT"][$agentid]);
			}
			JsMemcache::getInstance()->set("CRM_MEMCACHED_DATA",$CRM_MEMCACHED_DATA);
		}
		else*/if($this->useCrmMemcache!=true)
		{
			$this->connectObj->deleteExpiredLoginSession($expiryOffset);
		}
	}

	/**
	  * deletes expired backend login session for agent from table or in memcache
	  *
	  * @param $id
	  * @return none
	**/
	public function deleteAgentLoginSession($id)
	{
		if($this->useCrmMemcache==true)
		{
			$CRM_MEMCACHED_DATA = $this->getMemcachedCrmData();
			unset($CRM_MEMCACHED_DATA["AGENTS_CONNECT"][$id]);
			$this->setCrmMemcacheData("CRM_MEMCACHED_DATA",$CRM_MEMCACHED_DATA);
			$ret = true;
		}
		else
		{
			$ret = $this->connectObj->deleteRowWithId($id);
		}
		return $ret;
	}
/**
	  * update backend login session in jsadmin.CONNECT table
	  *
	  * @param $params
	  * @return $id(last_updated_id)
	**/
	public function updateLoginSessionForAgent($params)
	{
		$id = $this->connectObj->updateLoginSessionForAgent($params);
		return $id;
	}

	/**
	  * create new backend login session either in jsadmin.CONNECT table or memcache
	  *
	  * @param $params
	  * @return $id(last_inserted_id)
	**/
	public function createLoginSessionForAgent($params)
	{
		if($this->useCrmMemcache==true)
		{
			$CRM_MEMCACHED_DATA = $this->getMemcachedCrmData();
			if($CRM_MEMCACHED_DATA==false)
			{
				$CRM_MEMCACHED_DATA = array();
			}
			$id = $params['USER'];
			$params["TIME"] = time();
			$CRM_MEMCACHED_DATA["AGENTS_CONNECT"][$params['USER']] = $params;
			$this->setCrmMemcacheData("CRM_MEMCACHED_DATA",$CRM_MEMCACHED_DATA); 
		}
		else
		{
			$id = $this->connectObj->createLoginSessionForAgent($params);
		}
		return $id;
	}

	/**
	  * update session time in jsadmin.CONNECT table
	  *
	  * @param $id
	  * @return none
	**/
	public function updateAgentSessionTime($id)
	{
		if($this->useCrmMemcache==true)
		{
			$CRM_MEMCACHED_DATA = $this->getMemcachedCrmData();
			$CRM_MEMCACHED_DATA["AGENTS_CONNECT"][$id]["TIME"] = time();
			$this->setCrmMemcacheData("CRM_MEMCACHED_DATA",$CRM_MEMCACHED_DATA); 
		}
		else
		{
			$this->connectObj->updateUserTime($id);	
		}
	}

	/**
	  * validate username of profile
	  *
	  * @param $username
	  * @return null/profile obj
	**/
	public function validateProfileUsername($username,$detailsRequired="PROFILEID")
	{
		$profile = Operator::getInstance();
        $profile->getDetail($username,'USERNAME',$detailsRequired);
        $profileid = $profile->getPROFILEID();
        if($profileid == NULL || $profileid == '') //if invalid username
      		return null;
      	else
      		return $profile;
	}

	/**
	  * fetch id of agent from session id from jsadmin.CONNECT table or memcache
	  *
	  * @param $params
	  * @return ID
	**/
	public function fetchAgentIDBySessionID($sessionID)
	{
		if($this->useCrmMemcache==true)
		{
			$CRM_MEMCACHED_DATA = $this->getMemcachedCrmData();
			if($CRM_MEMCACHED_DATA==false || isset($CRM_MEMCACHED_DATA['AGENTS_CONNECT'][$sessionID])==false)
			{
				return null;
			}
			else
			{
				$id = $sessionID;
			}
		}
		else
		{
			$id = $this->connectObj->fetchUser($sessionID);
		}
		return $id;
	}

	/**
	  * fetch details of agent session from jsadmin.CONNECT table or memcache
	  *
	  * @param $id
	  * @return $details
	**/
	public function fetchSessionDetailsBySessionID($id)
	{
		if($this->useCrmMemcache==true)
		{
			$CRM_MEMCACHED_DATA = $this->getMemcachedCrmData();
			if($CRM_MEMCACHED_DATA==false || isset($CRM_MEMCACHED_DATA['AGENTS_CONNECT'][$id])==false)
			{
				return null;
			}
			else
			{
				$details = $CRM_MEMCACHED_DATA['AGENTS_CONNECT'][$id];
			}
		}
		else
		{
			$details = $this->connectObj->findUser($id);
		}
		return $details;
	}

	/**
	  * fetch details of agent from jsadmin.PSWRDS table after authentication
	  *
	  * @param $id,$ip="",$detailsReq="USERNAME"
	  * @return $details
	**/
	public function fetchPSWRDSDetailsBySessionID($id,$ip="",$detailsReq="USERNAME")
	{
		if($this->useCrmMemcache==true)
		{
			$CRM_MEMCACHED_DATA = $this->getMemcachedCrmData();
			if($CRM_MEMCACHED_DATA==false || isset($CRM_MEMCACHED_DATA['AGENTS_CONNECT'][$id])==false)
			{
				return null;
			}
			else
			{
				if($ip && $CRM_MEMCACHED_DATA["AGENTS_CONNECT"][$id]['IPADDR']!=$ip)
					return null;
				else
				{
					$details = $this->pswrdsObj->getArray($id,"RESID",$detailsReq);
					return $details;
				}
			}
		}
		else
		{
			$agentId = $this->connectObj->fetchUser($id);
			if($agentId)
			{
				$details = $this->pswrdsObj->getArray($agentId,"RESID",$detailsReq);
				return $details;
			}
			else
			{
				return null;
			}	
		}
	}

	/**
	  * get crm memcached data
	  *
	  * @param $key
	  * @return $output
	**/
	public function getMemcachedCrmData($key="")
	{
		$data = $this->memcacheObj->get("CRM_MEMCACHED_DATA");
		if($key)
			$output = $data[$key];
		else
			$output = $data;
		unset($data);
		return $output;
	}

	/**
	  * set/update crm memcached data
	  *
	  * @param $key,$value
	  * @return none
	**/
	public function setCrmMemcacheData($key="CRM_MEMCACHED_DATA",$value)
	{
		$this->memcacheObj->set($key,$value,86400); //confirm array or serialized array--------------
	}

	/**
	  * remove crm memcached data
	  *
	  * @param $key
	  * @return none
	**/
	public function removeCrmMemcachedData($key="CRM_MEMCACHED_DATA")
	{
		$this->memcacheObj->remove($key);
	}
}
?>
